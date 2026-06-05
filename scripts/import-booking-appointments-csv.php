<?php

use App\Models\BeautyStaff;
use App\Models\BookingAppointment;
use App\Models\BookingAppointmentSegment;
use App\Models\Branch;
use App\Models\Service;
use App\Models\ServiceCategory;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

require __DIR__ . '/../vendor/autoload.php';

$app = require __DIR__ . '/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$path = $argv[1] ?? null;
$branchId = (int) ($argv[2] ?? 0);

if (! $path || ! is_file($path)) {
    fwrite(STDERR, "CSV file not found.\n");
    exit(1);
}

$branch = Branch::query()->find($branchId);

if (! $branch) {
    fwrite(STDERR, "Branch not found.\n");
    exit(1);
}

$normalize = function (?string $value): string {
    $value = html_entity_decode((string) $value, ENT_QUOTES | ENT_HTML5, 'UTF-8');
    $value = strtr($value, [
        'á' => 'a', 'č' => 'c', 'ď' => 'd', 'é' => 'e', 'ě' => 'e', 'í' => 'i', 'ň' => 'n', 'ó' => 'o',
        'ř' => 'r', 'š' => 's', 'ť' => 't', 'ú' => 'u', 'ů' => 'u', 'ý' => 'y', 'ž' => 'z',
        'Á' => 'a', 'Č' => 'c', 'Ď' => 'd', 'É' => 'e', 'Ě' => 'e', 'Í' => 'i', 'Ň' => 'n', 'Ó' => 'o',
        'Ř' => 'r', 'Š' => 's', 'Ť' => 't', 'Ú' => 'u', 'Ů' => 'u', 'Ý' => 'y', 'Ž' => 'z',
    ]);
    $value = Str::lower(trim($value));
    $value = str_replace(['&', '+'], ' ', $value);
    $value = preg_replace('/\([^)]*\)/u', ' ', $value);
    $value = preg_replace('/\b\d+\s*min\b/u', ' ', $value);
    $value = iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $value) ?: $value;
    $value = preg_replace('/[^a-z0-9]+/', ' ', $value);

    return trim(preg_replace('/\s+/', ' ', $value));
};

$parseDate = function (?string $value): ?Carbon {
    $value = trim((string) $value);

    if ($value === '') {
        return null;
    }

    return Carbon::createFromFormat('d M Y H:i', $value);
};

$parsePrice = function (?string $value): int {
    return (int) preg_replace('/[^\d]/', '', (string) $value);
};

$parseDuration = function (string $serviceName, ?Service $service): int {
    if (preg_match('/(\d+)\s*min/i', $serviceName, $match)) {
        return max(1, (int) $match[1]);
    }

    return max(1, (int) ($service?->duration_minutes ?: 60));
};

$servicesByNormalizedName = fn () => Service::query()
    ->get()
    ->keyBy(fn (Service $service): string => $normalize($service->name));

$categories = ServiceCategory::query()
    ->get()
    ->keyBy(fn (ServiceCategory $category): string => $normalize($category->name));

$categoryForService = function (string $serviceName) use ($categories, $normalize): int {
    $name = $normalize($serviceName);

    $categoryKey = match (true) {
        str_contains($name, 'masaz') => 'masaze',
        str_contains($name, 'kosmet') => 'kosmetika',
        str_contains($name, 'depilace') => 'depilace',
        str_contains($name, 'pedikura') => 'pedikura',
        str_contains($name, 'rasa') || str_contains($name, 'oboci') || str_contains($name, 'lash') || str_contains($name, 'brow') => 'oboci rasy',
        str_contains($name, 'darkovy') || str_contains($name, 'poukaz') => 'poukazy',
        str_contains($name, 'akryl') || str_contains($name, 'gelove') || str_contains($name, 'nehtu') || str_contains($name, 'nehty') => 'modelaz nehtu',
        str_contains($name, 'manikura') => 'manikura',
        default => null,
    };

    if ($categoryKey && $categories->has($categoryKey)) {
        return $categories->get($categoryKey)->id;
    }

    return $categories->first()?->id ?? 1;
};

$services = $servicesByNormalizedName();
$created = 0;
$updated = 0;
$failed = 0;
$createdServices = 0;
$createdStaff = 0;
$lineNumber = 0;
$errors = [];

$file = new SplFileObject($path);
$file->setFlags(SplFileObject::READ_CSV | SplFileObject::SKIP_EMPTY);

DB::transaction(function () use (
    $file,
    $branch,
    $normalize,
    $parseDate,
    $parsePrice,
    $parseDuration,
    $categoryForService,
    $servicesByNormalizedName,
    &$services,
    &$created,
    &$updated,
    &$failed,
    &$createdServices,
    &$createdStaff,
    &$lineNumber,
    &$errors
) {
    $header = null;

    foreach ($file as $row) {
        $lineNumber++;

        if (! is_array($row) || count(array_filter($row, filled(...))) === 0) {
            continue;
        }

        if ($lineNumber <= 3) {
            continue;
        }

        if ($header === null) {
            $header = $row;
            continue;
        }

        $data = array_combine($header, array_pad($row, count($header), null));

        if (! $data) {
            $failed++;
            $errors[] = "Line {$lineNumber}: invalid CSV shape";
            continue;
        }

        try {
            $externalId = trim((string) ($data['ID'] ?? ''));
            $startsAt = $parseDate($data['DATE/TIME'] ?? null);

            if ($externalId === '' || ! $startsAt) {
                throw new RuntimeException('missing ID or DATE/TIME');
            }

            $serviceName = html_entity_decode(trim((string) ($data['SERVICES'] ?? '')), ENT_QUOTES | ENT_HTML5, 'UTF-8');
            $serviceKey = $normalize($serviceName);
            $service = $services->get($serviceKey);

            if (! $service && $serviceKey !== '') {
                $service = $services->first(function (Service $candidate, string $candidateKey) use ($serviceKey): bool {
                    return str_contains($candidateKey, $serviceKey) || str_contains($serviceKey, $candidateKey);
                });
            }

            $price = $parsePrice($data['TOTAL PRICE'] ?? null);
            $duration = $parseDuration($serviceName, $service);

            if (! $service) {
                $service = Service::query()->create([
                    'service_category_id' => $categoryForService($serviceName),
                    'name' => $serviceName ?: 'Imported service',
                    'price' => $price,
                    'duration_minutes' => $duration,
                    'sort_order' => 999,
                    'status' => true,
                ]);

                $services = $servicesByNormalizedName();
                $createdServices++;
            }

            $assistantName = trim((string) ($data['ASSISTANTS'] ?? ''));
            $staff = null;

            if ($assistantName !== '') {
                $staff = BeautyStaff::query()->firstOrCreate(
                    [
                        'branch_id' => $branch->id,
                        'name' => $assistantName,
                    ],
                    [
                        'slug' => Str::slug($assistantName) . '-' . $branch->id,
                        'status' => true,
                    ],
                );

                if ($staff->wasRecentlyCreated) {
                    $createdStaff++;
                }
            }

            $status = match (Str::lower(trim((string) ($data['STATUS'] ?? 'confirmed')))) {
                'canceled', 'cancelled' => 'cancelled',
                'pending' => 'pending',
                default => 'confirmed',
            };

            $customerName = trim(implode(' ', array_filter([
                trim((string) ($data['CUSTOMER FIRST NAME'] ?? '')),
                trim((string) ($data['CUSTOMER LAST NAME'] ?? '')),
            ])));

            $createdAt = $parseDate($data['CREATED'] ?? null);
            $endsAt = $startsAt->copy()->addMinutes($duration);
            $code = 'RES-' . $externalId;

            $appointment = BookingAppointment::query()->firstOrNew(['code' => $code]);
            $wasExisting = $appointment->exists;

            $appointment->fill([
                'branch_id' => $branch->id,
                'beauty_staff_id' => $staff?->id,
                'service_ids' => [$service->id],
                'service_snapshot' => [[
                    'id' => $service->id,
                    'name' => $serviceName ?: $service->name,
                    'duration_minutes' => $duration,
                    'price' => $price,
                ]],
                'starts_at' => $startsAt,
                'ends_at' => $endsAt,
                'total_duration_minutes' => $duration,
                'total_price' => $price,
                'customer_name' => $customerName ?: 'Khách #' . $externalId,
                'customer_phone' => trim((string) ($data['CUSTOMER PHONE'] ?? '')),
                'customer_email' => trim((string) ($data['CUSTOMER EMAIL'] ?? '')) ?: null,
                'customer_note' => trim((string) ($data['CUSTOMER ADDRESS'] ?? '')) ?: null,
                'status' => $status,
            ]);

            if ($createdAt) {
                $appointment->created_at = $createdAt;
            }

            $appointment->save();

            BookingAppointmentSegment::query()->updateOrCreate(
                [
                    'appointment_id' => $appointment->id,
                    'position' => 1,
                ],
                [
                    'service_id' => $service->id,
                    'beauty_staff_id' => $staff?->id,
                    'service_name' => $serviceName ?: $service->name,
                    'duration_minutes' => $duration,
                    'price' => $price,
                    'price_text' => trim((string) ($data['TOTAL PRICE'] ?? '')),
                    'starts_at' => $startsAt,
                    'ends_at' => $endsAt,
                ],
            );

            $wasExisting ? $updated++ : $created++;
        } catch (Throwable $exception) {
            $failed++;
            $errors[] = "Line {$lineNumber}: {$exception->getMessage()}";
        }
    }
});

echo "branch={$branch->name}\n";
echo "created={$created}\n";
echo "updated={$updated}\n";
echo "failed={$failed}\n";
echo "created_services={$createdServices}\n";
echo "created_staff={$createdStaff}\n";

foreach (array_slice($errors, 0, 20) as $error) {
    echo "error={$error}\n";
}

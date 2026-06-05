<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Mail\BookingCustomerConfirmation;
use App\Models\BookingAppointment;
use App\Models\BookingAppointmentSegment;
use App\Models\Branch;
use App\Models\BeautyStaff;
use App\Models\Service;
use App\Models\ServiceCategory;
use App\Settings\GeneralSettings;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class MelinailsController extends Controller
{
    public function home()
    {
        return view('frontend.melinails.home', $this->sharedData() + [
            'heroSlides' => $this->heroSlides(),
            'featuredCategories' => $this->categories()->take(6),
            'featuredServices' => $this->serviceCollection()->take(8),
        ]);
    }

    public function about()
    {
        return view('frontend.melinails.about', $this->sharedData());
    }

    public function services()
    {
        return view('frontend.melinails.services.index', $this->sharedData() + [
            'categories' => $this->categories(),
            'services' => $this->serviceCollection(),
        ]);
    }

    public function serviceCategory(string $slug)
    {
        $category = ServiceCategory::query()
            ->whereHas('slugData', fn ($query) => $query->where('slug', $slug))
            ->where('status', true)
            ->with(['services' => fn ($query) => $query->where('status', true)->orderBy('sort_order')->with('branches')])
            ->firstOrFail();

        return view('frontend.melinails.services.category', $this->sharedData() + [
            'category' => $category,
            'services' => $category->services,
            'relatedCategories' => $this->categories()->where('id', '!=', $category->id)->take(4),
        ]);
    }

    public function serviceDetail(string $slug)
    {
        $service = Service::query()
            ->whereHas('slugData', fn ($query) => $query->where('slug', $slug))
            ->where('status', true)
            ->with(['category.slugData', 'branches', 'staff.branch'])
            ->firstOrFail();

        return view('frontend.melinails.services.detail', $this->sharedData() + [
            'service' => $service,
            'relatedServices' => Service::query()
                ->where('status', true)
                ->where('service_category_id', $service->service_category_id)
                ->where('id', '!=', $service->id)
                ->with(['slugData', 'branches'])
                ->orderBy('sort_order')
                ->take(4)
                ->get(),
        ]);
    }

    public function prices()
    {
        return view('frontend.melinails.prices', $this->sharedData() + [
            'categories' => $this->categories()->load(['services' => fn ($query) => $query->where('status', true)->orderBy('sort_order')->with('branches')]),
        ]);
    }

    public function gallery()
    {
        return view('frontend.melinails.gallery', $this->sharedData() + [
            'galleryItems' => $this->galleryItems(),
        ]);
    }

    public function branches()
    {
        return view('frontend.melinails.branches', $this->sharedData());
    }

    public function booking()
    {
        $settings = app(GeneralSettings::class);

        return view('frontend.melinails.booking', $this->sharedData() + [
            'categories' => $this->categories(),
            'services' => $this->serviceCollection(),
            'staff' => BeautyStaff::query()->where('status', true)->with(['branch', 'services'])->get(),
            'bookingOnlineEnabled' => $settings->booking_online_enabled ?? true,
            'allowStaffSelection' => $settings->booking_customer_staff_selection_enabled ?? true,
            'autoAssignStaff' => $settings->booking_staff_auto_assign_enabled ?? true,
            'appointments' => BookingAppointment::query()
                ->with('segments')
                ->active()
                ->where('starts_at', '>=', now()->subDay())
                ->where('starts_at', '<=', now()->addDays(60))
                ->get(['id', 'branch_id', 'beauty_staff_id', 'starts_at', 'ends_at', 'status']),
        ]);
    }

    public function bookingStore(Request $request)
    {
        $settings = app(GeneralSettings::class);
        $allowStaffSelection = $settings->booking_customer_staff_selection_enabled ?? true;
        $autoAssignStaff = $settings->booking_staff_auto_assign_enabled ?? true;

        if (! ($settings->booking_online_enabled ?? true)) {
            return response()->json(['message' => 'Online booking je momentálně vypnutý. Kontaktujte prosím salon telefonicky.'], 422);
        }

        $data = $request->validate([
            'branch_id' => ['required', 'integer', 'exists:branches,id'],
            'service_ids' => ['required', 'array', 'min:1'],
            'service_ids.*' => ['required', 'integer', 'distinct', 'exists:services,id'],
            'staff_id' => ['nullable', 'integer', 'exists:beauty_staff,id'],
            'date' => ['required', 'date_format:Y-m-d'],
            'time' => ['required', 'date_format:H:i'],
            'customer.name' => ['required', 'string', 'max:255'],
            'customer.phone' => ['required', 'string', 'max:40'],
            'customer.email' => ['nullable', 'email', 'max:255'],
            'customer.note' => ['nullable', 'string', 'max:2000'],
        ]);

        $branch = Branch::query()
            ->where('status', true)
            ->with([
                'serviceCategories' => fn ($query) => $query->wherePivot('is_available', true),
                'services' => fn ($query) => $query->wherePivot('is_available', true),
            ])
            ->findOrFail($data['branch_id']);

        if (! ($branch->online_booking_enabled ?? true)) {
            return response()->json(['message' => 'Online booking pro vybranou pobočku je vypnutý.'], 422);
        }

        $serviceIds = collect($data['service_ids'])->map(fn ($id) => (int) $id)->values();
        $selectedServices = $branch->services
            ->whereIn('id', $serviceIds)
            ->sortBy(fn (Service $service) => $serviceIds->search($service->id))
            ->values();

        if ($selectedServices->count() !== $serviceIds->count()) {
            return response()->json(['message' => 'Một số dịch vụ không có tại chi nhánh đã chọn.'], 422);
        }

        $allowedCategoryIds = $branch->serviceCategories->pluck('id');
        if ($selectedServices->contains(fn (Service $service) => ! $allowedCategoryIds->contains($service->service_category_id))) {
            return response()->json(['message' => 'Danh mục của một số dịch vụ không được bật tại chi nhánh đã chọn.'], 422);
        }

        $timezone = $branch->timezone ?: 'Europe/Prague';
        $branchNow = now($timezone);
        $bookingDate = Carbon::createFromFormat('Y-m-d', $data['date'], $timezone)->startOfDay();

        if ($bookingDate->lt($branchNow->copy()->startOfDay())) {
            return response()->json(['message' => 'Datum rezervace nemůže být v minulosti.'], 422);
        }

        $totalDuration = (int) $selectedServices->sum(fn (Service $service) => $this->serviceDuration($service));
        $totalDuration = max(15, $totalDuration);
        $totalPrice = (int) $selectedServices->sum(fn (Service $service) => $this->servicePrice($service));

        $maxDaysAhead = max(1, (int) ($branch->booking_max_days_ahead ?? 60));
        if ($bookingDate->gt($branchNow->copy()->startOfDay()->addDays($maxDaysAhead))) {
            return response()->json(['message' => 'Termín je příliš daleko dopředu.'], 422);
        }

        $startsAt = Carbon::createFromFormat('Y-m-d H:i', $data['date'] . ' ' . $data['time'], $timezone);
        $endsAt = $startsAt->copy()->addMinutes($totalDuration);
        $opensAt = Carbon::createFromFormat('Y-m-d H:i', $data['date'] . ' ' . substr($branch->opening_time, 0, 5), $timezone);
        $closesAt = Carbon::createFromFormat('Y-m-d H:i', $data['date'] . ' ' . substr($branch->closing_time, 0, 5), $timezone);
        $minNoticeAt = $branchNow->copy()->addMinutes((int) ($branch->booking_min_notice_minutes ?? 0));

        if ($startsAt->lt($minNoticeAt)) {
            return response()->json(['message' => 'Termín je příliš brzy podle pravidla minimálního předstihu.'], 422);
        }

        if ($startsAt->lt($opensAt) || $endsAt->gt($closesAt)) {
            return response()->json(['message' => 'Čas je mimo otevírací dobu vybrané pobočky.'], 422);
        }

        $preferredStaffId = ($allowStaffSelection && $autoAssignStaff) ? (int) ($data['staff_id'] ?? 0) : 0;

        $appointment = DB::transaction(function () use ($allowStaffSelection, $autoAssignStaff, $branch, $data, $endsAt, $preferredStaffId, $selectedServices, $serviceIds, $settings, $startsAt, $totalDuration, $totalPrice) {
            $capacity = max(1, (int) ($branch->booking_capacity ?? 1));
            $currentBookings = BookingAppointment::query()
                ->active()
                ->where('branch_id', $branch->id)
                ->where('starts_at', '<', $endsAt)
                ->where('ends_at', '>', $startsAt)
                ->lockForUpdate()
                ->count();

            if ($currentBookings >= $capacity) {
                return ['error' => 'Kapacita pobočky pro vybraný čas je plná. Zkuste prosím jiný termín.'];
            }

            $segments = $this->buildSegments($selectedServices, $startsAt);
            $assignedSegments = [];

            foreach ($segments as $segment) {
                $assignedStaff = $autoAssignStaff
                    ? $this->assignStaffForSegment(
                        branch: $branch,
                        serviceId: $segment['service_id'],
                        startsAt: $segment['starts_at'],
                        endsAt: $segment['ends_at'],
                        preferredStaffId: $preferredStaffId,
                    )
                    : null;

                if (! $assignedStaff && $allowStaffSelection && $autoAssignStaff) {
                    return ['error' => 'Không có nhân viên đủ skill và còn trống cho một phần dịch vụ trong khung giờ này.'];
                }

                $assignedSegments[] = $segment + ['beauty_staff_id' => $assignedStaff?->id];
            }

            $segmentStaffIds = collect($assignedSegments)->pluck('beauty_staff_id')->filter()->unique()->values();
            $appointmentStaffId = $segmentStaffIds->count() === 1 ? $segmentStaffIds->first() : null;

            $appointment = BookingAppointment::query()->create([
                'code' => 'MELI-' . now()->format('ymdHis') . '-' . Str::upper(Str::random(4)),
                'branch_id' => $branch->id,
                'beauty_staff_id' => $appointmentStaffId,
                'service_ids' => $serviceIds->all(),
                'service_snapshot' => $selectedServices->map(fn (Service $service, int $index) => [
                    'id' => $service->id,
                    'name' => $service->name,
                    'price' => $this->servicePrice($service),
                    'price_text' => $this->servicePriceText($service),
                    'duration' => $this->serviceDuration($service),
                    'staff_id' => $assignedSegments[$index]['beauty_staff_id'] ?? null,
                    'starts_at' => isset($assignedSegments[$index]) ? $assignedSegments[$index]['starts_at']->format('Y-m-d H:i') : null,
                    'ends_at' => isset($assignedSegments[$index]) ? $assignedSegments[$index]['ends_at']->format('Y-m-d H:i') : null,
                ])->values()->all(),
                'starts_at' => $startsAt,
                'ends_at' => $endsAt,
                'total_duration_minutes' => $totalDuration,
                'total_price' => $totalPrice,
                'customer_name' => $data['customer']['name'],
                'customer_phone' => $data['customer']['phone'],
                'customer_email' => $data['customer']['email'] ?? null,
                'customer_note' => $data['customer']['note'] ?? null,
                'status' => ($settings->booking_auto_confirm_enabled ?? true) ? 'confirmed' : 'pending',
            ]);

            foreach ($assignedSegments as $segment) {
                $appointment->segments()->create($segment);
            }

            return ['appointment' => $appointment];
        });

        if (isset($appointment['error'])) {
            return response()->json(['message' => $appointment['error']], 422);
        }

        $appointment = $appointment['appointment'];
        $appointment->load(['branch', 'staff', 'segments.staff']);
        $this->sendBookingEmails($appointment, app(GeneralSettings::class));

        return response()->json([
            'message' => 'Rezervace byla vytvořena.',
            'appointment' => [
                'id' => $appointment->id,
                'code' => $appointment->code,
                'branch_id' => $appointment->branch_id,
                'staff_id' => $appointment->beauty_staff_id,
                'staff_name' => $appointment->staff?->name,
                'starts_at' => $appointment->starts_at->format('Y-m-d H:i'),
                'ends_at' => $appointment->ends_at->format('Y-m-d H:i'),
                'status' => $appointment->status,
                'thank_url' => route('meli.booking.thank-you', $appointment->code),
                'segments' => $appointment->segments->map(fn (BookingAppointmentSegment $segment) => [
                    'service_name' => $segment->service_name,
                    'staff_id' => $segment->beauty_staff_id,
                    'staff_name' => $segment->staff?->name,
                    'starts_at' => $segment->starts_at->format('Y-m-d H:i'),
                    'ends_at' => $segment->ends_at->format('Y-m-d H:i'),
                ])->values(),
            ],
        ]);
    }

    public function bookingThankYou(string $code)
    {
        $appointment = BookingAppointment::query()
            ->where('code', $code)
            ->with(['branch', 'staff', 'segments.staff'])
            ->firstOrFail();

        return view('frontend.melinails.booking-thank-you', $this->sharedData() + [
            'appointment' => $appointment,
        ]);
    }

    public function contact()
    {
        return view('frontend.melinails.contact', $this->sharedData());
    }

    protected function sharedData(): array
    {
        return [
            'meliBranches' => Branch::query()
                ->where('status', true)
                ->with([
                    'serviceCategories' => fn ($query) => $query->wherePivot('is_available', true),
                    'services' => fn ($query) => $query->wherePivot('is_available', true)->with('category')->orderBy('sort_order'),
                    'staff.services',
                ])
                ->orderBy('id')
                ->get(),
            'primaryPhone' => '+420 777 768 681',
            'primaryEmail' => 'info@melinails.cz',
        ];
    }

    protected function sendBookingEmails(BookingAppointment $appointment, GeneralSettings $settings): void
    {
        try {
            if (($settings->booking_customer_mail_enabled ?? true) && filled($appointment->customer_email)) {
                Mail::to($appointment->customer_email)->send(new BookingCustomerConfirmation($appointment, $settings));
            }

            if (($settings->booking_admin_mail_enabled ?? false) && filled($settings->booking_admin_mail_to)) {
                Mail::to($settings->booking_admin_mail_to)->send(new BookingCustomerConfirmation($appointment, $settings, true));
            }
        } catch (\Throwable) {
            // Booking is already confirmed; mail issues should not cancel the appointment.
        }
    }

    protected function buildSegments($selectedServices, Carbon $startsAt): array
    {
        $cursor = $startsAt->copy();

        return $selectedServices
            ->values()
            ->map(function (Service $service, int $index) use (&$cursor) {
                $duration = $this->serviceDuration($service);
                $segmentStart = $cursor->copy();
                $segmentEnd = $cursor->copy()->addMinutes($duration);
                $cursor = $segmentEnd->copy();

                return [
                    'service_id' => $service->id,
                    'position' => $index + 1,
                    'service_name' => $service->name,
                    'duration_minutes' => $duration,
                    'price' => $this->servicePrice($service),
                    'price_text' => $this->servicePriceText($service),
                    'starts_at' => $segmentStart,
                    'ends_at' => $segmentEnd,
                ];
            })
            ->all();
    }

    protected function assignStaffForSegment(Branch $branch, int $serviceId, Carbon $startsAt, Carbon $endsAt, int $preferredStaffId = 0): ?BeautyStaff
    {
        $bufferMinutes = (int) ($branch->booking_buffer_minutes ?? 0);
        $conflictStart = $startsAt->copy()->subMinutes($bufferMinutes);
        $conflictEnd = $endsAt->copy()->addMinutes($bufferMinutes);

        return BeautyStaff::query()
            ->where('status', true)
            ->where('branch_id', $branch->id)
            ->whereHas('services', fn ($query) => $query->whereKey($serviceId))
            ->with('services')
            ->orderBy('id')
            ->get()
            ->sortBy(fn (BeautyStaff $person) => $preferredStaffId > 0 && $person->id === $preferredStaffId ? 0 : 1)
            ->first(function (BeautyStaff $person) use ($branch, $conflictEnd, $conflictStart, $endsAt, $startsAt) {
                if (! $person->isWorkingDuring($startsAt, $endsAt, $branch)) {
                    return false;
                }

                if (BookingAppointmentSegment::query()
                    ->where('beauty_staff_id', $person->id)
                    ->whereHas('appointment', fn ($query) => $query->active())
                    ->where('starts_at', '<', $conflictEnd)
                    ->where('ends_at', '>', $conflictStart)
                    ->lockForUpdate()
                    ->exists()) {
                    return false;
                }

                return ! BookingAppointment::query()
                    ->active()
                    ->where('beauty_staff_id', $person->id)
                    ->whereDoesntHave('segments')
                    ->where('starts_at', '<', $conflictEnd)
                    ->where('ends_at', '>', $conflictStart)
                    ->lockForUpdate()
                    ->exists();
            });
    }

    protected function serviceDuration(Service $service): int
    {
        return max(1, (int) ($service->pivot->duration_minutes ?? $service->duration_minutes ?? 15));
    }

    protected function servicePrice(Service $service): int
    {
        return (int) ($service->pivot->price ?? $service->price ?? 0);
    }

    protected function servicePriceText(Service $service): ?string
    {
        return $service->pivot->price_text ?: (filled($this->servicePrice($service)) ? number_format($this->servicePrice($service), 0, ',', ' ') . ' Kč' : null);
    }

    protected function categories()
    {
        return ServiceCategory::query()
            ->where('status', true)
            ->where('parent_id', 0)
            ->with('slugData')
            ->orderBy('position')
            ->get();
    }

    protected function serviceCollection()
    {
        return Service::query()
            ->where('status', true)
            ->with(['slugData', 'category.slugData', 'branches'])
            ->orderBy('sort_order')
            ->get();
    }

    protected function heroSlides(): array
    {
        return [
            [
                'eyebrow' => 'Meli Nails & Beauty',
                'title' => 'Místo, kde krása začíná klidem.',
                'text' => 'Manikúra, pedikúra, kosmetika, řasy, masáže a jemná péče ve dvou salonech.',
                'image' => 'https://images.unsplash.com/photo-1521590832167-7bcbfaa6381f?auto=format&fit=crop&w=1800&q=82',
            ],
            [
                'eyebrow' => 'Rezervace online',
                'title' => 'Vyberte pobočku, služby a čas návštěvy.',
                'text' => 'Booking počítá délku procedur a umí pracovat s rozdílnou nabídkou jednotlivých salonů.',
                'image' => 'https://images.unsplash.com/photo-1604654894610-df63bc536371?auto=format&fit=crop&w=1800&q=82',
            ],
            [
                'eyebrow' => 'Beauty péče',
                'title' => 'Detail, elegance a chvíle jen pro vás.',
                'text' => 'Od decentního nude designu po relaxační masáž a kosmetické ošetření.',
                'image' => 'https://images.unsplash.com/photo-1515377905703-c4788e51af15?auto=format&fit=crop&w=1800&q=82',
            ],
        ];
    }

    protected function galleryItems(): array
    {
        return [
            ['title' => 'Nude nails', 'category' => 'Nails', 'image' => 'https://images.unsplash.com/photo-1604654894610-df63bc536371?auto=format&fit=crop&w=900&q=82'],
            ['title' => 'Lash detail', 'category' => 'Řasy', 'image' => 'https://images.unsplash.com/photo-1512496015851-a90fb38ba796?auto=format&fit=crop&w=900&q=82'],
            ['title' => 'Skin care', 'category' => 'Kosmetika', 'image' => 'https://images.unsplash.com/photo-1583001809873-a128495da465?auto=format&fit=crop&w=900&q=82'],
            ['title' => 'Relax masáž', 'category' => 'Masáže', 'image' => 'https://images.unsplash.com/photo-1544161515-4ab6ce6db874?auto=format&fit=crop&w=900&q=82'],
            ['title' => 'French nails', 'category' => 'Nails', 'image' => 'https://images.unsplash.com/photo-1519014816548-bf5fe059798b?auto=format&fit=crop&w=900&q=82'],
            ['title' => 'Salon mood', 'category' => 'Salon', 'image' => 'https://images.unsplash.com/photo-1556228720-195a672e8a03?auto=format&fit=crop&w=900&q=82'],
        ];
    }
}

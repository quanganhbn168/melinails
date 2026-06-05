<?php

namespace App\Filament\Widgets;

use App\Filament\Resources\BookingAppointments\BookingAppointmentResource;
use App\Models\BeautyStaff;
use App\Models\BookingAppointment;
use App\Models\Branch;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Guava\Calendar\Enums\CalendarViewType;
use Guava\Calendar\Filament\CalendarWidget;
use Guava\Calendar\ValueObjects\CalendarEvent;
use Guava\Calendar\ValueObjects\CalendarResource;
use Guava\Calendar\ValueObjects\DateClickInfo;
use Guava\Calendar\ValueObjects\FetchInfo;
use Illuminate\Support\HtmlString;
use Illuminate\Support\Collection;

class BookingCalendarWidget extends CalendarWidget
{
    public ?int $selectedBranchId = null;

    protected array $staffResourceCache = [];

    protected static ?int $sort = 1;

    protected CalendarViewType $calendarView = CalendarViewType::ResourceTimeGridDay;

    protected array $options = [
        'headerToolbar' => [
            'start' => 'title',
            'center' => 'resourceTimeGridDay,timeGridWeek,dayGridMonth,listWeek',
            'end' => 'today prev,next',
        ],
        'buttonText' => [
            'today' => 'Hôm nay',
            'dayGridMonth' => 'Tháng',
            'timeGridWeek' => 'Tuần',
            'resourceTimeGridDay' => 'Ngày',
            'listWeek' => 'Danh sách',
        ],
        'slotMinTime' => '08:00:00',
        'slotMaxTime' => '21:00:00',
        'dayMaxEvents' => true,
        'nowIndicator' => true,
    ];

    protected array $branchColors = [
        '#d97706',
        '#2563eb',
        '#16a34a',
        '#9333ea',
        '#dc2626',
        '#0891b2',
        '#be123c',
        '#4f46e5',
    ];

    protected bool $dateClickEnabled = true;

    protected bool $eventClickEnabled = false;

    protected string | HtmlString | bool | null $heading = 'Lịch booking';

    protected int | string | array $columnSpan = 'full';

    public function getHeaderActions(): array
    {
        $actions = [
            Action::make('branch_all')
                ->label('Tất cả shop')
                ->color(fn (): string => $this->selectedBranchId === null ? 'primary' : 'gray')
                ->outlined(fn (): bool => $this->selectedBranchId !== null)
                ->action(function (): void {
                    $this->selectedBranchId = null;
                    $this->refreshRecords();
                    $this->refreshResources();
                }),
        ];

        Branch::query()
            ->where('status', true)
            ->orderBy('name')
            ->get(['id', 'name'])
            ->each(function (Branch $branch) use (&$actions): void {
                $actions[] = Action::make('branch_' . $branch->id)
                    ->label($branch->name)
                    ->color(fn (): string => $this->selectedBranchId === $branch->id ? 'primary' : 'gray')
                    ->outlined(fn (): bool => $this->selectedBranchId !== $branch->id)
                    ->action(function () use ($branch): void {
                        $this->selectedBranchId = $branch->id;
                        $this->refreshRecords();
                        $this->refreshResources();
                    });
            });

        return [
            ActionGroup::make($actions)
                ->label($this->selectedBranchId ? Branch::find($this->selectedBranchId)?->name ?? 'Chọn shop' : 'Tất cả shop')
                ->button(),
        ];
    }

    public function getOptions(): array
    {
        return $this->options;
    }

    protected function getEvents(FetchInfo $info): Collection | array
    {
        $branchId = $this->branchId();

        if (! $this->shouldShowDetailedEvents($info)) {
            return $this->getSummaryEvents($info, $branchId);
        }

        $appointments = BookingAppointment::query()
            ->with(['branch', 'staff', 'segments'])
            ->when($branchId, fn ($query) => $query->where('branch_id', $branchId))
            ->where('starts_at', '<=', $info->end)
            ->where('ends_at', '>=', $info->start)
            ->orderBy('starts_at')
            ->orderBy('ends_at')
            ->get();

        $resourceIds = $this->allocateStaffResources($appointments);

        return $appointments
            ->map(fn (BookingAppointment $appointment): CalendarEvent => CalendarEvent::make($appointment)
                ->title($this->eventTitle($appointment))
                ->start($appointment->starts_at)
                ->end($appointment->ends_at)
                ->backgroundColor($this->eventColor($appointment))
                ->textColor('#ffffff')
                ->resourceId($resourceIds[$appointment->id] ?? $this->fallbackStaffResourceId($appointment->branch_id))
                ->url(BookingAppointmentResource::getUrl('edit', ['record' => $appointment]), '_self'));
    }

    protected function onDateClick(DateClickInfo $info): void
    {
        $this->setOption('date', $info->date->toDateString());
        $this->setOption('view', CalendarViewType::ResourceTimeGridDay->value);
        $this->refreshRecords();
        $this->refreshResources();
    }

    protected function getSummaryEvents(FetchInfo $info, ?int $branchId): Collection
    {
        return BookingAppointment::query()
            ->with('branch:id,name')
            ->when($branchId, fn ($query) => $query->where('branch_id', $branchId))
            ->where('starts_at', '<=', $info->end)
            ->where('ends_at', '>=', $info->start)
            ->get(['id', 'branch_id', 'starts_at', 'ends_at', 'status'])
            ->groupBy(fn (BookingAppointment $appointment): string => $appointment->starts_at->format('Y-m-d') . '|' . $appointment->branch_id)
            ->map(function (Collection $appointments): CalendarEvent {
                $first = $appointments->first();
                $date = $first->starts_at->copy()->startOfDay();
                $count = $appointments->count();
                $branchName = $first->branch?->name;
                $title = ($branchName ? $branchName . ': ' : '') . $count . ' lịch';

                return CalendarEvent::make()
                    ->title($title)
                    ->start($date)
                    ->end($date->copy()->addDay())
                    ->allDay()
                    ->backgroundColor($this->branchColor($first->branch_id))
                    ->textColor('#ffffff')
                    ->extendedProps([
                        'summary' => true,
                        'count' => $count,
                        'branch' => $branchName,
                    ]);
            })
            ->values();
    }

    protected function getResources(): Collection | array
    {
        $branches = Branch::query()
            ->where('status', true)
            ->when($this->branchId(), fn ($query) => $query->whereKey($this->branchId()))
            ->orderBy('name')
            ->with(['staff' => fn ($query) => $query->where('status', true)->orderBy('name')])
            ->get(['id', 'name', 'booking_capacity']);

        return $branches
            ->flatMap(function (Branch $branch): Collection {
                $staff = $branch->staff->values();
                $capacity = max($staff->count(), (int) ($branch->booking_capacity ?? 1), 1);

                return collect(range(1, $capacity))
                    ->map(function (int $slot) use ($branch, $staff): CalendarResource {
                        $staffMember = $staff->get($slot - 1);

                        if ($staffMember) {
                            return CalendarResource::make('staff-' . $staffMember->id)
                                ->title($staffMember->name . ' - ' . $branch->name)
                                ->eventBackgroundColor($this->branchColor($branch->id))
                                ->eventTextColor('#ffffff');
                        }

                        return CalendarResource::make('staff-slot-' . $branch->id . '-' . $slot)
                            ->title($branch->name . ' - NV ' . $slot)
                            ->eventBackgroundColor($this->branchColor($branch->id))
                            ->eventTextColor('#ffffff');
                    });
            })
            ->values();
    }

    protected function eventTitle(BookingAppointment $appointment): string
    {
        $services = $appointment->segments->pluck('service_name')->filter()->join(', ')
            ?: collect($appointment->service_snapshot ?? [])->pluck('name')->filter()->join(', ');
        $staff = $appointment->staff?->name ? ' - ' . $appointment->staff->name : '';
        $price = $appointment->total_price ? ' - ' . number_format((int) $appointment->total_price, 0, ',', ' ') . ' Kč' : '';

        return trim($appointment->customer_name . $staff . ($services ? ' | ' . $services : '') . $price);
    }

    protected function allocateStaffResources(Collection $appointments): array
    {
        $resourceIds = [];
        $slotsByBranch = [];

        foreach ($appointments as $appointment) {
            if ($appointment->beauty_staff_id) {
                $resourceIds[$appointment->id] = 'staff-' . $appointment->beauty_staff_id;

                continue;
            }

            $branchId = (int) $appointment->branch_id;
            $staffResourceIds = $this->staffResourceIdsForBranch($branchId);

            foreach ($staffResourceIds as $resourceId) {
                if (! isset($slotsByBranch[$branchId][$resourceId]) || $slotsByBranch[$branchId][$resourceId]->lte($appointment->starts_at)) {
                    $slotsByBranch[$branchId][$resourceId] = $appointment->ends_at;
                    $resourceIds[$appointment->id] = $resourceId;

                    continue 2;
                }
            }

            $resourceId = $staffResourceIds->last() ?? $this->fallbackStaffResourceId($branchId);
            $slotsByBranch[$branchId][$resourceId] = $appointment->ends_at;
            $resourceIds[$appointment->id] = $resourceId;
        }

        return $resourceIds;
    }

    protected function staffResourceIdsForBranch(int $branchId): Collection
    {
        if (array_key_exists($branchId, $this->staffResourceCache)) {
            return $this->staffResourceCache[$branchId];
        }

        $staffIds = BeautyStaff::query()
            ->where('branch_id', $branchId)
            ->where('status', true)
            ->orderBy('name')
            ->pluck('id')
            ->map(fn (int $id): string => 'staff-' . $id);

        $capacity = max(1, (int) (Branch::query()->whereKey($branchId)->value('booking_capacity') ?? 1));
        $placeholderIds = collect(range($staffIds->count() + 1, max($staffIds->count(), $capacity)))
            ->map(fn (int $slot): string => 'staff-slot-' . $branchId . '-' . $slot);

        return $this->staffResourceCache[$branchId] = $staffIds
            ->concat($placeholderIds)
            ->values();
    }

    protected function fallbackStaffResourceId(?int $branchId): string
    {
        return $this->staffResourceIdsForBranch((int) $branchId)->first() ?? 'staff-slot-' . ((int) $branchId) . '-1';
    }

    protected function statusColor(?string $status): string
    {
        return match ($status) {
            'pending' => '#d97706',
            'completed' => '#2563eb',
            'cancelled' => '#dc2626',
            default => '#16a34a',
        };
    }

    protected function eventColor(BookingAppointment $appointment): string
    {
        return $this->branchColor((int) $appointment->branch_id);
    }

    protected function branchColor(int $branchId): string
    {
        return $this->branchColors[($branchId - 1) % count($this->branchColors)];
    }

    protected function branchId(): ?int
    {
        return $this->selectedBranchId;
    }

    protected function selectedBranch(): ?Branch
    {
        return $this->selectedBranchId
            ? Branch::query()->find($this->selectedBranchId)
            : null;
    }

    protected function shouldShowDetailedEvents(FetchInfo $info): bool
    {
        return $info->start->diffInDays($info->end) <= 1;
    }
}

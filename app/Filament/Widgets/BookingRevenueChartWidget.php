<?php

namespace App\Filament\Widgets;

use App\Models\BookingAppointment;
use App\Models\Branch;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Carbon;

class BookingRevenueChartWidget extends ChartWidget
{
    protected ?string $heading = 'Doanh thu booking 7 ngày gần nhất';

    protected ?string $description = 'Tự lấy theo ngày booking mới nhất, chọn shop ở góc phải để lọc.';

    public ?string $filter = 'all';

    protected static ?int $sort = 3;

    protected int | string | array $columnSpan = [
        'default' => 'full',
        'lg' => 2,
    ];

    protected function getData(): array
    {
        $labels = [];
        $revenue = [];
        $bookings = [];
        $branchId = $this->branchId();
        $anchorDate = $this->latestBookingDate($branchId);

        for ($i = 6; $i >= 0; $i--) {
            $date = $anchorDate->copy()->subDays($i);
            $labels[] = $date->format('d/m');

            $query = BookingAppointment::query()
                ->when($branchId, fn ($query) => $query->where('branch_id', $branchId))
                ->whereIn('status', ['confirmed', 'completed'])
                ->whereDate('starts_at', $date->toDateString());

            $revenue[] = (int) (clone $query)->sum('total_price');
            $bookings[] = (clone $query)->count();
        }

        return [
            'datasets' => [
                [
                    'label' => 'Doanh thu (Kč)',
                    'data' => $revenue,
                    'borderColor' => '#d97706',
                    'backgroundColor' => 'rgba(217, 119, 6, .16)',
                    'fill' => true,
                    'tension' => .35,
                ],
                [
                    'label' => 'Số lịch',
                    'data' => $bookings,
                    'borderColor' => '#2563eb',
                    'backgroundColor' => 'rgba(37, 99, 235, .12)',
                    'tension' => .35,
                    'yAxisID' => 'y1',
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getOptions(): array
    {
        return [
            'scales' => [
                'y' => [
                    'beginAtZero' => true,
                ],
                'y1' => [
                    'beginAtZero' => true,
                    'position' => 'right',
                    'grid' => [
                        'drawOnChartArea' => false,
                    ],
                ],
            ],
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }

    protected function getFilters(): ?array
    {
        return [
            'all' => 'Tất cả shop',
            ...Branch::query()
                ->where('status', true)
                ->orderBy('name')
                ->pluck('name', 'id')
                ->mapWithKeys(fn (string $name, int $id): array => [(string) $id => $name])
                ->all(),
        ];
    }

    protected function branchId(): ?int
    {
        $branchId = $this->filter;

        return filled($branchId) && $branchId !== 'all' ? (int) $branchId : null;
    }

    protected function latestBookingDate(?int $branchId): Carbon
    {
        $latest = BookingAppointment::query()
            ->when($branchId, fn ($query) => $query->where('branch_id', $branchId))
            ->max('starts_at');

        return $latest ? Carbon::parse($latest) : Carbon::now();
    }
}

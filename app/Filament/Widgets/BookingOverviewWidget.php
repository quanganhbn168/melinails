<?php

namespace App\Filament\Widgets;

use App\Models\BookingAppointment;
use App\Models\Branch;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Carbon;

class BookingOverviewWidget extends BaseWidget
{
    protected static ?int $sort = 2;

    protected function getStats(): array
    {
        $revenueStatuses = ['confirmed', 'completed'];
        $today = now()->toDateString();
        $latestBookingDate = BookingAppointment::query()->max('starts_at');
        $latestBookingDate = $latestBookingDate ? Carbon::parse($latestBookingDate) : null;

        return [
            Stat::make('Lịch hôm nay', BookingAppointment::query()
                ->whereDate('starts_at', now()->toDateString())
                ->count())
                ->description('Tất cả shop')
                ->descriptionIcon('heroicon-m-calendar-days')
                ->color('info'),

            Stat::make('Doanh thu hôm nay', $this->money(BookingAppointment::query()
                ->whereIn('status', $revenueStatuses)
                ->whereDate('starts_at', $today)
                ->sum('total_price')))
                ->description('Tất cả shop, confirmed/completed')
                ->descriptionIcon('heroicon-m-banknotes')
                ->color('success'),

            Stat::make('Doanh thu tháng', $this->money(BookingAppointment::query()
                ->whereIn('status', $revenueStatuses)
                ->whereBetween('starts_at', [now()->startOfMonth(), now()->endOfMonth()])
                ->sum('total_price')))
                ->description('Tất cả shop, từ đầu tháng')
                ->descriptionIcon('heroicon-m-chart-bar')
                ->color('success'),

            Stat::make('Lịch chờ xác nhận', BookingAppointment::query()
                ->where('status', 'pending')
                ->count())
                ->description('Tất cả shop cần xử lý')
                ->descriptionIcon('heroicon-m-clock')
                ->color('warning'),

            Stat::make('Shop nhận booking', Branch::query()->where('status', true)->where('online_booking_enabled', true)->count())
                ->description('Đang bật booking online')
                ->descriptionIcon('heroicon-m-building-storefront')
                ->color('success'),

            Stat::make('Dữ liệu gần nhất', $latestBookingDate ? $latestBookingDate->format('d/m/Y') : 'Chưa có')
                ->description('Ngày booking mới nhất trong hệ thống')
                ->descriptionIcon('heroicon-m-circle-stack')
                ->color('gray'),
        ];
    }

    protected function money(int | float | string $amount): string
    {
        return number_format((int) $amount, 0, ',', ' ') . ' Kč';
    }
}

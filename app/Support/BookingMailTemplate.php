<?php

namespace App\Support;

use App\Models\BookingAppointment;

class BookingMailTemplate
{
    public const DEFAULT_SUBJECT = 'Potvrzení rezervace Meli Nails & Beauty';

    public const DEFAULT_BODY = <<<'TEXT'
Dear [name],

Thank you for booking with Meli Nails & Beauty.

Your appointment [booking_code] is confirmed for [date] at [time].

Shop: [shop]
Services: [services]
Total time: [duration]
Price: [price]

If you need to change your appointment, please contact the salon by phone.
TEXT;

    public static function placeholders(): array
    {
        return [
            '[name]' => 'Tên khách hàng',
            '[booking_code]' => 'Mã lịch hẹn',
            '[date]' => 'Ngày hẹn',
            '[time]' => 'Giờ hẹn',
            '[shop]' => 'Tên shop / chi nhánh',
            '[shop_address]' => 'Địa chỉ shop',
            '[services]' => 'Danh sách dịch vụ',
            '[staff]' => 'Nhân viên / team phụ trách',
            '[duration]' => 'Tổng thời lượng',
            '[price]' => 'Tổng tiền',
            '[note]' => 'Ghi chú của khách',
        ];
    }

    public static function render(string $template, BookingAppointment $appointment): string
    {
        return strtr($template, self::values($appointment));
    }

    public static function values(BookingAppointment $appointment): array
    {
        $appointment->loadMissing(['branch', 'staff', 'segments.staff']);

        $services = $appointment->segments->isNotEmpty()
            ? $appointment->segments->pluck('service_name')->filter()->join(', ')
            : collect($appointment->service_snapshot ?? [])->pluck('name')->filter()->join(', ');

        $staff = $appointment->segments->isNotEmpty()
            ? $appointment->segments->map(fn ($segment) => $segment->staff?->name)->filter()->unique()->join(', ')
            : $appointment->staff?->name;

        return [
            '[name]' => $appointment->customer_name ?? '',
            '[booking_code]' => $appointment->code ?? '',
            '[date]' => $appointment->starts_at?->format('d.m.Y') ?? '',
            '[time]' => trim(($appointment->starts_at?->format('H:i') ?? '') . ' - ' . ($appointment->ends_at?->format('H:i') ?? ''), ' -'),
            '[shop]' => $appointment->branch?->name ?? '',
            '[shop_address]' => $appointment->branch?->address ?? '',
            '[services]' => $services,
            '[staff]' => $staff ?: 'Meli Nails team',
            '[duration]' => ($appointment->total_duration_minutes ?? 0) . ' min',
            '[price]' => number_format((int) ($appointment->total_price ?? 0), 0, ',', ' ') . ' Kč',
            '[note]' => $appointment->customer_note ?? '',
        ];
    }
}

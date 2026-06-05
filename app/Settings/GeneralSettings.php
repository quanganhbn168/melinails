<?php

namespace App\Settings;

use Spatie\LaravelSettings\Settings;

class GeneralSettings extends Settings
{
    // ── Thương hiệu ──────────────────────────────────────────────
    public ?string $site_name;
    public ?string $company_name;
    public ?string $description;
    public ?string $logo;
    public ?string $favicon;
    public ?string $banner;
    public ?string $catalog_file;
    public ?string $business_code;
    public ?string $tax_code;

    // ── Liên hệ ──────────────────────────────────────────────────
    public ?string $phone;
    public ?string $phone_display;
    public ?string $email;
    public ?string $address;
    public ?string $map;
    public ?string $working_hours;

    // ── Mạng xã hội ─────────────────────────────────────────────
    public ?string $zalo;
    public ?string $messenger;
    public ?string $youtube;
    public ?string $tiktok;
    public ?string $bct_link;

    // ── Cấu hình Menu ────────────────────────────────────────────
    public ?int    $header_menu_id;

    // ── Cấu hình Footer ─────────────────────────────────────────
    public ?string $footer_background;
    public ?string $footer_col_2_title;
    public ?int    $footer_col_2_menu_id;
    public ?string $footer_col_3_title;
    public ?int    $footer_col_3_menu_id;

    // ── SEO ──────────────────────────────────────────────────────
    public ?string $meta_description;
    public ?string $meta_keywords;
    public ?string $meta_image;
    public ?string $head_script;
    public ?string $body_start_script;
    public ?string $body_script;

    // ── Booking rules ───────────────────────────────────────────
    public ?bool $booking_online_enabled;
    public ?bool $booking_customer_staff_selection_enabled;
    public ?bool $booking_staff_auto_assign_enabled;
    public ?bool $booking_auto_confirm_enabled;

    // ── Booking mail ────────────────────────────────────────────
    public ?bool $booking_customer_mail_enabled;
    public ?string $booking_customer_mail_subject;
    public ?string $booking_customer_mail_intro;
    public ?string $booking_customer_mail_body;
    public ?bool $booking_admin_mail_enabled;
    public ?string $booking_admin_mail_to;

    public static function group(): string
    {
        return 'general';
    }
}

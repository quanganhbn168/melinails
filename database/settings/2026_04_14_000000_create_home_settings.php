<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        // ── Khối Giới thiệu ──────────────────────────────────────
        $this->migrator->add('home.intro_title', null);
        $this->migrator->add('home.intro_description', null);
        $this->migrator->add('home.intro_image', null);

        // ── Điểm nổi bật giới thiệu ─────────────────────────────
        $this->migrator->add('home.intro_features', [
            ['icon' => 'fas fa-shield-alt', 'title' => 'Bảo Mật Cấp Doanh Nghiệp', 'description' => 'Kiến trúc bảo mật đa lớp chuẩn quốc tế, mã hóa đầu cuối với khả năng sao lưu.'],
            ['icon' => 'fas fa-bolt',       'title' => 'Hiệu Suất Vượt Trội',       'description' => 'Xử lý hàng triệu bản ghi nhờ công nghệ tối ưu hóa truy vấn.'],
        ]);

        // ── Video giới thiệu ─────────────────────────────────────
        $this->migrator->add('home.video_url', null);
        $this->migrator->add('home.video_title', null);
        $this->migrator->add('home.video_file', null);

        // ── Bộ đếm thống kê ─────────────────────────────────────
        $this->migrator->add('home.counters', [
            ['icon' => 'clock',          'value' => '10+',   'label' => 'Năm kinh nghiệm',     'color' => 'blue'],
            ['icon' => 'users',          'value' => '500+',  'label' => 'Khách hàng tin dùng',  'color' => 'emerald'],
            ['icon' => 'briefcase',      'value' => '1000+', 'label' => 'Dự án triển khai',     'color' => 'amber'],
            ['icon' => 'globe-alt',      'value' => '30+',   'label' => 'Tỉnh thành phủ sóng', 'color' => 'violet'],
        ]);

        // ── Tiêu đề & Mô tả các khối ────────────────────────────
        $this->migrator->add('home.services_title', 'Dịch Vụ Cung Cấp');
        $this->migrator->add('home.services_description', null);
        $this->migrator->add('home.fields_title', 'Lĩnh Vực Hoạt Động');
        $this->migrator->add('home.fields_description', null);
        $this->migrator->add('home.projects_title', 'Dự Án Tiêu Biểu');
        $this->migrator->add('home.projects_description', null);
        $this->migrator->add('home.products_title', 'Sản Phẩm & Thiết Bị');
        $this->migrator->add('home.products_description', null);
        $this->migrator->add('home.posts_title', 'Tin Tức Mới Nhất');
        $this->migrator->add('home.posts_description', null);
    }
};

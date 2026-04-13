<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        // ── Trang Về chúng tôi ──────────────────────────────────
        $this->migrator->add('intro.page_title',    'Về Chúng Tôi');
        $this->migrator->add('intro.page_subtitle', 'Đối tác chuyển đổi số tin cậy cho doanh nghiệp Việt');
        $this->migrator->add('intro.page_banner_id', null);

        // ── Câu chuyện / Tầm nhìn ──────────────────────────────
        $this->migrator->add('intro.story_title',       'Câu chuyện của chúng tôi');
        $this->migrator->add('intro.story_description', null);   // Rich text / HTML
        $this->migrator->add('intro.story_image_id',    null);   // Curator media ID
        $this->migrator->add('intro.founded_year',      null);   // Năm thành lập

        // ── Sứ mệnh & Tầm nhìn ────────────────────────────────
        $this->migrator->add('intro.mission_title',       'Sứ mệnh');
        $this->migrator->add('intro.mission_description', null);
        $this->migrator->add('intro.vision_title',        'Tầm nhìn');
        $this->migrator->add('intro.vision_description',  null);

        // ── Video giới thiệu ──────────────────────────────────
        $this->migrator->add('intro.video_title',        null);
        $this->migrator->add('intro.video_url',          null);   // YouTube embed URL
        $this->migrator->add('intro.video_thumbnail_id', null);   // Curator media ID

        // ── Stats / Số liệu nổi bật (Repeater) ────────────────
        $this->migrator->add('intro.stats', [
            ['icon' => 'clock',   'value' => '10+',   'label' => 'Năm kinh nghiệm',    'suffix' => ''],
            ['icon' => 'users',   'value' => '500+',  'label' => 'Khách hàng tin dùng', 'suffix' => ''],
            ['icon' => 'trophy',  'value' => '1000+', 'label' => 'Dự án triển khai',   'suffix' => ''],
            ['icon' => 'globe-alt','value' => '30+',  'label' => 'Tỉnh thành phủ sóng','suffix' => ''],
        ]);

        // ── Giá trị cốt lõi (Repeater) ────────────────────────
        $this->migrator->add('intro.core_values', [
            ['icon' => 'shield-check',  'title' => 'Uy tín',       'description' => 'Cam kết chất lượng và đúng hẹn trong mọi dự án'],
            ['icon' => 'heart',         'title' => 'Tận tâm',      'description' => 'Lắng nghe và đồng hành cùng khách hàng'],
            ['icon' => 'rocket-launch', 'title' => 'Đổi mới',      'description' => 'Liên tục cập nhật công nghệ tiên tiến'],
            ['icon' => 'light-bulb',    'title' => 'Sáng tạo',     'description' => 'Giải pháp linh hoạt, phù hợp từng doanh nghiệp'],
        ]);

        // ── CTA cuối trang ─────────────────────────────────────
        $this->migrator->add('intro.cta_title',    'Sẵn sàng chuyển đổi số cùng chúng tôi?');
        $this->migrator->add('intro.cta_subtitle', 'Liên hệ ngay để được tư vấn miễn phí và nhận báo giá phù hợp nhất');
        $this->migrator->add('intro.cta_button_label', 'Liên hệ tư vấn');
    }
};

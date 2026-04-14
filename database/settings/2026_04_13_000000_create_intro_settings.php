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

        // ── Câu chuyện công ty ──────────────────────────────────
        $this->migrator->add('intro.story_title',       'Câu chuyện của chúng tôi');
        $this->migrator->add('intro.story_description', null);
        $this->migrator->add('intro.story_image_id',    null);

        // ── Sứ mệnh & Tầm nhìn ────────────────────────────────
        $this->migrator->add('intro.mission_title',       'Sứ mệnh');
        $this->migrator->add('intro.mission_description', null);
        $this->migrator->add('intro.vision_title',        'Tầm nhìn');
        $this->migrator->add('intro.vision_description',  null);

        // ── Lịch sử phát triển (Timeline) ──────────────────────
        $this->migrator->add('intro.timeline', [
            ['year' => '2014', 'title' => 'Thành lập công ty',         'description' => 'Khởi đầu với đội ngũ 5 thành viên và niềm đam mê công nghệ.', 'image_id' => null],
            ['year' => '2017', 'title' => 'Mở rộng thị trường',       'description' => 'Phủ sóng 10 tỉnh thành, phục vụ hơn 200 khách hàng.',           'image_id' => null],
            ['year' => '2020', 'title' => 'Chuyển đổi số toàn diện',  'description' => 'Ra mắt nền tảng ERP thế hệ mới, đáp ứng chuẩn Enterprise.',     'image_id' => null],
            ['year' => '2024', 'title' => 'Vươn tầm khu vực',         'description' => 'Hơn 500 khách hàng, 30+ tỉnh thành, đối tác chiến lược quốc tế.', 'image_id' => null],
        ]);

        // ── Stats / Số liệu nổi bật ────────────────────────────
        $this->migrator->add('intro.stats', [
            ['icon' => 'clock',    'value' => '10+',   'label' => 'Năm kinh nghiệm',     'suffix' => ''],
            ['icon' => 'users',    'value' => '500+',  'label' => 'Khách hàng tin dùng',  'suffix' => ''],
            ['icon' => 'trophy',   'value' => '1000+', 'label' => 'Dự án triển khai',     'suffix' => ''],
            ['icon' => 'globe-alt','value' => '30+',   'label' => 'Tỉnh thành phủ sóng', 'suffix' => ''],
        ]);

        // ── Giá trị cốt lõi ────────────────────────────────────
        $this->migrator->add('intro.core_values', [
            ['icon' => 'shield-check',  'title' => 'Uy tín',   'description' => 'Cam kết chất lượng và đúng hẹn trong mọi dự án'],
            ['icon' => 'heart',         'title' => 'Tận tâm',  'description' => 'Lắng nghe và đồng hành cùng khách hàng'],
            ['icon' => 'rocket-launch', 'title' => 'Đổi mới',  'description' => 'Liên tục cập nhật công nghệ tiên tiến'],
            ['icon' => 'light-bulb',    'title' => 'Sáng tạo', 'description' => 'Giải pháp linh hoạt, phù hợp từng doanh nghiệp'],
        ]);

        // ── Khối nội dung tùy chỉnh (Builder) ──────────────────
        $this->migrator->add('intro.custom_blocks', []);

        // ── CTA cuối trang ─────────────────────────────────────
        $this->migrator->add('intro.cta_title',    'Sẵn sàng chuyển đổi số cùng chúng tôi?');
        $this->migrator->add('intro.cta_subtitle', 'Liên hệ ngay để được tư vấn miễn phí và nhận báo giá phù hợp nhất');
        $this->migrator->add('intro.cta_button_label', 'Liên hệ tư vấn');
    }
};

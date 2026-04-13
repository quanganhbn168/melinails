<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        $this->migrator->add('page.fields_cta_title', 'Bạn chưa tìm thấy ngành nghề của mình?');
        $this->migrator->add('page.fields_cta_description', 'Hệ thống của chúng tôi sở hữu lõi công nghệ linh hoạt Low-code/No-code cho phép Customize và thiết kế lại quy trình để vừa vặn với bất kỳ mô hình quản trị nào.');
        $this->migrator->add('page.fields_cta_link', '');
    }
};

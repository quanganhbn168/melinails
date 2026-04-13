<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        $pages = [
            'agency' => 'Đại lý',
            'consulting' => 'Tư vấn triển khai'
        ];

        foreach ($pages as $key => $title) {
            $this->migrator->add("page.{$key}_title", $title);
            $this->migrator->add("page.{$key}_headline", null);
            $this->migrator->add("page.{$key}_description", '');
            $this->migrator->add("page.{$key}_content", null);
            $this->migrator->add("page.{$key}_banner", null);
            $this->migrator->add("page.{$key}_cta_title", null);
            $this->migrator->add("page.{$key}_cta_description", null);
            $this->migrator->add("page.{$key}_cta_link", null);
        }
    }
};

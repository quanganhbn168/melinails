<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        $this->migrator->add('general.booking_form_layout', 'single');
    }

    public function down(): void
    {
        $this->migrator->delete('general.booking_form_layout');
    }
};

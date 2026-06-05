<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        $this->migrator->add('general.booking_staff_auto_assign_enabled', true);
    }

    public function down(): void
    {
        $this->migrator->delete('general.booking_staff_auto_assign_enabled');
    }
};

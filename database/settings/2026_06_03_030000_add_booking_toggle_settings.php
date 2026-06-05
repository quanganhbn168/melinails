<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        $this->migrator->add('general.booking_online_enabled', true);
        $this->migrator->add('general.booking_customer_staff_selection_enabled', true);
    }
};

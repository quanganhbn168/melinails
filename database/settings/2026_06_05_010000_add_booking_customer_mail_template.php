<?php

use App\Support\BookingMailTemplate;
use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        $this->migrator->add('general.booking_customer_mail_body', BookingMailTemplate::DEFAULT_BODY);
    }
};

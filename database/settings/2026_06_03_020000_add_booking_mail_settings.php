<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        $this->migrator->add('general.booking_customer_mail_enabled', true);
        $this->migrator->add('general.booking_customer_mail_subject', 'Potvrzení rezervace Meli Nails & Beauty');
        $this->migrator->add('general.booking_customer_mail_intro', 'Děkujeme za rezervaci. Níže najdete potvrzené údaje termínu.');
        $this->migrator->add('general.booking_admin_mail_enabled', false);
        $this->migrator->add('general.booking_admin_mail_to', null);
    }
};

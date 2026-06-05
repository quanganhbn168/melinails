<?php

namespace App\Mail;

use App\Models\BookingAppointment;
use App\Settings\GeneralSettings;
use App\Support\BookingMailTemplate;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class BookingCustomerConfirmation extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public BookingAppointment $appointment,
        public GeneralSettings $settings,
        public bool $adminCopy = false,
    ) {}

    public function envelope(): Envelope
    {
        $subject = BookingMailTemplate::render(
            $this->settings->booking_customer_mail_subject ?: BookingMailTemplate::DEFAULT_SUBJECT,
            $this->appointment,
        );

        return new Envelope(
            subject: $this->adminCopy ? '[Admin] ' . $subject : $subject,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.booking-customer-confirmation',
            with: [
                'mailSubject' => BookingMailTemplate::render(
                    $this->settings->booking_customer_mail_subject ?: BookingMailTemplate::DEFAULT_SUBJECT,
                    $this->appointment,
                ),
                'mailBody' => BookingMailTemplate::render(
                    $this->settings->booking_customer_mail_body ?: BookingMailTemplate::DEFAULT_BODY,
                    $this->appointment,
                ),
            ],
        );
    }
}

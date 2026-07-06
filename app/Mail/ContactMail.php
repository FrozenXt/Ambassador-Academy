<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Modules\Common\Entities\Contact;

class ContactMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Contact $contact) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'New Enquiry from ' . $this->contact->first_name . ' ' . $this->contact->last_name,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'web::emails.contact',
        );
    }
}

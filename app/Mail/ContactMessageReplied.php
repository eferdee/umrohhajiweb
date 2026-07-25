<?php

namespace App\Mail;

use App\Models\ContactMessage;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ContactMessageReplied extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public ContactMessage $contact)
    {
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Balasan untuk pesan Anda: ' . $this->contact->subject,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.contact-replied',
            with: [
                'contact' => $this->contact,
            ],
        );
    }
}

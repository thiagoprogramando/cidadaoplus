<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class Forgout extends Mailable {

    use Queueable, SerializesModels;

    public function __construct(public readonly array $data) {
        
    }

    public function envelope(): Envelope {
        return new Envelope(
            subject : $this->data['subject'],
            from    : new Address($this->data['fromEmail'], $this->data['fromName']),
        );
    }

    public function content(): Content {
        return new Content(
            view: 'Mail.forgoutPassword',
        );
    }

    public function attachments(): array {
        return [];
    }
}

<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use App\Models\Dealer;

class DealerApplicationMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $dealer;

    public function __construct(Dealer $dealer)
    {
        $this->dealer = $dealer;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Aczet Authorized Dealership Application Received - ' . $this->dealer->company_name,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.dealer_confirmation',
        );
    }
}

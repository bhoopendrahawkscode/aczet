<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class QuoteRfqMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $lead;
    public $products;

    public function __construct($lead, $products)
    {
        $this->lead = $lead;
        $this->products = $products;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Aczet Official Consolidated RFQ Proforma - RFQ #' . $this->lead->id,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.quote_rfq_confirmation',
        );
    }
}

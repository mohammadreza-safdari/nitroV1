<?php

namespace App\Mail;

use App\Models\Sales\Deal;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class DealRequestProcessedMail extends Mailable
{
    use Queueable, SerializesModels;

    public string $requestLabel;
    public string $statusLabel;

    public function __construct(
        public Deal   $deal,
        public string $requestType,
        public string $status,
    )
    {
        $this->requestLabel = match ($requestType) {
            'deal' => 'Purchase Request',
            'change_request' => 'Deal Change Request',
        };

        $this->statusLabel = match ($status) {
            'approved' => 'Approved',
            'rejected' => 'Rejected',
        };
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: "Nitro - {$this->requestLabel} {$this->statusLabel}",
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.deals.request-processed',
        );
    }
}

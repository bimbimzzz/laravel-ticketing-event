<?php

namespace App\Mail;

use App\Models\Vendor;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class VendorStatusMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Vendor $vendor, public string $newStatus) {}

    public function envelope(): Envelope
    {
        $label = $this->newStatus === 'approved' ? 'Disetujui' : 'Ditolak';
        return new Envelope(
            subject: "Vendor {$label} - JagoEvent",
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.vendor-status',
        );
    }
}

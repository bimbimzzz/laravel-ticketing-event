<?php

namespace App\Mail;

use App\Models\Order;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class EticketMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Order $order) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'E-Ticket Pesanan #' . $this->order->id . ' - KarcisDigital',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.eticket',
        );
    }

    public function attachments(): array
    {
        $this->order->load(['user', 'event.vendor', 'orderTickets.ticket.sku']);
        $order = $this->order;

        // Pre-fetch QR codes as base64 for PDF (DomPDF can't fetch external URLs)
        $qrCodes = [];
        foreach ($order->orderTickets as $ot) {
            $code = $ot->ticket->ticket_code;
            try {
                $qrUrl = 'https://api.qrserver.com/v1/create-qr-code/?size=200x200&format=png&data=' . urlencode($code);
                $imageData = @file_get_contents($qrUrl);
                if ($imageData) {
                    $qrCodes[$code] = 'data:image/png;base64,' . base64_encode($imageData);
                }
            } catch (\Exception $e) {
                // Skip if QR fetch fails
            }
        }

        $pdf = Pdf::loadView('invoices.eticket', compact('order', 'qrCodes'));

        return [
            Attachment::fromData(
                fn () => $pdf->output(),
                'eticket-' . $this->order->id . '.pdf'
            )->withMime('application/pdf'),
        ];
    }
}

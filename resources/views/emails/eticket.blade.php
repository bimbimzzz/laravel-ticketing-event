<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>

<body
    style="margin: 0; padding: 0; background-color: #f3f4f6; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;">
    <table role="presentation" width="100%" cellpadding="0" cellspacing="0"
        style="background-color: #f3f4f6; padding: 40px 20px;">
        <tr>
            <td align="center">
                <table role="presentation" width="600" cellpadding="0" cellspacing="0"
                    style="max-width: 600px; width: 100%;">

                    {{-- Header --}}
                    <tr>
                        <td
                            style="background: linear-gradient(135deg, #059669, #10b981); border-radius: 12px 12px 0 0; padding: 30px 40px; text-align: center;">
                            <h1
                                style="margin: 0; color: #ffffff; font-size: 24px; font-weight: 700; letter-spacing: -0.5px;">
                                KarcisDigital</h1>
                            <p style="margin: 8px 0 0; color: rgba(255,255,255,0.9); font-size: 14px;">Pembayaran
                                Berhasil!</p>
                        </td>
                    </tr>

                    {{-- Body --}}
                    <tr>
                        <td style="background-color: #ffffff; padding: 40px;">

                            {{-- Success Badge --}}
                            <table role="presentation" width="100%" cellpadding="0" cellspacing="0">
                                <tr>
                                    <td align="center" style="padding-bottom: 25px;">
                                        <div
                                            style="display: inline-block; width: 60px; height: 60px; background: #dcfce7; border-radius: 50%; text-align: center; line-height: 60px; font-size: 28px;">
                                            &#10003;</div>
                                    </td>
                                </tr>
                            </table>

                            <p style="margin: 0 0 5px; color: #6b7280; font-size: 14px;">Halo,</p>
                            <h2 style="margin: 0 0 15px; color: #111827; font-size: 20px; font-weight: 600;">
                                {{ $order->user->name }}</h2>
                            <p style="margin: 0 0 30px; color: #4b5563; font-size: 15px; line-height: 1.6;">Pembayaran
                                untuk pesanan <strong>#{{ str_pad($order->id, 5, '0', STR_PAD_LEFT) }}</strong> telah
                                dikonfirmasi. Berikut adalah e-ticket Anda.</p>

                            {{-- Event Card --}}
                            <table role="presentation" width="100%" cellpadding="0" cellspacing="0"
                                style="background: linear-gradient(135deg, #f0fdf4, #ecfdf5); border: 1px solid #bbf7d0; border-radius: 12px; overflow: hidden; margin-bottom: 30px;">
                                <tr>
                                    <td style="padding: 25px;">
                                        <p
                                            style="margin: 0 0 4px; color: #9ca3af; font-size: 11px; text-transform: uppercase; letter-spacing: 1px; font-weight: 600;">
                                            Event</p>
                                        <p style="margin: 0 0 8px; color: #111827; font-size: 18px; font-weight: 700;">
                                            {{ $order->event->name }}</p>
                                        <table role="presentation" cellpadding="0" cellspacing="0">
                                            <tr>
                                                <td style="padding-right: 20px;">
                                                    <p
                                                        style="margin: 0 0 2px; color: #9ca3af; font-size: 11px; text-transform: uppercase; letter-spacing: 0.5px;">
                                                        Tanggal</p>
                                                    <p
                                                        style="margin: 0; color: #374151; font-size: 14px; font-weight: 600;">
                                                        {{ $order->event_date ? \Carbon\Carbon::parse($order->event_date)->translatedFormat('d F Y') : \Carbon\Carbon::parse($order->event->start_date)->translatedFormat('d F Y') }}
                                                    </p>
                                                </td>
                                                <td style="padding-right: 20px;">
                                                    <p
                                                        style="margin: 0 0 2px; color: #9ca3af; font-size: 11px; text-transform: uppercase; letter-spacing: 0.5px;">
                                                        Penyelenggara</p>
                                                    <p
                                                        style="margin: 0; color: #374151; font-size: 14px; font-weight: 600;">
                                                        {{ $order->event->vendor->name ?? '-' }}</p>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>

                            {{-- Tickets --}}
                            <p style="margin: 0 0 15px; color: #111827; font-size: 16px; font-weight: 700;">Tiket Anda
                                ({{ $order->orderTickets->count() }})</p>

                            @foreach ($order->orderTickets as $ot)
                                <table role="presentation" width="100%" cellpadding="0" cellspacing="0"
                                    style="border: 1px solid #e5e7eb; border-radius: 10px; overflow: hidden; margin-bottom: 12px;">
                                    <tr>
                                        <td style="background: #f9fafb; padding: 15px 20px;">
                                            <table role="presentation" width="100%" cellpadding="0" cellspacing="0">
                                                <tr>
                                                    <td>
                                                        <p
                                                            style="margin: 0 0 4px; color: #6b7280; font-size: 12px; text-transform: uppercase; letter-spacing: 0.5px;">
                                                            {{ $ot->ticket->sku->name ?? 'Tiket' }} &bull;
                                                            {{ $ot->ticket->sku->category ?? '' }}</p>
                                                        <p
                                                            style="margin: 0; color: #111827; font-size: 20px; font-weight: 700; font-family: 'Courier New', monospace; letter-spacing: 2px;">
                                                            {{ $ot->ticket->ticket_code }}</p>
                                                    </td>
                                                    <td align="right" style="vertical-align: middle;">
                                                        <p
                                                            style="margin: 0; color: #4f46e5; font-size: 15px; font-weight: 700;">
                                                            Rp
                                                            {{ number_format($ot->ticket->sku->price ?? 0, 0, ',', '.') }}
                                                        </p>
                                                    </td>
                                                </tr>
                                            </table>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="padding: 12px 20px; border-top: 1px dashed #e5e7eb;">
                                            <table role="presentation" width="100%" cellpadding="0" cellspacing="0">
                                                <tr>
                                                    <td>
                                                        <img src="https://api.qrserver.com/v1/create-qr-code/?size=80x80&data={{ urlencode($ot->ticket->ticket_code) }}"
                                                            alt="QR" width="80" height="80"
                                                            style="border-radius: 6px;">
                                                    </td>
                                                    <td style="padding-left: 15px; vertical-align: middle;">
                                                        <p style="margin: 0 0 4px; color: #6b7280; font-size: 12px;">
                                                            Tunjukkan QR code ini saat masuk event</p>
                                                        <p style="margin: 0; color: #9ca3af; font-size: 11px;">Status:
                                                            <span style="color: #059669; font-weight: 600;">Aktif</span>
                                                        </p>
                                                    </td>
                                                </tr>
                                            </table>
                                        </td>
                                    </tr>
                                </table>
                            @endforeach

                            {{-- Total --}}
                            <table role="presentation" width="100%" cellpadding="0" cellspacing="0"
                                style="margin-top: 20px; border-top: 2px solid #111827; padding-top: 15px;">
                                <tr>
                                    <td>
                                        <p style="margin: 0; color: #111827; font-size: 16px; font-weight: 700;">Total
                                            Dibayar</p>
                                    </td>
                                    <td align="right">
                                        <p style="margin: 0; color: #111827; font-size: 20px; font-weight: 700;">Rp
                                            {{ number_format($order->total_price, 0, ',', '.') }}</p>
                                    </td>
                                </tr>
                            </table>

                            {{-- CTA --}}
                            <table role="presentation" width="100%" cellpadding="0" cellspacing="0"
                                style="margin-top: 30px;">
                                <tr>
                                    <td align="center">
                                        <a href="{{ url('/orders/' . $order->id) }}"
                                            style="display: inline-block; padding: 14px 40px; background: linear-gradient(135deg, #059669, #10b981); color: #ffffff; text-decoration: none; border-radius: 10px; font-size: 15px; font-weight: 700;">Lihat
                                            E-Ticket di Website</a>
                                    </td>
                                </tr>
                            </table>

                            {{-- Tips --}}
                            <table role="presentation" width="100%" cellpadding="0" cellspacing="0"
                                style="margin-top: 30px;">
                                <tr>
                                    <td
                                        style="background: #fffbeb; border-left: 4px solid #f59e0b; border-radius: 0 8px 8px 0; padding: 15px 20px;">
                                        <p style="margin: 0 0 4px; color: #92400e; font-size: 13px; font-weight: 600;">
                                            Tips Sebelum Event</p>
                                        <p style="margin: 0; color: #a16207; font-size: 13px; line-height: 1.5;">Simpan
                                            email ini atau screenshot QR code. Pastikan baterai HP cukup saat check-in
                                            di lokasi event.</p>
                                    </td>
                                </tr>
                            </table>

                        </td>
                    </tr>

                    {{-- Footer --}}
                    <tr>
                        <td
                            style="background-color: #f9fafb; border-top: 1px solid #e5e7eb; border-radius: 0 0 12px 12px; padding: 25px 40px; text-align: center;">
                            <p style="margin: 0 0 8px; color: #6b7280; font-size: 13px;">Butuh bantuan? Hubungi kami di
                                <a href="mailto:support@jagon8n.com"
                                    style="color: #4f46e5; text-decoration: none; font-weight: 500;">support@jagon8n.com</a>
                            </p>
                            <p style="margin: 0; color: #9ca3af; font-size: 12px;">&copy; {{ date('Y') }}
                                KarcisDigital. All rights reserved.</p>
                        </td>
                    </tr>

                </table>
            </td>
        </tr>
    </table>
</body>

</html>

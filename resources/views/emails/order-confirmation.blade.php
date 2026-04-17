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
                            style="background: linear-gradient(135deg, #4f46e5, #7c3aed); border-radius: 12px 12px 0 0; padding: 30px 40px; text-align: center;">
                            <h1
                                style="margin: 0; color: #ffffff; font-size: 24px; font-weight: 700; letter-spacing: -0.5px;">
                                KarcisDigital</h1>
                            <p style="margin: 5px 0 0; color: rgba(255,255,255,0.8); font-size: 13px;">Platform Tiket
                                Event Terpercaya</p>
                        </td>
                    </tr>

                    {{-- Body --}}
                    <tr>
                        <td style="background-color: #ffffff; padding: 40px;">

                            {{-- Status Badge --}}
                            <table role="presentation" width="100%" cellpadding="0" cellspacing="0">
                                <tr>
                                    <td align="center" style="padding-bottom: 25px;">
                                        <span
                                            style="display: inline-block; background: #fef3c7; color: #92400e; padding: 6px 16px; border-radius: 20px; font-size: 13px; font-weight: 600;">Menunggu
                                            Pembayaran</span>
                                    </td>
                                </tr>
                            </table>

                            <p style="margin: 0 0 5px; color: #6b7280; font-size: 14px;">Halo,</p>
                            <h2 style="margin: 0 0 20px; color: #111827; font-size: 20px; font-weight: 600;">
                                {{ $order->user->name }}</h2>
                            <p style="margin: 0 0 30px; color: #4b5563; font-size: 15px; line-height: 1.6;">Pesanan Anda
                                telah berhasil dibuat. Segera selesaikan pembayaran sebelum batas waktu berakhir.</p>

                            {{-- Order Info Card --}}
                            <table role="presentation" width="100%" cellpadding="0" cellspacing="0"
                                style="background: #f9fafb; border: 1px solid #e5e7eb; border-radius: 10px; overflow: hidden; margin-bottom: 25px;">
                                <tr>
                                    <td style="padding: 20px;">
                                        <table role="presentation" width="100%" cellpadding="0" cellspacing="0">
                                            <tr>
                                                <td style="padding-bottom: 15px; border-bottom: 1px solid #e5e7eb;">
                                                    <p
                                                        style="margin: 0 0 4px; color: #9ca3af; font-size: 11px; text-transform: uppercase; letter-spacing: 1px; font-weight: 600;">
                                                        No. Pesanan</p>
                                                    <p
                                                        style="margin: 0; color: #111827; font-size: 18px; font-weight: 700;">
                                                        #{{ str_pad($order->id, 5, '0', STR_PAD_LEFT) }}</p>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="padding-top: 15px;">
                                                    <p
                                                        style="margin: 0 0 4px; color: #9ca3af; font-size: 11px; text-transform: uppercase; letter-spacing: 1px; font-weight: 600;">
                                                        Event</p>
                                                    <p
                                                        style="margin: 0 0 2px; color: #111827; font-size: 16px; font-weight: 600;">
                                                        {{ $order->event->name }}</p>
                                                    <p style="margin: 0; color: #6b7280; font-size: 13px;">
                                                        {{ $order->event->vendor->name ?? '' }}</p>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>

                            {{-- Detail Table --}}
                            <table role="presentation" width="100%" cellpadding="0" cellspacing="0"
                                style="margin-bottom: 25px;">
                                <tr>
                                    <td style="padding: 12px 0; border-bottom: 1px solid #f3f4f6;">
                                        <table role="presentation" width="100%" cellpadding="0" cellspacing="0">
                                            <tr>
                                                <td style="color: #6b7280; font-size: 14px;">Tanggal Event</td>
                                                <td align="right"
                                                    style="color: #111827; font-size: 14px; font-weight: 600;">
                                                    {{ $order->event_date ? \Carbon\Carbon::parse($order->event_date)->translatedFormat('d F Y') : \Carbon\Carbon::parse($order->event->start_date)->translatedFormat('d F Y') }}
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="padding: 12px 0; border-bottom: 1px solid #f3f4f6;">
                                        <table role="presentation" width="100%" cellpadding="0" cellspacing="0">
                                            <tr>
                                                <td style="color: #6b7280; font-size: 14px;">Jumlah Tiket</td>
                                                <td align="right"
                                                    style="color: #111827; font-size: 14px; font-weight: 600;">
                                                    {{ $order->quantity }} tiket</td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                                @if ($order->orderTickets && $order->orderTickets->count() > 0)
                                    @foreach ($order->orderTickets->groupBy(fn($ot) => $ot->ticket->sku->name ?? 'Tiket') as $skuName => $tickets)
                                        <tr>
                                            <td style="padding: 12px 0; border-bottom: 1px solid #f3f4f6;">
                                                <table role="presentation" width="100%" cellpadding="0"
                                                    cellspacing="0">
                                                    <tr>
                                                        <td style="color: #6b7280; font-size: 14px;">{{ $skuName }}
                                                            x{{ $tickets->count() }}</td>
                                                        <td align="right"
                                                            style="color: #111827; font-size: 14px; font-weight: 500;">
                                                            Rp
                                                            {{ number_format(($tickets->first()->ticket->sku->price ?? 0) * $tickets->count(), 0, ',', '.') }}
                                                        </td>
                                                    </tr>
                                                </table>
                                            </td>
                                        </tr>
                                    @endforeach
                                @endif
                                @if ($order->discount_amount > 0)
                                    <tr>
                                        <td style="padding: 12px 0; border-bottom: 1px solid #f3f4f6;">
                                            <table role="presentation" width="100%" cellpadding="0" cellspacing="0">
                                                <tr>
                                                    <td style="color: #16a34a; font-size: 14px;">
                                                        Diskon{{ $order->promo_code ? ' (' . $order->promo_code . ')' : '' }}
                                                    </td>
                                                    <td align="right"
                                                        style="color: #16a34a; font-size: 14px; font-weight: 600;">-Rp
                                                        {{ number_format($order->discount_amount, 0, ',', '.') }}</td>
                                                </tr>
                                            </table>
                                        </td>
                                    </tr>
                                @endif
                                <tr>
                                    <td style="padding: 16px 0;">
                                        <table role="presentation" width="100%" cellpadding="0" cellspacing="0">
                                            <tr>
                                                <td style="color: #111827; font-size: 16px; font-weight: 700;">Total
                                                    Pembayaran</td>
                                                <td align="right"
                                                    style="color: #4f46e5; font-size: 20px; font-weight: 700;">Rp
                                                    {{ number_format($order->total_price, 0, ',', '.') }}</td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>

                            {{-- Payment Button --}}
                            @if ($order->payment_url && $order->status_payment === 'pending')
                                <table role="presentation" width="100%" cellpadding="0" cellspacing="0"
                                    style="margin-bottom: 20px;">
                                    <tr>
                                        <td align="center">
                                            <a href="{{ $order->payment_url }}"
                                                style="display: inline-block; padding: 16px 48px; background: linear-gradient(135deg, #4f46e5, #7c3aed); color: #ffffff; text-decoration: none; border-radius: 10px; font-size: 16px; font-weight: 700; letter-spacing: 0.3px;">Bayar
                                                Sekarang</a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td align="center" style="padding-top: 12px;">
                                            <p style="margin: 0; color: #9ca3af; font-size: 12px;">Selesaikan
                                                pembayaran dalam 1 jam</p>
                                        </td>
                                    </tr>
                                </table>
                            @endif

                            {{-- Info Box --}}
                            <table role="presentation" width="100%" cellpadding="0" cellspacing="0">
                                <tr>
                                    <td
                                        style="background: #eff6ff; border-left: 4px solid #3b82f6; border-radius: 0 8px 8px 0; padding: 15px 20px;">
                                        <p style="margin: 0 0 4px; color: #1e40af; font-size: 13px; font-weight: 600;">
                                            Informasi Penting</p>
                                        <p style="margin: 0; color: #3b82f6; font-size: 13px; line-height: 1.5;">
                                            Setelah pembayaran berhasil, e-ticket akan dikirim ke email ini. Anda juga
                                            bisa mengakses e-ticket melalui website KarcisDigital.</p>
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

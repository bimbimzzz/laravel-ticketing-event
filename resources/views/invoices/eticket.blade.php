<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>E-Ticket #{{ str_pad($order->id, 5, '0', STR_PAD_LEFT) }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: sans-serif;
            font-size: 13px;
            color: #1a1a2e;
            background: #fff;
        }

        .page {
            padding: 30px;
        }

        /* Header */
        .header {
            display: table;
            width: 100%;
            margin-bottom: 25px;
            padding-bottom: 20px;
            border-bottom: 3px solid #4f46e5;
        }

        .header-left {
            display: table-cell;
            vertical-align: middle;
        }

        .header-right {
            display: table-cell;
            vertical-align: middle;
            text-align: right;
        }

        .brand {
            font-size: 26px;
            font-weight: 800;
            color: #4f46e5;
            letter-spacing: -1px;
        }

        .brand-sub {
            font-size: 11px;
            color: #9ca3af;
            margin-top: 2px;
        }

        .doc-title {
            font-size: 18px;
            font-weight: 700;
            color: #111827;
        }

        .doc-id {
            font-size: 13px;
            color: #6b7280;
            margin-top: 2px;
        }

        /* Status */
        .status-bar {
            background: #dcfce7;
            border: 1px solid #bbf7d0;
            border-radius: 8px;
            padding: 10px 18px;
            margin-bottom: 25px;
            text-align: center;
        }

        .status-bar span {
            color: #166534;
            font-weight: 700;
            font-size: 14px;
        }

        .check-icon {
            display: inline-block;
            width: 20px;
            height: 20px;
            background: #16a34a;
            color: #fff;
            border-radius: 50%;
            text-align: center;
            line-height: 20px;
            font-size: 12px;
            font-weight: 700;
            margin-right: 6px;
            vertical-align: middle;
        }

        /* Info Grid */
        .info-grid {
            display: table;
            width: 100%;
            margin-bottom: 25px;
        }

        .info-col {
            display: table-cell;
            width: 50%;
            vertical-align: top;
        }

        .info-col-right {
            display: table-cell;
            width: 50%;
            vertical-align: top;
            text-align: right;
        }

        .info-label {
            font-size: 10px;
            color: #9ca3af;
            text-transform: uppercase;
            letter-spacing: 1.2px;
            font-weight: 700;
            margin-bottom: 3px;
        }

        .info-value {
            font-size: 14px;
            font-weight: 600;
            color: #111827;
            margin-bottom: 12px;
        }

        .info-value-sm {
            font-size: 13px;
            color: #4b5563;
            margin-bottom: 12px;
        }

        /* Ticket Card */
        .ticket {
            border: 2px solid #e5e7eb;
            border-radius: 12px;
            margin-bottom: 15px;
            overflow: hidden;
            page-break-inside: avoid;
        }

        .ticket-header {
            background: #f9fafb;
            padding: 15px 20px;
            border-bottom: 2px dashed #e5e7eb;
        }

        .ticket-body {
            padding: 15px 20px;
            display: table;
            width: 100%;
        }

        .ticket-info {
            display: table-cell;
            vertical-align: middle;
            width: 65%;
        }

        .ticket-qr {
            display: table-cell;
            vertical-align: middle;
            width: 35%;
            text-align: right;
        }

        .ticket-type {
            font-size: 11px;
            color: #6b7280;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            font-weight: 600;
        }

        .ticket-code {
            font-size: 22px;
            font-weight: 800;
            font-family: 'Courier New', monospace;
            color: #111827;
            letter-spacing: 3px;
            margin: 6px 0;
        }

        .ticket-price {
            font-size: 15px;
            font-weight: 700;
            color: #4f46e5;
        }

        .ticket-status {
            display: inline-block;
            background: #dcfce7;
            color: #166534;
            padding: 3px 10px;
            border-radius: 12px;
            font-size: 11px;
            font-weight: 700;
            margin-top: 6px;
        }

        .qr-img {
            border-radius: 8px;
            border: 1px solid #e5e7eb;
        }

        .qr-label {
            font-size: 9px;
            color: #9ca3af;
            text-align: center;
            margin-top: 4px;
        }

        /* Total */
        .total-section {
            display: table;
            width: 100%;
            border-top: 2px solid #111827;
            padding-top: 12px;
            margin-top: 10px;
        }

        .total-label {
            display: table-cell;
            font-size: 15px;
            font-weight: 700;
            color: #111827;
        }

        .total-value {
            display: table-cell;
            text-align: right;
            font-size: 20px;
            font-weight: 800;
            color: #4f46e5;
        }

        /* Footer */
        .footer {
            margin-top: 30px;
            padding-top: 15px;
            border-top: 1px solid #e5e7eb;
            text-align: center;
        }

        .footer p {
            font-size: 11px;
            color: #9ca3af;
            line-height: 1.6;
        }

        .footer .important {
            background: #fffbeb;
            border: 1px solid #fde68a;
            border-radius: 8px;
            padding: 12px 18px;
            margin-bottom: 15px;
            text-align: left;
        }

        .footer .important p {
            color: #92400e;
            font-size: 12px;
        }

        .footer .important strong {
            color: #78350f;
        }
    </style>
</head>

<body>
    <div class="page">

        {{-- Header --}}
        <div class="header">
            <div class="header-left">
                <div class="brand">JagoEvent</div>
                <div class="brand-sub">Platform Tiket Event Terpercaya</div>
            </div>
            <div class="header-right">
                <div class="doc-title">E-TICKET</div>
                <div class="doc-id">#{{ str_pad($order->id, 5, '0', STR_PAD_LEFT) }} &middot;
                    {{ $order->created_at->format('d M Y') }}</div>
            </div>
        </div>

        {{-- Status --}}
        <div class="status-bar">
            <span>PEMBAYARAN BERHASIL &mdash; TIKET AKTIF</span>
        </div>

        {{-- Info Grid --}}
        <div class="info-grid">
            <div class="info-col">
                <div class="info-label">Pemegang Tiket</div>
                <div class="info-value">{{ $order->user->name }}</div>
                <div class="info-label">Email</div>
                <div class="info-value-sm">{{ $order->user->email }}</div>
            </div>
            <div class="info-col-right">
                <div class="info-label">Event</div>
                <div class="info-value">{{ $order->event->name }}</div>
                <div class="info-label">Tanggal</div>
                <div class="info-value-sm">
                    {{ $order->event_date ? \Carbon\Carbon::parse($order->event_date)->translatedFormat('d F Y') : \Carbon\Carbon::parse($order->event->start_date)->translatedFormat('d F Y') }}
                </div>
                <div class="info-label">Penyelenggara</div>
                <div class="info-value-sm">{{ $order->event->vendor->name ?? '-' }}</div>
            </div>
        </div>

        {{-- Tickets --}}
        @foreach ($order->orderTickets as $index => $ot)
            <div class="ticket">
                <div class="ticket-header">
                    <div style="display: table; width: 100%;">
                        <div style="display: table-cell; vertical-align: middle;">
                            <span style="font-size: 12px; font-weight: 700; color: #374151;">Tiket {{ $index + 1 }}
                                dari {{ $order->orderTickets->count() }}</span>
                        </div>
                        <div style="display: table-cell; text-align: right; vertical-align: middle;">
                            <span class="ticket-status">AKTIF</span>
                        </div>
                    </div>
                </div>
                <div class="ticket-body">
                    <div class="ticket-info">
                        <div class="ticket-type">{{ $ot->ticket->sku->name ?? 'Tiket' }} &middot;
                            {{ $ot->ticket->sku->category ?? '' }}</div>
                        <div class="ticket-code">{{ $ot->ticket->ticket_code }}</div>
                        <div class="ticket-price">Rp {{ number_format($ot->ticket->sku->price ?? 0, 0, ',', '.') }}
                        </div>
                    </div>
                    <div class="ticket-qr">
                        @if (isset($qrCodes[$ot->ticket->ticket_code]))
                            <img class="qr-img" src="{{ $qrCodes[$ot->ticket->ticket_code] }}" width="110"
                                height="110">
                        @else
                            <div
                                style="width: 110px; height: 110px; border: 2px dashed #d1d5db; border-radius: 8px; display: table; float: right;">
                                <span
                                    style="display: table-cell; vertical-align: middle; text-align: center; color: #9ca3af; font-size: 10px;">QR
                                    Code</span>
                            </div>
                        @endif
                        <div class="qr-label">Scan untuk check-in</div>
                    </div>
                </div>
            </div>
        @endforeach

        {{-- Total --}}
        <div class="total-section">
            <div class="total-label">Total Dibayar</div>
            <div class="total-value">Rp {{ number_format($order->total_price, 0, ',', '.') }}</div>
        </div>

        {{-- Footer --}}
        <div class="footer">
            <div class="important">
                <strong>Petunjuk Penggunaan:</strong>
                <p>1. Tunjukkan QR code atau kode tiket kepada petugas saat memasuki venue.</p>
                <p>2. Setiap tiket hanya dapat digunakan satu kali (single entry).</p>
                <p>3. Tiket tidak dapat dipindahtangankan setelah check-in.</p>
                <p>4. Simpan dokumen ini sebagai bukti pembelian.</p>
            </div>
            <p>Dokumen ini dibuat otomatis oleh sistem JagoEvent dan sah tanpa tanda tangan.</p>
            <p>&copy; {{ date('Y') }} JagoEvent &middot; support@jagon8n.com</p>
        </div>

    </div>
</body>

</html>

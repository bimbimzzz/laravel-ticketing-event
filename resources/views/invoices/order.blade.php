<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Invoice #{{ $order->id }}</title>
    <style>
        body {
            font-family: sans-serif;
            font-size: 14px;
            color: #333;
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #4f46e5;
            padding-bottom: 15px;
        }

        .header h1 {
            color: #4f46e5;
            margin: 0;
            font-size: 24px;
        }

        .header p {
            color: #666;
            margin: 5px 0 0;
        }

        .info-grid {
            display: table;
            width: 100%;
            margin-bottom: 25px;
        }

        .info-left,
        .info-right {
            display: table-cell;
            width: 50%;
            vertical-align: top;
        }

        .info-right {
            text-align: right;
        }

        .label {
            color: #999;
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .value {
            font-weight: bold;
            margin-bottom: 8px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }

        th {
            background: #f5f5f5;
            padding: 10px;
            text-align: left;
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        td {
            padding: 10px;
            border-bottom: 1px solid #eee;
        }

        .total-row {
            font-size: 18px;
            font-weight: bold;
        }

        .footer {
            text-align: center;
            margin-top: 40px;
            padding-top: 20px;
            border-top: 1px solid #eee;
            color: #999;
            font-size: 12px;
        }

        .badge {
            display: inline-block;
            padding: 3px 10px;
            border-radius: 12px;
            font-size: 12px;
            font-weight: bold;
        }

        .badge-success {
            background: #dcfce7;
            color: #166534;
        }
    </style>
</head>

<body>
    <div class="header">
        <h1>JagoEvent</h1>
        <p>Invoice / Bukti Pembayaran</p>
    </div>

    <div class="info-grid">
        <div class="info-left">
            <p class="label">Pembeli</p>
            <p class="value">{{ $order->user->name }}</p>
            <p>{{ $order->user->email }}</p>
        </div>
        <div class="info-right">
            <p class="label">Invoice</p>
            <p class="value">#INV-{{ str_pad($order->id, 5, '0', STR_PAD_LEFT) }}</p>
            <p>{{ $order->created_at->format('d M Y H:i') }}</p>
            <p><span class="badge badge-success">LUNAS</span></p>
        </div>
    </div>

    <div>
        <p class="label">Event</p>
        <p class="value">{{ $order->event->name }}</p>
        <p>{{ $order->event->vendor->name ?? '' }} &bull;
            {{ \Carbon\Carbon::parse($order->event_date)->format('d M Y') }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>Tiket</th>
                <th>Kode</th>
                <th style="text-align: right;">Harga</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($order->orderTickets as $ot)
                <tr>
                    <td>{{ $ot->ticket->sku->name ?? 'Tiket' }} ({{ $ot->ticket->sku->category ?? '' }})</td>
                    <td style="font-family: monospace;">{{ $ot->ticket->ticket_code }}</td>
                    <td style="text-align: right;">Rp {{ number_format($ot->ticket->sku->price ?? 0, 0, ',', '.') }}
                    </td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td colspan="2" style="text-align: right; font-weight: bold;">Total</td>
                <td style="text-align: right;" class="total-row">Rp
                    {{ number_format($order->total_price, 0, ',', '.') }}</td>
            </tr>
            @if ($order->discount_amount > 0)
                <tr>
                    <td colspan="2" style="text-align: right; color: #16a34a;">Diskon</td>
                    <td style="text-align: right; color: #16a34a;">-Rp
                        {{ number_format($order->discount_amount, 0, ',', '.') }}</td>
                </tr>
            @endif
        </tfoot>
    </table>

    <div class="footer">
        <p>Terima kasih telah menggunakan JagoEvent</p>
        <p>Invoice ini dibuat otomatis dan sah tanpa tanda tangan</p>
    </div>
</body>

</html>

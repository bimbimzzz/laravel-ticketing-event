<?php

namespace App\Exports;

use App\Models\Order;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class VendorOrdersExport implements FromQuery, WithHeadings, WithMapping, WithStyles
{
    public function __construct(private int $eventId) {}

    public function query()
    {
        return Order::with(['user'])
            ->where('event_id', $this->eventId)
            ->latest();
    }

    public function headings(): array
    {
        return ['ID', 'Pembeli', 'Email', 'Qty', 'Total (Rp)', 'Status', 'Tanggal'];
    }

    public function map($order): array
    {
        return [
            $order->id,
            $order->user->name ?? '-',
            $order->user->email ?? '-',
            $order->quantity,
            $order->total_price,
            ucfirst($order->status_payment),
            $order->created_at->format('d M Y H:i'),
        ];
    }

    public function styles(Worksheet $sheet): array
    {
        foreach (range('A', 'G') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        return [1 => ['font' => ['bold' => true]]];
    }
}

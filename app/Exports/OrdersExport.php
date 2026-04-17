<?php

namespace App\Exports;

use App\Models\Order;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class OrdersExport implements FromQuery, WithHeadings, WithMapping, WithStyles
{
    public function __construct(private ?string $search = null, private ?string $status = null) {}

    public function query()
    {
        $query = Order::with(['user', 'event']);

        if ($this->status) {
            $query->where('status_payment', $this->status);
        }
        if ($this->search) {
            $query->whereHas('user', fn($q) => $q->where('name', 'like', "%{$this->search}%")
                ->orWhere('email', 'like', "%{$this->search}%"));
        }

        return $query->latest();
    }

    public function headings(): array
    {
        return ['ID', 'Pembeli', 'Email', 'Event', 'Qty', 'Total (Rp)', 'Status', 'Tanggal'];
    }

    public function map($order): array
    {
        return [
            $order->id,
            $order->user->name ?? '-',
            $order->user->email ?? '-',
            $order->event->name ?? '-',
            $order->quantity,
            $order->total_price,
            ucfirst($order->status_payment),
            $order->created_at->format('d M Y H:i'),
        ];
    }

    public function styles(Worksheet $sheet): array
    {
        foreach (range('A', 'H') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        return [1 => ['font' => ['bold' => true]]];
    }
}

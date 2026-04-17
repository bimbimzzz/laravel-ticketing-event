<?php

namespace App\Exports;

use App\Models\Order;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class RevenueReportTopEventsSheet implements FromCollection, WithHeadings, WithStyles, WithTitle
{
    public function __construct(private int $period = 30) {}

    public function title(): string
    {
        return 'Top Events';
    }

    public function collection()
    {
        return Order::where('orders.status_payment', 'success')
            ->where('orders.created_at', '>=', now()->subDays($this->period))
            ->join('events', 'orders.event_id', '=', 'events.id')
            ->join('vendors', 'events.vendor_id', '=', 'vendors.id')
            ->select(
                'events.name as event',
                'vendors.name as vendor',
                DB::raw('SUM(orders.total_price) as revenue'),
                DB::raw('SUM(orders.quantity) as tiket_terjual')
            )
            ->groupBy('events.id', 'events.name', 'vendors.name')
            ->orderByDesc('revenue')
            ->get()
            ->map(fn($row) => [
                'Event' => $row->event,
                'Vendor' => $row->vendor,
                'Revenue (Rp)' => $row->revenue,
                'Tiket Terjual' => $row->tiket_terjual,
            ]);
    }

    public function headings(): array
    {
        return ['Event', 'Vendor', 'Revenue (Rp)', 'Tiket Terjual'];
    }

    public function styles(Worksheet $sheet): array
    {
        foreach (range('A', 'D') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        return [1 => ['font' => ['bold' => true]]];
    }
}

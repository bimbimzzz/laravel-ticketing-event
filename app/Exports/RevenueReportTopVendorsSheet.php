<?php

namespace App\Exports;

use App\Models\Order;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class RevenueReportTopVendorsSheet implements FromCollection, WithHeadings, WithStyles, WithTitle
{
    public function __construct(private int $period = 30) {}

    public function title(): string
    {
        return 'Top Vendors';
    }

    public function collection()
    {
        return Order::where('orders.status_payment', 'success')
            ->where('orders.created_at', '>=', now()->subDays($this->period))
            ->join('events', 'orders.event_id', '=', 'events.id')
            ->join('vendors', 'events.vendor_id', '=', 'vendors.id')
            ->select(
                'vendors.name as vendor',
                DB::raw('SUM(orders.total_price) as revenue'),
                DB::raw('COUNT(DISTINCT events.id) as jumlah_event'),
                DB::raw('SUM(orders.quantity) as tiket_terjual')
            )
            ->groupBy('vendors.id', 'vendors.name')
            ->orderByDesc('revenue')
            ->get()
            ->map(fn($row) => [
                'Vendor' => $row->vendor,
                'Revenue (Rp)' => $row->revenue,
                'Jumlah Event' => $row->jumlah_event,
                'Tiket Terjual' => $row->tiket_terjual,
            ]);
    }

    public function headings(): array
    {
        return ['Vendor', 'Revenue (Rp)', 'Jumlah Event', 'Tiket Terjual'];
    }

    public function styles(Worksheet $sheet): array
    {
        foreach (range('A', 'D') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        return [1 => ['font' => ['bold' => true]]];
    }
}

<?php

namespace App\Exports;

use App\Models\Order;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class RevenueReportDailySheet implements FromCollection, WithHeadings, WithStyles, WithTitle
{
    public function __construct(private int $period = 30) {}

    public function title(): string
    {
        return 'Revenue Harian';
    }

    public function collection()
    {
        $data = Order::where('status_payment', 'success')
            ->where('created_at', '>=', now()->subDays($this->period))
            ->select(
                DB::raw('DATE(created_at) as tanggal'),
                DB::raw('SUM(total_price) as revenue'),
                DB::raw('COUNT(*) as jumlah_order')
            )
            ->groupBy('tanggal')
            ->orderBy('tanggal')
            ->get();

        return $data->map(fn($row) => [
            'Tanggal' => $row->tanggal,
            'Revenue (Rp)' => $row->revenue,
            'Jumlah Order' => $row->jumlah_order,
        ]);
    }

    public function headings(): array
    {
        return ['Tanggal', 'Revenue (Rp)', 'Jumlah Order'];
    }

    public function styles(Worksheet $sheet): array
    {
        foreach (range('A', 'C') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        return [1 => ['font' => ['bold' => true]]];
    }
}

<?php

namespace App\Exports;

use App\Models\Vendor;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class VendorsExport implements FromQuery, WithHeadings, WithMapping, WithStyles
{
    public function __construct(private ?string $search = null, private ?string $status = null) {}

    public function query()
    {
        $query = Vendor::with('user')->withCount('events');

        if ($this->status) {
            $query->where('verify_status', $this->status);
        }
        if ($this->search) {
            $query->where('name', 'like', "%{$this->search}%");
        }

        return $query->latest();
    }

    public function headings(): array
    {
        return ['ID', 'Nama Vendor', 'Pemilik', 'Email', 'Kota', 'Telepon', 'Status', 'Jumlah Event', 'Tgl Daftar'];
    }

    public function map($vendor): array
    {
        return [
            $vendor->id,
            $vendor->name,
            $vendor->user->name ?? '-',
            $vendor->user->email ?? '-',
            $vendor->city,
            $vendor->phone,
            ucfirst($vendor->verify_status),
            $vendor->events_count,
            $vendor->created_at->format('d M Y'),
        ];
    }

    public function styles(Worksheet $sheet): array
    {
        foreach (range('A', 'I') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        return [1 => ['font' => ['bold' => true]]];
    }
}

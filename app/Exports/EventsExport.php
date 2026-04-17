<?php

namespace App\Exports;

use App\Models\Event;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class EventsExport implements FromQuery, WithHeadings, WithMapping, WithStyles
{
    public function __construct(private ?string $search = null) {}

    public function query()
    {
        $query = Event::with(['vendor', 'eventCategory'])->withCount('skus');

        if ($this->search) {
            $query->where('name', 'like', "%{$this->search}%");
        }

        return $query->latest();
    }

    public function headings(): array
    {
        return ['ID', 'Nama Event', 'Vendor', 'Kategori', 'Tgl Mulai', 'Tgl Selesai', 'Jumlah SKU'];
    }

    public function map($event): array
    {
        return [
            $event->id,
            $event->name,
            $event->vendor->name ?? '-',
            $event->eventCategory->name ?? '-',
            $event->start_date ? \Carbon\Carbon::parse($event->start_date)->format('d M Y') : '-',
            $event->end_date ? \Carbon\Carbon::parse($event->end_date)->format('d M Y') : '-',
            $event->skus_count,
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

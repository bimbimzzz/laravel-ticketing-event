<?php

namespace App\Exports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class UsersExport implements FromQuery, WithHeadings, WithMapping, WithStyles
{
    public function __construct(private ?string $search = null) {}

    public function query()
    {
        $query = User::withCount('orders');

        if ($this->search) {
            $query->where(fn($q) => $q->where('name', 'like', "%{$this->search}%")
                ->orWhere('email', 'like', "%{$this->search}%"));
        }

        return $query->latest();
    }

    public function headings(): array
    {
        return ['ID', 'Nama', 'Email', 'Telepon', 'Role', 'Jumlah Order', 'Tgl Daftar'];
    }

    public function map($user): array
    {
        $role = str_ends_with($user->email, '@admin.com') ? 'Admin' : ($user->is_vendor ? 'Vendor' : 'Buyer');

        return [
            $user->id,
            $user->name,
            $user->email,
            $user->phone ?? '-',
            $role,
            $user->orders_count,
            $user->created_at->format('d M Y'),
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

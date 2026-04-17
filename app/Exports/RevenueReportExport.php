<?php

namespace App\Exports;

use App\Models\Order;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class RevenueReportExport implements WithMultipleSheets
{
    public function __construct(private int $period = 30) {}

    public function sheets(): array
    {
        return [
            'Revenue Harian' => new RevenueReportDailySheet($this->period),
            'Top Events' => new RevenueReportTopEventsSheet($this->period),
            'Top Vendors' => new RevenueReportTopVendorsSheet($this->period),
        ];
    }
}

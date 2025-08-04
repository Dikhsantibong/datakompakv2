<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithDrawings;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use Illuminate\Support\Facades\Auth;

class DailySummaryExport implements WithMultipleSheets
{
    protected $date;
    protected $units;

    public function __construct($date, $units)
    {
        $this->date = $date;
        $this->units = $units;
    }

    public function sheets(): array
    {
        $sheets = [];
        foreach ($this->units as $unit) {
            $sheets[] = new DailySummaryUnitSheetExport($this->date, $unit);
        }
        return $sheets;
    }
} 
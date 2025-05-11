<?php

namespace App\Exports;

use App\Models\AbnormalReport;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Events\AfterSheet;

class AbnormalReportExport implements WithMultipleSheets
{
    use Exportable;

    protected $report;

    public function __construct(AbnormalReport $report)
    {
        $this->report = $report;
    }

    public function sheets(): array
    {
        return [
            new AbnormalReportMainSheet($this->report),
            new AbnormalReportEvidenceSheet($this->report),
        ];
    }
} 
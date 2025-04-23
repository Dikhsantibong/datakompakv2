<?php

namespace App\Exports;

use App\Models\AbnormalReport;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;

class AbnormalReportExport implements FromView
{
    protected $report;

    public function __construct(AbnormalReport $report)
    {
        $this->report = $report;
    }

    public function view(): View
    {
        return view('admin.abnormal-report.excel', [
            'report' => $this->report
        ]);
    }
} 
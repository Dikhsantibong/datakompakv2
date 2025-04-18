<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Barryvdh\DomPDF\Facade\Pdf;

class CalendarPDFExport
{
    protected $schedules;

    public function __construct($schedules)
    {
        $this->schedules = $schedules;
    }

    public function download()
    {
        $pdf = PDF::loadView('exports.calendar-pdf', [
            'schedules' => $this->schedules
        ]);

        return $pdf->download('kalender-operasi.pdf');
    }
} 
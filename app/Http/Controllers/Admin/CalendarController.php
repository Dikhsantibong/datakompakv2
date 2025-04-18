<?php

namespace App\Http\Controllers\Admin;

use App\Exports\CalendarExport;
use App\Exports\CalendarPDFExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Http\Controllers\Controller;
use App\Models\Schedule;

class CalendarController extends Controller
{
    public function exportExcel()
    {
        $schedules = Schedule::with(['creator'])->get()->map(function ($schedule) {
            return [
                'date' => $schedule->schedule_date,
                'title' => $schedule->title,
                'start_time' => $schedule->start_time,
                'end_time' => $schedule->end_time,
                'location' => $schedule->location,
                'description' => $schedule->description,
                'status' => $schedule->status,
                'participants' => is_array($schedule->participants) ? implode(', ', $schedule->participants) : ''
            ];
        });

        return Excel::download(new CalendarExport($schedules), 'kalender-operasi.xlsx');
    }

    public function exportPDF()
    {
        $schedules = Schedule::with(['creator'])->get()->map(function ($schedule) {
            return [
                'date' => $schedule->schedule_date,
                'title' => $schedule->title,
                'start_time' => $schedule->start_time,
                'end_time' => $schedule->end_time,
                'location' => $schedule->location,
                'description' => $schedule->description,
                'status' => $schedule->status,
                'participants' => is_array($schedule->participants) ? implode(', ', $schedule->participants) : ''
            ];
        });

        $exporter = new CalendarPDFExport($schedules);
        return $exporter->download();
    }
} 
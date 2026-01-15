<?php

namespace App\Http\Controllers\Admin;

use App\Exports\CalendarExport;
use App\Exports\CalendarPDFExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Http\Controllers\Controller;
use App\Models\OperationSchedule;

class CalendarController extends Controller
{
    public function exportExcel()
    {
        $schedules = OperationSchedule::with(['creator'])
            ->orderBy('schedule_date', 'asc')
            ->orderBy('start_time', 'asc')
            ->get()
            ->map(function ($schedule) {
                return [
                    'date' => $schedule->schedule_date,
                    'title' => $schedule->title,
                    'start_time' => $schedule->start_time ? $schedule->start_time->format('H:i') : '-',
                    'end_time' => $schedule->end_time ? $schedule->end_time->format('H:i') : '-',
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
        $schedules = OperationSchedule::with(['creator'])
            ->orderBy('schedule_date', 'asc')
            ->orderBy('start_time', 'asc')
            ->get()
            ->map(function ($schedule) {
                return [
                    'date' => $schedule->schedule_date,
                    'title' => $schedule->title,
                    'start_time' => $schedule->start_time ? $schedule->start_time->format('H:i') : '-',
                    'end_time' => $schedule->end_time ? $schedule->end_time->format('H:i') : '-',
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
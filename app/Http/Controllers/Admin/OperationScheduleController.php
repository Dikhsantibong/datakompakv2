<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\OperationSchedule;
use Illuminate\Http\Request;
use Carbon\Carbon;

class OperationScheduleController extends Controller
{
    public function index(Request $request)
    {
        $today = Carbon::today();
        
        // Filter berdasarkan tanggal jika ada
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        
        $query = OperationSchedule::query();
        
        if ($startDate) {
            $query->whereDate('schedule_date', '>=', $startDate);
        }
        
        if ($endDate) {
            $query->whereDate('schedule_date', '<=', $endDate);
        }
        
        // Jika tidak ada filter, ambil semua
        $allSchedules = $query->get()
            ->groupBy(function($schedule) {
                return $schedule->schedule_date->format('Y-m-d');
            });
        
        // Schedules hari ini untuk sidebar
        $schedules = OperationSchedule::whereDate('schedule_date', $today)
            ->orderBy('start_time')
            ->get();

        return view('admin.kalender.calendar', compact('schedules', 'allSchedules', 'startDate', 'endDate'));
    }

    public function create()
    {
        return view('admin.kalender.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'schedule_date' => 'required|date',
            'start_time' => 'nullable',
            'end_time' => 'nullable|after:start_time',
            'location' => 'nullable|string|max:255',
            'participants' => 'nullable|string',
        ]);

        $validated['created_by'] = auth()->id();
        if ($validated['participants']) {
            $validated['participants'] = explode(',', $validated['participants']);
        }

        OperationSchedule::create($validated);

        return redirect()->route('admin.kalender.calendar')
            ->with('success', 'Jadwal berhasil ditambahkan');
    }

    public function getSchedulesByDate($date)
    {
        $schedules = OperationSchedule::whereDate('schedule_date', $date)
            ->orderBy('start_time')
            ->get();

        return response()->json($schedules);
    }

    public function edit(OperationSchedule $schedule)
    {
        return view('admin.kalender.edit', compact('schedule'));
    }

    public function update(Request $request, OperationSchedule $schedule)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'schedule_date' => 'required|date',
            'start_time' => 'nullable',
            'end_time' => 'nullable|after:start_time',
            'location' => 'nullable|string|max:255',
            'participants' => 'nullable|string',
            'status' => 'required|in:scheduled,completed,cancelled'
        ]);

        if ($validated['participants']) {
            $validated['participants'] = explode(',', $validated['participants']);
        }

        $schedule->update($validated);

        return redirect()->route('admin.kalender.calendar')
            ->with('success', 'Jadwal berhasil diperbarui');
    }

    public function destroy(OperationSchedule $schedule)
    {
        $schedule->delete();
        return redirect()->route('admin.kalender.calendar')
            ->with('success', 'Jadwal berhasil dihapus');
    }

    public function getAllSchedules()
    {
        $schedules = OperationSchedule::with('creator')
            ->orderBy('schedule_date', 'desc')
            ->orderBy('start_time', 'asc')
            ->get()
            ->map(function($schedule) {
                return [
                    'id' => $schedule->id,
                    'title' => $schedule->title,
                    'description' => $schedule->description,
                    'schedule_date' => $schedule->schedule_date->format('Y-m-d'),
                    'schedule_date_formatted' => $schedule->schedule_date->format('d M Y'),
                    'start_time' => $schedule->start_time ? $schedule->start_time->format('H:i') : '-',
                    'end_time' => $schedule->end_time ? $schedule->end_time->format('H:i') : '-',
                    'location' => $schedule->location,
                    'status' => $schedule->status,
                    'participants' => is_array($schedule->participants) ? $schedule->participants : [],
                    'created_by' => $schedule->creator ? $schedule->creator->name : '-',
                ];
            });

        return response()->json($schedules);
    }
}

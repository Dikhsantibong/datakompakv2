<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\OperationSchedule;
use Illuminate\Http\Request;
use Carbon\Carbon;

class OperationScheduleController extends Controller
{
    public function index()
    {
        $today = Carbon::today();
        $schedules = OperationSchedule::whereDate('schedule_date', $today)
            ->orderBy('start_time')
            ->get();
            
        $allSchedules = OperationSchedule::all()
            ->groupBy(function($schedule) {
                return $schedule->schedule_date->format('Y-m-d');
            });

        return view('admin.kalender.calendar', compact('schedules', 'allSchedules'));
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
            'start_time' => 'required',
            'end_time' => 'required|after:start_time',
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
            'start_time' => 'required',
            'end_time' => 'required|after:start_time',
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
}

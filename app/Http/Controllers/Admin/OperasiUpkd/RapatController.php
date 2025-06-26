<?php

namespace App\Http\Controllers\Admin\OperasiUpkd;

use App\Http\Controllers\Controller;
use App\Models\Meeting;
use Illuminate\Http\Request;

class RapatController extends Controller
{
    public function index(Request $request)
    {
        $query = Meeting::query();

        // Filter by PIC
        if ($request->filled('pic')) {
            $query->where('pic', $request->pic);
        }

        // Filter by Status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by Date Range
        if ($request->filled('start_date')) {
            $query->where('deadline_start', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $query->where('deadline_finish', '<=', $request->end_date);
        }

        $meetings = $query->latest()->get();
        return view('admin.operasi-upkd.rapat.index', compact('meetings'));
    }

    public function create()
    {
        return view('admin.operasi-upkd.rapat.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'pekerjaan' => 'required|string',
            'pic' => 'required|string',
            'deadline_start' => 'required|date',
            'deadline_finish' => 'required|date|after_or_equal:deadline_start',
            'kondisi' => 'required|string',
            'status' => 'required|in:open,on progress,closed',
        ]);

        Meeting::create($validated);

        return redirect()
            ->route('admin.operasi-upkd.rapat.index')
            ->with('success', 'Data rapat berhasil ditambahkan');
    }

    public function edit(Meeting $rapat)
    {
        return view('admin.operasi-upkd.rapat.edit', compact('rapat'));
    }

    public function update(Request $request, Meeting $rapat)
    {
        $validated = $request->validate([
            'pekerjaan' => 'required|string',
            'pic' => 'required|string',
            'deadline_start' => 'required|date',
            'deadline_finish' => 'required|date|after_or_equal:deadline_start',
            'kondisi' => 'required|string',
            'status' => 'required|in:open,on progress,closed',
        ]);

        $rapat->update($validated);

        return redirect()
            ->route('admin.operasi-upkd.rapat.index')
            ->with('success', 'Data rapat berhasil diperbarui');
    }

    public function destroy(Meeting $rapat)
    {
        $rapat->delete();

        return redirect()
            ->route('admin.operasi-upkd.rapat.index')
            ->with('success', 'Data rapat berhasil dihapus');
    }
}

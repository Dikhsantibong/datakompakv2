<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Machine;

class MeetingShiftController extends Controller
{
    public function index()
    {
        $machines = Machine::select('id', 'name')->orderBy('name')->get();
        return view('admin.meeting-shift.index', compact('machines'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'machine_status' => 'required|array',
            'machine_status.*.machine_id' => 'required|exists:machines,id',
            'machine_status.*.status' => 'required|in:operasi,standby,har_rutin,har_nonrutin,gangguan',
            'machine_status.*.keterangan' => 'nullable|string'
        ]);

        // Process the data here
        // You can store it in a new model for meeting records

        return redirect()->back()->with('success', 'Status mesin berhasil disimpan');
    }

    public function storeAlatBantu(Request $request)
    {
        $validated = $request->validate([
            'alat_bantu' => 'required|array',
            'alat_bantu.*.name' => 'required|string',
            'alat_bantu.*.status' => 'required|in:normal,abnormal,gangguan,flm',
            'alat_bantu.*.keterangan' => 'nullable|string'
        ]);

        // Process the data here
        // You can store it in a new model for meeting records

        return redirect()->back()->with('success', 'Status alat bantu berhasil disimpan');
    }
} 
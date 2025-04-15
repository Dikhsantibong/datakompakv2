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

    public function storeResource(Request $request)
    {
        $validated = $request->validate([
            'resources' => 'required|array',
            'resources.*.name' => 'required|string',
            'resources.*.status' => 'required|in:0-20,21-40,41-61,61-80,up-80',
            'resources.*.keterangan' => 'nullable|string'
        ]);

        // Process the data here
        // You can store it in a new model for meeting records

        return redirect()->back()->with('success', 'Status resource berhasil disimpan');
    }

    public function storeSistem(Request $request)
    {
        $validated = $request->validate([
            'catatan_sistem' => 'required|string'
        ]);

        // Process the data here
        // You can store it in a new model for meeting records

        return redirect()->back()->with('success', 'Catatan kondisi sistem berhasil disimpan');
    }

    public function storeCatatanUmum(Request $request)
    {
        $validated = $request->validate([
            'catatan_umum' => 'required|string'
        ]);

        // Process the data here
        // You can store it in a new model for meeting records

        return redirect()->back()->with('success', 'Catatan umum berhasil disimpan');
    }

    public function storeAbsensi(Request $request)
    {
        $validated = $request->validate([
            'absensi' => 'required|array',
            'absensi.*.nama' => 'required|string',
            'absensi.*.shift' => 'required|in:pagi,sore,malam'
        ]);

        // Process the data here
        // You can store it in a new model for meeting records

        return redirect()->back()->with('success', 'Data absensi berhasil disimpan');
    }
} 
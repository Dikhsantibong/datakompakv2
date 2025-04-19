<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Machine;
use App\Models\MeetingShift;
use App\Models\MeetingShiftMachineStatus;
use App\Models\MeetingShiftAuxiliaryEquipment;
use App\Models\MeetingShiftResource;
use App\Models\MeetingShiftK3l;
use App\Models\MeetingShiftNote;
use App\Models\MeetingShiftResume;
use App\Models\MeetingShiftAttendance;
use Illuminate\Support\Facades\DB;

class MeetingShiftController extends Controller
{
    public function index()
    {
        $machines = Machine::select('id', 'name')->orderBy('name')->get();
        $latestMeeting = MeetingShift::with([
            'machineStatuses',
            'auxiliaryEquipments',
            'resources',
            'k3ls',
            'notes',
            'resume',
            'attendances'
        ])->latest()->first();

        return view('admin.meeting-shift.index', compact('machines', 'latestMeeting'));
    }

    public function list()
    {
        $meetingShifts = MeetingShift::with([
            'machineStatuses',
            'auxiliaryEquipments',
            'resources',
            'k3ls',
            'notes',
            'resume',
            'attendances',
            'creator'
        ])
        ->latest()
        ->paginate(10);

        return view('admin.meeting-shift.list', compact('meetingShifts'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'machine_status' => 'required|array',
            'machine_status.*.machine_id' => 'required|exists:machines,id',
            'machine_status.*.status' => 'required|in:operasi,standby,har_rutin,har_nonrutin,gangguan',
            'machine_status.*.keterangan' => 'nullable|string'
        ]);

        DB::beginTransaction();
        try {
            $meetingShift = MeetingShift::create([
                'date' => now(),
                'shift' => $request->input('shift', 'pagi')
            ]);

            foreach ($validated['machine_status'] as $status) {
                MeetingShiftMachineStatus::create([
                    'meeting_shift_id' => $meetingShift->id,
                    'machine_id' => $status['machine_id'],
                    'status' => $status['status'],
                    'keterangan' => $status['keterangan'] ?? null
                ]);
            }

            DB::commit();
            return redirect()->back()->with('success', 'Status mesin berhasil disimpan');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Terjadi kesalahan saat menyimpan status mesin');
        }
    }

    public function storeAlatBantu(Request $request)
    {
        $validated = $request->validate([
            'alat_bantu' => 'required|array',
            'alat_bantu.*.name' => 'required|string',
            'alat_bantu.*.status' => 'required|in:normal,abnormal,gangguan,flm',
            'alat_bantu.*.keterangan' => 'nullable|string'
        ]);

        DB::beginTransaction();
        try {
            $meetingShift = MeetingShift::latest()->firstOrCreate([
                'date' => now(),
                'shift' => $request->input('shift', 'pagi')
            ]);

            foreach ($validated['alat_bantu'] as $equipment) {
                MeetingShiftAuxiliaryEquipment::create([
                    'meeting_shift_id' => $meetingShift->id,
                    'name' => $equipment['name'],
                    'status' => $equipment['status'],
                    'keterangan' => $equipment['keterangan'] ?? null
                ]);
            }

            DB::commit();
            return redirect()->back()->with('success', 'Status alat bantu berhasil disimpan');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Terjadi kesalahan saat menyimpan status alat bantu');
        }
    }

    public function storeResource(Request $request)
    {
        $validated = $request->validate([
            'resources' => 'required|array',
            'resources.*.name' => 'required|string',
            'resources.*.category' => 'required|string',
            'resources.*.status' => 'required|in:0-20,21-40,41-61,61-80,up-80',
            'resources.*.keterangan' => 'nullable|string'
        ]);

        DB::beginTransaction();
        try {
            $meetingShift = MeetingShift::latest()->firstOrCreate([
                'date' => now(),
                'shift' => $request->input('shift', 'pagi')
            ]);

            foreach ($validated['resources'] as $resource) {
                MeetingShiftResource::create([
                    'meeting_shift_id' => $meetingShift->id,
                    'name' => $resource['name'],
                    'category' => $resource['category'],
                    'status' => $resource['status'],
                    'keterangan' => $resource['keterangan'] ?? null
                ]);
            }

            DB::commit();
            return redirect()->back()->with('success', 'Status resource berhasil disimpan');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Terjadi kesalahan saat menyimpan status resource');
        }
    }

    public function storeK3l(Request $request)
    {
        $validated = $request->validate([
            'k3l' => 'required|array',
            'k3l.*.type' => 'required|string',
            'k3l.*.uraian' => 'required|string',
            'k3l.*.saran' => 'required|string',
            'k3l.*.eviden_path' => 'nullable|string'
        ]);

        DB::beginTransaction();
        try {
            $meetingShift = MeetingShift::latest()->firstOrCreate([
                'date' => now(),
                'shift' => $request->input('shift', 'pagi')
            ]);

            foreach ($validated['k3l'] as $k3l) {
                MeetingShiftK3l::create([
                    'meeting_shift_id' => $meetingShift->id,
                    'type' => $k3l['type'],
                    'uraian' => $k3l['uraian'],
                    'saran' => $k3l['saran'],
                    'eviden_path' => $k3l['eviden_path'] ?? null
                ]);
            }

            DB::commit();
            return redirect()->back()->with('success', 'Data K3L berhasil disimpan');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Terjadi kesalahan saat menyimpan data K3L');
        }
    }

    public function storeSistem(Request $request)
    {
        $validated = $request->validate([
            'catatan_sistem' => 'required|string'
        ]);

        DB::beginTransaction();
        try {
            $meetingShift = MeetingShift::latest()->firstOrCreate([
                'date' => now(),
                'shift' => $request->input('shift', 'pagi')
            ]);

            MeetingShiftNote::create([
                'meeting_shift_id' => $meetingShift->id,
                'type' => 'sistem',
                'content' => $validated['catatan_sistem']
            ]);

            DB::commit();
            return redirect()->back()->with('success', 'Catatan kondisi sistem berhasil disimpan');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Terjadi kesalahan saat menyimpan catatan sistem');
        }
    }

    public function storeCatatanUmum(Request $request)
    {
        $validated = $request->validate([
            'catatan_umum' => 'required|string'
        ]);

        DB::beginTransaction();
        try {
            $meetingShift = MeetingShift::latest()->firstOrCreate([
                'date' => now(),
                'shift' => $request->input('shift', 'pagi')
            ]);

            MeetingShiftNote::create([
                'meeting_shift_id' => $meetingShift->id,
                'type' => 'umum',
                'content' => $validated['catatan_umum']
            ]);

            DB::commit();
            return redirect()->back()->with('success', 'Catatan umum berhasil disimpan');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Terjadi kesalahan saat menyimpan catatan umum');
        }
    }

    public function storeResume(Request $request)
    {
        $validated = $request->validate([
            'resume' => 'required|string'
        ]);

        DB::beginTransaction();
        try {
            $meetingShift = MeetingShift::latest()->firstOrCreate([
                'date' => now(),
                'shift' => $request->input('shift', 'pagi')
            ]);

            MeetingShiftResume::create([
                'meeting_shift_id' => $meetingShift->id,
                'content' => $validated['resume']
            ]);

            DB::commit();
            return redirect()->back()->with('success', 'Resume rapat berhasil disimpan');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Terjadi kesalahan saat menyimpan resume rapat');
        }
    }

    public function storeAbsensi(Request $request)
    {
        $validated = $request->validate([
            'absensi' => 'required|array',
            'absensi.*.nama' => 'required|string',
            'absensi.*.shift' => 'required|in:A,B,C,D',
            'absensi.*.status' => 'required|string',
            'absensi.*.keterangan' => 'nullable|string'
        ]);

        DB::beginTransaction();
        try {
            $meetingShift = MeetingShift::latest()->firstOrCreate([
                'date' => now(),
                'shift' => $request->input('shift', 'pagi')
            ]);

            foreach ($validated['absensi'] as $attendance) {
                MeetingShiftAttendance::create([
                    'meeting_shift_id' => $meetingShift->id,
                    'nama' => $attendance['nama'],
                    'shift' => $attendance['shift'],
                    'status' => $attendance['status'],
                    'keterangan' => $attendance['keterangan'] ?? null
                ]);
            }

            DB::commit();
            return redirect()->back()->with('success', 'Data absensi berhasil disimpan');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Terjadi kesalahan saat menyimpan data absensi');
        }
    }
} 
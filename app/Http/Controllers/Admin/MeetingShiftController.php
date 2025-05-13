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
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\MeetingShiftExport;

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
        $query = MeetingShift::with([
            'machineStatuses',
            'auxiliaryEquipments',
            'resources',
            'k3ls',
            'notes',
            'resume',
            'attendances',
            'creator'
        ]);

        // Apply shift filter
        if (request()->has('shift') && request('shift') !== '') {
            $query->where('current_shift', request('shift'));
        }

        // Apply date range filter
        if (request()->has('start_date') && request('start_date') !== '') {
            $query->whereDate('tanggal', '>=', request('start_date'));
        }
        if (request()->has('end_date') && request('end_date') !== '') {
            $query->whereDate('tanggal', '<=', request('end_date'));
        }

        $meetingShifts = $query->latest()->paginate(10);

        return view('admin.meeting-shift.list', compact('meetingShifts'));
    }

    public function store(Request $request)
    {
        // Hapus field k3l.*.eviden yang kosong/null dari request agar validasi tidak error
        if ($request->has('k3l')) {
            foreach ($request->k3l as $index => $k3l) {
                if (empty($request->file("k3l.$index.eviden"))) {
                    $request->request->remove("k3l.$index.eviden");
                }
            }
        }

        try {
            // Validate all form inputs
            $validated = $request->validate([
                'current_shift' => 'required|in:A,B,C,D',
                'tanggal' => 'required|date',
                
                // Machine Statuses
                'machine_statuses' => 'required|array',
                'machine_statuses.*.machine_id' => 'required|exists:machines,id',
                'machine_statuses.*.status' => 'required|array|min:1',
                'machine_statuses.*.keterangan' => 'nullable|string',
                
                // Auxiliary Equipment
                'auxiliary_equipment' => 'required|array',
                'auxiliary_equipment.*.name' => 'required|string',
                'auxiliary_equipment.*.status' => 'required|array|min:1',
                'auxiliary_equipment.*.keterangan' => 'nullable|string',
                
                // Resources
                'resources' => 'required|array',
                'resources.*.name' => 'required|string',
                'resources.*.category' => 'required|in:PELUMAS,BBM,AIR PENDINGIN,UDARA START',
                'resources.*.status' => 'required|in:0-20,21-40,41-61,61-80,up-80',
                'resources.*.keterangan' => 'nullable|string',
                
                // K3L
                'k3l' => 'required|array',
                'k3l.*.type' => 'required|in:unsafe_action,unsafe_condition',
                'k3l.*.uraian' => 'required|string',
                'k3l.*.saran' => 'required|string',
                
                // Notes
                'catatan_sistem' => 'required|string',
                'catatan_umum' => 'required|string',
                
                // Resume
                'resume' => 'required|string',
                
                // Attendance
                'absensi' => 'required|array',
                'absensi.*.nama' => 'required|string',
                'absensi.*.shift' => 'required|in:A,B,C,D,staf ops,TL OP,TL HAR,TL OPHAR,MUL',
                'absensi.*.status' => 'required|in:hadir,izin,sakit,cuti,alpha,terlambat,ganti shift',
                'absensi.*.keterangan' => 'nullable|string',
            ]);

            DB::beginTransaction();

            // Create main meeting shift record first and make sure it's saved
            $meetingShift = new MeetingShift();
            $meetingShift->tanggal = $validated['tanggal'];
            $meetingShift->current_shift = $validated['current_shift'];
            $meetingShift->created_by = Auth::user()->getAuthIdentifier();
            $meetingShift->save();

            // Log the created meeting shift
            Log::info('Created meeting shift', ['id' => $meetingShift->id]);

            // Store machine statuses
            foreach ($validated['machine_statuses'] as $machineStatus) {
                if (!empty($machineStatus['status'])) {
                    try {
                        $status = new MeetingShiftMachineStatus([
                            'meeting_shift_id' => $meetingShift->id,
                            'machine_id' => $machineStatus['machine_id'],
                            'status' => json_encode($machineStatus['status']),
                            'keterangan' => $machineStatus['keterangan'] ?? null
                        ]);
                        $status->save();
                        
                        Log::info('Created machine status', [
                            'meeting_shift_id' => $meetingShift->id,
                            'machine_id' => $machineStatus['machine_id']
                        ]);
                    } catch (\Exception $e) {
                        Log::error('Failed to create machine status', [
                            'meeting_shift_id' => $meetingShift->id,
                            'machine_id' => $machineStatus['machine_id'],
                            'error' => $e->getMessage()
                        ]);
                        throw $e;
                    }
                }
            }

            // Store auxiliary equipment
            if (!empty($validated['auxiliary_equipment'])) {
                foreach ($validated['auxiliary_equipment'] as $equipment) {
                    if (!empty($equipment['status'])) {
                        MeetingShiftAuxiliaryEquipment::create([
                            'meeting_shift_id' => $meetingShift->id,
                            'name' => $equipment['name'],
                            'status' => json_encode($equipment['status']),
                            'keterangan' => $equipment['keterangan'] ?? null
                        ]);
                    }
                }
            }

            // Store resources
            if (!empty($validated['resources'])) {
                foreach ($validated['resources'] as $resource) {
                    MeetingShiftResource::create([
                        'meeting_shift_id' => $meetingShift->id,
                        'name' => $resource['name'],
                        'category' => $resource['category'],
                        'status' => (string) $resource['status'],
                        'keterangan' => $resource['keterangan'] ?? null
                    ]);
                }
            }

            // Store K3L records
            if (!empty($validated['k3l'])) {
                foreach ($validated['k3l'] as $index => $k3l) {
                    $evidenPath = null;
                    if ($request->hasFile("k3l.{$index}.eviden")) {
                        $file = $request->file("k3l.{$index}.eviden");
                        $evidenPath = $file->store('eviden/k3l', 'public');
                    }

                    MeetingShiftK3l::create([
                        'meeting_shift_id' => $meetingShift->id,
                        'type' => (string) $k3l['type'],
                        'uraian' => $k3l['uraian'],
                        'saran' => $k3l['saran'],
                        'eviden_path' => $evidenPath
                    ]);
                }
            }

            // Store system note
            MeetingShiftNote::create([
                'meeting_shift_id' => $meetingShift->id,
                'type' => 'sistem',
                'content' => $validated['catatan_sistem']
            ]);

            // Store general note
            MeetingShiftNote::create([
                'meeting_shift_id' => $meetingShift->id,
                'type' => 'umum',
                'content' => $validated['catatan_umum']
            ]);

            // Store resume
            if (!empty($validated['resume'])) {
                MeetingShiftResume::create([
                    'meeting_shift_id' => $meetingShift->id,
                    'content' => trim($validated['resume'])
                ]);
            }

            // Store attendance records
            if (!empty($validated['absensi'])) {
                foreach ($validated['absensi'] as $attendance) {
                    MeetingShiftAttendance::create([
                        'meeting_shift_id' => $meetingShift->id,
                        'nama' => trim($attendance['nama']),
                        'shift' => (string) $attendance['shift'],
                        'status' => (string) $attendance['status'],
                        'keterangan' => !empty($attendance['keterangan']) ? trim($attendance['keterangan']) : null
                    ]);
                }
            }

            DB::commit();
            
            Log::info('Meeting shift created successfully', ['meeting_shift_id' => $meetingShift->id]);
            
            return redirect()->route('admin.meeting-shift.show', $meetingShift->id)
                           ->with('success', 'Data meeting shift berhasil disimpan');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error saving meeting shift: ' . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return redirect()->back()
                           ->with('error', 'Terjadi kesalahan saat menyimpan data meeting shift: ' . $e->getMessage())
                           ->withInput();
        }
    }

    public function show($id)
    {
        /** @var \App\Models\MeetingShift $meetingShift */
        $meetingShift = MeetingShift::with([
            'machineStatuses.machine',
            'auxiliaryEquipments',
            'resources',
            'k3ls',
            'systemNote',
            'generalNote',
            'resume',
            'attendances',
            'creator'
        ])->findOrFail($id);

        return view('admin.meeting-shift.show', compact('meetingShift'));
    }

    public function storeAlatBantu(Request $request)
    {
        $validated = $request->validate([
            'auxiliary_equipment' => 'required|array',
            'auxiliary_equipment.*.name' => 'required|string',
            'auxiliary_equipment.*.status' => 'required|array',
            'auxiliary_equipment.*.keterangan' => 'nullable|string'
        ]);

        DB::beginTransaction();
        try {
            /** @var \App\Models\MeetingShift $meetingShift */
            $meetingShift = MeetingShift::latest()->firstOrCreate(
                [
                    'tanggal' => now()->toDateString(),
                    'current_shift' => $request->input('current_shift', 'A')
                ],
                [
                    'created_by' => auth()->id()
                ]
            );

            foreach ($validated['auxiliary_equipment'] as $equipment) {
                MeetingShiftAuxiliaryEquipment::create([
                    'meeting_shift_id' => $meetingShift->id,
                    'name' => $equipment['name'],
                    'status' => json_encode($equipment['status']),
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
            'absensi.*.shift' => 'required|in:A,B,C,D,staf ops,TL OP,TL HAR,TL OPHAR,MUL',
            'absensi.*.status' => 'required|in:hadir,izin,sakit,cuti,alpha,terlambat,ganti shift',
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

    public function downloadPdf(MeetingShift $meetingShift)
    {
        try {
            $meetingShift->load([
                'machineStatuses.machine',
                'auxiliaryEquipments',
                'resources',
                'k3ls',
                'notes',
                'resume',
                'attendances',
                'creator'
            ]);

            $pdf = PDF::loadView('admin.meeting-shift.pdf', compact('meetingShift'));
            
            return $pdf->download('meeting-shift-' . $meetingShift->tanggal->format('Y-m-d') . '-shift-' . $meetingShift->current_shift . '.pdf');
        } catch (\Exception $e) {
            Log::error('Error generating PDF: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan saat mengunduh PDF');
        }
    }

    public function export(MeetingShift $meetingShift)
    {
        return view('admin.meeting-shift.export', compact('meetingShift'));
    }

    public function downloadExcel(Request $request, MeetingShift $meetingShift)
    {
        try {
            $meetingShift->load([
                'machineStatuses.machine',
                'auxiliaryEquipments',
                'resources',
                'k3ls',
                'systemNote',
                'generalNote',
                'resume',
                'attendances',
                'creator'
            ]);

            $filename = 'meeting-shift-' . $meetingShift->tanggal->format('Y-m-d') . '-shift-' . $meetingShift->current_shift;
            
            return Excel::download(
                new MeetingShiftExport($meetingShift),
                $filename . '.xlsx'
            );
        } catch (\Exception $e) {
            Log::error('Error generating Excel: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan saat mengunduh Excel');
        }
    }

    public function edit($id)
    {
        $meetingShift = MeetingShift::with([
            'machineStatuses.machine',
            'auxiliaryEquipments',
            'resources',
            'k3ls',
            'systemNote',
            'generalNote',
            'resume',
            'attendances',
            'creator'
        ])->findOrFail($id);

        $machines = Machine::select('id', 'name')->orderBy('name')->get();

        return view('admin.meeting-shift.edit', compact('meetingShift', 'machines'));
    }

    public function update(Request $request, $id)
    {
        try {
            // Validate all form inputs
            $validated = $request->validate([
                'current_shift' => 'required|in:A,B,C,D',
                'tanggal' => 'required|date',
                
                // Machine Statuses
                'machine_statuses' => 'required|array',
                'machine_statuses.*.machine_id' => 'required|exists:machines,id',
                'machine_statuses.*.status' => 'required|array|min:1',
                'machine_statuses.*.keterangan' => 'nullable|string',
                
                // Auxiliary Equipment
                'auxiliary_equipment' => 'required|array',
                'auxiliary_equipment.*.name' => 'required|string',
                'auxiliary_equipment.*.status' => 'required|array|min:1',
                'auxiliary_equipment.*.keterangan' => 'nullable|string',
                
                // Resources
                'resources' => 'required|array',
                'resources.*.name' => 'required|string',
                'resources.*.category' => 'required|in:PELUMAS,BBM,AIR PENDINGIN,UDARA START',
                'resources.*.status' => 'required|in:0-20,21-40,41-61,61-80,up-80',
                'resources.*.keterangan' => 'nullable|string',
                
                // K3L
                'k3l' => 'required|array',
                'k3l.*.type' => 'required|in:unsafe_action,unsafe_condition',
                'k3l.*.uraian' => 'required|string',
                'k3l.*.saran' => 'required|string',
                
                // Notes
                'catatan_sistem' => 'required|string',
                'catatan_umum' => 'required|string',
                
                // Resume
                'resume' => 'required|string',
                
                // Attendance
                'absensi' => 'required|array',
                'absensi.*.nama' => 'required|string',
                'absensi.*.shift' => 'required|in:A,B,C,D,staf ops,TL OP,TL HAR,TL OPHAR,MUL',
                'absensi.*.status' => 'required|in:hadir,izin,sakit,cuti,alpha,terlambat,ganti shift',
                'absensi.*.keterangan' => 'nullable|string',
            ]);

            DB::beginTransaction();

            $meetingShift = MeetingShift::findOrFail($id);
            
            // Update main meeting shift record
            $meetingShift->update([
                'tanggal' => $validated['tanggal'],
                'current_shift' => $validated['current_shift']
            ]);

            // Update machine statuses
            $meetingShift->machineStatuses()->delete();
            foreach ($validated['machine_statuses'] as $machineStatus) {
                if (!empty($machineStatus['status'])) {
                    MeetingShiftMachineStatus::create([
                        'meeting_shift_id' => $meetingShift->id,
                        'machine_id' => $machineStatus['machine_id'],
                        'status' => json_encode($machineStatus['status']),
                        'keterangan' => $machineStatus['keterangan'] ?? null
                    ]);
                }
            }

            // Update auxiliary equipment
            $meetingShift->auxiliaryEquipments()->delete();
            if (!empty($validated['auxiliary_equipment'])) {
                foreach ($validated['auxiliary_equipment'] as $equipment) {
                    if (!empty($equipment['status'])) {
                        MeetingShiftAuxiliaryEquipment::create([
                            'meeting_shift_id' => $meetingShift->id,
                            'name' => $equipment['name'],
                            'status' => json_encode($equipment['status']),
                            'keterangan' => $equipment['keterangan'] ?? null
                        ]);
                    }
                }
            }

            // Update resources
            $meetingShift->resources()->delete();
            if (!empty($validated['resources'])) {
                foreach ($validated['resources'] as $resource) {
                    MeetingShiftResource::create([
                        'meeting_shift_id' => $meetingShift->id,
                        'name' => $resource['name'],
                        'category' => $resource['category'],
                        'status' => (string) $resource['status'],
                        'keterangan' => $resource['keterangan'] ?? null
                    ]);
                }
            }

            // Update K3L records
            $meetingShift->k3ls()->delete();
            if (!empty($validated['k3l'])) {
                foreach ($validated['k3l'] as $index => $k3l) {
                    $evidenPath = null;
                    if ($request->hasFile("k3l.{$index}.eviden")) {
                        $file = $request->file("k3l.{$index}.eviden");
                        $evidenPath = $file->store('eviden/k3l', 'public');
                    }

                    MeetingShiftK3l::create([
                        'meeting_shift_id' => $meetingShift->id,
                        'type' => (string) $k3l['type'],
                        'uraian' => $k3l['uraian'],
                        'saran' => $k3l['saran'],
                        'eviden_path' => $evidenPath
                    ]);
                }
            }

            // Update notes
            $meetingShift->notes()->delete();
            MeetingShiftNote::create([
                'meeting_shift_id' => $meetingShift->id,
                'type' => 'sistem',
                'content' => $validated['catatan_sistem']
            ]);

            MeetingShiftNote::create([
                'meeting_shift_id' => $meetingShift->id,
                'type' => 'umum',
                'content' => $validated['catatan_umum']
            ]);

            // Update resume
            $meetingShift->resume()->delete();
            if (!empty($validated['resume'])) {
                MeetingShiftResume::create([
                    'meeting_shift_id' => $meetingShift->id,
                    'content' => trim($validated['resume'])
                ]);
            }

            // Update attendance records
            $meetingShift->attendances()->delete();
            if (!empty($validated['absensi'])) {
                foreach ($validated['absensi'] as $attendance) {
                    MeetingShiftAttendance::create([
                        'meeting_shift_id' => $meetingShift->id,
                        'nama' => trim($attendance['nama']),
                        'shift' => (string) $attendance['shift'],
                        'status' => (string) $attendance['status'],
                        'keterangan' => !empty($attendance['keterangan']) ? trim($attendance['keterangan']) : null
                    ]);
                }
            }

            DB::commit();
            
            Log::info('Meeting shift updated successfully', ['meeting_shift_id' => $meetingShift->id]);
            
            return redirect()->route('admin.meeting-shift.show', $meetingShift->id)
                           ->with('success', 'Data meeting shift berhasil diperbarui');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error updating meeting shift: ' . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return redirect()->back()
                           ->with('error', 'Terjadi kesalahan saat memperbarui data meeting shift: ' . $e->getMessage())
                           ->withInput();
        }
    }
} 
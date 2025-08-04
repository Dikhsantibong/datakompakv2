@extends('layouts.app')

@section('content')
<div class="flex h-screen bg-gray-100">
    @include('components.sidebar')
    
    <div class="flex-1 flex flex-col overflow-hidden">
        <header class="bg-white shadow-sm">
            <div class="flex items-center justify-between h-16 px-4 sm:px-6 lg:px-8">
                <div class="flex items-center">
                    <button id="mobile-menu-toggle"
                        class="md:hidden relative inline-flex items-center justify-center rounded-md p-2 text-gray-400 hover:bg-[#009BB9] hover:text-white focus:outline-none focus:ring-2 focus:ring-inset focus:ring-white"
                        aria-controls="mobile-menu" aria-expanded="false">
                        <span class="sr-only">Open main menu</span>
                        <svg class="block size-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                        </svg>
                    </button>

                    <h1 class="text-xl font-semibold text-gray-900 ml-2">Edit Meeting dan Mutasi Shift Operator</h1>
                </div>
            </div>
        </header>

        <div class="flex items-center pt-2">
            <x-admin-breadcrumb :breadcrumbs="[
                ['name' => 'Meeting dan Mutasi Shift Operator', 'url' => route('admin.meeting-shift.list')],
                ['name' => 'Edit', 'url' => null]
            ]" />
        </div>

        <!-- Main Content Area -->
        <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-100">
            <div class="container mx-auto px-4 sm:px-6">
                <form action="{{ route('admin.meeting-shift.update', $meetingShift->id) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                        @csrf
                        @method('PUT')
                    
                    <!-- Basic Information -->
                    <div class="bg-white rounded-lg shadow-sm p-6">
                        <h2 class="text-lg font-semibold mb-4">Informasi Dasar</h2>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Tanggal</label>
                                <input type="date" name="tanggal" value="{{ $meetingShift->tanggal->format('Y-m-d') }}" 
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Shift</label>
                                <select name="current_shift" class="p-2 mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                                    <option value="A" {{ $meetingShift->current_shift == 'A' ? 'selected' : '' }}>Shift A</option>
                                    <option value="B" {{ $meetingShift->current_shift == 'B' ? 'selected' : '' }}>Shift B</option>
                                    <option value="C" {{ $meetingShift->current_shift == 'C' ? 'selected' : '' }}>Shift C</option>
                                    <option value="D" {{ $meetingShift->current_shift == 'D' ? 'selected' : '' }}>Shift D</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Machine Statuses -->
                    <div class="bg-white rounded-lg shadow-sm p-6">
                        <h2 class="text-lg font-semibold mb-4">Status Mesin</h2>
                        <div class="space-y-4" id="machine-statuses">
                            @foreach($meetingShift->machineStatuses as $index => $machineStatus)
                            <div class="border rounded p-4">
                                <input type="hidden" name="machine_statuses[{{ $index }}][machine_id]" value="{{ $machineStatus->machine_id }}">
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Mesin</label>
                                        <input type="text" value="{{ $machineStatus->machine->name }}" class="mt-1 block w-full rounded-md border-gray-300 bg-gray-50" readonly>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Status</label>
                                        <div class="mt-2 space-y-2">
                                            @php 
                                                $currentStatus = is_string($machineStatus->status) ? json_decode($machineStatus->status) : $machineStatus->status;
                                                $currentStatus = is_array($currentStatus) ? $currentStatus : [];
                                            @endphp
                                            <div class="flex items-center">
                                                <input type="checkbox" name="machine_statuses[{{ $index }}][status][]" value="operasi"
                                                    class="h-4 w-4 text-[#009BB9] focus:ring-[#009BB9] border-gray-300 rounded machine-status-checkbox"
                                                    {{ in_array('operasi', $currentStatus) ? 'checked' : '' }}
                                                    onchange="validateMachineStatus({{ $index }})">
                                                <label class="ml-2 text-sm text-gray-700">Operasi</label>
                                            </div>
                                            <div class="flex items-center">
                                                <input type="checkbox" name="machine_statuses[{{ $index }}][status][]" value="standby"
                                                    class="h-4 w-4 text-[#009BB9] focus:ring-[#009BB9] border-gray-300 rounded machine-status-checkbox"
                                                    {{ in_array('standby', $currentStatus) ? 'checked' : '' }}
                                                    onchange="validateMachineStatus({{ $index }})">
                                                <label class="ml-2 text-sm text-gray-700">Standby</label>
                                            </div>
                                            <div class="flex items-center">
                                                <input type="checkbox" name="machine_statuses[{{ $index }}][status][]" value="har_rutin"
                                                    class="h-4 w-4 text-[#009BB9] focus:ring-[#009BB9] border-gray-300 rounded machine-status-checkbox"
                                                    {{ in_array('har_rutin', $currentStatus) ? 'checked' : '' }}
                                                    onchange="validateMachineStatus({{ $index }})">
                                                <label class="ml-2 text-sm text-gray-700">HAR Rutin</label>
                                            </div>
                                            <div class="flex items-center">
                                                <input type="checkbox" name="machine_statuses[{{ $index }}][status][]" value="har_nonrutin"
                                                    class="h-4 w-4 text-[#009BB9] focus:ring-[#009BB9] border-gray-300 rounded machine-status-checkbox"
                                                    {{ in_array('har_nonrutin', $currentStatus) ? 'checked' : '' }}
                                                    onchange="validateMachineStatus({{ $index }})">
                                                <label class="ml-2 text-sm text-gray-700">HAR Non-Rutin</label>
                                            </div>
                                            <div class="flex items-center">
                                                <input type="checkbox" name="machine_statuses[{{ $index }}][status][]" value="gangguan"
                                                    class="h-4 w-4 text-[#009BB9] focus:ring-[#009BB9] border-gray-300 rounded machine-status-checkbox"
                                                    {{ in_array('gangguan', $currentStatus) ? 'checked' : '' }}
                                                    onchange="validateMachineStatus({{ $index }})">
                                                <label class="ml-2 text-sm text-gray-700">Gangguan</label>
                                            </div>
                                        </div>
                                        <!-- Error message for machine status -->
                                        <div class="hidden text-red-500 text-xs mt-1" id="machine-status-error-{{ $index }}">
                                            Pilih minimal satu status
                                        </div>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Keterangan</label>
                                        <input type="text" name="machine_statuses[{{ $index }}][keterangan]" value="{{ $machineStatus->keterangan }}"
                                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Auxiliary Equipment -->
                    <div class="bg-white rounded-lg shadow-sm p-6">
                        <h2 class="text-lg font-semibold mb-4">Alat Bantu</h2>
                        <div class="space-y-4" id="auxiliary-equipment">
                            @foreach($meetingShift->auxiliaryEquipments as $index => $equipment)
                            <div class="border rounded p-4">
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Nama Alat</label>
                                        <input type="text" name="auxiliary_equipment[{{ $index }}][name]" value="{{ $equipment->name }}"
                                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Status</label>
                                        <div class="mt-2 space-y-2">
                                            @php 
                                                $currentStatus = is_string($equipment->status) ? json_decode($equipment->status) : $equipment->status;
                                                $currentStatus = is_array($currentStatus) ? $currentStatus : [];
                                            @endphp
                                            <div class="flex items-center">
                                                <input type="checkbox" name="auxiliary_equipment[{{ $index }}][status][]" value="normal"
                                                    class="h-4 w-4 text-[#009BB9] focus:ring-[#009BB9] border-gray-300 rounded"
                                                    {{ in_array('normal', $currentStatus) ? 'checked' : '' }}>
                                                <label class="ml-2 text-sm text-gray-700">Normal</label>
                                            </div>
                                            <div class="flex items-center">
                                                <input type="checkbox" name="auxiliary_equipment[{{ $index }}][status][]" value="abnormal"
                                                    class="h-4 w-4 text-[#009BB9] focus:ring-[#009BB9] border-gray-300 rounded"
                                                    {{ in_array('abnormal', $currentStatus) ? 'checked' : '' }}>
                                                <label class="ml-2 text-sm text-gray-700">Abnormal</label>
                                            </div>
                                            <div class="flex items-center">
                                                <input type="checkbox" name="auxiliary_equipment[{{ $index }}][status][]" value="gangguan"
                                                    class="h-4 w-4 text-[#009BB9] focus:ring-[#009BB9] border-gray-300 rounded"
                                                    {{ in_array('gangguan', $currentStatus) ? 'checked' : '' }}>
                                                <label class="ml-2 text-sm text-gray-700">Gangguan</label>
                                            </div>
                                            <div class="flex items-center">
                                                <input type="checkbox" name="auxiliary_equipment[{{ $index }}][status][]" value="flm"
                                                    class="h-4 w-4 text-[#009BB9] focus:ring-[#009BB9] border-gray-300 rounded"
                                                    {{ in_array('flm', $currentStatus) ? 'checked' : '' }}>
                                                <label class="ml-2 text-sm text-gray-700">FLM</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Keterangan</label>
                                        <input type="text" name="auxiliary_equipment[{{ $index }}][keterangan]" value="{{ $equipment->keterangan }}"
                                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Resources -->
                    <div class="bg-white rounded-lg shadow-sm p-6">
                        <h2 class="text-lg font-semibold mb-4">Resources</h2>
                        <div class="space-y-4" id="resources">
                            @foreach($meetingShift->resources as $index => $resource)
                            <div class="border rounded p-4">
                                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Nama Resource</label>
                                        <input type="text" name="resources[{{ $index }}][name]" value="{{ $resource->name }}"
                                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Kategori</label>
                                        <select name="resources[{{ $index }}][category]" class="p-2 mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                                            <option value="PELUMAS" {{ $resource->category == 'PELUMAS' ? 'selected' : '' }}>PELUMAS</option>
                                            <option value="BBM" {{ $resource->category == 'BBM' ? 'selected' : '' }}>BBM</option>
                                            <option value="AIR PENDINGIN" {{ $resource->category == 'AIR PENDINGIN' ? 'selected' : '' }}>AIR PENDINGIN</option>
                                            <option value="UDARA START" {{ $resource->category == 'UDARA START' ? 'selected' : '' }}>UDARA START</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Status</label>
                                        <select name="resources[{{ $index }}][status]" class="p-2 mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                                            <option value="0-20" {{ $resource->status == '0-20' ? 'selected' : '' }}>0-20%</option>
                                            <option value="21-40" {{ $resource->status == '21-40' ? 'selected' : '' }}>21-40%</option>
                                            <option value="41-61" {{ $resource->status == '41-61' ? 'selected' : '' }}>41-61%</option>
                                            <option value="61-80" {{ $resource->status == '61-80' ? 'selected' : '' }}>61-80%</option>
                                            <option value="up-80" {{ $resource->status == 'up-80' ? 'selected' : '' }}>>80%</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Keterangan</label>
                                        <input type="text" name="resources[{{ $index }}][keterangan]" value="{{ $resource->keterangan }}"
                                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- K3L -->
                    <div class="bg-white rounded-lg shadow-sm p-6">
                        <h2 class="text-lg font-semibold mb-4">K3L</h2>
                        <div class="space-y-4" id="k3l">
                            @foreach($meetingShift->k3ls as $index => $k3l)
                            <div class="border rounded p-4">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Tipe</label>
                                        <select name="k3l[{{ $index }}][type]" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required onchange="handleK3lTypeChange(this, {{ $index }})">
                                            <option value="unsafe_action" {{ $k3l->type == 'unsafe_action' ? 'selected' : '' }}>Unsafe Action</option>
                                            <option value="unsafe_condition" {{ $k3l->type == 'unsafe_condition' ? 'selected' : '' }}>Unsafe Condition</option>
                                            <option value="positif" {{ $k3l->type == 'positif' ? 'selected' : '' }}>Positif (Tidak Ada K3L)</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Uraian</label>
                                        <textarea name="k3l[{{ $index }}][uraian]" rows="3" class="p-2 mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" {{ $k3l->type == 'positif' ? 'disabled' : 'required' }}>{{ $k3l->uraian }}</textarea>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Saran</label>
                                        <textarea name="k3l[{{ $index }}][saran]" rows="3" class="p-2 mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" {{ $k3l->type == 'positif' ? 'disabled' : 'required' }}>{{ $k3l->saran }}</textarea>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Eviden</label>
                                        @if($k3l->eviden_path)
                                            <div class="mt-2">
                                                <img src="{{ Storage::url($k3l->eviden_path) }}" alt="Eviden" class="h-32 w-auto object-cover rounded">
                                            </div>
                                        @endif
                                        <input type="file" name="k3l[{{ $index }}][eviden]" accept="image/*"
                                               class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100"
                                               {{ $k3l->type == 'positif' ? 'disabled' : '' }}>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Notes -->
                    <div class="bg-white rounded-lg shadow-sm p-6">
                        <h2 class="text-lg font-semibold mb-4">Catatan</h2>
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Catatan Sistem</label>
                                <textarea name="catatan_sistem" rows="4" class="p-2 mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>{{ $meetingShift->systemNote->content ?? '' }}</textarea>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Catatan Umum</label>
                                <textarea name="catatan_umum" rows="4" class="p-2 mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>{{ $meetingShift->generalNote->content ?? '' }}</textarea>
                            </div>
                        </div>
                    </div>

                    <!-- Uraian Shift -->
                    <div class="bg-white rounded-lg shadow-sm p-6">
                        <h2 class="text-lg font-semibold mb-4">Uraian Shift</h2>
                        <textarea name="uraian_shift" rows="4" class="p-2 mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>{{ $meetingShift->resume->uraian_shift ?? '' }}</textarea>
                    </div>

                    <!-- Resume -->
                    <div class="bg-white rounded-lg shadow-sm p-6">
                        <h2 class="text-lg font-semibold mb-4">Resume</h2>
                        <textarea name="resume" rows="4" class="p-2 mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>{{ $meetingShift->resume->content ?? '' }}</textarea>
                    </div>

                    <!-- Attendance -->
                    <div class="bg-white rounded-lg shadow-sm p-6">
                        <h2 class="text-lg font-semibold mb-4">Absensi</h2>
                        <div class="space-y-4" id="attendance">
                            @foreach($meetingShift->attendances as $index => $attendance)
                            <div class="border rounded p-4">
                                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Nama</label>
                                        <input type="text" name="absensi[{{ $index }}][nama]" value="{{ $attendance->nama }}"
                                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Shift</label>
                                        <select name="absensi[{{ $index }}][shift]" class="p-2 mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                                            <option value="A" {{ $attendance->shift == 'A' ? 'selected' : '' }}>Shift A</option>
                                            <option value="B" {{ $attendance->shift == 'B' ? 'selected' : '' }}>Shift B</option>
                                            <option value="C" {{ $attendance->shift == 'C' ? 'selected' : '' }}>Shift C</option>
                                            <option value="D" {{ $attendance->shift == 'D' ? 'selected' : '' }}>Shift D</option>
                                            <option value="staf ops" {{ $attendance->shift == 'staf ops' ? 'selected' : '' }}>Staf Ops</option>
                                            <option value="TL OP" {{ $attendance->shift == 'TL OP' ? 'selected' : '' }}>TL OP</option>
                                            <option value="TL HAR" {{ $attendance->shift == 'TL HAR' ? 'selected' : '' }}>TL HAR</option>
                                            <option value="TL OPHAR" {{ $attendance->shift == 'TL OPHAR' ? 'selected' : '' }}>TL OPHAR</option>
                                            <option value="MUL" {{ $attendance->shift == 'MUL' ? 'selected' : '' }}>MUL</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Status</label>
                                        <select name="absensi[{{ $index }}][status]" class="p-2 mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                                            <option value="hadir" {{ $attendance->status == 'hadir' ? 'selected' : '' }}>Hadir</option>
                                            <option value="izin" {{ $attendance->status == 'izin' ? 'selected' : '' }}>Izin</option>
                                            <option value="sakit" {{ $attendance->status == 'sakit' ? 'selected' : '' }}>Sakit</option>
                                            <option value="cuti" {{ $attendance->status == 'cuti' ? 'selected' : '' }}>Cuti</option>
                                            <option value="alpha" {{ $attendance->status == 'alpha' ? 'selected' : '' }}>Alpha</option>
                                            <option value="terlambat" {{ $attendance->status == 'terlambat' ? 'selected' : '' }}>Terlambat</option>
                                            <option value="ganti shift" {{ $attendance->status == 'ganti shift' ? 'selected' : '' }}>Ganti Shift</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Keterangan</label>
                                        <input type="text" name="absensi[{{ $index }}][keterangan]" value="{{ $attendance->keterangan }}"
                                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                </div>
            </div>
        </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="flex justify-end space-x-3">
                        <a href="{{ route('admin.meeting-shift.list') }}" 
                           class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-gray-700 bg-gray-100 hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                            Batal
                        </a>
                        <button type="submit"
                                class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </main>
    </div>
</div>

@push('scripts')
<script>
function handleK3lTypeChange(select, index) {
    var uraian = document.querySelector(`textarea[name='k3l[${index}][uraian]']`);
    var saran = document.querySelector(`textarea[name='k3l[${index}][saran]']`);
    var eviden = document.querySelector(`input[name='k3l[${index}][eviden]']`);
    if (select.value === 'positif') {
        if (uraian) { uraian.value = ''; uraian.required = false; uraian.disabled = true; }
        if (saran) { saran.value = ''; saran.required = false; saran.disabled = true; }
        if (eviden) { eviden.value = ''; eviden.required = false; eviden.disabled = true; }
    } else {
        if (uraian) { uraian.required = true; uraian.disabled = false; }
        if (saran) { saran.required = true; saran.disabled = false; }
        if (eviden) { eviden.required = false; eviden.disabled = false; }
    }
}

document.addEventListener('DOMContentLoaded', function() {
    // Set onchange for all K3L type select
    document.querySelectorAll('select[name^="k3l"][name$="[type]"]').forEach(function(select, idx) {
        select.onchange = function() { handleK3lTypeChange(this, idx); };
        // Trigger on load
        handleK3lTypeChange(select, idx);
    });

    // Validate machine status before form submission
    document.querySelector('form').addEventListener('submit', function(e) {
        let hasError = false;
        const machineStatuses = document.querySelectorAll('[id^="machine-status-error-"]');

        machineStatuses.forEach(errorElement => {
            const index = errorElement.id.replace('machine-status-error-', '');
            const checkboxes = document.querySelectorAll(`input[name^="machine_statuses[${index}][status]"]`);
            let isChecked = false;

            checkboxes.forEach(checkbox => {
                if (checkbox.checked) {
                    isChecked = true;
                }
            });

            if (!isChecked) {
                errorElement.classList.remove('hidden');
                hasError = true;
            } else {
                errorElement.classList.add('hidden');
            }
        });

        if (hasError) {
            e.preventDefault();
            // Scroll to first error
            const firstError = document.querySelector('.text-red-500:not(.hidden)');
            if (firstError) {
                firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
            }
        }
    });

    // Add validation for K3L file upload
    document.querySelectorAll('input[name$="[eviden]"]').forEach(input => {
        input.addEventListener('change', function() {
            const file = this.files[0];
            const errorElement = this.parentElement.querySelector('.file-error');
            
            if (errorElement) {
                errorElement.remove();
            }

            if (file) {
                const validTypes = ['image/jpeg', 'image/png', 'image/jpg', 'image/gif'];
                if (!validTypes.includes(file.type)) {
                    const error = document.createElement('p');
                    error.className = 'text-red-500 text-xs mt-1 file-error';
                    error.textContent = 'File harus berupa gambar (JPG, PNG, atau GIF)';
                    this.parentElement.appendChild(error);
                    this.value = ''; // Clear the file input
                } else if (file.size > 2 * 1024 * 1024) { // 2MB
                    const error = document.createElement('p');
                    error.className = 'text-red-500 text-xs mt-1 file-error';
                    error.textContent = 'Ukuran file tidak boleh lebih dari 2MB';
                    this.parentElement.appendChild(error);
                    this.value = ''; // Clear the file input
                }
            }
        });
    });
});

function validateMachineStatus(index) {
    const checkboxes = document.querySelectorAll(`input[name^="machine_statuses[${index}][status]"]`);
    const errorElement = document.getElementById(`machine-status-error-${index}`);
    let isChecked = false;

    checkboxes.forEach(checkbox => {
        if (checkbox.checked) {
            isChecked = true;
        }
    });

    if (!isChecked) {
        errorElement.classList.remove('hidden');
    } else {
        errorElement.classList.add('hidden');
    }
}
</script>
@endpush

@endsection 
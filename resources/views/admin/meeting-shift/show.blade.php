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

                    <h1 class="text-xl font-semibold text-gray-900 ml-2">Detail Meeting dan Mutasi Shift Operator</h1>
                </div>

                <div class="flex items-center space-x-4">
                    <span class="text-sm text-gray-500">{{ $meetingShift->date }}</span>
                    <span class="px-3 py-1 text-xs font-medium rounded-full 
                        @if($meetingShift->current_shift == 'A') bg-blue-100 text-blue-800
                        @elseif($meetingShift->current_shift == 'B') bg-green-100 text-green-800
                        @elseif($meetingShift->current_shift == 'C') bg-yellow-100 text-yellow-800
                        @else bg-purple-100 text-purple-800 @endif">
                        Shift {{ $meetingShift->current_shift }}
                    </span>
                </div>
            </div>
        </header>

        <div class="flex items-center pt-2">
            <x-admin-breadcrumb :breadcrumbs="[
                ['name' => 'Meeting dan Mutasi Shift Operator', 'url' => route('admin.meeting-shift.index')],
                ['name' => 'Detail', 'url' => null]
            ]" />
        </div>

        <!-- Main Content Area -->
        <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-100">
            <div class="container mx-auto px-4 sm:px-6 py-8">
                <div class="space-y-6">
                    <!-- Kondisi Mesin -->
                    <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                        <div class="px-6 py-4 border-b border-gray-200">
                            <h2 class="text-lg font-medium text-gray-900">
                                <i class="fas fa-cogs mr-2 text-gray-400"></i>
                                Kondisi Mesin
                            </h2>
                        </div>
                        <div class="p-6">
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Mesin</th>
                                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Keterangan</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        @foreach($meetingShift->machineStatuses as $status)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                                {{ $status->machine->name }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">
                                                @foreach(explode(',', $status->status) as $stat)
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                                        @if($stat == 'operasi') bg-green-100 text-green-800
                                                        @elseif($stat == 'standby') bg-blue-100 text-blue-800
                                                        @elseif($stat == 'har_rutin') bg-yellow-100 text-yellow-800
                                                        @elseif($stat == 'har_nonrutin') bg-orange-100 text-orange-800
                                                        @else bg-red-100 text-red-800 @endif
                                                        mr-1">
                                                        {{ ucfirst(str_replace('_', ' ', $stat)) }}
                                                    </span>
                                                @endforeach
                                            </td>
                                            <td class="px-6 py-4 text-sm text-gray-500">
                                                {{ $status->keterangan }}
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- Kondisi Alat Bantu -->
                    <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                        <div class="px-6 py-4 border-b border-gray-200">
                            <h2 class="text-lg font-medium text-gray-900">
                                <i class="fas fa-tools mr-2 text-gray-400"></i>
                                Kondisi Alat Bantu
                            </h2>
                        </div>
                        <div class="p-6">
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Alat Bantu</th>
                                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Keterangan</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        @foreach($meetingShift->auxiliaryEquipment as $equipment)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                                {{ $equipment->name }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">
                                                @foreach(explode(',', $equipment->status) as $stat)
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                                        @if($stat == 'normal') bg-green-100 text-green-800
                                                        @elseif($stat == 'abnormal') bg-yellow-100 text-yellow-800
                                                        @elseif($stat == 'gangguan') bg-red-100 text-red-800
                                                        @else bg-blue-100 text-blue-800 @endif
                                                        mr-1">
                                                        {{ ucfirst($stat) }}
                                                    </span>
                                                @endforeach
                                            </td>
                                            <td class="px-6 py-4 text-sm text-gray-500">
                                                {{ $equipment->keterangan }}
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- Kondisi Resource -->
                    <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                        <div class="px-6 py-4 border-b border-gray-200">
                            <h2 class="text-lg font-medium text-gray-900">
                                <i class="fas fa-boxes mr-2 text-gray-400"></i>
                                Kondisi Resource
                            </h2>
                        </div>
                        <div class="p-6">
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Resource</th>
                                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Keterangan</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        @foreach($meetingShift->resources as $resource)
                                        <tr class="{{ $resource->is_category ? 'bg-gray-50' : '' }}">
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 {{ $resource->is_category ? '' : 'pl-8' }}">
                                                {{ $resource->name }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">
                                                @if(!$resource->is_category)
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                                        @if($resource->status == '0-20') bg-red-100 text-red-800
                                                        @elseif($resource->status == '21-40') bg-orange-100 text-orange-800
                                                        @elseif($resource->status == '41-61') bg-yellow-100 text-yellow-800
                                                        @elseif($resource->status == '61-80') bg-blue-100 text-blue-800
                                                        @else bg-green-100 text-green-800 @endif">
                                                        {{ $resource->status }}%
                                                    </span>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 text-sm text-gray-500">
                                                {{ $resource->keterangan }}
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- Kondisi K3L -->
                    <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                        <div class="px-6 py-4 border-b border-gray-200">
                            <h2 class="text-lg font-medium text-gray-900">
                                <i class="fas fa-hard-hat mr-2 text-gray-400"></i>
                                Kondisi K3L
                            </h2>
                        </div>
                        <div class="p-6">
                            <div class="space-y-6">
                                @foreach($meetingShift->k3l as $k3l)
                                <div class="bg-gray-50 rounded-lg p-4">
                                    <div class="flex items-center mb-2">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                            {{ $k3l->type == 'unsafe_action' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800' }} mr-2">
                                            {{ $k3l->type == 'unsafe_action' ? 'Unsafe Action' : 'Unsafe Condition' }}
                                        </span>
                                    </div>
                                    <div class="space-y-2">
                                        <div>
                                            <h4 class="text-sm font-medium text-gray-900">Uraian:</h4>
                                            <p class="text-sm text-gray-500">{{ $k3l->uraian }}</p>
                                        </div>
                                        <div>
                                            <h4 class="text-sm font-medium text-gray-900">Saran & Tindak Lanjut:</h4>
                                            <p class="text-sm text-gray-500">{{ $k3l->saran }}</p>
                                        </div>
                                        @if($k3l->eviden_path)
                                        <div>
                                            <h4 class="text-sm font-medium text-gray-900">Eviden:</h4>
                                            <a href="{{ asset('storage/' . $k3l->eviden_path) }}" 
                                               target="_blank"
                                               class="inline-flex items-center text-sm text-blue-600 hover:text-blue-500">
                                                <i class="fas fa-file-image mr-1"></i>
                                                Lihat Eviden
                                            </a>
                                        </div>
                                        @endif
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <!-- Catatan -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Catatan Sistem -->
                        <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                            <div class="px-6 py-4 border-b border-gray-200">
                                <h2 class="text-lg font-medium text-gray-900">
                                    <i class="fas fa-clipboard-list mr-2 text-gray-400"></i>
                                    Catatan Sistem
                                </h2>
                            </div>
                            <div class="p-6">
                                <p class="text-sm text-gray-500 whitespace-pre-line">{{ $meetingShift->systemNote->content ?? '-' }}</p>
                            </div>
                        </div>

                        <!-- Catatan Umum -->
                        <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                            <div class="px-6 py-4 border-b border-gray-200">
                                <h2 class="text-lg font-medium text-gray-900">
                                    <i class="fas fa-clipboard-check mr-2 text-gray-400"></i>
                                    Catatan Umum
                                </h2>
                            </div>
                            <div class="p-6">
                                <p class="text-sm text-gray-500 whitespace-pre-line">{{ $meetingShift->generalNote->content ?? '-' }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Resume Rapat -->
                    <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                        <div class="px-6 py-4 border-b border-gray-200">
                            <h2 class="text-lg font-medium text-gray-900">
                                <i class="fas fa-file-alt mr-2 text-gray-400"></i>
                                Resume Rapat
                            </h2>
                        </div>
                        <div class="p-6">
                            <p class="text-sm text-gray-500 whitespace-pre-line">{{ $meetingShift->resume->content ?? '-' }}</p>
                        </div>
                    </div>

                    <!-- Absensi -->
                    <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                        <div class="px-6 py-4 border-b border-gray-200">
                            <h2 class="text-lg font-medium text-gray-900">
                                <i class="fas fa-users mr-2 text-gray-400"></i>
                                Absensi
                            </h2>
                        </div>
                        <div class="p-6">
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama</th>
                                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Shift</th>
                                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Keterangan</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        @foreach($meetingShift->attendance as $index => $attendance)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                {{ $index + 1 }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                                {{ $attendance->nama }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                                    @if($attendance->shift == 'A') bg-blue-100 text-blue-800
                                                    @elseif($attendance->shift == 'B') bg-green-100 text-green-800
                                                    @elseif($attendance->shift == 'C') bg-yellow-100 text-yellow-800
                                                    @else bg-purple-100 text-purple-800 @endif">
                                                    Shift {{ $attendance->shift }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                                    @if($attendance->status == 'hadir') bg-green-100 text-green-800
                                                    @elseif($attendance->status == 'izin') bg-blue-100 text-blue-800
                                                    @elseif($attendance->status == 'sakit') bg-yellow-100 text-yellow-800
                                                    @elseif($attendance->status == 'cuti') bg-purple-100 text-purple-800
                                                    @else bg-red-100 text-red-800 @endif">
                                                    {{ ucfirst($attendance->status) }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 text-sm text-gray-500">
                                                {{ $attendance->keterangan ?? '-' }}
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="mt-6 flex justify-end space-x-3">
                    <a href="{{ route('admin.meeting-shift.index') }}" 
                        class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#009BB9]">
                        <i class="fas fa-arrow-left mr-2"></i>
                        Kembali
                    </a>
                    <a href="{{ route('admin.meeting-shift.edit', $meetingShift->id) }}" 
                        class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-[#009BB9] hover:bg-[#009BB9]/80 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#009BB9]">
                        <i class="fas fa-edit mr-2"></i>
                        Edit
                    </a>
                    <button type="button"
                        onclick="printMeetingShift()"
                        class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-gray-600 hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                        <i class="fas fa-print mr-2"></i>
                        Cetak
                    </button>
                </div>
            </div>
        </main>
    </div>
</div>

@push('scripts')
<script>
function printMeetingShift() {
    window.print();
}
</script>
@endpush

@endsection 
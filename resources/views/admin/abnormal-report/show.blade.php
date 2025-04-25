@extends('layouts.app')

@section('content')
<div class="flex h-screen bg-gray-100">
    @include('components.sidebar')

    <div class="flex-1 flex flex-col overflow-hidden">
        <header class="bg-white shadow-sm">
            <div class="flex items-center justify-between h-16 px-4 sm:px-6 lg:px-8">
                <div class="flex items-center">
                    <h1 class="text-xl font-semibold text-gray-900">Detail Laporan Abnormal/Gangguan</h1>
                </div>
            </div>
        </header>

        <div class="flex items-center pt-2">
            <x-admin-breadcrumb :breadcrumbs="[
                ['name' => 'Laporan Abnormal/Gangguan', 'url' => route('admin.abnormal-report.index')],
                ['name' => 'Daftar', 'url' => route('admin.abnormal-report.list')],
                ['name' => 'Detail', 'url' => null]
            ]" />
        </div>

        <!-- Main Content Area -->
        <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-100">
            <div class="container mx-auto px-4 sm:px-6 py-8">
                <div class="space-y-6">
                    <!-- Report Info -->
                    <div class="bg-white rounded-lg shadow overflow-hidden">
                        <div class="px-6 py-4 border-b border-gray-200">
                            <h2 class="text-lg font-medium text-gray-900">Informasi Laporan</h2>
                        </div>
                        <div class="p-6">
                            <dl class="grid grid-cols-1 gap-x-4 gap-y-6 sm:grid-cols-2">
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Dibuat Oleh</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $report->creator->name ?? 'N/A' }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Tanggal Dibuat</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $report->created_at->format('d/m/Y H:i') }}</dd>
                                </div>
                            </dl>
                        </div>
                    </div>

                    <!-- Kronologi Kejadian -->
                    <div class="bg-white rounded-lg shadow overflow-hidden">
                        <div class="px-6 py-4 border-b border-gray-200">
                            <h2 class="text-lg font-medium text-gray-900">Kronologi Kejadian</h2>
                        </div>
                        <div class="p-6">
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead>
                                        <tr>
                                            <th rowspan="2" class="px-4 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border">Pukul (WIB)</th>
                                            <th rowspan="2" class="px-4 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border">Uraian kejadian</th>
                                            <th colspan="4" class="px-4 py-3 bg-gray-50 text-center text-xs font-medium text-gray-500 uppercase tracking-wider border">Pengamatan</th>
                                            <th colspan="4" class="px-4 py-3 bg-gray-50 text-center text-xs font-medium text-gray-500 uppercase tracking-wider border">Koordinasi</th>
                                        </tr>
                                        <tr>
                                            <th class="px-4 py-3 bg-gray-50 text-center text-xs font-medium text-gray-500 uppercase tracking-wider border">Visual parameter terkait</th>
                                            <th class="px-4 py-3 bg-gray-50 text-center text-xs font-medium text-gray-500 uppercase tracking-wider border">Turun beban</th>
                                            <th class="px-4 py-3 bg-gray-50 text-center text-xs font-medium text-gray-500 uppercase tracking-wider border">Off CBG</th>
                                            <th class="px-4 py-3 bg-gray-50 text-center text-xs font-medium text-gray-500 uppercase tracking-wider border">Stop</th>
                                            <th class="px-4 py-3 bg-gray-50 text-center text-xs font-medium text-gray-500 uppercase tracking-wider border">TL Ophar</th>
                                            <th class="px-4 py-3 bg-gray-50 text-center text-xs font-medium text-gray-500 uppercase tracking-wider border">TL OP</th>
                                            <th class="px-4 py-3 bg-gray-50 text-center text-xs font-medium text-gray-500 uppercase tracking-wider border">TL HAR</th>
                                            <th class="px-4 py-3 bg-gray-50 text-center text-xs font-medium text-gray-500 uppercase tracking-wider border">MUL</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        @forelse($report->chronologies as $chronology)
                                        <tr>
                                            <td class="border px-4 py-2">{{ $chronology->waktu->format('H:i') }}</td>
                                            <td class="border px-4 py-2">{{ $chronology->uraian_kejadian }}</td>
                                            <td class="border px-4 py-2">{{ $chronology->visual_parameter }}</td>
                                            <td class="border px-4 py-2 text-center">
                                                @if($chronology->turun_beban)
                                                    <i class="fas fa-check text-green-500"></i>
                                                @endif
                                            </td>
                                            <td class="border px-4 py-2 text-center">
                                                @if($chronology->off_cbg)
                                                    <i class="fas fa-check text-green-500"></i>
                                                @endif
                                            </td>
                                            <td class="border px-4 py-2 text-center">
                                                @if($chronology->stop)
                                                    <i class="fas fa-check text-green-500"></i>
                                                @endif
                                            </td>
                                            <td class="border px-4 py-2 text-center">
                                                @if($chronology->tl_ophar)
                                                    <i class="fas fa-check text-green-500"></i>
                                                @endif
                                            </td>
                                            <td class="border px-4 py-2 text-center">
                                                @if($chronology->tl_op)
                                                    <i class="fas fa-check text-green-500"></i>
                                                @endif
                                            </td>
                                            <td class="border px-4 py-2 text-center">
                                                @if($chronology->tl_har)
                                                    <i class="fas fa-check text-green-500"></i>
                                                @endif
                                            </td>
                                            <td class="border px-4 py-2 text-center">
                                                @if($chronology->mul)
                                                    <i class="fas fa-check text-green-500"></i>
                                                @endif
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="10" class="border px-4 py-2 text-center text-gray-500">
                                                Tidak ada data kronologi
                                            </td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- Mesin/Peralatan Terdampak -->
                    <div class="bg-white rounded-lg shadow overflow-hidden">
                        <div class="px-6 py-4 border-b border-gray-200">
                            <h2 class="text-lg font-medium text-gray-900">Mesin/Peralatan Terdampak</h2>
                        </div>
                        <div class="p-6">
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead>
                                        <tr>
                                            <th class="px-4 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border">No</th>
                                            <th class="px-4 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border">Nama Mesin/Peralatan</th>
                                            <th class="px-4 py-3 bg-gray-50 text-center text-xs font-medium text-gray-500 uppercase tracking-wider border">Rusak</th>
                                            <th class="px-4 py-3 bg-gray-50 text-center text-xs font-medium text-gray-500 uppercase tracking-wider border">Abnormal</th>
                                            <th class="px-4 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border">Keterangan</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($report->affectedMachines as $index => $machine)
                                        <tr>
                                            <td class="border px-4 py-2">{{ $index + 1 }}</td>
                                            <td class="border px-4 py-2">{{ $machine->nama_mesin }}</td>
                                            <td class="border px-4 py-2 text-center">
                                                @if($machine->kondisi_rusak)
                                                    <i class="fas fa-check text-green-500"></i>
                                                @endif
                                            </td>
                                            <td class="border px-4 py-2 text-center">
                                                @if($machine->kondisi_abnormal)
                                                    <i class="fas fa-check text-green-500"></i>
                                                @endif
                                            </td>
                                            <td class="border px-4 py-2">{{ $machine->keterangan }}</td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="5" class="border px-4 py-2 text-center text-gray-500">
                                                Tidak ada data mesin/peralatan
                                            </td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- Tindak Lanjut -->
                    <div class="bg-white rounded-lg shadow overflow-hidden">
                        <div class="px-6 py-4 border-b border-gray-200">
                            <h2 class="text-lg font-medium text-gray-900">Tindak Lanjut</h2>
                        </div>
                        <div class="p-6">
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead>
                                        <tr>
                                            <th class="px-4 py-3 bg-gray-50 text-center text-xs font-medium text-gray-500 uppercase tracking-wider border">FLM</th>
                                            <th class="px-4 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border">Usul MO Rutin</th>
                                            <th class="px-4 py-3 bg-gray-50 text-center text-xs font-medium text-gray-500 uppercase tracking-wider border">MO Non Rutin</th>
                                            <th class="px-4 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border">Lainnya</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($report->followUpActions as $action)
                                        <tr>
                                            <td class="border px-4 py-2 text-center">
                                                @if($action->flm_tindakan)
                                                    <i class="fas fa-check text-green-500"></i>
                                                @endif
                                            </td>
                                            <td class="border px-4 py-2">{{ $action->usul_mo_rutin }}</td>
                                            <td class="border px-4 py-2 text-center">
                                                @if($action->mo_non_rutin)
                                                    <i class="fas fa-check text-green-500"></i>
                                                @endif
                                            </td>
                                            <td class="border px-4 py-2">{{ $action->lainnya }}</td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="4" class="border px-4 py-2 text-center text-gray-500">
                                                Tidak ada data tindak lanjut
                                            </td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- Rekomendasi -->
                    <div class="bg-white rounded-lg shadow overflow-hidden">
                        <div class="px-6 py-4 border-b border-gray-200">
                            <h2 class="text-lg font-medium text-gray-900">Rekomendasi</h2>
                        </div>
                        <div class="p-6">
                            <div class="space-y-4">
                                @forelse($report->recommendations as $index => $recommendation)
                                <div class="flex items-center">
                                    <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-blue-100 text-blue-500 mr-3">
                                        {{ $index + 1 }}
                                    </span>
                                    <span class="text-gray-700">{{ $recommendation->rekomendasi }}</span>
                                </div>
                                @empty
                                <div class="text-center text-gray-500">
                                    Tidak ada rekomendasi
                                </div>
                                @endforelse
                            </div>
                        </div>
                    </div>

                    <!-- ADM -->
                    <div class="bg-white rounded-lg shadow overflow-hidden">
                        <div class="px-6 py-4 border-b border-gray-200">
                            <h2 class="text-lg font-medium text-gray-900">ADM</h2>
                        </div>
                        <div class="p-6">
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead>
                                        <tr>
                                            <th class="px-4 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border">No</th>
                                            <th class="px-4 py-3 bg-gray-50 text-center text-xs font-medium text-gray-500 uppercase tracking-wider border">FLM</th>
                                            <th class="px-4 py-3 bg-gray-50 text-center text-xs font-medium text-gray-500 uppercase tracking-wider border">PM</th>
                                            <th class="px-4 py-3 bg-gray-50 text-center text-xs font-medium text-gray-500 uppercase tracking-wider border">CM</th>
                                            <th class="px-4 py-3 bg-gray-50 text-center text-xs font-medium text-gray-500 uppercase tracking-wider border">PtW</th>
                                            <th class="px-4 py-3 bg-gray-50 text-center text-xs font-medium text-gray-500 uppercase tracking-wider border">SR</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($report->admActions as $index => $adm)
                                        <tr>
                                            <td class="border px-4 py-2">{{ $index + 1 }}</td>
                                            <td class="border px-4 py-2 text-center">
                                                @if($adm->flm)
                                                    <i class="fas fa-check text-green-500"></i>
                                                @endif
                                            </td>
                                            <td class="border px-4 py-2 text-center">
                                                @if($adm->pm)
                                                    <i class="fas fa-check text-green-500"></i>
                                                @endif
                                            </td>
                                            <td class="border px-4 py-2 text-center">
                                                @if($adm->cm)
                                                    <i class="fas fa-check text-green-500"></i>
                                                @endif
                                            </td>
                                            <td class="border px-4 py-2 text-center">
                                                @if($adm->ptw)
                                                    <i class="fas fa-check text-green-500"></i>
                                                @endif
                                            </td>
                                            <td class="border px-4 py-2 text-center">
                                                @if($adm->sr)
                                                    <i class="fas fa-check text-green-500"></i>
                                                @endif
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="6" class="border px-4 py-2 text-center text-gray-500">
                                                Tidak ada data ADM
                                            </td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex justify-end space-x-3">
                        <a href="{{ route('admin.abnormal-report.list') }}" 
                           class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                            <i class="fas fa-arrow-left mr-2"></i>
                            Kembali
                        </a>
                        <button type="button" 
                                onclick="window.print()"
                                class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-[#009BB9] hover:bg-[#009BB9]/80">
                            <i class="fas fa-print mr-2"></i>
                            Cetak
                        </button>
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>
@endsection 
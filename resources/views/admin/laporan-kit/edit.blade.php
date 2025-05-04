@extends('layouts.app')

@section('content')
<div class="flex h-screen bg-gray-50 overflow-auto">
    @include('components.sidebar')

    <div id="main-content" class="flex-1 main-content">
        <!-- Header -->
        <header class="bg-white shadow-sm sticky top-0 z-10">
            <div class="flex justify-between items-center px-6 py-3">
                <div class="flex items-center gap-x-3">
                    <h1 class="text-xl font-semibold text-gray-800">Edit Laporan KIT 00.00</h1>
                </div>
                <a href="{{ route('admin.laporan-kit.list') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                    <i class="fas fa-arrow-left mr-2"></i> Kembali
                </a>
            </div>
        </header>

        <div class="flex items-center pt-2">
            <x-admin-breadcrumb :breadcrumbs="[['name' => 'Edit Laporan KIT 00.00', 'url' => null]]" />
        </div>

        <div class="container mx-auto px-4 sm:px-6">
            <form action="{{ route('admin.laporan-kit.update', $laporan->id) }}" method="POST" class="space-y-6">
                @csrf
                @method('PUT')
                <div class="bg-white rounded-lg shadow-sm p-6 mb-6 border border-gray-200">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium">Tanggal</label>
                            <input type="date" name="tanggal" value="{{ old('tanggal', $laporan->tanggal) }}" class="form-input w-full" required>
                        </div>
                        <div>
                            <label class="block text-sm font-medium">Unit</label>
                            <select name="unit_source" class="form-select w-full">
                                @foreach($powerPlants as $plant)
                                    <option value="{{ $plant->unit_source }}" {{ $laporan->unit_source == $plant->unit_source ? 'selected' : '' }}>
                                        {{ $plant->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Jam Operasi Mesin -->
                <div class="bg-white rounded-lg shadow-sm overflow-hidden p-6 m-2">
                    <div class="p-6 bg-gradient-to-r from-blue-50 to-white border-b">
                        <h3 class="text-lg font-semibold text-gray-900">JAM OPERASI MESIN</h3>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead>
                                <tr class="bg-gray-50">
                                    <th>Mesin</th>
                                    <th>Ops</th>
                                    <th>Har</th>
                                    <th>Ggn</th>
                                    <th>Stby/Rsh</th>
                                    <th>Jam/Hari</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($machines as $machine)
                                @php
                                    $data = $laporan->jamOperasi->firstWhere('machine_id', $machine->id);
                                @endphp
                                <tr>
                                    <td>{{ $machine->name }}</td>
                                    <td><input type="number" step="0.1" name="mesin[{{ $machine->id }}][ops]" value="{{ $data->ops ?? '' }}" class="w-full border-gray-300 rounded-md"></td>
                                    <td><input type="number" step="0.1" name="mesin[{{ $machine->id }}][har]" value="{{ $data->har ?? '' }}" class="w-full border-gray-300 rounded-md"></td>
                                    <td><input type="number" step="0.1" name="mesin[{{ $machine->id }}][ggn]" value="{{ $data->ggn ?? '' }}" class="w-full border-gray-300 rounded-md"></td>
                                    <td><input type="number" step="0.1" name="mesin[{{ $machine->id }}][stby]" value="{{ $data->stby ?? '' }}" class="w-full border-gray-300 rounded-md"></td>
                                    <td><input type="number" step="0.1" name="mesin[{{ $machine->id }}][jam_hari]" value="{{ $data->jam_hari ?? '' }}" class="w-full border-gray-300 rounded-md"></td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Jenis Gangguan Mesin -->
                <div class="bg-white rounded-lg shadow-sm overflow-hidden p-6 m-2">
                    <div class="p-6 bg-gradient-to-r from-blue-50 to-white border-b">
                        <h3 class="text-lg font-semibold text-gray-900">JENIS GANGGUAN MESIN</h3>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead>
                                <tr class="bg-gray-50">
                                    <th>Mesin</th>
                                    <th>Mekanik</th>
                                    <th>Elektrik</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($machines as $machine)
                                @php
                                    $data = $laporan->gangguan->firstWhere('machine_id', $machine->id);
                                @endphp
                                <tr>
                                    <td>{{ $machine->name }}</td>
                                    <td><input type="number" name="gangguan[{{ $machine->id }}][mekanik]" value="{{ $data->mekanik ?? '' }}" class="w-full border-gray-300 rounded-md"></td>
                                    <td><input type="number" name="gangguan[{{ $machine->id }}][elektrik]" value="{{ $data->elektrik ?? '' }}" class="w-full border-gray-300 rounded-md"></td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Data Pemeriksaan BBM -->
                <div class="bg-white rounded-lg shadow-sm overflow-hidden p-6 m-2">
                    <div class="p-6 bg-gradient-to-r from-blue-50 to-white border-b">
                        <h3 class="text-lg font-semibold text-gray-900">DATA PEMERIKSAAN BBM</h3>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead>
                                <tr class="bg-gray-50">
                                    <th>Storage Tank 1 (cm)</th>
                                    <th>Storage Tank 1 (liter)</th>
                                    <th>Storage Tank 2 (cm)</th>
                                    <th>Storage Tank 2 (liter)</th>
                                    <th>Total Stok</th>
                                    <th>Service Tank 1 (liter)</th>
                                    <th>Service Tank 1 (%)</th>
                                    <th>Service Tank 2 (liter)</th>
                                    <th>Service Tank 2 (%)</th>
                                    <th>Total Stok Tangki</th>
                                    <th>Terima BBM</th>
                                    <th>Flowmeter 1 Awal</th>
                                    <th>Flowmeter 1 Akhir</th>
                                    <th>Flowmeter 1 Pakai</th>
                                    <th>Flowmeter 2 Awal</th>
                                    <th>Flowmeter 2 Akhir</th>
                                    <th>Flowmeter 2 Pakai</th>
                                    <th>Total Pakai</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($laporan->bbm as $i => $row)
                                <tr>
                                    <td><input type="number" name="bbm[{{ $i }}][storage_tank_1_cm]" value="{{ $row->storage_tank_1_cm }}" class="w-full border-gray-300 rounded-md"></td>
                                    <td><input type="number" name="bbm[{{ $i }}][storage_tank_1_liter]" value="{{ $row->storage_tank_1_liter }}" class="w-full border-gray-300 rounded-md"></td>
                                    <td><input type="number" name="bbm[{{ $i }}][storage_tank_2_cm]" value="{{ $row->storage_tank_2_cm }}" class="w-full border-gray-300 rounded-md"></td>
                                    <td><input type="number" name="bbm[{{ $i }}][storage_tank_2_liter]" value="{{ $row->storage_tank_2_liter }}" class="w-full border-gray-300 rounded-md"></td>
                                    <td><input type="number" name="bbm[{{ $i }}][total_stok]" value="{{ $row->total_stok }}" class="w-full border-gray-300 rounded-md"></td>
                                    <td><input type="number" name="bbm[{{ $i }}][service_tank_1_liter]" value="{{ $row->service_tank_1_liter }}" class="w-full border-gray-300 rounded-md"></td>
                                    <td><input type="number" name="bbm[{{ $i }}][service_tank_1_percentage]" value="{{ $row->service_tank_1_percentage }}" class="w-full border-gray-300 rounded-md"></td>
                                    <td><input type="number" name="bbm[{{ $i }}][service_tank_2_liter]" value="{{ $row->service_tank_2_liter }}" class="w-full border-gray-300 rounded-md"></td>
                                    <td><input type="number" name="bbm[{{ $i }}][service_tank_2_percentage]" value="{{ $row->service_tank_2_percentage }}" class="w-full border-gray-300 rounded-md"></td>
                                    <td><input type="number" name="bbm[{{ $i }}][total_stok_tangki]" value="{{ $row->total_stok_tangki }}" class="w-full border-gray-300 rounded-md"></td>
                                    <td><input type="number" name="bbm[{{ $i }}][terima_bbm]" value="{{ $row->terima_bbm }}" class="w-full border-gray-300 rounded-md"></td>
                                    <td><input type="number" name="bbm[{{ $i }}][flowmeter_1_awal]" value="{{ $row->flowmeter_1_awal }}" class="w-full border-gray-300 rounded-md"></td>
                                    <td><input type="number" name="bbm[{{ $i }}][flowmeter_1_akhir]" value="{{ $row->flowmeter_1_akhir }}" class="w-full border-gray-300 rounded-md"></td>
                                    <td><input type="number" name="bbm[{{ $i }}][flowmeter_1_pakai]" value="{{ $row->flowmeter_1_pakai }}" class="w-full border-gray-300 rounded-md"></td>
                                    <td><input type="number" name="bbm[{{ $i }}][flowmeter_2_awal]" value="{{ $row->flowmeter_2_awal }}" class="w-full border-gray-300 rounded-md"></td>
                                    <td><input type="number" name="bbm[{{ $i }}][flowmeter_2_akhir]" value="{{ $row->flowmeter_2_akhir }}" class="w-full border-gray-300 rounded-md"></td>
                                    <td><input type="number" name="bbm[{{ $i }}][flowmeter_2_pakai]" value="{{ $row->flowmeter_2_pakai }}" class="w-full border-gray-300 rounded-md"></td>
                                    <td><input type="number" name="bbm[{{ $i }}][total_pakai]" value="{{ $row->total_pakai }}" class="w-full border-gray-300 rounded-md"></td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="flex justify-end space-x-4 p-6">
                    <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

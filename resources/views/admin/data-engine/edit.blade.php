@extends('layouts.app')

@section('content')
<div class="flex h-screen bg-gray-100">
    @include('components.sidebar')

    <div class="flex-1 overflow-x-hidden overflow-y-auto">
        <header class="bg-white shadow-sm sticky top-0 z-10">
            <div class="px-6 py-4">
                <h1 class="text-xl font-semibold text-gray-800">Update Data Mesin</h1>
                <p class="text-sm text-gray-500 mt-1">
                    Tanggal: {{ \Carbon\Carbon::parse($date)->format('d F Y') }}
                </p>
            </div>
        </header>

        <div class="py-6">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <form action="{{ route('admin.data-engine.update') }}" method="POST">
                    @csrf
                    <input type="hidden" name="date" value="{{ $date }}">
                    
                    <div class="space-y-8">
                        @foreach($powerPlants as $powerPlant)
                            <div class="bg-white rounded-xl shadow-sm overflow-hidden border border-gray-100">
                                <div class="p-6 bg-gradient-to-r from-blue-50 to-white border-b">
                                    <h2 class="text-lg font-semibold text-gray-900">{{ $powerPlant->name }}</h2>
                                </div>

                                <div class="overflow-x-auto">
                                    <table class="min-w-full divide-y divide-gray-200">
                                        <thead>
                                            <tr class="bg-gray-50">
                                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Mesin</th>
                                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jam</th>
                                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Beban (kW)</th>
                                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">kVAR</th>
                                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Cos φ</th>
                                            </tr>
                                        </thead>
                                        <tbody class="bg-white divide-y divide-gray-200">
                                            @forelse($powerPlant->machines as $index => $machine)
                                                <tr class="hover:bg-gray-50">
                                                    <td class="px-4 py-3 text-sm text-gray-500 border border-gray-200">{{ $index + 1 }}</td>
                                                    <td class="px-4 py-3 border border-gray-200">
                                                        <div class="text-sm font-medium text-gray-900">{{ $machine->name }}</div>
                                                        <input type="hidden" name="machines[{{ $machine->id }}][machine_id]" value="{{ $machine->id }}">
                                                    </td>
                                                    <td class="px-4 py-3 border border-gray-200">
                                                        <input type="time" 
                                                               name="machines[{{ $machine->id }}][time]" 
                                                               value="{{ now()->format('H:i') }}"
                                                               class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                                    </td>
                                                    <td class="px-4 py-3 border border-gray-200">
                                                        <input type="number" 
                                                               name="machines[{{ $machine->id }}][kw]" 
                                                               step="0.01"
                                                               class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                                    </td>
                                                    <td class="px-4 py-3 border border-gray-200">
                                                        <input type="number" 
                                                               name="machines[{{ $machine->id }}][kvar]" 
                                                               step="0.01"
                                                               class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                                    </td>
                                                    <td class="px-4 py-3 border border-gray-200">
                                                        <input type="number" 
                                                               name="machines[{{ $machine->id }}][cos_phi]" 
                                                               step="0.01"
                                                               class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="6" class="px-4 py-8 text-center text-sm text-gray-500 border border-gray-200">
                                                        Tidak ada data mesin untuk unit ini
                                                    </td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        @endforeach

                        <div class="flex justify-end gap-4 mt-6">
                            <a href="{{ route('admin.data-engine.index', ['date' => $date]) }}" 
                               class="px-4 py-2 bg-gray-500 text-white rounded-md hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2">
                                Batal
                            </a>
                            <button type="submit" 
                                    class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                                Simpan Data
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection 
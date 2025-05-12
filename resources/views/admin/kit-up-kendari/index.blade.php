@extends('layouts.app')

@section('content')
<div class="flex h-screen bg-gray-50 overflow-auto">
    @include('components.sidebar')
    
    <div class="flex-1 overflow-auto">
        <div class="p-6">
            <div class="mb-6">
                <h1 class="text-2xl font-semibold text-gray-800">Data KIT UP Kendari</h1>
                <div class="text-sm text-gray-500">Monitoring data pembangkit per tanggal</div>
            </div>

            <div class="bg-white rounded-lg shadow overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider sticky left-0 bg-gray-50 z-10 border-r">
                                PEMBANGKIT IPP
                            </th>
                            @foreach($dates as $date)
                                <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider min-w-[100px]">
                                    {{ str_pad($date, 2, '0', STR_PAD_LEFT) }}
                                </th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($powerPlants as $system => $plants)
                            <!-- System Header -->
                            <tr class="bg-gray-50">
                                <td colspan="{{ count($dates) + 1 }}" class="px-6 py-2 text-sm font-medium text-gray-900">
                                    {{ $system }}
                                </td>
                            </tr>
                            <!-- Power Plants -->
                            @foreach($plants as $plant)
                                <tr>
                                    <td class="px-6 py-2 text-sm text-gray-900 sticky left-0 bg-white z-10 border-r">
                                        {{ $plant }}
                                    </td>
                                    @foreach($dates as $date)
                                        <td class="px-4 py-2 text-sm text-center border">
                                            <!-- Empty cell for now -->
                                        </td>
                                    @endforeach
                                </tr>
                            @endforeach
                            <!-- Total Row for each system -->
                            <tr class="bg-gray-100">
                                <td class="px-6 py-2 text-sm font-medium text-gray-900 sticky left-0 bg-gray-100 z-10 border-r">
                                    TOTAL
                                </td>
                                @foreach($dates as $date)
                                    <td class="px-4 py-2 text-sm text-center font-medium border">
                                        -
                                    </td>
                                @endforeach
                            </tr>
                            <!-- Spacer row -->
                            <tr class="h-2"></tr>
                        @endforeach
                        <!-- Final Total Row -->
                        <tr class="bg-gray-200">
                            <td class="px-6 py-2 text-sm font-medium text-gray-900 sticky left-0 bg-gray-200 z-10 border-r">
                                TOTAL KESELURUHAN
                            </td>
                            @foreach($dates as $date)
                                <td class="px-4 py-2 text-sm text-center font-medium border">
                                    -
                                </td>
                            @endforeach
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    /* Custom scrollbar styles */
    ::-webkit-scrollbar {
        width: 8px;
        height: 8px;
    }
    ::-webkit-scrollbar-track {
        background: #f1f1f1;
    }
    ::-webkit-scrollbar-thumb {
        background: #888;
        border-radius: 4px;
    }
    ::-webkit-scrollbar-thumb:hover {
        background: #555;
    }
</style>
@endpush 
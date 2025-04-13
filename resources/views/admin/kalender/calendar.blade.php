@extends('layouts.app')

@section('content')
<div class="flex">
    @include('components.sidebar')
    
    <div class="flex-1">
        <div class="container mx-auto px-4 py-8">
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-2xl font-semibold text-gray-800">Kalender Operasi</h1>
                <div class="flex space-x-4">
                    <button class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                        <i class="fas fa-plus mr-2"></i>Tambah Jadwal
                    </button>
                    <button class="px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition-colors">
                        <i class="fas fa-filter mr-2"></i>Filter
                    </button>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-lg p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Calendar View -->
                    <div class="bg-gray-50 rounded-lg p-4">
                        <div class="flex justify-between items-center mb-4">
                            <h2 class="text-lg font-semibold">Kalender</h2>
                            <div class="flex space-x-2">
                                <button class="p-2 hover:bg-gray-200 rounded-lg">
                                    <i class="fas fa-chevron-left"></i>
                                </button>
                                <span class="px-4 py-2 font-medium">Januari 2024</span>
                                <button class="p-2 hover:bg-gray-200 rounded-lg">
                                    <i class="fas fa-chevron-right"></i>
                                </button>
                            </div>
                        </div>
                        <div class="grid grid-cols-7 gap-1">
                            <div class="text-center font-medium py-2">M</div>
                            <div class="text-center font-medium py-2">S</div>
                            <div class="text-center font-medium py-2">S</div>
                            <div class="text-center font-medium py-2">R</div>
                            <div class="text-center font-medium py-2">K</div>
                            <div class="text-center font-medium py-2">J</div>
                            <div class="text-center font-medium py-2">S</div>
                            
                            <!-- Calendar days will be populated here -->
                            @for($i = 1; $i <= 31; $i++)
                                <div class="text-center p-2 hover:bg-blue-100 rounded-lg cursor-pointer">
                                    {{ $i }}
                                </div>
                            @endfor
                        </div>
                    </div>

                    <!-- Schedule List -->
                    <div class="bg-gray-50 rounded-lg p-4">
                        <h2 class="text-lg font-semibold mb-4">Jadwal Hari Ini</h2>
                        <div class="space-y-4">
                            <!-- Sample Schedule Items -->
                            <div class="bg-white p-4 rounded-lg shadow-sm">
                                <div class="flex justify-between items-start">
                                    <div>
                                        <h3 class="font-medium text-gray-800">Operasi Unit 1</h3>
                                        <p class="text-sm text-gray-600">08:00 - 16:00</p>
                                    </div>
                                    <span class="px-2 py-1 bg-green-100 text-green-800 text-xs rounded-full">Aktif</span>
                                </div>
                                <p class="mt-2 text-sm text-gray-600">Tim Operasi: John Doe, Jane Smith</p>
                            </div>

                            <div class="bg-white p-4 rounded-lg shadow-sm">
                                <div class="flex justify-between items-start">
                                    <div>
                                        <h3 class="font-medium text-gray-800">Maintenance Unit 2</h3>
                                        <p class="text-sm text-gray-600">10:00 - 14:00</p>
                                    </div>
                                    <span class="px-2 py-1 bg-yellow-100 text-yellow-800 text-xs rounded-full">Scheduled</span>
                                </div>
                                <p class="mt-2 text-sm text-gray-600">Tim Maintenance: Mike Johnson, Sarah Wilson</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 
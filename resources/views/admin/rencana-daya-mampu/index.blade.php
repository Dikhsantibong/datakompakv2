@extends('layouts.app')

@section('content')
<div class="flex h-screen bg-gray-100">
    <!-- Include Sidebar -->
    @include('components.sidebar')

    <!-- Main Content -->
    <div class="flex-1 overflow-x-hidden overflow-y-auto">
        <div class="container mx-auto px-6 py-8">
            <!-- Header Section -->
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-2xl font-semibold text-gray-900">Rencana Daya Mampu Bulanan</h1>
                <div class="flex space-x-3">
                    <button class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg flex items-center">
                        <i class="fas fa-plus mr-2"></i>Tambah Data
                    </button>
                    <button class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-lg flex items-center">
                        <i class="fas fa-file-excel mr-2"></i>Export Excel
                    </button>
                </div>
            </div>

            <!-- Filter Section -->
            <div class="bg-white rounded-lg shadow p-6 mb-6">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Bulan</label>
                        <select class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            <option>Januari</option>
                            <option>Februari</option>
                            <!-- Add other months -->
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Tahun</label>
                        <select class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            <option>2024</option>
                            <option>2023</option>
                            <!-- Add other years -->
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Unit Pembangkit</label>
                        <select class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            <option>Semua Unit</option>
                            <option>Unit 1</option>
                            <!-- Add other units -->
                        </select>
                    </div>
                </div>
            </div>

            <!-- Summary Cards -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="text-lg font-semibold text-gray-900">Total Daya Mampu</h3>
                    <p class="text-3xl font-bold text-blue-600 mt-2">850 MW</p>
                    <p class="text-sm text-gray-500 mt-1">Update terakhir: 2024-03-20</p>
                </div>
                <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="text-lg font-semibold text-gray-900">Rata-rata Daya Mampu</h3>
                    <p class="text-3xl font-bold text-green-600 mt-2">425 MW</p>
                    <p class="text-sm text-gray-500 mt-1">Per unit pembangkit</p>
                </div>
                <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="text-lg font-semibold text-gray-900">Status Unit</h3>
                    <p class="text-3xl font-bold text-orange-600 mt-2">8 Unit</p>
                    <p class="text-sm text-gray-500 mt-1">6 aktif, 2 maintenance</p>
                </div>
            </div>

            <!-- Main Table -->
            <div class="bg-white rounded-lg shadow overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Sistem Kelistrikan</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Mesin Pembangkit</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Site Pembangkit</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Daya PJBTL/SILM (kW)</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Daya Performa Nice Test (Update) (kW)</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">DMP Existing (kW)</th>
                                <th scope="col" colspan="12" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Bulan</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <!-- Sample Row 1 -->
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">1</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Sistem 1</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">PLTD</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Site A</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">1000</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">950</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">900</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">850</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">850</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">850</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">850</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">850</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">850</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">850</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">850</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">850</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">850</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">850</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">850</td>
                            </tr>
                            <!-- Sample Row 2 -->
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">2</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Sistem 2</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">PLTU</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Site B</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">2000</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">1900</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">1800</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">1750</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">1750</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">1750</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">1750</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">1750</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">1750</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">1750</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">1750</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">1750</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">1750</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">1750</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">1750</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <!-- Pagination -->
                <div class="bg-white px-4 py-3 border-t border-gray-200 sm:px-6">
                    <div class="flex justify-between items-center">
                        <div class="text-sm text-gray-700">
                            Showing 1 to 2 of 8 entries
                        </div>
                        <div class="flex space-x-2">
                            <button class="px-3 py-1 border rounded-md hover:bg-gray-50">Previous</button>
                            <button class="px-3 py-1 border rounded-md bg-blue-500 text-white">1</button>
                            <button class="px-3 py-1 border rounded-md hover:bg-gray-50">2</button>
                            <button class="px-3 py-1 border rounded-md hover:bg-gray-50">Next</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 
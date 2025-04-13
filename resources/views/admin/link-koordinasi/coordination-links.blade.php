@extends('layouts.app')

@section('content')
<div class="flex">
    @include('components.sidebar')
    
    <div class="flex-1">
        <div class="container mx-auto px-4 py-8">
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-2xl font-semibold text-gray-800">Link Koordinasi Operasi</h1>
                <button class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                    <i class="fas fa-plus mr-2"></i>Tambah Link
                </button>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <!-- Meeting Room Card -->
                <div class="bg-white rounded-lg shadow-lg p-6">
                    <div class="flex justify-between items-start mb-4">
                        <h2 class="text-lg font-semibold text-gray-800">Meeting Room</h2>
                        <span class="px-2 py-1 bg-green-100 text-green-800 text-xs rounded-full">Aktif</span>
                    </div>
                    <p class="text-gray-600 mb-4">Ruang virtual untuk rapat koordinasi operasi harian</p>
                    <a href="#" class="text-blue-600 hover:text-blue-800 flex items-center mb-4">
                        <i class="fas fa-external-link-alt mr-2"></i>
                        Buka Meeting Room
                    </a>
                    <div class="flex space-x-2">
                        <button class="px-3 py-1 bg-gray-100 text-gray-700 rounded hover:bg-gray-200">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button class="px-3 py-1 bg-red-100 text-red-700 rounded hover:bg-red-200">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </div>

                <!-- Chat Operasi Card -->
                <div class="bg-white rounded-lg shadow-lg p-6">
                    <div class="flex justify-between items-start mb-4">
                        <h2 class="text-lg font-semibold text-gray-800">Chat Operasi</h2>
                        <span class="px-2 py-1 bg-green-100 text-green-800 text-xs rounded-full">Aktif</span>
                    </div>
                    <p class="text-gray-600 mb-4">Grup chat untuk komunikasi tim operasi</p>
                    <a href="#" class="text-blue-600 hover:text-blue-800 flex items-center mb-4">
                        <i class="fas fa-external-link-alt mr-2"></i>
                        Buka Chat
                    </a>
                    <div class="flex space-x-2">
                        <button class="px-3 py-1 bg-gray-100 text-gray-700 rounded hover:bg-gray-200">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button class="px-3 py-1 bg-red-100 text-red-700 rounded hover:bg-red-200">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </div>

                <!-- Dokumen Operasi Card -->
                <div class="bg-white rounded-lg shadow-lg p-6">
                    <div class="flex justify-between items-start mb-4">
                        <h2 class="text-lg font-semibold text-gray-800">Dokumen Operasi</h2>
                        <span class="px-2 py-1 bg-green-100 text-green-800 text-xs rounded-full">Aktif</span>
                    </div>
                    <p class="text-gray-600 mb-4">Repositori dokumen dan SOP operasi</p>
                    <a href="#" class="text-blue-600 hover:text-blue-800 flex items-center mb-4">
                        <i class="fas fa-external-link-alt mr-2"></i>
                        Buka Dokumen
                    </a>
                    <div class="flex space-x-2">
                        <button class="px-3 py-1 bg-gray-100 text-gray-700 rounded hover:bg-gray-200">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button class="px-3 py-1 bg-red-100 text-red-700 rounded hover:bg-red-200">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 
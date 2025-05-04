@extends('layouts.app')

@section('content')
<div class="flex h-screen bg-gray-100">
    @include('components.sidebar')

    <div class="flex-1 flex flex-col overflow-hidden">
        <header class="bg-white shadow-sm">
            <div class="flex items-center justify-between h-16 px-4 sm:px-6 lg:px-8">
                <div class="flex items-center">
                    <h1 class="text-xl font-semibold text-gray-900">Daftar Patrol Check KIT</h1>
                </div>
            </div>
        </header>

        <div class="flex items-center pt-2">
            <x-admin-breadcrumb :breadcrumbs="[['name' => 'Patrol Check KIT', 'url' => route('admin.patrol-check.index')], ['name' => 'Daftar', 'url' => null]]" />
        </div>

        <!-- Main Content Area -->
        <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-100">
            <div class="container mx-auto px-4 sm:px-6 py-8">
                <!-- Success Message -->
                @if(session('success'))
                <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
                @endif

                <!-- Welcome Card -->
                <div class="bg-gradient-to-r from-blue-500 to-blue-600 rounded-lg shadow-sm p-6 mb-6 text-white relative">
                    <div class="max-w-3xl">
                        <h2 class="text-2xl font-bold mb-2">Patrol Check KIT</h2>
                        <p class="text-blue-100 mb-4">Pantau dan kelola data patrol check untuk memastikan kondisi peralatan bantu selalu optimal.</p>
                        <div class="flex flex-wrap gap-3">
                            <a href="{{ route('admin.patrol-check.index') }}" class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-blue-700 rounded-md hover:bg-blue-800">
                                <i class="fas fa-plus mr-2"></i> Tambah Patrol Check Baru
                            </a>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow overflow-hidden">
                    <div class="p-6">
                        <!-- Table Header -->
                        <div class="mb-4 flex items-center justify-between">
                            <h2 class="text-lg font-semibold text-gray-900">Data Patrol Check</h2>
                            <div class="flex space-x-2">
                                <button class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                                    <i class="fas fa-file-excel mr-2"></i>
                                    Export Excel
                                </button>
                                <button class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                                    <i class="fas fa-file-pdf mr-2"></i>
                                    Export PDF
                                </button>
                            </div>
                        </div>

                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Dibuat Oleh</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @forelse($patrols as $index => $patrol)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center border border-gray-200">
                                            {{ ($patrols->currentPage() - 1) * $patrols->perPage() + $index + 1 }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 text-center border border-gray-200">
                                            {{ optional($patrol->created_at)->format('d/m/Y H:i') ?? '-' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 text-center border border-gray-200">
                                            {{ optional($patrol->creator)->name ?? 'N/A' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 text-center border border-gray-200">
                                            @if(optional($patrol->conditions)->contains('status', 'abnormal'))
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                                    Ada Abnormal
                                                </span>
                                            @else
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                    Normal
                                                </span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-center border border-gray-200">
                                            <a href="{{ route('admin.patrol-check.show', $patrol->id ?? 0) }}" 
                                               class="text-blue-600 hover:text-blue-900 mr-3" 
                                               title="Lihat Detail">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('admin.patrol-check.edit', $patrol->id ?? 0) }}" 
                                               class="text-yellow-600 hover:text-yellow-900 mr-3" 
                                               title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <a href="{{ route('admin.patrol-check.export-excel', $patrol->id ?? 0) }}" 
                                               class="text-green-600 hover:text-green-900 mr-3" 
                                               title="Export Excel">
                                                <i class="fas fa-file-excel"></i>
                                            </a>
                                            <a href="{{ route('admin.patrol-check.export-pdf', $patrol->id ?? 0) }}" 
                                               class="text-red-600 hover:text-red-900 mr-3" 
                                               title="Export PDF">
                                                <i class="fas fa-file-pdf"></i>
                                            </a>
                                            <form action="{{ route('admin.patrol-check.destroy', $patrol->id ?? 0) }}" 
                                                  method="POST" 
                                                  class="inline"
                                                  onsubmit="return confirm('Apakah Anda yakin ingin menghapus data ini?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" 
                                                        class="text-red-600 hover:text-red-900" 
                                                        title="Hapus">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="5" class="px-6 py-4 text-center text-gray-500">
                                            Tidak ada data patrol check
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        @if($patrols->hasPages())
                        <div class="mt-4 flex justify-between items-center">
                            <div class="text-sm text-gray-500">
                                Menampilkan {{ $patrols->firstItem() ?? 0 }} sampai {{ $patrols->lastItem() ?? 0 }} dari {{ $patrols->total() }} data
                            </div>
                            <div class="flex justify-end">
                                {{ $patrols->links() }}
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>
@endsection 
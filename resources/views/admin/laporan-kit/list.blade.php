@extends('layouts.app')

@section('content')
<div class="flex h-screen bg-gray-100">
    @include('components.sidebar')

    <div class="flex-1 flex flex-col overflow-hidden">
        <header class="bg-white shadow-sm">
            <div class="flex items-center justify-between h-16 px-4 sm:px-6 lg:px-8">
                <div class="flex items-center">
                    <h1 class="text-xl font-semibold text-gray-900 ml-2">Daftar Laporan KIT 00.00</h1>
                </div>
            </div>
        </header>

        <div class="flex items-center pt-2">
            <x-admin-breadcrumb :breadcrumbs="[['name' => 'Daftar Laporan KIT 00.00', 'url' => null]]" />
        </div>

        <!-- Main Content Area -->
        <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-100">
            <div class="container mx-auto px-4 sm:px-6 ">
                <!-- Welcome Card -->
                <div class="bg-gradient-to-r from-blue-500 to-blue-600 rounded-lg shadow-sm p-6 mb-6 text-white relative">
                    <div class="max-w-3xl">
                        <h2 class="text-2xl font-bold mb-2">Laporan KIT 00.00</h2>
                        <p class="text-blue-100 mb-4">Daftar laporan KIT yang telah diinputkan.</p>
                        <div class="flex flex-wrap gap-3">
                            <a href="{{ route('admin.laporan-kit.index') }}" class="inline-flex items-center px-3 py-1.5 text-sm font-medium text-white bg-blue-700 rounded-md hover:bg-blue-800">
                                <i class="fas fa-plus mr-2"></i> Input Data Baru
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Laporan KIT List -->
                <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                    <div class="p-6">
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Unit</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Dibuat Oleh</th>
                                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @forelse($laporanKits as $laporan)
                                    <tr class="hover:bg-gray-50 transition-colors">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 border-r border-gray-200">
                                            {{ $loop->iteration + ($laporanKits->currentPage() - 1) * $laporanKits->perPage() }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 border-r border-gray-200">
                                            {{ $laporan->tanggal ? \Carbon\Carbon::parse($laporan->tanggal)->format('d F Y') : '-' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 border-r border-gray-200">
                                            {{ $powerPlants->firstWhere('unit_source', $laporan->unit_source)?->name ?? '-' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 border-r border-gray-200">
                                            {{ $laporan->creator->name ?? '-' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center border-r border-gray-200">
                                            <div class="flex items-center justify-center space-x-2">
                                                <a href="{{ route('admin.laporan-kit.show', $laporan) }}"
                                                    class="text-blue-600 hover:text-blue-900"
                                                    title="Lihat Detail">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="{{ route('admin.laporan-kit.edit', $laporan->id) }}"
                                                    class="text-yellow-600 hover:text-yellow-900"
                                                    title="Edit">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <form action="#" 
                                                      method="POST" 
                                                      class="inline-block"
                                                      onsubmit="return confirm('Apakah Anda yakin ingin menghapus data ini?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" 
                                                            class="text-red-600 hover:text-red-900"
                                                            title="Hapus">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                                <a href="{{ route('admin.laporan-kit.export-pdf', $laporan->id) }}" class="text-red-600 hover:text-red-900" title="Download PDF">
                                                    <i class="fas fa-file-pdf"></i>
                                                </a>
                                                <a href="{{ route('admin.laporan-kit.export-excel') }}" class="text-green-600 hover:text-green-900" title="Download Excel">
                                                    <i class="fas fa-file-excel"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="5" class="px-6 py-4 text-center text-sm text-gray-500">
                                            Tidak ada data laporan KIT.
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <div class="mt-4">
                            {{ $laporanKits->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>
@endsection

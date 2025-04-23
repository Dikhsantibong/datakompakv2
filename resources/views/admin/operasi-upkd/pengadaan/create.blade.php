@extends('layouts.app')

@section('content')
<div class="flex h-screen bg-gray-100">
    @include('components.sidebar')

    <div class="flex-1 flex flex-col overflow-hidden">
        <header class="bg-white shadow-sm">
            <div class="flex items-center justify-between h-16 px-4 sm:px-6 lg:px-8">
                <h1 class="text-xl font-semibold text-gray-900">Tambah Data Pengadaan</h1>
            </div>
        </header>

        <div class="flex items-center pt-2">
            <x-admin-breadcrumb :breadcrumbs="[
                ['name' => 'Operasi UPKD', 'url' => null],
                ['name' => 'Pengadaan Barang dan Jasa', 'url' => route('admin.operasi-upkd.pengadaan.index')],
                ['name' => 'Tambah Data', 'url' => null]
            ]" />
        </div>

        <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-100">
            <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8">
                <div class="bg-white rounded-lg shadow p-6">
                    <form action="{{ route('admin.operasi-upkd.pengadaan.store') }}" method="POST" class="space-y-6">
                        @csrf
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="judul" class="block text-sm font-medium text-gray-700">Judul Pekerjaan</label>
                                <input type="text" name="judul" id="judul" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                            </div>

                            <div>
                                <label for="tahun" class="block text-sm font-medium text-gray-700">Tahun</label>
                                <input type="number" name="tahun" id="tahun" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                            </div>

                            <div>
                                <label for="nilai_kontrak" class="block text-sm font-medium text-gray-700">Nilai Kontrak</label>
                                <input type="number" step="0.01" name="nilai_kontrak" id="nilai_kontrak" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            </div>

                            <div>
                                <label for="no_prk" class="block text-sm font-medium text-gray-700">No. PRK</label>
                                <input type="text" name="no_prk" id="no_prk" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            </div>

                            <div>
                                <label for="jenis" class="block text-sm font-medium text-gray-700">Jenis</label>
                                <select name="jenis" id="jenis" class="p-2 mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                                    <option value="">Pilih Jenis</option>
                                    <option value="Rutin">Rutin</option>
                                    <option value="Non Rutin">Non Rutin</option>
                                </select>
                            </div>

                            <div>
                                <label for="intensitas" class="block text-sm font-medium text-gray-700">Intensitas</label>
                                <input type="text" name="intensitas" id="intensitas" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                            </div>

                            <div>
                                <label for="pengusulan" class="block text-sm font-medium text-gray-700">Pengusulan</label>
                                <select name="pengusulan" id="pengusulan" class="p-2 mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                                    <option value="">Pilih Status</option>
                                    <option value="Open">Open</option>
                                    <option value="Close">Close</option>
                                    <option value="On Progress">On Progress</option>
                                </select>
                            </div>

                            <div>
                                <label for="proses_kontrak" class="block text-sm font-medium text-gray-700">Proses Kontrak</label>
                                <select name="proses_kontrak" id="proses_kontrak" class="p-2 mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                                    <option value="">Pilih Status</option>
                                    <option value="Open">Open</option>
                                    <option value="Close">Close</option>
                                    <option value="On Progress">On Progress</option>
                                </select>
                            </div>

                            <div>
                                <label for="pengadaan" class="block text-sm font-medium text-gray-700">Pengadaan</label>
                                <select name="pengadaan" id="pengadaan" class="p-2 mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                                    <option value="">Pilih Status</option>
                                    <option value="Open">Open</option>
                                    <option value="Close">Close</option>
                                    <option value="On Progress">On Progress</option>
                                </select>
                            </div>

                            <div>
                                <label for="pekerjaan_fisik" class="block text-sm font-medium text-gray-700">Pekerjaan Fisik</label>
                                <select name="pekerjaan_fisik" id="pekerjaan_fisik" class="p-2 mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                                    <option value="">Pilih Status</option>
                                    <option value="Open">Open</option>
                                    <option value="Close">Close</option>
                                    <option value="On Progress">On Progress</option>
                                </select>
                            </div>

                            <div>
                                <label for="pemberkasan" class="block text-sm font-medium text-gray-700">Pemberkasan</label>
                                <select name="pemberkasan" id="pemberkasan" class="p-2 mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                                    <option value="">Pilih Status</option>
                                    <option value="Open">Open</option>
                                    <option value="Close">Close</option>
                                    <option value="On Progress">On Progress</option>
                                </select>
                            </div>

                            <div>
                                <label for="pembayaran" class="block text-sm font-medium text-gray-700">Pembayaran</label>
                                <select name="pembayaran" id="pembayaran" class="p-2 mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                                    <option value="">Pilih Status</option>
                                    <option value="Open">Open</option>
                                    <option value="Close">Close</option>
                                    <option value="On Progress">On Progress</option>
                                </select>
                            </div>
                        </div>

                        <div>
                            <label for="keterangan" class="block text-sm font-medium text-gray-700">Keterangan</label>
                            <textarea name="keterangan" id="keterangan" rows="3" class="p-2 mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"></textarea>
                        </div>

                        <div class="flex justify-end space-x-3">
                            <a href="{{ route('admin.operasi-upkd.pengadaan.index') }}" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-gray-700 bg-gray-100 hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                                Batal
                            </a>
                            <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                Simpan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </main>
    </div>
</div>
@endsection 
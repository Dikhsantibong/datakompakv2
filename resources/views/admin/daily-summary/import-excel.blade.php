@extends('layouts.app')
@section('content')
<div class="flex h-screen bg-gray-50 overflow-auto">
    @include('components.sidebar')
    <div id="main-content" class="flex-1 main-content">
        <header class="bg-white shadow-sm sticky top-0 z-20
        ">
            <div class="flex justify-between items-center px-6 py-3">
                <div class="flex items-center gap-x-3">
                    <!-- Mobile Menu Toggle -->
                    <button id="mobile-menu-toggle"
                        class="md:hidden relative inline-flex items-center justify-center rounded-md p-2 text-gray-400 hover:bg-[#009BB9] hover:text-white focus:outline-none focus:ring-2 focus:ring-inset focus:ring-white"
                        aria-controls="mobile-menu" aria-expanded="false">
                        <span class="sr-only">Open main menu</span>
                        <svg class="block size-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                        </svg>
                    </button>

                    <button id="desktop-menu-toggle"
                        class="hidden md:block relative items-center justify-center rounded-md text-gray-400 hover:bg-[#009BB9] p-2 hover:text-white focus:outline-none focus:ring-2 focus:ring-inset focus:ring-white">
                        <span class="sr-only">Open main menu</span>
                        <svg class="block size-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                        </svg>
                    </button>

                    <h1 class="text-xl font-semibold text-gray-800">Dashboard</h1>
                </div>

                <div class="relative">
                    <button id="dropdownToggle" class="flex items-center" onclick="toggleDropdown()">
                        <img src="{{ Auth::user()->avatar ?? asset('foto_profile/admin1.png') }}" class="w-7 h-7 rounded-full mr-2">
                        <span class="text-gray-700 text-sm">{{ Auth::user()->name }}</span>
                        <i class="fas fa-caret-down ml-2 text-gray-600"></i>
                    </button>
                    <div id="dropdown" class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg hidden z-10">
                        <a href="{{ route('logout') }}"
                           class="block px-4 py-2 text-gray-800 hover:bg-gray-200"
                           onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            Logout
                        </a>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
                            @csrf
                            <input type="hidden" name="redirect" value="{{ route('homepage') }}">
                        </form>
                    </div>
                </div>
            </div>
        </header>
        <div class="px-6 py-8">
            @if(session('status'))
                <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-800 rounded">
                    {{ session('status') }}
                </div>
            @endif
            @if($errors->any())
                <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-800 rounded">
                    @foreach($errors->all() as $error)
                        <div>{{ $error }}</div>
                    @endforeach
                </div>
            @endif
            <div class="max-w-full mx-auto bg-white rounded-lg shadow-lg p-8 mb-8 border border-gray-100">
                <h2 class="text-2xl font-bold mb-6 text-gray-800 flex items-center gap-2">
                    <i class="fas fa-file-excel text-green-600"></i> Import Data Ikhtisar Harian dari Excel
                </h2>
                <form action="{{ route('daily-summary.import-excel.process') }}" method="POST" enctype="multipart/form-data" class="space-y-6" id="excel-upload-form">
                    @csrf
                    <div class="flex flex-col md:flex-row md:items-center gap-6 mb-2">
                        <div class="flex-1">
                            <label for="bulan" class="block text-sm font-semibold text-gray-700 mb-1">Pilih Bulan</label>
                            <input type="month" name="bulan" id="bulan" required class="w-full border border-gray-300 rounded-md px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                        </div>
                        <div class="flex-1">
                            <label for="excel" class="block text-sm font-semibold text-gray-700 mb-1">Pilih File Excel</label>
                            <input type="file" name="excel" id="excel" accept=".xlsx,.xls" required class="w-full border border-gray-300 rounded-md px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                        </div>
                    </div>
                    <div class="flex justify-end gap-2">
                        <a href="{{ route('daily-summary.import-excel.result') }}" class="inline-flex items-center gap-2 px-6 py-2.5 bg-gray-500 text-white text-base font-semibold rounded-lg shadow hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-500 transition-all">
                            <i class="fas fa-list"></i> Result
                        </a>
                        <button type="submit" class="inline-flex items-center gap-2 px-6 py-2.5 bg-blue-600 text-white text-base font-semibold rounded-lg shadow hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 transition-all">
                            <i class="fas fa-upload"></i> Upload & Preview
                        </button>
                    </div>
                    @error('excel')
                        <div class="text-red-600 mt-2">{{ $message }}</div>
                    @enderror
                    @error('bulan')
                        <div class="text-red-600 mt-2">{{ $message }}</div>
                    @enderror
                </form>
                <!-- Loader overlay -->
                <div id="upload-loader" class="fixed inset-0  flex items-center justify-center z-50" style="display:none;">
                    <div class="bg-white rounded-lg p-6 flex flex-col items-center shadow-lg">
                        <svg class="animate-spin h-10 w-10 text-blue-600 mb-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"></path>
                        </svg>
                        <div class="text-blue-700 font-semibold text-lg">Mengunggah & Memproses Data...</div>
                    </div>
                </div>
                <script>
                    document.getElementById('excel-upload-form').addEventListener('submit', function() {
                        document.getElementById('upload-loader').style.display = 'flex';
                    });
                </script>
            </div>
            @if(isset($previewSheets) && isset($bulan))
                @php
                    $carbonBulan = \Carbon\Carbon::createFromFormat('Y-m', $bulan);
                    $tabIdx = 0;
                    $isKolaka = session('unit') === 'mysql_kolaka';
                    $isBauBau = session('unit') === 'mysql_bau_bau';
                @endphp
                <h3 class="text-lg font-semibold mb-2">Preview Data per Hari:</h3>
                <div>
                    <!-- Tab Header -->
                    <div class="flex flex-wrap border-b mb-4 gap-y-2 overflow-x-auto scrollbar-thin scrollbar-thumb-gray-300 scrollbar-track-gray-100" id="sheetTabs" style="max-height: 80px;">
                        @foreach($previewSheets as $sheetName => $sheetRows)
                            @php
                                $tanggal = str_pad($sheetName, 2, '0', STR_PAD_LEFT) . '-' . $carbonBulan->format('m-Y');
                            @endphp
                            <button type="button" class="tab-btn px-4 py-2 -mb-px border-b-2 font-medium text-sm focus:outline-none {{ $loop->first ? 'border-blue-600 text-blue-600' : 'border-transparent text-gray-500' }}" data-tab="tab-{{ $sheetName }}" title="{{ $tanggal }}">
                                {{ str_pad($sheetName, 2, '0', STR_PAD_LEFT) }}
                            </button>
                        @endforeach
                    </div>
                    <style>
                        #sheetTabs {
                            scrollbar-width: thin;
                            scrollbar-color: #d1d5db #f3f4f6;
                        }
                        #sheetTabs::-webkit-scrollbar {
                            height: 6px;
                        }
                        #sheetTabs::-webkit-scrollbar-thumb {
                            background: #d1d5db;
                            border-radius: 4px;
                        }
                        #sheetTabs::-webkit-scrollbar-track {
                            background: #f3f4f6;
                        }
                    </style>
                    <!-- Tab Content -->
                    <div>
                        @foreach($previewSheets as $sheetName => $sheetRows)
                            <div class="tab-content" id="tab-{{ $sheetName }}" style="display: {{ $loop->first ? 'block' : 'none' }};">
                                <div class="overflow-x-auto">
                                    <table class="min-w-full divide-y divide-gray-200 border table-fixed" style="min-width: 1800px;">
                                        <thead class="bg-gray-50">
                                            <tr class="text-center border-b">
                                                <th class="px-4 py-3 border-r" rowspan="2">No</th>
                                                <th class="px-4 py-3 border-r" rowspan="2">Unit</th>
                                                <th class="px-4 py-3 border-r" rowspan="2">Mesin</th>
                                                @if($isBauBau)
                                                    <th class="px-4 py-3 border-r" colspan="2">Daya (MW)</th>
                                                    <th class="px-4 py-3 border-r" colspan="2">Beban Puncak (kW)</th>
                                                @else
                                                    <th class="px-4 py-3 border-r" colspan="3">Daya (MW)</th>
                                                    <th class="px-4 py-3 border-r" colspan="2">Beban Puncak (kW)</th>
                                                @endif
                                                <th class="px-4 py-3 border-r" rowspan="2">Ratio Daya Kit (%)</th>
                                                <th class="px-4 py-3 border-r" colspan="2">Produksi (kWh)</th>
                                                <th class="px-4 py-3 border-r" colspan="3">Pemakaian Sendiri</th>
                                                @if($isBauBau)
                                                    <!-- Tidak ada kolom Jam Periode untuk Bau-Bau -->
                                                @else
                                                    <th class="px-4 py-3 border-r" rowspan="2">Jam Periode</th>
                                                @endif
                                                <th class="px-4 py-3 border-r" colspan="5">Jam Operasi</th>
                                                <th class="px-4 py-3 border-r" colspan="2">Trip Non OMC</th>
                                                <th class="px-4 py-3 border-r" colspan="4">Derating</th>
                                                <th class="px-4 py-3 border-r" colspan="4">Kinerja Pembangkit</th>
                                                @if($isBauBau)
                                                    <!-- Tidak ada kolom NCF dan NOF untuk Bau-Bau -->
                                                @else
                                                    <th class="px-4 py-3 border-r" rowspan="2">NCF</th>
                                                    <th class="px-4 py-3 border-r" rowspan="2">NOF</th>
                                                @endif
                                                <th class="px-4 py-3 border-r" rowspan="2">JSI</th>
                                                @if($isBauBau)
                                                    <th class="px-4 py-3 border-r" colspan="3">Pemakaian Bahan Bakar/Baku</th>
                                                @elseif($isKolaka)
                                                    <th class="px-4 py-3 border-r" colspan="5">Pemakaian Bahan Bakar/Baku</th>
                                                @else
                                                    <th class="px-4 py-3 border-r" colspan="5">Pemakaian Bahan Bakar/Baku</th>
                                                @endif
                                                @if($isKolaka)
                                                    <th class="px-4 py-3 border-r" colspan="10">Pemakaian Pelumas</th>
                                                    <th class="px-4 py-3 border-r" colspan="3">Effisiensi</th>
                                                @elseif($isBauBau)
                                                    <th class="px-4 py-3 border-r" colspan="6">Pemakaian Pelumas</th>
                                                    <th class="px-4 py-3 border-r" colspan="3">Effisiensi</th>
                                                @else
                                                    <th class="px-4 py-3 border-r" colspan="8">Pemakaian Pelumas</th>
                                                    <th class="px-4 py-3 border-r" colspan="3">Effisiensi</th>
                                                @endif
                                                <th class="px-4 py-3 border-r" rowspan="2">Keterangan</th>
                                            </tr>
                                            <tr class="text-center border-b bg-gray-100 text-xs">
                                                @if($isBauBau)
                                                    <th class="border-r">Daya Terpasang</th>
                                                    <th class="border-r">Daya Mampu</th>
                                                    <th class="border-r">Siang</th>
                                                    <th class="border-r">Malam</th>
                                                @else
                                                    <th class="border-r">Daya Terpasang</th>
                                                    <th class="border-r">DMN SLO</th>
                                                    <th class="border-r">Daya Mampu</th>
                                                    <th class="border-r">Siang</th>
                                                    <th class="border-r">Malam</th>
                                                @endif
                                                <th class="border-r">Bruto</th>
                                                <th class="border-r">Netto</th>
                                                <th class="border-r">Aux (kWh)</th>
                                                <th class="border-r">Susut Trafo (kWh)</th>
                                                <th class="border-r">Persentase (%)</th>
                                                @if($isBauBau)
                                                    <th class="border-r">OPR</th>
                                                    <th class="border-r">HAR</th>
                                                    <th class="border-r">GGN</th>
                                                    <th class="border-r">STAND BY</th>
                                                    <th class="border-r">AH</th>
                                                @else
                                                    <th class="border-r">OPR</th>
                                                    <th class="border-r">PO</th>
                                                    <th class="border-r">MO</th>
                                                    <th class="border-r">FO</th>
                                                    <th class="border-r">STANDBY</th>
                                                @endif
                                                
                                                <th class="border-r">Mesin (kali)</th>
                                                <th class="border-r">Listrik (kali)</th>
                                                <th class="border-r">EFDH</th>
                                                <th class="border-r">EPDH</th>
                                                <th class="border-r">EUDH</th>
                                                <th class="border-r">ESDH</th>
                                                <th class="border-r">EAF (%)</th>
                                                <th class="border-r">SOF (%)</th>
                                                <th class="border-r">EFOR (%)</th>
                                                <th class="border-r">SdOF (Kali)</th>
                                                @if($isKolaka)
                                                <th class="border-r">HSD (Liter)</th>
                                                <th class="border-r">B40 (Liter)</th>
                                                <th class="border-r">MFO (Liter)</th>
                                                @elseif($isBauBau)
                                                    <th class="border-r">HSD (Liter)</th>
                                                    <th class="border-r">B40 (Liter)</th>
                                                @else
                                                    <th class="border-r">HSD (Liter)</th>
                                                    <th class="border-r">B35 (Liter)</th>
                                                    <th class="border-r">MFO (Liter)</th>
                                                @endif
                                                @if($isBauBau)
                                                    <th class="border-r">Total BBM (Liter)</th>
                                                @else
                                                    <th class="border-r">Total BBM (Liter)</th>
                                                    <th class="border-r">Air (M³)</th>
                                                @endif
                                                @if($isKolaka)
                                                    <th class="border-r">MEDITRAN SMX 15W/40</th>
                                                    <th class="border-r">SALYX 420</th>
                                                    <th class="border-r">DIALA B</th>
                                                    <th class="border-r">Turbo Oil 46</th>
                                                    <th class="border-r">TurbOil 68</th>
                                                    <th class="border-r">MEDITRAN S40</th>
                                                    <th class="border-r">Turbo Lube XT68</th>
                                                    <th class="border-r">Trafo Lube A</th>
                                                    <th class="border-r">MEDITRAN SX 15W/40</th>
                                                    <th class="border-r">TOTAL</th>
                                                @elseif($isBauBau)
                                                    <th class="border-r">Meditran S40</th>
                                                    <th class="border-r">Meditran SMX 15W/40</th>
                                                    <th class="border-r">Meditran S30</th>
                                                    <th class="border-r">Turbo oil 68</th>
                                                    <th class="border-r">Trafolube A</th>
                                                    <th class="border-r">TOTAL</th>
                                                @else
                                                    <th class="border-r">Meditran SX 15W/40 CH-4 (LITER)</th>
                                                    <th class="border-r">Salyx 420 (LITER)</th>
                                                    <th class="border-r">Salyx 430 (LITER)</th>
                                                    <th class="border-r">TravoLube A (LITER)</th>
                                                    <th class="border-r">Turbolube 46 (LITER)</th>
                                                    <th class="border-r">Turbolube 68 (LITER)</th>
                                                    <th class="border-r">Shell Argina S3 (LITER)</th>
                                                    <th class="border-r">TOTAL (LITER)</th>
                                                @endif
                                                    <th class="border-r">SFC/SCC (LITER/KWH)</th>
                                                    <th class="border-r">TARA KALOR/NPHR (KCAL/KWH)</th>
                                                    <th class="border-r">SLC (CC/KWH)</th>
                                            </tr>
                                        </thead>
                                        <tbody class="bg-white divide-y divide-gray-200">
                                            @foreach($sheetRows as $i => $row)
                                                <tr>
                                                    <td class="px-4 py-3 border-r text-center">{{ $i + 1 }}</td>
                                                    <td class="px-4 py-3 border-r">{{ $row['unit'] ?? '' }}</td>
                                                    <td class="px-4 py-3 border-r">{{ $row['machine_name'] ?? '' }}</td>
                                                    @foreach($fieldOrder as $field)
                                                        @if($field !== 'unit' && $field !== 'machine_name')
                                                            <td class="px-4 py-3 border-r">{{ $row[$field] }}</td>
                                                        @endif
                                                    @endforeach
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
                <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        const tabBtns = document.querySelectorAll('.tab-btn');
                        const tabContents = document.querySelectorAll('.tab-content');
                        tabBtns.forEach(btn => {
                            btn.addEventListener('click', function() {
                                // Remove active
                                tabBtns.forEach(b => b.classList.remove('border-blue-600', 'text-blue-600'));
                                tabBtns.forEach(b => b.classList.add('border-transparent', 'text-gray-500'));
                                tabContents.forEach(tc => tc.style.display = 'none');
                                // Set active
                                this.classList.add('border-blue-600', 'text-blue-600');
                                this.classList.remove('border-transparent', 'text-gray-500');
                                document.getElementById(this.dataset.tab).style.display = 'block';
                            });
                        });
                    });
                </script>
                <form action="{{ route('daily-summary.import-excel.save') }}" method="POST" id="save-excel-form">
                    @csrf
                    <input type="hidden" name="bulan" value="{{ $bulan }}">
                    <input type="hidden" name="preview_data" value="{{ base64_encode(serialize($previewSheets)) }}">
                    <div class="flex justify-end mt-6">
                        <button type="submit" class="inline-flex items-center gap-2 px-6 py-2.5 bg-green-600 text-white text-base font-semibold rounded-lg shadow hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 transition-all">
                            <i class="fas fa-save"></i> Simpan ke Database
                        </button>
                    </div>
                </form>
            @endif
            @if(isset($debugRows) && isset($firstSheetName) && count($debugRows) > 0)
                <div class="mb-6 p-4 bg-yellow-50 border border-yellow-300 rounded">
                    <div class="font-semibold mb-2 text-yellow-800">Debug: Hasil parsing Excel (sheet {{ $firstSheetName }}, baris 13-25):</div>
                    <div class="overflow-x-auto mb-2">
                        <table class="text-xs border">
                            <thead><tr><th class="border px-2">Row</th>@for($i=0;$i<count($debugRows[0]);$i++)<th class="border px-2">{{$i}}</th>@endfor</tr></thead>
                            <tbody>
                                @foreach($debugRows as $rIdx => $row)
                                    <tr><td class="border px-2">{{$rIdx}}</td>@foreach($row as $colIdx => $val)<td class="border px-2">{{$colIdx}}:{{ $val }}</td>@endforeach</tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @if(isset($debugMappedRows) && count($debugMappedRows) > 0)
                    <div class="font-semibold mb-2 text-yellow-800">Debug: Hasil mapping field ke value (sheet {{ $firstSheetName }}, baris 13-25):</div>
                    <div class="overflow-x-auto">
                        <table class="text-xs border">
                            <thead><tr><th class="border px-2">Row</th>@foreach(array_keys($debugMappedRows[0]) as $field)<th class="border px-2">{{$field}}</th>@endforeach</tr></thead>
                            <tbody>
                                @foreach($debugMappedRows as $rIdx => $row)
                                    <tr><td class="border px-2">{{$rIdx}}</td>@foreach($row as $val)<td class="border px-2">{{ $val }}</td>@endforeach</tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @endif
                    <div class="text-xs text-yellow-700 mt-2">Cocokkan urutan kolom (index 0, 1, 2, ...) dengan header Excel Anda dan hasil mapping field. Beri tahu jika ada field yang nilainya tidak sesuai index Excel.</div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

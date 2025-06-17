@if($data['type'] === 'data-engine')
    <div class="flex justify-end mb-2 sticky top-0 bg-white z-20">
        <form method="GET" action="{{ route('admin.monitoring-datakompak.export-excel') }}">
            <input type="hidden" name="tab" value="{{ $data['type'] }}">
            <input type="hidden" name="date" value="{{ $data['date'] }}">
            <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-3 py-1.5 rounded  text-sm">
                <i class="fas fa-file-excel mr-1"></i> Export Excel
            </button>
        </form>
    </div>
    <div class="mb-4 sticky top-12 bg-white z-20">
        <h3 class="text-lg font-semibold text-gray-900">Data Engine - {{ \Carbon\Carbon::parse($data['date'])->isoFormat('D MMMM Y') }}</h3>
        <p class="text-sm text-gray-500 mb-2">Arahkan cursor ke data yang terceklis untuk melihat detail data</p>
    </div>
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200 border">
            <thead class="bg-gray-50">
                <tr>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border-r sticky left-0 bg-gray-50 z-10">Unit</th>
                    @foreach($data['hours'] as $hour)
                        <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider border-r">
                            {{ \Carbon\Carbon::parse($hour)->format('H:i') }}
                        </th>
                    @endforeach
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @foreach($data['powerPlants'] as $powerPlant)
                    @if($powerPlant->name !== 'UP KENDARI')
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 border-r sticky left-0 bg-white z-10">
                                {{ app('App\Http\Controllers\Admin\MonitoringDatakompakController')->formatUnitName($powerPlant->name) }}
                            </td>
                            @foreach($data['hours'] as $hour)
                                @php
                                    $log = $powerPlant->hourlyLog[$hour] ?? null;
                                @endphp
                                <td class="px-6 py-4 whitespace-nowrap text-center border-r relative group">
                                    @if($powerPlant->hourlyStatus[$hour])
                                        <span class="inline-flex items-center justify-center size-6 bg-green-100 text-green-800 rounded-full cursor-pointer">
                                            <i class="fas fa-check text-xs"></i>
                                        </span>
                                        @if($log)
                                        <div class="hidden group-hover:block absolute z-20 bg-white border rounded-lg shadow-lg p-4 min-w-[300px] text-left -translate-x-1/2 left-1/2 mt-2">
                                            <div class="text-sm">
                                                <div class="flex justify-between items-center mb-2">
                                                    <p class="font-semibold">{{ $powerPlant->name }}</p>
                                                    <p class="text-gray-500">{{ \Carbon\Carbon::parse($hour)->format('d/m/Y H:i') }}</p>
                                                </div>
                                                <div class="grid grid-cols-2 gap-2">
                                                    <p class="text-gray-600">Status:</p>
                                                    <p>{{ $log->status ?? '-' }}</p>
                                                    <p class="text-gray-600">KW:</p>
                                                    <p>{{ $log->kw ?? '-' }}</p>
                                                    <p class="text-gray-600">KVAR:</p>
                                                    <p>{{ $log->kvar ?? '-' }}</p>
                                                    <p class="text-gray-600">Cos Phi:</p>
                                                    <p>{{ $log->cos_phi ?? '-' }}</p>
                                                    <p class="text-gray-600">Keterangan:</p>
                                                    <p>{{ $log->keterangan ?? '-' }}</p>
                                                </div>
                                            </div>
                                        </div>
                                        @endif
                                    @else
                                        <span class="inline-flex items-center justify-center size-6 bg-red-100 text-red-800 rounded-full">
                                            <i class="fas fa-times text-xs"></i>
                                        </span>
                                    @endif
                                </td>
                            @endforeach
                        </tr>
                    @endif
                @endforeach
            </tbody>
        </table>
    </div>
@elseif($data['type'] === 'bahan-bakar')
    <div class="flex justify-end mb-2">
        <form method="GET" action="{{ route('admin.monitoring-datakompak.export-excel') }}">
            <input type="hidden" name="tab" value="{{ $data['type'] }}">
            <input type="hidden" name="month" value="{{ $data['month'] }}">
            <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-3 py-1.5 rounded  text-sm">
                <i class="fas fa-file-excel mr-1"></i> Export Excel
            </button>
        </form>
    </div>
    <div class="mb-4">
        <h3 class="text-lg font-semibold text-gray-900">Data Bahan Bakar - {{ \Carbon\Carbon::parse($data['month'])->isoFormat('MMMM Y') }}</h3>
        <p class="text-sm text-gray-500 mb-2">Arahkan cursor ke data yang terceklis untuk melihat detail data</p>
    </div>
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200 border">
            <thead>
                <tr>
                    <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider sticky left-0 bg-white z-10 border-r">
                        Unit
                    </th>
                    @foreach($data['dates'] as $date)
                        <th class="px-3 py-3 bg-gray-50 text-center text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap border-r">
                            {{ $date }}
                        </th>
                    @endforeach
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @foreach($data['powerPlants'] as $powerPlant)
                    @if($powerPlant->name !== 'UP KENDARI')
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 sticky left-0 bg-white z-10 border-r">
                                {{ app('App\Http\Controllers\Admin\MonitoringDatakompakController')->formatUnitName($powerPlant->name) }}
                            </td>
                            @foreach($data['dates'] as $index => $date)
                                @php
                                    $fullDate = \Carbon\Carbon::createFromFormat('d/m', $date)->format('Y-m-d');
                                    $dayData = $powerPlant->dailyData[$fullDate];
                                @endphp
                                <td class="px-3 py-4 whitespace-nowrap text-center border-r relative group">
                                    @if($dayData['status'])
                                        <span class="inline-flex items-center justify-center size-6 bg-green-100 text-green-800 rounded-full cursor-pointer">
                                            <i class="fas fa-check text-xs"></i>
                                        </span>
                                        <div class="hidden group-hover:block absolute z-20 bg-white border rounded-lg shadow-lg p-4 min-w-[300px] text-left -translate-x-1/2 left-1/2 mt-2">
                                            <div class="text-sm">
                                                <div class="flex justify-between items-center mb-2">
                                                    <p class="font-semibold">{{ $powerPlant->name }}</p>
                                                    <p class="text-gray-500">{{ \Carbon\Carbon::parse($fullDate)->format('d/m/Y') }}</p>
                                                </div>
                                                <div class="grid grid-cols-2 gap-2">
                                                    <p class="text-gray-600">Jenis BBM:</p>
                                                    <p>{{ $dayData['data']->jenis_bbm }}</p>
                                                    <p class="text-gray-600">Saldo Awal:</p>
                                                    <p>{{ number_format($dayData['data']->saldo_awal, 2) }}</p>
                                                    <p class="text-gray-600">Penerimaan:</p>
                                                    <p>{{ number_format($dayData['data']->penerimaan, 2) }}</p>
                                                    <p class="text-gray-600">Pemakaian:</p>
                                                    <p>{{ number_format($dayData['data']->pemakaian, 2) }}</p>
                                                    <p class="text-gray-600">Saldo Akhir:</p>
                                                    <p>{{ number_format($dayData['data']->saldo_akhir, 2) }}</p>
                                                    <p class="text-gray-600">HOP:</p>
                                                    <p>{{ number_format($dayData['data']->hop, 2) }}</p>
                                                </div>
                                                @if($dayData['data']->catatan_transaksi)
                                                    <div class="mt-2 pt-2 border-t">
                                                        <p class="text-gray-600">Catatan:</p>
                                                        <p class="mt-1">{{ $dayData['data']->catatan_transaksi }}</p>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    @else
                                        <span class="inline-flex items-center justify-center size-6 bg-red-100 text-red-800 rounded-full">
                                            <i class="fas fa-times text-xs"></i>
                                        </span>
                                    @endif
                                </td>
                            @endforeach
                        </tr>
                    @endif
                @endforeach
            </tbody>
        </table>
    </div>
@elseif($data['type'] === 'pelumas')
    <div class="flex justify-end mb-2">
        <form method="GET" action="{{ route('admin.monitoring-datakompak.export-excel') }}">
            <input type="hidden" name="tab" value="{{ $data['type'] }}">
            <input type="hidden" name="month" value="{{ $data['month'] }}">
            <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-3 py-1.5 rounded  text-sm">
                <i class="fas fa-file-excel mr-1"></i> Export Excel
            </button>
        </form>
    </div>
    <div class="mb-4">
        <h3 class="text-lg font-semibold text-gray-900">Data Pelumas - {{ \Carbon\Carbon::parse($data['month'])->isoFormat('MMMM Y') }}</h3>
        <p class="text-sm text-gray-500 mb-2">Arahkan cursor ke data yang terceklis untuk melihat detail data</p>
    </div>
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200 border">
            <thead>
                <tr>
                    <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider sticky left-0 bg-white z-10 border-r">
                        Unit
                    </th>
                    @foreach($data['dates'] as $date)
                        <th class="px-3 py-3 bg-gray-50 text-center text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap border-r">
                            {{ $date }}
                        </th>
                    @endforeach
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @foreach($data['powerPlants'] as $powerPlant)
                    @if($powerPlant->name !== 'UP KENDARI')
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 sticky left-0 bg-white z-10 border-r">
                                {{ app('App\Http\Controllers\Admin\MonitoringDatakompakController')->formatUnitName($powerPlant->name) }}
                            </td>
                            @foreach($data['dates'] as $index => $date)
                                @php
                                    $fullDate = \Carbon\Carbon::createFromFormat('d/m', $date)->format('Y-m-d');
                                    $dayData = $powerPlant->dailyData[$fullDate];
                                @endphp
                                <td class="px-3 py-4 whitespace-nowrap text-center border-r relative group">
                                    @if($dayData['status'])
                                        <span class="inline-flex items-center justify-center size-6 bg-green-100 text-green-800 rounded-full cursor-pointer">
                                            <i class="fas fa-check text-xs"></i>
                                        </span>
                                        <div class="hidden group-hover:block absolute z-20 bg-white border rounded-lg shadow-lg p-4 min-w-[300px] text-left -translate-x-1/2 left-1/2 mt-2">
                                            <div class="text-sm">
                                                <div class="flex justify-between items-center mb-2">
                                                    <p class="font-semibold">{{ $powerPlant->name }}</p>
                                                    <p class="text-gray-500">{{ \Carbon\Carbon::parse($fullDate)->format('d/m/Y') }}</p>
                                                </div>
                                                <div class="grid grid-cols-2 gap-2">
                                                    <p class="text-gray-600">Jenis Pelumas:</p>
                                                    <p>{{ $dayData['data']->jenis_pelumas }}</p>
                                                    <p class="text-gray-600">Saldo Awal:</p>
                                                    <p>{{ number_format($dayData['data']->saldo_awal, 2) }}</p>
                                                    <p class="text-gray-600">Penerimaan:</p>
                                                    <p>{{ number_format($dayData['data']->penerimaan, 2) }}</p>
                                                    <p class="text-gray-600">Pemakaian:</p>
                                                    <p>{{ number_format($dayData['data']->pemakaian, 2) }}</p>
                                                    <p class="text-gray-600">Saldo Akhir:</p>
                                                    <p>{{ number_format($dayData['data']->saldo_akhir, 2) }}</p>
                                                </div>
                                                @if($dayData['data']->catatan_transaksi)
                                                    <div class="mt-2 pt-2 border-t">
                                                        <p class="text-gray-600">Catatan:</p>
                                                        <p class="mt-1">{{ $dayData['data']->catatan_transaksi }}</p>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    @else
                                        <span class="inline-flex items-center justify-center size-6 bg-red-100 text-red-800 rounded-full">
                                            <i class="fas fa-times text-xs"></i>
                                        </span>
                                    @endif
                                </td>
                            @endforeach
                        </tr>
                    @endif
                @endforeach
            </tbody>
        </table>
    </div>
@elseif($data['type'] === 'daily-summary')
    <div class="flex justify-end mb-2 sticky top-0 bg-white z-20">
        <form method="GET" action="{{ route('admin.monitoring-datakompak.export-excel') }}">
            <input type="hidden" name="tab" value="{{ $data['type'] }}">
            <input type="hidden" name="month" value="{{ $data['dates'][0] ? \Carbon\Carbon::parse($data['dates'][0])->format('Y-m') : '' }}">
            <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-3 py-1.5 rounded  text-sm">
                <i class="fas fa-file-excel mr-1"></i> Export Excel
            </button>
        </form>
    </div>
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200 border">
            <thead>
                <tr>
                    <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider sticky left-0 bg-white z-10 border-r">
                        Unit
                    </th>
                    @foreach($data['dates'] as $date)
                        <th class="px-3 py-3 bg-gray-50 text-center text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap border-r">
                            {{ \Carbon\Carbon::parse($date)->format('d/m') }}
                        </th>
                    @endforeach
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @foreach($data['powerPlants'] as $powerPlant)
                    @if($powerPlant->name !== 'UP KENDARI')
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 sticky left-0 bg-white z-10 border-r">
                                {{ app('App\Http\Controllers\Admin\MonitoringDatakompakController')->formatUnitName($powerPlant->name) }}
                            </td>
                            @foreach($data['dates'] as $date)
                                <td class="px-3 py-4 whitespace-nowrap text-center border-r">
                                    @if($powerPlant->dailyStatus[$date])
                                        <span class="inline-flex items-center justify-center size-6 bg-green-100 text-green-800 rounded-full">
                                            <i class="fas fa-check text-xs"></i>
                                        </span>
                                    @else
                                        <span class="inline-flex items-center justify-center size-6 bg-red-100 text-red-800 rounded-full">
                                            <i class="fas fa-times text-xs"></i>
                                        </span>
                                    @endif
                                </td>
                            @endforeach
                        </tr>
                    @endif
                @endforeach
            </tbody>
        </table>
    </div>
@elseif($data['type'] === 'meeting-shift')
    <div class="flex justify-end mb-2 sticky top-0 bg-white z-20">
        <form method="GET" action="{{ route('admin.monitoring-datakompak.export-excel') }}">
            <input type="hidden" name="tab" value="{{ $data['type'] }}">
            <input type="hidden" name="month" value="{{ $data['dates'][0] ? \Carbon\Carbon::parse($data['dates'][0])->format('Y-m') : '' }}">
            <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-3 py-1.5 rounded  text-sm">
                <i class="fas fa-file-excel mr-1"></i> Export Excel
            </button>
        </form>
    </div>
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200 border">
            <thead>
                <tr>
                    <th class="px-6 py-3 bg-gray-50 text-center text-xs font-medium text-gray-500 uppercase tracking-wider sticky left-0 bg-white z-10 border-r justify-center items-center flex">
                        Unit
                    </th>
                    @foreach($data['dates'] as $date)
                        <th class="px-3 py-3 bg-gray-50 text-center text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap border-r" colspan="4">
                            {{ \Carbon\Carbon::parse($date)->format('d/m') }}
                        </th>
                    @endforeach
                </tr>
                <tr>
                    <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider sticky left-0 bg-white z-10 border-r"></th>
                    @foreach($data['dates'] as $date)
                        @foreach($data['shifts'] as $shift)
                            <th class="px-3 py-3 bg-gray-50 text-center text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap border-r border-t">
                                {{ $shift }}
                            </th>
                        @endforeach
                    @endforeach
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @foreach($data['powerPlants'] as $powerPlant)
                    @if($powerPlant->name !== 'UP KENDARI')
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 sticky left-0 bg-white z-10 border-r">
                                {{ app('App\Http\Controllers\Admin\MonitoringDatakompakController')->formatUnitName($powerPlant->name) }}
                            </td>
                            @foreach($data['dates'] as $date)
                                @foreach($data['shifts'] as $shift)
                                    <td class="px-3 py-4 whitespace-nowrap text-center border-r">
                                        @if($powerPlant->shiftStatus[$date . '_' . $shift])
                                            <span class="inline-flex items-center justify-center size-6 bg-green-100 text-green-800 rounded-full">
                                                <i class="fas fa-check text-xs"></i>
                                            </span>
                                        @else
                                            <span class="inline-flex items-center justify-center size-6 bg-red-100 text-red-800 rounded-full">
                                                <i class="fas fa-times text-xs"></i>
                                            </span>
                                        @endif
                                    </td>
                                @endforeach
                            @endforeach
                        </tr>
                    @endif
                @endforeach
            </tbody>
        </table>
    </div>
@elseif($data['type'] === 'laporan-kit')
    <div class="flex justify-end mb-2">
        <form method="GET" action="{{ route('admin.monitoring-datakompak.export-excel') }}">
            <input type="hidden" name="tab" value="{{ $data['type'] }}">
            <input type="hidden" name="month" value="{{ $data['month'] }}">
            <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-3 py-1.5 rounded  text-sm">
                <i class="fas fa-file-excel mr-1"></i> Export Excel
            </button>
        </form>
    </div>
    <div class="mb-4">
        <h3 class="text-lg font-semibold text-gray-900">Laporan KIT - {{ \Carbon\Carbon::parse($data['month'])->isoFormat('MMMM Y') }}</h3>
        <p class="text-sm text-gray-500 mb-2">Arahkan cursor ke data yang terceklis untuk melihat detail data</p>
    </div>
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200 border">
            <thead>
                <tr>
                    <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider sticky left-0 bg-white z-10 border-r">
                        Unit
                    </th>
                    @foreach($data['dates'] as $date)
                        <th class="px-3 py-3 bg-gray-50 text-center text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap border-r">
                            {{ $date }}
                        </th>
                    @endforeach
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @foreach($data['powerPlants'] as $powerPlant)
                    @if($powerPlant->name !== 'UP KENDARI')
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 sticky left-0 bg-white z-10 border-r">
                                {{ app('App\Http\Controllers\Admin\MonitoringDatakompakController')->formatUnitName($powerPlant->name) }}
                            </td>
                            @foreach($data['dates'] as $index => $date)
                                @php
                                    $fullDate = \Carbon\Carbon::createFromFormat('d/m', $date)->format('Y-m-d');
                                    $dayData = $powerPlant->dailyData[$fullDate];
                                @endphp
                                <td class="px-3 py-4 whitespace-nowrap text-center border-r relative group">
                                    @if($dayData['status'])
                                        <span class="inline-flex items-center justify-center size-6 bg-green-100 text-green-800 rounded-full cursor-pointer">
                                            <i class="fas fa-check text-xs"></i>
                                        </span>
                                        <div class="hidden group-hover:block absolute z-20 bg-white border rounded-lg shadow-lg p-4 min-w-[300px] text-left -translate-x-1/2 left-1/2 mt-2">
                                            <div class="text-sm">
                                                <div class="flex justify-between items-center mb-2">
                                                    <p class="font-semibold">{{ $powerPlant->name }}</p>
                                                    <p class="text-gray-500">{{ \Carbon\Carbon::parse($fullDate)->format('d/m/Y') }}</p>
                                                </div>
                                                @if($dayData['data'])
                                                    <div class="space-y-2">
                                                        <p><span class="font-medium">Jam Operasi:</span> {{ $dayData['data']->jamOperasi->count() }} entries</p>
                                                        <p><span class="font-medium">Gangguan:</span> {{ $dayData['data']->gangguan->count() }} entries</p>
                                                        <p><span class="font-medium">BBM:</span> {{ $dayData['data']->bbm->count() }} entries</p>
                                                        <p><span class="font-medium">KWH:</span> {{ $dayData['data']->kwh->count() }} entries</p>
                                                        <p><span class="font-medium">Pelumas:</span> {{ $dayData['data']->pelumas->count() }} entries</p>
                                                        <p><span class="font-medium">Bahan Kimia:</span> {{ $dayData['data']->bahanKimia->count() }} entries</p>
                                                        <p><span class="font-medium">Beban Tertinggi:</span> {{ $dayData['data']->bebanTertinggi->count() }} entries</p>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    @else
                                        <span class="inline-flex items-center justify-center size-6 bg-red-100 text-red-800 rounded-full">
                                            <i class="fas fa-times text-xs"></i>
                                        </span>
                                    @endif
                                </td>
                            @endforeach
                        </tr>
                    @endif
                @endforeach
            </tbody>
        </table>
    </div>
@elseif($data['type'] === 'flm-inspection')
    <div class="flex justify-end mb-2">
        <form method="GET" action="{{ route('admin.monitoring-datakompak.export-excel') }}">
            <input type="hidden" name="tab" value="{{ $data['type'] }}">
            <input type="hidden" name="month" value="{{ $data['month'] }}">
            <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-3 py-1.5 rounded  text-sm">
                <i class="fas fa-file-excel mr-1"></i> Export Excel
            </button>
        </form>
    </div>
    <div class="mb-4">
        <h3 class="text-lg font-semibold text-gray-900">FLM Inspection - {{ \Carbon\Carbon::parse($data['month'])->isoFormat('MMMM Y') }}</h3>
        <p class="text-sm text-gray-500 mb-2">Arahkan cursor ke data yang terceklis untuk melihat detail data</p>
    </div>
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200 border">
            <thead>
                <tr>
                    <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider sticky left-0 bg-white z-10 border-r">
                        Unit
                    </th>
                    @foreach($data['dates'] as $date)
                        <th class="px-3 py-3 bg-gray-50 text-center text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap border-r">
                            {{ $date }}
                        </th>
                    @endforeach
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @foreach($data['powerPlants'] as $powerPlant)
                    @if($powerPlant->name !== 'UP KENDARI')
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 sticky left-0 bg-white z-10 border-r">
                                {{ app('App\Http\Controllers\Admin\MonitoringDatakompakController')->formatUnitName($powerPlant->name) }}
                            </td>
                            @foreach($data['dates'] as $index => $date)
                                @php
                                    $fullDate = \Carbon\Carbon::createFromFormat('d/m', $date)->format('Y-m-d');
                                    $dayData = $powerPlant->dailyData[$fullDate];
                                @endphp
                                <td class="px-3 py-4 whitespace-nowrap text-center border-r relative group">
                                    @if($dayData['status'])
                                        <span class="inline-flex items-center justify-center size-6 bg-green-100 text-green-800 rounded-full cursor-pointer">
                                            <i class="fas fa-check text-xs"></i>
                                        </span>
                                        <div class="hidden group-hover:block absolute z-20 bg-white border rounded-lg shadow-lg p-4 min-w-[300px] text-left -translate-x-1/2 left-1/2 mt-2">
                                            <div class="text-sm">
                                                <div class="flex justify-between items-center mb-2">
                                                    <p class="font-semibold">{{ $powerPlant->name }}</p>
                                                    <p class="text-gray-500">{{ \Carbon\Carbon::parse($fullDate)->format('d/m/Y') }}</p>
                                                </div>
                                                <div class="space-y-2">
                                                    <p><span class="font-medium">Total Inspections:</span> {{ $dayData['data']->count() }}</p>
                                                    @foreach($dayData['data'] as $inspection)
                                                        <div class="border-t pt-2">
                                                            <p><span class="font-medium">Time:</span> {{ $inspection->time }}</p>
                                                            <p><span class="font-medium">Shift:</span> {{ $inspection->shift }}</p>
                                                            <p><span class="font-medium">Status:</span> {{ $inspection->status }}</p>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                        </div>
                                    @else
                                        <span class="inline-flex items-center justify-center size-6 bg-red-100 text-red-800 rounded-full">
                                            <i class="fas fa-times text-xs"></i>
                                        </span>
                                    @endif
                                </td>
                            @endforeach
                        </tr>
                    @endif
                @endforeach
            </tbody>
        </table>
    </div>
@elseif($data['type'] === 'five-s5r')
    <div class="flex justify-end mb-2">
        <form method="GET" action="{{ route('admin.monitoring-datakompak.export-excel') }}">
            <input type="hidden" name="tab" value="{{ $data['type'] }}">
            <input type="hidden" name="month" value="{{ $data['month'] }}">
            <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-3 py-1.5 rounded  text-sm">
                <i class="fas fa-file-excel mr-1"></i> Export Excel
            </button>
        </form>
    </div>
    <div class="mb-4">
        <h3 class="text-lg font-semibold text-gray-900">5S 5R - {{ \Carbon\Carbon::parse($data['month'])->isoFormat('MMMM Y') }}</h3>
        <p class="text-sm text-gray-500 mb-2">Arahkan cursor ke data yang terceklis untuk melihat detail data</p>
    </div>
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200 border">
            <thead>
                <tr>
                    <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider sticky left-0 bg-white z-10 border-r">
                        Unit
                    </th>
                    @foreach($data['dates'] as $date)
                        <th class="px-3 py-3 bg-gray-50 text-center text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap border-r">
                            {{ $date }}
                        </th>
                    @endforeach
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @foreach($data['powerPlants'] as $powerPlant)
                    @if($powerPlant->name !== 'UP KENDARI')
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 sticky left-0 bg-white z-10 border-r">
                                {{ app('App\Http\Controllers\Admin\MonitoringDatakompakController')->formatUnitName($powerPlant->name) }}
                            </td>
                            @foreach($data['dates'] as $index => $date)
                                @php
                                    $fullDate = \Carbon\Carbon::createFromFormat('d/m', $date)->format('Y-m-d');
                                    $dayData = $powerPlant->dailyData[$fullDate];
                                @endphp
                                <td class="px-3 py-4 whitespace-nowrap text-center border-r relative group">
                                    @if($dayData['status'])
                                        <span class="inline-flex items-center justify-center size-6 bg-green-100 text-green-800 rounded-full cursor-pointer">
                                            <i class="fas fa-check text-xs"></i>
                                        </span>
                                        <div class="hidden group-hover:block absolute z-20 bg-white border rounded-lg shadow-lg p-4 min-w-[300px] text-left -translate-x-1/2 left-1/2 mt-2">
                                            <div class="text-sm">
                                                <div class="flex justify-between items-center mb-2">
                                                    <p class="font-semibold">{{ $powerPlant->name }}</p>
                                                    <p class="text-gray-500">{{ \Carbon\Carbon::parse($fullDate)->format('d/m/Y') }}</p>
                                                </div>
                                                <div class="space-y-2">
                                                    <p><span class="font-medium">Total Batches:</span> {{ $dayData['data']->count() }}</p>
                                                    @foreach($dayData['data'] as $batch)
                                                        <div class="border-t pt-2">
                                                            <p><span class="font-medium">Pemeriksaan:</span> {{ $batch->pemeriksaan->count() }}</p>
                                                            <p><span class="font-medium">Program Kerja:</span> {{ $batch->programKerja->count() }}</p>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                        </div>
                                    @else
                                        <span class="inline-flex items-center justify-center size-6 bg-red-100 text-red-800 rounded-full">
                                            <i class="fas fa-times text-xs"></i>
                                        </span>
                                    @endif
                                </td>
                            @endforeach
                        </tr>
                    @endif
                @endforeach
            </tbody>
        </table>
    </div>
@elseif($data['type'] === 'patrol-check')
    <div class="flex justify-end mb-2">
        <form method="GET" action="{{ route('admin.monitoring-datakompak.export-excel') }}">
            <input type="hidden" name="tab" value="{{ $data['type'] }}">
            <input type="hidden" name="month" value="{{ $data['month'] }}">
            <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-3 py-1.5 rounded  text-sm">
                <i class="fas fa-file-excel mr-1"></i> Export Excel
            </button>
        </form>
    </div>
    <div class="mb-4">
        <h3 class="text-lg font-semibold text-gray-900">Patrol Check - {{ \Carbon\Carbon::parse($data['month'])->isoFormat('MMMM Y') }}</h3>
        <p class="text-sm text-gray-500 mb-2">Arahkan cursor ke data yang terceklis untuk melihat detail data</p>
    </div>
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200 border">
            <thead>
                <tr>
                    <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider sticky left-0 bg-white z-10 border-r">
                        Unit
                    </th>
                    @foreach($data['dates'] as $date)
                        <th class="px-3 py-3 bg-gray-50 text-center text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap border-r">
                            {{ $date }}
                        </th>
                    @endforeach
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @foreach($data['powerPlants'] as $powerPlant)
                    @if($powerPlant->name !== 'UP KENDARI')
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 sticky left-0 bg-white z-10 border-r">
                                {{ app('App\Http\Controllers\Admin\MonitoringDatakompakController')->formatUnitName($powerPlant->name) }}
                            </td>
                            @foreach($data['dates'] as $index => $date)
                                @php
                                    $fullDate = \Carbon\Carbon::createFromFormat('d/m', $date)->format('Y-m-d');
                                    $dayData = $powerPlant->dailyData[$fullDate];
                                @endphp
                                <td class="px-3 py-4 whitespace-nowrap text-center border-r relative group">
                                    @if($dayData['status'])
                                        <span class="inline-flex items-center justify-center size-6 bg-green-100 text-green-800 rounded-full cursor-pointer">
                                            <i class="fas fa-check text-xs"></i>
                                        </span>
                                        <div class="hidden group-hover:block absolute z-20 bg-white border rounded-lg shadow-lg p-4 min-w-[300px] text-left -translate-x-1/2 left-1/2 mt-2">
                                            <div class="text-sm">
                                                <div class="flex justify-between items-center mb-2">
                                                    <p class="font-semibold">{{ $powerPlant->name }}</p>
                                                    <p class="text-gray-500">{{ \Carbon\Carbon::parse($fullDate)->format('d/m/Y') }}</p>
                                                </div>
                                                <div class="space-y-2">
                                                    <p><span class="font-medium">Total Patrols:</span> {{ $dayData['data']->count() }}</p>
                                                    @foreach($dayData['data'] as $patrol)
                                                        <div class="border-t pt-2">
                                                            <p><span class="font-medium">Time:</span> {{ $patrol->time }}</p>
                                                            <p><span class="font-medium">Shift:</span> {{ $patrol->shift }}</p>
                                                            <p><span class="font-medium">Status:</span> {{ $patrol->status }}</p>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                        </div>
                                    @else
                                        <span class="inline-flex items-center justify-center size-6 bg-red-100 text-red-800 rounded-full">
                                            <i class="fas fa-times text-xs"></i>
                                        </span>
                                    @endif
                                </td>
                            @endforeach
                        </tr>
                    @endif
                @endforeach
            </tbody>
        </table>
    </div>
@elseif($data['type'] === 'bahan-kimia')
    <div class="flex justify-end mb-2">
        <form method="GET" action="{{ route('admin.monitoring-datakompak.export-excel') }}">
            <input type="hidden" name="tab" value="{{ $data['type'] }}">
            <input type="hidden" name="month" value="{{ $data['month'] }}">
            <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-3 py-1.5 rounded  text-sm">
                <i class="fas fa-file-excel mr-1"></i> Export Excel
            </button>
        </form>
    </div>
    <div class="mb-4">
        <h3 class="text-lg font-semibold text-gray-900">Data Bahan Kimia - {{ \Carbon\Carbon::parse($data['month'])->isoFormat('MMMM Y') }}</h3>
        <p class="text-sm text-gray-500 mb-2">Arahkan cursor ke data yang terceklis untuk melihat detail data</p>
    </div>
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200 border">
            <thead>
                <tr>
                    <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider sticky left-0 bg-white z-10 border-r">
                        Unit
                    </th>
                    @foreach($data['dates'] as $date)
                        <th class="px-3 py-3 bg-gray-50 text-center text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap border-r">
                            {{ $date }}
                        </th>
                    @endforeach
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @foreach($data['powerPlants'] as $powerPlant)
                    @if($powerPlant->name !== 'UP KENDARI')
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 sticky left-0 bg-white z-10 border-r">
                                {{ app('App\Http\Controllers\Admin\MonitoringDatakompakController')->formatUnitName($powerPlant->name) }}
                            </td>
                            @foreach($data['dates'] as $index => $date)
                                @php
                                    $fullDate = \Carbon\Carbon::createFromFormat('d/m', $date)->format('Y-m-d');
                                    $dayData = $powerPlant->dailyData[$fullDate];
                                @endphp
                                <td class="px-3 py-4 whitespace-nowrap text-center border-r relative group">
                                    @if($dayData['status'])
                                        <span class="inline-flex items-center justify-center size-6 bg-green-100 text-green-800 rounded-full cursor-pointer">
                                            <i class="fas fa-check text-xs"></i>
                                        </span>
                                        <div class="hidden group-hover:block absolute z-20 bg-white border rounded-lg shadow-lg p-4 min-w-[300px] text-left -translate-x-1/2 left-1/2 mt-2">
                                            <div class="text-sm">
                                                <div class="flex justify-between items-center mb-2">
                                                    <p class="font-semibold">{{ $powerPlant->name }}</p>
                                                    <p class="text-gray-500">{{ \Carbon\Carbon::parse($fullDate)->format('d/m/Y') }}</p>
                                                </div>
                                                <div class="grid grid-cols-2 gap-2">
                                                    <p class="text-gray-600">Jenis Bahan:</p>
                                                    <p>{{ $dayData['data']->jenis_bahan }}</p>
                                                    <p class="text-gray-600">Saldo Awal:</p>
                                                    <p>{{ number_format($dayData['data']->saldo_awal, 2) }}</p>
                                                    <p class="text-gray-600">Penerimaan:</p>
                                                    <p>{{ number_format($dayData['data']->penerimaan, 2) }}</p>
                                                    <p class="text-gray-600">Pemakaian:</p>
                                                    <p>{{ number_format($dayData['data']->pemakaian, 2) }}</p>
                                                    <p class="text-gray-600">Saldo Akhir:</p>
                                                    <p>{{ number_format($dayData['data']->saldo_akhir, 2) }}</p>
                                                </div>
                                                @if($dayData['data']->catatan_transaksi)
                                                    <div class="mt-2 pt-2 border-t">
                                                        <p class="text-gray-600">Catatan:</p>
                                                        <p class="mt-1">{{ $dayData['data']->catatan_transaksi }}</p>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    @else
                                        <span class="inline-flex items-center justify-center size-6 bg-red-100 text-red-800 rounded-full">
                                            <i class="fas fa-times text-xs"></i>
                                        </span>
                                    @endif
                                </td>
                            @endforeach
                        </tr>
                    @endif
                @endforeach
            </tbody>
        </table>
    </div>
@else
    <table class="min-w-full divide-y divide-gray-200 border">
        <thead>
            <tr>
                <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider sticky left-0 bg-white z-10 border-r">
                    Unit
                </th>
                @foreach($data['dates'] as $date)
                    <th class="px-3 py-3 bg-gray-50 text-center text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap border-r" colspan="4">
                        {{ \Carbon\Carbon::parse($date)->format('d/m') }}
                    </th>
                @endforeach
            </tr>
            <tr>
                <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider sticky left-0 bg-white z-10 border-r"></th>
                @foreach($data['dates'] as $date)
                    @foreach($data['shifts'] as $shift)
                        <th class="px-3 py-3 bg-gray-50 text-center text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap border-r">
                            {{ $shift }}
                        </th>
                    @endforeach
                @endforeach
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
            @foreach($data['powerPlants'] as $powerPlant)
                @if($powerPlant->name !== 'UP KENDARI')
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 sticky left-0 bg-white z-10 border-r">
                            {{ app('App\Http\Controllers\Admin\MonitoringDatakompakController')->formatUnitName($powerPlant->name) }}
                        </td>
                        @foreach($data['dates'] as $date)
                            @foreach($data['shifts'] as $shift)
                                <td class="px-3 py-4 whitespace-nowrap text-center border-r">
                                    @if($powerPlant->shiftStatus[$date . '_' . $shift])
                                        <span class="inline-flex items-center justify-center size-6 bg-green-100 text-green-800 rounded-full">
                                            <i class="fas fa-check text-xs"></i>
                                        </span>
                                    @else
                                        <span class="inline-flex items-center justify-center size-6 bg-red-100 text-red-800 rounded-full">
                                            <i class="fas fa-times text-xs"></i>
                                        </span>
                                    @endif
                                </td>
                            @endforeach
                        @endforeach
                    </tr>
                @endif
            @endforeach
        </tbody>
    </table>
@endif

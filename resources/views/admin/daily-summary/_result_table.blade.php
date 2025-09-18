@php
$isKolaka = session('unit') === 'mysql_kolaka';
$isBauBau = session('unit') === 'mysql_bau_bau';
$isPoasia = session('unit') === 'mysql_poasia';
$isPoasiaContainerized = session('unit') === 'mysql_poasia_containerized';
$isWuaWua = session('unit') === 'mysql_wua_wua';
@endphp
<div class="w-full overflow-x-auto mb-8 bg-white rounded-lg shadow-md p-4">
<table class="min-w-full divide-y divide-gray-200 border table-fixed min-w-[1800px]" style="min-width: 1800px;">
    <thead class="bg-gray-50">
        <tr class="text-center border-b">
            <th class="px-4 py-3 border-r" rowspan="2">No</th>
            <th class="px-4 py-3 border-r" rowspan="2">Unit</th>
            <th class="px-4 py-3 border-r" rowspan="2">Mesin</th>
            @if($isBauBau || $isPoasia || $isPoasiaContainerized)
                <th class="px-4 py-3 border-r" colspan="2">Daya (MW)</th>
                <th class="px-4 py-3 border-r" colspan="2">Beban Puncak (kW)</th>
            @else
                <th class="px-4 py-3 border-r" colspan="3">Daya (MW)</th>
                <th class="px-4 py-3 border-r" colspan="2">Beban Puncak (kW)</th>
            @endif
            <th class="px-4 py-3 border-r" rowspan="2">Ratio Daya Kit (%)</th>
            <th class="px-4 py-3 border-r" colspan="2">Produksi (kWh)</th>
            <th class="px-4 py-3 border-r" colspan="3">Pemakaian Sendiri</th>
            @if($isBauBau || $isPoasia || $isPoasiaContainerized)
                <!-- Tidak ada kolom Jam Periode untuk Bau-Bau -->
            @else
                <th class="px-4 py-3 border-r" rowspan="2">Jam Periode</th>
            @endif
            <th class="px-4 py-3 border-r" colspan="5">Jam Operasi</th>
            <th class="px-4 py-3 border-r" colspan="2">Trip Non OMC</th>
            <th class="px-4 py-3 border-r" colspan="4">Derating</th>
            <th class="px-4 py-3 border-r" colspan="4">Kinerja Pembangkit</th>
            @if($isBauBau || $isPoasia || $isPoasiaContainerized)
                <!-- Tidak ada kolom NCF dan NOF untuk Bau-Bau -->
            @else
                <th class="px-4 py-3 border-r" rowspan="2">NCF</th>
                <th class="px-4 py-3 border-r" rowspan="2">NOF</th>
            @endif
            <th class="px-4 py-3 border-r" rowspan="2">JSI</th>
            @if($isBauBau)
                <th class="px-4 py-3 border-r" colspan="3">Pemakaian Bahan Bakar/Baku</th>
            @elseif($isKolaka || $isWuaWua)
                <th class="px-4 py-3 border-r" colspan="5">Pemakaian Bahan Bakar/Baku</th>
            @elseif($isPoasia || $isPoasiaContainerized)
                <th class="px-4 py-3 border-r" colspan="10">Pemakaian Bahan Bakar/Baku</th>
            @else
                <th class="px-4 py-3 border-r" colspan="6">Pemakaian Bahan Bakar/Baku</th>
            @endif
            @if($isKolaka)
                <th class="px-4 py-3 border-r" colspan="10">Pemakaian Pelumas</th>
            @elseif($isBauBau)
                <th class="px-4 py-3 border-r" colspan="6">Pemakaian Pelumas</th>
            @elseif($isPoasia || $isPoasiaContainerized)
                <th class="px-4 py-3 border-r" colspan="5">Pemakaian Pelumas</th>
            @elseif($isWuaWua)
                <th class="px-4 py-3 border-r" colspan="7">Pemakaian Pelumas</th>
            @else
                <th class="px-4 py-3 border-r" colspan="8">Pemakaian Pelumas</th>
            @endif
            <th class="px-4 py-3 border-r" colspan="3">Effisiensi</th>
            <th class="px-4 py-3 border-r" rowspan="2">Keterangan</th>
        </tr>
        <tr class="text-center border-b bg-gray-100 text-xs">
            @if($isBauBau || $isPoasia || $isPoasiaContainerized)
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
            @elseif($isPoasia || $isPoasiaContainerized)
                <th class="border-r">SH</th>
                <th class="border-r">PO</th>
                <th class="border-r">MO</th>
                <th class="border-r">FO</th>
                <th class="border-r">STANDBY</th>
            @else
                <th class="border-r">OPR</th>
                <th class="border-r">STANDBY</th>
                <th class="border-r">PO</th>
                <th class="border-r">MO</th>
                <th class="border-r">FO</th>
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
            @if($isBauBau)
                <th class="border-r">HSD (Liter)</th>
                <th class="border-r">B40 (Liter)</th>
                <th class="border-r">Total BBM (Liter)</th>
            @elseif($isKolaka || $isWuaWua)
                <th class="border-r">HSD (Liter)</th>
                <th class="border-r">B35 (Liter)</th>
                <th class="border-r">MFO (Liter)</th>
                <th class="border-r">Total BBM (Liter)</th>
                <th class="border-r">Air (M³)</th>
            @elseif($isPoasia)
                <th class="border-r">HSD (Liter)</th>
                <th class="border-r">B10 (Liter)</th>
                <th class="border-r">B15 (Liter)</th>
                <th class="border-r">B20 (Liter)</th>
                <th class="border-r">B25 (Liter)</th>
                <th class="border-r">B35 (Liter)</th>
                <th class="border-r">MFO (Liter)</th>
                <th class="border-r">Total BBM (Liter)</th>
                <th class="border-r">Batubara (KG)</th>
                <th class="border-r">Air (M³)</th>
            @else
                <th class="border-r">HSD (Liter)</th>
                <th class="border-r">B35 (Liter)</th>
                <th class="border-r">B40 (Liter)</th>
                <th class="border-r">MFO (Liter)</th>
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
            @elseif($isWuaWua)
                <th class="border-r">Meditran SMX 15W/40 (LITER)</th>
                <th class="border-r">Salyx 420 (LITER)</th>
                <th class="border-r">Salyx 430 (LITER)</th>
                <th class="border-r">TravoLube A (LITER)</th>
                <th class="border-r">Turbolube 46 (LITER)</th>
                <th class="border-r">Turbolube 68 (LITER)</th>
                <th class="border-r">TOTAL (LITER)</th>
            @elseif($isBauBau)
                <th class="border-r">Meditran S40</th>
                <th class="border-r">Meditran SMX 15W/40</th>
                <th class="border-r">Meditran S30</th>
                <th class="border-r">Turbo oil 68</th>
                <th class="border-r">Trafolube A</th>
                <th class="border-r">TOTAL</th>
            @elseif($isPoasia)
                <th class="border-r">Shell Argina S3 (LITER)</th>
                <th class="border-r">Thermo XT 32 (LITER)</th>
                <th class="border-r">Shell Diala B (LITER)</th>
                <th class="border-r">Meditran SX CH-4 (LITER)</th>
                <th class="border-r">TOTAL (LITER)</th>
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
        @forelse($rows as $i => $row)
            <tr>
                <td class="px-4 py-3 border-r text-center">{{ $i + 1 }}</td>
                <td class="px-4 py-3 border-r">{{ $row->powerPlant->name ?? '-' }}</td>
                <td class="px-4 py-3 border-r">{{ $row->machine_name }}</td>
                @if($isBauBau || $isPoasia)
                    <td class="px-4 py-3 border-r text-right">{{ $row->installed_power }}</td>
                    <td class="px-4 py-3 border-r text-right">{{ $row->capable_power }}</td>
                    <td class="px-4 py-3 border-r text-right">{{ $row->peak_load_day }}</td>
                    <td class="px-4 py-3 border-r text-right">{{ $row->peak_load_night }}</td>
                @else
                    <td class="px-4 py-3 border-r text-right">{{ $row->installed_power }}</td>
                    <td class="px-4 py-3 border-r text-right">{{ $row->dmn_power }}</td>
                    <td class="px-4 py-3 border-r text-right">{{ $row->capable_power }}</td>
                    <td class="px-4 py-3 border-r text-right">{{ $row->peak_load_day }}</td>
                    <td class="px-4 py-3 border-r text-right">{{ $row->peak_load_night }}</td>
                @endif
                <td class="px-4 py-3 border-r text-right">{{ $row->kit_ratio }}</td>
                <td class="px-4 py-3 border-r text-right">{{ $row->gross_production }}</td>
                <td class="px-4 py-3 border-r text-right">{{ $row->net_production }}</td>
                <td class="px-4 py-3 border-r text-right">{{ $row->aux_power }}</td>
                <td class="px-4 py-3 border-r text-right">{{ $row->transformer_losses }}</td>
                <td class="px-4 py-3 border-r text-right">{{ $row->usage_percentage }}</td>
                @if($isBauBau)
                    <td class="px-4 py-3 border-r text-right">{{ $row->operating_hours }}</td>
                    <td class="px-4 py-3 border-r text-right">{{ $row->planned_outage }}</td>
                    <td class="px-4 py-3 border-r text-right">{{ $row->forced_outage }}</td>
                    <td class="px-4 py-3 border-r text-right">{{ $row->standby_hours }}</td>
                    <td class="px-4 py-3 border-r text-right">{{ $row->ah }}</td>
                @elseif($isPoasia)
                    <td class="px-4 py-3 border-r text-right">{{ $row->operating_hours }}</td>
                    <td class="px-4 py-3 border-r text-right">{{ $row->planned_outage }}</td>
                    <td class="px-4 py-3 border-r text-right">{{ $row->maintenance_outage }}</td>
                    <td class="px-4 py-3 border-r text-right">{{ $row->forced_outage }}</td>
                    <td class="px-4 py-3 border-r text-right">{{ $row->standby_hours }}</td>
                @else
                    <td class="px-4 py-3 border-r text-right">{{ $row->period_hours }}</td>
                    <td class="px-4 py-3 border-r text-right">{{ $row->operating_hours }}</td>
                    <td class="px-4 py-3 border-r text-right">{{ $row->standby_hours }}</td>
                    <td class="px-4 py-3 border-r text-right">{{ $row->planned_outage }}</td>
                    <td class="px-4 py-3 border-r text-right">{{ $row->maintenance_outage }}</td>
                    <td class="px-4 py-3 border-r text-right">{{ $row->forced_outage }}</td>
                @endif
                <td class="px-4 py-3 border-r text-right">{{ $row->trip_machine }}</td>
                <td class="px-4 py-3 border-r text-right">{{ $row->trip_electrical }}</td>
                <td class="px-4 py-3 border-r text-right">{{ $row->efdh }}</td>
                <td class="px-4 py-3 border-r text-right">{{ $row->epdh }}</td>
                <td class="px-4 py-3 border-r text-right">{{ $row->eudh }}</td>
                <td class="px-4 py-3 border-r text-right">{{ $row->esdh }}</td>
                <td class="px-4 py-3 border-r text-right">{{ $row->eaf }}</td>
                <td class="px-4 py-3 border-r text-right">{{ $row->sof }}</td>
                <td class="px-4 py-3 border-r text-right">{{ $row->efor }}</td>
                <td class="px-4 py-3 border-r text-right">{{ $row->sdof }}</td>
                @if(!$isBauBau && !$isPoasia && !$isPoasiaContainerized)
                    <td class="px-4 py-3 border-r text-right">{{ $row->ncf }}</td>
                    <td class="px-4 py-3 border-r text-right">{{ $row->nof }}</td>
                @endif
                <td class="px-4 py-3 border-r text-right">{{ $row->jsi }}</td>
                @if($isBauBau)
                    <td class="px-4 py-3 border-r text-right">{{ $row->hsd_fuel }}</td>
                    <td class="px-4 py-3 border-r text-right">{{ $row->b40_fuel }}</td>
                    <td class="px-4 py-3 border-r text-right">{{ $row->total_fuel }}</td>
                    <td class="px-4 py-3 border-r text-right">{{ $row->meditran_s40 }}</td>
                    <td class="px-4 py-3 border-r text-right">{{ $row->meditran_smx_15w40 }}</td>
                    <td class="px-4 py-3 border-r text-right">{{ $row->meditran_s30 }}</td>
                    <td class="px-4 py-3 border-r text-right">{{ $row->turbolube_68 }}</td>
                    <td class="px-4 py-3 border-r text-right">{{ $row->trafo_lube_a }}</td>
                    <td class="px-4 py-3 border-r text-right">{{ $row->total_oil }}</td>
                @elseif($isKolaka)
                    <td class="px-4 py-3 border-r text-right">{{ $row->hsd_fuel }}</td>
                    <td class="px-4 py-3 border-r text-right">{{ $row->b35_fuel }}</td>
                    <td class="px-4 py-3 border-r text-right">{{ $row->mfo_fuel }}</td>
                    <td class="px-4 py-3 border-r text-right">{{ $row->total_fuel }}</td>
                    <td class="px-4 py-3 border-r text-right">{{ $row->water_usage }}</td>
                    <td class="px-4 py-3 border-r text-right">{{ $row->meditran_oil }}</td>
                    <td class="px-4 py-3 border-r text-right">{{ $row->salyx_420 }}</td>
                    <td class="px-4 py-3 border-r text-right">{{ $row->diala_b }}</td>
                    <td class="px-4 py-3 border-r text-right">{{ $row->turbolube_46 }}</td>
                    <td class="px-4 py-3 border-r text-right">{{ $row->turboil_68 }}</td>
                    <td class="px-4 py-3 border-r text-right">{{ $row->meditran_s40 }}</td>
                    <td class="px-4 py-3 border-r text-right">{{ $row->turbo_lube_xt68 }}</td>
                    <td class="px-4 py-3 border-r text-right">{{ $row->trafo_lube_a }}</td>
                    <td class="px-4 py-3 border-r text-right">{{ $row->meditran_sx_15w40 }}</td>
                    <td class="px-4 py-3 border-r text-right">{{ $row->total_oil }}</td>
                @elseif($isPoasia || $isPoasiaContainerized)
                    <td class="px-4 py-3 border-r text-right">{{ $row->hsd_fuel }}</td>
                    <td class="px-4 py-3 border-r text-right">{{ $row->b10_fuel }}</td>
                    <td class="px-4 py-3 border-r text-right">{{ $row->b15_fuel }}</td>
                    <td class="px-4 py-3 border-r text-right">{{ $row->b20_fuel }}</td>
                    <td class="px-4 py-3 border-r text-right">{{ $row->b25_fuel }}</td>
                    <td class="px-4 py-3 border-r text-right">{{ $row->b35_fuel }}</td>
                    <td class="px-4 py-3 border-r text-right">{{ $row->mfo_fuel }}</td>
                    <td class="px-4 py-3 border-r text-right">{{ $row->total_fuel }}</td>
                    <td class="px-4 py-3 border-r text-right">{{ $row->batubara }}</td>
                    <td class="px-4 py-3 border-r text-right">{{ $row->water_usage }}</td>
                    <td class="px-4 py-3 border-r text-right">{{ $row->shell_argina_s3 }}</td>
                    <td class="px-4 py-3 border-r text-right">{{ $row->thermo_xt_32 }}</td>
                    <td class="px-4 py-3 border-r text-right">{{ $row->shell_diala_b }}</td>
                    <td class="px-4 py-3 border-r text-right">{{ $row->meditran_sx_ch4 }}</td>
                    <td class="px-4 py-3 border-r text-right">{{ $row->total_oil }}</td>
                @elseif ($isWuaWua)
                    <td class="px-4 py-3 border-r text-right">{{ $row->hsd_fuel }}</td>
                    <td class="px-4 py-3 border-r text-right">{{ $row->b35_fuel }}</td>
                    <td class="px-4 py-3 border-r text-right">{{ $row->mfo_fuel }}</td>
                    <td class="px-4 py-3 border-r text-right">{{ $row->total_fuel }}</td>
                    <td class="px-4 py-3 border-r text-right">{{ $row->water_usage }}</td>
                    <td class="px-4 py-3 border-r text-right">{{ $row->meditran_sx_15w40 }}</td>
                    <td class="px-4 py-3 border-r text-right">{{ $row->salyx_420 }}</td>
                    <td class="px-4 py-3 border-r text-right">{{ $row->salyx_430 }}</td>
                    <td class="px-4 py-3 border-r text-right">{{ $row->travolube_a }}</td>
                    <td class="px-4 py-3 border-r text-right">{{ $row->turbolube_46 }}</td>
                    <td class="px-4 py-3 border-r text-right">{{ $row->turbolube_68 }}</td>
                    <td class="px-4 py-3 border-r text-right">{{ $row->total_oil }}</td>
                @else
                    <td class="px-4 py-3 border-r text-right">{{ $row->hsd_fuel }}</td>
                    <td class="px-4 py-3 border-r text-right">{{ $row->b35_fuel }}</td>
                    <td class="px-4 py-3 border-r text-right">{{ $row->b40_fuel }}</td>
                    <td class="px-4 py-3 border-r text-right">{{ $row->mfo_fuel }}</td>
                    <td class="px-4 py-3 border-r text-right">{{ $row->total_fuel }}</td>
                    <td class="px-4 py-3 border-r text-right">{{ $row->water_usage }}</td>
                    <td class="px-4 py-3 border-r text-right">{{ $row->meditran_oil }}</td>
                    <td class="px-4 py-3 border-r text-right">{{ $row->salyx_420 }}</td>
                    <td class="px-4 py-3 border-r text-right">{{ $row->salyx_430 }}</td>
                    <td class="px-4 py-3 border-r text-right">{{ $row->travolube_a }}</td>
                    <td class="px-4 py-3 border-r text-right">{{ $row->turbolube_46 }}</td>
                    <td class="px-4 py-3 border-r text-right">{{ $row->turbolube_68 }}</td>
                    <td class="px-4 py-3 border-r text-right">{{ $row->shell_argina_s3 }}</td>
                    <td class="px-4 py-3 border-r text-right">{{ $row->total_oil }}</td>
                @endif
                <td class="px-4 py-3 border-r text-right">{{ $row->sfc_scc }}</td>
                <td class="px-4 py-3 border-r text-right">{{ $row->nphr }}</td>
                <td class="px-4 py-3 border-r text-right">{{ $row->slc }}</td>
                <td class="px-4 py-3 border-r">{{ $row->notes }}</td>
            </tr>
        @empty
            <tr><td colspan="60" class="text-center py-4 text-gray-400">Tidak ada data</td></tr>
        @endforelse
    </tbody>
</table>
</div>


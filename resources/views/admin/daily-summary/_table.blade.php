<div class="bg-white rounded shadow-md p-4 mb-6">
    <h2 class="text-lg font-semibold text-gray-800 mb-4">{{ $unit->name }}</h2>
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200 border table-fixed" style="min-width: 3800px;">
            <thead class="bg-gray-50">
                <tr class="text-center border-b">
                    <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider border-r w-mesin">Mesin</th>
                    <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider border-r w-daya">Daya (MW)</th>
                    <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider border-r w-beban">Beban Puncak (kW)</th>
                    <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider border-r w-beban">Ratio Daya Kit (%)</th>
                    <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider border-r w-produksi">Produksi (kWh)</th>
                    <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider border-r w-pemakaian-sendiri">Pemakaian Sendiri</th>
                    <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider border-r w-jam-operasi">Jam Periode</th>
                    <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider border-r w-jam-operasi">Jam Operasi</th>
                    <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider border-r w-trip">Trip Non OMC</th>
                    <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider border-r w-derating">Derating</th>
                    <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider border-r w-kinerja">Kinerja Pembangkit</th>
                    <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider border-r w-capability">Capability Factor (%)</th>
                    <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider border-r w-nof">Nett Operating Factor (%)</th>
                    <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider border-r w-jsi">JSI</th>
                    <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider border-r w-bahan-bakar">Pemakaian Bahan Bakar/Baku</th>
                    <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider border-r w-pelumas">Pemakaian Pelumas</th>
                    <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider border-r w-efisiensi">Effisiensi</th>
                    <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider border-r w-keterangan">Ket.</th>
                </tr>
                <tr class="bg-gray-100 text-xs">
                    <th class="border-r"></th>
                    <th class="px-4 py-2 border-r">
                        <div class="grid grid-cols-3 gap-0">
                            <span class="subcol-border px-2 mr-4 text-center">Terpasang</span>
                            <span class="subcol-border px-2 mr-4 text-center" style="margin-left: 10px;">DMN</span>
                            <span class="px-2 text-center" style="margin-left: 10px;">Mampu</span>
                        </div>
                    </th>
                    <th class="px-4 py-2 border-r">
                        <div class="grid grid-cols-2 gap-0">
                            <span class="subcol-border px-2 text-center">Siang</span>
                            <span class="px-2 text-center">Malam</span>
                        </div>
                    </th>
                    <th class="px-4 py-2 border-r">
                        <div class="text-center px-2">
                            <span>Kit</span>
                        </div>
                    </th>
                    <th class="px-4 py-2 border-r">
                        <div class="grid grid-cols-2 gap-0">
                            <span class="subcol-border px-2 text-center">Bruto</span>
                            <span class="px-2 text-center">Netto</span>
                        </div>
                    </th>
                    <th class="px-4 py-2 border-r">
                        <div class="grid grid-cols-3 gap-0">
                            <span class="subcol-border px-2 text-center">Aux (kWh)</span>
                            <span class="subcol-border px-2 text-center">Susut Trafo (kWh)</span>
                            <span class="px-2 text-center">Persentase (%)</span>
                        </div>
                    </th>
                    <th class="px-4 py-2 border-r">
                        <div class="text-center px-2">
                            <span>Jam</span>
                        </div>
                    </th>
                    <th class="px-4 py-2">
                        <div class="grid grid-cols-5 gap-0">
                            <span class="subcol-border px-2 text-center">OPR</span>
                            <span class="subcol-border px-2 text-center">STANDBY</span>
                            <span class="subcol-border px-2 text-center">PO</span>
                            <span class="subcol-border px-2 text-center">MO</span>
                            <span class="px-2 text-center">FO</span>
                        </div>
                    </th>
                    <th class="px-4 py-2 border-r">
                        <div class="grid grid-cols-2 gap-0">
                            <span class="subcol-border px-2 text-center">Mesin (kali)</span>
                            <span class="px-2 text-center">Listrik (kali)</span>
                        </div>
                    </th>
                    <th class="px-4 py-2">
                        <div class="grid grid-cols-4 gap-0">
                            <span class="subcol-border px-2 text-center">EFDH</span>
                            <span class="subcol-border px-2 text-center">EPDH</span>
                            <span class="subcol-border px-2 text-center">EUDH</span>
                            <span class="px-2 text-center">ESDH</span>
                        </div>
                    </th>
                    <th class="px-4 py-2 border-r">
                        <div class="grid grid-cols-4 gap-0">
                            <span class="subcol-border px-2 text-center">EAF (%)</span>
                            <span class="subcol-border px-2 text-center">SOF (%)</span>
                            <span class="subcol-border px-2 text-center">EFOR (%)</span>
                            <span class="px-2 text-center">SdOF (Kali)</span>
                        </div>
                    </th>
                    <th class="px-4 py-2">
                        <div class="text-center">
                            <span class="px-2 text-center">NCF</span>
                        </div>
                    </th>
                    <th class="px-4 py-2 border-r">
                        <div class="text-center">
                            <span class="px-2 text-center">NOF</span>
                        </div>
                    </th>
                    <th class="px-4 py-2 border-r">
                        <div class="text-center">
                            <span class="px-2 text-center">Jam</span>
                        </div>
                    </th>
                    <th class="px-4 py-2 border border-gray-300">
                        <div class="grid grid-cols-6 gap-1">
                            <span class="px-2 text-center border-r border-gray-300 ">HSD (Liter)</span>
                            <span class="px-2 text-center border-r border-gray-300">B30 (Liter)</span>
                            <span class="px-2 text-center border-r border-gray-300">B40 (Liter)</span>
                            <span class="px-2 text-center border-r border-gray-300">MFO (Liter)</span>
                            <span class="px-2 text-center border-r border-gray-300">Total BBM (Liter)</span>
                            <span class="px-2 text-center">Air (M³)</span>
                        </div>
                    </th>
                    <th class="px-4 py-2 border border-gray-300">
                        <div class="grid grid-cols-8 gap-0">
                            <span class="px-1 text-center border-r border-gray-300 text-pelumas">Meditran SX 15W/40 CH-4 (LITER)</span>
                            <span class="px-1 text-center border-r border-gray-300 text-pelumas">Salyx 420 (LITER)</span>
                            <span class="px-1 text-center border-r border-gray-300 text-pelumas">Salyx 430 (LITER)</span>
                            <span class="px-1 text-center border-r border-gray-300 text-pelumas">TravoLube A (LITER)</span>
                            <span class="px-1 text-center border-r border-gray-300 text-pelumas">Turbolube 46 (LITER)</span>
                            <span class="px-1 text-center border-r border-gray-300 text-pelumas">Turbolube 68 (LITER)</span>
                            <span class="px-1 text-center border-r border-gray-300 text-pelumas">Shell Argina S3 (LITER)</span>
                            <span class="px-1 text-center text-pelumas">TOTAL (LITER)</span>
                        </div>
                    </th>
                    <th class="px-4 py-2 border border-gray-300">
                        <div class="grid grid-cols-3 gap-0">
                            <span class="px-1 text-center border-r border-gray-300 text-pelumas">SFC/SCC (LITER/KWH)</span>
                            <span class="px-1 text-center border-r border-gray-300 text-pelumas">TARA KALOR/NPHR (KCAL/KWH)</span>
                            <span class="px-1 text-center text-pelumas">SLC (CC/KWH)</span>
                        </div>
                    </th>
                    <th class="px-4 py-2">
                        <div class="text-center">
                            <span class="px-2">Keterangan</span>
                        </div>
                    </th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @foreach($unit->machines as $machine)
                    <tr>
                        <td class="px-4 py-3 border-r">{{ $machine->name }}</td>
                        
                        @php
                            $summary = $unit->dailySummaries->first(function($summary) use ($machine) {
                                // Try exact match first
                                if ($summary->machine_name === $machine->name) {
                                    return true;
                                }
                                
                                // Try with MIRRLEES prefix
                                $mirrleesMachineName = str_replace('MIRR', 'MIRRLEES', $machine->name);
                                return $summary->machine_name === $mirrleesMachineName;
                            });
                        @endphp
                        
                        <!-- Daya (MW) -->
                        <td class="px-4 py-3 border-r">
                            <div class="grid grid-cols-3 gap-0">
                                <div class="text-center border-r">{{ $summary ? number_format($summary->installed_power, 2) : '-' }}</div>
                                <div class="text-center border-r">{{ $summary ? number_format($summary->dmn_power, 2) : '-' }}</div>
                                <div class="text-center">{{ $summary ? number_format($summary->capable_power, 2) : '-' }}</div>
                            </div>
                        </td>

                        <!-- Beban Puncak -->
                        <td class="px-4 py-3 border-r">
                            <div class="grid grid-cols-2 gap-0">
                                <div class="text-center border-r">{{ $summary ? number_format($summary->peak_load_day, 2) : '-' }}</div>
                                <div class="text-center">{{ $summary ? number_format($summary->peak_load_night, 2) : '-' }}</div>
                            </div>
                        </td>

                        <!-- Ratio Daya Kit -->
                        <td class="px-4 py-3 border-r text-center">{{ $summary ? number_format($summary->kit_ratio, 2) : '-' }}</td>

                        <!-- Produksi -->
                        <td class="px-4 py-3 border-r">
                            <div class="grid grid-cols-2 gap-0">
                                <div class="text-center border-r">{{ $summary ? number_format($summary->gross_production, 2) : '-' }}</div>
                                <div class="text-center">{{ $summary ? number_format($summary->net_production, 2) : '-' }}</div>
                            </div>
                        </td>

                        <!-- Pemakaian Sendiri -->
                        <td class="px-4 py-3 border-r">
                            <div class="grid grid-cols-3 gap-0">
                                <div class="text-center border-r">{{ $summary ? number_format($summary->aux_power, 2) : '-' }}</div>
                                <div class="text-center border-r">{{ $summary ? number_format($summary->transformer_losses, 2) : '-' }}</div>
                                <div class="text-center">{{ $summary ? number_format($summary->usage_percentage, 2) : '-' }}</div>
                            </div>
                        </td>

                        <!-- Jam Periode -->
                        <td class="px-4 py-3 border-r text-center">{{ $summary ? number_format($summary->period_hours, 2) : '-' }}</td>

                        <!-- Jam Operasi -->
                        <td class="px-4 py-3 border-r">
                            <div class="grid grid-cols-5 gap-0">
                                <div class="text-center border-r">{{ $summary ? number_format($summary->operating_hours, 2) : '-' }}</div>
                                <div class="text-center border-r">{{ $summary ? number_format($summary->standby_hours, 2) : '-' }}</div>
                                <div class="text-center border-r">{{ $summary ? number_format($summary->planned_outage, 2) : '-' }}</div>
                                <div class="text-center border-r">{{ $summary ? number_format($summary->maintenance_outage, 2) : '-' }}</div>
                                <div class="text-center">{{ $summary ? number_format($summary->forced_outage, 2) : '-' }}</div>
                            </div>
                        </td>

                        <!-- Trip Non OMC -->
                        <td class="px-4 py-3 border-r">
                            <div class="grid grid-cols-2 gap-0">
                                <div class="text-center border-r">{{ $summary ? number_format($summary->trip_machine, 0) : '-' }}</div>
                                <div class="text-center">{{ $summary ? number_format($summary->trip_electrical, 0) : '-' }}</div>
                            </div>
                        </td>

                        <!-- Derating -->
                        <td class="px-4 py-3 border-r">
                            <div class="grid grid-cols-4 gap-0">
                                <div class="text-center border-r">{{ $summary ? number_format($summary->efdh, 2) : '-' }}</div>
                                <div class="text-center border-r">{{ $summary ? number_format($summary->epdh, 2) : '-' }}</div>
                                <div class="text-center border-r">{{ $summary ? number_format($summary->eudh, 2) : '-' }}</div>
                                <div class="text-center">{{ $summary ? number_format($summary->esdh, 2) : '-' }}</div>
                            </div>
                        </td>

                        <!-- Kinerja Pembangkit -->
                        <td class="px-4 py-3 border-r">
                            <div class="grid grid-cols-4 gap-0">
                                <div class="text-center border-r">{{ $summary ? number_format($summary->eaf, 2) : '-' }}</div>
                                <div class="text-center border-r">{{ $summary ? number_format($summary->sof, 2) : '-' }}</div>
                                <div class="text-center border-r">{{ $summary ? number_format($summary->efor, 2) : '-' }}</div>
                                <div class="text-center">{{ $summary ? number_format($summary->sdof, 0) : '-' }}</div>
                            </div>
                        </td>

                        <!-- Capability Factor -->
                        <td class="px-4 py-3 border-r text-center">{{ $summary ? number_format($summary->ncf, 2) : '-' }}</td>

                        <!-- Nett Operating Factor -->
                        <td class="px-4 py-3 border-r text-center">{{ $summary ? number_format($summary->nof, 2) : '-' }}</td>

                        <!-- JSI -->
                        <td class="px-4 py-3 border-r text-center">{{ $summary ? number_format($summary->jsi, 2) : '-' }}</td>

                        <!-- Pemakaian Bahan Bakar -->
                        <td class="px-4 py-3 border-r">
                            <div class="grid grid-cols-6 gap-0">
                                <div class="text-center border-r">{{ $summary ? number_format($summary->hsd_fuel, 2) : '-' }}</div>
                                <div class="text-center border-r">{{ $summary ? number_format($summary->b35_fuel, 2) : '-' }}</div>
                                <div class="text-center border-r">{{ $summary ? number_format($summary->b40_fuel, 2) : '-' }}</div>
                                <div class="text-center border-r">{{ $summary ? number_format($summary->mfo_fuel, 2) : '-' }}</div>
                                <div class="text-center border-r">{{ $summary ? number_format($summary->total_fuel, 2) : '-' }}</div>
                                <div class="text-center">{{ $summary ? number_format($summary->water_usage, 2) : '-' }}</div>
                            </div>
                        </td>

                        <!-- Pemakaian Pelumas -->
                        <td class="px-4 py-3 border-r">
                            <div class="grid grid-cols-8 gap-0">
                                <div class="text-center border-r">{{ $summary ? number_format($summary->meditran_oil, 2) : '-' }}</div>
                                <div class="text-center border-r">{{ $summary ? number_format($summary->salyx_420, 2) : '-' }}</div>
                                <div class="text-center border-r">{{ $summary ? number_format($summary->salyx_430, 2) : '-' }}</div>
                                <div class="text-center border-r">{{ $summary ? number_format($summary->travolube_a, 2) : '-' }}</div>
                                <div class="text-center border-r">{{ $summary ? number_format($summary->turbolube_46, 2) : '-' }}</div>
                                <div class="text-center border-r">{{ $summary ? number_format($summary->turbolube_68, 2) : '-' }}</div>
                                <div class="text-center border-r">{{ $summary ? number_format($summary->shell_argina_s3, 2) : '-' }}</div>
                                <div class="text-center">{{ $summary ? number_format($summary->total_oil, 2) : '-' }}</div>
                            </div>
                        </td>

                        <!-- Effisiensi -->
                        <td class="px-4 py-3 border-r">
                            <div class="grid grid-cols-3 gap-0">
                                <div class="text-center border-r">{{ $summary ? number_format($summary->sfc_scc, 3) : '-' }}</div>
                                <div class="text-center border-r">{{ $summary ? number_format($summary->nphr, 3) : '-' }}</div>
                                <div class="text-center">{{ $summary ? number_format($summary->slc, 3) : '-' }}</div>
                            </div>
                        </td>

                        <!-- Keterangan -->
                        <td class="px-4 py-3 text-center">{{ $summary ? $summary->notes : '-' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
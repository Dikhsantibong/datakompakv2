<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use PhpOffice\PhpSpreadsheet\IOFactory;
use App\Models\DailySummary;

class DailySummaryExcelImportController extends Controller
{
    public function index()
    {
        // Tampilkan halaman upload Excel
        return view('admin.daily-summary.import-excel');
    }

    public function process(Request $request)
    {
        $request->validate([
            'excel' => 'required|file|mimes:xlsx,xls',
            'bulan' => 'required|date_format:Y-m',
        ]);
        $file = $request->file('excel');
        \Log::info('DEBUG: Mulai proses upload', ['file' => $file ? $file->getClientOriginalName() : 'NULL']);
        $unitSource = $request->input('unit_source', session('unit', 'mysql'));
        $bulan = $request->input('bulan');
        $storagePath = storage_path('app/excel-uploads');
        if (!file_exists($storagePath)) {
            mkdir($storagePath, 0777, true);
        }
        $filename = $unitSource . '-' . $bulan . '.xlsx';
        $fullPath = $storagePath . '/' . $filename;
        if (file_exists($fullPath)) {
            unlink($fullPath);
            \Log::info('DEBUG: File lama dihapus', ['file' => $fullPath]);
        }
        if ($file) {
            try {
                // Simpan file ke storage dulu
                $file->move($storagePath, $filename);
                \Log::info('DEBUG: File excel berhasil diupload', [
                    'file' => $filename,
                    'path' => $fullPath,
                    'unit_source' => $unitSource,
                    'bulan' => $bulan
                ]);
            } catch (\Exception $e) {
                \Log::error('DEBUG: GAGAL UPLOAD FILE EXCEL', [
                    'file' => $filename,
                    'path' => $fullPath,
                    'error' => $e->getMessage()
                ]);
            }
        } else {
            \Log::error('DEBUG: File upload gagal: file tidak ditemukan di request');
        }
        $carbonBulan = \Carbon\Carbon::createFromFormat('Y-m', $bulan);
        $jumlahHari = $carbonBulan->daysInMonth;

        // Ambil urutan field dan label header dari model dan blade
        $fieldOrder = [
            'machine_name', 'installed_power', 'dmn_power', 'capable_power',
            'peak_load_day', 'peak_load_night', 'kit_ratio',
            'gross_production', 'net_production', 'aux_power', 'transformer_losses', 'usage_percentage',
            'period_hours', 'operating_hours', 'standby_hours', 'planned_outage', 'maintenance_outage', 'forced_outage',
            'trip_machine', 'trip_electrical',
            'efdh', 'epdh', 'eudh', 'esdh',
            'eaf', 'sof', 'efor', 'sdof',
            'ncf', 'nof', 'jsi',
            'hsd_fuel', 'b35_fuel', 'b40_fuel', 'mfo_fuel', 'total_fuel', 'water_usage',
            'meditran_oil', 'salyx_420', 'salyx_430', 'travolube_a', 'turbolube_46', 'turbolube_68', 'shell_argina_s3', 'total_oil',
            'sfc_scc', 'nphr', 'slc', 'notes'
        ];
        $headerLabels = [
            'machine_name' => 'Mesin',
            'installed_power' => 'Daya Terpasang (MW)',
            'dmn_power' => 'DMN SLO',
            'capable_power' => 'Daya Mampu',
            'peak_load_day' => 'Beban Puncak Siang (kW)',
            'peak_load_night' => 'Beban Puncak Malam (kW)',
            'kit_ratio' => 'Ratio Daya Kit (%)',
            'gross_production' => 'Produksi Bruto (kWh)',
            'net_production' => 'Produksi Netto (kWh)',
            'aux_power' => 'Aux (kWh)',
            'transformer_losses' => 'Susut Trafo (kWh)',
            'usage_percentage' => 'Persentase (%)',
            'period_hours' => 'Jam Periode',
            'operating_hours' => 'Jam Operasi',
            'standby_hours' => 'Standby',
            'planned_outage' => 'PO',
            'maintenance_outage' => 'MO',
            'forced_outage' => 'FO',
            'trip_machine' => 'Trip Mesin',
            'trip_electrical' => 'Trip Listrik',
            'efdh' => 'EFDH',
            'epdh' => 'EPDH',
            'eudh' => 'EUDH',
            'esdh' => 'ESDH',
            'eaf' => 'EAF (%)',
            'sof' => 'SOF (%)',
            'efor' => 'EFOR (%)',
            'sdof' => 'SdOF (Kali)',
            'ncf' => 'NCF',
            'nof' => 'NOF',
            'jsi' => 'JSI',
            'hsd_fuel' => 'HSD (Liter)',
            'b35_fuel' => 'B35 (Liter)',
            'mfo_fuel' => 'MFO (Liter)',
            'total_fuel' => 'Total BBM (Liter)',
            'water_usage' => 'Air (M³)',
            'meditran_oil' => 'Meditran SX 15W/40 CH-4 (LITER)',
            'salyx_420' => 'Salyx 420 (LITER)',
            'salyx_430' => 'Salyx 430 (LITER)',
            'travolube_a' => 'TravoLube A (LITER)',
            'turbolube_46' => 'Turbolube 46 (LITER)',
            'turbolube_68' => 'Turbolube 68 (LITER)',
            'shell_argina_s3' => 'Shell Argina S3 (LITER)',
            'total_oil' => 'TOTAL (LITER)',
            'sfc_scc' => 'SFC/SCC (LITER/KWH)',
            'nphr' => 'TARA KALOR/NPHR (KCAL/KWH)',
            'slc' => 'SLC (CC/KWH)',
            'notes' => 'Keterangan',
        ];

        // Cek session unit untuk pelumas khusus Kolaka
        $isKolaka = session('unit') === 'mysql_kolaka';
        $isBauBau = session('unit') === 'mysql_bau_bau';
        $isPoasia = session('unit') === 'mysql_poasia';
        if ($isKolaka) {
            $fieldOrder = [
                'installed_power', 'dmn_power', 'capable_power',
                'peak_load_day', 'peak_load_night', 'kit_ratio',
                'gross_production', 'net_production', 'aux_power', 'transformer_losses', 'usage_percentage',
                'period_hours', 'operating_hours', 'standby_hours', 'planned_outage', 'maintenance_outage', 'forced_outage',
                'trip_machine', 'trip_electrical',
                'efdh', 'epdh', 'eudh', 'esdh',
                'eaf', 'sof', 'efor', 'sdof',
                'ncf', 'nof', 'jsi',
                // Mulai mapping manual dari sini:
                'hsd_fuel', 'b35_fuel', 'mfo_fuel', 'total_fuel', 'water_usage',
                'meditran_oil', 'salyx_420', 'diala_b', 'turbolube_46', 'turboil_68', 'meditran_s40', 'turbo_lube_xt68', 'trafo_lube_a', 'meditran_sx_15w40', 'total_oil',
                'sfc_scc', 'nphr', 'slc', 'notes'
            ];
            $headerLabels = array_merge($headerLabels, [
                'meditran_oil' => 'MEDITRAN SMX 15W/40',
                'salyx_420' => 'SALYX 420',
                'diala_b' => 'DIALA B',
                'turbolube_46' => 'Turbo Oil 46',
                'turboil_68' => 'TurbOil 68',
                'meditran_s40' => 'MEDITRAN S40',
                'turbo_lube_xt68' => 'Turbo Lube XT68',
                'trafo_lube_a' => 'Trafo Lube A',
                'meditran_sx_15w40' => 'MEDITRAN SX 15W/40',
                'total_oil' => 'TOTAL',
            ]);
        } else if ($isBauBau) {
            $fieldOrder = [
                'installed_power', 'capable_power',
                'peak_load_day', 'peak_load_night', 'kit_ratio',
                'gross_production', 'net_production', 'aux_power', 'transformer_losses', 'usage_percentage',
                // Jam Indikator khusus Bau-Bau
                'operating_hours', 'planned_outage', 'forced_outage', 'standby_hours', 'ah',
                'trip_machine', 'trip_electrical',
                'efdh', 'epdh', 'eudh', 'esdh',
                'eaf', 'sof', 'efor', 'sdof',
                'jsi',
                'hsd_fuel', 'b40_fuel', 'total_fuel',
                // hanya pelumas Bau-Bau:
                'meditran_s40', 'meditran_smx_15w40', 'meditran_s30', 'turboil_68', 'trafo_lube_a', 'total_oil',
                // efisiensi:
                'sfc_scc', 'nphr', 'slc',
                'notes'
            ];
            $headerLabels = array_merge($headerLabels, [
                'meditran_s40' => 'Meditran S40',
                'meditran_smx_15w40' => 'Meditran SMX 15W/40',
                'meditran_s30' => 'Meditran S30',
                'turboil_68' => 'Turbo oil 68',
                'trafo_lube_a' => 'Trafolube A',
                'total_oil' => 'TOTAL',
            ]);
            unset($headerLabels['dmn_power']);
            unset($headerLabels['period_hours']);
            unset($headerLabels['ncf']);
            unset($headerLabels['nof']);
            unset($headerLabels['b35_fuel']);
            unset($headerLabels['mfo_fuel']);
            unset($headerLabels['water_usage']);
        } else if ($isPoasia) {
            // --- POASIA: Struktur meniru Bau-Bau, tapi blok sendiri ---
            $fieldOrder = [
                'installed_power', 'capable_power',
                'peak_load_day', 'peak_load_night', 'kit_ratio',
                'gross_production', 'net_production', 'aux_power', 'transformer_losses', 'usage_percentage',
                // Jam Indikator khusus Poasia (sama dulu dengan Bau-Bau)
                'operating_hours', 'planned_outage', 'maintenance_outage', 'forced_outage', 'standby_hours',
                'trip_machine', 'trip_electrical',
                'efdh', 'epdh', 'eudh', 'esdh',
                'eaf', 'sof', 'efor', 'sdof',
                'jsi',
                'hsd_fuel', 'b10_fuel', 'b15_fuel', 'b20_fuel', 'b25_fuel', 'b35_fuel', 'mfo_fuel', 'total_fuel', 'batubara', 'water_usage',
                // hanya pelumas Poasia (sama dulu dengan Bau-Bau):
                'shell_argina_s3', 'thermo_xt_32', 'shell_diala_b', 'meditran_sx_ch4', 'total_oil',
                // efisiensi:
                'sfc_scc', 'nphr', 'slc',
                'notes'
            ];
            $headerLabels = array_merge($headerLabels, [
                'shell_argina_s3' => 'Shell Argina S3',
                'thermo_xt_32' => 'Thermo XT 32',
                'shell_diala_b' => 'Shell Diala B',
                'meditran_sx_ch4' => 'Meditran SX CH-4',
                'total_oil' => 'TOTAL (LITER)',
            ]);
            unset($headerLabels['dmn_power']);
            unset($headerLabels['period_hours']);
            unset($headerLabels['ncf']);
            unset($headerLabels['nof']);
        }

        // Load file dari storage, bukan dari temp upload
        $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($fullPath);
        $allSheets = $spreadsheet->getAllSheets();
        $previewSheets = [];
        $unitSheets = [];
        $machineSheets = [];
        $debugRows = [];
        $debugMappedRows = [];
        $firstSheetName = null;
        $rowStart = 13;
        $rowEnd = 22;
        $isKolaka = session('unit') === 'mysql_kolaka';
        $isBauBau = session('unit') === 'mysql_bau_bau';
        $isPoasia = session('unit') === 'mysql_poasia';
        if ($isBauBau) {
            $rowStart = 121;
            $rowEnd = 125;
        } else if ($isPoasia) {
            $rowStart = 122; // Sama dulu dengan Bau-Bau
            $rowEnd = 126;
        }
        if ($isBauBau) {
            $sheetStart = 0; // mulai dari sheet pertama
        } else if ($isPoasia) {
            $sheetStart = 0; // Sama dulu dengan Bau-Bau
        } else {
            $sheetStart = 2; // default: skip 2 sheet awal
        }
        for ($i = $sheetStart; $i < count($allSheets); $i++) {
            $sheet = $allSheets[$i];
            $sheetName = $sheet->getTitle();
            if ($isBauBau) {
                $day = $i + 1; // sheet ke-0 = tgl 1, dst
            } else {
                if (!preg_match('/^\d{1,2}$/', $sheetName)) continue;
                $day = (int)$sheetName;
                if ($day < 1 || $day > $jumlahHari) continue;
            }
            $dataRows = [];
            for ($j = $rowStart; $j <= $rowEnd; $j++) {
                if ($j > $sheet->getHighestRow()) continue;
                $rowData = [];
                foreach ($sheet->getRowIterator($j, $j) as $row) {
                    foreach ($row->getCellIterator() as $cell) {
                        $val = $cell->getCalculatedValue();
                        if ($val === null || $val === '' || $val === '-') $val = 0;
                        $rowData[] = $val;
                    }
                }
                if (!empty($rowData)) $dataRows[] = $rowData;
            }
            if ($firstSheetName === null && !empty($dataRows)) {
                $debugRows = $dataRows;
            }
            // Mapping ke field DailySummary
            $mappedRows = [];
            foreach ($dataRows as $row) {
                if ($isBauBau) {
                    $unit = 'PLTD BAU BAU';
                    $mesin = $row[1] ?? '';
                } else if ($isKolaka) {
                    // Ambil unit dan mesin dari kolom ke-2 Excel (index 1)
                    $unitMesin = $row[1] ?? '';
                    $unit = $unitMesin;
                    $mesin = $unitMesin;
                    if (strpos($unitMesin, '-') !== false) {
                        [$unit, $mesin] = explode('-', $unitMesin, 2);
                        $unit = trim($unit);
                        $mesin = trim($mesin);
                    }
                } else if ($isPoasia) {
                    $unit = 'PLTD POASIA';
                    $mesin = $row[2] ?? ''; // Ambil nama mesin dari kolom ke-2 (index 2)
                } else {
                    // Ambil unit dan mesin dari kolom ke-2 Excel (index 1)
                    $unitMesin = $row[1] ?? '';
                    $unit = $unitMesin;
                    $mesin = $unitMesin;
                    if (strpos($unitMesin, '-') !== false) {
                        [$unit, $mesin] = explode('-', $unitMesin, 2);
                        $unit = trim($unit);
                        $mesin = trim($mesin);
                    }
                }
                $mapped = [
                    'unit' => $isBauBau ? 'PLTD BAU BAU' : ($isKolaka ? 'PLTD KOLAKA' : ($isPoasia ? 'PLTD POASIA' : $unit)),
                    'machine_name' => $isPoasia ? ($row[2] ?? '') : (($isBauBau) ? ($row[1] ?? '') : $mesin)
                ];
                if ($isKolaka) {
                    $idxHsd = array_search('hsd_fuel', $fieldOrder);
                    foreach ($fieldOrder as $idx => $field) {
                        if ($idx >= $idxHsd) break;
                        $excelIdx = $idx + 2;
                        $mapped[$field] = $row[$excelIdx] ?? '';
                    }
                    $kolakaManualMap = [
                        'hsd_fuel' => 32,
                        'b35_fuel' => 37,
                        'mfo_fuel' => 38,
                        'total_fuel' => 39,
                        'water_usage' => 40,
                        'meditran_oil' => 41,
                        'salyx_420' => 42,
                        'diala_b' => 43,
                        'turbolube_46' => 44,
                        'turboil_68' => 45,
                        'meditran_s40' => 46,
                        'turbo_lube_xt68' => 47,
                        'trafo_lube_a' => 48,
                        'meditran_sx_15w40' => 49,
                        'total_oil' => 50,
                        'sfc_scc' => 51,
                        'nphr' => 52,
                        'slc' => 53,
                        'notes' => 54,
                    ];
                    foreach ($kolakaManualMap as $field => $excelIdx) {
                        $mapped[$field] = $row[$excelIdx] ?? '';
                    }
                } else if ($isBauBau) {
                    $bauBauManualMap = [
                        'hsd_fuel' => 28,
                        'b40_fuel' => 29,
                        'total_fuel' => 30,
                        'meditran_s40' => 32, // Sesuai debug table
                        'meditran_smx_15w40' => 33,
                        'meditran_s30' => 34,
                        'turboil_68' => 35,
                        'trafo_lube_a' => 36,
                        'total_oil' => 37,
                        'sfc_scc' => 38,
                        'nphr' => 39,
                        'slc' => 40,
                        'notes' => 41,
                    ];
                    foreach ($fieldOrder as $idx => $field) {
                        if (isset($bauBauManualMap[$field])) {
                            $excelIdx = $bauBauManualMap[$field];
                            $mapped[$field] = $row[$excelIdx] ?? 0;
                        } else {
                            $excelIdx = $idx + 2;
                            $mapped[$field] = $row[$excelIdx] ?? 0;
                        }
                    }
                } else if ($isPoasia) {
                    // --- POASIA: mapping sesuai urutan kolom Excel Poasia ---
                    $poasiaManualMap = [
                        'installed_power' => 3,
                        'capable_power' => 4,
                        'peak_load_day' => 5,
                        'peak_load_night' => 6,
                        'kit_ratio' => 7,
                        'gross_production' => 8,
                        'net_production' => 9,
                        'aux_power' => 10,
                        'transformer_losses' => 11,
                        'usage_percentage' => 12,
                        'operating_hours' => 13,
                        'planned_outage' => 14,
                        'maintenance_outage' => 15,
                        'forced_outage' => 16,
                        'standby_hours' => 17,
                        'trip_machine' => 18,
                        'trip_electrical' => 19,
                        'efdh' => 20,
                        'epdh' => 21,
                        'eudh' => 22,
                        'esdh' => 23,
                        'eaf' => 24,
                        'sof' => 25,
                        'efor' => 26,
                        'sdof' => 27,
                        'jsi' => 28,
                        'hsd_fuel' => 29,
                        'b10_fuel' => 30,
                        'b15_fuel' => 31,
                        'b20_fuel' => 32,
                        'b25_fuel' => 33,
                        'b35_fuel' => 34,
                        'mfo_fuel' => 35,
                        'total_fuel' => 36,
                        'batubara' => 37,
                        'water_usage' => 38,
                        'shell_argina_s3' => 39,
                        'thermo_xt_32' => 40,
                        'shell_diala_b' => 41,
                        'meditran_sx_ch4' => 42,
                        'total_oil' => 43,
                        'sfc_scc' => 44,
                        'nphr' => 45,
                        'slc' => 46,
                        'notes' => 47,
                    ];
                    foreach ($fieldOrder as $field) {
                        if (isset($poasiaManualMap[$field])) {
                            $excelIdx = $poasiaManualMap[$field];
                            $mapped[$field] = $row[$excelIdx] ?? 0;
                        }
                    }
                } else {
                    foreach ($fieldOrder as $idx => $field) {
                        $excelIdx = $idx + 2;
                        $mapped[$field] = $row[$excelIdx] ?? '';
                    }
                }
                // Setelah mapping ke $mapped, pastikan semua field numerik kosong/null diubah ke 0
                foreach ($fieldOrder as $field) {
                    if (!isset($mapped[$field]) || $mapped[$field] === '' || $mapped[$field] === null || $mapped[$field] === '-') {
                        $mapped[$field] = 0;
                    }
                }
                $mappedRows[] = $mapped;
            }
            if ($firstSheetName === null && !empty($mappedRows)) {
                $debugMappedRows = $mappedRows;
                $firstSheetName = $sheetName;
            }
            $previewSheets[$sheetName] = $mappedRows;
        }
        return view('admin.daily-summary.import-excel', compact('previewSheets', 'headerLabels', 'fieldOrder', 'bulan', 'debugRows', 'debugMappedRows', 'firstSheetName'));
    }

    public function save(Request $request)
    {
        $request->validate([
            'bulan' => 'required|date_format:Y-m',
            'preview_data' => 'required',
        ]);
        $bulan = $request->input('bulan');
        $carbonBulan = \Carbon\Carbon::createFromFormat('Y-m', $bulan);
        $previewSheets = unserialize(base64_decode($request->input('preview_data')));
        $success = 0; $failed = 0;
        foreach ($previewSheets as $sheetName => $rows) {
            foreach ($rows as $row) {
                try {
                    $data = $row;
                    $data['date'] = $carbonBulan->copy()->day((int)$sheetName)->format('Y-m-d');
                    // Sesuaikan field yang boleh diisi
                    $allowed = (new DailySummary)->getFillable();
                    $insert = array_intersect_key($data, array_flip($allowed));
                    $isKolaka = session('unit') === 'mysql_kolaka';
                    $isBauBau = session('unit') === 'mysql_bau_bau';
                    $isPoasia = session('unit') === 'mysql_poasia';
                    if ($isKolaka) {
                        $insert['power_plant_id'] = 7;
                        $insert['unit'] = 'PLTD KOLAKA';
                    } else if ($isBauBau) {
                        $insert['power_plant_id'] = 12;
                        $insert['unit'] = 'PLTD BAU BAU';
                    } else if ($isPoasia) {
                        $insert['power_plant_id'] = 4; // Assuming a new power plant ID for Poasia
                        $insert['unit'] = 'PLTD POASIA';
                    }
                    $decimalFields = [
                        'installed_power','dmn_power','capable_power','peak_load_day','peak_load_night','kit_ratio','gross_production','net_production','aux_power','transformer_losses','usage_percentage','period_hours','operating_hours','standby_hours','planned_outage','maintenance_outage','forced_outage','trip_machine','trip_electrical','efdh','epdh','eudh','esdh','eaf','sof','efor','sdof','ncf','nof','jsi','hsd_fuel','b35_fuel','b40_fuel','mfo_fuel','total_fuel','water_usage','meditran_oil','salyx_420','salyx_430','travolube_a','turbolube_46','turbolube_68','shell_argina_s3','total_fuel','diala_b','turboil_68','meditran_s40','turbo_lube_xt68','turbo_oil_46','trafo_lube_a','sfc_scc','nphr','slc','meditran_smx_15w40','meditran_sx_15w40'
                    ];
                    foreach ($decimalFields as $field) {
                        if (!isset($insert[$field]) || $insert[$field] === '' || !is_numeric($insert[$field])) {
                            $insert[$field] = 0;
                        }
                    }
                    DailySummary::updateOrCreate([
                        'date' => $insert['date'] ?? null,
                        'power_plant_id' => $insert['power_plant_id'] ?? null,
                        'machine_name' => $insert['machine_name'] ?? null,
                    ], $insert);
                    $success++;
                } catch (\Exception $e) {
                    \Log::error('IMPORT DAILY SUMMARY ERROR', [
                        'row' => $row,
                        'error' => $e->getMessage(),
                        'trace' => $e->getTraceAsString(),
                    ]);
                    $failed++;
                }
            }
        }
        return redirect()->back()->with('status', "Import selesai. Berhasil: $success, Gagal: $failed");
    }

    public function downloadExcel(Request $request)
    {
        $bulan = $request->input('bulan');
        $unitSource = $request->input('unit_source', session('unit', 'mysql'));
        $filename = $unitSource . '-' . $bulan . '.xlsx';
        $path = storage_path('app/excel-uploads/' . $filename);
        if (!file_exists($path)) {
            abort(404, 'File tidak ditemukan');
        }
        return response()->download($path, $filename);
    }
}
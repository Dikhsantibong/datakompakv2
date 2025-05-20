<?php

namespace App\Listeners;

use App\Events\LaporanKitUpdated;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SyncLaporanKitToUpKendari
{
    public function handle(LaporanKitUpdated $event)
    {
        try {
            $upKendariDB = DB::connection('mysql');
                    \App\Models\LaporanKit::$isSyncing = true;

            switch ($event->action) {
                case 'create':
                case 'update':
                    // --- 1. Insert Parent (laporan_kits) ---
                    $laporanKit = $event->laporanKit;
                    $data = [
                        'tanggal' => $laporanKit->tanggal,
                        'unit_source' => $laporanKit->unit_source,
                        'created_by' => $laporanKit->created_by,
                        'created_at' => now(),
                        'updated_at' => now()
                    ];
                    if ($event->action === 'create') {
                        $laporanKitIdBaru = $upKendariDB->table('laporan_kits')->insertGetId($data);
                    } else {
                        $laporanKitIdBaru = $laporanKit->id;
                        $upKendariDB->table('laporan_kits')->updateOrInsert(['id' => $laporanKitIdBaru], $data);
                    }

                    // --- 2. BBM ---
                    foreach ($laporanKit->bbm as $bbm) {
                        $bbmData = [
                            'laporan_kit_id' => $laporanKitIdBaru,
                            'total_stok' => $bbm->total_stok,
                            'service_total_stok' => $bbm->service_total_stok,
                            'total_stok_tangki' => $bbm->total_stok_tangki,
                            'terima_bbm' => $bbm->terima_bbm,
                            'total_pakai' => $bbm->total_pakai,
                            'created_at' => now(),
                            'updated_at' => now()
                        ];
                        $bbmIdBaru = $upKendariDB->table('laporan_kit_bbm')->insertGetId($bbmData);
                        // Storage Tanks
                        foreach ($bbm->storageTanks as $tank) {
                            $tankData = [
                                'laporan_kit_bbm_id' => $bbmIdBaru,
                                'tank_number' => $tank->tank_number,
                                'cm' => $tank->cm,
                                'liter' => $tank->liter,
                                'created_at' => now(),
                                'updated_at' => now()
                            ];
                            $upKendariDB->table('laporan_kit_bbm_storage_tanks')->insert($tankData);
                        }
                        // Service Tanks
                        foreach ($bbm->serviceTanks as $tank) {
                            $tankData = [
                                'laporan_kit_bbm_id' => $bbmIdBaru,
                                'tank_number' => $tank->tank_number,
                                'liter' => $tank->liter,
                                'percentage' => $tank->percentage,
                                'created_at' => now(),
                                'updated_at' => now()
                            ];
                            $upKendariDB->table('laporan_kit_bbm_service_tanks')->insert($tankData);
                        }
                        // Flowmeters
                        foreach ($bbm->flowmeters as $flowmeter) {
                            $flowmeterData = [
                                'laporan_kit_bbm_id' => $bbmIdBaru,
                                'flowmeter_number' => $flowmeter->flowmeter_number,
                                'awal' => $flowmeter->awal,
                                'akhir' => $flowmeter->akhir,
                                'pakai' => $flowmeter->pakai,
                                'created_at' => now(),
                                'updated_at' => now()
                            ];
                            $upKendariDB->table('laporan_kit_bbm_flowmeters')->insert($flowmeterData);
                        }
                    }

                    // --- 3. KWH ---
                    foreach ($laporanKit->kwh as $kwh) {
                        $kwhData = [
                            'laporan_kit_id' => $laporanKitIdBaru,
                            'prod_total' => $kwh->prod_total,
                            'ps_total' => $kwh->ps_total,
                            'created_at' => now(),
                            'updated_at' => now()
                        ];
                        $kwhIdBaru = $upKendariDB->table('laporan_kit_kwh')->insertGetId($kwhData);
                        // Production Panels
                        foreach ($kwh->productionPanels as $panel) {
                            $panelData = [
                                'laporan_kit_kwh_id' => $kwhIdBaru,
                                'panel_number' => $panel->panel_number,
                                'awal' => $panel->awal,
                                'akhir' => $panel->akhir,
                                'created_at' => now(),
                                'updated_at' => now()
                            ];
                            $upKendariDB->table('laporan_kit_kwh_production_panels')->insert($panelData);
                        }
                        // PS Panels
                        foreach ($kwh->psPanels as $panel) {
                            $panelData = [
                                'laporan_kit_kwh_id' => $kwhIdBaru,
                                'panel_number' => $panel->panel_number,
                                'awal' => $panel->awal,
                                'akhir' => $panel->akhir,
                                'created_at' => now(),
                                'updated_at' => now()
                            ];
                            $upKendariDB->table('laporan_kit_kwh_ps_panels')->insert($panelData);
                        }
                    }

                    // --- 4. Pelumas ---
                    foreach ($laporanKit->pelumas as $pelumas) {
                        $pelumasData = [
                            'laporan_kit_id' => $laporanKitIdBaru,
                            'tank_total_stok' => $pelumas->tank_total_stok,
                            'drum_total_stok' => $pelumas->drum_total_stok,
                            'total_stok_tangki' => $pelumas->total_stok_tangki,
                            'terima_pelumas' => $pelumas->terima_pelumas,
                            'total_pakai' => $pelumas->total_pakai,
                            'jenis' => $pelumas->jenis,
                            'created_at' => now(),
                            'updated_at' => now()
                        ];
                        $pelumasIdBaru = $upKendariDB->table('laporan_kit_pelumas')->insertGetId($pelumasData);
                        // Storage Tanks
                        foreach ($pelumas->storageTanks as $tank) {
                            $tankData = [
                                'laporan_kit_pelumas_id' => $pelumasIdBaru,
                                'tank_number' => $tank->tank_number,
                                'cm' => $tank->cm,
                                'liter' => $tank->liter,
                                'created_at' => now(),
                                'updated_at' => now()
                            ];
                            $upKendariDB->table('laporan_kit_pelumas_storage_tanks')->insert($tankData);
                        }
                        // Drums
                        foreach ($pelumas->drums as $drum) {
                            $drumData = [
                                'laporan_kit_pelumas_id' => $pelumasIdBaru,
                                'area_number' => $drum->area_number,
                                'jumlah' => $drum->jumlah,
                                'created_at' => now(),
                                'updated_at' => now()
                            ];
                            $upKendariDB->table('laporan_kit_pelumas_drums')->insert($drumData);
                        }
                    }

                    // --- 5. Bahan Kimia ---
                    foreach ($laporanKit->bahanKimia as $kimia) {
                        $kimiaData = [
                            'laporan_kit_id' => $laporanKitIdBaru,
                            'jenis' => $kimia->jenis,
                            'stok_awal' => $kimia->stok_awal,
                            'terima' => $kimia->terima,
                            'total_pakai' => $kimia->total_pakai,
                            'created_at' => now(),
                            'updated_at' => now()
                        ];
                        $upKendariDB->table('laporan_kit_bahan_kimia')->insert($kimiaData);
                    }

                    // --- 6. Jam Operasi ---
                    foreach ($laporanKit->jamOperasi as $jam) {
                        $jamData = [
                            'laporan_kit_id' => $laporanKitIdBaru,
                            'machine_id' => $jam->machine_id,
                            'ops' => $jam->ops,
                            'har' => $jam->har,
                            'ggn' => $jam->ggn,
                            'stby' => $jam->stby,
                            'jam_hari' => $jam->jam_hari,
                            'created_at' => now(),
                            'updated_at' => now()
                        ];
                        $upKendariDB->table('laporan_kit_jam_operasi')->insert($jamData);
                    }

                    // --- 7. Beban Tertinggi ---
                    foreach ($laporanKit->bebanTertinggi as $beban) {
                        $bebanData = [
                            'laporan_kit_id' => $laporanKitIdBaru,
                            'machine_id' => $beban->machine_id,
                            'siang' => $beban->siang,
                            'malam' => $beban->malam,
                            'created_at' => now(),
                            'updated_at' => now()
                        ];
                        $upKendariDB->table('laporan_kit_beban_tertinggi')->insert($bebanData);
                    }

                    // --- 8. Gangguan ---
                    foreach ($laporanKit->gangguan as $gangguan) {
                        $gangguanData = [
                            'laporan_kit_id' => $laporanKitIdBaru,
                            'machine_id' => $gangguan->machine_id,
                            'mekanik' => $gangguan->mekanik,
                            'elektrik' => $gangguan->elektrik,
                            'keterangan' => $gangguan->keterangan,
                            'created_at' => now(),
                            'updated_at' => now()
                        ];
                        $upKendariDB->table('laporan_kit_gangguan')->insert($gangguanData);
                    }

                    break;

                case 'delete':
                    // Delete all related records first
                    $upKendariDB->table('laporan_kit_beban_tertinggi')
                        ->where('laporan_kit_id', $event->laporanKit->id)
                        ->delete();

                    $upKendariDB->table('laporan_kit_bbm_storage_tanks')
                        ->whereIn('laporan_kit_bbm_id', function($query) use ($event) {
                            $query->select('id')
                                ->from('laporan_kit_bbm')
                                ->where('laporan_kit_id', $event->laporanKit->id);
                        })
                        ->delete();

                    $upKendariDB->table('laporan_kit_bbm_service_tanks')
                        ->whereIn('laporan_kit_bbm_id', function($query) use ($event) {
                            $query->select('id')
                                ->from('laporan_kit_bbm')
                                ->where('laporan_kit_id', $event->laporanKit->id);
                        })
                        ->delete();

                    // Delete flowmeters
                    $upKendariDB->table('laporan_kit_bbm_flowmeters')
                        ->whereIn('laporan_kit_bbm_id', function($query) use ($event) {
                            $query->select('id')
                                ->from('laporan_kit_bbm')
                                ->where('laporan_kit_id', $event->laporanKit->id);
                        })
                        ->delete();

                    $upKendariDB->table('laporan_kit_kwh_production_panels')
                        ->whereIn('laporan_kit_kwh_id', function($query) use ($event) {
                            $query->select('id')
                                ->from('laporan_kit_kwh')
                                ->where('laporan_kit_id', $event->laporanKit->id);
                        })
                        ->delete();

                    $upKendariDB->table('laporan_kit_kwh_ps_panels')
                        ->whereIn('laporan_kit_kwh_id', function($query) use ($event) {
                            $query->select('id')
                                ->from('laporan_kit_kwh')
                                ->where('laporan_kit_id', $event->laporanKit->id);
                        })
                        ->delete();

                    $upKendariDB->table('laporan_kit_pelumas_storage_tanks')
                        ->whereIn('laporan_kit_pelumas_id', function($query) use ($event) {
                            $query->select('id')
                                ->from('laporan_kit_pelumas')
                                ->where('laporan_kit_id', $event->laporanKit->id);
                        })
                        ->delete();

                    $upKendariDB->table('laporan_kit_pelumas_drums')
                        ->whereIn('laporan_kit_pelumas_id', function($query) use ($event) {
                            $query->select('id')
                                ->from('laporan_kit_pelumas')
                                ->where('laporan_kit_id', $event->laporanKit->id);
                        })
                        ->delete();

                    // Finally delete the main record
                    $upKendariDB->table('laporan_kits')
                        ->where('id', $event->laporanKit->id)
                        ->delete();
                    break;
            }

            Log::info('Successfully synced LaporanKit and related data to UP Kendari', [
                'action' => $event->action,
                'id' => $event->laporanKit->id
            ]);

            \App\Models\LaporanKit::$isSyncing = false;

        } catch (\Exception $e) {
            \App\Models\LaporanKit::$isSyncing = false;
            Log::error('Error syncing LaporanKit and related data to UP Kendari:', [
                'action' => $event->action,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            throw $e;
        }
    }
} 
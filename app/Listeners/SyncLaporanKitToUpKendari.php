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
                    // Sync main LaporanKit data
                    $data = [
                        'tanggal' => $event->laporanKit->tanggal,
                        'unit_source' => $event->laporanKit->unit_source,
                        'created_by' => $event->laporanKit->created_by,
                        'created_at' => now(),
                        'updated_at' => now()
                    ];

                    if ($event->action === 'create') {
                        $upKendariDB->table('laporan_kits')->insert($data);
                    } else {
                        $upKendariDB->table('laporan_kits')
                            ->where('id', $event->laporanKit->id)
                            ->update($data);
                    }

                    // Sync BebanTertinggi
                    foreach ($event->laporanKit->bebanTertinggi as $beban) {
                        $bebanData = [
                            'laporan_kit_id' => $beban->laporan_kit_id,
                            'machine_id' => $beban->machine_id,
                            'siang' => $beban->siang,
                            'malam' => $beban->malam,
                            'created_at' => now(),
                            'updated_at' => now()
                        ];

                        if ($event->action === 'create') {
                            $upKendariDB->table('laporan_kit_beban_tertinggi')->insert($bebanData);
                        } else {
                            $upKendariDB->table('laporan_kit_beban_tertinggi')
                                ->where('laporan_kit_id', $beban->laporan_kit_id)
                                ->where('machine_id', $beban->machine_id)
                                ->update($bebanData);
                        }
                    }

                    // Sync BBM and related data
                    foreach ($event->laporanKit->bbm as $bbm) {
                        // Insert BBM induk tanpa ID
                        $bbmData = [
                            'laporan_kit_id' => $bbm->laporan_kit_id,
                            'total_stok' => $bbm->total_stok,
                            'service_total_stok' => $bbm->service_total_stok,
                            'total_stok_tangki' => $bbm->total_stok_tangki,
                            'terima_bbm' => $bbm->terima_bbm,
                            'total_pakai' => $bbm->total_pakai,
                            'created_at' => now(),
                            'updated_at' => now()
                        ];
                        $bbmId = $upKendariDB->table('laporan_kit_bbm')->insertGetId($bbmData);

                        // Sync Storage Tanks tanpa ID
                        foreach ($bbm->storageTanks as $tank) {
                            $tankData = [
                                'laporan_kit_bbm_id' => $bbmId,
                                'tank_number' => $tank->tank_number,
                                'cm' => $tank->cm,
                                'liter' => $tank->liter,
                                'created_at' => now(),
                                'updated_at' => now()
                            ];
                            $upKendariDB->table('laporan_kit_bbm_storage_tanks')->insert($tankData);
                        }

                        // Sync Service Tanks tanpa ID
                        foreach ($bbm->serviceTanks as $tank) {
                            $tankData = [
                                'laporan_kit_bbm_id' => $bbmId,
                                'tank_number' => $tank->tank_number,
                                'liter' => $tank->liter,
                                'percentage' => $tank->percentage,
                                'created_at' => now(),
                                'updated_at' => now()
                            ];
                            $upKendariDB->table('laporan_kit_bbm_service_tanks')->insert($tankData);
                        }

                        // Sync Flowmeters tanpa ID
                        foreach ($bbm->flowmeters as $flowmeter) {
                            $flowmeterData = [
                                'laporan_kit_bbm_id' => $bbmId,
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

                    // Sync KWH and related data
                    foreach ($event->laporanKit->kwh as $kwh) {
                        // Sync Production Panels
                        foreach ($kwh->productionPanels as $panel) {
                            $panelData = [
                                'id' => $panel->id,
                                'laporan_kit_kwh_id' => $panel->laporan_kit_kwh_id,
                                'panel_number' => $panel->panel_number,
                                'awal' => $panel->awal,
                                'akhir' => $panel->akhir,
                                'created_at' => now(),
                                'updated_at' => now()
                            ];
                            $upKendariDB->table('laporan_kit_kwh_production_panels')
                                ->updateOrInsert(
                                    ['id' => $panel->id],
                                    $panelData
                                );
                        }

                        // Sync PS Panels
                        foreach ($kwh->psPanels as $panel) {
                            $panelData = [
                                'id' => $panel->id,
                                'laporan_kit_kwh_id' => $panel->laporan_kit_kwh_id,
                                'panel_number' => $panel->panel_number,
                                'awal' => $panel->awal,
                                'akhir' => $panel->akhir,
                                'created_at' => now(),
                                'updated_at' => now()
                            ];
                            $upKendariDB->table('laporan_kit_kwh_ps_panels')
                                ->updateOrInsert(
                                    ['id' => $panel->id],
                                    $panelData
                                );
                        }
                    }

                    // Sync Pelumas and related data
                    foreach ($event->laporanKit->pelumas as $pelumas) {
                        // Sync Storage Tanks
                        foreach ($pelumas->storageTanks as $tank) {
                            $tankData = [
                                'id' => $tank->id,
                                'laporan_kit_pelumas_id' => $tank->laporan_kit_pelumas_id,
                                'tank_number' => $tank->tank_number,
                                'cm' => $tank->cm,
                                'liter' => $tank->liter,
                                'created_at' => now(),
                                'updated_at' => now()
                            ];
                            $upKendariDB->table('laporan_kit_pelumas_storage_tanks')
                                ->updateOrInsert(
                                    ['id' => $tank->id],
                                    $tankData
                                );
                        }

                        // Sync Drums
                        foreach ($pelumas->drums as $drum) {
                            $drumData = [
                                'id' => $drum->id,
                                'laporan_kit_pelumas_id' => $drum->laporan_kit_pelumas_id,
                                'area_number' => $drum->area_number,
                                'jumlah' => $drum->jumlah,
                                'created_at' => now(),
                                'updated_at' => now()
                            ];
                            $upKendariDB->table('laporan_kit_pelumas_drums')
                                ->updateOrInsert(
                                    ['id' => $drum->id],
                                    $drumData
                                );
                        }
                    }

                    // Sync Gangguan
                    foreach ($event->laporanKit->gangguan as $gangguan) {
                        try {
                            $gangguanData = [
                                'id' => $gangguan->id,
                                'laporan_kit_id' => $gangguan->laporan_kit_id,
                                'machine_id' => $gangguan->machine_id,
                                'mekanik' => $gangguan->mekanik,
                                'elektrik' => $gangguan->elektrik,
                                'keterangan' => $gangguan->keterangan,
                                'created_at' => now(),
                                'updated_at' => now()
                            ];

                            // Use updateOrInsert to handle duplicate keys
                            $upKendariDB->table('laporan_kit_gangguan')
                                ->updateOrInsert(
                                    ['id' => $gangguan->id],
                                    $gangguanData
                                );

                            Log::info('Successfully synced LaporanKitGangguan', [
                                'id' => $gangguan->id,
                                'laporan_kit_id' => $gangguan->laporan_kit_id
                            ]);
                        } catch (\Exception $e) {
                            Log::error('Error syncing LaporanKitGangguan:', [
                                'id' => $gangguan->id,
                                'error' => $e->getMessage(),
                                'trace' => $e->getTraceAsString()
                            ]);
                            // Continue with other records even if one fails
                            continue;
                        }
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
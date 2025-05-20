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

            switch ($event->action) {
                case 'create':
                case 'update':
                    // Sync main LaporanKit data (insert tanpa ID)
                    $data = [
                        'tanggal' => $event->laporanKit->tanggal,
                        'unit_source' => $event->laporanKit->unit_source,
                        'created_by' => $event->laporanKit->created_by,
                        'created_at' => now(),
                        'updated_at' => now()
                    ];

                    // Insert parent, dapatkan ID baru
                    $newKitId = null;
                    if ($event->action === 'create') {
                        $upKendariDB->table('laporan_kits')->insert($data);
                        $newKitId = $upKendariDB->getPdo()->lastInsertId();
                    } else {
                        $upKendariDB->table('laporan_kits')
                            ->where('id', $event->laporanKit->id)
                            ->update($data);
                        $newKitId = $event->laporanKit->id;
                    }

                    // Sync BebanTertinggi
                    foreach ($event->laporanKit->bebanTertinggi as $beban) {
                        $bebanData = [
                            'laporan_kit_id' => $newKitId,
                            'machine_id' => $beban->machine_id,
                            'siang' => $beban->siang,
                            'malam' => $beban->malam,
                            'created_at' => now(),
                            'updated_at' => now()
                        ];
                        $upKendariDB->table('laporan_kit_beban_tertinggi')
                            ->updateOrInsert(
                                ['laporan_kit_id' => $newKitId, 'machine_id' => $beban->machine_id],
                                $bebanData
                            );
                    }

                    // Sync BBM and related data
                    foreach ($event->laporanKit->bbm as $bbm) {
                        // Insert BBM parent, dapatkan ID baru
                        $bbmData = [
                            'laporan_kit_id' => $newKitId,
                            'total_stok' => $bbm->total_stok,
                            'service_total_stok' => $bbm->service_total_stok,
                            'total_stok_tangki' => $bbm->total_stok_tangki,
                            'terima_bbm' => $bbm->terima_bbm,
                            'total_pakai' => $bbm->total_pakai,
                            'created_at' => now(),
                            'updated_at' => now()
                        ];
                        $upKendariDB->table('laporan_kit_bbm')->insert($bbmData);
                        $newBbmId = $upKendariDB->getPdo()->lastInsertId();

                        // Sync Storage Tanks
                        foreach ($bbm->storageTanks as $tank) {
                            $tankData = [
                                'laporan_kit_bbm_id' => $newBbmId,
                                'tank_number' => $tank->tank_number,
                                'cm' => $tank->cm,
                                'liter' => $tank->liter,
                                'created_at' => now(),
                                'updated_at' => now()
                            ];
                            $upKendariDB->table('laporan_kit_bbm_storage_tanks')
                                ->updateOrInsert(
                                    ['laporan_kit_bbm_id' => $newBbmId, 'tank_number' => $tank->tank_number],
                                    $tankData
                                );
                        }

                        // Sync Service Tanks
                        foreach ($bbm->serviceTanks as $tank) {
                            $tankData = [
                                'laporan_kit_bbm_id' => $newBbmId,
                                'tank_number' => $tank->tank_number,
                                'liter' => $tank->liter,
                                'percentage' => $tank->percentage,
                                'created_at' => now(),
                                'updated_at' => now()
                            ];
                            $upKendariDB->table('laporan_kit_bbm_service_tanks')
                                ->updateOrInsert(
                                    ['laporan_kit_bbm_id' => $newBbmId, 'tank_number' => $tank->tank_number],
                                    $tankData
                                );
                        }

                        // Sync Flowmeters
                        foreach ($bbm->flowmeters as $flowmeter) {
                            $flowmeterData = [
                                'laporan_kit_bbm_id' => $newBbmId,
                                'flowmeter_number' => $flowmeter->flowmeter_number,
                                'awal' => $flowmeter->awal,
                                'akhir' => $flowmeter->akhir,
                                'pakai' => $flowmeter->pakai,
                                'created_at' => now(),
                                'updated_at' => now()
                            ];
                            $upKendariDB->table('laporan_kit_bbm_flowmeters')
                                ->updateOrInsert(
                                    ['laporan_kit_bbm_id' => $newBbmId, 'flowmeter_number' => $flowmeter->flowmeter_number],
                                    $flowmeterData
                                );
                        }
                    }

                    // Sync KWH and related data
                    foreach ($event->laporanKit->kwh as $kwh) {
                        // Insert KWH parent, dapatkan ID baru
                        $kwhData = [
                            'laporan_kit_id' => $newKitId,
                            'prod_total' => $kwh->prod_total,
                            'ps_total' => $kwh->ps_total,
                            'created_at' => now(),
                            'updated_at' => now()
                        ];
                        $upKendariDB->table('laporan_kit_kwh')->insert($kwhData);
                        $newKwhId = $upKendariDB->getPdo()->lastInsertId();

                        // Sync Production Panels
                        foreach ($kwh->productionPanels as $panel) {
                            $panelData = [
                                'laporan_kit_kwh_id' => $newKwhId,
                                'panel_number' => $panel->panel_number,
                                'awal' => $panel->awal,
                                'akhir' => $panel->akhir,
                                'created_at' => now(),
                                'updated_at' => now()
                            ];
                            $upKendariDB->table('laporan_kit_kwh_production_panels')
                                ->updateOrInsert(
                                    ['laporan_kit_kwh_id' => $newKwhId, 'panel_number' => $panel->panel_number],
                                    $panelData
                                );
                        }

                        // Sync PS Panels
                        foreach ($kwh->psPanels as $panel) {
                            $panelData = [
                                'laporan_kit_kwh_id' => $newKwhId,
                                'panel_number' => $panel->panel_number,
                                'awal' => $panel->awal,
                                'akhir' => $panel->akhir,
                                'created_at' => now(),
                                'updated_at' => now()
                            ];
                            $upKendariDB->table('laporan_kit_kwh_ps_panels')
                                ->updateOrInsert(
                                    ['laporan_kit_kwh_id' => $newKwhId, 'panel_number' => $panel->panel_number],
                                    $panelData
                                );
                        }
                    }

                    // Sync Pelumas and related data
                    foreach ($event->laporanKit->pelumas as $pelumas) {
                        // Insert Pelumas parent, dapatkan ID baru
                        $pelumasData = [
                            'laporan_kit_id' => $newKitId,
                            'tank_total_stok' => $pelumas->tank_total_stok,
                            'drum_total_stok' => $pelumas->drum_total_stok,
                            'total_stok_tangki' => $pelumas->total_stok_tangki,
                            'terima_pelumas' => $pelumas->terima_pelumas,
                            'total_pakai' => $pelumas->total_pakai,
                            'jenis' => $pelumas->jenis,
                            'created_at' => now(),
                            'updated_at' => now()
                        ];
                        $upKendariDB->table('laporan_kit_pelumas')->insert($pelumasData);
                        $newPelumasId = $upKendariDB->getPdo()->lastInsertId();

                        // Sync Storage Tanks
                        foreach ($pelumas->storageTanks as $tank) {
                            $tankData = [
                                'laporan_kit_pelumas_id' => $newPelumasId,
                                'tank_number' => $tank->tank_number,
                                'cm' => $tank->cm,
                                'liter' => $tank->liter,
                                'created_at' => now(),
                                'updated_at' => now()
                            ];
                            $upKendariDB->table('laporan_kit_pelumas_storage_tanks')
                                ->updateOrInsert(
                                    ['laporan_kit_pelumas_id' => $newPelumasId, 'tank_number' => $tank->tank_number],
                                    $tankData
                                );
                        }

                        // Sync Drums
                        foreach ($pelumas->drums as $drum) {
                            $drumData = [
                                'laporan_kit_pelumas_id' => $newPelumasId,
                                'area_number' => $drum->area_number,
                                'jumlah' => $drum->jumlah,
                                'created_at' => now(),
                                'updated_at' => now()
                            ];
                            $upKendariDB->table('laporan_kit_pelumas_drums')
                                ->updateOrInsert(
                                    ['laporan_kit_pelumas_id' => $newPelumasId, 'area_number' => $drum->area_number],
                                    $drumData
                                );
                        }
                    }

                    // Sync Gangguan
                    foreach ($event->laporanKit->gangguan as $gangguan) {
                        try {
                            $gangguanData = [
                                'id' => $gangguan->id,
                                'laporan_kit_id' => $newKitId,
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
                                'laporan_kit_id' => $newKitId
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

        } catch (\Exception $e) {
            Log::error('Error syncing LaporanKit and related data to UP Kendari:', [
                'action' => $event->action,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            throw $e;
        }
    }
} 
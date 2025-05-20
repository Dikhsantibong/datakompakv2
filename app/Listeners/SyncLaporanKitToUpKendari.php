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
                    // Sync main LaporanKit data
                    $data = [
                        'id' => $event->laporanKit->id,
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
                            'id' => $beban->id,
                            'laporan_kit_id' => $beban->laporan_kit_id,
                            'machine_id' => $beban->machine_id,
                            'siang' => $beban->siang,
                            'malam' => $beban->malam,
                            'created_at' => now(),
                            'updated_at' => now()
                        ];
                        $upKendariDB->table('laporan_kit_beban_tertinggi')
                            ->updateOrInsert(
                                ['id' => $beban->id],
                                $bebanData
                            );
                    }

                    // Sync BBM and related data
                    foreach ($event->laporanKit->bbm as $bbm) {
                        // Sync Storage Tanks
                        foreach ($bbm->storageTanks as $tank) {
                            $tankData = [
                                'id' => $tank->id,
                                'laporan_kit_bbm_id' => $tank->laporan_kit_bbm_id,
                                'tank_number' => $tank->tank_number,
                                'cm' => $tank->cm,
                                'liter' => $tank->liter,
                                'created_at' => now(),
                                'updated_at' => now()
                            ];
                            $upKendariDB->table('laporan_kit_bbm_storage_tanks')
                                ->updateOrInsert(
                                    ['id' => $tank->id],
                                    $tankData
                                );
                        }

                        // Sync Service Tanks
                        foreach ($bbm->serviceTanks as $tank) {
                            $tankData = [
                                'id' => $tank->id,
                                'laporan_kit_bbm_id' => $tank->laporan_kit_bbm_id,
                                'tank_number' => $tank->tank_number,
                                'liter' => $tank->liter,
                                'percentage' => $tank->percentage,
                                'created_at' => now(),
                                'updated_at' => now()
                            ];
                            $upKendariDB->table('laporan_kit_bbm_service_tanks')
                                ->updateOrInsert(
                                    ['id' => $tank->id],
                                    $tankData
                                );
                        }

                        // Sync Flowmeters
                        foreach ($bbm->flowmeters as $flowmeter) {
                            $flowmeterData = [
                                'id' => $flowmeter->id,
                                'laporan_kit_bbm_id' => $flowmeter->laporan_kit_bbm_id,
                                'flowmeter_number' => $flowmeter->flowmeter_number,
                                'awal' => $flowmeter->awal,
                                'akhir' => $flowmeter->akhir,
                                'pakai' => $flowmeter->pakai,
                                'created_at' => now(),
                                'updated_at' => now()
                            ];
                            $upKendariDB->table('laporan_kit_bbm_flowmeters')
                                ->updateOrInsert(
                                    ['id' => $flowmeter->id],
                                    $flowmeterData
                                );
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
                        $upKendariDB->table('laporan_kit_gangguan')
                            ->updateOrInsert(
                                ['id' => $gangguan->id],
                                $gangguanData
                            );
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
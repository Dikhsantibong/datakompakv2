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
                        $laporanKitId = $upKendariDB->table('laporan_kits')->insertGetId($data);
                    } else {
                        $laporanKitId = $event->laporanKit->id;
                        $upKendariDB->table('laporan_kits')
                            ->where('id', $laporanKitId)
                            ->update($data);
                    }

                    // Sync BebanTertinggi
                    foreach ($event->laporanKit->bebanTertinggi as $beban) {
                        $bebanData = [
                            'laporan_kit_id' => $laporanKitId,
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
                                ->where('laporan_kit_id', $laporanKitId)
                                ->where('machine_id', $beban->machine_id)
                                ->update($bebanData);
                        }
                    }

                    // Sync BBM and related data
                    foreach ($event->laporanKit->bbm as $bbm) {
                        // Insert BBM induk
                        $bbmData = [
                            'laporan_kit_id' => $laporanKitId,
                            'total_stok' => $bbm->total_stok,
                            'service_total_stok' => $bbm->service_total_stok,
                            'total_stok_tangki' => $bbm->total_stok_tangki,
                            'terima_bbm' => $bbm->terima_bbm,
                            'total_pakai' => $bbm->total_pakai,
                            'created_at' => now(),
                            'updated_at' => now()
                        ];

                        if ($event->action === 'create') {
                            $bbmId = $upKendariDB->table('laporan_kit_bbm')->insertGetId($bbmData);
                        } else {
                            $bbmId = $bbm->id;
                            $upKendariDB->table('laporan_kit_bbm')
                                ->where('id', $bbmId)
                                ->update($bbmData);
                        }

                        // Sync Storage Tanks
                        foreach ($bbm->storageTanks as $tank) {
                            $tankData = [
                                'laporan_kit_bbm_id' => $bbmId,
                                'tank_number' => $tank->tank_number,
                                'cm' => $tank->cm,
                                'liter' => $tank->liter,
                                'created_at' => now(),
                                'updated_at' => now()
                            ];

                            if ($event->action === 'create') {
                                $upKendariDB->table('laporan_kit_bbm_storage_tanks')->insert($tankData);
                            } else {
                                $upKendariDB->table('laporan_kit_bbm_storage_tanks')
                                    ->where('laporan_kit_bbm_id', $bbmId)
                                    ->where('tank_number', $tank->tank_number)
                                    ->update($tankData);
                            }
                        }

                        // Sync Service Tanks
                        foreach ($bbm->serviceTanks as $tank) {
                            $tankData = [
                                'laporan_kit_bbm_id' => $bbmId,
                                'tank_number' => $tank->tank_number,
                                'liter' => $tank->liter,
                                'percentage' => $tank->percentage,
                                'created_at' => now(),
                                'updated_at' => now()
                            ];

                            if ($event->action === 'create') {
                                $upKendariDB->table('laporan_kit_bbm_service_tanks')->insert($tankData);
                            } else {
                                $upKendariDB->table('laporan_kit_bbm_service_tanks')
                                    ->where('laporan_kit_bbm_id', $bbmId)
                                    ->where('tank_number', $tank->tank_number)
                                    ->update($tankData);
                            }
                        }

                        // Sync Flowmeters
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

                            if ($event->action === 'create') {
                                $upKendariDB->table('laporan_kit_bbm_flowmeters')->insert($flowmeterData);
                            } else {
                                $upKendariDB->table('laporan_kit_bbm_flowmeters')
                                    ->where('laporan_kit_bbm_id', $bbmId)
                                    ->where('flowmeter_number', $flowmeter->flowmeter_number)
                                    ->update($flowmeterData);
                            }
                        }
                    }

                    // Sync KWH and related data
                    foreach ($event->laporanKit->kwh as $kwh) {
                        $kwhData = [
                            'laporan_kit_id' => $laporanKitId,
                            'prod_panel1_awal' => $kwh->prod_panel1_awal,
                            'prod_panel1_akhir' => $kwh->prod_panel1_akhir,
                            'prod_panel2_awal' => $kwh->prod_panel2_awal,
                            'prod_panel2_akhir' => $kwh->prod_panel2_akhir,
                            'prod_total' => $kwh->prod_total,
                            'ps_panel1_awal' => $kwh->ps_panel1_awal,
                            'ps_panel1_akhir' => $kwh->ps_panel1_akhir,
                            'ps_panel2_awal' => $kwh->ps_panel2_awal,
                            'ps_panel2_akhir' => $kwh->ps_panel2_akhir,
                            'ps_total' => $kwh->ps_total,
                            'created_at' => now(),
                            'updated_at' => now()
                        ];

                        if ($event->action === 'create') {
                            $kwhId = $upKendariDB->table('laporan_kit_kwh')->insertGetId($kwhData);
                        } else {
                            $kwhId = $kwh->id;
                            $upKendariDB->table('laporan_kit_kwh')
                                ->where('id', $kwhId)
                                ->update($kwhData);
                        }

                        // Sync Production Panels
                        foreach ($kwh->productionPanels as $panel) {
                            $panelData = [
                                'laporan_kit_kwh_id' => $kwhId,
                                'panel_number' => $panel->panel_number,
                                'awal' => $panel->awal,
                                'akhir' => $panel->akhir,
                                'created_at' => now(),
                                'updated_at' => now()
                            ];

                            if ($event->action === 'create') {
                                $upKendariDB->table('laporan_kit_kwh_production_panels')->insert($panelData);
                            } else {
                                $upKendariDB->table('laporan_kit_kwh_production_panels')
                                    ->where('laporan_kit_kwh_id', $kwhId)
                                    ->where('panel_number', $panel->panel_number)
                                    ->update($panelData);
                            }
                        }

                        // Sync PS Panels
                        foreach ($kwh->psPanels as $panel) {
                            $panelData = [
                                'laporan_kit_kwh_id' => $kwhId,
                                'panel_number' => $panel->panel_number,
                                'awal' => $panel->awal,
                                'akhir' => $panel->akhir,
                                'created_at' => now(),
                                'updated_at' => now()
                            ];

                            if ($event->action === 'create') {
                                $upKendariDB->table('laporan_kit_kwh_ps_panels')->insert($panelData);
                            } else {
                                $upKendariDB->table('laporan_kit_kwh_ps_panels')
                                    ->where('laporan_kit_kwh_id', $kwhId)
                                    ->where('panel_number', $panel->panel_number)
                                    ->update($panelData);
                            }
                        }
                    }

                    // Sync Pelumas and related data
                    foreach ($event->laporanKit->pelumas as $pelumas) {
                        $pelumasData = [
                            'laporan_kit_id' => $laporanKitId,
                            'tank_total_stok' => $pelumas->tank_total_stok,
                            'drum_total_stok' => $pelumas->drum_total_stok,
                            'total_stok_tangki' => $pelumas->total_stok_tangki,
                            'terima_pelumas' => $pelumas->terima_pelumas,
                            'total_pakai' => $pelumas->total_pakai,
                            'jenis' => $pelumas->jenis,
                            'created_at' => now(),
                            'updated_at' => now()
                        ];

                        if ($event->action === 'create') {
                            $pelumasId = $upKendariDB->table('laporan_kit_pelumas')->insertGetId($pelumasData);
                        } else {
                            $pelumasId = $pelumas->id;
                            $upKendariDB->table('laporan_kit_pelumas')
                                ->where('id', $pelumasId)
                                ->update($pelumasData);
                        }

                        // Sync Storage Tanks
                        foreach ($pelumas->storageTanks as $tank) {
                            $tankData = [
                                'laporan_kit_pelumas_id' => $pelumasId,
                                'tank_number' => $tank->tank_number,
                                'cm' => $tank->cm,
                                'liter' => $tank->liter,
                                'created_at' => now(),
                                'updated_at' => now()
                            ];

                            if ($event->action === 'create') {
                                $upKendariDB->table('laporan_kit_pelumas_storage_tanks')->insert($tankData);
                            } else {
                                $upKendariDB->table('laporan_kit_pelumas_storage_tanks')
                                    ->where('laporan_kit_pelumas_id', $pelumasId)
                                    ->where('tank_number', $tank->tank_number)
                                    ->update($tankData);
                            }
                        }

                        // Sync Drums
                        foreach ($pelumas->drums as $drum) {
                            $drumData = [
                                'laporan_kit_pelumas_id' => $pelumasId,
                                'area_number' => $drum->area_number,
                                'jumlah' => $drum->jumlah,
                                'created_at' => now(),
                                'updated_at' => now()
                            ];

                            if ($event->action === 'create') {
                                $upKendariDB->table('laporan_kit_pelumas_drums')->insert($drumData);
                            } else {
                                $upKendariDB->table('laporan_kit_pelumas_drums')
                                    ->where('laporan_kit_pelumas_id', $pelumasId)
                                    ->where('area_number', $drum->area_number)
                                    ->update($drumData);
                            }
                        }
                    }

                    // Sync Gangguan
                    foreach ($event->laporanKit->gangguan as $gangguan) {
                        $gangguanData = [
                            'laporan_kit_id' => $laporanKitId,
                            'machine_id' => $gangguan->machine_id,
                            'mekanik' => $gangguan->mekanik,
                            'elektrik' => $gangguan->elektrik,
                            'keterangan' => $gangguan->keterangan,
                            'created_at' => now(),
                            'updated_at' => now()
                        ];

                        if ($event->action === 'create') {
                            $upKendariDB->table('laporan_kit_gangguan')->insert($gangguanData);
                        } else {
                            $upKendariDB->table('laporan_kit_gangguan')
                                ->where('laporan_kit_id', $laporanKitId)
                                ->where('machine_id', $gangguan->machine_id)
                                ->update($gangguanData);
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

                    $upKendariDB->table('laporan_kit_bbm_flowmeters')
                        ->whereIn('laporan_kit_bbm_id', function($query) use ($event) {
                            $query->select('id')
                                ->from('laporan_kit_bbm')
                                ->where('laporan_kit_id', $event->laporanKit->id);
                        })
                        ->delete();

                    $upKendariDB->table('laporan_kit_bbm')
                        ->where('laporan_kit_id', $event->laporanKit->id)
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

                    $upKendariDB->table('laporan_kit_kwh')
                        ->where('laporan_kit_id', $event->laporanKit->id)
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

                    $upKendariDB->table('laporan_kit_pelumas')
                        ->where('laporan_kit_id', $event->laporanKit->id)
                        ->delete();

                    $upKendariDB->table('laporan_kit_gangguan')
                        ->where('laporan_kit_id', $event->laporanKit->id)
                        ->delete();

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
            Log::error('Error in LaporanKit sync:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
        } finally {
            \App\Models\LaporanKit::$isSyncing = false;
        }
    }
} 
<?php

namespace App\Listeners;

use App\Events\K3KampReportUpdated;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\K3KampReport;
use App\Models\K3KampItem;
use App\Models\K3KampMedia;

class SyncK3KampReportToUpKendari
{
    public function handle(K3KampReportUpdated $event)
    {
        try {
            $currentSession = session('unit', 'mysql');

            // Add unit mapping
            $unitMapping = [
                'mysql_poasia' => 'PLTD POASIA',
                'mysql_kolaka' => 'PLTD KOLAKA',
                'mysql_bau_bau' => 'PLTD BAU BAU',
                'mysql_wua_wua' => 'PLTD WUA WUA',
                'mysql_winning' => 'PLTD WINNING',
                'mysql_erkee' => 'PLTD ERKEE',
                'mysql_ladumpi' => 'PLTD LADUMPI',
                'mysql_langara' => 'PLTD LANGARA',
                'mysql_lanipa_nipa' => 'PLTD LANIPA-NIPA',
                'mysql_pasarwajo' => 'PLTD PASARWAJO',
                'mysql_poasia_containerized' => 'PLTD POASIA CONTAINERIZED',
                'mysql_raha' => 'PLTD RAHA',
                'mysql_wajo' => 'PLTD WAJO',
                'mysql_wangi_wangi' => 'PLTD WANGI-WANGI',
                'mysql_rongi' => 'PLTD RONGI',
                'mysql_sabilambo' => 'PLTM SABILAMBO',
                'mysql_pltmg_bau_bau' => 'PLTD BAU BAU',
                'mysql_pltmg_kendari' => 'PLTD KENDARI',
                'mysql_baruta' => 'PLTD BARUTA',
                'mysql_moramo' => 'PLTD MORAMO',
                'mysql' => 'UP Kendari'
            ];

            Log::info('Processing K3 KAMP report sync event', [
                'current_session' => $currentSession,
                'report_id' => $event->k3KampReport->id,
                'date' => $event->k3KampReport->date,
                'action' => $event->action
            ]);

            // Skip if not the correct unit
            if ($currentSession !== 'mysql' && $currentSession !== $event->sourceUnit) {
                Log::info('Skipping sync - Not the correct unit', [
                    'current_session' => $currentSession,
                    'source_unit' => $event->sourceUnit
                ]);
                return;
            }

            // If from local unit, sync to UP Kendari
            if ($currentSession !== 'mysql') {
                $reportData = [
                    'date' => $event->k3KampReport->date,
                    'created_by' => $event->k3KampReport->created_by,
                    'sync_unit_origin' => $event->k3KampReport->sync_unit_origin,
                    'updated_at' => now()
                ];

                $upKendariDB = DB::connection('mysql');
                DB::beginTransaction();
                try {
                    switch($event->action) {
                        case 'create': {
                            $reportData['created_at'] = now();
                            // Insert report, get new id
                            $reportId = $upKendariDB->table('k3_kamp_reports')->insertGetId($reportData);
                            session(['k3_kamp_report_id_map.' . $event->k3KampReport->id => $reportId]);
                            // Insert items
                            foreach ($event->k3KampReport->items as $item) {
                                $itemData = [
                                    'report_id' => $reportId,
                                    'item_type' => $item->item_type,
                                    'item_name' => $item->item_name,
                                    'status' => $item->status,
                                    'kondisi' => $item->kondisi,
                                    'keterangan' => $item->keterangan,
                                    'created_at' => now(),
                                    'updated_at' => now()
                                ];
                                $itemId = $upKendariDB->table('k3_kamp_items')->insertGetId($itemData);
                                session(['k3_kamp_item_id_map.' . $item->id => $itemId]);
                                // Insert media
                                foreach ($item->media as $media) {
                                    $mediaData = [
                                        'item_id' => $itemId,
                                        'media_type' => $media->media_type,
                                        'file_path' => $media->file_path,
                                        'original_name' => $media->original_name,
                                        'file_size' => $media->file_size,
                                        'created_at' => now(),
                                        'updated_at' => now()
                                    ];
                                    $upKendariDB->table('k3_kamp_media')->insert($mediaData);
                                }
                            }
                            break;
                        }
                        case 'update': {
                            // Check if report exists
                            $exists = $upKendariDB->table('k3_kamp_reports')->where('id', $event->k3KampReport->id)->exists();
                            if (!$exists) {
                                // Treat as create
                                $reportData['created_at'] = now();
                                $reportId = $upKendariDB->table('k3_kamp_reports')->insertGetId($reportData);
                                session(['k3_kamp_report_id_map.' . $event->k3KampReport->id => $reportId]);
                            } else {
                                $upKendariDB->table('k3_kamp_reports')->where('id', $event->k3KampReport->id)->update($reportData);
                                $reportId = $event->k3KampReport->id;
                                session(['k3_kamp_report_id_map.' . $event->k3KampReport->id => $reportId]);
                            }
                            // Delete all child items & media first
                            $itemIds = $upKendariDB->table('k3_kamp_items')->where('report_id', $reportId)->pluck('id');
                            $upKendariDB->table('k3_kamp_media')->whereIn('item_id', $itemIds)->delete();
                            $upKendariDB->table('k3_kamp_items')->where('report_id', $reportId)->delete();
                            // Re-insert items & media
                            foreach ($event->k3KampReport->items as $item) {
                                $itemData = [
                                    'report_id' => $reportId,
                                    'item_type' => $item->item_type,
                                    'item_name' => $item->item_name,
                                    'status' => $item->status,
                                    'kondisi' => $item->kondisi,
                                    'keterangan' => $item->keterangan,
                                    'created_at' => now(),
                                    'updated_at' => now()
                                ];
                                $itemId = $upKendariDB->table('k3_kamp_items')->insertGetId($itemData);
                                session(['k3_kamp_item_id_map.' . $item->id => $itemId]);
                                foreach ($item->media as $media) {
                                    $mediaData = [
                                        'item_id' => $itemId,
                                        'media_type' => $media->media_type,
                                        'file_path' => $media->file_path,
                                        'original_name' => $media->original_name,
                                        'file_size' => $media->file_size,
                                        'created_at' => now(),
                                        'updated_at' => now()
                                    ];
                                    $upKendariDB->table('k3_kamp_media')->insert($mediaData);
                                }
                            }
                            break;
                        }
                        case 'delete': {
                            // Delete all child media, items, and report
                            $itemIds = $upKendariDB->table('k3_kamp_items')->where('report_id', $event->k3KampReport->id)->pluck('id');
                            $upKendariDB->table('k3_kamp_media')->whereIn('item_id', $itemIds)->delete();
                            $upKendariDB->table('k3_kamp_items')->where('report_id', $event->k3KampReport->id)->delete();
                            $upKendariDB->table('k3_kamp_reports')->where('id', $event->k3KampReport->id)->delete();
                            break;
                        }
                    }
                    DB::commit();
                    Log::info("K3 KAMP report sync to UP Kendari successful", [
                        'action' => $event->action,
                        'report_id' => $event->k3KampReport->id
                    ]);
                } catch (\Exception $e) {
                    DB::rollBack();
                    throw $e;
                }
            }
            // If from UP Kendari, sync to local unit
            else {
                $targetDB = DB::connection($event->sourceUnit);
                Log::info('Syncing from UP Kendari to local unit', [
                    'target_unit' => $event->sourceUnit,
                    'report_id' => $event->k3KampReport->id
                ]);
                $reportData = [
                    'date' => $event->k3KampReport->date,
                    'created_by' => $event->k3KampReport->created_by,
                    'sync_unit_origin' => $event->k3KampReport->sync_unit_origin,
                    'updated_at' => now()
                ];
                DB::beginTransaction();
                try {
                    switch($event->action) {
                        case 'create': {
                            $reportData['created_at'] = now();
                            $reportId = $targetDB->table('k3_kamp_reports')->insertGetId($reportData);
                            session(['k3_kamp_report_id_map.' . $event->k3KampReport->id => $reportId]);
                            foreach ($event->k3KampReport->items as $item) {
                                $itemData = [
                                    'report_id' => $reportId,
                                    'item_type' => $item->item_type,
                                    'item_name' => $item->item_name,
                                    'status' => $item->status,
                                    'kondisi' => $item->kondisi,
                                    'keterangan' => $item->keterangan,
                                    'created_at' => now(),
                                    'updated_at' => now()
                                ];
                                $itemId = $targetDB->table('k3_kamp_items')->insertGetId($itemData);
                                session(['k3_kamp_item_id_map.' . $item->id => $itemId]);
                                foreach ($item->media as $media) {
                                    $mediaData = [
                                        'item_id' => $itemId,
                                        'media_type' => $media->media_type,
                                        'file_path' => $media->file_path,
                                        'original_name' => $media->original_name,
                                        'file_size' => $media->file_size,
                                        'created_at' => now(),
                                        'updated_at' => now()
                                    ];
                                    $targetDB->table('k3_kamp_media')->insert($mediaData);
                                }
                            }
                            break;
                        }
                        case 'update': {
                            $exists = $targetDB->table('k3_kamp_reports')->where('id', $event->k3KampReport->id)->exists();
                            if (!$exists) {
                                $reportData['created_at'] = now();
                                $reportId = $targetDB->table('k3_kamp_reports')->insertGetId($reportData);
                                session(['k3_kamp_report_id_map.' . $event->k3KampReport->id => $reportId]);
                            } else {
                                $targetDB->table('k3_kamp_reports')->where('id', $event->k3KampReport->id)->update($reportData);
                                $reportId = $event->k3KampReport->id;
                                session(['k3_kamp_report_id_map.' . $event->k3KampReport->id => $reportId]);
                            }
                            $itemIds = $targetDB->table('k3_kamp_items')->where('report_id', $reportId)->pluck('id');
                            $targetDB->table('k3_kamp_media')->whereIn('item_id', $itemIds)->delete();
                            $targetDB->table('k3_kamp_items')->where('report_id', $reportId)->delete();
                            foreach ($event->k3KampReport->items as $item) {
                                $itemData = [
                                    'report_id' => $reportId,
                                    'item_type' => $item->item_type,
                                    'item_name' => $item->item_name,
                                    'status' => $item->status,
                                    'kondisi' => $item->kondisi,
                                    'keterangan' => $item->keterangan,
                                    'created_at' => now(),
                                    'updated_at' => now()
                                ];
                                $itemId = $targetDB->table('k3_kamp_items')->insertGetId($itemData);
                                session(['k3_kamp_item_id_map.' . $item->id => $itemId]);
                                foreach ($item->media as $media) {
                                    $mediaData = [
                                        'item_id' => $itemId,
                                        'media_type' => $media->media_type,
                                        'file_path' => $media->file_path,
                                        'original_name' => $media->original_name,
                                        'file_size' => $media->file_size,
                                        'created_at' => now(),
                                        'updated_at' => now()
                                    ];
                                    $targetDB->table('k3_kamp_media')->insert($mediaData);
                                }
                            }
                            break;
                        }
                        case 'delete': {
                            $itemIds = $targetDB->table('k3_kamp_items')->where('report_id', $event->k3KampReport->id)->pluck('id');
                            $targetDB->table('k3_kamp_media')->whereIn('item_id', $itemIds)->delete();
                            $targetDB->table('k3_kamp_items')->where('report_id', $event->k3KampReport->id)->delete();
                            $targetDB->table('k3_kamp_reports')->where('id', $event->k3KampReport->id)->delete();
                            break;
                        }
                    }
                    DB::commit();
                    Log::info("Sync to local unit successful", [
                        'action' => $event->action,
                        'report_id' => $event->k3KampReport->id,
                        'target_unit' => $event->sourceUnit
                    ]);
                } catch (\Exception $e) {
                    DB::rollBack();
                    throw $e;
                }
            }
        } catch (\Exception $e) {
            Log::error("K3 KAMP report sync failed", [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'report_id' => $event->k3KampReport->id ?? null,
                'session' => $currentSession ?? 'unknown'
            ]);
        }
    }
} 
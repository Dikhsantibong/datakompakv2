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
                    'sync_unit_origin' => $event->sourceUnit,
                    'updated_at' => now()
                ];

                $upKendariDB = DB::connection('mysql');
                
                DB::beginTransaction();
                
                try {
                    switch($event->action) {
                        case 'create':
                            $reportData['created_at'] = now();
                            
                            // Create report in UP Kendari
                            $reportId = $upKendariDB->table('k3_kamp_reports')->insertGetId($reportData);
                            
                            // Sync items
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
                                
                                // Sync media
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
                            
                        case 'update':
                            // Update report
                            $upKendariDB->table('k3_kamp_reports')
                                ->where('id', $event->k3KampReport->id)
                                ->update($reportData);
                            
                            // Get existing items in UP Kendari
                            $existingItems = $upKendariDB->table('k3_kamp_items')
                                ->where('report_id', $event->k3KampReport->id)
                                ->get()
                                ->keyBy('id');

                            // Update or create items
                            foreach ($event->k3KampReport->items as $item) {
                                $itemData = [
                                    'report_id' => $event->k3KampReport->id,
                                    'item_type' => $item->item_type,
                                    'item_name' => $item->item_name,
                                    'status' => $item->status,
                                    'kondisi' => $item->kondisi,
                                    'keterangan' => $item->keterangan,
                                    'updated_at' => now()
                                ];

                                if (isset($existingItems[$item->id])) {
                                    // Update existing item
                                    $upKendariDB->table('k3_kamp_items')
                                        ->where('id', $item->id)
                                        ->update($itemData);
                                    
                                    // Remove from existing items array to track deletions
                                    unset($existingItems[$item->id]);
                                } else {
                                    // Create new item
                                    $itemData['created_at'] = now();
                                    $itemId = $upKendariDB->table('k3_kamp_items')->insertGetId($itemData);
                                    
                                    // Sync media for new item
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

                                // Handle media updates for existing items
                                if (isset($item->id)) {
                                    // Get existing media
                                    $existingMedia = $upKendariDB->table('k3_kamp_media')
                                        ->where('item_id', $item->id)
                                        ->get()
                                        ->keyBy('id');

                                    // Update or create media
                                    foreach ($item->media as $media) {
                                        $mediaData = [
                                            'item_id' => $item->id,
                                            'media_type' => $media->media_type,
                                            'file_path' => $media->file_path,
                                            'original_name' => $media->original_name,
                                            'file_size' => $media->file_size,
                                            'updated_at' => now()
                                        ];

                                        if (isset($existingMedia[$media->id])) {
                                            $upKendariDB->table('k3_kamp_media')
                                                ->where('id', $media->id)
                                                ->update($mediaData);
                                            unset($existingMedia[$media->id]);
                                        } else {
                                            $mediaData['created_at'] = now();
                                            $upKendariDB->table('k3_kamp_media')->insert($mediaData);
                                        }
                                    }

                                    // Delete removed media
                                    if ($existingMedia->count() > 0) {
                                        $upKendariDB->table('k3_kamp_media')
                                            ->whereIn('id', $existingMedia->pluck('id'))
                                            ->delete();
                                    }
                                }
                            }

                            // Delete removed items (and their media via cascade)
                            if ($existingItems->count() > 0) {
                                $upKendariDB->table('k3_kamp_items')
                                    ->whereIn('id', $existingItems->pluck('id'))
                                    ->delete();
                            }
                            break;
                            
                        case 'delete':
                            // Delete report (cascade will handle items and media)
                            $upKendariDB->table('k3_kamp_reports')
                                ->where('id', $event->k3KampReport->id)
                                ->delete();
                            break;
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
                    'sync_unit_origin' => $event->sourceUnit,
                    'updated_at' => now()
                ];

                DB::beginTransaction();
                
                try {
                    switch($event->action) {
                        case 'create':
                            $reportData['created_at'] = now();
                            
                            // Create report in local unit
                            $reportId = $targetDB->table('k3_kamp_reports')->insertGetId($reportData);
                            
                            // Sync items
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
                                
                                // Sync media
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
                            
                        case 'update':
                            // Update report
                            $targetDB->table('k3_kamp_reports')
                                ->where('id', $event->k3KampReport->id)
                                ->update($reportData);
                            
                            // Get existing items in local unit
                            $existingItems = $targetDB->table('k3_kamp_items')
                                ->where('report_id', $event->k3KampReport->id)
                                ->get()
                                ->keyBy('id');

                            // Update or create items
                            foreach ($event->k3KampReport->items as $item) {
                                $itemData = [
                                    'report_id' => $event->k3KampReport->id,
                                    'item_type' => $item->item_type,
                                    'item_name' => $item->item_name,
                                    'status' => $item->status,
                                    'kondisi' => $item->kondisi,
                                    'keterangan' => $item->keterangan,
                                    'updated_at' => now()
                                ];

                                if (isset($existingItems[$item->id])) {
                                    // Update existing item
                                    $targetDB->table('k3_kamp_items')
                                        ->where('id', $item->id)
                                        ->update($itemData);
                                    
                                    // Remove from existing items array to track deletions
                                    unset($existingItems[$item->id]);
                                } else {
                                    // Create new item
                                    $itemData['created_at'] = now();
                                    $itemId = $targetDB->table('k3_kamp_items')->insertGetId($itemData);
                                    
                                    // Sync media for new item
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

                                // Handle media updates for existing items
                                if (isset($item->id)) {
                                    // Get existing media
                                    $existingMedia = $targetDB->table('k3_kamp_media')
                                        ->where('item_id', $item->id)
                                        ->get()
                                        ->keyBy('id');

                                    // Update or create media
                                    foreach ($item->media as $media) {
                                        $mediaData = [
                                            'item_id' => $item->id,
                                            'media_type' => $media->media_type,
                                            'file_path' => $media->file_path,
                                            'original_name' => $media->original_name,
                                            'file_size' => $media->file_size,
                                            'updated_at' => now()
                                        ];

                                        if (isset($existingMedia[$media->id])) {
                                            $targetDB->table('k3_kamp_media')
                                                ->where('id', $media->id)
                                                ->update($mediaData);
                                            unset($existingMedia[$media->id]);
                                        } else {
                                            $mediaData['created_at'] = now();
                                            $targetDB->table('k3_kamp_media')->insert($mediaData);
                                        }
                                    }

                                    // Delete removed media
                                    if ($existingMedia->count() > 0) {
                                        $targetDB->table('k3_kamp_media')
                                            ->whereIn('id', $existingMedia->pluck('id'))
                                            ->delete();
                                    }
                                }
                            }

                            // Delete removed items (and their media via cascade)
                            if ($existingItems->count() > 0) {
                                $targetDB->table('k3_kamp_items')
                                    ->whereIn('id', $existingItems->pluck('id'))
                                    ->delete();
                            }
                            break;
                            
                        case 'delete':
                            // Delete report (cascade will handle items and media)
                            $targetDB->table('k3_kamp_reports')
                                ->where('id', $event->k3KampReport->id)
                                ->delete();
                            break;
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
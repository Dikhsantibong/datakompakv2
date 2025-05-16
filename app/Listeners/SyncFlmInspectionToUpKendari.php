<?php

namespace App\Listeners;

use App\Events\FlmInspectionUpdated;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\FlmInspection;

class SyncFlmInspectionToUpKendari
{
    public function handle(FlmInspectionUpdated $event)
    {
        try {
            $currentSession = session('unit', 'mysql');

            Log::info('Processing FLM inspection sync event', [
                'current_session' => $currentSession,
                'flm_id' => $event->flmInspection->flm_id,
                'tanggal' => $event->flmInspection->tanggal,
                'action' => $event->action
            ]);

            // Jika dari unit lokal, sync ke UP Kendari
            if ($currentSession !== 'mysql') {
                $data = [
                    'flm_id' => $event->flmInspection->flm_id,
                    'tanggal' => $event->flmInspection->tanggal,
                    'operator' => $event->flmInspection->operator,
                    'shift' => $event->flmInspection->shift,
                    'time' => $event->flmInspection->time,
                    'mesin' => $event->flmInspection->mesin,
                    'sistem' => $event->flmInspection->sistem,
                    'masalah' => $event->flmInspection->masalah,
                    'kondisi_awal' => $event->flmInspection->kondisi_awal,
                    'tindakan_bersihkan' => $event->flmInspection->tindakan_bersihkan,
                    'tindakan_lumasi' => $event->flmInspection->tindakan_lumasi,
                    'tindakan_kencangkan' => $event->flmInspection->tindakan_kencangkan,
                    'tindakan_perbaikan_koneksi' => $event->flmInspection->tindakan_perbaikan_koneksi,
                    'tindakan_lainnya' => $event->flmInspection->tindakan_lainnya,
                    'kondisi_akhir' => $event->flmInspection->kondisi_akhir,
                    'catatan' => $event->flmInspection->catatan,
                    'eviden_sebelum' => $event->flmInspection->eviden_sebelum,
                    'eviden_sesudah' => $event->flmInspection->eviden_sesudah,
                    'status' => $event->flmInspection->status,
                    'sync_unit_origin' => $event->flmInspection->sync_unit_origin,
                    'updated_at' => now()
                ];

                $upKendariDB = DB::connection('mysql');
                
                DB::beginTransaction();
                
                try {
                    switch($event->action) {
                        case 'create':
                            $data['created_at'] = now();
                            
                            // Check if record already exists in UP Kendari
                            $existingRecord = $upKendariDB->table('flm_inspections')
                                ->where('flm_id', $event->flmInspection->flm_id)
                                ->where('tanggal', $event->flmInspection->tanggal)
                                ->first();

                            if ($existingRecord) {
                                // Update if exists
                                $upKendariDB->table('flm_inspections')
                                    ->where('flm_id', $event->flmInspection->flm_id)
                                    ->where('tanggal', $event->flmInspection->tanggal)
                                    ->update($data);
                                
                                Log::info("Updated existing FLM inspection in UP Kendari", [
                                    'flm_id' => $event->flmInspection->flm_id,
                                    'tanggal' => $event->flmInspection->tanggal
                                ]);
                            } else {
                                // Insert if not exists
                                $upKendariDB->table('flm_inspections')->insert($data);
                                
                                Log::info("Created new FLM inspection in UP Kendari", [
                                    'flm_id' => $event->flmInspection->flm_id,
                                    'tanggal' => $event->flmInspection->tanggal
                                ]);
                            }
                            break;
                            
                        case 'update':
                            $upKendariDB->table('flm_inspections')
                                ->where('flm_id', $event->flmInspection->flm_id)
                                ->where('tanggal', $event->flmInspection->tanggal)
                                ->update($data);
                            break;
                            
                        case 'delete':
                            $upKendariDB->table('flm_inspections')
                                ->where('flm_id', $event->flmInspection->flm_id)
                                ->where('tanggal', $event->flmInspection->tanggal)
                                ->delete();
                            break;
                    }
                    
                    DB::commit();
                    
                    Log::info("FLM inspection sync to UP Kendari successful", [
                        'action' => $event->action,
                        'flm_id' => $event->flmInspection->flm_id,
                        'tanggal' => $event->flmInspection->tanggal
                    ]);
                    
                } catch (\Exception $e) {
                    DB::rollBack();
                    throw $e;
                }
            }
            // Jika dari UP Kendari, sync ke unit lokal
            else {
                $targetDB = DB::connection($currentSession);
                
                Log::info('Syncing from UP Kendari to local unit', [
                    'target_unit' => $currentSession,
                    'flm_id' => $event->flmInspection->flm_id
                ]);

                $data = [
                    'flm_id' => $event->flmInspection->flm_id,
                    'tanggal' => $event->flmInspection->tanggal,
                    'operator' => $event->flmInspection->operator,
                    'shift' => $event->flmInspection->shift,
                    'time' => $event->flmInspection->time,
                    'mesin' => $event->flmInspection->mesin,
                    'sistem' => $event->flmInspection->sistem,
                    'masalah' => $event->flmInspection->masalah,
                    'kondisi_awal' => $event->flmInspection->kondisi_awal,
                    'tindakan_bersihkan' => $event->flmInspection->tindakan_bersihkan,
                    'tindakan_lumasi' => $event->flmInspection->tindakan_lumasi,
                    'tindakan_kencangkan' => $event->flmInspection->tindakan_kencangkan,
                    'tindakan_perbaikan_koneksi' => $event->flmInspection->tindakan_perbaikan_koneksi,
                    'tindakan_lainnya' => $event->flmInspection->tindakan_lainnya,
                    'kondisi_akhir' => $event->flmInspection->kondisi_akhir,
                    'catatan' => $event->flmInspection->catatan,
                    'eviden_sebelum' => $event->flmInspection->eviden_sebelum,
                    'eviden_sesudah' => $event->flmInspection->eviden_sesudah,
                    'status' => $event->flmInspection->status,
                    'sync_unit_origin' => $event->flmInspection->sync_unit_origin,
                    'updated_at' => now()
                ];

                DB::beginTransaction();
                
                try {
                    switch($event->action) {
                        case 'create':
                            $data['created_at'] = now();
                            $targetDB->table('flm_inspections')->insert($data);
                            break;
                            
                        case 'update':
                            $targetDB->table('flm_inspections')
                                ->where('flm_id', $event->flmInspection->flm_id)
                                ->where('tanggal', $event->flmInspection->tanggal)
                                ->update($data);
                            break;
                            
                        case 'delete':
                            $targetDB->table('flm_inspections')
                                ->where('flm_id', $event->flmInspection->flm_id)
                                ->where('tanggal', $event->flmInspection->tanggal)
                                ->delete();
                            break;
                    }
                    
                    DB::commit();
                    
                    Log::info("FLM inspection sync to local unit successful", [
                        'action' => $event->action,
                        'flm_id' => $event->flmInspection->flm_id,
                        'target_unit' => $currentSession
                    ]);
                    
                } catch (\Exception $e) {
                    DB::rollBack();
                    throw $e;
                }
            }

        } catch (\Exception $e) {
            Log::error("FLM inspection sync failed", [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'flm_id' => $event->flmInspection->flm_id ?? null,
                'session' => $currentSession ?? 'unknown'
            ]);
        }
    }
} 
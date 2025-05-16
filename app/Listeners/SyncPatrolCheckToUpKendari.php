<?php

namespace App\Listeners;

use App\Events\PatrolCheckUpdated;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\PatrolCheck;

class SyncPatrolCheckToUpKendari
{
    public function handle(PatrolCheckUpdated $event)
    {
        try {
            $currentSession = session('unit', 'mysql');

            Log::info('Processing Patrol Check sync event', [
                'current_session' => $currentSession,
                'id' => $event->patrolCheck->id,
                'created_by' => $event->patrolCheck->created_by,
                'action' => $event->action
            ]);

            // Jika dari unit lokal, sync ke UP Kendari
            if ($currentSession !== 'mysql') {
                $data = [
                    'created_by' => $event->patrolCheck->created_by,
                    'shift' => $event->patrolCheck->shift,
                    'time' => $event->patrolCheck->time,
                    'condition_systems' => json_encode($event->patrolCheck->condition_systems),
                    'abnormal_equipments' => json_encode($event->patrolCheck->abnormal_equipments),
                    'condition_after' => json_encode($event->patrolCheck->condition_after),
                    'notes' => $event->patrolCheck->notes,
                    'status' => $event->patrolCheck->status,
                    'updated_at' => now()
                ];

                $upKendariDB = DB::connection('mysql');
                
                DB::beginTransaction();
                
                try {
                    switch($event->action) {
                        case 'create':
                            $data['created_at'] = now();
                            
                            // Check if record already exists in UP Kendari
                            $existingRecord = $upKendariDB->table('patrol_checks')
                                ->where('id', $event->patrolCheck->id)
                                ->first();

                            if ($existingRecord) {
                                // Update if exists
                                $upKendariDB->table('patrol_checks')
                                    ->where('id', $event->patrolCheck->id)
                                    ->update($data);
                                
                                Log::info("Updated existing Patrol Check in UP Kendari", [
                                    'id' => $event->patrolCheck->id,
                                    'created_by' => $event->patrolCheck->created_by
                                ]);
                            } else {
                                // Insert if not exists
                                $upKendariDB->table('patrol_checks')->insert($data);
                                
                                Log::info("Created new Patrol Check in UP Kendari", [
                                    'id' => $event->patrolCheck->id,
                                    'created_by' => $event->patrolCheck->created_by
                                ]);
                            }
                            break;
                            
                        case 'update':
                            $upKendariDB->table('patrol_checks')
                                ->where('id', $event->patrolCheck->id)
                                ->update($data);
                            break;
                            
                        case 'delete':
                            $upKendariDB->table('patrol_checks')
                                ->where('id', $event->patrolCheck->id)
                                ->delete();
                            break;
                    }
                    
                    DB::commit();
                    
                    Log::info("Patrol Check sync to UP Kendari successful", [
                        'action' => $event->action,
                        'id' => $event->patrolCheck->id,
                        'created_by' => $event->patrolCheck->created_by
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
                    'id' => $event->patrolCheck->id
                ]);

                $data = [
                    'created_by' => $event->patrolCheck->created_by,
                    'shift' => $event->patrolCheck->shift,
                    'time' => $event->patrolCheck->time,
                    'condition_systems' => json_encode($event->patrolCheck->condition_systems),
                    'abnormal_equipments' => json_encode($event->patrolCheck->abnormal_equipments),
                    'condition_after' => json_encode($event->patrolCheck->condition_after),
                    'notes' => $event->patrolCheck->notes,
                    'status' => $event->patrolCheck->status,
                    'updated_at' => now()
                ];

                DB::beginTransaction();
                
                try {
                    switch($event->action) {
                        case 'create':
                            $data['created_at'] = now();
                            $targetDB->table('patrol_checks')->insert($data);
                            break;
                            
                        case 'update':
                            $targetDB->table('patrol_checks')
                                ->where('id', $event->patrolCheck->id)
                                ->update($data);
                            break;
                            
                        case 'delete':
                            $targetDB->table('patrol_checks')
                                ->where('id', $event->patrolCheck->id)
                                ->delete();
                            break;
                    }
                    
                    DB::commit();
                    
                    Log::info("Patrol Check sync to local unit successful", [
                        'action' => $event->action,
                        'id' => $event->patrolCheck->id,
                        'target_unit' => $currentSession
                    ]);
                    
                } catch (\Exception $e) {
                    DB::rollBack();
                    throw $e;
                }
            }

        } catch (\Exception $e) {
            Log::error("Patrol Check sync failed", [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'id' => $event->patrolCheck->id ?? null,
                'session' => $currentSession ?? 'unknown'
            ]);
        }
    }
} 
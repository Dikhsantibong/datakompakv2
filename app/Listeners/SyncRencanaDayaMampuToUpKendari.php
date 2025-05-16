<?php

namespace App\Listeners;

use App\Events\RencanaDayaMampuUpdated;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\RencanaDayaMampu;

class SyncRencanaDayaMampuToUpKendari
{
    public function handle(RencanaDayaMampuUpdated $event)
    {
        try {
            $currentSession = session('unit', 'mysql');
            $powerPlant = $event->rencanaDayaMampu->machine->powerPlant;

            Log::info('Processing rencana daya mampu sync event', [
                'current_session' => $currentSession,
                'machine_id' => $event->rencanaDayaMampu->machine_id,
                'tanggal' => $event->rencanaDayaMampu->tanggal,
                'action' => $event->action,
                'power_plant_unit' => $powerPlant ? $powerPlant->unit_source : null
            ]);

            // Skip jika tidak ada power plant atau unit source
            if (!$powerPlant || !$powerPlant->unit_source) {
                Log::info('Skipping sync - No power plant or unit source');
                return;
            }

            // Skip jika bukan unit yang benar
            if ($currentSession !== 'mysql' && $powerPlant->unit_source !== $currentSession) {
                Log::info('Skipping sync - Not the correct unit', [
                    'current_session' => $currentSession,
                    'power_plant_unit' => $powerPlant->unit_source
                ]);
                return;
            }

            // Jika dari unit lokal, sync ke UP Kendari
            if ($currentSession !== 'mysql') {
                $data = [
                    'uuid' => $event->rencanaDayaMampu->uuid,
                    'machine_id' => $event->rencanaDayaMampu->machine_id,
                    'tanggal' => $event->rencanaDayaMampu->tanggal,
                    'daily_data' => is_array($event->rencanaDayaMampu->daily_data) ? 
                        json_encode($event->rencanaDayaMampu->daily_data) : 
                        $event->rencanaDayaMampu->daily_data,
                    'daya_pjbtl_silm' => $event->rencanaDayaMampu->daya_pjbtl_silm,
                    'dmp_existing' => $event->rencanaDayaMampu->dmp_existing,
                    'unit_source' => $event->rencanaDayaMampu->unit_source,
                    'updated_at' => now()
                ];

                $upKendariDB = DB::connection('mysql');
                
                DB::beginTransaction();
                
                try {
                    switch($event->action) {
                        case 'create':
                            $data['created_at'] = now();
                            
                            // Check if record already exists in UP Kendari
                            $existingRecord = $upKendariDB->table('rencana_daya_mampu')
                                ->where('uuid', $event->rencanaDayaMampu->uuid)
                                ->first();

                            if ($existingRecord) {
                                // Update if exists
                                $upKendariDB->table('rencana_daya_mampu')
                                    ->where('uuid', $event->rencanaDayaMampu->uuid)
                                    ->update($data);
                                
                                Log::info("Updated existing record in UP Kendari", [
                                    'uuid' => $event->rencanaDayaMampu->uuid,
                                    'machine_id' => $event->rencanaDayaMampu->machine_id,
                                    'tanggal' => $event->rencanaDayaMampu->tanggal
                                ]);
                            } else {
                                // Insert if not exists
                                $upKendariDB->table('rencana_daya_mampu')->insert($data);
                                
                                Log::info("Created new record in UP Kendari", [
                                    'uuid' => $event->rencanaDayaMampu->uuid,
                                    'machine_id' => $event->rencanaDayaMampu->machine_id,
                                    'tanggal' => $event->rencanaDayaMampu->tanggal
                                ]);
                            }
                            break;
                            
                        case 'update':
                            $upKendariDB->table('rencana_daya_mampu')
                                ->where('uuid', $event->rencanaDayaMampu->uuid)
                                ->update($data);
                            break;
                            
                        case 'delete':
                            $upKendariDB->table('rencana_daya_mampu')
                                ->where('uuid', $event->rencanaDayaMampu->uuid)
                                ->delete();
                            break;
                    }
                    
                    DB::commit();
                    
                    Log::info("Rencana daya mampu sync to UP Kendari successful", [
                        'action' => $event->action,
                        'uuid' => $event->rencanaDayaMampu->uuid,
                        'machine_id' => $event->rencanaDayaMampu->machine_id
                    ]);
                    
                } catch (\Exception $e) {
                    DB::rollBack();
                    throw $e;
                }
            }
            // Jika dari UP Kendari, sync ke unit lokal
            else if ($powerPlant->unit_source !== 'mysql') {
                $targetDB = DB::connection($powerPlant->unit_source);
                
                Log::info('Syncing from UP Kendari to local unit', [
                    'target_unit' => $powerPlant->unit_source,
                    'machine_id' => $event->rencanaDayaMampu->machine_id
                ]);

                $data = [
                    'uuid' => $event->rencanaDayaMampu->uuid,
                    'machine_id' => $event->rencanaDayaMampu->machine_id,
                    'tanggal' => $event->rencanaDayaMampu->tanggal,
                    'daily_data' => is_array($event->rencanaDayaMampu->daily_data) ? 
                        json_encode($event->rencanaDayaMampu->daily_data) : 
                        $event->rencanaDayaMampu->daily_data,
                    'daya_pjbtl_silm' => $event->rencanaDayaMampu->daya_pjbtl_silm,
                    'dmp_existing' => $event->rencanaDayaMampu->dmp_existing,
                    'unit_source' => $event->rencanaDayaMampu->unit_source,
                    'updated_at' => now()
                ];

                DB::beginTransaction();
                
                try {
                    switch($event->action) {
                        case 'create':
                            $data['created_at'] = now();
                            $targetDB->table('rencana_daya_mampu')->insert($data);
                            break;
                            
                        case 'update':
                            $targetDB->table('rencana_daya_mampu')
                                ->where('uuid', $event->rencanaDayaMampu->uuid)
                                ->update($data);
                            break;
                            
                        case 'delete':
                            $targetDB->table('rencana_daya_mampu')
                                ->where('uuid', $event->rencanaDayaMampu->uuid)
                                ->delete();
                            break;
                    }
                    
                    DB::commit();
                    
                    Log::info("Sync to local unit successful", [
                        'action' => $event->action,
                        'uuid' => $event->rencanaDayaMampu->uuid,
                        'target_unit' => $powerPlant->unit_source
                    ]);
                    
                } catch (\Exception $e) {
                    DB::rollBack();
                    throw $e;
                }
            }

        } catch (\Exception $e) {
            Log::error("Rencana daya mampu sync failed", [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'machine_id' => $event->rencanaDayaMampu->machine_id ?? null,
                'session' => $currentSession ?? 'unknown'
            ]);
        }
    }
} 
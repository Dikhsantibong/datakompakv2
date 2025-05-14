<?php

namespace App\Listeners;

use App\Events\MachineLogUpdated;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\MachineLog;

class SyncMachineLogToUpKendari
{
    public function handle(MachineLogUpdated $event)
    {
        try {
            $currentSession = session('unit', 'mysql');
            $powerPlant = $event->machineLog->machine->powerPlant;

            Log::info('Processing machine log sync event', [
                'current_session' => $currentSession,
                'machine_id' => $event->machineLog->machine_id,
                'date' => $event->machineLog->date,
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
                    'machine_id' => $event->machineLog->machine_id,
                    'date' => $event->machineLog->date,
                    'time' => $event->machineLog->time,
                    'kw' => $event->machineLog->kw,
                    'kvar' => $event->machineLog->kvar,
                    'cos_phi' => $event->machineLog->cos_phi,
                    'status' => $event->machineLog->status,
                    'keterangan' => $event->machineLog->keterangan,
                    'daya_terpasang' => $event->machineLog->daya_terpasang,
                    'silm_slo' => $event->machineLog->silm_slo,
                    'dmp_performance' => $event->machineLog->dmp_performance,
                    'updated_at' => now()
                ];

                // Lanjutkan dengan sinkronisasi ke UP Kendari
                $upKendariDB = DB::connection('mysql');
                
                DB::beginTransaction();
                
                try {
                    switch($event->action) {
                        case 'create':
                            $data['created_at'] = now();
                            
                            // Check if record already exists in UP Kendari
                            $existingRecord = $upKendariDB->table('machine_logs')
                                ->where('machine_id', $event->machineLog->machine_id)
                                ->where('date', $event->machineLog->date)
                                ->where('time', $event->machineLog->time)
                                ->first();

                            if ($existingRecord) {
                                // Update if exists
                                $upKendariDB->table('machine_logs')
                                    ->where('machine_id', $event->machineLog->machine_id)
                                    ->where('date', $event->machineLog->date)
                                    ->where('time', $event->machineLog->time)
                                    ->update($data);
                                
                                Log::info("Updated existing record in UP Kendari", [
                                    'machine_id' => $event->machineLog->machine_id,
                                    'date' => $event->machineLog->date,
                                    'time' => $event->machineLog->time
                                ]);
                            } else {
                                // Insert if not exists
                                $upKendariDB->table('machine_logs')->insert($data);
                                
                                Log::info("Created new record in UP Kendari", [
                                    'machine_id' => $event->machineLog->machine_id,
                                    'date' => $event->machineLog->date,
                                    'time' => $event->machineLog->time
                                ]);
                            }
                            break;
                            
                        case 'update':
                            $upKendariDB->table('machine_logs')
                                ->where('machine_id', $event->machineLog->machine_id)
                                ->where('date', $event->machineLog->date)
                                ->where('time', $event->machineLog->time)
                                ->update($data);
                            break;
                            
                        case 'delete':
                            $upKendariDB->table('machine_logs')
                                ->where('machine_id', $event->machineLog->machine_id)
                                ->where('date', $event->machineLog->date)
                                ->where('time', $event->machineLog->time)
                                ->delete();
                            break;
                    }
                    
                    DB::commit();
                    
                    Log::info("Machine log sync to UP Kendari successful", [
                        'action' => $event->action,
                        'machine_id' => $event->machineLog->machine_id,
                        'date' => $event->machineLog->date,
                        'time' => $event->machineLog->time
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
                    'machine_id' => $event->machineLog->machine_id
                ]);

                $data = [
                    'machine_id' => $event->machineLog->machine_id,
                    'date' => $event->machineLog->date,
                    'time' => $event->machineLog->time,
                    'kw' => $event->machineLog->kw,
                    'kvar' => $event->machineLog->kvar,
                    'cos_phi' => $event->machineLog->cos_phi,
                    'status' => $event->machineLog->status,
                    'keterangan' => $event->machineLog->keterangan,
                    'daya_terpasang' => $event->machineLog->daya_terpasang,
                    'silm_slo' => $event->machineLog->silm_slo,
                    'dmp_performance' => $event->machineLog->dmp_performance,
                    'updated_at' => now()
                ];

                DB::beginTransaction();
                
                try {
                    switch($event->action) {
                        case 'create':
                            $data['created_at'] = now();
                            $targetDB->table('machine_logs')->insert($data);
                            break;
                            
                        case 'update':
                            $targetDB->table('machine_logs')
                                ->where('machine_id', $event->machineLog->machine_id)
                                ->where('date', $event->machineLog->date)
                                ->where('time', $event->machineLog->time)
                                ->update($data);
                            break;
                            
                        case 'delete':
                            $targetDB->table('machine_logs')
                                ->where('machine_id', $event->machineLog->machine_id)
                                ->where('date', $event->machineLog->date)
                                ->where('time', $event->machineLog->time)
                                ->delete();
                            break;
                    }
                    
                    DB::commit();
                    
                    Log::info("Sync to local unit successful", [
                        'action' => $event->action,
                        'machine_id' => $event->machineLog->machine_id,
                        'target_unit' => $powerPlant->unit_source
                    ]);
                    
                } catch (\Exception $e) {
                    DB::rollBack();
                    throw $e;
                }
            }

        } catch (\Exception $e) {
            Log::error("Machine log sync failed", [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'machine_id' => $event->machineLog->machine_id ?? null,
                'session' => $currentSession ?? 'unknown'
            ]);
        }
    }
} 
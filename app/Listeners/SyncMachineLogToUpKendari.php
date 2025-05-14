<?php

namespace App\Listeners;

use App\Events\MachineLogUpdated;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

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

            // Jika dari UP Kendari, sync ke unit lokal
            if ($currentSession === 'mysql' && $powerPlant && $powerPlant->unit_source !== 'mysql') {
                $targetDB = DB::connection($powerPlant->unit_source);
                
                Log::info('Syncing machine log from UP Kendari to local unit', [
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
                    
                    Log::info("Machine log sync to local unit successful", [
                        'action' => $event->action,
                        'machine_id' => $event->machineLog->machine_id,
                        'target_unit' => $powerPlant->unit_source
                    ]);
                    
                } catch (\Exception $e) {
                    DB::rollBack();
                    throw $e;
                }
                return;
            }

            // Jika dari unit lokal, sync ke UP Kendari
            if ($currentSession !== 'mysql') {
                // Skip jika sudah di UP Kendari
                if ($currentSession === 'mysql') {
                    Log::info('Skipping machine log sync - already in UP Kendari');
                    return;
                }

                // Validasi power plant
                if (!$powerPlant || !$powerPlant->unit_source) {
                    throw new \Exception('Invalid power plant data for machine log');
                }

                // Pastikan ini adalah unit yang benar untuk disinkronkan
                if ($powerPlant->unit_source !== $currentSession) {
                    Log::info('Skipping machine log sync - not the correct unit');
                    return;
                }

                // Verifikasi data di database lokal
                $localDB = DB::connection($currentSession);
                $localRecord = $localDB->table('machine_logs')
                    ->where('machine_id', $event->machineLog->machine_id)
                    ->where('date', $event->machineLog->date)
                    ->where('time', $event->machineLog->time)
                    ->first();

                if (!$localRecord && $event->action !== 'delete') {
                    throw new \Exception('Machine log data not found in local database');
                }

                // Lanjutkan dengan sinkronisasi ke UP Kendari
                $upKendariDB = DB::connection('mysql');
                
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
                            $upKendariDB->table('machine_logs')->insert($data);
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
                        'machine_id' => $event->machineLog->machine_id
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
                'session' => $currentSession
            ]);
        }
    }
} 
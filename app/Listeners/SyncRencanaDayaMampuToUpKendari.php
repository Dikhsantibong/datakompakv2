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

            Log::info('Processing RencanaDayaMampu sync event', [
                'current_session' => $currentSession,
                'machine_id' => $event->rencanaDayaMampu->machine_id,
                'uuid' => $event->rencanaDayaMampu->uuid,
                'action' => $event->action
            ]);

            // Jika dari UP Kendari, sync ke unit lokal
            if ($currentSession === 'mysql' && $powerPlant && $powerPlant->unit_source !== 'mysql') {
                $targetDB = DB::connection($powerPlant->unit_source);
                
                $data = [
                    'uuid' => $event->rencanaDayaMampu->uuid,
                    'machine_id' => $event->rencanaDayaMampu->machine_id,
                    'tanggal' => $event->rencanaDayaMampu->tanggal,
                    'rencana' => $event->rencanaDayaMampu->rencana,
                    'realisasi' => $event->rencanaDayaMampu->realisasi,
                    'daily_data' => $event->rencanaDayaMampu->daily_data,
                    'daya_pjbtl_silm' => $event->rencanaDayaMampu->daya_pjbtl_silm,
                    'dmp_existing' => $event->rencanaDayaMampu->dmp_existing,
                    'unit_source' => $powerPlant->unit_source,
                    'updated_at' => now()
                ];

                RencanaDayaMampu::$isSyncing = true;
                
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
                } catch (\Exception $e) {
                    DB::rollBack();
                    throw $e;
                } finally {
                    RencanaDayaMampu::$isSyncing = false;
                }
                return;
            }

            // Jika dari unit lokal, sync ke UP Kendari
            if ($currentSession !== 'mysql' && $powerPlant && $currentSession === $powerPlant->unit_source) {
                $upKendariDB = DB::connection('mysql');
                
                $data = [
                    'uuid' => $event->rencanaDayaMampu->uuid,
                    'machine_id' => $event->rencanaDayaMampu->machine_id,
                    'tanggal' => $event->rencanaDayaMampu->tanggal,
                    'rencana' => $event->rencanaDayaMampu->rencana,
                    'realisasi' => $event->rencanaDayaMampu->realisasi,
                    'daily_data' => $event->rencanaDayaMampu->daily_data,
                    'daya_pjbtl_silm' => $event->rencanaDayaMampu->daya_pjbtl_silm,
                    'dmp_existing' => $event->rencanaDayaMampu->dmp_existing,
                    'unit_source' => $currentSession,
                    'updated_at' => now()
                ];

                RencanaDayaMampu::$isSyncing = true;
                
                DB::beginTransaction();
                try {
                    switch($event->action) {
                        case 'create':
                            $data['created_at'] = now();
                            $upKendariDB->table('rencana_daya_mampu')->insert($data);
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
                } catch (\Exception $e) {
                    DB::rollBack();
                    throw $e;
                } finally {
                    RencanaDayaMampu::$isSyncing = false;
                }
            }

        } catch (\Exception $e) {
            Log::error("RencanaDayaMampu sync failed", [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'uuid' => $event->rencanaDayaMampu->uuid ?? null,
                'machine_id' => $event->rencanaDayaMampu->machine_id
            ]);
        }
    }
} 
<?php

namespace App\Listeners;

use App\Events\DailySummaryUpdated;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\DailySummary;

class SyncDailySummaryToUpKendari
{
    public function handle(DailySummaryUpdated $event)
    {
        try {
            $currentSession = session('unit', 'mysql');
            $powerPlant = $event->dailySummary->powerPlant;

            if (!$powerPlant) {
                Log::error('Power Plant not found for daily summary', [
                    'uuid' => $event->dailySummary->uuid,
                    'power_plant_id' => $event->dailySummary->power_plant_id
                ]);
                return;
            }

            Log::info('Processing daily summary sync event', [
                'current_session' => $currentSession,
                'power_plant_id' => $event->dailySummary->power_plant_id,
                'uuid' => $event->dailySummary->uuid,
                'action' => $event->action,
                'unit_source' => $event->dailySummary->unit_source
            ]);

            // Jika dari unit lokal, sync ke UP Kendari
            if ($currentSession !== 'mysql') {
                DailySummary::$isSyncing = true; // Prevent recursive sync
                
                $upKendariDB = DB::connection('mysql');
                $data = $this->prepareDataForSync($event->dailySummary, $powerPlant);
                
                Log::info('Syncing to UP Kendari', [
                    'uuid' => $event->dailySummary->uuid,
                    'data' => $data
                ]);

                DB::beginTransaction();
                
                try {
                    switch($event->action) {
                        case 'create':
                        case 'update':
                            $upKendariDB->table('daily_summaries')
                                ->updateOrInsert(
                                    ['uuid' => $event->dailySummary->uuid],
                                    $data
                                );
                            break;
                            
                        case 'delete':
                            $upKendariDB->table('daily_summaries')
                                ->where('uuid', $event->dailySummary->uuid)
                                ->delete();
                            break;
                    }
                    
                    DB::commit();
                    
                    Log::info("Sync to UP Kendari successful", [
                        'action' => $event->action,
                        'uuid' => $event->dailySummary->uuid
                    ]);
                    
                } catch (\Exception $e) {
                    DB::rollBack();
                    throw $e;
                } finally {
                    DailySummary::$isSyncing = false;
                }
            }

            // Jika dari UP Kendari, sync ke unit lokal
            if ($currentSession === 'mysql' && $powerPlant && $powerPlant->unit_source !== 'mysql') {
                $targetDB = DB::connection($powerPlant->unit_source);
                
                Log::info('Syncing from UP Kendari to local unit', [
                    'target_unit' => $powerPlant->unit_source,
                    'uuid' => $event->dailySummary->uuid
                ]);

                $data = $this->prepareDataForSync($event->dailySummary, $powerPlant);

                DB::beginTransaction();
                
                try {
                    switch($event->action) {
                        case 'create':
                            $data['created_at'] = now();
                            $targetDB->table('daily_summaries')->insert($data);
                            break;
                            
                        case 'update':
                            $targetDB->table('daily_summaries')
                                ->where('uuid', $event->dailySummary->uuid)
                                ->update($data);
                            break;
                            
                        case 'delete':
                            $targetDB->table('daily_summaries')
                                ->where('uuid', $event->dailySummary->uuid)
                                ->delete();
                            break;
                    }
                    
                    DB::commit();
                    
                    Log::info("Sync to local unit successful", [
                        'action' => $event->action,
                        'uuid' => $event->dailySummary->uuid,
                        'target_unit' => $powerPlant->unit_source
                    ]);
                    
                } catch (\Exception $e) {
                    DB::rollBack();
                    throw $e;
                }
                return;
            }

        } catch (\Exception $e) {
            Log::error("Daily Summary sync failed", [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'uuid' => $event->dailySummary->uuid ?? null
            ]);
        }
    }

    private function prepareDataForSync($dailySummary, $powerPlant)
    {
        return [
            'uuid' => $dailySummary->uuid,
            'power_plant_id' => $dailySummary->power_plant_id,
            'machine_name' => $dailySummary->machine_name,
            'unit_source' => $powerPlant->unit_source,
            'date' => $dailySummary->date,
            
            // Installed & Power
            'installed_power' => $dailySummary->installed_power,
            'dmn_power' => $dailySummary->dmn_power,
            'capable_power' => $dailySummary->capable_power,
            
            // Load & Production
            'peak_load_day' => $dailySummary->peak_load_day,
            'peak_load_night' => $dailySummary->peak_load_night,
            'kit_ratio' => $dailySummary->kit_ratio,
            'gross_production' => $dailySummary->gross_production,
            'net_production' => $dailySummary->net_production,
            
            // Power Losses
            'aux_power' => $dailySummary->aux_power,
            'transformer_losses' => $dailySummary->transformer_losses,
            'usage_percentage' => $dailySummary->usage_percentage,
            
            // Operation Hours
            'period_hours' => $dailySummary->period_hours,
            'operating_hours' => $dailySummary->operating_hours,
            'standby_hours' => $dailySummary->standby_hours,
            'planned_outage' => $dailySummary->planned_outage,
            'maintenance_outage' => $dailySummary->maintenance_outage,
            'forced_outage' => $dailySummary->forced_outage,
            'ah' => $dailySummary->ah,
            
            // Trips & Outages
            'trip_machine' => $dailySummary->trip_machine,
            'trip_electrical' => $dailySummary->trip_electrical,
            'efdh' => $dailySummary->efdh,
            'epdh' => $dailySummary->epdh,
            'eudh' => $dailySummary->eudh,
            'esdh' => $dailySummary->esdh,
            
            // Performance Metrics
            'eaf' => $dailySummary->eaf,
            'sof' => $dailySummary->sof,
            'efor' => $dailySummary->efor,
            'sdof' => $dailySummary->sdof,
            'ncf' => $dailySummary->ncf,
            'nof' => $dailySummary->nof,
            'jsi' => $dailySummary->jsi,
            
            // Fuel Usage
            'hsd_fuel' => $dailySummary->hsd_fuel,
            'b10_fuel' => $dailySummary->b10_fuel,
            'b15_fuel' => $dailySummary->b15_fuel,
            'b20_fuel' => $dailySummary->b20_fuel,
            'b25_fuel' => $dailySummary->b25_fuel,
            'batubara' => $dailySummary->batubara,
            'b35_fuel' => $dailySummary->b35_fuel,
            'b40_fuel' => $dailySummary->b40_fuel,
            'mfo_fuel' => $dailySummary->mfo_fuel,
            'total_fuel' => $dailySummary->total_fuel,
            'water_usage' => $dailySummary->water_usage,
            
            // Oil Usage
            'meditran_oil' => $dailySummary->meditran_oil,
            'salyx_420' => $dailySummary->salyx_420,
            'salyx_430' => $dailySummary->salyx_430,
            'travolube_a' => $dailySummary->travolube_a,
            'turbolube_46' => $dailySummary->turbolube_46,
            'turbolube_68' => $dailySummary->turbolube_68,
            'shell_argina_s3' => $dailySummary->shell_argina_s3,
            'thermo_xt_32' => $dailySummary->thermo_xt_32,
            'shell_diala_b' => $dailySummary->shell_diala_b,
            'meditran_sx_ch4' => $dailySummary->meditran_sx_ch4,
            'total_oil' => $dailySummary->total_oil,
            
            
            // Efficiency Metrics
            'sfc_scc' => $dailySummary->sfc_scc,
            'nphr' => $dailySummary->nphr,
            'slc' => $dailySummary->slc,
            
            // Additional Info
            'notes' => $dailySummary->notes,
            
            // Timestamps
            'updated_at' => now()
            
        ];
    }
} 
<?php

namespace App\Listeners;

use App\Events\DailySummaryUpdated;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SyncDailySummaryToUpKendari
{
    public function handle(DailySummaryUpdated $event)
    {
        try {
            $currentSession = session('unit', 'mysql');
            $powerPlant = $event->dailySummary->powerPlant;

            Log::info('Processing daily summary sync event', [
                'current_session' => $currentSession,
                'power_plant_id' => $event->dailySummary->power_plant_id,
                'uuid' => $event->dailySummary->uuid,
                'action' => $event->action,
                'power_plant_unit' => $powerPlant ? $powerPlant->unit_source : null
            ]);

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

            // Jika dari unit lokal, sync ke UP Kendari
            if ($currentSession !== 'mysql') {
                $upKendariDB = DB::connection('mysql');
                
                $data = $this->prepareDataForSync($event->dailySummary, $powerPlant);

                DB::beginTransaction();
                
                try {
                    switch($event->action) {
                        case 'create':
                            $data['created_at'] = now();
                            $upKendariDB->table('daily_summaries')->insert($data);
                            break;
                            
                        case 'update':
                            $upKendariDB->table('daily_summaries')
                                ->where('uuid', $event->dailySummary->uuid)
                                ->update($data);
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
                }
            }

        } catch (\Exception $e) {
            Log::error("Daily Summary sync failed", [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'uuid' => $event->dailySummary->uuid ?? null,
                'power_plant_id' => $event->dailySummary->power_plant_id,
                'session' => $currentSession
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
            'b35_fuel' => $dailySummary->b35_fuel,
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
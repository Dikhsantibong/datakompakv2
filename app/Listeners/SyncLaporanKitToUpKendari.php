<?php

namespace App\Listeners;

use App\Events\LaporanKitUpdated;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SyncLaporanKitToUpKendari
{
    public function handle(LaporanKitUpdated $event)
    {
        try {
            $currentSession = session('unit', 'mysql');
            $powerPlant = $event->model->powerPlant ?? null;

            Log::info('Processing Laporan KIT sync event', [
                'current_session' => $currentSession,
                'model_type' => $event->modelType,
                'action' => $event->action,
                'power_plant_unit' => $powerPlant ? $powerPlant->unit_source : null
            ]);

            // Skip if no power plant or unit source
            if (!$powerPlant || !$powerPlant->unit_source) {
                Log::info('Skipping sync - No power plant or unit source');
                return;
            }

            // Skip if not the correct unit
            if ($currentSession !== 'mysql' && $powerPlant->unit_source !== $currentSession) {
                Log::info('Skipping sync - Not the correct unit', [
                    'current_session' => $currentSession,
                    'power_plant_unit' => $powerPlant->unit_source
                ]);
                return;
            }

            // Get table name based on model type
            $tableName = $this->getTableName($event->modelType);
            if (!$tableName) {
                Log::error('Invalid model type for sync', ['model_type' => $event->modelType]);
                return;
            }

            // Prepare data for sync
            $data = $this->prepareDataForSync($event->model);
            $data['updated_at'] = now();

            // If from local unit, sync to UP Kendari
            if ($currentSession !== 'mysql') {
                $upKendariDB = DB::connection('mysql');
                
                DB::beginTransaction();
                
                try {
                    switch($event->action) {
                        case 'create':
                            $data['created_at'] = now();
                            $upKendariDB->table($tableName)->insert($data);
                            break;
                            
                        case 'update':
                            $upKendariDB->table($tableName)
                                ->where('id', $event->model->id)
                                ->update($data);
                            break;
                            
                        case 'delete':
                            $upKendariDB->table($tableName)
                                ->where('id', $event->model->id)
                                ->delete();
                            break;
                    }
                    
                    DB::commit();
                    
                    Log::info("Laporan KIT sync to UP Kendari successful", [
                        'action' => $event->action,
                        'model_type' => $event->modelType,
                        'id' => $event->model->id
                    ]);
                    
                } catch (\Exception $e) {
                    DB::rollBack();
                    throw $e;
                }
            }
            // If from UP Kendari, sync to local unit
            else if ($powerPlant->unit_source !== 'mysql') {
                $targetDB = DB::connection($powerPlant->unit_source);
                
                Log::info('Syncing from UP Kendari to local unit', [
                    'target_unit' => $powerPlant->unit_source,
                    'model_type' => $event->modelType
                ]);

                DB::beginTransaction();
                
                try {
                    switch($event->action) {
                        case 'create':
                            $data['created_at'] = now();
                            $targetDB->table($tableName)->insert($data);
                            break;
                            
                        case 'update':
                            $targetDB->table($tableName)
                                ->where('id', $event->model->id)
                                ->update($data);
                            break;
                            
                        case 'delete':
                            $targetDB->table($tableName)
                                ->where('id', $event->model->id)
                                ->delete();
                            break;
                    }
                    
                    DB::commit();
                    
                    Log::info("Sync to local unit successful", [
                        'action' => $event->action,
                        'model_type' => $event->modelType,
                        'target_unit' => $powerPlant->unit_source
                    ]);
                    
                } catch (\Exception $e) {
                    DB::rollBack();
                    throw $e;
                }
            }

        } catch (\Exception $e) {
            Log::error("Laporan KIT sync failed", [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'model_type' => $event->modelType ?? 'unknown',
                'session' => $currentSession ?? 'unknown'
            ]);
        }
    }

    private function getTableName($modelType)
    {
        $tableMap = [
            'LaporanKit' => 'laporan_kits',
            'LaporanKitBbm' => 'laporan_kit_bbm',
            'LaporanKitKwh' => 'laporan_kit_kwh',
            'LaporanKitPelumas' => 'laporan_kit_pelumas',
            'LaporanKitGangguan' => 'laporan_kit_gangguan',
            'LaporanKitBahanKimia' => 'laporan_kit_bahan_kimia',
            'LaporanKitJamOperasi' => 'laporan_kit_jam_operasi',
            'LaporanKitKwhPsPanel' => 'laporan_kit_kwh_ps_panels',
            'LaporanKitPelumasDrum' => 'laporan_kit_pelumas_drums',
            'LaporanKitBbmFlowmeter' => 'laporan_kit_bbm_flowmeters',
            'LaporanKitBbmServiceTank' => 'laporan_kit_bbm_service_tanks',
            'LaporanKitBebanTertinggi' => 'laporan_kit_beban_tertinggi',
            'LaporanKitKwhProductionPanel' => 'laporan_kit_kwh_production_panels',
            'LaporanKitPelumasStorageTank' => 'laporan_kit_pelumas_storage_tanks'
        ];

        return $tableMap[$modelType] ?? null;
    }

    private function prepareDataForSync($model)
    {
        return collect($model->getAttributes())
            ->except(['id', 'created_at', 'updated_at'])
            ->toArray();
    }
} 
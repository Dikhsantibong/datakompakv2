<?php

namespace App\Listeners;

use App\Events\AbnormalReportUpdated;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SyncAbnormalReportToUpKendari
{
    public function handle(AbnormalReportUpdated $event)
    {
        try {
            $currentSession = session('unit', 'mysql');

            Log::info('Processing abnormal report sync event', [
                'current_session' => $currentSession,
                'abnormal_report_id' => $event->abnormalReport->id,
                'action' => $event->action,
                'has_chronologies' => $event->abnormalReport->chronologies->count(),
                'has_affected_machines' => $event->abnormalReport->affectedMachines->count(),
                'has_follow_up_actions' => $event->abnormalReport->followUpActions->count(),
                'has_recommendations' => $event->abnormalReport->recommendations->count(),
                'has_adm_actions' => $event->abnormalReport->admActions->count(),
                'has_evidences' => $event->abnormalReport->evidences->count()
            ]);

            // Skip if already in mysql session
            if ($currentSession === 'mysql') {
                Log::info('Skipping sync - Already in mysql session');
                return;
            }

            $upKendariDB = DB::connection('mysql');
            
            try {
                switch($event->action) {
                    case 'create':
                        // First transaction: Create parent record
                        DB::beginTransaction();
                        try {
                            // Create main abnormal report record
                            $data = [
                                'id' => $event->abnormalReport->id,
                                'created_by' => $event->abnormalReport->created_by,
                                'created_at' => now(),
                                'updated_at' => now()
                            ];
                            
                            $upKendariDB->table('abnormal_reports')->insert($data);
                            
                            DB::commit();
                            
                            Log::info('Created main abnormal report record', [
                                'id' => $event->abnormalReport->id
                            ]);
                        } catch (\Exception $e) {
                            DB::rollBack();
                            Log::error('Error creating main abnormal report record', [
                                'error' => $e->getMessage()
                            ]);
                            throw $e;
                        }

                        // Second transaction: Create child records
                        DB::beginTransaction();
                        try {
                            // Sync chronologies
                            foreach ($event->abnormalReport->chronologies as $chronology) {
                                try {
                                    $upKendariDB->table('abnormal_chronologies')->insert([
                                        'abnormal_report_id' => $event->abnormalReport->id,
                                        'waktu' => $chronology->waktu,
                                        'uraian_kejadian' => $chronology->uraian_kejadian,
                                        'visual' => $chronology->visual,
                                        'parameter' => $chronology->parameter,
                                        'turun_beban' => $chronology->turun_beban,
                                        'off_cbg' => $chronology->off_cbg,
                                        'stop' => $chronology->stop,
                                        'tl_ophar' => $chronology->tl_ophar,
                                        'tl_op' => $chronology->tl_op,
                                        'tl_har' => $chronology->tl_har,
                                        'mul' => $chronology->mul,
                                        'created_at' => now(),
                                        'updated_at' => now()
                                    ]);
                                } catch (\Exception $e) {
                                    Log::error('Error syncing chronology', [
                                        'error' => $e->getMessage()
                                    ]);
                                    throw $e;
                                }
                            }

                            // Sync affected machines
                            foreach ($event->abnormalReport->affectedMachines as $machine) {
                                try {
                                    $upKendariDB->table('affected_machines')->insert([
                                        'abnormal_report_id' => $event->abnormalReport->id,
                                        'kondisi_rusak' => $machine->kondisi_rusak,
                                        'kondisi_abnormal' => $machine->kondisi_abnormal,
                                        'created_at' => now(),
                                        'updated_at' => now()
                                    ]);
                                } catch (\Exception $e) {
                                    Log::error('Error syncing affected machine', [
                                        'error' => $e->getMessage()
                                    ]);
                                    throw $e;
                                }
                            }

                            // Sync follow up actions
                            foreach ($event->abnormalReport->followUpActions as $action) {
                                try {
                                    $upKendariDB->table('follow_up_actions')->insert([
                                        'abnormal_report_id' => $event->abnormalReport->id,
                                        'flm_tindakan' => $action->flm_tindakan,
                                        'mo_non_rutin' => $action->mo_non_rutin,
                                        'created_at' => now(),
                                        'updated_at' => now()
                                    ]);
                                } catch (\Exception $e) {
                                    Log::error('Error syncing follow up action', [
                                        'error' => $e->getMessage()
                                    ]);
                                    throw $e;
                                }
                            }

                            // Sync recommendations
                            foreach ($event->abnormalReport->recommendations as $recommendation) {
                                try {
                                    $upKendariDB->table('recommendations')->insert([
                                        'abnormal_report_id' => $event->abnormalReport->id,
                                        'content' => $recommendation->content,
                                        'created_at' => now(),
                                        'updated_at' => now()
                                    ]);
                                } catch (\Exception $e) {
                                    Log::error('Error syncing recommendation', [
                                        'error' => $e->getMessage()
                                    ]);
                                    throw $e;
                                }
                            }

                            // Sync adm actions
                            foreach ($event->abnormalReport->admActions as $admAction) {
                                try {
                                    $upKendariDB->table('adm_actions')->insert([
                                        'abnormal_report_id' => $event->abnormalReport->id,
                                        'flm' => $admAction->flm,
                                        'pm' => $admAction->pm,
                                        'cm' => $admAction->cm,
                                        'ptw' => $admAction->ptw,
                                        'created_at' => now(),
                                        'updated_at' => now()
                                    ]);
                                } catch (\Exception $e) {
                                    Log::error('Error syncing adm action', [
                                        'error' => $e->getMessage()
                                    ]);
                                    throw $e;
                                }
                            }

                            // Sync evidences
                            foreach ($event->abnormalReport->evidences as $evidence) {
                                try {
                                    $upKendariDB->table('abnormal_evidences')->insert([
                                        'abnormal_report_id' => $event->abnormalReport->id,
                                        'file_path' => $evidence->file_path,
                                        'description' => $evidence->description,
                                        'created_at' => now(),
                                        'updated_at' => now()
                                    ]);
                                } catch (\Exception $e) {
                                    Log::error('Error syncing evidence', [
                                        'error' => $e->getMessage()
                                    ]);
                                    throw $e;
                                }
                            }

                            DB::commit();
                            Log::info('Successfully synced all related records', [
                                'abnormal_report_id' => $event->abnormalReport->id
                            ]);
                        } catch (\Exception $e) {
                            DB::rollBack();
                            throw $e;
                        }
                        break;

                    case 'update':
                        // Update main record
                        $upKendariDB->table('abnormal_reports')
                            ->where('id', $event->abnormalReport->id)
                            ->update([
                                'updated_at' => now()
                            ]);

                        // Delete and reinsert all related records
                        $upKendariDB->table('abnormal_chronologies')->where('abnormal_report_id', $event->abnormalReport->id)->delete();
                        $upKendariDB->table('affected_machines')->where('abnormal_report_id', $event->abnormalReport->id)->delete();
                        $upKendariDB->table('follow_up_actions')->where('abnormal_report_id', $event->abnormalReport->id)->delete();
                        $upKendariDB->table('recommendations')->where('abnormal_report_id', $event->abnormalReport->id)->delete();
                        $upKendariDB->table('adm_actions')->where('abnormal_report_id', $event->abnormalReport->id)->delete();
                        $upKendariDB->table('abnormal_evidences')->where('abnormal_report_id', $event->abnormalReport->id)->delete();

                        // Reinsert all related records
                        foreach ($event->abnormalReport->chronologies as $chronology) {
                            $upKendariDB->table('abnormal_chronologies')->insert([
                                'abnormal_report_id' => $event->abnormalReport->id,
                                'waktu' => $chronology->waktu,
                                'uraian_kejadian' => $chronology->uraian_kejadian,
                                'visual' => $chronology->visual,
                                'parameter' => $chronology->parameter,
                                'turun_beban' => $chronology->turun_beban,
                                'off_cbg' => $chronology->off_cbg,
                                'stop' => $chronology->stop,
                                'tl_ophar' => $chronology->tl_ophar,
                                'tl_op' => $chronology->tl_op,
                                'tl_har' => $chronology->tl_har,
                                'mul' => $chronology->mul,
                                'created_at' => now(),
                                'updated_at' => now()
                            ]);
                        }

                        foreach ($event->abnormalReport->affectedMachines as $machine) {
                            $upKendariDB->table('affected_machines')->insert([
                                'abnormal_report_id' => $event->abnormalReport->id,
                                'kondisi_rusak' => $machine->kondisi_rusak,
                                'kondisi_abnormal' => $machine->kondisi_abnormal,
                                'created_at' => now(),
                                'updated_at' => now()
                            ]);
                        }

                        foreach ($event->abnormalReport->followUpActions as $action) {
                            $upKendariDB->table('follow_up_actions')->insert([
                                'abnormal_report_id' => $event->abnormalReport->id,
                                'flm_tindakan' => $action->flm_tindakan,
                                'mo_non_rutin' => $action->mo_non_rutin,
                                'created_at' => now(),
                                'updated_at' => now()
                            ]);
                        }

                        foreach ($event->abnormalReport->recommendations as $recommendation) {
                            $upKendariDB->table('recommendations')->insert([
                                'abnormal_report_id' => $event->abnormalReport->id,
                                'content' => $recommendation->content,
                                'created_at' => now(),
                                'updated_at' => now()
                            ]);
                        }

                        foreach ($event->abnormalReport->admActions as $admAction) {
                            $upKendariDB->table('adm_actions')->insert([
                                'abnormal_report_id' => $event->abnormalReport->id,
                                'flm' => $admAction->flm,
                                'pm' => $admAction->pm,
                                'cm' => $admAction->cm,
                                'ptw' => $admAction->ptw,
                                'created_at' => now(),
                                'updated_at' => now()
                            ]);
                        }

                        foreach ($event->abnormalReport->evidences as $evidence) {
                            $upKendariDB->table('abnormal_evidences')->insert([
                                'abnormal_report_id' => $event->abnormalReport->id,
                                'file_path' => $evidence->file_path,
                                'description' => $evidence->description,
                                'created_at' => now(),
                                'updated_at' => now()
                            ]);
                        }
                        break;

                    case 'delete':
                        // Delete all related records first
                        $upKendariDB->table('abnormal_chronologies')->where('abnormal_report_id', $event->abnormalReport->id)->delete();
                        $upKendariDB->table('affected_machines')->where('abnormal_report_id', $event->abnormalReport->id)->delete();
                        $upKendariDB->table('follow_up_actions')->where('abnormal_report_id', $event->abnormalReport->id)->delete();
                        $upKendariDB->table('recommendations')->where('abnormal_report_id', $event->abnormalReport->id)->delete();
                        $upKendariDB->table('adm_actions')->where('abnormal_report_id', $event->abnormalReport->id)->delete();
                        $upKendariDB->table('abnormal_evidences')->where('abnormal_report_id', $event->abnormalReport->id)->delete();
                        
                        // Delete main record
                        $upKendariDB->table('abnormal_reports')->where('id', $event->abnormalReport->id)->delete();
                        break;
                }
                
                DB::commit();
                
                Log::info("Abnormal report sync to UP Kendari successful", [
                    'action' => $event->action,
                    'abnormal_report_id' => $event->abnormalReport->id
                ]);
                
            } catch (\Exception $e) {
                DB::rollBack();
                throw $e;
            }

        } catch (\Exception $e) {
            Log::error("Abnormal report sync failed", [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'abnormal_report_id' => $event->abnormalReport->id ?? null,
                'session' => $currentSession ?? 'unknown'
            ]);
        }
    }
} 
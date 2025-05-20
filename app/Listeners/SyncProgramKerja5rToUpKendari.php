<?php

namespace App\Listeners;

use App\Events\ProgramKerja5rUpdated;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\ProgramKerja5r;

class SyncProgramKerja5rToUpKendari
{
    public function handle(ProgramKerja5rUpdated $event)
    {
        try {
            $currentSession = session('unit', 'mysql');

            Log::info('Processing Program Kerja 5R sync event', [
                'current_session' => $currentSession,
                'id' => $event->programKerja->id,
                'action' => $event->action
            ]);

            // Jika dari unit lokal, sync ke UP Kendari
            if ($currentSession !== 'mysql') {
                $data = [
                    'program_kerja' => $event->programKerja->program_kerja,
                    'goal' => $event->programKerja->goal,
                    'kondisi_awal' => $event->programKerja->kondisi_awal,
                    'progress' => $event->programKerja->progress,
                    'kondisi_akhir' => $event->programKerja->kondisi_akhir,
                    'catatan' => $event->programKerja->catatan,
                    'eviden' => $event->programKerja->eviden,
                    'group_id' => $event->programKerja->group_id,
                    'batch_id' => $event->programKerja->batch_id,
                    'updated_at' => now()
                ];

                $upKendariDB = DB::connection('mysql');
                
                DB::beginTransaction();
                
                try {
                    switch($event->action) {
                        case 'create':
                            $data['created_at'] = now();
                            
                            // Check if record already exists in UP Kendari
                            $existingRecord = $upKendariDB->table('tabel_program_kerja_5r')
                                ->where('id', $event->programKerja->id)
                                ->first();

                            if ($existingRecord) {
                                // Update if exists
                                $upKendariDB->table('tabel_program_kerja_5r')
                                    ->where('id', $event->programKerja->id)
                                    ->update($data);
                                
                                Log::info("Updated existing Program Kerja 5R in UP Kendari", [
                                    'id' => $event->programKerja->id
                                ]);
                            } else {
                                // Insert if not exists
                                $upKendariDB->table('tabel_program_kerja_5r')->insert($data);
                                
                                Log::info("Created new Program Kerja 5R in UP Kendari", [
                                    'id' => $event->programKerja->id
                                ]);
                            }
                            break;
                            
                        case 'update':
                            $upKendariDB->table('tabel_program_kerja_5r')
                                ->where('id', $event->programKerja->id)
                                ->update($data);
                            break;
                            
                        case 'delete':
                            $upKendariDB->table('tabel_program_kerja_5r')
                                ->where('id', $event->programKerja->id)
                                ->delete();
                            break;
                    }
                    
                    DB::commit();
                    
                    Log::info("Program Kerja 5R sync to UP Kendari successful", [
                        'action' => $event->action,
                        'id' => $event->programKerja->id
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
                    'id' => $event->programKerja->id
                ]);

                $data = [
                    'program_kerja' => $event->programKerja->program_kerja,
                    'goal' => $event->programKerja->goal,
                    'kondisi_awal' => $event->programKerja->kondisi_awal,
                    'progress' => $event->programKerja->progress,
                    'kondisi_akhir' => $event->programKerja->kondisi_akhir,
                    'catatan' => $event->programKerja->catatan,
                    'eviden' => $event->programKerja->eviden,
                    'group_id' => $event->programKerja->group_id,
                    'batch_id' => $event->programKerja->batch_id,
                    'updated_at' => now()
                ];

                DB::beginTransaction();
                
                try {
                    switch($event->action) {
                        case 'create':
                            $data['created_at'] = now();
                            $targetDB->table('tabel_program_kerja_5r')->insert($data);
                            break;
                            
                        case 'update':
                            $targetDB->table('tabel_program_kerja_5r')
                                ->where('id', $event->programKerja->id)
                                ->update($data);
                            break;
                            
                        case 'delete':
                            $targetDB->table('tabel_program_kerja_5r')
                                ->where('id', $event->programKerja->id)
                                ->delete();
                            break;
                    }
                    
                    DB::commit();
                    
                    Log::info("Program Kerja 5R sync to local unit successful", [
                        'action' => $event->action,
                        'id' => $event->programKerja->id,
                        'target_unit' => $currentSession
                    ]);
                    
                } catch (\Exception $e) {
                    DB::rollBack();
                    throw $e;
                }
            }

        } catch (\Exception $e) {
            Log::error("Program Kerja 5R sync failed", [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'id' => $event->programKerja->id ?? null,
                'session' => $currentSession ?? 'unknown'
            ]);
        }
    }
} 
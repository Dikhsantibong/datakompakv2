<?php

namespace App\Listeners;

use App\Events\PelumasUpdated;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\Pelumas;

class SyncPelumasToUpKendari
{
    public function handle(PelumasUpdated $event)
    {
        try {
            $currentSession = session('unit', 'mysql');

            Log::info('Processing Pelumas sync event', [
                'current_session' => $currentSession,
                'id' => $event->pelumas->id,
                'unit_id' => $event->pelumas->unit_id,
                'action' => $event->action
            ]);

            // Jika dari unit lokal, sync ke UP Kendari
            if ($currentSession !== 'mysql') {
                $data = [
                    'tanggal' => $event->pelumas->tanggal,
                    'unit_id' => $event->pelumas->unit_id,
                    'jenis_pelumas' => $event->pelumas->jenis_pelumas,
                    'saldo_awal' => $event->pelumas->saldo_awal,
                    'penerimaan' => $event->pelumas->penerimaan,
                    'pemakaian' => $event->pelumas->pemakaian,
                    'saldo_akhir' => $event->pelumas->saldo_akhir,
                    'is_opening_balance' => $event->pelumas->is_opening_balance,
                    'catatan_transaksi' => $event->pelumas->catatan_transaksi,
                    'document' => $event->pelumas->document,
                    'updated_at' => now()
                ];

                $upKendariDB = DB::connection('mysql');
                
                DB::beginTransaction();
                
                try {
                    switch($event->action) {
                        case 'create':
                            $data['created_at'] = now();
                            
                            // Check if record already exists in UP Kendari
                            $existingRecord = $upKendariDB->table('pelumas')
                                ->where('id', $event->pelumas->id)
                                ->first();

                            if ($existingRecord) {
                                // Update if exists
                                $upKendariDB->table('pelumas')
                                    ->where('id', $event->pelumas->id)
                                    ->update($data);
                                
                                Log::info("Updated existing Pelumas in UP Kendari", [
                                    'id' => $event->pelumas->id,
                                    'unit_id' => $event->pelumas->unit_id
                                ]);
                            } else {
                                // Insert if not exists
                                $upKendariDB->table('pelumas')->insert($data);
                                
                                Log::info("Created new Pelumas in UP Kendari", [
                                    'id' => $event->pelumas->id,
                                    'unit_id' => $event->pelumas->unit_id
                                ]);
                            }
                            break;
                            
                        case 'update':
                            $upKendariDB->table('pelumas')
                                ->where('id', $event->pelumas->id)
                                ->update($data);
                            break;
                            
                        case 'delete':
                            $upKendariDB->table('pelumas')
                                ->where('id', $event->pelumas->id)
                                ->delete();
                            break;
                    }
                    
                    DB::commit();
                    
                    Log::info("Pelumas sync to UP Kendari successful", [
                        'action' => $event->action,
                        'id' => $event->pelumas->id,
                        'unit_id' => $event->pelumas->unit_id
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
                    'id' => $event->pelumas->id
                ]);

                $data = [
                    'tanggal' => $event->pelumas->tanggal,
                    'unit_id' => $event->pelumas->unit_id,
                    'jenis_pelumas' => $event->pelumas->jenis_pelumas,
                    'saldo_awal' => $event->pelumas->saldo_awal,
                    'penerimaan' => $event->pelumas->penerimaan,
                    'pemakaian' => $event->pelumas->pemakaian,
                    'saldo_akhir' => $event->pelumas->saldo_akhir,
                    'is_opening_balance' => $event->pelumas->is_opening_balance,
                    'catatan_transaksi' => $event->pelumas->catatan_transaksi,
                    'document' => $event->pelumas->document,
                    'updated_at' => now()
                ];

                DB::beginTransaction();
                
                try {
                    switch($event->action) {
                        case 'create':
                            $data['created_at'] = now();
                            $targetDB->table('pelumas')->insert($data);
                            break;
                            
                        case 'update':
                            $targetDB->table('pelumas')
                                ->where('id', $event->pelumas->id)
                                ->update($data);
                            break;
                            
                        case 'delete':
                            $targetDB->table('pelumas')
                                ->where('id', $event->pelumas->id)
                                ->delete();
                            break;
                    }
                    
                    DB::commit();
                    
                    Log::info("Pelumas sync to local unit successful", [
                        'action' => $event->action,
                        'id' => $event->pelumas->id,
                        'target_unit' => $currentSession
                    ]);
                    
                } catch (\Exception $e) {
                    DB::rollBack();
                    throw $e;
                }
            }

        } catch (\Exception $e) {
            Log::error("Pelumas sync failed", [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'id' => $event->pelumas->id ?? null,
                'session' => $currentSession ?? 'unknown'
            ]);
        }
    }
} 
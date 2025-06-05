<?php

namespace App\Listeners;

use App\Events\BahanKimiaUpdated;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\BahanKimia;

class SyncBahanKimiaToUpKendari
{
    public function handle(BahanKimiaUpdated $event)
    {
        try {
            $currentSession = session('unit', 'mysql');

            Log::info('Processing Bahan Kimia sync event', [
                'current_session' => $currentSession,
                'id' => $event->bahanKimia->id,
                'unit_id' => $event->bahanKimia->unit_id,
                'action' => $event->action
            ]);

            // Jika dari unit lokal, sync ke UP Kendari
            if ($currentSession !== 'mysql') {
                $data = [
                    'tanggal' => $event->bahanKimia->tanggal,
                    'unit_id' => $event->bahanKimia->unit_id,
                    'jenis_bahan' => $event->bahanKimia->jenis_bahan,
                    'saldo_awal' => $event->bahanKimia->saldo_awal,
                    'penerimaan' => $event->bahanKimia->penerimaan,
                    'pemakaian' => $event->bahanKimia->pemakaian,
                    'saldo_akhir' => $event->bahanKimia->saldo_akhir,
                    'is_opening_balance' => $event->bahanKimia->is_opening_balance,
                    'catatan_transaksi' => $event->bahanKimia->catatan_transaksi,
                    'evidence' => $event->bahanKimia->evidence,
                    'updated_at' => now()
                ];

                $upKendariDB = DB::connection('mysql');
                
                DB::beginTransaction();
                
                try {
                    switch($event->action) {
                        case 'create':
                            $data['created_at'] = now();
                            
                            // Check if record already exists in UP Kendari
                            $existingRecord = $upKendariDB->table('bahan_kimia')
                                ->where('id', $event->bahanKimia->id)
                                ->first();

                            if ($existingRecord) {
                                // Update if exists
                                $upKendariDB->table('bahan_kimia')
                                    ->where('id', $event->bahanKimia->id)
                                    ->update($data);
                                
                                Log::info("Updated existing Bahan Kimia in UP Kendari", [
                                    'id' => $event->bahanKimia->id,
                                    'unit_id' => $event->bahanKimia->unit_id
                                ]);
                            } else {
                                // Insert if not exists
                                $upKendariDB->table('bahan_kimia')->insert($data);
                                
                                Log::info("Created new Bahan Kimia in UP Kendari", [
                                    'id' => $event->bahanKimia->id,
                                    'unit_id' => $event->bahanKimia->unit_id
                                ]);
                            }
                            break;
                            
                        case 'update':
                            $upKendariDB->table('bahan_kimia')
                                ->where('id', $event->bahanKimia->id)
                                ->update($data);
                            break;
                            
                        case 'delete':
                            $upKendariDB->table('bahan_kimia')
                                ->where('id', $event->bahanKimia->id)
                                ->delete();
                            break;
                    }
                    
                    DB::commit();
                    
                    Log::info("Bahan Kimia sync to UP Kendari successful", [
                        'action' => $event->action,
                        'id' => $event->bahanKimia->id,
                        'unit_id' => $event->bahanKimia->unit_id
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
                    'id' => $event->bahanKimia->id
                ]);

                $data = [
                    'tanggal' => $event->bahanKimia->tanggal,
                    'unit_id' => $event->bahanKimia->unit_id,
                    'jenis_bahan' => $event->bahanKimia->jenis_bahan,
                    'saldo_awal' => $event->bahanKimia->saldo_awal,
                    'penerimaan' => $event->bahanKimia->penerimaan,
                    'pemakaian' => $event->bahanKimia->pemakaian,
                    'saldo_akhir' => $event->bahanKimia->saldo_akhir,
                    'is_opening_balance' => $event->bahanKimia->is_opening_balance,
                    'catatan_transaksi' => $event->bahanKimia->catatan_transaksi,
                    'evidence' => $event->bahanKimia->evidence,
                    'updated_at' => now()
                ];

                DB::beginTransaction();
                
                try {
                    switch($event->action) {
                        case 'create':
                            $data['created_at'] = now();
                            $targetDB->table('bahan_kimia')->insert($data);
                            break;
                            
                        case 'update':
                            $targetDB->table('bahan_kimia')
                                ->where('id', $event->bahanKimia->id)
                                ->update($data);
                            break;
                            
                        case 'delete':
                            $targetDB->table('bahan_kimia')
                                ->where('id', $event->bahanKimia->id)
                                ->delete();
                            break;
                    }
                    
                    DB::commit();
                    
                    Log::info("Bahan Kimia sync to local unit successful", [
                        'action' => $event->action,
                        'id' => $event->bahanKimia->id,
                        'target_unit' => $currentSession
                    ]);
                    
                } catch (\Exception $e) {
                    DB::rollBack();
                    throw $e;
                }
            }

        } catch (\Exception $e) {
            Log::error("Bahan Kimia sync failed", [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'id' => $event->bahanKimia->id ?? null,
                'session' => $currentSession ?? 'unknown'
            ]);
        }
    }
} 
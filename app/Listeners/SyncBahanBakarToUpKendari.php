<?php

namespace App\Listeners;

use App\Events\BahanBakarUpdated;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\BahanBakar;

class SyncBahanBakarToUpKendari
{
    public function handle(BahanBakarUpdated $event)
    {
        try {
            $currentSession = session('unit', 'mysql');

            Log::info('Processing Bahan Bakar sync event', [
                'current_session' => $currentSession,
                'id' => $event->bahanBakar->id,
                'unit_id' => $event->bahanBakar->unit_id,
                'action' => $event->action
            ]);

            // Jika dari unit lokal, sync ke UP Kendari
            if ($currentSession !== 'mysql') {
                $data = [
                    'tanggal' => $event->bahanBakar->tanggal,
                    'unit_id' => $event->bahanBakar->unit_id,
                    'jenis_bbm' => $event->bahanBakar->jenis_bbm,
                    'saldo_awal' => $event->bahanBakar->saldo_awal,
                    'penerimaan' => $event->bahanBakar->penerimaan,
                    'pemakaian' => $event->bahanBakar->pemakaian,
                    'saldo_akhir' => $event->bahanBakar->saldo_akhir,
                    'hop' => $event->bahanBakar->hop,
                    'catatan_transaksi' => $event->bahanBakar->catatan_transaksi,
                    'document' => $event->bahanBakar->document,
                    'updated_at' => now()
                ];

                $upKendariDB = DB::connection('mysql');
                
                DB::beginTransaction();
                
                try {
                    switch($event->action) {
                        case 'create':
                            $data['created_at'] = now();
                            
                            // Check if record already exists in UP Kendari
                            $existingRecord = $upKendariDB->table('bahan_bakar')
                                ->where('id', $event->bahanBakar->id)
                                ->first();

                            if ($existingRecord) {
                                // Update if exists
                                $upKendariDB->table('bahan_bakar')
                                    ->where('id', $event->bahanBakar->id)
                                    ->update($data);
                                
                                Log::info("Updated existing Bahan Bakar in UP Kendari", [
                                    'id' => $event->bahanBakar->id,
                                    'unit_id' => $event->bahanBakar->unit_id
                                ]);
                            } else {
                                // Insert if not exists
                                $upKendariDB->table('bahan_bakar')->insert($data);
                                
                                Log::info("Created new Bahan Bakar in UP Kendari", [
                                    'id' => $event->bahanBakar->id,
                                    'unit_id' => $event->bahanBakar->unit_id
                                ]);
                            }
                            break;
                            
                        case 'update':
                            $upKendariDB->table('bahan_bakar')
                                ->where('id', $event->bahanBakar->id)
                                ->update($data);
                            break;
                            
                        case 'delete':
                            $upKendariDB->table('bahan_bakar')
                                ->where('id', $event->bahanBakar->id)
                                ->delete();
                            break;
                    }
                    
                    DB::commit();
                    
                    Log::info("Bahan Bakar sync to UP Kendari successful", [
                        'action' => $event->action,
                        'id' => $event->bahanBakar->id,
                        'unit_id' => $event->bahanBakar->unit_id
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
                    'id' => $event->bahanBakar->id
                ]);

                $data = [
                    'tanggal' => $event->bahanBakar->tanggal,
                    'unit_id' => $event->bahanBakar->unit_id,
                    'jenis_bbm' => $event->bahanBakar->jenis_bbm,
                    'saldo_awal' => $event->bahanBakar->saldo_awal,
                    'penerimaan' => $event->bahanBakar->penerimaan,
                    'pemakaian' => $event->bahanBakar->pemakaian,
                    'saldo_akhir' => $event->bahanBakar->saldo_akhir,
                    'hop' => $event->bahanBakar->hop,
                    'catatan_transaksi' => $event->bahanBakar->catatan_transaksi,
                    'document' => $event->bahanBakar->document,
                    'updated_at' => now()
                ];

                DB::beginTransaction();
                
                try {
                    switch($event->action) {
                        case 'create':
                            $data['created_at'] = now();
                            $targetDB->table('bahan_bakar')->insert($data);
                            break;
                            
                        case 'update':
                            $targetDB->table('bahan_bakar')
                                ->where('id', $event->bahanBakar->id)
                                ->update($data);
                            break;
                            
                        case 'delete':
                            $targetDB->table('bahan_bakar')
                                ->where('id', $event->bahanBakar->id)
                                ->delete();
                            break;
                    }
                    
                    DB::commit();
                    
                    Log::info("Bahan Bakar sync to local unit successful", [
                        'action' => $event->action,
                        'id' => $event->bahanBakar->id,
                        'target_unit' => $currentSession
                    ]);
                    
                } catch (\Exception $e) {
                    DB::rollBack();
                    throw $e;
                }
            }

        } catch (\Exception $e) {
            Log::error("Bahan Bakar sync failed", [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'id' => $event->bahanBakar->id ?? null,
                'session' => $currentSession ?? 'unknown'
            ]);
        }
    }
} 
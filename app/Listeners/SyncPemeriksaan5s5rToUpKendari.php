<?php

namespace App\Listeners;

use App\Events\Pemeriksaan5s5rUpdated;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\Pemeriksaan5s5r;

class SyncPemeriksaan5s5rToUpKendari
{
    public function handle(Pemeriksaan5s5rUpdated $event)
    {
        try {
            $currentSession = session('unit', 'mysql');

            Log::info('Processing Pemeriksaan 5S5R sync event', [
                'current_session' => $currentSession,
                'id' => $event->pemeriksaan->id,
                'action' => $event->action
            ]);

            // Jika dari unit lokal, sync ke UP Kendari
            if ($currentSession !== 'mysql') {
                $data = [
                    'kategori' => $event->pemeriksaan->kategori,
                    'detail' => $event->pemeriksaan->detail,
                    'kondisi_awal' => $event->pemeriksaan->kondisi_awal,
                    'pic' => $event->pemeriksaan->pic,
                    'area_kerja' => $event->pemeriksaan->area_kerja,
                    'area_produksi' => $event->pemeriksaan->area_produksi,
                    'membersihkan' => $event->pemeriksaan->membersihkan,
                    'merapikan' => $event->pemeriksaan->merapikan,
                    'membuang_sampah' => $event->pemeriksaan->membuang_sampah,
                    'mengecat' => $event->pemeriksaan->mengecat,
                    'lainnya' => $event->pemeriksaan->lainnya,
                    'kondisi_akhir' => $event->pemeriksaan->kondisi_akhir,
                    'eviden' => $event->pemeriksaan->eviden,
                    'sync_unit_origin' => $event->pemeriksaan->sync_unit_origin,
                    'updated_at' => now()
                ];

                $upKendariDB = DB::connection('mysql');
                
                DB::beginTransaction();
                
                try {
                    switch($event->action) {
                        case 'create':
                            $data['created_at'] = now();
                            
                            // Check if record already exists in UP Kendari
                            $existingRecord = $upKendariDB->table('tabel_pemeriksaan_5s5r')
                                ->where('id', $event->pemeriksaan->id)
                                ->first();

                            if ($existingRecord) {
                                // Update if exists
                                $upKendariDB->table('tabel_pemeriksaan_5s5r')
                                    ->where('id', $event->pemeriksaan->id)
                                    ->update($data);
                                
                                Log::info("Updated existing Pemeriksaan 5S5R in UP Kendari", [
                                    'id' => $event->pemeriksaan->id
                                ]);
                            } else {
                                // Insert if not exists
                                $upKendariDB->table('tabel_pemeriksaan_5s5r')->insert($data);
                                
                                Log::info("Created new Pemeriksaan 5S5R in UP Kendari", [
                                    'id' => $event->pemeriksaan->id
                                ]);
                            }
                            break;
                            
                        case 'update':
                            $upKendariDB->table('tabel_pemeriksaan_5s5r')
                                ->where('id', $event->pemeriksaan->id)
                                ->update($data);
                            break;
                            
                        case 'delete':
                            $upKendariDB->table('tabel_pemeriksaan_5s5r')
                                ->where('id', $event->pemeriksaan->id)
                                ->delete();
                            break;
                    }
                    
                    DB::commit();
                    
                    Log::info("Pemeriksaan 5S5R sync to UP Kendari successful", [
                        'action' => $event->action,
                        'id' => $event->pemeriksaan->id
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
                    'id' => $event->pemeriksaan->id
                ]);

                $data = [
                    'kategori' => $event->pemeriksaan->kategori,
                    'detail' => $event->pemeriksaan->detail,
                    'kondisi_awal' => $event->pemeriksaan->kondisi_awal,
                    'pic' => $event->pemeriksaan->pic,
                    'area_kerja' => $event->pemeriksaan->area_kerja,
                    'area_produksi' => $event->pemeriksaan->area_produksi,
                    'membersihkan' => $event->pemeriksaan->membersihkan,
                    'merapikan' => $event->pemeriksaan->merapikan,
                    'membuang_sampah' => $event->pemeriksaan->membuang_sampah,
                    'mengecat' => $event->pemeriksaan->mengecat,
                    'lainnya' => $event->pemeriksaan->lainnya,
                    'kondisi_akhir' => $event->pemeriksaan->kondisi_akhir,
                    'eviden' => $event->pemeriksaan->eviden,
                    'sync_unit_origin' => $event->pemeriksaan->sync_unit_origin,
                    'updated_at' => now()
                ];

                DB::beginTransaction();
                
                try {
                    switch($event->action) {
                        case 'create':
                            $data['created_at'] = now();
                            $targetDB->table('tabel_pemeriksaan_5s5r')->insert($data);
                            break;
                            
                        case 'update':
                            $targetDB->table('tabel_pemeriksaan_5s5r')
                                ->where('id', $event->pemeriksaan->id)
                                ->update($data);
                            break;
                            
                        case 'delete':
                            $targetDB->table('tabel_pemeriksaan_5s5r')
                                ->where('id', $event->pemeriksaan->id)
                                ->delete();
                            break;
                    }
                    
                    DB::commit();
                    
                    Log::info("Pemeriksaan 5S5R sync to local unit successful", [
                        'action' => $event->action,
                        'id' => $event->pemeriksaan->id,
                        'target_unit' => $currentSession
                    ]);
                    
                } catch (\Exception $e) {
                    DB::rollBack();
                    throw $e;
                }
            }

        } catch (\Exception $e) {
            Log::error("Pemeriksaan 5S5R sync failed", [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'id' => $event->pemeriksaan->id ?? null,
                'session' => $currentSession ?? 'unknown'
            ]);
        }
    }
} 
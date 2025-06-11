<?php

namespace App\Listeners;

use App\Events\FiveS5rBatchUpdated;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\FiveS5rBatch;
use App\Models\Pemeriksaan5s5r;
use App\Models\ProgramKerja5r;

class SyncFiveS5rBatchToUpKendari
{
    public function handle(FiveS5rBatchUpdated $event)
    {
        try {
            $currentSession = session('unit', 'mysql');

            Log::info('Processing 5S5R batch sync event', [
                'current_session' => $currentSession,
                'batch_id' => $event->fiveS5rBatch->id,
                'action' => $event->action
            ]);

            // Skip if already in mysql session
            if ($currentSession === 'mysql') {
                Log::info('Skipping sync - Already in mysql session');
                return;
            }

            $upKendariDB = DB::connection('mysql');

            DB::beginTransaction();
            try {
                switch($event->action) {
                    case 'create':
                        // Try to get the latest ID from mysql database
                        $latestId = $upKendariDB->table('five_s5r_batches')->max('id');
                        $newId = $latestId ? $latestId + 1 : 1;

                        // Create main batch record with new ID
                        $data = [
                            'id' => $newId,
                            'created_by' => $event->fiveS5rBatch->created_by,
                            'sync_unit_origin' => $event->fiveS5rBatch->sync_unit_origin,
                            'created_at' => now(),
                            'updated_at' => now()
                        ];

                        // Insert with new ID
                        $upKendariDB->table('five_s5r_batches')->insert($data);

                        Log::info('Created batch record with new ID', [
                            'original_id' => $event->fiveS5rBatch->id,
                            'new_id' => $newId
                        ]);

                        // Store the mapping for child records
                        session(['five_s5r_batch_id_map.' . $event->fiveS5rBatch->id => $newId]);

                        // Sync Pemeriksaan5s5r records
                        foreach ($event->fiveS5rBatch->pemeriksaan as $pemeriksaan) {
                            $upKendariDB->table('tabel_pemeriksaan_5s5r')->insert([
                                'batch_id' => $newId,
                                'kategori' => $pemeriksaan->kategori,
                                'detail' => $pemeriksaan->detail,
                                'kondisi_awal' => $pemeriksaan->kondisi_awal,
                                'pic' => $pemeriksaan->pic,
                                'area_kerja' => $pemeriksaan->area_kerja,
                                'area_produksi' => $pemeriksaan->area_produksi,
                                'membersihkan' => $pemeriksaan->membersihkan,
                                'merapikan' => $pemeriksaan->merapikan,
                                'membuang_sampah' => $pemeriksaan->membuang_sampah,
                                'mengecat' => $pemeriksaan->mengecat,
                                'lainnya' => $pemeriksaan->lainnya,
                                'kondisi_akhir' => $pemeriksaan->kondisi_akhir,
                                'eviden' => $pemeriksaan->eviden,
                                'created_at' => now(),
                                'updated_at' => now()
                            ]);
                        }

                        // Sync ProgramKerja5r records
                        foreach ($event->fiveS5rBatch->programKerja as $program) {
                            $upKendariDB->table('tabel_program_kerja_5r')->insert([
                                'batch_id' => $newId,
                                'program_kerja' => $program->program_kerja,
                                'goal' => $program->goal,
                                'kondisi_awal' => $program->kondisi_awal,
                                'progress' => $program->progress,
                                'kondisi_akhir' => $program->kondisi_akhir,
                                'catatan' => $program->catatan,
                                'eviden' => $program->eviden,
                                'created_at' => now(),
                                'updated_at' => now()
                            ]);
                        }
                        break;

                    case 'update':
                        // Check if record exists
                        $exists = $upKendariDB->table('five_s5r_batches')
                            ->where('id', $event->fiveS5rBatch->id)
                            ->exists();

                        if (!$exists) {
                            // If record doesn't exist, treat it as a create with new ID
                            $latestId = $upKendariDB->table('five_s5r_batches')->max('id');
                            $newId = $latestId ? $latestId + 1 : 1;

                            // Create main record with new ID
                            $data = [
                                'id' => $newId,
                                'created_by' => $event->fiveS5rBatch->created_by,
                                'sync_unit_origin' => $event->fiveS5rBatch->sync_unit_origin,
                                'created_at' => now(),
                                'updated_at' => now()
                            ];

                            // Insert with new ID
                            $upKendariDB->table('five_s5r_batches')->insert($data);

                            // Store the mapping for child records
                            session(['five_s5r_batch_id_map.' . $event->fiveS5rBatch->id => $newId]);

                            Log::info('Created new record during update (record not found)', [
                                'original_id' => $event->fiveS5rBatch->id,
                                'new_id' => $newId
                            ]);

                            $parentId = $newId;
                        } else {
                            // Update existing record
                            $upKendariDB->table('five_s5r_batches')
                                ->where('id', $event->fiveS5rBatch->id)
                                ->update([
                                    'sync_unit_origin' => $event->fiveS5rBatch->sync_unit_origin,
                                    'updated_at' => now()
                                ]);

                            $parentId = $event->fiveS5rBatch->id;
                        }

                        // Delete existing child records
                        $upKendariDB->table('tabel_pemeriksaan_5s5r')->where('batch_id', $parentId)->delete();
                        $upKendariDB->table('tabel_program_kerja_5r')->where('batch_id', $parentId)->delete();

                        // Reinsert Pemeriksaan5s5r records
                        foreach ($event->fiveS5rBatch->pemeriksaan as $pemeriksaan) {
                            $upKendariDB->table('tabel_pemeriksaan_5s5r')->insert([
                                'batch_id' => $parentId,
                                'kategori' => $pemeriksaan->kategori,
                                'detail' => $pemeriksaan->detail,
                                'kondisi_awal' => $pemeriksaan->kondisi_awal,
                                'pic' => $pemeriksaan->pic,
                                'area_kerja' => $pemeriksaan->area_kerja,
                                'area_produksi' => $pemeriksaan->area_produksi,
                                'membersihkan' => $pemeriksaan->membersihkan,
                                'merapikan' => $pemeriksaan->merapikan,
                                'membuang_sampah' => $pemeriksaan->membuang_sampah,
                                'mengecat' => $pemeriksaan->mengecat,
                                'lainnya' => $pemeriksaan->lainnya,
                                'kondisi_akhir' => $pemeriksaan->kondisi_akhir,
                                'eviden' => $pemeriksaan->eviden,
                                'created_at' => now(),
                                'updated_at' => now()
                            ]);
                        }

                        // Reinsert ProgramKerja5r records
                        foreach ($event->fiveS5rBatch->programKerja as $program) {
                            $upKendariDB->table('tabel_program_kerja_5r')->insert([
                                'batch_id' => $parentId,
                                'program_kerja' => $program->program_kerja,
                                'goal' => $program->goal,
                                'kondisi_awal' => $program->kondisi_awal,
                                'progress' => $program->progress,
                                'kondisi_akhir' => $program->kondisi_akhir,
                                'catatan' => $program->catatan,
                                'eviden' => $program->eviden,
                                'created_at' => now(),
                                'updated_at' => now()
                            ]);
                        }
                        break;

                    case 'delete':
                        // Delete all related records first
                        $upKendariDB->table('tabel_pemeriksaan_5s5r')->where('batch_id', $event->fiveS5rBatch->id)->delete();
                        $upKendariDB->table('tabel_program_kerja_5r')->where('batch_id', $event->fiveS5rBatch->id)->delete();
                        // Delete main record
                        $upKendariDB->table('five_s5r_batches')->where('id', $event->fiveS5rBatch->id)->delete();
                        break;
                }

                DB::commit();

                Log::info("5S5R batch sync to UP Kendari successful", [
                    'action' => $event->action,
                    'batch_id' => $event->fiveS5rBatch->id
                ]);

            } catch (\Exception $e) {
                DB::rollBack();
                Log::error('Sync error details', [
                    'message' => $e->getMessage(),
                    'sql' => $e->getSql ?? null,
                    'bindings' => $e->getBindings ?? null,
                    'batch_id' => $event->fiveS5rBatch->id,
                    'action' => $event->action
                ]);
                throw $e;
            }

        } catch (\Exception $e) {
            Log::error("5S5R batch sync failed", [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'batch_id' => $event->fiveS5rBatch->id ?? null,
                'session' => $currentSession ?? 'unknown'
            ]);
        }
    }
} 
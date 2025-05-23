<?php

namespace App\Listeners;

use App\Events\MeetingShiftUpdated;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\MeetingShift;

class SyncMeetingShiftToUpKendari
{
    public function handle(MeetingShiftUpdated $event)
    {
        try {
            $currentSession = session('unit', 'mysql');

            Log::info('Processing meeting shift sync event', [
                'current_session' => $currentSession,
                'meeting_shift_id' => $event->meetingShift->id,
                'date' => $event->meetingShift->tanggal,
                'action' => $event->action,
                'has_machine_statuses' => $event->meetingShift->machineStatuses->count(),
                'has_auxiliary_equipment' => $event->meetingShift->auxiliaryEquipments->count(),
                'has_resources' => $event->meetingShift->resources->count(),
                'has_k3ls' => $event->meetingShift->k3ls->count(),
                'has_notes' => $event->meetingShift->notes->count(),
                'has_resume' => $event->meetingShift->resume ? 'yes' : 'no',
                'has_attendances' => $event->meetingShift->attendances->count()
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
                        DB::beginTransaction();
                        try {
                            $data = [
                                'id' => $event->meetingShift->id,
                                'tanggal' => $event->meetingShift->tanggal,
                                'current_shift' => $event->meetingShift->current_shift,
                                'created_by' => $event->meetingShift->created_by,
                                'created_at' => now(),
                                'updated_at' => now()
                            ];

                            // Cek apakah sudah ada ID tersebut di database tujuan
                            $exists = $upKendariDB->table('meeting_shifts')->where('id', $event->meetingShift->id)->exists();
                            if (!$exists) {
                                $upKendariDB->table('meeting_shifts')->insert($data);
                                Log::info('Created main meeting shift record', ['id' => $event->meetingShift->id]);
                            } else {
                                Log::warning('Meeting shift record already exists, skipping insert', ['id' => $event->meetingShift->id]);
                                // Optional: update data jika ingin update data lama
                            }

                            DB::commit();
                        } catch (\Exception $e) {
                            DB::rollBack();
                            Log::error('Error creating main meeting shift record', [
                                'error' => $e->getMessage()
                            ]);
                            throw $e;
                        }

                        // Second transaction: Create child records
                        DB::beginTransaction();
                        try {
                            // Sync machine statuses
                            foreach ($event->meetingShift->machineStatuses as $status) {
                                try {
                                    $upKendariDB->table('machine_statuses')->insert([
                                        'meeting_shift_id' => $event->meetingShift->id,
                                        'machine_id' => $status->machine_id,
                                        'status' => json_encode($status->status), // Ensure status is JSON encoded
                                        'keterangan' => $status->keterangan,
                                        'created_at' => now(),
                                        'updated_at' => now()
                                    ]);
                                } catch (\Exception $e) {
                                    Log::error('Error syncing machine status', [
                                        'machine_id' => $status->machine_id,
                                        'error' => $e->getMessage()
                                    ]);
                                    throw $e;
                                }
                            }

                            // Sync auxiliary equipment
                            foreach ($event->meetingShift->auxiliaryEquipments as $equipment) {
                                try {
                                    $upKendariDB->table('auxiliary_equipment_statuses')->insert([
                                        'meeting_shift_id' => $event->meetingShift->id,
                                        'name' => $equipment->name,
                                        'status' => json_encode($equipment->status), // Ensure status is JSON encoded
                                        'keterangan' => $equipment->keterangan,
                                        'created_at' => now(),
                                        'updated_at' => now()
                                    ]);
                                } catch (\Exception $e) {
                                    Log::error('Error syncing auxiliary equipment', [
                                        'name' => $equipment->name,
                                        'error' => $e->getMessage()
                                    ]);
                                    throw $e;
                                }
                            }

                            // Sync resources
                            foreach ($event->meetingShift->resources as $resource) {
                                try {
                                    $upKendariDB->table('resource_statuses')->insert([
                                        'meeting_shift_id' => $event->meetingShift->id,
                                        'name' => $resource->name,
                                        'category' => $resource->category,
                                        'status' => $resource->status,
                                        'keterangan' => $resource->keterangan ?? '',
                                        'created_at' => now(),
                                        'updated_at' => now()
                                    ]);
                                } catch (\Exception $e) {
                                    Log::error('Error syncing resource', [
                                        'name' => $resource->name,
                                        'error' => $e->getMessage()
                                    ]);
                                    throw $e;
                                }
                            }

                            // Sync K3L
                            foreach ($event->meetingShift->k3ls as $k3l) {
                                try {
                                    $upKendariDB->table('meeting_shift_k3l')->insert([
                                        'meeting_shift_id' => $event->meetingShift->id,
                                        'type' => $k3l->type,
                                        'uraian' => $k3l->uraian,
                                        'saran' => $k3l->saran,
                                        'eviden_path' => $k3l->eviden_path ?? null,
                                        'created_at' => now(),
                                        'updated_at' => now()
                                    ]);
                                } catch (\Exception $e) {
                                    Log::error('Error syncing k3l', [
                                        'type' => $k3l->type,
                                        'error' => $e->getMessage()
                                    ]);
                                    throw $e;
                                }
                            }

                            // Sync notes
                            foreach ($event->meetingShift->notes as $note) {
                                try {
                                    $upKendariDB->table('meeting_shift_notes')->insert([
                                        'meeting_shift_id' => $event->meetingShift->id,
                                        'type' => $note->type,
                                        'content' => $note->content,
                                        'created_at' => now(),
                                        'updated_at' => now()
                                    ]);
                                } catch (\Exception $e) {
                                    Log::error('Error syncing note', [
                                        'type' => $note->type,
                                        'error' => $e->getMessage()
                                    ]);
                                    throw $e;
                                }
                            }

                            // Sync resume
                            if ($event->meetingShift->resume) {
                                try {
                                    $upKendariDB->table('meeting_shift_resume')->insert([
                                        'meeting_shift_id' => $event->meetingShift->id,
                                        'content' => $event->meetingShift->resume->content,
                                        'created_at' => now(),
                                        'updated_at' => now()
                                    ]);
                                } catch (\Exception $e) {
                                    Log::error('Error syncing resume', [
                                        'error' => $e->getMessage()
                                    ]);
                                    throw $e;
                                }
                            }

                            // Sync attendances
                            foreach ($event->meetingShift->attendances as $attendance) {
                                try {
                                    $upKendariDB->table('meeting_shift_attendance')->insert([
                                        'meeting_shift_id' => $event->meetingShift->id,
                                        'nama' => $attendance->nama,
                                        'shift' => $attendance->shift,
                                        'status' => $attendance->status,
                                        'keterangan' => $attendance->keterangan ?? '',
                                        'created_at' => now(),
                                        'updated_at' => now()
                                    ]);
                                } catch (\Exception $e) {
                                    Log::error('Error syncing attendance', [
                                        'nama' => $attendance->nama,
                                        'error' => $e->getMessage()
                                    ]);
                                    throw $e;
                                }
                            }

                            DB::commit();
                            Log::info('Successfully synced all related records', [
                                'meeting_shift_id' => $event->meetingShift->id
                            ]);
                        } catch (\Exception $e) {
                            DB::rollBack();
                            throw $e;
                        }
                        break;
                        
                    case 'update':
                        // Update main meeting shift record
                        $upKendariDB->table('meeting_shifts')
                            ->where('id', $event->meetingShift->id)
                            ->update([
                                'tanggal' => $event->meetingShift->tanggal,
                                'current_shift' => $event->meetingShift->current_shift,
                                'updated_at' => now()
                            ]);
                            
                        // Delete and reinsert all related records
                        $upKendariDB->table('machine_statuses')->where('meeting_shift_id', $event->meetingShift->id)->delete();
                        $upKendariDB->table('auxiliary_equipment_statuses')->where('meeting_shift_id', $event->meetingShift->id)->delete();
                        $upKendariDB->table('resource_statuses')->where('meeting_shift_id', $event->meetingShift->id)->delete();
                        $upKendariDB->table('meeting_shift_k3l')->where('meeting_shift_id', $event->meetingShift->id)->delete();
                        $upKendariDB->table('meeting_shift_notes')->where('meeting_shift_id', $event->meetingShift->id)->delete();
                        $upKendariDB->table('meeting_shift_resume')->where('meeting_shift_id', $event->meetingShift->id)->delete();
                        $upKendariDB->table('meeting_shift_attendance')->where('meeting_shift_id', $event->meetingShift->id)->delete();

                        // Reinsert all related records
                        foreach ($event->meetingShift->machineStatuses as $status) {
                            $upKendariDB->table('machine_statuses')->insert([
                                'meeting_shift_id' => $event->meetingShift->id,
                                'machine_id' => $status->machine_id,
                                'status' => json_encode($status->status),
                                'keterangan' => $status->keterangan,
                                'created_at' => now(),
                                'updated_at' => now()
                            ]);
                        }

                        foreach ($event->meetingShift->auxiliaryEquipments as $equipment) {
                            $upKendariDB->table('auxiliary_equipment_statuses')->insert([
                                'meeting_shift_id' => $event->meetingShift->id,
                                'name' => $equipment->name,
                                'status' => json_encode($equipment->status),
                                'keterangan' => $equipment->keterangan,
                                'created_at' => now(),
                                'updated_at' => now()
                            ]);
                        }

                        foreach ($event->meetingShift->resources as $resource) {
                            $upKendariDB->table('resource_statuses')->insert([
                                'meeting_shift_id' => $event->meetingShift->id,
                                'name' => $resource->name,
                                'category' => $resource->category,
                                'status' => $resource->status,
                                'keterangan' => $resource->keterangan,
                                'created_at' => now(),
                                'updated_at' => now()
                            ]);
                        }

                        foreach ($event->meetingShift->k3ls as $k3l) {
                            $upKendariDB->table('meeting_shift_k3l')->insert([
                                'meeting_shift_id' => $event->meetingShift->id,
                                'type' => $k3l->type,
                                'uraian' => $k3l->uraian,
                                'saran' => $k3l->saran,
                                'eviden_path' => $k3l->eviden_path,
                                'created_at' => now(),
                                'updated_at' => now()
                            ]);
                        }

                        foreach ($event->meetingShift->notes as $note) {
                            $upKendariDB->table('meeting_shift_notes')->insert([
                                'meeting_shift_id' => $event->meetingShift->id,
                                'type' => $note->type,
                                'content' => $note->content,
                                'created_at' => now(),
                                'updated_at' => now()
                            ]);
                        }

                        if ($event->meetingShift->resume) {
                            $upKendariDB->table('meeting_shift_resume')->insert([
                                'meeting_shift_id' => $event->meetingShift->id,
                                'content' => $event->meetingShift->resume->content,
                                'created_at' => now(),
                                'updated_at' => now()
                            ]);
                        }

                        foreach ($event->meetingShift->attendances as $attendance) {
                            $upKendariDB->table('meeting_shift_attendance')->insert([
                                'meeting_shift_id' => $event->meetingShift->id,
                                'nama' => $attendance->nama,
                                'shift' => $attendance->shift,
                                'status' => $attendance->status,
                                'keterangan' => $attendance->keterangan,
                                'created_at' => now(),
                                'updated_at' => now()
                            ]);
                        }
                        break;
                        
                    case 'delete':
                        // Delete all related records first
                        $upKendariDB->table('machine_statuses')->where('meeting_shift_id', $event->meetingShift->id)->delete();
                        $upKendariDB->table('auxiliary_equipment_statuses')->where('meeting_shift_id', $event->meetingShift->id)->delete();
                        $upKendariDB->table('resource_statuses')->where('meeting_shift_id', $event->meetingShift->id)->delete();
                        $upKendariDB->table('meeting_shift_k3l')->where('meeting_shift_id', $event->meetingShift->id)->delete();
                        $upKendariDB->table('meeting_shift_notes')->where('meeting_shift_id', $event->meetingShift->id)->delete();
                        $upKendariDB->table('meeting_shift_resume')->where('meeting_shift_id', $event->meetingShift->id)->delete();
                        $upKendariDB->table('meeting_shift_attendance')->where('meeting_shift_id', $event->meetingShift->id)->delete();
                        
                        // Delete main record
                        $upKendariDB->table('meeting_shifts')->where('id', $event->meetingShift->id)->delete();
                        break;
                }
                
                DB::commit();
                
                Log::info("Meeting shift sync to UP Kendari successful", [
                    'action' => $event->action,
                    'meeting_shift_id' => $event->meetingShift->id
                ]);
                
            } catch (\Exception $e) {
                DB::rollBack();
                throw $e;
            }

        } catch (\Exception $e) {
            Log::error("Meeting shift sync failed", [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'meeting_shift_id' => $event->meetingShift->id ?? null,
                'session' => $currentSession ?? 'unknown'
            ]);
        }
    }
} 
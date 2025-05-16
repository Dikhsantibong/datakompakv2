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
                'action' => $event->action
            ]);

            // Skip if already in mysql session
            if ($currentSession === 'mysql') {
                Log::info('Skipping sync - Already in mysql session');
                return;
            }

            // Sync to UP Kendari (mysql)
            $data = [
                'tanggal' => $event->meetingShift->tanggal,
                'current_shift' => $event->meetingShift->current_shift,
                'created_by' => $event->meetingShift->created_by,
                'updated_at' => now()
            ];

            DB::beginTransaction();
            
            try {
                $upKendariDB = DB::connection('mysql');
                
                switch($event->action) {
                    case 'create':
                        $data['created_at'] = now();
                        
                        // Create main meeting shift record
                        $meetingShiftId = $upKendariDB->table('meeting_shifts')->insertGetId($data);
                        
                        // Sync machine statuses
                        foreach ($event->meetingShift->machineStatuses as $status) {
                            $upKendariDB->table('machine_statuses')->insert([
                                'meeting_shift_id' => $meetingShiftId,
                                'machine_id' => $status->machine_id,
                                'status' => $status->status,
                                'keterangan' => $status->keterangan,
                                'created_at' => now(),
                                'updated_at' => now()
                            ]);
                        }

                        // Sync auxiliary equipment
                        foreach ($event->meetingShift->auxiliaryEquipments as $equipment) {
                            $upKendariDB->table('auxiliary_equipment_statuses')->insert([
                                'meeting_shift_id' => $meetingShiftId,
                                'name' => $equipment->name,
                                'status' => $equipment->status,
                                'keterangan' => $equipment->keterangan,
                                'created_at' => now(),
                                'updated_at' => now()
                            ]);
                        }

                        // Sync resources
                        foreach ($event->meetingShift->resources as $resource) {
                            $upKendariDB->table('resource_statuses')->insert([
                                'meeting_shift_id' => $meetingShiftId,
                                'name' => $resource->name,
                                'category' => $resource->category,
                                'status' => $resource->status,
                                'keterangan' => $resource->keterangan,
                                'created_at' => now(),
                                'updated_at' => now()
                            ]);
                        }

                        // Sync K3L
                        foreach ($event->meetingShift->k3ls as $k3l) {
                            $upKendariDB->table('meeting_shift_k3l')->insert([
                                'meeting_shift_id' => $meetingShiftId,
                                'type' => $k3l->type,
                                'uraian' => $k3l->uraian,
                                'saran' => $k3l->saran,
                                'eviden_path' => $k3l->eviden_path,
                                'created_at' => now(),
                                'updated_at' => now()
                            ]);
                        }

                        // Sync notes
                        foreach ($event->meetingShift->notes as $note) {
                            $upKendariDB->table('meeting_shift_notes')->insert([
                                'meeting_shift_id' => $meetingShiftId,
                                'type' => $note->type,
                                'content' => $note->content,
                                'created_at' => now(),
                                'updated_at' => now()
                            ]);
                        }

                        // Sync resume
                        if ($event->meetingShift->resume) {
                            $upKendariDB->table('meeting_shift_resume')->insert([
                                'meeting_shift_id' => $meetingShiftId,
                                'content' => $event->meetingShift->resume->content,
                                'created_at' => now(),
                                'updated_at' => now()
                            ]);
                        }

                        // Sync attendances
                        foreach ($event->meetingShift->attendances as $attendance) {
                            $upKendariDB->table('meeting_shift_attendance')->insert([
                                'meeting_shift_id' => $meetingShiftId,
                                'nama' => $attendance->nama,
                                'shift' => $attendance->shift,
                                'status' => $attendance->status,
                                'keterangan' => $attendance->keterangan,
                                'created_at' => now(),
                                'updated_at' => now()
                            ]);
                        }
                        break;
                        
                    case 'update':
                        // Update main meeting shift record
                        $upKendariDB->table('meeting_shifts')
                            ->where('id', $event->meetingShift->id)
                            ->update($data);
                            
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
                                'status' => $status->status,
                                'keterangan' => $status->keterangan,
                                'created_at' => now(),
                                'updated_at' => now()
                            ]);
                        }

                        foreach ($event->meetingShift->auxiliaryEquipments as $equipment) {
                            $upKendariDB->table('auxiliary_equipment_statuses')->insert([
                                'meeting_shift_id' => $event->meetingShift->id,
                                'name' => $equipment->name,
                                'status' => $equipment->status,
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
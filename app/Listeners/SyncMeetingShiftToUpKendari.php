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

            DB::beginTransaction();
            try {
                switch($event->action) {
                    case 'create':
                        // Try to get the latest ID from mysql database
                        $latestId = $upKendariDB->table('meeting_shifts')->max('id');
                        $newId = $latestId ? $latestId + 1 : 1;

                        // Create main meeting shift record with new ID
                        $data = [
                            'id' => $newId,
                            'tanggal' => $event->meetingShift->tanggal,
                            'current_shift' => $event->meetingShift->current_shift,
                            'created_by' => $event->meetingShift->created_by,
                            'created_at' => now(),
                            'updated_at' => now()
                        ];

                        // Insert with new ID
                        $upKendariDB->table('meeting_shifts')->insert($data);

                        Log::info('Created meeting shift record with new ID', [
                            'original_id' => $event->meetingShift->id,
                            'new_id' => $newId
                        ]);

                        // Sync machine statuses with new parent ID
                        foreach ($event->meetingShift->machineStatuses as $status) {
                            $upKendariDB->table('machine_statuses')->insert([
                                'meeting_shift_id' => $newId,
                                'machine_id' => $status->machine_id,
                                'status' => is_string($status->status) ? $status->status : json_encode($status->status),
                                'keterangan' => $status->keterangan,
                                'created_at' => now(),
                                'updated_at' => now()
                            ]);
                        }

                        // Sync auxiliary equipment with new parent ID
                        foreach ($event->meetingShift->auxiliaryEquipments as $equipment) {
                            $upKendariDB->table('auxiliary_equipment_statuses')->insert([
                                'meeting_shift_id' => $newId,
                                'name' => $equipment->name,
                                'status' => is_string($equipment->status) ? $equipment->status : json_encode($equipment->status),
                                'keterangan' => $equipment->keterangan,
                                'created_at' => now(),
                                'updated_at' => now()
                            ]);
                        }

                        // Sync resources with new parent ID
                        foreach ($event->meetingShift->resources as $resource) {
                            $upKendariDB->table('resource_statuses')->insert([
                                'meeting_shift_id' => $newId,
                                'name' => $resource->name,
                                'category' => $resource->category,
                                'status' => $resource->status,
                                'keterangan' => $resource->keterangan ?? '',
                                'created_at' => now(),
                                'updated_at' => now()
                            ]);
                        }

                        // Sync K3L with new parent ID
                        foreach ($event->meetingShift->k3ls as $k3l) {
                            $upKendariDB->table('meeting_shift_k3l')->insert([
                                'meeting_shift_id' => $newId,
                                'type' => $k3l->type,
                                'uraian' => $k3l->uraian,
                                'saran' => $k3l->saran,
                                'eviden_path' => $k3l->eviden_path ?? null,
                                'created_at' => now(),
                                'updated_at' => now()
                            ]);
                        }

                        // Sync notes with new parent ID
                        foreach ($event->meetingShift->notes as $note) {
                            $upKendariDB->table('meeting_shift_notes')->insert([
                                'meeting_shift_id' => $newId,
                                'type' => $note->type,
                                'content' => $note->content,
                                'created_at' => now(),
                                'updated_at' => now()
                            ]);
                        }

                        // Sync resume with new parent ID
                        if ($event->meetingShift->resume) {
                            $upKendariDB->table('meeting_shift_resume')->insert([
                                'meeting_shift_id' => $newId,
                                'content' => $event->meetingShift->resume->content,
                                'created_at' => now(),
                                'updated_at' => now()
                            ]);
                        }

                        // Sync attendances with new parent ID
                        foreach ($event->meetingShift->attendances as $attendance) {
                            $upKendariDB->table('meeting_shift_attendance')->insert([
                                'meeting_shift_id' => $newId,
                                'nama' => $attendance->nama,
                                'shift' => $attendance->shift,
                                'status' => $attendance->status,
                                'keterangan' => $attendance->keterangan ?? '',
                                'created_at' => now(),
                                'updated_at' => now()
                            ]);
                        }

                        Log::info('Successfully synced all related records with new parent ID', [
                            'original_id' => $event->meetingShift->id,
                            'new_id' => $newId,
                            'machine_statuses' => $event->meetingShift->machineStatuses->count(),
                            'auxiliary_equipment' => $event->meetingShift->auxiliaryEquipments->count(),
                            'resources' => $event->meetingShift->resources->count(),
                            'k3ls' => $event->meetingShift->k3ls->count(),
                            'notes' => $event->meetingShift->notes->count(),
                            'has_resume' => $event->meetingShift->resume ? 'yes' : 'no',
                            'attendances' => $event->meetingShift->attendances->count()
                        ]);
                        break;

                    case 'update':
                        // Check if record exists
                        $exists = $upKendariDB->table('meeting_shifts')
                            ->where('id', $event->meetingShift->id)
                            ->exists();

                        if (!$exists) {
                            // If record doesn't exist, treat it as a create with new ID
                            $latestId = $upKendariDB->table('meeting_shifts')->max('id');
                            $newId = $latestId ? $latestId + 1 : 1;

                            // Create main record with new ID
                            $upKendariDB->table('meeting_shifts')->insert([
                                'id' => $newId,
                                'tanggal' => $event->meetingShift->tanggal,
                                'current_shift' => $event->meetingShift->current_shift,
                                'created_by' => $event->meetingShift->created_by,
                                'created_at' => now(),
                                'updated_at' => now()
                            ]);

                            Log::info('Created new record during update (record not found)', [
                                'original_id' => $event->meetingShift->id,
                                'new_id' => $newId
                            ]);

                            // Use new ID for all child records
                            $parentId = $newId;
                        } else {
                            // Update existing record
                            $upKendariDB->table('meeting_shifts')
                                ->where('id', $event->meetingShift->id)
                                ->update([
                                    'tanggal' => $event->meetingShift->tanggal,
                                    'current_shift' => $event->meetingShift->current_shift,
                                    'updated_at' => now()
                                ]);

                            $parentId = $event->meetingShift->id;
                        }

                        // Delete existing child records
                        $upKendariDB->table('machine_statuses')->where('meeting_shift_id', $parentId)->delete();
                        $upKendariDB->table('auxiliary_equipment_statuses')->where('meeting_shift_id', $parentId)->delete();
                        $upKendariDB->table('resource_statuses')->where('meeting_shift_id', $parentId)->delete();
                        $upKendariDB->table('meeting_shift_k3l')->where('meeting_shift_id', $parentId)->delete();
                        $upKendariDB->table('meeting_shift_notes')->where('meeting_shift_id', $parentId)->delete();
                        $upKendariDB->table('meeting_shift_resume')->where('meeting_shift_id', $parentId)->delete();
                        $upKendariDB->table('meeting_shift_attendance')->where('meeting_shift_id', $parentId)->delete();

                        // Reinsert all related records with correct parent ID
                        foreach ($event->meetingShift->machineStatuses as $status) {
                            $upKendariDB->table('machine_statuses')->insert([
                                'meeting_shift_id' => $parentId,
                                'machine_id' => $status->machine_id,
                                'status' => is_string($status->status) ? $status->status : json_encode($status->status),
                                'keterangan' => $status->keterangan,
                                'created_at' => now(),
                                'updated_at' => now()
                            ]);
                        }

                        foreach ($event->meetingShift->auxiliaryEquipments as $equipment) {
                            $upKendariDB->table('auxiliary_equipment_statuses')->insert([
                                'meeting_shift_id' => $parentId,
                                'name' => $equipment->name,
                                'status' => is_string($equipment->status) ? $equipment->status : json_encode($equipment->status),
                                'keterangan' => $equipment->keterangan,
                                'created_at' => now(),
                                'updated_at' => now()
                            ]);
                        }

                        foreach ($event->meetingShift->resources as $resource) {
                            $upKendariDB->table('resource_statuses')->insert([
                                'meeting_shift_id' => $parentId,
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
                                'meeting_shift_id' => $parentId,
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
                                'meeting_shift_id' => $parentId,
                                'type' => $note->type,
                                'content' => $note->content,
                                'created_at' => now(),
                                'updated_at' => now()
                            ]);
                        }

                        if ($event->meetingShift->resume) {
                            $upKendariDB->table('meeting_shift_resume')->insert([
                                'meeting_shift_id' => $parentId,
                                'content' => $event->meetingShift->resume->content,
                                'created_at' => now(),
                                'updated_at' => now()
                            ]);
                        }

                        foreach ($event->meetingShift->attendances as $attendance) {
                            $upKendariDB->table('meeting_shift_attendance')->insert([
                                'meeting_shift_id' => $parentId,
                                'nama' => $attendance->nama,
                                'shift' => $attendance->shift,
                                'status' => $attendance->status,
                                'keterangan' => $attendance->keterangan,
                                'created_at' => now(),
                                'updated_at' => now()
                            ]);
                        }

                        Log::info('Successfully synced all records', [
                            'action' => $exists ? 'updated' : 'created',
                            'parent_id' => $parentId,
                            'machine_statuses' => $event->meetingShift->machineStatuses->count(),
                            'auxiliary_equipment' => $event->meetingShift->auxiliaryEquipments->count(),
                            'resources' => $event->meetingShift->resources->count(),
                            'k3ls' => $event->meetingShift->k3ls->count(),
                            'notes' => $event->meetingShift->notes->count(),
                            'has_resume' => $event->meetingShift->resume ? 'yes' : 'no',
                            'attendances' => $event->meetingShift->attendances->count()
                        ]);
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
                Log::error('Sync error details', [
                    'message' => $e->getMessage(),
                    'sql' => $e->getSql ?? null,
                    'bindings' => $e->getBindings ?? null,
                    'meeting_shift_id' => $event->meetingShift->id,
                    'action' => $event->action
                ]);
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
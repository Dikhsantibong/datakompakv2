<?php

namespace App\Listeners;

use App\Events\OperationScheduleUpdated;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\OperationSchedule;

class SyncOperationScheduleToUpKendari
{
    public function handle(OperationScheduleUpdated $event)
    {
        try {
            $currentSession = session('unit', 'mysql');

            Log::info('Processing OperationSchedule sync event', [
                'current_session' => $currentSession,
                'id' => $event->operationSchedule->id,
                'date' => $event->operationSchedule->schedule_date,
                'action' => $event->action
            ]);
            // Debug: log semua session dan user id
            Log::info('DEBUG:: Full session', session()->all());
            Log::info('DEBUG:: Current user id', ['user_id' => auth()->check() ? auth()->id() : null]);

            // Jika unit saat ini UP Kendari (pusat), propagate ke seluruh unit remote
            if ($currentSession === 'mysql') {
                $schedule = $event->operationSchedule;
                $data = [
                    'id' => $schedule->id,
                    'title' => $schedule->title,
                    'description' => $schedule->description,
                    'schedule_date' => $schedule->schedule_date,
                    'start_time' => $schedule->start_time,
                    'end_time' => $schedule->end_time,
                    'location' => $schedule->location,
                    'status' => $schedule->status,
                    'participants' => is_array($schedule->participants) ? json_encode($schedule->participants) : $schedule->participants,
                    'created_by' => $schedule->created_by,
                    'updated_at' => now(),
                ];

                // Daftar semua unit selain mysql (UP Kendari)
                $units = [
                            'mysql_bau_bau',
                            'mysql_kolaka',
                            'mysql_poasia',
                            'mysql_wua_wua',
                            'mysql_ereke',
                            'mysql_ladumpi',
                            'mysql_langara',
                            'mysql_lanipa_nipa',
                            'mysql_pasarwajo',
                            'mysql_poasia_containerized',
                            'mysql_raha',
                            'mysql_wangi_wangi',
                            'mysql_mikuasi',
                            'mysql_rongi',
                            'mysql_sabilambo',
                            'mysql_winning',
                            'mysql_pltmg_bau_bau',
                            'mysql_kendari',
                            'mysql_baruta',
                            'mysql_moramo',
                        ];

                foreach ($units as $unit) {
                    $db = DB::connection($unit);
                    DB::connection($unit)->beginTransaction();
                    try {
                        switch ($event->action) {
                            case 'create':
                                $data['created_at'] = now();
                                $existingRecord = $db->table('operation_schedules')->where('id', $schedule->id)->first();
                                if ($existingRecord) {
                                    $db->table('operation_schedules')->where('id', $schedule->id)->update($data);
                                    Log::info("[SYNC][$unit] Updated existing OperationSchedule", ['id' => $schedule->id]);
                                } else {
                                    $db->table('operation_schedules')->insert($data);
                                    Log::info("[SYNC][$unit] Created new OperationSchedule", ['id' => $schedule->id]);
                                }
                                break;
                            case 'update':
                                $db->table('operation_schedules')->where('id', $schedule->id)->update($data);
                                Log::info("[SYNC][$unit] Updated OperationSchedule", ['id' => $schedule->id]);
                                break;
                            case 'delete':
                                $db->table('operation_schedules')->where('id', $schedule->id)->delete();
                                Log::info("[SYNC][$unit] Deleted OperationSchedule", ['id' => $schedule->id]);
                                break;
                        }
                        DB::connection($unit)->commit();
                    } catch (\Exception $e) {
                        DB::connection($unit)->rollBack();
                        Log::error("OperationSchedule sync to $unit failed", [
                            'message' => $e->getMessage(),
                            'trace' => $e->getTraceAsString(),
                            'id' => $schedule->id,
                            'unit' => $unit
                        ]);
                    }
                }
            }


        } catch (\Exception $e) {
            Log::error("OperationSchedule sync failed", [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'id' => $event->operationSchedule->id ?? null,
                'session' => $currentSession ?? 'unknown'
            ]);
        }
    }
}

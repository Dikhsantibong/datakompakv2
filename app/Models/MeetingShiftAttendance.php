<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MeetingShiftAttendance extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'meeting_shift_attendance';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'meeting_shift_id',
        'nama',
        'shift',
        'status',
        'keterangan'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'shift' => 'string',
        'status' => 'string'
    ];

    /**
     * Get valid shift values
     *
     * @return array
     */
    public static function getValidShifts()
    {
        return ['A', 'B', 'C', 'D'];
    }

    /**
     * Get valid status values
     *
     * @return array
     */
    public static function getValidStatuses()
    {
        return ['hadir', 'izin', 'sakit', 'cuti', 'alpha'];
    }

    /**
     * Get the meeting shift that owns this attendance.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function meetingShift()
    {
        return $this->belongsTo(MeetingShift::class);
    }
} 
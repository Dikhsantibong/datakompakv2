<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MeetingShiftMachineStatus extends Model
{
    use HasFactory;

    protected $table = 'machine_statuses';

    protected $fillable = [
        'meeting_shift_id',
        'machine_id',
        'status',
        'keterangan'
    ];

    protected $casts = [
        'status' => 'json'
    ];

    public function meetingShift()
    {
        return $this->belongsTo(MeetingShift::class);
    }

    public function machine()
    {
        return $this->belongsTo(Machine::class);
    }
} 
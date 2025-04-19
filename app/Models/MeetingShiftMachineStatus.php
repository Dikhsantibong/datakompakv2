<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MeetingShiftMachineStatus extends Model
{
    use HasFactory;

    protected $fillable = [
        'meeting_shift_id',
        'machine_id',
        'status',
        'keterangan'
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
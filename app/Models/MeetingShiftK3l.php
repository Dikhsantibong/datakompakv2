<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MeetingShiftK3l extends Model
{
    use HasFactory;

    protected $fillable = [
        'meeting_shift_id',
        'type',
        'uraian',
        'saran',
        'eviden_path'
    ];

    public function meetingShift()
    {
        return $this->belongsTo(MeetingShift::class);
    }
} 
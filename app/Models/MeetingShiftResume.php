<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MeetingShiftResume extends Model
{
    use HasFactory;

    protected $fillable = [
        'meeting_shift_id',
        'content'
    ];

    public function meetingShift()
    {
        return $this->belongsTo(MeetingShift::class);
    }
} 
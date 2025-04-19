<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MeetingShiftNote extends Model
{
    use HasFactory;

    protected $fillable = [
        'meeting_shift_id',
        'type',
        'content'
    ];

    public function meetingShift()
    {
        return $this->belongsTo(MeetingShift::class);
    }
} 
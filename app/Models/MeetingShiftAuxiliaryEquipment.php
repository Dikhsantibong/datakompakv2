<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MeetingShiftAuxiliaryEquipment extends Model
{
    use HasFactory;

    protected $fillable = [
        'meeting_shift_id',
        'name',
        'status',
        'keterangan'
    ];

    public function meetingShift()
    {
        return $this->belongsTo(MeetingShift::class);
    }
} 
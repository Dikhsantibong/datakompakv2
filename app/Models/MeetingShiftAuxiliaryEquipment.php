<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MeetingShiftAuxiliaryEquipment extends Model
{
    use HasFactory;

    protected $table = 'auxiliary_equipment_statuses';

    protected $fillable = [
        'meeting_shift_id',
        'name',
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

    public function getConnectionName()
    {
        return session('unit', 'mysql');
    }
} 
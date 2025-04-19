<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MeetingShift extends Model
{
    use HasFactory;

    protected $fillable = [
        'date',
        'current_shift',
        'created_by'
    ];

    protected $casts = [
        'date' => 'date'
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function machineStatuses()
    {
        return $this->hasMany(MeetingShiftMachineStatus::class);
    }

    public function auxiliaryEquipment()
    {
        return $this->hasMany(MeetingShiftAuxiliaryEquipment::class);
    }

    public function resources()
    {
        return $this->hasMany(MeetingShiftResource::class);
    }

    public function k3l()
    {
        return $this->hasMany(MeetingShiftK3l::class);
    }

    public function systemNote()
    {
        return $this->hasOne(MeetingShiftNote::class)->where('type', 'sistem');
    }

    public function generalNote()
    {
        return $this->hasOne(MeetingShiftNote::class)->where('type', 'umum');
    }

    public function resume()
    {
        return $this->hasOne(MeetingShiftResume::class);
    }

    public function attendance()
    {
        return $this->hasMany(MeetingShiftAttendance::class);
    }
}
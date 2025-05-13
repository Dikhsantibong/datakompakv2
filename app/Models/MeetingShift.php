<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MeetingShift extends Model
{
    use HasFactory;

    protected $fillable = [
        'tanggal',
        'current_shift',
        'created_by'
    ];

    protected $casts = [
        'tanggal' => 'date'
    ];

    protected $primaryKey = 'id';

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function machineStatuses()
    {
        return $this->hasMany(MeetingShiftMachineStatus::class);
    }

    public function auxiliaryEquipments()
    {
        return $this->hasMany(MeetingShiftAuxiliaryEquipment::class);
    }

    public function resources()
    {
        return $this->hasMany(MeetingShiftResource::class);
    }

    public function k3ls()
    {
        return $this->hasMany(MeetingShiftK3l::class);
    }

    public function notes()
    {
        return $this->hasMany(MeetingShiftNote::class);
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

    public function attendances()
    {
        return $this->hasMany(MeetingShiftAttendance::class);
    }
    public function getConnectionName()
    
    {
        return session('unit', 'mysql');
    }
}
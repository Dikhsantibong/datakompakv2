<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    use HasFactory;

    protected $table = 'operation_schedules';

    protected $fillable = [
        'title',
        'description',
        'schedule_date',
        'start_time',
        'end_time',
        'location',
        'status',
        'participants',
        'created_by'
    ];

    protected $casts = [
        'participants' => 'array',
        'schedule_date' => 'date',
        'start_time' => 'datetime',
        'end_time' => 'datetime'
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function getConnectionName()
    {
        return session('unit', 'mysql');
    }
} 
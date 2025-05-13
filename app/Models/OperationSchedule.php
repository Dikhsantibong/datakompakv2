<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OperationSchedule extends Model
{
    use HasFactory;

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
        'schedule_date' => 'date',
        'start_time' => 'datetime',
        'end_time' => 'datetime',
        'participants' => 'array'
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
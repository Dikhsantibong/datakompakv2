<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Meeting extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'department_id',
        'scheduled_at',
        'status',
        'created_by'
    ];

    protected $casts = [
        'scheduled_at' => 'datetime'
    ];

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function participants()
    {
        return $this->belongsToMany(User::class, 'meeting_participants')
                    ->withTimestamps()
                    ->withPivot('status', 'notes');
    }
    public function getConnectionName()
    {
        return session('unit', 'mysql');
    }
}
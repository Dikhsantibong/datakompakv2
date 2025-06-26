<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Meeting extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'pekerjaan',
        'pic',
        'deadline_start',
        'deadline_finish',
        'kondisi',
        'status',
    ];

    protected $casts = [
        'deadline_start' => 'date',
        'deadline_finish' => 'date',
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

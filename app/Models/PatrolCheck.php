<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PatrolCheck extends Model
{
    use HasFactory;

    protected $table = 'patrol_checks';

    protected $fillable = [
        'created_by',
        'condition_systems',
        'abnormal_equipments',
        'condition_after',
        'notes',
        'status',
    ];

    protected $casts = [
        'condition_systems' => 'array',
        'abnormal_equipments' => 'array',
        'condition_after' => 'array',
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
} 
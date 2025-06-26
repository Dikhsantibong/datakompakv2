<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LinkKoordinasi extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'uraian',
        'link',
        'monitoring',
        'koordinasi'
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime'
    ];
}

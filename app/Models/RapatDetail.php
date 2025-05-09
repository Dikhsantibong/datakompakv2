<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RapatDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'item_id',
        'jadwal',
        'mode',
        'resume',
        'notulen_path',
        'eviden_path'
    ];

    protected $casts = [
        'jadwal' => 'datetime'
    ];

    public function item()
    {
        return $this->belongsTo(Item::class);
    }
} 
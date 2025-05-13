<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class K3KampItem extends Model
{
    protected $fillable = [
        'report_id',
        'item_type',
        'item_name',
        'status',
        'kondisi',
        'keterangan'
    ];

    public function report(): BelongsTo
    {
        return $this->belongsTo(K3KampReport::class, 'report_id');
    }

    public function media(): HasMany
    {
        return $this->hasMany(K3KampMedia::class, 'item_id');
    }
    public function getConnectionName()
    {
        return session('unit', 'mysql');
    }
} 
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class K3KampReport extends Model
{
    protected $fillable = [
        'date',
        'created_by'
    ];

    protected $casts = [
        'date' => 'date'
    ];

    public function items(): HasMany
    {
        return $this->hasMany(K3KampItem::class, 'report_id');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
} 
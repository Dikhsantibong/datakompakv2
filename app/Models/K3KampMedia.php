<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class K3KampMedia extends Model
{
    protected $fillable = [
        'item_id',
        'media_type',
        'file_path',
        'original_name',
        'file_size'
    ];

    public function item(): BelongsTo
    {
        return $this->belongsTo(K3KampItem::class, 'item_id');
    }
} 
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AbnormalChronology extends Model
{
    protected $guarded = ['id'];

    protected $casts = [
        'waktu' => 'datetime',
        'turun_beban' => 'boolean',
        'off_cbg' => 'boolean',
        'stop' => 'boolean',
        'tl_ophar' => 'boolean',
        'tl_op' => 'boolean',
        'tl_har' => 'boolean',
        'mul' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function abnormalReport(): BelongsTo
    {
        return $this->belongsTo(AbnormalReport::class);
    }
} 
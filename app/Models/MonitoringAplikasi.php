<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MonitoringAplikasi extends Model
{
    use HasFactory;

    protected $fillable = [
        'item_id',
        'aplikasi',
        'subkolom_harian',
        'subkolom_bulanan',
        'pic_operasi'
    ];

    public function item()
    {
        return $this->belongsTo(Item::class);
    }
    public function getConnectionName()
    {
        return session('unit', 'mysql');
    }
} 
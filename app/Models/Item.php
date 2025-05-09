<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasFactory;

    protected $fillable = [
        'section_id',
        'subsection_id',
        'order_number',
        'uraian',
        'detail',
        'pic',
        'kondisi_eksisting',
        'tindak_lanjut',
        'kondisi_akhir',
        'goal',
        'status',
        'keterangan'
    ];

    public function section()
    {
        return $this->belongsTo(Section::class);
    }

    public function subsection()
    {
        return $this->belongsTo(Subsection::class);
    }

    public function rapatDetail()
    {
        return $this->hasOne(RapatDetail::class);
    }

    public function monitoringAplikasi()
    {
        return $this->hasOne(MonitoringAplikasi::class);
    }
} 
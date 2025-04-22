<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pemeriksaan5s5r extends Model
{
    protected $table = 'tabel_pemeriksaan_5s5r';
    
    protected $fillable = [
        'kategori',
        'detail',
        'kondisi_awal',
        'pic',
        'area_kerja',
        'area_produksi',
        'membersihkan',
        'merapikan',
        'membuang_sampah',
        'mengecat',
        'lainnya',
        'kondisi_akhir',
        'eviden'
    ];

    protected $casts = [
        'membersihkan' => 'boolean',
        'merapikan' => 'boolean',
        'membuang_sampah' => 'boolean',
        'mengecat' => 'boolean',
        'lainnya' => 'boolean',
    ];
} 
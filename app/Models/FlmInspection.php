<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FlmInspection extends Model
{
    use HasFactory;

    protected $fillable = [
        'flm_id',
        'tanggal',
        'operator',
        'mesin',
        'sistem',
        'masalah',
        'kondisi_awal',
        'tindakan_bersihkan',
        'tindakan_lumasi',
        'tindakan_kencangkan',
        'tindakan_perbaikan_koneksi',
        'tindakan_lainnya',
        'kondisi_akhir',
        'catatan',
        'eviden_sebelum',
        'eviden_sesudah',
        'status'
    ];

    protected $casts = [
        'tanggal' => 'date',
        'tindakan_bersihkan' => 'boolean',
        'tindakan_lumasi' => 'boolean',
        'tindakan_kencangkan' => 'boolean',
        'tindakan_perbaikan_koneksi' => 'boolean',
        'tindakan_lainnya' => 'boolean',
    ];

    public function getConnectionName()
    {
        return session('unit', 'mysql');
    }
} 
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PengadaanBarang extends Model
{
    protected $table = 'pengadaan_barang';
    
    protected $fillable = [
        'judul',
        'tahun',
        'nilai_kontrak',
        'no_prk',
        'jenis',
        'intensitas',
        'pengusulan',
        'proses_kontrak',
        'pengadaan',
        'pekerjaan_fisik',
        'pemberkasan',
        'pembayaran',
        'keterangan'
    ];

    protected $casts = [
        'tahun' => 'integer',
        'nilai_kontrak' => 'decimal:2'
    ];

    public function getConnectionName()
    {
        return session('unit', 'mysql');
    }
} 
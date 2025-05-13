<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LaporanKitBahanKimia extends Model
{
    protected $table = 'laporan_kit_bahan_kimia';

    protected $fillable = [
        'laporan_kit_id',
        'jenis',
        'stok_awal',
        'terima', 
        'total_pakai'
    ];
    
    public function getConnectionName()
    {
        return session('unit', 'mysql');
    }
}
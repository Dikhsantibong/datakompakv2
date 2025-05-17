<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\LaporanKitSyncable;

class LaporanKitBahanKimia extends Model
{
    use LaporanKitSyncable;

    protected $table = 'laporan_kit_bahan_kimia';

    protected $fillable = [
        'laporan_kit_id',
        'jenis',
        'stok_awal',
        'terima', 
        'total_pakai'
    ];
    
    public function laporanKit()
    {
        return $this->belongsTo(LaporanKit::class);
    }

    public function getConnectionName()
    {
        return session('unit', 'mysql');
    }
}
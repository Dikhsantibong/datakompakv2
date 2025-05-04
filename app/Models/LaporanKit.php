<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LaporanKit extends Model
{
    protected $table = 'laporan_kits';

    protected $fillable = [
        'tanggal',
        'unit_source',
        'created_by',
        // tambahkan field lain jika ada
    ];

    public function jamOperasi()    { return $this->hasMany(LaporanKitJamOperasi::class); }
    public function gangguan()      { return $this->hasMany(LaporanKitGangguan::class); }
    public function bbm()           { return $this->hasMany(LaporanKitBbm::class); }
    public function kwh()           { return $this->hasMany(LaporanKitKwh::class); }
    public function pelumas()       { return $this->hasMany(LaporanKitPelumas::class); }
    public function bahanKimia()    { return $this->hasMany(LaporanKitBahanKimia::class); }
    public function bebanTertinggi(){ return $this->hasMany(LaporanKitBebanTertinggi::class); }
    public function creator()       { return $this->belongsTo(User::class, 'created_by'); }
    public function powerPlant()
    {
        return $this->belongsTo(\App\Models\PowerPlant::class, 'unit_source', 'unit_source');
    }
}

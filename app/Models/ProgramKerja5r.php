<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProgramKerja5r extends Model
{
    protected $table = 'tabel_program_kerja_5r';
    
    protected $fillable = [
        'program_kerja',
        'goal',
        'kondisi_awal',
        'progress',
        'kondisi_akhir',
        'catatan',
        'eviden'
    ];

    public function getConnectionName()
    {
        return session('unit', 'mysql');
    }
} 
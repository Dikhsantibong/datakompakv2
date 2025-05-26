<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FiveS5rBatch extends Model
{
    protected $table = '    ';
    protected $fillable = [
        'created_by',
        'sync_unit_origin',
    ];

    public function pemeriksaan()
    {
        return $this->hasMany(Pemeriksaan5s5r::class, 'batch_id');
    }

    public function programKerja()
    {
        return $this->hasMany(ProgramKerja5r::class, 'batch_id');
    }
} 
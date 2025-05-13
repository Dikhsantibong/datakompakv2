<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AbnormalEvidence extends Model
{
    protected $table = 'abnormal_evidences';
    protected $fillable = [
        'abnormal_report_id',
        'file_path',
        'description'
    ];

    public function abnormalReport()
    {
        return $this->belongsTo(AbnormalReport::class);
    }
    public function getConnectionName()
    {
        return session('unit', 'mysql');
    }
} 
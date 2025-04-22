<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MeetingShiftK3l extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'meeting_shift_k3l';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'meeting_shift_id',
        'type',
        'uraian',
        'saran',
        'eviden_path'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'type' => 'string'
    ];

    /**
     * Get the valid types
     *
     * @return array
     */
    public static function getValidTypes()
    {
        return ['unsafe_action', 'unsafe_condition'];
    }

    /**
     * Get the meeting shift that owns this K3L report.
     */
    public function meetingShift()
    {
        return $this->belongsTo(MeetingShift::class);
    }
} 
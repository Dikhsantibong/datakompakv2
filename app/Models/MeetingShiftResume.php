<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MeetingShiftResume extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'meeting_shift_resume';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'meeting_shift_id',
        'content'
    ];

    /**
     * Get the meeting shift that owns this resume.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function meetingShift()
    {
        return $this->belongsTo(MeetingShift::class);
    }

    public function getConnectionName()
    
    {
        return session('unit', 'mysql');
    }
} 
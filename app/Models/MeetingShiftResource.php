<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MeetingShiftResource extends Model
{
    use HasFactory;

    protected $table = 'resource_statuses';

    protected $fillable = [
        'meeting_shift_id',
        'name',
        'category',
        'status',
        'keterangan'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [];

    /**
     * Get the valid status values
     *
     * @return array
     */
    public static function getValidStatuses()
    {
        return ['0-20', '21-40', '41-61', '61-80', 'up-80'];
    }

    /**
     * Get the valid categories
     *
     * @return array
     */
    public static function getValidCategories()
    {
        return ['PELUMAS', 'BBM', 'AIR PENDINGIN', 'UDARA START'];
    }

    public function meetingShift()
    {
        return $this->belongsTo(MeetingShift::class);
    }

    public function getConnectionName()
    
    {
        return session('unit', 'mysql');
    }
} 
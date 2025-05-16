<?php

namespace App\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use App\Models\MeetingShift;

class MeetingShiftUpdated
{
    use Dispatchable, SerializesModels;

    public $meetingShift;
    public $sourceUnit;
    public $action;

    public function __construct(MeetingShift $meetingShift, string $action)
    {
        $this->meetingShift = $meetingShift;
        $this->sourceUnit = session('unit', 'mysql');
        $this->action = $action;
    }
} 
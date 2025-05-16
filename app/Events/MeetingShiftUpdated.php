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
        // Eager load all relationships before storing in the event
        $this->meetingShift = $meetingShift->load([
            'machineStatuses.machine.powerPlant',
            'auxiliaryEquipments',
            'resources',
            'k3ls',
            'notes',
            'resume',
            'attendances'
        ]);
        $this->sourceUnit = session('unit', 'mysql');
        $this->action = $action;
    }
} 
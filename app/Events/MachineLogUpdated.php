<?php

namespace App\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use App\Models\MachineLog;

class MachineLogUpdated
{
    use Dispatchable, SerializesModels;

    public $machineLog;
    public $sourceUnit;
    public $action;

    public function __construct(MachineLog $machineLog, string $action)
    {
        $this->machineLog = $machineLog;
        $this->sourceUnit = session('unit', 'mysql');
        $this->action = $action;
    }
} 
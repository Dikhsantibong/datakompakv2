<?php

namespace App\Events;

use Illuminate\Queue\SerializesModels;
use App\Models\OperationSchedule;

class OperationScheduleUpdated
{
    use SerializesModels;

    public $operationSchedule;
    public $action;

    public function __construct(OperationSchedule $operationSchedule, $action)
    {
        $this->operationSchedule = $operationSchedule;
        $this->action = $action; // 'create', 'update', or 'delete'
    }
}

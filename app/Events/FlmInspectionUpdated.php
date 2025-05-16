<?php

namespace App\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use App\Models\FlmInspection;

class FlmInspectionUpdated
{
    use Dispatchable, SerializesModels;

    public $flmInspection;
    public $sourceUnit;
    public $action;

    public function __construct(FlmInspection $flmInspection, string $action)
    {
        $this->flmInspection = $flmInspection;
        $this->sourceUnit = session('unit', 'mysql');
        $this->action = $action;
    }
} 
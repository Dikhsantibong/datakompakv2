<?php

namespace App\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use App\Models\PatrolCheck;

class PatrolCheckUpdated
{
    use Dispatchable, SerializesModels;

    public $patrolCheck;
    public $sourceUnit;
    public $action;

    public function __construct(PatrolCheck $patrolCheck, string $action)
    {
        $this->patrolCheck = $patrolCheck;
        $this->sourceUnit = session('unit', 'mysql');
        $this->action = $action;
    }
} 
<?php

namespace App\Events;

use App\Models\Pelumas;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class PelumasUpdated
{
    use Dispatchable, SerializesModels;

    public $pelumas;
    public $action;

    public function __construct(Pelumas $pelumas, $action)
    {
        $this->pelumas = $pelumas;
        $this->action = $action;
    }
} 
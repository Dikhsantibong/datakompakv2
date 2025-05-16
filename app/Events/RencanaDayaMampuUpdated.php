<?php

namespace App\Events;

use App\Models\RencanaDayaMampu;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class RencanaDayaMampuUpdated
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $rencanaDayaMampu;
    public $action;

    public function __construct(RencanaDayaMampu $rencanaDayaMampu, string $action)
    {
        $this->rencanaDayaMampu = $rencanaDayaMampu;
        $this->action = $action;
    }
} 
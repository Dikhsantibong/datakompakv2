<?php

namespace App\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use App\Models\RencanaDayaMampu;

class RencanaDayaMampuUpdated
{
    use Dispatchable, SerializesModels;

    public $rencanaDayaMampu;
    public $sourceUnit;
    public $action;

    public function __construct(RencanaDayaMampu $rencanaDayaMampu, string $action)
    {
        $this->rencanaDayaMampu = $rencanaDayaMampu;
        $this->sourceUnit = session('unit', 'mysql');
        $this->action = $action;
    }
} 
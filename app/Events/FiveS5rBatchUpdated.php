<?php

namespace App\Events;

use App\Models\FiveS5rBatch;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class FiveS5rBatchUpdated
{
    use Dispatchable, SerializesModels;

    public $fiveS5rBatch;
    public $action;
    public $sourceUnit;

    /**
     * Create a new event instance.
     *
     * @param FiveS5rBatch $fiveS5rBatch
     * @param string $action
     * @param string|null $sourceUnit
     */
    public function __construct(FiveS5rBatch $fiveS5rBatch, string $action, ?string $sourceUnit = null)
    {
        $this->fiveS5rBatch = $fiveS5rBatch;
        $this->action = $action;
        $this->sourceUnit = $sourceUnit;
    }
} 
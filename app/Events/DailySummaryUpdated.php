<?php

namespace App\Events;

use App\Models\DailySummary;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class DailySummaryUpdated
{
    use Dispatchable, SerializesModels;

    public $dailySummary;
    public $action;
    public $sourceUnit;

    public function __construct(DailySummary $dailySummary, string $action)
    {
        $this->dailySummary = $dailySummary;
        $this->action = $action; // 'create', 'update', atau 'delete'
        $this->sourceUnit = session('unit', 'mysql');
    }
} 
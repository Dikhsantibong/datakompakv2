<?php

namespace App\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use App\Models\K3KampReport;

class K3KampReportUpdated
{
    use Dispatchable, SerializesModels;

    public $k3KampReport;
    public $sourceUnit;
    public $action;

    public function __construct(K3KampReport $k3KampReport, string $action)
    {
        $this->k3KampReport = $k3KampReport;
        $this->sourceUnit = session('unit', 'mysql');
        $this->action = $action;
    }
} 
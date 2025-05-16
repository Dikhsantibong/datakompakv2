<?php

namespace App\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use App\Models\AbnormalReport;

class AbnormalReportUpdated
{
    use Dispatchable, SerializesModels;

    public $abnormalReport;
    public $sourceUnit;
    public $action;

    public function __construct(AbnormalReport $abnormalReport, string $action)
    {
        // Eager load all relationships before storing in the event
        $this->abnormalReport = $abnormalReport->load([
            'chronologies',
            'affectedMachines',
            'followUpActions',
            'recommendations',
            'admActions',
            'evidences'
        ]);
        $this->sourceUnit = session('unit', 'mysql');
        $this->action = $action;
    }
} 
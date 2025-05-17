<?php

namespace App\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class LaporanKitUpdated
{
    use Dispatchable, SerializesModels;

    public $model;
    public $action;
    public $modelType;

    public function __construct($model, $action, $modelType)
    {
        $this->model = $model;
        $this->action = $action; // 'create', 'update', or 'delete'
        $this->modelType = $modelType; // The type of model being synced
    }
} 
<?php

namespace App\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use App\Models\Pemeriksaan5s5r;

class Pemeriksaan5s5rUpdated
{
    use Dispatchable, SerializesModels; 

    public $pemeriksaan;
    public $sourceUnit;
    public $action;

    public function __construct(Pemeriksaan5s5r $pemeriksaan, string $action)
    {
        $this->pemeriksaan = $pemeriksaan;
        $this->sourceUnit = session('unit', 'mysql');
        $this->action = $action;
    }
} 
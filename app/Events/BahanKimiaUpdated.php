<?php

namespace App\Events;

use App\Models\BahanKimia;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class BahanKimiaUpdated
{
    use Dispatchable, SerializesModels;

    public $bahanKimia;
    public $action;

    public function __construct(BahanKimia $bahanKimia, $action)
    {
        $this->bahanKimia = $bahanKimia;
        $this->action = $action;
    }
} 
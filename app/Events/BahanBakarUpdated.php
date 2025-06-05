<?php

namespace App\Events;

use App\Models\BahanBakar;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class BahanBakarUpdated
{
    use Dispatchable, SerializesModels;

    public $bahanBakar;
    public $action;

    public function __construct(BahanBakar $bahanBakar, $action)
    {
        $this->bahanBakar = $bahanBakar;
        $this->action = $action;
    }
} 
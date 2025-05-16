<?php

namespace App\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use App\Models\ProgramKerja5r;

class ProgramKerja5rUpdated
{
    use Dispatchable, SerializesModels;

    public $programKerja;
    public $sourceUnit;
    public $action;

    public function __construct(ProgramKerja5r $programKerja, string $action)
    {
        $this->programKerja = $programKerja;
        $this->sourceUnit = session('unit', 'mysql');
        $this->action = $action;
    }
} 
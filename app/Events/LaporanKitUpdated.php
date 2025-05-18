<?php

namespace App\Events;

use App\Models\LaporanKit;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class LaporanKitUpdated
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $laporanKit;
    public $action;

    /**
     * Create a new event instance.
     */
    public function __construct(LaporanKit $laporanKit, string $action)
    {
        // Ensure all relationships are loaded
        $laporanKit->load([
            'jamOperasi',
            'gangguan',
            'bbm',
            'bbm.storageTanks',
            'bbm.serviceTanks',
            'bbm.flowmeters',
            'kwh',
            'kwh.productionPanels',
            'kwh.psPanels',
            'pelumas',
            'pelumas.storageTanks',
            'pelumas.drums',
            'bahanKimia',
            'bebanTertinggi'
        ]);
        
        $this->laporanKit = $laporanKit;
        $this->action = $action;
    }
} 
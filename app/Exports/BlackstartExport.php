<?php

namespace App\Exports;

use App\Models\Blackstart;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Illuminate\Http\Request;

class BlackstartExport implements FromCollection, WithHeadings
{
    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function collection()
    {
        $query = Blackstart::with(['powerPlant', 'peralatanBlackstarts']);

        if ($this->request->filled('unit_id')) {
            $query->where('unit_id', $this->request->unit_id);
        }

        if ($this->request->filled('status')) {
            $query->where('status', $this->request->status);
        }

        if ($this->request->filled('start_date')) {
            $query->whereDate('tanggal', '>=', $this->request->start_date);
        }

        if ($this->request->filled('end_date')) {
            $query->whereDate('tanggal', '<=', $this->request->end_date);
        }

        if ($this->request->filled('pembangkit_status')) {
            $query->where('pembangkit_status', $this->request->pembangkit_status);
        }

        if ($this->request->filled('black_start_status')) {
            $query->where('black_start_status', $this->request->black_start_status);
        }

        if ($this->request->filled('pic')) {
            $query->where('pic', 'like', '%' . $this->request->pic . '%');
        }

        return $query->get()->map(function ($blackstart) {
            return [
                'Tanggal' => $blackstart->tanggal,
                'Unit' => $blackstart->powerPlant->name,
                'Status Pembangkit' => ucfirst($blackstart->pembangkit_status),
                'Status Black Start' => ucfirst($blackstart->black_start_status),
                'Status SOP' => ucfirst($blackstart->sop_status),
                'Status Load Set' => ucfirst($blackstart->load_set_status),
                'Status Line Energize' => ucfirst($blackstart->line_energize_status),
                'Status Jaringan' => ucfirst($blackstart->status_jaringan),
                'PIC' => $blackstart->pic,
                'Status' => strtoupper($blackstart->status)
            ];
        });
    }

    public function headings(): array
    {
        return [
            'Tanggal',
            'Unit',
            'Status Pembangkit',
            'Status Black Start',
            'Status SOP',
            'Status Load Set',
            'Status Line Energize',
            'Status Jaringan',
            'PIC',
            'Status'
        ];
    }
} 
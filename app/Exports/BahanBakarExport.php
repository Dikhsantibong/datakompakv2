<?php

namespace App\Exports;

use App\Models\BahanBakar;
use App\Models\PowerPlant;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\WithDrawings;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use Illuminate\Support\Facades\Auth;

class BahanBakarExport implements WithMultipleSheets
{
    protected $request;

    public function __construct($request)
    {
        $this->request = $request;
    }

    public function sheets(): array
    {
        return [
            'Data' => new BahanBakarDataSheet($this->request),
            'Eviden' => new BahanBakarEvidenSheet($this->request)
        ];
    }
}

class BahanBakarDataSheet implements FromView, WithDrawings
{
    protected $request;

    public function __construct($request)
    {
        $this->request = $request;
    }

    public function view(): View
    {
        $query = BahanBakar::with('unit');
        $units = PowerPlant::orderBy('name')->get();

        if ($this->request->filled('unit_id')) {
            $query->where('unit_id', $this->request->unit_id);
        }

        if ($this->request->filled('jenis_bbm')) {
            $query->where('jenis_bbm', $this->request->jenis_bbm);
        }

        if ($this->request->filled('start_date')) {
            $query->where('tanggal', '>=', $this->request->start_date);
        }
        if ($this->request->filled('end_date')) {
            $query->where('tanggal', '<=', $this->request->end_date);
        }

        $bahanBakar = $query->latest('tanggal')->get();

        return view('admin.energiprimer.exports.bahan-bakar-excel', [
            'bahanBakar' => $bahanBakar,
            'units' => $units,
            'navlog_path' => public_path('logo/navlog1.png'),
            'k3_path' => public_path('logo/k3_logo.png')
        ]);
    }

    public function drawings()
    {
        // PLN Logo (kiri)
        $plnDrawing = new Drawing();
        $plnDrawing->setName('PLN Logo');
        $plnDrawing->setDescription('PLN Logo');
        $plnDrawing->setPath(public_path('logo/navlog1.png'));
        $plnDrawing->setHeight(60);
        $plnDrawing->setCoordinates('A1');
        $plnDrawing->setOffsetX(5);
        $plnDrawing->setOffsetY(5);

        // Get current user name
        $userName = Auth::user()->name ?? '';
        
        // Unit Logo (kanan) - Choose logo based on user name
        $unitDrawing = new Drawing();
        $unitDrawing->setName('Unit Logo');
        $unitDrawing->setDescription('Unit Logo');
        
        // Set logo path based on user name
        if (stripos($userName, 'PLTU MORAMO') !== false) {
            $logoPath = 'logo/PLTU_MORAMO.png';
        } elseif (stripos($userName, 'PLTD WUA WUA') !== false || stripos($userName, 'PLTD WUA-WUA') !== false) {
            $logoPath = 'logo/PLTD_WUA_WUA.png';
        } elseif (stripos($userName, 'PLTD POASIA CONTAINERIZED') !== false) {
            $logoPath = 'logo/PLTD_POASIA_CONTAINERIZED.png';
        } elseif (stripos($userName, 'PLTD POASIA') !== false) {
            $logoPath = 'logo/PLTD_POASIA.png';
        } elseif (stripos($userName, 'PLTD KOLAKA') !== false) {
            $logoPath = 'logo/PLTD_KOLAKA.png';
        } elseif (stripos($userName, 'PLTD LANIPA NIPA') !== false || stripos($userName, 'PLTD LANIPANIPA') !== false) {
            $logoPath = 'logo/PLTD_LANIPA_NIPA.png';
        } elseif (stripos($userName, 'PLTD LADUMPI') !== false) {
            $logoPath = 'logo/PLTD_LADUMPI.png';
        } elseif (stripos($userName, 'PLTM SABILAMBO') !== false) {
            $logoPath = 'logo/PLTM_SABILAMBO.png';
        } elseif (stripos($userName, 'PLTM MIKUASI') !== false) {
            $logoPath = 'logo/PLTM_MIKUASI.png';
        } elseif (stripos($userName, 'PLTD BAU BAU') !== false || stripos($userName, 'PLTD BAU-BAU') !== false) {
            $logoPath = 'logo/PLTD_BAU_BAU.png';
        } elseif (stripos($userName, 'PLTD PASARWAJO') !== false) {
            $logoPath = 'logo/PLTD_PASARWAJO.png';
        } elseif (stripos($userName, 'PLTM WINNING') !== false) {
            $logoPath = 'logo/PLTM_WINNING.png';
        } elseif (stripos($userName, 'PLTD RAHA') !== false) {
            $logoPath = 'logo/PLTD_RAHA.png';
        } elseif (stripos($userName, 'PLTD WANGI WANGI') !== false || stripos($userName, 'PLTD WANGI-WANGI') !== false) {
            $logoPath = 'logo/PLTD_WANGI_WANGI.png';
        } elseif (stripos($userName, 'PLTD LANGARA') !== false) {
            $logoPath = 'logo/PLTD_LANGARA.png';
        } elseif (stripos($userName, 'PLTD EREKE') !== false) {
            $logoPath = 'logo/PLTD_EREKE.png';
        } elseif (stripos($userName, 'PLTMG KENDARI') !== false) {
            $logoPath = 'logo/PLTMG_KENDARI.png';
        } elseif (stripos($userName, 'PLTU BARUTA') !== false) {
            $logoPath = 'logo/PLTU_BARUTA.png';
        } elseif (stripos($userName, 'PLTMG BAU BAU') !== false || stripos($userName, 'PLTMG BAU-BAU') !== false) {
            $logoPath = 'logo/PLTMG_BAU_BAU.png';
        } elseif (stripos($userName, 'PLTM RONGI') !== false) {
            $logoPath = 'logo/PLTM_RONGI.png';
        } else {
            $logoPath = 'logo/UP_KENDARI.png';
        }
        
        $unitDrawing->setPath(public_path($logoPath));
        $unitDrawing->setHeight(60);
        $unitDrawing->setCoordinates('H1');
        $unitDrawing->setOffsetX(5);
        $unitDrawing->setOffsetY(5);

        return [$plnDrawing, $unitDrawing];
    }
}

class BahanBakarEvidenSheet implements FromView, WithDrawings
{
    protected $request;

    public function __construct($request)
    {
        $this->request = $request;
    }

    public function view(): View
    {
        $query = BahanBakar::with('unit');
        $units = PowerPlant::orderBy('name')->get();

        if ($this->request->filled('unit_id')) {
            $query->where('unit_id', $this->request->unit_id);
        }

        if ($this->request->filled('jenis_bbm')) {
            $query->where('jenis_bbm', $this->request->jenis_bbm);
        }

        if ($this->request->filled('start_date')) {
            $query->where('tanggal', '>=', $this->request->start_date);
        }
        if ($this->request->filled('end_date')) {
            $query->where('tanggal', '<=', $this->request->end_date);
        }

        $bahanBakar = $query->latest('tanggal')->get();

        return view('admin.energiprimer.exports.bahan-bakar-eviden-excel', [
            'bahanBakar' => $bahanBakar,
            'units' => $units,
            'navlog_path' => public_path('logo/navlog1.png'),
            'k3_path' => public_path('logo/k3_logo.png')
        ]);
    }

    public function drawings()
    {
        // PLN Logo (kiri)
        $plnDrawing = new Drawing();
        $plnDrawing->setName('PLN Logo');
        $plnDrawing->setDescription('PLN Logo');
        $plnDrawing->setPath(public_path('logo/navlog1.png'));
        $plnDrawing->setHeight(60);
        $plnDrawing->setCoordinates('A1');
        $plnDrawing->setOffsetX(5);
        $plnDrawing->setOffsetY(5);

        // Get current user name
        $userName = Auth::user()->name ?? '';
        
        // Unit Logo (kanan) - Choose logo based on user name
        $unitDrawing = new Drawing();
        $unitDrawing->setName('Unit Logo');
        $unitDrawing->setDescription('Unit Logo');
        
        // Set logo path based on user name
        if (stripos($userName, 'PLTU MORAMO') !== false) {
            $logoPath = 'logo/PLTU_MORAMO.png';
        } elseif (stripos($userName, 'PLTD WUA WUA') !== false || stripos($userName, 'PLTD WUA-WUA') !== false) {
            $logoPath = 'logo/PLTD_WUA_WUA.png';
        } elseif (stripos($userName, 'PLTD POASIA CONTAINERIZED') !== false) {
            $logoPath = 'logo/PLTD_POASIA_CONTAINERIZED.png';
        } elseif (stripos($userName, 'PLTD POASIA') !== false) {
            $logoPath = 'logo/PLTD_POASIA.png';
        } elseif (stripos($userName, 'PLTD KOLAKA') !== false) {
            $logoPath = 'logo/PLTD_KOLAKA.png';
        } elseif (stripos($userName, 'PLTD LANIPA NIPA') !== false || stripos($userName, 'PLTD LANIPANIPA') !== false) {
            $logoPath = 'logo/PLTD_LANIPA_NIPA.png';
        } elseif (stripos($userName, 'PLTD LADUMPI') !== false) {
            $logoPath = 'logo/PLTD_LADUMPI.png';
        } elseif (stripos($userName, 'PLTM SABILAMBO') !== false) {
            $logoPath = 'logo/PLTM_SABILAMBO.png';
        } elseif (stripos($userName, 'PLTM MIKUASI') !== false) {
            $logoPath = 'logo/PLTM_MIKUASI.png';
        } elseif (stripos($userName, 'PLTD BAU BAU') !== false || stripos($userName, 'PLTD BAU-BAU') !== false) {
            $logoPath = 'logo/PLTD_BAU_BAU.png';
        } elseif (stripos($userName, 'PLTD PASARWAJO') !== false) {
            $logoPath = 'logo/PLTD_PASARWAJO.png';
        } elseif (stripos($userName, 'PLTM WINNING') !== false) {
            $logoPath = 'logo/PLTM_WINNING.png';
        } elseif (stripos($userName, 'PLTD RAHA') !== false) {
            $logoPath = 'logo/PLTD_RAHA.png';
        } elseif (stripos($userName, 'PLTD WANGI WANGI') !== false || stripos($userName, 'PLTD WANGI-WANGI') !== false) {
            $logoPath = 'logo/PLTD_WANGI_WANGI.png';
        } elseif (stripos($userName, 'PLTD LANGARA') !== false) {
            $logoPath = 'logo/PLTD_LANGARA.png';
        } elseif (stripos($userName, 'PLTD EREKE') !== false) {
            $logoPath = 'logo/PLTD_EREKE.png';
        } elseif (stripos($userName, 'PLTMG KENDARI') !== false) {
            $logoPath = 'logo/PLTMG_KENDARI.png';
        } elseif (stripos($userName, 'PLTU BARUTA') !== false) {
            $logoPath = 'logo/PLTU_BARUTA.png';
        } elseif (stripos($userName, 'PLTMG BAU BAU') !== false || stripos($userName, 'PLTMG BAU-BAU') !== false) {
            $logoPath = 'logo/PLTMG_BAU_BAU.png';
        } elseif (stripos($userName, 'PLTM RONGI') !== false) {
            $logoPath = 'logo/PLTM_RONGI.png';
        } else {
            $logoPath = 'logo/UP_KENDARI.png';
        }
        
        $unitDrawing->setPath(public_path($logoPath));
        $unitDrawing->setHeight(60);
        $unitDrawing->setCoordinates('H1');
        $unitDrawing->setOffsetX(5);
        $unitDrawing->setOffsetY(5);

        return [$plnDrawing, $unitDrawing];
    }
} 
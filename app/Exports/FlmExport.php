<?php

namespace App\Exports;

use App\Models\FlmInspection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class FlmExport implements FromCollection, WithHeadings, WithMapping
{
    protected $id;

    public function __construct($id = null)
    {
        $this->id = $id;
    }

    public function collection()
    {
        if ($this->id) {
            return FlmInspection::where('id', $this->id)->get();
        }
        return FlmInspection::orderBy('tanggal', 'desc')->get();
    }

    public function headings(): array
    {
        return [
            'Tanggal',
            'Mesin/Peralatan',
            'Sistem Pembangkit',
            'Masalah',
            'Kondisi Awal',
            'Tindakan',
            'Kondisi Akhir',
            'Catatan',
            'Status'
        ];
    }

    public function map($flm): array
    {
        $tindakan = [];
        if ($flm->tindakan_bersihkan) $tindakan[] = 'Bersihkan';
        if ($flm->tindakan_lumasi) $tindakan[] = 'Lumasi';
        if ($flm->tindakan_kencangkan) $tindakan[] = 'Kencangkan';
        if ($flm->tindakan_perbaikan_koneksi) $tindakan[] = 'Perbaikan Koneksi';
        if ($flm->tindakan_lainnya) $tindakan[] = 'Lainnya';

        return [
            $flm->tanggal,
            $flm->mesin,
            $flm->sistem,
            $flm->masalah,
            $flm->kondisi_awal,
            implode(', ', $tindakan),
            $flm->kondisi_akhir,
            $flm->catatan,
            $flm->status
        ];
    }
} 
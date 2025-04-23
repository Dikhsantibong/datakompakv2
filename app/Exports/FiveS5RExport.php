<?php

namespace App\Exports;

use App\Models\Pemeriksaan5s5r;
use App\Models\ProgramKerja5r;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Illuminate\Support\Collection;

class FiveS5RExport implements WithMultipleSheets
{
    protected $date;

    public function __construct($date)
    {
        $this->date = $date;
    }

    public function sheets(): array
    {
        return [
            new FiveS5RPemeriksaanSheet($this->date),
            new FiveS5RProgramKerjaSheet($this->date),
        ];
    }
}

class FiveS5RPemeriksaanSheet implements FromCollection, WithTitle, WithHeadings, WithMapping
{
    protected $date;

    public function __construct($date)
    {
        $this->date = $date;
    }

    public function collection()
    {
        return Pemeriksaan5s5r::whereDate('created_at', $this->date)->get();
    }

    public function title(): string
    {
        return 'Pemeriksaan 5S5R';
    }

    public function headings(): array
    {
        return [
            'Kategori',
            'Kondisi Awal',
            'PIC',
            'Area Kerja',
            'Area Produksi',
            'Membersihkan',
            'Merapikan',
            'Membuang Sampah',
            'Mengecat',
            'Lainnya',
            'Kondisi Akhir'
        ];
    }

    public function map($row): array
    {
        return [
            $row->kategori,
            $row->kondisi_awal,
            $row->pic,
            $row->area_kerja,
            $row->area_produksi,
            $row->membersihkan ? 'Ya' : 'Tidak',
            $row->merapikan ? 'Ya' : 'Tidak',
            $row->membuang_sampah ? 'Ya' : 'Tidak',
            $row->mengecat ? 'Ya' : 'Tidak',
            $row->lainnya ? 'Ya' : 'Tidak',
            $row->kondisi_akhir
        ];
    }
}

class FiveS5RProgramKerjaSheet implements FromCollection, WithTitle, WithHeadings, WithMapping
{
    protected $date;

    public function __construct($date)
    {
        $this->date = $date;
    }

    public function collection()
    {
        return ProgramKerja5r::whereDate('created_at', $this->date)->get();
    }

    public function title(): string
    {
        return 'Program Kerja 5R';
    }

    public function headings(): array
    {
        return [
            'Program Kerja',
            'Goal',
            'Kondisi Awal',
            'Progress',
            'Kondisi Akhir',
            'Catatan'
        ];
    }

    public function map($row): array
    {
        return [
            $row->program_kerja,
            $row->goal,
            $row->kondisi_awal,
            $row->progress,
            $row->kondisi_akhir,
            $row->catatan
        ];
    }
} 
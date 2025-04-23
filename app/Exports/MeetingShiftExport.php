<?php

namespace App\Exports;

use App\Models\MeetingShift;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\Exportable;
use Illuminate\Support\Collection;

class MeetingShiftExport implements FromCollection, WithHeadings, WithMapping, WithTitle, WithMultipleSheets
{
    use Exportable;

    protected $meetingShift;

    public function __construct(MeetingShift $meetingShift)
    {
        $this->meetingShift = $meetingShift;
    }

    public function sheets(): array
    {
        return [
            'Machine Statuses' => new MeetingShiftMachineStatusSheet($this->meetingShift),
            'Auxiliary Equipment' => new MeetingShiftAuxiliarySheet($this->meetingShift),
            'Resources' => new MeetingShiftResourceSheet($this->meetingShift),
            'K3L' => new MeetingShiftK3LSheet($this->meetingShift),
            'Notes' => new MeetingShiftNoteSheet($this->meetingShift),
            'Attendance' => new MeetingShiftAttendanceSheet($this->meetingShift),
        ];
    }

    public function collection()
    {
        return new Collection([$this->meetingShift]);
    }

    public function headings(): array
    {
        return [
            'Tanggal',
            'Shift',
            'Dibuat Oleh',
            'Resume'
        ];
    }

    public function map($row): array
    {
        return [
            $row->tanggal->format('d/m/Y'),
            $row->current_shift,
            $row->creator->name,
            $row->resume ? $row->resume->content : ''
        ];
    }

    public function title(): string
    {
        return 'Info';
    }
}

class MeetingShiftMachineStatusSheet implements FromCollection, WithHeadings, WithMapping, WithTitle
{
    protected $meetingShift;

    public function __construct(MeetingShift $meetingShift)
    {
        $this->meetingShift = $meetingShift;
    }

    public function collection()
    {
        return $this->meetingShift->machineStatuses;
    }

    public function headings(): array
    {
        return [
            'Mesin',
            'Status',
            'Keterangan'
        ];
    }

    public function map($row): array
    {
        return [
            $row->machine->name,
            implode(', ', json_decode($row->status)),
            $row->keterangan ?? ''
        ];
    }

    public function title(): string
    {
        return 'Machine Statuses';
    }
}

class MeetingShiftAuxiliarySheet implements FromCollection, WithHeadings, WithMapping, WithTitle
{
    protected $meetingShift;

    public function __construct(MeetingShift $meetingShift)
    {
        $this->meetingShift = $meetingShift;
    }

    public function collection()
    {
        return $this->meetingShift->auxiliaryEquipments;
    }

    public function headings(): array
    {
        return [
            'Nama',
            'Status',
            'Keterangan'
        ];
    }

    public function map($row): array
    {
        return [
            $row->name,
            implode(', ', json_decode($row->status)),
            $row->keterangan ?? ''
        ];
    }

    public function title(): string
    {
        return 'Auxiliary Equipment';
    }
}

class MeetingShiftResourceSheet implements FromCollection, WithHeadings, WithMapping, WithTitle
{
    protected $meetingShift;

    public function __construct(MeetingShift $meetingShift)
    {
        $this->meetingShift = $meetingShift;
    }

    public function collection()
    {
        return $this->meetingShift->resources;
    }

    public function headings(): array
    {
        return [
            'Nama',
            'Kategori',
            'Status',
            'Keterangan'
        ];
    }

    public function map($row): array
    {
        return [
            $row->name,
            $row->category,
            $row->status,
            $row->keterangan ?? ''
        ];
    }

    public function title(): string
    {
        return 'Resources';
    }
}

class MeetingShiftK3LSheet implements FromCollection, WithHeadings, WithMapping, WithTitle
{
    protected $meetingShift;

    public function __construct(MeetingShift $meetingShift)
    {
        $this->meetingShift = $meetingShift;
    }

    public function collection()
    {
        return $this->meetingShift->k3ls;
    }

    public function headings(): array
    {
        return [
            'Tipe',
            'Uraian',
            'Saran'
        ];
    }

    public function map($row): array
    {
        return [
            $row->type,
            $row->uraian,
            $row->saran
        ];
    }

    public function title(): string
    {
        return 'K3L';
    }
}

class MeetingShiftNoteSheet implements FromCollection, WithHeadings, WithMapping, WithTitle
{
    protected $meetingShift;

    public function __construct(MeetingShift $meetingShift)
    {
        $this->meetingShift = $meetingShift;
    }

    public function collection()
    {
        return $this->meetingShift->notes;
    }

    public function headings(): array
    {
        return [
            'Tipe',
            'Konten'
        ];
    }

    public function map($row): array
    {
        return [
            ucfirst($row->type),
            $row->content
        ];
    }

    public function title(): string
    {
        return 'Notes';
    }
}

class MeetingShiftAttendanceSheet implements FromCollection, WithHeadings, WithMapping, WithTitle
{
    protected $meetingShift;

    public function __construct(MeetingShift $meetingShift)
    {
        $this->meetingShift = $meetingShift;
    }

    public function collection()
    {
        return $this->meetingShift->attendances;
    }

    public function headings(): array
    {
        return [
            'Nama',
            'Shift',
            'Status',
            'Keterangan'
        ];
    }

    public function map($row): array
    {
        return [
            $row->nama,
            $row->shift,
            $row->status,
            $row->keterangan ?? ''
        ];
    }

    public function title(): string
    {
        return 'Attendance';
    }
} 
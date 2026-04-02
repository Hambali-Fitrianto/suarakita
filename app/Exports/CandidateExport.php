<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class CandidateExport implements FromCollection, WithHeadings, ShouldAutoSize
{
    protected $data;

    public function __construct($data = null)
    {
        // Jika data null, nanti excelnya kosong (untuk template)
        $this->data = $data;
    }

    public function collection()
    {
        return $this->data ?? collect([]);
    }

    public function headings(): array
    {
        return [
            'ID',           // Acuan Utama (Jangan diisi kalau data baru)
            'Nama',
            'Gelar',
            'Jabatan',
            'Asal Sekolah',
            'No HP'
        ];
    }
}

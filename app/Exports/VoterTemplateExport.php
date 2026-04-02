<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class VoterTemplateExport implements WithHeadings, ShouldAutoSize
{
    public function headings(): array
    {
        return [
            'ID',           // Biarkan kosong untuk insert baru, isi ID untuk update
            'Nama',
            'Asal Sekolah',
            'No HP'
        ];
    }
}

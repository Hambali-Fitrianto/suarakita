<?php

namespace App\Exports;

use App\Models\Member;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class VoterExport implements FromCollection, WithHeadings
{
    protected $eventId;

    // Langsung minta eventId saja agar tidak tertukar
    public function __construct($eventId = null)
    {
        $this->eventId = $eventId;
    }

    public function collection()
    {
        // Pastikan narik data berdasarkan eventId yang dikirim dari tombol
        return Member::where('voting_event_id', $this->eventId)
            ->pemilih()
            ->select('id', 'nama', 'asal_sekolah', 'no_hp')
            ->get();
    }

    public function headings(): array
    {
        return ['ID', 'Nama', 'Asal Sekolah', 'No HP'];
    }
}

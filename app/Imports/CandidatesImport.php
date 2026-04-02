<?php

namespace App\Imports;

use App\Models\Member;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class CandidatesImport implements ToModel, WithHeadingRow
{
    protected $eventId;

    public function __construct($eventId)
    {
        $this->eventId = $eventId;
    }

    public function model(array $row)
    {
        if (empty($row['nama'])) return null;

        // UPSERT LOGIC: Cari ID, kalau ada UPDATE, kalau tidak ada CREATE
        return Member::updateOrCreate(
            ['id' => $row['id']], // Cari berdasarkan ID di excel
            [
                'voting_event_id' => $this->eventId,
                'nama'           => $row['nama'],
                'gelar'          => $row['gelar'],
                'jabatan'        => $row['jabatan'],
                'asal_sekolah'   => $row['asal_sekolah'],
                'no_hp'          => $row['no_hp'],
                'role'           => Member::ROLE_KANDIDAT,
                // Jika data baru (ID kosong), beri nomor_urut 0 dulu nanti di-reorder
                'nomor_urut'     => $row['id'] ? (Member::find($row['id'])->nomor_urut ?? 0) : 0,
            ]
        );
    }
}

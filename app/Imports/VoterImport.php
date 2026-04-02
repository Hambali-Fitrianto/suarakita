<?php

namespace App\Imports;

use App\Models\Member;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class VoterImport implements ToModel, WithHeadingRow
{
    protected $voting_event_id;

    public function __construct($voting_event_id)
    {
        $this->voting_event_id = $voting_event_id;
    }

    public function model(array $row)
    {
        // Jika nama kosong, lewati saja
        if (empty($row['nama'])) {
            return null;
        }

        // 1. Jika di Excel ADA ID, cari berdasarkan ID (untuk Update data lama)
        if (!empty($row['id'])) {
            return Member::updateOrCreate(
                ['id' => $row['id']],
                [
                    'voting_event_id' => $this->voting_event_id,
                    'nama'            => $row['nama'],
                    'asal_sekolah'    => $row['asal_sekolah'] ?? null,
                    'no_hp'           => $row['no_hp'] ?? null,
                    'role'            => Member::ROLE_PEMILIH,
                ]
            );
        }

        // 2. Jika di Excel TIDAK ADA ID, cari berdasarkan Nama & Event (untuk mencegah duplikat Nama di event yang sama)
        return Member::updateOrCreate(
            [
                'voting_event_id' => $this->voting_event_id,
                'nama'            => $row['nama'],
                'role'            => Member::ROLE_PEMILIH,
            ],
            [
                'asal_sekolah'    => $row['asal_sekolah'] ?? null,
                'no_hp'           => $row['no_hp'] ?? null,
            ]
        );
    }
}

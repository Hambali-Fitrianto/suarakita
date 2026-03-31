<?php

namespace App\Services;

use App\Models\VotingSession;
use Exception;

class VotingGuard
{
    /*
    |--------------------------------------------------------------------------
    | AUTHORIZE VOTING ⭐⭐⭐
    |--------------------------------------------------------------------------
    |
    | Validasi apakah voting boleh dilakukan
    |
    */

    public static function authorize(int $eventId): VotingSession
    {
        $session = VotingSession::activeForEvent($eventId);

        // Tidak ada session aktif
        if (!$session) {
            throw new Exception(
                'Voting belum dimulai atau sudah berakhir.'
            );
        }

        // Double safety check
        if (!$session->isAktif()) {
            throw new Exception(
                'Session voting tidak aktif.'
            );
        }

        return $session;
    }
}
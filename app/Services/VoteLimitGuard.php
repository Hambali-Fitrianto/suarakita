<?php

namespace App\Services;

use App\Models\Vote;
use Exception;

class VoteLimitGuard
{
    /*
    |--------------------------------------------------------------------------
    | ONE PERSON ONE VOTE (PER EVENT)
    |--------------------------------------------------------------------------
    */

    public static function ensureNotVoted(
        int $memberId,
        int $eventId
    ): void {

        $alreadyVoted = Vote::where('member_id', $memberId)
            ->where('voting_event_id', $eventId)
            ->exists();

        if ($alreadyVoted) {
            throw new Exception(
                'Anda sudah melakukan voting pada event ini.'
            );
        }
    }
}
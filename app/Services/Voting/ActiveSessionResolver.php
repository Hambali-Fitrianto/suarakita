<?php

namespace App\Services\Voting;

use App\Models\VotingSession;
use Carbon\Carbon;

class ActiveSessionResolver
{
    /*
    |--------------------------------------------------------------------------
    | RESOLVE ACTIVE SESSION OTOMATIS
    |--------------------------------------------------------------------------
    */
    public function resolve(): ?VotingSession
    {
        $now = Carbon::now();

        /*
        |--------------------------------------------------------------------------
        | 1. Cari session yang sedang berjalan
        |--------------------------------------------------------------------------
        */
        $active = VotingSession::query()
            ->whereNull('deleted_at')
            ->where('mulai_at', '<=', $now)
            ->where('selesai_at', '>=', $now)
            ->orderBy('mulai_at')
            ->first();

        if ($active) {
            return $active;
        }

        /*
        |--------------------------------------------------------------------------
        | 2. AUTO TRANSITION (NEXT SESSION)
        |--------------------------------------------------------------------------
        | Cari session paling dekat setelah sekarang
        */
        $next = VotingSession::query()
            ->whereNull('deleted_at')
            ->where('mulai_at', '>', $now)
            ->orderBy('mulai_at')
            ->first();

        return $next && $now->gte($next->mulai_at)
            ? $next
            : null;
    }
}
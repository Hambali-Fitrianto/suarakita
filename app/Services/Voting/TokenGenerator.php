<?php

namespace App\Services\Voting;

use App\Models\VotingSession;
use App\Models\Member;
use App\Models\Token;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class TokenGenerator
{
    public function generate(VotingSession $session): int
    {
        return DB::transaction(function () use ($session) {

            $members = Member::pemilih()
                ->event($session->voting_event_id)
                ->get();

            $created = 0;

            foreach ($members as $member) {

                // skip jika sudah punya token
                $exists = Token::where([
                    'voting_session_id' => $session->id,
                    'member_id' => $member->id,
                ])->exists();

                if ($exists) {
                    continue;
                }

                Token::create([
                    'voting_event_id'   => $session->voting_event_id,
                    'voting_session_id' => $session->id,
                    'member_id'         => $member->id,
                    'token'             => $this->generateToken(),
                ]);

                $created++;
            }

            return $created;
        });
    }

    private function generateToken(): string
    {
        return strtoupper(Str::random(8));
    }
}
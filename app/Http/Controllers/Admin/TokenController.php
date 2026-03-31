<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\VotingSession;
use App\Models\Token;
use App\Services\Voting\TokenGenerator;

class TokenController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | LIST TOKEN
    |--------------------------------------------------------------------------
    */
    public function index(VotingSession $session)
    {
        $tokens = Token::where('voting_session_id', $session->id)
            ->with('member')
            ->latest()
            ->paginate(20);

        return view('admin.tokens.index', compact('session','tokens'));
    }


    /*
    |--------------------------------------------------------------------------
    | GENERATE TOKEN MASSAL
    |--------------------------------------------------------------------------
    */
    public function generate(
        VotingSession $session,
        TokenGenerator $generator
    )
    {
        $total = $generator->generate($session);

        return back()->with(
            'success',
            "{$total} token berhasil dibuat."
        );
    }
}
<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Token;

class TokenController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | FORM INPUT TOKEN
    |--------------------------------------------------------------------------
    */
    public function index()
    {
        return view('token.index');
    }

    /*
    |--------------------------------------------------------------------------
    | VERIFY TOKEN
    |--------------------------------------------------------------------------
    */
    public function verify(Request $request)
    {
        $request->validate([
            'token' => ['required','string']
        ]);

        $token = Token::where('token', $request->token)->first();

        if (!$token || $token->is_used) {
            return back()->withErrors([
                'token' => 'Token tidak valid atau sudah digunakan.'
            ]);
        }

        /*
        | simpan token ke session voting
        */
        session([
            'voting_token_id' => $token->id
        ]);

        return redirect()->route('vote.index');
    }
}
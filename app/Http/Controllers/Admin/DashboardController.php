<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Member;
use App\Models\Vote;
use App\Models\VotingEvent;
use App\Models\VotingSession;

class DashboardController extends Controller
{
    public function index()
    {
        /*
        |--------------------------------------------------------------------------
        | TOTAL EVENT
        |--------------------------------------------------------------------------
        */
        $totalEvent = VotingEvent::count();

        /*
        |--------------------------------------------------------------------------
        | TOTAL SESSION
        |--------------------------------------------------------------------------
        */
        $totalSession = VotingSession::count();

        /*
        |--------------------------------------------------------------------------
        | TOTAL PEMILIH ⭐ (FIXED)
        |--------------------------------------------------------------------------
        */
        $totalPemilih = Member::pemilih()->count();

        /*
        |--------------------------------------------------------------------------
        | TOTAL KANDIDAT
        |--------------------------------------------------------------------------
        */
        $totalKandidat = Member::kandidat()->count();

        /*
        |--------------------------------------------------------------------------
        | TOTAL SUARA MASUK
        |--------------------------------------------------------------------------
        */
        $totalVote = Vote::count();

        return view('admin.dashboard', compact(
            'totalEvent',
            'totalSession',
            'totalPemilih',
            'totalKandidat',
            'totalVote'
        ));
    }
}
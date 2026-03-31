<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

use App\Models\VotingSession;
use App\Models\Member;

class ResultController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | LIST SESSION (INDEX)
    |--------------------------------------------------------------------------
    */
    public function index()
    {
        $sessions = VotingSession::with('event')
            ->latest()
            ->get();

        return view('public.result.index', compact('sessions'));
    }


    /*
    |--------------------------------------------------------------------------
    | SHOW RESULT PER SESSION
    |--------------------------------------------------------------------------
    */
    public function show(VotingSession $session)
    {
        $event = $session->event;

        /*
        |==================================================
        | RESULT COUNT PER SESSION
        |==================================================
        */

        $results = Member::query()
            ->where('members.voting_event_id', $event->id)
            ->where('members.role', Member::ROLE_KANDIDAT)
            ->leftJoin('votes', function ($join) use ($session) {
                $join->on('members.id', '=', 'votes.candidate_id')
                     ->where('votes.voting_session_id', $session->id);
            })
            ->select(
                'members.id',
                'members.nama',
                'members.jabatan',
                'members.foto',
                DB::raw('COUNT(votes.id) as total_suara')
            )
            ->groupBy(
                'members.id',
                'members.nama',
                'members.jabatan',
                'members.foto'
            )
            ->orderByDesc('total_suara')
            ->get();

        return view('public.result.show', compact(
            'session',
            'event',
            'results'
        ));
    }
}
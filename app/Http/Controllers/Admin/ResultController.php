<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\VotingSession;
use App\Models\Member;
use Illuminate\Support\Facades\DB;

class ResultController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | INDEX
    | Menampilkan list session voting
    |--------------------------------------------------------------------------
    */
    public function index()
    {
        $sessions = VotingSession::with('event')
            ->orderBy('voting_event_id')
            ->orderBy('urutan')
            ->get()
            ->groupBy(function ($session) {
                return $session->event->judul ?? 'Tanpa Event';
            });

        return view('admin.results.index', [
            'sessions' => $sessions,
        ]);
    }


    /*
    |--------------------------------------------------------------------------
    | SHOW
    | Detail hasil voting per session
    |--------------------------------------------------------------------------
    */
    public function show(VotingSession $session)
    {
        $eventId = $session->voting_event_id;

        /*
        |--------------------------------------------------
        | Hitung suara kandidat
        |--------------------------------------------------
        */
        $voteCounts = DB::table('votes')
            ->select('candidate_id', DB::raw('COUNT(*) as total'))
            ->where('voting_session_id', $session->id)
            ->groupBy('candidate_id')
            ->pluck('total', 'candidate_id');

        /*
        |--------------------------------------------------
        | Ambil kandidat event
        |--------------------------------------------------
        */
        $candidates = Member::where('voting_event_id', $eventId)
            ->kandidat()
            ->get();

        $results     = [];
        $totalVotes  = 0;

        foreach ($candidates as $candidate) {

            $votes = $voteCounts[$candidate->id] ?? 0;
            $totalVotes += $votes;

            $results[] = [
                'candidate' => $candidate,
                'votes'     => $votes,
            ];
        }

        /*
        |--------------------------------------------------
        | Ranking berdasarkan suara
        |--------------------------------------------------
        */
        usort($results, function ($a, $b) {
            return $b['votes'] <=> $a['votes'];
        });

        return view('admin.results.show', [
            'session'    => $session,
            'results'    => $results,
            'totalVotes' => $totalVotes,
        ]);
    }
}
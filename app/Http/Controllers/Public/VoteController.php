<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Services\Voting\ActiveSessionResolver;
use App\Models\Token;
use App\Models\Vote;
use App\Models\Member;
use App\Models\VotingEvent;

class VoteController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | DIRECT TOKEN ACCESS (QR / LINK)
    |--------------------------------------------------------------------------
    */
    public function direct(string $tokenValue)
    {
        $token = Token::where('token', $tokenValue)->first();

        if (!$token || $token->is_used) {
            return redirect('/token')
                ->with('error', 'Token tidak valid atau sudah digunakan.');
        }

        // simpan token ke session
        session([
            'voting_token_id' => $token->id
        ]);

        return redirect()->route('vote.index');
    }


    /*
    |--------------------------------------------------------------------------
    | SHOW VOTING PAGE
    |--------------------------------------------------------------------------
    */
    public function index(ActiveSessionResolver $resolver)
    {
        /*
        | TOKEN CHECK
        */
        $token = $this->resolveTokenFromSession();

        if (!$token) {
            return redirect('/token')
                ->with('error', 'Silakan masukkan token terlebih dahulu.');
        }

        /*
        | RESOLVE ACTIVE SESSION
        */
        $session = $resolver->resolve();

        if (!$session) {
            return view('voting.closed', [
                'message' => 'Belum ada session voting aktif.'
            ]);
        }

        /*
        | TOKEN HARUS SESUAI SESSION
        */
        if ($token->voting_session_id !== $session->id) {
            return view('voting.closed', [
                'message' => 'Token tidak berlaku untuk session ini.'
            ]);
        }

        /*
        | SESSION STATUS CHECK
        */
        if (!$session->isAktif()) {
            return view('voting.closed', [
                'message' => 'Voting belum dimulai atau sudah berakhir.'
            ]);
        }

        /*
        | EVENT
        */
        $event = VotingEvent::findOrFail($session->voting_event_id);

        /*
        | AMBIL KANDIDAT
        */
        $candidates = $event->kandidat()
            ->orderBy('nomor_urut')
            ->get();

        return view('voting.vote', [
            'session'    => $session,
            'event'      => $event,
            'candidates' => $candidates,
            'token'      => $token,
            'endsAt'     => optional($session->selesai_at)->timestamp,
        ]);
    }


    /*
    |--------------------------------------------------------------------------
    | SUBMIT VOTE
    |--------------------------------------------------------------------------
    */
    public function submit(Request $request, ActiveSessionResolver $resolver)
    {
        $request->validate([
            'candidate_id' => ['required', 'integer'],
        ]);

        /*
        | TOKEN VALIDATION
        */
        $token = $this->resolveTokenFromSession();

        if (!$token) {
            return redirect('/token')
                ->with('error', 'Session voting tidak ditemukan.');
        }

        if ($token->is_used) {
            return redirect('/vote/success');
        }

        $member = $token->member;

        /*
        | SESSION VALIDATION
        */
        $session = $resolver->resolve();

        if (!$session || $session->id !== $token->voting_session_id) {
            return redirect('/vote')
                ->with('error', 'Session voting sudah berakhir.');
        }

        if (!$session->isAktif()) {
            return redirect('/vote')
                ->with('error', 'Voting sudah ditutup.');
        }

        $event = VotingEvent::findOrFail($session->voting_event_id);

        /*
        | ONE PERSON ONE VOTE
        */
        if ($member->hasVotedInEvent($event->id)) {
            return redirect('/vote/success');
        }

        /*
        | VALIDASI KANDIDAT
        */
        $candidate = Member::where('id', $request->candidate_id)
            ->where('voting_event_id', $event->id)
            ->where('role', Member::ROLE_KANDIDAT)
            ->first();

        if (!$candidate) {
            return back()->with('error', 'Kandidat tidak valid.');
        }

        /*
        | SAVE VOTE (TRANSACTION SAFE)
        */
        DB::transaction(function () use (
            $token,
            $session,
            $candidate,
            $member,
            $event
        ) {

            Vote::create([
                'voting_event_id'   => $event->id,
                'voting_session_id' => $session->id,
                'member_id'         => $member->id,
                'token_id'          => $token->id,
                'candidate_id'      => $candidate->id,
            ]);

            $token->update([
                'is_used' => true,
                'used_at' => now(),
            ]);
        });

        /*
        | CLEAR SESSION TOKEN
        */
        session()->forget('voting_token_id');

        return redirect('/vote/success');
    }


    /*
    |--------------------------------------------------------------------------
    | HELPER — TOKEN SESSION RESOLVER
    |--------------------------------------------------------------------------
    */
    private function resolveTokenFromSession(): ?Token
    {
        $tokenId = session('voting_token_id');

        if (!$tokenId) {
            return null;
        }

        $token = Token::with('member')->find($tokenId);

        if (!$token || $token->is_used) {
            return null;
        }

        return $token;
    }


    /*
    |--------------------------------------------------------------------------
    | SUCCESS PAGE
    |--------------------------------------------------------------------------
    */
    public function success()
    {
        return view('voting.success');
    }
}
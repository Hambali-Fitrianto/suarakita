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
use App\Models\VotingSession; // WAJIB ADA

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
        | RESOLVE ACTIVE SESSION (LOGIC PERBAIKAN) ⭐
        */
        $session = $resolver->resolve();

        // JIKA RESOLVER GAGAL, PAKSA CARI BERDASARKAN ID DI TOKEN
        if (!$session) {
            $session = VotingSession::find($token->voting_session_id);
        }

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
        | SESSION STATUS CHECK (MENGGUNAKAN LOGIC MODEL YANG SUDAH DI-FIX) ⭐
        */
        if (!$session->isAktif()) {
            return view('voting.closed', [
                'message' => 'Voting ' . ($session->nama_sesi ?? '') . ' belum dimulai atau sudah berakhir.'
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
            'endsAt'     => $session->selesai_at
                ? \Carbon\Carbon::parse($session->selesai_at)->timestamp
                : null,
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
            return redirect()->route('public.result.show', $token->voting_session_id);
        }

        $member = $token->member;

        /*
        | SESSION VALIDATION (PAKSA CARI JIKA RESOLVER NULL) ⭐
        */
        $session = $resolver->resolve() ?? VotingSession::find($token->voting_session_id);

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
            return redirect()->route('public.result.show', $session->id);
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
        | SAVE VOTE
        */
        DB::transaction(function () use ($token, $session, $candidate, $member, $event) {
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

        $targetSessionId = $session->id;
        session()->forget('voting_token_id');

        return redirect()->route('public.result.show', $targetSessionId)
            ->with('success', 'Terima kasih! Suara Anda berhasil dikirim.');
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
        return redirect()->route('public.result.index');
    }
}

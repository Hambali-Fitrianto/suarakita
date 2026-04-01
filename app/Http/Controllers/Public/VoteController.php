<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Token;
use App\Models\Vote;
use App\Models\Member;
use App\Models\VotingEvent;
use App\Models\VotingSession;

class VoteController extends Controller
{
    public function direct(string $tokenValue)
    {
        $token = Token::where('token', $tokenValue)->first();

        if (!$token || $token->is_used) {
            return redirect('/token')->with('error', 'Token tidak valid atau sudah digunakan.');
        }

        session(['voting_token_id' => $token->id]);
        return redirect()->route('vote.index');
    }

    public function index()
    {
        $token = $this->resolveTokenFromSession();

        if (!$token) {
            return redirect('/token')->with('error', 'Silakan masukkan token terlebih dahulu.');
        }

        $session = VotingSession::find($token->voting_session_id);

        if (!$session) {
            return view('voting.closed', ['message' => 'Session tidak ditemukan.']);
        }

        if ($session->status !== 'aktif') {
            return view('voting.closed', [
                'message' => 'Voting ' . ($session->nama_sesi ?? '') . ' belum dibuka.'
            ]);
        }

        $event = VotingEvent::findOrFail($session->voting_event_id);
        $candidates = $event->kandidat()->orderBy('nomor_urut')->get();

        return view('voting.vote', [
            'session'    => $session,
            'event'      => $event,
            'candidates' => $candidates,
            'token'      => $token,
            'endsAt'     => $session->selesai_at ? \Carbon\Carbon::parse($session->selesai_at)->timestamp : null,
        ]);
    }

    public function submit(Request $request)
    {
        $request->validate([
            'candidate_id' => ['required', 'integer'],
        ]);

        $token = $this->resolveTokenFromSession();

        if (!$token) {
            return redirect('/token')->with('error', 'Session voting tidak ditemukan.');
        }

        // 1. Ambil Session Langsung dari Token (Anti Ribet)
        $session = VotingSession::find($token->voting_session_id);

        if (!$session || $session->status !== 'aktif') {
            return redirect('/vote')->with('error', 'Voting sudah ditutup atau tidak aktif.');
        }

        $event = VotingEvent::findOrFail($session->voting_event_id);
        $member = $token->member;

        // 2. Cek apakah sudah pernah vote di event ini
        if ($member->hasVotedInEvent($event->id)) {
            return redirect()->route('public.result.show', $session->id);
        }

        // 3. Validasi Kandidat
        $candidate = Member::where('id', $request->candidate_id)
            ->where('voting_event_id', $event->id)
            ->where('role', Member::ROLE_KANDIDAT)
            ->first();

        if (!$candidate) {
            return back()->with('error', 'Kandidat tidak valid.');
        }

        // 4. Proses Simpan Data
        try {
            DB::beginTransaction();

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

            DB::commit();

            $targetSessionId = $session->id;
            session()->forget('voting_token_id');

            return redirect()->route('public.result.show', $targetSessionId)
                ->with('success', 'Terima kasih! Suara Anda berhasil dikirim.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    private function resolveTokenFromSession(): ?Token
    {
        $tokenId = session('voting_token_id');
        if (!$tokenId) return null;

        $token = Token::with('member')->find($tokenId);
        if (!$token || $token->is_used) return null;

        return $token;
    }

    public function success()
    {
        return redirect()->route('public.result.index');
    }
}

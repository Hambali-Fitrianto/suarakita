<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\VotingSession;
use App\Models\VotingEvent;
use App\Models\Member;
use App\Models\Token;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class SessionController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | INDEX
    |--------------------------------------------------------------------------
    */
    public function index()
    {
        $sessions = VotingSession::with('event')
            ->orderBy('voting_event_id')
            ->orderBy('urutan')
            ->paginate(10);

        $trashCount = VotingSession::onlyTrashed()->count();

        return view('admin.sessions.index', compact(
            'sessions',
            'trashCount'
        ));
    }

    /*
    |--------------------------------------------------------------------------
    | CREATE
    |--------------------------------------------------------------------------
    */
    public function create()
    {
        $events = VotingEvent::latest()->get();

        return view('admin.sessions.create', compact('events'));
    }

    /*
    |--------------------------------------------------------------------------
    | STORE ⭐ AUTO URUTAN
    |--------------------------------------------------------------------------
    */
    public function store(Request $request)
    {
        $request->validate([
            'voting_event_id' => 'required|exists:voting_events,id',
            'nama_sesi'       => 'required|max:255',
            'mulai_at'        => 'nullable|date',
            'selesai_at'      => 'nullable|date|after:mulai_at',
            'status'          => 'required|in:draft,jeda,aktif',
        ]);

        DB::transaction(function () use ($request) {

            $lastOrder = VotingSession::where(
                'voting_event_id',
                $request->voting_event_id
            )
            ->lockForUpdate()
            ->max('urutan');

            $urutan = ($lastOrder ?? 0) + 1;

            VotingSession::create([
                'voting_event_id' => $request->voting_event_id,
                'nama_sesi'       => $request->nama_sesi,
                'urutan'          => $urutan,
                'status'          => $request->status,
                'mulai_at'        => $request->mulai_at,
                'selesai_at'      => $request->selesai_at,
                'jumlah_perpanjangan' => 0,
            ]);
        });

        return redirect()
            ->route('admin.sessions.index')
            ->with('success', 'Session berhasil dibuat');
    }

    /*
    |--------------------------------------------------------------------------
    | SHOW
    |--------------------------------------------------------------------------
    */
    public function show(VotingSession $session)
    {
        $session->load('event');

        return view('admin.sessions.show', compact('session'));
    }

    /*
    |--------------------------------------------------------------------------
    | EDIT
    |--------------------------------------------------------------------------
    */
    public function edit(VotingSession $session)
    {
        $events = VotingEvent::latest()->get();

        return view('admin.sessions.edit', compact(
            'session',
            'events'
        ));
    }

    /*
    |--------------------------------------------------------------------------
    | UPDATE
    |--------------------------------------------------------------------------
    */
    public function update(Request $request, VotingSession $session)
    {
        $request->validate([
            'nama_sesi'  => 'required|max:255',
            'mulai_at'   => 'nullable|date',
            'selesai_at' => 'nullable|date|after:mulai_at',
            'status'     => 'required|in:draft,jeda,aktif',
        ]);

        $session->update([
            'nama_sesi'  => $request->nama_sesi,
            'status'     => $request->status,
            'mulai_at'   => $request->mulai_at,
            'selesai_at' => $request->selesai_at,
        ]);

        return redirect()
            ->route('admin.sessions.index')
            ->with('success', 'Session berhasil diupdate');
    }

    /*
    |--------------------------------------------------------------------------
    | ⭐ GENERATE TOKENS (FIXED + OPTIMIZED + SAFE)
    |--------------------------------------------------------------------------
    */
    public function generateTokens(VotingSession $session)
    {
        DB::transaction(function () use ($session) {

            // lock session supaya tidak double generate
            VotingSession::where('id', $session->id)
                ->lockForUpdate()
                ->first();

            // ambil semua pemilih (ambil id saja biar ringan)
            $voterIds = Member::where('voting_event_id', $session->voting_event_id)
                ->pemilih()
                ->pluck('id');

            if ($voterIds->isEmpty()) {
                return;
            }

            // ambil member yg sudah punya token
            $existingMemberIds = Token::where('voting_session_id', $session->id)
                ->whereIn('member_id', $voterIds)
                ->pluck('member_id');

            // filter yg belum punya token
            $newMembers = $voterIds->diff($existingMemberIds);

            if ($newMembers->isEmpty()) {
                return;
            }

            $insertData = [];

            foreach ($newMembers as $memberId) {

                // generate token unik
                do {
                    $token = strtoupper(Str::random(8));
                } while (
                    Token::where('token', $token)->exists()
                );

                $insertData[] = [
                    'voting_event_id'   => $session->voting_event_id,
                    'voting_session_id' => $session->id,
                    'member_id'         => $memberId,
                    'token'             => $token,
                    'is_used'           => false,
                    'created_at'        => now(),
                    'updated_at'        => now(),
                ];
            }

            // insert massal (SUPER CEPAT)
            Token::insert($insertData);
        });

        return back()->with('success', 'Token voting berhasil dibuat');
    }

    /*
    |--------------------------------------------------------------------------
    | DELETE
    |--------------------------------------------------------------------------
    */
    public function destroy(VotingSession $session)
    {
        $session->delete();

        return back()->with('success', 'Session dipindahkan ke trash');
    }

    /*
    |--------------------------------------------------------------------------
    | TRASH
    |--------------------------------------------------------------------------
    */
    public function trash()
    {
        $sessions = VotingSession::onlyTrashed()
            ->with('event')
            ->latest('deleted_at')
            ->paginate(10);

        return view('admin.sessions.trash', compact('sessions'));
    }

    /*
    |--------------------------------------------------------------------------
    | RESTORE
    |--------------------------------------------------------------------------
    */
    public function restore($id)
    {
        VotingSession::onlyTrashed()
            ->findOrFail($id)
            ->restore();

        return back()->with('success', 'Session direstore');
    }

    /*
    |--------------------------------------------------------------------------
    | FORCE DELETE
    |--------------------------------------------------------------------------
    */
    public function forceDelete($id)
    {
        VotingSession::onlyTrashed()
            ->findOrFail($id)
            ->forceDelete();

        return back()->with('success', 'Session dihapus permanen');
    }

    /*
    |--------------------------------------------------------------------------
    | TOKEN LIST ⭐ (TAMBAHKAN DISINI)
    |--------------------------------------------------------------------------
    */
    public function tokens(VotingSession $session)
    {
        $tokens = Token::with([
                'member',
                'session.event'
            ])
            ->where('voting_session_id', $session->id)
            ->get()
            ->groupBy(function ($token) {
                return $token->session->event->judul ?? 'Tanpa Event';
            });

        return view('admin.sessions.tokens', compact(
            'session',
            'tokens'
        ));
    }
}
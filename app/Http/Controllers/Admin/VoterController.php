<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Member;
use App\Models\VotingEvent;

class VoterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | INDEX
    |--------------------------------------------------------------------------
    */
    public function index()
    {
        $voters = Member::pemilih()
            ->with('event')
            ->join('voting_events', 'members.voting_event_id', '=', 'voting_events.id')
            ->orderBy('voting_events.judul')
            ->orderBy('members.nama')
            ->select('members.*')
            ->paginate(20);

        $trashCount = Member::onlyTrashed()
            ->pemilih()
            ->count();

        return view('admin.voters.index', compact(
            'voters',
            'trashCount'
        ));
    }

    /*
    |--------------------------------------------------------------------------
    | TRASH
    |--------------------------------------------------------------------------
    */
    public function trash()
    {
        $voters = Member::onlyTrashed()
            ->pemilih()
            ->latest('deleted_at')
            ->paginate(10);

        return view('admin.voters.trash', compact('voters'));
    }

    /*
    |--------------------------------------------------------------------------
    | CREATE
    |--------------------------------------------------------------------------
    */
    public function create()
    {
        $events = VotingEvent::latest()->get();

        return view('admin.voters.create', compact('events'));
    }

    /*
    |--------------------------------------------------------------------------
    | STORE
    |--------------------------------------------------------------------------
    */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'voting_event_id' => ['required','exists:voting_events,id'],
            'nama'            => ['required','string','max:255'],
            'asal_sekolah'    => ['nullable','string','max:255'],
            'no_hp'           => ['nullable','string','max:20'],
        ]);

        Member::create([
            'voting_event_id' => $validated['voting_event_id'],
            'nama'            => $validated['nama'],
            'asal_sekolah'    => $validated['asal_sekolah'] ?? null,
            'no_hp'           => $validated['no_hp'] ?? null,
            'role'            => Member::ROLE_PEMILIH,
        ]);

        return redirect()
            ->route('admin.voters.index')
            ->with('success','Pemilih berhasil ditambahkan');
    }

    /*
    |--------------------------------------------------------------------------
    | SHOW
    |--------------------------------------------------------------------------
    */
    public function show(Member $voter)
    {
        $this->ensureVoter($voter);

        return view('admin.voters.show', compact('voter'));
    }

    /*
    |--------------------------------------------------------------------------
    | EDIT
    |--------------------------------------------------------------------------
    */
    public function edit(Member $voter)
    {
        $this->ensureVoter($voter);

        $events = VotingEvent::latest()->get();

        return view('admin.voters.edit', compact('voter','events'));
    }

    /*
    |--------------------------------------------------------------------------
    | UPDATE
    |--------------------------------------------------------------------------
    */
    public function update(Request $request, Member $voter)
    {
        $this->ensureVoter($voter);

        $validated = $request->validate([
            'voting_event_id' => ['required','exists:voting_events,id'],
            'nama'            => ['required','string','max:255'],
            'asal_sekolah'    => ['nullable','string','max:255'],
            'no_hp'           => ['nullable','string','max:20'],
        ]);

        $voter->update($validated);

        return redirect()
            ->route('admin.voters.index')
            ->with('success','Data pemilih berhasil diperbarui');
    }

    /*
    |--------------------------------------------------------------------------
    | SOFT DELETE
    |--------------------------------------------------------------------------
    */
    public function destroy(Member $voter)
    {
        $this->ensureVoter($voter);

        $voter->delete();

        return back()->with('success','Pemilih dipindahkan ke Trash');
    }

    /*
    |--------------------------------------------------------------------------
    | RESTORE
    |--------------------------------------------------------------------------
    */
    public function restore($id)
    {
        Member::onlyTrashed()
            ->pemilih()
            ->findOrFail($id)
            ->restore();

        return back()->with('success','Pemilih berhasil direstore');
    }

    /*
    |--------------------------------------------------------------------------
    | FORCE DELETE
    |--------------------------------------------------------------------------
    */
    public function forceDelete($id)
    {
        Member::onlyTrashed()
            ->pemilih()
            ->findOrFail($id)
            ->forceDelete();

        return back()->with('success','Pemilih dihapus permanen');
    }

    /*
    |--------------------------------------------------------------------------
    | INTERNAL GUARD
    |--------------------------------------------------------------------------
    */
    private function ensureVoter(Member $member): void
    {
        if (!$member->isPemilih()) {
            abort(404);
        }
    }
}
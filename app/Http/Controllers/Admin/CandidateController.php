<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Member;
use App\Models\VotingEvent;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class CandidateController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | INDEX
    |--------------------------------------------------------------------------
    */
    public function index(Request $request)
    {
        $eventId = $request->get('event');

        $candidates = Member::query()
            ->kandidat()
            ->when($eventId, fn ($q) => $q->event($eventId))
            ->orderBy('nomor_urut')
            ->paginate(10);

        return view('admin.candidates.index', compact('candidates'));
    }

    /*
    |--------------------------------------------------------------------------
    | TRASH
    |--------------------------------------------------------------------------
    */
    public function trash()
    {
        $candidates = Member::onlyTrashed()
            ->kandidat()
            ->orderBy('nomor_urut')
            ->paginate(10);

        return view('admin.candidates.trash', compact('candidates'));
    }

    /*
    |--------------------------------------------------------------------------
    | CREATE
    |--------------------------------------------------------------------------
    */
    public function create()
    {
        $events = VotingEvent::latest()->get();

        return view('admin.candidates.create', compact('events'));
    }

    /*
    |--------------------------------------------------------------------------
    | STORE ⭐ SAFE AUTO NUMBER
    |--------------------------------------------------------------------------
    */
    public function store(Request $request)
    {
        $request->validate([
            'voting_event_id'=>'required|exists:voting_events,id',
            'nama'=>'required|string|max:255',
            'gelar'=>'nullable|string|max:100',
            'jabatan'=>'nullable|string|max:255',
            'asal_sekolah'=>'nullable|string|max:255',
            'no_hp'=>'nullable|string|max:20',
            'foto'=>'nullable|image|max:2048',
        ]);

        $event = VotingEvent::findOrFail($request->voting_event_id);

        DB::transaction(function () use ($request, $event) {

            // LOCK supaya tidak tabrakan nomor
            $lastNumber = Member::where('voting_event_id',$event->id)
                ->kandidat()
                ->lockForUpdate()
                ->max('nomor_urut');

            $nomor = ($lastNumber ?? 0) + 1;

            $foto = null;

            if ($request->hasFile('foto')) {
                $foto = $request->file('foto')
                    ->store('members','public');
            }

            Member::create([
                'voting_event_id'=>$event->id,
                'nama'=>$request->nama,
                'gelar'=>$request->gelar,
                'jabatan'=>$request->jabatan,
                'asal_sekolah'=>$request->asal_sekolah,
                'no_hp'=>$request->no_hp,
                'foto'=>$foto,
                'nomor_urut'=>$nomor,
                'role'=>Member::ROLE_KANDIDAT,
            ]);
        });

        return redirect()
            ->route('admin.candidates.index')
            ->with('success','Kandidat berhasil ditambahkan');
    }

    /*
    |--------------------------------------------------------------------------
    | SHOW
    |--------------------------------------------------------------------------
    */
    public function show(Member $candidate)
    {
        abort_unless($candidate->isKandidat(),404);

        return view('admin.candidates.show',compact('candidate'));
    }

    /*
    |--------------------------------------------------------------------------
    | EDIT
    |--------------------------------------------------------------------------
    */
    public function edit(Member $candidate)
    {
        abort_unless($candidate->isKandidat(),404);

        $events = VotingEvent::latest()->get();

        return view('admin.candidates.edit',compact('candidate','events'));
    }

    /*
    |--------------------------------------------------------------------------
    | UPDATE
    |--------------------------------------------------------------------------
    */
    public function update(Request $request, Member $candidate)
    {
        abort_unless($candidate->isKandidat(),404);

        $request->validate([
            'voting_event_id'=>'required|exists:voting_events,id',
            'nama'=>'required|string|max:255',
            'gelar'=>'nullable|string|max:100',
            'jabatan'=>'nullable|string|max:255',
            'asal_sekolah'=>'nullable|string|max:255',
            'no_hp'=>'nullable|string|max:20',
            'foto'=>'nullable|image|max:2048',
        ]);

        if ($request->hasFile('foto')) {

            if ($candidate->foto) {
                Storage::disk('public')->delete($candidate->foto);
            }

            $candidate->foto =
                $request->file('foto')->store('members','public');
        }

        $candidate->update([
            'voting_event_id'=>$request->voting_event_id,
            'nama'=>$request->nama,
            'gelar'=>$request->gelar,
            'jabatan'=>$request->jabatan,
            'asal_sekolah'=>$request->asal_sekolah,
            'no_hp'=>$request->no_hp,
            'foto'=>$candidate->foto,
        ]);

        return redirect()
            ->route('admin.candidates.index')
            ->with('success','Kandidat berhasil diupdate');
    }

    /*
    |--------------------------------------------------------------------------
    | DELETE ⭐ AUTO REORDER
    |--------------------------------------------------------------------------
    */
    public function destroy(Member $candidate)
    {
        abort_unless($candidate->isKandidat(),404);

        $event = $candidate->event;

        $candidate->delete();

        if ($event) {
            $event->reorderCandidates();
        }

        return back()->with('success','Kandidat dipindahkan ke trash');
    }

    public function restore($id)
    {
        $candidate = Member::onlyTrashed()->findOrFail($id);
        $candidate->restore();

        $candidate->event?->reorderCandidates();

        return back()->with('success','Kandidat berhasil direstore');
    }

    public function forceDelete($id)
    {
        $candidate = Member::onlyTrashed()->findOrFail($id);

        if ($candidate->foto) {
            Storage::disk('public')->delete($candidate->foto);
        }

        $candidate->forceDelete();

        return back()->with('success','Kandidat dihapus permanen');
    }
}
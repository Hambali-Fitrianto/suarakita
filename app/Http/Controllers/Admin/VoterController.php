<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Member;
use App\Models\VotingEvent;
use App\Exports\VoterTemplateExport;
use App\Exports\VoterExport;
use App\Imports\VoterImport;
use Maatwebsite\Excel\Facades\Excel;

class VoterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | INDEX
    |--------------------------------------------------------------------------
    */
    public function index(Request $request)
    {
        $query = Member::pemilih()
            ->with('event')
            ->join('voting_events', 'members.voting_event_id', '=', 'voting_events.id')
            ->select('members.*');

        // Fitur Pencarian Nama
        if ($request->has('search')) {
            $query->where('members.nama', 'like', '%' . $request->search . '%');
        }

        // Fitur Filter per Event
        if ($request->filled('event_id')) {
            $query->where('members.voting_event_id', $request->event_id);
        }

        $voters = $query->orderBy('voting_events.judul', 'asc')
            ->orderBy('members.nama', 'asc')
            ->get();

        $events = VotingEvent::orderBy('judul')->get();
        $trashCount = Member::onlyTrashed()->pemilih()->count();

        return view('admin.voters.index', compact('voters', 'trashCount', 'events'));
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
            'voting_event_id' => ['required', 'exists:voting_events,id'],
            'nama'            => ['required', 'string', 'max:255'],
            'asal_sekolah'    => ['nullable', 'string', 'max:255'],
            'no_hp'           => ['nullable', 'string', 'max:20'],
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
            ->with('success', 'Pemilih berhasil ditambahkan');
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

        return view('admin.voters.edit', compact('voter', 'events'));
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
            'voting_event_id' => ['required', 'exists:voting_events,id'],
            'nama'            => ['required', 'string', 'max:255'],
            'asal_sekolah'    => ['nullable', 'string', 'max:255'],
            'no_hp'           => ['nullable', 'string', 'max:20'],
        ]);

        $voter->update($validated);

        return redirect()
            ->route('admin.voters.index')
            ->with('success', 'Data pemilih berhasil diperbarui');
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

        return back()->with('success', 'Pemilih dipindahkan ke Trash');
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

        return back()->with('success', 'Pemilih berhasil direstore');
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

        return back()->with('success', 'Pemilih dihapus permanen');
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

    /*
    |--------------------------------------------------------------------------
    | EXPORT & IMPORT
    |--------------------------------------------------------------------------
    */
    public function exportTemplate()
    {
        return Excel::download(new VoterTemplateExport, 'template-pemilih.xlsx');
    }

    public function exportData(Request $request)
    {
        // Mengambil event_id dari request URL
        $eventId = $request->event_id;

        // Pastikan hanya mengirim eventId ke constructor
        return Excel::download(new VoterExport($eventId), 'data-pemilih-existing.xlsx');
    }

    public function import(Request $request)
    {
        $request->validate([
            'voting_event_id' => 'required|exists:voting_events,id',
            'file_excel'      => 'required|mimes:xlsx,xls',
        ]);

        Excel::import(new VoterImport($request->voting_event_id), $request->file('file_excel'));

        return back()->with('success', 'Data pemilih berhasil diproses!');
    }
}

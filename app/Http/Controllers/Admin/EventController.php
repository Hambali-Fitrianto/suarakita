<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\VotingEvent;

class EventController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | INDEX (EVENT NORMAL)
    |--------------------------------------------------------------------------
    */
    public function index()
    {
        $events = VotingEvent::latest()->paginate(10);

        $trashCount = VotingEvent::onlyTrashed()->count();

        return view('admin.events.index', compact(
            'events',
            'trashCount'
        ));
    }


    /*
    |--------------------------------------------------------------------------
    | TRASH (SOFT DELETED)
    |--------------------------------------------------------------------------
    */
    public function trash()
    {
        $events = VotingEvent::onlyTrashed()
            ->latest()
            ->paginate(10);

        return view('admin.events.trash', compact('events'));
    }


    /*
    |--------------------------------------------------------------------------
    | CREATE
    |--------------------------------------------------------------------------
    */
    public function create()
    {
        return view('admin.events.create');
    }


    /*
    |--------------------------------------------------------------------------
    | STORE
    |--------------------------------------------------------------------------
    | Event hanya master data
    */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'judul'     => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
        ]);

        VotingEvent::create([
            'judul'     => $validated['judul'],
            'deskripsi' => $validated['deskripsi'] ?? null,
        ]);

        return redirect()
            ->route('admin.events.index')
            ->with('success', 'Event berhasil dibuat');
    }


    /*
    |--------------------------------------------------------------------------
    | SHOW
    |--------------------------------------------------------------------------
    */
    public function show(VotingEvent $event)
    {
        return view('admin.events.show', compact('event'));
    }


    /*
    |--------------------------------------------------------------------------
    | EDIT
    |--------------------------------------------------------------------------
    */
    public function edit(VotingEvent $event)
    {
        return view('admin.events.edit', compact('event'));
    }


    /*
    |--------------------------------------------------------------------------
    | UPDATE
    |--------------------------------------------------------------------------
    */
    public function update(Request $request, VotingEvent $event)
    {
        $validated = $request->validate([
            'judul'     => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
        ]);

        $event->update([
            'judul'     => $validated['judul'],
            'deskripsi' => $validated['deskripsi'] ?? null,
        ]);

        return redirect()
            ->route('admin.events.show', $event)
            ->with('success', 'Event berhasil diperbarui');
    }


    /*
    |--------------------------------------------------------------------------
    | DELETE → SOFT DELETE
    |--------------------------------------------------------------------------
    */
    public function destroy(VotingEvent $event)
    {
        $event->delete();

        return redirect()
            ->route('admin.events.index')
            ->with('success', 'Event dipindahkan ke Trash');
    }


    /*
    |--------------------------------------------------------------------------
    | RESTORE EVENT
    |--------------------------------------------------------------------------
    */
    public function restore($id)
    {
        VotingEvent::onlyTrashed()
            ->findOrFail($id)
            ->restore();

        return redirect()
            ->route('admin.events.trash')
            ->with('success', 'Event berhasil direstore');
    }


    /*
    |--------------------------------------------------------------------------
    | FORCE DELETE (PERMANENT)
    |--------------------------------------------------------------------------
    */
    public function forceDelete($id)
    {
        VotingEvent::onlyTrashed()
            ->findOrFail($id)
            ->forceDelete();

        return redirect()
            ->route('admin.events.trash')
            ->with('success', 'Event dihapus permanen');
    }
}
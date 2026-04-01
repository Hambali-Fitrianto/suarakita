<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\VotingEvent; // Ubah ke VotingEvent
use App\Models\VotingSession;
use App\Models\Token;
use App\Services\Voting\TokenGenerator;

class TokenController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | LIST TOKEN
    |--------------------------------------------------------------------------
    */
    // Ubah parameter dari VotingSession ke VotingEvent agar sesuai dengan Route {event}
    public function index(VotingEvent $event)
    {
        // Ambil token berdasarkan voting_event_id
        $tokens = Token::where('voting_event_id', $event->id)
            ->with(['member', 'session'])
            ->latest()
            ->paginate(20);

        // Tambahkan 'event' ke dalam compact agar tidak error di Blade
        return view('admin.tokens.index', compact('event', 'tokens'));
    }


    /*
    |--------------------------------------------------------------------------
    | GENERATE TOKEN MASSAL
    |--------------------------------------------------------------------------
    */
    public function generate(
        VotingEvent $event, // Sesuaikan juga di sini
        TokenGenerator $generator
    ) {
        // Logika generate tetap menggunakan session jika generator kamu butuh session
        // atau sesuaikan dengan logic service TokenGenerator kamu.
        // Jika butuh session pertama dari event:
        $session = $event->sessions()->first();

        if (!$session) {
            return back()->with('error', 'Belum ada session aktif untuk event ini.');
        }

        $total = $generator->generate($session);

        return back()->with(
            'success',
            "{$total} token berhasil dibuat."
        );
    }
}

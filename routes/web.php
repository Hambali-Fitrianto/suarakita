<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;

/*
|--------------------------------------------------------------------------
| PUBLIC CONTROLLERS
|--------------------------------------------------------------------------
*/
use App\Http\Controllers\Public\TokenController;
use App\Http\Controllers\Public\VoteController;
use App\Http\Controllers\Public\ResultController;

/*
|--------------------------------------------------------------------------
| ADMIN CONTROLLERS
|--------------------------------------------------------------------------
*/
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\EventController;
use App\Http\Controllers\Admin\MemberController;
use App\Http\Controllers\Admin\SessionController;
use App\Http\Controllers\Admin\TokenController as AdminTokenController;
use App\Http\Controllers\Admin\CandidateController;
use App\Http\Controllers\Admin\ChartController;
use App\Http\Controllers\Admin\VoterController;
use App\Http\Controllers\Admin\ResultController as AdminResultController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\EventVoterController;

/*
|--------------------------------------------------------------------------
| PUBLIC AREA — SUARAKITA
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('landing');
})->name('landing');

Route::get('/debug-waktu', function () {
    // Mencari session yang statusnya 'aktif' (Gunakan kolom 'status' sesuai DB)
    $session = \App\Models\VotingSession::where('status', 'aktif')
        ->orWhere(function ($q) {
            $q->where('mulai_at', '<=', now())
                ->where('selesai_at', '>=', now());
        })
        ->first();

    return response()->json([
        'WAKTU_SERVER_SAAT_INI' => now()->toDateTimeString(),
        'TIMEZONE_CONFIG'       => config('app.timezone'),
        'HASIL_DEBUG' => [
            'apakah_session_ketemu' => $session ? 'YA' : 'TIDAK',
            'id_session'            => $session?->id,
            'nama_session'          => $session?->nama_sesi, // FIX: Kolom DB adalah nama_sesi
            'status_di_db'          => $session?->status,   // Sesuai DB: 'aktif'
            'mulai_at'              => $session?->mulai_at,
            'selesai_at'            => $session?->selesai_at,
            'cek_logic_mulai'       => $session ? (now() >= $session->mulai_at) : false,
            'cek_logic_selesai'     => $session ? (now() <= $session->selesai_at) : false,
        ]
    ]);
});

/*
| TOKEN VALIDATION
*/
Route::get('/token', [TokenController::class, 'index'])->name('token.index');
Route::post('/token/verify', [TokenController::class, 'verify'])->name('token.verify');

/*
| VOTING
*/
Route::get('/vote', [VoteController::class, 'index'])->name('vote.index');
Route::post('/vote/submit', [VoteController::class, 'submit'])->name('vote.submit');
Route::get('/vote/success', [VoteController::class, 'success'])->name('vote.success');
Route::get('/vote/{token}', [VoteController::class, 'direct'])->where('token', '[A-Za-z0-9]+')->name('vote.direct');
Route::get('/vote/reset', function () {
    session()->forget('voting_token_id');
    return redirect()->route('landing');
})->name('vote.reset');

/*
| PUBLIC RESULT
*/
Route::prefix('hasil')
    ->name('public.result.')
    ->group(function () {
        Route::get('/', [ResultController::class, 'index'])->name('index');
        Route::get('/{session}', [ResultController::class, 'show'])->name('show');
    });

/*
|--------------------------------------------------------------------------
| DEFAULT DASHBOARD (Laravel Breeze Compatibility)
|--------------------------------------------------------------------------
*/
Route::get('/dashboard', function () {
    return redirect()->route('admin.dashboard');
})->middleware(['auth'])->name('dashboard');

/*
|--------------------------------------------------------------------------
| ADMIN AREA (LOGIN REQUIRED)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Event Trash & Management
    Route::get('/events/trash', [EventController::class, 'trash'])->name('events.trash');
    Route::put('/events/{id}/restore', [EventController::class, 'restore'])->name('events.restore');
    Route::delete('/events/{id}/force-delete', [EventController::class, 'forceDelete'])->name('events.forceDelete');
    Route::resource('events', EventController::class); // Lebih ringkas pakai resource jika memungkinkan

    // Invite Voters
    Route::get('/events/{event}/voters', [EventVoterController::class, 'index'])->name('events.voters.index');
    Route::post('/events/{event}/voters', [EventVoterController::class, 'store'])->name('events.voters.store');

    // Tokens
    Route::get('/events/{event}/tokens', [AdminTokenController::class, 'index'])->name('events.tokens.index');
    Route::post('/events/{event}/tokens/generate', [AdminTokenController::class, 'generate'])->name('events.tokens.generate');
    Route::get('/events/{event}/tokens/export', [AdminTokenController::class, 'export'])->name('events.tokens.export');

    // Candidates
    Route::get('/candidates/trash', [CandidateController::class, 'trash'])->name('candidates.trash');
    Route::put('/candidates/{id}/restore', [CandidateController::class, 'restore'])->name('candidates.restore');
    Route::delete('/candidates/{id}/force-delete', [CandidateController::class, 'forceDelete'])->name('candidates.forceDelete');
    Route::resource('candidates', CandidateController::class);

    // Voters
    Route::get('/voters/trash', [VoterController::class, 'trash'])->name('voters.trash');
    Route::put('/voters/{id}/restore', [VoterController::class, 'restore'])->name('voters.restore');
    Route::delete('/voters/{id}/force-delete', [VoterController::class, 'forceDelete'])->name('voters.forceDelete');
    Route::resource('voters', VoterController::class);

    // Sessions
    Route::get('/sessions/trash', [SessionController::class, 'trash'])->name('sessions.trash');
    Route::put('/sessions/{id}/restore', [SessionController::class, 'restore'])->name('sessions.restore');
    Route::delete('/sessions/{id}/force-delete', [SessionController::class, 'forceDelete'])->name('sessions.forceDelete');
    Route::resource('sessions', SessionController::class);
    Route::post('/sessions/{session}/generate-tokens', [SessionController::class, 'generateTokens'])->name('sessions.tokens.generate');
    Route::get('/sessions/{session}/tokens', [SessionController::class, 'tokens'])->name('sessions.tokens.index');

    // Charts & Results
    Route::get('/chart-data', [ChartController::class, 'data'])->name('chart.data');
    Route::prefix('results')->name('results.')->group(function () {
        Route::get('/', [AdminResultController::class, 'index'])->name('index');
        Route::get('/{session}', [AdminResultController::class, 'show'])->name('show');
    });

    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';

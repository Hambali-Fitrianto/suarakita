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
Route::get('/vote/success', [VoteController::class,'success'])->name('vote.success');
Route::get('/vote/{token}', [VoteController::class,'direct'])->where('token','[A-Za-z0-9]+')->name('vote.direct');
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
| DEFAULT DASHBOARD (IMPORTANT - BREEZE COMPATIBILITY)
|--------------------------------------------------------------------------
| Jangan dihapus.
| Ini supaya Laravel Breeze tetap redirect ke "dashboard"
| tapi kita arahkan ke admin dashboard.
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

    /*
    |--------------------------------
    | DASHBOARD
    |--------------------------------
    */
    Route::get('/dashboard', [DashboardController::class, 'index'])
    ->name('dashboard');

        /*
    |--------------------------------
    | EVENT TRASH (SOFT DELETE)
    |--------------------------------
    */
    Route::get('/events/trash', [EventController::class, 'trash'])->name('events.trash');
    Route::put('/events/{id}/restore', [EventController::class, 'restore'])->name('events.restore');
    Route::delete('/events/{id}/force-delete', [EventController::class, 'forceDelete'])->name('events.forceDelete');

    /*
    |--------------------------------
    | EVENT VOTING
    |--------------------------------
    */
    Route::get('/events', [EventController::class, 'index'])->name('events.index');
    Route::get('/events/create', [EventController::class, 'create'])->name('events.create');
    Route::post('/events/store', [EventController::class, 'store'])->name('events.store');
    Route::get('/events/{event}', [EventController::class, 'show'])->name('events.show');
    Route::get('/events/{event}/edit', [EventController::class, 'edit'])->name('events.edit');
    Route::put('/events/{event}/update', [EventController::class, 'update'])->name('events.update');
    Route::delete('/events/{event}/delete', [EventController::class, 'destroy'])->name('events.destroy');

    /*
    |--------------------------------
    | INVITE VOTERS (NEW)
    |--------------------------------
    */
    Route::get('/events/{event}/voters', [EventVoterController::class,'index'])->name('events.voters.index');
    Route::post('/events/{event}/voters', [EventVoterController::class,'store'])->name('events.voters.store');

    /*
    |--------------------------------
    | TOKEN MANAGEMENT FOR EVENTS
    |--------------------------------
    */
    Route::get('/events/{event}/tokens', [AdminTokenController::class, 'index'])->name('events.tokens.index');
    Route::post('/events/{event}/tokens/generate', [AdminTokenController::class, 'generate'])->name('events.tokens.generate');
    Route::get('/events/{event}/tokens/export', [AdminTokenController::class, 'export'])->name('events.tokens.export');

    /*
    |--------------------------------
    | KANDIDAT
    |--------------------------------
    */
    Route::get('/candidates', [CandidateController::class, 'index'])->name('candidates.index');
    Route::get('/candidates/trash', [CandidateController::class, 'trash'])->name('candidates.trash');
    Route::put('/candidates/{id}/restore', [CandidateController::class, 'restore'])->name('candidates.restore');
    Route::delete('/candidates/{id}/force-delete', [CandidateController::class, 'forceDelete'])->name('candidates.forceDelete');
    Route::get('/candidates/create', [CandidateController::class, 'create'])->name('candidates.create');
    Route::post('/candidates/store', [CandidateController::class, 'store'])->name('candidates.store');
    Route::get('/candidates/{candidate}', [CandidateController::class, 'show'])->name('candidates.show');
    Route::get('/candidates/{candidate}/edit', [CandidateController::class, 'edit'])->name('candidates.edit');
    Route::put('/candidates/{candidate}/update', [CandidateController::class, 'update'])->name('candidates.update');
    Route::delete('/candidates/{candidate}/delete', [CandidateController::class, 'destroy'])->name('candidates.destroy');

    /*
    |--------------------------------
    | PEMILIH (members is_pemilih)
    |--------------------------------
    */
    Route::get('/voters', [VoterController::class, 'index'])->name('voters.index');
    Route::get('/voters/trash', [VoterController::class, 'trash'])->name('voters.trash');
    Route::put('/voters/{id}/restore', [VoterController::class, 'restore'])->name('voters.restore');
    Route::delete('/voters/{id}/force-delete', [VoterController::class, 'forceDelete'])->name('voters.forceDelete');
    Route::get('/voters/create', [VoterController::class, 'create'])->name('voters.create');
    Route::post('/voters/store', [VoterController::class, 'store'])->name('voters.store');
    Route::get('/voters/{voter}', [VoterController::class, 'show'])->name('voters.show');
    Route::get('/voters/{voter}/edit', [VoterController::class, 'edit'])->name('voters.edit');
    Route::put('/voters/{voter}/update', [VoterController::class, 'update'])->name('voters.update');
    Route::delete('/voters/{voter}/delete', [VoterController::class, 'destroy'])->name('voters.destroy');

    /*
    |--------------------------------
    | VOTING SESSION
    |--------------------------------
    */

    Route::get('/sessions/trash', [SessionController::class, 'trash'])->name('sessions.trash');
    Route::put('/sessions/{id}/restore', [SessionController::class, 'restore'])->name('sessions.restore');
    Route::delete('/sessions/{id}/force-delete', [SessionController::class, 'forceDelete'])->name('sessions.forceDelete');
    Route::get('/sessions', [SessionController::class, 'index'])->name('sessions.index');
    Route::get('/sessions/create', [SessionController::class, 'create'])->name('sessions.create');
    Route::post('/sessions/store', [SessionController::class, 'store'])->name('sessions.store');
    Route::get('/sessions/{session}', [SessionController::class, 'show'])->name('sessions.show');
    Route::get('/sessions/{session}/edit', [SessionController::class, 'edit'])->name('sessions.edit');
    Route::put('/sessions/{session}/update', [SessionController::class, 'update'])->name('sessions.update');
    Route::delete('/sessions/{session}/delete', [SessionController::class, 'destroy'])->name('sessions.destroy');
    
    Route::post('/sessions/{session}/generate-tokens', [SessionController::class, 'generateTokens'])->name('sessions.tokens.generate');
    Route::get('/sessions/{session}/tokens', [SessionController::class, 'tokens'])->name('sessions.tokens.index');
    
    /*
    |--------------------------------
    | CHART REALTIME
    |--------------------------------
    */
    Route::get('/chart-data', [ChartController::class, 'data'])->name('chart.data');

    /*
    |--------------------------------
    | HASIL VOTING (ADMIN)
    |--------------------------------
    */
    Route::prefix('results')
        ->name('results.')
        ->group(function () {
            Route::get('/', [AdminResultController::class, 'index'])->name('index');
            Route::get('/{session}', [AdminResultController::class, 'show'])->name('show');
        });

    /*
    |--------------------------------
    | PROFILE
    |--------------------------------
    */
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

});

/*
|--------------------------------------------------------------------------
| AUTH ROUTES (Laravel Breeze)
|--------------------------------------------------------------------------
*/

require __DIR__.'/auth.php';
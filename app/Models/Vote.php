<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Vote extends Model
{
    /*
    |--------------------------------------------------------------------------
    | MASS ASSIGNMENT
    |--------------------------------------------------------------------------
    */
    protected $fillable = [
        'voting_event_id',
        'voting_session_id',
        'member_id',     // voter
        'candidate_id',  // kandidat
    ];

    /*
    |--------------------------------------------------------------------------
    | CASTS
    |--------------------------------------------------------------------------
    */
    protected $casts = [
        'voting_event_id'   => 'integer',
        'voting_session_id' => 'integer',
        'member_id'         => 'integer',
        'candidate_id'      => 'integer',
    ];

    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */

    public function event()
    {
        return $this->belongsTo(VotingEvent::class, 'voting_event_id');
    }

    public function session()
    {
        return $this->belongsTo(VotingSession::class, 'voting_session_id');
    }

    /**
     * Pemilih
     */
    public function voter()
    {
        return $this->belongsTo(Member::class, 'member_id')
            ->withTrashed();
    }

    /**
     * Kandidat
     */
    public function candidate()
    {
        return $this->belongsTo(Member::class, 'candidate_id')
            ->withTrashed();
    }

    /*
    |--------------------------------------------------------------------------
    | SCOPES (HELPER)
    |--------------------------------------------------------------------------
    */

    public function scopeForEvent($query, int $eventId)
    {
        return $query->where('voting_event_id', $eventId);
    }

    public function scopeForSession($query, int $sessionId)
    {
        return $query->where('voting_session_id', $sessionId);
    }
}
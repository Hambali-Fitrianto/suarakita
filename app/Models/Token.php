<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Token extends Model
{
    protected $table = 'tokens';

    protected $fillable = [
        'voting_event_id',
        'voting_session_id',
        'member_id',
        'token',
        'is_used',
        'used_at',
    ];

    protected $casts = [
        'is_used' => 'boolean',
        'used_at' => 'datetime',
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

    public function member()
    {
        return $this->belongsTo(Member::class, 'member_id');
    }

    /*
    |--------------------------------------------------------------------------
    | SCOPES
    |--------------------------------------------------------------------------
    */

    public function scopeAvailable($query)
    {
        return $query->where('is_used', false);
    }

    public function scopeUsed($query)
    {
        return $query->where('is_used', true);
    }

    /*
    |--------------------------------------------------------------------------
    | HELPERS
    |--------------------------------------------------------------------------
    */

    public function markAsUsed(): void
    {
        $this->update([
            'is_used' => true,
            'used_at' => now(),
        ]);
    }

    public function isAvailable(): bool
    {
        return !$this->is_used;
    }

    public function votingLink(): string
    {
        return route('vote.direct', $this->token);
    }

    public function statusLabel(): string
    {
        return $this->is_used
            ? 'Digunakan'
            : 'Belum Digunakan';
    }
}
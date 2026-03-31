<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Member extends Model
{
    use SoftDeletes;

    protected $table = 'members';

    /*
    |--------------------------------------------------------------------------
    | MASS ASSIGNMENT
    |--------------------------------------------------------------------------
    */
    protected $fillable = [
        'voting_event_id',
        'nama',
        'gelar',
        'jabatan',
        'asal_sekolah',
        'no_hp',
        'foto',
        'nomor_urut',
        'role',
    ];

    /*
    |--------------------------------------------------------------------------
    | ROLE CONSTANT ⭐ (ANTI TYPO)
    |--------------------------------------------------------------------------
    */
    public const ROLE_KANDIDAT = 'kandidat';
    public const ROLE_PEMILIH  = 'pemilih';


    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */

    /**
     * Member milik event
     */
    public function event()
    {
        return $this->belongsTo(VotingEvent::class, 'voting_event_id');
    }

    /**
     * Vote yang dilakukan member (sebagai pemilih)
     */
    public function votes()
    {
        return $this->hasMany(Vote::class, 'member_id');
    }

    /**
     * Vote yang diterima (jika kandidat)
     */
    public function receivedVotes()
    {
        return $this->hasMany(Vote::class, 'candidate_id');
    }

    /**
     * Token voting milik member
     */
    public function tokens()
    {
        return $this->hasMany(Token::class, 'member_id');
    }


    /*
    |--------------------------------------------------------------------------
    | QUERY SCOPES ⭐⭐⭐
    |--------------------------------------------------------------------------
    */

    /**
     * Kandidat saja
     */
    public function scopeKandidat($query)
    {
        return $query->where('role', self::ROLE_KANDIDAT);
    }

    /**
     * Pemilih saja
     */
    public function scopePemilih($query)
    {
        return $query->where('role', self::ROLE_PEMILIH);
    }

    /**
     * Filter event workspace
     */
    public function scopeEvent($query, int $eventId)
    {
        return $query->where('voting_event_id', $eventId);
    }


    /*
    |--------------------------------------------------------------------------
    | ACCESSORS / HELPERS
    |--------------------------------------------------------------------------
    */

    /**
     * Nama lengkap
     */
    public function namaLengkap(): string
    {
        return trim($this->nama . ' ' . ($this->gelar ?? ''));
    }

    /**
     * Apakah kandidat
     */
    public function isKandidat(): bool
    {
        return $this->role === self::ROLE_KANDIDAT;
    }

    /**
     * Apakah pemilih
     */
    public function isPemilih(): bool
    {
        return $this->role === self::ROLE_PEMILIH;
    }


    /*
    |--------------------------------------------------------------------------
    | ONE PERSON ONE VOTE ENGINE ⭐⭐⭐
    |--------------------------------------------------------------------------
    */

    /**
     * Cek apakah member sudah voting di event
     */
    public function hasVotedInEvent(int $eventId): bool
    {
        return $this->votes()
            ->where('voting_event_id', $eventId)
            ->exists();
    }


    /*
    |--------------------------------------------------------------------------
    | STATISTIC HELPERS (BONUS PRO)
    |--------------------------------------------------------------------------
    */

    /**
     * Total vote diterima kandidat
     */
    public function totalVotes(): int
    {
        return $this->receivedVotes()->count();
    }
}
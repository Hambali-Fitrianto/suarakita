<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;

class VotingSession extends Model
{
    use SoftDeletes;

    protected $table = 'voting_sessions';

    /*
    |--------------------------------------------------------------------------
    | MASS ASSIGNMENT
    |--------------------------------------------------------------------------
    */
    protected $fillable = [
        'voting_event_id',
        'nama_sesi',
        'urutan',
        'status',
        'mulai_at',
        'selesai_at',
        'jumlah_perpanjangan',
    ];

    /*
    |--------------------------------------------------------------------------
    | CASTS
    |--------------------------------------------------------------------------
    */
    protected $casts = [
        'mulai_at'   => 'datetime',
        'selesai_at' => 'datetime',
    ];

    /*
    |--------------------------------------------------------------------------
    | APPENDS (AMAN - TIDAK WAJIB)
    |--------------------------------------------------------------------------
    */
    protected $appends = [
        'computed_status',
    ];

    /*
    |--------------------------------------------------------------------------
    | STATUS CONSTANT
    |--------------------------------------------------------------------------
    */
    const STATUS_DRAFT   = 'draft';
    const STATUS_AKTIF   = 'aktif';
    const STATUS_SELESAI = 'selesai';
    const STATUS_JEDA    = 'jeda';

    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */

    public function event()
    {
        return $this->belongsTo(VotingEvent::class, 'voting_event_id');
    }

    public function votes()
    {
        return $this->hasMany(Vote::class, 'voting_session_id');
    }

    public function tokens()
    {
        return $this->hasMany(Token::class, 'voting_session_id');
    }

    /*
    |--------------------------------------------------------------------------
    | COMPUTED STATUS ENGINE ⭐⭐⭐
    |--------------------------------------------------------------------------
    */

    public function getComputedStatusAttribute(): string
    {
        if ($this->status === self::STATUS_JEDA) {
            return self::STATUS_JEDA;
        }

        if (!$this->mulai_at || !$this->selesai_at) {
            return self::STATUS_DRAFT;
        }

        $now = now();

        if ($now->lt($this->mulai_at)) {
            return self::STATUS_DRAFT;
        }

        if ($now->between($this->mulai_at, $this->selesai_at)) {
            return self::STATUS_AKTIF;
        }

        if ($now->gt($this->selesai_at)) {
            return self::STATUS_SELESAI;
        }

        return self::STATUS_DRAFT;
    }

    /*
    |--------------------------------------------------------------------------
    | HELPERS
    |--------------------------------------------------------------------------
    */

    public function isDraft(): bool
    {
        return $this->computed_status === self::STATUS_DRAFT;
    }

    public function isAktif(): bool
    {
        return $this->computed_status === self::STATUS_AKTIF;
    }

    public function sudahSelesai(): bool
    {
        return $this->computed_status === self::STATUS_SELESAI;
    }

    public function isJeda(): bool
    {
        return $this->computed_status === self::STATUS_JEDA;
    }

    /*
    |--------------------------------------------------------------------------
    | STATISTICS HELPERS ⭐ (OPTIMIZED)
    |--------------------------------------------------------------------------
    */

    public function totalToken(): int
    {
        return $this->tokens_count
            ?? $this->tokens()->count();
    }

    public function tokenTerpakai(): int
    {
        return $this->tokens()->where('is_used', true)->count();
    }

    public function tokenTersisa(): int
    {
        return $this->tokens()->where('is_used', false)->count();
    }

    public function totalVote(): int
    {
        return $this->votes_count
            ?? $this->votes()->count();
    }

    /*
    |--------------------------------------------------------------------------
    | ACTIVE SESSION RESOLVER ⭐⭐⭐ (OPTIMIZED)
    |--------------------------------------------------------------------------
    */

    public static function activeForEvent(int $eventId): ?self
    {
        return self::where('voting_event_id', $eventId)
            ->orderBy('urutan')
            ->get()
            ->first(function ($session) {
                return $session->isAktif();
            });
    }
}
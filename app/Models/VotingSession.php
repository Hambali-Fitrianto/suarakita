<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;

class VotingSession extends Model
{
    use SoftDeletes;

    protected $table = 'voting_sessions';

    protected $fillable = [
        'voting_event_id',
        'nama_sesi',
        'urutan',
        'status',
        'mulai_at',
        'selesai_at',
        'jumlah_perpanjangan',
    ];

    protected $casts = [
        'mulai_at'   => 'datetime',
        'selesai_at' => 'datetime',
    ];

    protected $appends = [
        'computed_status',
    ];

    const STATUS_DRAFT   = 'draft';
    const STATUS_AKTIF   = 'aktif';
    const STATUS_SELESAI = 'selesai';
    const STATUS_JEDA    = 'jeda';

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
    | COMPUTED STATUS ENGINE ⭐ FIXED
    |--------------------------------------------------------------------------
    */
    public function getComputedStatusAttribute(): string
    {
        // PERBAIKAN: Jika admin sudah set 'aktif' atau 'selesai' di DB, jangan di-override pakai waktu.
        // Ini biar status di production gak balik-balik ke draft terus.
        if (in_array($this->status, [self::STATUS_AKTIF, self::STATUS_SELESAI, self::STATUS_JEDA])) {
            return $this->status;
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

    public function totalToken(): int
    {
        return $this->tokens_count ?? $this->tokens()->count();
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
        return $this->votes_count ?? $this->votes()->count();
    }

    /*
    |--------------------------------------------------------------------------
    | ACTIVE SESSION RESOLVER ⭐ OPTIMIZED
    |--------------------------------------------------------------------------
    */
    public static function activeForEvent(int $eventId): ?self
    {
        // Prioritas 1: Cari yang statusnya 'aktif' langsung di database
        $direct = self::where('voting_event_id', $eventId)
            ->where('status', self::STATUS_AKTIF)
            ->orderBy('urutan')
            ->first();

        if ($direct) return $direct;

        // Prioritas 2: Cari berdasarkan logic waktu (jika status masih draft di DB)
        return self::where('voting_event_id', $eventId)
            ->orderBy('urutan')
            ->get()
            ->first(function ($session) {
                return $session->isAktif();
            });
    }
}

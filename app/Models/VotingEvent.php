<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class VotingEvent extends Model
{
    use SoftDeletes;

    protected $table = 'voting_events';

    protected $fillable = [
        'judul',
        'deskripsi',
        'kode_event',
    ];

    /*
    |--------------------------------------------------------------------------
    | AUTO EVENT CODE
    |--------------------------------------------------------------------------
    */
    protected static function booted()
    {
        static::creating(function ($event) {

            if (!$event->kode_event) {

                $prefix = collect(explode(' ', $event->judul))
                    ->take(3)
                    ->map(fn ($w) => strtoupper(substr($w, 0, 1)))
                    ->implode('');

                $year = now()->format('y');

                $event->kode_event = $prefix.$year;
            }
        });
    }

    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */

    public function members()
    {
        return $this->hasMany(Member::class,'voting_event_id');
    }

    public function kandidat()
    {
        return $this->members()
            ->where('role', Member::ROLE_KANDIDAT);
    }

    public function pemilih()
    {
        return $this->members()
            ->where('role', Member::ROLE_PEMILIH);
    }

    public function sessions()
    {
        return $this->hasMany(VotingSession::class,'voting_event_id');
    }

    /*
    |--------------------------------------------------------------------------
    | AUTO REORDER CANDIDATES
    |--------------------------------------------------------------------------
    */

    public function reorderCandidates(): void
    {
        $this->kandidat()
            ->orderBy('nomor_urut')
            ->get()
            ->values()
            ->each(function ($candidate, $index) {
                $candidate->updateQuietly([
                    'nomor_urut' => $index + 1
                ]);
            });
    }
}
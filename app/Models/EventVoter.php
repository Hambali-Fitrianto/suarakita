<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EventVoter extends Model
{
    protected $fillable = [
        'voting_event_id',
        'member_id',
    ];

    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */

    public function event()
    {
        return $this->belongsTo(VotingEvent::class,'voting_event_id');
    }

    public function member()
    {
        return $this->belongsTo(Member::class,'member_id');
    }
}
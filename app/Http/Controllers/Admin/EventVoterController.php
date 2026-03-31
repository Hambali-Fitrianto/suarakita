<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\VotingEvent;
use App\Models\Member;
use App\Models\EventVoter;
use App\Models\Token;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class EventVoterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | PAGE INVITE
    |--------------------------------------------------------------------------
    */
    public function index(VotingEvent $event)
    {
        $members = Member::pemilih()
            ->orderBy('nama')
            ->get();

        $invited = EventVoter::where('voting_event_id',$event->id)
            ->pluck('member_id')
            ->toArray();

        return view('admin.events.invite-voters', compact(
            'event',
            'members',
            'invited'
        ));
    }


    /*
    |--------------------------------------------------------------------------
    | STORE INVITE
    |--------------------------------------------------------------------------
    */
    public function store(Request $request, VotingEvent $event)
    {
        $memberIds = $request->members ?? [];

        foreach ($memberIds as $memberId) {

            EventVoter::firstOrCreate([
                'voting_event_id' => $event->id,
                'member_id'       => $memberId,
            ]);

            /*
            | AUTO TOKEN GENERATE
            */
            Token::firstOrCreate(
                [
                    'voting_event_id' => $event->id,
                    'member_id'       => $memberId,
                ],
                [
                    'voting_session_id' => 1,
                    'token' => Str::uuid(),
                ]
            );
        }

        return back()->with('success','Pemilih berhasil di-invite');
    }
}
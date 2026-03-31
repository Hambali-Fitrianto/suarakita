<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('votes', function (Blueprint $table) {

            $table->id();

            /*
            |--------------------------------------------------------------------------
            | WORKSPACE RELATION
            |--------------------------------------------------------------------------
            */
            $table->unsignedBigInteger('voting_event_id');
            $table->unsignedBigInteger('voting_session_id');

            /*
            |--------------------------------------------------------------------------
            | VOTER (SIAPA YANG MEMILIH) ⭐
            |--------------------------------------------------------------------------
            */
            $table->unsignedBigInteger('member_id');

            /*
            |--------------------------------------------------------------------------
            | TOKEN (AKSES VOTING)
            |--------------------------------------------------------------------------
            */
            $table->unsignedBigInteger('token_id')->unique();

            /*
            |--------------------------------------------------------------------------
            | KANDIDAT DIPILIH
            |--------------------------------------------------------------------------
            */
            $table->unsignedBigInteger('candidate_id');

            $table->timestamps();

            /*
            |--------------------------------------------------------------------------
            | ONE PERSON ONE VOTE LOCK ⭐⭐⭐
            |--------------------------------------------------------------------------
            */
            $table->unique(['voting_event_id', 'member_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('votes');
    }
};
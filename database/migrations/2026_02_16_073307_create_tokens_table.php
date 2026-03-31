<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tokens', function (Blueprint $table) {

            $table->id();

            /*
            |--------------------------------------------------------------------------
            | WORKSPACE RELATION
            |--------------------------------------------------------------------------
            | Token selalu milik:
            | - Event
            | - Session (putaran)
            | - Member (pemilih)
            */
            $table->unsignedBigInteger('voting_event_id')->index();
            $table->unsignedBigInteger('voting_session_id')->index();
            $table->unsignedBigInteger('member_id')->index();

            /*
            |--------------------------------------------------------------------------
            | TOKEN VALUE
            |--------------------------------------------------------------------------
            */
            $table->string('token', 20)->unique();

            /*
            |--------------------------------------------------------------------------
            | STATUS PEMAKAIAN
            |--------------------------------------------------------------------------
            */
            $table->boolean('is_used')->default(false);
            $table->timestamp('used_at')->nullable();

            $table->timestamps();

            /*
            |--------------------------------------------------------------------------
            | SECURITY LOCK ⭐⭐⭐
            |--------------------------------------------------------------------------
            | 1 member hanya boleh punya 1 token
            | dalam 1 session voting
            */
            $table->unique([
                'voting_session_id',
                'member_id'
            ], 'unique_member_session_token');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tokens');
    }
};
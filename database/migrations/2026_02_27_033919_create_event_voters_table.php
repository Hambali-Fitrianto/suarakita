<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('event_voters', function (Blueprint $table) {

            $table->id();

            $table->unsignedBigInteger('voting_event_id');
            $table->unsignedBigInteger('member_id');

            $table->timestamps();

            // tidak boleh invite dua kali
            $table->unique([
                'voting_event_id',
                'member_id'
            ]);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('event_voters');
    }
};
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('members', function (Blueprint $table) {

            $table->id();

            /*
            |--------------------------------------------------------------------------
            | RELATION
            |--------------------------------------------------------------------------
            | Member milik Event (workspace voting)
            */
            $table->unsignedBigInteger('voting_event_id');

            /*
            |--------------------------------------------------------------------------
            | DATA PRIBADI
            |--------------------------------------------------------------------------
            */
            $table->string('nama');
            $table->string('gelar')->nullable();
            $table->string('jabatan')->nullable();
            $table->string('asal_sekolah')->nullable();
            $table->string('no_hp')->nullable();
            $table->string('foto')->nullable();

            /*
            |--------------------------------------------------------------------------
            | KANDIDAT ONLY
            |--------------------------------------------------------------------------
            */
            $table->integer('nomor_urut')->nullable();

            /*
            |--------------------------------------------------------------------------
            | ROLE ⭐ (STRING - FLEXIBLE)
            |--------------------------------------------------------------------------
            | kandidat
            | pemilih
            */
            $table->string('role')->index();

            /*
            |--------------------------------------------------------------------------
            | TIMESTAMPS
            |--------------------------------------------------------------------------
            */
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('members');
    }
};
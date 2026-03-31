<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('voting_sessions', function (Blueprint $table) {
            $table->id();
            // relasi (tanpa foreign key)
            $table->unsignedBigInteger('voting_event_id');

            $table->string('nama_sesi');
            $table->integer('urutan')->default(1);

            // status fleksibel
            $table->string('status')->default('draft');
            /*
                draft
                aktif
                jeda
                selesai
            */

            // waktu voting
            $table->timestamp('mulai_at')->nullable();
            $table->timestamp('selesai_at')->nullable();

            // total perpanjangan (tracking admin)
            $table->integer('jumlah_perpanjangan')->default(0);

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('voting_sessions');
    }
};

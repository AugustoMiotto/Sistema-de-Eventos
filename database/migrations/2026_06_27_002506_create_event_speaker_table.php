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
        Schema::create('event_speaker', function (Blueprint $table) {
            $table->id();

            // Conecta com o Evento (se apagar o evento, apaga a ligação)
            $table->foreignId('event_id')->constrained()->cascadeOnDelete();

            // Conecta com o Palestrante (se apagar o palestrante, apaga a ligação)
            $table->foreignId('speaker_id')->constrained()->cascadeOnDelete();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('event_speaker');
    }
};

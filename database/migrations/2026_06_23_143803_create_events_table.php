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
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->foreignId('promoter_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('speaker_id')->nullable()->constrained('speakers')->onDelete('set null');
            $table->string('name');
            $table->text('description')->nullable();
            $table->foreignId('category_id')->constrained('categories')->onDelete('cascade');
            $table->string('location');
            $table->string('target_audience');
            $table->integer('max_slots');
            $table->boolean('is_free')->default(true);
            $table->decimal('price', 8, 2)->default(0.00);
            $table->dateTime('start_at'); // Data e hora unificados
            $table->string('cover_photo', 2048)->nullable(); // Foto de Capa do Evento
            $table->enum('status', ['New', 'In Progress', 'Finished'])->default('New');
            $table->boolean('manage_registrations')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};

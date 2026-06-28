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
        Schema::create('speakers', function (Blueprint $table) {
            $table->id();

            // Campos Obrigatórios
            $table->string('name');
            $table->string('email')->unique(); // Proteção contra duplicidade
            $table->string('phone'); // Visível apenas para gestão interna
            $table->string('expertise_area'); // Titulação / Área de atuação
            $table->string('institution'); // Instituição / Empresa
            $table->text('bio'); // text() permite parágrafos maiores que o string()

            // A foto geralmente deixamos nullable no banco de dados,
            // mas tornamos obrigatória na validação do formulário HTML/Controller.
            $table->string('profile_photo_path')->nullable();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('speakers');
    }
};

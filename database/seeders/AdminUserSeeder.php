<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'augusto.admin@sertao.ifrs.edu.br'], // E-mail institucional (sem a palavra "aluno")
            [
                'name' => 'Augusto Morandini Miotto',
                'password' => Hash::make('senha123'), // Senha padrão para testes
                'is_promoter' => true,

                'email_verified_at' => now(), // Já deixa o e-mail como verificado
            ]
        );

    }
}

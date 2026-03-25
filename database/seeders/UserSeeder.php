<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Criar um usuário de teste
        User::firstOrCreate(
            ['email' => 'admin@senhas.com'],
            [
                'name' => 'Administrador',
                'password' => Hash::make('password123'),
            ]
        );

        // Criar usuário adicional
        User::firstOrCreate(
            ['email' => 'teste@senhas.com'],
            [
                'name' => 'Usuário Teste',
                'password' => Hash::make('password123'),
            ]
        );
    }
}

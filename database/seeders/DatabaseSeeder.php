<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Verifica se já existe um Admin, se não, cria um padrão
        if (User::count() === 0) {
            User::create([
                'name' => 'Admin Master',
                'email' => 'admin@admin.com',
                'password' => bcrypt('12345678'),
                'role' => 'admin'
            ]);
        }
    }
}

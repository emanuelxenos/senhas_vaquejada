<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Categoria;
use App\Models\Setting;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Admin principal
        if (User::count() === 0) {
            User::create([
                'name' => 'Admin Master',
                'email' => 'admin@admin.com',
                'password' => bcrypt('12345678'),
                'role' => 'admin'
            ]);
        }

        // Criar usuários do UserSeeder
        $this->call(UserSeeder::class);

        // Criar Categorias padrão
        $categorias = [
            ['nome' => 'Aberto', 'preco_senha' => 200.00, 'limite_senhas_por_vaqueiro' => 2, 'minimo_bois_sucesso' => 2],
            ['nome' => 'Aspirante', 'preco_senha' => 150.00, 'limite_senhas_por_vaqueiro' => 2, 'minimo_bois_sucesso' => 2],
            ['nome' => 'Jovem', 'preco_senha' => 80.00, 'limite_senhas_por_vaqueiro' => 1, 'minimo_bois_sucesso' => 2],
            ['nome' => 'Feminina', 'preco_senha' => 100.00, 'limite_senhas_por_vaqueiro' => 1, 'minimo_bois_sucesso' => 2],
        ];

        foreach ($categorias as $cat) {
            Categoria::updateOrCreate(['nome' => $cat['nome']], $cat);
        }

        // Criar configurações padrão de bois por tipo de senha
        Setting::setValue('senha.bois_amador', '3');
        Setting::setValue('senha.bois_profissional', '2');
        Setting::setValue('senha.bois_boi_tv', '2');
        
        // Data limite nula por padrão (sem restrição até que seja configurado)
        if (!Setting::getValue('senha.data_limite_boi_tv')) {
            Setting::setValue('senha.data_limite_boi_tv', '');
        }

        // Outras configurações padrão se não existirem
        if (!Setting::getValue('parque.nome')) {
            Setting::setValue('parque.nome', 'Parque de Vaquejada Padrão');
        }
        if (!Setting::getValue('payment.gateway')) {
            Setting::setValue('payment.gateway', 'none');
        }
    }
}

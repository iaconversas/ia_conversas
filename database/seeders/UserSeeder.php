<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Criar usuário admin padrão
        $adminUser = User::firstOrCreate(
            ['email' => 'admin@iaconversas.com'],
            [
                'name' => 'Administrador',
                'email' => 'admin@iaconversas.com',
                'password' => Hash::make('admin123'),
                'email_verified_at' => now(),
            ]
        );

        // Verificar se o role admin existe e atribuir ao usuário
        $adminRole = Role::where('name', 'admin')->first();
        if ($adminRole && !$adminUser->hasRole('admin')) {
            $adminUser->assignRole('admin');
        }

        // Criar usuário cliente de exemplo
        $clientUser = User::firstOrCreate(
            ['email' => 'cliente@exemplo.com'],
            [
                'name' => 'Cliente Exemplo',
                'email' => 'cliente@exemplo.com',
                'password' => Hash::make('cliente123'),
                'email_verified_at' => now(),
            ]
        );

        // Verificar se o role client existe e atribuir ao usuário
        $clientRole = Role::where('name', 'client')->first();
        if ($clientRole && !$clientUser->hasRole('client')) {
            $clientUser->assignRole('client');
        }

        $this->command->info('Usuários criados com sucesso:');
        $this->command->info('Admin: admin@iaconversas.com / admin123');
        $this->command->info('Cliente: cliente@exemplo.com / cliente123');
    }
}

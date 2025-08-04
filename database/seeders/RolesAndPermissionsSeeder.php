<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // Criar permissões
        $permissions = [
            // Permissões administrativas
            'manage users',
            'manage tenants',
            'view admin dashboard',
            'manage system settings',
            'view reports',
            
            // Permissões de cliente
            'view client dashboard',
            'manage own profile',
            'view own data',
            'create content',
            'edit own content',
            'delete own content',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Criar role de Administrador
        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $adminRole->syncPermissions([
            'manage users',
            'manage tenants',
            'view admin dashboard',
            'manage system settings',
            'view reports',
            'view client dashboard', // Admin pode ver dashboard do cliente também
            'manage own profile',
            'view own data',
        ]);

        // Criar role de Cliente
        $clientRole = Role::firstOrCreate(['name' => 'client']);
        $clientRole->syncPermissions([
            'view client dashboard',
            'manage own profile',
            'view own data',
            'create content',
            'edit own content',
            'delete own content',
        ]);

        // Criar usuário administrador padrão
        $admin = User::firstOrCreate(
            ['email' => 'admin@iaconversas.com'],
            [
                'name' => 'Administrador',
                'password' => Hash::make('admin123'),
                'email_verified_at' => now(),
            ]
        );

        if (!$admin->hasRole('admin')) {
            $admin->assignRole($adminRole);
        }

        // Criar um cliente de exemplo
        $client = User::firstOrCreate(
            ['email' => 'cliente@exemplo.com'],
            [
                'name' => 'Cliente Exemplo',
                'password' => Hash::make('cliente123'),
                'email_verified_at' => now(),
            ]
        );

        if (!$client->hasRole('client')) {
            $client->assignRole($clientRole);
        }

        $this->command->info('Roles, permissões e usuários criados com sucesso!');
        $this->command->info('Admin: admin@iaconversas.com / admin123');
        $this->command->info('Cliente: cliente@exemplo.com / cliente123');
    }
}

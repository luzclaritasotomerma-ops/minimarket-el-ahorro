<?php

namespace Database\Seeders;


use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class RolesYUsuariosSeeder extends Seeder
{
    public function run(): void
    {
        // Crear los roles
        $administrador = Role::firstOrCreate(
            ['name' => 'Administrador'],
            ['description' => 'Acceso completo al sistema']
        );

        $vendedor = Role::firstOrCreate(
            ['name' => 'Vendedor'],
            ['description' => 'Acceso al registro de ventas y clientes']
        );

        // Crear usuario administrador
        User::updateOrCreate(
            ['email' => 'admin@minimarket.test'],
            [
                'name' => 'Administrador',
                'password' => Hash::make('Admin12345!'),
                'role_id' => $administrador->id,
            ]
        );

        // Crear usuario vendedor
        User::updateOrCreate(
            ['email' => 'vendedor@minimarket.test'],
            [
                'name' => 'Vendedor',
                'password' => Hash::make('Vendedor12345!'),
                'role_id' => $vendedor->id,
            ]
        );
    }
}

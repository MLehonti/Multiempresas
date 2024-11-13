<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Verificar si el rol de admin ya existe, si no, lo crea
        $adminRole = Role::firstOrCreate(['name' => 'admin']);

        // Verificar si el usuario ya existe o crear uno nuevo
        $admin = User::firstOrCreate(
            ['email' => 'admin@gmail.com'],
            [
                'name' => 'admin',
                'password' => Hash::make('admin'), // Cambia a tu contraseña
            ]
        );

        // Asignar el rol de admin al usuario
        $admin->assignRole($adminRole);
    }
}


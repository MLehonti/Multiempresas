<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolyPermisos extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Verificar si el rol ya existe antes de crearlo
        $role1 = Role::firstOrCreate(['name' => 'admin']);
        $role2 = Role::firstOrCreate(['name' => 'cliente']);

        // Permisos para el rol administrador
        Permission::firstOrCreate(['name' => 'users.index'])->syncRoles($role1);
        Permission::firstOrCreate(['name' => 'users.destroy'])->syncRoles($role1);
        Permission::firstOrCreate(['name' => 'empresas.index'])->syncRoles($role1);

        // Puedes agregar más permisos si es necesario
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    // Mostrar la vista de asignación de roles
    public function assignRole($id)
    {
        $user = User::findOrFail($id);
        $roles = Role::all(); // Obtener todos los roles

        return view('users.assign-role', compact('user', 'roles'));
    }

    // Guardar el rol asignado
    // Método para asignar el rol
    public function storeRole(Request $request, $id)
    {
        try {
            // Buscar el usuario por ID
            $user = User::findOrFail($id);

            // Obtener el rol desde la solicitud
            $role = $request->input('role');

            // Validar que el rol exista en la base de datos
            if (!Role::where('name', $role)->exists()) {
                return redirect()->back()->with('error', 'El rol seleccionado no existe.');
            }

            // Asignar el rol al usuario, removiendo cualquier rol anterior
            $user->syncRoles($role);

            return redirect()->back()->with('success', 'Rol asignado correctamente.');
        } catch (\Exception $e) {
            // En caso de error, regresar con un mensaje de error
            return redirect()->back()->with('error', 'Ocurrió un error al asignar el rol: ' . $e->getMessage());
        }
    }
}

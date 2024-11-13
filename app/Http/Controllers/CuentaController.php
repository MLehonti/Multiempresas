<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cuenta;

class CuentaController extends Controller
{
    // Obtener todas las cuentas y devolverlas en formato JSON
    public function index(Request $request)
    {
        $cuentas = Cuenta::all();

        // Verificar si es una solicitud AJAX para devolver JSON
        if ($request->ajax()) {
            return response()->json($cuentas);
        }

        // Si no es una solicitud AJAX, cargar la vista
        return view('cuentas.index');
    }

    // Guardar una nueva cuenta
    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'tipo' => 'required|string|max:255',
        ]);

        $cuenta = Cuenta::create([
            'nombre' => $request->nombre,
            'tipo' => $request->tipo,
        ]);

        return response()->json([
            'success' => true,
            'cuenta' => $cuenta
        ]);
    }

    // Mostrar los datos de una cuenta específica
    // public function show($id)
    // {
    //     $cuenta = Cuenta::findOrFail($id);
    //     return response()->json($cuenta);
    // }
    // CuentaController.php
public function show($id)
{
    // Encuentra la cuenta por ID
    $cuenta = Cuenta::find($id);

    // Si no existe la cuenta, devolver un error
    if (!$cuenta) {
        return response()->json([
            'success' => false,
            'message' => 'Cuenta no encontrada.'
        ], 404);
    }

    // Devolver la cuenta en formato JSON
    return response()->json([
        'success' => true,
        'cuenta' => $cuenta
    ]);
}


    // Actualizar una cuenta existente
    public function update(Request $request, $id)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'tipo' => 'required|string|max:255',
        ]);

        $cuenta = Cuenta::findOrFail($id);
        $cuenta->update([
            'nombre' => $request->nombre,
            'tipo' => $request->tipo,
        ]);

        return response()->json([
            'success' => true,
            'cuenta' => $cuenta
        ]);
    }

    // Eliminar una cuenta
    public function destroy($id)
    {
        $cuenta = Cuenta::findOrFail($id);
        $cuenta->delete();

        return response()->json([
            'success' => true
        ]);
    }
}

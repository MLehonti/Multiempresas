<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cuenta;
use App\Models\Depreciacion;
use App\Models\DetalleBalanceCopia;
use Illuminate\Support\Facades\Log;

class ActivoFijoController extends Controller
{
    public function index()
    {
        // Usamos `whereIn` para filtrar por múltiples valores
        $cuentas = Cuenta::whereIn('tipo', ['activo_no_corriente', 'activo_corriente'])->get();  // Filtramos solo las cuentas activas
        return view('activo_fijo.index', compact('cuentas'));
    }

    // Método para guardar la depreciación
    public function storeDepreciacion(Request $request, $cuentaId)
    {
        // Validación de los campos
        $request->validate([
            'porcentaje_depreciacion' => 'required|numeric|min:0|max:100',
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string|max:500',
        ]);

        // Crear la depreciación
        $depreciacion = new Depreciacion();
        $depreciacion->nombre = $request->input('nombre');
        $depreciacion->porcentaje_depreciacion = $request->input('porcentaje_depreciacion');
        $depreciacion->descripcion = $request->input('descripcion');
        $depreciacion->save();

        // Relacionar la depreciación con la cuenta seleccionada
        $cuenta = Cuenta::findOrFail($cuentaId);
        $cuenta->depreciaciones()->attach($depreciacion->id);

        return redirect()->route('activo_fijo.index')->with('success', 'Depreciación agregada correctamente');
    }


    public function show()
    {
        // Obtener todas las depreciaciones y cargar las cuentas asociadas
        $depreciaciones = Depreciacion::with('cuentas')->get();

        return view('activo_fijo.show', compact('depreciaciones'));
    }


    public function aplicarDepreciacion()
{
    // Obtener las depreciaciones y las cuentas asociadas
    $depreciaciones = Depreciacion::with('cuentas')->get();

    foreach ($depreciaciones as $depreciacion) {
        // Obtener el porcentaje de depreciación
        $porcentaje_depreciacion = $depreciacion->porcentaje_depreciacion / 100;

        // Recorrer las cuentas asociadas a la depreciación
        foreach ($depreciacion->cuentas as $cuenta) {
            // Buscar todos los registros en detalle_balance_copia para la cuenta actual
            $detallesBalance = DetalleBalanceCopia::where('cuenta_id', $cuenta->id)->get();

            // Verificar si existen registros
            if ($detallesBalance->isEmpty()) {
                Log::info("No se encontraron registros en detalle_balance_copia para Cuenta ID: {$cuenta->id}");
                continue; // Pasar a la siguiente cuenta si no hay registros
            }

            // Recorrer todos los registros encontrados
            foreach ($detallesBalance as $detalleBalance) {
                // Obtener el valor actual de 'debe'
                $valor_debe = $detalleBalance->debe;

                // Calcular la depreciación
                $depreciacion_aplicada = $valor_debe * $porcentaje_depreciacion;

                // Calcular el nuevo valor de 'debe' después de aplicar la depreciación
                $nuevo_valor_debe = $valor_debe - $depreciacion_aplicada;

                // Actualizar el valor de 'debe' en la tabla detalle_balance_copia
                $detalleBalance->debe = $nuevo_valor_debe;
                $detalleBalance->save();

                // Log para verificar el resultado
                Log::info("Depreciación aplicada: {$depreciacion->nombre}, Cuenta ID: {$cuenta->id}, Valor Original: {$valor_debe}, Depreciación Aplicada: {$depreciacion_aplicada}, Nuevo Valor: {$nuevo_valor_debe}");
            }
        }
    }

    return redirect()->route('activo_fijo.index')->with('success', 'Depreciación aplicada correctamente');
}



public function eliminarDepreciacion($id)
{
    // Buscar la depreciación
    $depreciacion = Depreciacion::findOrFail($id);

    // Eliminar la relación de la depreciación con las cuentas
    $depreciacion->cuentas()->detach();

    // Eliminar la depreciación
    $depreciacion->delete();

    // Redirigir de nuevo con un mensaje de éxito
    return redirect()->route('activo_fijo.index')->with('success', 'Depreciación eliminada correctamente.');
}



}

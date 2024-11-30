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
        // Obtener todas las cuentas y sus depreciaciones asociadas
        $cuentasConDepreciaciones = Cuenta::with('depreciaciones')->get();

        // Recorrer todas las cuentas y sus depreciaciones
        foreach ($cuentasConDepreciaciones as $cuenta) {
            // Verificar si la cuenta tiene depreciaciones asociadas
            if ($cuenta->depreciaciones->isEmpty()) {
                Log::info("No hay depreciaciones asociadas a la cuenta: " . $cuenta->id);
                continue; // Si no tiene depreciaciones, continuar con la siguiente cuenta
            }

            // Recorrer todas las depreciaciones asociadas a la cuenta
            foreach ($cuenta->depreciaciones as $depreciacion) {
                // Buscar los registros en detalle_balance_copia que coincidan con el cuenta_id
                $detalleBalanceCopia = DetalleBalanceCopia::where('cuenta_id', $cuenta->id)->get();

                // Verificar si encontramos registros de detalle_balance_copia
                if ($detalleBalanceCopia->isEmpty()) {
                    Log::info("No hay registros en detalle_balance_copia para la cuenta: " . $cuenta->id);
                    continue; // Si no hay registros, continuar con la siguiente depreciación
                }

                // Recorrer los registros de detalle_balance_copia
                foreach ($detalleBalanceCopia as $detalle) {
                    // Obtener el valor actual del 'debe'
                    $montoOriginal = $detalle->debe;

                    // Calcular la depreciación: montoOriginal * porcentaje_depreciacion / 100
                    $depreciacionAplicada = $montoOriginal * ($depreciacion->porcentaje_depreciacion / 100);

                    // Calcular el valor depreciado
                    $valorDepreciado = $montoOriginal - $depreciacionAplicada;

                    // Actualizar el valor del 'debe' en detalle_balance_copia con el valor depreciado
                    $detalle->debe = $valorDepreciado;
                    $detalle->save();

                    // Log de lo que ocurrió
                    Log::info("Depreciación aplicada a cuenta_id: " . $cuenta->id . " con porcentaje de: " . $depreciacion->porcentaje_depreciacion . "%, nuevo valor de debe: " . $valorDepreciado);
                }
            }
        }

        return redirect()->route('activo_fijo.index')->with('success', 'Depreciación aplicada correctamente');
    }


}

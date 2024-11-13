<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Asiento;
use App\Models\DetalleBalance;
use Illuminate\Support\Facades\Auth;
use App\Models\Empresa;
use App\Models\DetalleBalanceCopia;
use Illuminate\Support\Facades\Log; // Asegúrate de importar Log


class AsientoController extends Controller
{
    // public function create($empresa_id)
    // {
    //     $empresa = Empresa::findOrFail($empresa_id);
    //     $cuentas = $empresa->planCuenta->detalles->pluck('cuenta');
        
    //     return view('asientos.create', compact('empresa', 'cuentas'));
    // }


    public function create($empresa_id)
    {
        $empresa = Empresa::findOrFail($empresa_id);
        $cuentas = $empresa->planCuenta->detalles->pluck('cuenta');

        // Obtener los asientos contables de la empresa
        $asientos = Asiento::where('empresa_id', $empresa_id)
                        ->orderBy('fecha', 'desc') // Ordenar por fecha (de más reciente a más antiguo)
                        ->get();

        return view('asientos.create', compact('empresa', 'cuentas', 'asientos'));
    }



    public function store(Request $request)
{
    // Log para ver los datos que llegan al método
    Log::info('Datos recibidos para crear un asiento:', $request->all());

    try {
        // Validación de los datos
        $request->validate([
            'empresa_id' => 'required|exists:empresas,id',
            'cuenta_origen_id' => 'required|exists:cuentas,id',
            'cuenta_destino_id' => 'required|exists:cuentas,id',
            'monto' => 'required|numeric',
            'debe' => 'nullable|numeric',
            'haber' => 'nullable|numeric',
            'fecha' => 'required|date',
        ]);

        Log::info('Validación de datos de asiento exitosa');

        // Crear el asiento contable en la tabla `asientos`
        $asiento = new Asiento();
        $asiento->empresa_id = $request->empresa_id;
        $asiento->cuenta_origen_id = $request->cuenta_origen_id;
        $asiento->cuenta_destino_id = $request->cuenta_destino_id;
        $asiento->monto = $request->monto;
        $asiento->debe = $request->debe;
        $asiento->haber = $request->haber;
        $asiento->fecha = $request->fecha;

        // Log antes de intentar guardar
        Log::info('Intentando guardar el asiento en la base de datos.');

        $asiento->save();

        // Log para confirmar que el asiento fue creado
        if ($asiento->exists) {
            Log::info('Asiento creado exitosamente', ['asiento_id' => $asiento->id]);
        } else {
            Log::error('Error al crear el asiento. No se pudo guardar en la base de datos.');
        }

        // Llamar a la función de actualización del balance copia
        $this->actualizarBalanceCopia($asiento);

        return redirect()->route('asientos.create', ['empresa_id' => $request->empresa_id])
                         ->with('success', 'Asiento registrado correctamente.');

    } catch (\Illuminate\Validation\ValidationException $e) {
        // Log para capturar errores específicos de validación
        Log::error('Error de validación en datos de asiento', ['errors' => $e->errors()]);
        return back()->withErrors($e->errors())->withInput();
    } catch (\Exception $e) {
        // Log para capturar cualquier otra excepción
        Log::error('Excepción al intentar guardar el asiento:', ['error' => $e->getMessage()]);
        return back()->with('error', 'Ocurrió un error al registrar el asiento.')->withInput();
    }
}



    // private function actualizarBalanceCopia(Asiento $asiento)
    // {
    //     // Actualizar el balance de apertura copia con los valores del asiento
    //     $detalleOrigen = DetalleBalance::where('balance_id', $asiento->empresa_id)
    //                                     ->where('cuenta_id', $asiento->cuenta_origen_id)
    //                                     ->first();
    //     $detalleDestino = DetalleBalance::where('balance_id', $asiento->empresa_id)
    //                                     ->where('cuenta_id', $asiento->cuenta_destino_id)
    //                                     ->first();

    //     if ($detalleOrigen) {
    //         $detalleOrigen->debe -= $asiento->monto;
    //         $detalleOrigen->save();
    //     }

    //     if ($detalleDestino) {
    //         $detalleDestino->haber += $asiento->monto;
    //         $detalleDestino->save();
    //     }
    // }




    private function actualizarBalanceCopia(Asiento $asiento)
    {
        Log::info('Iniciando actualización de balance de apertura copia', ['asiento_id' => $asiento->id]);
    
        // Log de valores actuales del asiento
        Log::info('Valores de asiento', [
            'empresa_id' => $asiento->empresa_id,
            'cuenta_origen_id' => $asiento->cuenta_origen_id,
            'cuenta_destino_id' => $asiento->cuenta_destino_id,
            'debe' => $asiento->debe,
            'haber' => $asiento->haber
        ]);
    
        // Obtener o crear el detalle de origen para la cuenta de origen en balance_apertura_copia
        $detalleOrigen = DetalleBalanceCopia::firstOrCreate(
            ['balance_id' => $asiento->empresa_id, 'cuenta_id' => $asiento->cuenta_origen_id],
            ['debe' => 0, 'haber' => 0]
        );
    
        Log::info('Detalle de origen obtenido o creado', ['detalle_id' => $detalleOrigen->id, 'debe_actual' => $detalleOrigen->debe]);
    
        // Restar el `debe` especificado en el asiento
        $detalleOrigen->debe -= $asiento->debe;
        $detalleOrigen->save();
    
        Log::info('Detalle de origen actualizado', ['detalle_id' => $detalleOrigen->id, 'nuevo_debe' => $detalleOrigen->debe]);
    
        // Obtener o crear el detalle de destino para la cuenta de destino en balance_apertura_copia
        $detalleDestino = DetalleBalanceCopia::firstOrCreate(
            ['balance_id' => $asiento->empresa_id, 'cuenta_id' => $asiento->cuenta_destino_id],
            ['debe' => 0, 'haber' => 0]
        );
    
        Log::info('Detalle de destino obtenido o creado', ['detalle_id' => $detalleDestino->id, 'haber_actual' => $detalleDestino->haber]);
    
        // Sumar el `haber` especificado en el asiento
        $detalleDestino->haber += $asiento->haber;
        $detalleDestino->save();
    
        Log::info('Detalle de destino actualizado', ['detalle_id' => $detalleDestino->id, 'nuevo_haber' => $detalleDestino->haber]);
    
        Log::info('Actualización de balance de apertura copia completada');
    }
    


}

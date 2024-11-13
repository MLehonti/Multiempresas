<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BalanceApertura;
use App\Models\DetalleBalance;
use App\Models\Cuenta;
use App\Models\Empresa;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log; // Asegúrate de importar Log
use App\Models\BalanceAperturaCopia;
use App\Models\DetalleBalanceCopia;


class BalanceAperturaController extends Controller
{


    public function create($empresa_id)
    {
        $empresa = Empresa::findOrFail($empresa_id);

        // Verificar que la empresa pertenezca al usuario logueado
        if (Auth::user()->id !== $empresa->user_id) {
            abort(403, 'No tienes permiso para ver esta empresa.');
        }

        // Obtener el plan de cuentas de la empresa
        $planCuenta = $empresa->planCuenta()->with('detalles.cuenta')->first();

        if (!$planCuenta) {
            // Si no existe un plan de cuentas para la empresa, redirigir o mostrar un error
            abort(404, 'No se ha creado un plan de cuentas para esta empresa.');
        }

        // Obtener las cuentas que están en el plan de cuentas de la empresa
        $cuentas = $planCuenta->detalles->pluck('cuenta'); // Obtener las cuentas desde los detalles del plan de cuentas

        return view('empresa.balance_create', compact('empresa', 'cuentas'));
    }



    public function store(Request $request)
{
    try {
        $user = Auth::user();
        // Verificar los datos recibidos
        Log::info('Datos recibidos en la solicitud: ' . json_encode($request->all()));

        // Validar la solicitud
        $request->validate([
            'empresa_id' => 'required|exists:empresas,id',
            'detalles' => 'required|array',
            'detalles.*.cuenta_id' => 'required|exists:cuentas,id',
            'detalles.*.debe' => 'nullable|numeric|min:0',
            'detalles.*.haber' => 'nullable|numeric|min:0',
        ]);

        Log::info('Validación de la solicitud exitosa');

        // Crear el balance de apertura original
        $balance = BalanceApertura::create([
            'empresa_id' => $request->empresa_id,
            'fecha' => now(),
        ]);

        // Registrar el balance de apertura creado
        $empresa = $balance->empresa;

        activity()
            ->causedBy($user) // Obtener el usuario autenticado correctamente
            ->performedOn($balance) // Especificar el objeto (empresa) en el que se realizó la acción
            ->log('Se creó un nuevo balance : ' . $balance->id . ' de la empresa: ' . $empresa->nombre);

        Log::info('Balance de apertura creado: ' . $balance->id);

        // Crear la copia del balance de apertura
        $balanceCopia = BalanceAperturaCopia::create([
            'empresa_id' => $request->empresa_id,
            'fecha' => now(),
        ]);

        // Verificar que detalles no esté vacío y sea un array
        if (is_array($request->detalles)) {
            foreach ($request->detalles as $detalle) {
                // Insertar detalles en el balance original
                DetalleBalance::create([
                    'balance_id' => $balance->id,
                    'cuenta_id' => $detalle['cuenta_id'],
                    'debe' => $detalle['debe'] ?? 0,
                    'haber' => $detalle['haber'] ?? 0,
                ]);

                // Insertar los mismos detalles en la copia
                DetalleBalanceCopia::create([
                    'balance_id' => $balanceCopia->id,
                    'cuenta_id' => $detalle['cuenta_id'],
                    'debe' => $detalle['debe'] ?? 0,
                    'haber' => $detalle['haber'] ?? 0,
                ]);
            }
        } else {
            Log::error('Detalles de balance no proporcionados o formato incorrecto.');
            return response()->json(['success' => false, 'message' => 'Detalles de balance no proporcionados o formato incorrecto.'], 400);
        }

        return response()->json(['success' => true, 'message' => 'Balance de apertura y copia creados correctamente']);
    } catch (\Exception $e) {
        Log::error('Error al crear el balance de apertura: ' . $e->getMessage());
        return response()->json(['success' => false, 'message' => 'Error al crear el balance de apertura: ' . $e->getMessage()], 500);
    }
}



    // public function store(Request $request)
    // {
    //     try {
    //         $user = Auth::user();
    //         // Verificar los datos recibidos
    //         Log::info('Datos recibidos en la solicitud: ' . json_encode($request->all()));

    //         // Validar la solicitud
    //         $request->validate([
    //             'empresa_id' => 'required|exists:empresas,id',
    //             'detalles' => 'required|array',
    //             'detalles.*.cuenta_id' => 'required|exists:cuentas,id',
    //             'detalles.*.debe' => 'nullable|numeric|min:0',
    //             'detalles.*.haber' => 'nullable|numeric|min:0',
    //         ]);

    //         Log::info('Validación de la solicitud exitosa');

    //         // Crear el balance de apertura
    //         $balance = BalanceApertura::create([
    //             'empresa_id' => $request->empresa_id,
    //             'fecha' => now(),
    //         ]);
    //         //Registrar el balance de apertura creado
    //         $empresa = $balance->empresa;



    //         activity()
    //         ->causedBy($user) // Obtener el usuario autenticado correctamente
    //         ->performedOn($balance) // Especificar el objeto (empresa) en el que se realizó la acción
    //         ->log('Se creó una nuevo balance : ' . $balance->id . ' de la empresa: ' . $empresa->nombre);

    //         Log::info('Balance de apertura creado: ' . $balance->id);

    //         // Verificar que detalles no esté vacío y sea un array
    //         if (is_array($request->detalles)) {
    //             foreach ($request->detalles as $detalle) {
    //                 DetalleBalance::create([
    //                     'balance_id' => $balance->id,
    //                     'cuenta_id' => $detalle['cuenta_id'],
    //                     'debe' => $detalle['debe'] ?? 0,
    //                     'haber' => $detalle['haber'] ?? 0,
    //                 ]);
    //             }
    //         } else {
    //             Log::error('Detalles de balance no proporcionados o formato incorrecto.');
    //             return response()->json(['success' => false, 'message' => 'Detalles de balance no proporcionados o formato incorrecto.'], 400);
    //         }

    //         return response()->json(['success' => true, 'message' => 'Balance de apertura creado correctamente']);
    //     } catch (\Exception $e) {
    //         Log::error('Error al crear el balance de apertura: ' . $e->getMessage());
    //         return response()->json(['success' => false, 'message' => 'Error al crear el balance de apertura: ' . $e->getMessage()], 500);
    //     }
    // }


    // public function show($empresa_id)
    // {
    //     // Verifica que la empresa pertenezca al usuario autenticado
    //     $empresa = Empresa::where('id', $empresa_id)->where('user_id', Auth::user()->id)->firstOrFail();

    //     // Cargar el balance con los detalles y las cuentas asociadas utilizando with
    //     $balance = BalanceApertura::where('empresa_id', $empresa->id)
    //                 ->with(['detalles.cuenta']) // Cargar la relación 'detalles' y 'cuenta' en una sola consulta
    //                 ->first();

    //     // Verificar si la relación 'cuenta' está cargada correctamente
    //     foreach ($balance->detalles as $detalle) {
    //         if (!$detalle->relationLoaded('cuenta')) {
    //             Log::error('Relación cuenta no cargada para detalle con id: ' . $detalle->id);
    //         }
    //     }

    //     // Mostrar balance y detalles en la vista para depurar
    //     return view('empresa.balance_show', compact('empresa', 'balance'));
    // }

    public function show($empresa_id)
    {
        // Verifica que la empresa pertenezca al usuario autenticado
        $empresa = Empresa::where('id', $empresa_id)->where('user_id', Auth::user()->id)->firstOrFail();

        // Cargar el balance con los detalles y las cuentas asociadas utilizando with
        $balance = BalanceApertura::where('empresa_id', $empresa->id)
                    ->with(['detalles.cuenta']) // Cargar la relación 'detalles' y 'cuenta' en una sola consulta
                    ->first();

        // Si no existe un balance de apertura, redirigir a la vista de crear balance
        if (!$balance) {
            return redirect()->route('balance.create', ['empresa_id' => $empresa->id]);
        }

        // Si el balance existe, mostrar la vista de balance
        return view('empresa.balance_show', compact('empresa', 'balance'));
    }




    public function destroy($id)
    {
        try {
            // Buscar el balance de apertura por su ID
            $balance = BalanceApertura::findOrFail($id);

            // Eliminar los detalles asociados
            $balance->detalles()->delete();

            // Eliminar el balance
            $balance->delete();

            return response()->json(['success' => true, 'message' => 'Balance eliminado correctamente']);
        } catch (\Exception $e) {
            // Registrar el error en los logs
            Log::error('Error al eliminar el balance: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Error al eliminar el balance'], 500);
        }
    }








}

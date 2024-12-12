<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Asiento;
use App\Models\DetalleBalance;
use Illuminate\Support\Facades\Auth;
use App\Models\Empresa;
use App\Models\DetalleBalanceCopia;
use Illuminate\Support\Facades\Log;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Carbon\Carbon;

class AsientoController extends Controller
{

    public function exportarExcel(Request $request, $empresa_id)
{
    // Filtrar los asientos por fecha
    $asientos = Asiento::where('empresa_id', $empresa_id)
        ->when($request->fecha_inicio, function ($query) use ($request) {
            $query->whereDate('fecha', '>=', $request->fecha_inicio);
        })
        ->when($request->fecha_fin, function ($query) use ($request) {
            $query->whereDate('fecha', '<=', $request->fecha_fin);
        })
        ->get()
        ->groupBy('fecha');

    // Crear el archivo Excel
    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();
    $sheet->setTitle("Libro Diario");

    // Encabezados
    $sheet->setCellValue('A1', 'Fecha');
    $sheet->setCellValue('B1', 'Cuenta Origen');
    $sheet->setCellValue('C1', 'Cuenta Destino');
    $sheet->setCellValue('D1', 'Monto');
    $sheet->setCellValue('E1', 'Debe');
    $sheet->setCellValue('F1', 'Haber');
    $sheet->setCellValue('G1', 'Descripción');

    // Rellenar datos
    $row = 2;
    foreach ($asientos as $fecha => $asientosPorFecha) {
        $sheet->setCellValue("A$row", Carbon::parse($fecha)->format('d/m/Y'));

        foreach ($asientosPorFecha as $asiento) {
            $sheet->setCellValue("B$row", $asiento->cuentaOrigen->nombre);
            $sheet->setCellValue("C$row", $asiento->cuentaDestino->nombre);
            $sheet->setCellValue("D$row", number_format($asiento->monto, 2));
            $sheet->setCellValue("E$row", number_format($asiento->debe, 2));
            $sheet->setCellValue("F$row", number_format($asiento->haber, 2));
            $sheet->setCellValue("G$row", $asiento->descripcion);
            $row++;
        }

        $row++; // Agrega una fila en blanco entre fechas
    }

    // Descargar el archivo Excel
    $writer = new Xlsx($spreadsheet);
    $fileName = 'Libro_Diario_Mayor.xlsx';
    $temp_file = tempnam(sys_get_temp_dir(), $fileName);
    $writer->save($temp_file);

    return response()->download($temp_file, $fileName)->deleteFileAfterSend(true);
}





    // public function create($empresa_id)
    // {
    //     $empresa = Empresa::findOrFail($empresa_id);
    //     $cuentas = $empresa->planCuenta->detalles->pluck('cuenta');

    //     return view('asientos.create', compact('empresa', 'cuentas'));
    // }

    public function libroDiario($empresa_id)
{
    // Obtener los asientos contables de la empresa, agrupados por fecha
    $asientos = Asiento::where('empresa_id', $empresa_id)
                    ->orderBy('fecha', 'asc')
                    ->get()
                    ->groupBy('fecha');

    $empresa = Empresa::findOrFail($empresa_id);

    return view('libro_diario.index', compact('asientos', 'empresa'));
}





    public function mostrarDetallesBalanceCopia($empresa_id)
    {
        // Obtener todos los registros de la tabla detalles_balance_copia para la empresa específica
        $detalles = DetalleBalanceCopia::where('balance_id', $empresa_id)->get();

        // Log para verificar que se obtuvieron los registros correctamente
        Log::info('Detalles de balance copia obtenidos', ['empresa_id' => $empresa_id, 'detalles' => $detalles]);

        return view('asientos.index', compact('detalles', 'empresa_id'));
    }







    public function create($empresa_id)
    {
        $empresa = Empresa::findOrFail($empresa_id);
        $cuentas = $empresa->planCuenta->detalles->pluck('cuenta');

        // Obtener los asientos contables de la empresa
        $asientos = Asiento::where('empresa_id', $empresa_id)
            ->orderBy('fecha', 'desc') // Ordenar por fecha (de más reciente a más antiguo)
            ->get();

        // Obtener el código máximo actual para cada cuenta en los asientos contables
        $ultimoCodigoPorCuenta = [];
        foreach ($cuentas as $cuenta) {
            $ultimoAsiento = Asiento::where('cuenta_origen_id', $cuenta->id)
                                    ->orWhere('cuenta_destino_id', $cuenta->id)
                                    ->orderBy('created_at', 'desc')
                                    ->first();

            // Si hay un asiento previo, obtenemos el código y sumamos 1
            if ($ultimoAsiento) {
                $codigoParts = explode('.', $ultimoAsiento->codigo);
                $ultimoNumero = (int) end($codigoParts);
                $nuevoCodigo = implode('.', array_slice($codigoParts, 0, -1)) . '.' . ($ultimoNumero + 1);
            } else {
                // Si no existe un asiento, el primer código es el código de la cuenta seguido de ".1"
                $nuevoCodigo = $cuenta->codigo . '.1';
            }

            $ultimoCodigoPorCuenta[$cuenta->id] = $nuevoCodigo;
        }

        return view('asientos.create', compact('empresa', 'cuentas', 'asientos', 'ultimoCodigoPorCuenta'));
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
                'descripcion' => 'nullable|string|max:255' // Nueva validación para 'descripcion'
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
            $asiento->descripcion = $request->descripcion; // Asignación de 'descripcion'

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




    public function calcularFlujoCaja($empresa_id)
    {
        $asientos = Asiento::where('empresa_id', $empresa_id)->get();
    
        $actividades = [
            'operacion' => [33, 34, 35, 36, 37, 38, 39, 40, 41], 
            'inversion' => [8, 9, 10, 11, 12, 14], 
            'financiamiento' => [19, 20, 21, 22, 23, 24, 25, 26, 27, 28], 
        ];
    
        $resultados = [
            'operacion' => ['entradas' => 0, 'salidas' => 0, 'neto' => 0],
            'inversion' => ['entradas' => 0, 'salidas' => 0, 'neto' => 0],
            'financiamiento' => ['entradas' => 0, 'salidas' => 0, 'neto' => 0],
        ];
    
        // Procesar cada asiento
        foreach ($asientos as $asiento) {
            foreach ($actividades as $tipo => $cuentas) {
                // Entradas: Sumar el monto en "debe" cuando la cuenta de destino pertenece a la actividad
                if (in_array($asiento->cuenta_destino_id, $cuentas)) {
                    $resultados[$tipo]['entradas'] += $asiento->haber;
                }
    
                // Salidas: Sumar el monto en "haber" cuando la cuenta de origen pertenece a la actividad
                if (in_array($asiento->cuenta_origen_id, $cuentas)) {
                    $resultados[$tipo]['salidas'] += $asiento->debe;
                }
            }
        }
    
        // Calcular flujo neto por actividad
        foreach ($resultados as $tipo => &$resultado) {
            $resultado['neto'] = $resultado['salidas'] - $resultado['entradas'];
        }
    
        // Calcular el total general
        $totalGeneral = [
            'entradas' => array_sum(array_column($resultados, 'entradas')),
            'salidas' => array_sum(array_column($resultados, 'salidas')),
            'neto' => array_sum(array_column($resultados, 'neto')),
        ];
    
        return view('flujo_caja.index', compact('resultados', 'totalGeneral', 'empresa_id'));
    }
    
    

    

}

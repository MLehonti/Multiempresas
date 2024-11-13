<?php

namespace App\Http\Controllers;

use App\Models\Cuenta;
use App\Models\PlanCuenta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use app\Models\Empresa;
use Illuminate\Support\Facades\Log; // Asegúrate de importar Log
use Barryvdh\DomPDF\Facade\Pdf;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;



class PlanCuentasController extends Controller
{



    // public function exportExcel()
    // {
    //     // Obtener los planes de cuentas
    //     $planesDeCuentas = PlanCuenta::with(['empresa', 'detalles.cuenta'])->get();

    //     // Crear una nueva hoja de cálculo
    //     $spreadsheet = new Spreadsheet();
    //     $sheet = $spreadsheet->getActiveSheet();

    //     // Establecer encabezados
    //     $sheet->setCellValue('A1', 'Empresa');
    //     $sheet->setCellValue('B1', 'Fecha');
    //     $sheet->setCellValue('C1', 'Código');
    //     $sheet->setCellValue('D1', 'Cuenta');

    //     // Rellenar las filas
    //     $row = 2;
    //     foreach ($planesDeCuentas as $plan) {
    //         $empresa = $plan->empresa->nombre;
    //         $fecha = $plan->fecha;

    //         foreach ($plan->detalles as $detalle) {
    //             $codigo = $detalle->cuenta->id; // Asume que la cuenta tiene un ID que usarás como código
    //             $nombreCuenta = $detalle->cuenta->nombre;

    //             $sheet->setCellValue('A' . $row, $empresa);
    //             $sheet->setCellValue('B' . $row, $fecha);
    //             $sheet->setCellValue('C' . $row, $codigo);
    //             $sheet->setCellValue('D' . $row, $nombreCuenta);
    //             $row++;
    //         }
    //     }

    //     // Crear el archivo Excel
    //     $writer = new Xlsx($spreadsheet);
    //     $fileName = 'planes_de_cuentas.xlsx';
    //     $temp_file = tempnam(sys_get_temp_dir(), $fileName);
    //     $writer->save($temp_file);

    //     // Descargar el archivo Excel
    //     return response()->download($temp_file, $fileName)->deleteFileAfterSend(true);
    // }

    // //funcion para poder descargar en pdf
    // public function exportPdf()
    // {
    //     // Obtener los planes de cuentas con los datos necesarios
    //     $planesDeCuentas = PlanCuenta::with(['empresa', 'detalles.cuenta'])->get();

    //     // Generar el PDF utilizando la vista que ya tienes
    //     $pdf = PDF::loadView('plan_cuentas.pdf', compact('planesDeCuentas'));

    //     // Descargar el PDF
    //     return $pdf->download('planes_de_cuentas.pdf');
    // }

    public function exportExcel()
{
    // Obtener los planes de cuentas
    $planesDeCuentas = PlanCuenta::with(['empresa', 'detalles.cuenta'])->get();

    // Crear una nueva hoja de cálculo
    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();

    // Establecer encabezados
    $sheet->setCellValue('A1', 'Empresa');
    $sheet->setCellValue('B1', 'Fecha');
    $sheet->setCellValue('C1', 'Código');
    $sheet->setCellValue('D1', 'Cuenta');

    // Rellenar las filas
    $row = 2;
    foreach ($planesDeCuentas as $plan) {
        $empresa = $plan->empresa->nombre;
        $fecha = $plan->fecha;

        // Categorización y agrupación
        $codigoTipoCuenta = [
            'activo_corriente' => 1,
            'activo_no_corriente' => 2,
            'pasivo_corriente' => 3,
            'pasivo_no_corriente' => 4,
            'patrimonio' => 5,
        ];
        $contadores = [
            'activo_corriente' => 1,
            'activo_no_corriente' => 1,
            'pasivo_corriente' => 1,
            'pasivo_no_corriente' => 1,
            'patrimonio' => 1,
        ];
        $titulosTipos = [
            'activo_corriente' => '1 Activos Corrientes',
            'activo_no_corriente' => '2 Activos No Corrientes',
            'pasivo_corriente' => '3 Pasivos Corrientes',
            'pasivo_no_corriente' => '4 Pasivos No Corrientes',
            'patrimonio' => '5 Patrimonio',
        ];

        $detallesOrdenados = $plan->detalles->sortBy(function($detalle) use ($codigoTipoCuenta) {
            return $codigoTipoCuenta[$detalle->cuenta->tipo] ?? 999;
        });
        $tipoAnterior = null;

        // Añadir la empresa y fecha solo una vez por plan
        $sheet->setCellValue('A' . $row, $empresa);
        $sheet->setCellValue('B' . $row, $fecha);
        $row++;

        foreach ($detallesOrdenados as $detalle) {
            $tipoCuenta = $detalle->cuenta->tipo;

            // Si cambia la categoría, añadir una fila con el título
            if ($tipoCuenta !== $tipoAnterior) {
                $sheet->setCellValue('C' . $row, $titulosTipos[$tipoCuenta]);
                $sheet->getStyle('C' . $row)->getFont()->setBold(true);
                $row++;
                $tipoAnterior = $tipoCuenta;
            }

            // Generar el código usando el tipo y su contador
            $codigo = $codigoTipoCuenta[$tipoCuenta] . '.' . $contadores[$tipoCuenta];
            $contadores[$tipoCuenta]++;

            // Agregar la información de la cuenta
            $sheet->setCellValue('C' . $row, $codigo);
            $sheet->setCellValue('D' . $row, $detalle->cuenta->nombre . ' (' . $detalle->cuenta->tipo . ')');
            $row++;
        }
    }

    // Crear el archivo Excel
    $writer = new Xlsx($spreadsheet);
    $fileName = 'planes_de_cuentas.xlsx';
    $temp_file = tempnam(sys_get_temp_dir(), $fileName);
    $writer->save($temp_file);

    // Descargar el archivo Excel
    return response()->download($temp_file, $fileName)->deleteFileAfterSend(true);
}




   // Mostrar todas las cuentas y permitir al usuario seleccionar para el plan de cuentas
   public function create(Request $request)
   {


       // Obtener la empresa asociada al usuario logueado
       $empresa = Auth::user()->empresa;

       // Obtener todas las cuentas
       $cuentas = Cuenta::all();

       // Si la solicitud es AJAX (usada por fetch), devolver los datos en formato JSON
       if ($request->ajax()) {
           return response()->json([
               'empresa' => $empresa,
               'cuentas' => $cuentas
           ]);
       }

       // De lo contrario, retornar la vista normalmente
       return view('plan_cuentas.create', compact('empresa', 'cuentas'));
   }

   // Guardar el plan de cuentas
   public function store(Request $request)
   {
       $request->validate([
           'empresa_id' => 'required|exists:empresas,id',
           'cuentas_seleccionadas' => 'required|array',
           'cuentas_seleccionadas.*' => 'exists:cuentas,id',
       ]);

       // Depurar las cuentas seleccionadas
       Log::info("Cuentas seleccionadas: ", $request->cuentas_seleccionadas);

       // Crear el plan de cuentas asociado al usuario logueado
       $planCuenta = PlanCuenta::create([
           'empresa_id' => $request->empresa_id,
           'fecha' => now(),
           'user_id' => Auth::id(),
       ]);

       // Guardar las cuentas seleccionadas como detalles del plan de cuentas
       foreach (array_unique($request->cuentas_seleccionadas) as $cuentaId) {
           $planCuenta->detalles()->create([
               'cuenta_id' => $cuentaId,
           ]);
       }

       return redirect()->route('plan-cuentas.index')->with('success', 'Plan de cuentas creado correctamente.');
   }

    // Mostrar los planes de cuentas del usuario logueado
    // public function index()
    // {
    //     $planesDeCuentas = PlanCuenta::where('user_id', Auth::id())->get();
    //     return view('plan_cuentas.index', compact('planesDeCuentas'));
    // }


    public function index()
    {
        // Cargar los planes de cuentas junto con sus detalles y las cuentas asociadas
        $planesDeCuentas = PlanCuenta::where('user_id', Auth::id())
                            ->with('detalles.cuenta') // Cargar los detalles con las cuentas
                            ->get();



        return view('plan_cuentas.index', compact('planesDeCuentas'));
    }






}


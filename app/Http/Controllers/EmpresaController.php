<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Rubro;
use App\Models\Empresa;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class EmpresaController extends Controller
{


    public function exportExcel()
    {
        // Obtener todas las empresas de la base de datos
        $empresas = Empresa::with('rubro', 'user')->get();

        // Crear una nueva hoja de cálculo
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Agregar encabezados
        $sheet->setCellValue('A1', 'ID');
        $sheet->setCellValue('B1', 'Nombre');
        $sheet->setCellValue('C1', 'Rubro');
        $sheet->setCellValue('D1', 'Propietario');

        // Agregar datos de empresas
        $row = 2;
        foreach ($empresas as $empresa) {
            $sheet->setCellValue('A' . $row, $empresa->id);
            $sheet->setCellValue('B' . $row, $empresa->nombre);
            $sheet->setCellValue('C' . $row, $empresa->rubro->nombre);
            $sheet->setCellValue('D' . $row, $empresa->user->name);
            $row++;
        }

        // Crear el archivo Excel y guardarlo en la respuesta para descargar
        $writer = new Xlsx($spreadsheet);
        $fileName = 'lista_empresas.xlsx';
        $temp_file = tempnam(sys_get_temp_dir(), $fileName);
        $writer->save($temp_file);

        return response()->download($temp_file, $fileName)->deleteFileAfterSend(true);
    }


    public function exportPdf()
    {
        // Obtener todas las empresas con sus relaciones necesarias
        $empresas = Empresa::with('rubro', 'user')->get();

        // Generar el PDF usando una vista
        $pdf = PDF::loadView('empresa.pdf', compact('empresas'));

        // Descargar el PDF generado
        return $pdf->download('lista_empresas.pdf');
    }


    // Método para mostrar todas las empresas
    public function index()
    {
        // Obtener todas las empresas del sistema
        $empresas = Empresa::with('rubro', 'user')->get();

        // Retornar la vista con las empresas
        return view('empresa.index', compact('empresas'));
    }


    public function create()
    {
        try {
            $user = Auth::user();
            $empresa = Empresa::where('user_id', $user->id)->first();

            if ($empresa) {
                return redirect()->route('empresa.show', ['empresa' => $empresa->id]);
            }

            $rubros = Rubro::all();
            return view('empresa.create', compact('rubros'));
        } catch (\Exception $e) {
            Log::error('Error al obtener los rubros: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error al cargar la vista de creación de empresa.');
        }
    }

    public function store(Request $request)
    {

        try {
            $user = Auth::user();
            $empresaExistente = Empresa::where('user_id', $user->id)->first();

            if ($empresaExistente) {
                return response()->json([
                    'success' => false,
                    'message' => 'Ya has creado una empresa.'
                ], 400);
            }

            $request->validate([
                'nombre' => 'required|string|max:255',
                'rubro_id' => 'required|exists:rubros,id',
            ]);

            $empresa = Empresa::create([
                'nombre' => $request->nombre,
                'rubro_id' => $request->rubro_id,
                'user_id' => $user->id,
            ]);
            activity()
    ->causedBy($user) // Obtener el usuario autenticado correctamente
    ->performedOn($empresa) // Especificar el objeto (empresa) en el que se realizó la acción
    ->log('Se creó una nueva empresa: ' . $empresa->id . ' nombre: ' . $empresa->nombre);

            return response()->json([
                'success' => true,
                'message' => 'Empresa creada correctamente.',
                'empresa' => $empresa,
                'redirect' => route('empresa.show', ['empresa' => $empresa->id])
            ]);
        } catch (\Exception $e) {
            Log::error('Error al crear la empresa: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error al crear la empresa: ' . $e->getMessage()
            ], 500);
        }

    }

    public function show(Empresa $empresa)
    {
        if (Auth::user()->id !== $empresa->user_id) {
            abort(403, 'No tienes permisos para ver esta empresa.');
        }

        return view('empresa.show', compact('empresa'));
    }

    // Método para eliminar la empresa
    public function destroy(Empresa $empresa)
    {
        try {
            $user = Auth::user();

            if (Auth::user()->id !== $empresa->user_id) {
                return response()->json([
                    'success' => false,
                    'message' => 'No tienes permisos para eliminar esta empresa.'
                ], 403);
            }

            // Eliminar la empresa y todos los datos relacionados (si aplica)
            $empresa->delete();
            //Registrar la empresa eliminada
            activity()
            ->causedBy($user) // Obtener el usuario autenticado correctamente
            ->performedOn($empresa) // Especificar el objeto (empresa) en el que se realizó la acción
            ->log('Se elimino la empresa empresa: ' . $empresa->id . ' nombre: ' . $empresa->nombre);

            return response()->json([
                'success' => true,
                'message' => 'Empresa eliminada correctamente.'
            ]);
        } catch (\Exception $e) {
            Log::error('Error al eliminar la empresa: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error al eliminar la empresa: ' . $e->getMessage()
            ], 500);
        }
    }
}

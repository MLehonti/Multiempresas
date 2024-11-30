<?php

namespace App\Http\Controllers;

use App\Models\Cuenta;
use App\Models\Asiento;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class EstadoResultadosController extends Controller
{

    public function exportarExcel()
    {
        // Obtener la empresa del usuario autenticado
        $empresa = Auth::user()->empresa;

        // Definir las cuentas relevantes y obtener los datos
        $cuentasRelevantes = [
            'Ventas',
            'Ingresos Financieros',
            'Ingresos Extraordinarios',
            'Costo de Ventas',
            'Gastos de Administración',
            'Gastos de Ventas',
            'Gastos Financieros',
            'Gastos Extraordinarios',
            'Impuesto sobre la Renta'
        ];

        $asientos = Asiento::with(['cuentaOrigen', 'cuentaDestino'])
            ->where('empresa_id', $empresa->id)
            ->whereHas('cuentaOrigen', function($query) use ($cuentasRelevantes) {
                $query->whereIn('nombre', $cuentasRelevantes);
            })
            ->orWhereHas('cuentaDestino', function($query) use ($cuentasRelevantes) {
                $query->whereIn('nombre', $cuentasRelevantes);
            })
            ->get();

        // Calcular los totales como en la función `index`
        $totales = [];
        foreach ($cuentasRelevantes as $cuentaNombre) {
            $totales[$cuentaNombre] = $asientos->filter(function ($asiento) use ($cuentaNombre) {
                return ($asiento->cuentaOrigen && $asiento->cuentaOrigen->nombre === $cuentaNombre) ||
                       ($asiento->cuentaDestino && $asiento->cuentaDestino->nombre === $cuentaNombre);
            })->sum('monto');
        }

        $ingresos = $totales['Ventas'] + $totales['Ingresos Financieros'] + $totales['Ingresos Extraordinarios'];
        $costos = $totales['Costo de Ventas'];
        $gastos = $totales['Gastos de Administración'] + $totales['Gastos de Ventas'] + $totales['Gastos Financieros'] + $totales['Gastos Extraordinarios'];
        $impuestos = $totales['Impuesto sobre la Renta'];
        $utilidadNeta = $ingresos - $costos - $gastos - $impuestos;

        // Crear el archivo Excel
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle("Estado de Resultados");

        // Encabezados
        $sheet->setCellValue('A1', 'Cuenta');
        $sheet->setCellValue('B1', 'Monto (Bs)');

        // Rellenar datos
        $row = 2;
        $sheet->setCellValue("A$row", "Ingresos");
        $row++;
        $sheet->setCellValue("A$row", "Ventas");
        $sheet->setCellValue("B$row", number_format($totales['Ventas'], 2));
        $row++;
        $sheet->setCellValue("A$row", "Ingresos Financieros");
        $sheet->setCellValue("B$row", number_format($totales['Ingresos Financieros'], 2));
        $row++;
        $sheet->setCellValue("A$row", "Ingresos Extraordinarios");
        $sheet->setCellValue("B$row", number_format($totales['Ingresos Extraordinarios'], 2));
        $row++;

        $sheet->setCellValue("A$row", "Costos");
        $row++;
        $sheet->setCellValue("A$row", "Costo de Ventas");
        $sheet->setCellValue("B$row", number_format($totales['Costo de Ventas'], 2));
        $row++;

        $sheet->setCellValue("A$row", "Gastos Operativos");
        $row++;
        $sheet->setCellValue("A$row", "Gastos de Administración");
        $sheet->setCellValue("B$row", number_format($totales['Gastos de Administración'], 2));
        $row++;
        $sheet->setCellValue("A$row", "Gastos de Ventas");
        $sheet->setCellValue("B$row", number_format($totales['Gastos de Ventas'], 2));
        $row++;
        $sheet->setCellValue("A$row", "Gastos Financieros");
        $sheet->setCellValue("B$row", number_format($totales['Gastos Financieros'], 2));
        $row++;
        $sheet->setCellValue("A$row", "Gastos Extraordinarios");
        $sheet->setCellValue("B$row", number_format($totales['Gastos Extraordinarios'], 2));
        $row++;

        $sheet->setCellValue("A$row", "Impuestos");
        $row++;
        $sheet->setCellValue("A$row", "Impuesto sobre la Renta");
        $sheet->setCellValue("B$row", number_format($totales['Impuesto sobre la Renta'], 2));
        $row++;

        $sheet->setCellValue("A$row", "Utilidad Neta");
        $row++;
        $sheet->setCellValue("A$row", "Total Utilidad Neta");
        $sheet->setCellValue("B$row", number_format($utilidadNeta, 2));

        // Descargar el archivo Excel
        $writer = new Xlsx($spreadsheet);
        $fileName = 'Estado_Resultados.xlsx';
        $temp_file = tempnam(sys_get_temp_dir(), $fileName);
        $writer->save($temp_file);

        return response()->download($temp_file, $fileName)->deleteFileAfterSend(true);
    }




    public function index()
    {
        // Obtener la empresa del usuario autenticado
        $empresa = Auth::user()->empresa;

        // Definir los nombres de las cuentas relevantes para el estado de resultados
        $cuentasRelevantes = [
            'Ventas',
            'Ingresos Financieros',
            'Ingresos Extraordinarios',
            'Costo de Ventas',
            'Gastos de Administración',
            'Gastos de Ventas',
            'Gastos Financieros',
            'Gastos Extraordinarios',
            'Impuesto sobre la Renta'
        ];

        // Obtener los asientos de la empresa actual que tienen cuenta origen o destino en las cuentas relevantes
        $asientos = Asiento::with(['cuentaOrigen', 'cuentaDestino'])
            ->where('empresa_id', $empresa->id)
            ->whereHas('cuentaOrigen', function($query) use ($cuentasRelevantes) {
                $query->whereIn('nombre', $cuentasRelevantes);
            })
            ->orWhereHas('cuentaDestino', function($query) use ($cuentasRelevantes) {
                $query->whereIn('nombre', $cuentasRelevantes);
            })
            ->get();

        // Calcular los totales por cuenta
        $totales = [];
        foreach ($cuentasRelevantes as $cuentaNombre) {
            $totales[$cuentaNombre] = $asientos->filter(function ($asiento) use ($cuentaNombre) {
                return ($asiento->cuentaOrigen && $asiento->cuentaOrigen->nombre === $cuentaNombre) ||
                       ($asiento->cuentaDestino && $asiento->cuentaDestino->nombre === $cuentaNombre);
            })->sum('monto');
        }

        // Calcular los resultados finales
        $ingresos = $totales['Ventas'] + $totales['Ingresos Financieros'] + $totales['Ingresos Extraordinarios'];
        $costos = $totales['Costo de Ventas'];
        $gastos = $totales['Gastos de Administración'] + $totales['Gastos de Ventas'] + $totales['Gastos Financieros'] + $totales['Gastos Extraordinarios'];
        $impuestos = $totales['Impuesto sobre la Renta'];
        $utilidadNeta = $ingresos - $costos - $gastos - $impuestos;

        return view('estado_resultados.index', compact('empresa', 'totales', 'ingresos', 'costos', 'gastos', 'impuestos', 'utilidadNeta'));
    }
}

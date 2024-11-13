<?php

namespace App\Http\Controllers;

use App\Models\Cuenta;
use App\Models\Asiento;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EstadoResultadosController extends Controller
{
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

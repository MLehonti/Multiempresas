<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class CuentasEstadoResultadosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Lista de cuentas para el Estado de Resultados
        $cuentasEstadoResultados = [
            // Ingresos
            ['nombre' => 'Ventas', 'tipo' => 'ingreso'],
            ['nombre' => 'Ingresos Financieros', 'tipo' => 'ingreso'],
            ['nombre' => 'Ingresos Extraordinarios', 'tipo' => 'ingreso'],

            // Costos de Ventas
            ['nombre' => 'Costo de Ventas', 'tipo' => 'costo_ventas'],

            // Gastos Operativos
            ['nombre' => 'Gastos de Administración', 'tipo' => 'gasto_operativo'],
            ['nombre' => 'Gastos de Ventas', 'tipo' => 'gasto_operativo'],
            ['nombre' => 'Gastos Financieros', 'tipo' => 'gasto_financiero'],
            ['nombre' => 'Gastos Extraordinarios', 'tipo' => 'gasto_extraordinario'],

            // Impuestos sobre la Renta
            ['nombre' => 'Impuesto sobre la Renta', 'tipo' => 'impuesto_renta'],
        ];

        // Iterar sobre cada cuenta y crearla solo si no existe
        foreach ($cuentasEstadoResultados as $cuenta) {
            DB::table('cuentas')->updateOrInsert(
                ['nombre' => $cuenta['nombre']], // Condición para verificar si ya existe
                [
                    'tipo' => $cuenta['tipo'], 
                    'created_at' => Carbon::now(), 
                    'updated_at' => Carbon::now()
                ]
            );
        }
    }
}

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
            ['nombre' => 'Ventas', 'tipo' => 'activo_corriente'],
            ['nombre' => 'Ingresos Financieros', 'tipo' => 'activo_corriente'],
            ['nombre' => 'Ingresos Extraordinarios', 'tipo' => 'activo_corriente'],

            // Costos de Ventas
            ['nombre' => 'Costo de Ventas', 'tipo' => 'activo_corriente'],

            // Gastos Operativos
            ['nombre' => 'Gastos de Administración', 'tipo' => 'activo_corriente'],
            ['nombre' => 'Gastos de Ventas', 'tipo' => 'activo_corriente'],
            ['nombre' => 'Gastos Financieros', 'tipo' => 'activo_corriente'],
            ['nombre' => 'Gastos Extraordinarios', 'tipo' => 'activo_corriente'],

            // Impuestos sobre la Renta
            ['nombre' => 'Impuesto sobre la Renta', 'tipo' => 'activo_corriente'],
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

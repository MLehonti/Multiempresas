<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class CuentasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Lista de cuentas a insertar
        $cuentas = [
            // Activos Corrientes
            ['nombre' => 'Caja', 'tipo' => 'activo_corriente'],
            ['nombre' => 'Bancos', 'tipo' => 'activo_corriente'],
            ['nombre' => 'Clientes', 'tipo' => 'activo_corriente'],
            ['nombre' => 'Inventarios', 'tipo' => 'activo_corriente'],
            ['nombre' => 'Anticipos a Proveedores', 'tipo' => 'activo_corriente'],
            ['nombre' => 'Documentos por Cobrar', 'tipo' => 'activo_corriente'],
            ['nombre' => 'Inversiones Temporales', 'tipo' => 'activo_corriente'],

            // Activos No Corrientes
            ['nombre' => 'Terreno', 'tipo' => 'activo_no_corriente'],
            ['nombre' => 'Edificio', 'tipo' => 'activo_no_corriente'],
            ['nombre' => 'Maquinaria', 'tipo' => 'activo_no_corriente'],
            ['nombre' => 'Vehículos', 'tipo' => 'activo_no_corriente'],
            ['nombre' => 'Mobiliario y Equipos', 'tipo' => 'activo_no_corriente'],
            ['nombre' => 'Depreciación Acumulada', 'tipo' => 'activo_no_corriente'],
            ['nombre' => 'Inversiones a Largo Plazo', 'tipo' => 'activo_no_corriente'],
            ['nombre' => 'Patentes', 'tipo' => 'activo_no_corriente'],
            ['nombre' => 'Marcas', 'tipo' => 'activo_no_corriente'],
            ['nombre' => 'Licencias', 'tipo' => 'activo_no_corriente'],
            ['nombre' => 'Cuentas por Cobrar Diversas (Largo Plazo)', 'tipo' => 'activo_no_corriente'],

            // Pasivos Corrientes
            ['nombre' => 'Cuentas por pagar a proveedores', 'tipo' => 'pasivo_corriente'],
            ['nombre' => 'Préstamos bancarios a corto plazo', 'tipo' => 'pasivo_corriente'],
            ['nombre' => 'Documentos por Pagar (Corto Plazo)', 'tipo' => 'pasivo_corriente'],
            ['nombre' => 'Impuestos por Pagar', 'tipo' => 'pasivo_corriente'],
            ['nombre' => 'Obligaciones Laborales (Corto Plazo)', 'tipo' => 'pasivo_corriente'],
            ['nombre' => 'Anticipos de Clientes', 'tipo' => 'pasivo_corriente'],

            // Pasivos No Corrientes
            ['nombre' => 'Préstamos bancarios a largo plazo', 'tipo' => 'pasivo_no_corriente'],
            ['nombre' => 'Documentos por Pagar (Largo Plazo)', 'tipo' => 'pasivo_no_corriente'],
            ['nombre' => 'Provisiones para Beneficios a Largo Plazo', 'tipo' => 'pasivo_no_corriente'],

            // Patrimonio
            ['nombre' => 'Capital Social', 'tipo' => 'patrimonio'],
            ['nombre' => 'Reservas Legales', 'tipo' => 'patrimonio'],
            ['nombre' => 'Resultados Acumulados', 'tipo' => 'patrimonio'],
            ['nombre' => 'Utilidades del Ejercicio', 'tipo' => 'patrimonio'],
            ['nombre' => 'Pérdidas del Ejercicio', 'tipo' => 'patrimonio'],
        ];

        // Iterar sobre cada cuenta y crearla solo si no existe
        foreach ($cuentas as $cuenta) {
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

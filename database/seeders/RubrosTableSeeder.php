<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RubrosTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Insertar rubros en la tabla 'rubros'
        DB::table('rubros')->insert([
            ['nombre' => 'Comercial', 'created_at' => now(), 'updated_at' => now()],
            ['nombre' => 'Industrial', 'created_at' => now(), 'updated_at' => now()],
            ['nombre' => 'Agropecuaria', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}

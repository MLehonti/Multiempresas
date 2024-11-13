<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define los comandos que puede registrar tu aplicación.
     */
    protected $commands = [
        // Agrega tus comandos personalizados aquí si es necesario.
    ];

    /**
     * Define las tareas programadas para tu aplicación.
     */
    protected function schedule(Schedule $schedule)
    {
        // Programa un respaldo diario
        $schedule->command('backup:run')->daily();
    }

    /**
     * Registrar los comandos para la aplicación.
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}

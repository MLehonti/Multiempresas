<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Collection;
use Spatie\Backup\Tasks\Backup\BackupJob;
use Spatie\Backup\Config\Config as BackupConfig;

class BackupController extends Controller
{
    
    //
    public function index(){
        // Obtén los backups desde el sistema de archivos o desde donde los almacenes.
        $backups = Storage::disk('local')->files('Laravel'); // Asegúrate de que esta ruta sea la correcta.

        // Si deseas asegurarte de que sea un array (aunque podrías convertirlo a una colección si lo prefieres)
        $backups = collect($backups); // Convertir a colección para usar métodos como isNotEmpty()

        return view('backups.index', compact('backups'));

    }

    // Método para descargar un backup específico
    public function download($backup)
    {
        // Ruta completa relativa al disco 'local'
        $filePath = 'Laravel/' . $backup;

        // Verifica si el archivo existe en el disco
        if (Storage::disk('local')->exists($filePath)) {
            // Devuelve el archivo para ser descargado
            return response()->download(storage_path("app/private/{$filePath}"));
        }

        // Si el archivo no existe, redirige con un mensaje de error
        return redirect()->route('backups.index')->with('error', 'El archivo no existe.');
    }

    

    // Método para eliminar un backup específico
    public function delete($backup)
{
    // Ruta completa del archivo de backup
    $filePath = 'Laravel/' . $backup;

    // Verifica si el archivo existe
    if (Storage::disk('local')->exists($filePath)) {
        // Elimina el archivo
        Storage::disk('local')->delete($filePath);

        // Redirige a la lista de backups con un mensaje de éxito
        return redirect()->route('backups.index')->with('success', 'Backup eliminado correctamente.');
    }

    // Si el archivo no existe, redirige con un mensaje de error
    return redirect()->route('backups.index')->with('error', 'El archivo no existe.');
}

}

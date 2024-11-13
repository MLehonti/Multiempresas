<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\View;
use Spatie\Activitylog\Models\Activity;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\Bitacoraexport;

class BitacoraController extends Controller
{
    //
    public function index(Request $request)
    {
        $start_date = $request->input('start_date');
        $end_date = $request->input('end_date');

        if ($start_date && $end_date) {
            //Se proporcionó un rango de fechas, aplicar el filtro
            $activities = Activity::whereDate('created_at', '>=', $start_date)
                ->whereDate('created_at', '<=', $end_date)
                ->orderBy('created_at', 'desc')
                ->get();
        } else {
            // No se proporcionó un rango de fechas, cargar todas las actividades
            $activities = Activity::orderBy('created_at', 'desc')->get();
        }

        if ($request->ajax()) {
            $view = View::make('partials.activities_table', compact('activities'))->render();

            return response()->json([
                'view' => $view
            ]);
        }

        return view('bitacora.index', compact('activities'));
    }


    public function eliminar(Request $request)
    {
        // Validar las fechas
        $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date',
        ]);

        // Obtener las fechas desde la solicitud
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        // Eliminar las actividades que estén dentro del rango de fechas
        Activity::whereDate('created_at', '>=', $startDate)
            ->whereDate('created_at', '<=', $endDate)
            ->delete();

        // Redirigir con un mensaje de éxito
        return redirect()->route('bitacora.index')->with('success', 'Actividades eliminadas correctamente.');
    }

    public function destroy($id)
    {
        // Buscar la actividad por su ID
        $activity = Activity::findOrFail($id);

        // Eliminar la actividad
        $activity->delete();

        // Redirigir al índice con un mensaje de éxito
        return redirect()->route('bitacora.index')->with('success', 'Actividad eliminada correctamente.');
    }
}

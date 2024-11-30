<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Depreciacion extends Model
{
    use HasFactory;

    // Tabla de la base de datos
    protected $table = 'depreciaciones';

    // Atributos que se pueden asignar masivamente
    protected $fillable = [
        'nombre',
        'porcentaje_depreciacion',
        'descripcion',
    ];

    // Relación muchos a muchos con la tabla cuentas a través de la tabla intermedia cuenta_depreciacion
    public function cuentas()
    {
        return $this->belongsToMany(Cuenta::class, 'cuenta_depreciacion');
    }
}

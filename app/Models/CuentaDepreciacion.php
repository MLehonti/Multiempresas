<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CuentaDepreciacion extends Model
{
    use HasFactory;

    // No es necesario especificar una tabla, Laravel lo hará automáticamente
    // porque la convención para las tablas intermedias es el nombre en plural de
    // ambos modelos relacionados, en este caso: 'cuenta_depreciacion'

    // Si no quieres usar la convención, puedes especificar la tabla manualmente:
    protected $table = 'cuenta_depreciacion';

    // Si no se usan timestamps en la tabla, puedes deshabilitarlo:
    public $timestamps = true;  // o false si no usas timestamps

    // Define las relaciones con los modelos correspondientes
    public function cuenta()
    {
        return $this->belongsTo(Cuenta::class);
    }

    public function depreciacion()
    {
        return $this->belongsTo(Depreciacion::class);
    }
}

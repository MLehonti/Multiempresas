<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Asiento extends Model
{
    use HasFactory;
    protected $table = 'asientos'; // Asegúrate de que coincide con el nombre de la tabla en la base de datos


    protected $fillable = ['empresa_id', 'cuenta_origen_id', 'cuenta_destino_id', 'monto', 'debe', 'haber', 'fecha'];

    public function cuentaOrigen()
    {
        return $this->belongsTo(Cuenta::class, 'cuenta_origen_id');
    }

    public function cuentaDestino()
    {
        return $this->belongsTo(Cuenta::class, 'cuenta_destino_id');
    }

    public function empresa()
    {
        return $this->belongsTo(Empresa::class);
    }
}

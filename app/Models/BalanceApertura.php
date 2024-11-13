<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BalanceApertura extends Model
{
    use HasFactory;

    // Especificar el nombre exacto de la tabla
    protected $table = 'balance_apertura';

    // Desactivar la conversión a plural
    public $timestamps = true;
    public $incrementing = true;
    protected $primaryKey = 'id';

    // Campos que pueden ser llenados
    protected $fillable = ['empresa_id', 'fecha'];

    // Relaciones
    public function empresa()
    {
        return $this->belongsTo(empresa::class);
    }

    public function detalles()
    {
        return $this->hasMany(DetalleBalance::class, 'balance_id', 'id');
    }
}

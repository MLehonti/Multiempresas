<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BalanceAperturaCopia extends Model
{
    use HasFactory;

    protected $table = 'balance_apertura_copia';

    protected $fillable = ['empresa_id', 'fecha'];

    public function detalles()
    {
        return $this->hasMany(DetalleBalanceCopia::class, 'balance_id', 'id');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetalleBalanceCopia extends Model
{
    use HasFactory;

    protected $table = 'detalles_balance_copia';

    protected $fillable = ['balance_id', 'cuenta_id', 'debe', 'haber'];

    public function balance()
    {
        return $this->belongsTo(BalanceAperturaCopia::class, 'balance_id', 'id');
    }

    public function cuenta()
    {
        return $this->belongsTo(Cuenta::class, 'cuenta_id', 'id');
    }
}
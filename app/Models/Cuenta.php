<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cuenta extends Model
{
    use HasFactory;

    protected $fillable = ['nombre', 'tipo'];

    public function detallesBalance()
    {
        return $this->hasMany(DetalleBalance::class);
    }

    public function depreciaciones()
{
    return $this->belongsToMany(Depreciacion::class, 'cuenta_depreciacion');
}
}

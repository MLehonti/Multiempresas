<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Empresa;

class Rubro extends Model
{

    use HasFactory;

    protected $fillable = ['nombre'];

    // Relación con Empresas
    public function empresas()
    {
        return $this->hasMany(Empresa::class);
    }
}

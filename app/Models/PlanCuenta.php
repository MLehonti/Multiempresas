<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PlanCuenta extends Model
{
    use HasFactory;

    protected $fillable = ['empresa_id', 'user_id', 'fecha'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function empresa()
    {
        return $this->belongsTo(Empresa::class);
    }

    public function detalles()
    {
        return $this->hasMany(DetallePlanCuenta::class);
    }
}


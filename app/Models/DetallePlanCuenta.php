<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetallePlanCuenta extends Model
{
    use HasFactory;

    protected $fillable = ['plan_cuenta_id', 'cuenta_id'];

    public function planCuenta()
    {
        return $this->belongsTo(PlanCuenta::class);
    }

    public function cuenta()
    {
        return $this->belongsTo(Cuenta::class);
    }
}


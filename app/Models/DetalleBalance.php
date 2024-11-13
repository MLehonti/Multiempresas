<?php

// namespace App\Models;

// use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Illuminate\Database\Eloquent\Model;

// class DetalleBalance extends Model
// {
//     use HasFactory;

//     // Asegúrate de especificar el nombre de la tabla si es diferente a la convención de Laravel
//     protected $table = 'detalles_balance'; // Nombre correcto de la tabla en la base de datos

//     protected $fillable = ['balance_id', 'cuenta_id', 'debe', 'haber'];

//     public function balance()
//     {
//         return $this->belongsTo(BalanceApertura::class);
//     }

//     public function cuenta()
//     {
//         return $this->belongsTo(Cuenta::class);
//     }
// }


// Modelo: DetalleBalance.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Cuenta;

class DetalleBalance extends Model
{
    use HasFactory;

    protected $table = 'detalles_balance'; // Nombre exacto de la tabla
    protected $fillable = ['balance_id', 'cuenta_id', 'debe', 'haber'];

    public function balance()
    {
        return $this->belongsTo(BalanceApertura::class, 'balance_id', 'id');
    }

    public function cuenta()
    {
        return $this->belongsTo(Cuenta::class, 'cuenta_id', 'id');
    }
}


// class DetalleBalance extends Model
// {
//     use HasFactory;
//     protected $table = 'detalles_balance';


//     protected $fillable = ['balance_id', 'cuenta_id', 'debe', 'haber'];

//     public function balance()
//     {
//         return $this->belongsTo(BalanceApertura::class, 'balance_id', 'id');
//     }

//     public function cuenta()
//     {
//         return $this->belongsTo(Cuenta::class, 'cuenta_id', 'id'); // Verificar que esté correctamente relacionado con la tabla cuentas
//     }
// }

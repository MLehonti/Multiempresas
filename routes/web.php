<?php

use App\Http\Controllers\ActivoFijoController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EmpresaController;
use App\Http\Controllers\BalanceAperturaController;
use App\Http\Controllers\BitacoraController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\PlanCuentasController;
use App\Http\Controllers\SubscriptionController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CuentaController;
use App\Http\Controllers\BackupController;      //controlador del backup
use App\Http\Controllers\AsientoController;
use App\Http\Controllers\EstadoResultadosController;



Route::get('/', function () {
    return view('welcome');
});



Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');


Route::get('/plan-cuentas/export/excel', [PlanCuentasController::class, 'exportExcel'])->name('plan-cuentas.export.excel');


Route::get('/plan-cuentas/export-pdf', [PlanCuentasController::class, 'exportPdf'])->name('plan-cuentas.export.pdf');

Route::get('/libro-diario/exportar-excel/{empresa_id}', [AsientoController::class, 'exportarExcel'])->name('libro_diario.exportarExcel');



// ruta  para exportar pdf de usuario
Route::get('/users/export-pdf', [UserController::class, 'exportPdf'])->name('users.export.pdf');

//ruta para exportar exel de usuario
Route::get('/users/export-excel', [UserController::class, 'exportExcel'])->name('users.export.excel');

// ruta  para exportar pdf de empresa
Route::get('/empresas/export-pdf', [EmpresaController::class, 'exportPdf'])->name('empresas.export.pdf');

//ruta para descargar el exel
Route::get('/empresas/export/excel', [EmpresaController::class, 'exportExcel'])->name('empresas.export.excel');




//todas estas 6 rutas es para subscripciion y metodos de pago

Route::get('/precio', [SubscriptionController::class, 'index'])->name('subscription.index');
Route::get('/registrar', [SubscriptionController::class, 'registrar'])->name('subscription.registrar');
Route::post('/storeuser', [SubscriptionController::class, 'storeuser'])->name('subscription.storeuser');
Route::get('/pagar', [SubscriptionController::class, 'pagar'])->name('subscription.pagar');
Route::post('/create-payment', [SubscriptionController::class, 'createPayment'])->name('subscription.payment');
Route::get('/list', [SubscriptionController::class, 'listPayments'])->name('subscription.list');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/empresa/create', [EmpresaController::class, 'create'])->name('empresa.create');
    Route::post('/empresa', [EmpresaController::class, 'store'])->name('empresa.store');
    Route::get('/empresa/{empresa}', [EmpresaController::class, 'show'])->name('empresa.show');


    //Ruta para mostrar todos los usruaios del sistema
    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    //Ruta para eliminar un usaurio
    Route::delete('/users/{id}', [UserController::class, 'destroy'])->name('users.destroy');


    //Rutas para los roles
    Route::get('/users/{id}/assign-role', [RoleController::class, 'assignRole'])->name('users.assign-role');
    Route::post('/users/{id}/assign-role', [RoleController::class, 'storeRole'])->name('users.store-role');




    // Ruta para mostrar la lista de todas las empresas
    Route::get('/empresas', [EmpresaController::class, 'index'])->name('empresas.index');



    // Ruta para eliminar la empresa
    Route::delete('/empresa/{empresa}', [EmpresaController::class, 'destroy'])->name('empresa.destroy');


    //rutas para balance de apertuta

    Route::get('/empresa/{empresa_id}/balance', [BalanceAperturaController::class, 'create'])->name('balance.create');
    Route::post('/balance/store', [BalanceAperturaController::class, 'store'])->name('balance.store');
    Route::get('/empresa/{empresa_id}/balance/show', [BalanceAperturaController::class, 'show'])->name('balance.show');

    Route::delete('/balance/{id}', [BalanceAperturaController::class, 'destroy'])->name('balance.destroy');



    //rutas Rara el plan de cuentas

    Route::get('/plan-cuentas', [PlanCuentasController::class, 'index'])->name('plan-cuentas.index');
    Route::get('/plan-cuentas/create', [PlanCuentasController::class, 'create'])->name('plan-cuentas.create');
    Route::post('/plan-cuentas/store', [PlanCuentasController::class, 'store'])->name('plan-cuentas.store');

    //Rutas de la bitacora
    Route::resource('bitacora', BitacoraController::class);

    //rutas para las cuentas
    Route::get('/cuentas', [CuentaController::class, 'index'])->name('cuentas.index');
    Route::post('/cuentas', [CuentaController::class, 'store'])->name('cuentas.store');
    Route::put('/cuentas/{id}', [CuentaController::class, 'update'])->name('cuentas.update');
    Route::get('/cuentas/{id}', [CuentaController::class, 'show']);
    Route::delete('/cuentas/{id}', [CuentaController::class, 'destroy'])->name('cuentas.destroy');

    // Rutas para la gestión de backups
    // Ruta para mostrar la lista de backups
    Route::get('/backups', [BackupController::class, 'index'])->name('backups.index');
    // Ruta para descargar un backup
    Route::get('/backups/download/{backup}', [BackupController::class, 'download'])->name('backups.download');
    // Ruta para eliminar un backup
    Route::delete('/backups/delete/{backup}', [BackupController::class, 'delete'])->name('backups.delete');



    //rutas para asientos contables:
    Route::get('asientos/create/{empresa_id}', [AsientoController::class, 'create'])->name('asientos.create');
    Route::post('asientos/store', [AsientoController::class, 'store'])->name('asientos.store');
    Route::get('/detalles-balance-copia/{empresa_id}', [AsientoController::class, 'mostrarDetallesBalanceCopia'])->name('detalles_balance_copia.index');

    Route::get('/libro-diario/{empresa_id}', [AsientoController::class, 'libroDiario'])->name('libro_diario.index');



    //ruta para estado de resultado:
    Route::get('/estado-resultados', [EstadoResultadosController::class, 'index'])->name('estado_resultados.index');
    Route::get('/estado-resultados/exportar-excel', [EstadoResultadosController::class, 'exportarExcel'])->name('estado_resultados.exportarExcel');

    Route::get('/depreciaciones', [ActivoFijoController::class, 'index'])->name('activo_fijo.index');

    Route::post('/cuentas/{cuentaId}/depreciacion', [ActivoFijoController::class, 'storeDepreciacion'])->name('depreciacion.store');
    Route::get('depreciacioness', [ActivoFijoController::class, 'show'])->name('depreciacion.show');
    Route::get('/aplicar-depreciacion', [ActivoFijoController::class, 'aplicarDepreciacion'])->name('aplicar.depreciacion');
// Ruta para eliminar la depreciación
Route::delete('/depreciacion/{id}', [ActivoFijoController::class, 'eliminarDepreciacion'])->name('eliminar.depreciacion');

});




//RUTAS PARA USAR EN FLUTTER
Route::get('/loginjson', [AuthController::class, 'loginjson']);
require __DIR__.'/auth.php';

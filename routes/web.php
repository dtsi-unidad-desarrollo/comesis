<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{
    ComensaleController,
    UserController,
    DashboardController,
    EntradaController,
    ProfesoreController,
    EstudianteController,
    LoginController,
    RecepcionController,
    RepresentanteController,
    ServicioController,
};


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', [LoginController::class, 'index'])->middleware('guest');

Route::get('/login', [LoginController::class, 'index'])->name('login.index')->middleware('guest');
Route::post('/login', [LoginController::class, 'store'])->name('login.store');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Route::resource('/login', LoginController::class)->names('login')->middleware('guest');



Route::middleware('auth', 'validarRol')->group(function () {
    /** Tablero estadistico */
    Route::get('/panel', [DashboardController::class, 'index'])->name('admin.panel.index');

    /** Vista de recepción */
    Route::get('/recepcion', [RecepcionController::class, 'index'])->name('admin.recepcion.index');

    /** Control de entrada */
    Route::post('reportes', [EntradaController::class, 'getReporte'])->name('admin.entradas.reporte');
    Route::resource('/entradas', EntradaController::class)->names('admin.entradas');

    /** Control de servicios */
    Route::resource('/servicios', ServicioController::class)->names('admin.servicios');
    
    /** Rutas de controlador usuarios */
    Route::resource('/users', UserController::class)->names('admin.users');
    
    /** Rutas de Comensales */
    Route::resource('/comensales', ComensaleController::class)->names('admin.comensales');

    /** Ruta para sincronizar data con dux actualmente desactivada */
    Route::post('/sincronizarData', [ComensaleController::class, 'sincronizarData'])->name('admin.sincronizarData');
 
});
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
    AtmController,
    RoleController,
    PermisoController,
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
    Route::get('/api/dashboard-stats', [DashboardController::class, 'getStats'])->name('admin.dashboard.stats');

    /** Vista de recepción */
    Route::get('/recepcion', [RecepcionController::class, 'index'])->name('admin.recepcion.index');

    /** Control de entrada */
    Route::post('reportes', [EntradaController::class, 'getReporte'])->name('admin.entradas.reporte');
    Route::post('reportes-semanal', [EntradaController::class, 'getReporteSemanal'])->name('admin.entradas.reporte.semanal');
    Route::post('reportes-semanas-mes', [EntradaController::class, 'getReporteSemanasMes'])->name('admin.entradas.reporte.semanas.mes');
    Route::post('reportes-mensual', [EntradaController::class, 'getReporteMensual'])->name('admin.entradas.reporte.mensual');
    Route::resource('/entradas', EntradaController::class)->names('admin.entradas');

    /** Control de servicios */
    Route::resource('/servicios', ServicioController::class)->names('admin.servicios');
    /** Control de ATMs */
    Route::resource('/atms', AtmController::class)->names('admin.atms');
    Route::post('/atms/{atm}/open', [AtmController::class, 'open'])->name('admin.atms.open');
    
    /** Control de roles y permisos */
    Route::resource('/roles', RoleController::class)->names('admin.roles');
    Route::resource('/permisos', PermisoController::class)->names('admin.permisos');
    
    /** Rutas de controlador usuarios */
    Route::resource('/users', UserController::class)->names('admin.users');
    
    /** Rutas de Comensales */
    // Ruta para importación masiva desde Excel (registrada antes del resource para evitar conflicto con rutas parameterizadas)
    Route::post('/comensales/import', [ComensaleController::class, 'import'])->name('admin.comensales.import');
    // Descarga de plantilla en formato XLSX (solo encabezados)
    Route::get('/comensales/template.xlsx', [ComensaleController::class, 'downloadTemplateXlsx'])->name('admin.comensales.template.xlsx');
    // Exportar comensales registrados a Excel (xlsx)
    Route::get('/comensales/export', [ComensaleController::class, 'export'])->name('admin.comensales.export');
   
    Route::resource('/comensales', ComensaleController::class)->names('admin.comensales');

    /** Ruta para sincronizar data con dux actualmente desactivada */
    Route::post('/sincronizarData', [ComensaleController::class, 'sincronizarData'])->name('admin.sincronizarData');
 
});
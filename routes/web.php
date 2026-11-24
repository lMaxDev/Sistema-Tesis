<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ventasController;
use App\Http\Controllers\productosController;
use App\Http\Controllers\perfilController;
use App\Http\Controllers\empleadosController;
use App\Http\Controllers\loginController;
use App\Http\Controllers\estadisticasController;
use App\Http\Controllers\metricasController;


//VENTAS
Route::get('/', [ventasController::class, 'index'])->middleware('auth')->name('ventas.index');
Route::get('/ventas/registrar', [ventasController::class, 'registrarVenta'])->name('ventas.registrarVenta');
Route::get('/ventas/autocompletar', [ventasController::class, 'ventasAutocompletar'])->name('ventas.autocompletar');
Route::get('/ventas/vendedorAutocompletar', [ventasController::class, 'vendedorAutocompletar'])->name('ventas.vendedorAutocompletar');
Route::post('/ventas/crearPedido', [ventasController::class, 'crearPedido'])->name('ventas.crearPedido');
//Ventas - Detalles de venta
Route::get('/ventas/detalles/{id}', [ventasController::class, 'detalles'])->name('ventas.detalles');
//Ventas - Buscar ventas
Route::get('/ventas/buscar', [ventasController::class, 'buscarPedido'])->name('ventas.buscar');
//Ventas - Factura
Route::get('/ventas/factura/{id}', [ventasController::class, 'factura'])->name('ventas.factura');



//PRODUCTOS
Route::get('/productos', [productosController::class, 'index'])->name('productos.index');
Route::get('/productos/nuevo', [productosController::class, 'nuevo'])->name('productos.nuevo');
Route::get('/productos/existentes', [productosController::class, 'existentes'])->name('productos.existentes');
//Productos - Crear nuevo producto
Route::post('/productos/crearNuevo', [productosController::class, 'crearNuevo'])->name('productos.crearNuevo');
//Productos - Agregar a productos existentes
Route::post('/productos/agregarExistentes', [productosController::class, 'agregarExistentes'])->name('productos.agregarExistentes');
Route::get('/productos/autocompletar', [productosController::class, 'productosAutocompletar'])->name('productos.autocompletar');
//Productos - Editar producto
Route::get('/productos/editar/{id}', [productosController::class, 'editar'])->name('productos.editar');
//Productos - Actualizar producto
Route::put('/productos/actualizar/{id}', [productosController::class, 'actualizar'])->name('productos.actualizar');
//Productos - Eliminar producto
Route::delete('/productos/eliminar/{id}', [productosController::class, 'eliminar'])->name('productos.eliminar');
//Productos - Refrescar productos
Route::get('/productos/refrescar', [productosController::class, 'refrescar'])->name('producto.refrescar');


//LOGIN
Route::get('/login', [loginController::class, 'index'])->name('login');
Route::get('/login/autenticar', [loginController::class, 'autenticar'])->name('login.autenticar');



//PERFIL
Route::get('/perfil', [perfilController::class, 'index'])->name('perfil.index');
Route::get('/logout', [perfilController::class, 'logout'])->name('perfil.logout');




/*Comprobar el cargo y autenticacion*/
Route::middleware(['auth', 'cargo:Administrador'])->group(function () {

//EMPLEADOS
Route::get('/empleados', [empleadosController::class, 'index'])->name('empleados.index');
Route::post('/empleados/crearEmpleado', [empleadosController::class, 'crearEmpleado'])->name('empleados.crearEmpleado');
Route::post('/empleados/empresa', [empleadosController::class, 'empresaUpdate'])->name('empleados.empresaUpdate');


//ESTADISTICAS
Route::get('/estadisticas', [estadisticasController::class, 'index'])->name('estadisticas.index');
Route::get('/estadisticas1', [estadisticasController::class, 'estadistica1'])->name('estadstica1.request');
Route::get('/estadisticas2', [estadisticasController::class, 'estadistica2'])->name('estadstica2.request');
Route::get('/estadisticas3', [estadisticasController::class, 'estadistica3'])->name('estadstica3.request');


//METRICAS
Route::get('/metricas', [metricasController::class, 'index'])->name('metricas.index');
Route::get('/metricas1', [metricasController::class, 'metrica1'])->name('metrica1.request');
Route::get('/metricas2', [metricasController::class, 'metrica2'])->name('metrica2.request');
Route::get('/metricaVendedor/{id}', [metricasController::class, 'metricaVendedor'])->name('metricaVendedor');
Route::get('/metricasVendedorMes/{id}', [metricasController::class, 'metricasVendedorMes'])->name('metricasVendedorMes.request');
Route::get('/graficaVendedorMes/{id}', [metricasController::class, 'graficaVendedorMes'])->name('graficaVendedorMes.request');
Route::get('/graficaVendedorAnual/{id}', [metricasController::class, 'graficaVendedorAnual'])->name('graficaVendedorAnual.request');
});
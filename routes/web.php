<?php

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

Route::get('/', function () {
    return view('contenido/contenido');
});

Route::get('/ingreso', 'IngresoController@index');
Route::post('/ingreso/registrar', 'IngresoController@store');
Route::put('/ingreso/actualizar', 'IngresoController@update');
Route::put('/ingreso/desactivar', 'IngresoController@desactivar');
Route::put('/ingreso/activar', 'IngresoController@activar');

Route::get('/entrega', 'EntregaController@index');
Route::post('/entrega/registrar', 'EntregaController@store');
Route::put('/entrega/actualizar', 'EntregaController@update');
Route::put('/entrega/desactivar', 'EntregaController@desactivar');
Route::put('/entrega/activar', 'EntregaController@activar');

Route::get('/promotor', 'PromotorController@index');
Route::post('/promotor/registrar', 'PromotorController@store');
Route::put('/promotor/actualizar', 'PromotorController@update');
Route::put('/promotor/desactivar', 'PromotorController@desactivar');
Route::put('/promotor/activar', 'PromotorController@activar');

Route::get('/departamento', 'DepartamentoController@index');
Route::post('/departamento/registrar', 'DepartamentoController@store');
Route::put('/departamento/actualizar', 'DepartamentoController@update');
Route::put('/departamento/desactivar', 'DepartamentoController@desactivar');
Route::put('/departamento/activar', 'DepartamentoController@activar');

Route::get('/provincia', 'ProvinciaController@index');
Route::post('/provincia/registrar', 'ProvinciaController@store');
Route::put('/provincia/actualizar', 'ProvinciaController@update');
Route::put('/provincia/desactivar', 'ProvinciaController@desactivar');
Route::put('/provincia/activar', 'ProvinciaController@activar');

Route::get('/libro', 'LibroController@index');
Route::post('/libro/registrar', 'LibroController@store');
Route::put('/libro/actualizar', 'LibroController@update');
Route::put('/libro/desactivar', 'LibroController@desactivar');
Route::put('/libro/activar', 'LibroController@activar');

Route::get('/proveedor', 'ProveedorController@index');
Route::post('/proveedor/registrar', 'ProveedorController@store');
Route::put('/proveedor/actualizar', 'ProveedorController@update');
Route::put('/proveedor/desactivar', 'ProveedorController@desactivar');
Route::put('/proveedor/activar', 'ProveedorController@activar');

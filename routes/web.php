<?php

use App\Http\Controllers\UserController;

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

// Cargando clases
use App\Http\Middleware\ApiAuthMiddleware;

Route::get('/', function () {
    return view('welcome');
});

// Rutas del controlador de usuarios 

Route::post('api/register', 'UserController@register');
Route::post('api/login', 'UserController@login');
Route::put('api/user/update', 'UserController@update');
Route::post('api/user/upload', 'UserController@upload')->middleware(ApiAuthMiddleware::class);
Route::get('api/user/avatar/{filename}', 'UserController@getImage');
Route::get('api/user/detail/{id}', 'UserController@detail');

// Rutas del controlador de categorias 

Route::resource('/api/category', 'CategoryController');

// Rutas del controlador de propiedades

Route::resource('/api/propiedad', 'PropiedadController');
Route::post('/api/propiedad/upload', 'PropiedadController@upload');
Route::post('/api/propiedad/uploadPrincipal', 'PropiedadController@uploadPrincipal');
Route::get('/api/propiedad/image/{filename}', 'PropiedadController@getImage');
Route::get('/api/propiedad/category/{id}', 'PropiedadController@getPropiedadesByCategory');

// Rutas del controlador de correos

Route::post('/api/contacto', 'CorreoController@correoContacto');
Route::post('/api/confiar-propiedad', 'CorreoController@correoConfiarPropiedad');

// Ruta buscador 

Route::post('/api/buscar', 'BusquedaController@buscador');
// Route::get('/api/buscar', 'BusquedaController@buscador');

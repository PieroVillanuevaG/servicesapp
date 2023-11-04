<?php

use Illuminate\Support\Facades\Route;
use \App\Http\Controllers\UsuarioController;
use \App\Http\Controllers\CustomerController;
use \App\Http\Controllers\DoctorsController;
use \App\Http\Controllers\CitaController;
use \App\Http\Controllers\UserStockController;
use \App\Http\Controllers\ProductController;
use \App\Http\Controllers\EspecialidadesController;
use \App\Http\Controllers\CentrosController;
use \App\Mail\MailController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});


/** APP CMI CESAR LOPEZ SILVA*/

Route::resource('user', UsuariosController::class);
Route::resource('customer', CustomerController::class);
Route::resource('doctor', DoctorsController::class);
Route::resource('cita', CitaController::class);
Route::resource('especialidades', EspecialidadesController::class);
Route::resource('centros', CentrosController::class);


/** APP CONTROL DE STOCK */

Route::resource('user2', UserStockController::class);
Route::resource('products', ProductController::class);


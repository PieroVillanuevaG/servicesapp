<?php

use Illuminate\Support\Facades\Route;
use \App\Http\Controllers\UsuarioController;
use \App\Http\Controllers\CustomerController;
use \App\Http\Controllers\DoctorsController;
use \App\Http\Controllers\CitaController;
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


/** APP CONTROL DE STOCK */


<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use \App\Http\Controllers\EmailController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

/** APP CMI CESAR LOPEZ SILVA*/

Route::group(['middleware' => ['cors']], function () {
    Route::prefix('users')->group(function () {
        Route::post('verify', 'UsuariosController@userVerify');
    });
    Route::prefix('doctors')->group(function () {
        Route::post('search', 'DoctorsController@searchDoctor');
    });
    Route::prefix('cita')->group(function () {
        Route::post('historial', 'CitaController@historial');
    });

});


/** APP CONTROL DE STOCK */

Route::group(['middleware' => ['cors']], function () {
    Route::prefix('users2')->group(function () {
        Route::post('verify', 'UserStockController@userVerify');
    });

    Route::prefix('products')->group(function () {
        Route::post('massive', 'ProductController@massive');
        Route::post('search', 'ProductController@search');
        Route::post('salida', 'ProductController@salida');
        Route::get('pdf/{id}', 'ProductController@pdf');
    });
});






Route::get('message', 'EmailController@sendEmail');


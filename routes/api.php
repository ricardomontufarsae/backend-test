<?php

use App\Http\Controllers\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
Route::controller(AuthController::class)->group(function () {
    Route::post('/register', 'register');
    Route::post('/login', 'login');
    Route::post('/logout', 'logout');
});

Route::middleware('auth:api')->group(function () {
    Route::controller(\App\Http\Controllers\ProductoController::class)->group(function () {
        Route::get('/productos', 'index');
        Route::post('/productos', 'store');
        Route::put('/productos/{id}', 'update');
        Route::delete('/productos/{id}', 'destroy');
    });

    Route::controller(\App\Http\Controllers\CategoriaController::class)->group(function (){
       Route::get('/categorias', 'index');
       Route::post('/categorias', 'store');
       Route::put('/categorias/{id}', 'update');
       Route::delete('/categorias/{id}', 'destroy');
    });

    Route::controller(\App\Http\Controllers\FacturaController::class)->group(function () {
        Route::get('/facturas', 'index');
        Route::post('/facturas', 'store');
        Route::put('/facturas/{id}', 'update');
        Route::delete('/facturas/{id}', 'destroy');
        Route::get('/facturas/productos-facturados', 'productosFacturados');
        Route::get('/facturas/numfac/{facturaNum}', 'show');

    });
});

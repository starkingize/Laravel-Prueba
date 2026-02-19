<?php


use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ProductController;

/*
|--------------------------------------------------------------------------
| API Routes: /api/login, /api/register, /api/logout, /api/user, /api/products, /api/products/{id}, /api/products/{id}/prices
|--------------------------------------------------------------------------
*/

// Rutas públicas de autenticación
Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);

// Rutas protegidas (requieren token Bearer)
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user', [AuthController::class, 'user']);

    // Productos
    Route::get('/products', [ProductController::class, 'index']);
    Route::post('/products', [ProductController::class, 'store']);
    Route::get('/products/{id}', [ProductController::class, 'show'])->where('id', '[0-9]+');
    Route::put('/products/{id}', [ProductController::class, 'update'])->where('id', '[0-9]+');
    Route::delete('/products/{id}', [ProductController::class, 'destroy'])->where('id', '[0-9]+');
    Route::get('/products/{id}/prices', [ProductController::class, 'prices'])->where('id', '[0-9]+');
    Route::post('/products/{id}/prices', [ProductController::class, 'storePrice'])->where('id', '[0-9]+');
});

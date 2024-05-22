<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\MerchantController;
use App\Http\Controllers\Api\CustomerController;
use App\Http\Controllers\Api\ProductController;

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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::post('auth/login', [AuthController::class, 'login']);
Route::post('auth/reg/customer', [AuthController::class, 'registerCustomer']);
Route::post('auth/reg/merchant', [AuthController::class, 'registerMerchant']);

Route::middleware('auth:api')->group(function () {
    // Rute yang dapat diakses oleh semua pengguna yang terautentikasi
    Route::get('products', [ProductController::class, 'index']);
    Route::get('products/{id}', [ProductController::class, 'show']);

    // Rute untuk super admin
    Route::middleware('role:super-admin')->group(function () {
        Route::get('merchants', [MerchantController::class, 'index']);
        Route::get('customers', [CustomerController::class, 'index']);
    });

    // Rute untuk customer
    Route::middleware('role:customer')->group(function () {
        //
    });

    // Rute untuk merchant
    Route::middleware('role:merchant')->group(function () {
       Route::post('products', [ProductController::class, 'store']);
       Route::put('products/{id}', [ProductController::class, 'update']);
       Route::delete('products/{id}', [ProductController::class, 'delete']);
    });
});

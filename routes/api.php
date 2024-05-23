<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ClaimRewardController;
use App\Http\Controllers\Api\MerchantController;
use App\Http\Controllers\Api\CustomerController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\RewardController;
use App\Http\Controllers\Api\TransactionController;

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

Route::post('auth/login', [AuthController::class, 'login']);
Route::post('auth/reg/customer', [AuthController::class, 'registerCustomer']);
Route::post('auth/reg/merchant', [AuthController::class, 'registerMerchant']);


Route::middleware('auth:api')->group(function () {
    // Route yang dapat diakses oleh semua pengguna yang terautentikasi
    Route::get('products', [ProductController::class, 'index']);
    Route::get('products/{id}', [ProductController::class, 'show']);
    Route::get('rewards', [RewardController::class, 'index']);
    Route::get('rewards/{id}', [RewardController::class, 'show']);

    // Route untuk super admin
    Route::middleware('role:super-admin')->group(function () {
        Route::get('merchants', [MerchantController::class, 'index']);
        Route::get('customers', [CustomerController::class, 'index']);
        Route::post('rewards', [RewardController::class, 'store']);
        Route::put('rewards/{id}', [RewardController::class, 'update']);
        Route::delete('rewards/{id}', [RewardController::class, 'destroy']);
    });

    // Route untuk customer
    Route::middleware('role:customer')->group(function () {
        Route::get('/transactions', [TransactionController::class, 'index']);
        Route::post('/transactions', [TransactionController::class, 'store']);
        Route::post('/claim-rewards', [ClaimRewardController::class, 'claimReward']);
    });

    // Route untuk merchant
    Route::middleware('role:merchant')->group(function () {
       Route::post('products', [ProductController::class, 'store']);
       Route::put('products/{id}', [ProductController::class, 'update']);
       Route::delete('products/{id}', [ProductController::class, 'delete']);
       Route::get('/history', [TransactionController::class, 'getMerchantHistoryTransaction']);
    });
});

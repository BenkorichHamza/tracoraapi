<?php

use App\Http\Controllers\Api\v1\AuthController;
use App\Http\Controllers\Api\v1\BrandController;
use App\Http\Controllers\Api\v1\CategoryController;
use App\Http\Controllers\Api\v1\ContactController;
use App\Http\Controllers\Api\v1\MoneyTransactionController;
use App\Http\Controllers\Api\v1\ProductController;
use App\Http\Controllers\Api\v1\StransactionController;
use App\Http\Controllers\Api\v1\SyncLogController;
use App\Http\Controllers\Api\v1\WarehouseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
Route::prefix('v1')->group(function () {

    // AUTH (public)
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);

    Routes::get('/roles', [AuthController::class, 'roles']);

    // PROTECTED ROUTES
    Route::middleware('auth:sanctum')->group(function () {

    Route::post('/logout', [AuthController::class, 'logout']);

    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    Route::apiResource('brands', BrandController::class);
    Route::apiResource('categories', CategoryController::class);
    Route::apiResource('contacts', ContactController::class);
    Route::apiResource('products', ProductController::class);
    Route::apiResource('warehouses', WarehouseController::class);
    Route::apiResource('stransactions', StransactionController::class);
    Route::apiResource('mtransactions', MoneyTransactionController::class);
    Route::post('/push', [SyncLogController::class, 'push']);
    Route::get('/syncproducts', [ProductController::class, 'syncProduct']);
    Route::get('/syncwarehouses', [WarehouseController::class, 'sync']);
    Route::get('/synccontacts', [ContactController::class, 'sync']);
    Route::get('/syncmoneytransactions', [MoneyTransactionController::class, 'sync']);
    Route::get('/syncstransactions', [StransactionController::class, 'sync']);
    // Route::get('/money-transactions/{moneyTransaction}', [MoneyTransactionController::class, 'show']);
    Route::post('/users/{user}/link',[AuthController::class,'linkContact']);
    Route::post('/users/{user}/unlink',[AuthController::class,'linkContact']);

    });
});

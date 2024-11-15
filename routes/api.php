<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProductsController;
use App\Http\Controllers\StoreController;
use App\Http\Controllers\StoreproductController;
use App\Http\Controllers\StocktransferController;
use App\Http\Controllers\OrdersController;

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

Route::post('/register', [UserController::class, 'register']);
Route::post('/login',[UserController::class, 'login']);
Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});


Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::get('/products', [ProductsController::class, 'index']);
    Route::post('/products', [ProductsController::class, 'store']);
    Route::put('/products/{id}', [ProductsController::class, 'update']);
    Route::delete('/products/{id}', [ProductsController::class, 'destroy']);
    Route::post('/logout', [UserController::class, 'logout']);
    Route::get('/stores', [StoreController::class,'index']);
    Route::post('/stores', [StoreController::class, 'store']);
    Route::get('/stores/{store_id}/products',[StoreproductController::class, 'show']);
    Route::post('/stores/{store_id}/products',[StoreproductController::class, 'update']);
    Route::post('/stock-transfers',[StocktransferController::class,'store']);
    Route::put('/stock-transfers',[StocktransferController::class,'updateStockTransfer']);
    Route::get('/stock-transfers',[StocktransferController::class,'index']);
    Route::get('/orders',[OrdersController::class,'index']);
    Route::post('/orders',[OrdersController::class,'store']);
    Route::put('/orders/{id}',[OrdersController::class,'update']);
    Route::get('/orders/{id}',[OrdersController::class,'show']);
    Route::get('reports/inventory',[StoreController::class,'productStockStatus']);
    Route::get('reports/sales',[StoreController::class,'storeSales']);
    Route::get('reports/transfers',[StocktransferController::class,'reportOfStockTransfer']);
});
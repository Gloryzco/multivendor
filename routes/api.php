<?php

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\StoreController;
use App\Http\Controllers\VendorController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;

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

Route::group(['middleware' => ['role:superadmin', 'auth:sanctum'], 'prefix'=> 'users'], function () {
    Route::get('', [UserController::class, 'show']);
    Route::post('', [UserController::class, 'add']);
    Route::patch('{id}', [UserController::class, 'activateOrDeactivate']);
    Route::delete('{id}', [UserController::class, 'delete']);
});

Route::group(['middleware' => ['role:vendor', 'auth:sanctum'], 'prefix' => 'store'], function(){
    // Route::get('store', [StoreController::class, 'getAll'])->middleware('role:superadmin');
    Route::get('', [StoreController::class, 'getMyStores']);
    Route::get('{id}', [StoreController::class, 'getStoreById']);
    Route::post('', [StoreController::class, 'add']);
    Route::put('{id}', [StoreController::class, 'edit']);
    Route::delete('{id}', [StoreController::class, 'delete']);
});

Route::group(['middleware' => ['role:admin|superadmin', 'auth:sanctum'], 'prefix' => 'category'], function(){
    Route::post('', [CategoryController::class, 'add']);
    Route::put('{id}', [CategoryController::class, 'edit']);
    Route::delete('{id}', [CategoryController::class, 'delete']);
});

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout']);
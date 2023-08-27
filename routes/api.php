<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CollectionController;
use App\Http\Controllers\ContributorController;

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

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);

    Route::prefix('collections')->group(function () {
        Route::get('/',  [CollectionController::class, 'index']);
        Route::post('/', [CollectionController::class, 'store']);
        Route::get('{collection}', [CollectionController::class, 'show']);
        Route::patch('{collection}', [CollectionController::class, 'update']);
        Route::delete('{collection}', [CollectionController::class, 'destroy']);
        Route::post('{collection}/contributors', [ContributorController::class, 'store']);
    });
    
    Route::prefix('contributors')->group(function () {
        Route::get('/', [ContributorController::class, 'index']);
        Route::get('{contributor}', [ContributorController::class, 'show']);
        Route::patch('{contributor}', [ContributorController::class, 'update']);
        Route::delete('{contributor}', [ContributorController::class, 'destroy']);
    });
});

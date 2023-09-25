<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WebsiteController;
use App\Http\Controllers\WebsitePageController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
| for errors add Accept and application/json to headers
|
*/

Route::post('/sign-in', [AuthController::class, 'signIn']);

//user-routs
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/sign-up', [AuthController::class, 'signUp']);
    Route::post('/sign-off', [AuthController::class, 'signOff']);
    Route::put('/user/update', [UserController::class, 'update']);
    Route::put('/user/update-role', [UserController::class, 'updateRole']);
    Route::delete('/user/delete', [UserController::class, 'destroy']);
    Route::delete('/user/delete-user', [UserController::class, 'destroyUser']);
});

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/new-webiste', [WebsiteController::class, 'store']);
    Route::get('/websites', [WebsiteController::class, 'index']);
    Route::get('/website', [WebsiteController::class, 'show']);
});

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/pages', [WebsitePageController::class, 'store']);
});

<?php

use App\Http\Controllers\API\CompanyController;
use App\Http\Controllers\API\UserController;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/company', [CompanyController::class, 'all']);
Route::post('/auth/login', [UserController::class, 'login']);
Route::post('/auth/register', [UserController::class, 'register']);
Route::post('/auth/logout', [UserController::class, 'logout'])->middleware('auth:sanctum');
Route::get('/auth/user', [UserController::class, 'fetch'])->middleware('auth:sanctum');
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

// company
Route::get('/company/company', [CompanyController::class, 'all']);
Route::post('/company/company', [CompanyController::class, 'create'])->middleware('auth:sanctum');

// auth
Route::post('/auth/login', [UserController::class, 'login']);
Route::post('/auth/register', [UserController::class, 'register']);
Route::post('/auth/logout', [UserController::class, 'logout'])->middleware('auth:sanctum');
Route::get('/auth/user', [UserController::class, 'fetch'])->middleware('auth:sanctum');
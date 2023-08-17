<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\UserController;
use \App\Http\Controllers\Api\TestController;

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

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::group(['middleware' => ['role:ADMIN']], function () {
        Route::get('/users', [UserController::class, 'getAll']);

        //test
        Route::post('/test', [TestController::class, 'create']);
        Route::put('/test', [TestController::class, 'update']);
        Route::delete('/test', [TestController::class, 'delete']);
    });
    Route::get('/tests', [TestController::class, 'getAll']);
    Route::get('/test/{testId}/questions', [TestController::class, 'testQuestions']);
});
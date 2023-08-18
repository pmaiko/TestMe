<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\TestController;
use App\Http\Controllers\Api\QuestionController;
use App\Http\Controllers\Api\FavoriteController;
use App\Http\Controllers\Api\ResultController;

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
        //question
        Route::post('/question', [QuestionController::class, 'create']);
        Route::put('/question', [QuestionController::class, 'update']);
        Route::delete('/question', [QuestionController::class, 'delete']);
    });
    Route::get('/tests', [TestController::class, 'getAll']);
    Route::get('/test/{testId}', [TestController::class, 'test']);
    //favorite
    Route::get('/favorites', [FavoriteController::class, 'getAll']);
    Route::post('/favorite', [FavoriteController::class, 'create']);
    Route::delete('/favorite', [FavoriteController::class, 'delete']);
    //result
    Route::get('/results', [ResultController::class, 'getAll']);
    Route::post('/result', [ResultController::class, 'create']);
});
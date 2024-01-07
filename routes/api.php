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
  Route::get('/user', [AuthController::class, 'user']);
  Route::post('/logout', [AuthController::class, 'logout']);

  Route::group(['middleware' => ['role:ADMIN']], function () {
      Route::get('/users', [UserController::class, 'index']);

      //test
      Route::post('/test', [TestController::class, 'create']);
      Route::put('/test', [TestController::class, 'update']);
      Route::delete('/test', [TestController::class, 'delete']);
      //question
      Route::get('/question/{questionId}', [QuestionController::class, 'index']);
      Route::post('/question', [QuestionController::class, 'create']);
      Route::put('/question', [QuestionController::class, 'update']);
      Route::delete('/question', [QuestionController::class, 'delete']);
  });

  Route::get('/tests', [TestController::class, 'index']);
  Route::get('/test/{testId}', [TestController::class, 'test']);
  Route::get('/test/{testId}/testing', [TestController::class, 'testing']);
  Route::post('/test/{testId}/complete', [TestController::class, 'complete']);

  //favorite
  Route::get('/favorites', [FavoriteController::class, 'index']);
  Route::post('/favorite', [FavoriteController::class, 'create']);
  Route::delete('/favorite', [FavoriteController::class, 'delete']);

  //result
  Route::get('/results/tests', [ResultController::class, 'tests']);
  Route::get('/results/{testId}', [ResultController::class, 'attempts']);
  Route::get('/results/{testId}/dashboard', [ResultController::class, 'dashboard']);
  Route::get('/results/{testId}/{attemptId}', [ResultController::class, 'attempt']);
  Route::delete('/results/{testId}/{attemptId}', [ResultController::class, 'delete']);
  Route::post('/results/set-answer', [ResultController::class, 'setAnswer']);
});
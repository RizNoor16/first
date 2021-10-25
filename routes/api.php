<?php

use App\Http\Controllers\Api\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BookController;


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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
  
});
Route::group(['middleware' => 'auth:api'], function() {
    Route::get('/books', [BookController::class, 'getAllBooks']);
    Route::get('/books/{id}',  [BookController::class, 'getBook']);
    Route::post('/books',  [BookController::class, 'addBook']);
    Route::put('/books/{id}',  [BookController::class,'updateBook']);
    Route::delete('/books/{id}', [BookController::class, 'deleteBook']);    
  });

// Route::middleware('auth:api')->post('logout', [AuthController::class, 'logout']);

Route::post('/logout',[AuthController::class, 'logout']);

 

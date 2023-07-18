<?php

use App\Http\Controllers\ShareDocumentController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\Login;
use App\Http\Controllers\Auth\Register;

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

//Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//    return $request->user();
//});
Route::prefix('v1')->group(function () {
    Route::post('/register', [Register::class, 'register']);
    Route::post('/login', [Login::class, 'login']);
});

Route::prefix('v1')->middleware('auth:sanctum')->group(function () {
    Route::get('checkAuth', [Login::class, 'checkAuth']);
    Route::post('/send', [ShareDocumentController::class, 'store']);
});

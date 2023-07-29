<?php

use App\Http\Controllers\DownloadZipArchiveFiles;
use App\Http\Controllers\GetSharedDocumentsController;
use App\Http\Controllers\GetUsersListController;
use App\Http\Controllers\ReplyToDocumentController;
use App\Http\Controllers\ShareDocumentController;
use App\Http\Controllers\ToRaisController;
use App\Http\Controllers\UpdateStatusDocument;
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
    Route::post('/inbox', [GetSharedDocumentsController::class, 'inbox']);
    Route::post('/inbox/{uuid}', [GetSharedDocumentsController::class, 'showMail']);
    Route::post('/sent', [GetSharedDocumentsController::class, 'sent']);
    Route::get('/users', [GetUsersListController::class, 'usersList']);
    Route::post('/showed/{uuid}', [GetSharedDocumentsController::class, 'showed']);
    Route::post('/reply/{uuid}', [ReplyToDocumentController::class, 'reply']);
    Route::post('/update-status/{uuid}', [UpdateStatusDocument::class, 'updateStatus']);
    Route::post('/update-control/{uuid}', [UpdateStatusDocument::class, 'updateControl']);
    Route::post('/to-rais/{id}', [ReplyToDocumentController::class, 'fromRaisToUsers']);
    Route::post("/to-rais-reply/{uuid}", [ShareDocumentController::class, 'toRaisReplyDocument']);
    Route::get('/get-replied-to-rais', [ToRaisController::class, 'getRepliedToRais']);
    Route::get('/get-replied-to-rais/{id}', [ToRaisController::class, 'getRepliedToRaisById']);
});


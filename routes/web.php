<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GetDocumentsController;
use App\Http\Controllers\DownloadZipArchiveFiles;
use BeyondCode\LaravelWebSockets\Facades\WebSocketsRouter;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});
Route::post("/pdf-reports/{lang}", [GetDocumentsController::class, 'pdfReports']);
WebSocketsRouter::webSocket('/app/{appKey}', \BeyondCode\LaravelWebSockets\Http\Controllers\WebSocketController::class);

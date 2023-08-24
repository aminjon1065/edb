<?php

use App\Http\Controllers\DownloadZipArchiveFiles;
use App\Http\Controllers\GetDocumentsController;
use Illuminate\Support\Facades\Route;

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
Route::get('/test-notification', function () {
    event(new \App\Events\NotificationSharedMail('Test Message', "1"));
    return 'Notification sent';
});

Route::post("/pdf-reports/{lang}", [GetDocumentsController::class, 'pdfReports']);


<?php

use App\Http\Controllers\TranscriptionController;
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

Route::controller(TranscriptionController::class)->group(function () {
    Route::GET('/', 'index')->name('audio.index');
    Route::GET('/transcribe', 'transcribe')->name('audio.transcribe');
    Route::GET('/split', 'split')->name('audio.split');
    Route::POST('/audio/upload', 'upload')->name('audio.upload');
    Route::POST('/audio/split_and_download', 'splitAndDownload')->name('audio.splitAndDownload');
    Route::GET('audio/transcribed', 'showTranscribed')->name('audio.showTranscribed');
    Route::POST('/audio/generate', 'generateContentType')->name('audio.generateContentType');
});

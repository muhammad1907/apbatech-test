<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AntreanController;

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

Route::get('/auth', 'AuthController@getToken');

Route::prefix('antrean')->middleware('jwt.auth')->group(function () {
    Route::get('status/{kode_poli}/{tanggalperiksa}', [AntreanController::class, 'getStatusAntrean']);
    Route::post('/', [AntreanController::class, 'ambilAntrean']);
    Route::get('sisapeserta/{nomorkartu_jkn}/{kode_poli}/{tanggalperiksa}', [AntreanController::class, 'getSisaAntrean']);
    Route::put('batal', [AntreanController::class, 'batalAntrean']);
});
<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BarangController;
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
Route::get('/table', function () {
    return view('table');
});

Route::get('/get/session' , [BarangController::class, 'getDataFromSession']);
Route::post('/session' , [BarangController::class, 'createData']);
Route::get('/get/session/{id}' , [BarangController::class, 'getDataById']);
Route::post('/session/update/{id}' , [BarangController::class, 'updateDataById']);
Route::post('/session/save' , [BarangController::class, 'createDataCustomerAndBarang']);
Route::delete('/session/delete/{id}' , [BarangController::class, 'deleteData']);




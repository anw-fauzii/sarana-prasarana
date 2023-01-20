<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BarangController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\BarangKeluarController;
use App\Http\Controllers\BarangMasukController;
use App\Http\Controllers\AtkKeluarController;
use App\Http\Controllers\AtkMasukController;
use App\Http\Controllers\ScanController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\ATKController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


Auth::routes();

Route::get('/home', [HomeController::class, 'index'])->name('home');
Route::get('/laporan-barang-keluar', [LaporanController::class, 'keluar'])->name('laporan.keluar');
Route::get('/laporan-barang-masuk', [LaporanController::class, 'masuk'])->name('laporan.masuk');
Route::get('/', function () {
    return redirect()->route('login');
});

Route::resource('/data-barang', BarangController::class);
Route::get('/hapus-tamu/{id}',[BarangController::class,'hapus'])->name('hapus.tamu');
Route::resource('/data-atk', ATKController::class);
Route::get('/hapus-atk/{id}',[ATKController::class,'hapus'])->name('hapus.atk');

Route::resource('/atk-keluar', AtkKeluarController::class);
Route::get('/hapus-atk-keluar/{id}',[AtkKeluarController::class,'hapus'])->name('hapus.atk-keluar');
Route::resource('/atk-masuk', AtkMasukController::class);
Route::get('/hapus-atk-masuk/{id}',[AtkMasukController::class,'hapus'])->name('hapus.atk-masuk');

Route::resource('/barang-keluar', BarangKeluarController::class);
Route::get('/hapus-barang-keluar/{id}',[BarangKeluarController::class,'hapus'])->name('hapus.barang-keluar');
Route::resource('/barang-masuk', BarangMasukController::class);
Route::get('/hapus-barang-masuk/{id}',[BarangMasukController::class,'hapus'])->name('hapus.barang-masuk');
Route::get('/scan-barang/{id}',[ScanController::class,'create']);
Route::post('/scan-barang/store',[ScanController::class,'store'])->name('scan.store');
//Optimize
Route::get('/optimize', function() {
    $exitCode = Artisan::call('optimize');
    return '<h1>Clear Config cleared</h1>';
});
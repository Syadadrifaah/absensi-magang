<?php

use App\Http\Controllers\AbsensiController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\LogbookController;
use Illuminate\Support\Facades\Route;



Route::get('/', function () {
    return view('layouts.index');
});



Route::get('/dashboard', [AdminController::class, 'index']);
Route::get('/datalokasi', [AdminController::class, 'datalokasi'])->name('datalokasi');


// Route::get('/lokasi-absensi/create', [AdminController::class, 'create']);
// Route::post('/lokasi-absensi/store', [AdminController::class, 'store']);

Route::get('/lokasi-absensi', [AdminController::class, 'index']);

Route::post('/lokasi-absensi/store', [AdminController::class, 'store'])
    ->name('lokasi.store');

Route::put('/lokasi-absensi/{id}', [AdminController::class, 'update'])
    ->name('lokasi.update');

Route::delete('/lokasi-absensi/{id}', [AdminController::class, 'destroy'])->name('lokasi.destroy');
Route::patch('/lokasi/{id}/toggle', [AdminController::class, 'toggle'])->name('lokasi.toggle');

// Absensi
Route::delete('/absensi/{id}', [AbsensiController::class, 'destroy'])->name('absensi.destroy');

// Logbook
Route::put('/logbook/{id}', [LogbookController::class, 'update'])->name('logbook.update');
Route::delete('/logbook/{id}', [LogbookController::class, 'destroy'])->name('logbook.destroy');


Route::post('/absensi', [AdminController::class, 'Absensi']);

Route::get('/absensi', [AbsensiController::class, 'index'])->name('absensi.index');


Route::post('/absensi/store', [AbsensiController::class, 'store'])->name('absensi.store');




// Simpan logbook (modal)
Route::post('/logbook/store', [LogbookController::class, 'store'])
    ->name('logbook.store');
<?php



use Illuminate\Support\Facades\Route;
use App\Http\Controllers\IzinController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AbsensiController;
use App\Http\Controllers\LogbookController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\ActivityLogController;
use App\Http\Controllers\KategoriEmployeeController;






Route::get('/', function () {
    return redirect()->route('dashboard');
});



Route::get('/dashboard', [AdminController::class, 'index'])->name('dashboard');
Route::get('/datalokasi', [AdminController::class, 'datalokasi'])->name('datalokasi');


Route::get('/lokasi-absensi', [AdminController::class, 'index']);
Route::post('/lokasi-absensi/store', [AdminController::class, 'store'])->name('lokasi.store');

Route::put('/lokasi-absensi/{id}', [AdminController::class, 'update'])->name('lokasi.update');
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
Route::post('/logbook/store', [LogbookController::class, 'store'])->name('logbook.store');

Route::get('/employees', [EmployeeController::class, 'index'])->name('employees.index');
Route::get('/employees/create', [EmployeeController::class, 'create'])->name('employees.create');
Route::post('/employees/store', [EmployeeController::class, 'store'])->name('employees.store');
// Route::get('/employees/update/{id}', [EmployeeController::class, 'edit'])->name('employees.edit');
Route::put('/employees/update/{id}', [EmployeeController::class, 'update'])->name('employees.update');
Route::delete('/employees/delete/{id}', [EmployeeController::class, 'destroy'])->name('employees.destroy');

Route::get('/kategori-employee', [KategoriEmployeeController::class, 'index'])->name('kategori.employee.index');
Route::post('/kategori-employee/store', [KategoriEmployeeController::class, 'store'])->name('kategori.employee.store');
Route::put('/kategori-employee/update/{id}', [KategoriEmployeeController::class, 'update'])->name('kategori.employee.update');
Route::delete('/kategori-employee/delete/{id}', [KategoriEmployeeController::class, 'destroy'])->name('kategori.employee.delete');


Route::get('/data-absensi', [AbsensiController::class, 'dataabsensi'])->name('absensi.dataabsensi');
Route::put('/data-absensi/update/{id}', [AbsensiController::class, 'updatedataabsensi'])->name('absensi.updatedataabsensi');

Route::get('/izin', [IzinController::class, 'index'])->name('izin.index');

Route::post('/izin/store', [IzinController::class, 'store'])->name('izin.store');

Route::put('/izin/update/{id}', [IzinController::class, 'update'])->name('izin.update');

Route::delete('/izin/delete/{id}', [IzinController::class, 'destroy'])->name('izin.delete');

Route::get('/roles', [RoleController::class, 'index'])->name('roles.index');

// Simpan role baru
Route::post('/roles/store', [RoleController::class, 'store'])->name('roles.store');

// Update role
Route::put('/roles/update/{role}', [RoleController::class, 'update'])->name('roles.update');

// Hapus role
Route::delete('/roles/delete/{role}', [RoleController::class, 'destroy'])->name('roles.destroy');



Route::get('/users', [UserController::class, 'index'])->name('users.index');

// simpan user baru
Route::post('/users/store', [UserController::class, 'store'])->name('users.store');

// update user
Route::put('/users/update/{user}', [UserController::class, 'update'])->name('users.update');

// hapus user
Route::delete('/users/delete/{user}', [UserController::class, 'destroy'])->name('users.destroy');


Route::get('/activity-logs', [ActivityLogController::class, 'index'])
    ->name('activity-logs.index');

Route::put('/activity-logs/update/{log}', [ActivityLogController::class, 'update'])
    ->name('activity-logs.update');
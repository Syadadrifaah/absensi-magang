<?php



use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\IzinController;
use App\Http\Controllers\MapsController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\AbsensiController;
use App\Http\Controllers\LogbookController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\PositionController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ActivityLogController;
use App\Http\Controllers\KategoriEmployeeController;
use SebastianBergmann\CodeCoverage\Report\Xml\Report;


Route::get('/', function () {
    return redirect()->route('login');
});




// Grouped routes by role (examples)
Route::middleware(['auth','role:Admin'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    // Route::get('/dashboard', [DashboardController::class, 'showData'])->name('show.data');
    Route::get('/kategori-employee', [KategoriEmployeeController::class, 'index'])->name('kategori.employee.index');
    Route::post('/kategori-employee/store', [KategoriEmployeeController::class, 'store'])->name('kategori.employee.store');
    Route::put('/kategori-employee/update/{id}', [KategoriEmployeeController::class, 'update'])->name('kategori.employee.update');
    Route::delete('/kategori-employee/delete/{id}', [KategoriEmployeeController::class, 'destroy'])->name('kategori.employee.delete');
    
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
    
    // Positions (Jabatan)
    Route::get('/positions', [PositionController::class, 'index'])->name('positions.index');
    Route::get('/positions/create', [PositionController::class, 'create'])->name('positions.create');
    Route::post('/positions/store', [PositionController::class, 'store'])->name('positions.store');
    Route::get('/positions/{position}/edit', [PositionController::class, 'edit'])->name('positions.edit');
    Route::put('/positions/{position}', [PositionController::class, 'update'])->name('positions.update');
    Route::delete('/positions/{position}', [PositionController::class, 'destroy'])->name('positions.destroy');
    
    // simpan user baru
    Route::post('/users/store', [UserController::class, 'store'])->name('users.store');
    // update user
    Route::put('/users/update/{user}', [UserController::class, 'update'])->name('users.update');
    // hapus user
    Route::delete('/users/delete/{user}', [UserController::class, 'destroy'])->name('users.destroy');
    Route::get('/activity-logs', [ActivityLogController::class, 'index'])->name('activity-logs.index');
    Route::put('/activity-logs/update/{log}', [ActivityLogController::class, 'update'])->name('activity-logs.update');
    
    
    Route::get('/datalokasi', [AdminController::class, 'datalokasi'])->name('datalokasi');
    Route::get('/lokasi-absensi', [AdminController::class, 'index']);
    Route::post('/lokasi-absensi/store', [AdminController::class, 'store'])->name('lokasi.store');

    Route::put('/lokasi-absensi/{id}', [AdminController::class, 'update'])->name('lokasi.update');
    Route::delete('/lokasi-absensi/{id}', [AdminController::class, 'destroy'])->name('lokasi.destroy');
    Route::patch('/lokasi/{id}/toggle', [AdminController::class, 'toggle'])->name('lokasi.toggle');

    Route::get('/laporan', [ReportController::class, 'index'])
        ->name('laporan.index');

    Route::get('/pegawai', [ReportController::class, 'pegawai'])
        ->name('laporan.pegawai');

    Route::get('/absensi-harian', [ReportController::class, 'absensiHarian'])
        ->name('laporan.absensi.harian');

    Route::get('/absensi-bulanan', [ReportController::class, 'absensiBulanan'])
        ->name('laporan.absensi.bulanan');

    Route::get('/laporan', [ReportController::class, 'index'])->name('laporan.index');

    Route::get('/employees', [EmployeeController::class, 'index'])->name('employees.index');
    Route::get('/employees/create', [EmployeeController::class, 'create'])->name('employees.create');
    Route::post('/employees/store', [EmployeeController::class, 'store'])->name('employees.store');
    // Route::get('/employees/update/{id}', [EmployeeController::class, 'edit'])->name('employees.edit');
    Route::put('/employees/update/{id}', [EmployeeController::class, 'update'])->name('employees.update');
    Route::delete('/employees/delete/{id}', [EmployeeController::class, 'destroy'])->name('employees.destroy');

});

Route::middleware(['auth','role:Kepala Balai,pegawai,Admin'])->group(function () {
    Route::get('/absensi', [AbsensiController::class, 'index'])->name('absensi.index');
    Route::get('/logbook', [LogbookController::class, 'index'])->name('logbook.index');
    // Absensi
    Route::delete('/absensi/{id}', [AbsensiController::class, 'destroy'])->name('absensi.destroy');
    Route::get('/absensi', [AbsensiController::class, 'index'])->name('absensi.index');
    Route::post('/absensi/store', [AbsensiController::class, 'store'])->name('absensi.store');

    // Logbook
    Route::put('/logbook/{id}', [LogbookController::class, 'update'])->name('logbook.update');
    Route::delete('/logbook/{id}', [LogbookController::class, 'destroy'])->name('logbook.destroy');



    // Simpan logbook (modal)
    Route::post('/logbook/store', [LogbookController::class, 'store'])->name('logbook.store');
    Route::get('/data-absensi', [AbsensiController::class, 'dataabsensi'])->name('absensi.dataabsensi');
    Route::put('/data-absensi/update/{id}', [AbsensiController::class, 'updatedataabsensi'])
        ->name('absensi.updatedataabsensi')
        ->middleware('role:Admin');

});
    
  // Route::post('/absensi', [AdminController::class, 'Absensi'])->name('absensi');


// Auth
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');





    










   
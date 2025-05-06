<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\InicioController;
use App\Http\Controllers\PerfilController;
use App\Http\Controllers\ReservaController;
use App\Http\Controllers\PasswordController;
use App\Http\Controllers\MisReservasController;
use App\Http\Controllers\Admin\AulaController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\HorarioController;
use App\Http\Controllers\Admin\UserCreateController;
use App\Http\Controllers\Admin\HorariosClaseController;
use App\Http\Controllers\Admin\DiasNoLectivosController;

Auth::routes();

Route::match(['get', 'post'], '/', [InicioController::class, 'index'])->name('index');

Route::middleware(['auth'])->group(function () {

    Route::match(['get', 'post'], '/perfil', [PerfilController::class, 'index'])->name('perfil');
    Route::post('/perfil/email', [PerfilController::class, 'updateEmail'])->name('perfil.updateEmail');

    Route::get('/cambiar-contraseña', [PasswordController::class, 'edit'])->name('pass.edit');
    Route::post('/cambiar-contraseña', [PasswordController::class, 'update'])->name('pass.update');
});

Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [AdminController::class, 'index'])->name('dashboard');

    Route::prefix('users')->name('users.')->group(function () {
        Route::match(['get', 'post'], '/', [UserController::class, 'index'])->name('index');
        Route::put('/{user}/toggle-active', [UserController::class, 'toggleActive'])->name('toggleActive');
        Route::put('/{user}/change-role', [UserController::class, 'changeRole'])->name('changeRole');
        Route::put('/{user}/update-code', [UserController::class, 'updateCode'])->name('updateCode');
        Route::delete('/{user}', [UserController::class, 'destroy'])->name('destroy');
        Route::get('/create', [UserCreateController::class, 'create'])->name('create'); // Vista de creación
        Route::post('/create', [UserCreateController::class, 'store'])->name('create.store'); // Crear individual
        Route::post('/create/confirm', [UserCreateController::class, 'confirmUpload'])->name('create.confirm'); // Confirmación masiva
        Route::post('/create/store-massive', [UserCreateController::class, 'storeMassive'])->name('create.storeMassive'); // Guardar masivo
        Route::get('/create/download', [UserCreateController::class, 'downloadCsv'])->name('create.download'); // Descargar CSV
    });

    Route::prefix('horarios-clase')->name('horarios-clase.')->group(function () {
        Route::get('/', [HorariosClaseController::class, 'form'])->name('form');
        Route::post('/', [HorariosClaseController::class, 'import'])->name('import');
    });

    Route::prefix('dias-no-lectivos')->name('dias-no-lectivos.')->group(function () {
        Route::get('/', [DiasNoLectivosController::class, 'form'])->name('form');
        Route::post('/', [DiasNoLectivosController::class, 'import'])->name('import');
        Route::post('/store', [DiasNoLectivosController::class, 'store'])->name('store');
    });

    Route::prefix('aulas')->name('aulas.')->group(function () {
        Route::get('/', [AulaController::class, 'index'])->name('index');
        Route::post('/categoria', [AulaController::class, 'storeCategoria'])->name('categoria.store');
        Route::post('/', [AulaController::class, 'store'])->name('store');
    });

    Route::prefix('horarios')->name('horarios.')->group(function () {
        Route::get('/', [HorarioController::class, 'index'])->name('index');
        Route::post('/{horario}', [HorarioController::class, 'update'])->name('update');
    });

});

Route::middleware(['auth', 'profesor'])->group(function () {
    Route::post('/reservas', [InicioController::class, 'store'])->name('reservas.store');
    Route::delete('/mis-reservas/{reserva}', [MisReservasController::class, 'destroy'])->name('mis-reservas.destroy');
});

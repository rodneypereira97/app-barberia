<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\CitaController;

Route::middleware(['auth'])->group(function () {
    Route::get('/', function () {
        return redirect()->route('dashboard');
    });


    Route::get('/dashboard', function () {
        return view('dashboard');
    })->middleware(['auth', 'verified'])->name('dashboard');

    Route::get('/citas/calendario', [CitaController::class, 'calendario'])->name('citas.calendario');
    Route::put('/citas/{id}/confirmar', [CitaController::class, 'confirmar'])->name('citas.confirmar');
    Route::get('/verificar-cita', [CitaController::class, 'verificarDisponibilidad'])->name('citas.verificar');
    Route::resource('clientes', ClienteController::class);
    Route::resource('citas', CitaController::class);
    Route::resource('servicios', \App\Http\Controllers\ServicioController::class)->middleware('auth');


    Route::get('/', function () {
        return redirect()->route('login');
    });

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

});

require __DIR__.'/auth.php';

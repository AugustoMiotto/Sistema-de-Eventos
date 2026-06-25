<?php

use App\Models\Event;
use App\Models\Category;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\PromoterController;

Route::get('/', function () {
    return view('auth.login');
});

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Rotas de Categorias
        Route::get('/categorias/criar', [CategoryController::class, 'create'])->name('categories.create');
        Route::post('/categorias', [CategoryController::class, 'store'])->name('categories.store');

    // Rotas de Eventos
        Route::get('/eventos/criar', [EventController::class, 'create'])->name('events.create');
        Route::post('/eventos', [EventController::class, 'store'])->name('events.store');
        Route::get('/eventos/{event}/editar', [EventController::class, 'edit'])->name('events.edit');
        Route::put('/eventos/{event}', [EventController::class, 'update'])->name('events.update');
        Route::delete('/eventos/{event}', [EventController::class, 'destroy'])->name('events.destroy');
        //  Ver detalhes do evento
            Route::get('/eventos/{event}', [EventController::class, 'show'])
            ->name('events.show')
            ->withTrashed();
    // Painel do Promotor (Lista os eventos dele)
        Route::get('/meus-eventos', [PromoterController::class, 'index'])->name('promoter.events');
});

require __DIR__.'/auth.php';

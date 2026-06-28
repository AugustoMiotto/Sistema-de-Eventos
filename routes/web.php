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
use App\Http\Controllers\SpeakerController;
use App\Http\Controllers\RegistrationController;
use App\Http\Controllers\EventAttendeeController;


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
    // Rotas de Palestrantes
        Route::get('/palestrantes/novo', [SpeakerController::class, 'create'])->name('speakers.create');
        Route::post('/api/palestrantes', [App\Http\Controllers\SpeakerController::class, 'storeAjax'])->name('speakers.storeAjax');
        Route::post('/palestrantes', [SpeakerController::class, 'store'])->name('speakers.store');
    //Rotas de Inscrições
        Route::post('/events/{event}/register', [RegistrationController::class, 'store'])
            ->name('events.register')
            ->middleware('auth');
        Route::delete('/events/{event}/cancel', [RegistrationController::class, 'destroy'])
            ->name('events.cancel')
            ->middleware('auth');

    // Rotas de Relatórios
        Route::get('/events/{event}/attendees', [EventAttendeeController::class, 'index'])
                ->name('events.attendees')
                ->middleware('auth');

        Route::get('/events/{event}/attendees/export', [EventAttendeeController::class, 'exportCsv'])
                ->name('events.attendees.export')
                ->middleware('auth');
});

require __DIR__.'/auth.php';

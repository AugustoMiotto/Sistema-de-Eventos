<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EventoController;
use App\Http\Controllers\PromoterController;
// ... existing code ...

Route::get('/', function () {
    return view('welcome');
});

Route::get('/eventos', [EventoController::class, 'index']);
Route::get('/eventos/create', [EventoController::class, 'create']);
Route::post('/eventos', [EventoController::class, 'store']);
Route::get('/eventos/{evento}', [EventoController::class, 'show']);
Route::get('/eventos/{evento}/edit', [EventoController::class, 'edit']);
Route::put('/eventos/{evento}', [EventoController::class, 'update']);
Route::delete('/eventos/{evento}', [EventoController::class, 'destroy']);

Route::get('/promoters', [PromoterController::class, 'index']);
Route::get('/promoters/create', [PromoterController::class, 'create']);
Route::post('/promoters', [PromoterController::class, 'store']);
Route::get('/promoters/{promoter}', [PromoterController::class, 'show']);
Route::get('/promoters/{promoter}/edit', [PromoterController::class, 'edit']);
Route::put('/promoters/{promoter}', [PromoterController::class, 'update']);
Route::delete('/promoters/{promoter}', [PromoterController::class, 'destroy']);


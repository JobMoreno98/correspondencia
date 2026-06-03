<?php

use App\Http\Controllers\ReporteController;
use Filament\Http\Middleware\Authenticate;
use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;
use App\Http\Controllers\ChunkUploadController;

Route::get('/', function () {
    return redirect()->route('filament.admin.home');
})->name('home');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::get('/generar-reporte', [ReporteController::class, 'reporte'])->name('reporte.pdf')->middleware( Authenticate::class);

Route::post('/chunks/upload', [ChunkUploadController::class, 'upload'])->name('api.chunks.upload')->middleware( Authenticate::class);
/*
Route::middleware(['auth'])->group(function () {

    Route::redirect('settings', 'settings/profile');

    Volt::route('settings/profile', 'settings.profile')->name('settings.profile');
    Volt::route('settings/password', 'settings.password')->name('settings.password');
    Volt::route('settings/appearance', 'settings.appearance')->name('settings.appearance');
});

require __DIR__.'/auth.php';
*/
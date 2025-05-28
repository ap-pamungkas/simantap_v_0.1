<?php

use App\Livewire\Admin\BerandaController;
use App\Livewire\Admin\JabatanController;
use App\Livewire\Admin\LogPerangkatController;
use App\Livewire\Admin\PerangkatController;
use App\Livewire\Admin\Petugas\Show;
use App\Livewire\Admin\PetugasController;
use App\Livewire\Komando\DashboardController;
use Illuminate\Support\Facades\Route;
use Livewire\Livewire;


Route::prefix('sistemtelemetri_test')->group(function () {
    Livewire::setScriptRoute(fn($handle) => Route::get('/livewire/livewire.js', $handle));
Livewire::setUpdateRoute(fn($handle) => Route::post('/livewire/update', $handle));
});
Route::get('/', function () {
    return view('welcome');
});
Route::prefix('admin')->group(function () {
    Route::get('/', BerandaController::class)->name('admin.beranda');
    Route::get('/perangkat', PerangkatController::class)->name('admin.perangkat');
    Route::get('petugas', PetugasController::class)->name('admin.petugas');
    Route::get('jabatan', JabatanController::class)->name('admin.jabatan');
    Route::get('petugas/{id}', Show::class)->name('admin.petugas.show');
    Route::get('log-perangkat', LogPerangkatController::class)->name('admin.log-perangkat');
});



Route::prefix('komando')->group(function () {
    Route::get('/', DashboardController::class)->name('komando.dashboard');

});

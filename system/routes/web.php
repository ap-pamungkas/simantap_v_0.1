<?php

use App\Livewire\Admin\BerandaController;
use App\Livewire\Admin\JabatanController;
use App\Livewire\Admin\LogPerangkatController;
use App\Livewire\Admin\PerangkatController;
use App\Livewire\Admin\Petugas\Show;
use App\Livewire\Admin\PetugasController;
use App\Livewire\Komando\DashboardController;
use App\View\Components\Komando;
use Illuminate\Support\Facades\Route;
use App\Livewire\Komando\PetugasController as KomandoPetugasController;
use App\Livewire\Komando\PerangkatController as KomandoPerangkatController;
use App\Livewire\Komando\InsidenController as KomandoInsidenController;
use App\Livewire\Komando\Insiden\ShowInsiden as KomandoInsidenShow;
use App\Livewire\Komando\RegistrasiPetugasController;
use App\Livewire\Komando\TrackingPetugasController;
use Livewire\Livewire;


Route::prefix('simantap_v_0.1')->group(function () {
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
    Route::get('/petugas', KomandoPetugasController::class)->name('komando.petugas');
    Route::get('/petugas/registrasi', RegistrasiPetugasController::class)->name('komando.petugas.registrasi');
    Route::get('/perangkat', KomandoPerangkatController::class)->name('komando.perangkat');
    Route::get('/tracking-petugas', TrackingPetugasController::class)->name('komando.tracking-petugas');
    Route::get('/insiden', KomandoInsidenController::class)->name('komando.insiden');
    Route::get('/insiden/show/{id}', KomandoInsidenShow::class)->name('komando.insiden.show');

});


Route::get('/sensor-monitor', function () {
    return view('sensor-monitor');
});
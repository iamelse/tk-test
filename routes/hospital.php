<?php

use App\Http\Controllers\HospitalController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])->group(function () {
    Route::get('/hospital', [HospitalController::class, 'index'])->name('hospital.index');
    Route::get('/hospital/create', [HospitalController::class, 'create'])->name('hospital.create');
    Route::post('/hospital', [HospitalController::class, 'store'])->name('hospital.store');
    Route::get('/hospital/{hospital}/edit', [HospitalController::class, 'edit'])->name('hospital.edit');
    Route::put('/hospital/{hospital}', [HospitalController::class, 'update'])->name('hospital.update');
    Route::delete('/hospital/{hospital}', [HospitalController::class, 'destroy'])->name('hospital.destroy');
});
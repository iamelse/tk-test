<?php

use App\Http\Controllers\HospitalController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])->group(function () {
    Route::get('/hospital', [HospitalController::class, 'index'])->name('hospital.index');
    Route::post('/hospital', [HospitalController::class, 'store'])->name('hospital.store');
    Route::put('/hospital/{hospital}', [HospitalController::class, 'update'])->name('hospital.update');
    Route::delete('/hospital/{hospital}', [HospitalController::class, 'destroy'])->name('hospital.destroy');
});
<?php

use App\Http\Controllers\PatientController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])->group(function () {
    Route::get('/patient', [PatientController::class, 'index'])->name('patient.index');
    Route::post('/patient', [PatientController::class, 'store'])->name('patient.store');
    Route::put('/patient/{patient}', [PatientController::class, 'update'])->name('patient.update');
    Route::delete('/patient/{patient}', [PatientController::class, 'destroy'])->name('patient.destroy');
});
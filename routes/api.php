<?php

use App\Http\Controllers\EmployeeController;
use Illuminate\Support\Facades\Route;

Route::post('/employee', [EmployeeController::class, 'import']);
Route::get('/employee', [EmployeeController::class, 'index']);
Route::get('/employee/{id}', [EmployeeController::class, 'show']);
Route::delete('/employee/{id}', [EmployeeController::class, 'destroy']);

<?php

use App\Http\Controllers\API\EmployeeController;
use App\Http\Controllers\API\TimesheetController;
use Illuminate\Support\Facades\Route;

Route::apiResource('employees', EmployeeController::class);
Route::apiResource('timesheets', TimesheetController::class);
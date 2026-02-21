<?php

use App\Http\Controllers\UnitController;
use Illuminate\Support\Facades\Route;

Route::apiResource('/units', UnitController::class);

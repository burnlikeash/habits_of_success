<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;

// Main dashboard landing page
Route::get('/', [DashboardController::class, 'index']);

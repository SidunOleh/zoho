<?php

use App\Http\Controllers\GetTokenController;
use App\Http\Controllers\HandleFormController;
use App\Http\Controllers\IndexController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::post('/get-token', GetTokenController::class);

Route::post('/handle-form', HandleFormController::class);

Route::get('/{any}', IndexController::class)->where('any', '.*');

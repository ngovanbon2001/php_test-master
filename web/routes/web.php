<?php

use App\Http\Controllers\Api\DishController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::post('get-restaurant', [DishController::class, 'getLishRes'])->name('get-restaurant');
Route::post('get-dish', [DishController::class, 'getLishDish'])->name('get-dish');
Route::post('order', [DishController::class, 'order'])->name('order');


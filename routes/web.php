<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Controller;

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

Route::get('/', [Controller::class, 'netWorth'])->name('netWorth');
Route::get('/assets', [Controller::class, 'assets'])->name('assets');
Route::get('/liabilities', [Controller::class, 'liabilities'])->name('liabilities');
Route::get('/interest-rates', [Controller::class, 'interestRates'])->name('interestRates');
Route::get('/investments', [Controller::class, 'investments'])->name('investments');
Route::get('/charity', [Controller::class, 'charity'])->name('charity');

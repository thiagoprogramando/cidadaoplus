<?php

use App\Http\Controllers\Api\AcessController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('authenticated', [AcessController::class, 'login'])->name('authenticated');
Route::get('users', [AcessController::class, 'users'])->name('users');
Route::get('data', [AcessController::class, 'data'])->name('data');

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

<?php

use App\Http\Controllers\Web\MovieController;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\lang;
Route::get('/', function () {
    return view('welcome');
});
Route::prefix('{locale}/admin/movies')
    ->where(['locale' => 'en|ar'])
    ->name('movies.')
    ->middleware('lang') // ميدل وير لتعيين اللغة
    ->group(function () {
        Route::get('/', [MovieController::class, 'index'])->name('index');
        Route::get('/create', [MovieController::class, 'create'])->name('create');
        Route::post('/', [MovieController::class, 'store'])->name('store');
        Route::get('/{movie}/edit', [MovieController::class, 'edit'])->name('edit');
        Route::put('/{movie}', [MovieController::class, 'update'])->name('update');
        Route::delete('/{movie}', [MovieController::class, 'destroy'])->name('destroy');
    });

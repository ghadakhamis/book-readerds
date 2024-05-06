<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\ReaderController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(
    function () {
        Route::post('login', [AuthController::class, 'login'])->name('login');

        Route::get('books', [BookController::class, 'index'])->name('books.index');

        Route::middleware(['auth:user'])->group(function () {
            Route::post('books/{book}/readers', [ReaderController::class, 'store'])->name('books.readers.store');
        });
    }
);

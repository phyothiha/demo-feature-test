<?php

use App\Http\Controllers\Admin\ArticleController as AdminArticleController;
use App\Http\Controllers\ArticleController;
use Illuminate\Support\Facades\Route;

// Public routes
Route::get('articles', [ArticleController::class, 'index'])
    ->name('articles.index');
Route::get('articles/{article:slug}', [ArticleController::class, 'show'])
    ->name('articles.show');

Route::middleware('auth')->name('dashboard.')->prefix('/dashboard')->group(function () {
    Route::resource('articles', AdminArticleController::class);
});

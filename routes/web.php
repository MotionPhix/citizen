<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get(
  '/',
  [\App\Http\Controllers\HomeController::class, 'index']
)->name('home');

Route::get(
  '/organisation',
  [\App\Http\Controllers\AboutController::class, 'index']
)->name('about');

Route::prefix('contact')->name('contact.')->group(function () {

  Route::get(
    '/',
    [\App\Http\Controllers\ContactController::class, 'index']
  )->name('index');

  Route::post(
    '/',
    [\App\Http\Controllers\ContactController::class, 'submit']
  )->name('submit');

});

Route::prefix('projects')->name('projects.')->group(function () {

  Route::get(
    '/',
    [\App\Http\Controllers\ProjectController::class, 'index'],
  )->name('index');

  Route::get(
    '/preview/{project}',
    [\App\Http\Controllers\ProjectController::class, 'preview']
  )->name('preview');

  Route::get(
    '/s/{project:slug}',
    [\App\Http\Controllers\ProjectController::class, 'show']
  )->name('show');

});

Route::prefix('blogs')->name('blogs.')->group(function () {

  Route::get(
    '/',
    [\App\Http\Controllers\BlogController::class, 'index']
  )->name('index');

  Route::get(
    '/{slug}',
    [\App\Http\Controllers\BlogController::class, 'show']
  )->name('show');

  /*
  Route::post(
    '/{slug}/comments',
    [\App\Http\Controllers\CommentController::class, 'store']
  )->name('comments.store');

  Route::delete(
    '/comments/{comment}',
    [\App\Http\Controllers\CommentController::class, 'destroy']
  )->name('comments.destroy');

  // Likes
  Route::post(
    '/{slug}/likes',
    [\App\Http\Controllers\LikeController::class, 'store']
  )->name('likes.store');

  Route::delete(
    '/likes/{like}',
    [\App\Http\Controllers\LikeController::class, 'destroy']
  )->name('likes.destroy');*/

  // Protected routes that require authentication
  Route::middleware(['auth'])->group(function () {
    // Comments routes
    Route::post(
      '/{blog:slug}/comments',
      [App\Http\Controllers\CommentController::class, 'store']
    )->name('comments.store');

    Route::put('/comments/{comment}', [App\Http\Controllers\CommentController::class, 'update'])
      ->name('comments.update')
      ->middleware('can:update,comment');

    Route::delete('/comments/{comment}', [App\Http\Controllers\CommentController::class, 'destroy'])
      ->name('comments.destroy')
      ->middleware('can:delete,comment');

    // Likes routes
    Route::post('/{blog:slug}/like', [App\Http\Controllers\LikeController::class, 'toggle'])
      ->name('likes.toggle');
  });

});

Route::get('dashboard', function () {
  return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

require __DIR__ . '/settings.php';
require __DIR__ . '/auth.php';

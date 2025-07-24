<?php

use Illuminate\Support\Facades\Request;
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
  )->name('submit')
  ->middleware('honeypot');
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
    '/s/{project:uuid}',
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

  // Comments routes (allow anonymous commenting)
  Route::get('/{blog:slug}/comments', [App\Http\Controllers\CommentController::class, 'index'])
    ->name('comments.index');

  Route::post('/{blog:slug}/comments', [App\Http\Controllers\CommentController::class, 'store'])
    ->name('comments.store')
    ->middleware('throttle:5,60'); // 5 comments per minute

  Route::put('/comments/{comment}', [App\Http\Controllers\CommentController::class, 'update'])
    ->name('comments.update');

  Route::delete('/comments/{comment}', [App\Http\Controllers\CommentController::class, 'destroy'])
    ->name('comments.destroy');

  // Protected routes that require authentication
  Route::middleware(['auth'])->group(function () {
    Route::post('/comments/{comment}/like', [App\Http\Controllers\CommentLikeController::class, 'toggle'])
      ->name('comments.like.toggle');

    // Likes routes
    Route::post('/{blog:slug}/like', [App\Http\Controllers\LikeController::class, 'toggle'])
      ->name('likes.toggle');
  });
});

// Email-based comment reply routes (outside blogs prefix)
Route::post('/comments/{comment}/reply', [App\Http\Controllers\CommentController::class, 'replyViaEmail'])
  ->name('comments.reply');

Route::post('/comments/{comment}/unsubscribe', [App\Http\Controllers\CommentController::class, 'unsubscribe'])
  ->name('comments.unsubscribe');

Route::middleware(['auth'])->group(function () {
  Route::post('/profile/avatar', function (Request $request) {
    $request->validate([
      'avatar' => ['required', 'image', 'max:2048']
    ]);

    $user = $request->user();
    $user->clearMediaCollection('avatar');
    $media = $user->addMediaFromRequest('avatar')
      ->toMediaCollection('avatar');

    return response()->json([
      'avatar_url' => $media->getUrl('thumb')
    ]);
  })->name('profile.avatar.update');
});

Route::get('dashboard', function () {
  return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Import route files
require __DIR__ . '/newsletter.php';
require __DIR__ . '/settings.php';
require __DIR__ . '/auth.php';

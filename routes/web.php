<?php

use App\Http\Controllers\NewsletterPreviewController;
use App\Http\Controllers\SubscriberController;
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

    Route::delete(
      '/comments/{comment}',
      [App\Http\Controllers\CommentController::class, 'destroy']
    )->name('comments.destroy')
      ->middleware('can:delete,comment');

    Route::post(
      '/comments/{comment}/like',
      [App\Http\Controllers\CommentLikeController::class, 'toggle']
    )->name('comments.like.toggle');

    // Likes routes
    Route::post('/{blog:slug}/like', [App\Http\Controllers\LikeController::class, 'toggle'])
      ->name('likes.toggle');
  });

});

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

Route::middleware(['auth'])->group(function () {
  Route::get('admin/newsletters/{newsletterIssue}/preview', [NewsletterPreviewController::class, 'show'])
    ->name('newsletter.preview');
});

// Newsletter routes
Route::post(
  '/newsletter/subscribe',
  [SubscriberController::class, 'subscribe']
)->name('newsletter.subscribe');

Route::get(
  '/newsletter/unsubscribe/{subscriber}/{token}',
  [SubscriberController::class, 'unsubscribe']
)->name('newsletter.unsubscribe');

require __DIR__ . '/settings.php';
require __DIR__ . '/auth.php';

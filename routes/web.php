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
    [\App\Http\Controllers\BlogPostController::class, 'index']
  )->name('index');

  Route::get(
    '/{slug}',
    [\App\Http\Controllers\BlogPostController::class, 'show']
  )->name('show');

// Comments
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
  )->name('likes.destroy');

});

Route::prefix('donate')->name('donation.')->group(function () {

  Route::get(
    '/',
    [\App\Http\Controllers\DonationController::class, 'showDonationForm']
  )->name('form');

  Route::post(
    '/process',
    [\App\Http\Controllers\DonationController::class, 'processDonation']
  )->name('process');

  Route::get(
    '/success',
    [\App\Http\Controllers\DonationController::class, 'donationSuccess']
  )->name('success');

  Route::get(
    '/cancel',
    [\App\Http\Controllers\DonationController::class, 'donationCancel']
  )->name('cancel');

  Route::post(
    '/callback',
    [\App\Http\Controllers\DonationController::class, 'donationCallback']
  )->name('callback');

});

Route::get('dashboard', function () {
  return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

require __DIR__ . '/settings.php';
require __DIR__ . '/auth.php';

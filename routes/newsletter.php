<?php

use App\Http\Controllers\NewsletterFeedbackController;
use App\Http\Controllers\NewsletterPreferencesController;
use App\Http\Controllers\NewsletterPreviewController;
use App\Http\Controllers\SubscriberController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])->group(function () {
  // Preview routes
  Route::get(
    '/preview/{newsletterIssue}',
    [NewsletterPreviewController::class, 'show']
  )->name('preview');
});

// Public newsletter routes
Route::prefix('newsletter')->name('newsletter.')->group(function () {
  // Subscription management
  Route::post(
    '/subscribe',
    [SubscriberController::class, 'subscribe']
  )->name('subscribe');

  Route::get(
    '/unsubscribe/{subscriber}/{token}',
    [SubscriberController::class, 'unsubscribe']
  )->name('unsubscribe');

  // Preferences
  Route::get(
    '/preferences/{subscriber}/{token}',
    [NewsletterPreferencesController::class, 'show']
  )->name('preferences');

  Route::put(
    '/preferences/{subscriber}/{token}',
    [NewsletterPreferencesController::class, 'update']
  )->name('preferences.update');

  // Feedback
  Route::get(
    '/issue/{issue}/feedback/{subscriber}/{token}',
    [NewsletterFeedbackController::class, 'show']
  )->name('feedback');

  Route::post(
    '/issue/{issue}/feedback/{subscriber}/{token}',
    [NewsletterFeedbackController::class, 'store']
  )->name('feedback.store');

});

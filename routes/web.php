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

Route::get('dashboard', function () {
  return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

require __DIR__ . '/settings.php';
require __DIR__ . '/auth.php';

<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;

class EmailServiceProvider extends ServiceProvider
{
  public function boot()
  {
    // Ensure assets use absolute URLs in emails
    if (!app()->runningInConsole()) {
      URL::forceScheme('https');
    }
  }
}

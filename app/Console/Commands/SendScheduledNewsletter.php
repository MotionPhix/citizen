<?php

namespace App\Console\Commands;

use App\Jobs\SendNewsletter;
use App\Models\Newsletter;
use Illuminate\Console\Command;

class SendScheduledNewsletter extends Command
{
  protected $signature = 'newsletters:send-scheduled';
  protected $description = 'Send scheduled newsletters';

  public function handle(): void
  {
    Newsletter::scheduled()
      ->where('scheduled_for', '<=', now())
      ->get()
      ->each(function (Newsletter $newsletter) {
        SendNewsletter::dispatch($newsletter);
      });
  }
}

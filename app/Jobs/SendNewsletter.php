<?php

namespace App\Jobs;

use App\Models\Newsletter;
use App\Models\Subscriber;
use App\Notifications\NewsletterMail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendNewsletter implements ShouldQueue
{
  use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

  public function __construct(
    public Newsletter $newsletter
  ) {}

  public function handle(): void
  {
    $this->newsletter->update(['status' => 'sending']);

    Subscriber::active()->chunk(100, function ($subscribers) {
      $subscribers->each(function ($subscriber) {
        $subscriber->notify(new NewsletterMail($this->newsletter));

        $this->newsletter->subscribers()->attach($subscriber->id, [
          'status' => 'sent',
        ]);
      });
    });

    $this->newsletter->update([
      'status' => 'sent',
      'sent_at' => now(),
    ]);
  }
}

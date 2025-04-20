<?php

namespace App\Console\Commands;

use App\Models\NewsletterIssue;
use App\Models\Subscriber;
use App\Notifications\BiWeeklyNewsletter;
use Carbon\Carbon;
use Illuminate\Console\Command;

class SendBiWeeklyNewsletter extends Command
{
  protected $signature = 'newsletter:send-biweekly {--test-email= : Send to a specific email for testing}';
  protected $description = 'Send the bi-weekly newsletter to all subscribers';

  public function handle(): int
  {
    $latestIssue = NewsletterIssue::with(['stories', 'updates', 'events'])
      ->where('status', 'scheduled')
      ->where('published_at', '<=', Carbon::now())
      ->latest('published_at')
      ->first();

    if (!$latestIssue) {
      $this->error('No scheduled newsletter issue found for sending.');
      return 1;
    }

    if ($testEmail = $this->option('test-email')) {
      $subscriber = new Subscriber([
        'email' => $testEmail,
        'name' => 'Test User',
        'status' => 'subscribed'
      ]);

      $subscriber->notify(new BiWeeklyNewsletter($latestIssue, $subscriber));
      $this->info("Test newsletter sent to {$testEmail}");
      return 0;
    }

    $subscribers = Subscriber::where('status', 'subscribed')->get();

    $bar = $this->output->createProgressBar($subscribers->count());
    $this->info('Sending newsletter...');

    foreach ($subscribers as $subscriber) {
      try {
        $subscriber->notify(new BiWeeklyNewsletter($latestIssue, $subscriber));
      } catch (\Exception $e) {
        $this->error("Failed to send to {$subscriber->email}: {$e->getMessage()}");
        continue;
      }
      $bar->advance();
    }

    $bar->finish();
    $this->newLine();

    $latestIssue->update([
      'status' => 'published'
    ]);

    $this->info('Newsletter sent successfully!');
    return 0;
  }
}

<?php

namespace App\Http\Controllers;

use App\Http\Requests\ContactFormRequest;
use App\Models\ContactSubmission;
use App\Models\User;
use App\Notifications\ContactFormSubmission;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;
use App\Mail\ContactAutoReply;
use Spatie\Honeypot\Honeypot;
use Carbon\Carbon;
use Inertia\Inertia;
use Inertia\Response;

class ContactController extends Controller
{
  /**
   * Show the contact form page.
   */
  public function index(): Response
  {
    return Inertia::render('Contact', [
      'honeypot' => new Honeypot(config('honeypot'))
    ]);
  }

  /**
   * Handle contact form submission with enhanced security and features.
   */
  public function submit(ContactFormRequest $request): JsonResponse|Response
  {
    $ip = $request->ip();
    $userAgent = $request->userAgent();
    $rateLimitKey = 'contact-form:' . $ip;

    // Enhanced rate limiting with progressive penalties
    $maxAttempts = $this->getMaxAttemptsForIp($ip);

    if (RateLimiter::tooManyAttempts($rateLimitKey, $maxAttempts)) {
      $availableIn = RateLimiter::availableIn($rateLimitKey);

      $this->logSecurityEvent('rate_limit_exceeded', [
        'ip' => $ip,
        'attempts' => RateLimiter::attempts($rateLimitKey),
        'max_attempts' => $maxAttempts,
        'available_in' => $availableIn,
        'user_agent' => $userAgent,
      ]);

      return response()->json([
        'message' => 'Too many contact attempts. Please try again in ' .
          $this->formatTimeRemaining($availableIn) . '.',
        'retry_after' => $availableIn,
        'error_code' => 'RATE_LIMIT_EXCEEDED'
      ], 429);
    }

    // Check for suspicious activity patterns
    if ($this->detectSuspiciousActivity($request)) {
      $this->logSecurityEvent('suspicious_activity', [
        'ip' => $ip,
        'user_agent' => $userAgent,
        'name' => $request->input('name'),
        'email' => $request->input('email'),
      ]);

      return response()->json([
        'message' => 'Your submission could not be processed. Please try again later.',
        'error_code' => 'SECURITY_CHECK_FAILED'
      ], 422);
    }

    // Check for duplicate submissions
    if ($this->isDuplicateSubmission($request)) {
      $this->logSecurityEvent('duplicate_submission', [
        'ip' => $ip,
        'email' => $request->input('email'),
      ]);

      return response()->json([
        'message' => 'This message appears to be similar to a recent submission. Please modify your message if you need to resubmit.',
        'error_code' => 'DUPLICATE_SUBMISSION'
      ], 422);
    }

    try {
      // Use database transaction for data integrity
      $submission = DB::transaction(function () use ($request, $ip, $userAgent) {
        // Create the submission record
        $submission = ContactSubmission::create([
          'name' => trim($request->input('name')),
          'email' => strtolower(trim($request->input('email'))),
          'subject' => trim($request->input('subject')),
          'message' => trim($request->input('message')),
          'status' => 'unread',
          'ip_address' => $ip,
          'user_agent' => $userAgent,
          'referrer' => $request->header('referer'),
          'submitted_at' => now(),
          'spam_score' => $this->calculateSpamScore($request),
          'metadata' => json_encode([
            'browser_language' => $request->header('accept-language'),
            'timezone' => $request->header('timezone'),
            'screen_resolution' => $request->input('screen_resolution'),
            'form_fill_time' => $request->input('form_fill_time'),
          ])
        ]);

        // Log successful submission
        Log::info('Contact form submission created', [
          'submission_id' => $submission->id,
          'email' => $submission->email,
          'ip' => $ip,
          'spam_score' => $submission->spam_score,
        ]);

        return $submission;
      });

      // Increment rate limit counter after successful submission
      RateLimiter::hit($rateLimitKey, $this->getRateLimitDuration());

      // Send notifications asynchronously
      $this->sendNotifications($submission);

      // Send auto-reply to user if enabled
      $this->sendAutoReply($submission);

      // Cache submission details for duplicate detection
      $this->cacheSubmissionDetails($request, $submission);

      // Clean up old cache entries periodically
      if (rand(1, 100) === 1) { // 1% chance
        $this->cleanupOldCacheEntries();
      }

      return inertia('Contact', [
        'honeypot' => new Honeypot(config('honeypot')),
        'message' => 'Thank you for your message! We have received your inquiry and will get back to you within 24 hours.',
      ]);

    } catch (\Illuminate\Database\QueryException $e) {
      $this->logError('Database error during contact submission', $e, $request);

      return response()->json([
        'message' => 'There was a technical issue processing your submission. Please try again in a few minutes.',
        'error_code' => 'DATABASE_ERROR'
      ], 500);

    } catch (\Exception $e) {
      $this->logError('Unexpected error during contact submission', $e, $request);

      return response()->json([
        'message' => 'Sorry, there was an unexpected error. Please try again or contact us directly.',
        'error_code' => 'GENERAL_ERROR'
      ], 500);
    }
  }

  /**
   * Get maximum attempts allowed for an IP based on history.
   */
  protected function getMaxAttemptsForIp(string $ip): int
  {
    $violationKey = 'contact_violations:' . $ip;
    $violations = Cache::get($violationKey, 0);

    // Reduce max attempts for repeat offenders
    return match (true) {
      $violations >= 5 => 1,  // Very restricted
      $violations >= 3 => 2,  // Restricted
      $violations >= 1 => 3,  // Limited
      default => 5            // Normal
    };
  }

  /**
   * Get rate limit duration based on current attempts.
   */
  protected function getRateLimitDuration(): int
  {
    return 3600; // 1 hour base duration
  }

  /**
   * Detect suspicious activity patterns.
   */
  protected function detectSuspiciousActivity(ContactFormRequest $request): bool
  {
    $ip = $request->ip();
    $userAgent = $request->userAgent();

    // Check for bot-like user agents
    $botPatterns = [
      'bot', 'crawler', 'spider', 'scraper', 'curl', 'wget',
      'python', 'java', 'perl', 'ruby', 'go-http', 'postman'
    ];

    foreach ($botPatterns as $pattern) {
      if (stripos($userAgent, $pattern) !== false) {
        return true;
      }
    }

    // Check for suspicious timing patterns
    $timingKey = 'form_timing:' . $ip;
    $lastSubmission = Cache::get($timingKey);

    if ($lastSubmission) {
      $timeDiff = now()->diffInSeconds($lastSubmission);
      if ($timeDiff < 5) {
        return true;
      }
    }

    Cache::put($timingKey, now(), now()->addMinutes(10));

    // Check for suspicious content patterns
    $message = strtolower($request->input('message', ''));
    $suspiciousKeywords = [
      'viagra', 'casino', 'lottery', 'bitcoin', 'crypto', 'investment',
      'make money', 'get rich', 'click here', 'buy now', 'limited time',
      'free money', 'earn cash', 'work from home', 'guaranteed income'
    ];

    foreach ($suspiciousKeywords as $keyword) {
      if (str_contains($message, $keyword)) {
        return true;
      }
    }

    return false;
  }

  /**
   * Check if this is a duplicate submission.
   */
  protected function isDuplicateSubmission(ContactFormRequest $request): bool
  {
    $email = strtolower(trim($request->input('email')));
    $messageHash = hash('sha256', strtolower(trim($request->input('message'))));

    // Check for exact duplicate in cache
    $duplicateKey = "duplicate_submission:{$email}:{$messageHash}";
    if (Cache::has($duplicateKey)) {
      return true;
    }

    // Check database for similar recent submissions
    $recentSubmission = ContactSubmission::where('email', $email)
      ->where('created_at', '>', now()->subHours(24))
      ->where(function ($query) use ($request) {
        $query->where('subject', 'LIKE', '%' . $request->input('subject') . '%')
          ->orWhere('message', 'LIKE', '%' . substr($request->input('message'), 0, 100) . '%');
      })
      ->exists();

    return $recentSubmission;
  }

  /**
   * Calculate spam score for the submission.
   */
  protected function calculateSpamScore(ContactFormRequest $request): float
  {
    $score = 0.0;
    $message = $request->input('message', '');
    $name = $request->input('name', '');
    $email = $request->input('email', '');

    // Message length checks
    $messageLength = strlen($message);
    if ($messageLength < 20) {
      $score += 0.3;
    } elseif ($messageLength > 1500) {
      $score += 0.2;
    }

    // Link count check
    $linkCount = preg_match_all('/https?:\/\//', $message);
    $score += min($linkCount * 0.2, 0.8);

    // Name pattern checks
    if (preg_match('/\d{3,}/', $name) || preg_match('/[^\w\s\-\.\']/', $name)) {
      $score += 0.3;
    }

    // Disposable email check
    $disposablePatterns = ['tempmail', '10minutemail', 'guerrillamail', 'mailinator', 'yopmail'];
    foreach ($disposablePatterns as $pattern) {
      if (str_contains($email, $pattern)) {
        $score += 0.4;
        break;
      }
    }

    // Repeated characters check
    if (preg_match('/(.)\1{5,}/', $message)) {
      $score += 0.2;
    }

    // All caps check
    if (strlen($message) > 20 && strtoupper($message) === $message) {
      $score += 0.3;
    }

    return min($score, 1.0);
  }

  /**
   * Send notifications to administrators.
   */
  protected function sendNotifications(ContactSubmission $submission): void
  {
    try {
      if ($submission->spam_score < 0.7) {
        $notificationCount = 0;

        User::role(['super-admin', 'content-manager'])
          ->chunk(100, function ($admins) use ($submission, &$notificationCount) {
            foreach ($admins as $admin) {
              try {
                $admin->notify(new ContactFormSubmission($submission));
                $notificationCount++;
              } catch (\Exception $e) {
                Log::warning('Failed to send notification to admin', [
                  'admin_id' => $admin->id,
                  'submission_id' => $submission->id,
                  'error' => $e->getMessage(),
                ]);
              }
            }
          });

        Log::info('Contact form notifications sent', [
          'submission_id' => $submission->id,
          'notification_count' => $notificationCount,
        ]);
      } else {
        Log::info('Contact form notifications skipped due to high spam score', [
          'submission_id' => $submission->id,
          'spam_score' => $submission->spam_score,
        ]);
      }
    } catch (\Exception $e) {
      Log::error('Failed to send contact form notifications', [
        'submission_id' => $submission->id,
        'error' => $e->getMessage(),
      ]);
    }
  }

  /**
   * Send auto-reply confirmation to the user.
   */
  protected function sendAutoReply(ContactSubmission $submission): void
  {
    try {
      if ($submission->spam_score < 0.5) {
        // Send auto-reply confirmation email to the user
        Mail::to($submission->email)->send(new ContactAutoReply($submission));

        Log::info('Auto-reply sent', [
          'email' => $submission->email,
          'submission_id' => $submission->id,
        ]);
      } else {
        Log::info('Auto-reply skipped due to high spam score', [
          'submission_id' => $submission->id,
          'spam_score' => $submission->spam_score,
        ]);
      }
    } catch (\Exception $e) {
      Log::warning('Failed to send auto-reply', [
        'submission_id' => $submission->id,
        'error' => $e->getMessage(),
      ]);
    }
  }

  /**
   * Cache submission details for duplicate detection.
   */
  protected function cacheSubmissionDetails(ContactFormRequest $request, ContactSubmission $submission): void
  {
    try {
      $email = strtolower(trim($request->input('email')));
      $messageHash = hash('sha256', strtolower(trim($request->input('message'))));

      $duplicateKey = "duplicate_submission:{$email}:{$messageHash}";
      Cache::put($duplicateKey, $submission->id, now()->addHours(24));

      // Cache IP submission count
      $ipKey = "ip_submission_count:" . $request->ip();
      $currentCount = Cache::get($ipKey, 0);
      Cache::put($ipKey, $currentCount + 1, now()->addHours(24));

    } catch (\Exception $e) {
      Log::warning('Failed to cache submission details', [
        'submission_id' => $submission->id,
        'error' => $e->getMessage(),
      ]);
    }
  }

  /**
   * Clean up old cache entries to prevent memory issues.
   */
  protected function cleanupOldCacheEntries(): void
  {
    try {
      // Get all cache keys with common prefixes
      $prefixes = [
        'contact-form:',
        'duplicate_submission:',
        'form_timing:',
        'ip_submission_count:',
        'contact_violations:',
        'hcaptcha_used_',
        'suspicious_ip:'
      ];

      $cleanupCount = 0;

      foreach ($prefixes as $prefix) {
        // This is cache driver dependent - Redis example
        if (config('cache.default') === 'redis') {
          $keys = Cache::store('redis')->getRedis()->keys($prefix . '*');

          foreach ($keys as $key) {
            $fullKey = str_replace(config('cache.prefix') . ':', '', $key);
            $ttl = Cache::store('redis')->getRedis()->ttl($key);

            // Remove expired or very old entries
            if ($ttl < 0 || $ttl > 86400) { // Older than 24 hours
              Cache::forget($fullKey);
              $cleanupCount++;
            }
          }
        } else {
          Cache::clear(); // Fallback for other drivers, not ideal but ensures cleanup
        }
      }

      if ($cleanupCount > 0) {
        Log::info('Cache cleanup completed', [
          'cleaned_entries' => $cleanupCount,
          'timestamp' => now(),
        ]);
      }

    } catch (\Exception $e) {
      Log::warning('Cache cleanup failed', [
        'error' => $e->getMessage(),
        'timestamp' => now(),
      ]);
    }
  }

  /**
   * Format time remaining in a human-readable format.
   */
  protected function formatTimeRemaining(int $seconds): string
  {
    if ($seconds < 60) {
      return $seconds . ' second' . ($seconds !== 1 ? 's' : '');
    }

    $minutes = ceil($seconds / 60);
    if ($minutes < 60) {
      return $minutes . ' minute' . ($minutes !== 1 ? 's' : '');
    }

    $hours = ceil($minutes / 60);
    return $hours . ' hour' . ($hours !== 1 ? 's' : '');
  }

  /**
   * Log security events with consistent format.
   */
  protected function logSecurityEvent(string $event, array $context): void
  {
    Log::warning("Contact form security event: {$event}", array_merge([
      'event_type' => $event,
      'timestamp' => now()->toISOString(),
      'severity' => 'warning',
    ], $context));

    // Increment violation counter for this IP
    if (isset($context['ip'])) {
      $violationKey = 'contact_violations:' . $context['ip'];
      $violations = Cache::get($violationKey, 0);
      Cache::put($violationKey, $violations + 1, now()->addHours(24));
    }
  }

  /**
   * Log errors with detailed context.
   */
  protected function logError(string $message, \Exception $exception, ContactFormRequest $request): void
  {
    Log::error($message, [
      'exception' => $exception->getMessage(),
      'file' => $exception->getFile(),
      'line' => $exception->getLine(),
      'trace' => $exception->getTraceAsString(),
      'request_data' => $request->except(['h-captcha-response', 'password']),
      'ip_address' => $request->ip(),
      'user_agent' => $request->userAgent(),
      'timestamp' => now()->toISOString(),
    ]);
  }
}

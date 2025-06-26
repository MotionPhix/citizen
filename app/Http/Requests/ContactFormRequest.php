<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;

class ContactFormRequest extends FormRequest
{
  /**
   * Determine if the user is authorized to make this request.
   */
  public function authorize(): bool
  {
    return true;
  }

  /**
   * Get the validation rules that apply to the request.
   */
  public function rules(): array
  {
    $rules = [
      'name' => [
        'required',
        'string',
        'min:2',
        'max:100',
        'regex:/^[a-zA-Z\s\-\.\'À-ÿ]+$/u' // Allow unicode characters for international names
      ],
      'email' => [
        'required',
        'email:rfc,dns',
        'max:255',
        'not_regex:/\+.*@.*\+/' // Prevent multiple + signs which could indicate manipulation
      ],
      'subject' => [
        'required',
        'string',
        'min:5',
        'max:200',
        'not_regex:/https?:\/\/|www\.|\.com|\.net|\.org/i' // Prevent URLs and common TLDs
      ],
      'message' => [
        'required',
        'string',
        'min:10',
        'max:2000',
        function ($attribute, $value, $fail) {
          // Check for excessive links
          $linkCount = preg_match_all('/https?:\/\//', $value);
          if ($linkCount > 2) {
            $fail('Please limit URLs in your message to 2 or fewer.');
          }

          // Check for spam-like repetition
          if (preg_match('/(.{3,})\1{3,}/i', $value)) {
            $fail('Please avoid excessive repetition in your message.');
          }

          // Check for suspicious patterns
          $spamKeywords = ['viagra', 'casino', 'lottery', 'winner', 'prize', 'congratulations', 'bitcoin', 'crypto'];
          $lowerValue = strtolower($value);
          foreach ($spamKeywords as $keyword) {
            if (str_contains($lowerValue, $keyword)) {
              $fail('Your message contains content that appears to be spam.');
              break;
            }
          }
        }
      ],
      'h-captcha-response' => [
        'required',
        'string',
        'min:20' // hCaptcha tokens are typically much longer
      ],
    ];

    // Add Spatie Honeypot validation rules
    $honeypotConfig = config('honeypot');
    if ($honeypotConfig['enabled'] ?? false) {
      $nameField = $honeypotConfig['name_field_name'] ?? 'my_name';
      $timeField = $honeypotConfig['valid_from_field_name'] ?? 'my_time';

      $rules[$nameField] = ['honeypot'];
      $rules[$timeField] = ['required']; // 3 seconds minimum
    }

    return $rules;
  }

  /**
   * Get custom error messages for validator errors.
   */
  public function messages(): array
  {
    return [
      // Name validation messages
      'name.required' => 'Please enter your full name.',
      'name.min' => 'Your name must be at least 2 characters long.',
      'name.max' => 'Your name cannot exceed 100 characters.',
      'name.regex' => 'Please enter a valid name using only letters, spaces, hyphens, dots, and apostrophes.',

      // Email validation messages
      'email.required' => 'Please enter your email address.',
      'email.email' => 'Please enter a valid email address.',
      'email.max' => 'Email address cannot exceed 255 characters.',
      'email.not_regex' => 'Please use a standard email address format.',

      // Subject validation messages
      'subject.required' => 'Please enter a subject for your message.',
      'subject.min' => 'Subject must be at least 5 characters long.',
      'subject.max' => 'Subject cannot exceed 200 characters.',
      'subject.not_regex' => 'Please avoid including URLs or promotional content in the subject line.',

      // Message validation messages
      'message.required' => 'Please enter your message.',
      'message.min' => 'Your message must be at least 10 characters long.',
      'message.max' => 'Your message cannot exceed 2000 characters.',

      // hCaptcha validation messages
      'h-captcha-response.required' => 'Please complete the captcha verification.',
      'h-captcha-response.string' => 'Invalid captcha response format.',
      'h-captcha-response.min' => 'Invalid captcha response received.',

      // Honeypot messages (these should be generic to not reveal the honeypot)
      '*.honeypot' => 'Please try submitting the form again.',
      '*.honeypottime' => 'Please try submitting the form again.',
    ];
  }

  /**
   * Get custom attributes for validator errors.
   */
  public function attributes(): array
  {
    return [
      'name' => 'full name',
      'email' => 'email address',
      'subject' => 'subject',
      'message' => 'message',
      'h-captcha-response' => 'captcha verification',
    ];
  }

  /**
   * Configure the validator instance.
   */
  public function withValidator($validator): void
  {
    $validator->after(function ($validator) {
      // Verify hCaptcha
      if (!$this->verifyCaptcha()) {
        $validator->errors()->add(
          'h-captcha-response',
          'Captcha verification failed. Please try again.'
        );
      }

      // Additional security checks
      $this->performSecurityChecks($validator);
    });
  }

  /**
   * Verify hCaptcha response with enhanced security.
   */
  protected function verifyCaptcha(): bool
  {
    $captchaResponse = $this->input('h-captcha-response');

    if (empty($captchaResponse)) {
      Log::warning('hCaptcha verification failed: empty response', [
        'ip' => $this->ip(),
      ]);
      return false;
    }

    // Check for replay attacks - ensure this token hasn't been used before
    $cacheKey = 'hcaptcha_used_' . hash('sha256', $captchaResponse);
    if (Cache::has($cacheKey)) {
      Log::warning('hCaptcha replay attack detected', [
        'ip' => $this->ip(),
        'user_agent' => $this->userAgent(),
        'captcha_hash' => substr(hash('sha256', $captchaResponse), 0, 8),
      ]);
      return false;
    }

    try {
      // Get the correct configuration keys - fix the config key name issue
      $secretKey = config('services.hcaptcha.secret');
      $siteKey = config('services.hcaptcha.sitekey');

      // Debug log the configuration
      Log::info('hCaptcha configuration check', [
        'has_secret' => !empty($secretKey),
        'has_sitekey' => !empty($siteKey),
        'secret_length' => $secretKey ? strlen($secretKey) : 0,
        'sitekey_length' => $siteKey ? strlen($siteKey) : 0,
      ]);

      if (empty($secretKey)) {
        Log::error('hCaptcha secret key not configured', [
          'config_path' => 'services.hcaptcha.secret',
          'env_var' => 'HCAPTCHA_SECRET_KEY',
        ]);
        return false;
      }

      $response = Http::timeout(10)
        ->retry(2, 1000) // Retry twice with 1 second delay
        ->asForm()
        ->post('https://hcaptcha.com/siteverify', [
          'secret' => $secretKey,
          'response' => $captchaResponse,
          'sitekey' => $siteKey,
          'remoteip' => $this->ip(),
        ]);

      if (!$response->successful()) {
        Log::error('hCaptcha API request failed', [
          'status' => $response->status(),
          'body' => $response->body(),
          'ip' => $this->ip(),
        ]);
        return false;
      }

      $data = $response->json();
      $success = $data['success'] ?? false;

      // Log the full response for debugging
      Log::info('hCaptcha API response', [
        'success' => $success,
        'error_codes' => $data['error-codes'] ?? [],
        'hostname' => $data['hostname'] ?? null,
        'challenge_ts' => $data['challenge_ts'] ?? null,
        'ip' => $this->ip(),
      ]);

      if ($success) {
        // Cache this token to prevent replay attacks (valid for 5 minutes)
        Cache::put($cacheKey, true, now()->addMinutes(5));

        Log::info('hCaptcha verification successful', [
          'ip' => $this->ip(),
        ]);
      } else {
        Log::warning('hCaptcha verification failed', [
          'ip' => $this->ip(),
          'error_codes' => $data['error-codes'] ?? [],
          'hostname' => $data['hostname'] ?? null,
        ]);
      }

      return $success;

    } catch (\Exception $e) {
      Log::error('hCaptcha verification exception', [
        'message' => $e->getMessage(),
        'ip' => $this->ip(),
        'trace' => $e->getTraceAsString(),
      ]);
      return false;
    }
  }

  /**
   * Perform additional security checks.
   */
  protected function performSecurityChecks($validator): void
  {
    $ip = $this->ip();
    $userAgent = $this->userAgent();

    // Check for suspicious user agents
    $suspiciousAgents = ['bot', 'crawler', 'spider', 'scraper', 'curl', 'wget'];
    foreach ($suspiciousAgents as $agent) {
      if (stripos($userAgent, $agent) !== false) {
        Log::warning('Suspicious user agent detected', [
          'ip' => $ip,
          'user_agent' => $userAgent,
        ]);
        $validator->errors()->add('email', 'Please try again with a standard web browser.');
        return;
      }
    }

    // Fix timing check issue - use absolute time difference
    $timingKey = 'form_timing_' . $ip;
    $lastSubmission = Cache::get($timingKey);

    if ($lastSubmission) {
      $timeDiff = abs(now()->diffInSeconds($lastSubmission));

      Log::info('Form timing check', [
        'ip' => $ip,
        'time_diff' => $timeDiff,
        'last_submission' => $lastSubmission->toISOString(),
        'current_time' => now()->toISOString(),
      ]);

      if ($timeDiff < 3) {
        Log::warning('Form submitted too quickly', [
          'ip' => $ip,
          'time_diff' => $timeDiff,
        ]);
        $validator->errors()->add('name', 'Please take a moment to fill out the form completely.');
        return;
      }
    }

    // Update timing cache with current timestamp
    Cache::put($timingKey, now(), now()->addMinutes(10));

    // Check for duplicate submissions
    $contentHash = hash('sha256',
      strtolower(trim($this->input('name'))) .
      strtolower(trim($this->input('email'))) .
      strtolower(trim($this->input('message')))
    );

    $duplicateKey = 'contact_duplicate_' . $contentHash;
    if (Cache::has($duplicateKey)) {
      Log::warning('Duplicate contact form submission', [
        'ip' => $ip,
        'content_hash' => substr($contentHash, 0, 8),
      ]);
      $validator->errors()->add('message', 'This message appears to be a duplicate. Please modify your message if you need to resubmit.');
      return;
    }

    // Cache this content hash to prevent duplicates for 24 hours
    Cache::put($duplicateKey, true, now()->addHours(24));
  }

  /**
   * Get sanitized and validated data for processing.
   */
  public function getValidatedData(): array
  {
    return [
      'name' => trim($this->input('name')),
      'email' => strtolower(trim($this->input('email'))),
      'subject' => trim($this->input('subject')),
      'message' => trim($this->input('message')),
      'ip_address' => $this->ip(),
      'user_agent' => $this->userAgent(),
      'submitted_at' => now(),
    ];
  }

  /**
   * Check if this request passes basic security validations.
   */
  public function passesSecurityChecks(): bool
  {
    // Check if hCaptcha was verified
    if (!$this->verifyCaptcha()) {
      return false;
    }

    // Additional checks can be added here
    return true;
  }

  /**
   * Get a formatted summary for logging.
   */
  public function getLogSummary(): array
  {
    return [
      'name' => $this->input('name'),
      'email' => $this->input('email'),
      'subject' => $this->input('subject'),
      'message_length' => strlen($this->input('message', '')),
      'ip_address' => $this->ip(),
      'user_agent' => $this->userAgent(),
      'has_captcha' => !empty($this->input('h-captcha-response')),
      'timestamp' => now()->toISOString(),
    ];
  }
}

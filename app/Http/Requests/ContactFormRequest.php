<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Http;

class ContactFormRequest extends FormRequest
{
  public function authorize(): bool
  {
    return true;
  }

  public function rules(): array
  {
    return [
      'name' => ['required', 'string', 'max:255'],
      'email' => ['required', 'email', 'max:255'],
      'subject' => ['required', 'string', 'max:255'],
      'message' => ['required', 'string', 'max:5000'],
      'h-captcha-response' => [
        'required',
        function ($attribute, $value, $fail) {
          $response = Http::asForm()->post('https://api.hcaptcha.com/siteverify', [
            'secret' => config(('services.hcaptcha.secret')),
            'response' => $value,
            'sitekey' => env(('services.hcaptcha.sitekey')),
            'remoteip' => $this->ip(),
          ]);

          if (!$response->successful() || !$response->json('success')) {
            $fail('The captcha verification failed. Please try again.');
          }
        },
      ],
    ];
  }

  public function messages(): array
  {
    return [
      'h-captcha-response.required' => 'Please complete the captcha verification.',
    ];
  }

  /**
   * Configure the validator instance.
   */
  public function withValidator($validator)
  {
    // Add request time validation to prevent replay attacks
    $validator->after(function ($validator) {
      $response = Http::asForm()->post('https://api.hcaptcha.com/siteverify', [
        'secret' => env(('services.hcaptcha.secret')),
        'response' => $this->input('h-captcha-response'),
        'sitekey' => env(('services.hcaptcha.sitekey')),
        'remoteip' => $this->ip(),
      ]);

      if ($response->successful() && $response->json('success')) {
        // Check if the token is too old (more than 5 minutes)
        $challengeTs = strtotime($response->json('challenge_ts'));
        $currentTime = time();

        if (($currentTime - $challengeTs) > 300) { // 300 seconds = 5 minutes
          $validator->errors()->add(
            'h-captcha-response',
            'The captcha has expired. Please verify again.'
          );
        }
      }
    });
  }
}

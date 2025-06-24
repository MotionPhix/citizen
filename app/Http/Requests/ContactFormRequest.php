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
      'message' => ['required', 'string'],
      'h-captcha-response' => ['required', 'string'],
    ];
  }

  public function withValidator($validator)
  {
    $validator->after(function ($validator) {
      if (!$this->verifyCaptcha()) {
        $validator->errors()->add(
          'h-captcha-response',
          'The captcha verification failed. Please try again.'
        );
      }
    });
  }

  protected function verifyCaptcha(): bool
  {
    $response = Http::asForm()->post('https://hcaptcha.com/siteverify', [
      'secret' => config('services.hcaptcha.secret'),
      'response' => $this->input('h-captcha-response'),
      'sitekey' => config('services.hcaptcha.sitekey'),
    ]);

    return $response->json('success', false);
  }
}

<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

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
      'h-captcha-response' => ['required', 'HCaptcha'], // We'll use hCaptcha for spam protection
    ];
  }

  public function messages(): array
  {
    return [
      'h-captcha-response.required' => 'Please complete the captcha verification.',
      'h-captcha-response.hcaptcha' => 'Captcha verification failed. Please try again.',
    ];
  }
}

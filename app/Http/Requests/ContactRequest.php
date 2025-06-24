<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Http;

class ContactRequest extends FormRequest
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
      'g-recaptcha-response' => ['required', 'string'],
    ];
  }

  public function withValidator($validator)
  {
    $validator->after(function ($validator) {
      if (!$this->verifyRecaptcha()) {
        $validator->errors()->add(
          'g-recaptcha-response',
          'Failed to verify you are human. Please try again.'
        );
      }
    });
  }

  protected function verifyRecaptcha(): bool
  {
    $response = Http::asForm()->post('https://www.google.com/recaptcha/api/siteverify', [
      'secret' => config('services.recaptcha.secret_key'),
      'response' => $this->input('g-recaptcha-response'),
      'remoteip' => $this->ip(),
    ]);

    return $response->json('success', false) && $response->json('score', 0) > 0.5;
  }
}

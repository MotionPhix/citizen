<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CommentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // Allow anonymous commenting
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $rules = [
            'content' => [
                'required',
                'string',
                'min:10',
                'max:2000',
            ],
            'parent_id' => [
                'nullable',
                'exists:comments,id'
            ],
            'notify_on_reply' => 'boolean',
        ];

        // If user is not authenticated, require name and email
        if (!auth()->check()) {
            $rules['author_name'] = [
                'required',
                'string',
                'min:2',
                'max:100',
                'regex:/^[a-zA-Z\s\-\.\']+$/', // Only letters, spaces, hyphens, dots, apostrophes
            ];

            $rules['author_email'] = [
                'required',
                'email:rfc,dns',
                'max:255',
            ];

            $rules['author_website'] = [
                'nullable',
                'url',
                'max:255',
            ];
        }

        return $rules;
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'content.required' => 'Please enter your comment.',
            'content.min' => 'Your comment must be at least 10 characters long.',
            'content.max' => 'Your comment cannot exceed 2000 characters.',
            'author_name.required' => 'Please enter your name.',
            'author_name.regex' => 'Name can only contain letters, spaces, hyphens, dots, and apostrophes.',
            'author_email.required' => 'Please enter your email address.',
            'author_email.email' => 'Please enter a valid email address.',
            'author_website.url' => 'Please enter a valid website URL.',
            'parent_id.exists' => 'The comment you are replying to does not exist.',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes(): array
    {
        return [
            'author_name' => 'name',
            'author_email' => 'email',
            'author_website' => 'website',
            'parent_id' => 'parent comment',
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        // Clean and prepare the data
        $this->merge([
            'content' => trim($this->input('content')),
            'author_name' => $this->input('author_name') ? trim($this->input('author_name')) : null,
            'author_email' => $this->input('author_email') ? strtolower(trim($this->input('author_email'))) : null,
            'author_website' => $this->input('author_website') ? trim($this->input('author_website')) : null,
            'notify_on_reply' => $this->boolean('notify_on_reply', true),
        ]);

        // Add http:// to website if missing protocol
        if ($this->input('author_website') && !preg_match('/^https?:\/\//', $this->input('author_website'))) {
            $this->merge([
                'author_website' => 'http://' . $this->input('author_website')
            ]);
        }
    }
}

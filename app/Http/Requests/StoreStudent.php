<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreStudent extends FormRequest
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
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|unique:student|max:50',
            'email' => 'required|email|unique:students',
            'university_id' => 'required|integer'
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'A name student is required',
            'email.required' => 'A email is required',
            'email.email' => 'A email needs to be a valid email',
            'university_id.required' => 'An university id needs to be provided',
        ];
    }
}

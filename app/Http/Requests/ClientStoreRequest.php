<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ClientStoreRequest extends FormRequest
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
        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'middle_name' => ['nullable', 'string', 'max:255'],
            'surname' => ['nullable', 'string', 'max:255'],
            'date_of_birth' => ['required', 'date'],
            'time_of_birth' => ['nullable', 'date_format:H:i'],
            'sex' => ['nullable', 'string', 'in:male,female,na'],
            'country_of_birth' => ['nullable', 'string', 'max:255'],
            'town_of_birth' => ['nullable', 'string', 'max:255'],
        ];
        
        // SaveAs is only required if user is logged in (uniqueness handled manually for overwrite support)
        if ($this->user()) {
            $rules['save_as'] = [
                'required', 
                'string', 
                'max:255',
            ];
        }
        
        return $rules;
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'save_as.required' => 'The SaveAs field is required.',
        ];
    }
}

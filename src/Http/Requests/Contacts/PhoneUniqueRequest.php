<?php

namespace Vitaliiriabyshenko\Contacts\Http\Requests\Contacts;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class PhoneUniqueRequest extends FormRequest
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
        return [
            'phone_id' => ['nullable', 'integer', 'exists:phones,id'],
            'phone' => [
                'required',
                'string',
                'min:7',
                'max:30',
                'regex:/^(\+\d{1,2}\s?)?\(?\d{3}\)?[\s.-]?\d{3}[\s.-]?\d{4}$/',
                Rule::unique('phones', 'value')->ignore($this->phone_id)
            ]
        ];
    }
}

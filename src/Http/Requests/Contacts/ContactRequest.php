<?php

namespace Vitaliiriabyshenko\Contacts\Http\Requests\Contacts;

use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Illuminate\Foundation\Http\FormRequest;

class ContactRequest extends FormRequest
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
            'first_name' => ['required', 'string', 'min:2', 'max:50'],
            'last_name' => ['required', 'string', 'min:2', 'max:50'],
            'phones' => ['required', 'array', 'min:1'],
            'phones.*.id' => [
                'nullable',
                'integer',
                Rule::exists('phones', 'id')->where('contact_id', $this->route('contact'))
            ],
            'phones.*.value' => [
                'required',
                'string',
                'min:7',
                'max:30',
                'regex:/^(\+\d{1,2}\s?)?\(?\d{3}\)?[\s.-]?\d{3}[\s.-]?\d{4}$/',
                'distinct',
                function ($attribute, $value, $fail) {
                    $index = explode('.', $attribute)[1];
                    $phoneId = $this->input("phones.$index.id");

                    $query = DB::table('phones')
                        ->where('value', $value);

                    if ($phoneId) {
                        $query->where('id', '!=', $phoneId);
                    }

                    if ($query->exists()) {
                        $fail('The phone number has already been taken.');
                    }
                }
            ],
        ];
    }

    public function attributes()
    {
        return [
            'phones.*.value' => 'phone'
        ];
    }
}

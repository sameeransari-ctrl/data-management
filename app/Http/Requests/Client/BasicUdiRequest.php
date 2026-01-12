<?php

namespace App\Http\Requests\Client;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class BasicUdiRequest extends FormRequest
{
    /**
     * Method authorize
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', Rule::unique('basic_udids')->ignore($this->id)],
        ];
    }

    /**
     * Method messages
     *
     * @return void
     */
    public function messages()
    {
        $required = 'validation.required';
        return [
            'name.required' => trans($required, ['attribute' => 'basic udi number']),
            'name.unique' => trans('validation.unique', ['attribute' => 'basic udi number']),
        ];
    }
}

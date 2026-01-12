<?php

namespace App\Http\Requests\Admin;

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
     * Method rules
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'name' => ['required', Rule::unique('basic_udids')->ignore($this->id)],
            'added_by' => 'required',
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
            'added_by.required' => trans($required, ['attribute' => 'client name']),
        ];
    }
}

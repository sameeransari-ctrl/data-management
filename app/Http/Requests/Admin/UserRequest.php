<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use App\Rules\AlphaSpacesRule;
use App\Rules\EmailFormatRule;
use App\Rules\RemoveMultiSpace;
use Illuminate\Validation\Rule;

class UserRequest extends FormRequest
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
            'email' => ['required', new EmailFormatRule(), Rule::unique('users')->ignore($this->id)],
            'name' => ['required','min:2', 'max:30', new AlphaSpacesRule, new RemoveMultiSpace],
            'phone_number' => ['required', 'numeric', 'digits_between:10,12', Rule::unique('users')->ignore($this->id)],
            'user_type' => 'required',
            'address' => ['required', 'min:2', 'max:70'],
            'city_id' => 'required',
            'country_id' => 'required',
            'zip_code' => ['required', 'min:4', 'max:8'],
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
            'name.required' => trans($required, ['attribute' => 'name']),
            'phone_number.required' => trans($required, ['attribute' => 'mobile number']),
            'phone_number.numeric' => trans('validation.numeric', ['attribute' => 'mobile number']),
            'phone_number.digits_between' => trans('validation.digits_between', ['attribute' => 'mobile number']),
            'phone_number.unique' => trans('validation.unique', ['attribute' => 'mobile number']),
            'city_id.required' => trans($required, ['attribute' => 'city']),
            'country_id.required' => trans($required, ['attribute' => 'country']),
        ];
    }
}

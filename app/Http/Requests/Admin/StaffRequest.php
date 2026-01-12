<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use App\Rules\AlphaSpacesRule;
use App\Rules\UpdateStrictPasswordRule;
use App\Rules\UpdateWithoutSpaceRule;
use App\Rules\EmailFormatRule;
use App\Rules\RemoveMultiSpace;
use Illuminate\Validation\Rule;



class StaffRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
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
            'name' => ['required','min:2', 'max:50', new AlphaSpacesRule, new RemoveMultiSpace],
            'phone_number' => ['required', 'numeric', 'digits_between:10,12', Rule::unique('users')->ignore($this->id)],
            'user_type' => 'required',
            "password" => ['required_if:formType,==,add', new UpdateWithoutSpaceRule($this->id), new UpdateStrictPasswordRule($this->id)],
            'confirm_password' => 'required_if:formType,==,add|same:password',
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
            'user_type.required' => trans($required, ['attribute' => 'role']),
            'password.required_if' => trans($required, ['attribute' => 'password']),
            'confirm_password.required_if' => trans($required, ['attribute' => 'confirm password']),
        ];
    }

}

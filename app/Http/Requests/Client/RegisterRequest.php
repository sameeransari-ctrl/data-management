<?php

namespace App\Http\Requests\Client;

use Illuminate\Foundation\Http\FormRequest;
use App\Rules\AlphaSpacesRule;
use App\Rules\EmailFormatRule;
use App\Rules\RemoveMultiSpace;
use App\Rules\StrictPasswordRule;
use App\Rules\WithoutSpacesRule;
use Illuminate\Validation\Rule;

class RegisterRequest extends FormRequest
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
            'name' => ['required','min:2', 'max:50', new AlphaSpacesRule, new RemoveMultiSpace],
            'email' => ['required', new EmailFormatRule(), Rule::unique('users')],
            'phone_number' => ['required', 'numeric', 'digits_between:10,12', Rule::unique('users')],
            'client_role_id' => 'required',
            'address' => 'required',
            'country_id' => 'required',
            'city_id' => 'required',
            "password" => ['required', new WithoutSpacesRule(), new StrictPasswordRule()],
            'confirm_password' => 'required|same:password',
            'actor_id' => ['required', Rule::unique('users')],
            'card_holder_name' => ['required','min:2', 'max:50', new AlphaSpacesRule, new RemoveMultiSpace],
            'card_number' => ['required', 'numeric', 'digits_between:8,20'],
            // 'ifsc_code' => ['required'],
            // 'iban_number' => ['required'],
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
            'phone_number.required' => trans($required, ['attribute' => 'mobile number']),
            'phone_number.numeric' => trans('validation.numeric', ['attribute' => 'mobile number']),
            'phone_number.digits_between' => trans('validation.digits_between', ['attribute' => 'mobile number']),
            'phone_number.unique' => trans('validation.unique', ['attribute' => 'mobile number']),
            'client_role_id.required' => trans($required, ['attribute' => 'role']),
            'country_id.required' => trans($required, ['attribute' => 'country']),
            'city_id.required' => trans($required, ['attribute' => 'city']),
            'password.required' => trans($required, ['attribute' => 'password']),
            'confirm_password.required' => trans($required, ['attribute' => 'confirm password']),
            'actor_id.required' => trans($required, ['attribute' => 'actor id/srn']),
            'actor_id.unique' => trans('validation.unique', ['attribute' => 'actor id/srn']),

            // 'card_holder_name.required' => trans($required, ['attribute' => 'account holder name']),
            // 'card_holder_name.min' => trans('validation.min', ['attribute' => 'account holder name']),
            // 'card_holder_name.max' => trans('validation.max', ['attribute' => 'account holder name']),
            // 'card_number.required' => trans($required, ['attribute' => 'account number']),
            // 'card_number.numeric' => trans('validation.numeric', ['attribute' => 'account number']),
            // 'card_number.digits_between' => trans('validation.digits_between', ['attribute' => 'account number']),
            // 'ifsc_code.required' => trans($required, ['attribute' => 'IFSC/BIC/SWIFT Code']),
            // 'iban_number.required' => trans($required, ['attribute' => 'IBAN No.']),
        ];
    }
}

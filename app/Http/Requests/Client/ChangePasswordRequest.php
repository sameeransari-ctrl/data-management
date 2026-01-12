<?php

namespace App\Http\Requests\Client;

use App\Rules\{
    CheckCurrentPassword,
    WithoutSpacesRule,
    StrictPasswordRule
};
use Illuminate\Foundation\Http\FormRequest;

class ChangePasswordRequest extends FormRequest
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
     * @return void
     */
    public function rules()
    {
        return [
            'current_password' => [
                'required',
                'string',
                new CheckCurrentPassword
            ],
            'password'=> [
                'required',
                'confirmed',
                'different:current_password',
                new WithoutSpacesRule,
                new StrictPasswordRule
            ],
            'password_confirmation' => 'required|same:password',
        ];
    }

    /**
     * Method messages
     *
     * @return void
     */
    public function messages()
    {
        return [
            'current_password.required' => trans(
                'validation.required', ['attribute' => 'current password']
            ),
            'password.required' => trans(
                'validation.required', ['attribute' => 'new password']
            ),
            'password.confirmed' => trans('validation.password.confirmed'),
            'password.different' => trans('validation.password.different'),

        ];
    }
}

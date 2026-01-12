<?php

namespace App\Http\Requests\Admin;
use App\Rules\{
    WithoutSpacesRule,
    StrictPasswordRule
};

use Illuminate\Foundation\Http\FormRequest;

class OtpResetPasswordRequest extends FormRequest
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
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'email' => 'required|email',
            'otp' => 'required',
            'password'=> [
                'required',
                'confirmed',
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
            'password.required' => trans(
                'validation.required', ['attribute' => 'new password']
            ),
            'password.confirmed' => trans('validation.password.confirmed'),
            'otp.required' => trans('validation.required', ['attribute' => 'otp']),
            'password_confirmation.required' => trans('validation.required', ['attribute' => 'confirm new password']),
            'password_confirmation.same' => trans('validation.same', ['attribute' => 'confirm new password']),

        ];
    }
}

<?php

namespace App\Http\Requests\Api;

use App\Http\Requests\Api\ApiRequest;
use App\Rules\{
    CheckCurrentPassword,
    WithoutSpacesRule,
    StrictPasswordRule
};
use Illuminate\Foundation\Http\FormRequest;

class ChangePasswordRequest extends ApiRequest
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
     * @return array
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
        ];
    }

    /**
     * Get the validation error message.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'current_password.required' => trans(
                'validation.required', ['attribute' => 'Current password']
            ),
            'password.required' => trans(
                'validation.required', ['attribute' => 'New password']
            ),
            'password.confirmed' => trans('validation.password.confirmed'),
            'password.different' => trans('validation.password.different'),

        ];
    }
}

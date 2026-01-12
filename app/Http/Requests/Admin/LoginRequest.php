<?php

namespace App\Http\Requests\Admin;

use App\Rules\EmailFormatRule;
use Illuminate\Foundation\Http\FormRequest;

/**
 * LoginRequest
 */
class LoginRequest extends FormRequest
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
            'email' => ['required', new EmailFormatRule],
            'password' => 'required',
            'remember' => '',
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
            'email.required' => trans(
                'validation.required',
                ['attribute' => 'Email']
            ),
            'password.required' => trans(
                'validation.required',
                ['attribute' => 'Password']
            ),
        ];
    }
}

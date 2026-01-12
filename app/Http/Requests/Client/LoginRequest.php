<?php

namespace App\Http\Requests\Client;

use App\Rules\EmailFormatRule;
use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
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
            'email' => ['required', new EmailFormatRule],
            'password' => 'required',
            'remember' => '',
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
            'email.required' => trans(
                'validation.required',
                ['attribute' => 'email']
            ),
            'password.required' => trans(
                'validation.required',
                ['attribute' => 'password']
            ),
        ];
    }
}

<?php

namespace App\Http\Requests\Client;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class VerifyEmailRequest extends FormRequest
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
            'email' => ['required','email',
            Rule::exists('users', 'email')
            ->where('user_type', User::TYPE_CLIENT)]
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
            'email.email' => trans('validation.email'),
            'email.exists' => trans('validation.not_found'),
            'email.required' => trans(
                'validation.required',
                ['attribute' => 'email']
            ),
        ];
    }
}

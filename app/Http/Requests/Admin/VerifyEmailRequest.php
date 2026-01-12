<?php

namespace App\Http\Requests\Admin;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * VerifyEmailRequest
 */
class VerifyEmailRequest extends FormRequest
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
            'email' => ['required','email',
            Rule::exists('users', 'email')
            ->whereIn('user_type', [User::TYPE_ADMIN, User::TYPE_STAFF])]
        ];
    }

    /**
     * Method messages
     *
     * @return array
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

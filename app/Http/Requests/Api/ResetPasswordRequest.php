<?php

namespace App\Http\Requests\Api;

class ResetPasswordRequest extends ApiRequest
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
            'password' => 'required|min:6|max:15',
            'confirm_password' => 'required|same:password',
        ];
    }
}

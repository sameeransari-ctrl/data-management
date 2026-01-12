<?php

namespace App\Http\Requests\Client;

use Illuminate\Foundation\Http\FormRequest;

class VerifyOtpRequest extends FormRequest
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
            'email' => 'required|email',
            'otp' => 'required',
        ];
    }
}

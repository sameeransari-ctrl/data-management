<?php

namespace App\Http\Requests\Api;

use App\Models\User;
use Illuminate\Validation\Rule;
use App\Rules\{
    EmailFormatRule
};
class SendOtpRequest extends ApiRequest
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
            'username_type' => 'bail|required|in:email,phone_number',
            'email' => ['bail', 'nullable', 'required_if:username_type,email', new EmailFormatRule],
            'phone_code' => 'bail|nullable|required_if:username_type,phone_number|digits_between:1,4',
            'phone_number' => 'bail|nullable|digits_between:10,12|required_if:username_type,phone_number',
            'type' => 'nullable|string|'.Rule::in(User::$otpTypes),
            'device_id' => 'required',
            'device_type' => 'required|string|in:ios,android',
        ];
    }
}

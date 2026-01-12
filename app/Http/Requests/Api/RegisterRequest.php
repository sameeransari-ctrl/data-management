<?php

namespace App\Http\Requests\Api;

use App\Models\User;
use Illuminate\Validation\Rule;
use App\Rules\{
    WithoutSpacesRule,
    StrictPasswordRule
};
class RegisterRequest extends ApiRequest
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
            'name' => 'required|string|min:2|max:50',
            'email' => 'required|email:strict|unique:users,email,NULL,id,deleted_at,NULL',
            'phone_code' => 'required|digits_between:1,4',
            'phone_number' => 'required|digits_between:6,14|unique:users,phone_number,NULL,id,deleted_at,NULL',
            'user_type' => 'required|string|'.Rule::in(User::$userTypes),
            'address' => 'required|string|min:3|max:255',
            'country_id' => 'required|exists:countries,id',
            'city_id' => 'required|exists:cities,id',
            'zip_code' => 'required|digits_between:4,8',
        ];
    }
}

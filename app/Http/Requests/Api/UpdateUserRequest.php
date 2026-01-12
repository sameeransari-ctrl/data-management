<?php

namespace App\Http\Requests\Api;

use Illuminate\Validation\Rule;

class UpdateUserRequest extends ApiRequest
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
            'email' => 'required|email:strict|'.Rule::unique('users')->ignore(auth()->user()),
            'phone_code' => 'required|digits_between:1,4',
            'phone_number' => 'required|digits_between:6,14|'.Rule::unique('users')->ignore(auth()->user()),
            'profile_image' => 'nullable|mimes:jpg,png,jpeg',
            'address' => 'required|string|min:3|max:255',
            'country_id' => 'required|exists:countries,id',
            'city_id' => 'required|exists:cities,id',
            'zip_code' => 'required|digits_between:4,8',
        ];
    }
}

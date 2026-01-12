<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use App\Rules\AlphaSpacesRule;
use App\Rules\UpdateStrictPasswordRule;
use App\Rules\UpdateWithoutSpaceRule;
use App\Rules\EmailFormatRule;
use App\Rules\RemoveMultiSpace;
use Illuminate\Validation\Rule;

class ClientRequest extends FormRequest
{
    /**
     * Determine if the client is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required','min:2', 'max:50'],
            'email' => ['required', new EmailFormatRule(), Rule::unique('users')->ignore($this->id)],
            'phone_number' => ['required', 'numeric', 'digits_between:10,12', Rule::unique('users')->ignore($this->id)],
            'client_role_id' => 'required',
            'actor_id' => ['required', Rule::unique('users')->ignore($this->id)],
            'address' => 'required',
            'city_id' => 'required',
            'country_id' => 'required',
            "password" => ['required_if:formType,==,add', new UpdateWithoutSpaceRule($this->id), new UpdateStrictPasswordRule($this->id)],
            'confirm_password' => 'required_if:formType,==,add|same:password',
            'card_holder_name' => ['required','min:2', 'max:50', new AlphaSpacesRule, new RemoveMultiSpace],
            'card_number' => ['required','numeric'],
        ];
    }

    /**
     * Method messages
     *
     * @return void
     */
    public function messages()
    {
        $required = 'validation.required';
        return [
            'name.required' => trans($required, ['attribute' => 'name']),
            'city_id.required' => trans($required, ['attribute' => 'city']),
            'country_id.required' => trans($required, ['attribute' => 'country']),
            'password.required_if' => trans($required, ['attribute' => 'password']),
            'confirm_password.required_if' => trans($required, ['attribute' => 'confirm password']),
            'client_role_id.required' => trans($required, ['attribute' => 'role']),
            'card_number.required' => trans($required, ['attribute' => 'account number']),
            'card_number.numeric' => trans('validation.numeric', ['attribute' => 'account number']),
            'card_holder_name.required' => trans($required, ['attribute' => 'account holder name']),
            'card_holder_name.min' => trans('validation.min', ['attribute' => 'account holder name']),
            'card_holder_name.max' => trans('validation.max', ['attribute' => 'account holder name']),
            'phone_number.required' => trans($required, ['attribute' => 'mobile number']),
            'phone_number.numeric' => trans('validation.numeric', ['attribute' => 'mobile number']),
            'phone_number.digits_between' => trans('validation.digits_between', ['attribute' => 'mobile number']),
            'phone_number.unique' => trans('validation.unique', ['attribute' => 'mobile number']),
        ];
    }
}

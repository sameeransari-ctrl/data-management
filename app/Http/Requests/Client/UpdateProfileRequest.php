<?php

namespace App\Http\Requests\Client;

use App\Rules\AlphaSpacesRule;
use App\Rules\EmailFormatRule;
use App\Rules\RemoveMultiSpace;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;

class UpdateProfileRequest extends FormRequest
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
           'name'  => ['required','min:3','max:50',new AlphaSpacesRule,new RemoveMultiSpace],
           'email' => ['required','min:1','max:50','unique:users,email,'.Auth::user()->id,new EmailFormatRule],
           'phone_number' => ['required', 'numeric', 'digits_between:10,12', 'unique:users,phone_number,'.Auth::user()->id],
           'address' => 'required',
           'city_id' => 'required',
           'country_id' => 'required',
           'actor_id' => ['required', 'unique:users,actor_id,'.Auth::user()->id],
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
            'phone_number.required' => trans($required, ['attribute' => 'mobile number']),
            'phone_number.numeric' => trans('validation.numeric', ['attribute' => 'mobile number']),
            'phone_number.digits_between' => trans('validation.digits_between', ['attribute' => 'mobile number']),
            'phone_number.unique' => trans('validation.unique', ['attribute' => 'mobile number']),
            'city_id.required' => trans($required, ['attribute' => 'city']),
            'country_id.required' => trans($required, ['attribute' => 'country']),
            'actor_id.required' => trans($required, ['attribute' => 'actor id/srn']),
            'actor_id.unique' => trans('validation.unique', ['attribute' => 'actor id/srn']),
        ];
    }
}

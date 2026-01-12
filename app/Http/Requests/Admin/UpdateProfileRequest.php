<?php

namespace App\Http\Requests\Admin;

use App\Rules\AlphaSpacesRule;
use App\Rules\EmailFormatRule;
use App\Rules\RemoveMultiSpace;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

/**
 * UpdateProfileRequest
 */
class UpdateProfileRequest extends FormRequest
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
           'name'  => [
                'required',
                'min:3',
                'max:50',
                new AlphaSpacesRule,
                new RemoveMultiSpace
            ],
        ];
    }

    /**
     * Method messages
     *
     * @return array
     */
    public function messages()
    {
        $required = 'validation.required';
        return [
            'name.required' => trans($required, ['attribute' => 'name']),
        ];
    }
}

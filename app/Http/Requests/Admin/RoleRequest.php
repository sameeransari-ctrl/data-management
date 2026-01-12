<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Rules\AlphaSpacesRule;
use App\Rules\RemoveMultiSpace;

class RoleRequest extends FormRequest
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
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        $rules =  [
            'permissions' => 'required|array|min:1',
         ];
        if (empty($this->role->id)) {
            $rules['name'] = [
                'required', 'min:2', 'max:50', 'unique:roles,name', new AlphaSpacesRule, new RemoveMultiSpace
            ];
        } else {
            $rules['name'] = [
                'required', 'min:2', 'max:50',
                new AlphaSpacesRule, new RemoveMultiSpace, Rule::unique('roles')->ignore($this->role->id)
            ];
        }
        return $rules;
    }

    /**
     * Method messages
     *
     * @return void
     */
    public function messages()
    {
        return [
            'name.required' => trans('validation.required', ['attribute' => 'role']),
            'name.min' => trans('validation.min', ['attribute' => 'role']),
            'name.max' => trans('validation.max', ['attribute' => 'role']),
            'name.unique' => trans('validation.unique', ['attribute' => 'role']),
        ];
    }
}

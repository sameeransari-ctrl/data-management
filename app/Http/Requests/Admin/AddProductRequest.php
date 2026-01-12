<?php

namespace App\Http\Requests\Admin;

use App\Rules\{AlphaSpacesRule, WithoutSpacesRule, ValidateImageUrlRule};
use Illuminate\Foundation\Http\FormRequest;

class AddProductRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
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
        $counts = $this->file_url ? count($this->file_url) : 0;
        $rules = [
            'image_url' => [
                'required',
                'url',
                new ValidateImageUrlRule('image'),
            ],
            'product_name' => [
                'required',
                'min:2', 'max:50',
            ],
            'product_description' => ['required'],
            'basic_udid_id' => ['required'],
            'udi_number' => [
                'required',
                'min:2', 'max:50',
                'unique:products,udi_number,NULL,id,deleted_at,NULL',
                new WithoutSpacesRule,
            ],
            'client_id' => ['required'],
            'client_name' => [
                "required_if:client_id,==,other",
                //'min:2', 'max:50',
            ],
            'class_id' => ['required'],
            'file_url' => ['array'],
            'file_url.*' => [
                'url',
                'nullable',
                new ValidateImageUrlRule('file'),
            ],
        ];

        if ($counts > 1) {
            $rules['file_url.*'] = [
                'required',
                'url',
                new ValidateImageUrlRule('file'),
            ];
        }

        return $rules;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function messages()
    {
        return [
            'image_url.required' => trans('validation.required', ['attribute' => 'image url']),
            'product_name.required' => trans('validation.required', ['attribute' => 'product name']),
            'basic_udid_id.required' => trans('validation.required', ['attribute' => 'basic udid number']),
            'udi_number.required' => trans('validation.required', ['attribute' => 'udi number']),
            'client_id.required' => trans('validation.required', ['attribute' => 'client name']),
            'client_name.required_if' => trans('validation.required', ['attribute' => 'other client name']),
            'class_id.required' => trans('validation.required', ['attribute' => 'class']),
            'file_url.*.required' => trans('validation.file_url_valid'),
            'file_url.*.url' => trans('validation.file_url_valid'),
            'file_url.*' => trans('validation.file_url_not_allowed'),
        ];
    }
}

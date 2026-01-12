<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use App\Rules\CheckDescription;

/**
 * CmsRequest
 */
class CmsRequest extends FormRequest
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
            'page_title' => [
                'required',
                'min:5',
                'max:100'
            ],
            'meta_title' => [
                'max:100'
            ],
            'meta_keywords' => [
                'max:100'
            ],
            'meta_description' => [
            ],
            'page_content' => [
                'required',
                new CheckDescription('page_content')
            ]
        ];
    }

    /**
     * Method messages
     *
     * @return array
     */
    public function messages()
    {
        return [
            'page_title.required' => __(
                'validation.required',
                ['attribute' => 'page title']
            ),
            'meta_title.required' => __(
                'validation.required',
                ['attribute' => 'meta title']
            ),
            'meta_keywords.required' => __(
                'validation.required',
                ['attribute' => 'meta keywords']
            ),
            'meta_description.required' => __(
                'validation.required',
                ['attribute' => 'meta description']
            ),
            'page_content.required' => __(
                'validation.required',
                ['attribute' => 'description']
            ),
        ];
    }
}

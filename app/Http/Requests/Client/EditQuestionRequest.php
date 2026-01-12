<?php

namespace App\Http\Requests\Client;

use Illuminate\Foundation\Http\FormRequest;

class EditQuestionRequest extends FormRequest
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
     * @return array
     */
    public function rules(): array
    {
        return [
            'question_title' => [
                'required', 'max:250',
            ],
            'answer_type' => [
                'required', 'max:250',
            ],
            'answer_options_check_box.*' => [
                "required_if:answer_type,==,check_box",
                'max:250',
            ],
            'answer_options_radio_button.*' => [
                "required_if:answer_type,==,radio_button",
                'max:250',
            ],
            'answer_options_check_box.*' => [
                "required_if:answer_type,==,check_box",
                'max:20',
            ],
            'answer_options_radio_button.*' => [
                "required_if:answer_type,==,radio_button",
                'max:15',
            ],
        ];
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function messages()
    {
        return [
            'question_title.required' => trans('validation.required', ['attribute' => 'question title']),
            'answer_type.required' => trans('validation.required', ['attribute' => 'answer type']),
            'answer_options_check_box.*.required_if' => trans('validation.required_if', ['attribute' => 'input value', 'other' => 'answer type', 'value' => 'check box']),
            'answer_options_radio_button.*.required_if' => trans('validation.required_if', ['attribute' => 'input value', 'other' => 'answer type', 'value' => 'radio button']),
            'answer_options_check_box.*.max' => trans('validation.max', ['attribute' => 'input value', 'other' => 'answer type', 'value' => 'check box']),
            'answer_options_radio_button.*.max' => trans('validation.max', ['attribute' => 'input value', 'other' => 'answer type', 'value' => 'radio button']),
        ];
    }

}

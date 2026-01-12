<?php

namespace App\Http\Requests\Client;

use App\Rules\AlphaSpacesRule;
use App\Rules\RemoveMultiSpace;
use App\Rules\RemoveSpace;
use Illuminate\Foundation\Http\FormRequest;

class FsnRequest extends FormRequest
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
        $rules = [
            'title' => ['required','min:2', 'max:50', new AlphaSpacesRule, new RemoveMultiSpace],
            'product_id' => 'required',
            'description' => ['required', new RemoveSpace],
            'attachType' => 'required',
            'xlsx_file' => ['required_if:attachType,xlsx', 'mimetypes:text/csv,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', 'max:10240'],
            'video_file' => ['required_if:attachType,video', 'mimetypes:video/x-flv,video/mp4,video/3gpp,video/x-msvideo,video/x-ms-wmv,video/avi,video/mpeg,video/quicktime', 'max:10240'],
        ];

        if ($this->attachType == 'url') {
            $rules["url"] = ['required_if:attachType,url','url'];
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
        $required = 'validation.required';
        return [
            'title.required' => trans($required, ['attribute' => 'title']),
            'product_id.required' => trans($required, ['attribute' => 'udi number']),
            'attachType.required' => trans($required, ['attribute' => 'attach type']),
            'xlsx_file.required_if' => trans($required, ['attribute' => 'file']),
            'xlsx_file.mimetypes' => trans('validation.mimetypes', ['attribute' => 'file']),
            'xlsx_file.max' => trans('validation.maximum_file_size'),
            'video_file.required_if' => trans($required, ['attribute' => 'file']),
            'video_file.mimetypes' => trans('validation.mimetypes', ['attribute' => 'file']),
            'video_file.max' => trans('validation.maximum_file_size'),
            'url.required_if' => trans($required, ['attribute' => 'url']),
        ];
    }
}

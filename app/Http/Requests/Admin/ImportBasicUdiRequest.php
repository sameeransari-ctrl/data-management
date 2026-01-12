<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class ImportBasicUdiRequest extends FormRequest
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
            'basic_udi' => [
                'required',
                'mimetypes:text/csv,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                'max:5000'
            ],
        ];
    }
}

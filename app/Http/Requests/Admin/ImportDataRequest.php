<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class ImportDataRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
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
            'datas' => [
                'required',
                'mimetypes:text/csv,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                'max:2000'
            ],
        ];
    }
}

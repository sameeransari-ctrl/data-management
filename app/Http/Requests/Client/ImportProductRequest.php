<?php

namespace App\Http\Requests\Client;

use Illuminate\Foundation\Http\FormRequest;

class ImportProductRequest extends FormRequest
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
            'product_file' => [
                'required',
                'mimetypes:text/csv,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                'max:5000'
            ],
        ];
    }
}

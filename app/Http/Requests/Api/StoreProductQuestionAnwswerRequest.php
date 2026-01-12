<?php

namespace App\Http\Requests\Api;

use Illuminate\Validation\Rule;

class StoreProductQuestionAnwswerRequest extends ApiRequest
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
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'product_id' => 'required',
            'user_id' => 'required',
            'answer' => 'nullable|max:255'
        ];
    }
}

<?php

namespace App\Http\Requests\Api;

use Illuminate\Validation\Rule;

class StoreProductRatingRequest extends ApiRequest
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
            'rating' => 'bail|integer|between:1,5',
        ];
    }
}

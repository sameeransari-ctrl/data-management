<?php

namespace App\Http\Resources;

use App\Models\Product;
use App\Models\ProductQuestion;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductAnwerResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param Request $request 
     * 
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return  [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'product_id' => $this->product_id,
            'product_question_id ' => $this->product_question_id ,
            'question_type' => $this->question_type,
            'question_title' => $this->question_title,
            'answer_type' => $this->answer_type,
            'answer_options' => $this->answer_options,
            'answer' => $this->answer,
        ];
    }
}

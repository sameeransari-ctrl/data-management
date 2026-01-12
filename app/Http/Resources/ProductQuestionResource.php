<?php

namespace App\Http\Resources;

use App\Models\Product;
use App\Models\ProductAnswer;
use App\Models\ProductQuestion;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductQuestionResource extends JsonResource
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
        $answerTypesArray = [
            'review' => ProductAnwerResource::collection($this->product->productAnswers->where('question_type', 1)->where('user_id', auth()->user()->id)->where('product_id', $this->product_id)->where('product_question_id', $this->id)),
            'product' => ProductAnwerResource::collection($this->product->productAnswers->where('question_type', 2)->where('user_id', auth()->user()->id)->where('product_id', $this->product_id)->where('product_question_id', $this->id)),
        ];
        $questionType = ProductQuestion::$productQuestionTypes[$this->question_type];
        return  [
            'id' => $this->id,
            'product_id' => $this->product_id,
            'question_type' => $questionType,
            'question_title' => $this->question_title,
            'answer_type' => $this->answer_type,
            'answer_options' => json_decode($this->answer_options, true),
            'default_answer' => $this->default_answer,
            $this->mergeWhen(
                $questionType == 'product', [
                    'answers' => $answerTypesArray['product']
                ]
            ),
            $this->mergeWhen(
                $questionType == 'review', [
                    'answers' => $answerTypesArray['review']
                ]
            ),
        ];
    }
}

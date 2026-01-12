<?php

namespace App\Http\Resources;

use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Resources\ProductAnwerResource;
use App\Http\Resources\ProductQuestionResource;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductDetailResource extends JsonResource
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
        $userId = auth()->user()->id;
        $scannedProduct = $this->productScanner->where('user_id', $userId)->where('product_id', $this->id)->first();
        $isProductAnswer = $this->productAnswers->where('question_type', 2)
            ->where('user_id', $userId)
            ->where('product_id', $this->id)
            ->count();
        
        $isRatingAnswer = $this->productAnswers->where('question_type', 1)
            ->where('user_id', $userId)
            ->where('product_id', $this->id)
            ->count();
        
        return  [
            'id' => $this->id,
            'product_name' => $this->product_name,
            'product_description' => $this->product_description,
            'udi_number' => $this->udi_number,
            'basic_udi_id' => $this->basicUdid->name ?? '',
            'product_image' => $this->image_url,
            'client' => $this->when(
                $this->client,
                function () {
                    return [
                        'name' => $this->client->name,
                        'location' => $this->client->address,
                        'srn' => $this->client->actor_id,
                        'email' => $this->client->email,
                        'role' => $this->client->clientRole->name,
                        'country_name' => $this->client->country->name,
                        'country_flag' => $this->client->country->flag_image_url
                    ];
                }
            ),
            'class_name' => !empty($this->productClass) ? $this->productClass->name : "",
            'verification_by' => Product::$productVerificationTypes[$this->verification_by],
            'total_scan_count' => 0,
            'image_url' => $this->product_image_url,
            'product_ratings' => $this->productRatingReview->avg('rating'),
            'product_answers' => ProductAnwerResource::collection($this->productAnswers->where('user_id', $userId)),
            'product_rating_reviews' => ProductRatingReviewResource::collection($this->productRatingReview->where('review_by', $userId)),
            'scanned_at' => ($scannedProduct->count() > 0) ? $scannedProduct->created_at : "",
            'is_product_answer' => ($isProductAnswer > 0) ? true : false,
            'is_rating_answer' => ($isRatingAnswer > 0) ? true : false
        ];
    }
}

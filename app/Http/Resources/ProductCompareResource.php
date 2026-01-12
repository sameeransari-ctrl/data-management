<?php

namespace App\Http\Resources;

use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Resources\ProductAnwerResource;
use App\Http\Resources\ProductQuestionResource;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductCompareResource extends JsonResource
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
        return  [
            'id' => $this->id,
            'product_name' => $this->product_name,
            'product_description' => $this->product_description,
            'udi_number' => $this->udi_number,
            'basic_udi_number' => $this->basicUdid->name ?? '',
            'product_image' => $this->image_url,
            //'client' => is_null($this->client_id) ? $this->client_name : $this->client,
            'client' => $this->when(
                $this->client,
                function () {
                    return [
                        'name' => $this->client->name,
                        'location' => $this->client->address,
                        'srn' => $this->client->actor_id,
                        'email' => $this->client->email,
                        'role' => $this->client->clientRole->name,
                        'country_flag' => $this->client->country->flag_image_url
                    ];
                }
            ),
            'class_name' => !empty($this->productClass) ? $this->productClass->name : "",
            'verification_by' => Product::$productVerificationTypes[$this->verification_by],
            'total_scan_count' => $this->productScanner->count(),
            'product_ratings' => $this->productRatingReview->avg('rating'),
            'country' => 'India',
            'actor_id' => '4223123'
        ];
    }
}

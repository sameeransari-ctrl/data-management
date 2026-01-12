<?php

namespace App\Http\Resources;

use App\Models\Product;
use App\Models\ProductQuestion;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductScannerResource extends JsonResource
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
            'scanned_at' => changeDateToFormat($this->created_at, "d-m-Y H:i"),
            'scanned_date' => changeDateToFormat($this->created_at, "d-m-Y"),
            'scanned_time' => changeDateToFormat($this->created_at, "H:i:s"),
        ];
    }
}

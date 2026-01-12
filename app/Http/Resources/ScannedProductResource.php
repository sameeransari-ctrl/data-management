<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use App\Http\Resources\ProductResource;
use Illuminate\Http\Resources\Json\JsonResource;

class ScannedProductResource extends JsonResource
{
    /**
     * Method toArray
     *
     * @param $request $request [explicite description]
     *
     * @return void
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'product_id' => $this->product_id,
            'created_at' => getConvertedDate($this->created_at, ''),
            'countryName' => !empty($this->country) ? $this->country->name : '-',
            'cityName' => !empty($this->city) ? $this->city : '-',
            'city' => ucwords($this->city),
            'country' => $this->when(
                $this->country,
                [
                    'id' => $this->country?->id,
                    'name' => ucwords($this->country?->name),
                ]
            ),
            'product' => $this->when(
                $this->product,
                [
                    'id' => $this->product->id,
                    'product_name' => ucwords($this->product->product_name),
                    'udi_number' => $this->product->udi_number,
                    'image_url' => $this->product->image_url,
                    'created_at' => getConvertedDate($this->product->created_at, ''),
                ]
            ),
        ];
    }
}

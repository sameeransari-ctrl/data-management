<?php
namespace App\Http\Resources;

use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Resources\ProductScannerResource;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
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
        return  [
            'id' => $this->id,
            'product_image' => $this->image_url,
            'udi_number' => $this->udi_number,
            'product_name' => ucwords($this->product_name),
            'product_description' => $this->product_description,
            'client_name' => ucwords($this->client->name) ?? $this->client_name,
            'verification_by' => Product::$productVerificationTypes[$this->verification_by],
            'total_scan_count' => count($this->productScanner),
            'class_name' => !empty($this->productClass) ? $this->productClass->name : "",
            'image_url' => $this->product_image_url,
            'product_ratings' => $this->productRatingReview->avg('rating'),
            'scanned_at' => $this->scanned_at,
        ];
    }
}

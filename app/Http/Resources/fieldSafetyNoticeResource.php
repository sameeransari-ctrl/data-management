<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class fieldSafetyNoticeResource extends JsonResource
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
            'title' => ucfirst($this->title),
            'notice_description' => $this->notice_description,
            'attachment_type' => $this->attachment_type,
            'upload_file' => $this->upload_file,
            'upload_file_url' => $this->upload_file_url,
            'thumbnail' => $this->thumbnail_url,
            'status' => $this->status,
            'created_at' => $this->created_at,
            'udi_number' => !empty($this->product) ? $this->product->udi_number : '-',
            'product_name' => !empty($this->product) ? ucwords($this->product->product_name) : '-',
            'product_id' => !empty($this->product) ? $this->product->id : '-',
            'client_name' => !empty($this->user) ? ucwords($this->user->name) : '-',
        ];
    }
}

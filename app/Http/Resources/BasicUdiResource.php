<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BasicUdiResource extends JsonResource
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
            'name' =>  $this->name,
            'client_name' => ucwords($this->client?->name) ?? $this->client_name,
            'actor_id' => $this->client?->actor_id ?? $this->actor_id,
        ];
    }
}

<?php

namespace App\Http\Resources\Collections;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class NotificationResource extends JsonResource
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
        $notificationData = json_decode($this->data);
        return [
            'id' => $this->id,
            'url' => $notificationData?->url ?? '',
            'redirectTo' => notificationRoute($notificationData->type, $notificationData->id),
            'title' => $notificationData?->title ?? '',
            'message' => $notificationData?->message ?? '',
            'is_read' => $this->read_at == null ? 0 : 1,
            'created_at' => $this->created_at,
        ];
    }
}

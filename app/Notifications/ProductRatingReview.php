<?php

namespace App\Notifications;

use App\Models\Product;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ProductRatingReview extends Notification
{
    use Queueable;

    protected $product;

    /**
     * Method __construct
     *
     * @param Product $product [explicite description]
     *
     * @return void
     */
    public function __construct(Product $product)
    {
        $this->product = $product;
    }

    /**
     * Method via
     *
     * @param $notifiable $notifiable [explicite description]
     *
     * @return void
     */
    public function via($notifiable)
    {
        return ['database'];
    }

    /**
     * Method toArray
     *
     * @param $notifiable $notifiable [explicite description]
     *
     * @return void
     */
    public function toArray($notifiable)
    {
        return [
            'type' => 'productRating',
            'id' => $this->product['id'],
            'title' => trans('message.notification.ratingReview.title', ['productName' => $this->product['product_name'], 'udiNumber' => $this->product['udi_number']]),
            'message' => trans('message.notification.ratingReview.message', ['productName' => $this->product['product_name'], 'udiNumber' => $this->product['udi_number']]),
        ];
    }
}

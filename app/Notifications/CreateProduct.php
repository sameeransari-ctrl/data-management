<?php

namespace App\Notifications;

use App\Models\Product;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class CreateProduct extends Notification
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
            'type' => 'createProduct',
            'id' => $this->product['id'],
            'title' => trans('message.notification.productCreate.title'),
            'message' => trans('message.notification.productCreate.message', ['addedBy' => auth()->user()->name]),
        ];
    }
}

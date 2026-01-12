<?php

namespace App\Notifications;

use App\Models\Product;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class CreateFsn extends Notification
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
            'type' => 'createFsn',
            'id' => '',
            'title' => trans('message.notification.fsnCreate.title', ['productName' => ucwords($this->product['product_name'].'/'.$this->product['udi_number'])]),
            'message' => trans('message.notification.fsnCreate.message', ['productName' => ucwords($this->product['product_name'].'/'.$this->product['udi_number'])]),
        ];
    }
}

<?php

namespace App\Notifications;

use App\Models\Product;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ScanProduct extends Notification
{
    use Queueable;

    protected $product;
    protected $user;

    /**
     * Method __construct
     *
     * @param Product $product [explicite description]
     * @param User    $user    [explicite description]
     *
     * @return void
     */
    public function __construct(Product $product, User $user)
    {
        $this->product = $product;
        $this->user = $user;
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
            'type' => 'scanProduct',
            'id' => $this->product['id'],
            'title' => trans('message.notification.productScanned.title'),
            'message' => trans('message.notification.productScanned.message', ['productName' => $this->product['product_name'], 'udiNumber' => $this->product['udi_number'], 'userName' => $this->user['name']]),
        ];
    }
}

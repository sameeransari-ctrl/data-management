<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ProductImportActorIdRequired extends Notification
{
    use Queueable;

    protected $notificationMessage;

    /**
     * Method __construct
     *
     * @param $notificationMessage $notificationMessage [explicite description]
     *
     * @return void
     */
    public function __construct($notificationMessage)
    {
        $this->notificationMessage = $notificationMessage;
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
            'type' => 'actorIdRequired',
            'id' => '',
            'title' => trans('message.notification.productImportActorIdRequired.title'),
            'message' => trans('message.notification.productImportActorIdRequired.message', ['notificationActorIdMessage' => $this->notificationMessage]),
        ];
    }
}

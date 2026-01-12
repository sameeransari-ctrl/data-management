<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class CreateBasicUdi extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct()
    {
        //
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
            'type' => 'createBasicUdi',
            'id' => '',
            'title' => trans('message.notification.udiCreate.title'),
            'message' => trans('message.notification.udiCreate.message'),
        ];
    }
}

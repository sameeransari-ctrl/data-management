<?php

namespace App\Notifications;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class CreateClient extends Notification
{
    use Queueable;

    protected $client;

    /**
     * Method __construct
     *
     * @param User $client [explicite description]
     *
     * @return void
     */
    public function __construct(User $client)
    {
        $this->client = $client;
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
            'type' => 'createClient',
            'id' => $this->client['id'],
            'title' => trans('message.notification.clientCreate.title'),
            'message' => trans('message.notification.clientCreate.message'),
        ];
    }
}

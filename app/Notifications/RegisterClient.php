<?php

namespace App\Notifications;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class RegisterClient extends Notification
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
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
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
            'type' => 'registerClient',
            'id' => $this->client['id'],
            'title' => trans('message.notification.clientRegistered.title'),
            'message' => trans('message.notification.clientRegistered.message'),
        ];
    }
}

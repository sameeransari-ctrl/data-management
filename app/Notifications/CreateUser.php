<?php

namespace App\Notifications;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class CreateUser extends Notification
{
    use Queueable;

    protected $user;

    /**
     * Method __construct
     *
     * @param User $user [explicite description]
     *
     * @return void
     */
    public function __construct(User $user)
    {
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
            'type' => 'createUser',
            'id' => $this->user['id'],
            'title' => trans('message.notification.userCreate.title'),
            'message' => trans('message.notification.userCreate.message'),
        ];
    }
}

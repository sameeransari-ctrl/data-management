<?php

namespace App\Notifications;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class WelcomeClient extends Notification
{
    use Queueable;

    protected $client;
    protected $password;

    /**
     * Method __construct
     *
     * @param User   $client   [explicite description]
     * @param string $password [explicite description]
     *
     * @return void
     */
    public function __construct(User $client, string $password='')
    {
        $this->client = $client;
        $this->password = $password;
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
        return ['mail'];
    }

    /**
     * Method toMail
     *
     * @param $notifiable $notifiable [explicite description]
     *
     * @return void
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->line('Welcome to '. getAppName())
            ->line('Your account has been created.')
            ->line(__('labels.name').': '.$this->client->name)
            ->line(__('labels.email').': '.$this->client->email)
            ->line(__('labels.mobile').': '.$this->client->phone_code.' '.$this->client->phone_number)
            ->line(__('labels.role').': '.$this->client->clientRole->name)
            ->line(__('labels.actor_id_srn').': '.$this->client->actor_id)
            ->line(__('labels.address').': '.$this->client->address)
            ->line(__('labels.country').': '.$this->client->country->name)
            ->line(__('labels.city').': '.$this->client->city->name)
            ->line(__('labels.password').': '.$this->password)
            ->line('Thank you for using our application!');
    }

    /**
     * Method toArray
     *
     * @param object $notifiable [explicite description]
     *
     * @return array
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}

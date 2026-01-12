<?php

namespace App\Notifications;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class WelcomeUser extends Notification
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
            ->line(__('labels.name').': '.$this->user->name)
            ->line(__('labels.email_address').': '.$this->user->email)
            ->line(__('labels.mobile').': '.$this->user->phone_code.' '.$this->user->phone_number)
            ->line(__('labels.user_type').': '.$this->user->user_type)
            ->line(__('labels.address').': '.$this->user->address)
            ->line(__('labels.country').': '.$this->user->country->name)
            ->line(__('labels.city').': '.$this->user->city->name)
            ->line(__('labels.zip_code').': '.$this->user->zip_code)
            ->line('Thank you for using our application!');
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
            //
        ];
    }
}

<?php

namespace App\Notifications;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class WelcomeStaff extends Notification
{
    use Queueable;

    protected $staff;

    protected $password;

    /**
     * Method __construct
     *
     * @param User $staff [explicite description]
     *
     * @return void
     */
    public function __construct(User $staff, string $password='')
    {
        $this->staff = $staff;
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
            ->line(__('labels.name').': '.$this->staff->name)
            ->line(__('labels.email_address').': '.$this->staff->email)
            ->line(__('labels.mobile').': '.$this->staff->phone_code.' '.$this->staff->phone_number)
            ->line(__('labels.role').': '.$this->staff->roles[0]['name'])
            ->line(__('labels.password').': '.$this->password)
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

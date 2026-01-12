<?php

namespace App\Notifications;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class OtpVerification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $user;
    protected $type;

    /**
     * Create a new notification instance.
     *
     * @param User $user 
     * @param string $type
     *
     * @return void
     */
    public function __construct(User $user, string $type = '')
    {
        $this->user = $user;
        $this->type = $type;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param mixed $notifiable 
     *
     * @return array
     */
    public function via($notifiable)
    {
        $channels = [];
        $sendVia = (!empty($this->type)) ? $this->type : config('constants.send_otp_by');
        if (in_array($sendVia, ['sms', 'both'])) {
            array_push($channels, 'sms');
        }
        if (in_array($sendVia, ['mail', 'both'])) {
            array_push($channels, 'mail');
        }
        
        return $channels;
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param mixed $notifiable 
     *
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $fromEmail = config('mail.from.address');
        $fromName = getAppName();
        return (new MailMessage)
            ->from($fromEmail, $fromName)
            ->line('Use this code '.$this->user->otp.' to verify your account.')
            ->line('Thank you for using our application!');
    }
    
    /**
     * Method toSms
     *
     * @return void
     */
    public function toSms()
    {
        $fromName = getAppName();
        $receiverNumber = $this->user->phone_code . $this->user->phone_number;
        $message = 'Use this code '.$this->user->otp.' to verify your account.';
        sendOtpByTwillio($receiverNumber, $message);
    }

    /**
     * Get the array representation of the notification.
     *
     * @param mixed $notifiable 
     *
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}

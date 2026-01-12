<?php
namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use App\Messages\FirebaseMessage;

class FirebaseNotification extends Notification
{
    use Queueable;

    protected $title;
    protected $message;
    protected $additionalData;
    protected $fcmTokens;
    
    /**
     * Method __construct
     *
     * @param $data $data [explicite description]
     *
     * @return void
     */
    public function __construct($data)
    {
        $this->title = isset($data['title']) ? $data['title'] : '';
        $this->message = isset($data['message']) ? $data['message'] : '';
        $this->additionalData = isset($data['additionalData']) ? $data['additionalData'] : [];
        $this->fcmTokens = $data['userDevicesAaray'];
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
        return ['firebase'];
    }
    
    /**
     * Method toFirebase
     *
     * @param $notifiable $notifiable [explicite description]
     *
     * @return void
     */
    public function toFirebase($notifiable)
    {
        return (new FirebaseMessage)
            ->withTitle($this->title)
            ->withBody($this->message)
            ->withAdditionalData($this->additionalData)
            ->asNotification($this->fcmTokens); // OR ->asMessage($deviceTokens);
    }
}

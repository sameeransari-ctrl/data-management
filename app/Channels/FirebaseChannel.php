<?php

namespace App\Channels;

use App\Models\User;
use App\Notifications\FirebaseNotification;

class FirebaseChannel
{
    /**
     * Method send
     *
     * @param $notifiable   $notifiable 
     * @param Notification $notification 
     *
     * @return void
     */
    public function send($notifiable, FirebaseNotification $notification)
    {
        $notification->toFirebase($notifiable);
    }
}

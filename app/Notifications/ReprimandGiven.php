<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ReprimandGiven extends Notification
{
    use Queueable;
    public $issuer;

    public function __construct($issuer)
    {
        $this->issuer = $issuer;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toDatabase($notifiable)
    {
        return [
            'link'=>'/pms/home',
            'message'=> 'You have got a reprimand from '.$this->issuer->fname . " " . $this->issuer->lname

        ];
    }
}

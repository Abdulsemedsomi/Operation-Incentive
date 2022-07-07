<?php
namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Auth;
class MentionNotificationwp extends Notification
{
    use Queueable;

    public $model;

    public function __construct($model)
    {
        $this->model = $model;
       
    }

    public function via($notifiable): array
    {
        return ['database'];
    }

    public function toDatabase($notifiable): array
    {
       
        // The instance `$this->model` represent the `Comment` model.
        $username = Auth::user()->fname;
        // $modelId = $this->model->getKey();
        $modelId = $this->model->plan_id;

        $message = "<strong style='color:red;'>@$username </strong> has mentioned your name in a comment";

        // You could (and probably should) use a route name here with the function `route()`.
       
        $link =  "/pms/wcomment/{$modelId}#comment";

        return [
          
            'message' => $message,
            'link' => $link,
            'type' => 'mention'
        ];
    }
}
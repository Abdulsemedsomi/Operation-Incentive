<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Plan;
use App\Comment;
use Illuminate\Support\Facades\Auth;
class NewComment extends Notification
{
    use Queueable;
    public $plan;
    public $type;
    public $commenter;
    public $plan_id;

    public function __construct($plan)
    {
        $this->plan=$plan;
        $this->commenter = Auth::user();
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toDatabase($notifiable)
    {
        if($this->plan->plantype == "weekly"){
            return [
                /* 'commenter' =>$this->commenter->fname . " ". $this->commenter->lname,
                'type' =>$this->plan->plantype, */
                'plan_id' =>$this->plan->id,
                'link'=>'/pms/wcomment/'.$this->plan->id.'#comment',
                'message'=>$this->commenter->fname . " ". $this->commenter->lname . " commented on your ". 
                $this->plan->plantype . " plan" . "."
            ];  
        }
        else{
            $comment = Comment::where('plan_id',$this->plan->id)->first();
            //dd($comment);
            //$comment_body = 
            return [
                /* 'commenter' =>$this->commenter->fname . " ". $this->commenter->lname,
                'type' =>$this->plan->plantype, */
                'plan_id' =>$this->plan->id,
                'link'=>'/pms/comment/'.$this->plan->id.'#comment',
                'message'=>$this->commenter->fname . " ". $this->commenter->lname . " commented on your ". 
                $this->plan->plantype . " plan."
            ];  
        }
        
    }
}

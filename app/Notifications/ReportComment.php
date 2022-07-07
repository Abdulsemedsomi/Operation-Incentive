<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Plan;
use Illuminate\Support\Facades\Auth;
class ReportComment extends Notification
{
    use Queueable;
    public $report;
    public $type;
    public $commenter;
    public $report_id;
    public function __construct($report)
    {
        $this->report=$report;
        $this->commenter = Auth::user();
    }

    public function via($notifiable)
    {
        return ['database'];
    }
    public function toDatabase($notifiable)
    {
        if($this->report->reporttype == "weekly"){
            return [
                /* 'commenter' =>$this->commenter->fname . " ". $this->commenter->lname,
                'type' =>$this->plan->plantype, */
                'report_id' =>$this->report->id,
                'link'=>'/pms/wrcomment/'.$this->report->id.'#comment',
                'message'=>$this->commenter->fname . " ". $this->commenter->lname . " commented on your ". 
                $this->report->reporttype . " report."
            ];  
        }
        else{
            return [
                /* 'commenter' =>$this->commenter->fname . " ". $this->commenter->lname,
                'type' =>$this->plan->plantype, */
                'report_id' =>$this->report->id,
                'link'=>'/pms/drcomment/'.$this->report->id.'#comment',
                'message'=>$this->commenter->fname . " ". $this->commenter->lname . " commented on your ". 
                $this->report->reporttype . " report."
                
            ];  
        }
        
    }
}

<?php

namespace App\Notifications;
use App\Team;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class WeeklyPlanNoti extends Notification
{
    use Queueable;
    public $user;
    public $teamname;
    public $teamid;
    public $team;
    public function __construct($user)
    {
        $this->teamname = $user->team; 
        //dd($this->teamname);
        $this->team = Team::where('team_name',$this->teamname)->first();
        //dd($this->team);
        $this->teamid  = $this->team->id;
        //dd($this->teamid);
        $this->user = $user;
    }
    public function via($notifiable)
    {
        return ['database'];
    }


    public function toDatabase($notifiable)
    {
        return [
            'link'=>'/pms/weeklyplan/'.$this->teamid,
            'message'=>$this->user->fname . " ". $this->user->lname . " Posted On Weekly Plan "
        ];
    }
}

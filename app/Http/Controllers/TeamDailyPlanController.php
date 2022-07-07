<?php

namespace App\Http\Controllers;

use App\Comment;
use App\Dailyplan;
use App\Plan;
use App\Session;
use App\Subtask;
use App\Task;
use App\Team;
use App\Teammember;
use App\User;
use App\Weeklyplan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TeamDailyPlanController extends Controller
{
    //
    public function index($teamid, $sessionid)
    {
        //
        $session = Session::find($sessionid);
        $team = Team::find($teamid);
        $plan = Plan::where('userid', Auth::user()->id)->where('teamid', $teamid)->where('sessionid', $sessionid)->where('plantype', 'weekly')->where('isReported', 0)->orderby('id', 'desc')->first();
      
        if ($plan) {

            $weekplan = Weeklyplan::where('weeklyplans.planid', $plan->id)->where('tasks.status', 0)->join('tasks', 'weeklyplans.task_id', '=', 'tasks.id')
                ->join('keyresults', 'tasks.keyresultid', '=', 'keyresults.id')->select('keyresultid', 'taskname', 'tasks.id', 'parent_task')->get();
            $taskids = []; $tcount = 0;
            $mileids = []; $mcount =0;

            $metric = Weeklyplan::where('weeklyplans.planid', $plan->id)->where('tasks.status', 0)->join('tasks', 'weeklyplans.task_id', '=', 'tasks.id')
                ->join('keyresults', 'tasks.keyresultid', '=', 'keyresults.id')->select('keyresultid', 'keyresult_name')->distinct()->get();

            $subtasks = Subtask::where('status', 0)->get();
            $subs = Weeklyplan::where('weeklyplans.planid', $plan->id)->where('tasks.status', 0)->where('subtasks.status', 0)->where('subtasks.isactive', 1)->join('tasks', 'weeklyplans.task_id', '=', 'tasks.id')
            ->join('keyresults', 'tasks.keyresultid', '=', 'keyresults.id')->join('subtasks', 'tasks.id', '=', 'subtasks.taskid')->select('subtasks.id', 'keyresultid')->get();
                $pid = $plan->id;
        } else {
            $weekplan = [];
            $metric = [];
            $subtasks = [];
            $subs = [];
            $pid=[];
        }
        $currentPath=url()->current();
        $plan = Plan::where('plantype', 'daily')->where('teamid', $teamid)->orderby('plans.created_at', 'desc')->where('sessionid', $session->id)
             ->join('users', 'users.id', '=', 'plans.userid')->select('plans.id', 'plans.updated_at', 'users.fname', 'users.lname','users.avatarcolor', 'plans.userid', 'users.team', 'plans.created_at', 'plans.reportsTo', 'plans.cc')->paginate(20);
       $existplan = Plan::where("userid", Auth::user()->id)->where('plantype', 'daily')->where('teamid', $teamid)->where('sessionid', $session->id)->where('isReported', 0)->orderby('updated_at', 'desc')->first(); //there is an unreported plan
        $existwplan = Plan::where("userid", Auth::user()->id)->where('plantype', 'weekly')->where('teamid', $teamid)->where('sessionid', $session->id)->where('isReported', 0)->orderby('updated_at', 'desc')->first(); // //there is an unreported plan
        $users = Teammember::where('team_id', $teamid)->where('users.active', 1)->join('users', 'users.id', '=', 'teammembers.user_id')->get();

        return view('teamdailyplan', compact(['weekplan', 'metric', 'plan', 'subtasks', 'team', 'existplan', 'subs','existwplan','users','pid', 'session','currentPath' ]));
    }
     public function edit($id)
    {
        //
        //find team by id
     
        $dplan = Plan::find($id);
        $teamid = $dplan->teamid;
        $plan = Plan::orderby('id', 'desc')->where('userid', Auth::user()->id)->where('teamid', $teamid)->where('plantype', 'weekly')->where('id', '<', $id)->first();
        if($dplan->weekplanid !=null){
            $plan = Plan::find($dplan->weekplanid);
        }
          $subtasks = Subtask::all();
            $subs = Weeklyplan::where('weeklyplans.planid', $plan->id)->where('tasks.status', 0)->where('subtasks.status', 0)->where('subtasks.isactive', 1)->join('tasks', 'weeklyplans.task_id', '=', 'tasks.id')
            ->join('keyresults', 'tasks.keyresultid', '=', 'keyresults.id')->join('subtasks', 'tasks.id', '=', 'subtasks.taskid')->select('subtasks.id', 'keyresultid')->get();
        
         $team = Team::find($teamid);
          $users = Teammember::where('team_id', $teamid)->where('users.active', 1)->join('users', 'users.id', '=', 'teammembers.user_id')->get();
        $session = Session::where('status', 'active')->orderby('id', 'desc')->first(); //active session

        //fetch all objectives in the active sesssion of the authenticated user
        
 $metric = Weeklyplan::where('weeklyplans.planid', $plan->id)->join('tasks', 'weeklyplans.task_id', '=', 'tasks.id')
                ->join('keyresults', 'tasks.keyresultid', '=', 'keyresults.id')->select('keyresultid', 'keyresult_name')->distinct()->get();
  $weekplan = Weeklyplan::where('weeklyplans.planid', $plan->id)->join('tasks', 'weeklyplans.task_id', '=', 'tasks.id')
                ->join('keyresults', 'tasks.keyresultid', '=', 'keyresults.id')->select('keyresultid', 'taskname', 'tasks.id', 'parent_task')->get();
        //fetch all keyresutls and tasks (that are not milestones) in the active sesssion of the authenticated user
       
         return view('teditdailyplan', compact([ 'plan' , 'team', 'users', 'metric','weekplan', 'dplan', 'subs','subtasks']));
    }
   public function store(Request $request, $teamid)
    {
        //
        $input = $request->all();
        $session = Session::where('status', 'Active')->first();

        if (!$request->has('taskcheck') && !$request->has('subtaskcheck')) {
            return back()->with('error', 'Error: No task selected!')->withInput($request->input());
        }
        $existplan = Plan::where("userid", Auth::user()->id)->where('plantype', 'daily')->where('teamid', $teamid)->where('sessionid', $session->id)->where('isReported', 0)->orderby('updated_at', 'desc')->first();
        if ($existplan) {
            return back()->with('error', 'Error: You have an unreported plan. Please submit a report for that first!');
        }

        $taskids = [];
        if ($request->has('taskcheck')) {
            $taskids = $input['taskcheck'];
        }
        $subtaskids = [];
        if ($request->has('subtaskcheck')) {
            $subtaskids =  $input['subtaskcheck'];
        }
       
        DB::transaction(function () use ($teamid,$taskids, $subtaskids, $input, $request) {
        $plan = new Plan;
        $plan->plantype = "daily";
        $plan->teamid = $teamid;
        $plan->sessionid = Session::where('status', 'Active')->first()->id;
        $plan->reportsTo = $input['reportsTo'];
        $plan->userid = Auth::user()->id;
        if($request->has('weekplanid')){
            $plan->weekplanid = $input['weekplanid'];
        }
         if($request->has('cc')){
            $cc = $input['cc'];
            $finalv = "";
            foreach($cc as $c){
                if($finalv == ""){
                    $finalv = $c;
                }
                else{
                     $finalv .= "," . $c;
                }
               
            }
            $plan->cc = $finalv;
        }
        $plan->save();
       

        $plan = Plan::where("userid", Auth::user()->id)->where('plantype', 'daily')->where('teamid', $teamid)->where('isReported', 0)->orderby('id', 'desc')->first();

        if (!empty($taskids)) {
            foreach ($taskids as $tid) {
                $task = Task::find($tid);
                $dailyplan = new Dailyplan;


                $dailyplan->planid = $plan->id;
                

                $dailyplan->task_id =  $task->id;
                $dailyplan->save();
            }
        }
        $dayplan = Dailyplan::where('planid', $plan->id)->orderby('id', 'desc')->get();

        if (!empty($subtaskids)) {
            foreach ($subtaskids as $sid) {
                $stask = Subtask::find($sid);
                $dayplan = Dailyplan::where('planid', $plan->id)->where('task_id', $stask->taskid)->whereNull('subtask_id')->first();
                if (!$dayplan) {
                    $dailyplan = new Dailyplan;


                    $dailyplan->planid = $plan->id;
                   
                    $dailyplan->subtask_id =  $sid;
                    $dailyplan->task_id =  $stask->taskid;
                    $dailyplan->save();
                }
            }
        }
    });
    
     //broadcast a notification to all team members of auth user
         //get team id of auth user
        //  if(Auth::user()->id !=170){
         $authuser = Auth::user();
         $team = $authuser->team;
         $members = User::where('team',$team)->get();
         //dd($members);
         
        $manager = Team::where('team_name', Auth::user()->team)->first();
        
        //Get team manager
        $managerid = $manager->manager_id;
        if($managerid != null){
            $muser = User::find($managerid);
            //notify the team manager
            $muser->notify(new \App\Notifications\DailyPlanNoti(Auth::user()));
        }
         
         foreach ($members as $member) {
             if($member->fname.$member->lname != $authuser->fname.$authuser->lname){
                $member->notify(new \App\Notifications\DailyPlanNoti(Auth::user()));
            }
           
        }
         
        return back()->with('success', 'Daily plan Added Successfully.');
    }
    
     public function update(Request $request, $planid)
    {
        //
        $input = $request->all();

        if (!$request->has('taskcheck') && !$request->has('subtaskcheck')) {
            return back()->with('error', 'Error: No task selected!')->withInput($request->input());
        }
      

        $taskids = [];
        if ($request->has('taskcheck')) {
            $taskids = $input['taskcheck'];
        }
        $subtaskids = [];
        if ($request->has('subtaskcheck')) {
            $subtaskids =  $input['subtaskcheck'];
        }
       
        DB::transaction(function () use ($taskids, $subtaskids, $input, $request, $planid) {
        $plan = Plan::find($planid);
        
       
       
        $plan->reportsTo = $input['reportsTo'];
     
        
         if($request->has('cc')){
            $cc = $input['cc'];
            $finalv = "";
            foreach($cc as $c){
                if($finalv == ""){
                    $finalv = $c;
                }
                else{
                     $finalv .= "," . $c;
                }
               
            }
            $plan->cc = $finalv;
        }
        $plan->save();
       

       Dailyplan::where('planid',$planid )->delete();

        if (!empty($taskids)) {
            foreach ($taskids as $tid) {
                $task = Task::find($tid);
                $dailyplan = new Dailyplan;


                $dailyplan->planid = $plan->id;
                

                $dailyplan->task_id =  $task->id;
                $dailyplan->save();
            }
        }
        $dayplan = Dailyplan::where('planid', $plan->id)->orderby('id', 'desc')->get();

        if (!empty($subtaskids)) {
            foreach ($subtaskids as $sid) {
                $stask = Subtask::find($sid);
                $dayplan = Dailyplan::where('planid', $plan->id)->where('task_id', $stask->taskid)->whereNull('subtask_id')->first();
                if (!$dayplan) {
                    $dailyplan = new Dailyplan;


                    $dailyplan->planid = $plan->id;
                   
                    $dailyplan->subtask_id =  $sid;
                    $dailyplan->task_id =  $stask->taskid;
                    $dailyplan->save();
                }
            }
        }
    });
    
     
        return back()->with('success', 'Daily plan Updated Successfully.');
    }
    public function comment($plan_id)
    {
        $plan = Plan::where('plantype', 'daily')->where('plans.id', $plan_id)->orderby('plans.created_at', 'desc')
            ->join('users', 'users.id', '=', 'plans.userid')->select('plans.id', 'plans.updated_at', 'users.fname', 'users.lname', 'plans.userid', 'users.team', 'plans.created_at', 'plans.teamid', 'plans.reportsTo', 'cc')->first();
        $message = "What are your targets for tomorrow?";
        $comments = Comment::where('plan_id', $plan_id)->orderby('comments.created_at', 'asc')
            ->join('users', 'users.id', '=', 'comments.commentor_id')
            ->select('fname', 'lname', 'commentor_id', 'position', 'comment', 'comments.created_at', 'comments.id', 'comments.updated_at')
            ->get();
        $type = "plan";
        
        $currentPath=url()->current().'#comment';
       
        $team = Team::find($plan->teamid);
        return view('dailycomment', compact(['plan', 'message', 'comments', 'type' , 'team','currentPath']));
    }
    
     public function boostcomment(Request $request)
    {
        Comment::create(['plan_id' => $request->input('plan_id'), 'commentor_id' => Auth::user()->id, 'comment' => $request->input('comment'), 'type' => 2]);
        return 1;
    }
}
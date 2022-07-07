<?php

namespace App\Http\Controllers;

use App\Comment;
use App\Team;
use App\Keyresult;
use App\Objective;
use App\Session;
use App\Plan;
use App\Task;
use App\User;
use App\Report;
use App\Teammember;
use App\Weeklyplan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TeamWeeklyplanController extends Controller
{
    //
    public function index($teamid, $sessionid)
    {
        //find team by id
        $team = Team::find($teamid);

        $session = Session::find($sessionid); //active session
        $objectives = [];
        //fetch all objectives in the active sesssion of the authenticated user
        if( $session){
        $objectives = Objective::where('session_id', $session->id)->where('user_id', Auth::user()->id)->get();
            
            
            
        }

        $currentPath=url()->current();

        //fetch all keyresutls and tasks (that are not milestones) in the active sesssion of the authenticated user
        $db = Keyresult::where('tasks.isMilestone', 0)->where('tasks.isactive', 1)->where('tasks.status', 0)->join('tasks', 'keyresults.id', '=', 'tasks.keyresultid')->select('tasks.id', 'tasks.taskname', 'tasks.keyresultid', 'tasks.status', 'tasks.parent_task')->get();

        //fetch all keyresutls in the database
        $metric = Keyresult::all();

        $plan = Plan::where('plantype', 'weekly')->where('teamid', $teamid)->orderby('plans.created_at', 'desc')->where('sessionid', $session->id)
            ->join('users', 'users.id', '=', 'plans.userid')->select('plans.id', 'plans.updated_at', 'users.fname','users.lname','users.avatarcolor', 'plans.userid', 'users.team', 'plans.created_at', 'plans.reportsTo', 'plans.cc')->paginate(5);
        $existplan = Plan::where("userid", Auth::user()->id)->where('plantype', 'weekly')->where('sessionid', $session->id)->where('teamid', $teamid)->where('isReported', 0)->orderby('id', 'desc')->first();
        $users = Teammember::where('team_id', $teamid)->join('users', 'users.id', '=', 'teammembers.user_id')->get();

        return view('teamweeklyplan', compact(['db', 'plan' , 'team', 'objectives', 'existplan', 'users','currentPath', 'session']));
      
    }
     public function edit($teamid, $sessionid, $id)
    {
        //
        //find team by id
     
        $plan = Plan::find($id);
       
        $team = Team::find($teamid);
        $users = Teammember::where('team_id', $teamid)->join('users', 'users.id', '=', 'teammembers.user_id')->get();
        $session = Session::find($sessionid); //active session

        //fetch all objectives in the active sesssion of the authenticated user
        $objectives = Objective::where('session_id', $plan->sessionid)->where('user_id', $plan->userid)->get();

        //fetch all keyresutls and tasks (that are not milestones) in the active sesssion of the authenticated user
        $db = Keyresult::join('tasks', 'keyresults.id', '=', 'tasks.keyresultid')->select('tasks.id', 'tasks.taskname', 'tasks.keyresultid', 'tasks.status', 'tasks.parent_task', 'tasks.isMilestone', 'tasks.isactive')->get();

        //fetch all keyresutls in the database
        $metric = Keyresult::all();
        
         return view('editteamweeklyplan', compact(['db', 'plan' , 'team', 'objectives', 'users', 'session']));
    }
 public function show($id)
    {
        return Plan::find($id);
    }
    public function store(Request $request, $teamid, $sessionid)
    {
         $input = $request->all();
   
        /* Input Validation */

        //if no task is selected
        if(!$request->has('taskcheck')){
            return back()->with('error', 'Error: No task selected!')->withInput($request->input());
        }

        //check percent calculation
        $taskids = $input['taskcheck'];
        $krids =[];
        $task_percent = 0;
        $task_percents = [];
        $kr_percent =0;
        $kr_percents = [];
        $i = 0;
        foreach($taskids as $taskid){
            $task = Task::find($taskid);
            $krids[$i] = $task->keyresultid;


            //if no percent value is given
            if($request->has('options'.$task->keyresultid) && $input['options'.$task->keyresultid] == null || $request->has('taskoptions'.$task->id) && $input['taskoptions'.$task->id] == null){
                return back()->with('error', 'Error: Percent value of a selected result cannot be empty!')->withInput($request->input());
            }
            if($request->has('options'.$task->keyresultid) && $input['options'.$task->keyresultid] == 0 || $request->has('taskoptions'.$task->id) && $input['taskoptions'.$task->id] == 0){
                return back()->with('error', 'Error: Percent value of a selected result cannot be zero!')->withInput($request->input());
            }
            $task_percent += $input['taskoptions'.$task->id];
            $task_percents[$i] = $task_percent;

            $i++;
        }

        if($task_percent != 100 ){
            return back()->with('error', 'Error: Wrong Percent Calculation!')->withInput($request->input());
        }
        $i = 0;
        $krids = array_unique($krids);


        foreach($krids as $kr ){
            $kr_percent += $input['options'.$kr];
            $task_percent =0;
            foreach($taskids as $taskid){
                $task = Task::find($taskid);
                if($task->keyresultid == $kr){
                    $task_percent += $input['taskoptions'.$task->id];
                }
            }

            if($task_percent != $input['options'.$kr] ){
                return back()->with('error', 'Error: Wrong Percent Calculation!')->withInput($request->input());
            }

        }


        if($kr_percent != 100 ){
            return back()->with('error', 'Error: Wrong Percent Calculation!')->withInput($request->input());
        }

        $existplan = Plan::where("userid", Auth::user()->id)->where('plantype', 'weekly')->where('teamid', $teamid)->where('sessionid', Session::where('status', 'Active')->first()->id)->where('isReported',0)->orderby('updated_at', 'desc')->first();
        if($existplan){
            return back()->with('error', 'Error: You have an unreported plan. Please submit a report for that first!');

        }

        /* Add plan */
        DB::transaction(function () use ($teamid,$taskids,$input, $request, $sessionid) {
        $plan = new Plan;
        $plan->plantype = "weekly";
        $plan->teamid = $teamid;
        $plan->sessionid = $sessionid;
        $plan->userid = Auth::user()->id;
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
        

        

        $plan = Plan::where("userid", Auth::user()->id)->where('plantype', 'weekly')->where('teamid', $teamid)->where('sessionid', $sessionid)->orderby('updated_at', 'desc')->where('sessionid', Session::where('status', 'Active')->first()->id)->first();

         foreach($taskids as $taskid){
             $task = Task::find($taskid);
             $weekplan = new Weeklyplan;
             $weekplan->planid =$plan->id;
            

            $weekplan->keyresult_id =  $task->keyresultid;
            $weekplan->task_id =  $task->id;
             $weekplan->keyresult_percent = $input['options'.$task->keyresultid];
             $weekplan->task_percent = $input['taskoptions'.$task->id];

            $weekplan->save();
         }

    //broadcast a notification to all team members of auth user
         //get team id of auth user
         if(Auth::user()->id !=170){
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
            $muser->notify(new \App\Notifications\WeeklyPlanNoti(Auth::user()));
        }
         
         foreach ($members as $member) {
            //$member->notify(new \App\Notifications\WeeklyPlanNoti(Auth::user()));
            if($member->fname.$member->lname != $authuser->fname.$authuser->lname){
                $member->notify(new \App\Notifications\WeeklyPlanNoti(Auth::user()));
            }
        }
         
}
        });
        return back()->with('success', 'Weekly plan Added Successfully.');

    }
    public function comment($teamid, $sessionid, $plan_id){
     
      
        
         $plan = Plan::where('plantype', 'weekly')->where('plans.id', $plan_id)->orderby('plans.created_at', 'desc')
        ->join('users', 'users.id', '=', 'plans.userid')->select('plans.id', 'plans.updated_at', 'users.fname','users.lname', 'plans.userid', 'users.team', 'plans.created_at','plans.teamid', 'plans.reportsTo', 'plans.sessionid', 'plans.cc')->first();
        $message = "What are your goals for the next week?";
        $comments = Comment::where('plan_id', $plan_id)->orderby('comments.created_at', 'asc')
        ->join('users', 'users.id', '=', 'comments.commentor_id')
        ->select('fname','lname','commentor_id','position','comment','comments.created_at', 'comments.id', 'comments.updated_at')
        ->get();
        $type= "plan";
           $session = Session::find($plan->sessionid);
        $currentPath=url()->current().'#comment';
        
        $team = Team::find($plan->teamid);
         return view('teamweeklycomment', compact(['plan','message','comments', 'type' , 'team', 'session', 'currentPath']));
    }
    
    public function update(Request $request, $planid)
    {
        //
        //
        $input = $request->all();

        /* Input Validation */

        //if no task is selected
        if(!$request->has('taskcheck')){
            return back()->with('error', 'Error: No task selected!')->withInput($request->input());
        }

        //check percent calculation
        $taskids = $input['taskcheck'];
        $krids =[];
        $task_percent = 0;
        $task_percents = [];
        $kr_percent =0;
        $kr_percents = [];
        $i = 0;
        foreach($taskids as $taskid){
            $task = Task::find($taskid);
            $krids[$i] = $task->keyresultid;


            //if no percent value is given
            if($request->has('options'.$task->keyresultid) && $input['options'.$task->keyresultid] == null || $request->has('taskoptions'.$task->id) && $input['taskoptions'.$task->id] == null){
                return back()->with('error', 'Error: Percent value of a selected result cannot be empty!')->withInput($request->input());
            }
            if($request->has('options'.$task->keyresultid) && $input['options'.$task->keyresultid] == 0 || $request->has('taskoptions'.$task->id) && $input['taskoptions'.$task->id] == 0){
                return back()->with('error', 'Error: Percent value of a selected result cannot be zero!')->withInput($request->input());
            }
            $task_percent += $input['taskoptions'.$task->id];
            $task_percents[$i] = $task_percent;

            $i++;
        }

        if($task_percent != 100 ){
            return back()->with('error', 'Error: Wrong Percent Calculation!')->withInput($request->input());
        }
        $i = 0;
        $krids = array_unique($krids);


        foreach($krids as $kr ){
            $kr_percent += $input['options'.$kr];
            $task_percent =0;
            foreach($taskids as $taskid){
                $task = Task::find($taskid);
                if($task->keyresultid == $kr){
                    $task_percent += $input['taskoptions'.$task->id];
                }
            }

            if($task_percent != $input['options'.$kr] ){
                return back()->with('error', 'Error: Wrong Percent Calculation!')->withInput($request->input());
            }

        }


        if($kr_percent != 100 ){
            return back()->with('error', 'Error: Wrong Percent Calculation!')->withInput($request->input());
        }

        

        /* Add plan */
         $plan = Plan::find($planid);
        
        DB::transaction(function () use ($planid,$taskids,$input,$plan, $request) {
       
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

       Weeklyplan::where('planid',$planid )->delete();

         foreach($taskids as $taskid){
             $task = Task::find($taskid);
             $weekplan = new Weeklyplan;
             $weekplan->planid =$plan->id;
         

            $weekplan->keyresult_id =  $task->keyresultid;
            $weekplan->task_id =  $task->id;
             $weekplan->keyresult_percent = $input['options'.$task->keyresultid];
             $weekplan->task_percent = $input['taskoptions'.$task->id];

            $weekplan->save();
         }

        });
         $message = "What are your goals for the next week?";
        $comments = Comment::where('plan_id', $planid)->orderby('comments.created_at', 'asc')
        ->join('users', 'users.id', '=', 'comments.commentor_id')
        ->select('fname','lname','commentor_id','position','comment','comments.created_at', 'comments.id', 'comments.updated_at')
        ->get();
        $type= "plan";
        $team = Team::find($plan->teamid);
           $plan = Plan::where('plantype', 'weekly')->where('plans.id', $planid)->orderby('plans.created_at', 'desc')
        ->join('users', 'users.id', '=', 'plans.userid')->select('plans.id', 'plans.updated_at', 'users.fname','users.lname', 'plans.userid', 'users.team', 'plans.created_at','plans.teamid')->first();
    
       // return view('weeklycomment', compact(['plan','message','comments', 'type' , 'team']))->with('success', 'Weekly plan updated Successfully.');
       
        return back()->with('success', 'Weekly Plan updated Successfully.');
    }
    public function boostcomment(Request $request)
    {
        Comment::create(['plan_id' => $request->input('plan_id'), 'commentor_id' => Auth::user()->id, 'comment' => $request->input('comment'), 'type' => 2]);
        
          if($request->has('plan_id')){
                        $plan = Plan::find($input['plan_id']);
                        $user = Plan::find($input['plan_id'])->userid;
                        if(Plan::find($input['plan_id'])->userid != Auth::user()->id){
                            $fs = User::find($user);
                            //notification to the planner
                            $fs->notify(new \App\Notifications\NewComment($plan));
                        }
                        
                        
                    }
        return 1;
    }
     public function destroy($id)
    {
        $plan = Plan::find($id);
         $plan = Plan::destroy($id);
         $report = Report::where('plan_id', $id)->delete();
         
         if($plan->plantype == "weekly") {$dplan = Plan::where('weekplanid', $id)->delete();}
        return $plan;
    }
}

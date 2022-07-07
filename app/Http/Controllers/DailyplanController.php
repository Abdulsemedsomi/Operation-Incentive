<?php

namespace App\Http\Controllers;

use App\Comment;
use App\Dailyplan;
use App\Plan;
use App\Report;
use App\Session;
use App\Subtask;
use App\Task;
use App\Team;
use App\Teammember;
use App\User;
use App\Weeklyplan;
use App\Tempplan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

use App\Engagement;
use App\Score;
use App\Formula;
use App\FillEngagement;
use Barryvdh\DomPDF\Facade as PDF;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;

class DailyplanController extends Controller
{
    //
    public function index(Request $request,$teamid)
    {
        //
        $team = Team::find($teamid);
        $session = Session::where('status', 'Active')->orderby('id', 'desc')->first(); //active session
        $plan = Plan::where('userid', Auth::user()->id)->where('teamid', $teamid)->where('plantype', 'weekly')->where('sessionid', $session->id )->where('isReported', 0)->orderby('id', 'desc')->first();
      
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
        
        $session = Session::where('status', 'Active')->orderby('id', 'desc')->first(); //active session
        $userf= $request->has('user')? $request->input('user'):0;
        $plan = Plan::where('plantype', 'daily')->where('teamid', $teamid)->orderby('plans.created_at', 'desc')->where('sessionid', $session->id)
            ->join('users', 'users.id', '=', 'plans.userid')->select('plans.id', 'plans.updated_at', 'users.fname', 'users.lname','users.avatarcolor', 'plans.userid', 'users.team', 'plans.created_at', 'plans.reportsTo', 'plans.cc')->paginate(10);
        if($userf != 0){
            $plan = Plan::where('plantype', 'daily')->where('teamid', $teamid)->orderby('plans.created_at', 'desc')->where('sessionid', $session->id)->where('plans.userid',$userf)
            ->join('users', 'users.id', '=', 'plans.userid')->select('plans.id', 'plans.updated_at', 'users.fname', 'users.lname','users.avatarcolor', 'plans.userid', 'users.team', 'plans.created_at', 'plans.reportsTo', 'plans.cc')->paginate(10)
            ->appends('user', $userf);
       }
       
        $count = Plan::where('plantype', 'daily')->where('teamid', $teamid)->orderby('plans.created_at', 'desc')->where('sessionid', $session->id)
            ->join('users', 'users.id', '=', 'plans.userid')->select('plans.id', 'plans.updated_at', 'users.fname', 'users.lname','users.avatarcolor', 'plans.userid', 'users.team', 'plans.created_at', 'plans.reportsTo', 'plans.cc')->get()->count();
       
       
        $existplan = Plan::where("userid", Auth::user()->id)->where('plantype', 'daily')->where('teamid', $teamid)->where('sessionid', $session->id)->where('isReported', 0)->orderby('updated_at', 'desc')->first(); //there is an unreported plan
        $existwplan = Plan::where("userid", Auth::user()->id)->where('plantype', 'weekly')->where('teamid', $teamid)->where('sessionid', $session->id)->where('isReported', 0)->orderby('updated_at', 'desc')->first(); // //there is an unreported plan
        $users = Teammember::where('team_id', $teamid)->where('users.active', 1)->join('users', 'users.id', '=', 'teammembers.user_id')->get();

        return view('dailyplan', compact(['weekplan', 'metric', 'plan', 'subtasks', 'team', 'existplan', 'subs','existwplan','users','pid','currentPath', 'userf', 'count' ]));
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
       
         return view('editdailyplan', compact([ 'plan' , 'team', 'users', 'metric','weekplan', 'dplan', 'subs','subtasks']));
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
       $fplan =0;
        DB::transaction(function () use ($teamid,$taskids, $subtaskids, $input, $request, $fplan) {
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
       $fplan = $plan;
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
        
    //      if(Auth::user()->team != 'Executive' && Auth::user()->ismanager != 1){
    //   if(strtotime($fplan->created_at) > strtotime('22:00:59') ||strtotime($fplan->created_at) < strtotime('17:00:00')){
    //          $report = Report::where("user_id", Auth::user()->id)->where('reporttype', 'daily')->orderby('id', 'desc')->first();
    //          if($report){
    //     $v = FillEngagement::where('report_id', $report->id)->where('engagement_id', 5)->where('Reason', 'This is issued because you failed to submit your daily checkin before the deadline')->first();
    //       if(! $v){
    //          $request2 = new Request(["plan_id"=> $report->id,'issuer' => $plan->reportsTo, "objective"=> 5, 'Reason' => 'This is issued because you failed to submit your daily checkin before the deadline', 'Improvement' => 'Please submit your checkins before 10PM', 'Action' => 'Formal reminder', 'Perspective' => 1]);
    //       $input2 = $request2->all();
          
    //       $message = $this->issueenagement($input2, $request2);
    //         $message = "Reprimand issued successfully";
    //       }
    //          }
      
    //          }
    //      }
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
        $c = Comment::create(['plan_id' => $request->input('plan_id'), 'commentor_id' => Auth::user()->id, 'comment' => $request->input('comment'), 'type' => 2]);
        
         $plan = Plan::find($request->input('plan_id'));
         if($plan && $plan->reportsTo == $c->commentor_id){
            $plan->isCommented = 1;
            $plan->save();
         }
      
        return 1;
    }
    //interactive achieve or fail task
    public function temporaryalter(Request $request){
        $input = $request->all();
        if($input['status'] == 0){
            $temp = new Tempplan;
            $temp->planid =  $input['planid'];
            $temp->task_id =  $input['task_id'];
            $temp->subtask_id =  $input['subtask_id'];
            $temp->save();
        }
        else if($input['status'] == 1 && $input['type'] == 1 ){
        Tempplan::where('planid', $input['planid'])->where('task_id', $input['task_id'] )->delete(); 
            
        }
        else if($input['status'] == 1 && $input['type'] == 2 ){
        Tempplan::where('planid', $input['planid'])->where('subtask_id', $input['subtask_id'] )->delete(); 
            
        }
        return Tempplan::where('planid',$input['planid'] )->get()->count();
    }
    
    
      
     public function issueenagement($input , $request){
    
        $issuedto = $request->has('plan_id')? (Report::find($input['plan_id'])->user_id ): $input['user_id'] ;
        $sessionid = $request->has('plan_id')? Plan::find(Report::find($input['plan_id'])->plan_id)->sessionid : Session::where('status', 'Active')->first()->id ;

       
        $notifiable = $issuedto;
        
      DB::transaction(
                function () use ($input, $issuedto, $sessionid, $request) {
try{
        //for excellence
        if ($input['Perspective'] == 0) {
           
                    $engagement = new FillEngagement;
                    $engagement->Reason = $input['Reason'];
                    $engagement->Description = Engagement::find($input['objective'])->Objective;
                    $engagement->CC =$request->has('cc') &&  $input['cc'] !=0? $input['cc']: 86;
                    if($request->has('plan_id')){
                        $engagement->report_id = $input['plan_id'];
                    }
                   
                    
                    $engagement->issuer = $input['issuer'];
                     $engagement->session_id = $sessionid;
                    $engagement->issued_to = $issuedto;
                    $engagement->engagement_id = $input['objective'];
                   
                    $engagement->save();
        }
        
          
        //discipline
        elseif ($input['Perspective'] == 1) {
           
            $engagement = new FillEngagement;
            $engagement->Reason = $input['Reason'];
            $engagement->Improvement = $input['Improvement'];
            $engagement->Action = $input['Action'];
            $engagement->Description = Engagement::find($input['objective'])->Objective;
            $engagement->CC = $request->has('cc') &&  $input['cc'] !=0? $input['cc']: 86;
            if($request->has('plan_id')){
                        $engagement->report_id = $input['plan_id'];
                    }
                  
            $engagement->session_id = $sessionid;
            $engagement->issuer = $input['issuer'];
            $engagement->issued_to = $issuedto;
            $engagement->engagement_id = $input['objective'];
            $engagement->save();


           
      
         
        }
             $engagements = Engagement::all();
        $userScore = 0;
        foreach($engagements as $engagement){
            
            $fillengagements = FillEngagement::where('engagement_id',$engagement->id)->where('session_id',$sessionid)->where('issued_to', $issuedto)->get();
          
                $formula  = Formula::find($engagement->formula_id); //formula for the engagement objective
                $target = $engagement->Target; //target of the specific engagement criteria
                $weight = $engagement->Weight; //weight of the specific engagement criteria
                $actual = 0;
                   if($fillengagements->count() > 0){ 
                        $actual = $fillengagements->count();
                   }
                $score = $this->executeFormula($formula, $target, $weight, $actual);
             
             $userScore += $score;
        }
     $score = Score::where('user_id',$issuedto )->where('session_id',$sessionid )->first();
        
                    Score::updateOrCreate(
                        ['user_id' => $issuedto, 'session_id' => $sessionid],
                        ['engagementScore' => $userScore, 'appcount' =>  $input['Perspective'] == 0 ? $score->appcount  + 1: $score->appcount,  'repcount' =>  $input['Perspective'] == 1 ? $score->repcount  + 1: $score->repcount]
                    );
                }
         //Send a notifiaction when some get a reprimand 
        catch (\Exception $e) {
        return 0;
            
                }

                }); // end transaction
         

            $engagement = FillEngagement::where('engagement_id',$input['objective'])->where('session_id',$sessionid)->where('issued_to', $issuedto)->orderby('id', 'desc')->first();
        $usert =  $request->has('plan_id')? (Report::find($input['plan_id'])->user_id ): $input['user_id'];
          $issued_to = User::find($usert)->fname . " " . User::find($usert)->lname;
        $issued_by_email = User::find($input['issuer'])->email;
        $issued_by = User::find($input['issuer'])->fname . " " . User::find($input['issuer'])->lname;
        $issued_email = User::find($usert)->email;
        $position = User::find($input['issuer'])->position;

        if(is_null($position)){
            $position= 'Manager';
        }

        $data = array(
            'id' => '',
            'email' =>$issued_email,
            'name' => $issued_to,
            'Sender' => $issued_by,
            'position' => $position,


        );
       
       
 if ($input['Perspective'] == 1) {
         $pdf_name =  $issued_to . " Reprimand Notice ". Carbon::parse($engagement->created_at)->format('M d Y') . ".pdf";
     
        $pdf = PDF::loadView('reptemplate', compact('engagement'));
            
        $carbc = $request->has('cc') &&  $input['cc'] !=0? array($issued_by_email, 'meried@ienetworksolutions.com','Eyerusalem@ienetworks.co','hawi@ienetworks.co','eliyas@ienetworksolutions.com','redate@ienetworksolutions.com','biniyam@ienetworksolutions.com', User::find($input['cc'])->email): array($issued_by_email, 'meried@ienetworksolutions.com','eliyas@ienetworksolutions.com','redate@ienetworksolutions.com', 'hawi@ienetworks.co','Eyerusalem@ienetworks.co','biniyam@ienetworksolutions.com');

         $type = "Reprimand";
           
            Mail::send('emails.warning',["pass_data" => $data], function ($message) use ($pdf, $type,$issued_email,$issued_by_email,$issued_by, $carbc, $pdf_name) {
              $message->from($issued_by_email, $issued_by);

              $message->to($issued_email)->cc($carbc)->subject($type);
              
               
              $message->attachData($pdf->output(), $pdf_name);
            });
            return 1;
 } 
 
  elseif ($input['Perspective'] == 0) {
            
             $customPaper = array(0,0,500.00,670.80);
        $pdf = PDF::loadview('certificate', ['engagement' => $engagement])->setPaper($customPaper, 'landscape');;
         
           
         
           $type = "Appreciation";
         $carbc = $request->has('cc') &&  $input['cc'] !=0? array($issued_by_email, 'meried@ienetworksolutions.com','Eyerusalem@ienetworks.co','eliyas@ienetworksolutions.com','hawi@ienetworks.co','redate@ienetworksolutions.com', 'biniyam@ienetworksolutions.com', User::find($input['cc'])->email): array($issued_by_email, 'meried@ienetworksolutions.com','eliyas@ienetworksolutions.com','redate@ienetworksolutions.com','hawi@ienetworks.co','Eyerusalem@ienetworks.co','biniyam@ienetworksolutions.com');
         
         $pdf_name = $issued_to . " Appreciation Certificate ". Carbon::parse($engagement->created_at)->format('M d Y') . ".pdf";
         Mail::send('emails.Excellence',["pass_data" => $data], function ($message) use ($pdf, $type,$issued_email,$issued_by_email,$issued_by, $carbc, $pdf_name) {
             $message->from($issued_by_email, $issued_by);

          $message->to($issued_email)->cc($carbc)->subject($type);

            $message->attachData($pdf->output(), $pdf_name);
         });
           return 1;
}
       
 



    }

function executeFormula($formula, $target, $weight, $actual){
     //execute formula
        $formulaarray = explode(" ", $formula->formula);
        for ($i = 0; $i < sizeof($formulaarray); $i++) {
            if ($formulaarray[$i] == "actual") {
                $formulaarray[$i] = $actual;
            } elseif ($formulaarray[$i] == "target") {
                $formulaarray[$i] = $target;
            } elseif ($formulaarray[$i] == "weight") {
                $formulaarray[$i] = $weight / 100;
            } else if ($formulaarray[$i] == "x") {
                $formulaarray[$i] = "*";
            } else if ($formulaarray[$i] == "÷") {
                $formulaarray[$i] = "/";
            }
        }
        $finalvalue = "";
        foreach ($formulaarray as $fa) {
            $finalvalue .= $fa;
        }


        $evaluatedValue = 0;
        try {
            $evaluatedValue = eval('return (' . $finalvalue . ');');
            return $evaluatedValue * 100;
        } catch (Exception $ex) {
             return $evaluatedValue * 100;
        }
}
}

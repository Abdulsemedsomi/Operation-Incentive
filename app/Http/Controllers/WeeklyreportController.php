<?php

namespace App\Http\Controllers;

use App\Comment;
use App\Keyresult;
use App\Objective;
use App\FillEngagement;
use App\Plan;
use App\Report;
use App\Score;
use App\Failuretarget;

use App\Session;
use App\Task;
use App\Team;
use App\Teammember;  
use App\Http\Controllers\FillEngagementController;
use App\User;
use App\Weeklyplan;
use App\Failure;
use App\Weeklyreport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
class WeeklyreportController extends Controller
{
    //
    public function index(Request $request,$teamid)
    {
$session = Session::where('status', 'Active')->first();
        $team = Team::find($teamid);
        $plan = Plan::where('userid', Auth::user()->id)->where('plantype', 'weekly')->where('isReported', 0)->where('sessionid', $session->id )->orderby('plans.created_at', 'desc')->first();
        $taskachieved = 0;
        $existplan = 0;
        if ($plan) {
            $objectives = Weeklyplan::where('weeklyplans.planid', $plan->id)->join('tasks', 'weeklyplans.task_id', '=', 'tasks.id')
                ->join('keyresults', 'tasks.keyresultid', '=', 'keyresults.id')->join('objectives', 'keyresults.objective_id', '=', 'objectives.id')->select('objectives.id', 'objective_name')->distinct()->get();

            $weekplan = Weeklyplan::where('weeklyplans.planid', $plan->id)->join('tasks', 'weeklyplans.task_id', '=', 'tasks.id')
                ->join('keyresults', 'tasks.keyresultid', '=', 'keyresults.id')->select('keyresultid', 'taskname', 'tasks.id', 'task_percent', 'tasks.status')->get();

            $metric = Weeklyplan::where('weeklyplans.planid', $plan->id)->join('tasks', 'weeklyplans.task_id', '=', 'tasks.id')
                ->join('keyresults', 'tasks.keyresultid', '=', 'keyresults.id')->select('keyresultid', 'keyresult_name', 'objective_id', 'keyresult_percent')->distinct()->get();

            foreach ($metric as $m) {
                $kp = $m->keyresult_percent;

                foreach ($weekplan as $wp) {
                    if ($wp->keyresultid == $m->keyresultid) {
                        $task = Task::find($wp->id);
                        if ($task->status == 1) {
                            $taskachieved += $wp->task_percent;
                        }
                    }
                }
            }
        } else {
            $weekplan = [];
            $metric = [];
            $objectives = [];
            $existplan = 1;
        }

         $currentPath=url()->current();
         $userf= $request->has('user')? $request->input('user'):0;
        $plan = Report::where('reporttype', 'weekly')->where('team_id', $teamid)->orderby('reports.id', 'desc')->where('session_id', $session->id )->whereNotNull('plan_id')
            ->join('users', 'users.id', '=', 'reports.user_id')->select('reports.id', 'reports.updated_at', 'users.fname', 'users.lname','users.avatarcolor', 'reports.user_id', 'users.team', 'reports.created_at', 'reports.reportsTo', 'reports.cc' )->paginate(5);
         if($userf != 0){
          $plan = Report::where('reporttype', 'weekly')->where('team_id', $teamid)->orderby('reports.id', 'desc')->where('session_id', $session->id )->whereNotNull('plan_id')->where('reports.user_id', $userf)
            ->join('users', 'users.id', '=', 'reports.user_id')->select('reports.id', 'reports.updated_at', 'users.fname', 'users.lname','users.avatarcolor', 'reports.user_id', 'users.team', 'reports.created_at', 'reports.reportsTo', 'reports.cc' )->paginate(5)
            ->appends('user', $userf);
       }
        

  $users = Teammember::where('team_id', $teamid)->where('users.active', 1)->join('users', 'users.id', '=', 'teammembers.user_id')->get();
$failuretargets = Failuretarget::all();

 $count = Report::where('reporttype', 'weekly')->where('team_id', $teamid)->orderby('reports.id', 'desc')->where('session_id', $session->id )->whereNotNull('plan_id')
            ->join('users', 'users.id', '=', 'reports.user_id')->select('reports.id', 'reports.updated_at', 'users.fname', 'users.lname','users.avatarcolor', 'reports.user_id', 'users.team', 'reports.created_at', 'reports.reportsTo', 'reports.cc' )->get()->count();
        return view('weeklyreport', compact(['weekplan', 'metric', 'plan', 'team', 'objectives', 'taskachieved', "existplan", 'users', 'failuretargets','currentPath', 'userf', 'count']));
    }
        public function edit($id)
    {
        //
        //find team by id
     $report = Report::find($id);
     $team = Team::find($report->team_id);
     $taskachieved = 0;
     $objectives = Weeklyplan::where('weeklyplans.planid', $report->plan_id)->join('tasks', 'weeklyplans.task_id', '=', 'tasks.id')
                ->join('keyresults', 'tasks.keyresultid', '=', 'keyresults.id')->join('objectives', 'keyresults.objective_id', '=', 'objectives.id')->select('objectives.id', 'objective_name')->distinct()->get();

            $weekplan = Weeklyplan::where('weeklyplans.planid', $report->plan_id)->join('tasks', 'weeklyplans.task_id', '=', 'tasks.id')
                ->join('keyresults', 'tasks.keyresultid', '=', 'keyresults.id')->select('keyresultid', 'taskname', 'tasks.id', 'task_percent', 'tasks.status')->get();

            $metric = Weeklyplan::where('weeklyplans.planid', $report->plan_id)->join('tasks', 'weeklyplans.task_id', '=', 'tasks.id')
                ->join('keyresults', 'tasks.keyresultid', '=', 'keyresults.id')->select('keyresultid', 'keyresult_name', 'objective_id', 'keyresult_percent')->distinct()->get();

            foreach ($metric as $m) {
                $kp = $m->keyresult_percent;

                foreach ($weekplan as $wp) {
                    if ($wp->keyresultid == $m->keyresultid) {
                        $task = Task::find($wp->id);
                        if ($task->status == 1) {
                            $taskachieved += $wp->task_percent;
                        }
                    }
                }
            }
            $failuretargets = Failuretarget::all();
        $users = Teammember::where('team_id', $team->id)->where('users.active', 1)->join('users', 'users.id', '=', 'teammembers.user_id')->get();
         return view('editweeklyreport', compact(['team', 'users', 'metric','weekplan', 'report',  'objectives', 'taskachieved','failuretargets', 'report']));
    }
    public function store(Request $request, $teamid)
    {
       
        $input = $request->all();
        
      $session = Session::where('status', 'Active')->first();
        $plan = Plan::where("userid", Auth::user()->id)->where('plantype', 'weekly')->where('teamid', $teamid)->where('sessionid', $session->id)->where('isReported', 0)->orderby('id', 'desc')->first();
        $wplan = Weeklyplan::where('planid', $plan->id)->get();
        DB::transaction(function () use ($teamid,$wplan,$input, $plan,$request) {
        $report = new Report;
        $report->reporttype = "weekly";

        $report->user_id = Auth::user()->id;
        $report->plan_id = $plan->id;
        $report->team_id = $teamid;
        $report->reportsTo = $input['reportsTo'];
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
            $report->cc = $finalv;
       }
            
        $report->save();
      
        $report = Report::where("user_id", Auth::user()->id)->where('reporttype', 'weekly')->where('plan_id', $plan->id)->orderby('id', 'desc')->first();

        $att = 0;
        foreach ($wplan as $wp) {
            $task = Task::find($wp->task_id);

            $weeklyreport = new Weeklyreport;
            $weeklyreport->report_id = $report->id;
           
            $weeklyreport->task_id = $wp->task_id;
            $weeklyreport->task_status = $task->status;
            $weeklyreport->Keyresult_target = $wp->keyresult_percent;
            $weeklyreport->task_target = $wp->task_percent;
            $weeklyreport->keyresult_id = $task->keyresultid;
            $weeklyreport->feedback = $input['rfeedback'.$wp->task_id];
            if($request->has('failyanalysis'.$task->id)){
               $weeklyreport->failurereason_id = $input['failyanalysis'.$task->id];
            }
            
            $weeklyreport->save();
        }
        $plan->isReported = 1;
        $plan->save();

        $weeklyreportf = Weeklyreport::where('report_id',  $report->id)->get();

        $taskachieved =0;
        if($weeklyreportf){
        foreach($weeklyreportf as $wr){
            if($wr->task_status == 1){
                $taskachieved += $wr->task_target;
            }

        }
    }
        $report->attainment = $taskachieved;
         $report->session_id = Session::where('status', 'Active')->first()->id;
        $report->save();
        
        //failure analysis
        if($request->has('fmemebercount')){
        for($i=0; $i <= $input['fmemebercount'];$i++){
            if($input['reasoncount'.$i] !=0){
            $failure = new Failure;
            $failure->report_id = $report->id;
            $failure->reason = $input['freason'.$i];
            $failure->percent = $input['reasoncount'.$i];
            $failure->save();
            }
        }
        }
        
        
        $plansthissession = Plan::where("sessionid", $plan->sessionid)->where('userid', Auth::user()->id)->select('id')->get();
         $sum = 0; $count =0;
        foreach($plansthissession as $ps){
            $sreport = Report::where("plan_id", $ps->id)->where('reporttype', 'weekly')->first();
            if($sreport){
                $sum += $sreport->attainment;
                $count ++;
            }
        }
        $average = round($sum / $count, 2);



            $objectives = Objective::where('user_id', Auth::user()->id)->where('session_id', Session::where('status', 'active')->first()->id)->get();
            
            $totalobj = 0; $ocount = 0;
        foreach ($objectives as $obj) {
            $keyresults = Keyresult::where('objective_id', $obj->id)->get();
            $krattain = 0;
            foreach ($keyresults as $kr) {
                $tasks = Task::where('keyresultid', $kr->id)->where('isMilestone', 1)->get();
                $achieved = 0;
                $countmile = 0;
                foreach ($tasks as $task) {
                     $plantask = Weeklyplan::where('task_id', $task->id)->first();
                    if ($task->status == 1) {
                        $achieved++;
                    }
                    if($plantask){
                        $countmile++;
                    }
                }
                $kr->attainment = 0;
                if ($countmile> 0) {
                    $kr->attainment = $achieved / $countmile;
                }


                $kr->save();
                $krattain += $kr->attainment;
            }
             $alignedobjs = Objective::where('objectives.aligned_to', $obj->id)->where('users.active', 1)->join('users','users.id', '=', 'objectives.user_id')->get();
            
            $parentobj = Objective::find($obj->id)->aligned_to != null? Objective::find($obj->id)->aligned_to: null;
           
            if($parentobj != null){
                $userid = Objective::find($parentobj)->user_id;
                $this->calculateAtt($userid, $plan->sessionid);
            }
            
            
            
            if ($alignedobjs->count() > 0) {
                $totalatt = 0;
                foreach ($alignedobjs as $aobj) {
                    $totalatt += $aobj->attainment;
                }

                $alignedattainment = $totalatt / $alignedobjs->count();
                $krattainment = 0;
                if ($keyresults->count() > 0) {
                    $krattainment = $krattain / $keyresults->count();
                }

                $obj->attainment = 0.8 * $alignedattainment + 0.2 *  $krattainment / 2;
                $obj->save();
            } else {
                $obj->attainment = 0;
                if ($keyresults->count() > 0) {
                    $obj->attainment =   $krattain / $keyresults->count();
                }
                $obj->save();
            }
            $totalobj +=  $obj->attainment;
            $ocount ++;
        }
        $objav = 0;
        if($ocount > 0 ){
              $objav = round ($totalobj/$ocount * 100,2);
        }
      
        Score::updateOrCreate(
            ['user_id' => $plan->userid, 'session_id' => $plan->sessionid],
            ['WeeklyScore'=> $average, 'OKR_Score' => $objav]
        );
        
        
            
      
    //      if(date("h:i:sa",strtotime($freport->created_at)) > date("h:i:sa", strtotime('22:00:59')) || date("h:i:sa",strtotime($freport->created_at)) < date("h:i:sa", strtotime('17:00:00')) ){
    //     $v = FillEngagement::where('report_id', $report->id)->where('engagement_id', 5)->where('Reason', 'This is issued because you failed to submit your daily checkin before the deadline')->first();
    //          if(! $v){
    //          $request2 = new Request(["plan_id"=> $report->id,'issuer' => $report->reportsTo, "objective"=> 5, 'Reason' => 'This is issued because you failed to submit your daily checkin before the deadline', 'Improvement' => 'Please submit your checkins before 10PM', 'Action' => 'Formal reminder', 'Perspective' => 1]);
    //       $input2 = $request2->all();
          
    //       $message = $this->issueenagement($input2, $request2);
    //         $message = "Reprimand issued successfully";
    //          }
    //          }
    
        });
       
            //broadcast a notification to all team members of auth user
         //get team id of auth user
         $authuser = Auth::user();
         $team = $authuser->team;
         $members = User::where('team',$team)->get();
         
         
        $manager = Team::where('team_name', Auth::user()->team)->first();
        
        //Get team manager
        $managerid = $manager->manager_id;
        if($managerid != null){
            $muser = User::find($managerid);
            //notify the team manager
            $muser->notify(new \App\Notifications\WeeklyReportNoti(Auth::user()));
        }
         
         foreach ($members as $member) {
            
            if($member->fname.$member->lname != $authuser->fname.$authuser->lname){
                $member->notify(new \App\Notifications\WeeklyReportNoti(Auth::user()));
            }
        
        }
        return back()->with('success', 'Weekly Report Added Successfully.');
    }
    
    public function update(Request $request, $reportid)
    {
         $input = $request->all();
         $report = Report::find($reportid);
        $plan = Plan::find($report->plan_id);
        $wplan = Weeklyplan::where('planid', $plan->id)->get();
        
        
        DB::transaction(function () use ($input,$request, $reportid, $report, $plan, $wplan) {
        
        
        $report->reportsTo = $input['reportsTo'];
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
            $report->cc = $finalv;
       }
            
        $report->save();
      
        Weeklyreport::where('report_id', $reportid )->delete();
        $att = 0;
        foreach ($wplan as $wp) {
            $task = Task::find($wp->task_id);

            $weeklyreport = new Weeklyreport;
            $weeklyreport->report_id = $report->id;
           
            $weeklyreport->task_id = $wp->task_id;
            $weeklyreport->task_status = $task->status;
            $weeklyreport->Keyresult_target = $wp->keyresult_percent;
            $weeklyreport->task_target = $wp->task_percent;
            $weeklyreport->keyresult_id = $task->keyresultid;
            $weeklyreport->feedback = $input['rfeedback'.$wp->task_id];
            if($request->has('failyanalysis'.$task->id)){
               $weeklyreport->failurereason_id = $input['failyanalysis'.$task->id];
            }
            
            $weeklyreport->save();
        }
        $plan->isReported = 1;
        $plan->save();

        $weeklyreportf = Weeklyreport::where('report_id',  $report->id)->get();

        $taskachieved =0;
        if($weeklyreportf){
        foreach($weeklyreportf as $wr){
            if($wr->task_status == 1){
                $taskachieved += $wr->task_target;
            }

        }
    }
        $report->attainment = $taskachieved;
         $report->session_id = Session::where('status', 'Active')->first()->id;
        $report->save();
        
        //failure analysis
        if($request->has('fmemebercount')){
        for($i=0; $i <= $input['fmemebercount'];$i++){
            if($input['reasoncount'.$i] !=0){
            $failure = new Failure;
            $failure->report_id = $report->id;
            $failure->reason = $input['freason'.$i];
            $failure->percent = $input['reasoncount'.$i];
            $failure->save();
            }
        }
        }
        
        
        $plansthissession = Plan::where("sessionid", $plan->sessionid)->where('userid', Auth::user()->id)->select('id')->get();
         $sum = 0; $count =0;
        foreach($plansthissession as $ps){
            $sreport = Report::where("plan_id", $ps->id)->where('reporttype', 'weekly')->first();
            if($sreport){
                $sum += $sreport->attainment;
                $count ++;
            }
        }
        $average = round($sum / $count, 2);



            $objectives = Objective::where('user_id', Auth::user()->id)->where('session_id', Session::where('status', 'active')->first()->id)->get();
            
            $totalobj = 0; $ocount = 0;
        foreach ($objectives as $obj) {
            $keyresults = Keyresult::where('objective_id', $obj->id)->get();
            $krattain = 0;
            foreach ($keyresults as $kr) {
                $tasks = Task::where('keyresultid', $kr->id)->where('isMilestone', 1)->get();
                $achieved = 0;
                $countmile = 0;
                foreach ($tasks as $task) {
                     $plantask = Weeklyplan::where('task_id', $task->id)->first();
                    if ($task->status == 1) {
                        $achieved++;
                    }
                    if($plantask){
                        $countmile++;
                    }
                }
                $kr->attainment = 0;
                if ($countmile > 0) {
                    $kr->attainment = $achieved / $countmile;
                }


                $kr->save();
                $krattain += $kr->attainment;
            }
             $alignedobjs = Objective::where('objectives.aligned_to', $obj->id)->where('users.active', 1)->join('users','users.id', '=', 'objectives.user_id')->get();
            
            $parentobj = Objective::find($obj->id)->aligned_to != null? Objective::find($obj->id)->aligned_to: null;
           
            if($parentobj != null){
                $userid = Objective::find($parentobj)->user_id;
                $this->calculateAtt($userid, $plan->sessionid);
            }
            
            
            
            if ($alignedobjs->count() > 0) {
                $totalatt = 0;
                foreach ($alignedobjs as $aobj) {
                    $totalatt += $aobj->attainment;
                }

                $alignedattainment = $totalatt / $alignedobjs->count();
                $krattainment = 0;
                if ($keyresults->count() > 0) {
                    $krattainment = $krattain / $keyresults->count();
                }

                $obj->attainment = 0.8 * $alignedattainment + 0.2 *  $krattainment / 2;
                $obj->save();
            } else {
                $obj->attainment = 0;
                if ($keyresults->count() > 0) {
                    $obj->attainment =   $krattain / $keyresults->count();
                }
                $obj->save();
            }
            $totalobj +=  $obj->attainment;
            $ocount ++;
        }
        $objav = 0;
        if($ocount > 0 ){
              $objav = round ($totalobj/$ocount * 100,2);
        }
      
        Score::updateOrCreate(
            ['user_id' => $plan->userid, 'session_id' => $plan->sessionid],
            ['WeeklyScore'=> $average, 'OKR_Score' => $objav]
        );
        });
       
      
        return back()->with('success', 'Weekly Report Updated Successfully.');
    }
    
    public function comment($report_id)
    {
        $plan = Report::where('reporttype', 'weekly')->where('reports.id', $report_id)->orderby('reports.created_at', 'desc')
            ->join('users', 'users.id', '=', 'reports.user_id')->select('reports.id', 'reports.updated_at', 'users.fname', 'users.lname', 'reports.user_id', 'users.team', 'reports.created_at' , 'reports.team_id', 'reports.reportsTo' , 'cc')->first();
        $message = "Which of your planned goals did you achieve this week? How much is that in percentage? Which unplanned tasks did you deliver? Which goals did you fail to achieve? Why?";
        $comments = Comment::where('report_id', $report_id)->orderby('comments.created_at', 'asc')
            ->join('users', 'users.id', '=', 'comments.commentor_id')
            ->select('fname', 'lname', 'commentor_id', 'position', 'comment', 'comments.created_at', 'comments.id', 'comments.updated_at')
            ->get();

        $type = "report";
        $currentPath=url()->current().'#comment';
        
        $team = Team::find($plan->team_id);
        return view('weeklyrcomment', compact(['plan', 'message', 'comments', 'type' , 'team','currentPath']));
    }
     public function neutralcomment($rid){
         Comment::create(['report_id'=> $rid, 'commentor_id'=> Auth::user()->id, 'comment' =>'Noted', 'type'=>2]);
        
        
        return back();
    }
    public function calculateAtt($userid, $sessionid){
           $plansthissession = Plan::where("sessionid", $sessionid)->where('userid', Auth::user()->id)->select('id')->get();
         $sum = 0; $count =0;
        foreach($plansthissession as $ps){
            $sreport = Report::where("plan_id", $ps->id)->where('reporttype', 'weekly')->first();
            if($sreport){
                $sum += $sreport->attainment;
                $count ++;
            }
        }
        $average = round($sum / $count, 2);



            $objectives = Objective::where('user_id', $userid)->where('session_id', Session::where('status', 'active')->first()->id)->get();
            
            $totalobj = 0; $ocount = 0;
        foreach ($objectives as $obj) {
            $keyresults = Keyresult::where('objective_id', $obj->id)->get();
            $krattain = 0;
            foreach ($keyresults as $kr) {
                $tasks = Task::where('keyresultid', $kr->id)->where('isMilestone', 1)->get();
                $achieved = 0;
                $countmile=0;
                foreach ($tasks as $task) {
                    $plantask = Weeklyplan::where('task_id', $task->id)->first();
                    if ($task->status == 1) {
                        $achieved++;
                    }
                    if($plantask){
                         $countmile++;
                    }
                }
                $kr->attainment = 0;
                if ( $countmile > 0) {
                    $kr->attainment = $achieved / $countmile;
                }


                $kr->save();
                $krattain += $kr->attainment;
            }
             $alignedobjs = Objective::where('objectives.aligned_to', $obj->id)->where('users.active', 1)->join('users','users.id', '=', 'objectives.user_id')->get();
            
          
            
            
            
            
            if ($alignedobjs->count() > 0) {
                $totalatt = 0;
                foreach ($alignedobjs as $aobj) {
                    $totalatt += $aobj->attainment;
                }

                $alignedattainment = $totalatt / $alignedobjs->count();
                $krattainment = 0;
                if ($keyresults->count() > 0) {
                    $krattainment = $krattain / $keyresults->count();
                }

                $obj->attainment = 0.8 * $alignedattainment + 0.2 *  $krattainment / 2;
                $obj->save();
            } else {
                $obj->attainment = 0;
                if ($keyresults->count() > 0) {
                    $obj->attainment =   $krattain / $keyresults->count();
                }
                $obj->save();
            }
            $totalobj +=  $obj->attainment;
            $ocount ++;
        }
        $objav = 0;

        if($ocount > 0 ){
             $objav = round ($totalobj/$ocount * 100,2);
        }
       
        Score::updateOrCreate(
            ['user_id' => $userid, 'session_id' => $sessionid],
            ['OKR_Score' => $objav]
        );
        
    }
     public function boostcomment(Request $request)
    {
       $c= Comment::create(['report_id' => $request->input('report_id'), 'commentor_id' => Auth::user()->id, 'comment' => $request->input('comment'), 'type' => 2]);
         
            
            
        
         
         
         
       $report = Report::find($request->input('report_id'));
       
       $plan = Plan::find($report->plan_id);
        $allreports = Report::where("user_id", $report->user_id)->where("team_id", $plan->teamid)->where('reporttype', 'daily')->where('created_at', '>', $plan->created_at)->pluck('id')->toArray();
        $concerns = Comment::whereIn('report_id', $allreports)->where('type', 2)->where('comment', 'Noted with concern')->get();
        
         if($report && $report->reportsTo == $c->commentor_id){
            $report->isCommented = 1;
            $report->save();
         }
          $d = FillEngagement::where('report_id', $request->input('report_id'))->where('engagement_id', 9)->where('Reason', 'This is issued for your poor performance for three successive days this week')->first();
      
          if ($concerns->count() > 2){
              $request->merge(["plan_id"=> $report->id, "objective"=> 9, 'Reason' => 'This is issued for your poor performance for three successive days this week', 'Improvement' => 'Please improve your planning and execute it accordingly', 'Action' => 'Formal reminder', 'Perspective' => 1]);
           $input = $request->all();
           $f = new FillEngagementController;
           $message = $f->issueenagement($input, $request);
            $message = "Reprimand issued successfully"; 
          }
       $v = FillEngagement::where('report_id', $request->input('report_id'))->where('engagement_id', 9)->where('Reason', 'This is issued for your poor performance this week as shown on your weekly report')->first();
       $av = FillEngagement::where('report_id', $request->input('report_id'))->where('engagement_id', 4)->where('Reason', 'Well done for achieving more than 84% of your weekly plan')->first();
       if($report->attainment >= 85 && ! $av){
          $request->merge(["plan_id"=> $report->id, "objective"=> 4, 'Reason' => 'Well done for achieving more than 84% of your weekly plan', 'Perspective' => 0]);
           $input = $request->all();
           $f = new FillEngagementController;
           $message = $f->issueenagement($input, $request);
            $message = "Appreciation issued successfully";
           
            
       }
        else if ($report->attainment <= 60 && ! $v){
          $request->merge(["plan_id"=> $report->id, "objective"=> 9, 'Reason' => 'This is issued for your poor performace this week as shown on your weekly report', 'Improvement' => 'Please improve your planning and execute it accordingly', 'Action' => 'Formal reminder', 'Perspective' => 1]);
           $input = $request->all();
           $f = new FillEngagementController;
           $message = $f->issueenagement($input, $request);
            $message = "Reprimand issued successfully";
           
            
       }
      
        return 1;
    }
}

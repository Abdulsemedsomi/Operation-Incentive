<?php

namespace App\Http\Controllers;
ini_set('max_execution_time', 500);
use App\Comment;
use App\Dailyplan;
use App\Dailyreport;
use App\Engagement;
use App\Plan;
use App\Objective;
use App\Keyresult;
use App\Score;
use App\Report;
use App\Session;
use App\Subtask;
use App\Teammember;
use App\Task;
use App\Team;
use App\User;
use App\Weeklyplan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TeamDailyreportController extends Controller
{
    //
    public function index($teamid, $sessionid, Request $request)
    {
        //
        $team = Team::find($teamid);
        $session = Session::find($sessionid); //active session
        $dplan = Plan::where('userid', Auth::user()->id)->where('plantype', 'daily')->where('isReported', 0)->where('sessionid', $session->id )->orderby('plans.id', 'desc')->first();
        $existplan = 0;
        if ($dplan) {
            $metric = DB::table('dailyplans')->where('dailyplans.planid', $dplan->id)->orderby('keyresults.created_at')->join('tasks', 'dailyplans.task_id', '=', 'tasks.id')
                ->join('keyresults', 'tasks.keyresultid', '=', 'keyresults.id')->select('keyresultid', 'keyresult_name', 'keyresults.created_at')->distinct()->get();
            $weekplan = DB::table('dailyplans')->where('dailyplans.planid', $dplan->id)->orderby('tasks.id', 'asc')->join('tasks', 'dailyplans.task_id', '=', 'tasks.id')
                ->join('keyresults', 'tasks.keyresultid', '=', 'keyresults.id')->select('keyresultid', 'taskname', 'tasks.id', 'dailyplans.planid as did', 'parent_task')->groupby('tasks.id')->distinct()->get();

            
            $subts = DB::table('dailyplans')->where('subtasks.isactive', 1)->where('subtasks.status', 0)->where('dailyplans.planid', $dplan->id)->join('subtasks', 'dailyplans.subtask_id', '=', 'subtasks.id')
            ->join('tasks', 'subtasks.taskid', '=', 'tasks.id')
            ->join('keyresults', 'keyresults.id', '=', 'tasks.keyresultid')
            ->get();

        } else {
            $weekplan = [];
            $metric = [];
            $subtasks = [];
            $existplan = 1;
            $subts = [];
        }
       
        $currentPath=url()->current();
       
       $userf= $request->has('user')? $request->input('user'):0;
       $plan = Report::where('reporttype', 'daily')->where('team_id', $teamid)->orderby('reports.created_at', 'desc')->where('session_id', $session->id)
            ->join('users', 'users.id', '=', 'reports.user_id')->select('reports.id', 'reports.updated_at', 'users.fname', 'users.lname','users.avatarcolor',  'reports.user_id', 'users.team', 'reports.created_at', 'reports.reportsTo', 'reports.cc')->paginate(20);
       if($userf != 0){
           $plan = Report::where('reporttype', 'daily')->where('team_id', $teamid)->orderby('reports.created_at', 'desc')->where('session_id', $session->id)->where('reports.user_id', $userf)
            ->join('users', 'users.id', '=', 'reports.user_id')->select('reports.id', 'reports.updated_at', 'users.fname', 'users.lname','users.avatarcolor',  'reports.user_id', 'users.team', 'reports.created_at', 'reports.reportsTo', 'reports.cc')->paginate(20)
            ->appends('user', $userf);
       }
        
         $users = Teammember::where('team_id', $teamid)->where('users.active', 1)->join('users', 'users.id', '=', 'teammembers.user_id')->get();

        return view('teamdailyreport', compact(['weekplan', 'metric', 'plan',  'team', 'existplan','subts','users','currentPath', 'userf', 'session']));
    }
    
      public function edit($id)
    {
        //
        //find team by id
     $dplan = Report::find($id);
     $team = Team::find($dplan->team_id);
      $metric = DB::table('dailyplans')->where('dailyplans.planid', $dplan->plan_id)->orderby('keyresults.created_at')->join('tasks', 'dailyplans.task_id', '=', 'tasks.id')
                ->join('keyresults', 'tasks.keyresultid', '=', 'keyresults.id')->select('keyresultid', 'keyresult_name', 'keyresults.created_at')->distinct()->get();
            $weekplan = DB::table('dailyplans')->where('dailyplans.planid', $dplan->plan_id)->orderby('tasks.id', 'asc')->join('tasks', 'dailyplans.task_id', '=', 'tasks.id')
                ->join('keyresults', 'tasks.keyresultid', '=', 'keyresults.id')->select('keyresultid', 'taskname', 'tasks.id', 'dailyplans.planid as did', 'parent_task')->groupby('tasks.id')->distinct()->get();

            
            $subtts = DB::table('dailyplans')->where('subtasks.isactive', 1)->where('subtasks.status', 0)->where('dailyplans.planid', $dplan->plan_id)->join('subtasks', 'dailyplans.subtask_id', '=', 'subtasks.id')
            ->join('tasks', 'subtasks.taskid', '=', 'tasks.id')
            ->join('keyresults', 'keyresults.id', '=', 'tasks.keyresultid')
            ->get();
        $users = Teammember::where('team_id', $team->id)->where('users.active', 1)->join('users', 'users.id', '=', 'teammembers.user_id')->get();
         return view('editdailyreport', compact(['team', 'users', 'metric','weekplan', 'dplan', 'subtts']));
    }
    public function store(Request $request, $teamid)
    {
        //
        $input = $request->all();
   $session = Session::where('status', 'Active')->first();
        $plan = Plan::where("userid", Auth::user()->id)->where('plantype', 'daily')->where('teamid', $teamid)->where('sessionid', $session->id )->where('isReported', 0)->orderby('id', 'desc')->first();
        if($plan){
        $dplan = Dailyplan::where('planid', $plan->id)->whereNotNull('subtask_id')->get();

        DB::transaction(function () use ($dplan,$plan, $teamid, $input,$request) {
        $report = new Report;
        $report->reporttype = "daily";

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
           $report->session_id = Session::where('status', 'Active')->first()->id;
        $report->save();
        
       
        $report = Report::where("user_id", Auth::user()->id)->where('plan_id', $plan->id)->where('reporttype', 'daily')->orderby('id', 'desc')->first();

        foreach ($dplan as $dp) {


            $subtask = Subtask::find($dp->subtask_id);
            $subtask->status = $input['subradio-group' . $dp->subtask_id];
            $subtask->save();
            $dailyreport = new Dailyreport;
            $dailyreport->report_id = $report->id;
           
            $dailyreport->task_id = $dp->task_id;
            $dailyreport->subtask_id = $dp->subtask_id;
            $dailyreport->status =  $input['subradio-group' . $dp->subtask_id];
            $dailyreport->feedback =  $input['subfeedback' . $dp->subtask_id];
            $dailyreport->save();
        }
        $dplanwithtask = Dailyplan::where('planid', $plan->id)->whereNull('subtask_id')->get();
        foreach ($dplanwithtask as $dp) {


            $task = Task::find($dp->task_id);
            $task->status = $input['radio-group' . $dp->task_id];
            $task->save();
            $dailyreport = new Dailyreport;
            $dailyreport->report_id = $report->id;
           
            $dailyreport->task_id = $dp->task_id;

            $dailyreport->status =  $input['radio-group' . $dp->task_id];
            $dailyreport->feedback =  $input['feedback' . $dp->task_id];
            $dailyreport->save();
        }
        $plan->isReported = 1;
        $plan->save();

 
    
    //update okr
      
        $plansthissession = Plan::where("sessionid", $plan->sessionid)->where('userid', Auth::user()->id)->select('id')->get();
          $reportsavg = Report::where("session_id", $plan->sessionid)->where('user_id', Auth::user()->id)->where('reporttype', 'weekly')->avg('attainment');
         $sum = 0; $count =0;
        foreach($plansthissession as $ps){
            $sreport = Report::where("plan_id", $ps->id)->where('reporttype', 'weekly')->first();
            if($sreport){
                $sum += $sreport->attainment;
                $count ++;
            }
        }
        $average =  round($reportsavg, 2);



            $objectives = Objective::where('user_id', Auth::user()->id)->where('session_id', $plan->sessionid)->get();
            $totalobj = 0; $ocount = 0;
        foreach ($objectives as $obj) {
            $keyresults = Keyresult::where('objective_id', $obj->id)->get();
            $krattain = 0;
            foreach ($keyresults as $kr) {
                $tasks = Task::where('keyresultid', $kr->id)->where('isMilestone', 1)->get();
                $achieved = 0;
                foreach ($tasks as $task) {
                    if ($task->status == 1) {
                        $achieved++;
                    }
                }
                $kr->attainment = 0;
                if ($tasks->count() > 0) {
                    $kr->attainment = $achieved / $tasks->count();
                }


                $kr->save();
                $krattain += $kr->attainment;
            }
            $alignedobjs = Objective::where('aligned_to', $obj->id)->get();
            
            
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
              $parentobj = Objective::find($obj->id)->aligned_to != null? Objective::find($obj->id)->aligned_to: null;
           
            if($parentobj != null){
                $userid = Objective::find($parentobj)->user_id;
                $this->calculateAtt($userid, $plan->sessionid);
            }
        }
        $objav = round ($totalobj/$ocount * 100,2);
        Score::updateOrCreate(
            ['user_id' => $plan->userid, 'session_id' => $plan->sessionid],
            ['OKR_Score' => $objav]
        );
        });
    
     //broadcast a notification to all team members of auth user
         //get team id of auth user
         $authuser = Auth::user();
         $team = $authuser->team;
         $members = User::where('team',$team)->get();
        //  //dd($members);
         
        $manager = Team::where('team_name', Auth::user()->team)->first();
        
        // //Get team manager
         $managerid = $manager->manager_id;
        if($managerid != null){
            $muser = User::find($managerid);
            //notify the team manager
            $muser->notify(new \App\Notifications\DailyReportNoti(Auth::user()));
        }
         foreach ($members as $member) {
             if($member->fname.$member->lname != $authuser->fname.$authuser->lname){
                $member->notify(new \App\Notifications\DailyReportNoti(Auth::user()));
            }
           
        }
        return back()->with('success', 'Report added Successfully.');
        }
        else{
            return back()->with('error', 'Server Error. Please try again.');
        }
    }
    public function comment($report_id)
    {
        $plan = Report::where('reporttype', 'daily')->where('reports.id', $report_id)->orderby('reports.created_at', 'desc')
            ->join('users', 'users.id', '=', 'reports.user_id')->select('reports.id', 'reports.updated_at', 'users.fname', 'users.lname', 'reports.user_id', 'users.team' , 'reports.created_at' , 'reports.team_id','reports.reportsTo', 'cc')->first();
        $message = "What did you work on today? Which challenges did affect your progress today?";
        $comments = Comment::where('report_id', $report_id)->orderby('comments.created_at', 'asc')
            ->join('users', 'users.id', '=', 'comments.commentor_id')
            ->select('fname', 'lname', 'commentor_id', 'position', 'comment', 'comments.created_at', 'comments.id', 'comments.updated_at')
            ->get();
        $session = Session::find(Plan::find(Report::find($plan->id)->plan_id)->sessionid);
        $engagements = Engagement::all();

       
        $currentPath=url()->current().'#comment';
        
        $team = Team::find($plan->team_id);
        $type = "report";
        return view('dailyrcomment', compact(['plan', 'message', 'comments', 'type', 'session', 'engagements' , 'team','currentPath']));
    }
      public function update(Request $request, $id)
    {
        //
        $input = $request->all();
   
        

        DB::transaction(function () use ($input,$request, $id) {
        $report = Report::find($id);

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
        $teamid = $report->team_id;
        $plan = Plan::find($report->plan_id);
        $dplan = Dailyplan::where('planid', $plan->id)->whereNotNull('subtask_id')->get();
       
        Dailyreport::where('report_id',$report->id )->delete();

        foreach ($dplan as $dp) {

            if($request->has('subradio-group' . $dp->subtask_id)){
                 $subtask = Subtask::find($dp->subtask_id);
            $subtask->status = $input['subradio-group' . $dp->subtask_id];
            $subtask->save();
            $dailyreport = new Dailyreport;
            $dailyreport->report_id = $report->id;
           
            $dailyreport->task_id = $dp->task_id;
            $dailyreport->subtask_id = $dp->subtask_id;
            $dailyreport->status =  $input['subradio-group' . $dp->subtask_id];
            $dailyreport->feedback = $request->has('subfeedback' . $dp->subtask_id)?$input['subfeedback' . $dp->subtask_id]:null;
            $dailyreport->save();
            }
           
        }
        $dplanwithtask = Dailyplan::where('planid', $plan->id)->whereNull('subtask_id')->get();
        foreach ($dplanwithtask as $dp) {
            if($request->has('radio-group' . $dp->task_id)){
            $task = Task::find($dp->task_id);
            $task->status = $input['radio-group' . $dp->task_id];
            $task->save();
            $dailyreport = new Dailyreport;
            $dailyreport->report_id = $report->id;
           
            $dailyreport->task_id = $dp->task_id;

            $dailyreport->status =  $input['radio-group' . $dp->task_id];
            $dailyreport->feedback = $request->has('feedback' . $dp->task_id)? $input['feedback' . $dp->task_id]: null;
            $dailyreport->save();
            }
        }
       

//update okr
  
        $plansthissession = Plan::where("sessionid", $plan->sessionid)->where('userid', Auth::user()->id)->select('id')->get();
        $reportsavg = Report::where("session_id", $plan->sessionid)->where('reporttype', 'weekly')->where('user_id', Auth::user()->id)->avg('attainment');
         $sum = 0; $count =0;
        foreach($plansthissession as $ps){
            $sreport = Report::where("plan_id", $ps->id)->where('reporttype', 'weekly')->first();
            if($sreport){
                $sum += $sreport->attainment;
                $count ++;
            }
        }
        $average =  round($reportsavg, 2);



            $objectives = Objective::where('user_id', Auth::user()->id)->where('session_id', $plan->sessionid)->get();
            $totalobj = 0; $ocount = 0;
        foreach ($objectives as $obj) {
            $keyresults = Keyresult::where('objective_id', $obj->id)->get();
            $krattain = 0;
            foreach ($keyresults as $kr) {
                $tasks = Task::where('keyresultid', $kr->id)->where('isMilestone', 1)->get();
                $achieved = 0;
                foreach ($tasks as $task) {
                    if ($task->status == 1) {
                        $achieved++;
                    }
                }
                $kr->attainment = 0;
                if ($tasks->count() > 0) {
                    $kr->attainment = $achieved / $tasks->count();
                }


                $kr->save();
                $krattain += $kr->attainment;
            }
            $alignedobjs = Objective::where('aligned_to', $obj->id)->get();
           
            
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
              $parentobj = Objective::find($obj->id)->aligned_to != null? Objective::find($obj->id)->aligned_to: null;
           
            if($parentobj != null){
                $userid = Objective::find($parentobj)->user_id;
                $this->calculateAtt($userid, $plan->sessionid);
            }
        }
        $objav = round ($totalobj/$ocount * 100,2);
        Score::updateOrCreate(
            ['user_id' => $plan->userid, 'session_id' => $plan->sessionid],
            ['WeeklyScore'=> $average, 'OKR_Score' => $objav]
        );
        



    });
    
     

        return back()->with('success', 'Report updated Successfully.');
    }
      public function neutralcomment($rid){
         Comment::create(['report_id'=> $rid, 'commentor_id'=> Auth::user()->id, 'comment' =>'Noted', 'type'=>2]);
        
        
        return back();
    }
    
     public function calculateAtt($userid, $sessionid){
           $plansthissession = Plan::where("sessionid", $sessionid)->where('userid', $userid)->select('id')->get();
         $sum = 0; $count =0;
        



            $objectives = Objective::where('user_id', $userid)->where('session_id', $sessionid)->get();
            
            $totalobj = 0; $ocount = 0;
        foreach ($objectives as $obj) {
            $keyresults = Keyresult::where('objective_id', $obj->id)->get();
            $krattain = 0;
            foreach ($keyresults as $kr) {
                $tasks = Task::where('keyresultid', $kr->id)->where('isMilestone', 1)->get();
                $achieved = 0;
                foreach ($tasks as $task) {
                    if ($task->status == 1) {
                        $achieved++;
                    }
                }
                $kr->attainment = 0;
                if ($tasks->count() > 0) {
                    $kr->attainment = $achieved / $tasks->count();
                }


                $kr->save();
                $krattain += $kr->attainment;
            }
            $alignedobjs = Objective::where('aligned_to', $obj->id)->get();
          
            
            
            
            
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
                if(User::find($userid)->position == 'CEO'){
                     $obj->attainment = $alignedattainment;
                }
               
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
             $parentobj = Objective::find($obj->id)->aligned_to != null? Objective::find($obj->id)->aligned_to: null;
           
            // if($parentobj != null){
            //     $userid = Objective::find($parentobj)->user_id;
            //     $this->calculateAtt($userid, $sessionid);
            // }
        }
        $objav = 0;
        if($ocount > 0){
            $objav = round ($totalobj/$ocount * 100,2);
        }
        
        Score::updateOrCreate(
            ['user_id' => $userid, 'session_id' => $sessionid],
            ['OKR_Score' => $objav]
        );
        
    }
      public function boostcomment(Request $request)
    {
        Comment::create(['report_id' => $request->input('report_id'), 'commentor_id' => Auth::user()->id, 'comment' => $request->input('comment'), 'type' => 2]);
        return 1;
    }
      
}

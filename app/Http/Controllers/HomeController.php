<?php

namespace App\Http\Controllers;

use App\Keyresult;
use App\Objective;
use App\Plan;
use App\Home;
use App\Projectmember;
use App\Score;
use App\Imports\AwardImport;
use App\Session;
use App\Team;
use App\User;
use App\Task;
use App\Companyinfo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $user = User::where('email', Auth::user()->email)->first();
        $team = Team::where('team_name', $user->team)->first();
        $session = Session::where('status', 'Active')->first();
        $plan = Plan::where('userid', Auth::user()->id)->where('plantype', 'daily')->orderby('plans.id', 'desc')->where('sessionid',$session->id )->first();
        $dp = 0;
        $adp=0;
        if ($plan) {
            $dp = DB::table('dailyplans')->where('dailyplans.planid', $plan->id)->get()->count();
            $adp = DB::table('tempplans')->where('planid', $plan->id)->get()->count();
            $metric = DB::table('dailyplans')->where('dailyplans.planid', $plan->id)->orderby('keyresults.created_at')->join('tasks', 'dailyplans.task_id', '=', 'tasks.id')
                ->join('keyresults', 'tasks.keyresultid', '=', 'keyresults.id')->select('keyresultid', 'keyresult_name', 'keyresults.created_at')->distinct()->get();
            $weekplan = DB::table('dailyplans')->where('dailyplans.planid', $plan->id)->join('tasks', 'dailyplans.task_id', '=', 'tasks.id')->orderby('tasks.id', 'asc')
                ->join('keyresults', 'tasks.keyresultid', '=', 'keyresults.id')->select('keyresultid', 'taskname', 'tasks.id')->distinct()->get();

          
        } else {
            $weekplan = [];
            $metric = [];
           
        }
        $wp = Plan::where('userid', Auth::user()->id)->where('plantype', 'weekly')->orderby('plans.id', 'desc')->where('sessionid',$session->id )->first();
        $objectives = [];
        $metricvalues = [];
        $weekplann = [];
        $taskachieved = 0;
        if ($wp) {
            $objectives = DB::table('weeklyplans')->where('weeklyplans.planid', $wp->id)->join('keyresults', 'weeklyplans.keyresult_id', '=', 'keyresults.id')
                ->join('objectives', 'keyresults.objective_id', '=', 'objectives.id')
                ->select('objective_name', 'objective_id')->distinct()->get();

            $metricvalues = DB::table('weeklyplans')->where('weeklyplans.planid', $wp->id)->join('tasks', 'weeklyplans.task_id', '=', 'tasks.id')
                ->join('keyresults', 'tasks.keyresultid', '=', 'keyresults.id')->select('keyresultid', 'keyresult_name', 'keyresult_percent', 'objective_id')->distinct()->get();
            $weekplann = DB::table('weeklyplans')->where('weeklyplans.planid', $wp->id)->join('tasks', 'weeklyplans.task_id', '=', 'tasks.id')
                ->join('keyresults', 'tasks.keyresultid', '=', 'keyresults.id')->select('keyresultid', 'taskname', 'tasks.id', 'task_percent', 'tasks.status')->get();
     foreach ($metricvalues as $m) {
                $kp = $m->keyresult_percent;

                foreach ($weekplann as $wp) {
                    if ($wp->keyresultid == $m->keyresultid) {
                        $task = Task::find($wp->id);
                        if ($task->status == 1) {
                            $taskachieved += $wp->task_percent;
                        }
                    }
                }
            }
        }

        $session = Session::where('status', 'active')->orderby('id', 'desc')->first();
        $score = [];
        if ($session) {
            $score = Score::where('user_id', $user->id)->where('session_id', $session->id)->first();
        }
        $company = Companyinfo::where('session_id', $session->id)->first();
        $projects = Projectmember::where('user_id', $user->id)->where('projects.status',1)->join('projects', "projects.id", '=', "projectmembers.project_id")->get();
        
        
        
        
        return view('hub', compact(['user','adp', 'dp','team', 'plan', 'weekplan', 'metric', 'objectives', 'metricvalues', 'weekplann', "projects", 'score', 'company', 'taskachieved']));
    }
    public function store(Request $request)
    {
        $sessionid = Session::where('status', 'Active')->first()->id;
        if ($request->input('type') == "revenue") {
            Home::updateOrCreate(['session_id' => $sessionid], ['revenuetarget' => $request->input('revenuetarget'), 'revenue' => $request->input('revenue')]);
        } else if ($request->input('type') == "cash") {
            Home::updateOrCreate(['session_id' => $sessionid], ['cashtarget' => $request->input('cashtarget'), 'cash' => $request->input('cash')]);
        } else if ($request->input('type') == "award") {
            Home::updateOrCreate(['session_id' => $sessionid], ['awardtarget' => $request->input('awardtarget'), 'award' => $request->input('award')]);
        } else if ($request->input('type') == "ebtda") {
            Home::updateOrCreate(['session_id' => $sessionid], ['ebtdatarget' => $request->input('ebtdatarget'), 'ebtda' => $request->input('ebtda')]);
        }
        return back();
    }

}

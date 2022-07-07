<?php

namespace App\Http\Controllers;

use App\Comment;
use App\Engagementdifference;
use App\Engagement;
use App\Engagementcount;
use App\Projectmember;
use App\Bidmember;
use Carbon\Carbon;
use App\Kpi;
use App\FillEngagement;
use App\Formula;
use App\Failuretarget;
use App\Award;
use App\Generatedincentive;
use App\Detailedreport;
use App\Companyinfo;
use App\Bid;
use App\Leave;
use App\Weeklyplan;
use App\Project;
use App\Projectdelivery;
use App\OperationIncentive;
use App\Plan;
use App\Objective;
use App\Kpiform;
use App\Keyresult;
use App\Filledkpi;
use App\Filledkpilist;
use App\KpiNotice;
use Barryvdh\DomPDF\Facade as PDF;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\IncentivExport;
use App\Weeklyreport;
use App\Failure;
use App\Hrmsdata;
use App\Task;
use App\Report;
use App\Team;
use App\User;
use Illuminate\Http\Request;
use App\Session;
use Illuminate\Support\Collection;

use App\Score;
use App\Celoxis;
use Illuminate\Support\Facades\Http;
use DateTime;

use GuzzleHttp\Client;

class VisualizationController extends Controller
{
    //
    public function teamEngagement($teamid, Request $request)
    {


        $activesession = Session::find($request->query('sessionid'));
        $userdata = User::where('team', Team::find($teamid)->team_name)->where('active', 1)->orderby('fname', 'asc')->get();

        $result = [];
        $count = 0;
        $mostapp = Score::where('users.active', 1)->where('scores.session_id', $activesession->id)->join('users', 'users.id', 'scores.user_id')->max('appcount');
        $mostengagedusers = Score::where('session_id', $activesession->id)->where('appcount', $mostapp)->get();

        $minrep = $mostengagedusers->min('repcount');

        $mostengaged = Score::where('session_id', $activesession->id)->where('appcount', $mostapp)->where('repcount', $minrep)->get();


        foreach ($userdata as $udata) {

            $filledengapp = FillEngagement::where('fill_engagements.issued_to', $udata->id)->where('engagements.Perspective', 0)->where('session_id', $activesession->id)->join('engagements', 'engagements.id', '=', 'fill_engagements.engagement_id')->get();
            $filledengrep = FillEngagement::where('fill_engagements.issued_to', $udata->id)->where('engagements.Perspective', 1)->where('fill_engagements.type', 0)->where('session_id', $activesession->id)->join('engagements', 'engagements.id', '=', 'fill_engagements.engagement_id')->get();
            $kpiapp = KpiNotice::where('kpi_notices.issued_to', $udata->id)->where('type', 1)->where('session_id', $activesession->id)->get();
            $kpirep = KpiNotice::where('kpi_notices.issued_to', $udata->id)->where('type', 2)->where('session_id', $activesession->id)->get();
            $status = 0;
            if ($mostengaged->contains('user_id', $udata->id)) {
                $status = 1;
            }
            $result[$count] = [
                'user' => $udata->fname . " " . $udata->lname,
                'repcount' => $filledengrep->count() +  $kpirep->count(),
                'appcount' =>  $filledengapp->count() +  $kpiapp->count(),
                'mostengaged' => $status
            ];
            $count++;
        }
        return $result;
    }

    public function weeklyperformance($userid, Request $request)
    {


        $activesession = Session::find($request->query('sessionid'));

        $reports = Report::where('reporttype', 'weekly')->where('user_id', $userid)->where('session_id', $activesession->id)->orderby('created_at', 'asc')->get();

        $result[0] = $activesession;
        $result[1] = $reports;
        return $result;
    }
    public function incetive()
    {

        $sessions = Session::where('isNeeded', 1)->get();
        return view('incentivelanding', compact('sessions'));
    }
    public function incentivesetting()
    {
        return view('incentivesettings');
    }
    public function deleteincentivereport(Request $request, $id)
    {
        $input = $request->all();
        $session_id = $input['deletesession'];
        Generatedincentive::destroy($id);
        Detailedreport::where('incentive_id', $id)->delete();
        OperationIncentive::where('session_id', $session_id)->delete();
        return redirect()->back()
            ->with('success', 'Deleted Successfully.');
    }
    public function openincentivereport($id)
    {
        $gi = Generatedincentive::where('session_id', $id)->first();
        $company = Companyinfo::where('session_id', $id)->first();
        $reports =  Detailedreport::where('incentive_id', $gi->id)->get();
        $projects = OperationIncentive::groupby('project_id')->get();
        $tasks = OperationIncentive::groupby('task_id')->get();

        $forex = OperationIncentive::groupby('project_id')->get();
        $source = OperationIncentive::groupby('project_id')->where('custom_schedule_tracker', 'Sourcing End')->get();
        $order = OperationIncentive::groupby('project_id')->where('custom_schedule_tracker', 'Logistics End')->get();
        $implementation = OperationIncentive::groupby('project_id')->where('custom_schedule_tracker', 'Implementation End')->get();
        $project = OperationIncentive::groupby('project_id')->where('custom_schedule_tracker', 'Project End')->get();
        $allProjects = OperationIncentive::groupby('project_id')->get();
        $milestonepayment = OperationIncentive::groupby('project_id')->where('custom_schedule_tracker', 'Payment End')->get();
        $users = User::where('active', 1)->get();
        $participants = DB::table('opration_incentive_user')->get();
        $bonusamount = OperationIncentive::get();
        // $forexstart = Celoxis::groupby('project_id')->where('custom_schedule_tracker', 'Forex Start')->get();
        // $forexend = Celoxis::groupby('project_id')->where('custom_schedule_tracker', 'Forex End')->get();

        return view('incentives', compact('company', 'gi', 'id', 'projects', 'forex', 'source', 'order', 'implementation', 'project', 'users', 'tasks', 'allProjects', 'participants', 'bonusamount'));

        $min = 0;
        $teams = Team::where('isActive', 1)->get();
        // Hrmsdata::where('type', 2)->where('session_id',$sessionid)->join()->get();
        foreach ($teams as $team) {
            $userdata = User::where('team', Team::find($team->id)->team_name)->where('active', 1)->where('position', '!=', 'CEO')->orderby('fname', 'asc')->get();


            $result = [];
            $count = 0;
            $tpscore = 0;

            foreach ($userdata as $user) {
                $tardy = Hrmsdata::where('user_id', $user->id)->where('type', 2)->where('session_id', $sessionid)->get();
                $unplan = Hrmsdata::where('user_id', $user->id)->where('type', 1)->where('session_id', $sessionid)->get();
                $ssum = Leave::where('user_id', $user->id)->where('leave_type', 'Sick Leave')->where('session_id', $sessionid)->get();
                $esum = Leave::where('user_id', $user->id)->where('leave_type', 'Emergency Annual leave')->where('session_id', $sessionid)->get();
                $_ssum = $ssum->count() > 0 ? $ssum->sum('duration') : 0;
                $_esum = $esum->count() > 0 ? $esum->sum('duration') : 0;
                $total = $tardy->count() + $unplan->count() + $_ssum + $_ssum;
            }
            $count++;
            $result[$count] = [
                'team' => $team->id,
                'total' => $total
            ];
            if ($total < $min) {
                $min = $total;
            }
        }
        return $result;
    }
    public function bestperformer($sessionid)
    {
        $data = Engagementdifference::where('session_id', $sessionid)->get()->take(3);
        return $data;
    }


    public function changedashboard(Request $request)
    {
        $input = $request->all();
        $failuretargets = Failuretarget::all();
        $session = Session::find($input['session-select']);
        $mostapp = Score::where('users.active', 1)->where('scores.session_id', $session->id)->join('users', 'users.id', 'scores.user_id')->max('appcount');
        $mostengagedusers = Score::where('session_id', $session->id)->where('appcount', $mostapp)->get();

        $minrep = $mostengagedusers->min('repcount');

        $mostengaged = Score::where('session_id', $session->id)->where('appcount', $mostapp)->where('repcount', $minrep)->get();

        $user = User::where('email', Auth::user()->email)->first();
        $id = 0;

        if ($user) {
            $id = $user->id;
        }

        if ($session) {
            $sessionid = $session->id;
            $objectives = Objective::where('user_id', $id)->where('session_id', $sessionid)->get();
            $objatt = 0;
            $t = 0;
            if ($objectives->count() > 0) {
                foreach ($objectives as $ob) {
                    $t += $ob->attainment;
                }
                $objatt = $t / $objectives->count();
            } else {
            }
            $keyresults = DB::table('keyresults')->where('objectives.user_id', $id)

                ->join('objectives', 'objectives.id', '=', 'keyresults.objective_id')->get();
        } else {
            $keyresults = [];
            $objatt = 0;
            $objectives = [];
        }

        $userdata = User::where('team', 'BAI')->orderby('fname', 'asc')->where('active', 1)->get();
        $score = Score::where('session_id', $session->id)->get();
        $result = [];
        $count = 0;
        $tpscore = 0;

        foreach ($userdata as $udata) {

            $result[$count] = [
                'user' => $udata->fname . " " . $udata->lname,
                'tpscore' => 0
            ];
            foreach ($score as $sc) {
                if ($udata->id == $sc->user_id) {
                    $result[$count] = [
                        'user' => $udata->fname . " " . $udata->lname,
                        'tpscore' => $sc->WeeklyScore
                    ];
                }
            }
            $count++;
        }
        $this->array_sort_by_column($result, "tpscore");

        //team performance
        $teamperf = [];
        $count = 0;
        $avscore = 0;


        $allteams = Team::where('isActive', 1)->where('team_name', '!=', 'Drivers')->get();

        foreach ($allteams as $at) {
            if ($at->team_name != "CEO") {
                $teamperf[$count] = [
                    'team' => $at->team_name,
                    'avscore' => 0
                ];
                $sum = 0;
                $c = 0;
                foreach ($score as $sc) {
                    if ($at->team_name == User::find($sc->user_id)->team) {

                        $sum += $sc->WeeklyScore;


                        $c++;
                    }
                }
                $teamperf[$count] = [
                    'team' => $at->team_name,
                    'avscore' => $c == 0 ? 0 : round($sum / $c, 2)
                ];
                $count++;
            }
        }
        $this->array_sort_by_column($teamperf, "avscore");
        $bestperformers = Engagementdifference::where('session_id', $session->id)->get()->take(5);


        return view('dashboard', compact(['keyresults', 'objatt', 'objectives', 'session', 'failuretargets', 'result', 'teamperf', 'mostengaged', 'bestperformers']));
    }
    public function leavedatacount($session, $managerid)
    {
        $users = User::where('active', 1)->where('reportsTo', User::find($managerid)->fullname)->where('team', 'Middle Management')->where('created_at', '<', $session->start_date)->pluck('id');
        $leavesc = Leave::whereIn('user_id', $users)->where('session_id', $session->id)->get()->sum('duration');
        $hrmsc = Hrmsdata::whereIn('user_id', $users)->where('session_id', $session->id)->get()->count();
        return $users->count() > 0 ? round(($leavesc + $hrmsc) / $users->count(), 2) : 0;
    }
    public function topperformers($type, Request $request)
    {

        $session = Session::find($request->query('sessionid'));
        $result = "";
        if ($type == 1) {
            $result = Engagementdifference::where('session_id', $session->id)->get()->take(5);
        } else if ($type == 2) {

            $result = DB::select('call mostengaged(?)', array($session->id));
        } else if ($type == 3) {
            $result = DB::select('call bestleader(?,?,?)', array($session->id, 0, 1));
        } else if ($type == 4) {
            $result = DB::select('call bestleader(?,?,?)', array($session->id, 1, 2));
        } else if ($type == 5) {
            $teams = Team::where('isActive', 1)->get();
            if ($session->id == 17) {
                $teams = Team::where('isActive', 1)->whereNotIn('id',  [76, 70, 68])->get();
            }
            $result = [];
            $c = 0;
            foreach ($teams as $team) {
                $val = DB::select('call 	leavedata(?,?)', array($team->id, $session->id));
                if ($val[0]->result == null) {
                    $val[0]->result = 0;
                }
                if ($session->id == 17 && $team->id == 59) {
                    $val2 = DB::select('call 	leavedata(?,?)', array(68, $session->id));
                    $val[0]->result = $val2[0]->result + (2 * $val[0]->result) / 3;
                }
                if ($team->manager_id != null) {
                    $result[$c] = [
                        'fname' => User::where('id', $team->manager_id)->first()->fname,
                        'lname' => User::where('id', $team->manager_id)->first()->lname,
                        'team' => $session->id == 17 && $team->id == 59 ? 'Finance' : $team->team_name,
                        'score' => round(floatval($val[0]->result), 2),
                        'teamid' => $team->id
                    ];
                    $c++;
                } else {
                    $arr = explode(',', $team->multiplemanagers);
                    foreach ($arr as $a) {
                        $score = $this->leavedatacount($session, $a);
                        if ($score != 0) {
                            $result[$c] = [
                                'fname' => User::where('id', $a)->first()->fname,
                                'lname' => User::where('id', $a)->first()->lname,
                                'team' => $team->team_name . '(' . User::where('id', $a)->first()->fname . ')',
                                'score' => $score,
                                'teamid' => $team->id
                            ];
                            $c++;
                        }
                    }
                }
            }




            $this->array_sort_by_columnasc($result, "score");
        }

        return $result;
    }
    public function teamperformances($id, Request $request)
    {


        $session = Session::find($id);

        $type = $request->query('type');
        $score = Score::where('session_id', $session->id)->get();

        $teamperf = [];
        $count = 0;
        $avscore = 0;


        $allteams = Team::where('isActive', 1)->where('team_name', '!=', 'Drivers')->get();

        foreach ($allteams as $at) {
            if ($at->team_name != "CEO") {
                $teamperf[$count] = [
                    'team' => $at->team_name,
                    'avscore' => 0
                ];
                $sum = 0;
                $c = 0;
                foreach ($score as $sc) {
                    if ($at->team_name == User::find($sc->user_id)->team) {
                        if ($type == 1) {
                            $sum += $sc->WeeklyScore;
                        }
                        if ($type == 2) {
                            $sum += $sc->engagementScore;
                        } else if ($type == 3) {
                            $sum += $sc->KPI_Score;
                        } else if ($type == 4) {
                            $sum +=  round(($sc->KPI_Score + $sc->engagementScore + $sc->WeeklyScore) / 3, 2);
                        }
                        $c++;
                    }
                }
                $teamperf[$count] = [
                    'team' => $at->team_name,
                    'avscore' => $c == 0 ? 0 : round($sum / $c, 2)
                ];
                $count++;
            }
        }
        $this->array_sort_by_column($teamperf, "avscore");
        return $teamperf;
    }
    public function topPerformer($teamid, Request $request)
    {


        $activesession = Session::find($request->query('sessionid'));
        $type = $request->query('type');
        $userdata = User::where('team', Team::find($teamid)->team_name)->where('active', 1)->orderby('fname', 'asc')->get();

        $score = Score::where('session_id', $activesession->id)->get();
        $result = [];
        $count = 0;
        $tpscore = 0;

        foreach ($userdata as $udata) {
            if ($udata->position != 'CEO') {
                $result[$count] = [
                    'user' => $udata->fname . " " . $udata->lname,
                    'tpscore' => 0
                ];
                foreach ($score as $sc) {
                    $uscore = $sc->WeeklyScore;
                    if ($udata->id == $sc->user_id) {
                        if ($type == 2) {
                            $uscore = $sc->engagementScore;
                        } else if ($type == 3) {
                            $uscore = $sc->KPI_Score;
                        } else if ($type == 4) {
                            $uscore = round(($sc->KPI_Score + $sc->engagementScore + $sc->WeeklyScore) / 3, 2);
                        }
                        $result[$count] = [
                            'user' => $udata->fname . " " . $udata->lname,
                            'tpscore' => $uscore
                        ];
                    }
                }
                $count++;
            }
        }
        $this->array_sort_by_column($result, "tpscore");
        return $result;
    }
    function array_sort_by_column(&$arr, $col, $dir = SORT_DESC)
    {
        $sort_col = array();
        foreach ($arr as $key => $row) {
            $sort_col[$key] = $row[$col];
        }

        array_multisort($sort_col, $dir, $arr);
    }
    function array_sort_by_columnasc(&$arr, $col, $dir = SORT_ASC)
    {
        $sort_col = array();
        foreach ($arr as $key => $row) {
            $sort_col[$key] = $row[$col];
        }

        array_multisort($sort_col, $dir, $arr);
    }
    public function leavedata($teamid, Request $request)
    {


        $activesession = Session::find($request->query('sessionid'));

        $userdata = User::where('team', Team::find($teamid)->team_name)->where('active', 1)->where('position', '!=', 'CEO')->orderby('fname', 'asc')->get();


        $result = [];
        $count = 0;
        $tpscore = 0;
        $total = 0;
        foreach ($userdata as $user) {
            $tardy = Hrmsdata::where('user_id', $user->id)->where('type', 2)->where('session_id', $activesession->id)->get();

            $unplan = Hrmsdata::where('user_id', $user->id)->where('type', 1)->where('session_id', $activesession->id)->get();
            $ssum = Leave::where('user_id', $user->id)->where('leave_type', 'Sick Leave')->where('session_id', $activesession->id)->get();
            $esum = Leave::where('user_id', $user->id)->where('leave_type', 'Emergency Annual leave')->where('session_id', $activesession->id)->get();
            $total = $tardy->count() + $unplan->count() +   $ssum->count() > 0 ? $ssum->sum('duration') : 0 + $esum->count() > 0 ? $esum->sum('duration') : 0;
            $stat = $tardy->count() == 0 && $unplan->count() == 0 && $ssum->count() == 0 && $esum->count() == 0 ? 1 : 0;
            $result[$count] = [
                'user' => $user->fname . " " . $user->lname,
                'absent' => $unplan->count(),
                'ssum' => $ssum->count() > 0 ? $ssum->sum('duration') : 0,
                'unplan' => $esum->count() > 0 ? $esum->sum('duration') : 0,
                'tardy' => $tardy->count(),
                'status' => $stat,
                'total' => $total
            ];


            $count++;
        }

        return $result;
    }


    public function failureanalysis($userid, Request $request)
    {

        $activesession = Session::find($request->query('sessionid'));

        $reports = Report::where('reporttype', 'weekly')->where('user_id', $userid)->where('session_id', $activesession->id)->pluck('id');
        $wreports = Weeklyreport::whereIn('report_id', $reports)->whereNotNull('failurereason_id')
            ->join('failuretargets', 'failuretargets.id', '=', 'weeklyreports.failurereason_id')->get();

        $failuretargets = Failuretarget::all();

        $result[0] = $failuretargets;
        $result[1] = $wreports;
        return $wreports;
    }
    public function fill(Request $request)
    {
    }

    public function addWeeklyreport($userid, $rt, $teamid, $attainment, $m, $date)
    {
        Report::create(['user_id' => $userid, 'reportsTo' => $rt, 'team_id' => $teamid, 'reporttype' => 'weekly', 'attainment' => $attainment, 'created_at' => Carbon::parse('2020-' . $m . '-' . $date . ' 12:30:00'), 'updated_at' => Carbon::parse('2020-' . $m . '-' . $date . ' 12:30:00')]);
    }
    public function calcweek($userid)
    {
        $plansthissession = Report::where('reporttype', 'weekly')->where('user_id', $userid)->where('session_id', 9)->get();
        $sum = 0;
        $count = 0;
        foreach ($plansthissession as $ps) {

            $sum += $ps->attainment;
            $count++;
        }
        $average = 0;
        if ($count > 0) {
            $average = round($sum / 13, 2);
        }
        Score::updateOrCreate(
            ['user_id' => $userid, 'session_id' => 9],
            ['WeeklyScore' => $average]
        );
    }


    public function calculateAtt($userid, $sessionid)
    {
        $plansthissession = Plan::where("sessionid", $sessionid)->where('userid', $userid)->select('id')->get();
        $sum = 0;
        $count = 0;




        $objectives = Objective::where('user_id', $userid)->where('session_id', $sessionid)->get();

        $totalobj = 0;
        $ocount = 0;
        foreach ($objectives as $obj) {
            $keyresults = Keyresult::where('objective_id', $obj->id)->get();
            $krattain = 0;
            foreach ($keyresults as $kr) {
                $tasks = Task::where('keyresultid', $kr->id)->where('isMilestone', 1)->get();
                $achieved = 0;
                $countmile  = 0;
                foreach ($tasks as $task) {
                    $plantask = Weeklyplan::where('task_id', $task->id)->first();
                    if ($task->status == 1) {
                        $achieved++;
                    }
                    if ($plantask) {
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
            $alignedobjs = Objective::where('objectives.aligned_to', $obj->id)->where('users.active', 1)->join('users', 'users.id', '=', 'objectives.user_id')->get();






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
                if (User::find($userid)->position == 'CEO') {
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
            $ocount++;
            $parentobj = Objective::find($obj->id)->aligned_to != null ? Objective::find($obj->id)->aligned_to : null;

            // if($parentobj != null){
            //     $userid = Objective::find($parentobj)->user_id;
            //     $this->calculateAtt($userid, $sessionid);
            // }
        }
        $objav = 0;
        if ($ocount > 0) {
            $objav = round($totalobj / $ocount * 100, 2);
        }

        Score::updateOrCreate(
            ['user_id' => $userid, 'session_id' => $sessionid],
            ['OKR_Score' => $objav]
        );
    }



    public function fillenag($userid, $engid)
    {

        $issuedto = $userid;
        $sessionid = Session::where('status', 'Active')->first()->id;


        $formula  = Formula::find(Engagement::find($engid)->formula_id); //formula for the objective

        $score = Engagementcount::where('user_id', $issuedto)->where('session_id', $sessionid)->orderby('id', 'desc')->first(); //user engagement in a session;
        $userscore = Score::where('user_id', $issuedto)->where('session_id', $sessionid)->first();
        $prevval = Engagementcount::where('user_id',   $issuedto)->where('session_id', $sessionid)->where('engagement_id', $engid)->first();
        $actual = 0;
        $initscore = Engagement::where('Perspective', 1)->get()->sum('Weight');

        $initengscore = 0;
        $target = Engagement::find($engid)->Target;
        $weight = Engagement::find($engid)->Weight;

        //if a user score is added to db fetch engagement score
        if ($score) {
            $initscore = $userscore->engagementScore;
        }
        //if it is discipline set initial score to weight of that dicipline
        if (Engagement::find($engid)->Perspective == 1) {
            $initengscore = $weight;
        }
        //if the user has received an engagement before set actual to previous count and set initial score to previous score
        if ($prevval) {
            $actual = $prevval->actual;
            $initengscore = $prevval->engscore;
        }


        //execute formula
        $formulaarray = explode(" ", $formula->formula);
        for ($i = 0; $i < sizeof($formulaarray); $i++) {
            if ($formulaarray[$i] == "actual") {
                $formulaarray[$i] = 1;
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
        } catch (Exception $ex) {
            return redirect()->back()
                ->with('error', 'Error.');
            dd($ex);
        }

        //for excellence
        if (Engagement::find($engid)->Perspective == 0) {
            DB::transaction(
                function () use ($actual, $initengscore, $evaluatedValue, $initscore, $issuedto, $sessionid, $engid) {


                    Score::updateOrCreate(
                        ['user_id' => $issuedto, 'session_id' => $sessionid],
                        ['engagementScore' => $initscore + ($evaluatedValue * 100)]
                    );
                    Engagementcount::updateOrCreate(
                        ['user_id' => $issuedto, 'session_id' => $sessionid, 'engagement_id' => $engid],
                        ['actual' => $actual + 1, 'engscore' => $initengscore + ($evaluatedValue * 100)]
                    );
                }
            );
        }
        //discipline
        elseif (Engagement::find($engid)->Perspective == 1) {
            DB::transaction(
                function () use ($actual, $initengscore, $evaluatedValue, $initscore, $issuedto, $sessionid, $engid) {



                    Score::updateOrCreate(
                        ['user_id' => $issuedto, 'session_id' => $sessionid],
                        ['engagementScore' => $initscore - ($initengscore - ($evaluatedValue * 100))]
                    );
                    Engagementcount::updateOrCreate(
                        ['user_id' => $issuedto, 'session_id' => $sessionid, 'engagement_id' => $engid],
                        ['actual' => $actual + 1, 'engscore' => $evaluatedValue * 100]
                    );
                }
            );
        }
    }
    public function generateIncentive(Request $request)
    {
        DB::transaction(function () use ($request) {
            $id = $request->input('session_id');
            $revenue = 1;
            $kpi = 1;
            $award = 1;
            $cash = 1;
            $eng = 1;
            $ebitdav = 1;
            $weekly = 1;

            $projects = Project::where('status', 0)->where('closedsession', $id)->get();
            $bids = Bid::where('session_id', $id)->get();
            $projectdeliveries = Projectdelivery::where('session_id', $id)->where('status', 1)->get();
            $leaders = User::wherein('team', ['Middle Management', 'Executive'])->where('active', 1)->where('position', '!=', 'CEO')->get();

            $company = Companyinfo::where('session_id', $id)->first();
            $levelmin = DB::table('incentivelevels')->where('type', 0)->orderby('min', 'asc')->first();
            $ebitdalevelmin = DB::table('incentivelevels')->where('type', 1)->orderby('min', 'asc')->first();
            $ebitdalevels = DB::table('incentivelevels')->where('type', 1)->get();
            $levels = DB::table('incentivelevels')->where('type', 0)->orderby('min', 'asc')->get();
            Generatedincentive::updateOrCreate(['session_id' => $id], ['fullcycle' => 0]);
            $gene = Generatedincentive::where('session_id', $id)->first();
            $ebitda = round($company->ebitda_actual / max($company->ebitda_target, 1) * 100, 2);;
            $ofunds = DB::table('incentivefunds')->where('type', 'order')->get();
            $levels = DB::table('incentivelevels')->where('type', 0)->orderby('min', 'asc')->get();
            $Celoxis_data = Celoxis::where('session_id', $id)->get();
            $session_id = $request->input('session_id');
            foreach ($Celoxis_data as  $data) {

                if ($data->custom_schedule_tracker == 'Forex Start' && $data->session_id == $session_id) {
                    $forexend = Celoxis::where(['project_id' => $data->project_id, 'milestone_name' => $data->milestone_name, 'custom_schedule_tracker' => 'Forex End'])->get();
                    foreach ($forexend as $key => $end) {
                        $task_id = $end->task_id;
                        $session_id = $request->input('session_id');
                        $project_id = $end->project_id;
                        $project_name = $end->project_name;
                        $project_amount = $end->project_amount;
                        $task_name = $end->task_name;
                        $task_amount = $end->task_amount;
                        $milestone_name = $end->milestone_name;
                        $milestone_amount = $end->milestone_amount;
                        $custome_milestone_name = $end->custom_milestone_name;
                        $percent_completion = $end->percent_completion;
                        $schedule_tracker = $end->custom_schedule_tracker;

                        $planned_start = $data->planned_start;
                        $planned_finish = $end->planned_finish;
                        $actual_finish = $end->actual_finish;

                        $planned_start_date = new DateTime($planned_start);
                        $panned_finish_date = new DateTime($planned_finish);
                        $actual_finished_date = new DateTime($actual_finish);

                        $interval = date_diff($planned_start_date, $panned_finish_date);

                        $SAC = (int)($interval->format("%a"));

                        $actual_time = date_diff($planned_start_date, $actual_finished_date);
                        $interval = (int)($actual_time->format("%a"));
                        $actual_time = $interval;

                        $actual_finish_quarter = $end->actual_finish_quarter;
                        $earned_schedule = doubleval($SAC) * doubleval($percent_completion / 100);
                        $SPI = number_format(($earned_schedule / $actual_time), 2);
                        $users = $end->users;
                        $position = $end->position;
                        $minimum_spi = $end->minimum_spi;
                        $PPA = $end->PPA;
                        $project_percent_amount = $PPA / 100;
                        $bonus =  $SPI * ($project_percent_amount * $task_amount);
                        OperationIncentive::create(
                            [
                                'task_id' => $task_id,
                                'session_id' => $session_id,
                                'project_id' => $project_id,
                                'project_name' => $project_name,
                                'project_amount' => $project_amount,
                                'task_name' => $task_name,
                                'task_amount' => $task_amount,
                                'custom_schedule_tracker' => $schedule_tracker,
                                'milestone_name' => $milestone_name,
                                'milestone_amount' => $milestone_amount,
                                'custom_milestone_name' => $custome_milestone_name,
                                'percent_completion' => $percent_completion,
                                'planned_start' => $planned_start,
                                'planned_finish' => $planned_finish,
                                'SAC' => $SAC,
                                'actual_finish' => $actual_finish,
                                'actual_time' => $actual_time,
                                'actual_finish_quarter' => $actual_finish_quarter,
                                'earned_schedule' => $earned_schedule,
                                'SPI' => $SPI,
                                'users' => $users,
                                'position' => $position,
                                'minimum_spi' => $minimum_spi,
                                'PPA' => $PPA,
                                'bonus' => $bonus,
                            ]
                        );
                    }
                } elseif ($data->custom_schedule_tracker == 'Sourcing Start' && $data->session_id == $session_id) {
                    $sourcingend = Celoxis::where(['project_id' => $data->project_id, 'milestone_name' => $data->milestone_name, 'custom_schedule_tracker' => 'Sourcing End'])->get();
                    foreach ($sourcingend as $key => $end) {
                        $task_id = $end->task_id;
                        $session_id = $request->input('session_id');
                        $project_id = $end->project_id;
                        $project_name = $end->project_name;
                        $project_amount = $end->project_amount;
                        $task_name = $end->task_name;
                        $task_amount = $end->task_amount;
                        $milestone_name = $end->milestone_name;
                        $milestone_amount = $end->milestone_amount;
                        $custome_milestone_name = $end->custom_milestone_name;
                        $percent_completion = $end->percent_completion;
                        $schedule_tracker = $end->custom_schedule_tracker;

                        $planned_start = $data->planned_start;
                        $planned_finish = $end->planned_finish;
                        $actual_finish = $end->actual_finish;

                        $planned_start_date = new DateTime($planned_start);
                        $panned_finish_date = new DateTime($planned_finish);
                        $actual_finished_date = new DateTime($actual_finish);

                        $interval = date_diff($planned_start_date, $panned_finish_date);

                        $SAC = (int)($interval->format("%a"));

                        $actual_time = date_diff($planned_start_date, $actual_finished_date);
                        $interval = (int)($actual_time->format("%a"));
                        $actual_time = $interval;

                        $actual_finish_quarter = $end->actual_finish_quarter;
                        $earned_schedule = doubleval($SAC) * doubleval($percent_completion / 100);
                        $SPI = number_format(($earned_schedule / $actual_time), 2);
                        $users = $end->users;
                        $position = $end->position;
                        $minimum_spi = $end->minimum_spi;
                        $PPA = $end->PPA;
                        $project_percent_amount = $PPA / 100;
                        $bonus =  $SPI * ($project_percent_amount * $task_amount);
                        OperationIncentive::create(
                            [
                                'task_id' => $task_id,
                                'session_id' => $session_id,
                                'project_id' => $project_id,
                                'project_name' => $project_name,
                                'project_amount' => $project_amount,
                                'task_name' => $task_name,
                                'task_amount' => $task_amount,
                                'custom_schedule_tracker' => $schedule_tracker,
                                'milestone_name' => $milestone_name,
                                'milestone_amount' => $milestone_amount,
                                'custom_milestone_name' => $custome_milestone_name,
                                'percent_completion' => $percent_completion,
                                'planned_start' => $planned_start,
                                'planned_finish' => $planned_finish,
                                'SAC' => $SAC,
                                'actual_finish' => $actual_finish,
                                'actual_time' => $actual_time,
                                'actual_finish_quarter' => $actual_finish_quarter,
                                'earned_schedule' => $earned_schedule,
                                'SPI' => $SPI,
                                'users' => $users,
                                'position' => $position,
                                'minimum_spi' => $minimum_spi,
                                'PPA' => $PPA,
                                'bonus' => $bonus,
                            ]
                        );
                    }
                } elseif ($data->custom_schedule_tracker == 'Logistics Start' && $data->session_id == $session_id) {
                    $deliveryend = Celoxis::where(['project_id' => $data->project_id, 'milestone_name' => $data->milestone_name, 'custom_schedule_tracker' => 'Logistics End'])->get();
                    foreach ($deliveryend as $key => $end) {
                        $task_id = $end->task_id;
                        $session_id = $request->input('session_id');
                        $project_id = $end->project_id;
                        $project_name = $end->project_name;
                        $project_amount = $end->project_amount;
                        $task_name = $end->task_name;
                        $task_amount = $end->task_amount;
                        $milestone_name = $end->milestone_name;
                        $custome_milestone_name = $end->custom_milestone_name;
                        $milestone_amount = $end->milestone_amount;
                        $percent_completion = $end->percent_completion;
                        $schedule_tracker = $end->custom_schedule_tracker;

                        $planned_start = $data->planned_start;
                        $planned_finish = $end->planned_finish;
                        $actual_finish = $end->actual_finish;

                        $planned_start_date = new DateTime($planned_start);
                        $panned_finish_date = new DateTime($planned_finish);
                        $actual_finished_date = new DateTime($actual_finish);

                        $interval = date_diff($planned_start_date, $panned_finish_date);

                        $SAC = (int)($interval->format("%a"));

                        $actual_time = date_diff($planned_start_date, $actual_finished_date);
                        $interval = (int)($actual_time->format("%a"));
                        $actual_time = $interval;

                        $actual_finish_quarter = $end->actual_finish_quarter;
                        $earned_schedule = doubleval($SAC) * doubleval($percent_completion / 100);
                        $SPI = number_format(($earned_schedule / $actual_time), 2);
                        $users = $end->users;
                        $position = $end->position;
                        $minimum_spi = $end->minimum_spi;
                        $PPA = $end->PPA;
                        $project_percent_amount = $PPA / 100;
                        $bonus =  $SPI * ($project_percent_amount * $task_amount);
                        OperationIncentive::create(
                            [
                                'task_id' => $task_id,
                                'session_id' => $session_id,
                                'project_id' => $project_id,
                                'project_name' => $project_name,
                                'project_amount' => $project_amount,
                                'task_name' => $task_name,
                                'task_amount' => $task_amount,
                                'custom_schedule_tracker' => $schedule_tracker,
                                'milestone_name' => $milestone_name,
                                'milestone_amount' => $milestone_amount,
                                'custom_milestone_name' => $custome_milestone_name,
                                'percent_completion' => $percent_completion,
                                'planned_start' => $planned_start,
                                'planned_finish' => $planned_finish,
                                'SAC' => $SAC,
                                'actual_finish' => $actual_finish,
                                'actual_time' => $actual_time,
                                'actual_finish_quarter' => $actual_finish_quarter,
                                'earned_schedule' => $earned_schedule,
                                'SPI' => $SPI,
                                'users' => $users,
                                'position' => $position,
                                'minimum_spi' => $minimum_spi,
                                'PPA' => $PPA,
                                'bonus' => $bonus,
                            ]
                        );
                    }
                } elseif ($data->custom_schedule_tracker == 'Implementation Start' &&  $data->session_id == $session_id) {
                    $implementationend = Celoxis::where(['project_id' => $data->project_id, 'milestone_name' => $data->milestone_name, 'custom_schedule_tracker' => 'Implementation End'])->get();
                    foreach ($implementationend as $key => $end) {
                        $task_id = $end->task_id;
                        $session_id = $request->input('session_id');
                        $project_id = $end->project_id;
                        $project_name = $end->project_name;
                        $project_amount = $end->project_amount;
                        $task_name = $end->task_name;
                        $task_amount = $end->task_amount;
                        $milestone_name = $end->milestone_name;
                        $custome_milestone_name = $end->custom_milestone_name;
                        $milestone_amount = $end->milestone_amount;
                        $percent_completion = $end->percent_completion;
                        $schedule_tracker = $end->custom_schedule_tracker;

                        $planned_start = $data->planned_start;
                        $planned_finish = $end->planned_finish;
                        $actual_finish = $end->actual_finish;

                        $planned_start_date = new DateTime($planned_start);
                        $panned_finish_date = new DateTime($planned_finish);
                        $actual_finished_date = new DateTime($actual_finish);

                        $interval = date_diff($planned_start_date, $panned_finish_date);

                        $SAC = (int)($interval->format("%a"));

                        $actual_time = date_diff($planned_start_date, $actual_finished_date);
                        $interval = (int)($actual_time->format("%a"));
                        $actual_time = $interval;

                        $actual_finish_quarter = $end->actual_finish_quarter;
                        $earned_schedule = doubleval($SAC) * doubleval($percent_completion / 100);
                        $SPI = number_format(($earned_schedule / $actual_time), 2);
                        $users = $end->users;
                        $position = $end->position;
                        $minimum_spi = $end->minimum_spi;
                        $PPA = $end->PPA;
                        $project_percent_amount = $PPA / 100;
                        $bonus =  $SPI * ($project_percent_amount * $task_amount);
                        OperationIncentive::create(
                            [
                                'task_id' => $task_id,
                                'session_id' => $session_id,
                                'project_id' => $project_id,
                                'project_name' => $project_name,
                                'project_amount' => $project_amount,
                                'task_name' => $task_name,
                                'task_amount' => $task_amount,
                                'custom_schedule_tracker' => $schedule_tracker,
                                'milestone_name' => $milestone_name,
                                'milestone_amount' => $milestone_amount,
                                'custom_milestone_name' => $custome_milestone_name,
                                'percent_completion' => $percent_completion,
                                'planned_start' => $planned_start,
                                'planned_finish' => $planned_finish,
                                'SAC' => $SAC,
                                'actual_finish' => $actual_finish,
                                'actual_time' => $actual_time,
                                'actual_finish_quarter' => $actual_finish_quarter,
                                'earned_schedule' => $earned_schedule,
                                'SPI' => $SPI,
                                'users' => $users,
                                'position' => $position,
                                'minimum_spi' => $minimum_spi,
                                'PPA' => $PPA,
                                'bonus' => $bonus,
                            ]
                        );
                    }
                } elseif ($data->custom_schedule_tracker == 'Project Start' &&  $data->session_id == $session_id) {
                    $projectend = Celoxis::where(['project_id' => $data->project_id, 'milestone_name' => $data->milestone_name, 'custom_schedule_tracker' => 'Project End'])->get();
                    foreach ($projectend as $key => $end) {
                        $task_id = $end->task_id;
                        $session_id = $request->input('session_id');
                        $project_id = $end->project_id;
                        $project_name = $end->project_name;
                        $project_amount = $end->project_amount;
                        $task_name = $end->task_name;
                        $task_amount = $end->task_amount;
                        $milestone_name = $end->milestone_name;
                        $milestone_amount = $end->milestone_amount;
                        $percent_completion = $end->percent_completion;
                        $custome_milestone_name = $end->custom_milestone_name;
                        $schedule_tracker = $end->custom_schedule_tracker;

                        $planned_start = $data->planned_start;
                        $planned_finish = $end->planned_finish;
                        $actual_finish = $end->actual_finish;

                        $planned_start_date = new DateTime($planned_start);
                        $panned_finish_date = new DateTime($planned_finish);
                        $actual_finished_date = new DateTime($actual_finish);

                        $interval = date_diff($planned_start_date, $panned_finish_date);

                        $SAC = (int)($interval->format("%a"));

                        $actual_time = date_diff($planned_start_date, $actual_finished_date);
                        $interval = (int)($actual_time->format("%a"));
                        $actual_time = $interval;

                        $actual_finish_quarter = $end->actual_finish_quarter;
                        $earned_schedule = doubleval($SAC) * doubleval($percent_completion / 100);
                        $SPI = number_format(($earned_schedule / $actual_time), 2);
                        $users = $end->users;
                        $position = $end->position;
                        $minimum_spi = $end->minimum_spi;
                        $PPA = $end->PPA;
                        $project_percent_amount = $PPA / 100;
                        $bonus =  $SPI * ($project_percent_amount * $task_amount);
                        OperationIncentive::create(
                            [
                                'task_id' => $task_id,
                                'session_id' => $session_id,
                                'project_id' => $project_id,
                                'project_name' => $project_name,
                                'project_amount' => $project_amount,
                                'task_name' => $task_name,
                                'task_amount' => $task_amount,
                                'custom_schedule_tracker' => $schedule_tracker,
                                'milestone_name' => $milestone_name,
                                'milestone_amount' => $milestone_amount,
                                'custom_milestone_name' => $custome_milestone_name,
                                'percent_completion' => $percent_completion,
                                'planned_start' => $planned_start,
                                'planned_finish' => $planned_finish,
                                'SAC' => $SAC,
                                'actual_finish' => $actual_finish,
                                'actual_time' => $actual_time,
                                'actual_finish_quarter' => $actual_finish_quarter,
                                'earned_schedule' => $earned_schedule,
                                'SPI' => $SPI,
                                'users' => $users,
                                'position' => $position,
                                'minimum_spi' => $minimum_spi,
                                'PPA' => $PPA,
                                'bonus' => $bonus,
                            ]
                        );
                    }
                } elseif ($data->custom_schedule_tracker == 'Finance Start' &&  $data->session_id == $session_id) {
                    $financeend = Celoxis::where(['project_id' => $data->project_id, 'milestone_name' => $data->milestone_name, 'custom_schedule_tracker' => 'Finance End'])->get();
                    foreach ($financeend as $key => $end) {
                        $task_id = $end->task_id;
                        $session_id = $request->input('session_id');
                        $project_id = $end->project_id;
                        $project_name = $end->project_name;
                        $project_amount = $end->project_amount;
                        $task_name = $end->task_name;
                        $task_amount = $end->task_amount;
                        $milestone_name = $end->milestone_name;
                        $milestone_amount = $end->milestone_amount;
                        $custome_milestone_name = $end->custom_milestone_name;
                        $percent_completion = $end->percent_completion;
                        $schedule_tracker = $end->custom_schedule_tracker;

                        $planned_start = $data->planned_start;
                        $planned_finish = $end->planned_finish;
                        $actual_finish = $end->actual_finish;

                        $planned_start_date = new DateTime($planned_start);
                        $panned_finish_date = new DateTime($planned_finish);
                        $actual_finished_date = new DateTime($actual_finish);

                        $interval = date_diff($planned_start_date, $panned_finish_date);

                        $SAC = (int)($interval->format("%a"));

                        $actual_time = date_diff($planned_start_date, $actual_finished_date);
                        $interval = (int)($actual_time->format("%a"));
                        $actual_time = $interval;

                        $actual_finish_quarter = $end->actual_finish_quarter;
                        $earned_schedule = doubleval($SAC) * doubleval($percent_completion / 100);
                        $SPI = number_format(($earned_schedule / $actual_time), 2);
                        $users = $end->users;
                        $position = $end->position;
                        $minimum_spi = $end->minimum_spi;
                        $PPA = $end->PPA;
                        $project_percent_amount = $PPA / 100;
                        $bonus =  $SPI * ($project_percent_amount * $task_amount);
                        OperationIncentive::create(
                            [
                                'task_id' => $task_id,
                                'session_id' => $session_id,
                                'project_name' => $project_name,
                                'project_amount' => $project_amount,
                                'task_name' => $task_name,
                                'task_amount' => $task_amount,
                                'custom_schedule_tracker' => $schedule_tracker,
                                'milestone_name' => $milestone_name,
                                'milestone_amount' => $milestone_amount,
                                'custom_milestone_name' => $custome_milestone_name,
                                'percent_completion' => $percent_completion,
                                'percent_completion' => $percent_completion,
                                'planned_start' => $planned_start,
                                'planned_finish' => $planned_finish,
                                'SAC' => $SAC,
                                'actual_finish' => $actual_finish,
                                'actual_time' => $actual_time,
                                'actual_finish_quarter' => $actual_finish_quarter,
                                'earned_schedule' => $earned_schedule,
                                'SPI' => $SPI,
                                'users' => $users,
                                'position' => $position,
                                'minimum_spi' => $minimum_spi,
                                'PPA' => $PPA,
                                'bonus' => $bonus,
                            ]
                        );
                    }
                }
            }



            foreach ($projects as $project) {
                $funds = DB::table('incentivefunds')->where('type', 'project')->where('amountmin', '<=', $project->amount)->where('amountmax', '>', $project->amount)->first();
                $projectmembers = Projectmember::where('project_id', $project->id)->wherein('projectmembers.position', ['FE', 'TL', 'PM'])->where('users.active', 1)->join('users', 'users.id', '=', 'projectmembers.user_id')->where('scores.session_id', 9)->join('scores', 'scores.user_id', 'users.id')->select('projectmembers.id', 'projectmembers.user_id', 'level')->get();
                foreach ($projectmembers as $pms) {
                    $dr = new Detailedreport;
                    $dr->incentive_id = $gene->id;
                    $dr->project_id = $project->id;
                    $dr->user_id = $pms->user_id;
                    $dr->type = 0;
                    $user = User::where('active', 1)->where('user_id', $pms->user_id)->where('scores.session_id', $id)->join('scores', 'scores.user_id', 'users.id')->first();
                    $min = $user ? min($user->engagementScore, $user->KPI_Score, $user->WeeklyScore, round($company->award_actual / max($company->award_target, 1) * 100, 2), round($company->cash_actual / max($company->cash_target, 1) * 100, 2), round($company->revenue_actual / max($company->revenue_target, 1) * 100, 2)) : 0;
                    $message = "NA";
                    $bonus = "No Bonus";

                    if ($min >= $levelmin->min) {
                        foreach ($levels as $level) {
                            if ($level->max == 0 && $min >= $level->min && $ebitda >= $level->ebitdamin) {
                                $message = $level->level;
                            } else if ($min >= $level->min && $min <= $level->max && $ebitda >= $level->ebitdamin && $ebitda <= $level->ebitdamax) {
                                $message = $level->level;
                            }
                        }
                    }
                    if ($funds && $pms->level != 1 && $message != "NA") {
                        $level = DB::table('incentivelevels')->where('type', 0)->where('level', $message)->first();
                        $bonus = DB::table('individualfunds')->where('level_id', $level->id)->where('type', 1)->first();
                        if ($pms->level == 2) {
                            $dr->bonus = $bonus->partialamount;
                        } else if ($pms->level == 3) {
                            $dr->bonus = $bonus->fullamount;
                        }
                    }

                    $plevel = "Participant";
                    if ($pms->level == 2) {
                        $plevel = "Half cycle Participant";
                    } else if ($pms->level == 3) {
                        $plevel = "Full cycle Participant";
                    }
                    $dr->level = $plevel;
                    $dr->score = $message;
                    $dr->save();
                    // $dr->bid_id = $gene->id;
                    // $dr->projectdelivery_id = $gene->id;
                }
            }

            foreach ($bids as $project) {
                $funds = DB::table('incentivefunds')->where('type', 'project')->where('amountmin', '<=', $project->bid_amount)->where('amountmax', '>', $project->bid_amount)->first();
                $projectmembers = Bidmember::where('bid_id', $project->id)->wherein('bidmembers.position', ['PSE', 'SA', 'AE'])->where('users.active', 1)->join('users', 'users.id', '=', 'bidmembers.user_id')->where('scores.session_id', 9)->join('scores', 'scores.user_id', 'users.id')->select('bidmembers.id', 'bidmembers.user_id', 'level')->get();
                foreach ($projectmembers as $pms) {
                    $dr = new Detailedreport;
                    $dr->incentive_id = $gene->id;
                    $dr->bid_id = $project->id;
                    $dr->user_id = $pms->user_id;
                    $dr->type = 1;

                    $user = User::where('active', 1)->where('user_id', $pms->user_id)->where('scores.session_id', $id)->join('scores', 'scores.user_id', 'users.id')->first();
                    $min = $user ? min($user->engagementScore, $user->KPI_Score, $user->WeeklyScore, round($company->award_actual / max($company->award_target, 1) * 100, 2), round($company->cash_actual / max($company->cash_target, 1) * 100, 2), round($company->revenue_actual / max($company->revenue_target, 1) * 100, 2)) : 0;
                    $message = "NA";
                    $bonus = "No Bonus";

                    if ($min >= $levelmin->min) {
                        foreach ($levels as $level) {
                            if ($level->max == 0 && $min >= $level->min && $ebitda >= $level->ebitdamin) {
                                $message = $level->level;
                            } else if ($min >= $level->min && $min <= $level->max && $ebitda >= $level->ebitdamin && $ebitda <= $level->ebitdamax) {
                                $message = $level->level;
                            }
                        }
                    }
                    if ($funds && $pms->level != 1 && $message != "NA") {
                        $level = DB::table('incentivelevels')->where('type', 0)->where('level', $message)->first();
                        $bonus = DB::table('individualfunds')->where('level_id', $level->id)->where('type', 1)->first();
                        if ($pms->level == 2) {
                            $dr->bonus = $bonus->partialamount;
                        } else if ($pms->level == 3) {
                            $dr->bonus = $bonus->fullamount;
                        }
                    }

                    $plevel = "Participant";
                    if ($pms->level == 2) {
                        $plevel = "Half cycle Participant";
                    } else if ($pms->level == 3) {
                        $plevel = "Full cycle Participant";
                    }
                    $dr->level = $plevel;
                    $dr->score = $message;
                    $dr->save();
                    // $dr->bid_id = $gene->id;
                    // $dr->projectdelivery_id = $gene->id;
                }
            }
            foreach ($projectdeliveries as $project) {
                $funds = DB::table('incentivefunds')->where('type', 'order')->where('amountmin', '<=', $project->amount)->where('amountmax', '>', $project->amount)->first();

                $projectmembers = Projectmember::where('project_id', $project->project_id)->where('users.active', 1)->wherein('projectmembers.position', ['OFE', 'PFO', 'FTL'])->join('users', 'users.id', '=', 'projectmembers.user_id')->where('scores.session_id', 9)->join('scores', 'scores.user_id', 'users.id')->select('projectmembers.id', 'projectmembers.user_id', 'level')->get();
                foreach ($projectmembers as $pms) {
                    $dr = new Detailedreport;
                    $dr->incentive_id = $gene->id;
                    $dr->projectdelivery_id = $project->id;
                    $dr->user_id = $pms->user_id;
                    $dr->type = 2;

                    $user = User::where('active', 1)->where('user_id', $pms->user_id)->where('scores.session_id', $id)->join('scores', 'scores.user_id', 'users.id')->first();
                    $min = $user ? min($user->engagementScore, $user->KPI_Score, $user->WeeklyScore, round($company->award_actual / max($company->award_target, 1) * 100, 2), round($company->cash_actual / max($company->cash_target, 1) * 100, 2), round($company->revenue_actual / max($company->revenue_target, 1) * 100, 2)) : 0;
                    $message = "NA";
                    $bonus = "No Bonus";

                    if ($min >= $levelmin->min) {
                        foreach ($levels as $level) {
                            if ($level->max == 0 && $min >= $level->min && $ebitda >= $level->ebitdamin) {
                                $message = $level->level;
                            } else if ($min >= $level->min && $min <= $level->max && $ebitda >= $level->ebitdamin && $ebitda <= $level->ebitdamax) {
                                $message = $level->level;
                            }
                        }
                    }
                    if ($funds && $pms->level != 1 && $message != "NA") {
                        $level = DB::table('incentivelevels')->where('type', 0)->where('level', $message)->first();
                        $bonus = DB::table('individualfunds')->where('level_id', $level->id)->where('type', 1)->first();
                        if ($pms->level == 2) {
                            $dr->bonus = $bonus->partialamount;
                        } else if ($pms->level == 3) {
                            $dr->bonus = $bonus->fullamount;
                        }
                    }

                    $plevel = "Participant";
                    if ($pms->level == 2) {
                        $plevel = "Half cycle Participant";
                    } else if ($pms->level == 3) {
                        $plevel = "Full cycle Participant";
                    }
                    $dr->level = $plevel;
                    $dr->score = $message;
                    $dr->save();
                    // $dr->bid_id = $gene->id;
                    // $dr->projectdelivery_id = $gene->id;
                }
            }
            foreach ($leaders as $usser) {



                $dr = new Detailedreport;
                $dr->incentive_id = $gene->id;

                $dr->user_id = $usser->id;
                $dr->type = 3;


                $user = User::where('active', 1)->where('user_id', $usser->id)->where('scores.session_id', $id)->join('scores', 'scores.user_id', 'users.id')->first();

                $min = $user ? min(($eng == "1" ? $user->engagementScore : 100), ($kpi == "1" ? $user->KPI_Score : 100), ($weekly == "1" ? $user->WeeklyScore : 100), ($award == "1" ? round($company->award_actual / max($company->award_target, 1) * 100, 2) : 100), ($cash == "1" ? round($company->cash_actual / max($company->cash_target, 1) * 100, 2) : 100), ($revenue == "1" ? round($company->revenue_actual / max($company->revenue_target, 1) * 100, 2) : 100)) : 0;

                if ($user && $eng == "1" && $kpi == "1" && $weekly == "1") {
                    $min = round(($user->engagementScore + $user->KPI_Score + $user->WeeklyScore) / 3, 2);
                }
                $message = "NA";
                $bonus = "No Bonus";
                $ebitda = round($company->ebitda_actual / max($company->ebitda_target, 1) * 100, 2);
                if ($min >= $levelmin->min) {
                    foreach ($levels as $level) {
                        if ($level->max == 0 && $min >= $level->min) {
                            $message = $level->level;
                            $bonus = DB::table('individualfunds')->where('level_id', $level->id)->where('type', 2)->first()->fullamount;
                        } else if ($min >= $level->min && $min <= $level->max) {
                            $message = $level->level;
                            $bonus = DB::table('individualfunds')->where('level_id', $level->id)->where('type', 2)->first()->fullamount;
                        }
                    }
                }
                $dr->bonus = $bonus;

                $dr->score = $message;
                $dr->save();
            }
            return redirect()->back()
                ->with('success', 'Added Successfully.');
        });

        return redirect()->back()
            ->with('error', 'Report generation failed');
    }
    function exportincentive($id)
    {
        return Excel::download(new IncentivExport($id), 'incentives.xlsx');
    }
    function getformula($incentiveid, $type)
    {
        $d = Detailedreport::where('incentive_id', $incentiveid)->where('type', $type)->first();
        $formula = $d->formula;
        return $formula;
    }
    function editgincentive(Request $request)
    {
        $input  = $request->all();

        $revenue = $input['rmybtn'];
        $kpi = $input['kmybtn'];
        $award = $input['amybtn'];
        $cash = $input['cmybtn'];
        $eng = $input['emybtn'];
        $ebitdav = $input['ebmybtn'];
        $weekly = $input['wmybtn'];

        $drs =  Detailedreport::where('incentive_id', $input['incentive_id'])->where('type', $input['type'])->delete();



        DB::transaction(function () use ($request, $input, $ebitdav, $revenue, $kpi, $award, $cash, $eng, $weekly) {
            $id = Generatedincentive::find($input['incentive_id'])->session_id;

            $projects = Project::where('status', 0)->where('closedsession', $id)->get();

            $bids = Bid::where('session_id', $id)->get();
            $projectdeliveries = Projectdelivery::where('session_id', $id)->where('status', 1)->get();
            $leaders = User::wherein('team', ['Middle Management', 'Executive'])->where('active', 1)->where('position', '!=', 'CEO')->get();

            $company = Companyinfo::where('session_id', $id)->first();
            $levelmin = DB::table('incentivelevels')->where('type', 0)->orderby('min', 'asc')->first();
            $ebitdalevelmin = DB::table('incentivelevels')->where('type', 1)->orderby('min', 'asc')->first();
            $ebitdalevels = DB::table('incentivelevels')->where('type', 1)->get();
            $levels = DB::table('incentivelevels')->where('type', 0)->orderby('min', 'asc')->get();

            $gene =  Generatedincentive::find($input['incentive_id']);
            $ebitda =  $ebitdav == "1" ? round($company->ebitda_actual / max($company->ebitda_target, 1) * 100, 2) : 100;

            $ofunds = DB::table('incentivefunds')->where('type', 'order')->get();

            if ($input['type'] == 0) {

                foreach ($projects as $project) {


                    $funds = DB::table('incentivefunds')->where('type', 'project')->where('amountmin', '<=', $project->amount)->where('amountmax', '>', $project->amount)->first();
                    $projectmembers = Projectmember::where('project_id', $project->id)->wherein('projectmembers.position', ['FE', 'TL', 'PM'])->where('users.active', 1)->join('users', 'users.id', '=', 'projectmembers.user_id')->where('scores.session_id', 9)->join('scores', 'scores.user_id', 'users.id')->select('projectmembers.id', 'projectmembers.user_id', 'level')->get();
                    foreach ($projectmembers as $pms) {

                        $dr = new Detailedreport;
                        $dr->incentive_id = $gene->id;
                        $dr->project_id = $project->id;

                        $dr->type = 0;
                        $formula = ($eng == "1" ? "E," : "") . ($kpi == "1" ? "K," : "") . ($award == "1" ? "A," : "") . ($cash == "1" ? "C," : "") . ($ebitdav == "1" ? "Eb," : "") . ($weekly == "1" ? "W," : "") . ($revenue == "1" ? "R" : "");
                        $formula =  rtrim($formula, ",");

                        $dr->formula = $formula;
                        $user = User::where('active', 1)->where('user_id', $pms->user_id)->where('scores.session_id', $id)->join('scores', 'scores.user_id', 'users.id')->first();
                        $engscore = $user->engagementScore;
                        $kpiscore = $user->project_kpi_score;
                        $weeklyscore = $user->WeeklyScore;
                        if ($project->projectstart != null && $project->projectstart != $id) {

                            $engscore = round(Score::where('user_id', $pms->user_id)->whereBetween('session_id', [$project->projectstart, $id])->avg('engagementScore'), 2);
                            $kpiscore = round(Score::where('user_id', $pms->user_id)->whereBetween('session_id', [$project->projectstart, $id])->avg('project_kpi_score'), 2);
                            $weeklyscore = round(Score::where('user_id', $pms->user_id)->whereBetween('session_id', [$project->projectstart, $id])->avg('WeeklyScore'), 2);
                        }
                        $dr->user_id = $pms->user_id;
                        $dr->engscore = $engscore;
                        $dr->kpiscore = $kpiscore;
                        $dr->weeklyscore = $weeklyscore;
                        $min = $user ? min(($eng == "1" ? $engscore : 100), ($kpi == "1" ? $kpiscore : 100), ($weekly == "1" ? $weeklyscore : 100), ($award == "1" ? round($company->award_actual / max($company->award_target, 1) * 100, 2) : 100), ($cash == "1" ? round($company->cash_actual / max($company->cash_target, 1) * 100, 2) : 100), ($revenue == "1" ? round($company->revenue_actual / max($company->revenue_target, 1) * 100, 2) : 100)) : 0;
                        if ($user && $eng == "1" && $kpi == "1" && $weekly == "1") {
                            $min = round(($engscore + $kpiscore + $weeklyscore) / 3, 2);
                        }
                        $message = "NA";
                        $bonus = "No Bonus";

                        if ($min >= $levelmin->min) {
                            foreach ($levels as $level) {
                                if ($level->max == 0 && $min >= $level->min && ($ebitda == 100 || $ebitda >= $level->ebitdamin)) {
                                    $message = $level->level;
                                } else if ($min >= $level->min && $min < $level->max && ($ebitda == 100 || $ebitda >= $level->ebitdamin && $ebitda <= $level->ebitdamax)) {
                                    $message = $level->level;
                                }
                            }
                        }
                        if ($funds && $pms->level != 1 && $message != "NA") {
                            $level = DB::table('incentivelevels')->where('type', 0)->where('level', $message)->first();
                            $bonus = DB::table('individualfunds')->where('level_id', $level->id)->where('type', 1)->where('levelname', $funds->id)->first();
                            if ($pms->level == 2) {
                                $dr->bonus = $bonus->partialamount;
                            } else if ($pms->level == 3) {
                                $dr->bonus = $bonus->fullamount;
                            }
                        }

                        $plevel = "Participant";
                        if ($pms->level == 2) {
                            $plevel = "Half cycle Participant";
                        } else if ($pms->level == 3) {
                            $plevel = "Full cycle Participant";
                        }
                        $dr->level = $plevel;
                        $dr->score = $message;
                        $dr->save();
                        // $dr->bid_id = $gene->id;
                        // $dr->projectdelivery_id = $gene->id;
                    }
                }
            } else if ($input['type'] == 1) {
                foreach ($bids as $project) {
                    $funds = DB::table('incentivefunds')->where('type', 'project')->where('amountmin', '<=', $project->bid_amount)->where('amountmax', '>', $project->bid_amount)->first();
                    $projectmembers = Bidmember::where('bid_id', $project->id)->wherein('bidmembers.position', ['PSE', 'SA', 'AE'])->where('users.active', 1)->join('users', 'users.id', '=', 'bidmembers.user_id')->where('scores.session_id', 9)->join('scores', 'scores.user_id', 'users.id')->select('bidmembers.id', 'bidmembers.user_id', 'level')->get();
                    foreach ($projectmembers as $pms) {
                        $dr = new Detailedreport;
                        $dr->incentive_id = $gene->id;
                        $dr->bid_id = $project->id;
                        $dr->user_id = $pms->user_id;
                        $dr->type = 1;
                        $formula = ($eng == "1" ? "E," : "") . ($kpi == "1" ? "K," : "") . ($award == "1" ? "A," : "") . ($cash == "1" ? "C," : "") . ($ebitdav == "1" ? "Eb," : "") . ($weekly == "1" ? "W," : "") . ($revenue == "1" ? "R" : "");
                        $formula =  rtrim($formula, ",");
                        $dr->formula = $formula;

                        $user = User::where('active', 1)->where('user_id', $pms->user_id)->where('scores.session_id', $id)->join('scores', 'scores.user_id', 'users.id')->first();
                        $min = $user ? min(($eng == "1" ? $user->engagementScore : 100), ($kpi == "1" ? $user->sales_kpi_score : 100), ($weekly == "1" ? $user->WeeklyScore : 100), ($award == "1" ? round($company->award_actual / max($company->award_target, 1) * 100, 2) : 100), ($cash == "1" ? round($company->cash_actual / max($company->cash_target, 1) * 100, 2) : 100), ($revenue == "1" ? round($company->revenue_actual / max($company->revenue_target, 1) * 100, 2) : 100)) : 0;
                        if ($user && $eng == "1" && $kpi == "1" && $weekly == "1") {
                            $min = round(($user->engagementScore + $user->sales_kpi_score + $user->WeeklyScore) / 3, 2);
                        }
                        $dr->engscore = $user->engagementScore;
                        $dr->kpiscore = $user->sales_kpi_score;
                        $dr->weeklyscore = $user->WeeklyScore;
                        $message = "NA";
                        $bonus = "No Bonus";

                        if ($min >= $levelmin->min) {
                            foreach ($levels as $level) {
                                if ($level->max == 0 && $min >= $level->min && ($ebitda == 100 || $ebitda >= $level->ebitdamin)) {
                                    $message = $level->level;
                                } else if ($min >= $level->min && $min < $level->max && ($ebitda == 100 || $ebitda >= $level->ebitdamin && $ebitda <= $level->ebitdamax)) {
                                    $message = $level->level;
                                }
                            }
                        }
                        if ($funds && $pms->level != 1 && $message != "NA") {
                            $level = DB::table('incentivelevels')->where('type', 0)->where('level', $message)->first();
                            $bonus = DB::table('individualfunds')->where('level_id', $level->id)->where('type', 1)->where('levelname', $funds->id)->first();
                            if ($pms->level == 2) {
                                $dr->bonus = $bonus->partialamount;
                            } else if ($pms->level == 3) {
                                $dr->bonus = $bonus->fullamount;
                            }
                        }

                        $plevel = "Participant";
                        if ($pms->level == 2) {
                            $plevel = "Half cycle Participant";
                        } else if ($pms->level == 3) {
                            $plevel = "Full cycle Participant";
                        }
                        $dr->level = $plevel;
                        $dr->score = $message;
                        $dr->save();
                        // $dr->bid_id = $gene->id;
                        // $dr->projectdelivery_id = $gene->id;
                    }
                }
            } else if ($input['type'] == 2) {
                foreach ($projectdeliveries as $project) {
                    $funds = DB::table('incentivefunds')->where('type', 'order')->where('amountmin', '<=', $project->amount)->where('amountmax', '>', $project->amount)->first();

                    $projectmembers = Projectmember::where('project_id', $project->project_id)->where('users.active', 1)->wherein('projectmembers.position', ['OFE', 'PFO', 'FTL'])->join('users', 'users.id', '=', 'projectmembers.user_id')->where('scores.session_id', 9)->join('scores', 'scores.user_id', 'users.id')->select('projectmembers.id', 'projectmembers.user_id', 'level')->get();
                    foreach ($projectmembers as $pms) {
                        $dr = new Detailedreport;
                        $dr->incentive_id = $gene->id;
                        $dr->projectdelivery_id = $project->id;
                        $dr->user_id = $pms->user_id;
                        $dr->type = 2;

                        $formula = ($eng == "1" ? "E," : "") . ($kpi == "1" ? "K," : "") . ($award == "1" ? "A," : "") . ($cash == "1" ? "C," : "") . ($ebitdav == "1" ? "Eb," : "") . ($weekly == "1" ? "W," : "") . ($revenue == "1" ? "R" : "");
                        $formula =  rtrim($formula, ",");
                        $dr->formula = $formula;
                        $user = User::where('active', 1)->where('user_id', $pms->user_id)->where('scores.session_id', $id)->join('scores', 'scores.user_id', 'users.id')->first();
                        $min = $user ? min(($eng == "1" ? $user->engagementScore : 100), ($kpi == "1" ? $user->order_kpi_score : 100), ($weekly == "1" ? $user->WeeklyScore : 100), ($award == "1" ? round($company->award_actual / max($company->award_target, 1) * 100, 2) : 100), ($cash == "1" ? round($company->cash_actual / max($company->cash_target, 1) * 100, 2) : 100), ($revenue == "1" ? round($company->revenue_actual / max($company->revenue_target, 1) * 100, 2) : 100)) : 0;

                        if ($user && $eng == "1" && $kpi == "1" && $weekly == "1") {
                            $min = round(($user->engagementScore + $user->order_kpi_score + $user->WeeklyScore) / 3, 2);
                        }
                        $dr->engscore = $user->engagementScore;
                        $dr->kpiscore = $user->order_kpi_score;
                        $dr->weeklyscore = $user->WeeklyScore;
                        $message = "NA";
                        $bonus = "No Bonus";

                        if ($min >= $levelmin->min) {
                            foreach ($levels as $level) {
                                if ($level->max == 0 && $min >= $level->min && $ebitda >= $level->ebitdamin) {
                                    $message = $level->level;
                                } else if ($min >= $level->min && $min < $level->max &&  ($ebitda == 100 || $ebitda >= $level->ebitdamin && $ebitda <= $level->ebitdamax)) {
                                    $message = $level->level;
                                }
                            }
                        }
                        if ($funds && $pms->level != 1 && $message != "NA") {
                            $level = DB::table('incentivelevels')->where('type', 0)->where('level', $message)->first();

                            $bonus = DB::table('individualfunds')->where('level_id', $level->id)->where('type', 1)->where('levelname', ($funds->id - 7))->first();
                            if ($pms->level == 2) {
                                $dr->bonus = $bonus->partialamount;
                            } else if ($pms->level == 3) {
                                $dr->bonus = $bonus->fullamount;
                            }
                        }

                        $plevel = "Participant";
                        if ($pms->level == 2) {
                            $plevel = "Half cycle Participant";
                        } else if ($pms->level == 3) {
                            $plevel = "Full cycle Participant";
                        }
                        $dr->level = $plevel;
                        $dr->score = $message;
                        $dr->save();
                        // $dr->bid_id = $gene->id;
                        // $dr->projectdelivery_id = $gene->id;
                    }
                }
            } else if ($input['type'] == 3) {
                foreach ($leaders as $usser) {



                    $dr = new Detailedreport;
                    $dr->incentive_id = $gene->id;

                    $dr->user_id = $usser->id;
                    $dr->type = 3;
                    $formula = ($eng == "1" ? "E," : "") . ($kpi == "1" ? "K," : "") . ($award == "1" ? "A," : "") . ($cash == "1" ? "C," : "") . ($ebitdav == "1" ? "Eb," : "") . ($weekly == "1" ? "W," : "") . ($revenue == "1" ? "R" : "");
                    $formula =  rtrim($formula, ",");
                    $dr->formula = $formula;

                    $user = User::where('active', 1)->where('user_id', $usser->id)->where('scores.session_id', $id)->join('scores', 'scores.user_id', 'users.id')->first();
                    $min = $user ? min(($eng == "1" ? $user->engagementScore : 100), ($kpi == "1" ? $user->leadership_kpi_score : 100), ($weekly == "1" ? $user->WeeklyScore : 100), ($award == "1" ? round($company->award_actual / max($company->award_target, 1) * 100, 2) : 100), ($cash == "1" ? round($company->cash_actual / max($company->cash_target, 1) * 100, 2) : 100), ($revenue == "1" ? round($company->revenue_actual / max($company->revenue_target, 1) * 100, 2) : 100)) : 0;

                    if ($user && $eng == "1" && $kpi == "1" && $weekly == "1") {
                        $min = round(($user->engagementScore + $user->leadership_kpi_score + $user->WeeklyScore) / 3, 2);
                    }
                    $dr->engscore = $user->engagementScore;
                    $dr->kpiscore = $user->leadership_kpi_score;
                    $dr->weeklyscore = $user->WeeklyScore;

                    $message = "NA";
                    $bonus = "No Bonus";
                    $ebitda = round($company->ebitda_actual / max($company->ebitda_target, 1) * 100, 2);
                    if ($min >= $levelmin->min) {
                        foreach ($levels as $level) {
                            if ($level->max == 0 && $min >= $level->min) {
                                $message = $level->level;
                                $bonus = DB::table('individualfunds')->where('level_id', $level->id)->where('type', 2)->first()->fullamount;
                            } else if ($min >= $level->min && $min < $level->max) {
                                $message = $level->level;
                                $bonus = DB::table('individualfunds')->where('level_id', $level->id)->where('type', 2)->first()->fullamount;
                            }
                        }
                    }
                    $dr->bonus = $bonus;

                    $dr->score = $message;
                    $dr->save();
                }
            }
            return redirect()->back()
                ->with('success', 'Added Successfully.');
        });
        return redirect()->back()
            ->with('error', 'Error.');
    }
    public function issueenagement($input, $request)
    {

        $issuedto = $request->has('plan_id') ? (Report::find($input['plan_id'])->user_id) : $input['user_id'];
        $sessionid = $request->has('plan_id') ? Plan::find(Report::find($input['plan_id'])->plan_id)->sessionid : Session::where('status', 'Active')->first()->id;


        $notifiable = $issuedto;

        DB::transaction(
            function () use ($input, $issuedto, $sessionid, $request) {
                try {
                    //for excellence
                    if ($input['Perspective'] == 0) {

                        $engagement = new FillEngagement;
                        $engagement->Reason = $input['Reason'];
                        $engagement->Description = Engagement::find($input['objective'])->Objective;
                        $engagement->CC = $request->has('cc') &&  $input['cc'] != 0 ? $input['cc'] : 86;
                        if ($request->has('plan_id')) {
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
                        $engagement->CC = $request->has('cc') &&  $input['cc'] != 0 ? $input['cc'] : 86;
                        if ($request->has('plan_id')) {
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
                    foreach ($engagements as $engagement) {

                        $fillengagements = FillEngagement::where('engagement_id', $engagement->id)->where('session_id', $sessionid)->where('issued_to', $issuedto)->get();

                        $formula  = Formula::find($engagement->formula_id); //formula for the engagement objective
                        $target = $engagement->Target; //target of the specific engagement criteria
                        $weight = $engagement->Weight; //weight of the specific engagement criteria
                        $actual = 0;
                        if ($fillengagements->count() > 0) {
                            $actual = $fillengagements->count();
                        }
                        $score = $this->executeFormula($formula, $target, $weight, $actual);

                        $userScore += $score;
                    }
                    $score = Score::where('user_id', $issuedto)->where('session_id', $sessionid)->first();

                    Score::updateOrCreate(
                        ['user_id' => $issuedto, 'session_id' => $sessionid],
                        ['engagementScore' => $userScore, 'appcount' =>  $input['Perspective'] == 0 ? $score->appcount  + 1 : $score->appcount,  'repcount' =>  $input['Perspective'] == 1 ? $score->repcount  + 1 : $score->repcount]
                    );
                }
                //Send a notifiaction when some get a reprimand 
                catch (\Exception $e) {
                    return 0;
                }
            }
        ); // end transaction


        $engagement = FillEngagement::where('engagement_id', $input['objective'])->where('session_id', $sessionid)->where('issued_to', $issuedto)->orderby('id', 'desc')->first();
        $usert =  $request->has('plan_id') ? (Report::find($input['plan_id'])->user_id) : $input['user_id'];
        $issued_to = User::find($usert)->fname . " " . User::find($usert)->lname;
        $issued_by_email = User::find($input['issuer'])->email;
        $issued_by = User::find($input['issuer'])->fname . " " . User::find($input['issuer'])->lname;
        $issued_email = User::find($usert)->email;
        $position = User::find($input['issuer'])->position;

        if (is_null($position)) {
            $position = 'Manager';
        }

        $data = array(
            'id' => '',
            'email' => $issued_email,
            'name' => $issued_to,
            'Sender' => $issued_by,
            'position' => $position,


        );


        if ($input['Perspective'] == 1) {
            $pdf_name =  $issued_to . " Reprimand Notice " . Carbon::parse($engagement->created_at)->format('M d Y') . ".pdf";

            $pdf = PDF::loadView('reptemplate', compact('engagement'));

            $carbc = $request->has('cc') &&  $input['cc'] != 0 ? array($issued_by_email, 'meried@ienetworksolutions.com', 'hawi@ienetworks.co', 'eliyas@ienetworksolutions.com', 'redate@ienetworksolutions.com', 'biniyam@ienetworksolutions.com', User::find($input['cc'])->email) : array($issued_by_email, 'meried@ienetworksolutions.com', 'eliyas@ienetworksolutions.com', 'redate@ienetworksolutions.com', 'hawi@ienetworks.co', 'biniyam@ienetworksolutions.com');


            $type = "Reprimand";

            Mail::send('emails.warning', ["pass_data" => $data], function ($message) use ($pdf, $type, $issued_email, $issued_by_email, $issued_by, $carbc, $pdf_name) {
                $message->from($issued_by_email, $issued_by);

                $message->to($issued_email)->cc($carbc)->subject($type);


                $message->attachData($pdf->output(), $pdf_name);
            });
            return 1;
        } elseif ($input['Perspective'] == 0) {

            $customPaper = array(0, 0, 500.00, 670.80);
            $pdf = PDF::loadview('certificate', ['engagement' => $engagement])->setPaper($customPaper, 'landscape');;



            $type = "Appreciation";
            $carbc = $request->has('cc') &&  $input['cc'] != 0 ? array($issued_by_email, 'meried@ienetworksolutions.com', 'eliyas@ienetworksolutions.com', 'hawi@ienetworks.co', 'redate@ienetworksolutions.com', 'biniyam@ienetworksolutions.com', User::find($input['cc'])->email) : array($issued_by_email, 'meried@ienetworksolutions.com', 'eliyas@ienetworksolutions.com', 'redate@ienetworksolutions.com', 'hawi@ienetworks.co', 'biniyam@ienetworksolutions.com');

            $pdf_name = $issued_to . " Appreciation Certificate " . Carbon::parse($engagement->created_at)->format('M d Y') . ".pdf";
            Mail::send('emails.Excellence', ["pass_data" => $data], function ($message) use ($pdf, $type, $issued_email, $issued_by_email, $issued_by, $carbc, $pdf_name) {
                $message->from($issued_by_email, $issued_by);

                $message->to($issued_email)->cc($carbc)->subject($type);

                $message->attachData($pdf->output(), $pdf_name);
            });
            return 1;
        }
    }


    function executeFormula($formula, $target, $weight, $actual)
    {
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

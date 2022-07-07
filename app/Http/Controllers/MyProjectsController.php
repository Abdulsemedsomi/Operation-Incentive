<?php

namespace App\Http\Controllers;

use App\Comment;
use App\Engagement;
use App\Engagementcount;
use App\FillEngagement;
use App\Formula;
use App\User;
use App\Project;
use App\Projectdelivery;
use App\Projectcheckin;
use App\Projectmember;
use App\Score;
use App\Session;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MyProjectsController extends Controller
{
    //
    public function index()
    {
        //
        $projectmember = Projectmember::where('user_id', Auth::user()->id)->select('project_id')->distinct()->get();

        return view('project', compact('projectmember'));
    }
    public function landing($id){
          $project = Project::find($id);
        return view('projectlanding', compact('project'));
    }
    public function checkin($id)
    {
        //
        $project = Project::find($id);
       
        return view('projectcheckin', compact('project'));
    }
      public function details($id)
    {
        //
        $project = Project::find($id);
          $projectmembers = Projectmember::where('project_id', $id)->where('users.active', 1)->orderby('projectmembers.position', 'asc')->join('users', 'users.id', '=', 'projectmembers.user_id')->select('projectmembers.id','project_id', 'user_id', 'fname', 'lname', 'avatarcolor', 'avatar', 'projectmembers.position', 'level')->get();
        return view('projectinfo', compact('project', 'projectmembers'));
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }
    public function setparticipant($id, Request $request){
        $pms = Projectmember::where('project_id', $id)->get();
        
        foreach($pms as $pm){
            if($request->has('user_id'. $pm->id)){
                $pm->level = $request->input('participation' . $pm->id);
                $pm->save();
            }
            
           
            
        }
        
       
         return redirect()->back()->with('success', 'Project checkin submitted successfully.');
    }
    public function resourcematrix(){
        $users = User::orderby('fname','asc')->get();
        $projects = Project::all();
        $projectmatrix= Project::join('projectmembers', 'projects.id', '=', 'projectmembers.project_id')->get();
        return view('resourcematrix', compact(['users', 'projects', 'projectmatrix']));
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        $input = $request->all();
        $checkin =  $input['checkin'];
        $session = Session::where('status', 'Active')->first();
        Projectcheckin::create(['project_id' => $input['project_id'], 'user_id' => Auth::user()->id, 'checkin' => $checkin, 'session_id'=> $session->id]);
        return redirect()->back()
            ->with('success', 'Project checkin submitted successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Score  $score
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
        $member = Projectmember::where('projectmembers.id', $id)->join('users', 'users.id', '=', 'projectmembers.user_id')->select('projectmembers.id', 'projectmembers.position', 'fname', 'lname', 'user_id')->first();
        
        return $member;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Score  $score
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Score  $score
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
        $input = $request->all();
        $member = Projectmember::find($id);
        $member->position = $input['memberposition'];
        $member->save();
       
        return redirect()->back()
            ->with('success', 'Project member data updated successfully.');
    }
    public function storemember(Request $request){
        $input = $request->all();
       
        Projectmember::updateOrCreate(['user_id' => $input['userinproject'], 'project_id'=> $input['projectid']],['position'=> $input['mposition']]);
        return redirect()->back()
            ->with('success', 'Project member added successfully.');
    }
     public function storedelivery(Request $request){
        $input = $request->all();
       
        Projectdelivery::create(['amount' => $input['amount'], 'project_id'=> $input['projectid'],'milestone_name'=> $input['milestone_name'], 'currency' => $input['dcurrencytype'], 'status' => $input['dstatus'] ]);
        return redirect()->back()
            ->with('success', 'Project delivery milestone added successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Score  $score
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
        Projectmember::destroy($id);
        return redirect()->back()
            ->with('success', 'Project member rempved successfully.');
    }
    public function comment($id)
    {
        $projectc = Projectcheckin::where('id',$id)->orderby('created_at','desc')->first();
        $engagements = Engagement::all();
        $comments = Comment::where('project_id', $id)->orderby('comments.created_at', 'asc')
            ->join('users', 'users.id', '=', 'comments.commentor_id')
            ->select('fname', 'lname', 'commentor_id', 'position', 'comment', 'comments.created_at', 'comments.id')
            ->get();
        return view('projectcomment', compact(['projectc', 'engagements', 'comments']));
    }
    public function openproject($id){
        $pr = Project::find($id);
        if($pr){
            $pr->status = 1;
             $pr->closedsession = null;
            $pr->save();
             return redirect()->route('myprojects.index');
             //return redirect()->action([MyProjectsController::class, 'index']);
        }
    }
     public function closeproject($id){
        $pr = Project::find($id);
        if($pr){
            $pr->status = 0;
            $pr->closedsession = Session::where('status', 'Active')->first()->id;
            $pr->save();
            
             return redirect()->action([MyProjectsController::class, 'details'], ['project_id' => $id]);
        }
    }
    public function fillengagement(Request $request)
    {
        $input = $request->all();


        $formula  = Formula::find(Engagement::find($input['engagement'])->formula_id);
        $score = Engagementcount::where('user_id', Projectcheckin::find($input['projectc_id'])->user_id)->where('session_id', Session::orderby('id', 'desc')->first()->id)->orderby('id', 'desc')->first();
        $userscore = Score::where('user_id', Projectcheckin::find($input['projectc_id'])->user_id)->where('session_id', Session::orderby('id', 'desc')->first()->id)->first();
        $prevval = Engagementcount::where('user_id', Projectcheckin::find($input['projectc_id'])->user_id)->where('session_id', Session::orderby('id', 'desc')->first()->id)->where('engagement_id', $input['engagement'])->first();
        $actual = 0;
        $initscore = 60;
        $initengscore = 0;
        $target = Engagement::find($input['engagement'])->Target;
        $weight = Engagement::find($input['engagement'])->Weight;


        if ($score) {

            $initscore = $userscore->engagementScore;
        }
        if ($input['Perspective'] == 1) {
            $initengscore = $weight;
        }
        if ($prevval) {
            $actual = $prevval->actual;
            $initengscore = $prevval->engscore;
        }



        $formulaarray = explode(" ", $formula->formula);
        for ($i = 0; $i < sizeof($formulaarray); $i++) {
            if ($formulaarray[$i] == "actual") {
                $formulaarray[$i] = $actual + 1;
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
        if ($input['Perspective'] == 0) {
            $engagement = new FillEngagement();
            $engagement->Reason = $input['Reason'];
            $engagement->Description = Engagement::find($input['engagement'])->Objective;
            $engagement->CC = "Employee File";
            $engagement->projectchekin_id = $input['projectc_id'];
            $engagement->issuer = Auth::user()->id;
            $engagement->issued_to = Projectcheckin::find($input['projectc_id'])->user_id;
            $engagement->engagement_id = $input['engagement'];
            $engagement->save();
            $session_id = Session::orderby('id', 'desc')->first()->id;

            Score::updateOrCreate(
                ['user_id' => Projectcheckin::find($input['projectc_id'])->user_id, 'session_id' => $session_id],
                ['engagementScore' => $initscore + ($evaluatedValue * 100)]
            );
            Engagementcount::updateOrCreate(
                ['user_id' => Projectcheckin::find($input['projectc_id'])->user_id, 'session_id' => $session_id, 'engagement_id' => $input['engagement']],
                ['actual' => $actual + 1, 'engscore' => $initengscore + ($evaluatedValue * 100)]
            );
        } elseif ($input['Perspective'] == 1) {
            $engagement = new FillEngagement;
            $engagement->Reason = $input['Reason'];
            $engagement->Improvement = $input['Improvement'];
            $engagement->Action = $input['Action'];
            $engagement->Description = Engagement::find($input['engagement'])->Objective;
            $engagement->CC = "Employee File";
            $engagement->projectchekin_id = $input['projectc_id'];
            $engagement->issuer = Auth::user()->id;
            $engagement->issued_to = Projectcheckin::find($input['projectc_id'])->user_id;
            $engagement->engagement_id = $input['engagement'];
            $engagement->save();
            $session_id = Session::orderby('id', 'desc')->first()->id;

            Score::updateOrCreate(
                ['user_id' => Projectcheckin::find($input['projectc_id'])->user_id, 'session_id' => $session_id],
                ['engagementScore' => $initscore - ($initengscore - ($evaluatedValue * 100))]
            );
            Engagementcount::updateOrCreate(
                ['user_id' => Projectcheckin::find($input['projectc_id'])->user_id, 'session_id' => $session_id, 'engagement_id' => $input['engagement']],
                ['actual' => $actual + 1, 'engscore' => $evaluatedValue * 100]
            );
        }
        return redirect()->back()
            ->with('success', 'Engagement created successfully.');
    }

}

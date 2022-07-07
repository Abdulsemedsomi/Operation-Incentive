<?php

namespace App\Http\Controllers;
ini_set('max_execution_time', 500);
use App\Http\Controllers\Controller;
use Validator;
use Illuminate\Http\Request;
use App\Http\Resources\Objective as ObjectiveResource;
use App\Keyresult;
use App\Objective;
use App\User;
use Carbon\Carbon;
use App\Session;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;

class TryController extends Controller
{
    //
    public function index()
    {
        //
        $objectives = Objective::all();

       // return $this->sendResponse(ObjectiveResource::collection($objectives), 'Objectives retrieved successfully.');

    }
   public function open($id)
    {
        //
        $session = Session::find($id);



          $objectives =DB::table('objectives')->where('session_id', '=', $session->id)

        ->join('users', 'users.id', '=', 'objectives.user_id')
        ->select("objectives.id", "fname", "lname", "attainment", "users.id as uid", "session_id", "objective_name", "avatar", "aligned_to")
        ->orderby('fname', 'asc')->get();

        //$otherobjectives = Objective::where('session_id', $session->id)->where('user_id', '!=', Auth::user()->id)->get();
        $otherobjectives = DB::table('objectives')->where('session_id', '=', $session->id)->where('user_id', '!=', Auth::user()->id)
                         ->join('users', 'users.id', '=', 'objectives.user_id')
        ->select("objectives.id", "fname", "lname", "attainment", "users.id as uid", "session_id", "objective_name")

                         ->get();
        $db = Objective::where("user_id", Auth::user()->id)->join('keyresults', 'keyresults.objective_id', '=', 'objectives.id')->get();
        $goal = Objective::where("user_id", Auth::user()->id)->where("session_id", $id)->get();
        return view('okrinsession', compact(['session', 'objectives', 'otherobjectives', 'db','goal']));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($id)
    {
        //
        return view('createokr', compact('id'));
        
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
public function addtasks($id){

    $db = Keyresult::where("keyresults.objective_id", $id)->join('tasks', 'keyresults.id', '=', 'tasks.keyresultid')->select('tasks.id', 'tasks.taskname', 'tasks.keyresultid')->get();
    $metric = Keyresult::where("objective_id", $id)->get();


    return view('editokr', compact(['db', 'metric']));
}

    public function store(Request $request)
    {
        //
      if((Gate::denies('okr') && Gate::denies('assignokr')) && $request->has('user_id')){
            return redirect()->back()
            ->with('erroro', 'Unauthorized user!');
        }
        if(Gate::allows('addObjective') || Gate::allows('assignokr')|| Gate::allows('okr')){
            $input = $request->all();
            if(!$request->has('user_id')){
                $input['user_id']= Auth::user()->id;
            }
         
            Objective::create($input);
            return redirect()->back()
            ->with('successo', 'Objective created successfully.');
        }
        else{
            return redirect()->back()
            ->with('erroro', 'Unauthorized user!');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
      $objective = Objective::find($id);
        if (is_null($objective)) {
            return 'Objective not found';
        }
       $objective = DB::table('objectives')->where('objectives.id', $id)
                    ->join('users', 'users.id', '=', 'objectives.user_id')->select('users.fname', 'users.lname', 'user_id', 'objectives.id','objective_name')->get();
        return $objective;
    }
    public function showbyuser($id)
    {
        //

 if(!Objective::where('user_id', $id)->first()){
                return "no";
        }
       $objective = DB::table('objectives')->where('user_id',$id)
                    ->join('users', 'users.id', '=', 'objectives.user_id')->select('users.fname', 'users.lname', 'user_id', 'objectives.id','objective_name')->get();
        return $objective;
    }
    public function eshowbyuser(Request $request)
    {
        //
        $input = $request->all();
        if(!Objective::where('user_id', $input['user_id'])->first()){
            return "no";
        }
        $objective = DB::table('objectives')->where('user_id',$input['user_id'])->where('objectives.id','!=',$input['objective_id'])
                            ->join('users', 'users.id', '=', 'objectives.user_id')->select('users.fname', 'users.lname', 'user_id', 'objectives.id','objective_name')->get();
        return $objective;
    }
    public function showbymanager($id)
    {
        //
        $reportsTo = User::find($id)->reportsTo;
        $manager =[];$managerid=0;
        if($reportsTo !=null){
            $manager = User::where('fname', explode(" ", $reportsTo)[0])->where('lname', explode(" ", $reportsTo)[1])->first();
            if($manager){
                $managerid = $manager->id;
                if(!Objective::where('user_id', $managerid)->first()){
                    return "no";
                }
            }
            else if($managerid==0){
                return "no";
            }

        }
        else{
            return "no";
        }
        $objective = DB::table('objectives')->where('user_id',$managerid)
                            ->join('users', 'users.id', '=', 'objectives.user_id')->select('users.fname', 'users.lname', 'user_id', 'objectives.id','objective_name')->get();
        return $objective;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {

    
    $objective =Objective::where('objectives.id',$id)
    ->join('users', 'users.id', '=', 'objectives.user_id')->select("fname", "lname", "objectives.id", "objective_name", "session_id", "aligned_to", 'user_id')->first();
    $alignedobjective = Objective::where('objectives.id',$objective->aligned_to)
            ->join('users', 'users.id', '=', 'objectives.user_id')->select("fname", "lname", "objectives.id", "objective_name", "session_id", "aligned_to", 'user_id')->first();
    if(!$alignedobjective) {
        $alignedobjective = [];
    }
    else{
        $alignedobjective = $alignedobjective->toArray();
    }
    $result[0] = $objective->toArray();
    $result[1] = $alignedobjective;
    //$result = array_merge($objective->toArray(), $alignedobjective );
    return $result;
       // return view('editokr', compact(['objective', 'otherobjectives', 'keyresults', 'objectiveatt', 'alignedobjective']));
    }
    public function getObjective($id)
    {
        //
        $objective = Objective::find($id);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
      if((Gate::denies('okr') || Gate::denies('assignokr')) && $request->has('user_id')){
            return redirect()->back()
            ->with('erroro', 'Unauthorized user!');
        }
        if(Gate::allows('addObjective') || Gate::allows('assignokr')|| Gate::allows('okr')){
            $input = $request->all();
            if(!$request->has('user_id')){
                $input['user_id']= Auth::user()->id;
            }
            $objective = Objective::find($id);
            $objective->fill($input);
            $objective->save();
            return redirect()->back()
            ->with('successo', 'Objective updated successfully.');
        }
        else{
            return redirect()->back()
            ->with('erroro', 'Unauthorized user!');
        }
    }
public function list($id){
   $arr = explode ("m", $id);
  $obj = Objective::where('user_id', $arr[0])->where("session_id", $arr[1])->get();
   return $obj;
}
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
      $objective = Objective::find($id);
        if((Gate::denies('okr') && Gate::denies('assignokr')) && Auth::user()->id !=$objective->user_id){
            return redirect()->back()
            ->with('erroro', 'Unauthorized user!');
        }
        if(Gate::allows('addObjective') || Gate::allows('assignokr')|| Gate::allows('okr')){


        $objectives = Objective::where('aligned_to',$id)->get();
        foreach($objectives as $obj){
            $obj->aligned_to = null;
            $obj->save();
        }
        $objective->delete();
            return redirect()->back()
            ->with('successo', 'Objective deleted successfully.');
        } 
        else{
            return redirect()->back()
            ->with('erroro', 'Unauthorized user!');
        }



    
    }
}

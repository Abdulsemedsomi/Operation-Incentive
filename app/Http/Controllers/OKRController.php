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
use App\Task;
use Carbon\Carbon;
use App\Session;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;

class OKRController extends Controller
{
    //
    public function index()
    {
        //
        $objectives = Objective::all();

       // return $this->sendResponse(ObjectiveResource::collection($objectives), 'Objectives retrieved successfully.');

    }
    public function importOkr(){
          
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($id)
    {
         return view('createokr', compact('id'));
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */


    public function store(Request $request)
    {
      $input =$request->all();
        DB::transaction(function () use ($input, $request) {
    /*  $krcount = $input['krcount'];
        $krcount is replaced by $krID to prevent the issue when delete keyresult in the middle when adding keyresult */
      $krIDs = explode(',',$input['kr_id']);
      $object = new Objective;
      $object->user_id = $input['user_id'];
      if($request->has('aligned_to')){
      $object->aligned_to = $input['aligned_to'];}
      $object->objective_name = $input['objective_name'];
      $object->session_id = $input['session_id'];
      $object->value = $input['obj-value'];
      $object->priority = $input['obj-priority'];
      $object->save();
      
      $objective = Objective::where('session_id', $input['session_id'])->where('objective_name',$input['objective_name'] )->where('user_id', $input['user_id'])->orderby('id', 'desc')->first();
      for($i =0; $i< count($krIDs); $i++){
          if($request->has('keyresult_name'.$krIDs[$i]) && $input['keyresult_name'.$krIDs[$i]]!=null){
              $kr = new Keyresult;
              $krType = $input['krstatus'.$krIDs[$i]];
              $kr->objective_id = $objective->id;
              $kr->keyresult_name = $input['keyresult_name'.$krIDs[$i]];
              $kr->keyresult_type = $krType;
              $kr->weight = $input['kr_weight'.$krIDs[$i]];
                               
             if($krType == 4 /*percentage tracked*/){
                  $kr->unit = '%';
                  $initalValue = $input['percentageKr-initalValue'.$krIDs[$i]]; 
                  $kr->initialValue = $initalValue>99?0:$initalValue;
                  $kr->targetValue = 100;
             }
             //should increase and should decrease
             elseif( $krType ==2 || $krType == 3){
                $kr->unit = $input['kr-measuring-unit'.$krIDs[$i]];
                $kr->initialValue = $input['initialv'.$krIDs[$i]];
                $kr->targetValue = $input['targetv'.$krIDs[$i]];  

             }
              $kr->save();

              // TODO ...
              if($krType==0){
                  $keyr = Keyresult::where('keyresult_name', $input['keyresult_name'.$krIDs[$i]])->where('objective_id', $objective->id)->orderby('id', 'desc')->first();
                  if($keyr){
                      for($j=0; $j < 4; $j++){
                      $task = new Task;
                      $task->keyresultid = $keyr->id;
                      $task->taskname = 'Milestone ' . ($j+1);
                      $task->isMilestone = 1;
                      $task->isactive = 1;
                      $task->save();
                      }
                  }
              }
          }
        
      }
        });
      return redirect()->back()
            ->with('success', 'Objective updated successfully.');
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
         $session = Session::where("status", "Active")->first();
        if(!Objective::where('user_id', $id)->first()){
                return "no";
        }
       $objective = DB::table('objectives')->where('user_id',$id)->where("session_id", $session->id)
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
        $session = Session::where("status", "Active")->first();
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
        $objective = DB::table('objectives')->where('user_id',$managerid)->where("session_id", $session->id)
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
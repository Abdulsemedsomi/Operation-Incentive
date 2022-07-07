<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Validator;
use Illuminate\Http\Request;
use App\Http\Resources\Keyresult as KeyresultResource;
use App\Keyresult;
use App\Objective;
use App\Task;
use App\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
class KeyresultController extends Controller
{
    //
    public function index()
    {
        //

        $keyresults = Keyresult::all();

        return  $keyresults;
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

        if($input['targetValue'] == "" && $input['initialValue'] == "" ){
            unset($input['targetValue']);
            unset($input['initialValue']);
        }
        else{
           $input['currentState'] = doubleval($input['initialValue']);
            $input['attainment'] = doubleval($input['initialValue']) / doubleval($input['targetValue']);
        }


        $keyresult = Keyresult::create($input);
         $keyr = Keyresult::where('keyresult_name', $input['keyresult_name'])->where('objective_id', $input['objective_id'])->orderby('id', 'desc')->first();
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
        $obj = Objective::find($keyresult->objective_id);
        $keyresults = Keyresult::where('objective_id', $keyresult->objective_id)->get();
        $krcount = 0;
        foreach ($keyresults as $kr){
            $krcount += $kr->attainment;
        }
        $obj->attainment = $krcount/$keyresults->count();
        $obj->save();

        $result[0] =  Keyresult::find($keyresult->id);
        $result[1] = $obj->attainment;
        $result[2] = $keyresults->count();
        $result[3] = User::find($obj->user_id)?User::find($obj->user_id)->fname[0] . User::find($obj->user_id)->lname[0]:"U";
        return $result;

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function showkr($id)
    {
        //

        $keyresult = DB::table('keyresults')->where('keyresults.id', $id)
                ->join('objectives', 'objectives.id', '=', 'keyresults.objective_id')
                ->join('users', 'users.id', '=', 'objectives.user_id')->select("fname", "lname", "keyresults.id", "keyresult_name", "session_id", "keyresults.attainment")->first();

        $tasks = Task::where('keyresultid', $id)->where('isMilestone', 1)->get();


                $result[0] =   $keyresult;
                $result[1] = $tasks->toArray();

                return $result;
    }
      public function showrestrictedkr($id)
    {
        //

        $keyresult = DB::table('keyresults')->where('keyresults.id', $id)
                ->join('objectives', 'objectives.id', '=', 'keyresults.objective_id')
                ->join('users', 'users.id', '=', 'objectives.user_id')->select("fname", "lname", "keyresults.id", "keyresult_name", "session_id", "keyresults.attainment")->first();

        $tasks = Task::where('keyresultid', $id)->where('isMilestone', 1)->get();
        $keyrsultval = Keyresult::find($id);
        $obj = Objective::find($keyrsultval->objective_id);
        $userid= 0;
        if($obj){
            $userid = $obj->user_id;
        }
        $access = 0;
        if(Gate::allows('assignokr')|| Gate::allows('okr') || $userid == Auth::user()->id){
            $access =1;
        }

                $result[0] =   $keyresult;
                $result[1] = $tasks->toArray();
                $result[2] =$access; 
                return $result;
    }


    public function show($id)
    {


                return Keyresult::find($id);
    }
    public function list($id)
    {
        //
        $keyresults = Keyresult::where('objective_id', $id)->get();
        return $keyresults;

    }
    public function milelist($id)
    {
        //
        $miles = Task::where('keyresultid', $id)->where('isMilestone', 1)->where('status', 0)->where('isactive', 1)->get();
        return $miles;

    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //

    }
    public function updateStatus(Request $request)
    {
        //
        $input = $request->all();
        $keyresult = Keyresult::find($input['id']);


        $input['attainment'] = doubleval($input['currentState']) / doubleval($keyresult->targetValue);


        $keyresult->fill($input);

        $keyresult->save();

        $objective = Objective::find($keyresult->objective_id);

        $keyresults = Keyresult::where("objective_id", $objective->id)->get();
        $total = 0;
        foreach($keyresults as $kr){
          $total += $kr->attainment;

        }
        $objectiveatt = 0;
        if($keyresults->count() > 0){
            $objectiveatt = $total/$keyresults->count();
        }
        $objective->attainment = $objectiveatt;
        $objective->save();

        $result[0] = $keyresult;
        $result[1] = $objective;


        return $result;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Keyresult $keyresult)
    {
        //
        $input = $request->all();


        if($input['targetValue'] == "" && $input['initialValue'] == "" ){
            unset($input['targetValue']);
            unset($input['initialValue']);
        }
        else{
           $input['currentState'] = doubleval($input['initialValue']);
            $input['attainment'] = doubleval($input['initialValue']) / doubleval($input['targetValue']);
        }


        $keyresult = Keyresult::find($input['id']);
        $keyresult->fill($input);

        $keyresult->save();
        $obj = Objective::find($keyresult->objective_id);
        $keyresults = Keyresult::where('objective_id', $keyresult->objective_id)->get();
        $krcount = 0;
        foreach ($keyresults as $kr){
            $krcount += $kr->attainment;
        }
        $obj->attainment = $krcount/$keyresults->count();
        $obj->save();

        $result[0] =  Keyresult::find($keyresult->id);
        $result[1] = $obj->attainment;
        $result[2] = $keyresults->count();
        $result[3] = User::find($obj->user_id)?User::find($obj->user_id)->fname[0] . User::find($obj->user_id)->lname[0]:"U";
        return $result;

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $objid = Keyresult::find($id)->objective_id;

        $kr = Keyresult::destroy($id);
        $obj = Objective::find($objid);
        $keyresults = Keyresult::where('objective_id',  $objid)->get();
        $krcount = 0;
        foreach ($keyresults as $kr){
            $krcount += $kr->attainment;
        }
        $obj->attainment = $krcount/$keyresults->count();
        $obj->save();
        $result[0] = $obj;
        $result[1] = $keyresults->count();
        return $result;
    }
}

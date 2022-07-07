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

class NewObjectiveController extends Controller
{
    //
    public function index()
    {
        //
        $objectives = Objective::all();

       // return $this->sendResponse(ObjectiveResource::collection($objectives), 'Objectives retrieved successfully.');

    }
    public function importOkr(){
          $token = env('TOKEN');
            $baseUrl = env('DESERT_EBS_BASE_URL');

            $headers = [
                'Authorization' => 'Bearer ' . $token,
                'Accept'        => 'application/json',
                'gtmhub-accountId' => '5c370ea429f3490001988e3a',
            ];

            $client = new Client();
            $response = $client->request('GET', $baseUrl . '/sessions', [
                'headers' => $headers
            ]);
            $statusCode = $response->getStatusCode();
            $body = $response->getBody()->getContents();
            $jArr = json_decode($body);

            foreach ($jArr as $items) {
                if (is_array($items)) {
                    foreach ($items as $session) {

                        Session::updateorCreate(['session_name'=>$session->title ],[
                            'start_date' => Carbon::parse($session->start)->format('Y-m-d H:i:s'),
                            'end_date' => Carbon::parse($session->start)->format('Y-m-d H:i:s'),
                            'status' => $session->status == 'open'? "Active": "Closed",
                            'gtmhub_sessionid' =>$session->id
                        ]);


                    }
                }
            }

            $response3 = $client->request('GET', $baseUrl . '/employees', [
                'headers' => $headers
            ]);

            $body3 = $response3->getBody()->getContents();
            $jArr3 = json_decode($body3);


            foreach ($jArr3 as $items3) {

                if (is_array($items3)) {

                    foreach ($items3 as $user) {
                        User::where('email', $user->email)
                            ->update([
                            'gtmhub_userid' => $user->id,

                        ]);
                    }
                }
            }
            $userdata = User::all();
            foreach($userdata as $ud){
                if($ud->gtmhub_userid != null) {
                try{
                $response4 = $client->request('GET', $baseUrl . '/goals/metric/'.$ud->gtmhub_userid, [
                    'headers' => $headers
                ]);

                $body4 = $response4->getBody()->getContents();
                $jArr4 = json_decode($body4);
                $statusCode = $response4->getStatusCode();

            foreach ($jArr4 as $items4) {

                if (is_array($items4)) {

                    foreach ($items4 as $goal) {

                        $user = User::where('gtmhub_userid', $goal->ownerId)->first();
                        $session = Session::where('gtmhub_sessionid',$goal->sessionId)->first();
                        if($goal->parentId == "" && $session){
                       Objective::updateorCreate(['gtmhub_objid'=>$goal->id ],[
                        'objective_name' => $goal->name,
                        'user_id'=>$ud->id,
                        'attainment' =>$goal->fullAggregatedAttainment,
                        'session_id' => $session->id
                    ]);
                    $obj = Objective::where('gtmhub_objid',$goal->id)->first();
                    foreach($goal->metrics as $kr){
                        Keyresult::updateorCreate(['gtmhub_krid'=>$kr->id ],[
                            'keyresult_name' => $kr->name,
                            'objective_id'=>$obj->id,
                            'attainment' =>$kr->attainment,
                            'initialValue' =>$kr->target == 100?null:$kr->initialValue,
                            'targetValue' =>$kr->target == 100?null:$kr->target,
                            'keyresult_type' =>$kr->target == 100? 0:1,
                            'currentState' =>$kr->target == 100?null:$kr->actual

                        ]);
                        }
                       }
                    }

                    foreach ($items4 as $goal) {

                        $user = User::where('gtmhub_userid', $goal->ownerId)->first();
                        $session = Session::where('gtmhub_sessionid',$goal->sessionId)->first();
                        if($session && $goal->parentId != ""){
                       Objective::updateorCreate(['gtmhub_objid'=>$goal->id ],[
                        'objective_name' => $goal->name,
                        'user_id'=>$ud->id,
                        'attainment' =>$goal->fullAggregatedAttainment,
                        'aligned_to' => Objective::where('gtmhub_objid',$goal->parentId)->first()? Objective::where('gtmhub_objid',$goal->parentId)->first()->id:null,
                        'session_id' => $session->id
                    ]);
                    $obj = Objective::where('gtmhub_objid',$goal->id)->first();
                    foreach($goal->metrics as $kr){
                    Keyresult::updateorCreate(['gtmhub_krid'=>$kr->id ],[
                        'keyresult_name' => $kr->name,
                        'objective_id'=>$obj->id,
                        'attainment' =>$kr->attainment,
                        'initialValue' =>$kr->target == 100?null:$kr->initialValue,
                        'targetValue' =>$kr->target == 100?null:$kr->target,
                        'keyresult_type' =>$kr->target == 100? 0:1,
                        'currentState' =>$kr->target == 100?null:$kr->actual

                    ]);
                    }
                       }
                    }

                }
            }
           
            }
            catch (\GuzzleHttp\Exception\BadResponseException $e) {
                // handle exception or api errors.
                continue;
             }
            }
        }
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
            if(!$request->has('user_id') ){
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
    public function showbyuser($value)
    {
        //
         $id = explode("m", $value)[0];
         $sid = explode("m", $value)[1];
        if(!Objective::where('user_id', $id)->first()){
                return "no";
        }
       $objective = DB::table('objectives')->where('user_id',$id)->where('session_id', $sid)
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
        $s= Session::where('status', 'Active')->first();
        $objective = DB::table('objectives')->where('user_id',$input['user_id'])->where('objectives.id','!=',$input['objective_id'])->where('session_id',$s->id)
                            ->join('users', 'users.id', '=', 'objectives.user_id')->select('users.fname', 'users.lname', 'user_id', 'objectives.id','objective_name')->get();
        return $objective;
    }
   public function showbymanager($value)
    {
        //
        $id = explode("m", $value)[0];
         $sid = explode("m", $value)[1];
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
        $objective = DB::table('objectives')->where('user_id',$managerid)->where('session_id', $sid)
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

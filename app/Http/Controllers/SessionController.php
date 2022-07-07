<?php

namespace App\Http\Controllers;
use Validator;
use Illuminate\Http\Request;
use App\Http\Resources\Session as SessionResource;
use App\Keyresult;
use App\Objective;
use App\Session;
use App\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;

class SessionController extends Controller
{
    public function index()
    {
        //
        $sessions = Session::orderby('start_date', 'desc')->get();

        return view('home', compact('sessions'));
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
    public function myokr(){
        $db = Objective::where("user_id", Auth::user()->id)->join('keyresults', 'keyresults.objective_id', '=', 'objectives.id')->get();
        $goal = Objective::where("user_id", Auth::user()->id)->get();
        return view('home', ['db' => $db, 'goal' => $goal]);
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
        if (Gate::allows('addSession') || Gate::allows('okr')) {


        $input = $request->all();





        Session::create($input);
        return redirect()->back()
        ->with('success', 'Session created successfully.');
    } else {
        return redirect()->back()
        ->with('error', 'Unauthorized user.');
      }

    }
public function okr($id){
return view('alignment');
}
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function open($id)
    {
        //
        $session = Session::find($id);



          $objectives =DB::table('objectives')->where('session_id', '=', $session->id)

        ->join('users', 'users.id', '=', 'objectives.user_id')
        ->select("objectives.id", "fname", "lname", "attainment", "users.id as uid", "session_id", "objective_name", "avatar", "aligned_to","avatarcolor" )
        ->orderby('fname', 'asc')->get();

        //$otherobjectives = Objective::where('session_id', $session->id)->where('user_id', '!=', Auth::user()->id)->get();
        $otherobjectives = DB::table('objectives')->where('session_id', '=', $session->id)->where('user_id', '!=', Auth::user()->id)
                         ->join('users', 'users.id', '=', 'objectives.user_id')
        ->select("objectives.id", "fname", "lname", "attainment", "users.id as uid", "session_id", "objective_name")

                         ->get();
        $db = Objective::where("user_id", Auth::user()->id)->join('keyresults', 'keyresults.objective_id', '=', 'objectives.id')->get();
        $goal = Objective::where("user_id", Auth::user()->id)->where("session_id", $id)->get();
                                                                                           return view('sessionxokr', compact(['session', 'objectives', 'otherobjectives', 'db','goal']));
    }
    public function show($id)
    {
        //
        $session = Session::find($id);

        if (is_null($session)) {
            return $this->sendError('Session not found.');
        }

        return $session;
    }
public function activesessions(){
    $count = Session::where('status', 'Active')->get()->count();
    return $count;
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

        $session =  Session::find($id);
        return $session; 
    }
    public function performance (Request $request)
    {

        $session = $request->sessions;
        $emps = $request->emps;
        $objs = $request->objs;

  $user = User::find($emps);
  $objatt =0;
  if($objs != '0'){
    $objatt = Objective::find($objs)->attainment;
  }
 
  $t = 0;
if($user->email == 'meried@ienetworksolutions.com' ){
    $objlist = Objective::where('aligned_to', $objs)->get();
    if($objlist->count()> 0){
        foreach($objlist as $ob){
           $t += $ob->attainment;
        }
        $objatt = $t/$objlist->count();
    }
    else{
        $objatt = 0;
    }
}
$keyresults = Keyresult::where('objective_id', $objs)->get();

$objectives = Objective::where('session_id', $session)->where('user_id', Auth::user()->id)->get();
         return view('dashboard', compact(['keyresults', 'objatt', 'objectives']));
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
        if (Gate::allows('editSession')  || Gate::allows('okr')) {
        $input = $request->all();
      $session = Session::find($id);
        $session->fill($input);

        $session->save();

        return redirect()->back()
        ->with('success', 'Session updated successfully.');
    } else {
        return redirect()->back()
        ->with('error', 'Unauthorized user.');
      }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Session $session)
    {
        if (Gate::allows('deleteSession') || Gate::allows('okr')) {
            $objective = Objective::where('session_id', $session->id)->get();
            if($objective->count() > 0){
                return redirect()->back()
                ->with('error', 'Error: You have objectives under this session.');
            }
            $session->delete();

            return redirect()->back()
            ->with('success', 'Session updated successfully.');   }
        else {
            return redirect()->back()
            ->with('error', 'Unauthorized user.');
          }
        }

}
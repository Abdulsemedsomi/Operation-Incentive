<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;

use App\Team;
use App\Teammember;

use App\Session;
use Illuminate\Support\Facades\Auth;
use Validator;
use App\Http\Resources\Team as TeamResource;
use App\Imports\TeamsImport;
use App\User;
use Exception;
use Illuminate\Support\Facades\DB;
use App\FillEngagement;
use Maatwebsite\Excel\Facades\Excel;

class TeamController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $teams = Team::all();
        $allusers = User::all();
        return view('teamdisplay', compact(['teams', 'allusers']));
    }
    public function checkin($id)
    {
        $team = Team::find($id);
        return view("checkin", ['team' => $team]);
    }
      public function drivers($id)
    {
        $team = Team::find($id);
        $users = Teammember::where('team_id', $id)->where('users.active', 1)->where('users.position', 'Driver and Messenger')->join('users', 'teammembers.user_id', '=', 'users.id')->orderby('users.fname', 'asc')->get();
        
         $fill_engagements = FillEngagement::all();
        return view("driverspage", ['team' => $team, 'fill_engagements' => $fill_engagements, 'users' => $users]);
    }
public function teamcheckin($teamid, $sessionid)
    {
        $team = Team::find($teamid);
        $session = Session::find($sessionid);
        return view("teamcheckin", compact(['team', 'session']));
    }
    public function teammember($id)
    {
        $team = Team::find($id);
        $teammembers = Teammember::where('team_id', $id)->get();
        return view("editteammembers", compact(['team', 'teammembers']));
    }

    public function import(Request $request)
    {
        $this->validate($request, [
            'select_file'  => 'required|mimes:xls,xlsx'
        ]);

        try {
            Excel::import(new TeamsImport, request()->file('select_file'));

            return back()->with('successt', 'Excel Data Imported successfully.');
        } catch (Exception $e) {
           
            return back()->with('errort', 'Import Error.');
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
        $users = User::all();
        return $users;
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



        $team = Team::create($input);
        $teamresult = DB::table('teams')->where("teams.id", $team->id)
            ->join('users', 'users.id', '=', 'teams.manager_id')->get();
        if ($team->teamparent_id) {
            $teamresult = DB::table('teams as t')->where("t.id", $team->id)
                ->join('users', 'users.id', '=', 't.manager_id')
                ->join('teams as tt', 'tt.id', '=', 't.parent_id')->get();
        }

        return $teamresult;
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
        $team = Team::find($id);

        if (is_null($team)) {
            return $this->sendError('Team not found.');
        }
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

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Team $team)
    {
        //
        $input = $request->all();



        $team->fill($input);

        $team->save();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Team $team)
    {
       
    }
}

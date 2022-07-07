<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;

use App\Team;



use Illuminate\Support\Facades\Auth;
use Validator;
use App\Http\Resources\Team as TeamResource;
use App\Imports\TeamsImport;
use App\User;
use App\Session;
use App\Teammember;
use Exception;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class MyTeamsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($teamid)
    {
        //
        $team = Team::find($teamid);
        $sessions = Session::orderby('start_date', 'desc')->get();
        $members = Teammember::where('team_id', $teamid)->where('users.active', 1)->orderby('users.fname','asc')->join('users', 'users.id', 'teammembers.user_id')->paginate(17);
        
        return view('sessioninteam', compact(['sessions', 'team', 'members']));
     
    }
    public function checkin($id)
    {
      
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

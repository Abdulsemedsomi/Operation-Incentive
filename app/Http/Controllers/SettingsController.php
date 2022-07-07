<?php

namespace App\Http\Controllers;

use App\Role;
use App\Team;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SettingsController extends Controller
{
    //
    public function index(){
        $users = User::orderby('fname', 'asc')->get();
        $teammembers = DB::table('teammembers')
        ->join('users', 'users.id', '=', 'teammembers.user_id')
        ->join('teams', 'teams.id', '=', 'teammembers.team_id')

        ->get();
        $teams = Team::all();
        $roles = Role::all();
        $allusers = User::all();
        return view('settings', compact(['users','teammembers','teams', 'allusers','roles']));
    }
}

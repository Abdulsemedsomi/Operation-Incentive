<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\User;


use App\Imports\UsersImport;
use App\Role;
use App\Role_user;
use App\Team;
use App\Teammember;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Intervention\Image\Facades\Image as FacadesImage;
use Intervention\Image\Image;
use Maatwebsite\Excel\Facades\Excel;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $users = User::where('active', 1)->get();
        $teammembers = DB::table('teammembers')->where('users.active', '1')
        ->join('users', 'users.id', '=', 'teammembers.user_id')
        ->join('teams', 'teams.id', '=', 'teammembers.team_id')

        ->get();

        return view('userdisplay', compact(['users','teammembers' ]));
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        $teams = Team::all();
        return $teams;
    }
     public function doNewColor(){
        $color = dechex(rand(0x000000, 0xFFFFFF));
        
        return $color;
    }
    public function updateColor(){
        
        $users = User::where('active', 1)->get();
        foreach($users as $user){
            $user->avatarcolor = $this->doNewColor();
            $user->save();
            
        }
        return "done";
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


        $input['password'] =  bcrypt('%TGBnhy6');
        $user = User::create($input);
        $userdata = DB::table('users')->where('users.id', '=', $user->id)->get();
        if($input['team_name'] !="None"){
        $team = Team::where("team_name", $input['team_name'])->first();
        $teammeber = Teammember::create(['user_id' => $user->id, 'team_id' => $team->id]);
        $userdata =  DB::table('users')->where('users.id', '=', $user->id)
        ->join('teammembers', 'users.id', '=', 'teammembers.user_id')
        ->join('teams', 'teams.id', '=', 'teammembers.team_id')
       ->select('users.id', 'fname', 'lname', 'title','email','team_name')
        ->get();
    }


       $role = Role::where('name', 'User')->first();
       $user_roles = Role_user::create(['user_id' => $user->id, 'role_id' => $role->id]);


        return $userdata;
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
        $suser = User::find($id);

        if (is_null($suser)) {
            return 'User not found.';
        }

      return $suser;
    }
    public function import(Request $request)
    {
        $this->validate($request, [
            'select_file'  => 'required|mimes:xls,xlsx'
           ]);
           try{
           Excel::import(new UsersImport, request()->file('select_file'));
           $users = User::all();
           foreach ($users as $user){
               $role = Role_user::where('user_id', $user->id)->first();
                $team = Team::where('team_name', $user->team)->first();
            
               if(!$role){
                $user_role = new Role_user;
                $user_role->user_id = $user->id;
                $user_role->role_id = 3;
                $user_role->save();
               }
               
                if (trim($user->team == 'Executive') || trim($user->team == 'CEO')){
                        $teams = Team::all();
                        foreach($teams as $t){
                            if(!Teammember::where('user_id', $user->id)->where('team_id', $t->id)->first()){
                                $team_member = new Teammember;
                                $team_member->user_id = $user->id;
                                $team_member->team_id = $t->id;
                                $team_member->save();
                            }
                        }
                    }
                else if($team){
               
                     if(trim($user->team) == 'Middle Management'){
                        if(!Teammember::where('user_id', $user->id)->where('team_id', $team->id)->first()){
                        $team_member = new Teammember;
                        $team_member->user_id = $user->id;
                        $team_member->team_id = $team->id;
                        $team_member->save(); 
                        }
                       
                        if(Team::where ('manager_id', $user->id)->first() && !Teammember::where('user_id', $user->id)->where('team_id', Team::where ('manager_id', $user->id)->first()->id)->first()){

                        $team_member = new Teammember;
                        $team_member->user_id = $user->id;
                        $team_member->team_id = Team::where ('manager_id', $user->id)->first()->id;
                        $team_member->save();
                        }
                    }
                    else{
                        if(!Teammember::where('user_id', $user->id)->where('team_id', $team->id)->first()){
                        $team_member = new Teammember;
                        $team_member->user_id = $user->id;
                        $team_member->team_id = $team->id;
                        $team_member->save();
                        }
                    }
                
               }

           }
           
          
        return back()->with('successu', 'Excel Data Imported successfully.');

           }
           catch(Exception $e){

        return back()->with('erroru', 'Import Error.');

           }



       // Excel::import(new UsersImport, 'WebHR_Report.xlsx');
        // $array = Excel::toArray(new UsersImport, 'WebHR_Report.xlsx');
        // dd($array);
    }
public function allUsers(){
    $users = User::where('active', 1)->get();
    return $users;
}
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $i
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
        $user = User::find($id);
        $teammember = Teammember::where('user_id', $user->id)->count();
        $userdata = DB::table('users')->where('users.id', '=', $user->id)->get();
        if($teammember >0){
        $userdata =  DB::table('users')->where('users.id', '=', $user->id)
        ->join('teammembers', 'users.id', '=', 'teammembers.user_id')
        ->join('teams', 'teams.id', '=', 'teammembers.team_id')
       ->select('users.id', 'fname', 'lname', 'title','email','team_name')
        ->get();
        }


        $teams = Team::all();
        $result = array_merge($userdata->toArray(), $teams->toArray());
      return $result;

    }
    public function profile(){
        return view('profile');
        }

    public function update_avatar(Request $request){

        // Handle the user upload of avatar

       $request->validate(['avatar' => 'required|image'  ]);


        if($request->hasFile('avatar')){
            $avatar = $request->file('avatar');
            $filename = time() . '.' . $avatar->getClientOriginalExtension();
           
            FacadesImage::make($avatar)->resize(100, 100)->save( public_path('/uploads/avatars/' . $filename ) );
            $user = Auth::user();
            $user->avatar = $filename;
            $user->save();
            Auth::setUser($user);

        }
        else{
            return back()->with('erroru', 'Please choose a file.');
        }

        return back()->with('successu', 'Profile updated Successfully.');
    }
    public function changesignature(Request $request){
     
         $request->validate(['sign' => 'required|image'  ]);
   
           
        if($request->hasFile('sign')){
            $sign = $request->file('sign');
            $filename = time() . '.' . $sign->getClientOriginalExtension();
          
            FacadesImage::make($sign)->save( public_path('/uploads/signature/' . $filename ) );
            $user = User::find($request->input('userid'));
            $user->signature = $filename;
           
            $user->save();
           
        }
        else{
            return back()->with('erroru', 'Please choose a file.');
        }

        return back()->with('successu', 'Signature updated Successfully.');
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        //
        $input = $request->all();



        $user->fill($input);


        $user->save();
        $team = Team::where("team_name", $input['team_name'])->first();



        $teammember = Teammember::where('user_id', $user->id)->first();
        $teammember->team_id = $team->id;
        $teammember->save();
        $userdata =  DB::table('users')->where('users.id', '=', $user->id)
        ->join('teammembers', 'users.id', '=', 'teammembers.user_id')
        ->join('teams', 'teams.id', '=', 'teammembers.team_id')
       ->select('users.id', 'fname', 'lname', 'title','email','team_name')
        ->get();
        return $userdata;

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
     
      public function updaterole(Request $request, $id)
    {

        $input = $request->all();
        Role_user::where('user_id', $id)->update(['role_id' => $input['role']]);
        return back()->with('successu', 'Role updated Successfully.');
    }
    public function destroy(User $user)
    {
       
    }
}

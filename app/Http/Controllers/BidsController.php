<?php

namespace App\Http\Controllers;

use App\Bid;
use App\Bidmember;
use App\User;
use App\Award;
use App\Project;
use App\Projectmember;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BidsController extends Controller

{
    public function index()
    {
        //
        $bids = Bid::where('status', 1)->get();
        $new = Bid::where('status', 0)->get();
        $sessions = Bid::join('sessions', 'sessions.id', '=', 'bids.session_id')->select('session_name', 'session_id')->orderby('sessions.start_date', 'desc')->distinct()->get();
        return view('managebids', compact('bids', 'sessions', 'new'));
    }
    public function store(Request $request)
    {
        //
        $input = $request->all();
        Bid::create(
            ['bid_name' => $input['bidname'], 'bid_amount' => $input['bidamount']]
        );
        $nbid = Bid::orderby('id', 'desc')->first();
        $bidmember = new Bidmember();
        $bidmember->bid_id = $nbid->id;
        $bidmember->user_id = $input['ae'];
        $bidmember->position = "AE";
        $bidmember->save();
        for ($i = 0; $i < $input['memebercount']; $i++) {
            $namearr = explode(" ", $input['member' . ($i + 1)]);
            $bidmember = new Bidmember;
            $bidmember->bid_id = $nbid->id;
            $bidmember->user_id = User::where('fname', $namearr[0])->where('lname', $namearr[1])->first()->id;
            $bidmember->position = $input['positionlist' . ($i + 1)];
            $bidmember->save();
        }
        return redirect()->back()
            ->with('success', 'Bid Added successfully.');
    }
     public function show($id)
    {
        $bid = Bid::find($id);
        $bidmembers = Bidmember::where('bid_id', $id)->join('users', 'users.id', '=', 'bidmembers.user_id')->select('bidmembers.id', 'user_id', 'bidmembers.position', 'fname', 'lname' , 'avatarcolor', 'level')->get();
        $project = Project::where('bid_id', $bid->id)->first();
        return view('bids', compact('bid', 'bidmembers', 'project'));
    }
    public function showmembers($id){
         $bidmembers = Bidmember::where('bidmembers.id', $id)->join('users', 'users.id', '=', 'bidmembers.user_id')->select('bidmembers.id', 'user_id', 'bidmembers.position', 'fname', 'lname' , 'avatarcolor', 'level')->first();
        return $bidmembers;
        
    }
    public function storebidmember(Request $request){
         $input = $request->all();
         
        Bidmember::create(
            ['bid_id' => $input['bid_id'], 'position' => $input['bposition'], 'user_id' => $input['userinbid'], 'level' => $input['level']]
        );
       $bid = Bid::find($input['bid_id']);
       $bid->status = 1;
       $bid->save();
         return redirect()->back()
            ->with('success', 'Bid member added successfully.');
    }
     public function update(Request $request, $id)
    {
        //
        $input = $request->all();
        $bidmember =Bidmember::find($id);
        $bidmember->position =  $input['bposition'];
        $bidmember->level =  $input['level'];
        $bidmember->save();
       
        return redirect()->back()
            ->with('success', 'Bid Added successfully.');
    }
    public function destroy($id){
        Bidmember::destroy($id);
         return redirect()->back()
            ->with('success', 'Bid Deleted successfully.');
        
    }
    public function createproject(Request $request){
          DB::transaction(function () use ($request) {
        $bid = Bid::find( $request->input('bid_id'));
        $project = new Project;
        $project->project_name = $bid->bid_name;
        $project->amount = $bid->bid_amount;
        $project->contract_sign_date = $bid->contract;
        $project->bid_id = $bid->id;
        $project->save();
        
        $cproject = Project::where('bid_id', $bid->id)->first();
        $projectm = new Projectmember;
        $projectm->project_id = $cproject->id;
        $projectm->user_id = $request->input('userinproject');
        $projectm->position = $request->input('pposition');
        $projectm->save();
         return redirect()->back()
            ->with('success', 'Project created successfully.');
          });
          return redirect()->back()
            ->with('error', 'Error! Please try again.');
    }
}

<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;


use App\Session;
use Illuminate\Support\Facades\Auth;
use Validator;
use App\Award;
use App\Financialstatement;
use App\Companyinfo;
use App\Imports\AwardImport;
use App\Imports\FinancialImports;
use App\User;
use Exception;
use App\Bid;
use App\Project;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use App\Http\Controllers\BidsController;
class CompanyTargetController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $session = Session::where('status', 'Active')->first();
       $awards = Award::where('session_id', $session->id)->get();
        $statements = Financialstatement::where('session_id', $session->id)->get();
         $info = Companyinfo::where('session_id', $session->id)->first();
        return view('companytargets', compact(['info', 'statements', 'session', 'awards']));
    }
   public function changecompanytarget(Request $request){
        $session = Session::find($request->input('session-select'));
       $awards = Award::where('session_id', $session->id)->get();
        $statements = Financialstatement::where('session_id', $session->id)->get();
         $info = Companyinfo::where('session_id', $session->id)->first();
        return view('companytargets', compact(['info', 'statements', 'session', 'awards']));
       
   }
   public function updatetargetaward(Request $request){
       $input = $request->all();
      
       Companyinfo::updateOrCreate( ['session_id' => $input['session_id']],
                    ['award_target' => $input['award_target']]);
       return back()->with('successa', 'Target Award updated successfully.');
   }
    public function updatefinancialstat(Request $request){
       $input = $request->all();
      
       Companyinfo::updateOrCreate( ['session_id' => $input['session_id']],
                    ['cash_target' => $input['cash_target'], 'revenue_target' => $input['revenue_target'], 'ebitda_target' => $input['ebitda_target']]);
       return back()->with('successf', 'Financial statement target values updated successfully.');
   }
    public function awardimport(Request $request)
    {
        $this->validate($request, [
            'select_file'  => 'required|mimes:xls,xlsx'
        ]);
       
        try {
            Excel::import(new AwardImport($request->input('session_id')), request()->file('select_file'));
            $sessions = Session::all();
            foreach($sessions as $session){
                if(Award::where('session_id', $session->id)->first()){
                     $awardsum = Award::where('session_id', $session->id)->get()->sum('award');
                    Companyinfo::updateOrCreate( ['session_id' => $session->id], ['award_actual' =>$awardsum ]);
                }
               
            }
            $awards = Award::where('switch', 0)->get();
            foreach($awards as $award){
                
                $bid = new Bid;
                // if(!$projecte){
                //     $project = new Project;
                //     $project->project_name = $award->client;
                //     $project->amount = $award->award;
                //     $project->contract_sign_date = $award->contract_sign_date;
                //     $project->save();
                // }
                
                $bid->bid_name = $award->client;
                
                $bid->bid_amount = $award->award;
                
                $bid->session_id = $award->session_id;
                
                 $bid->contract_sign = $award->contract_sign_date;
                 $award->switch = 1;
                 $award->save();
                 $bid->save();
                
            }
             $bids = Bid::where('status', 1)->get();
        $new = Bid::where('status', 0)->get();
        $sessions = Bid::join('sessions', 'sessions.id', '=', 'bids.session_id')->select('session_name', 'session_id')->orderby('sessions.start_date', 'desc')->distinct()->get();
        
           
           return redirect()->action([BidsController::class, 'index']);
        } catch (Exception $e) {
          dd($e);
            return back()->with('errora', 'Import Error.');
        }
        
    }
    
    public function financialimport(Request $request)
    {
        $this->validate($request, [
            'select_file'  => 'required|mimes:xls,xlsx'
        ]);
   
        try {
            Excel::import(new FinancialImports($request->input('session_id'),  $request->input('date')), request()->file('select_file'));
           
            //  Companyinfo::updateOrCreate( ['session_id' => $request->input('session_id')],['award_actual' =>$awardsum ]);
            return back()->with('successf', 'Excel Data Imported successfully.');
        } catch (Exception $e) {
          dd($e);
            return back()->with('errorf', 'Import Error.');
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
<?php

namespace App\Http\Controllers;
ini_set('max_execution_time', 2000);
use App\Engagementdifference;
use App\Filledkpi;
use Carbon\Carbon;
use App\Engagement;
use App\Filledkpilist;
use App\FillEngagement;
use App\Kpiform;
use App\Failuretarget;
use App\Objective;
use App\Keyresult;
use App\Session;
use App\Imports\LeavesImport;
use App\Imports\AttendanceImport;

use App\Formula;
use App\Score;
use App\Hrmsdata;
use App\KpiNotice;
use App\User;
use App\Team;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade as PDF;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Gate;

use App\Leave;

class ReportsController extends Controller
{
    //
 public function showengagement()
    {
       
        $session = Session::where('status', 'Active')->first();
        $userscore = Score::where('user_id', Auth::user()->id)->where('session_id',$session->id)->first();
        $leaves = Leave::where('user_id', Auth::user()->id)->where('session_id',$session->id)->get();
        $attendance = Hrmsdata::where('user_id', Auth::user()->id)->where('session_id',$session->id)->get();
        
        if( Gate::allows('crud')){
           $leaves = Leave::where('session_id',$session->id)->get();
           $attendance = Hrmsdata::where('session_id',$session->id)->get();
       }
        $fill_engagements = FillEngagement::where('session_id', $session->id)->where('type', 0)->get();
        $sent = FillEngagement::where('issuer', Auth::user()->id)->where('session_id', $session->id)->where('type', 0)->get();
        $received = FillEngagement::where('issued_to', Auth::user()->id)->where('session_id', $session->id)->where('type', 0)->get();
        
        return view('disciplinedisplay', compact('fill_engagements', 'sent', 'session', 'userscore', 'leaves', 'received', 'attendance'));
    }
    public function engagementdisplay()
    {
       
        $session = Session::where('status', 'Active')->first();
        $userscore = Score::where('user_id', Auth::user()->id)->where('session_id',$session->id)->first();
        $leaves = Leave::where('user_id', Auth::user()->id)->where('session_id',$session->id)->get();
        $attendance = Hrmsdata::where('user_id', Auth::user()->id)->where('session_id',$session->id)->get();
        
        if( Gate::allows('crud')){
           $leaves = Leave::where('session_id',$session->id)->get();
           $attendance = Hrmsdata::where('session_id',$session->id)->get();
       }
        $fill_engagements = FillEngagement::where('session_id', $session->id)->where('type', 0)->get();
        $sent = FillEngagement::where('issuer', Auth::user()->id)->where('session_id', $session->id)->where('type', 0)->get();
        $received = FillEngagement::where('issued_to', Auth::user()->id)->where('session_id', $session->id)->where('type', 0)->get();
        
        return redirect('disciplinedisplay', compact('fill_engagements', 'sent', 'session', 'userscore', 'leaves', 'received', 'attendance'));
    }
     
    public function displayengagement()
    {
               $session = Session::where('status', 'Active')->first();
        $userscore = Score::where('user_id', Auth::user()->id)->where('session_id',$session->id)->first();
        $leaves = Leave::where('user_id', Auth::user()->id)->where('session_id',$session->id)->get();
        $attendance = Hrmsdata::where('user_id', Auth::user()->id)->where('session_id',$session->id)->get();
        if( Gate::allows('crud')){
           $leaves = Leave::where('session_id',$session->id)->get();
           $attendance = Hrmsdata::where('session_id',$session->id)->get();
       }
        $fill_engagements = FillEngagement::where('session_id', $session->id)->where('type', 0)->get();
        $sent = FillEngagement::where('issuer', Auth::user()->id)->where('session_id', $session->id)->where('type', 0)->get();
        $received = FillEngagement::where('issued_to', Auth::user()->id)->where('session_id', $session->id)->where('type', 0)->get();
        
        return view('disciplinedisplay', compact('fill_engagements', 'sent', 'session', 'userscore', 'leaves', 'received', 'attendance'));
    }
   
     public function changeengagementReport(Request $request)
    {
        $session = Session::find($request->input('session'));
        $leaves = Leave::where('user_id', Auth::user()->id)->where('session_id',$session->id)->get();
        $attendance = Hrmsdata::where('user_id', Auth::user()->id)->where('session_id',$session->id)->get();
        if( Gate::allows('crud')){
           $leaves = Leave::where('session_id',$session->id)->get();
            $attendance = Hrmsdata::where('session_id',$session->id)->get();
        }
        $userscore = Score::where('user_id', Auth::user()->id)->where('session_id',$session->id)->first();
        $fill_engagements = FillEngagement::where('session_id', $session->id)->where('type', 0)->get();
        $sent = FillEngagement::where('issuer', Auth::user()->id)->where('session_id', $session->id)->where('type', 0)->get();
        $received = FillEngagement::where('issued_to', Auth::user()->id)->where('session_id', $session->id)->where('type', 0)->get();
        return view('disciplinedisplay', compact('fill_engagements', 'sent', 'session', 'userscore', 'leaves', 'received', 'attendance'));
    }
    public function bulkdownload(Request $request){
        
        $engagements = FillEngagement::where('created_at', '>=', $request->start_date)->where('created_at', '<=', $request->end_date)->get();
   
        $pdffiles= "";
         $customPaper = array(0,0,500.00,670.80);
         $ff = 'engagement ' . Carbon::now();
          Storage::disk('local')->makeDirectory($ff);
          $path = storage_path(). '/'. $ff ;
          
         
          
        foreach($engagements as $engagement){
             $name = User::find($engagement->issued_to)->fname . " " .  User::find($engagement->issued_to)->lname;
            if(Engagement::find($engagement->engagement_id)->Perspective == 0){
           
            $pdf = PDF::loadview('certificate', ['engagement' => $engagement])->setPaper($customPaper, 'landscape');
           
        $pdf_name = $name . " Appreciation Certificate ". Carbon::parse($engagement->created_at)->format('M d Y') . ".pdf";
        $pdf->save(storage_path().'/app/'.$ff.'/'.$pdf_name );
        
        }
        
        else if(Engagement::find($engagement->engagement_id)->Perspective == 1){
               $pdf = PDF::loadView('reptemplate', compact('engagement'));
              $pdf_name = $name . " Reprimand Notice ". Carbon::parse($engagement->created_at)->format('M d Y') . ".pdf";
                 $pdf->save(storage_path().'/app/'.$ff.'/'.$pdf_name );
        }
            
        }
    // Here we choose the folder which will be used.
            $dirName = storage_path().'/app/'.$ff;

            // Choose a name for the archive.
            $zipFileName = $ff . '.zip';

            // Create "MyCoolName.zip" file in public directory of project.
            $zip = new \ZipArchive;

            if ( $zip->open( public_path() . '/' . $zipFileName, \ZipArchive::CREATE ) === true )
            {
                // Copy all the files from the folder and place them in the archive.
                foreach ( glob( $dirName . '/*' ) as $fileName )
                {
                    $file = basename( $fileName );
                    $zip->addFile( $fileName, $file );
                }

                $zip->close();
            }
        return response()->download($zipFileName);
    }
    
    public function showdiscipline($id)
    {
        $engagement = FillEngagement::find($id);

        return view('showdiscipline', compact('engagement'));
    }
     public function downloadereport($id)
    {
        $engagement = FillEngagement::find($id);
        $name = User::find($engagement->issued_to)->fname . " " .  User::find($engagement->issued_to)->lname;
        if(Engagement::find($engagement->engagement_id)->Perspective == 0){
            $customPaper = array(0,0,500.00,670.80);
            $pdf = PDF::loadview('certificate', ['engagement' => $engagement])->setPaper($customPaper, 'landscape');
            return $pdf->download($name . " Appreciation Certificate ". Carbon::parse($engagement->created_at)->format('M d Y') . ".pdf");
        }
        
        else if(Engagement::find($engagement->engagement_id)->Perspective == 1){
              $pdf = PDF::loadView('reptemplate', compact('engagement'));
               return $pdf->download($name . " Reprimand Notice ". Carbon::parse($engagement->created_at)->format('M d Y') . ".pdf");
        }
       
        
         
         
    }
    public function downloadkreport($id)
    {
        $engagement = KpiNotice::find($id);
        $name = User::find($engagement->issued_to)->fname . " " .  User::find($engagement->issued_to)->lname;
        if($engagement->type == 1){
            $customPaper = array(0,0,500.00,670.80);
            $pdf = PDF::loadview('certificatekpi', ['engagement' => $engagement])->setPaper($customPaper, 'landscape');
            return $pdf->download($name . " Appreciation Certificate ". Carbon::parse($engagement->created_at)->format('M d Y') . ".pdf");
        }
        
        else if($engagement->type == 2){
              $pdf = PDF::loadView('repkpitemplate', compact('engagement'));
               return $pdf->download($name . " Reprimand Notice ". Carbon::parse($engagement->created_at)->format('M d Y') . ".pdf");
        }
       
        
         
         
    }
    
    public function showexcellence($id)
    {
        $engagement = FillEngagement::find($id);

        return view('showexcellence', compact('engagement'));
    }
    public function kpiReport()
    {
         $session = Session::where('status', 'Active')->first();
          $userscore = Score::where('user_id', Auth::user()->id)->where('session_id',$session->id)->first();
        $fill_kpis = KpiNotice::where('session_id', $session->id)->get();
        $sent = KpiNotice::where('issuer', Auth::user()->id)->where('session_id', $session->id)->get();
        $received = KpiNotice::where('issued_to', Auth::user()->id)->where('session_id', $session->id)->get();
        
        return view('kpireportdisplay', compact('fill_kpis', 'sent', 'session', 'received', 'userscore'));
    }
     public function changekpiReport(Request $request)
    {
        $session = Session::find($request->input('session'));
         $userscore = Score::where('user_id', Auth::user()->id)->where('session_id',$session->id)->first();
        $fill_kpis = KpiNotice::where('session_id', $session->id)->get();
        $sent = KpiNotice::where('issuer', Auth::user()->id)->where('session_id', $session->id)->get();
        $received = KpiNotice::where('issued_to', Auth::user()->id)->where('session_id', $session->id)->get();
        $totalkpis = KpiNotice::where('issued_to', Auth::user()->id)->where('session_id', $session->id)->select('kpi_id')->distinct()->get();
        return view('kpireportdisplay', compact('fill_kpis', 'sent', 'session', 'received', 'userscore'));
    }
    public function showkpidiscipline($id)
    {
        $kpi =  KpiNotice::find($id);

        return view('showkpirep', compact('kpi'));
    }
     public function showkpi($id)
    {
        $engagement = KpiNotice::find($id);
        return view('showkpicert', compact(['engagement']));
    }
    public function taskReport()
    {
        $session = Session::where('status', 'Active')->first();
        $objectives = Objective::where('session_id', $session->id)->where('user_id', Auth::user()->id)->get();
        $db = Keyresult::where('tasks.isMilestone', 0)->join('tasks', 'keyresults.id', '=', 'tasks.keyresultid')->select('tasks.id', 'tasks.taskname', 'tasks.keyresultid', 'tasks.status', 'tasks.parent_task')->get();
     

        return view('mytasks', compact(['db', 'objectives','session']));
    } 
     public function changetaskReport(Request $request)
    {
        $input = $request->all();
        
        $session = Session::find($input['session-select']);
        $objectives = Objective::where('session_id', $input['session-select'])->where('user_id', Auth::user()->id)->get();
        $db = Keyresult::where('tasks.isMilestone', 0)->join('tasks', 'keyresults.id', '=', 'tasks.keyresultid')->select('tasks.id', 'tasks.taskname', 'tasks.keyresultid', 'tasks.status', 'tasks.parent_task')->get();
     

        return view('mytasks', compact(['db', 'objectives','session']));
    } 
  
    public function visualization(){
          $session = Session::where('status', 'active')->orderby('id','desc')->first();
           $mostapp = Score::where('users.active', 1)->where('scores.session_id', $session->id)->join('users', 'users.id', 'scores.user_id')->max('appcount');
           $mostengagedusers= Score::where('session_id', $session->id)->where('appcount',$mostapp )->get();
         
           $minrep = $mostengagedusers->min('repcount');
       
            $mostengaged= Score::where('session_id', $session->id)->where('appcount',$mostapp )->where('repcount', $minrep)->get();
        $user = User::where('email', Auth::user()->email)->first();
        $failuretargets = Failuretarget::all();
        $id = 0;

        if($user){
            $id = $user->id;
        }
      
        if($session){
            $sessionid = $session->id;
            $objectives = Objective::where('user_id', $id)->where('session_id',$sessionid)->get();
            $objatt = 0;
            $t =0;
            if($objectives->count()> 0){
                foreach($objectives as $ob){
                   $t += $ob->attainment;
                }
                $objatt = $t/$objectives->count();
            }
            else{

            }
            $keyresults = DB::table('keyresults')->where('objectives.user_id', $id)

            ->join('objectives', 'objectives.id', '=', 'keyresults.objective_id') ->get();
            
           
            
            
            

        }

else{
$keyresults = [];
$objatt = 0;
$objectives = [];
}
         //top performer 

        $userdata = User::where('team', 'BAI')->orderby('fname', 'asc')->where('active', 1)->get();
         $score = Score::where('session_id', $session->id)->get();
 $result = [];
        $count = 0;
        $tpscore = 0;

        foreach( $userdata as $udata){
          
         $result[$count] = [
             'user'=> $udata->fname . " ". $udata->lname,
             'tpscore' => 0
         ];  
            foreach($score as $sc){
               if($udata->id == $sc->user_id){
         $result[$count] = [
               'user'=> $udata->fname . " ". $udata->lname,
               'tpscore' => $sc->WeeklyScore
        ];

          }  
        }
        $count++;
     }
        $this->array_sort_by_column($result,"tpscore");
       



        //team performance
        $teamperf = [];
        $count = 0;
        $avscore = 0;

        
         $allteams = Team::where('isActive', 1)->where('team_name', '!=', 'Drivers')->get();
        
            foreach($allteams as $at){
                 if($at->team_name !="CEO"){
                 $teamperf[$count] = [
             'team'=> $at->team_name,
             'avscore' => 0
         ];  
                $sum = 0;
                $c = 0;
               foreach($score as $sc){
               if($at->team_name == User::find($sc->user_id)->team){
               $sum += $sc->WeeklyScore;
               $c++;

          }  
        }
         $teamperf[$count] = [
               'team'=> $at->team_name,
               'avscore' => $c==0?0:round($sum/$c, 2)
        ];
        $count++; 
                    
                }
                
            }
             $this->array_sort_by_column($teamperf,"avscore");
             $bestperformers = Engagementdifference::where('session_id', $session->id)->get()->take(5);
             
             
        return view('dashboard', compact(['keyresults', 'objatt', 'objectives', 'session','failuretargets', 'result', 'teamperf', 'mostengaged', 'bestperformers']));
    }
     function array_sort_by_column(&$arr, $col, $dir = SORT_DESC) {
    $sort_col = array();
    foreach ($arr as $key=> $row) {
        $sort_col[$key] = $row[$col];
    }

    array_multisort($sort_col, $dir, $arr);
}
static function  executeFormula($formula, $target, $weight, $actual){
     //execute formula
        $formulaarray = explode(" ", $formula->formula);
        for ($i = 0; $i < sizeof($formulaarray); $i++) {
            if ($formulaarray[$i] == "actual") {
                $formulaarray[$i] = $actual;
            } elseif ($formulaarray[$i] == "target") {
                $formulaarray[$i] = $target;
            } elseif ($formulaarray[$i] == "weight") {
                $formulaarray[$i] = $weight / 100;
            } else if ($formulaarray[$i] == "x") {
                $formulaarray[$i] = "*";
            } else if ($formulaarray[$i] == "÷") {
                $formulaarray[$i] = "/";
            }
        }
        $finalvalue = "";
        foreach ($formulaarray as $fa) {
            $finalvalue .= $fa;
        }


        $evaluatedValue = 0;
        try {
            $evaluatedValue = eval('return (' . $finalvalue . ');');
            return $evaluatedValue * 100;
        } catch (Exception $ex) {
             return $evaluatedValue * 100;
        }
}
 public function leaveimport(Request $request)
    {
        $this->validate($request, [
            'select_file'  => 'required|mimes:xls,xlsx'
        ]);
       
        try {
            Excel::import(new LeavesImport, request()->file('select_file'));
          $leaves = Leave::where('isReprimanded', 0)->get();
          foreach($leaves as $leave){
              $duration = $leave->duration;
              
              $engage = Engagement::where('Objective', 'Avoid emergency and sick leave')->first();
              for($i =0; $i< $duration; $i++){
              $engagement = new FillEngagement;
            $engagement->Reason = "You have taken an " . $leave->leave_type . " for " . $leave->duration;
           
            $engagement->Improvement = "Please Avoid emergency and sick leave";
            $engagement->Action = "Formal reminder";
            $engagement->Description = $engage->Objective;
            $engagement->CC =  86;
            
            $engagement->session_id = $leave->session_id;
            $engagement->issuer = 86;
            $engagement->issued_to = $leave->user_id;
            $engagement->engagement_id = $engage->id;
            $engagement->type = 1;
            $engagement->save();
              }
               $engagements = Engagement::all();
        $userScore = 0;
        foreach($engagements as $engagement){
            
            $fillengagements = FillEngagement::where('engagement_id',$engagement->id)->where('session_id', $leave->session_id)->where('issued_to', $leave->user_id)->get();
          
                $formula  = Formula::find($engagement->formula_id); //formula for the engagement objective
                $target = $engagement->Target; //target of the specific engagement criteria
                $weight = $engagement->Weight; //weight of the specific engagement criteria
                $actual = 0;
                   if($fillengagements->count() > 0){ 
                        $actual = $fillengagements->count();
                   }
                $score = ReportsController::executeFormula($formula, $target, $weight, $actual);
             
             $userScore += $score;
        }
     
        
                    Score::updateOrCreate(
                        ['user_id' => $leave->user_id, 'session_id' =>  $leave->session_id],
                        ['engagementScore' => $userScore]
                    );
                $leave->isReprimanded = 1;
                $leave->save();
          }
       
           return back()->with('successa', 'Imported Successfully.');
        } catch (Exception $e) {
          dd($e);
            return back()->with('errora', 'Import Error.');
        }
        
    }
     public function attendanceimport(Request $request)
    {
        $this->validate($request, [
            'select_file'  => 'required|mimes:xls,xlsx'
        ]);
       
        try {
            Excel::import(new AttendanceImport, request()->file('select_file'));
          $leaves = Hrmsdata::where('isReprimanded', 0)->get();
          foreach($leaves as $leave){
             
              $message = "Please checkin on-time";
              $reason = "You did not checkin on time";
              $engage = Engagement::where('Objective', 'Checkin on-time')->first();
              if($leave->type == 1){
                  $engage = Engagement::where('Objective', 'Be on-duty all the time')->first();
                   $reason = "You took an unplanned leave";
              }
              
              $engagement = new FillEngagement;
            $engagement->Reason = $reason;
           
            $engagement->Improvement = $message;
            $engagement->Action = "Formal reminder";
            $engagement->Description = $engage->Objective;
            $engagement->CC =  86;
            
            $engagement->session_id = $leave->session_id;
            $engagement->issuer = 86;
            $engagement->issued_to = $leave->user_id;
            $engagement->engagement_id = $engage->id;
            $engagement->type = 1;
            $engagement->save();
             
               $engagements = Engagement::all();
        $userScore = 0;
        foreach($engagements as $engagement){
            
            $fillengagements = FillEngagement::where('engagement_id',$engagement->id)->where('session_id', $leave->session_id)->where('issued_to', $leave->user_id)->get();
          
                $formula  = Formula::find($engagement->formula_id); //formula for the engagement objective
                $target = $engagement->Target; //target of the specific engagement criteria
                $weight = $engagement->Weight; //weight of the specific engagement criteria
                $actual = 0;
                   if($fillengagements->count() > 0){ 
                        $actual = $fillengagements->count();
                   }
                $score = ReportsController::executeFormula($formula, $target, $weight, $actual);
             
             $userScore += $score;
        }
     
        
                    Score::updateOrCreate(
                        ['user_id' => $leave->user_id, 'session_id' =>  $leave->session_id],
                        ['engagementScore' => $userScore]
                    );
                $leave->isReprimanded = 1;
                $leave->save();
          }
       
           return back()->with('successa', 'Imported Successfully.');
        } catch (Exception $e) {
          dd($e);
            return back()->with('errora', 'Import Error.');
        }
        
    }
    function calculateeng(){
        $users = User::where('active', 1)->get();
        foreach($users as $user){
        $engagements = Engagement::all();
        $userScore = 0;
        foreach($engagements as $engagement){
            
            $fillengagements = FillEngagement::where('engagement_id',$engagement->id)->where('session_id', 17)->where('issued_to', $user->id)->get();
          
                $formula  = Formula::find($engagement->formula_id); //formula for the engagement objective
                $target = $engagement->Target; //target of the specific engagement criteria
                $weight = $engagement->Weight; //weight of the specific engagement criteria
                $actual = 0;
                   if($fillengagements->count() > 0){ 
                        $actual = $fillengagements->count();
                   }
                $score = ReportsController::executeFormula($formula, $target, $weight, $actual);
             
             $userScore += $score;
        }
     
        
                    Score::updateOrCreate(
                        ['user_id' => $user->id, 'session_id' =>  17],
                        ['engagementScore' => $userScore]
                    );
                
          }
    
    }
}

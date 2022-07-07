<?php

namespace App\Http\Controllers;

use App\Engagement;
use App\Engagementcount;

use App\FillEngagement;
use App\Formula;
use App\Plan;
use App\Kpi;
use App\Report;
use App\Kpiform;
use App\Filledkpi;
use App\Filledkpilist;
use App\KpiNotice;
use App\User;
use App\Session;
use App\Projectcheckin;
use App\Score;
use Exception;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade as PDF;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Symfony\Component\CssSelector\Exception\SyntaxErrorException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
class FillEngagementController extends Controller
{

    public function index()
    {
        return view('dailyrcomment');
    }


    public function create()
    {
        //
    }

   public function store(Request $request)
    {


        $input = $request->all();
       
        if($input['apptype'] == 1  || $input['apptype'] == 3 ){
           $m= $this->issueenagement($input, $request);
             $message = $input['Perspective'] == 0 ? "Appreciation issued successfully":"Reprimand issued successfully";
              if($m == 0){
                return redirect()->back()
            ->with('error', "An Error has occured. Please try again");
            }
           
            return redirect()->back()
            ->with('success', $message);
        }
        else if($input['apptype'] == 2){
             $m = $this->issuekpi($request);
            if($m == 0){
                return redirect()->back()
            ->with('error', "An Error has occured. Please try again");
            }
            
            $message = $input['Perspective'] == 0  ? "Appreciation issued successfully":"Reprimand issued successfully";
            return redirect()->back()
            ->with('success', $message);
        }
         
       
       
    }
 

//drivers engagement
   
    public function filldriverenagement($request){
        
        $input = $request->all();
       
         $m= $this->issueenagement($input, $request);
             $message = $input['Perspective'] == 0 ? "Appreciation issued successfully":"Reprimand issued successfully";
              if($m == 0){
                return redirect()->back()
            ->with('error', "An Error has occured. Please try again");
            }
           
            return redirect()->back()
            ->with('success', $message);
    }
    
    
 //engagement
     public function issueenagement($input , $request){
    
        $issuedto = $request->has('plan_id')? (Report::find($input['plan_id'])->user_id ): $input['user_id'] ;
        $sessionid = $request->has('plan_id')? Plan::find(Report::find($input['plan_id'])->plan_id)->sessionid : Session::where('status', 'Active')->first()->id ;

       
        $notifiable = $issuedto;
        
      DB::transaction(
                function () use ($input, $issuedto, $sessionid, $request) {
try{
        //for excellence
        if ($input['Perspective'] == 0) {
           
                    $engagement = new FillEngagement;
                    $engagement->Reason = $input['Reason'];
                    $engagement->Description = Engagement::find($input['objective'])->Objective;
                    $engagement->CC =$request->has('cc') &&  $input['cc'] !=0? $input['cc']: 86;
                    if($request->has('plan_id')){
                        $engagement->report_id = $input['plan_id'];
                    }
                    else if($request->has('project_id')){
                        $engagement->projectcheckin_id = $input['project_id'];
                    }
                    
                    $engagement->issuer = Auth::user()->id;
                     $engagement->session_id = $sessionid;
                    $engagement->issued_to = $issuedto;
                    $engagement->engagement_id = $input['objective'];
                    $engagement->save();
        }
        
          
        //discipline
        elseif ($input['Perspective'] == 1) {
           
            $engagement = new FillEngagement;
            $engagement->Reason = $input['Reason'];
            $engagement->Improvement = $input['Improvement'];
            $engagement->Action = $input['Action'];
            $engagement->Description = Engagement::find($input['objective'])->Objective;
            $engagement->CC = $request->has('cc') &&  $input['cc'] !=0? $input['cc']: 86;
            if($request->has('plan_id')){
                        $engagement->report_id = $input['plan_id'];
                    }
                    else if($request->has('project_id')){
                        $engagement->projectcheckin_id = $input['project_id'];
                    }
            $engagement->session_id = $sessionid;
            $engagement->issuer = Auth::user()->id;
            $engagement->issued_to = $issuedto;
            $engagement->engagement_id = $input['objective'];
            $engagement->save();


           
      
         
        }
             $engagements = Engagement::all();
        $userScore = 0;
        foreach($engagements as $engagement){
            
            $fillengagements = FillEngagement::where('engagement_id',$engagement->id)->where('session_id',$sessionid)->where('issued_to', $issuedto)->get();
          
                $formula  = Formula::find($engagement->formula_id); //formula for the engagement objective
                $target = $engagement->Target; //target of the specific engagement criteria
                $weight = $engagement->Weight; //weight of the specific engagement criteria
                $actual = 0;
                   if($fillengagements->count() > 0){ 
                        $actual = $fillengagements->count();
                   }
                $score = $this->executeFormula($formula, $target, $weight, $actual);
             
             $userScore += $score;
        }
     $score = Score::where('user_id',$issuedto )->where('session_id',$sessionid )->first();
        
                    Score::updateOrCreate(
                        ['user_id' => $issuedto, 'session_id' => $sessionid],
                        ['engagementScore' => $userScore, 'appcount' =>  $input['Perspective'] == 0 ? $score->appcount  + 1: $score->appcount,  'repcount' =>  $input['Perspective'] == 1 ? $score->repcount  + 1: $score->repcount]
                    );
                }
         //Send a notifiaction when some get a reprimand 
        catch (\Exception $e) {
        return 0;
            
                }

                }); // end transaction
         

            $engagement = FillEngagement::where('engagement_id',$input['objective'])->where('session_id',$sessionid)->where('issued_to', $issuedto)->orderby('id', 'desc')->first();
        $usert =  $request->has('plan_id')? (Report::find($input['plan_id'])->user_id ): $input['user_id'];
          $issued_to = User::find($usert)->fname . " " . User::find($usert)->lname;
        $issued_by_email = Auth::user()->email;
        $issued_by = Auth::user()->fname . " " . Auth::user()->lname;
        $issued_email = User::find($usert)->email;
        $position = Auth::user()->position;

        if(is_null($position)){
            $position= 'Manager';
        }

        $data = array(
            'id' => '',
            'email' =>$issued_email,
            'name' => $issued_to,
            'Sender' => $issued_by,
            'position' => $position,


        );
       
       
 if ($input['Perspective'] == 1) {
         $pdf_name =  $issued_to . " Reprimand Notice ". Carbon::parse($engagement->created_at)->format('M d Y') . ".pdf";
     
        $pdf = PDF::loadView('reptemplate', compact('engagement'));
            
        $carbc = $request->has('cc') &&  $input['cc'] !=0? array($issued_by_email, 'meried@ienetworksolutions.com','hawi@ienetworks.co','Eyerusalem@ienetworks.co','eliyas@ienetworksolutions.com','redate@ienetworksolutions.com','biniyam@ienetworksolutions.com', User::find($input['cc'])->email): array($issued_by_email, 'meried@ienetworksolutions.com','eliyas@ienetworksolutions.com','Eyerusalem@ienetworks.co','redate@ienetworksolutions.com', 'hawi@ienetworks.co','biniyam@ienetworksolutions.com');

         $type = "Reprimand";
           
            Mail::send('emails.warning',["pass_data" => $data], function ($message) use ($pdf, $type,$issued_email,$issued_by_email,$issued_by, $carbc, $pdf_name) {
              $message->from($issued_by_email, $issued_by);

              $message->to($issued_email)->cc($carbc)->subject($type);
              
               
              $message->attachData($pdf->output(), $pdf_name);
            });
            return 1;
 } 
 
  elseif ($input['Perspective'] == 0) {
            
             $customPaper = array(0,0,500.00,670.80);
        $pdf = PDF::loadview('certificate', ['engagement' => $engagement])->setPaper($customPaper, 'landscape');;
         
           
         
           $type = "Appreciation";
         $carbc = $request->has('cc') &&  $input['cc'] !=0? array($issued_by_email, 'meried@ienetworksolutions.com','Eyerusalem@ienetworks.co','eliyas@ienetworksolutions.com','hawi@ienetworks.co','redate@ienetworksolutions.com', 'biniyam@ienetworksolutions.com', User::find($input['cc'])->email): array($issued_by_email, 'meried@ienetworksolutions.com','Eyerusalem@ienetworks.co','eliyas@ienetworksolutions.com','redate@ienetworksolutions.com','hawi@ienetworks.co','biniyam@ienetworksolutions.com');
         
         $pdf_name = $issued_to . " Appreciation Certificate ". Carbon::parse($engagement->created_at)->format('M d Y') . ".pdf";
         Mail::send('emails.Excellence',["pass_data" => $data], function ($message) use ($pdf, $type,$issued_email,$issued_by_email,$issued_by, $carbc, $pdf_name) {
             $message->from($issued_by_email, $issued_by);

          $message->to($issued_email)->cc($carbc)->subject($type);

            $message->attachData($pdf->output(), $pdf_name);
         });
           return 1;
}
       
 



    }

function executeFormula($formula, $target, $weight, $actual){
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


function deleteEngagement($id){
      $issuedto = FillEngagement::find($id)->issued_to;
      $sessionid = FillEngagement::find($id)->session_id;
      $eng = Engagement::find(FillEngagement::find($id)->engagement_id)->Perspective;
      $date = Carbon::parse(FillEngagement::find($id)->created_at)->format('M d Y');
        FillEngagement::destroy($id);
      DB::transaction(
                function () use ($id, $issuedto, $sessionid, $eng, $date) {
                    try{

   

   
    $engagements = Engagement::all();
        $userScore = 0;
        foreach($engagements as $engagement){
            
            $fillengagements = FillEngagement::where('engagement_id',$engagement->id)->where('session_id',$sessionid)->where('issued_to', $issuedto)->get();
          
                $formula  = Formula::find($engagement->formula_id); //formula for the engagement objective
                $target = $engagement->Target; //target of the specific engagement criteria
                $weight = $engagement->Weight; //weight of the specific engagement criteria
                $actual = 0;
                   if($fillengagements->count() > 0){ 
                        $actual = $fillengagements->count();
                   }
                $score = $this->executeFormula($formula, $target, $weight, $actual);
             
             $userScore += $score;
        }
       
      
     
                $score = Score::where('user_id', $issuedto)->where('session_id', $sessionid)->first();
                    Score::updateOrCreate(
                        ['user_id' => $issuedto, 'session_id' => $sessionid],
                        ['engagementScore' => $userScore , 'appcount' => $eng == 0 ? $score->appcount  - 1: $score->appcount,  'repcount' =>  $eng == 1 ? $score->repcount  - 1: $score->repcount]
                    );
                    
                   
        
                    }
                    catch(\Exception $e){
                        return redirect()->back()
            ->with('error', 'An Error has occured. Please try again');
                    }
     
                });
                  $issued_to = User::find($issuedto)->fname . " " . User::find($issuedto)->lname;
        $issued_by_email = Auth::user()->email;
        $issued_by = Auth::user()->fname . " " . Auth::user()->lname;
        $issued_email = User::find($issuedto)->email;
        $position = Auth::user()->position;
        $type =$eng == 0? "Appreciation":"Reprimand";
        if(is_null($position)){
            $position= 'Manager';
        }

        $data = array(
            'id' => '',
            'email' =>$issued_email,
            'name' => $issued_to,
            'Sender' => $issued_by,
            'position' => $position,
            'type' =>$type,
            'date' => $date
        );
                 $carbc =   array($issued_by_email, 'meried@ienetworksolutions.com','Eyerusalem@ienetworks.co','eliyas@ienetworksolutions.com','redate@ienetworksolutions.com');
                
                  Mail::send('emails.retraction',["pass_data" => $data], function ($message) use ($issued_email,$issued_by_email,$issued_by, $carbc) {
             $message->from($issued_by_email, $issued_by);

          $message->to($issued_email)->cc($carbc)->subject("Retraction");

           
         });
                return redirect()->back()
            ->with('success', 'Deleted Successfully');
}



//KPI
 public function issuekpi($request){
        
        $input = $request->all();
     
       
        $sessionid = $request->has('plan_id')? Plan::find(Report::find($input['plan_id'])->plan_id)->sessionid  : Session::where('status', 'Active')->first()->id ;
        $userid = $request->has('plan_id')?Report::find($input['plan_id'])->user_id:  $input['user_id'];
        $kpi = Kpiform::find($input['objective']);
        $ktype = Kpi::find($kpi->kpi_id)->kpi_id;
        
        DB::transaction(
            function () use ($input, $sessionid,  $userid,   $kpi, $request) {
try{
        KpiNotice::create(
            ['kpiform_id' => $kpi->id, 'kpi_id' => $kpi->kpi_id, 'session_id'=>$sessionid, 'issuer' => Auth::user()->id, 'issued_to' => $userid, 'report_id' => $request->has('plan_id')? $input['plan_id']:null , 'project_id' => $request->has('project_id')? $input['project_id']:null, 'reason' => $input['Reason'],'improvement' =>$request->has('Improvement')? $input['Improvement']:null,'action' => $request->has('Action')?$input['Action']:null, 'cc' => $request->has('cc') &&  $input['cc'] !=0? $input['cc']: 86, 'type' => $input['Perspective'] == 0 ? 1: 2]
        );
        
        
        
       
        $kpis  = Kpi::join('kpi_notices', 'kpi_notices.kpi_id', 'kpis.id')->where('issued_to', $userid)->where('session_id', $sessionid)->select('kpis.id', 'kpis.kpi_id')->distinct()->get() ;
       $sales = 0;
       $leadership = 0;
       $order = 0;
       $project = 0;
       $pc =0; $sc=0; $lc=0;$oc=0;
       foreach($kpis as $k){
           $userkpis = Kpiform::where('kpi_id', $k->id)->get();
           $kpisc = 0;
           foreach($userkpis as $userkpi){
                $count = KpiNotice::where('issued_to', $userid)->where('session_id', $sessionid)->where('kpiform_id', $userkpi->id)->get()->count();
                $formula  = Formula::find($userkpi->formula_id); //formula for the kpi objective
                $target = $userkpi->target; //target of the specific kpi criteria
                $weight = $userkpi->weight; //weight of the specific kpi criteria
                $actual = 0;
                   if($count > 0){ 
                        $actual = $count;
                   }
            $score =  $actual ==0? ($userkpi->type == 1?0:$weight):$this->executeFormula($formula, $target, $weight, $actual);
             $kpisc += $score;
             
           }
           
           if($k->kpi_id == 1 && $kpisc != 0){
               $project += $kpisc;
               $pc++;
           }
           else if($k->kpi_id == 2 && $kpisc != 0){
               $sales += $kpisc;
                $sc++;
           }
           else if($k->kpi_id == 3 && $kpisc != 0){
               $order += $kpisc;
                $oc++;
           }
           else if($k->kpi_id == 4 && $kpisc != 0){
               $leadership += $kpisc;
                $lc++;
           }
       }
      
        $total = 0;
       $count = 0;
       $salest =0; $projectt =0; $ordert =0; $leadershipt =0; 
       if($sales > 0){
            $salest = round($sales/$sc, 2);
           $total += $salest;
           $count++;
       }
        if($project > 0){
            $projectt = round($project/$pc, 2);
           $total += $projectt;
           $count++;
       }
       
       if($order > 0){
           $ordert = round($order/$oc, 2);
           $total += $ordert;
           $count++;
       }
        if($leadership > 0){
           $leadershipt = round($leadership/$lc, 2);
           $total += $leadershipt;
           $count++;
       }
       
       
       $kpiscore= 0;
       if($count > 0){
            $kpiscore = round($total/$count, 2);
       }
      $score = Score::where('user_id', $userid)->where('session_id', $sessionid)->first();
       
        Score::updateOrCreate(
            ['user_id' => $userid, 'session_id' => $sessionid],
            ['KPI_Score' =>  $kpiscore, 'project_kpi_score' => $projectt,  'order_kpi_score' => $ordert, 'sales_kpi_score' => $salest, 'leadership_kpi_score' => $leadershipt, 'appcount' =>  $input['Perspective'] == 0 ? $score->appcount  + 1: $score->appcount,  'repcount' =>  $input['Perspective'] == 1 ? $score->repcount  + 1: $score->repcount]
        );
}
         catch (\Exception $e) {
        return 0;
            
                }
            });
        $issued_to = User::find($userid)->fname . " " . User::find($userid)->lname;
        $issued_by_email = Auth::user()->email;
        $issued_by = Auth::user()->fname . " " . Auth::user()->lname;
        $issued_email = User::find($userid)->email;
        $position = Auth::user()->position;

        if(is_null($position)){
            $position= 'Manager';
        }

        $data = array(
            'id' => '',
            'email' =>$issued_email,
            'name' => $issued_to,
            'Sender' => $issued_by,
            'position' => $position,


        );
         

        $engagement = KpiNotice::where('kpiform_id', $kpi->id)->where('session_id', $sessionid)->where('issuer' , Auth::user()->id) ->where( 'issued_to', $userid)->orderby('id', 'desc')->first();
            
        $type= "Reprimand";
        if($input['Perspective'] == 0){
            $pdf_name = $issued_to . " Appreciation Certificate ". Carbon::parse($engagement->created_at)->format('M d Y') . ".pdf";
            $customPaper = array(0, 0, 500.00, 670.80);
        $pdf = PDF::loadView('certificatekpi', compact('engagement'))->setPaper($customPaper, 'landscape');
         
 //$carbonc = $issued_by_email;
       $carbonc = $request->has('cc') &&  $input['cc'] !=0? array($issued_by_email, 'meried@ienetworksolutions.com','Eyerusalem@ienetworks.co','hawi@ienetworks.co','eliyas@ienetworksolutions.com','redate@ienetworksolutions.com','biniyam@ienetworksolutions.com', User::find($input['cc'])->email): array($issued_by_email, 'meried@ienetworksolutions.com','Eyerusalem@ienetworks.co','eliyas@ienetworksolutions.com','redate@ienetworksolutions.com','hawi@ienetworks.co','biniyam@ienetworksolutions.com');
         $type = "Appreciation";
        
  
        Mail::send('emails.Excellence',["pass_data" => $data], function ($message) use ($pdf, $type,$issued_email,$issued_by_email,$issued_by, $carbonc,  $pdf_name) {
            $message->from($issued_by_email, $issued_by);

            $message->to($issued_email)->cc($carbonc)->subject($type);

            $message->attachData($pdf->output(),  $pdf_name);
        });
         return 1;
        }
        else{
             //$carbonc = $issued_by_email;
            
         $pdf = PDF::loadView('repkpitemplate', compact('engagement'));
     
          $pdf_name =  $issued_to . " Reprimand Notice ". Carbon::parse($engagement->created_at)->format('M d Y') . ".pdf";
           $carbonc = $request->has('cc') &&  $input['cc'] !=0? array($issued_by_email, 'meried@ienetworksolutions.com','Eyerusalem@ienetworks.co','hawi@ienetworks.co','eliyas@ienetworksolutions.com','redate@ienetworksolutions.com','biniyam@ienetworksolutions.com', User::find($input['cc'])->email): array($issued_by_email, 'meried@ienetworksolutions.com','Eyerusalem@ienetworks.co','eliyas@ienetworksolutions.com','redate@ienetworksolutions.com', 'hawi@ienetworks.co','biniyam@ienetworksolutions.com');
            Mail::send('emails.warning',["pass_data" => $data], function ($message) use ($pdf, $type,$issued_email,$issued_by_email,$issued_by, $carbonc,$pdf_name ) {
              $message->from($issued_by_email, $issued_by);

             $message->to($issued_email)->cc($carbonc)->subject($type);
               
               
              $message->attachData($pdf->output(), $pdf_name);
            });
            return 1;
        }

 


    }
    function deleteKpi($id){
      $issuedto = KpiNotice::find($id)->issued_to;
      $sessionid = KpiNotice::find($id)->session_id;
      $eng = KpiNotice::find($id)->type;
      $date = Carbon::parse(KpiNotice::find($id)->created_at)->format('M d Y');
      KpiNotice::destroy($id);
      DB::transaction(
                function () use ($id, $issuedto, $sessionid, $eng, $date) {
                    try{

   
    
   
    
      
      
        $kpis  = Kpi::join('kpi_notices', 'kpi_notices.kpi_id', 'kpis.id')->where('issued_to', $issuedto)->where('session_id', $sessionid)->select('kpis.id', 'kpis.kpi_id')->distinct()->get() ;
       $sales = 0;
       $leadership = 0;
       $order = 0;
       $project = 0;
       $pc =0; $sc=0; $lc=0;$oc=0;
       foreach($kpis as $k){
           $userkpis = Kpiform::where('kpi_id', $k->id)->get();
           $kpisc = 0;
           foreach($userkpis as $userkpi){
                $count = KpiNotice::where('issued_to', $issuedto)->where('session_id', $sessionid)->where('kpiform_id', $userkpi->id)->get()->count();
                $formula  = Formula::find($userkpi->formula_id); //formula for the kpi objective
                $target = $userkpi->target; //target of the specific kpi criteria
                $weight = $userkpi->weight; //weight of the specific kpi criteria
                $actual = 0;
                   if($count > 0){ 
                        $actual = $count;
                   }
                $score = $this->executeFormula($formula, $target, $weight, $actual);
             $kpisc += $score;
             
           }
           
           if($k->kpi_id == 1 && $kpisc != 0){
               $project += $kpisc;
               $pc++;
           }
           else if($k->kpi_id == 2 && $kpisc != 0){
               $sales += $kpisc;
                $sc++;
           }
           else if($k->kpi_id == 3 && $kpisc != 0){
               $order += $kpisc;
                $oc++;
           }
           else if($k->kpi_id == 4 && $kpisc != 0){
               $leadership += $kpisc;
                $lc++;
           }
       }
      
       
       $total = 0;
       $count = 0;
       if($sales > 0){
            $salest = $sales/$sc;
           $total += $salest;
           $count++;
       }
        if($project > 0){
            $projectt = $project/$pc;
           $total += $projectt;
           $count++;
       }
       if($order > 0){
           $ordert = $order/$oc;
           $total += $ordert;
           $count++;
       }
        if($leadership > 0){
           $leadershipt = $leadership/$lc;
           $total += $leadershipt;
           $count++;
       }
       
       $kpiscore= 0;
       if($count > 0){
            $kpiscore = round($total/$count, 2);
       }
      $score =Score::where('session_id', $sessionid)->where('user_id',$issuedto)->first();
       
        Score::updateOrCreate(
            ['user_id' => $issuedto, 'session_id' => $sessionid],
            ['KPI_Score' =>  $kpiscore, 'project_kpi_score' => $project,  'order_kpi_score' => $order, 'sales_kpi_score' => $sales, 'leadership_kpi_score' => $leadership,
            'appcount' =>  $eng == 0 ? $score->appcount  - 1: $score->appcount,  'repcount' =>  $eng == 1 ? $score->repcount  - 1: $score->repcount]
        );
        
                  
        
                    }
                    catch(\Exception $e){
                        return redirect()->back()
            ->with('error', 'An Error has occured. Please try again');
                    }
     
                });
                  $issued_to = User::find($issuedto)->fname . " " . User::find($issuedto)->lname;
        $issued_by_email = Auth::user()->email;
        $issued_by = Auth::user()->fname . " " . Auth::user()->lname;
        $issued_email = User::find($issuedto)->email;
        $position = Auth::user()->position;
        $type =$eng == 0? "Appreciation":"Reprimand";
        if(is_null($position)){
            $position= 'Manager';
        }

        $data = array(
            'id' => '',
            'email' =>$issued_email,
            'name' => $issued_to,
            'Sender' => $issued_by,
            'position' => $position,
            'type' =>$type,
            'date' => $date
        );
                 $carbc =   array($issued_by_email, 'meried@ienetworksolutions.com','Eyerusalem@ienetworks.co','eliyas@ienetworksolutions.com','redate@ienetworksolutions.com','biniyam@ienetworksolutions.com');
                
                  Mail::send('emails.retraction',["pass_data" => $data], function ($message) use ($issued_email,$issued_by_email,$issued_by, $carbc) {
             $message->from($issued_by_email, $issued_by);

          $message->to($issued_email)->cc($carbc)->subject("Retraction");

           
         });
                return redirect()->back()
            ->with('success', 'Deleted Successfully');
}

}

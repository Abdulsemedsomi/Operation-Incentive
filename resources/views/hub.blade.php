@extends('layouts.backend')
@section('content')
<div class="bg-info">
    <div class="bg-pattern bg-black-op-25 py-30" style="background-image: url('images/bg-pattern.png');">
        <div class="content content-full text-center">
            <!-- Avatar -->
            <!-- Personal -->
            <h1 class="h3 text-white font-w700 mb-10">
               {{$user->fname}} {{$user->lname}}
            </h1>
            <h2 class="h5 text-white-op">
                {{$user->position}}<a class="text-primary-light" href="{{ route('checkin', $team->id) }}"> {{$user->team}}</a>
            </h2>
            <!-- END Personal -->

            <!-- Actions -->
            <a type="button" class="btn btn-rounded btn-hero btn-sm btn-alt-success mb-5" href="{{ route('dailyplan', $team->id) }}">
                <i class="fa fa-plus mr-5"></i>Daily Plan
            </a>
            &nbsp;
            <a type="button" class="btn btn-rounded btn-hero btn-sm btn-alt-primary mb-5" href="{{ route('dailyreport', $team->id) }}">
                <i class="fa fa-plus mr-5"></i>Daily Report
            </a>
            <!-- END Actions -->
        </div>
    </div>
</div>
<!-- END User Info -->

<!-- Main Content -->
<div class="content">
    <div class="row gutters-tiny">
        <div class="col-xl-3">
            <a class="block block-link-shadow text-right" style="border-radius: 15px;" href="javascript:void(0)">
                <div class="block-content block-content-full clearfix bg-gd-dusk">
                    <div class="float-left mt-10 d-none d-sm-block">
                        <i class="si si-graph fa-3x text-white"></i>
                    </div>
                    <div class="font-size-h3 font-w700 text-white" data-toggle="appear" data-class="animated fadeInLeft" data-timeout="300">{{$score? $score->WeeklyScore: 0}}%</span></div>
                    <div class="font-size-sm font-w600 text-white">Average Weekly Score</div>
                </div>
            </a>
        </div>
        <div class="col-xl-3">
            <a class="block block-link-shadow text-right" href="javascript:void(0)">
                <div class="block-content block-content-full clearfix bg-gd-sea">
                    <div class="float-left mt-10 d-none d-sm-block">
                        <i class="si si-bar-chart fa-3x text-white"></i>
                    </div>
                    <div class="font-size-h3 font-w700 text-white" data-toggle="appear" data-class="animated fadeInLeft" data-timeout="300">{{$score? $score->OKR_Score: 0}}%</span></div>
                    <div class="font-size-sm font-w600 text-white">OKR Score</div>
                </div>
            </a>
        </div>
        <div class="col-xl-3">
            <a class="block block-link-shadow text-right" href="javascript:void(0)">
                <div class="block-content block-content-full clearfix bg-gd-emerald">
                    <div class="float-left mt-10 d-none d-sm-block">
                        <i class="si si-note fa-3x text-white"></i>
                    </div>
                    <div class="font-size-h3 font-w700 text-white" data-toggle="appear" data-class="animated fadeInRight" data-timeout="300">{{$score? $score->engagementScore: 60}}%</span></div>
                    <div class="font-size-sm font-w600 text-white">Engagement Score</div>
                </div>
            </a>
        </div>
        <div class="col-xl-3">
            <div id="kpi" class="carousel slide" data-ride="carousel">
                <div class="carousel-inner">
                    @php
                        $pscore = $score? $score->project_kpi_score:0;
                        $sscore =$score?$score->sales_kpi_score:0;
                        $oscore = $score?$score->order_kpi_score:0;
                        $lscore = $score?$score->leadership_kpi_score:0;

                    @endphp
                    <div class="carousel-item active">
                        <div class="block block-link-shadow text-right" href="javascript:void(0)">
                            <div class="block-content block-content-full clearfix bg-gd-corporate">
                                <div class="float-left mt-10 d-none d-sm-block">
                                    <i class="si si-bar-chart fa-3x text-white"></i>
                                </div>
                                <div class="font-size-h3 font-w700 text-white" data-toggle="appear" data-class="animated fadeInRight" data-timeout="300"></span></div>
                                <div class="font-size-h3 font-w200 text-white mt-5">KPI Score</div>
                            </div>
                        </div>
                    </div>
                    @if ($pscore!=0)
                    <div class="carousel-item">
                        <div class="block block-link-shadow text-right" href="javascript:void(0)">
                            <div class="block-content block-content-full clearfix bg-gd-corporate">
                                <div class="float-left mt-10 d-none d-sm-block">
                                    <i class="si si-bar-chart fa-3x text-white"></i>
                                </div>
                                <div class="font-size-h3 font-w700 text-white" data-toggle="appear" data-class="animated fadeInRight" data-timeout="300">{{$score? $score->project_kpi_score: 0}}%</span></div>
                                <div class="font-size-sm font-w600 text-white">Project KPI Score</div>
                            </div>
                        </div>
                    </div>
                    @endif
                    @if ($sscore!=0)
                    <div class="carousel-item">
                        <div class="block block-link-shadow text-right" href="javascript:void(0)">
                            <div class="block-content block-content-full clearfix bg-gd-corporate">
                                <div class="float-left mt-10 d-none d-sm-block">
                                    <i class="si si-bar-chart fa-3x text-white"></i>
                                </div>
                                <div class="font-size-h3 font-w700 text-white" data-toggle="appear" data-class="animated fadeInRight" data-timeout="300">{{$score? $score->sales_kpi_score: 0}}%</span></div>
                                <div class="font-size-sm font-w600 text-white">Sales KPI Score</div>
                            </div>
                        </div>
                    </div>
                    @endif
                    @if($oscore!=0)
                    <div class="carousel-item">
                        <div class="block block-link-shadow text-right" href="javascript:void(0)">
                            <div class="block-content block-content-full clearfix bg-gd-corporate">
                                <div class="float-left mt-10 d-none d-sm-block">
                                    <i class="si si-bar-chart fa-3x text-white"></i>
                                </div>
                                <div class="font-size-h3 font-w700 text-white" data-toggle="appear" data-class="animated fadeInRight" data-timeout="300">{{$score? $score->order_kpi_score: 0}}%</span></div>
                                <div class="font-size-sm font-w600 text-white">Order and Fulfilment KPI Score</div>
                            </div>
                        </div>
                    </div>
                    @endif
                    @if($lscore!=0)
                    <div class="carousel-item">
                        <div class="block block-link-shadow text-right" href="javascript:void(0)">
                            <div class="block-content block-content-full clearfix bg-gd-corporate">
                                <div class="float-left mt-10 d-none d-sm-block">
                                    <i class="si si-bar-chart fa-3x text-white"></i>
                                </div>
                                <div class="font-size-h3 font-w700 text-white" data-toggle="appear" data-class="animated fadeInRight" data-timeout="300">{{$score? $score->leadership_kpi_score: 0}}%</span></div>
                                <div class="font-size-sm font-w600 text-white">Leadership KPI Score</div>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
                @if($pscore!=0||$sscore!=0||$oscore!=0||$lscore!=0)
                <a class="carousel-control-prev" href="#kpi" role="button" data-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    <span class="sr-only">Previous</span>
                </a>
                <a class="carousel-control-next" href="#kpi" role="button" data-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    <span class="sr-only">Next</span>
                </a>
                @endif
            </div>
        </div>
    </div>
    <div class="row items-push gutters-tiny">
        <div class="col-md-6">
            <div class="block block-themed block-link-shadow" href="javascript:void(0)">
                <div class="block-header">
                    <h3 class="block-title">Today's Plan</h3>
                </div>
                <div class="block-content block-content-full example-1 scrollbar-ripe-malinka">
                   
                  
                         @if($weekplan && $weekplan->count() > 0)
                        <label  class="pull-right "><b>Tasks Achieved: <span id="taskachievedc">{{$adp}}/{{$dp}}</span></b></label>
                        @endif
                               <label></label>         
                  
                    <p>
                      
          @foreach($metric as $mv)

          <div class="row ">
              <div class="col-md-12">
                  <p class="default metric-btn" style="font-size:18px;"><b>Key Result: {{$mv->keyresult_name}} </b></p>
              </div>
          </div>
          <div  class="panel-collapse collapse-in">
              <div class="panel-body ">
                  <div class="tasks-list">
                      <ol type="1">
                           
                          @foreach($weekplan as $wp)
                              @if($wp->keyresultid == $mv->keyresultid)
                                    @php
                                        $subtasks = DB::table('dailyplans')->where('dailyplans.planid', $plan->id)->where('dailyplans.task_id', $wp->id)->join('subtasks','dailyplans.subtask_id','=', 'subtasks.id')->get();
                                        
                                    @endphp
                                   
                                    @if($subtasks->count() == 0)
                                    @php
                                    $temp = App\Tempplan::where('planid', $plan->id)->where('task_id', $wp->id)->first();
                                    @endphp
                                        <li id="task{{$wp->id}}" class=" default task-btn {{$temp?'check':' '}}" style="font-size:16px; list-style:none">
                                           
                                            <input type="checkbox" onchange="checktask(this,{{$wp->id}}, 1, {{$plan->id}}, {{$dp}})" {{$temp?'checked':' '}}>
                                            
                                        
                                            {{$wp->taskname}}
                                        </li> 
                                        
                                    @else
                                   
                                    <li class=" default task-btn" style="font-size:16px;list-style:none">{{$wp->taskname}}</li>
                                         <ul>
                                      @foreach($subtasks as $subtask)
                                        @php
                                            $stemp = App\Tempplan::where('planid', $plan->id)->where('subtask_id', $subtask->subtask_id)->first();
                                        @endphp
                                        <div class="container">
                                            <li id="subt{{$subtask->subtask_id}}" class=" default task-btn {{$stemp?'check':' '}}" style="list-style:none">
                                                
                                            <input type="checkbox"  onchange="checktask(this,{{$subtask->subtask_id}}, 2, {{$plan->id}}, {{$dp}})" {{$stemp?'checked':' '}}>
                                            
                                          
                                                {{$subtask->subtask_name}}</li>
                                        </div>
                                     @endforeach
                                    </ul>
                                    @endif
                                 
                              @endif
                          @endforeach
                      </ol>
                      
                  </div>
              </div>
               
          </div>

      @endforeach
    
                    </p>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="block block-themed block-link-shadow">
                <div class="block-header">
                    <h3 class="block-title">This Week's Plan</h3>
                </div>
                <div class="block-content block-content-full example-1 scrollbar-ripe-malinka">
                    @if($weekplann && $weekplann->count() > 0)
                        <label  class="pull-right "><b>Current Achievement: <span class = "{{$taskachieved >= 60?($taskachieved >= 85 ?'text-success': 'text-warning'): 'text-danger'}}">{{$taskachieved}}%</span></b></label>
                         <label></label> 
                        @endif
                    <p>
                        
                        @foreach($objectives as $ob)
                            <p class="card-text"><h5>Objective: {{$ob->objective_name}}</h5></p>
                            @foreach($metricvalues as $mv)
                                @if($mv->objective_id == $ob->objective_id)
                                    <div class="row">
                                        <div class="col-md-12">
                                            <b class=" default metric-btn">Key Result: {{$mv->keyresult_name}} ({{$mv->keyresult_percent}}%)</b>
                                        </div>
                                    </div>
                                    <div  class="panel-collapse collapse-in">
                                        <div class="panel-body ">
                                            <div class="tasks-list">
                                                <ol type="1">
                                                    @foreach($weekplann as $wp)
                                                        @if($wp->keyresultid == $mv->keyresultid)
                                                            <li class=" default task-btn {{$wp->status == 1? 'check':''}}">{{$wp->taskname}} ({{$wp->task_percent }}%)</li>
                                                           
                                                        @endif
                                                    @endforeach
                                                </ol>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            @endforeach
                        @endforeach
                    </p>
                </div>
            </div>
        </div>
    </div>
    @php
     $usereport= App\User::where('active', 1)->where('reportsTo', Auth::user()->fname." ".Auth::user()->lname)->first();
                                    
    @endphp
    @if (Auth::user()->id == 170)
        <hr>
            <div class="row">
                <div class="col-md-12">
                    <div class="block block-themed block-link-shadow">
                        <div class="block-header">
                            <h3 class="block-title">Latest Management Feedback</h3>
                        </div>
                        <div class="block-content block-content-full example-1 scrollbar-ripe-malinka">
                            <table class="table table-hover table-vcenter">
                                <thead>
                                    <tr>
                                        <th class="text-center" style="width: 100px;">#</th>
                                        <th style="width: 300px;">Full Name</th>
                                        <th class="text-left">Weekly Report</th>
                                        <th class="text-left">Weekly Plan</th>
                                        <th class="text-left">Daily Report</th>
                                        <th class="text-left">Daily Plan</th>
                                       
                                        
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                    $count=1;
                                  
                                    $session = App\Session::where('status', 'Active')->first();
                                    $musers= App\User::where('active', 1)->where('reportsTo', Auth::user()->fname." ".Auth::user()->lname)->get();
                                    
                                     @endphp
                                    
                                    @foreach($musers as $muser)
                                    <tr>
                                        @php
                                          $wplan = App\Plan::where('reportsTo', Auth::user()->id)->where('sessionid', $session->id)->where('plantype', 'weekly')->where('userid', $muser->id)->orderby('id', 'desc')->first();
                                            $wreport = App\Report::where('reportsTo', Auth::user()->id)->where('session_id', $session->id)->where('reporttype', 'weekly')->where('user_id', $muser->id)->orderby('id', 'desc')->first();
                                             $dplan = App\Plan::where('reportsTo', Auth::user()->id)->where('sessionid', $session->id)->where('plantype', 'daily')->where('userid', $muser->id)->orderby('id', 'desc')->first();
                                            $dreport = App\Report::where('reportsTo', Auth::user()->id)->where('session_id', $session->id)->where('reporttype', 'daily')->where('user_id', $muser->id)->orderby('id', 'desc')->first();
                                           
                                        @endphp
                                        
                                        <th class="text-center" scope="row">{{$count++}}</th>
                                        <td>{{ $muser->fname . " ". $muser->lname }}</td>
                                        <td class="d-none d-sm-table-cell">(Sent on {{ $wreport? Carbon\Carbon::parse($wreport->created_at)->isoFormat('MMMM Do YYYY, h:mm:ss a'): '-'}})  <div class="text-{{$wreport->isCommented == 1? (App\Comment::where('report_id',$wreport->id)->where('type',2)->first()?'success':'warning' ):'danger'}}"><i class="fa fa-{{$wreport->isCommented == 1? (App\Comment::where('report_id',$wreport->id)->where('type',2)->first()?'check-circle-o':'spinner' ):'times-circle-o'}}"></i> {{$wreport->isCommented == 1? (App\Comment::where('report_id',$wreport->id)->where('type',2)->first()? "Approved":"Commented"):'Not Approved'}}</div></td>
                                        <td class="d-none d-sm-table-cell">(Sent on {{ $wplan?Carbon\Carbon::parse($wplan->created_at)->isoFormat('MMMM Do YYYY, h:mm:ss a'): '-'}})<div class="text-{{$wplan->isCommented == 1? (App\Comment::where('plan_id',$wplan->id)->where('type',2)->first()?'success':'warning' ):'danger'}}"><i class="fa fa-{{$wplan->isCommented == 1? (App\Comment::where('plan_id',$wplan->id)->where('type',2)->first()?'check-circle-o':'spinner' ):'times-circle-o'}}"></i> {{$wplan->isCommented == 1? (App\Comment::where('plan_id',$wplan->id)->where('type',2)->first()?"Approved":"Commented"):'Not Approved'}}</div></td>
                                        <td class="d-none d-sm-table-cell">(Sent on {{ $dreport?Carbon\Carbon::parse($dreport->created_at)->isoFormat('MMMM Do YYYY, h:mm:ss a'): '-'}})<div class="text-{{$dreport->isCommented == 1? (App\Comment::where('report_id',$dreport->id)->where('type',2)->first()?'success':'warning' ):'danger'}}"><i class="fa fa-{{$dreport->isCommented == 1? (App\Comment::where('report_id',$dreport->id)->where('type',2)->first()?'check-circle-o':'spinner' ):'times-circle-o'}}"></i> {{$dreport->isCommented == 1? (App\Comment::where('report_id',$dreport->id)->where('type',2)->first()?"Approved":"Commented"):'Not Approved'}}</div></td>
                                       
                                       <td class="d-none d-sm-table-cell">(Sent  on {{ $dplan?Carbon\Carbon::parse($dplan->created_at)->isoFormat('MMMM Do YYYY, h:mm:ss a'): '-'}})<div class="text-{{ $dplan->isCommented == 1? (App\Comment::where('plan_id',$dplan->id)->where('type',2)->first()?'success':'warning' ):'danger'}}"><i class="fa fa-{{$dplan->isCommented == 1? (App\Comment::where('plan_id',$dplan->id)->where('type',2)->first()?'check-circle-o':'spinner' ):'times-circle-o'}}"></i> {{$dplan->isCommented == 1? (App\Comment::where('plan_id',$dplan->id)->where('type',2)->first()?"Approved":"Commented"):'Not Approved'}}</div></td>
                                       
                                    </tr>
                                    @endforeach()
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
    @endif
     <h2 class="content-heading">
        Company Targets
    </h2>
   
    <div class="row">
        <div class="col-md-6">
            <a class="block block-link-shadow" href="javascript:void(0)">
                <div class="block-content ribbon ribbon-modern ribbon-info">
                     
                    <div class="ribbon-box">
                       <?php
                            $perc =$company? $company->revenue_actual/max($company->revenue_target, 1) * 100:0;
                            echo round($perc,"2")."%";
                        ?>
                    </div>
                    <div class="row py-20">
                        <div class="col-6 text-right border-r">
                            <div class="js-appear-enabled animated fadeInLeft" data-toggle="appear" data-class="animated fadeInLeft">
                                <div class="font-size-h3 font-w600">{{$company?number_format($company->revenue_actual): 0}}</div>
                                <div class="font-size-sm font-w600 text-uppercase text-muted">Revenue</div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="js-appear-enabled animated fadeInRight" data-toggle="appear" data-class="animated fadeInRight">
                                <div class="font-size-h3 font-w600">{{$company? number_format($company->revenue_target): 0}}</div>
                                <div class="font-size-sm font-w600 text-uppercase text-muted">Target Revenue</div>
                            </div>
                        </div>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-md-6">
            <a class="block block-link-shadow" href="javascript:void(0)">
                <div class="block-content ribbon ribbon-modern ribbon-info">
                   
                    <div class="ribbon-box">
                       <?php
                            $perc = $company? $company->cash_actual/max($company->cash_target, 1) * 100:0;
                            echo round($perc,"2")."%";
                        ?>
                    </div>
                    <div class="row py-20">
                        <div class="col-6 text-right border-r">
                            <div class="js-appear-enabled animated fadeInLeft" data-toggle="appear" data-class="animated fadeInLeft">
                                <div class="font-size-h3 font-w600">{{$company? number_format($company->cash_actual): 0}}</div>
                                <div class="font-size-sm font-w600 text-uppercase text-muted">Cash Collection</div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="js-appear-enabled animated fadeInRight" data-toggle="appear" data-class="animated fadeInRight">
                                <div class="font-size-h3 font-w600">{{$company? number_format($company->cash_target): 0}}</div>
                                <div class="font-size-sm font-w600 text-uppercase text-muted">Target Cash Collection</div>
                            </div>
                        </div>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-md-6">
            <a class="block block-link-shadow" href="javascript:void(0)">
                <div class="block-content ribbon ribbon-modern ribbon-info">
                   
                    <div class="ribbon-box">
                       <?php
                            $perc = $company? $company->award_actual/max($company->award_target,1) * 100:0;
                            echo round($perc,"2")."%";
                        ?>
                    </div>
                   <div class="row py-20">
                        <div class="col-6 text-right border-r">
                            <div class="js-appear-enabled animated fadeInLeft" data-toggle="appear" data-class="animated fadeInLeft">
                                <div class="font-size-h3 font-w600">{{$company? number_format($company->award_actual): 0}}</div>
                                <div class="font-size-sm font-w600 text-uppercase text-muted">Awards</div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="js-appear-enabled animated fadeInRight" data-toggle="appear" data-class="animated fadeInRight">
                                <div class="font-size-h3 font-w600">{{$company? number_format($company->award_target): 0}}</div>
                                <div class="font-size-sm font-w600 text-uppercase text-muted">Target Awards </div>
                            </div>
                        </div>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-md-6">
            <a class="block block-link-shadow" href="javascript:void(0)">
                <div class="block-content ribbon ribbon-modern ribbon-info">
                    
                    
                     <div class="ribbon-box">
                       <?php
                            $perc = $company? $company->ebitda_actual/max($company->ebitda_target,1) * 100:0;
                            echo round($perc,"2")."%";
                        ?>
                    </div>
                   <div class="row py-20">
                        <div class="col-6 text-right border-r">
                            <div class="js-appear-enabled animated fadeInLeft" data-toggle="appear" data-class="animated fadeInLeft">
                                <div class="font-size-h3 font-w600">{{$company? number_format($company->ebitda_actual): 0}}</div>
                                <div class="font-size-sm font-w600 text-uppercase text-muted">EBTDA</div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="js-appear-enabled animated fadeInRight" data-toggle="appear" data-class="animated fadeInRight">
                                <div class="font-size-h3 font-w600">{{$company? number_format($company->ebitda_target): 0}}</div>
                                <div class="font-size-sm font-w600 text-uppercase text-muted">Target EBTDA </div>
                            </div>
                        </div>
                    </div>
                </div>
            </a>
        </div>

    </div>
    <h2 class="content-heading">
        <i class="si si-briefcase mr-5"></i>My Active Projects
    </h2>
    <div class="row items-push gutters-tiny">
        @foreach($projects as $project)
            <div class="col-md-4">
                <div class="block bg-gd-lake" style="height: 150px;">
                    <a href="{{route('projectcheckin', $project->id)}}" style="text-decoration: none; color:#575757;">
                        <div class="block-content">
                            <p class="mt-5 text-center">
                                <i class="si si-folder fa-3x text-white-op"></i>
                            </p>
                            <h5 class="font-w600 text-white text-center">{{$project->project_name}}</h5>
                        </div>
                    </a>
                </div>
            </div>
        @endforeach
    </div>
</div>
<script>
 var baseurl = window.location.protocol + "//" + window.location.host + '/pms/';
    function checktask(el,id, type, planid, total){
          $.ajaxSetup({
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
        });
        var status =0;
        //if task
        if(type == 1 ){
         if (el.checked == true){
           document.getElementById('task' + id).classList.add('check')
          } else {
             document.getElementById('task' + id).classList.remove('check')
             status =1;
          }
            
        }
        //if subtask
        else if(type == 2){
            if (el.checked == true){
           document.getElementById('subt' + id).classList.add('check')
          } else {
             document.getElementById('subt' + id).classList.remove('check')
             status =1;
          }
        }
      var formData = {
            planid: planid,
            task_id: type==1?id:null,
           subtask_id:type==2?id:null,
           status: status,
           type:type
        };
        

        var type = "POST";

        var ajaxurl = baseurl + "temporaryalter";

        $.ajax({
            type: type,
            url: ajaxurl,
            data: formData,
            dataType: "json",
            success: function(data) {
              document.getElementById('taskachievedc').innerHTML = data + '/' + total
            },
            error: function(data) {
                console.log("Error:", data.responseText);
            },
        });
    }
      
    
</script>
@endsection

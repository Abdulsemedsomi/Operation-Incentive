@extends('layouts.backend')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.3/Chart.css">
@section('content')
<div class="container">
    <div class="mt-50 mb-10 text-center">
        <h4 class="font-w500 mb-10">Management Dashboard</h4>
        <hr>
    </div>
</div>
<div class="container">
     <div class="block">
            <div class="block-content">
                <form action="{{route('changedashboard')}}" method="post">
                    @csrf
                   
                    <div class="form-group row">
                        <label class="col-12" for="example-select">Session</label>
                        <div class="col-md-5">
                            <select class="form-control" id="session-select" name="session-select">
                                @php
                                $sessions = App\Session::orderby('start_date', 'desc')->get();
                                @endphp
                                <option value="0" disabled>Please select Session</option>
                                @foreach($sessions as $s)
                                    <option value='{{$s->id}}' {{$session->id == $s->id ? "selected":""}}>{{$s->session_name}}</option>
                                @endforeach
                                
                            </select>
                        </div>
                        <button type="submit" class="btn btn-outline btn-alt-info ml-10 " >
                                    <i class="fa fa-check"></i> Change
                        </button>
                    </div>
                </form>
            </div>
        </div>
    <h2 class="content-heading">
        <i class="fa fa-bar-chart mr-5"></i>Weekly Performance
    </h2>

    <?php
    
    $sessions= App\Session::all();
    $activesession =$session;
    $weekly= App\Report::all();
    $reports= App\Report::all();
    $users = App\User::where('team', 'BAI')->orderby('fname', 'asc')->where('active', 1)->get();
    $manager = App\User::where('email', 'biruk@ienetworksolutions.com')->first();
    $ceo = App\User::where('email', 'meried@ienetworksolutions.com')->first();
    $allusers = App\User::orderby('fname', 'asc')->where('active', 1)->get(); 
    $allteams = App\Team::where('isActive', 1)->get();
    $score = App\Score::where('session_id', $session->id)->get();
    $ucount = 0;
    $rcount = 0;
    
    
    $keys = array_keys($result);
    
     $keyst = array_keys($teamperf);

    ?>
  
    

        <div class="row gutters-tiny">
        <div class="col-md-12">
            <div class="block block-rounded">
                <div class="block-content" id="weeklycontent">
                    <div class="form-group row">
                        
                        <label class="col-12" for="example-select">Employee</label>
                        <select class="form-control col-md-4" id="userweekly" name="engagement" onchange="filteruser({{$session->id}})" data-toggle="block-option" data-action="state_toggle" data-action-mode="demo">
                            <option value="0" disabled>Select Employee</option>
                          
                            @foreach($allusers as $at)
                                @if($at->position !="CEO")
                                    <option value={{$at->id}}>{{$at->fname . " " . $at->lname}}</option>
                                @endif
                            @endforeach
                            
                        </select>
                     
                        
                        
                    </div>
                    <canvas class="js-chartjs-lines" id="myChart"></canvas>
                </div>
            </div>
        </div>
    </div>
  <label class="pull-right"><span>To check out Failure analysis, <a href="{{route('failures.index')}}">click here</a></span></label>

    <h2 class="content-heading">
        <i class="si si-badge mr-5"></i>Peformance
    </h2>
    <div class="row gutters-tiny">
          <div class="col-md-6">
            <div class="block block-rounded js-appear-enabled animated fadeIn" data-toggle="appear">
                <div class="block-header bg-primary-light">
                    <h3 class="block-title text-white">Individual Performance</h3>
                </div>

                <div class="block-content scrollbar-ripe-malinka">
                    <div class="form-group row col-md-12">
                      <label class="col-12" for="example-select">Team</label>
                      <select class="form-control col-md-4" id="topscore" name="engagement" onchange="avgtopfilter({{$session->id}})" data-toggle="block-option" data-action="state_toggle" data-action-mode="demo">

                            <option value="0" disabled>Please select Team</option>
                            @foreach($allteams as $at)
                            @if($at->team_name !="CEO" && $at->team_name !="Drivers" )
                            <option value={{$at->id}} {{$at->team_name == "BAI"? "selected":""}}>{{$at->team_name}}</option>
                            @endif
                            @endforeach
    
                        </select>
                        <select class="form-control col-md-6 ml-10" id="typescore" name="typescore" onchange="avgtopfilter({{$session->id}})" data-toggle="block-option" data-action="state_toggle" data-action-mode="demo">

                           
                            <option value=1 selected>Weekly average</option>
                            <option value=2 >Engagement score</option>
                            <option value=3 >KPI score</option>
                            <option value=4 >Total average</option>
                           
    
                        </select>
                        
                    </div>
               
             
           
                 
                    <table class="table table-hover table-vcenter">
                        <thead>
                            <tr>
                                <th>Rank</th>
                                <th>Full Name</th>
                                <th>Sccore % </th>
                            </tr>
                        </thead>
                        <tbody id="topscorelist">
                            @for($i = 0; $i < count($result); $i++)
                            
                            <tr class="{{$rcount < 3? 'table-success':''}}">
                                <td>{{++$rcount}}.</td>
                                 @foreach($result[$keys[$i]] as $key => $value)
                                 @if($key == 'user')
                                    <td>{{$value}}</td>
                                @else
                                <td>{{$value}}%</td>
                                @endif
                                @endforeach
                            </tr>
                            @endfor
                        </tbody>
                    </table>
                </div>
            </div>
            <br>
            <hr>
        </div>
         <div class="col-md-6">
            <div class="block block-rounded js-appear-enabled animated fadeIn" data-toggle="appear">
                <div class="block-header bg-primary-light">
                    <h3 class="block-title text-white">Team Performance</h3>
                </div>

               
                <div class="block-content example-1 scrollbar-ripe-malinka">
                      <div class="form-group row col-md-12">
                  
                      <label class="col-12" for="example-select">Score type</label>
                        <select class="form-control col-md-6 ml-10" id="teamtypescore" name="typescore" onchange="teamperformance({{$session->id}})" data-toggle="block-option" data-action="state_toggle" data-action-mode="demo">

                           
                            <option value=1 selected>Weekly average</option>
                            <option value=2 >Engagement score</option>
                            <option value=3 >KPI score</option>
                            <option value=4 >Total average</option>
                           
    
                        </select>
                        
                   
                </div>
                 
                    <table class="table table-hover table-vcenter">
                        <thead>
                            <tr>
                                <th>Rank</th>
                                <th>Team Name</th>
                                <th>Score %</th>
                               
                            </tr>
                        </thead>
                        @php
                        $rcount = 0;
                        @endphp
                        <tbody id="teamperformancelist">
                            @for($i = 0; $i < count($teamperf); $i++)
                            
                            <tr class="{{$rcount < 3? 'table-success':''}}">
                                <td>{{++$rcount}}.</td>
                                 @foreach($teamperf[$keyst[$i]] as $key => $value)
                                 @if($key == 'team')
                                    <td>{{$value}}</td>
                                @else
                                <td>{{$value}}%</td>
                                @endif
                                @endforeach
                            </tr>
                            @endfor
                           
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        </div>
         <h2 class="content-heading">
        <i class="fa fa-comments mr-5"></i>Feedback 
    </h2>
     <div class="row gutters-tiny">
        <div class="col-md-6">
            <div class="block block-rounded js-appear-enabled animated fadeIn" data-toggle="appear">
                <div class="block-header bg-primary-light">
                    <h3 class="block-title text-white">Individual Report</h3>
                </div>
                <div class="block-content">
                    
                    <div class="form-group row">   
                      <label class="col-12" for="example-select">Team</label>
                      <select class="form-control col-md-4" id="teamengagement" name="engagement" onchange="filterteam({{$session->id}})" data-toggle="block-option" data-action="state_toggle" data-action-mode="demo">

                            <option value="0" disabled>Please select Team</option>
                            @foreach($allteams as $at)
                                @if($at->team_name !="CEO")
                                    <option value='{{$at->id}}' {{$at->team_name == "BAI"? "selected":""}}>{{$at->team_name}}</option>
                                @endif
                            @endforeach
    
                        </select>
                      
                    </div>
                </div>
                <div class="block-content example-1 scrollbar-ripe-malinka">
                    <table class="table table-hover table-vcenter">
                        <thead>
                            <tr >
                                <th class="text-center" style="width: 50px;">#</th>
                                <th>Full Name</th>
                                <th class="text-center" style="width: 100px;">Appreciation</th>
                                <th class="text-center" style="width: 100px;">Reprimand</th>
                            </tr>
                        </thead>
                        <tbody id="teamfilterlist">
                            @foreach($users as $user)
                            <tr>

                                <th class="text-center" scope="row">{{++$ucount}}.</th>
                                <td>{{$user->fname. " ". $user->lname}}</td>
                                @php
                                  $filledengapp = App\FillEngagement::where('fill_engagements.issued_to', $user->id)->where('engagements.Perspective',0)->where('session_id',$session->id)->join('engagements', 'engagements.id','=', 'fill_engagements.engagement_id')->get();
                                  $filledengrep = App\FillEngagement::where('fill_engagements.issued_to', $user->id)->where('engagements.Perspective',1)->where('fill_engagements.type', 0)->where('session_id',$session->id)->join('engagements', 'engagements.id','=', 'fill_engagements.engagement_id')->get();
                                 $kpiapp = App\KpiNotice::where('kpi_notices.issued_to', $user->id)->where('type',1)->where('session_id',$session->id)->get();
                                 $kpirep = App\KpiNotice::where('kpi_notices.issued_to', $user->id)->where('type',2)->where('session_id',$session->id)->get();
                                @endphp
                                <td class="d-none d-sm-table-cell">{{$filledengapp->count() + $kpiapp->count()}}</td>
                                <td class="d-none d-sm-table-cell">{{$filledengrep->count() + $kpirep->count()}}</td>
                            </tr>

                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <br>
            <hr>
        </div>
       
        <div class="col-md-6">
            <div class="block block-rounded js-appear-enabled animated fadeIn" data-toggle="appear">
                <div class="block-header bg-primary-light">
                    <h3 class="block-title text-white">Management Feedback report</h3>
                </div>
                <div class="block-content">
                  
                    <div class="form-group row">   
                      <label class="col-12" for="example-select">Team</label>
                      <select class="form-control col-md-4" id="teamengagement" name="engagement" onchange="mfilterteam({{$session->id}})" data-toggle="block-option" data-action="state_toggle" data-action-mode="demo">
                            @php
                                
                                $date1 = date("Y/m/d");
                                function numWeeks($dateOne, $dateTwo){
                                        //Create a DateTime object for the first date.
                                        $firstDate = new DateTime($dateOne);
                                        //Create a DateTime object for the second date.
                                        $secondDate = new DateTime($dateTwo);
                                        //Get the difference between the two dates in days.
                                        $differenceInDays = $firstDate->diff($secondDate)->days;
                                        //Divide the days by 7
                                        $differenceInWeeks = $differenceInDays / 7;
                                        //Round down with floor and return the difference in weeks.
                                        return ceil($differenceInWeeks);
                                    }
                               $diff = numWeeks($date1,$session->start_date)  
                            @endphp
                            <option value="0" disabled>Please select Week</option>
                            @for($i =0; $i< $diff; $i++)
                               
                                    <option value='{{$i}}' {{$i == $diff - 1 ? "selected":""}}>Week {{$i + 1}}</option>
                                
                            @endfor
                             <option value='t' >Total </option>

                        </select>
                      
                    </div>
                </div>
               
                <div class="block-content example-1 scrollbar-ripe-malinka">
                    
                 
                    <table class="table table-hover table-vcenter">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Full Name</th>
                                <th>Appreciation </th>
                                <th>Reprimand </th>
                            </tr>
                        </thead>
                        <tbody id="">
                            @php
                                $count = 1;
                            @endphp
                            @foreach($allusers as $at)
                            
                                
                           
                                @if($at->team == "Middle Management" || $at->team == "Executive" )
                                @php
                                 $filledengapp = App\FillEngagement::where('fill_engagements.type', 0)->where('fill_engagements.issuer', $at->id)->where('engagements.Perspective',0)->where('session_id',$session->id)->join('engagements', 'engagements.id','=', 'fill_engagements.engagement_id')->whereDate('fill_engagements.created_at', '>=', Carbon\Carbon::now()->startOfWeek()->toDateString())->get();
                                 $filledengrep = App\FillEngagement::where('fill_engagements.type', 0)->where('fill_engagements.issuer', $at->id)->where('engagements.Perspective',1)->where('session_id',$session->id)->join('engagements', 'engagements.id','=', 'fill_engagements.engagement_id')->whereDate('fill_engagements.created_at', '>=', Carbon\Carbon::now()->startOfWeek()->toDateString())->get();
                                  $kpiapp = App\KpiNotice::where('kpi_notices.issuer', $at->id)->where('type',1)->where('session_id',$session->id)->whereDate('kpi_notices.created_at', '>=', Carbon\Carbon::now()->startOfWeek()->toDateString())->get();
                                 $kpirep = App\KpiNotice::where('kpi_notices.issuer', $at->id)->where('type',2)->where('session_id',$session->id)->whereDate('kpi_notices.created_at', '>=', Carbon\Carbon::now()->startOfWeek()->toDateString())->get();
                                   @endphp  
                                    <tr>
                                        <td>{{$count++}}</td>
                                        <td>{{$at->fname . " " . $at->lname}}</td>
                                        <td>{{$filledengapp->count() +   $kpiapp->count() }}</td>
                                        <td>{{$filledengrep->count() +   $kpirep->count()}}</td>
                                    </tr>
                                @endif
                            @endforeach
                           
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
       
        
          <div class="col-md-6">
            <div class="block block-rounded js-appear-enabled animated fadeIn" data-toggle="appear">
                <div class="block-header bg-primary-light">
                    <h3 class="block-title text-white">Leave Data</h3>
                </div>
                <div class="block-content">
                    
                    <div class="form-group row">   
                      <label class="col-12" for="example-select">Team</label>
                      <select class="form-control col-md-4" id="leavedata" name="leavedata" onchange="leavedata({{$session->id}})" data-toggle="block-option" data-action="state_toggle" data-action-mode="demo">

                            <option value="0" disabled>Please select Team</option>
                            @foreach($allteams as $at)
                                @if($at->team_name !="CEO")
                                    <option value='{{$at->id}}' {{$at->team_name == "BAI"? "selected":""}}>{{$at->team_name}}</option>
                                @endif
                            @endforeach
    
                        </select>
                      
                    </div>
                </div>
                <div class="block-content example-1 scrollbar-ripe-malinka">
                    <table class="table table-hover table-vcenter">
                        <thead>
                            <tr>
                                <th class="text-center" >#</th>
                                <th style="width: 50%;">Full Name</th>
                                 <th class="text-center" >Tardy</th>
                                <th class="text-center" >Absent</th>
                                <th class="text-center" >Sick Leave</th>
                                <th class="text-center" >Unplanned Leave</th>
                                
                            </tr>
                        </thead>
                         @php
                        $ucount = 0;
                        @endphp
                        <tbody id="leavedatalist">
                            @foreach($users as $user)
                             @php
                                $unplan = App\Hrmsdata::where('user_id', $user->id)->where('type', 1)->where('session_id', $session->id)->get();
                                $tardy = App\Hrmsdata::where('user_id', $user->id)->where('type', 2)->where('session_id', $session->id)->get();
                                $ssum = App\Leave::where('user_id', $user->id)->where('leave_type', 'Sick Leave')->where('session_id', $session->id)->get();
                                $esum = App\Leave::where('user_id', $user->id)->where('leave_type', 'Emergency Annual leave')->where('session_id', $session->id)->get();
                               $stat = $tardy->count() == 0 && $unplan->count() == 0 && $ssum->count() == 0 && $esum->count() == 0 ? 'table-success': '';
                                 @endphp
                            <tr class="{{$stat}}">

                                <th class="text-center" scope="row">{{++$ucount}}.</th>
                                <td>{{$user->fname. " ". $user->lname}}</td>
                               
                                 <td class="d-none d-sm-table-cell">{{ $tardy->count()}}</td>
                                <td class="d-none d-sm-table-cell">{{ $unplan->count()}}</td>
                                <td class="d-none d-sm-table-cell">{{$ssum->count() > 0? $ssum->sum('duration'):0}}</td>
                                <td class="d-none d-sm-table-cell">{{$esum->count() > 0? $esum->sum('duration') : 0}}</td>
                            </tr>

                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <br>
            <hr>
        </div>
        @if($session->status == 'Closed')
         <div class="col-md-6">
            <div class="block block-rounded js-appear-enabled animated fadeIn" data-toggle="appear">
                <div class="block-header bg-primary-light">
                    <h3 class="block-title text-white">Top Performers</h3>
                </div>
                <div class="block-content">
                    
                    <div class="form-group row">   
                      <label class="col-12" for="example-select">Type</label>
                      <select class="form-control col-md-6" id="topperform" name="topperform" onchange="topperformers({{$session->id}})" data-toggle="block-option" data-action="state_toggle" data-action-mode="demo">

                            <option value="0" disabled>Please select Type</option>
                            
                                    <option value='1' >Best Performing staff</option>
                                     <option value='2' >Most Engaged staff</option>
                                     <option value='3' >Appreciating leader</option>
                                     <option value='4' >Strong leader</option>
                                     
                              
                          
    
                        </select>
                      
                    </div>
                </div>
                <div class="block-content example-1 scrollbar-ripe-malinka">
                    <table class="table table-hover table-vcenter">
                        <thead>
                            <tr>
                                <th class="text-center" >#</th>
                                <th style="width: 50%;">Full Name</th>
                                 <th class="text-center" >Team</th>
                                <th class="text-center" >Score</th>
                                <th class="text-center" >Criteria</th>
                              
                                
                            </tr>
                        </thead>
                         @php
                        $ucount = 0;
                        @endphp
                        <tbody id="bestperflist">
                            @foreach($bestperformers as $bestperformer)
                             @php
                                $user = App\User::find($bestperformer->user_id);
                                
                                 @endphp
                            <tr class="">

                                <th class="text-center" scope="row">{{++$ucount}}.</th>
                                <td>{{$user->fname. " ". $user->lname}}</td>
                               
                                 <td class="d-none d-sm-table-cell">{{ $user->team}}</td>
                                <td class="d-none d-sm-table-cell">{{ $bestperformer->difference}}</td>
                                  <td class="d-none d-sm-table-cell"># App - # Rep</td>
                              
                            </tr>

                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <br>
            <hr>
        </div>
        @endif
   
   
    </div>
  </div>

    


<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.3/Chart.js"></script>
<script>

  $(document).ready(function($) {
  var scores =new Array();
//   var item =new Array();

   
         var baseurl = window.location.protocol + "//" + window.location.host + '/pms/';
   
    
             var ctx = document.getElementById('myChart').getContext("2d");
                var current = document.getElementById('userweekly')
                var opt = current.options[current.selectedIndex]
              
                 var scores = []
                  var e = document.getElementById("session-select");
            var sessionid = e.options[e.selectedIndex].value;
                $.get(baseurl + "weeklyperformancefilter/"+ opt.value +"?sessionid="+sessionid, function(data) {
                    const oneDay =  1000 * 60 * 60 * 24 * 7;
                    
                    var sd = new Date(data[0].start_date)
                    var ed = new Date(data[0].end_date)
                    var diff = Math.round((ed - sd) / oneDay)
                    var today = new Date(); 
                    var tdiff = Math.round((today - sd) / oneDay)
                  console.log(tdiff)
                    if (data[1].length == 0){
                         
                    }
                    else{
                         
                         var att = []
                          var dd = sd
                       var dayofweek = dd.getDay()
                      
                    
                      if(dayofweek != 5){
                          dd = new Date(sd.getTime() + ((5-dayofweek) * 24 * 60 * 60 * 1000)); 
                          if(dayofweek > 5){
                              dd = new Date(dd.getTime() + (7 * 24 * 60 * 60 * 1000)); 
                          }
                      }
                  
                    for(var i = 0; i < tdiff; i++){
                        var days = i * 7
                        var d2 = (i * 7) + 7
                       
                       var dv = dd.getTime() + (days * 24 * 60 * 60 * 1000); 
                       ndv = new Date(new Date(dv).toDateString())
                       var fdv = dd.getTime() + (d2 * 24 * 60 * 60 * 1000); 
                       nfdv =  new Date(new Date(fdv).toDateString())
                       att[i] =0
                         for(var j = 0; j < data[1].length; j++){
                           if(i == 1){
                               
                               
                           }
                            
                             if( new Date(new Date(data[1][j].created_at).toDateString()) >= ndv &&  new Date(new Date(data[1][j].created_at).toDateString()) < nfdv ){
                                 att[i] =data[1][j].attainment 
                                 
                             }
                            
                         }
                    }
                    
                      
                
                   
                     
                    }
            
                
               
            var myChart = new Chart(ctx, {
               
                type: 'line',
                data: {
                    labels: ["1","2","3","4","5","6","7","8","9","10","11","12","13"],
                    
                    datasets: [{
                                           
                        label: "Weekly Performance",
                        borderColor: "#80b6f4",
                        pointBorderColor: "#80b6f4",
                        pointBackgroundColor: "#80b6f4",
                        pointHoverBackgroundColor: "#80b6f4",
                        pointHoverBorderColor: "#80b6f4",
                        pointBorderWidth: 10,
                        pointHoverRadius: 10,
                        pointHoverBorderWidth: 1,
                        pointRadius: 3,
                        fill: true,
                        borderWidth: 4,
            
                        data: att,
                    }]
                   
                },
                options: {
                    responsive: true,
                    legend: {
                        position: "bottom"
                    },
                    scales: {
                        yAxes: [{
                            ticks: {
                                label: "Percentage",
                                fontColor: "rgba(0,0,0,0.5)",
                                fontStyle: "bold",
                                min: 0,
                                max:100,
                                maxTicksLimit: 10,
                                padding: 20
                            },
                            gridLines: {
                                drawTicks: false,
                                display: false
                            }
            
                        }],
                        xAxes: [{
                            gridLines: {
                                zeroLineColor: "transparent"
                            },
                            ticks: {
                                padding: 20,
                                fontColor: "rgba(0,0,0,0.5)",
                                fontStyle: "bold",
                            }
                        }]
                    }
                }
            });
        
    });

 
 });
   
   function filteruser(id){
     
      
       var baseurl = window.location.protocol + "//" + window.location.host + '/pms/';
        $('#myChart').remove(); // this is my <canvas> element
        $('#weeklycontent').append('<canvas class="js-chartjs-lines" id="myChart"></canvas>');
    
         var ct = document.getElementById('myChart').getContext("2d");
                var curr = document.getElementById('userweekly')
                var opt = curr.options[curr.selectedIndex]
                
                 var scores = []
        
                 var e = document.getElementById("session-select");
            var sessionid = e.options[e.selectedIndex].value;
                $.get(baseurl + "weeklyperformancefilter/"+ opt.value +"?sessionid="+sessionid, function(data) {
                                   
                    const oneDay =  1000 * 60 * 60 * 24 * 7;
                    
                    var sd = new Date(data[0].start_date)
                    var ed = new Date(data[0].end_date)
                    var diff = Math.round((ed - sd) / oneDay)
                    var today = new Date(); 
                    var tdiff = Math.round((today - sd) / oneDay)
                    
                    if (data[1].length == 0){
                         
                    }
                    else{
                         
                         var att = []
                          var dd = sd
                       var dayofweek = dd.getDay()
                     
                    
                      if(dayofweek != 5){
                          dd = new Date(sd.getTime() + ((5-dayofweek) * 24 * 60 * 60 * 1000)); 
                          if(dayofweek > 5){
                              dd = new Date(dd.getTime() + (7 * 24 * 60 * 60 * 1000)); 
                          }
                          
                      }
                      
                     // dd = new Date(dd.getTime() + (7 * 24 * 60 * 60 * 1000)); 
                    
                    for(var i = 0; i < tdiff; i++){
                        var days = i * 7
                        var d2 = (i * 7) + 7
                       
                       var dv = dd.getTime() + (days * 24 * 60 * 60 * 1000); 
                       
                       ndv = new Date(new Date(dv).toDateString())
                       var fdv = dd.getTime() + (d2 * 24 * 60 * 60 * 1000); 
                       nfdv =  new Date(new Date(fdv).toDateString())
                    //   console.log(i + " " + ndv)
                    //   console.log(i + " " + nfdv)
                         for(var j = 0; j < data[1].length; j++){
                           
                            
                             if( new Date(new Date(data[1][j].created_at).toDateString()) >= ndv &&  new Date(new Date(data[1][j].created_at).toDateString()) < nfdv ){
                                 att[i] =data[1][j].attainment 
                                 break;
                             }
                             else{
                                 att[i] =0
                             }
                         }
                    }
                    
                      
                console.log(att)
                   
                     
                    }
            var myChart = new Chart(ct, {
               
                type: 'line',
                data: {
                    labels: ["1","2","3","4","5","6","7","8","9","10","11","12","13"],
                    
                    datasets: [{
                                           
                        label: "Weekly Performance",
                        borderColor: "#80b6f4",
                        pointBorderColor: "#80b6f4",
                        pointBackgroundColor: "#80b6f4",
                        pointHoverBackgroundColor: "#80b6f4",
                        pointHoverBorderColor: "#80b6f4",
                        pointBorderWidth: 10,
                        pointHoverRadius: 10,
                        pointHoverBorderWidth: 1,
                        pointRadius: 3,
                        fill: true,
                        borderWidth: 4,
            
                        data: att,
                    }]
                   
                },
                options: {
                    responsive: true,
                    legend: {
                        position: "bottom"
                    },
                    scales: {
                        yAxes: [{
                            ticks: {
                                label: "Percentage",
                                fontColor: "rgba(0,0,0,0.5)",
                                fontStyle: "bold",
                                min: 0,
                                max:100,
                                maxTicksLimit: 5,
                                padding: 20
                            },
                            gridLines: {
                                drawTicks: false,
                                display: false
                            }
            
                        }],
                        xAxes: [{
                            gridLines: {
                                zeroLineColor: "transparent"
                            },
                            ticks: {
                                padding: 20,
                                fontColor: "rgba(0,0,0,0.5)",
                                fontStyle: "bold",
                            }
                        }]
                    }
                }
            });
        
       
    });
    
            
    }
    
    function leavedata(sessionid){
         var baseurl = window.location.protocol + "//" + window.location.host + '/pms/';
           var teamid = $("#leavedata").children("option:selected").val()
       
        $.get("leavedata/"+ teamid +"?sessionid="+sessionid, function(data) {
           
            var team = ""
            var count = 1;
            $tpscore = 0;
            data.forEach(function(item) {
                  var stat = item.status == 1 ? 'table-success': '';
                team += '<tr class="'+stat+'"><th class="text-center" scope="row">'+ count++ + '</th><td>'+ item.user+'</td>'
                team+='<td class="d-none d-sm-table-cell">'+item.tardy+'</td><td class="d-none d-sm-table-cell">'+item.absent+'</td><td class="d-none d-sm-table-cell">'+item.ssum+'</td><td class="d-none d-sm-table-cell">'+item.unplan+'</td>'
            });
               
        
            $("#leavedatalist").html(team)
           
        });
    }
    function teamperformance(sessionid){
         var baseurl = window.location.protocol + "//" + window.location.host + '/pms/';
         var type = $("#teamtypescore").children("option:selected").val()
       
        $.get("teamperformances/"+ sessionid +"?type="+type, function(data) {
            
           
            var count = 1;
            var team =""
         
            data.forEach(function(item) {
                  var message = count < 4? 'table-success':''
                team += '<tr class="'+message+'"><th class="text-center" scope="row">'+ count++ + '</th><td>'+ item.team+'</td>'
                team+='<td class="d-none d-sm-table-cell">'+item.avscore+'%</td>'
            });
               
        
            $("#teamperformancelist").html(team)
           
        });
    }
    
    
  function topperformers(sessionid){
         var baseurl = window.location.protocol + "//" + window.location.host + '/pms/';
           var type = $("#topperform").children("option:selected").val()
       
        $.get("topperformers/"+ type +"?sessionid="+sessionid, function(data) {
           console.log(data)
            var team = ""
            var count = 1;
            $tpscore = 0;
            data.forEach(function(item) {
                var message = '# App - # Rep'
                var score = item.difference;
                if(type == 2){
                    message = 'Perfect Attendance'
                    score = 0
                }
                else if(type == 3){
                    message = 'Issued Appreciations'
                    score = item.sum
                }
                else if(type == 4){
                    message = 'Issued Reprimands'
                    score = item.sum
                }
                 else if(type == 5){
                    message = 'leave data/ # of members'
                    score = item.score
                }
                team += '<tr ><th class="text-center" scope="row">'+ count++ + '</th><td>'+ item.fname + ' ' + item.lname  +'</td>'
                team+='<td class="d-none d-sm-table-cell">'+item.team+'</td><td class="d-none d-sm-table-cell">'+ score +'</td><td class="d-none d-sm-table-cell">'+message+'</td>'
            });
               
        
            $("#bestperflist").html(team)
           
        });
    }
function onlyUnique(value, index, self) {
  return self.indexOf(value) === index;
}


</script>
<script>
    $(document).ready(function() {
    $('#performance').DataTable({
        "ordering": false,
        "info":     false,
        'pageLength': 20,
        "lengthChange": false
    });
} );
</script>

@endsection

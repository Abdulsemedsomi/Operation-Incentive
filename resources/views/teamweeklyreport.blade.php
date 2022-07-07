@extends('layouts.backend')
<!-- CSS only -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/css/bootstrap-select.css" />
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
@section('content')
<div class="container">
    <nav class="breadcrumb bg-white push">
        <a class="breadcrumb-item" href="{{ route('myteams', $team->id) }}">{{$team->team_name}}</a>
         <a class="breadcrumb-item"  href="{{ route('teamcheckin', ['teamid'=>$team->id, 'sessionid'=>$session->id]) }}" >{{$session->session_name}}</a>
        <span class="breadcrumb-item active">Weekly Report</span>
    </nav>
</div>
<div class="container">
    <h3 class="font-w500 text-center">Weekly Report Check-in</h3>
    <div class="col-md-8">
        @if($message = Session::get('success'))
        <div class="alert alert-success alert-block">
         <button type="button" class="close" data-dismiss="alert">×</button>
                <strong>{{ $message }}</strong>
        </div>
        @elseif($message = Session::get('error'))
        <div class="alert alert-danger alert-block">
            <button type="button" class="close" data-dismiss="alert">×</button>
                   <strong>{{ $message }}</strong>
           </div>
        @endif
     </div>
     
      <div>
        <?php 
        $counter = 0;
        $unreadCount = Auth::user()->unreadNotifications()->count();
        ?>
         @foreach (Auth::user()->unreadNotifications as $notifications)  
            @php
         $notfipath= "https://ienetworks.co".$notifications->data['link'] ; 
         
            if ($currentPath == $notfipath){
                
                $notifications->markAsRead();
                 ++$counter;
            } 
            @endphp
        @endforeach   
      
        
        <p hidden id="readCount">{{$counter}}</p>
    </div>
    
    <div  class="container neopad adddailycard" hidden>
        <div class="card text-center">
            <div class="card-body text-left">
                <form action = "{{ route('weeklyreports', $team->id) }}" id="submitweeklyreport"  method="post">
                    @csrf
                    <div class="form-group row">
                            <label for="reportTo" class="col-md-4 col-form-label text-md-right">Selam</label>
                            <div class="col-md-6">
                                <select class=" form-control round" id="reportTo" name="reportsTo">
                                      @foreach($users as $user)
                                    <option value="{{$user->id}}" {{$user->fname. " " . $user->lname == Auth::user()->reportsTo? 'selected': ''}}>{{$user->fname. " " . $user->lname}}</option>
                                      @endforeach
                                    
                                </select>
                            </div>
                        </div>
                        @foreach($objectives as $objs)
                            <b class="card-text" style="font-size:20px;">Objective: {{$objs->objective_name}}</b>
                            @foreach($metric as $m)
                                @if($m->objective_id == $objs->id)
                                    <div class="row card-title">
                                        <div class="col-md-8 ">
                                            <b class="default metric-btn" style="font-size:16px;" value={{$m->keyresultid}}>Key Result: {{$m->keyresult_name}} ({{$m->keyresult_percent}}%)</b>
                                        </div>

                                        <a class="task collapsed" data-toggle="collapse" data-parent="#accordion" href="#daily{{$m->keyresultid}}">
                                            <div class="col-md-2">
                                                <span class="pull-right"><i class="fa fa-caret-down"></i></span>
                                            </div>
                                        </a>
                                    </div>
                                    <span class = "border-top-0">
                                        <div id="daily{{$m->keyresultid}}" class="panel-collapse collapse show">
                                            <div class="card-body mx-2">
                                                <div>
                                                    <ul id="tasks-list{{$m->keyresultid}}">
                                                        @foreach($weekplan as $task)
                                                            @if($task->keyresultid == $m->keyresultid)
                                                                    @php
                                                                        $dailyreport = App\Dailyreport::where('task_id', $task->id)->orderby('id','desc')->first();
                                                                        $feedback="";
                                                                        if( $dailyreport){
                                                                            $feedback = $dailyreport->feedback;
                                                                        }
                                                                    @endphp
                                                                   
                                                                <li style="list-style-type:none" class="row mb-10" id="taskl{{$task->id}}">
                                                                    <label class="form-check-label col-md-6" for="customCheck{{$task->id}}">{{$task->taskname}} ( {{$task->task_percent}}%)</label>
                                                                    @if($task->status == 0)
                                                                       
                                                                        <b class="form-check-label col-md-2 text-danger" > Not Yet</b> 
                                                                        <div class="col-md-4">
                                                                            <input type="text" class="form-control round" placeholder="Reason" name="rfeedback{{$task->id}}" value="{{$feedback}}" required>
                                                                       </div>
                                                                       <div class="form-group ml-10 col-md-11 mt-10 mb-20" >
                                                                            <select class="form-control round col-md-11"  id="failyanalysis{{$task->id}}" name="failyanalysis{{$task->id}}" required>
                                                                                <option disabled selected value="">Please select reason for failure</option>
                                                                                @foreach($failuretargets as $ft)
                                                                                    <option value="{{$ft->id}}"> {{$ft->target}}</option>
                                                                                @endforeach
                                                                            </select>
                                                                    </div>
                                                                    @else
                                                                        <b class="form-check-label col-md-2 text-success" >Achieved</b>
                                                                        <div class="col-md-4">
                                                                            <input type="text" class="form-control round " placeholder="Reason" name="rfeedback{{$task->id}}" value="{{$feedback}}">
                                                                       </div>
                                                                    @endif
                                                                </li>
                                                            @endif
                                                        @endforeach
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    </span>
                                @endif
                            @endforeach
                        @endforeach
                       
                        
                        <label  class="text-center">Total: {{$taskachieved}}%</label>
                        
                        <div class=" row">
                                    <label for="cc" class="col-md-2 col-form-label text-md-right">CC</label>
                                    
                                        <select class="selectpicker" id="cc" name="cc[]" multiple >
                                          
                                              @foreach($users as $user)
                                            <option value="{{$user->id}}" >{{$user->fname. " " . $user->lname}}</option>
                                              @endforeach
                                            
                                        </select>
                                    
                                </div>

        <div class="card-body">
            <div class="row pull-right">
           <button type="submit" class="btn btn-rounded btn-outline-primary submitdayplan">Submit Weekly report</button>
            <div class="col-md-1"></div>
           <button  type="button" class="btn btn-rounded btn-outline-danger canceldayplan">Cancel</button>
            </div>
       </div>
</form>
            </div>

          </div>
        </div>
        @php
        $isdisabled = $existplan == 1? 'disabled':"";
        $title =  $existplan == 1? 'You have an unreported weekly plan':"";
        @endphp
    <div class="col text-center" {{trim(Auth::user()->team) == trim(App\Team::find($team->id)->team_name) ? '':'hidden' }}>
        <button type="button"  class="btn btn-rounded btn-outline-info min-width-125 mb-10 adddailyplanbutton" {{$isdisabled}} title='{{$title}}'>Add this week's report</button>
    </div>

    <div  class="container neopad">
        @if($plan->count() > 5)
         <form action="{{route('weeklyreport', $team->id)}}" method="get" >
            <div class="form-group row  pull-right mr-1">
               
                @php
                    $filterusers = App\User::where('team', App\Team::find($team->id)->team_name)->where('active', 1)->orderby('fname', 'asc')->get();
                @endphp
                <div >
                    <select class=" form-control" id="filteruser" name="user">
                        <option>All</option>
                          @foreach($filterusers as $user)
                        <option value="{{$user->id}}" {{$user->id == $userf? 'selected': ''}}>{{$user->fname. " " . $user->lname}}</option>
                          @endforeach
                        
                    </select>
                </div>
                 <button type="submit"  class="btn btn-rounded btn-outline-info  ml-10 ">Filter</button>
              </div>
        </form>
        @endif
         {{ $plan->links() }}
        @foreach($plan as $p)
        @php

            $objectivesp = DB::table('weeklyreports')->where('weeklyreports.report_id', $p->id)->join('tasks','weeklyreports.task_id','=', 'tasks.id')
                ->join('keyresults','tasks.keyresultid','=', 'keyresults.id')->join('objectives','keyresults.objective_id','=', 'objectives.id')->select('objectives.id', 'objective_name')->distinct()->get();

                $metric = DB::table('weeklyreports')->where('weeklyreports.report_id', $p->id)->orderby('keyresults.id', 'asc')->join('tasks','weeklyreports.task_id','=', 'tasks.id')
                ->join('keyresults','tasks.keyresultid','=', 'keyresults.id')->select('keyresultid', 'keyresult_name','keyresult_target', 'objective_id', 'keyresults.id as kid')->distinct()->get();

                $weekplan =DB::table('weeklyreports')->where('weeklyreports.report_id', $p->id)->orderby('keyresults.id', 'asc')->join('tasks','weeklyreports.task_id','=', 'tasks.id')
                ->join('keyresults','tasks.keyresultid','=', 'keyresults.id')->select('keyresultid', 'taskname', 'tasks.id', 'task_target', 'task_status', 'keyresults.id as keyresid','feedback', 'isMilestone')->get();
                $reportsTo = $p->reportsTo != null ? (App\User::find($p->reportsTo)->fname ." ". App\User::find($p->reportsTo)->lname ): App\User::find($p->userid)->reportsTo;
             
                $disciplinec = DB::table('fill_engagements')->where('report_id', $p->id)->where('Perspective', 1)->join("engagements", 'engagements.id', '=','fill_engagements.engagement_id')->get()->count();
                $excellencec = DB::table('fill_engagements')->where('report_id', $p->id)->where('Perspective', 0)->join("engagements", 'engagements.id', '=','fill_engagements.engagement_id')->get()->count();
                $comments = DB::table('comments')->where('report_id', $p->id)->where('type', 1)->get()->count();
                  $ncomments = DB::table('comments')->where('report_id', $p->id)->where('type', 2)->first();
                $taskachieved = 0;
                $failures = App\Failure::where('report_id',$p->id)->get();
                $failuretargets = App\Weeklyreport::where('report_id', $p->id)->whereNotNull('failurereason_id')->select('failurereason_id')->distinct()->get();
                $disciplinekc = DB::table('kpi_notices')->where('report_id', $p->id)->where('type', 2)->get()->count();
                $excellencekc = DB::table('kpi_notices')->where('report_id', $p->id)->where('type', 1)->get()->count();
               $fps = 0;
            foreach($weekplan as $wp){

                    if($wp->task_status == 1){
                        $taskachieved+= $wp->task_target;
                    }
                }
 $fullname = $p->fname . " " . $p->lname;


        @endphp
    <div class="card text-center ribbon ribbon-success">
         {{-- For Appericiation --}}
                @if($excellencec + $excellencekc> 0)
                <div class="ribbon-box ">
                    <b>{{$excellencec + $excellencekc}} </b><i class="si fa-fw si-badge "></i>
                </div>
                @endif
                @if($disciplinec + $disciplinekc >0)
                {{-- For Reprimand --}}
                <div class="ribbon-box2">
                    <b>{{$disciplinec + $disciplinekc}} </b><i class="fa fa-fw fa-bomb "></i>
                </div>
                @endif
        <div class="card-body text-right" style="padding-bottom: 0%;">
            <div class="row pull-right">
                <div class="text-muted mr-10">
                    <span class="floar-right">{{$p->isEdited == 0 ? Carbon\Carbon::parse($p->created_at)->isoFormat('MMMM Do YYYY, h:mm:ss a')  :"Updated at ". Carbon\Carbon::parse($p->created_at)->isoFormat('MMMM Do YYYY, h:mm:ss a') }}</span>
                </div>
                @php
                @endphp
                  @if(Auth::user()->id == $p->user_id && (!$ncomments || Auth::user()->id == 389) )
                <div class="dropdown">
                    <button type="button" class="btn-block-option" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="fa fa-fw fa-ellipsis-v"></i>
                    </button>
                    <div class="dropdown-menu dropdown-menu-right" style="">
                        <a class="dropdown-item" href="{{ route('weeklyreport.edit' , $p->id) }}">
                            <i class="si fa-fw si-pencil mr-5" ></i>Edit
                        </a>
                        <a class="dropdown-item" href="#">
                            <i class="si fa-fw si-trash mr-5"></i>Delete
                        </a>
                    </div>
                </div>
                @endif
            </div>
        </div>
        <div class="card-body text-left">
         
             <div class ="row mb-20" style="margin-top:-3%;">
                <div class="img-link col-md-1" href="" data-toggle="modal" data-target="#modal-normal">
                    @if(App\User::find($p->user_id)->avatar == null)
                        <div class="aacircle" style="--avatar-size: 3rem;   background-color: #{{$p->avatarcolor}} !important;">
                            <span class="aainitials">{{$p->fname[0] . $p->lname[0]}}</span>
                        </div>
                    @else
                        <img src="https://ienetworks.co/pms/uploads/avatars/{{ App\User::find($p->user_id)->avatar }}" style="width:3rem; height3rem; border-radius:50%;">
                    @endif
                </div>
              <h4 class="card-title col-md-7 mt-10" style="margin-left:-2%;"><a href="" style="text-decoration:none">{{$p->fname . " " . $p->lname}}</a></h4>
            </div>
          <h5 class="card-text" style="font-weight:bold; padding-top:0.5rem;">Selam <a href="" style="text-decoration: aqua">{{$reportsTo !=null ?$reportsTo:"Manager"}}</a></h5>
          
          @foreach($objectivesp as $objs)
          <b class="card-text"style="font-size:20px;">Objective: {{$objs->objective_name}}</b>
          @foreach($metric as $m)
              @if($m->objective_id == $objs->id)
                  <div class="row card-title">
                      <div class="col-md-12 ">
                          <b class="default metric-btn" style="font-size:16px;">Key Result: {{$m->keyresult_name}} ({{$m->keyresult_target}}%)</b>
                      </div>

                      <a class="task collapsed" data-toggle="collapse" data-parent="#accordion" href="#daily{{$m->keyresultid}}">

                      </a>
                  </div>
                  <span class = "border-top-0">
                      <div id="daily{{$m->keyresultid}}" class="panel-collapse collapse show">
                          <div class="card-body mx-2">
                              <div>
                                  <ol type="1" >
                                     
                                      @foreach($weekplan as $task)
                                   
                                          @if($task->keyresultid == $m->keyresultid)
                                              <li  class="row default task-btn" id="taskl{{$task->id}}">
                                                  <label class="form-check-label col-md-8" for="customCheck{{$task->id}}">{{$task->taskname}} ( {{$task->task_target}}%)
                                                  @if($task->isMilestone == 1) 
                                                      <i class="fa fa-star"></i>
                                                      @endif
                                                  </label>
                                                  @if($task->task_status == 0)
                                                      <b class="form-check-label col-md-3 text-danger" > Not Yet {{$task->feedback !=null?"(". $task->feedback. ")":""}}</b>
                                                  @else
                                                      <b class="form-check-label col-md-3 text-success" >Achieved {{$task->feedback !=null?"(".$task->feedback. ")":""}}</b>
                                                  @endif
                                              </li>
                                          @endif
                                      @endforeach
                                  </ol>
                              </div>
                          </div>
                      </div>
                  </span>
              @endif
          @endforeach
      @endforeach
        @if($failures->count() >0)
        <label  class="text-center"><h5>Failure Analysis</h5></label>
         <div class="form-group ">
        @foreach($failures as $f)
       
                <div class="row">
                                <div class="col-md-6">
                                    <label>{{$f->reason}}</label>
                                </div>
                                <div class="col-md-2">
                                   <label  class="form-control"> {{$f->percent}}</label>
                                </div>
                                
                            
                            
                        </div>
                        
        @endforeach
        </div>
        @elseif ($failuretargets->count() >0)
         <label  class="text-center"><h5>Failure Analysis ({{100 - $taskachieved}}%)</h5></label>
         <div class="form-group ">
           
        @foreach($failuretargets as $f)
           
                <div class="row">
                                <div class="col-md-6">
                                    <label>{{App\Failuretarget::find($f->failurereason_id) ?App\Failuretarget::find($f->failurereason_id)->target:"Other" }}</label>
                                </div>
                                <div class="col-md-2">
                                   <label  class="form-control">{{ App\Weeklyreport::where('report_id', $p->id)->where('failurereason_id', $f->failurereason_id)->get()->count() > 0 ? App\Weeklyreport::where('report_id', $p->id)->where('failurereason_id', $f->failurereason_id)->get()->sum('task_target'):0}}
 </label>
                                </div>
                                
                            
                            
                        </div>
                        
        @endforeach
        </div>
        @endif
        
      <label  class="text-center">Total: {{$taskachieved}}%</label>
 @if($p->cc !=null)
            <h6 class="card-text" style="padding-top:0.5rem;">CC:
             @php
                $ccarr = explode(",",$p->cc)
                @endphp
                @foreach($ccarr as $c)
                    <a href="" style="text-decoration: aqua">{{App\User::find((int)$c)?App\User::find((int)$c)->fname . " ". App\User::find((int)$c)->lname:""}}</a>
                @endforeach
            </h6>
            
            @endif
             @if($ncomments)
            @php
            $emoji = "&#128077;";
               if($ncomments->comment == "Noted with concern") {
                   $emoji ="&#128560;";
               }
               else if($ncomments->comment == "Good Job"){
                   $emoji = "&#128079;";
               }
            @endphp
            <div class="container" >
                <a class="btn btn-rounded mr-10 btn-outline-info mr-5 mb-5 ancdisabled float-right"> {!! $emoji !!} {{$ncomments->comment}}</a>
            </div>
         @elseif(Auth::user()->id == $p->reportsTo)
        <div class="container" id="resultdiv{{$p->id}}" style="display:none">
        <a class="btn btn-rounded mr-10 btn-outline-info mr-5 mb-5 ancdisabled float-right" id="resutlcbut{{$p->id}}"> <label id="ctext{{$p->id}}"></label></a>
            </div>
            <div class="container" id="boostdiv{{$p->id}}">
                <div class="row pull-right" onclick="myFunction({{$p->id}})">
                    <button class="btn btn-sm btn-circle btn-outline-success nb mr-30 mb-5" ><i class="si si-rocket" aria-role="presentation" aria-label="Noted"></i></button>
                </div>
                <div class="text-left pull-right mt-20" id="reaction{{$p->id}}" hidden>

                    <ul><a class="btn btn-circle mr-10 btn-outline-info" onclick="addcomment({{$p->id}}, 'Noted')" aria-role="presentation" aria-label="Noted" id="Noted{{$p->id}}"></a>Noted</ul>

                    <ul><button class="btn btn-circle mr-10 btn-outline-info" onclick="addcomment({{$p->id}}, 'Noted with concern')"  id="Noted2{{$p->id}}"></button>Noted with concern</ul>
                    <ul><button class="btn btn-circle mr-10 btn-outline-info" onclick="addcomment({{$p->id}}, 'Good Job')"  id="Sounds{{$p->id}}"></button>Good Job</ul>

                </div>
            </div>
            
        @endif
          @if( Auth::user()->position == "CEO" || Auth::user()->id == 170 || (Auth::user()->team != $team->team_name && Gate::any(['fillkpiteam', 'fillengageteam'])))
                 <div class="mt-20" >
                    <div class="" style="margin-bottom: -15px;">
                        <div class="row col-md-6">
                                    <div class="col-md-2 col-sm-1 col-xs-1">
                                        <button type="button" class="btn btn-circle btn-outline-success mr-5 mb-5" onclick="app({{$p->id}})" id="app{{$p->id}}"><i class="si fa-fw si-badge "></i></button>
                                    </div>
                                    <div class="col-md-2 col-sm-1 col-xs-1">
                                        <button type="button" class="btn btn-circle btn-outline-danger mr-5 mb-5" onclick="rep({{$p->id}})" id="rep{{$p->id}}"><i class="fa fa-fw fa-bomb "></i></button>
                                    </div>
                        </div>
                                <div class="text-left ml-10" id="appb{{$p->id}}" style="display: none;">
                                    <button type="button" class="btn btn-sm btn-outline-success mb-5" onclick="launchAppreciationmodal('{{$p->id}}','{{$fullname}}', 1);">KPI</i></button>
                                    <button type="button" class="btn btn-sm btn-outline-success mb-5" onclick="launchAppreciationmodal('{{$p->id}}','{{$fullname}}', 2);">Engagement</i></button>
                                </div>
                                <div class="text-left ml-10" id="repb{{$p->id}}" style="display: none;">
                                    <button type="button" class="btn btn-sm btn-outline-danger mb-5" onclick="launchReprimandmodal('{{$p->id}}','{{$fullname}}', 1);">KPI</i></button>
                                    <button type="button" class="btn btn-sm btn-outline-danger mb-5" onclick="launchReprimandmodal('{{$p->id}}','{{$fullname}}', 2);">Engagement</i></button>
                                </div> 
                    
                
                    </div>
                 </div>
            @endif 
        </div>
        
        <div class="card-body">
            <div class="row pull-right">
           <a type="submit" class="btn btn-rounded btn-outline-info min-width-125" href="{{route('weeklyrcomment', $p->id)}}">
            @if($comments > 1)
            {{$comments}} comments
              @elseif($comments > 0)
            {{$comments}} comment
            @else
            Discuss
             @endif

           </a>
            <div class="col-md-1"></div>

            </div>
       </div>
      </div>
      @endforeach
      {{ $plan->links() }}
    </div>
    @include('includes.apprepmodal')
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.bundle.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/js/bootstrap-select.min.js"></script>
<script>
 function app(userid) {
  var x = document.getElementById("appb" + userid);
    var y = document.getElementById("repb" + userid);
  if (x.style.display === "none") {
    x.style.display = "block";
    y.style.display = "none";
  } else {
    x.style.display = "none";
    y.style.display = "none";
  }
}
function rep(userid) {
  var x = document.getElementById("repb" + userid);
   var y = document.getElementById("appb" + userid);
  if (x.style.display === "none") {
    x.style.display = "block";
    y.style.display = "none";
  } else {
    x.style.display = "none";
    y.style.display = "none";
  }
}
    function myFunction(planid) {
    $("#Noted" + planid).html("&#128077;");
    $("#Noted2" + planid).html("&#128560;");
    $("#Sounds" + planid).html("&#128079;");
    document.getElementById("reaction"+planid).hidden = false;

}
function addcomment(planid, comment) {
     var baseurl = window.location.protocol + "//" + window.location.host + '/pms/';
   $.ajaxSetup({
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
        });


        formData = {
                report_id: planid,
                comment: comment,
            };

        var type = "POST";

        var ajaxurl = baseurl + "dailyrncomment";

        $.ajax({
            type: type,
            url: ajaxurl,
            data: formData,
            dataType: "json",
            success: function (data) {
                if(data == 1){

                      $("#resutlcbut" + planid).html("&#128077;" + " " + comment);
                        if(comment == "Noted with concern") {
                           $("#resutlcbut" + planid).html("&#128560;"  + " " + comment);
                        }
                        else if(comment == "Good Job"){
                            $("#resutlcbut" + planid).html("&#128079;"  + " " + comment);

                        }

                    document.getElementById('boostdiv'+planid).style.display = 'none';
                   // document.getElementById('ctext'+planid).innerHTML = comment;
                    document.getElementById('resultdiv'+planid).style.display = 'block';


                }
            },
            error: function (data) {
                console.log("Error:", data.responseText);
            },
        });
}
</script>

 <script>
  var mark = document.getElementById("readCount").innerHTML;
           
            console.log(mark);  
            
              $('.mark-as-read').ready(function(){
                var cou = document.getElementById('counter').innerHTML;
                    cou=cou-mark;
                    document.getElementById('counter').innerHTML=cou;
            });
            
    var newmark = document.getElementById('readCount').innerHTML=0;
    console.log(newmark);      
    
 </script>

@endsection

@extends('layouts.backend')
<!-- CSS only -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/css/bootstrap-select.css" />
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
@section('content')
<div class="container">
    <nav class="breadcrumb bg-white push">
         <a class="breadcrumb-item" href="{{ route('checkin', $team->id) }}">{{$team->team_name}}</a>
        <a class="breadcrumb-item" href="{{ route('checkin', $team->id) }}">Check-in</a>
        <span class="breadcrumb-item active">Daily Report</span>
    </nav>
</div>
<div class="container">
    <h3 class="font-w500 text-center">Daily Report Check-in</h3>
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

    <div class="container neopad addreportcard" hidden>
        <div class="card text-center" >
            <div class="card-body text-left">
                <form action = "{{ route('dailyreports', $team->id) }}"   method="post">
                    @csrf
                    <div class="form-group row">
                            <label for="reportTo" class="col-md-4 col-form-label text-md-right">Selam</label>
                            <div class="col-md-6">
                                <select class=" form-control" id="reportTo" name="reportsTo">
                                      @foreach($users as $user)
                                    <option value="{{$user->id}}" {{$user->fname. " " . $user->lname == Auth::user()->reportsTo? 'selected': ''}}>{{$user->fname. " " . $user->lname}}</option>
                                      @endforeach
                                    
                                </select>
                            </div>
                        </div>
                    @foreach($metric as $m)
                        <div class="card">
                            <div class="card-header" id="headingFour{{$m->keyresultid}}">
                                <div class="row">
                                    <a class="col-md-5"><b>Key Result: </b>{{$m->keyresult_name}}</a>
                                    <p class="ml-50 mr-60 mt-5"><i class="si si-flag mr-10 text-success"></i>{{$weekplan->where('keyresultid',$m->keyresultid)->count()}} Tasks</p>
                                    <p class="ml-50 ml-50 mt-5" style="margin-right: 16em;"><i class="fa fa-tasks mr-10 text-warning"></i>{{$subts ? $subts->where('keyresultid', $m->keyresultid)->count():0}} Subtasks</p>
                                    <a class="btn btn-sm ml-50 mt-5" type="button" data-toggle="collapse" data-target="#collapseFour{{$m->keyresultid}}" aria-expanded="false" aria-controls="collapseFour">
                                    <i class="si si-arrow-down"></i>
                                    </a>
                                </div>
                            </div>
                            <div id="collapseFour{{$m->keyresultid}}" class="collapse show" aria-labelledby="headingFour{{$m->keyresultid}}" data-parent="#accordionExample">
                                <div class="card-body">
                                   
                                    @foreach($weekplan as $task)
                                        @if($task->keyresultid == $m->keyresultid)
                                            <div class="container">
                                                 @php
                                                    $subts = DB::table('dailyplans')->where('dailyplans.planid', $task->did)->where('task_id', $task->id)->join('subtasks','dailyplans.subtask_id','=', 'subtasks.id')->get();
                                                @endphp
                                                <div class="row mb-10">
                                                    @if($subts->count() ==0)
                                                     @php
                                                        $temp = App\Tempplan::where('planid', $task->did)->where('task_id', $task->id)->first();
                                                     @endphp
                                                    <div class="col-md-3">
                                                        <label class="css-control-sm css-control-info css-checkbox css-checkbox-rounded">
                                                            <span class="css-control-indicator"></span> {{$task->taskname}}
                                                        </label>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <a class="mt-5" style="margin-right: 3em;"><span class="dot mt-5"></span> {{ App\Task::find($task->id)->isMilestone == 0 ?App\Task::find($task->parent_task)->taskname: ""}}</a>
                                                    </div>
                                                        <div class="col-md-3">
                                                            <label class="css-control-sm css-control-success css-radio mt-5">
                                                                <input type="radio" class="css-control-input" name="radio-group{{$task->id}}" onchange="taskstatuscheck({{$task->id}})"  value=1 {{$temp?'checked':' '}}>
                                                                <span class="css-control-indicator"></span> Achieved
                                                            </label>
                                                            <label class="css-control-sm css-control-danger css-radio mt-5">
                                                                <input type="radio" class="css-control-input" name="radio-group{{$task->id}}" value=0 onchange="taskstatuscheck({{$task->id}})" {{$temp?'':'checked'}}>
                                                                <span class="css-control-indicator"></span> Not Yet
                                                            </label>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <input type="text" class="form-control round" placeholder="Reason" name="feedback{{$task->id}}" id="tfeedback{{$task->id}}"  {{$temp?'':'required'}} >
                                                        </div>
                                                    @else
                                                    <div class="col-md-3">
                                                        <label class="css-control-sm css-control-info css-checkbox css-checkbox-rounded">
                                                            <span class="css-control-indicator"></span> {{$task->taskname}}
                                                        </label>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <a class="mt-5" style="margin-right: 3em;"><span class="dot mt-5"></span> {{ App\Task::find($task->id)->isMilestone == 0 ?App\Task::find($task->parent_task)->taskname: ""}}</a>
                                                    </div>
                                                        @foreach($subts as $subtask)
                                                         @php
                                                            $stemp = App\Tempplan::where('planid', $task->did)->where('subtask_id', $subtask->subtask_id)->first();
                                                        @endphp
                                                        <div class="container">
                                                            <div class="container ml-10">
                                                                <div class="row">
                                                                    <div class="col-md-5">
                                                                        <label class="css-control-sm css-control-info css-checkbox css-checkbox-rounded">
                                                                        
                                                                            <span class="css-control-indicator"></span> {{$subtask->subtask_name}}
                                                                        </label>
                                                                    </div>
                                                                    <div class="col-md-3">
                                                                        <label class="css-control-sm css-control-success css-radio mt-5">
                                                                            <input type="radio" class="css-control-input" name="subradio-group{{$subtask->id}}" onchange="substatuscheck({{$subtask->id}})"  value=1 {{$stemp?'checked':' '}}>
                                                                            <span class="css-control-indicator"></span> Achieved
                                                                        </label>
                                                                        <label class="css-control-sm css-control-danger css-radio mt-5">
                                                                            <input id="failedsubradio-group{{$subtask->id}}" type="radio" class="css-control-input" name="subradio-group{{$subtask->id}}" onchange="substatuscheck({{$subtask->id}})"  value=0 {{$stemp?'':'checked'}}>
                                                                            <span class="css-control-indicator"></span> Not Yet
                                                                        </label>
                                                                    </div>
                                                                   
                                                                    <div class="col-md-3">
                                                                        <input type="text" class="form-control round" placeholder="Reason" name="subfeedback{{$subtask->id}}" id="subfeedback{{$subtask->id}}" {{$stemp?'':'required'}}>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        @endforeach
                                                    
                                                    @endif
                                                </div>
                                            </div>
                                        @endif
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endforeach
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
                            <button type="submit" class="btn btn-rounded btn-outline-primary submitreportplan" >Submit Daily report</button>
                            <div class="col-md-1"></div>
                            <button  type="button" class="btn btn-rounded btn-outline-danger cancelreportplan">Cancel</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @php
    $isdisabled = $existplan == 1? 'disabled':"";
    $title =  $existplan == 1? 'Please submit daily plan first':"";
    @endphp

    <div class="col text-center" {{ trim(Auth::user()->team) == trim(App\Team::find($team->id)->team_name) ? '':'hidden' }}>
        <button type="button"  class="btn btn-rounded btn-outline-info min-width-125 mb-10 adddailyreportbutton" {{$isdisabled}} title='{{$title}}'>Add Today's report</button>
    </div>
    <br>
    @if($count > 10)
        <form action="{{route('dailyreport', $team->id)}}" method="get" >
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
                 <button type="submit"  class="btn btn-rounded btn-outline-info ml-10 ">Filter</button>
              </div>
        </form>
        @endif
    <div class="container neopad" style="margin-top:4%">
        
         {{ $plan->links() }}
        @foreach($plan as $p)
            @php
                $metricvalues = DB::table('dailyreports')->where('report_id', $p->id)->join('tasks', 'dailyreports.task_id', '=', 'tasks.id')->join('keyresults', 'tasks.keyresultid', '=', 'keyresults.id')->select('keyresultid', 'keyresult_name')->distinct()->get();
                $weekplan = DB::table('dailyreports')->where('report_id', $p->id)->join('tasks','dailyreports.task_id','=', 'tasks.id')->orderby('tasks.id','asc')->select('tasks.id', 'taskname', 'keyresultid','dailyreports.status', 'feedback')->groupby('tasks.id')->distinct()->get();
                $teammanager= DB::table('teams')->where('team_name', $p->team)->first()->manager_id;
               
                $reportsTo = $p->reportsTo != null ? (App\User::find($p->reportsTo)->fname ." ". App\User::find($p->reportsTo)->lname ): App\User::find($p->userid)->reportsTo;
              
                $comments = DB::table('comments')->where('report_id', $p->id)->where('type', 1)->get()->count();
                  $ncomments = DB::table('comments')->where('report_id', $p->id)->where('type', 2)->first();
                $disciplinekc = DB::table('kpi_notices')->where('report_id', $p->id)->where('type', 2)->get()->count();
                $excellencekc = DB::table('kpi_notices')->where('report_id', $p->id)->where('type', 1)->get()->count();
                
                $disciplinec = DB::table('fill_engagements')->where('report_id', $p->id)->where('Perspective', 1)->join("engagements", 'engagements.id', '=','fill_engagements.engagement_id')->get()->count();
                $excellencec = DB::table('fill_engagements')->where('report_id', $p->id)->where('Perspective', 0)->join("engagements", 'engagements.id', '=','fill_engagements.engagement_id')->get()->count();
                $fullname = $p->fname . " " . $p->lname;
            @endphp
            <div id="dailyreport{{$p->id}}">
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
                <div class="card-body text-left">
                    <div class="card-body text-right" style="padding-bottom: 0%;">
                        <div class="row pull-right">
                            <div class="text-muted mr-10">
                                <span class="floar-right">{{$p->isEdited == 0 ? Carbon\Carbon::parse($p->created_at)->isoFormat('MMMM Do YYYY, h:mm:ss a')  :"Updated at ". Carbon\Carbon::parse($p->created_at)->isoFormat('MMMM Do YYYY, h:mm:ss a') }}</span>
                            </div>
                             @if(Auth::user()->id == $p->user_id && !$ncomments)
                            <div class="dropdown">
                                <button type="button" class="btn-block-option" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="fa fa-fw fa-ellipsis-v"></i>
                                </button>
                                <div class="dropdown-menu dropdown-menu-right" style="">
                                    <a class="dropdown-item" href="{{route('dailyreport.edit', $p->id)}}">
                                        <i class="si fa-fw si-pencil mr-5"></i>Edit
                                    </a>
                                    <a class="dropdown-item" href="#">
                                        <i class="si fa-fw si-trash mr-5"></i>Delete
                                    </a>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="card-body text-left">
                    <div class ="row mb-20" style="margin-top:-3%;">
                        <div class="img-link col-md-1" href="" data-toggle="modal" data-target="#modal-normal">
                            @if(App\User::find($p->user_id)->avatar == null)
                                <div class="aacircle" style="--avatar-size: 3rem; background-color: #{{$p->avatarcolor}} !important;">
                                    <span class="aainitials">{{$p->fname[0] . $p->lname[0]}}</span>
                                </div>
                            @else
                                <img src="https://ienetworks.co/pms/uploads/avatars/{{ App\User::find($p->user_id)->avatar }}" style="width:3rem; height:3rem; border-radius:50%;">
                            @endif
                        </div>
                      <h4 class="card-title mt-10 col-md-7" style="margin-left:-2%;"><a href="" style="text-decoration:none">{{$p->fname . " " . $p->lname}}</a></h4>
                    </div>
                    <h5 class="card-text" style="font-weight:bold; padding-top:0.5rem;">Selam <a href="" style="text-decoration: aqua">{{$reportsTo !=null ?$reportsTo:"Manager"}}</a></h5>
                    @foreach($metricvalues as $mv)
                        <div class="row">
                            <div class="col-md-12">
                                <p class=" default metric-btn" style="font-size:18px;"><b>Key Result: {{$mv->keyresult_name}}</b></p>
                            </div>
                        </div>
                        <div class="panel-collapse collapse-in">
                            <div class="panel-body">
                                <div class="tasks-list">
                                    <ol type="1">
                                        @foreach($weekplan as $wp)
                                            @if($wp->keyresultid == $mv->keyresultid)
                                                @php
                                                    $subtasks =  DB::table('dailyreports')->where('report_id', $p->id)->where('dailyreports.task_id', $wp->id)->join('subtasks','dailyreports.subtask_id','=', 'subtasks.id')->select('subtasks.id','subtask_name', 'feedback', 'dailyreports.status')->get();
                                                @endphp
                                                @if($subtasks->count() == 0)
                                                    <div class = "row">
                                                        <li class=" default task-btn col-md-8">{{$wp->taskname}}</li>
                                                            @if($wp->status == 0)
                                                                <b class="text-danger col-md-4">Not Yet  @if($wp->feedback !=null)({{$wp->feedback}})@endif</b>
                                                                
                                                            @else
                                                                <b class="text-success col-md-4">Achieved  @if( $wp->feedback != null)({{$wp->feedback}})@endif</b>
                                                            @endif
                                                    </div>
                                                @else
                                                    <div class = "row">
                                                        <li class=" default task-btn col-md-9">{{$wp->taskname}}</li>
                                                    </div>
                                                    <ol>
                                                        @foreach($subtasks as $st)
                                                            <div class = "row">
                                                                <li class=" default task-btn col-md-8">{{$st->subtask_name}} </li>
                                                                @if($st->status == 0)
                                                                    <b class="text-danger col-md-4">Not Yet @if($st->feedback !=null)({{$st->feedback}})@endif</b>
                                                                   
                                                                @else
                                                                    <b class="text-success col-md-4">Achieved  @if( $st->feedback != null)({{$st->feedback}})@endif</b>
                                                                @endif
                                                            </div>
                                                        @endforeach
                                                    </ol>
                                                @endif
                                            @endif
                                        @endforeach
                                    </ol>
                                </div>
                            </div>
                        </div>
                    @endforeach
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
            @if(Auth::user()->id == $p->reportsTo || Auth::user()->id == 170)
            <div class="container" id="boosted{{$p->id}}">
                <div class="row pull-right" onclick="retract({{$p->id}})">
                      <a class="btn btn-rounded mr-10 btn-outline-info mr-5 mb-5 {{Auth::user()->id == $p->reportsTo || Auth::user()->id == 170? '':'ancdisabled'}} float-right" > {!! $emoji !!} {{$ncomments->comment}}</a>
                </div>
                <div class="text-left pull-right mt-20" id="retractresult{{$p->id}}" hidden>

                  

                    <ul><button class="btn btn-circle mr-10 btn-outline-info" onclick="accesscomment('{{$p->id}}', '{{$fullname}}' )"  id="accessc{{$p->id}}"></button>Give Edit Access</ul>
                  
                    <ul><button class="btn btn-circle mr-10 btn-outline-info" onclick="deletecomment({{$p->id}})" aria-role="presentation" aria-label="Delete" id="deletenc{{$p->id}}"></button>Delete</ul>
                </div>
            </div>
             
            <div class="container" id="boostdiv{{$p->id}}" style="display:none">
                <div class="row pull-right" onclick="myFunction({{$p->id}})">
                    <button class="btn btn-sm btn-circle btn-outline-success nb mr-30 mb-5" ><i class="si si-rocket" aria-role="presentation" aria-label="Noted"></i></button>
                </div>
                <div class="text-left pull-right mt-20" id="reaction{{$p->id}}" hidden>

                    <ul><a class="btn btn-circle mr-10 btn-outline-info" onclick="addcomment({{$p->id}}, 'Noted')" aria-role="presentation" aria-label="Noted" id="Noted{{$p->id}}"></a>Noted</ul>

                    <ul><button class="btn btn-circle mr-10 btn-outline-info" onclick="addcomment({{$p->id}}, 'Noted with concern')"  id="Noted2{{$p->id}}"></button>Noted with concern</ul>
                    <ul><button class="btn btn-circle mr-10 btn-outline-info" onclick="addcomment({{$p->id}}, 'Good Job')"  id="Sounds{{$p->id}}"></button>Good Job</ul>

                </div>
            </div>
            @else
            <div class="container" >
                <a class="btn btn-rounded mr-10 btn-outline-info mr-5 mb-5 ancdisabled float-right"  > {!! $emoji !!} {{$ncomments->comment}}</a>
            </div>
            @endif
           
         @elseif(Auth::user()->id == $p->reportsTo)
         
          <div class="container" id="boosted{{$p->id}}" style="display:none">
                <div class="row pull-right" onclick="retract({{$p->id}})">
                      <a  class="btn btn-rounded mr-10 btn-outline-info mr-5 mb-5 {{Auth::user()->id == 170? '':'ancdisabled'}} float-right" id="bbost{{$p->id}}" ><label id="ctext{{$p->id}}"></label></a>
                </div>
                <div class="text-left pull-right mt-20" id="retractresult{{$p->id}}" hidden>
                    <ul><button class="btn btn-circle mr-10 btn-outline-info" onclick="accesscomment('{{$p->id}}', '{{$fullname}}' )"  id="accessc{{$p->id}}"></button>Give Edit Access</ul>
                    <ul><button class="btn btn-circle mr-10 btn-outline-info" onclick="deletecomment({{$p->id}})" aria-role="presentation" aria-label="Delete" id="deletenc{{$p->id}}"></button>Delete</ul>

                   
                  

                </div>
            </div>
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
           
            @if( Auth::user()->position == "CEO"  || (Auth::user()->id == 402 && Auth::user()->id != $p->user_id)||(Auth::user()->id == 160 && Auth::user()->id != $p->user_id)|| (Auth::user()->id == 86 && Auth::user()->id != $p->user_id) || (Auth::user()->id == 388 && Auth::user()->id != $p->user_id)|| Auth::user()->id == 187 ||  (Auth::user()->id == 183 && Auth::user()->id != $p->user_id)  || (Auth::user()->team != $team->team_name && Gate::any(['fillkpiteam', 'fillengageteam'])) || Auth::user()->id == $p->reportsTo)
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
                 
                  @elseif($p->cc != null)
                         @php
                        $ccarr = explode(",",$p->cc)
                        @endphp
                        @foreach($ccarr as $c)
                       
                        @if(App\User::find((int)$c) && App\User::find((int)$c)->id== Auth::user()->id)
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
                            
                        @endforeach
           
            
            
                 
            @endif
                </div>
                <div class="card-body">
                    <div class="row pull-right">
                        <a type="submit" class="btn btn-rounded btn-outline-info min-width-125" href="{{route('dailyrcomment', $p->id)}}#comment" id="submitbutton{{$p->id}}">
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
            </div>
        @endforeach
        {{ $plan->links() }}
    </div>
</div>

@include('includes.apprepmodal')
 <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.bundle.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/js/bootstrap-select.min.js"></script>
<script>
var baseurl = window.location.protocol + "//" + window.location.host + '/pms/';
    function myFunction(planid) {
    $("#Noted" + planid).html("&#128077;");
    $("#Noted2" + planid).html("&#128560;");
    $("#Sounds" + planid).html("&#128079;");
    document.getElementById("reaction"+planid).hidden = false;

}
 function retract(planid) {
    if( document.getElementById("retractresult"+planid).hidden == true){
        $("#deletenc" + planid).html("&#x274C;");
        $("#accessc" + planid).html("&#9999;");
   
    document.getElementById("retractresult"+planid).hidden = false;
    } 
    else{
         document.getElementById("retractresult"+planid).hidden = true;
    }
    
   

}
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
function deletecomment(planid){
      $.ajaxSetup({
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
        });
        formData = {
                report_id: planid,
                
            };
            var ajaxurl = baseurl + "deleteboost";
        var type = "POST";
        $.ajax({
            type: type,
            url: ajaxurl,
            data: formData,
            dataType: "json",
            success: function (data) {
                
               
                      

                    document.getElementById('boostdiv'+planid).style.display = 'block';
                  
                    document.getElementById('boosted'+planid).style.display = 'none';
                    document.getElementById('resultdiv'+planid).style.display = 'none';

                document.getElementById("reaction"+planid).hidden = true;
                
            },
            error: function (data) {
                console.log("Error:", data.responseText);
            },
        });

}
function accesscomment(planid, fullname){
      $.ajaxSetup({
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
        });
        var x = '<span class="mention-area"><span class="highlight"><a href="#" data-item-id="199" class="mentiony-link">@'+fullname+'</a></span></span><span class="normal-text"> </span>'
        formData = {
                report_id: planid,
                comment: 'Selam '+ x + ", please update as discussed"
            };

        var type = "POST";
           var ajaxurl = baseurl + "editaccess";

        $.ajax({
            type: type,
            url: ajaxurl,
            data: formData,
            dataType: "json",
            success: function (data) {
                
               
                        
                    document.getElementById('submitbutton'+planid).innerHTML = data == -1 ? 'Discuss': data + ' comment';
                    document.getElementById('boostdiv'+planid).style.display = 'block';
                  
                    document.getElementById('boosted'+planid).style.display = 'none';
                    document.getElementById('resultdiv'+planid).style.display = 'none';

                document.getElementById("reaction"+planid).hidden = true;
            },
            error: function (data) {
                console.log("Error:", data.responseText);
            },
        });

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

                      $("#bbost" + planid).html("&#128077;" + " " + comment);
                        if(comment == "Noted with concern") {
                           $("#bbost" + planid).html("&#128560;"  + " " + comment);
                        }
                        else if(comment == "Good Job"){
                            $("#bbost" + planid).html("&#128079;"  + " " + comment);

                        }
                        
                         
                    document.getElementById('boostdiv'+planid).style.display = 'none';
                  
                     document.getElementById('boosted'+planid).style.display = 'block';
                     if(document.getElementById("retractresult"+planid)){
                         document.getElementById("retractresult"+planid).hidden = true;
                     }
                        


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

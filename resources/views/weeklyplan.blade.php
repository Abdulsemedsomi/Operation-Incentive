@extends('layouts.backend')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/css/bootstrap-select.css" />
 {{-- <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous"> --}}
 @section('content')
<div class="container">
    <nav class="breadcrumb bg-white push">
         <a class="breadcrumb-item" href="{{ route('checkin', $team->id) }}">{{$team->team_name}}</a>
        <a class="breadcrumb-item" href="{{ route('checkin', $team->id) }}">Check-in</a>
        <span class="breadcrumb-item active">Weekly Plan</span>
    </nav>
</div>
 <div class="container">
    <h3 class="font-w500 text-center">Weekly Plan Check-in</h3>
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
    
     <div  class="container neopad addweeklancard" hidden>
         <div class="card text-center" >
             <div class="card-body text-left">
                  
                    <form action = "{{ route('weeklyplans', $team->id) }}" id="submitweeklyplan"  method="post">
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
                            @php
                                $metric = App\Keyresult::where('objective_id', $objs->id)->get();
                            @endphp
                             <h5 class="font-w400">{{$objs->objective_name}}</h5>

                            @foreach($metric as $m)
                                @if($m->objective_id == $objs->id)
                                <div class="card">
                                    <div class="card-header" id="weeklyheading{{$m->id}}" style="font-size: 14px;">
                                        <div class="row ">
                                            <a class="col-md-3" value={{$m->id}}>{{$m->keyresult_name}}</a>
                                            <div class="row col-md-3">
                                            <input name="options{{$m->id}}"  type=number step=0.01 class="mt-5 col-md-5 round percentc" style="color: #000; height:28px;" id="percentc{{$m->id}}" data-id= "{{$m->id}}" value= "{{ old('options'. $m->id) ?? 0}}" placeholder="0" >
                                            </div>
                                            <div class="row col-md-5">
                                            <p class=" mt-5 col-md-4"><i class="si si-flag mr-10 text-success"></i>{{App\Task::where('isMilestone', 1)->where('isactive', 1)->where('keyresultid', $m->id)->where('status', 0)->get()->count()}} Milestones</p>
                                            <p class=" mt-5 col-md-4" ><i class="fa fa-tasks mr-10 text-warning"></i><span id="taskno{{$m->id}}">{{App\Task::where('isMilestone', 0)->where('keyresultid', $m->id)->where('status', 0)->where('tasks.isactive', 1)->get()->count()}} </span> Tasks</p>
                                            <a type="button" class="text-success mt-5  col-md-4 addweeklytask {{App\Task::where('isMilestone', 1)->where('keyresultid', $m->id)->where('status', 0)->where('isactive', 1)->get()->count() > 0?'':'ancdisabled'}}" id="addweeklytask{{$m->id}}" data-id = '{{$m->id}}'><i class="fa fa-plus mr-5"></i> Add Task
                                           
                                            </a>
                                            </div>
                                            <a class="btn btn-sm mt-5 col-md-1 collapsed" type="button" id="weeklyaccordionExample{{$m->id}}" data-toggle="collapse" data-target="#weekcollapse{{$m->id}}" aria-expanded="false" aria-controls="weekcollapse{{$m->id}}">
                                                <i class="si si-arrow-down"></i>
                                            </a>
                                        </div>
                                    </div>
                                   
                                    <div id="weekcollapse{{$m->id}}" class="collapse" aria-labelledby="weeklyheading{{$m->id}}" data-parent="#accordionExample{{$m->id}}">
                                    <div class="card-body" id="tasks-list{{$m->id}}">
                                        
                                        <label class="css-control-sm css-control-info css-checkbox css-checkbox-rounded" id="selectallb" {{$db->where('keyresultid', $m->id)->count() > 0?"":"hidden"}} >
                                         <input type="checkbox" class="css-control-input selectall{{$m->id}}" onClick="toggle({{$m->id}})">
                                         <span class="css-control-indicator"></span> Select All
                                         </label>
                                     
                                            @foreach($db as $task)
                                            @if($task->keyresultid == $m->id)
                                          
                                            <div class="container" id="taskl{{$task->id}}">
                                                <div class="row mb-10">
                                                    <div class="col-md-4 mt-5">
                                                        <label class="css-control-sm css-control-info css-checkbox css-checkbox-rounded">
                                                            <input type="checkbox" class="css-control-input taskcheck tc{{$m->id}}" name="taskcheck[]" value="{{$task->id}}" id="taskcheckv{{$task->id}}" data-mid="{{$m->id}}">
                                                            <span class="css-control-indicator"></span> {{$task->taskname}}
                                                        </label>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <!--<a  class="btn btn-sm mt-5 text-success teditpercent" id= "teditpercent{{$task->id}}" data-id= {{$task->id}} data-value=0><i class="fa fa-pencil" ></i></a>-->
                                                      
                                                    <input type=number name="taskoptions{{$task->id}}" step=0.01 class="mt-5 col-md-5 round tpercentc tasklist{{$m->id}}" id="tpercentc{{$task->id}}" value= '{{ old('taskoptions'. $task->id) ?? 0}}' data-id= "{{$task->id}}" onkeyup="getTotalTask({{$m->id}})" placeholder="0" min=0 max=100>
                                                    <!--<a  class="btn btn-sm mt-5 text-success ttickpercent" id="ttickpercent{{$task->id}}"  hidden data-id= {{$task->id}}><i class="fa fa-check"></i></a>-->
                                                    </div>
                                                    <div class ="col-md-3">
                                                        <a class="mt-5"><span class="dot mt-5"></span> {{App\Task::find($task->parent_task)->taskname}}</a>
                                                    </div>
                                                   
                                                        <a class="col-md-1 col-sm-1" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                                                            <i class="fa fa-bars"></i>
                                                        </a>
                                                    <div class="dropdown-menu dropdown-menu-right min-width-200 " aria-labelledby="page-header-user-dropdown" style="position: absolute; transform: translate3d(-103px, 34px, 0px); top: 0px; left: 0px; will-change: transform;" x-placement="bottom-end">
                                                        <a class="dropdown-item d-flex align-items-center justify-content-between editweeklytask" type="button" data-id="{{$task->id}}">
                                                                Edit
                                                        </a>
                                                        <a class="dropdown-item deletewtask" data-id= '{{$task->id}}'>
                                                            Delete
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                            @endif
                                            @endforeach
                                           
                                        </div>
                                         <div class="tasktotal col-md-4"   id="tasktotal{{$m->id}}" {{$db->where('keyresultid', $m->id)->count() > 0?"":"hidden"}}> <b>Total: <label id="tasktotalvalue{{$m->id}}">0</label> </b> </div>
                                    </div>
                                </div>
                                @endif
                                @endforeach

                                @endforeach

                              <div class=" row">
                                    <label for="cc" class="col-md-2 col-form-label text-md-right">CC</label>
                                    
                                        <select class="selectpicker" id="cc" name="cc[]" multiple >
                                          
                                              @foreach($users as $user)
                                            <option value="{{$user->id}}" >{{$user->fname. " " . $user->lname}}</option>
                                              @endforeach
                                            
                                        </select>
                                    
                                </div>
                                 <div class="krtotal"   id="krtotal"> <b>Total: <label id="ktotalvalue">0</label> </b> </div>
                                <div id="weeklyplanerrorbod" class="col-md-7"></div>
                            <div class="card-body">
                                <div class="row pull-right">
                                <button type="submit" class="btn btn-rounded btn-outline-primary submitweekplan">Submit Weekly target</button>
                                <div class="col-md-1"></div>
                                <button type="button" class="btn btn-rounded btn-outline-danger cancelweeklyplan">Cancel</button>
                                </div>
                            </div>
                    </form>
             </div>

           </div>
         </div>
     <div class="col text-center" {{trim(Auth::user()->team) == trim(App\Team::find($team->id)->team_name) ? '':'hidden' }}>
        @php
        $isdisabled =  $existplan || !$objectives ? 'disabled':"";
        $title =  $existplan || !$objectives? 'Please submit weekly report first':"";
        @endphp

         <button type="button"  class="btn btn-rounded btn-outline-info min-width-125 mb-10 addweekplanbutton" {{$isdisabled }} title='{{$title}}'>Add Next Week's target</button>
    
     </div>
    
     @if($count > 5)
  <form action="{{route('weeklyplan', $team->id)}}" method="get" >
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
     <div  class="container neopad infinite-scroll" style="margin-top:4%">
        
          {{ $plan->links() }}
        @foreach($plan as $p)
        @php
                $objectives = DB::table('weeklyplans')->where('weeklyplans.planid', $p->id)->join('keyresults','weeklyplans.keyresult_id','=', 'keyresults.id')
                ->join('objectives','keyresults.objective_id','=', 'objectives.id')
                ->select('objective_name', 'objective_id')->distinct()->get();

                $metricvalues = DB::table('weeklyplans')->where('weeklyplans.planid', $p->id)->join('tasks','weeklyplans.task_id','=', 'tasks.id')
                ->join('keyresults','tasks.keyresultid','=', 'keyresults.id')->select('keyresultid', 'keyresult_name', 'keyresult_percent', 'objective_id')->distinct()->get();
                $weekplan = DB::table('weeklyplans')->where('weeklyplans.planid', $p->id)->join('tasks','weeklyplans.task_id','=', 'tasks.id')
                ->join('keyresults','tasks.keyresultid','=', 'keyresults.id')->get();
                $teammanager= DB::table('teams')->where('team_name', $p->team)->first()->manager_id;
                $reportsTo = $p->reportsTo != null ? (App\User::find($p->reportsTo)->fname ." ". App\User::find($p->reportsTo)->lname ): App\User::find($p->userid)->reportsTo;
             
                $comments = DB::table('comments')->where('plan_id', $p->id)->where('type', 1)->get()->count();
                  $ncomments = DB::table('comments')->where('plan_id', $p->id)->where('type', 2)->first();
                   $fullname = $p->fname . " " . $p->lname;
        @endphp
    <div class="card text-center mt-10">
       
        <div class="card-body text-right" style="padding-bottom: 0%;">
            <div class="row pull-right">
                <div class="text-muted mr-10">
                    <span class="floar-right">{{$p->isEdited == 0 ? Carbon\Carbon::parse($p->created_at)->isoFormat('MMMM Do YYYY, h:mm:ss a')  :"Updated at ". Carbon\Carbon::parse($p->created_at)->isoFormat('MMMM Do YYYY, h:mm:ss a') }}</span>
                </div>
                @if(Auth::user()->id == $p->userid && !$ncomments)
                <div class="dropdown">
                    <button type="button" class="btn-block-option" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="fa fa-fw fa-ellipsis-v"></i>
                    </button>
                    <div class="dropdown-menu dropdown-menu-right" style="">
                        <a class="dropdown-item" href="{{route('weeklyplan.edit', $p->id)}}">
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
        <div class="card-body text-left">
            <div class ="row mb-20" style="margin-top:-3%;">
                <div class="img-link ml-3" href="" data-toggle="modal" data-target="#modal-normal">
                    @if(App\User::find($p->userid)->avatar == null)
                        <div class="aacircle" style="--avatar-size: 3rem;  background-color: #{{$p->avatarcolor}} !important;">
                            <span class="aainitials">{{$p->fname[0] . $p->lname[0]}}</span>
                        </div>
                    @else
                        <img src="https://ienetworks.co/pms/uploads/avatars/{{ App\User::find($p->userid)->avatar }}" style="width:3rem; height:3rem; border-radius:50%;">
                    @endif
                </div>
              <h4 class="card-title mt-10 col-md-7" ><a href="" style="text-decoration:none">{{$p->fname . " " . $p->lname}}</a></h4>
            </div>
          <h5 class="card-text" style="font-weight:bold; padding-top:0.5rem;">Selam <a href="" style="text-decoration: aqua">{{$reportsTo !=null ?$reportsTo:"Manager"}}</a></h5>
          @foreach($objectives as $ob)
          <b class="card-text" style="font-size:20px;">Objective: {{$ob->objective_name}}</b>
          @foreach($metricvalues as $mv)
          @if($mv->objective_id == $ob->objective_id)
          <div class="row ml-20">
              <div class="col-md-12">
                  <b class=" default metric-btn" style="font-size:16px;">Key Result: {{$mv->keyresult_name}} ({{$mv->keyresult_percent}}%)</b>
              </div>
          </div>
          <div  class="panel-collapse collapse-in ml-20">
              <div class="panel-body ">
                  <div class="tasks-list">
                      <ol type="1">
                          @foreach($weekplan as $wp)
                              @if($wp->keyresultid == $mv->keyresultid)
                                  <li class=" default task-btn" >{{$wp->taskname}} ({{$wp->task_percent }}%) 
                                  
                                  @if($wp->isMilestone == 1) 
                                  
                                  <i class="fa fa-star"></i>
                                  @endif
                                  </li>
                              @endif
                          @endforeach
                      </ol>
                  </div>
              </div>
          </div>
          @endif
      @endforeach
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
               else if($ncomments->comment == "Sounds Good"){
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

                     <ul><a class="btn btn-circle mr-10 btn-outline-info" onclick="addcomment({{$p->id}}, 'Have a productive week')" aria-role="presentation" aria-label="Noted" id="Noted{{$p->id}}"></a>Have a productive week</ul>


                </div>
            </div>
            @else
            <div class="container" >
                <a class="btn btn-rounded mr-10 btn-outline-info mr-5 mb-5 ancdisabled float-right"  > {!! $emoji !!} {{$ncomments->comment}}</a>
            </div>
            @endif
         @elseif(Auth::user()->id == $p->reportsTo || Auth::user()->id == 170)
         <div class="container" id="boosted{{$p->id}}" style="display:none">
                <div class="row pull-right" onclick="retract({{$p->id}})">
                      <a  class="btn btn-rounded mr-10 btn-outline-info mr-5 mb-5 {{Auth::user()->id == $p->reportsTo? '':'ancdisabled'}} float-right" id="bbost{{$p->id}}" ><label id="ctext{{$p->id}}"></label></a>
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
                <div class="row pull-right" onclick="addcomment({{$p->id}}, 'Have a productive week')"">
                    <button class="btn btn-sm btn-circle btn-outline-success nb mr-30 mb-5" ><i class="si si-rocket" aria-role="presentation" aria-label="Noted"></i></button>
                </div>
                <div class="text-left pull-right mt-20" id="reaction{{$p->id}}" hidden>

                    <ul><a class="btn btn-circle mr-10 btn-outline-info" onclick="addcomment({{$p->id}}, 'Have a productive week')" aria-role="presentation" aria-label="Noted" id="Noted{{$p->id}}"></a>Have a productive week</ul>

                   
                    

                </div>
            </div>
            
        @endif
        </div>
        <div class="card-body">
            <div class="row pull-right">
           <a type="submit" class="btn btn-rounded btn-outline-info min-width-125" href="{{route('weeklycomment', $p->id)}}" id="submitbutton{{$p->id}}">
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

</div>
  @include('includes.weeklyplan-addmodal')
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
function deletecomment(planid){
      $.ajaxSetup({
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
        });
        formData = {
                plan_id: planid,
                
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
                plan_id: planid,
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
                
               
                         document.getElementById('submitbutton'+planid).innerHTML = data == 0 ? 'Discuss': data + ' comment';

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
                plan_id: planid,
                comment: comment,
            };

        var type = "POST";

        var ajaxurl = baseurl + "weeklyncomment";

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
                        else if(comment == "Sounds Good"){
                            $("#bbost" + planid).html("&#128079;"  + " " + comment);

                        }

                       
                        if(document.getElementById("retractresult"+planid)){
                         document.getElementById("retractresult"+planid).hidden = true;
                     }
                         
                    document.getElementById('boostdiv'+planid).style.display = 'none';
                  
                     document.getElementById('boosted'+planid).style.display = 'block';


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

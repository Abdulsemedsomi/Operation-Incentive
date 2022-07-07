@extends('layouts.backend')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/css/bootstrap-select.css" />
 {{-- <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous"> --}}
 @section('content')
<div class="container">
    <nav class="breadcrumb bg-white push">
        <a class="breadcrumb-item" href="{{ route('checkin', $team->id) }}">Check-in</a>
        <span class="breadcrumb-item active">Weekly Plan</span>
    </nav>
</div>
 <div class="container">
    <h3 class="font-w500 text-center">Edit Weekly Plan</h3>
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
     <div  class="container neopad " >
         <div class="card text-center" >
             <div class="card-body text-left">
                    <form action = "{{ route('editweeklyplan', $plan->id)}}"   method="post">
                        @csrf
                         <div class="form-group row">
                            <label for="reportTo" class="col-md-4 col-form-label text-md-right">Selam</label>
                            <div class="col-md-6">
                                <select class=" form-control" id="reportTo" name="reportsTo">
                                      @foreach($users as $user)
                                    <option value="{{$user->id}}" {{$user->id == $plan->reportsTo? 'selected': ''}}>{{$user->fname. " " . $user->lname}}</option>
                                      @endforeach
                                    
                                </select>
                            </div>
                        </div>
                         <input type="hidden" name="_method" value="put" />
                        @php
                          $ksum =0;
                         @endphp
                        @foreach($objectives as $objs)
                         
                            @php
                                $metric = App\Keyresult::where('objective_id', $objs->id)->get();
                               
                            @endphp
                             <h5 class="font-w400">{{$objs->objective_name}}</h5>
                            
                            @foreach($metric as $m)
                                @if($m->objective_id == $objs->id)
                                @php
                                $weeklyplans = App\Weeklyplan::where('planid',$plan->id)->where('keyresult_id', $m->id)->get();
                                $weeklyplank = App\Weeklyplan::where('planid',$plan->id)->where('keyresult_id', $m->id)->first();
                                $collapse="";
                                $c = "collapsed";
                                if($weeklyplans->count()> 0){
                                    $collapse = "show";
                                    $ksum +=$weeklyplank->keyresult_percent;
                                    $c ="";
                                }
                                @endphp
                                <div class="card">
                                    <div class="card-header" id="heading{{$m->id}}" style="font-size: 14px;">
                                        <div class="row ">
                                            <a class="col-md-3" value={{$m->id}}>{{$m->keyresult_name}}</a>
                                            <div class="row col-md-3">
                                               <input name="options{{$m->id}}" type=number step=0.01 class="mt-5 col-md-5 round percentc" style="color: #000; height:28px; " id="percentc{{$m->id}}" value= '{{$weeklyplank?$weeklyplank->keyresult_percent:0}}' data-id= "{{$m->id}}" placeholder="0">
                                            
                                            </div>
                                            <div class="row col-md-5">
                                            <p class=" mt-5 col-md-4"><i class="si si-flag mr-10 text-success"></i>{{App\Task::where('isMilestone', 1)->where('keyresultid', $m->id)->where('isactive', 1)->where('status', 0)->get()->count()}} Milestones</p>
                                            <p class=" mt-5 col-md-4" ><i class="fa fa-tasks mr-10 text-warning"></i><span id="taskno{{$m->id}}">{{App\Task::where('isMilestone', 0)->where('keyresultid', $m->id)->where('status', 0)->where('tasks.isactive', 1)->get()->count()}} </span> Tasks</p>
                                            <a type="button" class="text-success mt-5  col-md-4 addweeklytask {{App\Task::where('isMilestone', 1)->where('isactive', 1)->where('keyresultid', $m->id)->where('status', 0)->get()->count() > 0?'':'ancdisabled'}} " id="addweeklytask{{$m->id}}" data-id = '{{$m->id}}'><i class="fa fa-plus mr-5"></i> Add Task
                                           
                                            </a>
                                            </div>
                                        
                                            <a class="btn btn-sm mt-5 col-md-1 {{$c}}" type="button" data-toggle="collapse" data-target="#collapse{{$m->id}}" aria-expanded="false" aria-controls="collapseTwo" id="weeklyaccordionExample{{$m->id}}">
                                                <i class="si si-arrow-down"></i>
                                            </a>
                                        </div>
                                    </div>
                                   
                                    <div id="collapse{{$m->id}}" class="collapse {{$collapse}}" aria-labelledby="heading{{$m->id}}" data-parent="#accordionExample">
                                    <div class="card-body" id="tasks-list{{$m->id}}">
                                         <label class="css-control-sm css-control-info css-checkbox css-checkbox-rounded" id="selectallb" {{$db->where('keyresultid', $m->id)->count() > 0?"":"hidden"}} >
                                             <input type="checkbox" class="css-control-input selectall{{$m->id}}" onClick="toggle({{$m->id}})">
                                                <span class="css-control-indicator"></span> Select All
                                         </label>
                                      
                                         @php
                                         $sum = 0;
                                         @endphp
                                            @foreach($db as $task)
                                            @if($task->keyresultid == $m->id)
                                             @php
                                                $weeklyplan = App\Weeklyplan::where('planid',$plan->id)->where('keyresult_id', $m->id)->where('task_id', $task->id)->first();
                                                $check="";
                                                
                                                if($weeklyplan){
                                                    $check = "checked";
                                                    $sum += $weeklyplan->task_percent;
                                                }
                                            @endphp
                                            @if(  ($weeklyplan && $weeklyplan->task_id == $task->id) || ($task->isactive == 1 && $task->isMilestone == 0 && $task->status == 0)  )
                                            <div class="container" id="taskl{{$task->id}}">
                                                <div class="row mb-10">
                                                    <div class="col-md-4 mt-5">
                                                        <label class="css-control-sm css-control-info css-checkbox css-checkbox-rounded">
                                                            <input type="checkbox" class="css-control-input taskcheck tc{{$m->id}}" name="taskcheck[]" value="{{$task->id}}"  id="taskcheckv{{$task->id}}" data-mid="{{$m->id}}" {{$check}} >
                                                            <span class="css-control-indicator"></span> {{$task->taskname}}
                                                        </label>
                                                    </div>
                                                    <div class="col-md-3">
                                                       <input type=number step=0.01 name="taskoptions{{$task->id}}"  class="mt-5 col-md-5 round tpercentc tasklist{{$m->id}}" id="tpercentc{{$task->id}}" placeholder="0" value= '{{$weeklyplan?$weeklyplan->task_percent:0}}' data-id= "{{$task->id}}"  onkeyup="getTotalTask({{$m->id}})"  min=0 max=100>
                                                   
                                                    </div>
                                                    @if($task->isMilestone == 0)
                                                    <div class ="col-md-3">
                                                        <a class="mt-5"><span class="dot mt-5"></span> {{$task->parent_task? App\Task::find($task->parent_task)->taskname:""}}</a>
                                                    </div>
                                                    @endif
                                                    @if($task->isMilestone == 0)
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
                                                    @else
                                                    <div class="col-md-1">
                                                        <button type="button" class="btn btn-alt-danger miletaskremove" value="{{$task->id}}" data-mid="{{$m->id}}"><i class="fa fa-times"></i></button>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                            @endif
                                            @endif
                                            @endforeach
                                        </div>
                                        <div class="tasktotal col-md-4"   id="tasktotal{{$m->id}}" {{$db->where('keyresultid', $m->id)->count() > 0?"":"hidden"}}> <b>Total: <label id="tasktotalvalue{{$m->id}}">{{$sum}}</label> </b> </div>
                                    </div>
                                </div>
                                @endif
                                @endforeach

                                @endforeach
                                <div class=" row">
                                    <label for="cc" class="col-md-2 col-form-label text-md-right">CC</label>
                                    
                                        <select class="selectpicker" id="cc" name="cc[]" multiple >
                                          @php
                                          $ccarr = 0;
                                            if($plan->cc != null){
                                                $ccarr = explode(",",$plan->cc);
                                            }
                                          @endphp
                                        @foreach($users as $user)
                                               
                                                    
                                        <option value="{{$user->id}}" {{ $ccarr !=0 && in_array($user->id, $ccarr)?"selected":""}} >{{$user->fname. " " . $user->lname}}</option>
                                                    
                                              
                                            
                                        @endforeach
                                            
                                        </select>
                                    
                                </div>
                                 <div class="krtotal"   id="krtotal"> <b>Total: <label id="ktotalvalue">{{$ksum}}</label> </b> </div>
                                <div id="weeklyplanerrorbod" class="col-md-7"></div>

                            {{-- <button type="button" data-toggle="modal" data-target="#exampleModalCenter" class="btn btn-rounded btn-outline-success">Add task</button> --}}
                            <div class="card-body">
                                <div class="row pull-right">
                                <button type="submit" class="btn btn-rounded btn-outline-primary ">Edit Weekly target</button>
                                <div class="col-md-1"></div>
                                <a type="button" class="btn btn-rounded btn-outline-danger " href="{{ route('weeklyplan', $plan->teamid) }}">Cancel</a>
                                </div>
                            </div>
                    </form>
             </div>

           </div>
         </div>
   
   

</div>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.bundle.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/js/bootstrap-select.min.js"></script>
 @include('includes.weeklyplan-addmodal')

@endsection

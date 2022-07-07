@extends('layouts.backend')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/css/bootstrap-select.css" />
<!-- CSS only -->
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
@section('content')
<div class="container">
    <nav class="breadcrumb bg-white push">
        <a class="breadcrumb-item" href="{{ route('checkin', $team->id) }}">Check-in</a>
        <span class="breadcrumb-item active">Daily Plan</span>
    </nav>
</div>
<div class="container">
    <h3 class="font-w500 text-center">Edit Daily Plan</h3>
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
    <div  class="container neopad adddailycard">
     
        <div class="card text-center" >
            <div class="card-body text-left">
              
                <form action = "{{ route('editdailyplan', $dplan->id) }}"   method="post">
                    @csrf
                     <input type="hidden" name="_method" value="put" />
                    <div class="form-group row">
                            <label for="reportTo" class="col-md-4 col-form-label text-md-right">Selam</label>
                            <div class="col-md-6">
                                <select class=" form-control" id="reportTo" name="reportsTo">
                                      @foreach($users as $user)
                                    <option value="{{$user->id}}" {{$user->id == $dplan->reportsTo? 'selected': ''}}>{{$user->fname. " " . $user->lname}}</option>
                                      @endforeach
                                    
                                </select>
                            </div>
                        </div>
                        @foreach($metric as $m)
                         @php
                         $taskls = App\Task::where('keyresultid', $m->keyresultid)->pluck('id');
                           $dailyplans = App\Dailyplan::where('planid',$dplan->id)->whereIn('task_id', $taskls)->first();
                             $collapse="";
                                if($dailyplans){
                                    $collapse = "show";  
                                                
                                                   
                                                }
                        @endphp
                            <div class="card">
                                <div class="card-header" id="headingThree{{$m->keyresultid}}">
                                    <div class="row">
                                        <a class="col-md-5"><b>Key Result: </b>{{$m->keyresult_name}}</a>
                                        <p class="ml-50 mr-60 mt-5"><i class="fa fa-tasks mr-10 text-warning"></i>{{$weekplan?$weekplan->where('keyresultid', $m->keyresultid)->count():0}} Tasks</p>
                                        <p class="ml-50 ml-50 mt-5" style="margin-right: 16em;"><i class="fa fa-tasks mr-10 text-warning"></i><span id="subtaskno{{$m->keyresultid}}">{{$subs?$subs->where('keyresultid', $m->keyresultid)->count():0}}</span> Subtasks</p>
                                        <a class="btn btn-sm ml-70 mt-5" type="button" data-toggle="collapse" data-target="#collapseThree{{$m->keyresultid}}" aria-expanded="false" aria-controls="collapseThree{{$m->keyresultid}}">
                                            <i class="si si-arrow-down"></i>
                                        </a>
                                    </div>
                                </div>
                                <div id="collapseThree{{$m->keyresultid}}" class="collapse {{$collapse}}" aria-labelledby="headingThree{{$m->keyresultid}}" data-parent="#accordionExample">
                                    @foreach($weekplan as $task)
                                        @if($task->keyresultid == $m->keyresultid)
                                        @php
                                        $dailyplan = App\Dailyplan::where('planid',$dplan->id)->whereNull('subtask_id')->where('task_id', $task->id)->first();
                                                $check="";
                                                
                                                if($dailyplan){
                                                    $check = "checked";
                                                   
                                                }
                                        @endphp
                                            <div class="card-body">
                                                <div class="container" >
                                                    <div class="row mb-20">
                                                        <div class="col-md-5">
                                                            <label class="css-control-sm css-control-info css-checkbox css-checkbox-rounded" >
                                                                <input type="checkbox" class="css-control-input" name="taskcheck[]" value= "{{$task->id}}" {{$check}} id="taskcheckc{{$task->id}}">
                                                                <span class="css-control-indicator"></span> {{$task->taskname}}
                                                            </label>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <a class="mt-5" style="margin-right: 7em;"><span class="dot mt-5"></span> {{ App\Task::find($task->id)->isMilestone == 0 ?App\Task::find($task->parent_task)->taskname: ""}}</a>
                                                        </div>
                                                         <a type="button" class="text-success mt-5 adddailysubtask" style="margin-right: 3em; margin-left: 13em;" id="adddailysubtask{{$task->id}}" data-id="{{$task->id}}"><i class="fa fa-plus mr-5"></i> Add Subtask</a>

                                                    </div>
                                                 
                                                     <div id="subtasks-list{{$task->id}}">
                                                    
                                                    @foreach($subtasks as $subtask)
                                                        @if($subtask->taskid == $task->id)
                                                       @php
                                                            $dayplan = App\Dailyplan::where('planid',$dplan->id)->whereNotNull('subtask_id')->where('subtask_id', $subtask->id)->first();
                                                                    $check="";
                                                                    
                                                                    if($dayplan){
                                                                        $check = "checked";
                                                                       
                                                                    }
                                                            @endphp
                                                            <div class="container" id="subtaskl{{$subtask->id}}">
                                                                <div class="row mr-10">
                                                                    <div class="col-md-5">
                                                                        <label class="css-control-sm css-control-info css-checkbox css-checkbox-rounded">
                                                                            <input type="checkbox" class="css-control-input" name="subtaskcheck[]" value= "{{$subtask->id}}" id="subtaskcheckv{{$subtask->id}}" {{$check}}>
                                                                            <span class="css-control-indicator"></span> {{$subtask->subtask_name}}
                                                                        </label>
                                                                    </div>

                                                                    <p class="mb-5 col-md-1"><a class="font-w600" href="javascript:void(0)"></p>
                                                                    <a class="col-md-1 col-sm-1" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true" style="margin-left: 23em;">
                                                                        <i class="fa fa-bars"></i>
                                                                    </a>
                                                                    <div class="dropdown-menu dropdown-menu-right min-width-200" aria-labelledby="page-header-user-dropdown" style="position: absolute; transform: translate3d(-103px, 34px, 0px); top: 0px; left: 0px; will-change: transform;" x-placement="bottom-end">
                                                                        <a class="dropdown-item d-flex align-items-center justify-content-between editdailysubtask" data-id="{{$subtask->id}}">
                                                                                Edit
                                                                        </a>
                                                                        <a class="dropdown-item deletedsubtask" data-id="{{$subtask->id}}">
                                                                            Delete
                                                                        </a>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        @endif
                                                    @endforeach
                                                </div>

                                                </div>
                                            </div>
                                        @endif
                                    @endforeach
                                </div>
                            </div>
           @endforeach
                                <div class=" row">
                                    <label for="cc" class="col-md-2 col-form-label text-md-right">CC</label>
                                    
                                        <select class="selectpicker" id="cc" name="cc[]" multiple >
                                          @php
                                          $ccarr = 0;
                                            if($plan->cc != null){
                                                $ccarr = explode(",",$dplan->cc);
                                            }
                                          @endphp
                                        @foreach($users as $user)
                                               
                                                    
                                        <option value="{{$user->id}}" {{ $ccarr !=0 && in_array($user->id, $ccarr)?"selected":""}} >{{$user->fname. " " . $user->lname}}</option>
                                                    
                                              
                                            
                                        @endforeach
                                            
                                        </select>
                                    
                                </div>

        <div class="card-body">
            <div class="row pull-right">
           <button type="" class="btn btn-rounded btn-outline-primary submitdayplan" >Edit Daily target</button>
            <div class="col-md-1"></div>
           <a   class="btn btn-rounded btn-outline-danger canceldayplan" href="{{ route('dailyplan', $team->id) }}">Cancel</a>
            </div>
       </div>
</form>
            </div>

          </div>
        </div>
        </div>
@include('includes.dailyplan-addmodal')
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.bundle.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/js/bootstrap-select.min.js"></script>
@endsection
@extends('layouts.backend')
<!-- CSS only -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/css/bootstrap-select.css" />
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
@section('content')
<div class="container">
    <nav class="breadcrumb bg-white push">
        <a class="breadcrumb-item" href="{{ route('checkin', $team->id) }}">Check-in</a>
        <span class="breadcrumb-item active"> Edit Daily Report</span>
    </nav>
</div>
<div class="container">
    <h3 class="font-w500 text-center">Edit Daily Report</h3>
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

    <div class="container neopad addreportcard" >
        <div class="card text-center" >
            <div class="card-body text-left">
                <form action = "{{ route('editdailyreport', $dplan->id) }}"   method="post" id="submitdailyreport">
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
                        <div class="card">
                            <div class="card-header" id="headingFour{{$m->keyresultid}}">
                                <div class="row">
                                    <a class="col-md-5"><b>Key Result: </b>{{$m->keyresult_name}}</a>
                                    <p class="ml-50 mr-60 mt-5"><i class="si si-flag mr-10 text-success"></i>{{$weekplan->where('keyresultid',$m->keyresultid)->count()}} Tasks</p>
                                    <p class="ml-50 ml-50 mt-5" style="margin-right: 16em;"><i class="fa fa-tasks mr-10 text-warning"></i>{{$subtts ? $subtts->where('keyresultid', $m->keyresultid)->count():0}} Subtasks</p>
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
                                                        $dailyreport = App\Dailyreport::where('report_id', $dplan->id)->where('task_id', $task->id)->first();
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
                                                                <input type="radio" class="css-control-input togglestatus" name="radio-group{{$task->id}}"  value=1 onchange="taskstatuscheck({{$task->id}})" {{$dailyreport && $dailyreport->status == 1? "checked" : ""}}>
                                                                <span class="css-control-indicator"></span> Achieved
                                                            </label>
                                                            <label class="css-control-sm css-control-danger css-radio mt-5">
                                                                <input type="radio" class="css-control-input togglestatus" name="radio-group{{$task->id}}"  value=0 onchange="taskstatuscheck({{$task->id}})" {{$dailyreport && $dailyreport->status == 0? "checked" : ""}}>
                                                                <span class="css-control-indicator"></span> Not Yet
                                                            </label>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <input type="text" class="form-control round" placeholder="Reason" name="feedback{{$task->id}}" id="tfeedback{{$task->id}}" value = "{{ $dailyreport && $dailyreport->feedback != null? $dailyreport->feedback : ""}}" {{$dailyreport && $dailyreport->status == 0? "required" : ""}}>
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
                                                            $dailyreport = App\Dailyreport::where('report_id', $dplan->id)->where('subtask_id', $subtask->id)->first();
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
                                                                            <input type="radio" class="css-control-input stogglestatus" name="subradio-group{{$subtask->id}}" onchange="substatuscheck({{$subtask->id}})"  value=1 {{$dailyreport && $dailyreport->status == 1? "checked" : ""}}>
                                                                            <span class="css-control-indicator"></span> Achieved
                                                                        </label>
                                                                        <label class="css-control-sm css-control-danger css-radio mt-5">
                                                                            <input id="failedsubradio-group{{$subtask->id}}" type="radio" class="css-control-input stogglestatus" name="subradio-group{{$subtask->id}}" data-id="{{$subtask->id}}"  value=0 {{$dailyreport && $dailyreport->status == 0? "checked" : ""}}>
                                                                            <span class="css-control-indicator"></span> Not Yet
                                                                        </label>
                                                                    </div>
                                                                    
                                                                   
                                                                    <div class="col-md-3">
                                                                        <input type="text" class="form-control round" placeholder="Reason" name="subfeedback{{$subtask->id}}" id="subfeedback{{$subtask->id}}" value = "{{$dailyreport && $dailyreport->feedback != null? $dailyreport->feedback : ""}}" {{$dailyreport && $dailyreport->status == 0? "required" : ""}} >
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
                                          @php
                                          $ccarr = 0;
                                            if($dplan->cc != null){
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
                            <button type="submit" class="btn btn-rounded btn-outline-primary submitreportplan" >Edit report</button>
                            <div class="col-md-1"></div>
                            <a  class="btn btn-rounded btn-outline-danger cancelreportplan" href="{{ route('dailyreport', $team->id) }}">Cancel</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
  

    
</div>


 <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.bundle.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/js/bootstrap-select.min.js"></script>
@endsection

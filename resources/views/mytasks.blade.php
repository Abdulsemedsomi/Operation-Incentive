@extends('layouts.backend')
@section('content')
<div class="mt-50 mb-10 text-center">
    <h2 class="font-w700 text-black mb-10">My Tasks</h2>
    <hr>
</div>
<div class="container">
    <div class="block">
        <div class="block-content">
            <form action="{{route('changetaskReport')}}" method="post">
                @csrf
                <div class="form-group row">
                    <label class="col-12" for="example-select">Session</label>
                    <div class="col-md-5">
                        <select class="form-control" id="session-select" name="session-select">
                            @php
                                $sessions = App\Session::all();
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
        <div class="block-content">
             @foreach($objectives as $objs)
                @php
                    $metric = App\Keyresult::where('objective_id', $objs->id)->get();
                @endphp
                <h5 class="font-w400">{{$objs->objective_name}}</h5>
                @foreach($metric as $m)
                    @if($m->objective_id == $objs->id)
                        <div class="card">
                            <div class="card-header" id="heading{{$m->id}}" style="font-size: 14px;">
                                <div class="row ">
                                    <a class="col-md-3" value={{$m->id}}>{{$m->keyresult_name}}</a>
                                    <p class=" mt-5 col-md-2 "><i class="si si-flag mr-10 text-success"></i>{{App\Task::where('isMilestone', 1)->where('keyresultid', $m->id)->where('status', 0)->get()->count()}} Milestones</p>
                                    <p class=" mt-5 col-md-2" ><i class="fa fa-tasks mr-10 text-warning"></i><span id="taskno{{$m->id}}">{{App\Task::where('isMilestone', 0)->where('keyresultid', $m->id)->where('tasks.isactive', 1)->get()->count()}} </span> Tasks</p>
                                    <p class=" mt-5 col-md-2" ><i class="fa fa-bullseye mr-10 text-{{$m->attainment* 100 > 70 ?'success':($m->attainment* 100 > 30?'warning':'danger') }}"></i><span >{{$m->attainment* 100}}% </span> Achieved</p>
                                    <a class="btn btn-sm mt-5 col-md-1" type="button" data-toggle="collapse" data-target="#collapse{{$m->id}}" aria-expanded="false" aria-controls="collapseTwo">
                                        <i class="si si-arrow-down"></i>
                                    </a>
                                </div>
                            </div>
                            <div id="collapse{{$m->id}}" class="collapse" aria-labelledby="heading{{$m->id}}" data-parent="#accordionExample">
                                <div class="card-body  mt-10 mb-10" >
                                    <button type="button" class=" btn btn-success round addmytask  float-right" id="addweeklytask{{$m->id}}" data-id = '{{$m->id}}' {{App\Task::where('isMilestone', 1)->where('keyresultid', $m->id)->where('isactive', 1)->where('status', 0)->get()->count() > 0?'':'disabled'}}><i class="fa fa-plus mr-5"></i> Add Task</button>
                                </div>
                                <div class="card-body" id="tasks-list{{$m->id}}">
                                    @foreach($db as $task)
                                        @if($task->keyresultid == $m->id)
                                            <div class="container" id="taskl{{$task->id}}">
                                                <div class="row mb-10">
                                                    <div class="col-md-7 mt-5">
                                                        @if($task->status==0)
                                                            <label class="css-control-sm css-control-info css-checkbox css-checkbox-rounded ">
                                                                <span class="css-control-indicator"></span> {{$task->taskname}}
                                                            </label>
                                                        @else
                                                            <label class="css-control-sm css-control-info css-checkbox css-checkbox-rounded text-success">
                                                                <span class="css-control-indicator "></span> {{$task->taskname}}
                                                            </label>
                                                        @endif
                                                    </div>
                                                    <div class ="col-md-3">
                                                        <a class="mt-5"><span class="dot mt-5 "></span> {{App\Task::find($task->parent_task)->taskname}}</a>
                                                    </div>
                                                    <a class="col-md-1 col-sm-1" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                                                            <i class="fa fa-bars"></i>
                                                    </a>
                                                    <div class="dropdown-menu dropdown-menu-right min-width-200 " aria-labelledby="page-header-user-dropdown" style="position: absolute; transform: translate3d(-103px, 34px, 0px); top: 0px; left: 0px; will-change: transform;" x-placement="bottom-end">
                                                        <a class="dropdown-item d-flex align-items-center justify-content-between editmytask" type="button" data-id="{{$task->id}}">
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
                            </div>
                        </div>
                    @endif
                @endforeach
            @endforeach
        </div>
    </div>
</div>
@include('includes.weeklyplan-addmodal')
@endsection

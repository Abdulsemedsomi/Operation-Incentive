@extends('layouts.backend')
@section('content')

<style>
.c-progress {
    transition: all 1s ease-in-out;
    width: 150px;
    height: 150px;
    background: none;
    position: relative;
}

.c-progress::after {
    content: "";
    width: 100%;
    height: 100%;
    border-radius: 50%;
    border: 12px solid #eee;
    position: absolute;
    top: 0;
    left: 0;
    transition: all 1s ease-in-out;

}

.c-progress>span {
    transition: all 1s ease-in-out;
    width: 50%;
    height: 100%;
    overflow: hidden;
    position: absolute;
    top: 0;
    z-index: 1;
}

.c-progress .c-progress-left {
    left: 0;
}

.c-progress .c-progress-bar {
    width: 100%;
    height: 100%;
    background: none;
    border-width: 12px;
    border-style: solid;
    position: absolute;
    top: 0;
}

.c-progress .c-progress-left .c-progress-bar {
    transition: all 1s ease-in-out;
    left: 100%;
    border-top-right-radius: 80px;
    border-bottom-right-radius: 80px;
    border-left: 0;
    -webkit-transform-origin: center left;
    transform-origin: center left;
}

.c-progress .c-progress-right {
    right: 0;
}

.c-progress .c-progress-right .c-progress-bar {
    transition: all 1s ease-in-out;
    left: -100%;
    border-top-left-radius: 80px;
    border-bottom-left-radius: 80px;
    border-right: 0;
    -webkit-transform-origin: center right;
    transform-origin: center right;
}

.c-progress .c-progress-value {
    position: absolute;
    top: 0;
    left: 0;
}
</style>
<div class=" container">
    <div' class="row justify-content-center">
        <!--  current user okr card -->
        <div class='p-10 col-11 col-sm-7'>
            <div style='height:100%; border-radius: 6px; box-shadow:none;' class='card container'>
                <!--header -->
                <div class='row p-10 d-flex align-items-center'>
                    <div class='col-6 d-flex pt-5 flex-row align-items-center'>
                        @if(Auth::User()->avatar == null)
                        <div class="avatar-circle2 d-flex justify-content-center align-items-center "
                            style="width:40px; height:40px; border-radius:50%; --ccolor: #{{Auth::User()->avatarcolor}};">
                            <span class="pinitials1">{{Auth::User()->fname[0] . Auth::User()->lname[0]}}</span>
                        </div>
                        @else
                        <img class='rounded-circle'
                            src="https://ienetworks.co/pms/uploads/avatars/{{ Auth::User()->avatar }}"
                            style="width:40px; height:40px;">
                        @endif
                        <h6 class='m-0 px-2'>{{ Auth::user()->fullname }}</h6>
                    </div>
                    @if (Auth::user()->ismanager == 1)
                    <ul class='nav nav-tabs right ml-auto'>
                        <li class='active nav-item'>
                            <a class="nav-link clickable-link active" id="myokr-tab" data-toggle="tab" href="#myokr"
                                role="tab" aria-controls="myokr" aria-selected="true">My OKR</a>
                        </li>
                        <li class='active nav-item'>
                            <a class="nav-link clickable-link" id="teamokr-tab" data-toggle="tab" href="#teamokr"
                                role="tab" aria-controls="teamokr" aria-selected="true">My Team</a>
                        </li>
                    </ul>
                    @endif
                </div>
                <!-- body -->
                <div class="tab-content" id="myTabContent">
                    <!-- <div class='d-flex  align-items-center'> -->
                    <div class="col-lg-12 tab-pane fade show active" id="myokr" role="tabpanel"
                        aria-labelledby="okr-tab">
                        @php
                        $objcount = 1;
                        $okrAttainment = 0;
                        @endphp
                        @foreach($goal as $m)
                        <!--iterate through metrics -->
                        <div class="panel task-panel childClass">
                            <div class="panel-heading">
                                <div class="panel-title">
                                    <div class="row">

                                        <a class="task col-11 ml-20  text-muted" data-toggle="collapse"
                                            data-parent="#accordion" href="#metric{{$m->id}}" show>
                                            <!-- <i class="fa fa-dot-circle-o mt-5 mr-5 text-success"></i> -->
                                            {{$m->objective_name}}
                                            <!-- <span class="pull-right"><i
                                                    class="si si-arrow-down text-primary"></i></span> -->
                                        </a>
                                        <?php
                                           $cal = round($m->attainment * 100, 2);
                                           $okrAttainment += $m->attainment;
                                           $message = $cal <=30 ? "danger":($cal <= 75 ? "warning": "success");
                                         ?>
                                        <div class="col-11 mt-5 d-flex justify-content-center align-items-center">
                                            <div class="col-12 ml-10">
                                                <div class="progress" style="height:4px; border-radius:8px">
                                                    <div class="progress-bar bg-{{$message}}" role="progressbar"
                                                        style="width: {{$cal}}%;" aria-valuenow="30" aria-valuemin="0"
                                                        aria-valuemax="100"></div>
                                                </div>
                                            </div>
                                            <!-- <div class="col-md-1">
                                            <div class="text-muted">{{$cal}}%</div>
                                        </div> -->
                                        </div>
                                    </div>

                                </div>
                            </div>
                            <div class="ml-20 mt-10">
                                <div id="metric{{$m->id}}" class="panel-collapse collapse">
                                    <div class="panel-body">
                                        <ul id="tasks-list{{$m->id}}">
                                            <?php
                                               $metriccount=1;
                                             ?>
                                            @foreach($db as $task)
                                            @if($task->objective_id == $m->id)
                                            <li style="list-style: none" id="task{{$task->id}}">
                                                <div class="row">
                                                    <div class="col-sm-8">
                                                        <p class="small text-muted"><i
                                                                class="si si-graph mt-5 mr-5 text-info"></i>
                                                            {{$task->keyresult_name}}
                                                        </p>
                                                    </div>
                                                    <?php
                                                       $cal = round($task->attainment * 100, 2);
                                                       $message = $cal <=30 ? "danger":($cal <= 75 ? "warning": "success");
                                                     ?>
                                                    <div class="col-md-3 mt-5 px-0 d-flex flex-row align-items-center">
                                                        <div class="flex-auto progress"
                                                            style="height: 4px;width: 75%; border-radius:4px">
                                                            <div class="progress-bar m-0  progress-bar progress-bar-animated bg-{{$message}}"
                                                                role="progressbar" style="width: {{$cal}}%;"
                                                                aria-valuenow="30" aria-valuemin="0"
                                                                aria-valuemax="100">
                                                            </div>
                                                        </div>
                                                        <div class="pl-2 text-muted">{{$cal}}%</div>
                                                    </div>

                                                </div>
                                            </li>
                                            @endif
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    <!--my team okr -->
                    <div class="col-lg-12 tab-pane fade" id="teamokr" role="tabpanel" aria-labelledby="teamokr-tab">
                        @php
                        $objcount = 1;
                        @endphp
                        @foreach($goal as $m)
                        <!--iterate through metrics -->
                        <div class="panel task-panel childClass">
                            <div class="panel-heading">
                                <div class="panel-title">
                                    <div class="row">

                                        <a class="task col-11 ml-20  text-muted" data-toggle="collapse"
                                            data-parent="#accordion" href="#teammetric{{$m->id}}" show>
                                            <!-- <i class="fa fa-dot-circle-o mt-5 mr-5 text-success"></i> -->
                                            {{$m->objective_name}}
                                            <!-- <span class="pull-right"><i
                                                    class="si si-arrow-down text-primary"></i></span> -->
                                        </a>
                                        <?php
                                           $calx = round($m->attainment * 100, 2);
                                           $messagex = $calx <=30 ? "danger":($calx <= 75 ? "warning": "success");
                                         ?>
                                        <div class="col-11 mt-5 d-flex justify-content-center align-items-center">
                                            <div class="col-12 ml-10">
                                                <div class="progress" style="height:4px; border-radius:4px">
                                                    <div class="progress-bar bg-{{$messagex}}" role="progressbar"
                                                        style="width: {{$calx}}%;" aria-valuenow="30" aria-valuemin="0"
                                                        aria-valuemax="100"></div>
                                                </div>
                                            </div>
                                            <!-- <div class="col-md-1">
                                            <div class="text-muted">{{$cal}}%</div>
                                        </div> -->
                                        </div>
                                    </div>

                                </div>
                            </div>
                            <div class="ml-20 mt-10">
                                <div id="teammetric{{$m->id}}" class="panel-collapse collapse">
                                    <div class="panel-body">
                                        <ul id="tasks-list{{$m->id}}">
                                            @php
                                            $alignedobj = App\Objective::where('aligned_to',
                                            $m->id)->get();
                                            @endphp
                                            @foreach($alignedobj as $task)
                                            <li style="list-style: none" id="task{{$task->id}}">
                                                <div class="row">
                                                    <div class="col-sm-8">
                                                        <p class="small text-muted"><i
                                                                class="si si-graph mt-5 mr-5 text-info"></i>
                                                            {{$task->objective_name}} (
                                                            {{ App\User::find($task->user_id) ? App\User::find($task->user_id)->fname. " " . App\User::find($task->user_id)->lname:"No name" }}
                                                        </p>
                                                    </div>
                                                    <?php
                                                       $cal = round($task->attainment * 100, 2);
                                                       $message = $cal <=30 ? "danger":($cal <= 75 ? "warning": "success");
                                                     ?>
                                                    <div class="col-md-3 mt-5 px-0 d-flex flex-row align-items-center">
                                                        <div class="flex-auto progress"
                                                            style="height: 4px;width: 75%; border-radius:4px">
                                                            <div class="progress-bar m-0  progress-bar progress-bar-animated bg-{{$message}}"
                                                                role="progressbar" style="width: {{$cal}}%;"
                                                                aria-valuenow="30" aria-valuemin="0"
                                                                aria-valuemax="100">
                                                            </div>
                                                        </div>
                                                        <div class="pl-2 text-muted">{{$cal}}%</div>
                                                    </div>

                                                </div>
                                            </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    <!-- </div> -->
                </div>
                @php
                $disabled = $goal->count() >= 6? 'ancdisabled': "";
                $title = $goal->count() >= 6? 'You have reached objective limit': "";
                @endphp
                <div class='d-flex mr-30 justify-content-end'>
                    @if(Gate::any(['okr', 'addObjective', 'assignokr']) && $session->status == 'Active' )
                    <a type="button" class="btn btn-rounded btn-outline-primary {{$disabled}}"
                        href="{{route('okr.create', $session->id)}} " value='{{$session->id}}' title='{{$title}}'>Add
                        Objective</a>
                    @endif
                </div>
                @if($message = Session::get('successo'))
                <div class="alert alert-success alert-block col-md-7">
                    <button type="button" class="close" data-dismiss="alert">×</button>
                    <strong>{{ $message }}</strong>
                </div>
                @elseif($message = Session::get('erroro'))
                <div class="alert alert-danger alert-block col-md-7">
                    <button type="button" class="close" data-dismiss="alert">×</button>
                    <strong>{{ $message }}</strong>
                </div>
                @endif
            </div>

        </div>
        <!-- okr card END-->
        <!-- okr progress card  -->
        @php
        $okrProgress = count($goal)==0?0:round(($okrAttainment / count($goal))*100,1)
        @endphp
        <div style='border-radius:10px;' class='p-10 col-11 col-sm-4'>
            <div style='height:100%; max-height:60vh; border-radius: 6px; box-shadow:none;'
                class='card justify-content-center align-items-center'>
                <h4 class='primary header-title font-weight-bold'>OKR Progress</h4>
                <div class="c-progress mx-auto" data-value='{{$okrProgress}}'>
                    <span class="c-progress-left">
                        <span class="c-progress-bar border-primary"></span>
                    </span>
                    <span class="c-progress-right">
                        <span class="c-progress-bar border-primary"></span>
                    </span>
                    <div
                        class="progress-value w-100 h-100 rounded-circle d-flex align-items-center justify-content-center">
                        <div class="h2 font-weight-bold m-0 p-0  d-flex align-items-center justify-content-center">
                            {{$okrProgress}}%
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- okr progress card END -->
        <!-- all employee's okr -->
        <div class="col-11 px-10">
            <div style='box-shadow:none;' class="card  p-20">
                @include('includes.employeeokr')
            </div>
        </div>
</div>
</div>
<script>
$(function() {
    $(".c-progress").each(function() {

        var value = $(this).attr('data-value');
        var left = $(this).find('.c-progress-left .c-progress-bar');
        var right = $(this).find('.c-progress-right .c-progress-bar');

        if (value > 0) {
            if (value <= 50) {
                right.css('transform', 'rotate(' + percentageToDegrees(value) + 'deg)')
            } else {
                right.css('transform', 'rotate(180deg)')
                left.css('transform', 'rotate(' + percentageToDegrees(value - 50) + 'deg)')
            }
        }

    })

    function percentageToDegrees(percentage) {
        return percentage / 100 * 360
    }

});
</script>

@endsection
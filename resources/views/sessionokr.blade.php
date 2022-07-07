@extends('layouts.backend')
@section('content')
<style>
.node {
    cursor: pointer;
}

.overlay {
    background-color: #EEE;
}

#session {
    display: none;
}

table tr:first-child td {
    border-top: 0;

}

.node circle {
    fill: #fff;
    stroke: steelblue;
    stroke-width: 1.5px;
}

.node text {
    font-size: 10px;
    font-family: sans-serif;
}

.link {
    fill: none;
    stroke: #ccc;
    stroke-width: 1.5px;
}

.templink {
    fill: none;
    stroke: red;
    stroke-width: 3px;
}

.ghostCircle.show {
    display: block;
}

.ghostCircle,
.activeDrag .ghostCircle {
    display: none;
}
</style>
<div class="mt-50 mx-50">
    <div class="row">
        <ul class="nav nav-tabs" id="myTab" role="tablist">
            <li class="nav-item">
                <a class="nav-link clickable-link active" id="home-tab" data-toggle="tab" href="#home" role="tab"
                    aria-controls="home" aria-selected="false">{{$session->session_name}}</a>
            </li>
            <li class="nav-item">
                <a class="nav-link clickable-link" id="okr-tab" data-toggle="tab" href="#myokr" role="tab"
                    aria-controls="myokr" aria-selected="true">My OKR</a>
            </li>
            @if(Auth::user()->ismanager == 1)
            <li class="nav-item">
                <a class="nav-link clickable-link" id="myteam-tab" data-toggle="tab" href="#myteam" role="tab"
                    aria-controls="myteam" aria-selected="false">My Team</a>
            </li>
            @endif
            <!--<li class="nav-item">-->
            <!--    <a class="nav-link clickable-link "  id="align-tab" data-toggle="tab" href="#align" role="tab" aria-controls="align" aria-selected="false">Alignment</a>-->
            <!--</li>-->
        </ul>
    </div>
    {{-- manage okr tab --}}
    <div class="tab-content" id="myTabContent">
        <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
            @php
            $disabled = $goal->count() >= 6? 'ancdisabled': "";
            $title = $goal->count() >= 6? 'You have reached objective limit': "";
            @endphp
            <div class="text-right mb-10">
                @if(Gate::any(['okr', 'addObjective', 'assignokr']) && $session->status == 'Active' )
                <a id='xcx' name='xcx' href type="button" class="btn btn-rounded btn-outline-info {{$disabled}}"
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
            <div class="block">
                <div class="block-content">
                    <table class="my-2 table table-hover table-responsive" id="session">
                        <thead class="thead-info">
                            <tr>
                                <th>Owner</th>
                                <th style="width: 45%;">Objective</th>
                                <th style="width: 5%;"># of KRs</th>
                                <th>Key Results</th>
                                <th>Objective Attainment</th>
                            </tr>
                        </thead>
                        <tbody id="objlist">
                            @foreach($objectives as $objective)
                            <tr id="okrlist{{$objective->id}}">
                                <?php
                            $keyresults = DB::table('keyresults')->where('objective_id', '=', $objective->id)->get()
                            ?>
                                <td>
                                    @if($objective->avatar == null)
                                    <div class="avatar-circle2"
                                        style="width:35px; height:35px; border-radius:50%; --ccolor: #{{$objective->avatarcolor}};">
                                        <span class="pinitials1">{{$objective->fname[0] . $objective->lname[0]}}</span>
                                    </div>

                                    @else
                                    <img src="https://ienetworks.co/pms/uploads/avatars/{{ $objective->avatar }}"
                                        style="width:35px; height:35px; border-radius:50%;">
                                    @endif
                                    @include('includes.objectivemodals')
                                </td>

                                <td style="width: 45%;">
                                    @if((Gate::any(['okr', 'assignokr']) || $objective->uid == Auth::user()->id) &&
                                    $session->status =="Active")
                                    <div class="aclickable-row dropdown" data-id={{$objective->id}}
                                        data-toggle="dropdown" id="navbarDropdown3" data-toggle="dropdown">
                                        {{$objective->objective_name}}&#10; <p class="text-muted">
                                            {{$objective->fname . " ". $objective->lname}}</p>
                                    </div>
                                    <div class="dropdown-menu " aria-labelledby="navbarDropdown3">
                                        <li><a id="update{{$objective->id}}" class="dropdown-item "
                                                data-id={{$objective->id}} data-toggle="modal"
                                                data-target="#objectivedetails{{$objective->id}}">Details</a></li>
                                        <li><a id="edit{{$objective->id}}" class="dropdown-item editobj"
                                                data-id={{$objective->id}} data-toggle="modal"
                                                data-target="#editobjective{{$objective->id}}">Edit</a></li>
                                        <li><a id="delete{{$objective->id}}" class="dropdown-item deleteobj"
                                                data-id={{$objective->id}} data-toggle="modal"
                                                data-target="#deleteobjective{{$objective->id}}">Delete</a></li>
                                    </div>
                                    @else
                                    <div class="aclickable-row " data-id={{$objective->id}} data-toggle="modal"
                                        data-target="#objectivedetails{{$objective->id}}">
                                        {{$objective->objective_name}} &#10; <p class="text-muted">
                                            {{$objective->fname . " ". $objective->lname}}</p>
                                    </div>
                                    @endif

                                </td>

                                <td class="krcount">
                                    <div class="aclickable-row numberkr{{$objective->id}}" data-id={{$objective->id}}>
                                        {{$keyresults->count()}}</div>
                                </td>
                                <td>
                                    @if($keyresults->count() > 0)
                                    <ul class="krlistokr{{$objective->id}}">
                                        @php
                                        $count = 1;
                                        @endphp
                                        <table>
                                            <tbody>
                                                @foreach($keyresults as $keyresult)
                                                <tr id="krval{{$keyresult->id}}">
                                                    <td>
                                                        <li class="row">
                                                            <div class="col-md-8 aclickable-row"
                                                                data-id="{{$keyresult->id}}"
                                                                onclick="addtaskkr({{$keyresult->id}})">
                                                                {{$keyresult->keyresult_name}}</div>
                                                            <div class="col-md-2" id="frontkratt{{$keyresult->id}}">
                                                                {{round($keyresult->attainment * 100, 2)}}%
                                                            </div>
                                                        </li>
                                                        {{-- @if($keyresults->count() > $count++ )<hr id="krhr{{$keyresult->id}}"
                                                        style="text-align:center;margin-left:0"> @endif --}}
                                                    </td>
                                                </tr>



                                                @endforeach
                                            </tbody>
                                        </table>
                                    </ul>
                                </td>
                                @endif
                                <td>
                                    <div class="font-w600" style="font-size: 18px" id="frontobj{{$objective->id}}">
                                        {{round($objective->attainment * 100, 2)}}%</div>
                                </td>

                            </tr>
                            @endforeach

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        {{-- my okr tab --}}
        <div class="col-lg-12 tab-pane fade" id="myokr" role="tabpanel" aria-labelledby="okr-tab">
            <br>
            <br>
            <div class="row">
                <div class="col-md-12">
                    <div class="block">
                        <div class="block-content">
                            <div class="panel panel-default">
                                <div class="panel-body">
                                    <div class="col-lg-12">
                                        <div class="panel panel-info">
                                            <div class="panel-heading">
                                                <h4 class="panel-title my-1"><b> My OKR</b></h4>
                                            </div>
                                            <div class="panel-body">
                                                <hr>
                                                @php
                                                $objcount = 1;
                                                @endphp
                                                @foreach($goal as $m)
                                                <!--iterate through metrics -->
                                                <div class="panel task-panel childClass">
                                                    <div class="panel-heading">
                                                        <div class="panel-title">
                                                            <div class="row">

                                                                <a class="task col-md-8 h5 text-muted font-w400"
                                                                    data-toggle="collapse" data-parent="#accordion"
                                                                    href="#metric{{$m->id}}" show>
                                                                    <i
                                                                        class="fa fa-dot-circle-o mt-5 mr-5 text-success"></i>
                                                                    {{$m->objective_name}}
                                                                    <span class="pull-right"><i
                                                                            class="si si-arrow-down text-primary"></i></span>
                                                                </a>
                                                                <?php
                                                                        $cal = round($m->attainment * 100, 2);
                                                                        $message = $cal <=30 ? "danger":($cal <= 75 ? "warning": "success");
                                                                   ?>
                                                                <div class="col-md-3 mt-5">
                                                                    <div class="progress push" style="height: 8px;">
                                                                        <div class="progress-bar progress-bar-striped bg-{{$message}}"
                                                                            role="progressbar" style="width: {{$cal}}%;"
                                                                            aria-valuenow="30" aria-valuemin="0"
                                                                            aria-valuemax="100"></div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-1">
                                                                    <div class="text-muted">{{$cal}}%</div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="ml-20">
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
                                                                                <p class="text-muted font-w300"><i
                                                                                        class="si si-graph mt-5 mr-5 text-info"></i>
                                                                                    {{$task->keyresult_name}}</p>
                                                                            </div>
                                                                            <?php
                                                                                            $cal = round($task->attainment * 100, 2);
                                                                                            $message = $cal <=30 ? "danger":($cal <= 75 ? "warning": "success");
                                                                                        ?>
                                                                            <div class="col-md-3 mt-5">
                                                                                <div class="progress push"
                                                                                    style="height: 8px;">
                                                                                    <div class="progress-bar progress-bar-striped progress-bar-animated bg-{{$message}}"
                                                                                        role="progressbar"
                                                                                        style="width: {{$cal}}%;"
                                                                                        aria-valuenow="30"
                                                                                        aria-valuemin="0"
                                                                                        aria-valuemax="100"></div>
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-md-1">
                                                                                <div class="text-muted">{{$cal}}%</div>
                                                                            </div>
                                                                        </div>
                                                                    </li>
                                                                    @endif
                                                                    @endforeach
                                                                </ul>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <hr>
                                                </div>
                                                @endforeachs
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @if(Auth::user()->ismanager == 1)
        <div class="col-lg-12 tab-pane fade" id="myteam" role="tabpanel" aria-labelledby="myteam-tab">
            <br>
            <br>
            <div class="row">
                <div class="col-md-12">
                    <div class="block">
                        <div class="block-content">
                            <div class="panel panel-default">
                                <div class="panel-body">
                                    <div class="col-lg-12">
                                        <div class="panel panel-info">
                                            <div class="panel-heading">
                                                <h4 class="panel-title my-1"><b> My Team</b></h4>
                                            </div>
                                            <div class="panel-body">
                                                <hr>
                                                @php
                                                $objcount = 1;
                                                @endphp
                                                @foreach($goal as $m)
                                                <!--iterate through metrics -->
                                                <div class="panel task-panel childClass">
                                                    <div class="panel-heading">
                                                        <div class="panel-title">
                                                            <div class="row">

                                                                <a class="task col-md-8 h5 text-muted font-w400"
                                                                    data-toggle="collapse" data-parent="#accordion"
                                                                    href="#teammetric{{$m->id}}" show>
                                                                    <i
                                                                        class="fa fa-dot-circle-o mt-5 mr-5 text-success"></i>
                                                                    {{$m->objective_name}}
                                                                    <span class="pull-right"><i
                                                                            class="si si-arrow-down text-primary"></i></span>
                                                                </a>
                                                                <?php
                                                                        $cal = round($m->attainment * 100, 2);
                                                                        $message = $cal <=30 ? "danger":($cal <= 75 ? "warning": "success");
                                                                   ?>
                                                                <div class="col-md-3 mt-5">
                                                                    <div class="progress push" style="height: 8px;">
                                                                        <div class="progress-bar progress-bar-striped bg-{{$message}}"
                                                                            role="progressbar" style="width: {{$cal}}%;"
                                                                            aria-valuenow="30" aria-valuemin="0"
                                                                            aria-valuemax="100"></div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-1">
                                                                    <div class="text-muted">{{$cal}}%</div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="ml-20">
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
                                                                                <p class="text-muted font-w300"><i
                                                                                        class="si si-graph mt-5 mr-5 text-info"></i>
                                                                                    {{$task->objective_name}} (
                                                                                    {{ App\User::find($task->user_id) ? App\User::find($task->user_id)->fname. " " . App\User::find($task->user_id)->lname:"No name" }}
                                                                                    )</p>
                                                                            </div>
                                                                            <?php
                                                                                            $calp = round($task->attainment * 100, 2);
                                                                                            $message = $cal <=30 ? "danger":($cal <= 75 ? "warning": "success");
                                                                                        ?>
                                                                            <div class="col-md-3 mt-5">
                                                                                <div class="progress push"
                                                                                    style="height: 8px;">
                                                                                    <div class="progress-bar progress-bar-striped progress-bar-animated bg-{{$message}}"
                                                                                        role="progressbar"
                                                                                        style="width: {{$cal}}%;"
                                                                                        aria-valuenow="30"
                                                                                        aria-valuemin="0"
                                                                                        aria-valuemax="100"></div>
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-md-1">
                                                                                <div class="text-muted">{{$cal}}%</div>
                                                                            </div>
                                                                        </div>
                                                                    </li>

                                                                    @endforeach
                                                                </ul>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <hr>
                                                </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endif
        {{-- alignment tab --}}
        <!--<div class="col-lg-12 tab-pane fade" id="align" role="tabpanel" aria-labelledby="align-tab">-->

        <!--    <div id="tree-container"></div>-->
        <!--</div>-->
    </div>
</div>
<script>
var baseurl = window.location.protocol + "//" + window.location.host + '/pms/';
$(document).ready(function() {
    $('#session').DataTable({
        "ordering": false,
        "info": false,
        'pageLength': 10
    });
    $('#session').show()

});

function addtaskkr(id) {

    $.get(baseurl + "showkeyresult/" + id, function(data) {

        document.getElementById("krname").innerHTML = data[0].keyresult_name
        var value = '<p data-letters=' + data[0].fname[0] + data[0].lname[0] + ' class="objvalue col-md-10">' +
            data[0].fname + " " + data[0].lname +
            '</p><p class="col-md-2 float-right objattainment" id="objattainment">' + parseFloat(data[0]
                .attainment).toFixed(2) * 100 + '% </p>'

        document.getElementById("objowner").innerHTML = value

        document.querySelector('.addmilestoneblock').setAttribute('id', 'addmilestoneblock' + id);
        document.querySelector('.addmilestoneblock').innerHTML = ""
        document.querySelector('.milestonelist').setAttribute('id', 'milestonelist' + id);
        var count = 1
        var tasks = ""
        for (var i = 0; i < data[1].length; i++) {
            if (data[1][i].isactive == 1) {
                var message = data[1][i].status == 0 ? "Not Achieved" : "Achieved"
                tasks += '<li id="miletaskl' + data[1][i].id +
                    '" class="container"><div class = "row" ><div class="col-md-7"><b class="milevalue">' +
                    data[1][i].taskname + '</b> </div>'
                tasks += '<div class="col-md-4" id="taskstat' + data[1][i].id + '"><p>' + message + '</p></div>'
                if (data[2] == 1) {
                    tasks += '<div class="col-md-1 pull-right">'
                    tasks += '<button type="button" id ="editmiletask' + data[1][i].id +
                        '" class="btn btn-sm btn-rounded btn-outline-success  editmile" data-id= "' + data[1][i]
                        .id + '" ><i class="si si-pencil"></i></button></div>'

                }
                tasks += '</div></li>'
            } else if (data[1][i].isactive == 0) {
                var message = data[1][i].status == 0 ? "Not Achieved/Deleted" : "Achieved/Deleted"
                tasks += '<li id="miletaskl' + data[1][i].id +
                    '" class="container"><div class = "row" ><div class="col-md-7"><b class="milevalue">' +
                    data[1][i].taskname + '</b> </div>'
                tasks += '<div class="col-md-4" id="taskstat' + data[1][i].id + '"><p>' + message + '</p></div>'
                if (data[2] == 1) {
                    tasks += '<div class="col-md-1 pull-right">'
                    tasks += '<button type="button" id ="editmiletask' + data[1][i].id +
                        '" class="btn btn-sm btn-rounded btn-outline-success  editmile" data-id= "' + data[1][i]
                        .id + '" ><i class="si si-pencil"></i></button></div>'

                }
                tasks += '</div></li>'
            }
        }
        document.getElementById("milestonelist" + id).innerHTML = tasks

        jQuery("#addtaskkr").modal("show");

    });


}
</script>
@include('includes.objective-kr-modals')
@include("includes.krcrud-modals")
@endsection
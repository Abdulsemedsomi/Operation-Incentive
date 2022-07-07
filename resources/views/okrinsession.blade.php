@extends('layouts.backend')
@section('content')
<style>
        .node {
        cursor: pointer;
    }

    .overlay{
        background-color:#EEE;
    }
    #session{
        display:none;
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
        font-size:10px;
        font-family:sans-serif;
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

    .ghostCircle.show{
        display:block;
    }

    .ghostCircle, .activeDrag .ghostCircle{
        display: none;
    }

</style>
 <div class="mt-50 mx-50">
     <div class="row">
        <ul class="nav nav-tabs" id="myTab" role="tablist">
            <li class="nav-item">
                <a class="nav-link clickable-link active" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true">{{$session->session_name}}</a>
            </li>
            <li class="nav-item">
                <a class="nav-link clickable-link" id="okr-tab" data-toggle="tab" href="#myokr" role="tab" aria-controls="myokr" aria-selected="false">My OKR</a>
                </li>
            <!--<li class="nav-item">-->
            <!--    <a class="nav-link clickable-link "  id="align-tab" data-toggle="tab" href="#align" role="tab" aria-controls="align" aria-selected="false">Alignment</a>-->
            <!--</li>-->
          </ul>
     </div >

{{-- manage okr tab --}}
 <div class="tab-content" id="myTabContent">
    <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
      
            <div class="text-right mb-10">
                
                 @if(Gate::any(['newrole']))
                <a id="addObjective2" type="button" class="btn btn-rounded btn-outline-info "  href="{{route('okr.create', $session->id)}}">Add Objective</a>
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
                                    <!--<div class="name">{{$objective->fname ." ". $objective->lname}}</div>-->
                                    <div class="avatar-circle2" style="width:35px; height:35px; border-radius:50%;">
                                        <span class="pinitials1">{{$objective->fname[0] . $objective->lname[0]}}</span>
                                    </div>
                            @else
                                    <img src="https://ienetworks.co/pms/uploads/avatars/{{ $objective->avatar }}" style="width:50px; height:50px; border-radius:50%;">
                            @endif
                           
                            </td>
                            
                             <td style="width: 45%;">
                                @if(Gate::any(['okr', 'assignokr']) || $objective->uid == Auth::user()->id)
                                    <div class="aclickable-row dropdown" data-id={{$objective->id}} data-toggle="dropdown" id="navbarDropdown3"  data-toggle="dropdown"> {{$objective->objective_name}}&#10; <p class="text-muted">{{$objective->fname . " ". $objective->lname}}</p> </div>
                                    <div class="dropdown-menu " aria-labelledby="navbarDropdown3">
                                        <li><a id="update{{$objective->id}}" class="dropdown-item " data-id= {{$objective->id}}  data-toggle="modal" data-target="#objectivedetails{{$objective->id}}">Details</a></li>
                                        <li><a  id ="edit{{$objective->id}}" class="dropdown-item editobj" data-id= {{$objective->id}} data-toggle="modal" data-target="#editobjective{{$objective->id}}">Edit</a></li>
                                        <li><a id="delete{{$objective->id}}" class="dropdown-item deleteobj" data-id= {{$objective->id}} data-toggle="modal" data-target="#deleteobjective{{$objective->id}}">Delete</a></li>
                                    </div>
                                @else
                                    <div class="aclickable-row " data-id= {{$objective->id}} data-toggle="modal" data-target="#objectivedetails{{$objective->id}}"> {{$objective->objective_name}} &#10; <p class="text-muted">{{$objective->fname . " ". $objective->lname}}</p> </div>
                                @endif
                             
                            </td>

                            <td class="krcount" ><div class="aclickable-row numberkr{{$objective->id}}" data-id={{$objective->id}}>{{$keyresults->count()}}</div></td>
                            <td>
                                @if($keyresults->count() > 0)
                                <ul class="krlistokr{{$objective->id}}">
                                    @php
                                        $count = 1;
                                    @endphp
                                    <table >
                                        <tbody >
                            @foreach($keyresults as $keyresult)
                                        <tr id="krval{{$keyresult->id}}">
                                            <td>
                                <li class = "row" > <div class= "col-md-8 aclickable-row" data-id={{$keyresult->id}} data-toggle="modal" data-target="#addtaskkr{{$keyresult->id}}"> {{$keyresult->keyresult_name}}</div>
                                    <div class= "col-md-2" id="frontkratt{{$keyresult->id}}">
                                        {{round($keyresult->attainment * 100, 2)}}%
                                    </div>
                                </li>
                                {{-- @if($keyresults->count() > $count++ )<hr id="krhr{{$keyresult->id}}" style="text-align:center;margin-left:0"> @endif --}}
                                            </td>
                            </tr>
                            
                            
                    
                            @endforeach
                                        </tbody>
                                    </table>
                                </ul>
                            </td>
                            @endif
                            <td><div class="font-w600" style="font-size: 18px" id="frontobj{{$objective->id}}">{{round($objective->attainment * 100, 2)}}%</div></td>

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
                                    <div  class="col-lg-12" >
                                        <div class="panel panel-info">
                                            <div class="panel-heading">
                                                <h4 class="panel-title my-1"><b> My OKR</b></h4>
                                            </div>
                                            <div class="panel-body">
                                                <hr>
                                                @php
                                                    $objcount = 1;
                                                @endphp
                                                @foreach($goal as $m) <!--iterate through metrics -->
                                                    <div class="panel task-panel childClass">
                                                        <div class="panel-heading">
                                                            <div class="panel-title">
                                                                <div class="row">
                                                                    
                                                                    <a class="task col-md-8 h5 text-muted font-w400" data-toggle="collapse" data-parent="#accordion" href="#metric{{$m->id}}" show>
                                                                         <i class="fa fa-dot-circle-o mt-5 mr-5 text-success"></i> {{$m->objective_name}}
                                                                        <span  class="pull-right"><i class="si si-arrow-down text-primary"></i></span>
                                                                    </a>
                                                                    <?php
                                                                        $cal = round($m->attainment * 100, 2);
                                                                        $message = $cal <=30 ? "danger":($cal <= 75 ? "warning": "success");
                                                                   ?>
                                                                    <div class="col-md-3 mt-5">
                                                                        <div class="progress push" style="height: 8px;">
                                                                            <div class="progress-bar progress-bar-striped bg-{{$message}}" role="progressbar" style="width: {{$cal}}%;" aria-valuenow="30" aria-valuemin="0" aria-valuemax="100"></div>
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
                                                                                            <p class="text-muted font-w300"><i class="si si-graph mt-5 mr-5 text-info"></i> {{$task->keyresult_name}}</p>
                                                                                        </div>
                                                                                         <?php
                                                                                            $cal = round($task->attainment * 100, 2);
                                                                                            $message = $cal <=30 ? "danger":($cal <= 75 ? "warning": "success");
                                                                                        ?>
                                                                                        <div class="col-md-3 mt-5">
                                                                                            <div class="progress push" style="height: 8px;">
                                                                                                <div class="progress-bar progress-bar-striped progress-bar-animated bg-{{$message}}" role="progressbar" style="width: {{$cal}}%;" aria-valuenow="30" aria-valuemin="0" aria-valuemax="100"></div>
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
        {{-- alignment tab --}}
        <!--<div class="col-lg-12 tab-pane fade" id="align" role="tabpanel" aria-labelledby="align-tab">-->

        <!--    <div id="tree-container"></div>-->
        <!--</div>-->
    </div>
</div>
<script>
 $(document).ready(function() {
    $('#session').DataTable( {
        "ordering": false,
        "info":     false,
         'pageLength': 10
    } );
     $('#session').show()
     

$('.name').nameBadge({border: {
        width: 0
    },
    colors: ['#1abc9c', '#2ecc71', '#e74c3c', '#34495e', '#f1c40f'],
    text: '#fff',
    margin: 15,
    size: 120});
});
} );

</script>
@include('includes.objective-kr-modals')
@endsection


@extends('layouts.backend')
@section('content')
<link rel='stylesheet' href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/css/bootstrap-select.css" />
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

<div class="bg-info">
    <div class="bg-pattern bg-black-op-25 py-30" style="background-image: url('images/bg-pattern.png');">
        <div class="content content-full text-center">

            <h2 class="font-w700 text-white mb-10"> {{App\Session::find($id)->session_name}} Quarterly Incentives </h2>
        </div>
    </div>
</div>
<div class="mx-10">
    @if(Gate::any(['crud', 'incentive']))
    <a type="submit" class="btn btn-rounded btn-outline-info float-right" href="{{route('exportincentive', $gi->id)}}">Export Data</a>
    @endif
    <hr>
    <div class="d-flex  justify-content-center align-items-baseline" style="gap:10px">
        <h3 class="font-w300 text-center mb-10">Operation Incentive</h3>
        <a type="button" data-toggle="modal" data-target="#formula" title="View Detail">
            <i class=" fa fa-info text-dark"></i></a>
    </div>
    @include('includes.incentiveParticipantSourcing-modal')
    <div class="row">
        <div class="col-md-4">
            <!-- 400 -->
            <a class="block block-link-shadow">
                <div class="block-content text-center">
                    <div class="py-20">
                        <p class="h1 text-warning font-w600 mb-10">{{count($allProjects)}}</p>
                        <p class="font-size-lg">Projects</p>
                        <p>
                        </p>
                    </div>
                </div>
            </a>
            <!-- END 400 -->
        </div>
        <div class="col-md-4">
            <!-- 401 -->
            @php
            $bonussum = 0;
            @endphp


            <a class="block block-link-shadow">
                <div class="block-content text-center">
                    <div class="py-20">
                        <p class="h1 text-warning font-w600 mb-10">{{count($participants)}}</p>
                        <p class="font-size-lg">Participants</p>
                        <p>
                        </p>
                    </div>
                </div>
            </a>
            <!-- END 401 -->
        </div>
        <div class="col-md-4">
            <!-- 403 -->
            <a class="block block-link-shadow">
                <div class="block-content text-center">
                    <div class="py-20">
                        <p class="h1 text-corporate font-w600 mb-10">
                            @php
                            $totalbonus = 0;
                            @endphp
                            @foreach($bonusamount as $bonus)
                            @php
                            $totalbonus=$totalbonus + $bonus->bonus;
                            @endphp
                            @endforeach
                            {{$totalbonus}}
                        </p>
                        <p class="font-size-lg">Incentive Amount</p>
                        <p>
                        </p>
                    </div>
                </div>
            </a>
            <!-- END 403 -->
        </div>


    </div>

    <div id="accordion">
        @foreach($allProjects as $oi)
        <div class="card">

            <div class="card-header" id="heading{{$oi->project_id}}">
                <h5 class="mb-0">
                    <button class="btn btn-link" data-toggle="collapse" data-target="#collapseOne{{$oi->project_id}}" aria-expanded="true" aria-controls="collapseOne{{$oi->project_id}}">
                        {{$oi->project_name}}
                    </button>
                </h5>
            </div>


            <div id="collapseOne{{$oi->project_id}}" class="collapse" aria-labelledby="heading{{$oi->project_id}}" data-parent="#accordion">
                <div class="block">
                    @php
                    $tasks = App\OperationIncentive::where('project_id', $oi->project_id)->get();
                    $milestonepaymenttasks = App\OperationIncentive::where('project_id', $oi->project_id)->where('task_id', $oi->task_id)->where('custom_schedule_tracker', 'Finance End')->get();
                    @endphp
                    @foreach($tasks as $tl)
                    @if($tl->custom_schedule_tracker == 'Forex End')
                    <div class="container">

                        <table class="table table-vcenter" style="font-size:10px;">
                            <h6 class="text-dark mt-20">PFO</h6>

                            <tbody>
                                <ul style="list-style:none">
                                    <th class="d-sm-table-cell" style="width: 25%;">Name</th>
                                    <th class="d-sm-table-cell" style="width: 15%;">Milestone Amount</th>
                                    <th class="d-sm-table-cell" style="width: 5%;">%Comp</th>
                                    <th class="d-sm-table-cell" style="width: 5%;">SAC</th>
                                    <th class="d-sm-table-cell" style="width: 5%;">AT</th>
                                    <th class="d-sm-table-cell" style="width: 5%;">ES</th>
                                    <th class="d-sm-table-cell" style="width: 5%;">SPI</th>
                                    <th class="d-sm-table-cell" style="width: 5%;">Bonus</th>
                                    <th class="d-sm-table-cell" style="width: 15%;">Employee Name</th>
                                    <th class="d-sm-table-cell" style="width: 20%;">Action</th>




                                    <tr class="{{$oi->bonus != 0? 'table-success':'' }}">
                                        <td>{{$tl->custom_milestone_name}}</td>
                                        <td>{{$tl->task_amount}}</td>
                                        <td>{{$tl->percent_completion}}%</td>
                                        <td>{{$tl->SAC}}</td>
                                        <td>{{$tl->actual_time}}</td>
                                        <td>{{$tl->earned_schedule}}</td>
                                        <td>{{$tl->SPI}}</td>
                                        <td>{{$tl->bonus}}</td>

                                        <td>@foreach($tl->userss as $user)
                                            {{$user->fullname}}<br />
                                            @endforeach
                                        </td>

                                        <td>
                                            <div style="display:flex; gap: 20px; align-items: center; ">
                                                <a type="button" data-toggle="modal" data-target="#forex{{$tl->task_id}}" title="Add Participants"><i class="fa fa-plus fa-lg"></i></a>

                                                <a type="button" data-toggle="modal" data-target="#viewdetail{{$tl->task_id}}" title="View Detail">
                                                    <i class=" fa fa-eye fa-lg text-dark"></i></a>

                                            </div>
                                        </td>

                                    </tr>
                                    @include('includes.incentiveParticipantFinance-modal')



                                </ul>


                            </tbody>
                        </table>

                    </div>
                    @endif
                    @endforeach

                    @php
                    $tasks = App\OperationIncentive::where('project_id', $oi->project_id)->get();
                    $milestonepaymenttasks = App\OperationIncentive::where('project_id', $oi->project_id)->where('task_id', $oi->task_id)->where('custom_schedule_tracker', 'Sourcing End')->get();
                    @endphp
                    @foreach($tasks as $tl)
                    @if($tl->custom_schedule_tracker == 'Souring End')
                    <div class="container">

                        <table class="table table-vcenter" style="font-size:10px;">
                            <h6 class="text-dark mt-20">SE</h6>
                            <tbody>
                                <ul style="list-style:none">
                                    <th class="d-sm-table-cell" style="width: 25%;">Name</th>
                                    <th class="d-sm-table-cell" style="width: 15%;">Milestone Amount</th>
                                    <th class="d-sm-table-cell" style="width: 5%;">%Comp</th>
                                    <th class="d-sm-table-cell" style="width: 5%;">SAC</th>
                                    <th class="d-sm-table-cell" style="width: 5%;">AT</th>
                                    <th class="d-sm-table-cell" style="width: 5%;">ES</th>
                                    <th class="d-sm-table-cell" style="width: 5%;">SPI</th>
                                    <th class="d-sm-table-cell" style="width: 5%;">Bonus</th>
                                    <th class="d-sm-table-cell" style="width: 15%;">Employee Name</th>
                                    <th class="d-sm-table-cell" style="width: 20%;">Action</th>



                                    <tr class="{{$oi->bonus != 0? 'table-success':'' }}">
                                        <td>{{$tl->custom_milestone_name}}</td>
                                        <td>{{$tl->task_amount}}</td>
                                        <td>{{$tl->percent_completion}}%</td>
                                        <td>{{$tl->SAC}}</td>
                                        <td>{{$tl->actual_time}}</td>
                                        <td>{{$tl->earned_schedule}}</td>
                                        <td>{{$tl->SPI}}</td>
                                        <td>{{$tl->bonus}}</td>

                                        <td>@foreach($tl->userss as $user)
                                            {{$user->fullname}}<br />
                                            @endforeach
                                        </td>

                                        <td>
                                            <div style="display:flex; gap: 20px; align-items: center; ">
                                                <a type="button" data-toggle="modal" data-target="#forex{{$tl->task_id}}" title="Add Participants"><i class="fa fa-plus fa-lg"></i></a>

                                                <a type="button" data-toggle="modal" data-target="#viewdetail{{$tl->task_id}}" title="View Detail">
                                                    <i class=" fa fa-eye fa-lg text-dark"></i></a>

                                            </div>
                                        </td>
                                    </tr>
                                    @include('includes.incentiveParticipantFinance-modal')



                                </ul>


                            </tbody>
                        </table>
                    </div>
                    @endif
                    @endforeach

                    @php
                    $tasks = App\OperationIncentive::where('project_id', $oi->project_id)->get();
                    @endphp
                    @foreach($tasks as $tl)
                    @if($tl->custom_schedule_tracker == 'Logistics End')

                    <div class="container">
                        <table class="table table-vcenter" style="font-size:10px;">
                            <h6 class="text-dark mt-20">LO</h6>
                            <tbody>
                                <ul style="list-style:none">
                                    <th class="d-sm-table-cell" style="width: 25%;">Name</th>
                                    <th class="d-sm-table-cell" style="width: 15%;">Milestone Amount</th>
                                    <th class="d-sm-table-cell" style="width: 5%;">%Comp</th>
                                    <th class="d-sm-table-cell" style="width: 5%;">SAC</th>
                                    <th class="d-sm-table-cell" style="width: 5%;">AT</th>
                                    <th class="d-sm-table-cell" style="width: 5%;">ES</th>
                                    <th class="d-sm-table-cell" style="width: 5%;">SPI</th>
                                    <th class="d-sm-table-cell" style="width: 5%;">Bonus</th>
                                    <th class="d-sm-table-cell" style="width: 15%;">Employee Name</th>
                                    <th class="d-sm-table-cell" style="width: 20%;">Action</th>



                                    <tr class="{{$oi->bonus != 0? 'table-success':'' }}">
                                        <td>{{$tl->custom_milestone_name}}</td>
                                        <td>{{$tl->task_amount}}</td>
                                        <td>{{$tl->percent_completion}}%</td>
                                        <td>{{$tl->SAC}}</td>
                                        <td>{{$tl->actual_time}}</td>
                                        <td>{{$tl->earned_schedule}}</td>
                                        <td>{{$tl->SPI}}</td>
                                        <td>{{$tl->bonus}}</td>

                                        <td>@foreach($tl->userss as $user)
                                            {{$user->fullname}}<br />
                                            @endforeach
                                        </td>

                                        <td>
                                            <div style="display:flex; gap: 20px; align-items: center; ">
                                                <a type="button" data-toggle="modal" data-target="#forex{{$tl->task_id}}" title="Add Participants"><i class="fa fa-plus fa-lg"></i></a>

                                                <a type="button" data-toggle="modal" data-target="#viewdetail{{$tl->task_id}}" title="View Detail">
                                                    <i class=" fa fa-eye fa-lg text-dark"></i></a>

                                            </div>
                                        </td>
                                    </tr>



                                </ul>


                            </tbody>
                        </table>
                        @include('includes.incentiveParticipantFinance-modal')
                    </div>


                    @endif
                    @endforeach

                    @php
                    $tasks = App\OperationIncentive::where('project_id', $oi->project_id)->get();
                    @endphp
                    @foreach($tasks as $tl)
                    @if($tl->custom_schedule_tracker == 'Implementation End')
                    <div class="container">

                        <table class="table table-vcenter" style="font-size:10px;">
                            <h6 class="text-dark mt-20">TL and FE</h6>
                            <tbody>
                                <ul style="list-style:none">
                                    <th class="d-sm-table-cell" style="width: 25%;">Name</th>
                                    <th class="d-sm-table-cell" style="width: 15%;">Milestone Amount</th>
                                    <th class="d-sm-table-cell" style="width: 5%;">%Comp</th>
                                    <th class="d-sm-table-cell" style="width: 5%;">SAC</th>
                                    <th class="d-sm-table-cell" style="width: 5%;">AT</th>
                                    <th class="d-sm-table-cell" style="width: 5%;">ES</th>
                                    <th class="d-sm-table-cell" style="width: 5%;">SPI</th>
                                    <th class="d-sm-table-cell" style="width: 5%;">Bonus</th>
                                    <th class="d-sm-table-cell" style="width: 15%;">Employee Name</th>
                                    <th class="d-sm-table-cell" style="width: 20%;">Action</th>



                                    <tr class="{{$oi->bonus != 0? 'table-success':'' }}">
                                        <td>{{$tl->custom_milestone_name}}</td>
                                        <td>{{$tl->task_amount}}</td>
                                        <td>{{$tl->percent_completion}}%</td>
                                        <td>{{$tl->SAC}}</td>
                                        <td>{{$tl->actual_time}}</td>
                                        <td>{{$tl->earned_schedule}}</td>
                                        <td>{{$tl->SPI}}</td>
                                        <td>{{$tl->bonus}}</td>

                                        <td>@foreach($tl->userss as $user)
                                            {{$user->fullname}}<br />
                                            @endforeach
                                        </td>

                                        <td>
                                            <div style="display:flex; gap: 20px; align-items: center; ">
                                                <a type="button" data-toggle="modal" data-target="#forex{{$tl->task_id}}" title="Add Participants"><i class="fa fa-plus fa-lg"></i></a>

                                                <a type="button" data-toggle="modal" data-target="#viewdetail{{$tl->task_id}}" title="View Detail">
                                                    <i class=" fa fa-eye fa-lg text-dark"></i></a>

                                            </div>
                                        </td>
                                    </tr>




                                </ul>


                            </tbody>
                            @include('includes.incentiveParticipantFinance-modal')
                        </table>


                    </div>
                    @endif
                    @endforeach

                    @php
                    $tasks = App\OperationIncentive::where('project_id', $oi->project_id)->get();
                    @endphp
                    @foreach($tasks as $tl)
                    @if($tl->custom_schedule_tracker == 'Project End')
                    <div class="container">
                        <table class="table table-vcenter" style="font-size:10px;">
                            <h6 class="text-dark mt-20">PM</h6>
                            <tbody>
                                <ul style="list-style:none">
                                    <th class="d-sm-table-cell" style="width: 25%;">Name</th>
                                    <th class="d-sm-table-cell" style="width: 15%;">Milestone Amount</th>
                                    <th class="d-sm-table-cell" style="width: 5%;">%Comp</th>
                                    <th class="d-sm-table-cell" style="width: 5%;">SAC</th>
                                    <th class="d-sm-table-cell" style="width: 5%;">AT</th>
                                    <th class="d-sm-table-cell" style="width: 5%;">ES</th>
                                    <th class="d-sm-table-cell" style="width: 5%;">SPI</th>
                                    <th class="d-sm-table-cell" style="width: 5%;">Bonus</th>
                                    <th class="d-sm-table-cell" style="width: 15%;">Employee Name</th>
                                    <th class="d-sm-table-cell" style="width: 20%;">Action</th>


                                    <tr class="{{$oi->bonus != 0? 'table-success':'' }}">
                                        <td>{{$tl->custom_milestone_name}}</td>
                                        <td>{{$tl->task_amount}}</td>
                                        <td>{{$tl->percent_completion}}%</td>
                                        <td>{{$tl->SAC}}</td>
                                        <td>{{$tl->actual_time}}</td>
                                        <td>{{$tl->earned_schedule}}</td>
                                        <td>{{$tl->SPI}}</td>
                                        <td>{{$tl->bonus}}</td>

                                        <td>@foreach($tl->userss as $user)
                                            {{$user->fullname}}<br />
                                            @endforeach
                                        </td>

                                        <td>
                                            <div style="display:flex; gap: 20px; align-items: center; ">
                                                <a type="button" data-toggle="modal" data-target="#forex{{$tl->task_id}}" title="Add Participants"><i class="fa fa-plus fa-lg"></i></a>

                                                <a type="button" data-toggle="modal" data-target="#viewdetail{{$tl->task_id}}" title="View Detail">
                                                    <i class=" fa fa-eye fa-lg text-dark"></i></a>

                                            </div>
                                        </td>
                                    </tr>
                                    @include('includes.incentiveParticipantFinance-modal')
                                </ul>


                            </tbody>
                        </table>
                    </div>
                    @endif
                    @endforeach
                </div>
            </div>
        </div>

        @endforeach








        <div class="block">
            <div class="block-header block-header-default">
                <h3 class="block-title">Multidimensional Performace Evaluation - Project</h3>
                <div class="block-options">
                    @if(Gate::any(['crud']))
                    <button type="button" class="btn-block-option text-primary" data-toggle="tooltip" title="" data-original-title="Edit" onclick="editCriteria(0, {{$gi->id}})" disabled>
                        <i class="si si-pencil"></i>
                    </button>
                    @endif
                </div>
            </div>
            <div class="block-content">

                <table class="table table-vcenter" style="font-size:10px;">
                    <thead>
                        <tr>
                            <th class="text-center" style="width: 50px;">#</th>
                            <th>Project title</th>
                            <th> Project amount</th>

                        </tr>
                    </thead>
                    <tbody>
                        @php
                        $count = 1;
                        $projectreports = App\Detailedreport::where('incentive_id', $gi->id)->where('type', 0)->select('project_id')->distinct()->get();
                        function convert($num){
                        $result = round($num/30, 2);

                        if($result >=12){
                        $result = round($num/365, 2) . " month";
                        }
                        else if($result < 0.25){ $result=$num . " day" ; } else{ $result .=" month" ; } return $result; } @endphp @foreach($projectreports as $p) @php $project=App\Project::find($p->project_id);

                            $projectmembers = App\Detailedreport::where('detailedreports.project_id', $p->project_id)->where('incentive_id', $gi->id)->get();
                            $pmone = App\Detailedreport::where('detailedreports.project_id', $p->project_id)->where('incentive_id', $gi->id)->first();
                            @endphp

                            <tr>
                                <th class="text-center" scope="row">{{$count++}}</th>
                                <td>{{$project->project_name}}</td>
                                <td>{{number_format($project->amount)}}</td>

                                <td>
                                    <ul style="list-style:none">
                                        <table>

                                            <th>Employee name</th>
                                            <th>Participation level</th>
                                            <th>Title</th>
                                            <th class="d-sm-table-cell" style="width: 5%;">Revenue</th>
                                            <th class="d-sm-table-cell" style="width: 5%;">Cash Collection</th>
                                            <th class="d-sm-table-cell" style="width: 5%;">EBTIDA</th>
                                            <th class="d-sm-table-cell" style="width: 5%;">Awards</th>

                                            <th class="d-sm-table-cell" style="width: 5%;">Weekly Score</th>
                                            <th class="d-sm-table-cell" style="width: 5%;">Project KPI Score</th>
                                            <th class="d-sm-table-cell" style="width: 5%;">Engagement Score</th>
                                            <th class="d-sm-table-cell" style="width: 5%;">Average Score</th>
                                            <th class="d-sm-table-cell" style="width: 5%;">Performance Level</th>
                                            <th class="d-sm-table-cell" style="width: 5%;">Bonus</th>

                                            @foreach($projectmembers as $pms)
                                            <tr class="{{$pms->bonus != 0? 'table-success':'' }}">
                                                @php
                                                $user = App\User::where('active', 1)->where('user_id', $pms->user_id)->where('scores.session_id', $gi->session_id)->join('scores', 'scores.user_id', 'users.id')->first();



                                                @endphp
                                                <td>{{$user?$user->fname . " " . $user->lname:""}}</td>
                                                <td>{{$pms->level}}</td>


                                                <td>{{$user && App\Projectmember::where('project_id', $project->id)->where('user_id', $user->user_id)->first()? App\Projectmember::where('project_id', $project->id)->where('user_id', $user->user_id)->first()->position: "NA"}}</td>

                                                <td>{{round($company->revenue_actual/max($company->revenue_target, 1) * 100, 2)}}%</td>

                                                <td>{{round($company->cash_actual/max($company->cash_target, 1) * 100, 2)}}%</td>

                                                <td>{{round($company->ebitda_actual/max($company->revenue_actual, 1) * 100, 2)}}%</td>

                                                <td>{{round($company->award_actual/max($company->award_target,1) * 100, 2)}}%</td>

                                                <td>{{$pms->weeklyscore}}%</td>

                                                <td>{{$pms->kpiscore}}%</td>

                                                <td>{{$pms->engscore}}%</td>
                                                <td>{{$user?round(($pms->weeklyscore +$pms->kpiscore + $pms->engscore)/3, 2) :0}}%</td>


                                                <td>{{$pms->score}}</td>

                                                <td>{{$pms->bonus!= 0? convert($pms->bonus): "No bonus"}}</td>
                                            </tr>
                                            @endforeach
                                        </table>
                                </td>
                            </tr>
                            @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        <hr>

        <h3 class="font-w300 text-center mb-10">Deal Closure Recognition Program </h3>
        <hr>
        <div class="block">
            <div class="block-header block-header-default">
                <h3 class="block-title">Multidimensional Performace Evaluation - Deal Closure</h3>
                <div class="block-options">
                    @if(Gate::any(['crud']))
                    <button type="button" class="btn-block-option text-primary" data-toggle="tooltip" title="" data-original-title="Edit" onclick="editCriteria(1, {{$gi->id}})" disabled>
                        <i class="si si-pencil"></i>
                    </button>
                    @endif
                </div>
            </div>
            <div class="block-content">
                <table class="table table-vcenter" style="font-size:10px;">
                    <thead>
                        <tr>
                            <th class="text-center" style="width: 50px;">#</th>
                            <th> Bid Title</th>
                            <th> Signed contract size</th>


                        </tr>
                    </thead>
                    <tbody>


                        @php
                        $count = 1;
                        $bids = App\Detailedreport::where('incentive_id', $gi->id)->where('type', 1)->select('bid_id')->distinct()->get();

                        @endphp

                        @foreach($bids as $p)
                        @php
                        $bid = App\Bid::find($p->bid_id);

                        $projectmembers = App\Detailedreport::where('detailedreports.bid_id', $p->bid_id)->get();


                        @endphp

                        <tr>
                            <th class="text-center" scope="row">{{$count++}}</th>
                            <td>{{$bid->bid_name}}</td>
                            <td>{{number_format($bid->bid_amount)}}</td>
                            <td>
                                <table>

                                    <th>Employee name</th>
                                    <th>Participation level</th>
                                    <th>Title</th>
                                    <th class="d-sm-table-cell" style="width: 5%;">Revenue</th>
                                    <th class="d-sm-table-cell" style="width: 5%;">Cash Collection</th>
                                    <th class="d-sm-table-cell" style="width: 5%;">EBTIDA</th>
                                    <th class="d-sm-table-cell" style="width: 5%;">Awards</th>

                                    <th class="d-sm-table-cell" style="width: 5%;">Weekly Score</th>
                                    <th class="d-sm-table-cell" style="width: 5%;">Sales KPI Score</th>
                                    <th class="d-sm-table-cell" style="width: 5%;">Engagement Score</th>
                                    <th class="d-sm-table-cell" style="width: 5%;">Average Score</th>
                                    <th class="d-sm-table-cell" style="width: 5%;">Performance Level</th>
                                    <th class="d-sm-table-cell" style="width: 5%;">Bonus</th>

                                    @foreach($projectmembers as $pms)
                                    <tr class="{{$pms->bonus != 0? 'table-success':'' }}">
                                        @php
                                        $user = App\User::where('user_id', $pms->user_id)->where('scores.session_id', $gi->session_id)->join('scores', 'scores.user_id', 'users.id')->first();


                                        @endphp
                                        <td>{{$user?$user->fname . " " . $user->lname:""}}</td>
                                        <td>{{$pms->level}}</td>

                                        <td>{{App\Bidmember::where('bid_id', $p->bid_id)->wherein('position', ['AE','SA', 'PSE'])->where('user_id', $user->user_id)->first()? App\Bidmember::where('bid_id', $p->bid_id)->wherein('position', ['AE','SA', 'PSE'])->where('user_id', $user->user_id)->first()->position: "NA"}}</td>

                                        <td>{{round($company->revenue_actual/max($company->revenue_target, 1) * 100, 2)}}%</td>

                                        <td>{{round($company->cash_actual/max($company->cash_target, 1) * 100, 2)}}%</td>

                                        <td>{{round($company->ebitda_actual/max($company->revenue_actual, 1) * 100, 2)}}%</td>

                                        <td>{{round($company->award_actual/max($company->award_target,1) * 100, 2)}}%</td>

                                        <td>{{$pms->weeklyscore}}%</td>

                                        <td>{{$pms->kpiscore}}%</td>

                                        <td>{{$pms->engscore}}%</td>
                                        <td>{{$user?round(($pms->weeklyscore +$pms->kpiscore + $pms->engscore)/3, 2) :0}}%</td>

                                        <td>{{$pms->score}}</td>

                                        <td>{{$pms->bonus!= 0? convert($pms->bonus): "No bonus"}}</td>
                                    </tr>
                                    @endforeach
                                </table>
                            </td>
                        </tr>
                        @endforeach

                    </tbody>
                </table>
            </div>
        </div>
        <hr>

        <div class="block">
            <div class="block-header block-header-default">
                <h3 class="block-title">Multidimensional Performace Evaluation - Order and Delivery</h3>
                <div class="block-options">
                    @if(Gate::any(['crud']))
                    <button type="button" class="btn-block-option text-primary" data-toggle="tooltip" title="" data-original-title="Edit" onclick="editCriteria(2, {{$gi->id}})" disabled>
                        <i class="si si-pencil"></i>
                    </button>
                    @endif
                </div>
            </div>
            <div class="block-content">
                <table class="table table-vcenter" style="font-size:10px;">
                    <thead>
                        <tr>
                            <th class="text-center" style="width: 50px;">#</th>
                            <th> Delivery Milestone</th>
                            <th> Delivery milestone size </th>


                        </tr>
                    </thead>
                    <tbody>


                        @php
                        $count = 1;
                        $deliveries = App\Detailedreport::where('incentive_id', $gi->id)->where('type', 2)->select('projectdelivery_id')->distinct()->get();

                        @endphp

                        @foreach($deliveries as $p)
                        @php
                        $delivery = App\Projectdelivery::find($p->projectdelivery_id);
                        $project = App\Project::find($delivery->project_id);
                        $projectmembers =App\Detailedreport::where('detailedreports.projectdelivery_id', $p->projectdelivery_id)->get();

                        @endphp

                        <tr>
                            <th class="text-center" scope="row">{{$count++}}</th>
                            <td>{{$project->project_name . " (" . $delivery->milestone_name . ")"}}</td>
                            <td>{{number_format($delivery->amount)}}</td>
                            <td>
                                <table>

                                    <th>Employee name</th>
                                    <th>Participation level</th>
                                    <th>Title</th>
                                    <th class="d-sm-table-cell" style="width: 5%;">Revenue</th>
                                    <th class="d-sm-table-cell" style="width: 5%;">Cash Collection</th>
                                    <th class="d-sm-table-cell" style="width: 5%;">EBTIDA</th>
                                    <th class="d-sm-table-cell" style="width: 5%;">Awards</th>

                                    <th class="d-sm-table-cell" style="width: 5%;">Weekly Score</th>
                                    <th class="d-sm-table-cell" style="width: 5%;">O&F KPI Score</th>
                                    <th class="d-sm-table-cell" style="width: 5%;">Engagement Score</th>
                                    <th class="d-sm-table-cell" style="width: 5%;">Average Score</th>
                                    <th class="d-sm-table-cell" style="width: 5%;">Performance Level</th>
                                    <th class="d-sm-table-cell" style="width: 5%;">Bonus</th>

                                    @foreach($projectmembers as $pms)
                                    <tr class="{{$pms->bonus != 0? 'table-success':'' }}">
                                        @php
                                        $user = App\User::where('active', 1)->where('user_id', $pms->user_id)->where('scores.session_id', $gi->session_id)->join('scores', 'scores.user_id', 'users.id')->first();

                                        @endphp
                                        <td>{{$user?$user->fname . " " . $user->lname:""}}</td>
                                        <td>{{$pms->level}}</td>

                                        <td>{{App\Projectmember::where('project_id', $project->id)->where('user_id', $user->user_id)->first()? App\Projectmember::where('project_id', $project->id)->where('user_id', $user->user_id)->first()->position: "NA"}}</td>

                                        <td>{{round($company->revenue_actual/max($company->revenue_target, 1) * 100, 2)}}%</td>

                                        <td>{{round($company->cash_actual/max($company->cash_target, 1) * 100, 2)}}%</td>

                                        <td>{{round($company->ebitda_actual/max($company->revenue_actual, 1) * 100, 2)}}%</td>

                                        <td>{{round($company->award_actual/max($company->award_target,1) * 100, 2)}}%</td>

                                        <td>{{$pms->weeklyscore}}%</td>

                                        <td>{{$pms->kpiscore}}%</td>

                                        <td>{{$pms->engscore}}%</td>
                                        <td>{{$user?round(($pms->weeklyscore +$pms->kpiscore + $pms->engscore)/3, 2) :0}}%</td>

                                        <td>{{$pms->score}}</td>

                                        <td>{{$pms->bonus!= 0? convert($pms->bonus): "No bonus"}}</td>
                                    </tr>
                                    @endforeach
                                </table>
                            </td>
                        </tr>
                        @endforeach

                    </tbody>
                </table>
            </div>
        </div>
        <h3 class="font-w300 text-center mb-10">Leadership Program</h3>
        <hr>
        <div class="block">
            <div class="block-header block-header-default">
                <h3 class="block-title">Multidimensional Performace Evaluation - Leadership</h3>
                <div class="block-options">
                    @if(Gate::any(['crud']))
                    <button type="button" class="btn-block-option text-primary" data-toggle="tooltip" title="" data-original-title="Edit" onclick="editCriteria(3, {{$gi->id}})" disabled>
                        <i class="si si-pencil"></i>
                    </button>
                    @endif
                </div>
            </div>
            <div class="block-content">
                <table class="table table-vcenter" style="font-size:10px;">
                    <thead>
                        <tr>
                            <th class="text-center" style="width: 50px;">#</th>
                            <th>Employee Name</th>
                            <th class="d-sm-table-cell" style="width: 5%;">Revenue</th>
                            <th class="d-sm-table-cell" style="width: 5%;">Cash Collection</th>
                            <th class="d-sm-table-cell" style="width: 5%;">EBTIDA</th>
                            <th class="d-sm-table-cell" style="width: 5%;">Awards</th>

                            <th class="d-sm-table-cell" style="width: 5%">Weekly Score</th>
                            <th class="d-sm-table-cell" style="width: 5%;">Leadership KPI Score</th>
                            <th class="d-sm-table-cell" style="width: 5%;">Engagement Score</th>
                            <th class="d-sm-table-cell" style="width: 5%;">Average Score</th>
                            <th class="d-sm-table-cell" style="width: 5%;">Performance Level</th>
                            <th class="d-sm-table-cell" style="width: 5%;">Bonus</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php

                        $users = App\Detailedreport::orderby('users.fname', 'asc')->where('incentive_id', $gi->id)->where('type', 3)->join('users', 'users.id', 'detailedreports.user_id')->get();
                        $count = 1;
                        @endphp
                        @foreach($users as $usser)
                        @php
                        $user = App\User::where('active', 1)->where('user_id', $usser->id)->where('scores.session_id', $gi->session_id)->join('scores', 'scores.user_id', 'users.id')->first();

                        @endphp

                        <tr class="{{$usser->bonus !=0? 'table-success':'' }}">
                            <th class="text-center" scope="row">{{$count++}}</th>
                            <td>{{$usser->fname." ".$usser->lname}}</td>
                            <td>{{round($company->revenue_actual/max($company->revenue_target, 1) * 100, 2)}}%</td>

                            <td>{{round($company->cash_actual/max($company->cash_target, 1) * 100, 2)}}%</td>

                            <td>{{round($company->ebitda_actual/max($company->revenue_actual, 1) * 100, 2)}}%</td>

                            <td>{{round($company->award_actual/max($company->award_target,1) * 100, 2)}}%</td>

                            <td>{{$usser->weeklyscore}}%</td>

                            <td>{{$usser->kpiscore}}%</td>

                            <td>{{$usser->engscore}}%</td>
                            <td>{{$user?round(($usser->weeklyscore +$usser->kpiscore + $usser->engscore)/3, 2) :0}}%</td>
                            <td>{{$usser->score}}</td>
                            <td>{{$usser->bonus!= 0? convert($usser->bonus): "No bonus"}}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @php
        $criterias = App\Detailedreport::where('incentive_id', $gi->id)->where('type', 4)->select('recognition_type')->distinct()->get();
        @endphp
        @if($criterias->count() > 0)
        <h3 class="font-w300 text-center mb-10">Special recognition </h3>
        <hr>
        <div class="block">
            <div class="block-header block-header-default">
                <h3 class="block-title">Performace Evaluation</h3>
                <div class="block-options">
                    @if(Gate::any(['crud']))
                    <button type="button" class="btn-block-option text-primary" data-toggle="tooltip" title="" data-original-title="Edit" onclick="editCriteria(3, {{$gi->id}})" disabled>
                        <i class="si si-pencil"></i>
                    </button>
                    @endif
                </div>
            </div>
            <div class="block-content">
                <table class="table table-vcenter" style="font-size:10px;">
                    <thead>
                        <tr>
                            <th class="text-center" style="width: 50px;">#</th>
                            <th>Recognition </th>


                        </tr>
                    </thead>
                    <tbody>
                        @php
                        $criterias = App\Detailedreport::where('incentive_id', $gi->id)->where('type', 4)->select('recognition_type')->distinct()->get();

                        $users = App\Detailedreport::where('incentive_id', $gi->id)->where('type', 4)->join('users', 'users.id', 'detailedreports.user_id')->get();
                        $count = 1;
                        @endphp
                        @foreach($criterias as $c)
                        @php
                        $users = App\Detailedreport::where('incentive_id', $gi->id)->where('type', 4)->where('recognition_type', $c->recognition_type)->join('users', 'users.id', 'detailedreports.user_id')->get();
                        @endphp


                        <tr class="">
                            <th class="text-center" scope="row">{{$count++}}</th>
                            <td>{{$c->recognition_type}}</td>
                            <td>
                                <table>

                                    <th class="d-sm-table-cell" style="width: 5%;">Employee name</th>
                                    <th class="d-sm-table-cell" style="width: 5%;">Criteria</th>
                                    <th class="d-sm-table-cell" style="width: 5%;">Score</th>

                                    <th class="d-sm-table-cell" style="width: 5%;">Bonus</th>
                                    @foreach($users as $user)


                                    <tr>
                                        <td>{{$user->fullname}}</td>
                                        <td>{{$user->formula}}</td>
                                        <td>{{$user->score}}</td>

                                        <td>{{convert($user->bonus)}}</td>
                                    </tr>


                                    @endforeach
                                </table>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @endif
        </div>
    </div>
    <div class="modal" id="editcriteria" tabindex="-1" role="dialog" aria-labelledby="modal-large" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <form method="post" action="{{route('editgincentive')}}">
                    @csrf
                    <input type="hidden" name="incentive_id" value="{{$gi->id}}">
                    <input type="hidden" name="type" value="0" id="itype">
                    <div class="block block-themed block-transparent mb-0">
                        <div class="block-header bg-primary-info">
                            <h3 class="block-title">Edit Performance Criteria</h3>
                            <div class="block-options">
                                <button type="button" class="btn-block-option" data-dismiss="modal" aria-label="Close">
                                    <i class="si si-close"></i>
                                </button>
                            </div>
                        </div>
                        <div class="block-content">
                            <ol>
                                <li id="R" class="row">
                                    <p class="col-md-9">Revenue</p>
                                    <div class="col-md-2"> <button id="rmybtn" type="button" class="btn btn-danger " onclick="confirm('rmybtn')" value="0">Include</button><input type="hidden" name="rmybtn" value="0" id="irmybtn"></div>
                                </li>
                                <li id="C" class="row">
                                    <p class="col-md-9">Cash collection</p>
                                    <div class="col-md-2"><button id="cmybtn" type="button" class="btn btn-danger " onclick="confirm('cmybtn')" value="0">Include</button><input type="hidden" name="cmybtn" value="0" id="icmybtn"></div>
                                </li>
                                <li id="Eb" class="row">
                                    <p class="col-md-9">EBITDA </p>
                                    <div class="col-md-2"><button id="ebmybtn" type="button" class="btn btn-danger " onclick="confirm('ebmybtn')" value="0">Include</button><input type="hidden" name="ebmybtn" value="0" id="iebmybtn"></div>
                                </li>
                                <li id="A" class="row">
                                    <p class="col-md-9">Award </p>
                                    <div class="col-md-2"><button id="amybtn" type="button" class="btn btn-danger " onclick="confirm('amybtn')" value="0">Include</button><input type="hidden" name="amybtn" value="0" id="iamybtn"></div>
                                </li>
                                <li id="W" class="row">
                                    <p class="col-md-9">Average Weekly Score </p>
                                    <div class="col-md-2"><button id="wmybtn" type="button" class="btn btn-danger " onclick="confirm('wmybtn')" value="0">Include</button><input type="hidden" name="wmybtn" value="0" id="iwmybtn"></div>
                                </li>
                                <li id="E" class="row">
                                    <p class="col-md-9">Engagement Score</p>
                                    <div class="col-md-2"><button id="emybtn" type="button" class="btn btn-danger " onclick="confirm('emybtn')" value="0">Include</button><input type="hidden" name="emybtn" value="0" id="iemybtn"></div>
                                </li>
                                <li id="K" class="row">
                                    <p class="col-md-9">KPI Score</p>
                                    <div class="col-md-2"><button id="kmybtn" type="button" class="btn btn-danger " onclick="confirm('kmybtn')" value="0">Include</button> <input type="hidden" name="kmybtn" value="0" id="ikmybtn"></div>
                                </li>

                            </ol>

                            <p id="formula"></p>


                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-alt-primary">Edit</button>
                        <button type="button" class="btn btn-alt-secondary" data-dismiss="modal">Close</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script>
        var baseurl = window.location.protocol + "//" + window.location.host + '/pms/';

        function change() {
            var t = document.getElementById('table');
            var row = t.getElementsByTagName("td")[0];
            row.className = 'hidden';
        }

        function editCriteria(type, incentiveid) {
            $.get(baseurl + "getformulas/" + incentiveid + "/formula/" + type, function(data) {
                var f = "";
                var formula = data.split(",");
                console.log(formula)
                document.getElementById('itype').value = type;
                document.getElementById('kmybtn').innerHTML = 'Include';
                document.getElementById('kmybtn').classList.remove("btn-success");
                document.getElementById('kmybtn').classList.add("btn-danger");
                document.getElementById('ikmybtn').value = "0";
                document.getElementById('rmybtn').innerHTML = 'Include';
                document.getElementById('rmybtn').classList.remove("btn-success");
                document.getElementById('rmybtn').classList.add("btn-danger");
                document.getElementById('irmybtn').value = "0";

                document.getElementById('amybtn').innerHTML = 'Include';
                document.getElementById('amybtn').classList.remove("btn-success");
                document.getElementById('amybtn').classList.add("btn-danger");
                document.getElementById('iamybtn').value = "0";

                document.getElementById('wmybtn').innerHTML = 'Include';
                document.getElementById('wmybtn').classList.remove("btn-success");
                document.getElementById('wmybtn').classList.add("btn-danger");
                document.getElementById('iwmybtn').value = "0";

                document.getElementById('ebmybtn').innerHTML = 'Include';
                document.getElementById('ebmybtn').classList.remove("btn-success");
                document.getElementById('ebmybtn').classList.add("btn-danger");
                document.getElementById('iebmybtn').value = "0";

                document.getElementById('cmybtn').innerHTML = 'Include';
                document.getElementById('cmybtn').classList.remove("btn-success");
                document.getElementById('cmybtn').classList.add("btn-danger");
                document.getElementById('iemybtn').value = "0";

                document.getElementById('emybtn').innerHTML = 'Include';
                document.getElementById('emybtn').classList.remove("btn-success");
                document.getElementById('emybtn').classList.add("btn-danger");
                document.getElementById('iemybtn').value = "0";

                for (var i = 0; i < formula.length; i++) {
                    if (formula[i] == "K") {
                        document.getElementById('kmybtn').innerHTML = 'Exclude';
                        document.getElementById('kmybtn').classList.add("btn-success");
                        document.getElementById('kmybtn').classList.remove("btn-danger");
                        document.getElementById('ikmybtn').value = "1";
                        f += "Kpi ^ "
                    }


                    if (formula[i] == 'R') {
                        document.getElementById('rmybtn').innerHTML = 'Exclude';
                        document.getElementById('rmybtn').classList.add("btn-success");
                        document.getElementById('rmybtn').classList.remove("btn-danger");
                        document.getElementById('irmybtn').value = "1";
                        f += "Revenue ^ "
                    }

                    if (formula[i] == "W") {
                        document.getElementById('wmybtn').innerHTML = 'Exclude';
                        document.getElementById('wmybtn').classList.add("btn-success");
                        document.getElementById('wmybtn').classList.remove("btn-danger");
                        document.getElementById('iwmybtn').value = "1";
                        f += "Weekly average ^ "
                    }

                    if (formula[i] == "A") {
                        document.getElementById('amybtn').innerHTML = 'Exclude';
                        document.getElementById('amybtn').classList.add("btn-success");
                        document.getElementById('amybtn').classList.remove("btn-danger");
                        document.getElementById('iamybtn').value = "1";
                        f += "Award && "
                    }

                    if (formula[i] == "E") {
                        document.getElementById('emybtn').innerHTML = 'Exclude';
                        document.getElementById('emybtn').classList.add("btn-success");
                        document.getElementById('emybtn').classList.remove("btn-danger");
                        document.getElementById('iemybtn').value = "1";
                        f += "Engagement score ^ "
                    }

                    if (formula[i] == 'Eb') {
                        document.getElementById('ebmybtn').innerHTML = 'Exclude';
                        document.getElementById('ebmybtn').classList.add("btn-success");
                        document.getElementById('ebmybtn').classList.remove("btn-danger");
                        document.getElementById('iebmybtn').value = "1";
                        f += "EBITDA ^ "
                    }

                    if (formula[i] == 'C') {
                        document.getElementById('cmybtn').innerHTML = 'Exclude';
                        document.getElementById('cmybtn').classList.add("btn-success");
                        document.getElementById('cmybtn').classList.remove("btn-danger");
                        document.getElementById('icmybtn').value = "1";
                        f += "Cash collection ^ "
                    }

                }
                f = f.substring(0, f.length - 3);

                jQuery('#editcriteria').modal("show");
            });

        }

        function confirm(id) {
            var btn = document.getElementById(id);
            if (btn.innerHTML == 'Include') {
                btn.innerHTML = 'Exclude';
                btn.classList.add("btn-success");
                btn.classList.remove("btn-danger");
                document.getElementById('i' + id).value = "1";
            } else if (btn.innerHTML == 'Exclude') {
                btn.innerHTML = 'Include';
                btn.classList.remove("btn-success");
                btn.classList.add("btn-danger");
                document.getElementById('i' + id).value = "0";
            }


        }
    </script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/js/bootstrap-select.min.js"></script>

    @endsection
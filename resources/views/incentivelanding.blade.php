@extends('layouts.backend')
@section('content')
<div class="bg-info">
    <div class="bg-pattern bg-black-op-25 py-30" style="background-image: url('images/bg-pattern.png');">
        <div class="content content-full text-center">

            <h1 class="h3 text-white font-w700 mb-10">
                Incentives
            </h1>
        </div>
    </div>
</div>
<div class="d-flex float-right">
    <a type="submit" class="btn btn-rounded btn-outline-info justify-content-between float-right mt-3" href="{{route('incentivesetting')}}"><i class="fa fa-cog"></i> Settings</a>
    <button type="submit" class="btn btn-rounded btn-info float-right mt-3" data-toggle="modal" data-target="#exampleModalCenter"><i class="fa fa-cog"></i> Refresh</button>
</div>
<div class="content">
    @if(Gate::any(['crud']))
    <h2 class="content-heading">
        Generate new incentive report
    </h2>
    <div class="block block-rounded">
        <div class="block-content bg-gd-sea">
            <form action="{{route('generateincentive')}}" method="post">
                @csrf
                <div class="form-group row">
                    <label class="col-12 text-white" for="example-select">Session</label>
                    <div class="col-md-5">
                        <select class="form-control" id="session-select" name="session_id" required>
                            <option value="0" disabled>Please select Session</option>
                            @php
                            $sessions = App\Session::where('isNeeded', 1)->get();


                            @endphp
                            <option selected disabled value="">Select session</option>
                            @foreach($sessions as $session)
                            @php
                            $report = App\Generatedincentive::where('session_id', $session->id)->first();
                            @endphp
                            @if( !$report)
                            <option value="{{$session->id}}">{{$session->session_name}}</option>
                            @endif
                            @endforeach

                        </select>

                    </div>

                    <button type="submit" class="btn btn-rounded btn-outline btn-alt-info ml-5" {{Auth::user()->id == 86 || Auth::user()->id == 170 ||Auth::user()->id == 492  ? '': 'disabled'}}>
                        <i class="si si-rocket"></i> Generate
                    </button>
                </div>
            </form>
        </div>
    </div>
    @endif

    <h2 class="content-heading">
        Generated incentive reports
    </h2>
    <div class="row">
        @php
        $reports = App\Generatedincentive::all();
        @endphp
        @foreach($reports as $report)
        @php
        $pfreports = App\Detailedreport::where('incentive_id', $report->id)->where('type', 0)->where('level', 'Full cycle Participant')->where('bonus', '!=', 0)->get();
        $phreports = App\Detailedreport::where('incentive_id', $report->id)->where('type', 0)->where('level', 'Half cycle Participant')->where('bonus', '!=', 0)->get();

        $bfreports = App\Detailedreport::where('incentive_id', $report->id)->where('type', 1)->where('level', 'Full cycle Participant')->where('bonus', '!=', 0)->get();
        $bhreports = App\Detailedreport::where('incentive_id', $report->id)->where('type', 1)->where('level', 'Half cycle Participant')->where('bonus', '!=', 0)->get();

        $ofreports = App\Detailedreport::where('incentive_id', $report->id)->where('type', 2)->where('level', 'Full cycle Participant')->where('bonus', '!=', 0)->get();
        $ohreports = App\Detailedreport::where('incentive_id', $report->id)->where('type', 2)->where('level', 'Half cycle Participant')->where('bonus', '!=', 0)->get();

        $lreports = App\Detailedreport::where('incentive_id', $report->id)->where('type', 3)->where('bonus', '!=', 0)->get();
        $oireports = App\OperationIncentive::where('bonus', '!=', 0)->get();
        @endphp
        <div class="col-md-6">
            <div class="block block-themed block-link-shadow">
                <div class="block-header">
                    <h3 class="block-title">{{App\Session::find($report->session_id)->session_name}}</h3>
                    <div class="block-options row gap-1">
                        <a type="button" class="btn btn-small btn-rounded btn-outline btn-alt-info" href="{{route('openincentivereport', $report->session_id)}}">
                            Open Report
                        </a>
                        @if(Gate::any(['crud']))
                        <form action="{{route('deleteincentivereport', $report->id)}}" method="post">
                            @csrf
                            <input name='deletesession' value="{{App\Session::find($report->session_id)->id}}" hidden>
                            <button type="submit" class="btn btn-small btn-rounded btn-outline btn-alt-danger {{Auth::user()->id == 86 || Auth::user()->id == 170 || Auth::user()->id == 492  ? '': 'ancdisabled'}}">
                                Delete Report
                            </button>
                        </form>
                        @endif
                    </div>
                </div>
                <div class="block-content block-content-full">
                    <table class="table table-striped table-vcenter">
                        <thead>
                            <tr>
                                <th class="text-center" style="width: 50px;">#</th>
                                <th>Incentives</th>
                                <th>Total Incentives With Full Cycle</th>
                                <th>Total Incentives With Half Cycle</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                @php
                                $count='1';
                                @endphp
                                @if($report->session_id >= 24)
                                <th class="text-center" scope="row">{{$count++}}</th>
                                <td>Operation Incentive</td>
                                <td>{{$oireports->count()}}</td>
                                <td>-</td>
                                @else
                                <th class="text-center" scope="row">{{$count++}}</th>
                                <td>Project Incentives</td>
                                <td>{{$pfreports->count()}}</td>
                                <td>{{$phreports->count()}}</td>
                            </tr>

                            <tr>
                                <th class="text-center" scope="row">{{$count++}}</th>
                                <td>Order and Delivery Incentives</td>
                                <td>{{$ofreports->count()}}</td>
                                <td>{{$ohreports->count()}}</td>
                            </tr>
                            @endif
                            <tr>
                                <th class="text-center" scope="row">{{$count++}}</th>
                                <td>Deal Closure Incentives</td>
                                <td>{{$bfreports->count()}}</td>
                                <td>{{$bhreports->count()}}</td>

                            </tr>
                            <tr>
                                <th class="text-center" scope="row">{{$count++}}</th>
                                <td>Leadership Incentive</td>
                                <td>{{$lreports->count()}}</td>
                                <td>-</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>
<div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content" style="border-radius:20px !important;">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Select Quarter</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="">
                    <form action="{{route('store')}}" method='post'>
                        @csrf
                        <select class=" form-control mb-5" id="session-select" name="session_id" required>
                            <option value="0" disabled>Please select Session</option>
                            @php
                            $sessions = App\Session::where('isNeeded', 1)->get();


                            @endphp
                            <option selected disabled value="">Select session</option>
                            @foreach($sessions as $session)
                            @php
                            $report = App\Generatedincentive::where('session_id', $session->id)->first();
                            @endphp
                            @if( !$report)
                            <option value="{{$session->id}}">{{$session->session_name}}</option>
                            @endif
                            @endforeach

                        </select>
                </div>
                <div class="row text-center mb-10 mt-10">
                    <div class="col-12 col-md-7">
                        <p> Minimum SPI</p>
                    </div>
                    <div class="col-12 col-md-5 d-flex">

                        <div class="input-group">

                            <input type="number" name="minimum_spi" id="numberInput" class="form-control number" step='0.1' value="0.1" min="0.1" max="1" require>

                        </div>
                    </div>

                </div>
                <div class="row text-center">
                    <div class="col-12 col-md-7">
                        <p> Incentive Percentage</p>
                    </div>
                    <div class="col-12 col-md-5 d-flex">

                        <input type="number" name="ppa" class="form-control " step="0.5" id="numberInputs" value="1" min="1" max="100">
                        <span class="input-group-btn">

                        </span>
                    </div>

                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-rounded btn-info btn-info">Submit</button>
            </div>
            </form>
        </div>
    </div>
</div>

@endsection
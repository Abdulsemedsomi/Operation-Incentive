@extends('layouts.backend')
@section('content')
<div class="container mt-20">
    <div class="block">
        <div class="block-header block-header-default">
            <h3 class="block-title">CFR Response</h3>
        </div>
        <div class="block-content">
            <div class="row">
                <h4 class="col-md-2">Employee: </h4>
                <h4 class="col-md-4">Samuel Negash</h4>
            </div>
        </div>
        <div class="block-content">
            <ol>
                <h5><li>In which engagement and KPI measures does he/she has got reprimand in this quarter please mention and review each reprimand issued?</li></h5>
                <h6>Check-in on time</h6>
                <h5><li>In which engagement and KPI measures does he/she has got appreciation in this quarter please mention and review each appreciation issued?</li></h5>
                <h6>Go beyond and above</h6>
                <h5><li>What are the two or three things your manager could do differently to better manage and increase your engagement and performance in the next few weeks of the quarter</li></h5>
                <h6>None</h6>
                <h5><li>What are the two or three things could your manager do differently to better manage and increase your performance in the next quarter?</li></h5>
                <h6>Check in on time</h6>
                <h5><li>What accomplishment this quarter are you most proud of from your goals/tasks.</li></h5>
                <h6>Example Achivement</h6>
                <h5><li>What personal strength help you do your job effectively?</li></h5>
                <h6>Example strength</h6>
                <h5><li>What motivates you to get your job done?</li></h5>
                <h6>Example Motivation</h6>
                <h5><li>Managers Feedback</li></h5>
                <h6>Example Feedback</h6>
            </ol>
        </div>
        <div class="block-content" align="right">
            <a type="button" class="btn btn-rounded btn-lg btn-secondary" href="{{ url('cfrpage') }}">Back</a>
        </div>
        <div class="block-content"></div>
    </div>
</div>
@endsection

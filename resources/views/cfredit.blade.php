@extends('layouts.backend')
@section('content')
<div class="container mt-50">
    <div class="block">
        <div class="block-content">
            <div class="row">
                <div class="col-md-2">
                    <a type="button" class="btn btn-rounded btn-secondary" href="{{ url('cfrpage') }}">
                        <i class="fa fa-arrow-left"></i> Back to Action plans
                    </a>
                </div>
                <h2 class="col-md-8">Action Plan - Edit</h2>
            </div>
            <hr>
            <ol>
                <h5><li>Issue Raised</li></h5>
                <textarea class="form-control round" rows="5" style="outline: none; ">More external projects for BAI</textarea>
                <h5><li>What will be done? Steps and tasks</li></h5>
                <textarea class="form-control round" rows="5" style="outline: none; ">Department managers/Team Lead jointly with Sales team to work to build team expertise and solution development</textarea>
                <h5><li>Who will do it?</li></h5>
                <input class="form-control round" value="Hawi Tesfaye" style="outline: none; ">
                <h5><li>Who will need to be involved?</li></h5>
                <input class="form-control round" value="People Depatment" style="outline: none; ">
                <h5><li>What resource are needed?</li></h5>
                <textarea class="form-control round" rows="5" style="outline: none; ">NONE</textarea>
                <h5><li>Status</li></h5>
                <div class="col-md-5">
                    <select class="form-control round mb-10" id="sessionselect" name="session-select">
                            <option value="0" disabled>Please select Status</option>
                            <option value="Open">Open</option>
                            <option value="In Progress">In Progress</option>
                            <option value="Done">Done</option>
                    </select>
                </div>
                <h5><li>Remark</li></h5>
                <textarea class="form-control round" rows="5" style="outline: none; "></textarea>
            </ol>
        </div>
        <div class="block-content">
            <h5>Logs</h5>
            <ol>
                <li>Samuel Negash edited this on <i>May 12, 2021 8:12 PM</i></li>
            </ol>
        </div>
        <div class="block-content" align="right">
            <button type="button" class="btn btn-rounded btn-lg btn-info">Done</button>
            <button type="button" class="btn btn-rounded btn-lg btn-secondary">Cancel</button>
        </div>
        <div class="block-content"></div>
    </div>
</div>
@endsection

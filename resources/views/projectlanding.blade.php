@extends('layouts.backend')
@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-6 mt-50">
            <div class="block block-rounded mt-50 bg-gd-sun">
                <a href="{{route('projectcheckin', $project->id)}}" style="text-decoration: none; color:#575757;">
                <div class="block-content text-center">
                    <p class="mt-5 text-center">
                        <i class="si si-check fa-3x text-white-op"></i>
                    </p>
                    <h3 class="font-w300 text-white">Project Checkin</h3>
                    <p class="text-white">What did you work on today? What are your targets for tomorrow?</p>
                </div>
                </a>
            </div>
        </div>
        <div class="col-md-6 mt-50">
            <div class="block block-rounded mt-50 bg-gd-sea">
                <a href="{{route('projectsinfo', $project->id)}}" style="text-decoration: none; color:#575757;">
                <div class="block-content text-center">
                    <p class="mt-5 text-center">
                        <i class="si si-info fa-3x text-white-op"></i>
                    </p>
                    <h3 class="font-w300 text-white">Project details</h3>
                     <p class="text-white">Update project details and set delivery milestones</p>
                </div>
                </a>
            </div>
        </div>
    </div>
</div>
@endsection

@extends('layouts.backend')
@section('content')
<style>
#horizontal-list > li {
    display: inline-block;
    /* You can also add some margins here to make it look prettier */
    zoom:1;
    *display:inline;
    /* this fix is needed for IE7- */
}
</style>
<div class="container mt-20">
    <h3 class="font-w500 text-center">{{$team->team_name}} Team</h3>
        <div class="container">
        <ul class="mx-50" id="horizontal-list">
            @foreach($members as $member)
                @if($member->avatar == null)
                    <li>
                        <a title="{{$member->fname . " " . $member->lname}}, {{$member->position}}">
                        <div class="avatar-circle2" style="width:35px; height:35px; border-radius:50%; --ccolor: #{{$member->avatarcolor}};" >
                            <span class="pinitials1" style="cursor:default">{{$member->fname[0] . $member->lname[0]}}</span>
                        </div>
                        <!--<div class="tooltip"></div>-->
                        </a>
                   </li>
                @else
                   <li>
                       <a title="{{$member->fname . " " . $member->lname}}, {{$member->position}}">
                        <img src="https://ienetworks.co/pms/uploads/avatars/{{ $member->avatar }}" style="width:35px; height:35px; border-radius:50%;">
                       </a>
                    </li>
                @endif
            @endforeach
             @if(Gate::any(['addtoteam', 'newrole']))
             <li class="float-right"><button  type="button" class="btn btn-sm btn-rounded btn-outline-primary float-right mt-5" onclick="window.location='{{route('editteammembers', $team->id)}}'" >Add/remove people</button> </li>
             @endif
        </ul>
        </div>
    <div class="row items-push gutters-tiny mt-30">
        @foreach($sessions as $session)
        <div class="col-md-4">
            <div class="block block-bordered bg-gd-sea round">
                <a href="{{ route('teamcheckin', ['teamid' => $team->id, 'sessionid' => $session->id]) }}" style="text-decoration: none; color:#575757;" onclick="Codebase.loader('show', 'bg-gd-sea');setTimeout(function () { Codebase.loader('hide'); });">
                    <div class="block-content">
                        <h5 class="font-w600 text-white text-center">{{$session->session_name}}{{$session->status=="Active"? " (Active)": ''}}</h5>
                    </div>
                </a>
            </div>
        </div>
        @endforeach
    </div>
</div>
@include('includes.project-modals')
@endsection

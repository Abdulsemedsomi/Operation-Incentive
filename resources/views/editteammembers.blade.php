@extends('layouts.backend')
@section('content')
<div class="container">
    <div class="mt-50 mb-10 text-center">
        <h2 class="font-w700 text-black mb-10">{{$team->team_name}}</h2>
        <hr>
    </div>
     @if(Gate::any(['addtoteam', 'newrole']))
        <div class=" form-group">
            <button  type="button" class="btn btn-rounded btn-outline-primary float-right " data-toggle="modal" data-target="#addMembermodal"  >Add member</button>
        </div>
    @endif
   <div class="container mt-30 row">
        @foreach($teammembers as $tm)
            <div class="col-md-4 col-xl-4" id="team-m{{$tm->id}}"> 
                <div class="block" >
                    <div class="block-header block-header-default">
                        <h3 class="block-title">
                            <div class="row">
                             @if(App\User::find($tm->user_id)->avatar == null)
                                 <div class="avatar-circle2 " style="width:30px; height:30px; border-radius:50%; --ccolor: #{{App\User::find($tm->user_id)->avatarcolor}};" >
                                    <span class="pinitials2" style=" cursor:default">{{App\User::find($tm->user_id)->fname[0] . App\User::find($tm->user_id)->lname[0]}}</span>
                                </div>
                            @else
                                <img  src="https://ienetworks.co/pms/uploads/avatars/{{ App\User::find($tm->user_id)->avatar }}" style="width:30px; height:30px; border-radius:50%;">
                            @endif
                            <p class="col-md-9">{{App\User::find($tm->user_id)? App\User::find($tm->user_id)->fname. " " . App\User::find($tm->user_id)->lname:""}} </p>
                            </div>
                            </h3>
                        <div class="block-options">
                            <button type="button" class="btn-block-option text-danger deleteteammember" data-id="{{$tm->user_id}}">
                                <i class="si si-trash"></i>
                            </button>
                        </div>
                    </div>
                    <div class="block-content">
                        <p>{{App\User::find($tm->user_id)->position}}</p>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>
 @include('includes.teammembermodals')
@endsection

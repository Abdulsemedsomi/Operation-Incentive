@extends('layouts.backend')
@section('content')
<div class="bg-info">
    <div class="bg-pattern bg-black-op-25 py-30" style="background-image: url('images/bg-pattern.png');">
        <div class="content content-full text-center">
            <div class="content content-full text-center">
                <div class="mb-15">
                    <a class="img-link">
                        @if(Auth::user()->avatar == null)
                        <div class="avatar-circle" style="width:150px; height:150px; float:left; border-radius:50%; margin-right:25px;">
                            <span class="pinitials" >{{Auth::user()->fname[0] . Auth::user()->lname[0]}}</span>
                        </div>
                    @else
                        <img src="uploads/avatars/{{ Auth::user()->avatar }}" style="width:150px; height:150px; float:left; border-radius:50%; margin-right:25px;">
                    @endif
                    </a>
                </div>
                <h1 class="h3 text-white font-w700 mb-10">{{Auth::user()->fname . " " . Auth::user()->lname}}</h1>
                <h2 class="h5 text-white-op">
                    {{Auth::user()->position}} || {{Auth::user()->team}}
                </h2>
            </div>
        </div>
    </div>
</div>
<div class="content">
<!-- User Profile -->
<div class="block">
    @if($message = Session::get('success'))
    <div class="alert alert-success alert-block">
        <button type="button" class="close" data-dismiss="alert">×</button>
        <strong>{{ $message }}</strong>
    </div>
@elseif($message = Session::get('error'))
    <div class="alert alert-danger alert-block">
        <button type="button" class="close" data-dismiss="alert">×</button>
        <strong>{{ $message }}</strong>
    </div>
@endif
    <div class="block-header block-header-default">
        <h3 class="block-title">
            <i class="fa fa-user-circle mr-5 text-muted"></i> User Profile
        </h3>
    </div>
    <div class="block-content">
        <div class="row items-push">
            <div class="col-lg-3">
                <p class="text-muted">
                        Update your avatar here
                </p>
            </div>
            <div class="col-lg-7 offset-lg-1">
                <div class="form-group row">
                    <div class="col-12">
                        <label for="profile-settings-name">Name</label>
                        <input type="text" class="form-control form-control-lg" id="profile-settings-name" name="profile-settings-name" placeholder="Enter your name.." value="{{Auth::user()->fname. " ". Auth::user()->lname}}" disabled>
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-12">
                        <label for="profile-settings-email">Email Address</label>
                        <input type="email" class="form-control form-control-lg" id="profile-settings-email" name="profile-settings-email" placeholder="Enter your email.." value="{{Auth::user()->email}}" disabled>
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-md-10 col-xl-6">
                        <div class="push">
                            @if(Auth::user()->avatar == null)
                                <div class="avatar-circle" style="width:150px; height:150px; float:left; border-radius:50%; margin-right:25px;" id="initaldiv">
                                    <span class="pinitials" >{{Auth::user()->fname[0] . Auth::user()->lname[0]}}</span>
                                </div>
                                <img src="uploads/avatars/{{ Auth::user()->avatar }}" style="width:150px; height:150px; float:left; border-radius:50%; margin-right:25px;" id="displayimg" hidden>
                            @else
                                <img src="uploads/avatars/{{ Auth::user()->avatar }}" style="width:150px; height:150px; float:left; border-radius:50%; margin-right:25px;" id="displayimg">
                            @endif
                        </div>
                    </div>
                </div>
                @if($errors->any())
                    <div class="alert alert-danger" role="alert">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>

                        @foreach($errors->all() as $error)
                            {{ $error }}<br/>
                        @endforeach
                    </div>
                @endif
                <form enctype="multipart/form-data" action="{{ route('changeprofile')}}" method="POST">
                    @csrf
                    <div class="custom-file col-md-10 col-xl-6">
                        <input type="file" name="avatar" class="custom-file-input" id="profile-settings-avatar"  data-toggle="custom-file-input" onchange="PreviewImage();">
                        <label class="custom-file-label" for="profile-settings-avatar">Choose new avatar</label>
                    </div>
                    <div class="form-group row mt-3">
                        <div class="col-12">
                            <button type="submit" class="btn btn-alt-primary">Upload</button>
                        </div>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>
@endsection

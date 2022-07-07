@extends('layouts.backend')
@section('content')
<div class="container">
     <div class="mt-50 mb-10 text-center">
        <h2 class="font-w700 mb-10">Drivers Engagement page</h2>
    </div>
    <hr>
</div>
<div class="container">
    <div class="col-md-8">
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
    </div>
    @if(Auth::user()->id == 153 ||Auth::user()->id == 194 )
      <ul class="nav nav-tabs md-tabs" id="myTabMD" role="tablist">
            
            <li class="nav-item">
                <a class="nav-link active" id="home-tab-md" data-toggle="tab" href="#home-md" role="tab" aria-controls="home-md"
                aria-selected="true">Drivers</a>
            </li>
        
            <li class="nav-item">
                <a class="nav-link " id="profile-tab-md" data-toggle="tab" href="#profile-md" role="tab" aria-controls="profile-md"
                aria-selected="false">Managers</a>
            </li>
            </ul>
    <div class="tab-content pt-5" id="myTabContentMD">
      
    <div class="tab-pane fade show active" id="home-md" role="tabpanel" aria-labelledby="home-tab-md">
    <div class="block">
        <div class="block-content">
            <table class="table table-bordered table-vcenter">
                <thead>
                    <tr>
                        <th class="text-center" style="width: 50px;">#</th>
                        <th>Name</th>
                        <th class="d-none d-sm-table-cell" style="width: 15%;">Position</th>
                        <th class="text-center" style="width: 30%;">Engagement</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $count = 1;
                    @endphp
              
                    
                    @foreach($users as $user)
                    <tr>
                        <th class="text-center" scope="row">{{$count++}}</th>
                        <td>{{$user->fname . " ". $user->lname}}</td>
                        <td class="d-none d-sm-table-cell">
                            {{$user->position}}
                        </td>
                        
                        <td class="text-center">
                            <div class="container">
                                <div class="row">
                                    <div class="col-md-2 col-sm-1 col-xs-1">
                                        <button type="button" class="btn btn-circle btn-outline-success mr-5 mb-5" onclick="launchdriverengage('{{$user->fname . " ". $user->lname}}', {{$user->id}}, 1)" id="app"><i class="si fa-fw si-badge "></i></button>
                                    </div>
                                    <div class="col-md-2 col-sm-1 col-xs-1">
                                        <button type="button" class="btn btn-circle btn-outline-danger mr-5 mb-5" onclick="launchdriverengage('{{$user->fname . " ". $user->lname}}', {{$user->id}} , 2)" id="rep"><i class="fa fa-fw fa-bomb "></i></button>
                                    </div>
                                </div>
                                
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    </div>
     <div class="tab-pane fade " id="profile-md" role="tabpanel" aria-labelledby="profile-tab-md">
    <div class="block">
        <div class="block-content">
            <table class="table table-bordered table-vcenter">
                <thead>
                    <tr>
                        <th class="text-center" style="width: 50px;">#</th>
                        <th>Name</th>
                        <th class="d-none d-sm-table-cell" style="width: 15%;">Position</th>
                        <th class="text-center" style="width: 30%;">Engagement</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $count = 1;
                        $usersm = App\User::where('active', 1)->where('ismanager',1)->orderby('users.fname', 'asc')->get();
                    @endphp
              
                    
                    @foreach($usersm as $user)
                    <tr>
                        <th class="text-center" scope="row">{{$count++}}</th>
                        <td>{{$user->fname . " ". $user->lname}}</td>
                        <td class="d-none d-sm-table-cell">
                            {{$user->position}}
                        </td>
                        
                        <td class="text-center">
                            <div class="container">
                                <div class="row">
                                    <div class="col-md-2 col-sm-1 col-xs-1">
                                        <button type="button" class="btn btn-circle btn-outline-success mr-5 mb-5" onclick="launchdriverengage('{{$user->fname . " ". $user->lname}}', {{$user->id}}, 1)" id="app"><i class="si fa-fw si-badge "></i></button>
                                    </div>
                                    <div class="col-md-2 col-sm-1 col-xs-1">
                                        <button type="button" class="btn btn-circle btn-outline-danger mr-5 mb-5" onclick="launchdriverengage('{{$user->fname . " ". $user->lname}}', {{$user->id}} , 2)" id="rep"><i class="fa fa-fw fa-bomb "></i></button>
                                    </div>
                                </div>
                                
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    </div>
    </div>
    @else
     <div class="block">
        <div class="block-content">
            <table class="table table-bordered table-vcenter">
                <thead>
                    <tr>
                        <th class="text-center" style="width: 50px;">#</th>
                        <th>Name</th>
                        <th class="d-none d-sm-table-cell" style="width: 15%;">Position</th>
                        <th class="text-center" style="width: 30%;">Engagement</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $count = 1;
                    @endphp
              
                    
                    @foreach($users as $user)
                    <tr>
                        <th class="text-center" scope="row">{{$count++}}</th>
                        <td>{{$user->fname . " ". $user->lname}}</td>
                        <td class="d-none d-sm-table-cell">
                            {{$user->position}}
                        </td>
                        
                        <td class="text-center">
                            <div class="container">
                                <div class="row">
                                    <div class="col-md-2 col-sm-1 col-xs-1">
                                        <button type="button" class="btn btn-circle btn-outline-success mr-5 mb-5" onclick="launchdriverengage('{{$user->fname . " ". $user->lname}}', {{$user->id}}, 1)" id="app"><i class="si fa-fw si-badge "></i></button>
                                    </div>
                                    <div class="col-md-2 col-sm-1 col-xs-1">
                                        <button type="button" class="btn btn-circle btn-outline-danger mr-5 mb-5" onclick="launchdriverengage('{{$user->fname . " ". $user->lname}}', {{$user->id}} , 2)" id="rep"><i class="fa fa-fw fa-bomb "></i></button>
                                    </div>
                                </div>
                                
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif
</div>
@include('includes.driverapprepmodal')
<script>
   
</script>
@endsection

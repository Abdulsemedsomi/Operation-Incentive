@extends('layouts.backend')
@section('content')
<div class="container">
     <div class="mt-50 mb-10 text-center">
        <h4 class="font-w500 mb-10">What did you work on today? What are your goals for tomorrow?</h4>
        <hr>
    </div>
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
    <div class="block">
          <form action = "{{ route('projectcheckins.store') }}"   method="post">
                @csrf
            <input type="text" class="form-control" name="project_id" hidden value={{$project->id}}>

        <div class="block-content">

                <div class="form-group">
                    <div class="col-12">
                        <textarea name="checkin" id="pcheckin" rows="30"></textarea>
                    </div>
                </div>
        </div>
        <div class="block-content">
            <div  class="row">
                <button type="submit" class="btn btn-rounded btn-outline-success min-width-125 mx-40 mb-10 ml-30"  >Add Answer</button>
            </div>
        </div>
        </form>
    </div>
     <hr>
     @if( Gate::any(['fillengageproject', 'fillkpiproject']))
                 <div class="mt-20" style="width: 140px;border: 0.5px solid #ccc; box-sizing: border-box;  padding: 15px 15px 15px 15px;">
                    <div class="" style="margin-bottom: -15px;">
                        <div class="row">
                            <div class="col-6">
                                <label class="css-control css-control-primary css-switch-square">
                                    <input type="checkbox" class="css-control-input" id="typetoggle" checked data-toggle="toggle" data-size="xs" data-off="KPI" data-on="Engagement" data-onstyle="outline-info" data-offstyle="outline-primary">
                                    <span class="css-control-indicator"></span>
                                </label>
                            </div>
                        </div>
                    <div class="  mb-10">
                        <div class="row ">
                            <button class="btn btn-sm btn-circle btn-success nb mr-30 ml-20" onclick="launchprojectAppreciationmodal({{$project->id}});"><i class="si si-emoticon-smile" aria-role="presentation" aria-label="Appreciation"></i></button>
                            <button class="btn btn-sm btn-circle btn-danger nb" onclick="launchprojectReprimandmodal({{$project->id}});"><i class="fa fa-bomb" aria-role="presentation" aria-label="POUTING FACE"></i></button>
                        </div>
                    </div>
                    </div>
                 </div>
            @endif
     <div  class="container neopad">
         @php
            $pcheckins = App\Projectcheckin::where('project_id', $project->id)->orderby('projectcheckins.created_at','desc')->join('users', 'users.id', '=', "projectcheckins.user_id")->select('projectcheckins.id', 'users.id as uid', 'users.fname', 'users.lname', 'checkin', 'projectcheckins.created_at')->get();
            $pmember = App\Projectmember::where('position', 'PM')->where('project_id', $project->id)->first();
            $reportsTo = App\User::find($pmember->user_id);
             $comments = DB::table('comments')->where('project_id', $project->id)->get()->count();
        @endphp
        @foreach($pcheckins as $pcheckin)
        @php

        @endphp
    <div class="card text-center">
        <div class="card-body text-left">
          <h5 class="card-title"><a href="" style="text-decoration:none">{{$pcheckin->fname . " " . $pcheckin->lname}}</a></h5>
          <div class="row pull-right">
                    <div class="text-muted">
                        <span class="floar-right">{{ Carbon\Carbon::parse($pcheckin->created_at)->isoFormat('MMMM Do YYYY, h:mm:ss a') }}</span>
                    </div>
                    <div class="dropdown">
                        <!--<button type="button" class="btn-block-option" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">-->
                        <!--    <i class="fa fa-fw fa-ellipsis-v"></i>-->
                        <!--</button>-->
                        <!--<div class="dropdown-menu dropdown-menu-right" style="">-->
                        <!--    <a class="dropdown-item" href="#">-->
                        <!--        <i class="si fa-fw si-pencil mr-5"></i>Edit-->
                        <!--    </a>-->
                        <!--    <a class="dropdown-item" href="#">-->
                        <!--        <i class="si fa-fw si-trash mr-5"></i>Delete-->
                        <!--    </a>-->
                        <!--</div>-->
                    </div>
                </div>
          <p class="card-text" style="font-weight:bold; padding-top:0.5rem;">Selam <a href="" style="text-decoration: aqua">{{$reportsTo?$reportsTo->fname . " " . $reportsTo->lname:"Manager"}}</a></p>
            <div class="container">
                <p class="card-text">{!!$pcheckin->checkin!!}</p>
            </div>



        </div>
        <div class="card-body">
            <div class="row pull-right">
           <a type="submit" class="btn btn-rounded btn-outline-info min-width-125" href="{{route('projectcomment', $pcheckin->id)}}">
            @if($comments > 1)
            {{$comments}} comments
            @elseif($comments > 0)
            {{$comments}} comment

           @else
           Discuss
            @endif
        </a>
            <div class="col-md-1"></div>

            </div>
       </div>
      </div>
      @endforeach
    </div>
</div>
@include('includes.projectapprepmodal')
 <script>
    tinymce.init({
      selector: '#pcheckin',
      branding: false,
      height: 300,
      statusbar: false,
      plugins: 'lists',
      toolbar: "formatselect | bold italic backcolor forecolor| alignleft aligncenter alignright alignjustify | bullist numlist | '",
      menubar: 'false'
      });
  </script>
@endsection


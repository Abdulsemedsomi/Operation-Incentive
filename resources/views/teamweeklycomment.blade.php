@extends('layouts.backend')
@section('content')
<div class="container">
    <nav class="breadcrumb bg-white push">
        <a class="breadcrumb-item" href="{{ route('myteams', $team->id) }}">{{$team->team_name}}</a>
         <a class="breadcrumb-item"  href="{{ route('teamcheckin', ['teamid'=>$team->id, 'sessionid'=>$session->id]) }}" >{{$session->session_name}} </a>
          <a class="breadcrumb-item"  href="{{ route('tweeklyplan', ['teamid'=>$team->id, 'sessionid'=>$session->id]) }}" >Weekly Plan </a>
        <span class="breadcrumb-item active">Discuss</span>
    </nav>
</div>
<div class="container">
    <div class="content">
        <div class="mb-10">
            <h5 class="font-w300">{{$plan->fname . " ". $plan->lname}}'s Answer to</h5>
            <h4 class="font-w400">{{$message}}</h4>
        </div>
         <div>
        <?php 
        $counter = 0;
        $unreadCount = Auth::user()->unreadNotifications()->count();
        ?>
         @foreach (Auth::user()->unreadNotifications as $notifications)  
            @php
         $notfipath= "https://ienetworks.co".$notifications->data['link'] ; 
         
            if ($currentPath == $notfipath){
                
                $notifications->markAsRead();
                 ++$counter;
            } 
            @endphp
        @endforeach   
      
        
        <p hidden id="readCount">{{$counter}}</p>
    </div>
        <div class="block">
            @php
                    $objectives = DB::table('weeklyplans')->where('weeklyplans.planid', $plan->id)->join('keyresults','weeklyplans.keyresult_id','=', 'keyresults.id')
                    ->join('objectives','keyresults.objective_id','=', 'objectives.id')
                    ->select('objective_name', 'objective_id')->distinct()->get();

                    $metricvalues = DB::table('weeklyplans')->where('weeklyplans.planid', $plan->id)->join('tasks','weeklyplans.task_id','=', 'tasks.id')
                    ->join('keyresults','tasks.keyresultid','=', 'keyresults.id')->select('keyresultid', 'keyresult_name', 'keyresult_percent', 'objective_id')->distinct()->get();
                    $weekplan = DB::table('weeklyplans')->where('weeklyplans.planid', $plan->id)->join('tasks','weeklyplans.task_id','=', 'tasks.id')
                    ->join('keyresults','tasks.keyresultid','=', 'keyresults.id')->get();
                      $reportsTo = $plan->reportsTo != null ? (App\User::find($plan->reportsTo)->fname ." ". App\User::find($plan->reportsTo)->lname ): App\User::find($plan->userid)->reportsTo;
                     
            @endphp
            <div class="block text-center">
                <div class="card-body text-right" style="padding-bottom: 0%;">
                    <div class="row pull-right">
                        <div class="text-muted">
                            <span class="floar-right">{{$plan->isEdited == 0 ? Carbon\Carbon::parse($plan->created_at)->isoFormat('MMMM Do YYYY, h:mm:ss a')  :"Updated at ". Carbon\Carbon::parse($plan->created_at)->isoFormat('MMMM Do YYYY, h:mm:ss a') }}</span>
                        </div>
                        @if(Auth::user()->id == $plan->userid)
                        <div class="dropdown">
                            <button type="button" class="btn-block-option" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fa fa-fw fa-ellipsis-v"></i>
                            </button>
                            <div class="dropdown-menu dropdown-menu-right" style="">
                                <a class="dropdown-item" href="{{route('weeklyplan.edit', $plan->id)}}">
                                    <i class="si fa-fw si-pencil mr-5"></i>Edit
                                </a>
                                <a class="dropdown-item" href="#">
                                    <i class="si fa-fw si-trash mr-5"></i>Delete
                                </a>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
                <div class="card-body text-left">
                    <h5 class="card-text" style="font-weight:bold;">Selam <a href="" style="text-decoration: aqua">{{$reportsTo !=null ?$reportsTo:"Manager"}}</a></h5>
                    @foreach($objectives as $ob)
                        <b class="card-text"style="font-size:20px;">Objective: {{$ob->objective_name}}</b>
                        @foreach($metricvalues as $mv)
                            @if($mv->objective_id == $ob->objective_id)
                                <div class="row ml-20">
                                    <div class="col-md-12">
                                      <b class=" default metric-btn" style="font-size:16px;"><b>Key Result: </b>{{$mv->keyresult_name}} ({{$mv->keyresult_percent}}%)</b>
                                  </div>
                                </div>
                                <div  class="panel-collapse collapse-in ml-20">
                                    <div class="panel-body ">
                                        <div class="tasks-list">
                                            <ol type="1">
                                                 @foreach($weekplan as $wp)
                                                    @if($wp->keyresultid == $mv->keyresultid)
                                                        <li class=" default task-btn">{{$wp->taskname}} ({{$wp->task_percent }}%)
                                                          @if($wp->isMilestone == 1) 
                                                      <i class="fa fa-star"></i>
                                                      @endif
                                                        </li>
                                                    @endif
                                                @endforeach
                                            </ol>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        @endforeach
                    @endforeach
                    @if($plan->cc !=null)
                        <h6 class="card-text" style="padding-top:0.5rem;">CC:
                         @php
                            $ccarr = explode(",",$plan->cc)
                            @endphp
                            @foreach($ccarr as $c)
                                <a href="" style="text-decoration: aqua">{{App\User::find((int)$c)?App\User::find((int)$c)->fname . " ". App\User::find((int)$c)->lname:""}}</a>
                            @endforeach
                        </h6>
                    
                    @endif
                </div>
            </div>

           
            <div class="bg-gray-lighter">
                <div class="content content-full">
                    <div class="row justify-content-left py-10">
                        <div class="col-lg-12">
                            <h3 class="font-w700 mb-10" id="comment">Comments</h3>
                            
                                <!--<div class="col-md-8">-->
                                <!--    @if($message = Session::get('success'))-->
                                <!--        <div class="alert alert-success alert-block">-->
                                <!--            <button type="button" class="close" data-dismiss="alert">×</button>-->
                                <!--            <strong>{{ $message }}</strong>-->
                                <!--        </div>-->
                                <!--    @elseif($message = Session::get('error'))-->
                                <!--        <div class="alert alert-danger alert-block">-->
                                <!--            <button type="button" class="close" data-dismiss="alert">×</button>-->
                                <!--            <strong>{{ $message }}</strong>-->
                                <!--        </div>-->
                                <!--    @endif-->
                                <!--</div>-->
                            <div class="commentarea" >
                                @foreach($comments as $comment)
                                   
                                      <div class="media mb-15" id="commentarea{{$comment->id}}">
                                        <div class="media-body mx-5 my-5">
                                            <div class="row">
                                                <p class="mb-5 col-md-9 col-sm-9"><a class="font-w600" href="javascript:void(0)">{{$comment->fname. " " .$comment->lname}}</a><span class="font-w600" href="javascript:void(0)">,  {{$comment->position}}</span></p>
                                                <div class="text-muted col-md-2" style="font-size:10px;">
                                                    <span class="floar-right">{{$comment->isEdited == 0 ? Carbon\Carbon::parse($comment->created_at)->isoFormat('MMMM Do YYYY, h:mm:ss a')  :"Updated at ". Carbon\Carbon::parse($comment->created_at)->isoFormat('MMMM Do YYYY, h:mm:ss a') }}</span>
                                                </div>
                                                 @if(Auth::user()->id == $comment->commentor_id)
                                                <a class="col-md-1 col-sm-1" data-id={{$comment->id}} type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                                                    <i class="fa fa-bars"></i>
                                                </a>
                                                 <div class="dropdown-menu " aria-labelledby="navbarDropdown3">
                                                     <li><a  id ="edit" class="dropdown-item commentdata editComment" data-type={{$type}} data-id= "{{$comment->id}}">Edit</a></li>
                                                     <li><a id="delete" class="dropdown-item deletecom "  data-toggle="modal" data-target="#deletecomment{{$comment->id}}">Delete</a></li>
                                                </div>
                                                @endif
                                            </diV>
                                            <div class="container">
                                                <div class="row">
                                                    <div class="container">
                                                        <div class="mr-20" id="commenttext{{$comment->id}}"> {!!$comment->comment!!}</div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                     </div>
                                      @include('includes.commentdelete-modal')
                                    
                                @endforeach
                            </div>
                        </div>
                        <div class="media mb-30 col-md-12">
                            <div class="media-body">
                                <form action="{{route('comments.store')}}" method="post" >
                                    @csrf
                                    <textarea class="form-control mb-5 tribute-demo-input mentionClass " contenteditable="true" rows="5" placeholder="Add Comment.." id="commentbody" name = "comment" data-uid = 0></textarea>

                                    <input type="hidden" name="{{$type}}_id" value="{{$plan->id}}">
                                    <button type="submit" class="btn btn-secondary" data-id="{{$plan->id}}" data-type={{$type}} onclick="Codebase.loader('show', 'bg-gd-sea');setTimeout(function () { Codebase.loader('hide'); });">
                                        <i class="fa fa-reply mr-4"></i>Add comment
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>

var mark = document.getElementById("readCount").innerHTML;
           
            console.log(mark);  
            
              $('.mark-as-read').ready(function(){
                var cou = document.getElementById('counter').innerHTML;
                    cou=cou-mark;
                    document.getElementById('counter').innerHTML=cou;
            });
            
    var newmark = document.getElementById('readCount').innerHTML=0;
    console.log(newmark);      
    
</script>

@endsection

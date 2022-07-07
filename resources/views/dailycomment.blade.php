@extends('layouts.backend')
@section('content')
<div class="container">
    <nav class="breadcrumb bg-white push">
        <a class="breadcrumb-item" href="{{ route('checkin', $team->id) }}">Check-in</a>
        <a class="breadcrumb-item" href="{{ route('dailyplan', $team->id) }}">Daily-plan</a>
        <span class="breadcrumb-item active">Discuss Page</span>
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
            $metricvalues = DB::table('dailyplans')->where('dailyplans.planid', $plan->id)->orderby('keyresults.id', 'asc')->join('tasks','dailyplans.task_id','=', 'tasks.id')
            ->join('keyresults','tasks.keyresultid','=', 'keyresults.id')->select('keyresultid', 'keyresult_name', 'keyresults.id as keyid')->distinct()->get();
            $weekplan = DB::table('dailyplans')->where('dailyplans.planid', $plan->id)->orderby('tasks.id', 'asc')->join('tasks','dailyplans.task_id','=', 'tasks.id')
            ->join('keyresults','tasks.keyresultid','=', 'keyresults.id')->select('keyresultid', 'taskname', 'tasks.id')->distinct()->get();
           $reportsTo = $plan->reportsTo != null ? (App\User::find($plan->reportsTo)->fname ." ". App\User::find($plan->reportsTo)->lname ): App\User::find($plan->userid)->reportsTo;
                $ncomments = DB::table('comments')->where('plan_id', $plan->id)->where('type', 2)->first();
          
            @endphp
            <div class="block text-center">
                <div class="card-body text-right" style="padding-bottom: 0%;">
                    <div class="row pull-right">
                        <div class="text-muted">
                            <span class="floar-right">{{$plan->isEdited == 0 ? Carbon\Carbon::parse($plan->created_at)->isoFormat('MMMM Do YYYY, h:mm:ss a')  :"Updated at ". Carbon\Carbon::parse($plan->created_at)->isoFormat('MMMM Do YYYY, h:mm:ss a') }}</span>
                        </div>
                          @if(Auth::user()->id == $plan->userid && !$ncomments)
                        <div class="dropdown">
                            <button type="button" class="btn-block-option" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fa fa-fw fa-ellipsis-v"></i>
                            </button>
                            <div class="dropdown-menu dropdown-menu-right" style="">
                                <a class="dropdown-item" href="{{route('dailyplan.edit', $plan->id)}}">
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
                    <h5 class="card-text" style="font-weight:bold; padding-top:0.5rem;">Selam <a href="" style="text-decoration: aqua">{{$reportsTo !=null ?$reportsTo:"Manager"}}</a></h5>
                    @foreach($metricvalues as $mv)
                        <div class="row">
                            <div class="col-md-12">
                                <ul class=" default metric-btn" style="font-size:18px;"><b>Key Result: {{$mv->keyresult_name}}</b></ul>
                            </div>
                        </div>
                        <div class="panel-collapse collapse-in">
                            <div class="panel-body ">
                                <div class="tasks-list">
                                    <ol type="1">
                                        @foreach($weekplan as $wp)
                                            @if($wp->keyresultid == $mv->keyresultid)
                                                @php
                                        $subtasks = DB::table('dailyplans')->where('dailyplans.planid', $plan->id)->where('dailyplans.task_id', $wp->id)->join('subtasks','dailyplans.subtask_id','=', 'subtasks.id')->get();
                                    @endphp
                                   
                                    @if($subtasks->count() == 0)
                                        <li class=" default task-btn" style="font-size:16px;">{{$wp->taskname}}</li>
                                        
                                    @else
                                    <li class=" default task-btn" style="font-size:16px;">{{$wp->taskname}}</li>
                                         <ul>
                                      @foreach($subtasks as $subtask)
                                        <div class="container">
                                            <li class=" default task-btn">{{$subtask->subtask_name}}</li>
                                        </div>
                                     @endforeach
                                    </ul>
                                    @endif
                                            @endif
                                        @endforeach
                                    </ol>
                                </div>
                            </div>
                        </div>
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
                    <div class=" justify-content-left py-10">
                        <div class="col-lg-12">
                            <h3 class="font-w700 mb-10" id="comment">Comments</h3>
                            
                         
                            <div class="commentarea" >
                                @foreach($comments as $comment)
                                   
                                      <div class=" mb-15" id="commentarea{{$comment->id}}">
                                        <!--<div class=" mx-5 my-5">-->
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
                                            </div>
                                            <div class="container">
                                                <div class="row">
                                                    <div class="container">
                                                        <div class="mr-20" id="commenttext{{$comment->id}}"> {!!$comment->comment!!}</div>
                                                    </div>
                                                </div>
                                            </div>
                                        <!--</div>-->
                                     </div>
                                      @include('includes.commentdelete-modal')
                                    
                                @endforeach
                              
                            </div>
                        </div>
                        </div>
                        <div class="media mb-30 col-md-12">
                            <div class="media-body">
                                <form action="{{route('comments.store')}}" method="post" >
                                    @csrf
                                    <textarea class="form-control mb-5 tribute-demo-input mentionClass" contenteditable="true" rows="5" placeholder="Add Comment.." id="commentbody" name = "comment" data-uid = 0></textarea>

                                    <input type="hidden" name="{{$type}}_id" value="{{$plan->id}}">
                                    <button type="submit" class="btn btn-secondary" data-id="{{$plan->id}}" data-type={{$type}} >
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

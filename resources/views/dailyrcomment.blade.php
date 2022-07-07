@extends('layouts.backend')
@section('content')
<div class="container">
    <nav class="breadcrumb bg-white push">
        <a class="breadcrumb-item" href="{{ route('checkin', $team->id) }}">Check-in</a>
        <a class="breadcrumb-item" href="{{ route('dailyreport', $team->id) }}">Daily-Report</a>
        <span class="breadcrumb-item active">Discuss Page</span>
    </nav>
</div>
<div class="container">
<div class="content">
    <div class="mb-10">
        <h5 class="font-w300">{{$plan->fname . " ". $plan->lname}}'s Answer to</h5>
        <h4 class="font-w400">{{$message}}</h4>
    </div>
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
                $metricvalues = DB::table('dailyreports')->where('report_id', $plan->id)->join('tasks', 'dailyreports.task_id', '=', 'tasks.id')->join('keyresults', 'tasks.keyresultid', '=', 'keyresults.id')->select('keyresultid', 'keyresult_name')->distinct()->get();
                $weekplan = DB::table('dailyreports')->where('report_id', $plan->id)->orderby('tasks.id','asc')->join('tasks','dailyreports.task_id','=', 'tasks.id')
                        ->select('tasks.id', 'taskname', 'keyresultid','dailyreports.status', 'feedback')->groupby('tasks.id')->distinct()->get();
                $reportsTo =$plan->reportsTo != null ? (App\User::find($plan->reportsTo)->fname ." ". App\User::find($plan->reportsTo)->lname ): App\User::find($plan->userid)->reportsTo;
                $disciplinec = DB::table('fill_engagements')->where('report_id', $plan->id)->where('Perspective', 1)->join("engagements", 'engagements.id', '=','fill_engagements.engagement_id')->get()->count();
                $excellencec = DB::table('fill_engagements')->where('report_id', $plan->id)->where('Perspective', 0)->join("engagements", 'engagements.id', '=','fill_engagements.engagement_id')->get()->count();
                $ncomments = DB::table('comments')->where('report_id', $plan->id)->where('type', 2)->first();
            @endphp
        <div class="block text-center">
            <div class="block-content block-content-full ribbon ribbon-success">
                {{-- For Appericiation --}}
                @if($excellencec> 0)
                <div class="ribbon-box">
                    <b>{{$excellencec}} </b><i class="si fa-fw si-badge mt-10"></i>
                </div>
                @endif
                @if($disciplinec >0)
                {{-- For Reprimand --}}
                <div class="ribbon-box2">
                    <b>{{$disciplinec}} </b><i class="fa fa-fw fa-bomb mt-10"></i>
                </div>
                @endif
                {{-- Remove the comment and put an if else statement --}}
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
                            <a class="dropdown-item" href="{{route('dailyreport.edit', $plan->id)}}">
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
                            <ul class=" default metric-btn" style="font-size:16px;"><b>Key Result: {{$mv->keyresult_name}}</b></ul>
                        </div>
                    </div>
                    <div class="panel-collapse collapse-in">
                        <div class="block-content">
                            <div class="tasks-list">
                                <ol type="1">
                                    @foreach($weekplan as $wp)
                                        @if($wp->keyresultid == $mv->keyresultid)
                                            @php
                                            $subtasks =  DB::table('dailyreports')->where('report_id', $plan->id)->where('dailyreports.task_id', $wp->id)->join('subtasks','dailyreports.subtask_id','=', 'subtasks.id')->get();
                                            @endphp
                                             @if($subtasks->count() ==0)
                                                    <div class = "row">
                                                        <li class=" default task-btn col-md-8">{{$wp->taskname}}</li>

                                                            @if($wp->status == 0)
                                                                <b class="text-danger col-md-4">Not Yet @if($wp->feedback !=null)({{$wp->feedback}})@endif</b>
                                                            @else
                                                                <b class="text-success col-md-4">Achieved @if($wp->feedback !=null)({{$wp->feedback}})@endif</b>
                                                            @endif
                                                    </div>
                                            @else
                                                <div class = "row">
                                                    <li class=" default task-btn col-md-9">{{$wp->taskname}}</li>
                                                </div>
                                                <ol >
                                                    @foreach($subtasks as $st)
                                                        <div class = "row">
                                                            <li class=" default task-btn col-md-8">{{$st->subtask_name}} </li>
                                                            @if($st->status == 0)
                                                                <b class="text-danger col-md-4">Not Yet @if($st->feedback !=null)({{$st->feedback}})@endif</b>
                                                            @else
                                                                <b class="text-success col-md-4">Achieved  @if( $st->feedback != null)({{$st->feedback}})@endif</b>
                                                            @endif
                                                        </div>
                                                    @endforeach
                                                </ol>
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
                <div class="block-content ">
                    <div class="container mt-5 mx-5 my-5 col-md-12">
                        <!--<div  class="row pull-right">-->
                        <!--    <button class="btn btn-lg btn-circle btn-alt-success nb mr-5 mb-5" data-toggle="modal" data-target="#modal-large-app" ><i class="si si-emoticon-smile" aria-role="presentation" aria-label="Appreciation"></i></button>-->
                          
                        <!--    <button class="btn btn-lg btn-circle btn-alt-danger nb mr-5 mb-5" data-toggle="modal" data-target="#modal-large"><i class="fa fa-bomb" aria-role="presentation" aria-label="POUTING FACE" ></i></button>-->
                        <!--</div>-->
                    </div>
                </div>
            </div>
            </div>
        </div>
          <div class="bg-gray-lighter">
                <div class="content content-full">
                    <div class="row justify-content-left py-10">
                        <div class="col-lg-12">
                            <h3 class="font-w700 mb-10" id="comment" >Comments</h3>
                           <div class="commentarea" >
                                @foreach($comments as $comment)
                                   
                                      <div class=" mb-15" id="commentarea{{$comment->id}}">
                                        <!--<div class=" mx-5 my-5">-->
                                            <div class="row">
                                                <p class="mb-5 col-md-9 col-sm-9"><a class="font-w600" href="javascript:void(0)">{{$comment->fname. " " .$comment->lname}}</a><span class="font-w600" href="javascript:void(0)">,  {{$comment->position}}</span></p>
                                                
                                                 @if(Auth::user()->id == $comment->commentor_id)
                                                 <div class="text-muted col-md-2" style="font-size:10px;">
                                                    <span class="floar-right">{{$comment->isEdited == 0 ? Carbon\Carbon::parse($comment->created_at)->isoFormat('MMMM Do YYYY, h:mm:ss a')  :"Updated at ". Carbon\Carbon::parse($comment->created_at)->isoFormat('MMMM Do YYYY, h:mm:ss a') }}</span>
                                                </div>
                                                <div class="col-md-1 col-sm-1">
                                                <a  data-id={{$comment->id}} type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                                                    <i class="fa fa-bars"></i>
                                                </a>
                                                 <div class="dropdown-menu " aria-labelledby="navbarDropdown3">
                                                     <li><a  id ="edit" class="dropdown-item commentdata editComment" data-type={{$type}} data-id= "{{$comment->id}}">Edit</a></li>
                                                     <li><a id="delete" class="dropdown-item deletecom "  data-toggle="modal" data-target="#deletecomment{{$comment->id}}">Delete</a></li>
                                                </div>
                                                </div>
                                                @else
                                                 <div class="text-muted col-md-2" style="font-size:10px;">
                                                    <span class="floar-right">{{$comment->isEdited == 0 ? Carbon\Carbon::parse($comment->created_at)->isoFormat('MMMM Do YYYY, h:mm:ss a')  :"Updated at ". Carbon\Carbon::parse($comment->created_at)->isoFormat('MMMM Do YYYY, h:mm:ss a') }}</span>
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
                                    <textarea class="form-control mb-5 tribute-demo-input mentionClass " contenteditable="true" rows="5" placeholder="Add Comment.." id="commentbody" name = "comment" data-uid = 0></textarea>

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
        
@include('includes.report-modal')
@endsection

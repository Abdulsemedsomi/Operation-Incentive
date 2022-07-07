@extends('layouts.backend')
@section('content')
<div class="content">
     @php
        $pcheckin = App\Projectcheckin::where('projectcheckins.id', $projectc->id)->first();
        $pmember = App\Projectmember::where('position', 'PM')->where('project_id', $projectc->project_id)->first();
        $reportsTo = App\User::find($pmember->user_id);

    @endphp

    <h5>{{ App\User::find($pcheckin->user_id)->fname . " " . App\User::find($pcheckin->user_id)->lname}}'s Answer to</h5>
    <h2>Project check-in</h2>
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
        <div class="block text-center">
            <div class="card-body text-left">
                <p class="card-text" style="font-weight:bold; padding-top:0.5rem;">Selam <a href="" style="text-decoration: aqua">{{$reportsTo?$reportsTo->fname . " " . $reportsTo->lname:"Manager"}}</a></p>
                    <div class="block-content">
                       {!!$pcheckin->checkin!!}
                    </div>
                <!--<div class="block-content ">-->
                <!--    <div class="container mt-5 mx-5 my-5 col-md-12">-->
                <!--       <div  class="row pull-right">-->
                <!--            <button class="btn btn-lg btn-circle btn-alt-success nb mr-5 mb-5" data-toggle="modal" data-target="#modal-large-app"><i class="si si-emoticon-smile" aria-role="presentation" aria-label="Appreciation"></i></button>-->
                <!--            <button class="btn btn-lg btn-circle btn-alt-info nb mr-5 mb-5"  data-id="{{$projectc->id}}" data-type="project" id="addNeutralComment"><i class="fa fa-hand-peace-o" aria-role="presentation" aria-label="Noted"></i></button>-->
                <!--            <button class="btn btn-lg btn-circle btn-alt-danger nb mr-5 mb-5" data-toggle="modal" data-target="#modal-large"><i class="fa fa-bomb" aria-role="presentation" aria-label="POUTING FACE"></i></button>-->
                <!--        </div>-->
                <!--    </div>-->
                <!--</div>-->
            </div>
        </div>



        <div class="bg-gray-lighter">
            <div class="content content-full">
                <div class="row justify-content-left py-10">
                    <div class="col-lg-12">
                        <h3 class="font-w700 mb-10">Comments</h3>
                        <div class="commentarea">
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
                                                     <li><a  id ="edit" class="dropdown-item commentdata editComment"  data-id= "{{$comment->id}}">Edit</a></li>
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
                   
                    <div class="media mb-30 col-md-12">
                        <div class="media-body">
                           <form action="{{route('comments.store')}}" method="post" >
                                    @csrf
                                    <textarea class="form-control mb-5 tribute-demo-input mentionClass " contenteditable="true" rows="5" placeholder="Add Comment.." id="commentbody" name = "comment" data-uid = 0></textarea>

                                    <input type="hidden" name="project_id" value="{{$pcheckin->id}}">
                                    <button type="submit" class="btn btn-secondary">
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
   @include('includes.projectcheckin-modal')
 
  <!--<script>-->
  <!--  tinymce.init({-->
  <!--    selector: 'textarea',-->
  <!--    branding: false,-->
  <!--    height: 300,-->
  <!--    statusbar: false,-->
  <!--    plugins: 'lists',-->
  <!--    toolbar: "formatselect | bold italic backcolor forecolor| alignleft aligncenter alignright alignjustify | bullist numlist | '",-->
  <!--    menubar: 'false'-->
  <!--    });-->
  <!--</script>  -->
   
@endsection

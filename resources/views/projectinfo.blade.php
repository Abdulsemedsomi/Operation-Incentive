@extends('layouts.backend')
<link href="https://gitcdn.github.io/bootstrap-toggle/2.2.2/css/bootstrap-toggle.min.css" rel="stylesheet">

@section('content')
<div class="container" id="pcontainer">
    <div class="mt-50 mb-10 text-center">
        <h2 class="font-w700 mb-10">{{$project->project_name}}</h2>
        <hr>
    </div>
    <div class="row">
        <div class="col-md-5">
            <div class="block">
                <div class="block-header block-header-default bg-gd-sea">
                    <h3 class="block-title text-white">Project Members</h3>
                     @if(Gate::any(['fillengageproject', 'fillkpiproject']) && $project->status == 1)
                    <div class="block-options">
                        <button type="button" class="btn-block-option js-tooltip-enabled text-white" data-toggle="modal" data-target="#addmembers">
                            <i class="si si-plus"></i>
                        </button>
                        
                    </div>
                    @endif
                </div>
                <div class="block-content example-2 scrollbar-ripe-malinka" style="height: 700px">
                    <ul style="list-style: none;">
                        @foreach($projectmembers as $pm)
                        
                       <li class="mt-20 row"> 
                           <div class="col-md-2">
                                @if($pm->avatar == null)
                                    <div class="avatar-circle2" style="width:35px; height:35px; border-radius:50%; background-color: #{{$pm->avatarcolor}} !important;">
                                        <span class="pinitials1" >{{$pm->fname[0] . $pm->lname[0]}}</span>
                                    </div>
                                @else
                                        <img class="" src="https://ienetworks.co/pms/uploads/avatars/{{ $pm->avatar }}" style="width:35px; height:35px; border-radius:50%;">
                                @endif
                           </div>
                            <p class="col-md-6 mt-10">{{$pm->fname . " " . $pm->lname}} </p> 
                            <div class="col-md-2 ">
                                <a class="badge bg-gd-sea text-white mt-10">{{$pm->position}}</a>
                            </div>
                            <div class="col-md-1 mt-10">
                                    @if(Gate::any(['fillengageproject', 'fillkpiproject']) && $project->status == 1)
                                        <button class="btn btn-sm btn-alt-secondary " onclick="launchedit({{$pm->id}})"><i class="si si-pencil"></i></button> &nbsp;
                                       
                                    @endif
                            </div>
                        </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
        <div class="col-md-7">
            <div class="block">
                <div class="block-header block-header-default bg-gd-sea">
                    <h3 class="block-title text-white">Project Details</h3>
                </div>
                <div class="block-content">
                <div class="col-md-8" id="fmessage">
                    </div></div>
                     
                <div class="block-content">
                     @if(Gate::any(['fillengageproject', 'fillkpiproject']) && $project->status == 1)
                    <div class="form-group row">
                        <label class="col-12" for="example-text-input">Project Name</label>
                        <div class="col-md-9">
                            <input type="text" class="form-control round" id="projectname" name="projectname" placeholder="Project Name" value="{{$project->project_name}}">
                        </div>
                       
                    </div>
                    <div class="form-group row">
                        <label class="col-12" for="example-text-input">Project size before VAT</label>
                        <div class="col-md-8">
                            <input type="number" class="form-control round" id="projectsize" name="projectsize" placeholder="Project Size" value="{{$project->amount}}">
                        </div>
                        <div class="col-md-3">
                            <select id="currencytype" class="form-control round">
                                <option value=0 {{$project->currency == 0? 'selected' : ""}}>ETB</option>
                                <option value=1 {{$project->currency == 1? 'selected' : ""}}>USD</option>
                            </select>
                            
                        </div>
                        
                    </div>
                    <div class="form-group row col-md-5">
                    <button id="updatesize" class="btn btn-rounded btn-outline-info "  onclick="updateinfo( {{$project->id}} )" >Update</button>
                    </div>
                    @else
                     <div class="form-group row">
                        <label class="col-12" for="example-text-input">Project Name</label>
                        <div class="col-md-9">
                            <input type="text" class="form-control round" id="projectname" name="projectname" placeholder="Project Name" value="{{$project->project_name}}" disabled>
                        </div>
                       
                    </div>
                    <div class="form-group row">
                        <label class="col-12" for="example-text-input">Project size</label>
                        <div class="col-md-8">
                            <input type="number" class="form-control round" id="projectsize" name="projectsize" placeholder="Project Size" value="{{$project->amount}}" disabled>
                        </div>
                        <div class="col-md-3">
                            <select id="currencytype" class="form-control round" disabled>
                                <option value=0 {{$project->currency == 0? 'selected' : ""}}>ETB</option>
                                <option value=1 {{$project->currency == 1? 'selected' : ""}}>USD</option>
                            </select>
                            
                        </div>
                        
                    </div>
                   
                    @endif
                </div>
                <div class="block-header block-header-default bg-gd-sea">
                    <h3 class="block-title text-white">Project Delivery Milestones</h3>
                     @if(Gate::any(['fillengageproject', 'fillkpiproject']) && $project->status == 1)
                    <div class="block-options">
                        {{-- This add button is not working i could't figure out the ajax for it --}}
                        <button type="button" class="btn-block-option js-tooltip-enabled text-white" data-toggle="modal" data-target="#adddelivery">
                            <i class="si si-plus"></i>
                        </button>
                    </div>
                     @endif
                </div>
                <div class="block-content example-2 scrollbar-ripe-malinka">
                    <ol class="font-w300">
                        @php
                           $projectdeliveries = App\Projectdelivery::where('project_id', $project->id)->get();
                           @endphp
                            @foreach($projectdeliveries as $pd)
                           <li>
                        <div class="row">
                            <div class="col-md-5">
                               
                                  {{$pd->milestone_name}}
                            </div>
                            <div class="col-md-3">
                              {{$pd->amount}} {{$pd->currency == 0? "ETB" : "USD"}}
                            </div>
                          
                            <div class="col-md-3">
                                <p class="text-success font-w300" {{$pd->status == 1 ? " ": "hidden"}}>Delivered</p>
                                <p class="text-danger font-w300" {{$pd->status == 0 ? " ": "hidden"}}>Not Delivered</p>
                            </div>
                          
                        </div>
                        </li>
                          @endforeach
                    </ul>
                </div>
            </div>
        </div>
        @if($project->status == 0 || Auth::user()->id == 170)
            <div class="col-md-12">
                <div class="block">
                    <div class="block-header block-header-default bg-gd-sea">
                        <h3 class="block-title text-white">Project Participation</h3>
                    </div>
                    <form action ="{{route('setparticipant', $project->id )}}" method="post">
                        @csrf
                        
                    <div class="block-content">
                        
                        <table class="table table-vcenter">
                            <thead>
                                <tr>
                                    <th class="text-center" style="width: 50px;">#</th>
                                    <th>Project title</th>
                                    <th>Particiation</th>
                                </tr>
                            </thead>
                            
                            <tbody>
                                 @php
                                    $count = 1;
                                @endphp
                                @foreach($projectmembers as $pm)
                                <tr>
                                    <input type="hidden" name="user_id{{$pm->id}}" value="{{$pm->user_id}}">
                                    <th class="text-center" scope="row">{{$count++}}</th>
                                    <td>{{$pm->fname . " " . $pm->lname}}</td>
                                    <td>
                                       
                                        <div class="col-12">
                                            <select class="form-control round" id="example-select{{$pm->id}}" name="participation{{$pm->id}}" required="">
                                            <option value="0" disabled selected>Please select</option>
                                            
                                            <option value="1" {{$pm->level == 1? "selected": "" }}>Participant</option>
                                            <option value="2" {{$pm->level == 2? "selected": "" }}>Half cycle participant</option>
                                            <option value="3" {{$pm->level == 3? "selected": "" }}>Full cycle participant</option>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                            
                        </table>
                        <div class="row mb-20">
                            <div class="col-md-10">
                                
                            </div>
                            <div class="col-md-2">
                                <button type="submit" class="btn btn-rounded btn-outline-info" >Update</button>
                            </div
                        </div>
                        
                    </div>
                </div>
                </form>
            </div>
</div>
@endif
{{-- Add Members Modal --}}
<div class="modal" id="addmembers" tabindex="-1" role="dialog" aria-labelledby="modal-large" aria-hidden="true">
    <div class="modal-dialog " role="document">
        <div class="modal-content">
            <form  action = "{{route('storemember')}}"   method="post">
                @csrf
                <input type="hidden" name="projectid" value="{{$project->id}}" />
            <div class="block block-themed block-transparent mb-0">
                <div class="block-header bg-primary-info">
                    <h3 class="block-title">Add Members</h3>
                    <div class="block-options">
                        <button type="button" class="btn-block-option" data-dismiss="modal" aria-label="Close">
                            <i class="si si-close"></i>
                        </button>
                    </div>
                </div>
                <div class="block-content">
                    <div class="form-group row">
                        <div class="col-md-7">
                         
                             <select class="form-control round" id="userinproject" name="userinproject">
                                <option disabled>Please select</option>
                                @php
                                    $users = App\User::where('active', 1)->get();
                                @endphp
                                @foreach($users as $user)
                                    <option value="{{$user->id}}">{{$user-> fname. " " . $user->lname}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <select class="form-control round" id="mposition" name="mposition">
                                <option disabled>Please select</option>
                                @php
                                    $positions = App\Projectmember::select('position')->distinct()->get();
                                @endphp
                                @foreach($positions as $pos)
                                    <option value="{{$pos->position}}">{{$pos-> position}}</option>
                                @endforeach
                            </select>
                        </div>
                        
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-alt-success" >
                    <i class="fa fa-plus"></i> Add
                </button>
                <button type="button" class="btn btn-alt-secondary" data-dismiss="modal">Close</button>
            </div>
             </form>
        </div>
    </div>
</div>

<!--Edit member-->
<div class="modal" id="editmembers" tabindex="-1" role="dialog" aria-labelledby="modal-large" aria-hidden="true">
    <div class="modal-dialog " role="document">
        <div class="modal-content">
            <form id="editmemberform" action = ""   method="post">
                @csrf
                <input type="hidden" name="_method" value="put" />
            <div class="block block-themed block-transparent mb-0">
                <div class="block-header bg-primary-info">
                    <h3 class="block-title">Edit Member info</h3>
                    <div class="block-options">
                        <button type="button" class="btn-block-option" data-dismiss="modal" aria-label="Close">
                            <i class="si si-close"></i>
                        </button>
                    </div>
                </div>
                <div class="block-content">
                    <div class="form-group row">
                        <div class="col-md-7">
                         
                            <input type="text" class="form-control round" id="membername" name="membername" disabled value=0>
                            
                        </div>
                        <div class="col-md-4">
                            <select class="form-control round" id="memberposition" name="memberposition">
                                <option disabled>Please select</option>
                                @php
                                    $positions = App\Projectmember::select('position')->distinct()->get();
                                @endphp
                                @foreach($positions as $pos)
                                    <option >{{$pos-> position}}</option>
                                @endforeach
                            </select>
                        </div>
                        
                    </div>
                      <a type="button" class="btn btn-rounded btn-outline-danger mr-5 mb-5 mt-5" href="" id="removemember">
                                <i class="fa fa-plus mr-5"></i>Remove user 
                    </a>
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-alt-success" >
                    <i class="fa fa-plus"></i> Update
                </button>
                <button type="button" class="btn btn-alt-secondary" data-dismiss="modal">Close</button>
            </div>
             </form>
        </div>
    </div>
</div>

<div class="modal" id="adddelivery" tabindex="-1" role="dialog" aria-labelledby="modal-large" aria-hidden="true">
    <div class="modal-dialog " role="document">
        <div class="modal-content">
            <form  action = "{{route('storedelivery')}}"   method="post">
                @csrf
                <input type="hidden" name="projectid" value="{{$project->id}}" />
            <div class="block block-themed block-transparent mb-0">
                <div class="block-header bg-primary-info">
                    <h3 class="block-title">Add Project Delivery Milestone</h3>
                    <div class="block-options">
                        <button type="button" class="btn-block-option" data-dismiss="modal" aria-label="Close">
                            <i class="si si-close"></i>
                        </button>
                    </div>
                </div>
                <div class="block-content">
                    <div class="form-group row">
                        
                            <label class="col-md-5">Delivery Milestone Name</label>
                           <input type="text" class="form-control round col-md-7" id="milestone_name" name="milestone_name"  placeholder="Title">
                             
                        
                    </div>
                    <div class="form-group row">
                         <label class="col-md-3">Milestone Value</label>
                        <div class="col-md-5">
                            <input type="number" step=0.01 class="form-control round" id="poamount" name="amount" placeholder="Project Size" value="0">
                        </div>
                         <div class="col-md-3">
                            <select id="dcurrencytype" name="dcurrencytype" class="form-control round">
                                <option value=1 >USD</option>
                                <option value=0 >ETB</option>
                            </select>
                            
                        </div>
                    </div>
                       <div class="form-group row">
                        <label class="col-md-3">Status</label>
                       
                            <select class="form-control round col-md-4" id="dstatus" name="dstatus">
                                <option value="0">Not delivered</option>
                                <option value="1">Delivered</option>
                              
                            </select>
                       
                        
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-alt-success" >
                    <i class="fa fa-plus"></i> Add
                </button>
                <button type="button" class="btn btn-alt-secondary" data-dismiss="modal">Close</button>
            </div>
             </form>
        </div>
    </div>
</div>
<script src="https://gitcdn.github.io/bootstrap-toggle/2.2.2/js/bootstrap-toggle.min.js"></script>
<script>
  var baseurl = window.location.protocol + "//" + window.location.host + '/pms/';
function updateinfo(id){
    // Adding timestamp to set cache false
     $.ajaxSetup({
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
        });

        var formData = {
            id: id,
            project_name: document.getElementById('projectname').value,
            amount: document.getElementById('projectsize').value,
            currency: document.getElementById('currencytype').value
               
        };

        var type = "POST";

        var ajaxurl = baseurl + "projectupdate";

        $.ajax({
            type: type,
            url: ajaxurl,
            data: formData,
            dataType: "json",
          
            success: function(data) {
             document.getElementById('projectname').value = data.project_name;
             document.getElementById('projectsize').value = data.amount;
            
                 document.getElementById('currencytype').value = data.currency
             
             document.getElementById('currencytype')
             var el = '<div class="alert alert-success alert-block"><button type="button" class="close" data-dismiss="alert">×</button><strong>Project info updated successfully</strong></div>'
             document.getElementById('fmessage').innerHTML = el;
            },
            error: function(data) {
                console.log("Error:", data.responseText);
                var el = '<div class="alert alert-danger alert-block"><button type="button" class="close" data-dismiss="alert">×</button><strong>Error! Please try again</strong></div>'
             document.getElementById('fmessage').innerHTML = el;
            },
        });      
}
function launchedit(id){
    
   $.get(baseurl + "myprojects/" + id, function(data) {
       document.getElementById('membername').value = data.fname + " " + data.lname;
       document.getElementById('memberposition').value = data.position
       document.getElementById('editmemberform').action = baseurl + 'myprojects/' + data.id;
        document.getElementById('removemember').href = baseurl + 'myprojects/delete/' + data.id;
      
       jQuery('#editmembers').modal("show");
   });
    
}
function updatemember(id){
    // Adding timestamp to set cache false
     $.ajaxSetup({
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
        });

        var formData = {
            id: id,
            project_name: document.getElementById('projectname').value,
            amount: document.getElementById('projectsize').value,
            currency: document.getElementById('currencytype').value
               
        };

        var type = "POST";

        var ajaxurl = baseurl + "projectupdate";

        $.ajax({
            type: type,
            url: ajaxurl,
            data: formData,
            dataType: "json",
          
            success: function(data) {
             document.getElementById('projectname').value = data.project_name;
             document.getElementById('projectsize').value = data.amount;
            
                 document.getElementById('currencytype').value = data.currency
             
             document.getElementById('currencytype')
             var el = '<div class="alert alert-success alert-block"><button type="button" class="close" data-dismiss="alert">×</button><strong>Project info updated successfully</strong></div>'
             document.getElementById('fmessage').innerHTML = el;
            },
            error: function(data) {
                console.log("Error:", data.responseText);
                var el = '<div class="alert alert-danger alert-block"><button type="button" class="close" data-dismiss="alert">×</button><strong>Error! Please try again</strong></div>'
             document.getElementById('fmessage').innerHTML = el;
            },
        });      
}



</script>
@endsection

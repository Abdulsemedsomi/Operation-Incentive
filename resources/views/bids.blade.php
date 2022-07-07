@extends('layouts.backend')
@section('content')
<div class="container">
    <div class="mt-50 mb-10 text-center">
        <h2 class="font-w700 text-black mb-10">Bids: {{$bid->bid_name}}</h2>
        <hr>
    </div>

    <div class="row gutters-tiny">
        <div class="col-md-6">
            <div class="block block-rounded">
                <div class="block-header block-header-default bg-gd-leaf">
                    <h3 class="block-title text-white">Bid</h3>
                </div>
                <div class="block-content">
                     <div class="form-group row">
                        <label class="col-12" for="example-text-input">Bid Name</label>
                        <div class="col-md-9">
                            <input type="text" class="form-control round" value="{{$bid->bid_name}}" id="projectname" name="projectname" placeholder="Bid Name">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-12" for="example-text-input">Signed contrat size before VAT</label>
                        <div class="col-md-7">
                            <input type="number" class="form-control round" id="projectsize" value="{{$bid->bid_amount}}" name="projectsize" placeholder="Bid size">
                        </div>
                    </div>
                    
                    <button type="submit" class="btn btn-rounded btn-outline-info mb-10">Update</button>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="block">
                <div class="block-header block-header-default bg-gd-leaf">
                    <h3 class="block-title text-white">Bid Members</h3>
                    <div class="block-options">
                        <button type="button" class="btn-block-option js-tooltip-enabled text-white" data-toggle="modal" data-target="#addmembers">
                            <i class="si si-plus"></i>
                        </button>
                    </div>
                </div>
                <div class="block-content example-2 scrollbar-ripe-malinka" style="height: 630px">
                    <ul style="list-style: none;">
                        @foreach($bidmembers as $pm)
                        @php
                        $message = "Participant";
                        if($pm->level == 2) {
                            $message = "Half cycle participant";
                        }
                        else if($pm->level == 3){
                            $message = "Full cycle participant";
                        }
                        @endphp
                       <li class="mt-20 row"> 
                           <div class="col-md-6 row ">
                               <div class="col-md-2">
                                @if($pm->avatar == null)
                                    <div class="avatar-circle2 " style="width:35px; height:35px; border-radius:50%; background-color: #{{$pm->avatarcolor}} !important;">
                                        <span class="pinitials1" >{{$pm->fname[0] . $pm->lname[0]}}</span>
                                    </div>
                                @else
                                        <img class="" src="https://ienetworks.co/pms/uploads/avatars/{{ $pm->avatar }}" style="width:35px; height:35px; border-radius:50%;">
                                @endif
                                </div>
                                 <p class="col-md-8 mt-10">{{$pm->fname . " " . $pm->lname}} </p> 
                                 <div class="col-md-2">
                                <a class="badge bg-gd-sea text-white mt-10">{{$pm->position}}</a>
                            </div>
                           </div>
                              <div class="col-md-5 mt-10">
                                <label >{{$message}}</label>
                            </div>
                            <div class="col-md-1 mt-10">
                                    @if(Gate::any(['fillengageproject', 'fillkpiproject']))
                                        <button class="btn btn-sm btn-alt-secondary " onclick="launchedit({{$pm->id}})"><i class="si si-pencil"></i></button> &nbsp;
                                        
                                       
                                    @endif
                            </div>
                        </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>
    @if(!$project)
     <div class="col-md-12">
                <div class="block">
                    <div class="block-header block-header-default bg-gd-sea">
                        <h3 class="block-title text-white">Create Project</h3>
                    </div>
                    <form action ="{{route('createproject')}}" method="post">
                        @csrf
                    <div class="block-content">
                         <label  >Project name: {{$bid->bid_name}}</label>
                        <table class="table table-vcenter">
                            <thead>
                                <tr>
                                  
                                    <th>Member name</th>
                                    <th>Title</th>
                                </tr>
                            </thead>
                            
                            <tbody>
                                
                                <tr>
                                    <input type="hidden" name="bid_id" value="{{$bid->id}}">
                                   
                                    <td>
                                      <select class="form-control round" id="userinproject" name="userinproject" required>
                                        <option disabled selected value="">Please select user</option>
                                        @php
                                            $users = App\User::where('active', 1)->orderby('fname', 'asc')->get();
                                        @endphp
                                        @foreach($users as $user)
                                            <option value="{{$user->id}}">{{$user-> fname. " " . $user->lname}}</option>
                                        @endforeach
                                    </select>
                                    </td>
                                    <td>
                                       
                                        <div class="col-12">
                                            <select class="form-control round" id="pposition" name="pposition" required>
                                           
                                                <option disabled selected value="">Please select position</option>
                                                @php
                                                    $positions = App\Projectmember::select('position')->distinct()->get();
                                                @endphp
                                                @foreach($positions as $pos)
                                                    <option value="{{$pos->position}}">{{$pos-> position}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </td>
                                </tr>
                               
                            </tbody>
                            
                        </table>
                        <div class="row mb-20">
                            <div class="col-md-10">
                                
                            </div>
                            <div class="col-md-2">
                                <button type="submit" class="btn btn-rounded btn-outline-info" >create</button>
                            </div
                        </div>
                        
                    </div>
                </div>
                </form>
            </div>
</div>
@endif
</div>



{{-- Add Members Modal --}}
<div class="modal" id="addmembers" tabindex="-1" role="dialog" aria-labelledby="modal-large" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
             <form  action = "{{route('storebidmember')}}"   method="post">
                @csrf
                <input type="hidden" name="bid_id" value="{{$bid->id}}" />
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
                        <div class="col-md-6">
                         
                             <select class="form-control round" id="userinbid" name="userinbid" required>
                                <option disabled selected value="">Please select user</option>
                                @php
                                    $users = App\User::where('active', 1)->orderby('fname', 'asc')->get();
                                @endphp
                                @foreach($users as $user)
                                    <option value="{{$user->id}}">{{$user-> fname. " " . $user->lname}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <select class="form-control round" id="mposition" name="bposition" required>
                                <option disabled selected value="">Select position</option>
                              
                                    <option value="HoS">HoS</option>
                                     <option value="PSE">PSE</option>
                                      <option value="AE">AE</option>
                                      <option value="SA">SA</option>
                                      <option value="STL">STL</option>
                            </select>
                        </div>
                         <div class="col-md-3">
                        <select class="form-control round" id="participation" name="level" required>
                                            <option disabled selected value="">Select Participation level</option>
                                            <option value="1" >Participant</option>
                                            <option value="2">Half cycle</option>
                                            <option value="3">Full cycle</option>
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
                        <div class="col-md-6">
                         
                             <select class="form-control round" id="euserinbid" name="userinbid" disabled>
                                <option disabled selected>Please select user</option>
                                @php
                                    $users = App\User::where('active', 1)->orderby('fname', 'asc')->get();
                                @endphp
                                @foreach($users as $user)
                                    <option value="{{$user->id}}">{{$user-> fname. " " . $user->lname}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <select class="form-control round" id="ebposition" name="bposition" required>
                                <option disabled selected>Select position</option>
                              
                                    <option value="HoS">HoS</option>
                                     <option value="PSE">PSE</option>
                                      <option value="AE">AE</option>
                                      <option value="SA">SA</option>
                                      <option value="STL">STL</option>
                            </select>
                        </div>
                         <div class="col-md-3">
                        <select class="form-control round" id="eparticipation" name="level" required>
                                            <option value="0" disabled selected>Select Participation level</option>
                                            <option value="1" >Participant</option>
                                            <option value="2">Half cycle</option>
                                            <option value="3">Full cycle</option>
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
<script>
 var baseurl = window.location.protocol + "//" + window.location.host + '/pms/';
    function launchedit(id){
    
   $.get(baseurl + "showbidmembers/" + id, function(data) {
       document.getElementById('euserinbid').value = data.user_id;
       document.getElementById('ebposition').value = data.position
        document.getElementById('eparticipation').value = data.level
       document.getElementById('editmemberform').action = baseurl + 'bids/' + data.id;
        document.getElementById('removemember').href = baseurl + 'bids/delete/' + data.id;
      
       jQuery('#editmembers').modal("show");
   });
    
}
</script>
@endsection

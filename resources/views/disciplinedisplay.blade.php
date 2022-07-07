@extends('layouts.backend')

@section('content')
<div class="container">
    <div class="mt-50 mb-10 text-center">
        <h2 class="font-w700 text-black mb-10">Engagement Display</h2>
    </div>
    <div class="block block-rounded">
        <div class="block-content bg-gd-sea">
         
            <form action="{{route('changeengagementReport')}}" method="post">
                @csrf
                <div class="form-group row">
                    <label class="col-12 text-white" for="example-select">Session</label>
                    <div class="col-md-5">
                        <select class="form-control" id="sessionselect" name="session">
                             @php
                                $sessions = App\Session::where('isNeeded', 1)->get();
                                @endphp
                                <option value="0" disabled>Please select Session</option>
                                @foreach($sessions as $s)
                                    <option value='{{$s->id}}' {{$s->id == $session->id?'selected':""}}>{{$s->session_name}}</option>
                                @endforeach
                        </select>
                    </div>
                    <button type="submit" class="btn btn-rounded btn-outline btn-alt-info ml-5">Select</button>
                </div>
            </form>
        </div>
    </div>
    <ul class="nav nav-tabs md-tabs" id="myTabMD" role="tablist">
             @if($received->count() > 0)
            <li class="nav-item">
                <a class="nav-link active" id="home-tab-md" data-toggle="tab" href="#home-md" role="tab" aria-controls="home-md"
                aria-selected="true">Received</a>
            </li>
              @endif
             @if($sent->count() > 0)
            <li class="nav-item">
                <a class="nav-link {{$received->count() == 0 ? 'active':''}}" id="profile-tab-md" data-toggle="tab" href="#profile-md" role="tab" aria-controls="profile-md"
                aria-selected="{{$received->count() == 0 ? 'true':'false'}}">Sent</a>
            </li>
            @endif
            @if(Gate::any('crud') || App\Role_user::where('user_id', Auth::user()->id)->where('role_id', 13)->first())
            <li class="nav-item">
                <a class="nav-link {{$received->count() == 0  && $sent->count() == 0 ? 'active':''}}" id="contact-tab-md" data-toggle="tab" href="#contact-md" role="tab" aria-controls="contact-md"
                aria-selected="{{$received->count() == 0  && $sent->count() == 0 ? 'true':'false'}}">All</a>
            </li>
            @endif
             @if($leaves->count() > 0 || $attendance->count() >0)
            <li class="nav-item">
                <a class="nav-link {{$received->count() == 0 && $sent->count() == 0 && !Gate::any('crud') ? 'active':''}}" id="myleave-tab-md" data-toggle="tab" href="#myleave-md" role="tab" aria-controls="myleave-md"
                aria-selected="{{$received->count() == 0 && $sent->count() == 0 && !Gate::any('crud') ? 'true':'false'}}">Attendance Data</a>
            </li>
            @endif
             @if(Auth::user()->position != "CEO" && $session->id !=9)
            <li class="nav-item">
                <a class="nav-link {{$received->count() == 0 && $sent->count() == 0 && !Gate::any('crud') && $leaves->count() == 0 ? 'active':''}}" id="myscore-tab-md" data-toggle="tab" href="#myscore-md" role="tab" aria-controls="myscore-md"
                aria-selected="{{$received->count() == 0 && $sent->count() == 0 && !Gate::any('crud') && $leaves->count() == 0 ? 'true':'false'}}">My Score</a>
            </li>
            @endif
        </ul>
    <div class="tab-content pt-5" id="myTabContentMD">
        @if($received->count() > 0)
        <div class="tab-pane fade show active" id="home-md" role="tabpanel" aria-labelledby="home-tab-md">
            <div class="block">
                <div class="block-header block-header-default">
                    <h3 class="block-title">Engagement Actions</h3>
                </div>
                <div class="block-content">
                    <table class="table table-hover table-vcenter" id="received">
                        <thead>
                            <tr>
                                <th class="text-center" style="width: 50px;">#</th>
                                <th>Employee</th>
                                <th>Issuer</th>
                                <th class="d-none d-sm-table-cell" style="width: 15%;">Type</th>
                                <th>Category</th>
                                <th>From</th>
                                <th>Issue date</th>
                                <th class="text-center" style="width: 100px;">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $count=1;
                            @endphp
                            @foreach($received as $rf)
                                <tr>
                                    <th class="text-center" scope="row">{{$count++}}</th>
                                    <td>{{App\User::find($rf->issued_to)->fname . " " . App\User::find($rf->issued_to)->lname}}</td>
                                    <td>{{App\User::find($rf->issuer)->fname . " " . App\User::find($rf->issuer)->lname}}</td>
                                    @if(App\Engagement::find($rf->engagement_id)->Perspective == 1)
                                        <td class="d-none d-sm-table-cell">
                                            <span class="badge badge-danger">Reprimand</span>
                                        </td>
                                    @else
                                        <td class="d-none d-sm-table-cell">
                                            <span class="badge badge-success">Appreciation</span>
                                        </td>
                                    @endif
                                    <td>{{App\Engagement::find($rf->engagement_id)->Objective}}</td>
                                    <td>{{$rf->report_id == null ? ($rf->type==1 ?"Payroll":"Project") : "Report"}}</td>
                                    <td>{{ Carbon\Carbon::parse($rf->created_at)->format('M d Y')}}</td>

                                    <td class="text-center">
                                        <div class="btn-group">
                                       <a type="button" class="btn btn-sm btn-secondary" data-toggle="tooltip" title="Download" href = "{{ route('downloadereport', $rf->id) }}">
                                            <i class="fa fa-download"></i>
                                        </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        @endif
          @if($sent->count() > 0)
        <div class="tab-pane fade {{$received->count() == 0 ? 'show active':''}}" id="profile-md" role="tabpanel" aria-labelledby="profile-tab-md">
            <div class="block">
                <div class="block-header block-header-default">
                    <h3 class="block-title">Engagement Actions</h3>
                </div>
                <div class="block-content">
                    <table class="table table-hover table-vcenter" id="sent">
                        <thead>
                            <tr>
                                <th class="text-center" style="width: 50px;">#</th>
                                <th>Employee</th>
                                <th>Issuer</th>
                                <th class="d-none d-sm-table-cell" style="width: 15%;">Type</th>
                                <th>Category</th>
                                <th>From</th>
                                <th>Issue date</th>
                                <th class="text-center" style="width: 100px;">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $count=1;
                            @endphp
                            @foreach($sent as $rf)
                                <tr>
                                    <th class="text-center" scope="row">{{$count++}}</th>
                                    <td>{{App\User::find($rf->issued_to)->fname . " " . App\User::find($rf->issued_to)->lname}}</td>
                                    <td>{{App\User::find($rf->issuer)->fname . " " . App\User::find($rf->issuer)->lname}}</td>

                                    @if(App\Engagement::find($rf->engagement_id)->Perspective == 1)
                                        <td class="d-none d-sm-table-cell">
                                            <span class="badge badge-danger">Reprimand</span>
                                        </td>
                                        @else
                                        <td class="d-none d-sm-table-cell">
                                            <span class="badge badge-success">Appreciation</span>
                                        </td>
                                    @endif
    
                                    <td>{{App\Engagement::find($rf->engagement_id)->Objective}}</td>
                                    <td>{{$rf->report_id == null ? "Project" : "Report"}}</td>
                                    <td>{{ Carbon\Carbon::parse($rf->created_at)->format('M d Y')}}</td>

                                    <td class="text-center">
                                        <div class="btn-group">
                                      
                                           <a type="button" class="btn btn-sm btn-secondary" data-toggle="tooltip" title="Download" href = "{{ route('downloadereport', $rf->id) }}">
                                            <i class="fa fa-download"></i>
                                        </a>
                                        
                                        <button type="button" class="btn btn-sm btn-secondary" class="js-swal-confirm btn btn-alt-secondary" onclick="deleteeng({{$rf->id}})">
                                            <i class="fa fa-trash"></i>
                                        </button>
                                        
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        @endif
         @if(Gate::any('crud')|| App\Role_user::where('user_id', Auth::user()->id)->where('role_id', 13)->first())
        <div class="tab-pane fade {{$received->count() == 0 && $sent->count() == 0  ? 'show active':''}}" id="contact-md" role="tabpanel" aria-labelledby="contact-tab-md">
            <div class="block">
                <div class="block-header block-header-default">
                    <h3 class="block-title">Engagement Actions</h3>
                    <div class="text-right mb-10">
                @if(Gate::any(['crud']))
                <button  type="button" class="btn btn-rounded btn-outline-info " data-toggle="modal" data-target="#bulkdownload" >Bulk download</button>
                <button  type="button" class="btn btn-rounded btn-outline-info " data-toggle="modal" data-target="#importleave" >Import leave</button>
                @endif
                
                
            </div>
                </div>
                <div class="block-content">
                    <table class="table table-hover table-vcenter" id="all">
                        <thead>
                            <tr>
                                <th class="text-center" style="width: 50px;">#</th>
                                <th>Employee</th>
                                <th>Issuer</th>
                                <th class="d-none d-sm-table-cell" style="width: 15%;">Type</th>
                                <th>Category</th>
                                <th>From</th>
                                <th>Issue date</th>
                                <th class="text-center" style="width: 100px;">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $count=1;
                            @endphp
                                @foreach($fill_engagements as $rf)
                            <tr>
                            <th class="text-center" scope="row">{{$count++}}</th>


                                    <td>{{App\User::find($rf->issued_to)->fname . " " . App\User::find($rf->issued_to)->lname}}</td>
                                    <td>{{App\User::find($rf->issuer)->fname . " " . App\User::find($rf->issuer)->lname}}</td>

                                    @if(App\Engagement::find($rf->engagement_id)->Perspective == 1)
                                        <td class="d-none d-sm-table-cell">
                                            <span class="badge badge-danger">Reprimand</span>
                                        </td>
                                        @else
                                        <td class="d-none d-sm-table-cell">
                                            <span class="badge badge-success">Appreciation</span>
                                        </td>
                                    @endif

                                    <td>{{App\Engagement::find($rf->engagement_id)->Objective}}</td>
                                    <td>{{$rf->report_id == null ? "Project" : "Report"}}</td>
                                    <td>{{ Carbon\Carbon::parse($rf->created_at)->format('M d Y')}}</td>

                                    <td class="text-center">
                                        <div class="btn-group">
                                        
                                        <a type="button" class="btn btn-sm btn-secondary" data-toggle="tooltip" title="Download" href = "{{ route('downloadereport', $rf->id) }}">
                                            <i class="fa fa-download"></i>
                                        </a>
                                        @if($rf->issuer == Auth::user()->id)
                                         <button type="button" class="btn btn-sm btn-secondary" class="js-swal-confirm btn btn-alt-secondary" onclick="deleteeng({{$rf->id}})">
                                            <i class="fa fa-trash"></i>
                                        </button>
                                        @endif
                                     
                                        </div>
                                    </td>
                                @endforeach
                            </tr>

                        </tbody>
                    </table>
                    
                </div>
            </div>
        </div>
        @endif
          @if($leaves->count()> 0 || $attendance->count() > 0 )
        <div class="tab-pane fade {{$received->count() == 0 && $sent->count() == 0 && !Gate::any('crud') ? 'show active':''}}" id="myleave-md" role="tabpanel" aria-labelledby="myleave-tab-md">
            @if(Gate::any(['crud']))
                <div class="form-group text-right">
               <button  type="button" class="btn btn-rounded btn-outline-info " data-toggle="modal" data-target="#importattendance" >HRMS import</button>
                <button  type="button" class="btn btn-rounded btn-outline-info " data-toggle="modal" data-target="#importleave" >WebHr Import</button>
                </div>
                @endif
            @if($attendance->count() >0)
            <div class="block">
                <div class="block-header block-header-default">
                   <h3 class="block-title">Absent/Tardy</h3>
                    <div class="text-right mb-10">
                
                 
                
            </div>
                </div>
                <div class="block-content">
                    <table class="table table-hover table-vcenter" id="attendance">
                        <thead>
                            <tr>
                                <th class="text-center" style="width: 50px;">#</th>
                                <th>User </th>
                                <th>Type</th>
                                <th>Date</th>
                                
                              
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $count=1;
                            @endphp
                                @foreach($attendance as $a)
                            <tr>
                            <th class="text-center" scope="row">{{$count++}}</th>

                                    <td>{{App\User::find($a->user_id)?App\User::find($a->user_id)->fname." ".App\User::find($a->user_id)->lname: "No name" }}</td>
                                    <td>{{$a->type == 1? "Unplanned leave": "Tardy"}}</td>
                                    <td>{{$a->date}}</td>
                                    
                                  
                               
                            </tr>
                              @endforeach

                        </tbody>
                    </table>
                    
                </div>
            </div>
            @endif
            @if($leaves->count() >0)
            <div class="block">
                <div class="block-header block-header-default">
                   <h3 class="block-title">Leave data</h3>
                    <div class="text-right mb-10">
                
               
                
            </div>
                </div>
                <div class="block-content">
                    <table class="table table-hover table-vcenter" id="leave">
                        <thead>
                            <tr>
                                <th class="text-center" style="width: 50px;">#</th>
                                <th>User </th>
                                <th>Leave type</th>
                                <th>Leave from</th>
                                <th >Leave to</th>
                                <th>Duration</th>
                              
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $count=1;
                            @endphp
                                @foreach($leaves as $leave)
                            <tr>
                            <th class="text-center" scope="row">{{$count++}}</th>

                                    <td>{{App\User::find($leave->user_id)?App\User::find($leave->user_id)->fname." ".App\User::find($leave->user_id)->lname: "No name" }}</td>
                                    <td>{{$leave->leave_type}}</td>
                                    <td>{{$leave->leave_from}}</td>
                                    <td>{{$leave->leave_to}}</td>
                                    <td>{{$leave->duration}}</td>
                                  
                               
                            </tr>
                              @endforeach

                        </tbody>
                    </table>
                    
                </div>
            </div>
            @endif
        </div>
        @endif
         @if(Auth::user()->position != "CEO" && $session->id !=9)
        <div class="tab-pane fade {{$received->count() == 0 && $sent->count() == 0 && !Gate::any('crud') && $leaves->count() == 0 ? 'show active':''}}" id="myscore-md" role="tabpanel" aria-labelledby="myscore-tab-md">
            <div class="block">
                
                 <div class="block-header block-header-default">
                    <h3 class="block-title">Detailed score</h3>
                    <div class="text-right mb-10">
                        <p>{{$userscore?round($userscore->engagementScore, 2): 0 }}%</p>
                    </div>
                </div>
                @php
                $pers = App\Engagement::select('Perspective', 'name')->distinct()->get();
                @endphp
                <div class="block-content">
                      @foreach($pers as $p)
                        <div class="block">
                            <div class="block-header block-header-default bg-gray-dark">
                                <h3 class="block-title text-white" >{{$p->name }}</h3>
                            </div>
                            @php
                                $engagements =  App\Engagement::where('Perspective',$p->Perspective)->get();
                            @endphp
                            <div class="block-content">
                                <table class="table table-bordered table-hover table-vcenter">
                                    <thead>
                                        <tr>
                                            <th>Objective</th>
                                            <th>Measure</th>
                                            <th>Target</th>
                                            <th>Actual</th>
                                            <th>Weight</th>
                                            <th>Formula</th>
                                            <th>Score</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($engagements as $engagement)
                                            @php
                                             $fillengagements = App\FillEngagement::where('engagement_id',$engagement->id)->where('session_id',$session->id)->where('issued_to', Auth::user()->id)->get();
                                             $actual = $fillengagements->count()> 0? $fillengagements->count():0;
                                            @endphp
                                            <tr >
                                                <td>{{$engagement->Objective}}</td>
                                                <td>{{$engagement->Measure}}</td>
                                                <td>{{$engagement->Target}}</td>
                                                <td>{{$actual}}</td>
                                                <td>{{$engagement->Weight}}%</td>
                                                <td>{{App\Formula::find($engagement->formula_id)->formula}}</td>
                                                <td>{{ App\Http\Controllers\ReportsController::executeFormula(App\Formula::find($engagement->formula_id), $engagement->Target, $engagement->Weight, $actual)}}</td>
                                            </tr>
                                         @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
        @endif
    </div>
</div>

<div class="modal" id="bulkdownload" tabindex="-1" role="dialog" aria-labelledby="modal-large" aria-hidden="true">
    <div class="modal-dialog " role="document">
        <div class="modal-content">
           <form method="get" action="{{route('bulkdownload')}}">
              
               
            <div class="block block-themed block-transparent mb-0">
                <div class="block-header bg-primary-info">
                    <h3 class="block-title">Bulk download</h3>
                    <div class="block-options">
                        <button type="button" class="btn-block-option" data-dismiss="modal" aria-label="Close">
                            <i class="si si-close"></i>
                        </button>
                    </div>
                </div>
                <div class="block-content">
               
                        <div class="form-group row">
                            <label for="start_date" class="col-md-4 col-form-label ">From</label>
                            <div class="col-md-6">
                                <input type="date" id="start_date" class="form-control round"  name ="start_date" min ="{{$fill_engagements->count()>0?App\Session::find($fill_engagements[0]->session_id)->start_date:''}}" max="{{$fill_engagements->count()>0?App\Session::find($fill_engagements[0]->session_id)->end_date:''}}"  value="{{$fill_engagements->count()>0?App\Session::find($fill_engagements[0]->session_id)->start_date:''}}" required>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="end_date" class="col-md-4 col-form-label">To</label>
                            <div class="col-md-6">
                                <input type="date" id="eenddate" class="form-control round" name="end_date" value="{{$fill_engagements->count()>0?App\Session::find($fill_engagements[0]->session_id)->end_date:''}}" min ="{{$fill_engagements->count()>0?App\Session::find($fill_engagements[0]->session_id)->start_date:''}}" max="{{$fill_engagements->count()>0?App\Session::find($fill_engagements[0]->session_id)->end_date:''}}" required>
                            </div>
                        </div>
                        <!--<div class="form-group row">-->
                           
                        <!--    <div class="col-md-10">-->
                        <!--        <div class="form-check form-check-inline">-->
                        <!--            <input class="form-check-input" type="radio" name="type" id="inlineRadio1" value="1" checked >-->
                        <!--            <label class="form-check-label" for="inlineRadio1">Compiled Single file</label>-->
                        <!--        </div>-->
                        <!--        <div class="form-check form-check-inline">-->
                        <!--            <input class="form-check-input" type="radio" name="type" id="inlineRadio2" value="2">-->
                        <!--            <label class="form-check-label" for="inlineRadio2">Zip file</label>-->
                        <!--        </div>-->
                                
                        <!--    </div>-->
                        <!--</div>-->
                  
                     
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-alt-primary" disabled >Download</button>
                <button type="button" class="btn btn-alt-secondary" data-dismiss="modal">Close</button>
            </div>
             </form>
        </div>
    </div>
</div>

<div class="modal " id="deleteEngagement" tabindex="-1" role="dialog" aria-labelledby="modal-large" aria-hidden="true">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <form  method="post" id="deleteegnform">
                @csrf
                  
                <div class="block block-themed block-transparent mb-0">
                    <div class="block-header bg-danger">
                        <h3 class="block-title">Delete Engagement</h3>
                        <div class="block-options">
                            <button type="button" class="btn-block-option" data-dismiss="modal" aria-label="Close">
                                <i class="si si-close"></i>
                            </button>
                        </div>
                    </div>
                    <div class="block-content">
                        <div class="form-group row">
                            <div class="modal-body">
                                <label for="inputLink" class=" control-label col-md-push-1" >Are you sure you want to delete?</label>
                            </div>
                        </div>


                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-alt-danger " >
                        Delete
                    </button>
                    <button type="button" class="btn btn-alt-secondary" data-dismiss="modal">Close</button>
                </div>
            </form>
        </div>
    </div>
</div>
<div class="modal " id="importleave" tabindex="-1" role="dialog" aria-labelledby="modal-large" aria-hidden="true">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
           
                    <form method="post" enctype="multipart/form-data" action="{{ url('/import_excel/leaveimport') }}">
                        @csrf
                        
                        
                        <div class="block block-themed block-transparent mb-0">
                    <div class="block-header bg-info">
                        <h3 class="block-title">Import Leave</h3>
                        <div class="block-options">
                            <button type="button" class="btn-block-option" data-dismiss="modal" aria-label="Close">
                                <i class="si si-close"></i>
                            </button>
                        </div>
                    </div>
                    <div class="block-content">
                        <div class="form-group">
                            <label>Select File for Upload</label>
                            
                            <input type="file" name="select_file"/>
                                   
                        </div>


                    </div>
                </div>
                <div class="modal-footer">
                    <input type="submit" class="btn btn-outline-info btn-rounded" value="Import data">
                    <button type="button" class="btn btn-alt-secondary" data-dismiss="modal">Close</button>
                </div>
                    </form>
                
        </div>
    </div>
</div>
<div class="modal " id="importattendance" tabindex="-1" role="dialog" aria-labelledby="modal-large" aria-hidden="true">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
           
                    <form method="post" enctype="multipart/form-data" action="{{ url('/import_excel/attendanceimport') }}">
                        @csrf
                        
                        
                        <div class="block block-themed block-transparent mb-0">
                    <div class="block-header bg-info">
                        <h3 class="block-title">Import Attendance data</h3>
                        <div class="block-options">
                            <button type="button" class="btn-block-option" data-dismiss="modal" aria-label="Close">
                                <i class="si si-close"></i>
                            </button>
                        </div>
                    </div>
                    <div class="block-content">
                        <div class="form-group">
                            <label>Select File for Upload</label>
                            
                            <input type="file" name="select_file"/>
                                   
                        </div>


                    </div>
                </div>
                <div class="modal-footer">
                    <input type="submit" class="btn btn-outline-info btn-rounded" value="Import data">
                    <button type="button" class="btn btn-alt-secondary" data-dismiss="modal">Close</button>
                </div>
                    </form>
                
        </div>
    </div>
</div>

<script>
 var baseurl = window.location.protocol + "//" + window.location.host + '/pms/';
    $(document).ready(function() {
        $('#attendance').DataTable({
        "columns": [
   null,
    null, 
    null,
     { "type": "date" }
  ]
    });
    $('#all').DataTable({
        "columns": [
   null,
    null,
    null,
    null,
    null, 
    null, 
     { "type": "date" },
     null
  ]
    });
      $('#leave').DataTable({
        "columns": [
   null,
    null,
    null,
     { "type": "date" },
     { "type": "date" }, 
    null
  ]
    });
     $('#sent').DataTable(
         {
        "columns": [
   null,
    null,
    null,
    null,
    null, 
    null, 
     { "type": "date" },
     null
  ]
    });
     $('#received').DataTable(
         {
        "columns": [
   null,
    null,
    null,
    null,
    null, 
    null, 
     { "type": "date" },
     null
  ]
    });
} );

function deleteeng(id){
    document.getElementById('deleteegnform').action = baseurl + 'deleteEngagement/' + id 
    jQuery('#deleteEngagement').modal('show')
    
}


</script>

@endsection

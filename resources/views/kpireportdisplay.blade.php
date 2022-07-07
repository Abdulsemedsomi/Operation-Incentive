@extends('layouts.backend')

@section('content')
<style>
    .content{
         overflow: hidden;
    }
</style>
<div class="container">
    <div class="mt-50 mb-10 text-center">
        <h2 class="font-w700 text-black mb-10">KPI Display</h2>
    </div>
    <div class="block block-rounded">
        <div class="block-content bg-gd-sea">
         
            <form action="{{route('changekpiReport')}}" method="post">
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
                                    <option value='{{$s->id}}' {{$s->status == 'Active'?'selected':""}}>{{$s->session_name}}</option>
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
            <li class="nav-item ">
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
                <a class="nav-link {{$received->count() == 0 && $sent->count() == 0 ? 'active':''}}" id="contact-tab-md" data-toggle="tab" href="#contact-md" role="tab" aria-controls="contact-md"
                aria-selected="{{$received->count() == 0 && $sent->count() == 0? 'true':'false'}}">All</a>
            </li>
            @endif
            @if(Auth::user()->position != "CEO" && $session->id !=9 && Auth::user()->id == 170)
            <li class="nav-item">
                <a class="nav-link {{$received->count() == 0 && $sent->count() == 0 && !Gate::any('crud')  ? 'active':''}}" id="myscore-tab-md" data-toggle="tab" href="#myscore-md" role="tab" aria-controls="myscore-md"
                aria-selected="{{$received->count() == 0 && $sent->count() == 0 && !Gate::any('crud')  ? 'true':'false'}}">My Score</a>
            </li>
            @endif
        </ul>
    <div class="tab-content pt-5" id="myTabContentMD">
        @if($received->count() >0)
        <div class="tab-pane fade show active" id="home-md" role="tabpanel" aria-labelledby="home-tab-md">
            <div class="block">
               
                <div class="block-content">
                    <table class="table table-hover table-vcenter table-responsive" id="received" >
                                <thead>
                                    <tr>
                                        <th class="text-center" style="width: 50px;">#</th>
                                        <th>Employee</th>
                                        <th>Issuer</th>
                                         <th class="d-none d-sm-table-cell" style="width: 15%;">Type</th>
                                         <th class="d-none d-sm-table-cell" style="width: 15%;">KPI name</th>
                                        <th class="d-none d-sm-table-cell" style="width: 15%;">Position</th>

                                        <th>From</th>
                                        <th>Updated on</th>
                                        <th class="text-center" style="width: 100px;">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $count=1;
                                       
                                    @endphp
                                     @foreach($fill_kpis->where('issued_to', Auth::user()->id) as $rf)
                                    <tr>
                                    <th class="text-center" scope="row">{{$count++}}</th>


                                            <td>{{App\User::find($rf->issued_to)->fname . " " . App\User::find($rf->issued_to)->lname}}</td>
                                            <td>{{App\User::find($rf->issuer)->fname . " " . App\User::find($rf->issuer)->lname}}</td>

                                            @if(($rf)->type == 2)
                                                <td class="d-none d-sm-table-cell">
                                                    <span class="badge badge-danger">Reprimand</span>
                                                </td>
                                                @else
                                                <td class="d-none d-sm-table-cell">
                                                    <span class="badge badge-success">Appreciation</span>
                                                </td>
                                            @endif
                                                <td class="d-none d-sm-table-cell">
                                                    {{App\Kpi:: find($rf->kpi_id)->kpi_name}}
                                                </td>



                                            <td>  {{App\Kpi:: find($rf->kpi_id)->position}}</td>
                                            <td>{{App\KpiNotice::find($rf->id)->report_id != null? "Report" : "Project"}}</td>
                                            <td>{{ Carbon\Carbon::parse($rf->updated_at)->format('M d Y H:m')}}</td>

                                            
                                              <td class="text-center">
                                                <div class="btn-group">
                                       <a type="button" class="btn btn-sm btn-secondary" data-toggle="tooltip" title="Download" href = "{{ route('downloadkreport', $rf->id) }}">
                                            <i class="fa fa-download"></i>
                                        </a>
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
          @if($sent->count() > 0)
        <div class="tab-pane fade {{$received->count() == 0 ? 'show active':''}}" id="profile-md" role="tabpanel" aria-labelledby="profile-tab-md">
            <div class="block">
              
                <div class="block-content">
                    <table class="table table-hover table-vcenter table-responsive" id="sent">
                                <thead>
                                    <tr>
                                        <th class="text-center" style="width: 50px;">#</th>
                                        <th>Employee</th>
                                        <th>Issuer</th>
                                         <th class="d-none d-sm-table-cell" style="width: 15%;">Type</th>
                                         <th class="d-none d-sm-table-cell" style="width: 15%;">KPI name</th>
                                        <th class="d-none d-sm-table-cell" style="width: 15%;">Position</th>

                                        <th>From</th>
                                        <th>Updated on</th>
                                        <th class="text-center" style="width: 100px;">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $count=1;
                                    @endphp
                                     @foreach($fill_kpis->where('issuer', Auth::user()->id) as $rf)
                                    <tr>
                                    <th class="text-center" scope="row">{{$count++}}</th>


                                            <td>{{App\User::find($rf->issued_to)->fname . " " . App\User::find($rf->issued_to)->lname}}</td>
                                            <td>{{App\User::find($rf->issuer)->fname . " " . App\User::find($rf->issuer)->lname}}</td>

                                            @if(($rf)->type == 2)
                                                <td class="d-none d-sm-table-cell">
                                                    <span class="badge badge-danger">Reprimand</span>
                                                </td>
                                                @else
                                                <td class="d-none d-sm-table-cell">
                                                    <span class="badge badge-success">Appreciation</span>
                                                </td>
                                            @endif
                                              <td class="d-none d-sm-table-cell">
                                                    {{App\Kpi:: find($rf->kpi_id)->kpi_name}}
                                                </td>



                                            <td>  {{App\Kpi:: find($rf->kpi_id)->position}}</td>
                                            <td>{{App\KpiNotice::find($rf->id)->report_id != null? "Report" : "Project"}}</td>
                                            <td>{{ Carbon\Carbon::parse($rf->updated_at)->format('M d Y H:m')}}</td>

                                            
                                              <td class="text-center">
                                                <div class="btn-group">
                                      
                                           <a type="button" class="btn btn-sm btn-secondary" data-toggle="tooltip" title="Download" href = "{{ route('downloadkreport', $rf->id) }}">
                                            <i class="fa fa-download"></i>
                                        </a>
                                        
                                        <button type="button" class="btn btn-sm btn-secondary" class="js-swal-confirm btn btn-alt-secondary" onclick="deletekpi({{$rf->id}})">
                                            <i class="fa fa-trash"></i>
                                        </button>
                                        
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
         @if(Gate::any('crud') || App\Role_user::where('user_id', Auth::user()->id)->where('role_id', 13)->first())
        <div class="tab-pane fade {{$received->count() == 0 && $sent->count() == 0 ? 'show active':''}}" id="contact-md" role="tabpanel" aria-labelledby="contact-tab-md">
            <div class="block">
                <div class="block-header block-header-default">
                     <h3 class="block-title">KPI Actions</h3>
                    <div class="text-right mb-10">
                @if(Gate::any(['crud']))
                <button  type="button" class="btn btn-rounded btn-outline-info " data-toggle="modal" data-target="#bulkdownload" >Bulk download</button>
                @endif
                
            </div>
                </div>
                <div class="block-content">
                   <table class="table table-hover table-vcenter table-responsive" id="all">
                                <thead>
                                    <tr>
                                        <th class="text-center" style="width: 50px;">#</th>
                                        <th>Employee</th>
                                        <th>Issuer</th>
                                         <th class="d-none d-sm-table-cell" style="width: 15%;">Type</th>
                                         <th class="d-none d-sm-table-cell" style="width: 15%;">KPI name</th>
                                        <th class="d-none d-sm-table-cell" style="width: 15%;">Position</th>

                                        <th>From</th>
                                        <th>Updated on</th>
                                        <th class="text-center" style="width: 100px;">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $count=1;
                                    @endphp
                                     @foreach($fill_kpis as $rf)
                                    <tr>
                                    <th class="text-center" scope="row">{{$count++}}</th>


                                            <td>{{App\User::find($rf->issued_to)->fname . " " . App\User::find($rf->issued_to)->lname}}</td>
                                            <td>{{App\User::find($rf->issuer)->fname . " " . App\User::find($rf->issuer)->lname}}</td>

                                            @if(($rf)->type == 2)
                                                <td class="d-none d-sm-table-cell">
                                                    <span class="badge badge-danger">Reprimand</span>
                                                </td>
                                                @else
                                                <td class="d-none d-sm-table-cell">
                                                    <span class="badge badge-success">Appreciation</span>
                                                </td>
                                            @endif
                                              <td class="d-none d-sm-table-cell">
                                                    {{App\Kpi:: find($rf->kpi_id)->kpi_name}}
                                                </td>



                                            <td>  {{App\Kpi:: find($rf->kpi_id)->position}}</td>
                                            <td>{{App\KpiNotice::find($rf->id)->report_id != null? "Report" : "Project"}}</td>
                                            <td>{{ Carbon\Carbon::parse($rf->updated_at)->format('M d Y H:m')}}</td>

                                            
                                              <td class="text-center">
                                                  <div class="btn-group">
                                        
                                        <a type="button" class="btn btn-sm btn-secondary" data-toggle="tooltip" title="Download" href = "{{ route('downloadkreport', $rf->id) }}">
                                            <i class="fa fa-download"></i>
                                        </a>
                                        @if($rf->issuer == Auth::user()->id)
                                         <button type="button" class="btn btn-sm btn-secondary" class="js-swal-confirm btn btn-alt-secondary" onclick="deletekpi({{$rf->id}})">
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
         @if(Auth::user()->position != "CEO" && $session->id !=9 && Auth::user()->id == 170)
        <div class="tab-pane fade {{$received->count() == 0 && $sent->count() == 0 && !Gate::any('crud') ? 'show active':''}}" id="myscore-md" role="tabpanel" aria-labelledby="myscore-tab-md">
            <div class="block">
                <div class="block-content">
                   <table class="table table-hover table-vcenter table-responsive" id="all">
                                <thead>
                                    <tr>
                                        <th class="text-center">#</th>
                                        <th>KPI Type</th>
                                        <th class="text-center">Actions</th>
                                    </tr>
                                </thead>
                                @if($userscore && $userscore->project_kpi_score !=0)
                                @php
                                $kns =  App\KpiNotice::where('kpis.kpi_id', 1)->where('issued_to', Auth::user()->id)->where('kpi_notices.session_id', $session->id)->join('kpis', 'kpi_notices.kpi_id', '=', 'kpis.id')->select('kpi_name', 'kpis.id')->distinct()->get();
                                @endphp
                               <tbody class="labels">
                        			<tr>
                        				<td colspan="3">
                        					<label for="accounting">Project KPI Score</label>
                        					<input type="checkbox" name="accounting" id="project" data-toggle="toggle">
                        					<label class="text-right">{{$userscore->project_kpi_score}}</label>
                        				</td>
                        			</tr>
                        		</tbody>
                        		<tbody class="hide">
                        		    @foreach($kns as $kn)
                        			<tr>
                        				<td>{{$kn->kpi_name}}</td>
                        				 <td class="text-center">
                                                <div class="btn-group">
                                                
                                                <a type="button" class="btn btn-sm btn-secondary" data-toggle="tooltip" title="View" href = "{{ route('kpiview', $kn->id) }}">
                                                    <i class="si si-eye"></i>
                                                </a>
                                              
                                                </div>
                                            </td>
                        			</tr>
                        			@endforeach
                        		</tbody>
                        		@endif
                        		 @if($userscore && $userscore->sales_kpi_score !=0)
                                @php
                                $kns =  App\KpiNotice::where('kpis.kpi_id', 2)->where('issued_to', Auth::user()->id)->where('kpi_notices.session_id', $session->id)->join('kpis', 'kpi_notices.kpi_id', '=', 'kpis.id')->select('kpi_name', 'kpis.id')->distinct()->get();
                                @endphp
                               <tbody class="labels">
                        			<tr>
                        				<td colspan="3">
                        					<label for="accounting">Sales KPI Score</label>
                        					<input type="checkbox" name="accounting" id="project" data-toggle="toggle">
                        					<label class="text-right">{{$userscore->sales_kpi_score}}</label>
                        				</td>
                        			</tr>
                        		</tbody>
                        		<tbody class="hide">
                        		    @foreach($kns as $kn)
                        			<tr>
                        				<td>{{$kn->kpi_name}}</td>
                        				 <td class="text-center">
                                                <div class="btn-group">
                                                
                                                <a type="button" class="btn btn-sm btn-secondary" data-toggle="tooltip" title="View" href = "{{ route('kpiview', $kn->id) }}">
                                                    <i class="si si-eye"></i>
                                                </a>
                                              
                                                </div>
                                            </td>
                        			</tr>
                        			@endforeach
                        		</tbody>
                        		@endif
                        		 @if($userscore && $userscore->order_kpi_score !=0)
                                @php
                                $kns =  App\KpiNotice::where('kpis.kpi_id', 3)->where('issued_to', Auth::user()->id)->where('kpi_notices.session_id', $session->id)->join('kpis', 'kpi_notices.kpi_id', '=', 'kpis.id')->select('kpi_name', 'kpis.id')->distinct()->get();
                                @endphp
                               <tbody class="labels">
                        			<tr>
                        			    <button type="button" class="collapsible">
                        				<td colspan="3">
                        					<label for="accounting">Order and delivery KPI Score</label>
                        					<label class="text-right">{{$userscore->order_kpi_score}}</label>
                        				</td>
                        				</button>
                        			</tr>
                        	
                        		<div class="content">
                        		    @foreach($kns as $kn)
                        			<tr>
                        				<td>{{$kn->kpi_name}}</td>
                        				 <td class="text-center">
                                                <div class="btn-group">
                                                
                                                <a type="button" class="btn btn-sm btn-secondary" data-toggle="tooltip" title="View" href = "{{ route('kpiview', $kn->id) }}">
                                                    <i class="si si-eye"></i>
                                                </a>
                                              
                                                </div>
                                            </td>
                        			</tr>
                        			@endforeach
                        		</div>>
                        		@endif
                        		 @if($userscore && $userscore->leadership_kpi_score !=0)
                                @php
                                $kns =  App\KpiNotice::where('kpis.kpi_id', 4)->where('issued_to', Auth::user()->id)->where('kpi_notices.session_id', $session->id)->join('kpis', 'kpi_notices.kpi_id', '=', 'kpis.id')->select('kpi_name', 'kpis.id')->distinct()->get();
                                @endphp
                               <tbody class="labels">
                        			<tr>
                        				<td colspan="5">
                        					<label for="accounting">Leadership KPI Score</label>
                        					<input type="checkbox" name="accounting" id="project" data-toggle="toggle">
                        					<label class="text-right">{{$userscore->leadership_kpi_score}}</label>
                        				</td>
                        			</tr>
                        		</tbody>
                        		<tbody class="hide">
                        		    @foreach($kns as $kn)
                        			<tr>
                        				<td>{{$kn->kpi_name}}</td>
                        				 <td class="text-center">
                                                <div class="btn-group">
                                                
                                                <a type="button" class="btn btn-sm btn-secondary" data-toggle="tooltip" title="View" href = "{{ route('kpiview', $kn->id) }}">
                                                    <i class="si si-eye"></i>
                                                </a>
                                              
                                                </div>
                                            </td>
                        			</tr>
                        			@endforeach
                        		</tbody>
                        		@endif
                            </table>
                            
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
                                <input type="date" id="start_date" class="form-control round"  name ="start_date" min ="{{ $fill_kpis->count() > 0 ? App\Session::find($fill_kpis[0]->session_id)->start_date: ''}}" max="{{$fill_kpis->count()>0?App\Session::find($fill_kpis[0]->session_id)->end_date:''}}"  value="{{$fill_kpis->count()>0?App\Session::find($fill_kpis[0]->session_id)->start_date:''}}" required>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="end_date" class="col-md-4 col-form-label">To</label>
                            <div class="col-md-6">
                                <input type="date" id="eenddate" class="form-control round" name="end_date" value="{{$fill_kpis->count()>0?App\Session::find($fill_kpis[0]->session_id)->end_date:''}}" min ="{{$fill_kpis->count()>0?App\Session::find($fill_kpis[0]->session_id)->start_date:''}}" max="{{$fill_kpis->count()>0?App\Session::find($fill_kpis[0]->session_id)->end_date:''}}" required>
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
<div class="modal " id="deleteKpi" tabindex="-1" role="dialog" aria-labelledby="modal-large" aria-hidden="true">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <form  method="post" id="deletekpiform">
                @csrf
                  
                <div class="block block-themed block-transparent mb-0">
                    <div class="block-header bg-danger">
                        <h3 class="block-title">Delete KPI report</h3>
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
<script>
var baseurl = window.location.protocol + "//" + window.location.host + '/pms/';
    $(document).ready(function() {
    $('#all').DataTable({
        "columns": [
   null,
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
     $('#sent').DataTable(
         {
        "columns": [
   null,
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
    null,
     { "type": "date" },
     null
  ]
    });
    $('[data-toggle="toggle"]').change(function(){
		$(this).parents().next('.hide').toggle();
	});
} );
function deletekpi(id){
    document.getElementById('deletekpiform').action = baseurl + 'deleteKpi/' + id 
    jQuery('#deleteKpi').modal('show')
    
}


</script>

@endsection

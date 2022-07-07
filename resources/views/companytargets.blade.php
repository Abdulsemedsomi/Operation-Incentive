@extends('layouts.backend')
@section('content')
<div class="container">
    <div class="mt-50 mb-10 text-center">
        <h2 class="font-w700 text-black mb-10">Company Target</h2>
        <hr>
    </div>
       
   <div class="container mt-20">
        <div class="block">
            <div class="block-content">
                <form action="{{route('changecompanytarget')}}" method="post">
                    @csrf
                    <div class="form-group row">
                        <label class="col-12" for="example-select">Session</label>
                        <div class="col-md-5">
                            <select class="form-control" id="session-select" name="session-select">
                                @php
                                $sessions = App\Session::all();
                                @endphp
                                <option value="0" disabled>Please select Session</option>
                                @foreach($sessions as $s)
                                    <option value='{{$s->id}}' {{$session->id == $s->id ? "selected":""}}>{{$s->session_name}}</option>
                                @endforeach
                                
                            </select>
                        </div>
                        <button type="submit" class="btn btn-outline btn-alt-info ml-10 " >
                                    <i class="fa fa-check"></i> Change
                        </button>
                    </div>
                </form>
            </div>
        </div>
        @if(Gate::any(['crud']) || Auth::user()->id == 144 )
        <div class="block">
            <div class="block-header block-header-default">
                <h3 class="block-title">Awards</h3>
            </div>
            <div class="block-content">
                <div class="col-md-8">
                    @if($message = Session::get('successa'))
                    <div class="alert alert-success alert-block">
                     <button type="button" class="close" data-dismiss="alert">×</button>
                            <strong>{{ $message }}</strong>
                    </div>
                    @elseif($message = Session::get('errora'))
                    <div class="alert alert-danger alert-block">
                        <button type="button" class="close" data-dismiss="alert">×</button>
                               <strong>{{ $message }}</strong>
                       </div>
                    @endif
                </div>
                <div class="col-md-8">
                    <h5>
                       Import Awards
                    </h5>
                    <form method="post" enctype="multipart/form-data" action="{{ url('/import_excel/awardimport') }}">
                        @csrf
                        <input type="hidden"  value="{{$session->id}}" name="session_id" />
                        <div class="form-group">
                            <table class="table">
                                <tr>
                                    <td width="40%" ><label>Select File for Upload</label></td>
                                    <td width="30">
                                        <input type="file" name="select_file"/>
                                    </td>
                                    <td width="30%" >
                                        <input type="submit" class="btn btn-outline-info btn-rounded" value="Import data">
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </form>
                </div>
                 <h5>
                       Target Value
                </h5>
                <hr>
                <form action="{{route('updatetargetaward')}}" method="post">
                     @csrf
                    <input type="hidden"  value="{{$session->id}}" name="session_id" />
                    <div class="form-group">
                        <div class="col-md-10 row form-group">
                            <div class="col-md-3">
                                <h6>Award Target</h6>
                            </div>
                            <div class="col-md-4">
                                <input class=" form-control round" type="number" step=0.01 value="{{$info ? $info->award_target : 0}}" name="award_target" required/>
                            </div>
                             <div class="col-md-4">
                                    <input class=" form-control round" type="number" step=0.01 value="{{$info ? $info->award_actual : 0}}" disabled/>
                                </div>
                        </div>
                        <button type="submit" class="btn btn-outline-info btn-rounded col-md-1 form-control"> Update </button>
                    </div>
                </form>
                @if($awards->count()>0)
                    <table class="table table-bordered table-vcenter">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Client</th>
                                <th>Award amount</th>
                                <th>Added by</th>
                                <th>Date Added</th>
                               
                            </tr>
                        </thead>
                        <tbody>
                            @php
                            $count = 1;
                            @endphp
                            @foreach($awards as $award)
                              
                                    <tr>
                                        <td>{{$count++}}</td>
                                        <td>{{$award->client}}</td>
                                        <td>{{$award->award}}</td>
                                        <td>{{App\User::find($award->user_id) ? App\User::find($award->user_id)->fname . " " . App\User::find($award->user_id)->lname : "Unknown user" }}</td>
                                        <td>{{Carbon\Carbon::parse($award->created_at)->format('M d Y')}}</td>
                                       
                                    </tr>
                               
                            @endforeach
                        </tbody>
                    </table>
                @endif
            </div>
        </div>
        @endif
        @if(Gate::any(['crud']) ||  Auth::user()->id == 187)
        <div class="block">
            <div class="block-header block-header-default">
                <h3 class="block-title">Financial statements</h3>
            </div>
            <div class="block-content">
                <div class="col-md-8">
                    @if($message = Session::get('successf'))
                    <div class="alert alert-success alert-block">
                     <button type="button" class="close" data-dismiss="alert">×</button>
                            <strong>{{ $message }}</strong>
                    </div>
                    @elseif($message = Session::get('errorf'))
                    <div class="alert alert-danger alert-block">
                        <button type="button" class="close" data-dismiss="alert">×</button>
                               <strong>{{ $message }}</strong>
                       </div>
                    @endif
                </div>
                <div class="col-md-8">
                    <h5>
                       Import Financial Statement
                    </h5>
                    <form method="post" enctype="multipart/form-data" action="{{ url('/import_excel/financialimport') }}">
                        @csrf
                        <input type="hidden"  value="{{$session->id}}" name="session_id" />
                        <div class="form-group">
                            <table class="table">
                                <tr>
                                    <td width="40%" ><label>Select File</label></td>
                                    <td width="20%" ><input type="date" name="date" class="form-control round" placeholder="Select date" required/></td>
                                    <td width="20">
                                        <input type="file" name="select_file" required/>
                                    </td>
                                    <td width="20%" >
                                        <input type="submit" class="btn btn-outline-info btn-rounded" value="Import data">
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </form>
                </div>
                <h5>
                        Values
                </h5>
                <hr>
                <form action="{{route('updatefinancialstat')}}" method="post">
                     @csrf
                    <input type="hidden"  value="{{$session->id}}" name="session_id" />
                     <div class="form-group">
                        <div class="col-md-10 row form-group">
                            <div class="col-md-3">
                                <h6>Cash Collection Target</h6>
                            </div>
                            <div class="col-md-4">
                                <input class=" form-control round" type="number" step=".01" value="{{$info ? $info->cash_target : 0}}" name="cash_target" required />
                            </div>
                            
                            <div class="col-md-4">
                                <input class=" form-control round" type="number" step="0.01" value="{{$info ? $info->cash_actual : 0}}" disabled/>
                            </div>
                        </div>
                        <div class="col-md-10 row form-group">
                            <div class="col-md-3">
                                <h6>Revenue Target</h6>
                                
                            </div>
                           <div class="col-md-4">
                                <input class=" form-control round" type="number" step="0.01" value="{{$info ? $info->revenue_target : 0}}" name="revenue_target" required />
                            </div>
                             <div class="col-md-4">
                                <input class=" form-control round" type="number" step="0.01" value="{{$info ? $info->revenue_actual : 0}}" disabled/>
                            </div>
                        </div>
                        <div class="col-md-10 row form-group">
                            <div class="col-md-3">
                                <h6>EBITDA Target</h6>
                            </div>
                            <div class="col-md-4">
                                <input class=" form-control round" type="number" step=0.01 value="{{$info ? $info->ebitda_target : 0}}" name="ebitda_target" required />
                            </div>
                             <div class="col-md-4">
                                <input class=" form-control round" type="number" step=0.01 value="{{$info ? $info->ebitda_actual : 0}}" disabled/>
                            </div>
                           
                        </div>
                        <button type="submit"  class="btn btn-outline-info btn-rounded col-md-1 form-control ml-10"> Update </button>
                     </div>
                 </form>
                @if($statements->count()>0)
                    <table class="table table-bordered table-vcenter">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Record</th>
                                <th>Cash collection</th>
                                <th>Revenue</th>
                                <th>EBITDA</th>
                                <th>Added by</th>
                                <th>Date Added</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $count = 1;
                            @endphp
                            @foreach($statements as $statement)
                              
                                    <tr>
                                        <td>{{$count++}}</td>
                                        <td>{{Carbon\Carbon::parse($statement->record)->format('M d Y')}}</td>
                                         <td>{{$statement->cash}}</td>
                                        <td>{{$statement->revenue}}</td>
                                        <td>{{$statement->ebitda}}</td>
                                        <td>{{App\User::find($statement->user_id)? App\User::find($statement->user_id)->fname . " " . App\User::find($statement->user_id)->lname: "Unknown user" }}</td>
                                        <td>{{Carbon\Carbon::parse($statement->created_at)->format('M d Y')}}</td>
                                       
                                    </tr>
                               
                            @endforeach
                        </tbody>
                    </table>
                @endif
            </div>
        </div>
        @endif
    </div>
</div>

@endsection

@extends('layouts.backend')
<!-- CSS only -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/css/bootstrap-select.css" />
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
@section('content')
<div class="container">
    <nav class="breadcrumb bg-white push">
        <a class="breadcrumb-item" href="{{ route('checkin', $team->id) }}">Check-in</a>
        <span class="breadcrumb-item active">Weekly Report</span>
    </nav>
</div>
<div class="container">
    <h3 class="font-w500 text-center"> Edit Weekly Report </h3>
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
    <div  class="container neopad adddailycard" >
        <div class="card text-center">
            <div class="card-body text-left">
                <form action = "{{ route('editweeklyreport', $report->id) }}"   method="post">
                    @csrf
                     <input type="hidden" name="_method" value="put" />
                    <div class="form-group row">
                            <label for="reportTo" class="col-md-4 col-form-label text-md-right">Selam</label>
                            <div class="col-md-6">
                                <select class=" form-control round" id="reportTo" name="reportsTo">
                                      @foreach($users as $user)
                                    <option value="{{$user->id}}" {{$user->fname. " " . $user->lname == Auth::user()->reportsTo? 'selected': ''}}>{{$user->fname. " " . $user->lname}}</option>
                                      @endforeach
                                    
                                </select>
                            </div>
                        </div>
                        @foreach($objectives as $objs)
                            <b class="card-text" style="font-size:20px;">Objective: {{$objs->objective_name}}</b>
                            @foreach($metric as $m)
                                @if($m->objective_id == $objs->id)
                                    <div class="row card-title">
                                        <div class="col-md-8 ">
                                            <b class="default metric-btn" style="font-size:16px;" value={{$m->keyresultid}}>Key Result: {{$m->keyresult_name}} ({{$m->keyresult_percent}}%)</b>
                                        </div>

                                        <a class="task collapsed" data-toggle="collapse" data-parent="#accordion" href="#daily{{$m->keyresultid}}">
                                            <div class="col-md-2">
                                                <span class="pull-right"><i class="fa fa-caret-down"></i></span>
                                            </div>
                                        </a>
                                    </div>
                                    <span class = "border-top-0">
                                        <div id="daily{{$m->keyresultid}}" class="panel-collapse collapse show">
                                            <div class="card-body mx-2">
                                                <div>
                                                    <ul id="tasks-list{{$m->keyresultid}}">
                                                        @foreach($weekplan as $task)
                                                            @if($task->keyresultid == $m->keyresultid)
                                                                    @php
                                                                        $weeklyreport = App\Weeklyreport::where('task_id', $task->id)->orderby('id','desc')->first();
                                                                        $dailyreport = App\Dailyreport::where('task_id', $task->id)->orderby('id','desc')->first();
                                                                        $feedback="";
                                                                        if( $dailyreport){
                                                                            $feedback = $dailyreport->feedback;
                                                                        }
                                                                        if( $weeklyreport){
                                                                            $feedback = $weeklyreport->feedback;
                                                                        }
                                                                    @endphp
                                                                   
                                                                <li style="list-style-type:none" class="row mb-10" id="taskl{{$task->id}}">
                                                                    <label class="form-check-label col-md-6" for="customCheck{{$task->id}}">{{$task->taskname}} ( {{$task->task_percent}}%)</label>
                                                                    @if($task->status == 0)
                                                                       
                                                                        <b class="form-check-label col-md-2 text-danger" > Failed</b> 
                                                                        <div class="col-md-4">
                                                                            <input type="text" class="form-control round" placeholder="Reason" name="rfeedback{{$task->id}}" value="{{$feedback}}" required>
                                                                       </div>
                                                                       <div class="form-group ml-10 col-md-11 mt-10 mb-20" >
                                                                            <select class="form-control round col-md-11"  id="failyanalysis{{$task->id}}" name="failyanalysis{{$task->id}}" required>
                                                                                <option disabled selected value="">Please select reason for failure</option>
                                                                                @foreach($failuretargets as $ft)
                                                                                    <option value="{{$ft->id}}" {{ $weeklyreport->failurereason_id == $ft->id?"selected":"" }}> {{$ft->target}}</option>
                                                                                @endforeach
                                                                            </select>
                                                                    </div>
                                                                    @else
                                                                        <b class="form-check-label col-md-2 text-success" >Achieved</b>
                                                                        <div class="col-md-4">
                                                                            <input type="text" class="form-control round " placeholder="Reason" name="rfeedback{{$task->id}}" value="{{$feedback}}">
                                                                       </div>
                                                                    @endif
                                                                </li>
                                                            @endif
                                                        @endforeach
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    </span>
                                @endif
                            @endforeach
                        @endforeach
                       
                        
                        <label  class="text-center">Total: {{$taskachieved}}%</label>
                        
                        <div class=" row">
                                    <label for="cc" class="col-md-2 col-form-label text-md-right">CC</label>
                                    
                                        <select class="selectpicker" id="cc" name="cc[]" multiple >
                                          
                                              @foreach($users as $user)
                                            <option value="{{$user->id}}" >{{$user->fname. " " . $user->lname}}</option>
                                              @endforeach
                                            
                                        </select>
                                    
                                </div>

        <div class="card-body">
            <div class="row pull-right">
           <button type="submit" class="btn btn-rounded btn-outline-primary submitdayplan">Submit Weekly report</button>
            <div class="col-md-1"></div>
           <button  type="button" class="btn btn-rounded btn-outline-danger canceldayplan">Cancel</button>
            </div>
       </div>
</form>
            </div>

          </div>
        </div>
   </div>
     <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.bundle.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/js/bootstrap-select.min.js"></script>
@endsection

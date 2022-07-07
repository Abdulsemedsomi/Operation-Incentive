@extends('layouts.backend')
@section('content')
<div class="container">
    <div class="mt-50 mb-10 text-center">
        <h2 class="font-w700 text-black mb-10">Failure targets</h2>
        <hr>
    </div>
   
    @if($message = Session::get('success'))
        <div class="alert alert-success alert-block col-md-7">
         <button type="button" class="close" data-dismiss="alert">×</button>
                <strong>{{ $message }}</strong>
        </div>
    @elseif($message = Session::get('error'))
        <div class="alert alert-danger alert-block col-md-7">
            <button type="button" class="close" data-dismiss="alert">×</button>
                   <strong>{{ $message }}</strong>
        </div>
    @endif
    <div class="container mt-30">
        <div class="row">
        <ul class="nav nav-tabs" id="myTab" role="tablist">
            <li class="nav-item">
                <a class="nav-link clickable-link active" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true">Manage targets</a>
            </li>
            <li class="nav-item">
                <a class="nav-link clickable-link" id="failure-tab" data-toggle="tab" href="#failure" role="tab" aria-controls="failure" aria-selected="false">Failure analysis</a>
            </li>
           
           
          </ul>
     </div >
     <div class="tab-content" id="myTabContent">
    <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
        <h2 class="content-heading">
                <i class="fa fa-target mr-5"></i>Targets
            </h2>
            @if(Gate::any(['crud']))
    <div class= "row mt-5 mb-5">
        <button  type="submit" class="btn btn-rounded btn-outline-primary pull-right" data-toggle="modal" data-target="#addFailuremodal" >Add Failure target</button>
    </div>
    @endif
        @if($failures->count() > 0)
        
            <div class="block">
                
                <div class="block-content">
                     
                    <table class="table table-hover table-vcenter table-responsive" id="example" style="width:100%">
                        <thead>
                            <tr>
                                <th class="text-center" style="width: 5%;">#</th>
                                <th style="width: 65%;">Target</th>
                                <th style="width: 15%;">Date Added</th>
                                
                                <th class="text-center" style="width: 15%;">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $count=1;
                            @endphp
                             @foreach($failures as $f)
                            <tr>
                            <th class="text-center" scope="row">{{$count++}}</th>
    
    
                                    <td>{{$f->target}}</td>
                                    <td>{{ Carbon\Carbon::parse($f->created_at)->format('M d Y')}}</td>
    
    
                                        
    
                                    <td class="text-center row">
                            @if(Gate::any(['crud']))
                            <button class="btn btn-sm btn-alt-secondary editfailuretarget " value="{{$f->id}}" ><i class="si si-pencil"></i></button> &nbsp;
                             <button class="btn btn-sm btn-alt-danger deletefailuretarget " value="{{$f->id}}" ><i class="si si-trash" ></i></button>
                            @endif
                                    </td>
                                @endforeach
                            </tr>
    
                        </tbody>
                    </table>
                </div>
            </div>
        @endif
        </div>
        <div class="tab-pane fade show " id="failure" role="tabpanel" aria-labelledby="failure-tab">
             <h2 class="content-heading">
                <i class="fa fa-area-chart mr-5"></i>Failure Analysis
            </h2>
            @php
            $session = App\Session::where('status', 'Active')->first();
             $allusers = App\User::orderby('fname', 'asc')->where('active', 1)->get(); 
            @endphp
           
                
                    <div class="block block-rounded">
                        <div class="block-content" id="failurecontent">
                            <div class="form-group row">
                                <label class="col-12" for="failureanalysis">Employee</label>
                                <select class="form-control col-md-4" id="failureanalysis" name="failureanalysis" onchange="failureanalysis({{$session->id}})" data-toggle="block-option" data-action="state_toggle" data-action-mode="demo">
                                    <option disabled selected>Select Employee</option>
                                      @foreach($allusers as $at)
                                        @if($at->position !="CEO")
                                            <option value='{{$at->id}}'>{{$at->fname . " " . $at->lname}}</option>
                                        @endif
                                    @endforeach
                                </select>
                                
                            </div>
                            <canvas class="js-flot-pie" id="myPieChart"></canvas>
                        </div>
                   
                </div>
          
         </div>
    </div> 
</div>
</div>
<script>
    $(document).ready(function() {
    $('#example').DataTable();
        var ft = {!! isset($failures) ? $failures: 0!!}
    var ctx2 = document.getElementById('myPieChart').getContext("2d");
    var labels = [];
  var colors = []
  var percents = [];
    for(var i = 0; i < ft.length; i++){
        labels[i] = ft[i].target
        colors[i] = '#' + ft[i].color
        percents[i] =  (1/15 * 100).toFixed(2); 
    }
 

 
     $('#myPieChart').remove(); // this is my <canvas> element
  $('#failurecontent').append('<canvas class="js-flot-pie" id="myPieChart"></canvas>');
    var ctx2 = document.getElementById('myPieChart').getContext("2d");
    var myPieChart = new Chart(ctx2, {
    type: 'doughnut',
    data: {
        labels: labels,
        datasets: [{
          label: 'My First Dataset',
          data: percents,
          backgroundColor:colors,
          hoverOffset: 4
        }]
    },
    options: {
        legend: {
            display: true,
            labels: {
                fontColor: 'rgb(43, 11, 63)',
                defaultFontSize:7
            },
            position: 'right',
            align:'right'
        },
        layout: {
            padding: {
                left: 80,
                right: 80,
                top: 80,
                bottom: 80
            }
        },
    }
});
    
} );
function onlyUnique(value, index, self) {
  return self.indexOf(value) === index;
}
function failureanalysis(sessionid){
    var baseurl = window.location.protocol + "//" + window.location.host + '/pms/';
    var e = document.getElementById('failureanalysis')
      var id = e.options[e.selectedIndex].value
   $.get(baseurl + "failureanalysis/"+ id +"?sessionid="+sessionid, function(data) {
    
    var labels = [];
  var colors = []
  $('#myPieChart').remove(); // this is my <canvas> element
  if(data.length > 0){
        for(var i = 0; i < data.length; i++){
            labels[i] = data[i].target
            colors[i] = '#' + data[i].color
        }
         var labelsdistinct = labels.filter(onlyUnique);
         var colorsdistinct = colors.filter(onlyUnique);
         var percents = [];
        var total = 0
         for(var j=0; j <labelsdistinct.length; j++){
                 var sum = 0;
                for(var i = 0; i < data.length; i++){
                    if(labelsdistinct[j] == labels[i]){
                        sum += parseFloat(data[i].task_target)
                    }
                }
                percents[j] = sum;
               total += sum;
            }
            var rate = []
              percents.forEach(function(item) {
            rate.push((parseFloat(item/total) * 100).toFixed(2))
        });
            
            
            $('#nocont').remove();
              $('#failurecontent').append('<canvas class="js-flot-pie" id="myPieChart"></canvas>');
                var ctx = document.getElementById('myPieChart').getContext("2d");
                var myPieChart = new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: labelsdistinct,
                    datasets: [{
                      label: 'My First Dataset',
                      data: rate,
                      backgroundColor:colorsdistinct,
                      hoverOffset: 4
                    }]
                },
                options: {
                    legend: {
                        display: true,
                        labels: {
                            fontColor: 'rgb(43, 11, 63)',
                            defaultFontSize:7
                        },
                        position: 'right',
                        align:'right'
                    },
                    layout: {
                        padding: {
                            left: 80,
                            right: 80,
                            top: 80,
                            bottom: 80
                        }
                    },
                }
            });
  }
  else{
      $('#failurecontent').append('<label id="nocont">No content<label/>');
  }
    });  
}
</script>
 @include('includes.failuremodals')
@endsection






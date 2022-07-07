@extends('layouts.backend')
@section('content')
<div class="container">
    <div class="mt-50 mb-10 text-center">
        <h2 class="font-w700 text-black mb-10">Incentive Settings</h2>
        
        <hr>
    </div>
  
        <div class="row">
            
            
    <div class="col-md-3">
        
        <button  type="button" class="btn btn-rounded btn-outline-info text min-width-125" onclick="launchlevels()">Performance levels</a>
    </div>
    <div class="col-md-9">
    <!--<button type="button" class="btn btn-rounded btn-outline-info pull-right">Add Incentive Fund</button>-->
    </div>
</div>

</div>
     <div class="container mt-20">
        <div class="block">
            <div class="block-header block-header-default">
                <h3 class="block-title">Implementation Effectiveness Recognition Program</h3>
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
               
                  
                      <!--<button  type="submit" class="btn btn-rounded btn-outline-primary float-right" data-toggle="modal" data-target="#addSessionmodal" >Add</button>-->
               
                @php
                $projects = DB::table('incentivefunds')->where('type', 'project')->get();
                
               function number_shorten($number)
{
    $abbrevs = [12 => 'T', 9 => 'B', 6 => 'M', 3 => 'K', 0 => ''];

    foreach ($abbrevs as $exponent => $abbrev) {
        if (abs($number) >= pow(10, $exponent)) {
            $display = $number / pow(10, $exponent);
            $decimals = ($exponent >= 3 && round($display) < 100) ? 1 : 0;
            $number = number_format($display, $decimals).$abbrev;
            break;
        }
    }

    return $number;
}
                         function convert($num){
                                $result = round($num/30, 2);
                                
                                if($result >=12){
                                    $result = round($num/360, 2) . " year";
                                }
                                else if($result < 0.25){
                                 $result =$num . " day";
                                }
                                else{
                                    $result .= " month";
                                }
                                return $result;
                             }
                @endphp
             
                @if($projects->count() > 0)
                    <table class="table table-bordered table-vcenter mt-10">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Contract Value (before VAT)</th>
                                <th>Performance Level</th>
                                <th>Salary Bonus per person for full-cycle (from Initiation to Closure)</th>
                                <th>Salary Bonus per person for half-cycle (from Implementation to  Closure)</th>
                               
                            </tr>
                        </thead>
                        <tbody>
                            @php
                            $count = 1;
                            
                            @endphp
                            
                                    @foreach($projects as $p)
                                    
                                    <tr>
                                        <td>{{$count++}}</td>
                                        <td>{{number_shorten($p->amountmin)}}{{$p->amountmax == 0? "+": "-".number_shorten($p->amountmax)}}</td>
                                        <td><ul style="list-style:none">
                                        @for ($i = 1; $i < 5; $i++)
                                        @php
                                        $ifs = DB::table ('individualfunds')->where('individualfunds.type', 1)->where('levelname', $count-1)->where('level_id', $i)->join('incentivelevels', 'incentivelevels.id', '=', 'individualfunds.level_id')->first();
                                       
                                        @endphp
                                        
                                        
                                        <li>{{$ifs? $ifs->level:""}}</li>
                                       
                                        
                                        @endfor
                                        </ul></td>
                                        <td><ul style="list-style:none">
                                        @for ($i = 1; $i < 5; $i++)
                                        @php
                                        $ifs = DB::table ('individualfunds')->where('individualfunds.type', 1)->where('levelname', $count-1)->where('level_id', $i)->join('incentivelevels', 'incentivelevels.id', '=', 'individualfunds.level_id')->first();
                                        $month = 0;
                                        if($ifs){
                                            $month =  round($ifs->fullamount / 30, 3) . " month";
                                            if($month >= 12 ){
                                            $month =  round($ifs->fullamount / 360, 3) . " year";
                                            }
                                        }
                                        @endphp
                                        
                                       
                                       
                                        <li>{{$ifs? convert($ifs->fullamount):0}}</li>
                                        
                                     
                                        @endfor
                                        </ul></td>
                                        <td><ul style="list-style:none">
                                        @for ($i = 1; $i < 5; $i++)
                                        @php
                                        $ifs = DB::table ('individualfunds')->where('individualfunds.type', 1)->where('levelname', $count-1)->where('level_id', $i)->join('incentivelevels', 'incentivelevels.id', '=', 'individualfunds.level_id')->first();
                                        $month = 0;
                                        if($ifs){
                                            $month =  round($ifs->partialamount / 30, 3) . " month";
                                            if($month >= 12 ){
                                            $month =  round($ifs->partialamount / 360, 3) . " year";
                                            
                                        }
                                        }
                                        @endphp
                                        
                                        
                                       
                                      
                                        <li>{{$ifs? convert($ifs->partialamount):0}}</li>
                                       
                                        @endfor
                                        </ul></td>
                                        
                                    </tr>
                                    @endforeach
                               
                            
                        </tbody>
                    </table>
                    @endif
            </div>
        </div>
       
        <div class="block">
            <div class="block-header block-header-default">
                <h3 class="block-title">Deal Closure Recognition Program</h3>
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
               
                  
                      <!--<button  type="submit" class="btn btn-rounded btn-outline-primary float-right" data-toggle="modal" data-target="#addSessionmodal" >Add</button>-->
               
              
                 @php
                $projects = DB::table('incentivefunds')->where('type', 'project')->get();
                
             
                @endphp
             
                @if($projects->count() > 0)
                    <table class="table table-bordered table-vcenter mt-10">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Contract Value (before VAT)</th>
                                <th>Performance Level</th>
                                <th>Salary Bonus per person for full-cycle (from Initiation to Closure)</th>
                                <th>Salary Bonus per person for half-cycle (from Implementation to  Closure)</th>
                               
                            </tr>
                        </thead>
                        <tbody>
                            @php
                            $count = 1;
                            
                            @endphp
                            
                                    @foreach($projects as $p)
                                    
                                    <tr>
                                        <td>{{$count++}}</td>
                                        <td>{{number_shorten($p->amountmin)}}{{$p->amountmax == 0? "+": "-". number_shorten($p->amountmax)}}</td>
                                        <td><ul style="list-style:none">
                                        @for ($i = 1; $i < 5; $i++)
                                        @php
                                        $ifs = DB::table ('individualfunds')->where('individualfunds.type', 1)->where('levelname', $count-1)->where('level_id', $i)->join('incentivelevels', 'incentivelevels.id', '=', 'individualfunds.level_id')->first();
                                        @endphp
                                        
                                        
                                        <li>{{$ifs? $ifs->level:""}}</li>
                                       
                                        
                                        @endfor
                                        </ul></td>
                                        <td><ul style="list-style:none">
                                        @for ($i = 1; $i < 5; $i++)
                                        @php
                                        $ifs = DB::table ('individualfunds')->where('individualfunds.type', 1)->where('levelname', $count-1)->where('level_id', $i)->join('incentivelevels', 'incentivelevels.id', '=', 'individualfunds.level_id')->first();
                                        $month = 0;
                                        if($ifs){
                                            $month =  round($ifs->fullamount / 30, 3) . " month";
                                            if($month >= 12 ){
                                            $month =  round($ifs->fullamount / 360, 3) . " year";
                                            }
                                        }
                                        @endphp
                                        
                                       
                                       
                                        <li>{{$ifs? convert($ifs->fullamount):0}}</li>
                                        
                                     
                                        @endfor
                                        </ul></td>
                                        <td><ul style="list-style:none">
                                        @for ($i = 1; $i < 5; $i++)
                                        @php
                                        $ifs = DB::table ('individualfunds')->where('individualfunds.type', 1)->where('levelname', $count-1)->where('level_id', $i)->join('incentivelevels', 'incentivelevels.id', '=', 'individualfunds.level_id')->first();
                                         $month = 0;
                                        if($ifs){
                                            $month =  round($ifs->partialamount / 30, 3) . " month";
                                            if($month >= 12 ){
                                            $month =  round($ifs->partialamount / 360, 3) . " year";
                                            }
                                        }
                                        
                                        @endphp
                                        
                                       
                                      
                                        <li>{{$ifs? convert($ifs->partialamount):0}}</li>
                                       
                                        @endfor
                                        </ul></td>
                                        
                                    </tr>
                                    @endforeach
                               
                            
                        </tbody>
                    </table>
                    @endif
              
            </div>
        </div>
         <div class="block">
            <div class="block-header block-header-default">
                <h3 class="block-title">Effective Order and Delivery Recognition Program</h3>
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
               
                  
                      <!--<button  type="submit" class="btn btn-rounded btn-outline-primary float-right" data-toggle="modal" data-target="#addSessionmodal" >Add</button>-->
               
              
                   @php
                $projects = DB::table('incentivefunds')->where('type', 'order')->get();
                
              
                @endphp
             
                @if($projects->count() > 0)
                    <table class="table table-bordered table-vcenter mt-10">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Contract Value (before VAT)</th>
                                <th>Performance Level</th>
                                <th>Salary Bonus per person for full-cycle (from Initiation to Closure)</th>
                                <th>Salary Bonus per person for half-cycle (from Implementation to  Closure)</th>
                               
                            </tr>
                        </thead>
                        <tbody>
                            @php
                            $count = 1;
                            
                            @endphp
                            
                                    @foreach($projects as $p)
                                    
                                    <tr>
                                        <td>{{$count++}}</td>
                                        <td>{{number_shorten($p->amountmin)}}{{$p->amountmax == 0? "+": "-".number_shorten($p->amountmax)}}</td>
                                        <td><ul style="list-style:none">
                                        @for ($i = 1; $i < 5; $i++)
                                        @php
                                        $ifs = DB::table ('individualfunds')->where('individualfunds.type', 1)->where('levelname', $count-1)->where('level_id', $i)->join('incentivelevels', 'incentivelevels.id', '=', 'individualfunds.level_id')->first();
                                       
                                        @endphp
                                        
                                        
                                        <li>{{$ifs? $ifs->level:""}}</li>
                                       
                                        
                                        @endfor
                                        </ul></td>
                                        <td><ul style="list-style:none">
                                        @for ($i = 1; $i < 5; $i++)
                                        @php
                                        $ifs = DB::table ('individualfunds')->where('individualfunds.type', 1)->where('levelname', $count-1)->where('level_id', $i)->join('incentivelevels', 'incentivelevels.id', '=', 'individualfunds.level_id')->first();
                                         $month = 0;
                                        if($ifs){
                                            $month =  round($ifs->fullamount / 30, 3) . " month";
                                            if($month >= 12 ){
                                            $month =  round($ifs->fullamount / 360, 3) . " year";
                                            }
                                        }
                                        @endphp
                                        
                                       
                                       
                                        <li>{{$ifs? convert($ifs->fullamount):0}}</li>
                                        
                                     
                                        @endfor
                                        </ul></td>
                                        <td><ul style="list-style:none">
                                        @for ($i = 1; $i < 5; $i++)
                                        @php
                                        $ifs = DB::table ('individualfunds')->where('individualfunds.type', 1)->where('levelname', $count-1)->where('level_id', $i)->join('incentivelevels', 'incentivelevels.id', '=', 'individualfunds.level_id')->first();
                                         $month = 0;
                                        if($ifs){
                                            $month =  round($ifs->partialamount / 30, 3) . " month";
                                            if($month >= 12 ){
                                            $month =  round($ifs->partialamount / 360, 3) . " year";
                                            }
                                        }
                                        @endphp
                                        
                                       
                                      
                                        <li>{{$ifs? convert($ifs->partialamount):0}}</li>
                                       
                                        @endfor
                                        </ul></td>
                                        
                                    </tr>
                                    @endforeach
                               
                            
                        </tbody>
                    </table>
                    @endif
              
            </div>
        </div>
         <div class="block">
            <div class="block-header block-header-default">
                <h3 class="block-title">Leadership Program</h3>
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
               
                  
                      <!--<button  type="submit" class="btn btn-rounded btn-outline-primary float-right" data-toggle="modal" data-target="#addSessionmodal" >Add</button>-->
               
              
                     @php
                $leaders = DB::table('individualfunds')->where('type', '2')->distinct('levelname')->get();
                
              
                @endphp
             
                @if($projects->count() > 0)
                    <table class="table table-bordered table-vcenter mt-10">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Level</th>
                                <th>Performance Level</th>
                                <th>Salary Bonus per person</th>
                                
                               
                            </tr>
                        </thead>
                        <tbody>
                            @php
                            $count = 1;
                            
                            @endphp
                            
                                    @foreach($leaders as $p)
                                    
                                    <tr>
                                        <td>{{$count++}}</td>
                                        <td>Level {{$count - 1}}</td>
                                        <td>
                                        @php
                                        $ifs = DB::table ('individualfunds')->where('individualfunds.type', 2)->where('levelname', $count - 1)->where('level_id', $count - 1)->join('incentivelevels', 'incentivelevels.id', '=', 'individualfunds.level_id')->first();
                                        @endphp
                                        
                                        
                                        {{$ifs? $ifs->level:""}}</td>
                                        <td>
                                       
                                       
                                        
                                       
                                      {{$ifs?  convert($ifs->fullamount):0}}</td>
                                        
                                        
                                    </tr>
                                    @endforeach
                               
                            
                        </tbody>
                    </table>
                    @endif
              
            </div>
        </div>
    </div>
<div class="modal" id="performancelevels" tabindex="-1" role="dialog" aria-labelledby="modal-large" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
           
            <div class="block block-themed block-transparent mb-0">
                <div class="block-header bg-primary-info">
                    <h3 class="block-title">Performance levels</h3>
                    <div class="block-options">
                        <button type="button" class="btn-block-option" data-dismiss="modal" aria-label="Close">
                            <i class="si si-close"></i>
                        </button>
                    </div>
                </div>
                <div class="block-content table-responsive">
                   
                       
                   
                                @php
                                    $levels = DB::table('incentivelevels')->orderby('min', 'desc')->get();
                                    $count = 1;
                                @endphp
                                
                                    <table class="table table-bordered " id="table">
                                        <thead>
                                            <th>#</th>
                                            <th>Level</th>
                                            <th>Minimum </th>
                                            <th>Maximum (Exclusive)</th>
                                            <th>Ebitda Minimum</th>
                                             <th>Ebitda Maximum</th>
                                             @if(Gate::any(['crud'])) 
                                             <th>Actions</th>
                                             @endif
                                        </thead>
                                        <tbody id="leveltable">
                                            
                                       
                                @foreach($levels as $level)
                                    <tr id="perflevel{{$level->id}}">
                                       <td>{{$count++}}</td> 
                                           <td  >{{$level->level}}</td> 
                                       
                                             <td  >{{$level->min}}</td>
                                       
                                             <td  >{{$level->max == 0 ? "-": $level->max}}</td>
                                        
                                             <td  >{{$level->ebitdamin}}</td>
                                     
                                             <td  >{{$level->ebitdamax == 0 ? "-": $level->ebitdamax}}</td>
                                               @if(Gate::any(['crud'])) 
                                            <td>
                                                 <button class="btn btn-sm btn-alt-secondary " onclick="launchedit()"><i class="si si-pencil"></i></button> &nbsp;
                                                  <button class="btn btn-sm btn-alt-danger table-remove" onclick="launchedit()"><i class="si si-trash"></i></button> 
                                            </td>
                                            @endif
                                        
                                    </tr>
                                @endforeach
                                 </tbody>
                                    </table>
                           
                       
                    @if(Gate::any(['crud'])) 
                      <a type="button" class="btn btn-rounded btn-outline-success mr-5 mb-5 mt-5 table-add" onclick="addLevel({{$count}})" id="addlevel">
                                <i class="fa fa-plus mr-5"></i>Add level 
                    </a>
                    @endif
                </div>
            </div>
            <div class="modal-footer">
              
                <button type="button" class="btn btn-alt-secondary" data-dismiss="modal">Close</button>
            </div>
             
        </div>
    </div>
</div>
<script>
    function launchlevels(){
    
  
      
       jQuery('#performancelevels').modal("show");
   
    
}
function addLevel(count){
  
    
    var tab = '<tr id="addlevel"><td>' + count + '</td><td >Add Level</td><td >Add Minimum value</td><td  >Add Maximum value</td><td >Add EBITDA minimum</td><td  >Add EBITDA Maximum </td><td><button class="btn btn-sm btn-alt-success " onclick="launchedit()"><i class="si si-plus"></i></button> &nbsp;<button class="btn btn-sm btn-alt-danger" onclick="launchedit()"><i class="si si-close"></i></button> </td></tr>'
     $('#leveltable').append(tab);
}
</script>

@endsection

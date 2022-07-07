<?php
// Grouping OKR based on Employees id --BEGIN--
  $userOkrs = [];
  $allOkrs = [];
  if($objectives==null)
    return;
  $currentUser = $objectives[0]->uid;
  foreach($objectives as $obj){
      $keyresults = DB::table('keyresults')->where('objective_id', '=', $obj->id)->get();
      $obj->keyresults = $keyresults;
      if($currentUser == $obj->uid){
         array_push($userOkrs,$obj);
      }
      else{
          array_push($allOkrs,$userOkrs);
          $userOkrs = [];
          $currentUser = $obj->uid;
          array_push($userOkrs,$obj);
      }
  }
  // Grouping OKR based on Employees id --END--
  $json = json_encode($allOkrs)
//   
 ?>
<div>
    <table id="dtBasicExample" class="table table-striped table-bordered table-sm" cellspacing="0" width="100%">
        <thead class="d-none">
            <tr>
                <td class="hidden"></td>
            </tr>
        </thead>
        <tbody>
            @foreach($allOkrs as $employee_objectives)
            <tr>
                <td>
                    <div class='p-10 col-11'>
                        <div style='box-shadow: none; border-radius: 6px;' class='container'>
                            <!--header -->
                            <div class='row p-10 d-flex align-items-center'>
                                <div class='col-6 d-flex pt-5 flex-row align-items-center'>

                                    @if($employee_objectives[0]->avatar == null)
                                    <div class="avatar-circle2 d-flex justify-content-center align-items-center "
                                        style="width:40px; height:40px; border-radius:50%; --ccolor: #{{$employee_objectives[0]->avatarcolor}};">
                                        <span
                                            class="pinitials1">{{$employee_objectives[0]->fname[0] . $employee_objectives[0]->lname[0]}}</span>
                                    </div>
                                    @else
                                    <img class='rounded-circle'
                                        src="https://ienetworks.co/pms/uploads/avatars/{{ $employee_objectives[0]->avatar }}"
                                        style="width:40px; height:40px;">
                                    @endif
                                    <h6 class='m-0 px-2'>
                                        {{ $employee_objectives[0]->fname." ".$employee_objectives[0]->lname }}</h6>
                                    </h6>
                                </div>
                                <!-- my team okr tab -->
                            </div>
                            <!-- body -->
                            <div class='d-flex  align-items-center'>
                                <div class="panel-body col-12">
                                    @php
                                    $objcount = 1;
                                    $okrAttainment = 0;

                                    @endphp
                                    @foreach($employee_objectives as $m)
                                    <!--iterate through metrics -->
                                    <div class="panel task-panel childClass">
                                        <div class="panel-heading">
                                            <div class="panel-title">
                                                <div class="row">

                                                    <a class="task col-11 ml-20 text-muted" data-toggle="collapse"
                                                        data-parent="#accordion" href="#metric{{$m->id}}" show>
                                                        <!-- <i class="fa fa-dot-circle-o mt-5 mr-5 text-success"></i> -->
                                                        {{$m->objective_name}}
                                                        <!-- <span class="pull-right"><i
                                                    class="si si-arrow-down text-primary"></i></span> -->
                                                    </a>
                                                    <?php
                                           $cal = round($m->attainment * 100, 2);
                                           $okrAttainment += $m->attainment;
                                           $message = $cal <=30 ? "danger":($cal <= 75 ? "warning": "success");
                                         ?>
                                                    <div
                                                        class="col-11 mt-5 d-flex justify-content-center align-items-center">
                                                        <div class="col-12 ml-10">
                                                            <div class="progress" style="height:2px; border-radius:2px">
                                                                <div class="progress-bar bg-{{$message}}"
                                                                    role="progressbar" style="width: {{$cal}}%;"
                                                                    aria-valuenow="30" aria-valuemin="0"
                                                                    aria-valuemax="100"></div>
                                                            </div>
                                                        </div>
                                                        <!-- <div class="col-md-1">
                                            <div class="text-muted">{{$cal}}%</div>
                                        </div> -->
                                                    </div>
                                                </div>

                                            </div>
                                        </div>
                                        <div class="ml-20 mt-10">
                                            <div id="metric{{$m->id}}" class="panel-collapse collapse">
                                                <div class="panel-body">
                                                    <ul id="tasks-list{{$m->id}}">
                                                        <?php
                                               $metriccount=1;
                                             ?>
                                                        @foreach($m->keyresults as $task)
                                                        @if($task->objective_id == $m->id)
                                                        <li style="list-style: none" id="task{{$task->id}}">
                                                            <div class="row">
                                                                <div class="col-sm-8">
                                                                    <p class="small text-muted"><i
                                                                            class="si si-graph mt-5 mr-5 text-info"></i>
                                                                        {{$task->keyresult_name}}
                                                                    </p>
                                                                </div>
                                                                <?php
                                                       $cal = round($task->attainment * 100, 2);
                                                       $message = $cal <=30 ? "danger":($cal <= 75 ? "warning": "success");
                                                     ?>
                                                                <div
                                                                    class="col-md-3 mt-5 px-0 d-flex flex-row align-items-center">
                                                                    <div class="flex-auto progress"
                                                                        style="height: 2px;width: 75%; border-radius:2px;">
                                                                        <div class="progress-bar m-0 progress-bar-animated bg-{{$message}}"
                                                                            role="progressbar" style="width: {{$cal}}%;"
                                                                            aria-valuenow="30" aria-valuemin="0"
                                                                            aria-valuemax="100">
                                                                        </div>
                                                                    </div>
                                                                    <div class="pl-2 text-muted">{{$cal}}%</div>
                                                                </div>

                                                            </div>
                                                        </li>
                                                        @endif
                                                        @endforeach
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                    </div>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
<script>
$(document).ready(function() {
    $('#dtBasicExample').DataTable();
    // $('.dataTables_length').addClass('bs-select');
});
</script>
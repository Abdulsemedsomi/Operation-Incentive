{{-- ADD Part  --}}
<link rel='stylesheet' href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/css/bootstrap-select.css" />
{{--Add implementation--}}

<div class="modal fade" id="forex{{$tl->task_id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content" style="border-radius: 20px;">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Participants</h5>
            </div>
            <form action="{{ route('addparticipants', $tl->task_id) }}" method="POST">
                @csrf
                <div class="form-group m-auto w-75 my-10">

                    <select class="form-control  mb-5" id='finance' multiple data-live-search='true' name="participants[]">

                        @foreach($users as $user)
                        <option value="{{ $user->id }}">{{$user->fullname}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-rounded btn-white border-dark min-width-100" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-rounded btn-info  min-width-100">Add</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.bundle.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/js/bootstrap-select.min.js"></script>

<div class="modal fade bd-example-modal-lg" id="viewdetail{{$tl->task_id}}" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="card-header  align-items-baseline">
                <h4 class="modal-title" id="exampleModalLongTitle">{{$oi->project_name}} Milestone{{$tl->milestone_name}}<span class="float-right h6 mt-10">Bonus: {{$tl->bonus}}</span></h5>
                    <p class='text-dark text font-weight-bold'>Participants: @foreach($tl->userss as $user)
                        {{$user->fullname}},
                        @endforeach
                    </p>
            </div>
            <div class="container mb-30 ">
                @php
                $taskend = App\Celoxis::get();
                foreach($taskend as $end){
                $custom_schedule_tracker = $end->custom_schedule_tracker;

                $separated = explode(" ",$custom_schedule_tracker );
                $start = $separated[0]." ". "Start" ;

                $taskStart = App\Celoxis::where('project_id',$oi->project_id)->where('milestone_name', $tl->milestone_name)->where('custom_schedule_tracker', $start)->get();
                }
                @endphp



                <div class="row border border-dark bg-info text-white" id="heading">
                    <div class="col-2 border border-dark">Schedule</div>
                    <div class="col-4 border border-dark">Task Name</div>
                    <div class="col-2 border border-dark">Planed Start Date(PSD)</div>
                    <div class="col-2 border border-dark">Planed Finish Date(PFD)</div>
                    <div class="col-2 border border-dark">Actual Finish Date(AFD)</div>
                </div>

                <div class="row border border-dark">
                    <div class="col-2 p-10 border border-dark">Task Start</div>
                    @foreach($taskStart as $ts)
                    <div class="col-4  p-10 border border-dark">{{$ts->task_name}}</div>
                    <div class="col-2  p-10 border border-dark">{{$ts->planned_start}}</div>
                    <div class="col-2  p-10 border border-dark">{{$ts->planned_finish}}</div>
                    <div class="col-2  p-10 border border-dark">{{$ts->actual_finish}}</div>
                    @endforeach
                </div>

                <div class="row border border-dark">
                    <div class="col-2 p-10 border border-dark">Task end</div>
                    <div class="col-4 p-10 border border-dark">{{$tl->task_name}}</div>
                    <div class="col-2 p-10 border border-dark">{{$tl->planned_start}}</div>
                    <div class="col-2 p-10 border border-dark">{{$tl->planned_finish}}</div>
                    <div class="col-2 p-10 border border-dark">{{$tl->actual_finish}}</div>
                </div>


            </div>
            <div class="mb-10 mr-2">
                <button type="submit" class="btn btn-rounded btn-outline-danger float-right  min-width-100 " data-dismiss="modal">Close</button>
            </div>
        </div>

    </div>
</div>
</div>
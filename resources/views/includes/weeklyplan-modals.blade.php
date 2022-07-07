<div class="modal " id="edittask{{$task->id}}" tabindex="-1" role="dialog" aria-labelledby="modal-large" aria-hidden="true">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">

                <div class="block block-themed block-transparent mb-0">
                    <div class="block-header bg-info">
                        <h3 class="block-title">Edit Task</h3>
                        <div class="block-options">
                            <button type="button" class="btn-block-option" data-dismiss="modal" aria-label="Close">
                                <i class="si si-close"></i>
                            </button>
                        </div>
                    </div>
                    <div class="block-content">
                        <div class="form-group row">
                            <label class="col-12" for="example-select">Milestone</label>
                            <div class="col-md-12">
                                @php
                                    $milestones = App\Task::where('keyresultid', $m->id)->where('isMilestone', 1)->where('status', 0)->get();
                                    $disabled = "";
                                    if($milestones->count() == 0){
                                        $disabled = "disabled";
                                    }
                                @endphp
                                <select class="form-control emilechoice" id="emilechoice{{$task->id}}" >
                                    <option value="0" disabled>Select Milestone</option>
                                    @foreach($milestones as $mile)
                                    @if($mile->id == $task->parent_task)
                                        <option value={{$mile->id}} selected>{{$mile->taskname}}</option>
                                    @else
                                        <option value={{$mile->id}}>{{$mile->taskname}}</option>
                                    @endif
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    <div class="form-group row">
                            <label class="col-12" for="example-email-input">Task</label>
                            <div class="col-md-12">
                                <input type="text" class="form-control etaskvalue" id="etaskvalue{{$task->id}}" value='{{$task->taskname}}' placeholder="Task"  >
                            </div>
                        </div>

                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-alt-success taskedit"   {{$disabled}} data-id = {{$task->id}}>
                        <i class="fa fa-pencil"></i> Edit Task
                    </button>
                    <button type="button" class="btn btn-alt-secondary" data-dismiss="modal">Close</button>
                </div>

        </div>
    </div>
</div>



<div class="modal " id="deletetask{{$task->id}}" tabindex="-1" role="dialog" aria-labelledby="modal-large" aria-hidden="true">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">

                <div class="block block-themed block-transparent mb-0">
                    <div class="block-header bg-danger">
                        <h3 class="block-title">Delete Task</h3>
                        <div class="block-options">
                            <button type="button" class="btn-block-option" data-dismiss="modal" aria-label="Close">
                                <i class="si si-close"></i>
                            </button>
                        </div>
                    </div>
                    <div class="block-content">
                        <div class="form-group row">
                            <div class="modal-body">
                                <label for="inputLink" class=" control-label col-md-push-1" >Are you sure you want to delete the task <em>{{$task->taskname}}</em> ?</label>
                            </div>
                        </div>


                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-alt-danger taskdelete"   data-id = '{{$task->id}}'>
                        Delete Task
                    </button>
                    <button type="button" class="btn btn-alt-secondary" data-dismiss="modal">Close</button>
                </div>

        </div>
    </div>
</div>

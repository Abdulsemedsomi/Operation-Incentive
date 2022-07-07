<div class="modal " id="editsubtask{{$subtask->id}}" tabindex="-1" role="dialog" aria-labelledby="modal-large" aria-hidden="true">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
                <div class="block block-themed block-transparent mb-0">
                    <div class="block-header bg-info">
                        <h3 class="block-title">Edit Subtask</h3>
                        <div class="block-options">
                            <button type="button" class="btn-block-option" data-dismiss="modal" aria-label="Close">
                                <i class="si si-close"></i>
                            </button>
                        </div>
                    </div>
                    <div class="block-content">
                        <div class="form-group row">
                            <label class="col-12" for="example-email-input">Task</label>
                            <div class="col-md-12">
                                <input type="text" class="form-control taskvalue"   value="{{$task->taskname}}" disabled >
                            </div>
                        </div>
                        <div class="form-group row">
                                <label class="col-12" for="example-email-input">Sub Task</label>
                                <div class="col-md-12">
                                    <input type="text" class="form-control esubtaskvalue" id="esubtaskvalue{{$subtask->id}}" value="{{$subtask->subtask_name}}"  placeholder="Add Task">
                                </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-alt-success subtaskedit"    data-id = {{$subtask->id}}>
                        <i class="fa fa-pencil"></i> Edit subask
                    </button>
                    <button type="button" class="btn btn-alt-secondary" data-dismiss="modal">Close</button>
                </div>

        </div>
    </div>
</div>



<div class="modal " id="deletesubtask{{$subtask->id}}" tabindex="-1" role="dialog" aria-labelledby="modal-large" aria-hidden="true">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">

                <div class="block block-themed block-transparent mb-0">
                    <div class="block-header bg-danger">
                        <h3 class="block-title">Delete subask</h3>
                        <div class="block-options">
                            <button type="button" class="btn-block-option" data-dismiss="modal" aria-label="Close">
                                <i class="si si-close"></i>
                            </button>
                        </div>
                    </div>
                    <div class="block-content">
                        <div class="form-group row">
                            <div class="modal-body">
                                <label for="inputLink" class=" control-label col-md-push-1" >Are you sure you want to delete the subtask <em>{{$subtask->subtask_name}}</em> ?</label>
                            </div>
                        </div>


                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-alt-danger subtaskdelete"    data-id = {{$subtask->id}}>
                        Delete Task
                    </button>
                    <button type="button" class="btn btn-alt-secondary" data-dismiss="modal">Close</button>
                </div>

        </div>
    </div>
</div>

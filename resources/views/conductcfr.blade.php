@extends('layouts.backend')
@section('content')
<div class="container mt-20">
    <div class="block">
        <div class="block-content">
            <h3>CFR Questions</h3>
            <hr>
            <div class="form-group row">
                <label class="col-md-1 mt-5" for="example-select">Employee</label>
                <div class="col-md-3">
                    <select class="form-control round" id="sessionselect" name="session-select">
                            <option value="0" default disabled>Please select Employee</option>
                    </select>
                </div>
            </div>
        </div>
        <div class="block-content">
            <ol>
                <h5><li>In which engagement and KPI measures does he/she has got reprimand in this quarter please mention and review each reprimand issued?</li></h5>
                <textarea class="form-control round" rows="5" id="response" style="outline: none; "></textarea>
                <h5><li>In which engagement and KPI measures does he/she has got appreciation in this quarter please mention and review each appreciation issued?</li></h5>
                <textarea class="form-control round" rows="5" id="response2" style="outline: none; "></textarea>
                <h5><li>What could be the reason for the cause of poor performance or the reprimand certificates issued in this quarter?</li></h5>
                <textarea class="form-control round" rows="5" id="response"  style="outline: none; "></textarea>
                <h5><li>What are the two or three things could your manager do differently to better manage and increase your performance in the next quarter?</li></h5>
                <textarea class="form-control round" rows="5" id="response"  style="outline: none; "></textarea>
                <h5><li>What accomplishment this quarter are you most proud of from your goals/tasks.</li></h5>
                <textarea class="form-control round" rows="5" id="response"  style="outline: none; "></textarea>
                <h5><li>What personal strength help you do your job effectively?</li></h5>
                <textarea class="form-control round" rows="5" id="response"  style="outline: none; "></textarea>
                <h5><li>What motivates you to get your job done?</li></h5>
                <textarea class="form-control round" rows="5" id="response"  style="outline: none; "></textarea>
                <h5><li>Managers Feedback</li></h5>
                <textarea class="form-control round" rows="5" id="response"  style="outline: none; "></textarea>
            </ol>
        </div>
        <div class="block-content">
            <h3>Action Plans</h3>
            <hr>
            <div class="ml-50 pull-right">
                <button type="button" class="btn btn-rounded btn-sm btn-success" data-toggle="modal" data-target="#add-action">
                    <i class="si si-plus"></i>  Add Action Plan
                </button>
            </div>
            <table class="table table-hover table-vcenter">
                <thead>
                    <tr>
                        <th class="text-center">#</th>
                        <th class="text-left">Issue raised</th>
                        <th class="text-left">What will be done? Steps and tasks</th>
                        <th class="text-left">Who will do it?</th>
                        <th class="text-left">who will need to be involved?</th>
                        <th class="text-left">What resource are needed?</th>
                        <th class="text-left">Status</th>
                        <th class="text-left">Remarks</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <th class="text-center" scope="row">1</th>
                        <td>More external projects for BAI</td>
                        <td>Department managers/Team Lead jointly with Sales team to work to build team expertise and solution development</td>
                        <td>Hawi Tesfaye</td>
                        <td>People Department</td>
                        <td>None</td>
                        <td>Open</td>
                        <td>Udates</td>
                        <td class="d-none d-sm-table-cell">
                            <div class="row">
                                <button type="button" class="btn btn-sm btn-secondary mr-10" data-toggle="modal" data-target="#edit-action">
                                <i class="si si-pencil"></i>
                            </button>
                            <a type="button" class="btn btn-sm btn-secondary" data-toggle="tooltip" title="Delete">
                                <i class="si si-trash"></i>
                            </a>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="block-content" align="right">
            <button type="button" class="btn btn-rounded btn-lg btn-info">Done</button>
            <a type="button" class="btn btn-rounded btn-lg btn-secondary" href="{{ url('cfrpage') }}">Cancel</a>
        </div>
        <div class="block-content"></div>
    </div>
</div>
<div class="modal" id="add-action" tabindex="-1" role="dialog" aria-labelledby="add-action" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="block block-themed block-transparent mb-0">
                <div class="block-header bg-info">
                    <h3 class="block-title">Add Action Plan</h3>
                    <div class="block-options">
                        <button type="button" class="btn-block-option" data-dismiss="modal" aria-label="Close">
                            <i class="si si-close"></i>
                        </button>
                    </div>
                </div>
                    <div class="block-content">
                        <div class="form-group row">
                            <label class="col-12" for="issues">Issues Raised</label>
                            <div class="col-12">
                                <textarea class="form-control round" id="issues-raised" name="issues" rows="3"></textarea>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-12" for="steps">What will be done? Steps and tasks</label>
                            <div class="col-12">
                                <textarea class="form-control round" id="steps" name="steps" rows="3"></textarea>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-12" for="who-will">Who will do it?</label>
                            <div class="col-md-12">
                                <input type="text" class="form-control round" id="who-will" name="who-will">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-12" for="who-involved">who will need to be involved?</label>
                            <div class="col-md-12">
                                <input type="text" class="form-control round" id="who-involved" name="who-involved">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-12" for="resource">What resource are needed?</label>
                            <div class="col-12">
                                <textarea class="form-control round" id="resource" name="resource" rows="3"></textarea>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-12" for="status">Status</label>
                            <div class="col-md-12">
                                <select class="form-control round" id="status" name="status">
                                    <option value="0" disabled>Please select Status</option>
                                    <option value="Open">Open</option>
                                    <option value="In Progress">In Progress</option>
                                    <option value="Done">Done</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-12" for="remark">Remarks</label>
                            <div class="col-12">
                                <textarea class="form-control round" id="remark" name="remark" rows="3"></textarea>
                            </div>
                        </div>
                    </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-rounded btn-alt-info" data-dismiss="modal">Done</button>
                    <button type="button" class="btn btn-rounded btn-alt-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
</div>


<div class="modal" id="edit-action" tabindex="-1" role="dialog" aria-labelledby="edit-action" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="block block-themed block-transparent mb-0">
                <div class="block-header bg-info">
                    <h3 class="block-title">Edit Action Plan</h3>
                    <div class="block-options">
                        <button type="button" class="btn-block-option" data-dismiss="modal" aria-label="Close">
                            <i class="si si-close"></i>
                        </button>
                    </div>
                </div>
                    <div class="block-content">
                        <div class="form-group row">
                            <label class="col-12" for="issues">Issues Raised</label>
                            <div class="col-12">
                                <textarea class="form-control round" id="issues-raised" name="issues" rows="3">More external projects for BAI</textarea>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-12" for="steps">What will be done? Steps and tasks</label>
                            <div class="col-12">
                                <textarea class="form-control round" id="steps" name="steps" rows="3">Department managers/Team Lead jointly with Sales team to work to build team expertise and solution development</textarea>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-12" for="who-will">Who will do it?</label>
                            <div class="col-md-12">
                                <input type="text" class="form-control round" id="who-will" name="who-will" value="Hawi Tesfaye">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-12" for="who-involved">who will need to be involved?</label>
                            <div class="col-md-12">
                                <input type="text" class="form-control round" id="who-involved" name="who-involved" value="People Department">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-12" for="resource">What resource are needed?</label>
                            <div class="col-12">
                                <textarea class="form-control round" id="resource" name="resource" rows="3">None</textarea>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-12" for="status">Status</label>
                            <div class="col-md-12">
                                <select class="form-control round" id="status" name="status">
                                    <option value="0" disabled>Please select Status</option>
                                    <option value="Open" selected>Open</option>
                                    <option value="In Progress">In Progress</option>
                                    <option value="Done">Done</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-12" for="remark">Remarks</label>
                            <div class="col-12">
                                <textarea class="form-control round" id="remark" name="remark" rows="3">Updates</textarea>
                            </div>
                        </div>
                    </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-rounded btn-alt-info" data-dismiss="modal">Done</button>
                    <button type="button" class="btn btn-rounded btn-alt-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
</div>
 <script>
    tinymce.init({
      selector: 'textarea',
      branding: false,
      height: 300,
      statusbar: false,
      plugins: 'lists',
      toolbar: "formatselect | bold italic backcolor forecolor| alignleft aligncenter alignright alignjustify | bullist numlist | '",
      menubar: 'false'
      });
  </script>
@endsection

{{-- Modal for Reprimand --}}
<div class="modal" id="modal-large" tabindex="-1" role="dialog" aria-labelledby="modal-large" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">

        <div class="modal-content">
            <form action="{{ route('fill_engagement.store') }}" method="POST">
                @csrf
            <div class="block block-themed block-transparent mb-0">
                <div class="block-header bg-primary">
                    <h3 class="block-title">Discipline</h3>
                    <div class="block-options">
                        <button type="button" class="btn-block-option" data-dismiss="modal" aria-label="Close">
                            <i class="si si-close"></i>
                        </button>
                    </div>
                </div>
                <div class="container">
                    <div class="block-content">
                        <input type="hidden" name="Perspective" value=1>
                        <input type="hidden" name="plan_id" value="{{$plan->id}}">

                        <div class="form-group row">
                            <label class="col-12" for="example-select">Type</label>
                            <select class="form-control col-md-12 round" id="reptype" name="apptype">
                                <option selected disabled>Select type</option>
                                <option value="1">Engagement</option>
                                <option value="2">KPI</option>
                            </select>
                        </div>
                        <div class="form-group row">
                            <label class="mr-10 mt-5" for="example-select">Position</label>
                            <select class="form-control col-md-5 mr-10 round" id="positionchoicer" name="position" disabled>
                                <option selected disabled>Select Position</option>

                            </select>
                            <label class="mr-10 mt-5" for="example-select">Perspective</label>
                            <select class="form-control col-md-4 round" id="perspectivechoicer" name="pperspective" disabled>
                                <option selected disabled>Select pesrspective</option>

                            </select>
                        </div>

                    <div class="form-group row">
                        <label class="col-12" for="example-select">Objective</label>
                        <select class="form-control round appmod" id="objectiveselr" name="objective" disabled>
                            <option selected disabled>Select Objective</option>

                        </select>
                    </div>
                        <div class="form-group row">
                            <label class="col-12" for="example-select">Detailed Reason</label>
                            <textarea class="form-control round" id="detailedreasonr" name="Reason" rows="4" placeholder="Detailed Reason" disabled></textarea>
                        </div>
                        <div class="form-group row">
                            <label class="col-12" for="example-select">Action</label>
                            <input type="text" class="form-control round " placeholder="Action" name="Action" id="actionr" disabled>
                        </div>
                        <div class="form-group row">
                            <label class="col-12" for="example-select">Expected Improvement</label>
                            <textarea class="form-control round" id="improve" name="Improvement" rows="4" placeholder="Expected Improvement" disabled></textarea>
                        </div>
                        <div class="form-group row">
                            <label class="col-12" for="example-select">CC</label>
                            <select class="form-control round" id="ccr" name="ccr" disabled>
                               
                                <option value="1">Eliyas Teshager</option>
                                 <option value="2">Biruk Mathewos</option>
                                  <option value="3">Redate Birhanu</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-alt-success" >
                        <i class="fa fa-check"></i> Submit
                    </button>
                    <button type="button" class="btn btn-alt-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
            </form>
        </div>
    </div>
    </div>
{{-- Modal for Appericiation --}}
<div class="modal" id="modal-large-app" tabindex="-1" role="dialog" aria-labelledby="modal-large-app" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <form action="{{ route('fill_engagement.store') }}" method="POST">
            @csrf

        <div class="modal-content">

            <div class="block block-themed block-transparent mb-0">
                <div class="block-header bg-primary">
                    <h3 class="block-title">Excellence</h3>
                    <div class="block-options">
                        <button type="button" class="btn-block-option" data-dismiss="modal" aria-label="Close">
                            <i class="si si-close"></i>
                        </button>
                    </div>
                </div>
                <div class="container">
                <div class="block-content">
                    <input type="hidden" name="Perspective" value=0>
                    <input type="hidden" name="plan_id" value="{{$plan->id}}">
                    <div class="form-group row">
                            <label class="col-12" for="example-select">Type</label>
                            <select class="form-control col-md-12 round" id="apptype" name="apptype">
                                <option selected disabled>Select type</option>
                                <option value="1">Engagement</option>
                                <option value="2">KPI</option>
                            </select>
                        </div>
                        <div class="form-group row">
                            <label class="mr-10 mt-5" for="example-select">Position</label>
                            <select class="form-control col-md-5 mr-10 round" id="positionchoice" name="position" disabled>
                                <option selected disabled>Select Position</option>

                            </select>
                            <label class="mr-10 mt-5" for="example-select">Perspective</label>
                            <select class="form-control col-md-4 round" id="perspectivechoice" name="pperspective" disabled>
                                <option selected disabled>Select pesrspective</option>

                            </select>
                        </div>

                    <div class="form-group row">
                        <label class="col-12" for="example-select">Objective</label>
                        <select class="form-control round appmod" id="objectivesel" name="objective" disabled>
                            <option selected disabled>Select Objective</option>

                        </select>
                    </div>
                    <div class="form-group row">
                        <label class="col-12" for="example-select ">Detailed Reason</label>
                        <textarea class="form-control round appmod" id="detailedreason" name="Reason" rows="4" placeholder="Detailed Reason" disabled></textarea>
                    </div>
                    <div class="form-group row">
                        <label class="col-12" for="example-select">CC</label>
                        <select class="form-control round appmod" id="cc" name="CC" disabled>
                          
                            <option value="1">Eliyas Teshager</option>
                            <option value="2">Biruk Mathewos</option>
                             <option value="3">Redate Birhanu</option>
                        </select>
                    </div>
                    </div>
                </div>

            <div class="modal-footer">
                <button type="submit" class="btn btn-alt-success">
                    <i class="fa fa-check"></i> Submit
                </button>
                <button type="button" class="btn btn-alt-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>

        </div>
        </form>
    </div>
</div>

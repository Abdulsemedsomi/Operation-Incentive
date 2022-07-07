
<div class="modal fade" id="modal-fadein" tabindex="-1" role="dialog" aria-labelledby="modal-fadein" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">

            <form action = "{{route('addform', $kpid) }}"   method="POST">
                @csrf
            <div class="block block-themed block-transparent mb-0">
                <div class="block-header">
                    <h3 class="block-title">Add KPI</h3>
                    <div class="block-options">
                        <button type="button" class="btn-block-option" data-dismiss="modal" aria-label="Close">
                            <i class="si si-close"></i>
                        </button>
                    </div>
                </div>
                <div class="block-content">
                    @if($message = Session::get('error'))
                    <div class="alert alert-danger alert-block">
                        <button type="button" class="close" data-dismiss="alert">×</button>
                               <strong>{{ $message }}</strong>
                       </div>
                    @endif
                    <label for="example-text-input">Select Expectation Type</label>
                    <select class="form-control my-10" id="example-select" name="expectation">
                        <option value="0">Please select</option>
                        <option value="1">General Expectation</option>
                        <option value="2">Department Expectation</option>
                    </select>
                    <div class="form-group row">
                        <label class="col-12" for="example-text-input" >Criteria</label>
                        <div class="col-md-9">
                            <input type="text" class="form-control round" id="example-text-input" name="criteria" placeholder="Criteria">
                        </div>
                    </div>
                    <div class="container my-10 subcriteriadata" id="subcriteriadata" data-count=1 >
                        <label class="col-11" for="example-text-input">Sub-criteria</label>
                        <div class="form-group row ">

                            <div class="col-md-10">
                                <input type="text" class="form-control" id="example-text-input" name="sub_criteria1" placeholder="Sub-criteria">
                            </div>
                            <div class="col-1 text-center mt-5">
                                <a href="#" id="addsubcriteria" ><i class="fa fa-plus" aria-hidden="true"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-alt-success">
                    <i class="fa fa-check"></i> Add
                </button>
                <button type="button" class="btn btn-alt-secondary" data-dismiss="modal">Cancel</button>

            </div>
        </form>


        </div>
    </div>
</div>

{{-- Edit --}}
<div class="modal fade" id="editkpiform" tabindex="-1" role="dialog" aria-labelledby="editkpiform" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">

            <form action = "{{route('addform', $kpid) }}"   method="POST">
                @csrf
            <div class="block block-themed block-transparent mb-0">
                <div class="block-header">
                    <h3 class="block-title">Edit KPI</h3>
                    <div class="block-options">
                        <button type="button" class="btn-block-option" data-dismiss="modal" aria-label="Close">
                            <i class="si si-close"></i>
                        </button>
                    </div>
                </div>
                <div class="block-content">
                    @if($message = Session::get('error'))
                    <div class="alert alert-danger alert-block">
                        <button type="button" class="close" data-dismiss="alert">×</button>
                               <strong>{{ $message }}</strong>
                       </div>
                    @endif
                    <label for="example-text-input">Select Expectation Type</label>
                    <select class="form-control my-10" id="example-select" name="expectation">
                        <option value="0">Please select</option>
                        <option value="1">General Expectation</option>
                        <option value="2">Department Expectation</option>
                    </select>
                    <div class="form-group row">
                        <label class="col-12" for="example-text-input" >Criteria</label>
                        <div class="col-md-9">
                            <input type="text" class="form-control" id="example-text-input" name="criteria" placeholder="Criteria">
                        </div>
                    </div>
                    <div class="container my-10 subcriteriadata" id="subcriteriadata" data-count=1 >
                        <label class="col-11" for="example-text-input">Sub-criteria</label>
                        <div class="form-group row ">

                            <div class="col-md-10">
                                <input type="text" class="form-control" id="example-text-input" name="sub_criteria1" placeholder="Sub-criteria">
                            </div>
                            <div class="col-1 text-center mt-5">
                                <a href="#" id="addsubcriteria" ><i class="fa fa-plus" aria-hidden="true"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-alt-success">
                    <i class="fa fa-check"></i> Add
                </button>
                <button type="button" class="btn btn-alt-secondary" data-dismiss="modal">Cancel</button>

            </div>
        </form>


        </div>
    </div>
</div>

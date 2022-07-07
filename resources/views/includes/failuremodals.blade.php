
<div class="modal fade addFailuremodal"  id="addFailuremodal" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="block block-themed block-transparent mb-0">
            <div class="block-header bg-info">
                <h3 class="block-title">Add Failure target</h3>
                <div class="block-options">
                    <button type="button" class="btn-block-option" data-dismiss="modal" aria-label="Close">
                        <i class="si si-close"></i>
                    </button>
                </div>
            </div>
            <form class="smodalFormData" id="smodalFormData" action = "{{ route('failures.store') }}"   method="post">
                @csrf
                <div class="modal-content">
                    <div class="modal-body" id="addbod">
                        <div class="form-group row">
                            <label for="failure_name" class="col-md-3 col-form-label text-md-right">target</label>
                            <div class="col-md-8">
                                <input type="text" id="sname" class="form-control round" name="target" required autocomplete="off">
                            </div>
                        </div>
                    </div>
                <!--Footer-->
                    <div class="modal-footer">
                        <button type="submit " class="btn btn-rounded btn-outline-success addfailurebutton">
                            Add
                        </button>
                        <button type="button" class="btn btn-rounded btn-outline-danger" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade addFailuremodal"  id="editFailuremodal" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="block block-themed block-transparent mb-0">
            <div class="block-header bg-info">
                <h3 class="block-title">Edit Failure target</h3>
                <div class="block-options">
                    <button type="button" class="btn-block-option" data-dismiss="modal" aria-label="Close">
                        <i class="si si-close"></i>
                    </button>
                </div>
            </div>
            <form  id="editfmodalFormData"   method="post">
                @csrf
                    <input type="hidden" name="_method" value="put" />
                <div class="modal-content">
                    <div class="modal-body">
                        <div class="form-group row">
                            <label for="failure_name" class="col-md-3 col-form-label text-md-right" >target</label>
                            <div class="col-md-8">
                                <input type="text" class="form-control round" id="editfailure" name="target" required autocomplete="off">
                            </div>
                        </div>
                    </div>
                <!--Footer-->
                    <div class="modal-footer">
                        <button type="submit " class="btn btn-rounded btn-outline-success editFailure">
                            Edit
                        </button>
                        <button type="button" class="btn btn-rounded btn-outline-danger" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

 <!-- delete modal -->
 <div class="modal fade" id="deleteFailuremodal" aria-hidden="true">
    <div class="modal-dialog">
        <form id="deletefmodalFormData"   method="post">
            @csrf
            <input type="hidden" name="_method" value="delete" />
            <div class="block block-themed block-transparent mb-0">
                <div class="block-header bg-danger">
                    <h3 class="block-title">Delete Failure Target</h3>
                    <div class="block-options">
                        <button type="button" class="btn-block-option" data-dismiss="modal" aria-label="Close">
                            <i class="si si-close"></i>
                        </button>
                    </div>
                </div>
                <div class="modal-content">
                    <div class="modal-body">
                        <label for="inputLink" class=" control-label col-md-push-1" id="deletefailurelabel"></label>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-rounded btn-outline-danger" id="failure-delete" value="">Delete</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

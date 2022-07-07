
<div class="modal fade addSessionmodal"  id="addSessionmodal" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="block block-themed block-transparent mb-0">
            <div class="block-header bg-info">
                <h3 class="block-title">Add Session</h3>
                <div class="block-options">
                    <button type="button" class="btn-block-option" data-dismiss="modal" aria-label="Close">
                        <i class="si si-close"></i>
                    </button>
                </div>
            </div>
            <form class="smodalFormData" id="smodalFormData" action = "{{ route('sessions.store') }}"   method="post">
                @csrf
                <div class="modal-content">
                    <div class="modal-body" id="addbod">
                    <div class="form-group row">
                        <label for="session_name" class="col-md-4 col-form-label text-md-right">Session Name</label>
                        <div class="col-md-6">
                            <input type="text" id="sname" class="form-control round" name="session_name" required>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="phone_number" class="col-md-4 col-form-label text-md-right">Start Date</label>
                        <div class="col-md-6">
                            <input type="date" id="startdate" class="form-control round" name="start_date" required>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="phone_number" class="col-md-4 col-form-label text-md-right">End Date</label>
                        <div class="col-md-6">
                            <input type="date" id="enddate" class="form-control round" name="end_date" required>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="phone_number" class="col-md-4 col-form-label text-md-right">Status</label>
                        <div class="col-md-6">
                        <select class=" form-control" id="statusselect" name="status">
                            <option value="Active">Active</option>
                            <option value="Closed">Closed</option>
                        </select>
                        </div>
                    </div>


                </div>
                <!--Footer-->
                <div class="modal-footer">
                    <button type="submit " class="btn btn-rounded btn-outline-success addsessionbutton">
                        Add
                    </button>
                    <button type="button" class="btn btn-rounded btn-outline-danger" data-dismiss="modal">Close</button>
                </div>
            </form>
            </div>
        </div>
    </div>
</div>

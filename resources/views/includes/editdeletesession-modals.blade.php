<!--edit modal-->
<div class="modal fade editSessionmodal" id ="editSessionmodal{{$session->id}}" aria-hidden="true" >
    <div class="modal-dialog" role="document">
        <div class="block block-themed block-transparent mb-0">
            <div class="block-header bg-info">
                <h3 class="block-title">Edit Session</h3>
                <div class="block-options">
                    <button type="button" class="btn-block-option" data-dismiss="modal" aria-label="Close">
                        <i class="si si-close"></i>
                    </button>
                </div>
            </div>

            <form class="editsmodalFormData" id="editsmodalFormData{{$session->id}}" action = "{{ route('sessions.update', $session->id) }}"   method="post" data-id="{{$session->id}}">
                @csrf
                <input type="hidden" name="_method" value="put" />
                <div class="modal-content">
                    <div class="modal-body editbod">
                        <div class="form-group row">
                            <label for="session_name" class="col-md-4 col-form-label text-md-right">Session Name</label>
                            <div class="col-md-6">
                                <input type="text" id="esname{{$session->id}}" class="form-control round" name="session_name" value="{{$session->session_name}}"  required>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="phone_number" class="col-md-4 col-form-label text-md-right">Start Date</label>
                            <div class="col-md-6">
                                <input type="date" id="estartdate{{$session->id}}" class="form-control round"  name ="start_date" value="{{$session->start_date}}" required>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="phone_number" class="col-md-4 col-form-label text-md-right">End Date</label>
                            <div class="col-md-6">
                                <input type="date" id="eenddate{{$session->id}}" class="form-control round" name="end_date" value="{{$session->end_date}}"  required>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="phone_number" class="col-md-4 col-form-label text-md-right">Status</label>
                            <div class="col-md-6">
                                <select class=" form-control estatus" id="estatus{{$session->id}}" name="status">
                                    <option value="Active" {{$session->status=='Active'?'selected':''}}>Active</option>
                                    <option value="Closed" {{$session->status=='Closed'?'selected':''}}>Closed</option>
                                </select>
                            </div>
                        </div>


                    </div>
                    <div class="modal-footer">
                        <button type="submit " class="btn btn-rounded btn-outline-secondary editSessionbutton">
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
 <div class="modal fade" id="deleteSessionmodal{{$session->id}}" aria-hidden="true">
    <div class="modal-dialog">
        <form id="delete" action = "{{ route('sessions.destroy', $session->id) }}"   method="post">
            @csrf
            <input type="hidden" name="_method" value="delete" />
            <div class="block block-themed block-transparent mb-0">
                <div class="block-header bg-danger">
                    <h3 class="block-title">Delete Session</h3>
                    <div class="block-options">
                        <button type="button" class="btn-block-option" data-dismiss="modal" aria-label="Close">
                            <i class="si si-close"></i>
                        </button>
                    </div>
                </div>
                <div class="modal-content">
                    <div class="modal-body">
                        <label for="inputLink" class=" control-label col-md-push-1" id="deletelabel">Are you sure you want to delete the session: {{$session->session_name}}</label>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-rounded btn-outline-danger" id="session-delete" value="">Delete</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

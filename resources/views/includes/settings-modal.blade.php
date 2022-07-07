{{-- Add role --}}
<div class="modal" id="modal-large" tabindex="-1" role="dialog" aria-labelledby="modal-large" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="block block-themed block-transparent mb-0">
                <form action = "{{ route('roles.store') }}"   method="post">
                    @csrf
                    <div class="block-header bg-info">
                        <h3 class="block-title">Add Role</h3>
                        <div class="block-options">
                            <button type="button" class="btn-block-option" data-dismiss="modal" aria-label="Close">
                                <i class="si si-close"></i>
                            </button>
                        </div>
                    </div>
                    <div class="block-content">
                        <div class="form-group row">
                            <label class="col-12" for="example-text-input">Role Name</label>
                            <div class="col-md-9">
                                <input type="text" class="form-control" id="rolename" name="rolename" placeholder="Role Name" autocomplete="off">
                            </div>
                        </div>
                        <label class="mb-10" for="example-text-input">Permisions</label>
                        <div class="row mb-10">
                            <div class="col-md-6">
                                <div class="custom-control custom-checkbox mb-5 ">
                                    <input class="custom-control-input" type="checkbox" name="kpimodule" id="kpimodule" value="1">
                                    <label class="custom-control-label" for="kpimodule"> <b class="mb-10" >Manage KPI</b></label>
                                </div>
                               
                            </div>
                            <div class="col-md-6">
                                <div class="custom-control custom-checkbox mb-5 ">
                                    <input class="custom-control-input" type="checkbox" name="engagement" id="engagement" value="1">
                                    <label class="custom-control-label" for="engagement"> <b class="mb-10">Manage Engagement</b></label>
                                </div>
                               
                            </div>
                        </div>
                        <div class="row mb-10">
                            <div class="col-md-6">
                                <div class="custom-control custom-checkbox mb-5 ">
                                    <input class="custom-control-input" type="checkbox" name="fillkpiproject" id="fillkpiproject" value="1">
                                    <label class="custom-control-label" for="fillkpiproject"> <b class="mb-10" >Fill Kpi on project</b></label>
                                </div>
                               
                            </div>
                            <div class="col-md-6">
                                <div class="custom-control custom-checkbox mb-5 ">
                                    <input class="custom-control-input" type="checkbox" name="fillengageproject" id="fillengageproject" value="1">
                                    <label class="custom-control-label" for="fillengageproject"> <b class="mb-10">Fill Engagement on project</b></label>
                                </div>
                               
                            </div>
                        </div>
                        <div class="row mb-10">
                            <div class="col-md-6">
                                <div class="custom-control custom-checkbox mb-5 ">
                                    <input class="custom-control-input" type="checkbox" name="fillkpiteam" id="fillkpiteam" value="1">
                                    <label class="custom-control-label" for="fillkpiteam"> <b class="mb-10" >Fill Kpi on team</b></label>
                                </div>
                               
                            </div>
                            <div class="col-md-6">
                                <div class="custom-control custom-checkbox mb-5 ">
                                    <input class="custom-control-input" type="checkbox" name="fillengageteam" id="fillengageteam" value="1">
                                    <label class="custom-control-label" for="fillengageteam"> <b class="mb-10">Fill Engagement on team</b></label>
                                </div>
                               
                            </div>
                        </div>
                        <div class="row mb-10">
                            <div class="col-md-6">
                                <div class="custom-control custom-checkbox mb-5 ">
                                    <input class="custom-control-input" type="checkbox" name="okr" id="okr" value="1">
                                    <label class="custom-control-label" for="okr"> <b class="mb-10" >OKR</b></label>
                                </div>
                                <div class="custom-control custom-checkbox mb-5 ml-20">
                                    <input class="custom-control-input okr" type="checkbox" name="addSession" id="addSession" value="1">
                                    <label class="custom-control-label" for="addSession">Add Sessions</label>
                                </div>
                                <div class="custom-control custom-checkbox mb-5 ml-20">
                                    <input class="custom-control-input okr" type="checkbox" name="editSession" id="editSession" value="1">
                                    <label class="custom-control-label" for="editSession">Edit Sessions</label>
                                </div>
                                <div class="custom-control custom-checkbox mb-5 ml-20">
                                    <input class="custom-control-input okr" type="checkbox" name="deleteSession" id="deleteSession" value="1">
                                    <label class="custom-control-label" for="deleteSession">Delete Sessions</label>
                                </div>
                                <div class="custom-control custom-checkbox mb-5 ml-20">
                                    <input class="custom-control-input okr" type="checkbox" name="addObjective" id="addObjective" value="1">
                                    <label class="custom-control-label" for="addObjective">Add objective</label>
                                </div>
                                <div class="custom-control custom-checkbox mb-5 ml-20">
                                    <input class="custom-control-input okr" type="checkbox" name="assignokr" id="assignokr" value="1">
                                    <label class="custom-control-label" for="assignokr">Assign OKR for other Employees</label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="custom-control custom-checkbox mb-5 ">
                                    <input class="custom-control-input" type="checkbox" name="otherhr" id="otherhr" value="1">
                                    <label class="custom-control-label" for="otherhr"> <b class="mb-10" >Other HR Modules</b></label>
                                </div>
                                <div class="custom-control custom-checkbox mb-5 ml-20">
                                    <input class="custom-control-input hr" type="checkbox" name="rolemanage" id="rolemanage" value="1">
                                    <label class="custom-control-label" for="rolemanage">Role Management</label>
                                </div>
                                <div class="custom-control custom-checkbox mb-5 ml-20">
                                    <input class="custom-control-input hr" type="checkbox" name="addStamp" id="addStamp" value="1">
                                    <label class="custom-control-label" for="addStamp">Add Stamp</label>
                                </div>
                                <div class="custom-control custom-checkbox mb-5 ml-20">
                                    <input class="custom-control-input hr" type="checkbox" name="addSignature" id="addSignature" value="1">
                                    <label class="custom-control-label" for="addSignature">Add Signature </label>
                                </div>
                                <div class="custom-control custom-checkbox mb-5 ml-20">
                                    <input class="custom-control-input hr" type="checkbox" name="employees" id="employees" value="1">
                                    <label class="custom-control-label" for="employees">Manage Employees </label>
                                </div>
                                <div class="custom-control custom-checkbox mb-5 ml-20">
                                    <input class="custom-control-input hr" type="checkbox" name="teams" id="teams" value="1">
                                    <label class="custom-control-label" for="teams">Manage Teams </label>
                                </div>
                            </div>
                        </div>
                        <div class="row mb-10">
                            <div class="col-md-6">
                                <div class="custom-control custom-checkbox mb-5 ">
                                    <input class="custom-control-input" type="checkbox" name="projects" id="projects" value="1">
                                    <label class="custom-control-label" for="projects"> <b class="mb-10">Projects</b></label>
                                </div>
                                <div class="custom-control custom-checkbox mb-5 ">
                                    <input class="custom-control-input" type="checkbox" name="manageprojects" id="manageprojects" value="1">
                                    <label class="custom-control-label" for="manageprojects"> <b class="mb-10" >Manage Projects</b></label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="custom-control custom-checkbox mb-5 ">
                                    <input class="custom-control-input" type="checkbox" name="visualization" id="visualization" value="1">
                                    <label class="custom-control-label" for="visualization"> <b class="mb-10" >Visualization</b></label>
                                </div>
                                <div class="custom-control custom-checkbox mb-5 ">
                                    <input class="custom-control-input" type="checkbox" name="generalreports" id="generalreports" value="1">
                                    <label class="custom-control-label" for="generalreports"> <b class="mb-10">General Reports</b></label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-rounded btn-outline-success min-width-125 mb-10" >
                            <i class="fa fa-check"></i> Create
                        </button>
                        <button type="button" class="btn btn-rounded btn-outline-danger min-width-125 mb-10" data-dismiss="modal">Close</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>



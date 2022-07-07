<div class="modal fade" id="editRole{{$role->id}}" tabindex="-1" role="dialog" aria-labelledby="modal-large" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="block block-themed block-transparent mb-0">
                <form action = "{{ route('roles.update', $role->id) }}"  method="post">
                    @csrf
                    <input type="hidden" name="_method" value="put" />
                    <div class="block-header bg-info">
                        <h3 class="block-title">Edit Role</h3>
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
                                <input type="text" class="form-control" id="erolename{{$role->id}}" name="rolename" value="{{$role->name}}" placeholder="Role Name" autocomplete="off">
                            </div>
                        </div>
                        <label class="mb-10" for="example-text-input">Permisions</label>
                        @php
                        $permissions = json_decode($role->permissions, true);


                        @endphp

                        <div class="row mb-10">
                            <div class="col-md-6">
                                <div class="custom-control custom-checkbox mb-5 ">
                                    <input class="custom-control-input" type="checkbox" name="kpimodule" id="ekpimodule{{$role->id}}" value="1" {{array_key_exists("kpimodule", $permissions)? "checked": ""}}>
                                    <label class="custom-control-label" for="ekpimodule{{$role->id}}"> <b class="mb-10" > Manage KPI</b></label>
                                </div>
                                
                            </div>
                            <div class="col-md-6">
                                <div class="custom-control custom-checkbox mb-5 ">
                                    <input class="custom-control-input" type="checkbox" name="engagement" id="engagemente{{$role->id}}" value="1" {{array_key_exists("engagement", $permissions)? "checked": ""}}>
                                    <label class="custom-control-label" for="engagemente{{$role->id}}"> <b class="mb-10"> Manage Engagement</b></label>
                                </div>
                              
                            </div>
                        </div>
                        <div class="row mb-10">
                            <div class="col-md-6">
                                <div class="custom-control custom-checkbox mb-5 ">
                                    <input class="custom-control-input" type="checkbox" name="fillkpiproject" id="fillkpiprojecte{{$role->id}}" value="1" {{array_key_exists("fillkpiproject", $permissions)? "checked": ""}}>
                                    <label class="custom-control-label" for="fillkpiprojecte{{$role->id}}"> <b class="mb-10" >Fill Kpi on project</b></label>
                                </div>
                               
                            </div>
                            <div class="col-md-6">
                                <div class="custom-control custom-checkbox mb-5 ">
                                    <input class="custom-control-input" type="checkbox" name="fillengageproject" id="fillengageprojecte{{$role->id}}" value="1" {{array_key_exists("fillengageproject", $permissions)? "checked": ""}}>
                                    <label class="custom-control-label" for="fillengageprojecte{{$role->id}}"> <b class="mb-10">Fill Engagement on project</b></label>
                                </div>
                               
                            </div>
                        </div>
                        <div class="row mb-10">
                            <div class="col-md-6">
                                <div class="custom-control custom-checkbox mb-5 ">
                                    <input class="custom-control-input" type="checkbox" name="fillkpiteam" id="fillkpiteame{{$role->id}}" value="1" {{array_key_exists("fillkpiteam", $permissions)? "checked": ""}}>
                                    <label class="custom-control-label" for="fillkpiteame{{$role->id}}"> <b class="mb-10" >Fill Kpi on team</b></label>
                                </div>
                               
                            </div>
                            <div class="col-md-6">
                                <div class="custom-control custom-checkbox mb-5 ">
                                    <input class="custom-control-input" type="checkbox" name="fillengageteam" id="fillengageteame{{$role->id}}" value="1" {{array_key_exists("fillengageteam", $permissions)? "checked": ""}}>
                                    <label class="custom-control-label" for="fillengageteame{{$role->id}}"> <b class="mb-10">Fill Engagement on team</b></label>
                                </div>
                               
                            </div>
                        </div> 
                        <div class="row mb-10">
                            <div class="col-md-6">
                                <div class="custom-control custom-checkbox mb-5 ">
                                    <input class="custom-control-input okrr" type="checkbox" name="okr" id="eokr{{$role->id}}" value="1" {{array_key_exists("okr", $permissions)? "checked": ""}}>
                                    <label class="custom-control-label" for="eokr{{$role->id}}"> <b class="mb-10" >OKR</b></label>
                                </div>
                                <div class="custom-control custom-checkbox mb-5 ml-20">
                                    <input class="custom-control-input eokr"  type="checkbox" name="addSession" id="eaddSession{{$role->id}}" value="1" {{array_key_exists("okr", $permissions) || array_key_exists("addSession", $permissions)? "checked": ""}}>
                                    <label class="custom-control-label " for="eaddSession{{$role->id}}">Add Sessions</label>
                                </div>
                                <div class="custom-control custom-checkbox mb-5 ml-20">
                                    <input class="custom-control-input eokr" type="checkbox" name="editSession" id="editSessione{{$role->id}}" value="1" {{array_key_exists("okr", $permissions) || array_key_exists("editSession", $permissions)? "checked": ""}}>
                                    <label class="custom-control-label" for="editSessione{{$role->id}}">Edit Sessions</label>
                                </div>
                                <div class="custom-control custom-checkbox mb-5 ml-20">
                                    <input class="custom-control-input eokr" type="checkbox" name="deleteSession" id="edeleteSession{{$role->id}}" value="1" {{array_key_exists("okr", $permissions) || array_key_exists("deleteSession", $permissions)? "checked": ""}}>
                                    <label class="custom-control-label" for="edeleteSession{{$role->id}}">Delete Sessions</label>
                                </div>
                                <div class="custom-control custom-checkbox mb-5 ml-20">
                                    <input class="custom-control-input eokr" type="checkbox" name="addObjective" id="eaddObjective{{$role->id}}" value="1" {{array_key_exists("okr", $permissions) || array_key_exists("addObjective", $permissions)? "checked": ""}}>
                                    <label class="custom-control-label " for="eaddObjective{{$role->id}}">Add objective</label>
                                </div>
                                <div class="custom-control custom-checkbox mb-5 ml-20">
                                    <input class="custom-control-input eokr" type="checkbox" name="assignokr" id="eassignokr{{$role->id}}" value="1" {{array_key_exists("okr", $permissions) || array_key_exists("assignokr", $permissions)? "checked": ""}}>
                                    <label class="custom-control-label" for="eassignokr{{$role->id}}">Assign OKR for other Employees</label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="custom-control custom-checkbox mb-5 ">
                                    <input class="custom-control-input otherhrr" type="checkbox" name="otherhr" id="eotherhr{{$role->id}}" value="1" {{array_key_exists("otherhr", $permissions) ? "checked": ""}}>
                                    <label class="custom-control-label" for="eotherhr{{$role->id}}"> <b class="mb-10" >Other HR Modules</b></label>
                                </div>
                                <div class="custom-control custom-checkbox mb-5 ml-20">
                                    <input class="custom-control-input ehr" type="checkbox" name="rolemanage" id="erolemanage{{$role->id}}" value="1" {{array_key_exists("otherhr", $permissions) || array_key_exists("rolemanage", $permissions)? "checked": ""}}>
                                    <label class="custom-control-label" for="erolemanage{{$role->id}}">Role Management</label>
                                </div>
                                <div class="custom-control custom-checkbox mb-5 ml-20">
                                    <input class="custom-control-input ehr" type="checkbox" name="addStamp" id="eaddStamp{{$role->id}}" value="1" {{array_key_exists("otherhr", $permissions) || array_key_exists("addStamp", $permissions)? "checked": ""}}>
                                    <label class="custom-control-label" for="eaddStamp{{$role->id}}">Add Stamp</label>
                                </div>
                                <div class="custom-control custom-checkbox mb-5 ml-20">
                                    <input class="custom-control-input ehr" type="checkbox" name="addSignature" id="eaddSignature{{$role->id}}" value="1" {{array_key_exists("otherhr", $permissions) || array_key_exists("addSignature", $permissions)? "checked": ""}}>
                                    <label class="custom-control-label" for="eaddSignature{{$role->id}}">Add Signature </label>
                                </div>
                                <div class="custom-control custom-checkbox mb-5 ml-20">
                                    <input class="custom-control-input ehr" type="checkbox" name="employees" id="employeese{{$role->id}}" value="1" {{array_key_exists("otherhr", $permissions) || array_key_exists("employees", $permissions)? "checked": ""}}>
                                    <label class="custom-control-label" for="employeese{{$role->id}}">Manage Employees </label>
                                </div>
                                <div class="custom-control custom-checkbox mb-5 ml-20">
                                    <input class="custom-control-input ehr" type="checkbox" name="teams" id="eteams{{$role->id}}" value="1" {{array_key_exists("otherhr", $permissions) || array_key_exists("teams", $permissions)? "checked": ""}}>
                                    <label class="custom-control-label" for="eteams{{$role->id}}">Manage Teams </label>
                                </div>
                            </div>
                        </div>
                        <div class="row mb-10">
                            <div class="col-md-6">
                                <div class="custom-control custom-checkbox mb-5 ">
                                    <input class="custom-control-input" type="checkbox" name="projects" id="eprojects{{$role->id}}" value="1" {{array_key_exists("projects", $permissions) ? "checked": ""}}>
                                    <label class="custom-control-label" for="eprojects{{$role->id}}"> <b class="mb-10">Projects</b></label>
                                </div>
                                <div class="custom-control custom-checkbox mb-5 ">
                                    <input class="custom-control-input" type="checkbox" name="manageprojects" id="emanageprojects{{$role->id}}" value="1" {{array_key_exists("manageprojects", $permissions) ? "checked": ""}}>
                                    <label class="custom-control-label" for="emanageprojects{{$role->id}}"> <b class="mb-10" >Manage Projects</b></label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="custom-control custom-checkbox mb-5 ">
                                    <input class="custom-control-input" type="checkbox" name="visualization" id="evisualization{{$role->id}}" value="1" {{array_key_exists("visualization", $permissions) ? "checked": ""}}>
                                    <label class="custom-control-label" for="evisualization{{$role->id}}"> <b class="mb-10" >Visualization</b></label>
                                </div>
                                <div class="custom-control custom-checkbox mb-5 ">
                                    <input class="custom-control-input" type="checkbox" name="generalreports" id="egeneralreports{{$role->id}}" value="1" {{array_key_exists("generalreports", $permissions) ? "checked": ""}}>
                                    <label class="custom-control-label" for="egeneralreports{{$role->id}}"> <b class="mb-10">General Reports</b></label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-rounded btn-outline-success min-width-125 mb-10" >
                            <i class="fa fa-check"></i> Edit
                        </button>
                        <button type="button" class="btn btn-rounded btn-outline-danger min-width-125 mb-10" data-dismiss="modal">Close</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

{{-- delete role --}}
<div class="modal " id="deleteRole{{$role->id}}" tabindex="-1" role="dialog" aria-labelledby="modal-large" aria-hidden="true">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <form action = "{{ route('roles.destroy', $role->id) }}"  method="post">
                @csrf
                <input type="hidden" name="_method" value="delete" />
                <div class="block block-themed block-transparent mb-0">
                    <div class="block-header bg-danger">
                        <h3 class="block-title">Delete role</h3>
                        <div class="block-options">
                            <button type="button" class="btn-block-option" data-dismiss="modal" aria-label="Close">
                                <i class="si si-close"></i>
                            </button>
                        </div>
                    </div>
                    <div class="block-content">
                        <div class="form-group row">
                            <div class="modal-body">
                                <label for="inputLink" class=" control-label col-md-push-1" >Are you sure you want to delete the role <em>{{$role->name}}</em> ?</label>
                            </div>
                        </div>


                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-alt-danger "    data-id = {{$role->id}}>
                        Delete role
                    </button>
                    <button type="button" class="btn btn-alt-secondary" data-dismiss="modal">Close</button>
                </div>
            </form>
        </div>
    </div>
</div>


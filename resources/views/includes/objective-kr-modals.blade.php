{{-- Add objective modal --}}
<div class="modal right fade" id="exampleModal3"  role="dialog"  aria-hidden="true">
    <div class="modal-dialog modal-dialog-slideout modal-lg" role="document">
        <div class="modal-content">
            <form class="objectivemodalFormData" id="objectivemodalFormData" action = "{{ route('objectives.store') }}"   method="post">
                @csrf
                <div class="modal-body">
                    <div class="container">
                        <div class="col-lg-11 addobjbod">
                            <div class="modal-top">
                                <h5>Add objective</h5>
                            </div>
                             <hr style="border-top: 5px solid #ccc">
                            <div class="form-group">
                                <label for="objective" >Objective</label>
                                <input type="text" class="form-control round" id="objective_name" name="objective_name" aria-describedby="objective" autocomplete="off" required>
                                <input type="hidden" name="session_id" value="{{$session->id}}" />
                            </div>
                            @if(Gate::any(['okr', 'assignokr']))
                                <div class="form-group ownerslist autocomplete" >
                                    <label for="objective"> <i class="fa fa-user"></i> Owner</label>
                                    <input type="text" class="form-control round" id="ownerlist" placeholder = "Owner" autocomplete="off" value="{{Auth::user()->fname . " ". Auth::user()->lname}}"  required >
                                    <input type="hidden" class="form-control round" id="idholder"  autocomplete="off"  required name="user_id" >
                                </div>
                            @endif
                            <div class="modal-top" style="margin-top: 5em;">
                                <h5>Alignment</h5>
                            </div>
                            <hr style="border-top: 5px solid #ccc">
                            <div class="alignlink">
                                <a class="launchalign" href="#" data-id="{{Auth::user()->id}}" id="launchalign">+ Align this objective with another objective</a>
                            </div>
                            <div class='row alignedobj'  hidden data-obj ="0" id="alignedobj" ></div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer" style="margin-top: 2em;">
                    <button type="submit " id="addobjbutton" class="btn btn-rounded btn-outline-info" >Add</button>
                    <button type="button " id="clbutton" class="btn btn-rounded btn-outline-secondary" data-dismiss="modal">Close</button>
                </div>
            </form>
          </div>
        </div>
    </div>
</div>
{{-- Add alignment --}}
<div class="modal fade" id="objectivelist" role="dialog"  aria-hidden="true" >
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="block block-themed block-transparent mb-0">
                <div class="block-header bg-info">
                    <h3 class="block-title">Choose Objective</h3>
                    <div class="block-options">
                        <button type="button" class="btn-block-option" data-dismiss="modal" aria-label="Close">
                            <i class="si si-close"></i>
                        </button>
                    </div>
                </div>
            </div>
            <div class="modal-body" >
                <div class="input-group">
                    <div class="col-md-7 autocomplete">
                        <input type="text" class = "form-control round alignobjchoice" placeholder="Owner" id="alignobjchoice" autocomplete="off" >
                    </div>
                    <button class="btn btn-circle btn-outline-info searchowner" type="button" id="searchowner">
                        <i class="fa fa-search"></i>
                    </button>
                </div>
                <div class="container my-3">
                    <ul id="objectives_list"></ul>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="editkrmodal" aria-labelledby="editkr" aria-hidden="true" tab-index=-1>
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="block block-themed block-transparent mb-0">
                <div class="block-header bg-info">
                    <h3 class="block-title">Edit keyresult</h3>
                    <div class="block-options">
                        <button type="button" class="btn-block-option " data-dismiss="modal"  aria-hidden="true">
                            <i class="si si-close"></i>
                        </button>
                    </div>
                </div>
            </div>
            <form id="editkrmodalFormData"  method="post" >
                <div class="modal-body" id=krbody>


                </div>
                <div class="modal-footer">
                    <button type="submit " id="editkrbutton" class="btn btn-rounded btn-outline-info" >Edit</button>
                    <button type="button " id="ekrclbutton" class="btn btn-rounded btn-outline-secondary" data-dismiss="modal">Close</button>
                </div>
            </form>
        </div>
    </div>
</div>






<div class="modal fade" id="addkrmodal" aria-labelledby="modal-large" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="block block-themed block-transparent mb-0">
                <div class="block-header bg-info">
                    <h3 class="block-title">Add keyresult</h3>
                    <div class="block-options">
                        <button type="button" class="btn-block-option" data-dismiss="modal" aria-label="Close">
                            <i class="si si-close"></i>
                        </button>
                    </div>
                </div>
            </div>
            <form id="addkrmodalFormData" name="my-form"  method="post" >
                <div class="modal-body addbody">
                    <div class="form-group row">
                        <label for="keyresult_name" class="col-12 col-form-label">Key Result</label>
                        <input type="text" class="form-control mx-20 round" id="keyresult_name" aria-describedby="objective" placeholder="Key result" autocomplete="off" required>
                    </div>
                    <div class="form-group row">
                        <label for="ktype" class="col-12 col-form-label">Key result type</label>
                        <div class="form-group col-md-12">
                            <select id="keyresulttype" class="form-control" >
                                <option value=0>Achieved or not</option>
                                <option value=1>Should increase to</option>

                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-md-6 targetnumber" hidden>
                            <label for="Target" class="col-12 col-form-label">Target Number</label>
                            <div class="form-group col-md-12">
                                <input type="number" id="targetnumber" name="targetnumber" class="form-control round"
                                min="0" max="1000" >
                            </div>
                        </div>
                        <div class="form-group col-md-6 row initialnumber" hidden>
                            <label for="Target" class="col-12 col-form-label">Initial Number</label>
                            <div class="form-group col-md-12">
                                <input type="number" id="initialnumber" name="initialtargetnumber" class="form-control round"
                                min="0" max="1000" >
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit " id="addkrbutton" class="btn btn-rounded btn-outline-info" >Add</button>
                    <button type="button " id="krclbutton" class="btn btn-rounded btn-outline-secondary" data-dismiss="modal">Close</button>
                </div>
            </form>
        </div>
    </div>
</div>
 <!-- delete kr modal -->
 <div class="modal fade" id="deletekrmodal" aria-hidden="true">
    <div class="modal-dialog">
        <form id="delete" action = ""   method="post">
            <div class="block block-themed block-transparent mb-0">
                <div class="block-header bg-danger">
                    <h3 class="block-title">Delete Keyresult</h3>
                    <div class="block-options">
                        <button type="button" class="btn-block-option" data-dismiss="modal" aria-label="Close">
                            <i class="si si-close"></i>
                        </button>
                    </div>
                </div>
                <div class="modal-content">
                    <div class="modal-body">
                        <label for="inputLink" class=" control-label col-md-push-1" id="deletekrlabel"></label>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-rounded btn-outline-danger" id="kr-delete" value="">Delete</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

{{-- update keyresult --}}
<div class="modal fade " id=updatekrmodal aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="block block-themed block-transparent mb-0">
                <div class="block-header bg-info">
                    <h3 class="block-title">Update keyresult status</h3>
                    <div class="block-options">
                        <button type="button" class="btn-block-option" data-dismiss="modal" aria-label="Close">
                            <i class="si si-close"></i>
                        </button>
                    </div>
                </div>
            </div>
            <form id="updatekrmodalFormData" name="my-form"  method="post" >
                <div class="modal-body updatebody">

                </div>
                <div class="modal-footer">
                    <button type="submit " id="updatekrbutton" class="btn btn-rounded btn-outline-info" >Update</button>
                    <button type="button " id="clbuttonu" class="btn btn-rounded btn-outline-secondary" data-dismiss="modal">Close</button>
                </div>
            </form>

        </div>
    </div>
</div>




 {{-- add milestone to kr --}}
<!--<div class="modal fade " id="addtaskmodal" aria-hidden="true">-->
<!--          <div class="modal-dialog" role="document">-->
<!--            <div class="modal-content">-->
              <!--Header-->
<!--              <div class="block block-themed block-transparent mb-0">-->
<!--                   <div class="block-header bg-info">-->
<!--                       <h3 class="block-title">Add Milestone</h3>-->
<!--                        <div class="block-options">-->
<!--                            <button type="button" class="btn-block-option" data-dismiss="modal" aria-label="Close">-->
<!--                                <i class="si si-close"></i>-->
<!--                            </button>-->
<!--                        </div>-->
<!--                   </div>-->
<!--              </div>-->
              <!--Body-->
<!--              <form id="addtaskmodalFormData" name="my-form"  method="post" >-->

<!--                <div class="modal-body">-->
<!--                    <div class="form-group row targetnumber">-->
<!--                        <label for="Target" class="ml-20 col-form-label ">Milestone</label>-->
<!--                        <div class="form-group col-md-12">-->
<!--                            <input type="text" id="taskname" name="taskname" class="form-control round" autocomplete="off" required>-->
<!--                          </div>-->
<!--                    </div>-->
<!--                </div>-->
<!--            <div class="modal-footer">-->
<!--                <button type="submit " id="addtaskbutton" class="btn btn-rounded btn-outline-info" >Add</button>-->
<!--                <button type="button " id="tkrclbutton" class="btn btn-rounded btn-outline-secondary" data-dismiss="modal">Close</button>-->
<!--            </div>-->
<!--        </form>-->
<!--          </div>-->
<!--        </div>-->
<!--      </div>-->


    {{-- edit task --}}
<div class="modal fade " id='edittaskmodal' aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
          <!--Header-->
          <div class="block block-themed block-transparent mb-0">
               <div class="block-header bg-info">
                   <h3 class="block-title">Edit Milestone</h3>
                    <div class="block-options">
                        <button type="button" class="btn-block-option" data-dismiss="modal" aria-label="Close">
                            <i class="si si-close"></i>
                        </button>
                    </div>
               </div>
          </div>
          <!--Body-->
          <form id="edittaskmodalFormData" name="my-form"  method="post" >

            <div class="modal-body milebody">

            </div>
        <div class="modal-footer">
            <button type="submit " id="edittaskbutton" class="btn btn-rounded btn-outline-info" >Edit</button>
            <button type="button " id="etaskclbutton" class="btn btn-rounded btn-outline-secondary" data-dismiss="modal">Close</button>
        </div>
    </form>
      </div>
    </div>
</div>

     <!-- delete modal -->



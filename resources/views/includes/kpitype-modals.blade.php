{{-- add kpi model--}}
<div class="modal fade" id="KpiModal" tabindex="-1" role="dialog" aria-labelledby="#addb" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">

            <form id="KpimodalFormData" name="kpi-form" method="post" >
                <div class="modal-body">
            <div class="block block-themed block-transparent mb-0">
                <div class="block-header bg-primary-dark">
                    <h3 class="block-title">Add Department Type</h3>
                    <div class="block-options">
                        <button type="button" class="btn-block-option" data-dismiss="modal" aria-label="Close">
                            <i class="si si-close"></i>
                        </button>
                    </div>
                </div>
                <div class="block-content">
                    <?php
                    $message = "#";
                     $allteams = App\Team::all();
                ?>
                    <input type="text" class="form-control mb-10 p-2 round" id="kpi_name" name="example-text-input" placeholder="KPI Name" required>
                    <select class="form-control" id="department_name" name="example-select" required>

                        <option value="0">Please select Team</option>
                        @foreach($allteams as $at)
                        <option value={{$at->id}}>{{$at->team_name}}</option>
                        @endforeach

                    </select>
                </div>
            </div>
            <br>
            <div class="modal-footer">
                <button type="submit " id="addkpibutton" class="btn btn-alt-success">
                    <i class="fa fa-check"></i> Add
                </button>
                <button type="button" id="clbutton" class="btn btn-alt-secondary" data-dismiss="modal">Close</button>
            </div>
        </form>
    </div>
        </div>
    </div>

{{-- This is the invalid div. --}}

</div>


{{-- Edit kpi modeal --}}


<div class="modal fade" id="editKpiModal" tabindex="-1" role="dialog" aria-labelledby="#addb" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">

            <form id="editKpimodalFormData" name="kpi-form" method="post" >
                <div class="modal-body">
            <div class="block block-themed block-transparent mb-0">
                <div class="block-header bg-primary-dark">
                    <h3 class="block-title">Edit Department Type</h3>
                    <div class="block-options">
                        <button type="button" class="btn-block-option" data-dismiss="modal" aria-label="Close">
                            <i class="si si-close"></i>
                        </button>
                    </div>
                </div>
                <div class="block-content">
                    <?php
                    $message = "#";
                     $allteams = App\Team::all();
                ?>
                    <input type="text" class="form-control mb-10 p-2 round" id="edit_kpi_name" name="example-text-input" placeholder="KPI Name" required>
                    <select class="form-control" id="edit_department_name" name="example-select" required>

                        <option value="0">Please select Team</option>
                        @foreach($allteams as $at)
                        <option value={{$at->id}}>{{$at->team_name}}</option>
                        @endforeach

                    </select>
                </div>
            </div>
            <br>
            <div class="modal-footer">
                <button type="submit " id="editkpibutton" class="btn btn-alt-success">
                    <i class="fa fa-check"></i> Edit
                </button>
                <button type="button" id="clbutton" class="btn btn-alt-secondary" data-dismiss="modal">Close</button>
            </div>
        </form>
    </div>
        </div>
    </div>

{{-- This is the invalid div. --}}

</div>


<!-- delete modal -->
<div class="modal fade" id="deletekpimodal" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="taskdelete">Delete a KPI Type</h4>
            </div>
            <div class="modal-body">
                <label for="inputLink" class=" control-label col-md-push-1" id="deletelabel"></label>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" id="kpi-delete">Delete</button>

            </div>
        </div>
    </div>
</div>

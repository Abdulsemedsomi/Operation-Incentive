{{-- add modal--}}
<div class="modal fade" id="addFormulaModal" tabindex="-1" role="dialog" aria-labelledby="#addb" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="block block-themed block-transparent mb-0">
                <div class="block-header bg-primary">
                    <h3 class="block-title">Add Formula</h3>
                    <div class="block-options">
                        <button type="button" class="btn-block-option" data-dismiss="modal" aria-label="Close">
                            <i class="si si-close"></i>
                        </button>
                    </div>
                </div>
        <div class="modal-content">

            {{-- <form id="formulamodalFormData" name="kpi-form" method="post" > --}}
            <div class="modal-body">

                <div class="block-content">
                    <?php

                ?>
                <div class="calculator__keys">
                   <div class="calculator__keys row">
                        <button class="btn btn-alt-danger mr-5 mb-5" type="button"  id = "clear">AC</button>
                        <button class="btn btn-alt-info mr-5 mb-5" type="button"  id = "backspace"><i class="si si-arrow-left"></i></button>
                        <button class="btn btn-alt-info mr-5 mb-5" type="button" value="actual" id = "actual" onclick = "addVal('actual')">Actual</button>
                        <button class="btn btn-alt-info mr-5 mb-5" type="button" value="target" id = "target" onclick = "addVal('target')">Target</button>
                        <button class="btn btn-alt-info mr-5 mb-5" type="button" value="weight" id = "weight" onclick = "addVal('weight')">Weight</button>
                     </div>
                     <div class="calculator__keys row">
                        <button class="btn btn-alt-success mr-10 mb-5" type="button"  id="add" onclick = "addVal('add')" value = "+" data-action="add">+</button>
                        <button class="btn btn-alt-success mr-10 mb-5" type="button" id="subtract" data-action="subtract" value = "-" onclick = "addVal('subtract')">-</button>
                        <button class="btn btn-alt-success mr-10 mb-5" type="button" id="multiply"  data-action="multiply" onclick = "addVal('multiply')" value = "x">&times;</button>
                        <button class="btn btn-alt-success mr-10 mb-5" type="button"  id = "divide" data-action="divide" onclick = "addVal('divide')" value = "÷">÷</button>
                        <button class="btn btn-alt-success mr-10 mb-5" type="button" id="leftbrack" value = "(" onclick = "addVal('leftbrack')" data-action="openingbracket">(</button>
                        <button class="btn btn-alt-success mr-10 mb-5" type="button" id="rightbrack" value = ")" onclick = "addVal('rightbrack')" data-action="closingbracket">)</button>
                    </div>

                    <div class="calculator__keys row">
                        <button class="btn btn-lg btn-alt-secondary mr-5 mb-5" type="button" value= 7 id = "seven" onclick = "addVal('seven')">7</button>
                        <button class="btn btn-lg btn-alt-secondary mr-5 mb-5" type="button" value= 8 id = "eight" onclick = "addVal('eight')">8</button>
                        <button class="btn btn-lg btn-alt-secondary mr-5 mb-5" type="button" value= 9 id = "nine" onclick = "addVal('nine')">9</button>
                    </div>
                    <div class="calculator__keys row">
                        <button class="btn btn-lg btn-alt-secondary mr-5 mb-5" type="button" value= 4 id = "four" onclick = "addVal('four')">4</button>
                        <button class="btn btn-lg btn-alt-secondary mr-5 mb-5" type="button" value= 5 id = "five" onclick = "addVal('five')">5</button>
                        <button class="btn btn-lg btn-alt-secondary mr-5 mb-5" type="button" value= 6 id = "six" onclick = "addVal('six')">6</button>
                    </div>
                    <div class="calculator__keys row">
                        <button class="btn btn-lg btn-alt-secondary mr-5 mb-5" type="button" value= 1 id = "one" onclick = "addVal('one')">1</button>
                        <button class="btn btn-lg btn-alt-secondary mr-5 mb-5" type="button" value= 2 id = "two" onclick = "addVal('two')">2</button>
                        <button class="btn btn-lg btn-alt-secondary mr-5 mb-5" type="button" value= 3 id = "three" onclick = "addVal('three')">3</button>
                    </div>
                    <div class="calculator__keys row">
                        <button class="btn btn-lg btn-alt-secondary mr-5 mb-5" type="button" value= 0 id = "zero" onclick = "addVal('zero')">0</button>
                    </div>

                </div>
                  <div class="row mt-20">
                    <textarea type="text" class="form-control mb-10 p-2 col-md-12" id="formula" name="example-text-input" rows="9" placeholder="Formula" disabled></textarea>
                    {{-- <button type = "button" data-action="delete" class="btn btn-alt-danger">x</button> --}}
                  </div>
                  <div>
                    <label class="text-danger" id="errormessage"></label>
                </div>
                </div>
            </div>
            <br>
            <div class="modal-footer">
                <a type="button " id="addFormula" class="btn btn-alt-success" >
                    <i class="fa fa-check"></i> Add
                </a>
                <button type="button" id="clbutton" class="btn btn-alt-secondary" data-dismiss="modal">Close</button>
            </div>
        {{-- </form> --}}
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
                    <input type="text" class="form-control mb-10 p-2" id="edit_kpi_name" name="example-text-input" placeholder="KPI Name" required>
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

<div class="modal" id="addproject" tabindex="-1" role="dialog" aria-labelledby="modal-large" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
             <form action = "{{ route('projects.store') }}"   method="post">
                @csrf
            <div class="block block-themed block-transparent mb-0">
                <div class="block-header bg-primary-info">
                    <h3 class="block-title">Add Project</h3>
                    <div class="block-options">
                        <button type="button" class="btn-block-option" data-dismiss="modal" aria-label="Close">
                            <i class="si si-close"></i>
                        </button>
                    </div>
                </div>
                <div class="block-content">
                    <div class="form-group row">
                        <label class="col-12" for="projectname">Project Name</label>
                        <div class="col-md-12">
                            <input type="text" class="form-control round" id="projectname" name="projectname" placeholder="Project Name" required>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-2 mt-5" for="projectdescription">Project Size</label>
                        <div class="col-md-4">
                            <input type="number" class="form-control round" id="projectdescription" name="projectdescription" placeholder="Project Size" required>
                        </div>
                        <div class="col-md-3">
                            <select class="form-control round" id="example-select" name="example-select">
                                <option disabled>Please select</option>
                                <option value="ETB">ETB</option>
                                <option value="USD">USD</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <input type="number" class="form-control round" id="currency" name="currency" placeholder="Exchange rate" required>
                        </div>
                    </div>
                    <div class="form-group row">
                        {{-- <div class="col-md-4">
                            <label for="example-select">Select PMOM</label>
                            <select class="form-control" id="example-select" name="example-select">
                                <option disabled>Please select</option>
                                <option value="1">Option #1</option>
                                <option value="2">Option #2</option>
                                <option value="3">Option #3</option>
                            </select>
                        </div> --}}
                        <div class="col-md-4">
                            <label for="pm">Select PM</label>
                            @php
                              $pms = App\User::where('position', 'like', '%Project Manager')->get();
                            @endphp
                            <select class="form-control round" id="pm" name="pm">
                                <option disabled>Please select</option>
                                @foreach($pms as $pm)
                                <option value={{$pm->id}}>{{$pm->fname . " " .$pm->lname}}</option>
                                @endforeach

                            </select>

                        </div>
                            <input type="text" class="form-control round" id="memebercount" name="memebercount" hidden value=0>

                        <div class="col-md-4 mt-20">
                                <button type="button" class="btn btn-rounded btn-outline-info mr-5 mt-5 addProjectMember" id="addProjectMember" data-count =0 >
                                    <i class="fa fa-plus mr-5"></i>Add Member
                                </button>
                        </div>
                        <div class="memberslist col-md-10 mt-20">
                        </div>
                        {{-- <div class="col-md-4">
                            <label for="example-select">Select TL</label>
                            <select class="form-control" id="example-select" name="example-select">
                                <option disabled>Please select</option>
                                <option value="1">Option #1</option>
                                <option value="2">Option #2</option>
                                <option value="3">Option #3</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-md-3">
                            <label for="example-select">Select FE</label>
                            <select class="form-control" id="example-select" name="example-select">
                                <option disabled>Please select</option>
                                <option value="1">Option #1</option>
                                <option value="2">Option #2</option>
                                <option value="3">Option #3</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="example-select">Select LFO</label>
                            <select class="form-control" id="example-select" name="example-select">
                                <option disabled>Please select</option>
                                <option value="1">Option #1</option>
                                <option value="2">Option #2</option>
                                <option value="3">Option #3</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="example-select">Select ISE</label>
                            <select class="form-control" id="example-select" name="example-select">
                                <option disabled>Please select</option>
                                <option value="1">Option #1</option>
                                <option value="2">Option #2</option>
                                <option value="3">Option #3</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="example-select">Select Technician</label>
                            <select class="form-control" id="example-select" name="example-select">
                                <option disabled>Please select</option>
                                <option value="1">Option #1</option>
                                <option value="2">Option #2</option>
                                <option value="3">Option #3</option>
                            </select>
                        </div> --}}
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-alt-success" >
                    <i class="fa fa-plus"></i> Add
                </button>
                <button type="button" class="btn btn-alt-secondary" data-dismiss="modal">Close</button>
            </div>
             </form>
        </div>
    </div>
</div>

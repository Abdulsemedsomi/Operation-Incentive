<div class="modal" id="addbid" tabindex="-1" role="dialog" aria-labelledby="modal-large" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
             <form action = "{{ route('bids.store') }}"   method="post">
                @csrf
            <div class="block block-themed block-transparent mb-0">
                <div class="block-header bg-primary-info">
                    <h3 class="block-title">Add Bids</h3>
                    <div class="block-options">
                        <button type="button" class="btn-block-option" data-dismiss="modal" aria-label="Close">
                            <i class="si si-close"></i>
                        </button>
                    </div>
                </div>
                <div class="block-content">
                    <div class="form-group row">
                        <label class="col-12" for="projectname">Bid Name</label>
                        <div class="col-md-12">
                            <input type="text" class="form-control round" id="bidname" name="bidname" placeholder="Bid Name">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-12" for="projectdescription">Bid Size in ETB</label>
                        <div class="col-12">
                            <input class="form-control round" id="bidamount" name="bidamount" placeholder="Bid Size in ETB">
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-md-4">
                            <label for="ae">Select Account Executive</label>
                            @php
                              $pms = App\User::where('position', 'like', '%Account Executive')->get();
                            @endphp
                            <select class="form-control round" id="ae" name="ae">
                                <option disabled>Please select</option>
                                @foreach($pms as $ae)
                                <option value={{$ae->id}}>{{$ae->fname . " " .$ae->lname}}</option>
                                @endforeach
                            </select>

                        </div>
                            <input type="text" class="form-control round" id="memebercount" name="memebercount" hidden value=0>
                        <div class="col-md-4 mt-20">
                                <button type="button" class="btn btn-rounded btn-outline-info mr-5 mb-5 mt-5 addbidmember" id="addbidmember" data-count=0>
                                    <i class="fa fa-plus mr-5"></i>Add Member
                                </button>
                        </div>
                        <div class="memberslist col-md-10 mt-20">
                        </div>
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

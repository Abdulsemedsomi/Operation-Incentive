<div class="modal" id="modal-normal4" tabindex="-1" role="dialog" aria-labelledby="modal-normal" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="block block-themed block-transparent mb-0">
                <div class="block-header bg-info">
                    <h3 class="block-title">EBTDA Target</h3>
                    <div class="block-options">
                        <button type="button" class="btn-block-option" data-dismiss="modal" aria-label="Close">
                            <i class="si si-close"></i>
                        </button>
                    </div>
                </div>
                <form action="{{ route('home.store') }}" method="POST">
                    @csrf
                    <div class="block-content">
                        <div class="form-group row">
                            <label class="col-12" for="example-email-input">EBTDA Target</label>
                            <div class="col-md-9">
                                <input type="number" class="form-control round" name="ebtdatarget" id="ebtdatarget" value="{{$company->ebtdatarget}}" placeholder="EBTDA Target">
                                <input type="hidden" class="form-control round" name="type" value="ebtda">
                            </div>
                            <button type="submit" class="btn btn-rounded btn-outline-info">
                                <i class="fa fa-check"></i>Set
                            </button>
                        </div>
                        <div class="form-group row">
                            <label class="col-12" for="example-email-input">Update EBTDA</label>
                            <div class="col-md-9">
                                <input type="number" class="form-control round" name="ebtda" id="ebtda" placeholder="EBTDA">
                                <input type="hidden" class="form-control round" name="type" value="ebtda">
                            </div>
                            <button type="submit" class="btn btn-rounded btn-outline-info">
                                <i class="fa fa-check"></i>Update
                            </button>
                        </div>
                    </div>
                </form>
                <div class="modal-footer">
                    <button type="button" class="btn btn-alt-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    var inp4 = document.getElementById("ebtda");
    inp4.oninput = function () {
    document.getElementById("ebtdatarget").readOnly = this.value != "";
};
</script>

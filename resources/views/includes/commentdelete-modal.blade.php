<div class="modal fade" id="deletecomment{{$comment->id}}" aria-hidden="true">
    <div class="modal-dialog">
        <form id="delete" action = "{{ route('comments.destroy', $comment->id) }}"   method="post">
            @csrf
            <input type="hidden" name="_method" value="delete" />
            <div class="block block-themed block-transparent mb-0">
                <div class="block-header bg-danger">
                    <h3 class="block-title">Delete comment</h3>
                    <div class="block-options">
                        <button type="button" class="btn-block-option" data-dismiss="modal" aria-label="Close">
                            <i class="si si-close"></i>
                        </button>
                    </div>
                </div>
                <div class="modal-content">
                    <div class="modal-body">
                        <label for="inputLink" class=" control-label col-md-push-1" id="deletelabel">Are you sure you want to delete this comment?</label>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-rounded btn-outline-danger" id="session-delete" value="" onclick="Codebase.loader('show', 'bg-gd-sea');setTimeout(function () { Codebase.loader('hide'); });">Delete</button>
                        <button type="button" class="btn btn-rounded btn-outline-secondary" data-dismiss="modal">Cancel</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
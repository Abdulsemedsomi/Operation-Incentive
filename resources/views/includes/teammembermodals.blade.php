
<div class="modal fade addMembermodal"  id="addMembermodal" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="block block-themed block-transparent mb-0">
            <div class="block-header bg-info">
                <h3 class="block-title">Add Team Member </h3>
                <div class="block-options">
                    <button type="button" class="btn-block-option" data-dismiss="modal" aria-label="Close">
                        <i class="si si-close"></i>
                    </button>
                </div>
            </div>
            <form class="membermodalFormData" id="membermodalFormData" action = "{{ route('members.store') }}"   method="post">
                @csrf
                <div class="modal-content">
                    @php
                        
                        $users = App\User::all();
                    @endphp
                    <div class="modal-body" id="addbod">
                        
                        <div class="form-group row">
                            <label for="member_name" class="col-md-3 col-form-label text-md-right">Name</label>
                            <div class="col-md-6">
                                <input  type="hidden" name="team_id" value="{{$team->id}}"/>
                                <select class=" form-control" id="user_id" name="user_id">
                                    <option value="0" >Please select user</option>
                                      @foreach($users as $user)
                                      @php
                                           $usersinteam = App\Teammember::where('team_id', $team->id)->where('user_id', $user->id)->first(); 
                                      @endphp
                                      @if(!$usersinteam)
                                    <option value="{{$user->id}}" {{$user->fname. " " . $user->lname == Auth::user()->reportsTo? 'selected': ''}}>{{$user->fname. " " . $user->lname}}</option>
                                      @endif
                                      @endforeach
                                    
                                </select>
                            </div>
                        </div>
                    </div>
                <!--Footer-->
                    <div class="modal-footer">
                        <button type="submit " class="btn btn-rounded btn-outline-success addmemberbutton">
                            Add
                        </button>
                        <button type="button" class="btn btn-rounded btn-outline-danger" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>



 <!-- delete modal -->
 <div class="modal fade" id="deleteMembermodal" aria-hidden="true">
    <div class="modal-dialog">
        <form id="deletefmodalFormData"   method="post">
            @csrf
            <input type="hidden" name="_method" value="delete" />
            <div class="block block-themed block-transparent mb-0">
                <div class="block-header bg-danger">
                    <h3 class="block-title">Remove User from team</h3>
                    <div class="block-options">
                        <button type="button" class="btn-block-option" data-dismiss="modal" aria-label="Close">
                            <i class="si si-close"></i>
                        </button>
                    </div>
                </div>
                <div class="modal-content">
                    <div class="modal-body">
                        <label for="inputLink" class=" control-label col-md-push-1" id="deletememberlabel"></label>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-rounded btn-outline-danger" id="tmember-delete" value="">Delete</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

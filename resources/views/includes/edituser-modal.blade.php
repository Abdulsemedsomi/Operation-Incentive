<div class="modal" id="assignrole{{$user->id}}" tabindex="-1" role="dialog" aria-labelledby="modal-large" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <form action="{{ route('updaterole', $user->id) }}" method="POST">
                @csrf
                <div class="block block-themed block-transparent mb-0">
                    <div class="block-header bg-info">
                        <h3 class="block-title">Assign Role</h3>
                        <div class="block-options">
                            <button type="button" class="btn-block-option" data-dismiss="modal" aria-label="Close">
                                <i class="si si-close"></i>
                            </button>
                        </div>
                    </div>
                    <div class="block-content">
                        <div class="form-group row">
                            <div class="col-12">
                                <label for="profile-settings-name">Name</label>
                                <input type="text" class="form-control form-control-lg" id="profile-settings-name" name="profile-settings-name" placeholder="Enter your name.." value="{{$user->fname . " " . $user->lname}}" disabled>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-12">
                                <label for="profile-settings-email">Email Address</label>
                                <input type="email" class="form-control form-control-lg" id="profile-settings-email" name="profile-settings-email" placeholder="Enter your email.." value="{{$user->email}}" disabled>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-12">
                                <label for="profile-settings-email">Current Role:</label>
                                <span class="badge badge-info">{{App\Role_user::where("user_id", $user->id)->first()? ( App\Role::find(App\Role_user::where("user_id", $user->id)->first()->role_id) ? App\Role::find(App\Role_user::where("user_id", $user->id)->first()->role_id)->name: "User"):"User"}}</span>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-12">
                                <div class="form-group row">
                                    <label class="col-12" for="example-select">Assign New Role</label>
                                    <div class="col-md-9">
                                        <select class="form-control" id="example-select" name="role">
                                            @php
                                            $roles = App\Role::all();
                                            @endphp
                                            <option value="0" disabled>Please select Role</option>
                                            @foreach($roles as $role)
                                                @if(App\Role_user::where('user_id', $user->id)->first()->role_id == $role->id)
                                                <option value={{$role->id}} selected>{{$role->name}}</option>
                                                @else
                                                <option value={{$role->id}}>{{$role->name}}</option>
                                                @endif

                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-rounded btn-outline-success min-width-125 mb-10" >
                        <i class="fa fa-check"></i> Done
                    </button>
                    <button type="button" class="btn btn-rounded btn-outline-danger min-width-125 mb-10" data-dismiss="modal">Close</button>
                </div>
            </form>
        </div>
    </div>
</div>

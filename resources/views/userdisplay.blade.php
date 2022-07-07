<div class="neotable">
    <div class="container">
        <div class="row">
            <div class="col-md">
                <div class="row">
                    @can('crud')
                        @if(count($errors) > 0)
                            <div class="alert alert-danger">
                                Upload Validation Error<br><br>
                                <ul>
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        @if($message = Session::get('successu'))
                            <div class="alert alert-success alert-block">
                                <button type="button" class="close" data-dismiss="alert">×</button>
                                 <strong>{{ $message }}</strong>
                            </div>
                        @elseif($message = Session::get('erroru'))
                            <div class="alert alert-danger alert-block">
                                <button type="button" class="close" data-dismiss="alert">×</button>
                                <strong>{{ $message }}</strong>
                            </div>
                        @endif
                        <form method="post" enctype="multipart/form-data" action="{{ url('/import_excel/importuser') }}">
                            @csrf
                            <div class="form-group">
                                <table class="table">
                                    <tr>
                                        <td width="40%" ><label>Select File for Upload</label></td>
                                        <td width="30">
                                            <input type="file" name="select_file" />
                                        </td>
                                        <td width="30%" >
                                            <input type="submit" name="upload" class="btn btn-primary" value="Update data">
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </form>
                    @endcan
                    <table class="table table-striped" id="user">
                        <thead>
                            <tr>
                                <th scope="col"></th>
                                <th scope="col">Name</th>
                                <th scope="col">Title</th>
                                <th scope="col">Email</th>
                                <th scope="col">Team</th>
                                <th>Role</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody id="userlist">
                            <?php
                                $count = 1;
                            ?>
                            @foreach($users as $user)
                                @if($user->fname !="Admin" && $user->active =="1")

                                    <tr id = "userdata{{$user->id}}">
                                        <th scope="row"><div class="aacircle" style=" --avatar-size: 3rem; background-color: #{{$user->avatarcolor}} !important;;" >
                                            <span class="aainitials">{{$user->fname[0] . $user->lname[0]}}</span>
                                            </div>
                                        </th>
                                        <td>{{$user->fname . " " . $user->lname}}</td>
                                        <td>{{$user->position}}</td>
                                        <td>{{$user->email}}</td>
                                        <td>{{ $user->team}}</td>
                                        <td>
                                            <span class="badge badge-info">{{ App\Role_user::where("user_id", $user->id)->first()? ( App\Role::find(App\Role_user::where("user_id", $user->id)->first()->role_id) ? App\Role::find(App\Role_user::where("user_id", $user->id)->first()->role_id)->name: "User"):"User"  }}</span>
                                        </td>
                                        
                                            <td>
                                            <a type="button" class="btn btn-sm btn-secondary" data-toggle="modal" title="Assign Role" data-target="#assignrole{{$user->id}}">
                                                        <i class="si si-pencil"></i>
                                                </a>

                                            </td>
                                             @include('includes.edituser-modal')
                                        
                                    </tr>
                                @endif
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function() {
    $('#user').DataTable({
        "ordering": false,
        "info":     false,
        'pageLength': 20,
        "lengthChange": false
    });
} );
</script>
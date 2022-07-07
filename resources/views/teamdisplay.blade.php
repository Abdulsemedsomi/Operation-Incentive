<div class="neotable">
    <div class="container row">
        
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
                @if($message = Session::get('successt'))
                    <div class="alert alert-success alert-block">
                        <button type="button" class="close" data-dismiss="alert">×</button>
                        <strong>{{ $message }}</strong>
                    </div>
                @elseif($message = Session::get('errort'))
                    <div class="alert alert-danger alert-block">
                        <button type="button" class="close" data-dismiss="alert">×</button>
                        <strong>{{ $message }}</strong>
                    </div>
                @endif
                <form method="post" enctype="multipart/form-data" action="{{ url('/import_excel/import') }}">
                    @csrf
                    <div class="form-group">
                        <table class="table">
                            <tr>
                                <td width="40%" align="right"><label>Select File for Upload</label></td>
                                <td width="30">
                                    <input type="file" name="select_file" />
                                </td>
                                <td width="30%" align="left">
                                    <input type="submit" name="upload" class="btn btn-primary" value="Update data">
                                </td>
                            </tr>
                        </table>
                    </div>
                </form>
            @endcan
            <table class="table table-striped" id="teamtable" >
                <thead>
                    <tr>
                        <th scope="col"></th>
                        <th scope="col">Team Name</th>
                        <th scope="col">Manager </th>
                        <th scope="col">Parent Team</th>
                   </tr>
                </thead>
                <tbody>
                    <?php
                    $count = 1;
                    ?>
                    @foreach($teams as $team)
                        <tr>
                            <?php
                            $manager = "";
                            if($team->manager_id != null){
                                $manager = App\User::find($team->manager_id);
                            }
                                $parent = App\User::whereNotNull('reportsTo')->where('team', $team->team)->first();
                            ?>
                            <th scope="row">
                                <div class="avatar-circle">
                                    <span class="initials">{{$count++}}</span>
                                </div>
                            </th>
                            <td>{{$team->team_name}}</td>
                            @if($manager)
                                <td>{{$manager->fname . " ". $manager->lname}}</td>
                            @else
                                <td> -</td>
                            @endif
                            <td>{{$team->parentteam}}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
   
</div>
    <!-- Modal -->
    <!-- Add Modal -->
    <div class="modal fade addTeammodal"
      aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <!--Header-->
          <div class="modal-header">
            <h4 class="modal-title" id="myModalLabel">Add Team</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">×</span>
            </button>
          </div>
          <!--Body-->
          <form id="teammodalFormData" name="my-form" onsubmit="return validform()" action="success.php" method="">

          <div class="modal-body">

                <div class="form-group row">
                    <label for="f_name" class="col-md-4 col-form-label text-md-right">Team Name</label>
                    <div class="col-md-6">
                        <input type="text" id="team_name" class="form-control" name="f-name" required>
                        @error('f-name')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                </div>
                <div class="form-group row">
                    <label for="l_name" class="col-md-4 col-form-label text-md-right">Description</label>
                    <div class="col-md-6">
                        <input type="text" id="description" class="form-control" name="l-name" required>
                        @error('l-name')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                    </div>
                </div>



                <div class="form-group row">
                    <label for="Team" class="col-md-4 col-form-label text-md-right">Parent Team</label>
                    <div class="form-group col-md-4">
                        <select id="teaminputState" class="form-control" >



                        </select>
                      </div>
                </div>
                <div class="form-group row">
                    <label for="Team" class="col-md-4 col-form-label text-md-right">Manager</label>
                    <div class="form-group col-md-4">
                        <select id="userinputState" class="form-control" >



                        </select>
                      </div>
                </div>

                <div class="form-group row">
                    <label for="phone_number" class="col-md-4 col-form-label text-md-right" required>Title</label>
                    <div class="col-md-6">
                        <input type="text" id="user_title" class="form-control">
                    </div>
                </div>





          </div>
          <!--Footer-->
          <div class="modal-footer">
            <button type="button" class="btn btn-outline-primary" data-dismiss="modal">Close</button>
            <button type="submit " class="btn btn-primary adduserbutton">
                Register
                </button>
          </div>
        </form>
        </div>
      </div>
    </div>

      <!--Edit modal-->
    <div class="modal fade editUsermodal" id ="editUsermodal"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <!--Header-->
        <div class="modal-header">
          <h4 class="modal-title" id="myModalLabel">Edit User</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>
        </div>
        <!--Body-->
        <form id="editmodalFormData" name="my-form" onsubmit="return validform()"  method="post">

        <div class="modal-body">

              <div class="form-group row">
                  <label for="f_name" class="col-md-4 col-form-label text-md-right">First Name</label>
                  <div class="col-md-6">
                      <input type="text" id="euser_fname" class="form-control" name="f-name" required>
                      @error('f-name')
                      <span class="invalid-feedback" role="alert">
                          <strong>{{ $message }}</strong>
                      </span>
                      @enderror
                  </div>
              </div>
              <div class="form-group row">
                  <label for="l_name" class="col-md-4 col-form-label text-md-right">Last Name</label>
                  <div class="col-md-6">
                      <input type="text" id="euser_lname" class="form-control" name="l-name" required>
                      @error('l-name')
                      <span class="invalid-feedback" role="alert">
                          <strong>{{ $message }}</strong>
                      </span>
                  @enderror
                  </div>
              </div>

              <div class="form-group row">
                  <label for="email_address" class="col-md-4 col-form-label text-md-right">E-Mail Address</label>
                  <div class="col-md-6">
                      <input id="euser_email" type="email" class="form-control @error('email') is-invalid @enderror" name="email"  required autocomplete="email" autofocus>

                      @error('email')
                          <span class="invalid-feedback" role="alert">
                              <strong>{{ $message }}</strong>
                          </span>
                      @enderror
                  </div>
              </div>

              <div class="form-group row">
                  <label for="Team" class="col-md-4 col-form-label text-md-right">Team</label>
                  <div class="form-group col-md-4">
                      <select id="eteaminputState" class="form-control" >



                      </select>
                    </div>
              </div>

              <div class="form-group row">
                  <label for="phone_number" class="col-md-4 col-form-label text-md-right" required>Title</label>
                  <div class="col-md-6">
                      <input type="text" id="euser_title" class="form-control">
                  </div>
              </div>



              <input type="hidden" name="_method" value="PUT">

        </div>
        <!--Footer-->
        <div class="modal-footer">
          <button type="button" class="btn btn-outline-primary" data-dismiss="modal">Close</button>
          <button type="submit " class="btn btn-primary edituserbutton">
              Edit
              </button>
        </div>
      </form>
      </div>
    </div>
  </div>

  <!-- delete modal -->
  <div class="modal fade" id="deleteusermodal" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="taskdelete">Delete Task</h4>
            </div>
            <div class="modal-body">
                <label for="inputLink" class=" control-label col-md-push-1" id="deletelabel"></label>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" id="user-delete" value="">Delete</button>
                <input type="hidden" id="task_id" name="task_id" value="0">
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function() {
    $('#teamtable').DataTable();
} );
</script>
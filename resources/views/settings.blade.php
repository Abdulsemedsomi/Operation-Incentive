@extends('layouts.backend')
@section('content')
<div class="mt-50 mb-10 text-center">
    <h4 class="font-w500"><i class="si si-wrench mr-5 mt-10"></i>  Settings</h4>
</div>
<div class="container">
    <div class="container mt-50">
        <ul class="nav nav-tabs md-tabs" id="myTabMD" role="tablist">
            <li class="nav-item">
                <a class="nav-link active" id="home-tab-md" data-toggle="tab" href="#home-md" role="tab" aria-controls="home-md"
                aria-selected="true">HR</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="profile-tab-md" data-toggle="tab" href="#profile-md" role="tab" aria-controls="profile-md"
                aria-selected="false">Users</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="contact-tab-md" data-toggle="tab" href="#contact-md" role="tab" aria-controls="contact-md"
                aria-selected="false">Teams</a>
            </li>
        </ul>
        <div class="tab-content pt-5" id="myTabContentMD">
            <div class="tab-pane fade show active" id="home-md" role="tabpanel" aria-labelledby="home-tab-md">
                <div class="block">
                    <div class="block-header block-header-default">
                        <h3 class="block-title">
                            <i class="fa fa-info-circle mr-5 text-muted"></i>Role Management
                        </h3>
                    </div>
                    <div class="block-content">
                        <form action="be_pages_generic_profile.edit.html" method="POST" enctype="multipart/form-data" onsubmit="return false;">
                            <div class="row items-push">
                                <div class="col-lg-3">
                                    <p class="text-muted">
                                        Manage your roles
                                    </p>
                                </div>
                                <div class="col-lg-7 offset-lg-1">
                                    <button type="button" class="btn btn-rounded btn-outline-info min-width-125 mb-10 float-right" data-toggle="modal" data-target="#modal-large">Add Role</button>
                                    <div class="col-lg-4">
                                        <h5>
                                            Available roles
                                        </h5>
                                    </div>
                                    @if($message = Session::get('successr'))
                                    <div class="alert alert-success alert-block">
                                     <button type="button" class="close" data-dismiss="alert">×</button>
                                            <strong>{{ $message }}</strong>
                                    </div>
                                    @elseif($message = Session::get('errorr'))
                                    <div class="alert alert-danger alert-block">
                                        <button type="button" class="close" data-dismiss="alert">×</button>
                                               <strong>{{ $message }}</strong>
                                       </div>
                                    @endif
                                    <table class="table table-hover table-bordered table-striped table-vcenter">
                                        <thead class="thead-light">
                                            <tr>
                                                <th>Role</th>
                                                <th class="text-center" style="width: 100px;">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($roles as $role)
                                            @include('includes.editdeleterole-modal')
                                            <tr>
                                                <td>{{$role->name}}</td>
                                                <td>
                                                    <div class="btn-group">
                                                        <a type="button" class="btn btn-sm btn-secondary editrolee" data-toggle="modal" data-target="#editRole{{$role->id}}" >
                                                                <i class="si si-pencil"></i>
                                                        </a>
                                                        <a type="button" class="btn btn-sm btn-secondary deleterole" data-toggle="modal" data-target="#deleteRole{{$role->id}}">
                                                                <i class="si si-trash"></i>
                                                        </a>
                                                    </div>
                                                </td>
                                            </tr>
                                           @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <!--<div class="block">-->
                <!--    <div class="block-header block-header-default">-->
                <!--        <h3 class="block-title">-->
                <!--            <i class="fa fa-circle-o-notch mr-5 text-muted"></i>Add Stamp-->
                <!--        </h3>-->
                <!--    </div>-->
                <!--    <div class="block-content">-->
                <!--        <form action="be_pages_generic_profile.edit.html" method="POST" enctype="multipart/form-data" onsubmit="return false;">-->
                <!--            <div class="row items-push">-->
                <!--                <div class="col-lg-3">-->
                <!--                    <p class="text-muted">-->
                <!--                        Upload the Company stamp here-->
                <!--                    </p>-->
                <!--                </div>-->
                <!--                <div class="col-lg-7 offset-lg-1">-->
                <!--                    <div class="form-group row">-->
                <!--                        <div class="col-md-10 col-xl-6">-->
                <!--                            <p class="text-muted">Current Stamp</p>-->
                <!--                            <div class="push">-->
                <!--                                <img alt="ie-stamp" style="height: 10em; weight: 10em;" src="{{url('images/stamp.png')}}">-->
                <!--                            </div>-->
                <!--                            <div class="custom-file">-->
                                                <!-- Populating custom file input label with the selected filename (data-toggle="custom-file-input" is initialized in Helpers.coreBootstrapCustomFileInput()) -->
                <!--                                <input type="file" class="custom-file-input" id="profile-settings-avatar" name="profile-settings-avatar" data-toggle="custom-file-input">-->
                <!--                                <label class="custom-file-label" for="profile-settings-avatar">Upload new stamp</label>-->
                <!--                            </div>-->
                <!--                        </div>-->
                <!--                    </div>-->
                <!--                    <div class="form-group row">-->
                <!--                        <div class="col-12">-->
                <!--                            <button type="submit" class="btn btn-alt-primary">Upload</button>-->
                <!--                        </div>-->
                <!--                    </div>-->
                <!--                </div>-->
                <!--            </div>-->
                <!--        </form>-->
                <!--    </div>-->
                <!--</div>-->
                <div class="block">
                    <div class="block-header block-header-default">
                        <h3 class="block-title">
                            <i class="fa fa-pencil-square mr-5 text-muted"></i>Add Manager's Signature
                        </h3>
                    </div>
                    <div class="block-content">
                        
                            <div class="row items-push">
                                <div class="col-lg-3">
                                    <p class="text-muted">
                                        Upload Signatures
                                    </p>
                                </div>
                                <div class="col-lg-7 offset-lg-1">
                                     
                                    <div class="form-group row">
                                        <div class="col-md-10 col-xl-6">
                                             <form enctype="multipart/form-data" action="{{ route('changesignature')}}" method="post">
                                            @csrf
                                            <label for="example-select">Employees</label>
                                            <select class="form-control col-md-12" id="example-select" name="userid">
                                                @php
                                                 $allusers = App\User::orderby('fname', 'asc')->where('active', 1)->get(); 
                                                @endphp
                                                <option  disabled>Select Employee</option>
                                                @foreach($allusers as $at)
                                                    @if($at->fname =="Eliyas")
                                                        <option value={{$at->id}} selected>{{$at->fname . " " . $at->lname}}</option>
                                                    @else
                                                    <option value={{$at->id}} >{{$at->fname . " " . $at->lname}}</option>
                                                    @endif
                                                @endforeach
                                            </select>
                                            <p class="text-muted mt-10">Current Signature</p>
                                            <div class="push">
                                                <img alt="ie-stamp" style="height: 10em; weight: 10em;" src="{{url('images/Eliyas Signature.png')}}" id="displayimg">
                                                 
                                            </div>
                                                <div class="custom-file mt-20">
                                                    <input type="file" name="sign" class="custom-file-input" id="sign" >
                                                    <label class="custom-file-label" for="profile-settings-avatar">Upload Signature</label>
                                                </div>
                                                <div class="form-group row mt-3">
                                                    <div class="col-12">
                                                        <button type="submit" class="btn btn-alt-primary">Upload</button>
                                                    </div>
                                                </div>
                                            </form>
                                           
                                        </div>
                                    </div>
                                   
                                </div>
                            </div>
                        
                    </div>
                </div>
            </div>
            <div class="tab-pane fade" id="profile-md" role="tabpanel" aria-labelledby="profile-tab-md">
                @include('userdisplay')

            </div>
            <div class="tab-pane fade" id="contact-md" role="tabpanel" aria-labelledby="contact-tab-md">
                @include('teamdisplay')
            </div>
        </div>
    </div>
</div>
@include('includes.settings-modal')
<script>
    function readURL(input) {
  if (input.files && input.files[0]) {
    var reader = new FileReader();
    
    reader.onload = function(e) {
      $('#displayimg').attr('src', e.target.result);
    }
    
    reader.readAsDataURL(input.files[0]); // convert to base64 string
  }
}

$("#sign").change(function() {
  readURL(this);
});
</script>
@endsection

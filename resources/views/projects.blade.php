@extends('layouts.backend')
@section('content')
<div class="container">
     <div class="mt-50 mb-10 text-center">
        <button class="btn btn-rounded btn-outline-info min-width-125 float-right" data-toggle="modal" data-target="#addproject">Add Project</button>
        <h2 class="font-w700 mb-10">Projects</h2>
    </div>
</div>
<div class="container mt-20">
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
                <form method="post" enctype="multipart/form-data" action="{{ url('/import_excel/importproject') }}">
                    @csrf
                    <div class="form-group">
                        <table class="table">
                            <tr>
                                <td width="40%" align="right"><label>Select File for Upload</label></td>
                                <td width="30">
                                    <input type="file" name="select_file" />
                                </td>
                                <td width="30%" align="left">
                                    <input type="submit" name="upload" class="btn btn-rounded btn-outline-primary" value="Update data">
                                </td>
                            </tr>
                        </table>
                    </div>
                </form>
    <div class="row gutters-tiny">
        @foreach($projects as $project)
            <div class="col-md-3">
                <div class="block bg-gd-sea">
                    <div class="block-content">
                        <div class="block-options">
                            <button type="button" class="btn-block-option text-white pull-right" data-toggle="tooltip" title="Edit" data-original-title="Edit">
                                Edit
                            </button>
                            <button type="button" class="btn-block-option text-white pull-right" data-toggle="tooltip" title="Delete" data-original-title="Delete">
                                Delete
                            </button>
                        </div>
                        <p class="mt-5 text-center">
                            <i class="si si-folder fa-3x text-white-op"></i>
                        </p>
                        <h5 class="font-w300 text-white text-center">{{$project->project_name}}</h5>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>
@include('includes.project-modals')
@endsection

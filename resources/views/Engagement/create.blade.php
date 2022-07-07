@extends('layouts.backend')
@section('content')
    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h2>Add New Engagement</h2>
            </div>
            <div class="pull-right">
                <a class="btn btn-primary" href="{{ route('engagement.index') }}"> Back</a>
            </div>
        </div>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger">
            <strong>Whoops!</strong> There were some problems with your input.<br><br>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('engagement.store') }}" method="POST">
        @csrf

        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-12">
                <select class="form-control" id="example-select" name="Perspective">
                    <option disabled selected>Select perspective</option>
                    <option value="0">Excellence</option>
                    <option value="1">Discipline</option>
                </select>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="form-group">
                    <strong>Objective:</strong>
                    <input type="text" name="Objective" class="form-control" placeholder="Objective">
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="form-group">
                    <strong>Measure:</strong>
                    <input type="text" class="form-control" name="Measure" placeholder="measure">
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="form-group">
                    <strong>Target:</strong>
                    <input type="number" name="Target" class="form-control" placeholder="Target">
                </div>
            </div>

            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="form-group">
                    <strong>Weight:</strong>
                    <input type="number" name="Weight" class="form-control" placeholder="Weight in percent">
                </div>
            </div>

            <div class="col-xs-12 col-sm-12 col-md-12 text-center">
                <button type="submit" class="btn btn-primary">Submit</button>
            </div>
        </div>

    </form>
@endsection

@extends('layouts.backend')
@section('content')
<div class="container mt-30">
    <div class="row">
        <div class ="col-xl-12">
           <div class="block">
               <div class="block-header block-header-default">
                    <h1 class="block-title text-center">Reprimand Notice</h1>
                </div>
                <div class="block-content">
                    <form action = "{{route('reprifill.store')}}"  method="POST">
                        @csrf
                        <div class="form-group row mt-10">
                            <div class="col-md-4">
                                <h5 class="col-12" for="example-select">Select Employee</h5>
                                <select class="form-control" id="example-select" name="employee">
                                    <option selected disabled>Please select Employee</option>
                                    @foreach($employees as $emp)
                                        <option value="{{$emp->id}}">{{$emp->fname." ".$emp->lname}}</option>
                                    @endforeach

                                </select>
                            </div>
                            <div class="col-md-4">
                                <h5 class="col-12" for="example-select">Select Session</h5>
                                <select class="form-control" id="example-select" name="session">
                                    <option selected disabled>Please select session</option>
                                    @foreach($sessions as $session)
                                        <option value="{{$session->session_name}}">{{$session->session_name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4">
                                <h5 class="col-12">Date Filled</h5>
                                <input type="date" id="startdate" class="form-control" name="date_filled" required>
                            </div>
                        </div>
                            <div class="form-group row">
                                <h5 class="col-12">Offences</h5>
                                <div class="row" style="display:inline-block;">
                                    <ul>
                                        @foreach($categorys as $category)
                                            <ul><h4>{{$category->category_name}}</h4></ul>
                                            @foreach($offences as $offence)
                                            @if($category->id ==$offence->category_id)
                                                <div class="form-check form-check-inline" style="margin-left:200px;">
                                                    <input type="radio" class="form-check-input" id="materialInline1" name="offence" value="{{$offence->id}}">
                                                    <label class="form-check-label" for="materialInline1">{{$offence->description}}</label>
                                                </div>

                                                @endif
                                                @endforeach
                                            @endforeach
                                    </ul>
                                </div>

                            </div>
                            <div class="form-group row mt-20">
                                <h5 class="col-12" for="example-textarea-input">Detailed Reason</h5>
                                <div class="col-12">
                                    <textarea class="form-control" id="example-textarea-input" name="reason" rows="6" placeholder="Detailed Reason"></textarea>
                                </div>
                            </div>

                            <div class="form-group row">
                                <h5 class="col-12" for="example-text-input">Action</h5>
                                    <div class="col-md-9">
                                        <input type="text" class="form-control" id="example-text-input" name="action" placeholder="Action">
                                    </div>
                            </div>
                            <div class="form-group row mt-20">
                                <h5 class="col-12" for="example-textarea-input">Expected Improvment</h5>
                                <div class="col-12">
                                    <textarea class="form-control" id="example-textarea-input" name="required_improvement" rows="6" placeholder="Expected Improvment"></textarea>
                                </div>
                            </div>
                            <div class="form-group row mt-10">
                            <h5 class="col-12" for="example-select">CC</h5>
                            <div class="col-md-4">
                                <select class="form-control" id="example-select" name="cc">
                                    <option value="0">Please select CC</option>
                                    <option value="1">Samuel Negash</option>
                                    <option value="2">Estifanos Alemseged</option>
                                    <option value="3">Odda Kussa</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-12">
                                <button type="submit" class="btn btn-alt-primary">submit</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

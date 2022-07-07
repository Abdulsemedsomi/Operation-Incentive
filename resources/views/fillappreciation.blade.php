@extends('layouts.backend')
@section('content')
<div class="container mt-30">
    <div class="row">
        <div class ="col-xl-12">
                           <div class="block">
                               <div class="block-header block-header-default">
                                    <h1 class="block-title text-center">Appreciation Certificate</h1>
                                </div>
                                <div class="block-content">
                                    <form action="" method="post" enctype="multipart/form-data" onsubmit="return false;">
                                        <div class="form-group row mt-10">
                                            <div class="col-md-4">
                                                <h5 class="col-12" for="example-select">Select Employee</h5>
                                                <select class="form-control" id="example-select" name="example-select">
                                                    <option value="0">Please select Employee</option>
                                                    <option value="1">Samuel Negash</option>
                                                    <option value="2">Estifanos Alemseged</option>
                                                    <option value="3">Odda Kussa</option>
                                                </select>
                                            </div>
                                            <div class="col-md-4">
                                                <h5 class="col-12" for="example-select">Select Session</h5>
                                                    <select class="form-control" id="example-select" name="example-select">
                                                        <option value="0">Please select Session</option>
                                                        <option value="1">Session 1</option>
                                                        <option value="2">Session 2</option>
                                                    </select>
                                            </div>
                                            <div class="col-md-4">
                                                <h5 class="col-12">Date</h5>
                                                <input type="date" id="startdate" class="form-control" name="date" required>
                                            </div>
                                        </div>
                                            <div class="form-group row">
                                                <h5 class="col-12">Crieterias</h5>
                                                    <div class="col-12">
                                                        <div class="custom-control custom-checkbox mb-5">
                                                            <input class="custom-control-input" type="checkbox" name="example-checkbox1" id="example-checkbox1" value="option1">
                                                            <label class="custom-control-label" for="example-checkbox1">Finding solutions for problems by thinking outside of the box</label>
                                                        </div>
                                                        <div class="custom-control custom-checkbox mb-5">
                                                            <input class="custom-control-input" type="checkbox" name="example-checkbox2" id="example-checkbox2" value="option2">
                                                            <label class="custom-control-label" for="example-checkbox2">Putting company mission before anything else </label>
                                                        </div>
                                                        <div class="custom-control custom-checkbox mb-5">
                                                            <input class="custom-control-input" type="checkbox" name="example-checkbox3" id="example-checkbox3" value="option3">
                                                            <label class="custom-control-label" for="example-checkbox3">Completing and accomplishing a goal by going extra miles</label>
                                                        </div>
                                                    </div>
                                            </div>
                                            <div class="form-group row mt-20">
                                                <h5 class="col-12" for="example-textarea-input">More Explanations</h5>
                                                <div class="col-12">
                                                    <textarea class="form-control" id="example-textarea-input" name="example-textarea-input" rows="6" placeholder="More Explanation"></textarea>
                                                </div>
                                            </div>

                                        <div class="form-group row mt-10">
                                            <h5 class="col-12" for="example-select">CC</h5>
                                            <div class="col-md-4">
                                                <select class="form-control" id="example-select" name="example-select">
                                                    <option value="0">Please select CC</option>
                                                    <option value="1">Samuel Negash</option>
                                                    <option value="2">Estifanos Alemseged</option>
                                                    <option value="3">Odda Kussa</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <div class="col-12">
                                                <button type="submit" class="btn btn-alt-primary">Submit</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
@endsection

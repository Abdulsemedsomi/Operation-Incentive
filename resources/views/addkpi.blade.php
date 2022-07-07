@extends('layouts.backend')
@section('content')
<div class="container">
    <div class="mt-50 mb-10 text-center">
        <h2 class="font-w700 text-black mb-10">Add KPI</h2>
        <hr>
    </div>
</div>

<div class="container mt-20">
    <div class="block block-bordered">
        <div class="block-content">
            <div class="col-md-8">
        @if($message = Session::get('success'))
        <div class="alert alert-success alert-block">
         <button type="button" class="close" data-dismiss="alert">×</button>
                <strong>{{ $message }}</strong>
        </div>
        @elseif($message = Session::get('error'))
        <div class="alert alert-danger alert-block">
            <button type="button" class="close" data-dismiss="alert">×</button>
                   <strong>{{ $message }}</strong>
           </div>
        @endif
     </div>
            <form action = "{{ route('kpis.store') }}"   method="post">
                @csrf
                <div class="row mb-20">
                    <div class="col-md-6">
                            <div class="form-material floating">
                                <input type="text" class="form-control" id="kpiname" name="kpiname">

                                <label for="kpiname">KPI Name</label>
                            </div>
                    </div>
                    <input type="text" class="form-control" id="objcount" name="objcount" hidden value=1>
                    <input type="text" class="form-control" id="perscount" name="perscount" hidden value=1>
                    <div class="col-md-6">
                        <div class="form-material floating">
                            <input type="text" class="form-control" id="kpiposition" name="kpiposition">
                            <label for="kpiposition">Select Position</label>
                        </div>
                    </div>
                </div>
                <div class ="addpersdata">
                <div class="block block-bordered">
                    <div class="block-content ">
                        <div class="row mb-20">
                            <div class="col-md-12">
                                <div class="form-material floating">
                                    <input type="text" class="form-control" id="perspective1" name="perspective1">
                                    <label for="perspective1">Perspective</label>
                                </div>
                            </div>
                        </div>
                        <div class="block block-bordered">
                            <div class="block-content">
                                <div class="container addkpidata1">
                                    <div class="row mb-20">
                                        <div class="col-md-6">
                                            <div class="form-material floating">
                                                <textarea class="form-control" id="kpiobj1" name="kpiobj1 1" rows="3"></textarea>
                                                <label for="kpiobj1">Objective</label>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-material floating">
                                                <textarea class="form-control" id="measure1" name="measure1 1" rows="3"></textarea>
                                                <label for="measure1">Measure</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mb-20">
                                        <div class="col-md-3">
                                            <div class="form-material floating">
                                                <input type="number" class="form-control" id="target1" name="target1 1" min=0>
                                                <label for="target1">Target</label>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-material floating">
                                                <input type="number" class="form-control" id="weight1" name="weight1 1" min=0>
                                                <label for="weight1">Weight</label>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-material floating">
                                                <select class="form-control" id="type1" name="type1 1">
                                                    <option disabled selected>Select Type</option>
                                                    <option value = 1 >Appreciation</option>
                                                    <option value = 2>Reprimand</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-material floating">
                                                <select class="form-control" id="formula1" name="formula1 1">
                                                    <option disabled selected>Select Formula</option><!-- Empty value for demostrating material select box -->
                                                    <option value="0">Yes or No</option>
                                                    @php
                                                    $formulas = App\Formula::where('type',1)->get()
                                                    @endphp
                                                    @foreach($formulas as $f)
                                                    <option value={{$f->id}}>{{$f->formula}}</option>
                                                    @endforeach


                                                </select>

                                            </div>
                                        </div>

                                    </div>

                                </div>

                            </div>

                        </div>
                        <div class="col-md-3 mt-20">
                                            <button type="button" class="btn btn-rounded btn-outline-info mr-5 mb-5 addObjKpi" id="addObjKpi1" data-count =1 data-pers = 1>
                                                <i class="fa fa-plus mr-5"></i>Add Objective
                                            </button>
                            </div>


                    </div>

                </div>
                </div>
                <div class ="row mb-20">
                            <div class="col-md-3">
                                <button type="button" class="btn btn-rounded btn-outline-info mr-5 mb-5" id="addPersKpi" data-count=1>
                                    <i class="fa fa-plus mr-5"></i>Add Perspective
                                </button>
                            </div>
                        </div>
                <div class ="row mb-20 ">
                    <div class="col-md-12 ">
                        <button type="submit" class="btn btn-alt-success pull-right" id="finishbutton" data-dismiss="modal">
                            <i class="fa fa-check"></i> Finish
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@extends('layouts.backend')
@section('content')
<div class="container">
    <div class="mt-50 mb-10 text-center">
        <h2 class="font-w700 text-black mb-10">Edit KPI</h2>
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

            <form action = "{{ route('kpis.update', $kpi->id) }}"   method="post">
                @csrf
                 <input type="hidden" name="_method" value="put" />
                <div class="row mb-20">
                    <div class="col-md-6">
                            <div class="form-material floating open">
                                <input type="text" class="form-control" id="kpiname" name="kpiname" value="{{$kpi->kpi_name}}">

                                <label for="kpiname">KPI Name</label>
                            </div>
                    </div>
                    <input type="text" class="form-control" id="objcount" name="objcount" hidden value={{$objcount}}>
                    <input type="text" class="form-control" id="perscount" name="perscount" hidden value={{$pers->count()}}>
                    <div class="col-md-6">
                        <div class="form-material floating open">
                            <input type="text" class="form-control" id="kpiposition" name="kpiposition" value="{{$kpi->position}}">
                            <label for="kpiposition">Select Position</label>
                        </div>
                    </div>
                </div>
                <div class ="addpersdata">
                    @php
                     $count = 1;
                    @endphp
                    @foreach($pers as $p)
                <div class="block block-bordered">
                    <div class="block-content ">
                        <div class="row mb-20">
                            <div class="col-md-12">
                                <div class="form-material floating open">

                                <input type="text" class="form-control" id="perspective{{$count}}" name="perspective{{$count}}" value="{{$p->perspective}}">
                                    <label for="perspective1">Perspective</label>
                                </div>
                            </div>
                        </div>
                        <div class="block block-bordered">
                            <div class="block-content">
                                <div class="container addkpidata{{$count}}">
                                     @php
                                    $kpiforms = App\Kpiform::where('kpi_id',$kpi->id)->where('perspective', $p->perspective)->get();
                                    $ocount = 0;
                                        @endphp
                                    @foreach($kpiforms as $kp)
                                <div id="objblock{{++$ocount}}{{$count}}">
                                    @if($ocount > 1)
                                    <hr style="color:blue;" >
                                    @endif
                                    <div class="row mb-20">
                                        <div class="col-md-6">
                                            <div class="form-material floating open">
                                                <textarea class="form-control" id="kpiobj{{$ocount}}" name="kpiobj{{$ocount}} {{$count}}" rows="3"  >{{$kp->objective}}</textarea>
                                                <label for="kpiobj{{$ocount}}">Objective</label>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-material floating open">
                                                <textarea class="form-control" id="measure{{$ocount}}" name="measure{{$ocount}} {{$count}}" rows="3" >{{$kp->measure}}</textarea>
                                                <label for="measure{{$ocount}}">Measure</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mb-20">
                                        <div class="col-md-3">
                                            <div class="form-material floating open">
                                                <input type="number" class="form-control" id="target{{$ocount}}" name="target{{$ocount}} {{$count}}" min=0 value = {{$kp->target}}>
                                                <label for="target{{$ocount}}">Target</label>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-material floating open">
                                                <input type="number" class="form-control" id="weight{{$ocount}}" name="weight{{$ocount}} {{$count}}" min=0 value = {{$kp->weight}}>
                                                <label for="weight{{$ocount}}">Weight</label>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-material floating open">
                                                <select class="form-control" id="formula{{$ocount}}" name="formula{{$ocount}} {{$count}}">
                                                    <option disabled selected>Select Formula</option><!-- Empty value for demostrating material select box -->
                                                    <option value="0" {{$kp->formula_id == null? 'selected': ""}}>Yes or No</option>
                                                    @php
                                                    $formulas = App\Formula::where('type',1)->get()
                                                    @endphp
                                                    @foreach($formulas as $f)
                                                    <option value={{$f->id}} {{$kp->formula_id == $f->id? 'selected': ""}}>{{$f->formula}}</option>
                                                    @endforeach
                                                    @if($formulas->count()==0)
                                                    <option value=0>No Formula. Please add a Formula</option>
                                                    @endif

                                                </select>

                                            </div>

                                        </div>
                                         @if($ocount > 1)
                                            <div class="col-md-2 mt-20">
                                                <button type="button" class="btn btn-outline-danger mr-5 mb-5 removeObjKpi"  data-count ={{$ocount}} data-pers = {{$count}}><i class="fa fa-minus mr-5"></i></button>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                                    @endforeach
                                </div>

                            </div>

                        </div>
                        <div class="col-md-3 mt-20">
                                            <button type="button" class="btn btn-rounded btn-outline-info mr-5 mb-5 addObjKpi" id="addObjKpi{{$count}}" data-count ={{$ocount}} data-pers = {{$count++}}>
                                                <i class="fa fa-plus mr-5"></i>Add Objective
                                            </button>
                            </div>


                    </div>

                </div>
                @endforeach
                </div>
                <div class ="row mb-20">
                            <div class="col-md-3">
                                <button type="button" class="btn btn-rounded btn-outline-info mr-5 mb-5" id="addPersKpi" data-count={{$pers->count()}}>
                                    <i class="fa fa-plus mr-5"></i>Add Perspective
                                </button>
                            </div>
                        </div>
                <div class ="row mb-20 ">
                    <div class="col-md-12 ">
                        <button type="submit" class="btn btn-alt-success pull-right" id="finishbutton" data-dismiss="modal">
                            <i class="fa fa-check"></i> Edit
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@extends('layouts.backend')
@section('content')
<div class="container">
    <div class="mt-50 mb-10 text-center">
        <h2 class="font-w700 text-black mb-10">Employee Engagement</h2>
        <hr>
    </div>
    <div class="row">
        <div class="col-md-3">
        <button type="button" class="btn btn-rounded btn-outline-info" data-toggle="modal" data-target="#modal-extra-large">Add Engagement Objective</button>
        </div>
        <div class="col-md-9">
        <button type="button" class="btn btn-rounded btn-outline-info pull-right" data-toggle="modal" data-target="#addFormulaModal" id="engageformula" data-id = 30>Add Formula</button>
        </div>
    </div>
    @if ($message = Session::get('success'))
        <div class="alert alert-success row" id="mess">
            <p class="col-md-11">{{ $message }}</p>
            <a href="" style="margin-left: 30px;" class="pull-right" ><i onclick="myFunction()" class="fa fa-close"></i></a>
        </div>
    @endif

<div class="container mt-20">
    <div class="block">
        <div class="block-header block-header-default">
            <h3 class="block-title">Excellence</h3>
        </div>
        <div class="block-content">
            <table class="table table-bordered table-vcenter">
                <thead>
                    <tr>
                        <th>Objective</th>
                        <th>Measure</th>
                        <th>Target</th>
                        <th>Weight</th>
                        <th style="text-align: center;">Actions</th>
                    </tr>
                </thead>
                <tbody>

                @foreach($engagements as $engagement)
                    @if($engagement->Perspective == 0)
                        <tr>
                            <td>{{$engagement->Objective}}</td>
                            <td>{{$engagement->Measure}}</td>
                            <td class="d-none d-sm-table-cell">{{$engagement->Target}}</td>
                            <td class="d-none d-sm-table-cell">{{$engagement->Weight}}</td>
                            <td style="text-align: center;">
                                <form action="{{ route('engagement.destroy',$engagement->id) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <div class="btn-group">
                                        <a href="{{ route('engagement.edit',$engagement->id) }}" type="button"
                                           data-toggle="modal" data-target="#modal-extra-large-edit"
                                           class="btn btn-sm btn-secondary" data-toggle="tooltip" title="Edit">
                                            <i class="si si-pencil"></i>
                                        </a>

                                        <button type="submit" class="btn btn-sm btn-secondary" data-toggle="tooltip" title="Delete">
                                            <i class="si si-trash"></i>
                                        </button>
                                    </div>
                                </form>
                            </td>
                        </tr>
                    @endif
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
                    <div class="block">
                        <div class="block-header block-header-default">
                            <h3 class="block-title">Dicipline</h3>
                        </div>
                        <div class="block-content">
                            <table class="table table-bordered table-vcenter">
                                <thead>
                                    <tr>
                                        <th>Objective</th>
                                        <th>Measure</th>
                                        <th>Target</th>
                                        <th>Weight</th>
                                        <th style="text-align: center;">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                @foreach($engagements as $engagement)
                                    @if($engagement->Perspective == 1)
                                        <tr>
                                            <td>{{$engagement->Objective}}</td>
                                            <td>{{$engagement->Measure}}</td>
                                            <td class="d-none d-sm-table-cell">{{$engagement->Target}}</td>
                                            <td class="d-none d-sm-table-cell">{{$engagement->Weight}}</td>
                                            <td style="text-align: center;">
                                                <form action="{{ route('engagement.destroy',$engagement->id) }}" method="POST">
                                                    @csrf
                                                    @method('DELETE')
                                                    <div class="btn-group">
                                                        <a href="{{ route('engagement.edit',$engagement->id) }}" type="button"
                                                           data-toggle="modal" data-target="#modal-extra-large-edit"
                                                           class="btn btn-sm btn-secondary" data-toggle="tooltip" title="Edit">
                                                            <i class="si si-pencil"></i>
                                                        </a>

                                                        <button type="submit" class="btn btn-sm btn-secondary" data-toggle="tooltip" title="Delete">
                                                            <i class="si si-trash"></i>
                                                        </button>
                                                    </div>
                                                </form>
                                            </td>
                                        </tr>
                                    @endif
                                    @endforeach

                                </tbody>
                            </table>
                        </div>
                    </div>
</div>
</div>

<div class="modal" id="modal-extra-large" tabindex="-1" role="dialog" aria-labelledby="modal-extra-large" aria-hidden="true">

    <form action="{{ route('engagement.store') }}" method="POST">
        @csrf
    <div class="modal-dialog modal-m" role="document">

                <div class="modal-content">

                    <div class="block block-themed block-transparent mb-0">
                        <div class="block-header bg-primary">
                            <h3 class="block-title">Add Engagement Criteria</h3>
                            <div class="block-options">
                                <button type="button" class="btn-block-option" data-dismiss="modal" aria-label="Close">
                                    <i class="si si-close"></i>
                                </button>
                            </div>
                        </div>
                        <div class="block-content">
                            <div class="container">

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
                                <div class="form-group">

                                        <div class="row form-group">
                                            <label class="col-md-3" for="example-select">Perspective</label>
                                                <select class="form-control col-md-9" id="example-select" name="Perspective">
                                                    <option selected disabled>Select Pespective</option>
                                                    <option value="0">Excellence</option>
                                                    <option value="1">Discipline</option>
                                                </select>
                                        </div>
                                        <div class="row form-group">
                                            <label class="col-md-3" for="example-select">Objective</label>
                                                <textarea class="form-control col-md-9" id="example-textarea-input" name="Objective" rows="1" placeholder="Objective"></textarea>
                                        </div>
                                        <div class="row form-group">
                                            <label class="col-md-3" for="example-select">Measure</label>
                                            <textarea class="form-control col-md-9" id="example-textarea-input" name="Measure" rows="1" placeholder="Measure"></textarea>
                                        </div>
                                        <div class="row form-group">
                                            <label class="col-md-3" for="example-select">Target</label>
                                            <input type="number" name="Target" class="form-control col-md-2"  min="0">
                                        </div>
                                        <div class="row form-group">
                                            <label class="col-md-3" for="example-select">Weight</label>
                                            <input type="number" name="Weight" class="form-control col-md-2" placeholder="%" min="0">
                                        </div>
                                        <div class="row form-group">
                                            <label class="col-md-3" for="example-select">Formula</label>
                                                <select class="form-control col-md-9" id="example-select" name="formula_id">
                                                   @php
                                                    $formulas = App\Formula::where('type',0)->get()
                                                    @endphp
                                                    @foreach($formulas as $f)
                                                    <option value={{$f->id}}>{{$f->formula}}</option>
                                                    @endforeach
                                                    @if($formulas->count()==0)
                                                    <option value=0>No Formula. Please add a Formula</option>
                                                    @endif
                                                </select>
                                        </div>
                                        <div class="col-md-7 mt-10">
                                            <label class="text-secondary" for="example-select"> </label>

                                        </div>
                            </div>

                        </div>
                    </div>
                    <div class="modal-footer">

                        <button type="submit" class="btn btn-alt-info"  {{$formulas->count()>0? "":"disabled" }}>
                            <i class="fa fa-check"></i> Add
                        </button>
                        <button type="button" class="btn btn-alt-danger" data-dismiss="modal">Close</button>

                    </div>

                </div>

            </div>
        </div>
    </form>
</div>


<script>
    function myFunction() {
        document.getElementById("mess").style.display = "hidden";
    }
</script>

@include('includes.engageformula-modal')
@endsection

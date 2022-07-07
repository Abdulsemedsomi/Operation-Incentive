@extends('layouts.backend')
@section('content')
<div class="container">
<div class="mt-50 mb-10 text-center">
    <h2 class="font-w700 text-black mb-10">Key Performance Indicators</h2>
    <hr>

</div>
<div class="row">
    <div class="col-md-3">
        <a  type="button" class="btn btn-rounded btn-outline-info min-width-125" href="{{ route('kpis.create') }}">Add KPI</a>

    </div>
    <div class="col-md-9">
    <button type="button" class="btn btn-rounded btn-outline-info pull-right" data-toggle="modal" data-target="#addFormulaModal" id="kpiformula" data-id = 40>Add Formula</button>
    </div>
</div>
</div>
<div class="container mt-20">
    <div class="row">
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
        <!--<form method="post" enctype="multipart/form-data" action="{{ url('/import_excel/importkpi') }}">-->
        <!--    @csrf-->
        <!--    <div class="form-group">-->
        <!--        <table class="table">-->
        <!--            <tr>-->
        <!--                <td width="40%" align="right"><label>Select File for Upload</label></td>-->
        <!--                <td width="30">-->
        <!--                    <input type="file" name="select_file" />-->
        <!--                </td>-->
        <!--                <td width="30%" align="left">-->
        <!--                    <input type="submit" name="upload" class="btn btn-primary" value="Update data">-->
        <!--                </td>-->
        <!--            </tr>-->
        <!--        </table>-->
        <!--    </div>-->
        <!--</form>-->
        @foreach($kpis as $kpi)
    <div class="col-md-6" id="kpidata{{$kpi->id}}">
        <div class="block">
                    <div class="block-header block-header-default">
                         <h3 class="block-title">KPI</h3>
                        <div class="block-options pull-right">
                            <a type="button" class="btn-block-option" href = {{ route('kpis.edit',$kpi->id )}}>
                                <i class="si si-pencil"></i>
                            </a>
                            <button type="button" class="btn-block-option deletekpi" data-id={{$kpi->id}}>
                                <i class="si si-trash"></i>
                            </button>
                        </div>
                    </div>
             <a class="block" href = {{ route('kpis.show',$kpi->id )}}>
                <div class="block-content">
                    <h5>{{$kpi->kpi_name}}</h5>
                </div>
            </a>
        </div>


    </div>
    @endforeach


</div>

 <!-- delete modal -->
<div class="modal fade" id="deletekpimodal" aria-hidden="true">
    <div class="modal-dialog">
        <div class="block block-themed block-transparent mb-0">
            <div class="block-header bg-danger">
                <h3 class="block-title">Delete KPI</h3>
                <div class="block-options">
                    <button type="button" class="btn-block-option" data-dismiss="modal" aria-label="Close">
                        <i class="si si-close"></i>
                    </button>
                </div>
            </div>
            <div class="modal-content">
                <div class="modal-body">
                    <label for="inputLink" class=" control-label col-md-push-1" id="deletelabel"></label>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-rounded btn-outline-danger" id="kpi-delete" value="">Delete</button>
    
                </div>
            </div>
        </div>
    </div>
</div>

        @include('includes.engageformula-modal')
@endsection

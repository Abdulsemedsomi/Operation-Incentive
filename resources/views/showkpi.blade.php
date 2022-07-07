@extends('layouts.backend')
@section('content')
<div class="container">
    <div class="mt-50 mb-10 ">
        <div class="row mb-10">
            <label class="font-w700 text-black col-md-4">Name of {{App\Kpi::find($filledkpi->kpi_id)->position}}:</label>
            <label class="font-w700 col-md-4">{{App\User::find($filledkpi->employee_id)->fname. " ". App\User::find($filledkpi->employee_id)->lname}}</label>
         </div>
         <div class="row mb-10">
            <label class="font-w700 text-black col-md-4">Manager:</label>
            <label class="font-w700  col-md-4">{{App\User::find($filledkpi->filledby_id)->fname. " ". App\User::find($filledkpi->filledby_id)->lname}}</label>
         </div>
          <div class="row mb-10">
            <label class="font-w700 text-black col-md-4">Last update:</label>
            <label class="font-w700  col-md-4">{{$filledkpi->updated_at}}</label>
         </div>
         <div class="row mb-10">
            <label class="font-w700 text-black col-md-4">Total score:</label>
            <label class="font-w700  col-md-4">{{$filledkpi->totalScore}}</label>
         </div>

            <hr>
    </div>



</div>
    {{-- Table Display --}}
<div class="container mt-20">
    @foreach($pers as $p)
    <div class="block">

        <div class="block-header block-header-default bg-gray-dark">
            <h3 class="block-title text-white" >{{$p->perspective }}</h3>
        </div>
            @php
                        $kpiforms = App\Kpiform::where('kpi_id',$filledkpi->kpi_id)->where('perspective', $p->perspective)->get();
            @endphp

        <div class="block-content">
            <table class="table table-bordered table-hover table-vcenter">
                <thead>
                    <tr>


                        <th>Objective</th>
                        <th>Measure</th>
                        <th>Target</th>
                        <th>Actual</th>
                        <th>Weight</th>
                        <th>Score</th>
                    </tr>
                </thead>
                <tbody>


                    @foreach($kpiforms as $kp)
                    @php
                    $filledkpilist = App\Filledkpilist::where('filledkpi_id',$filledkpi->id)->where('kpiform_id', $kp->id)->first();
                    @endphp
                    <tr >

                            <td>{{$kp->objective}}</td>
                             <td>{{$kp->measure}}</td>
                             <td>{{$kp->formula_id == null? "Yes" : $kp->target}}</td>
                               <td>{{$filledkpilist?$filledkpilist->actual:0}}</td>
                              <td>{{$kp->weight}}%</td>
                               <td>{{$filledkpilist?$filledkpilist->score:0}}</td>
                            @endforeach



                    </tr>

                </tbody>
            </table>
        </div>

    </div>
    @endforeach

</div>





</div>

@endsection

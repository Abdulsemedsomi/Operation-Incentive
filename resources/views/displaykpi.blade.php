@extends('layouts.backend')
@section('content')
<div class="container">
    <div class="mt-50 mb-10 text-center">
        <h2 class="font-w700 text-black mb-10">{{$kpi->kpi_name}}</h2>
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
                        $kpiforms = App\Kpiform::where('kpi_id',$kpi->id)->where('perspective', $p->perspective)->get();
            @endphp

        <div class="block-content">
            <table class="table table-bordered table-hover table-vcenter">
                <thead>
                    <tr>


                        <th>Objective</th>
                        <th>Measure</th>
                        <th>Target</th>
                        <th>Weight</th>
                        <th>Formula</th>
                    </tr>
                </thead>
                <tbody>


                    @foreach($kpiforms as $kp)
                    <tr >

                            <td>{{$kp->objective}}</td>
                             <td>{{$kp->measure}}</td>
                             <td>{{$kp->formula_id == null? "Yes" : $kp->target}}</td>
                              <td>{{$kp->weight}}%</td>
                               <td>{{$kp->formula_id == null? "Yes or no": App\Formula::find($kp->formula_id)->formula}}</td>
                            @endforeach



                    </tr>

                </tbody>
            </table>
        </div>

    </div>
    @endforeach

</div>





</div>
@include('includes.filledkpi-modal')
@endsection

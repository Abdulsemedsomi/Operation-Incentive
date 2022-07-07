@extends('layouts.backend')
@section('content')
<div class="container">
    <div class="mt-50 mb-10 text-center">
        <h2 class="font-w700 text-black mb-10">Appreciation</h2>
        <hr>
    </div>
        <button id = "addb" type="button" class="btn btn-rounded btn-outline-info min-width-125" data-toggle="modal" data-target="#modal-fadein">Add Appreciation Criteria</button>
    <div class="container mt-20">
        <div class="row">

                <div class="col-xl-12">
                    <div class="block">
                        <div class="block-content block-content-full">
                            <h3 class="text-left">Appreciation Criteria</h3>
                            <ul>
                                Finding solutions for problems by thinking outside of the box
                            </ul>
                            <ul>
                                Putting company mission before anything else
                            </ul>
                        </div>
                    </div>
                </div>

        </div>
    </div>
</div>
@include('includes.addappreciation-modal')
@endsection

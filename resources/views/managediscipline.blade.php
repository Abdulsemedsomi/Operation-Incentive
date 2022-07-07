@extends('layouts.backend')
@section('content')
<div class="container">
<div class="mt-50 mb-10 text-center">
    <h2 class="font-w700 text-black mb-10">Reprimand And Appericiation</h2>
    <hr>

</div>
<div class="container mt-20">
    <div class="row">
        <div class="col-xl-4">

                <div class="block">
                    <div class="block-header block-header-default">

                        <div class="block-options">
                            <div class="btn-group show" role="group">
                                <button type="button" class="btn btn-rounded btn-dual-secondary" id="page-header-user-dropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                                    <i class="fa fa-user d-sm-none"></i>
                                    <span class="d-none d-sm-inline-block"> <i class="fa fa-bars"></i></span>
                                    <i class="fa fa-angle-down ml-5"></i>
                                </button>
                                <div class="dropdown-menu dropdown-menu-right min-width-200 " aria-labelledby="page-header-user-dropdown" style="position: absolute; transform: translate3d(-103px, 34px, 0px); top: 0px; left: 0px; will-change: transform;" x-placement="bottom-end">
                                    <a class="dropdown-item d-flex align-items-center justify-content-between" href="">
                                        Edit
                                    </a>
                                    <a class="dropdown-item" href="">
                                    Delete
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                <a href="{{ url('reprimand') }}">
                    <div class="block-content block-content-full">
                        <h1 class="text-left">Reprimand</h1>
                    </div>
                    </a>
                </div>

        </div>
        <div class="col-xl-4">

                <div class="block">
                    <div class="block-header block-header-default">

                        <div class="block-options">
                            <div class="btn-group show" role="group">
                                <button type="button" class="btn btn-rounded btn-dual-secondary" id="page-header-user-dropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                                    <i class="fa fa-user d-sm-none"></i>
                                    <span class="d-none d-sm-inline-block"> <i class="fa fa-bars"></i></span>
                                    <i class="fa fa-angle-down ml-5"></i>
                                </button>
                                <div class="dropdown-menu dropdown-menu-right min-width-200 " aria-labelledby="page-header-user-dropdown" style="position: absolute; transform: translate3d(-103px, 34px, 0px); top: 0px; left: 0px; will-change: transform;" x-placement="bottom-end">
                                    <a class="dropdown-item d-flex align-items-center justify-content-between" href="">
                                        Edit
                                    </a>
                                    <a class="dropdown-item" href="">
                                    Delete
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                <a href="{{ url('appreciation') }}">
                    <div class="block-content block-content-full">
                        <h1 class="text-left">Appericiation</h1>
                    </div>
                    </a>
                </div>

        </div>
    </div>
</div>
</div>

@endsection

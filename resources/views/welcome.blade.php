@extends('layouts.simple')

@section('content')

    <!-- Hero -->
    <div id="page-container" class="main-content-boxed">

        <!-- Main Container -->
        <main id="main-container">
            <!-- Page Content -->
            <div class="bg-image" style="background-image: url('images/Login.png');">
                <div class="row mx-0">
                    <div class="hero-static col-md-6 col-xl-8 d-none d-md-flex align-items-md-end">
                        <div class="p-30 invisible" data-toggle="appear">
                            <p class="font-size-h3 font-w600 text-white">
                                Good is the enemy of Great
                            </p>
                            <p class="font-italic text-white-op">
                                IE Networks &copy; <span class="js-year-copy"></span>
                            </p>
                        </div>
                    </div>
                    <div class="hero-static col-md-6 col-xl-4 d-flex align-items-center bg-white invisible text-center" data-toggle="appear" data-class="animated fadeInRight">
                        <div class="content content-full">
                            <!-- Header -->
                             <!--<div class="alert alert-danger alert-dismissible fade show mb-2 col-md-10 text-center "  ><strong>System under maintenance! Please come back later</strong></div>-->
                            <div class="px-30 py-10">
                                 
                                <a class="link-effect font-w700">
                                    {{-- <i class="si si-fire"></i> --}}
                                      
                                    <img src="{{url('images/IE logo.png')}}"  width="75" height="75">
                                    <br>
                                    <span class="font-size-h3 text-primary-dark">Performance Managment System</span>
                                </a>
                                <h1 class="h3 font-w700 mt-30 mb-10 text-center">Welcome</h1>
                                <h2 class="h5 font-w400 text-muted mb-0">Please Login with your respective account.</h2>
                                <div class="py-30">
                                    <a class="btn btn-hero btn-noborder btn-rounded btn-success min-width-175 mb-10 mx-5 invisible <style " data-toggle="appear" data-class="animated fadeInUp" href="<?php echo e(route('microsoft')); ?>">
                                        <i class="fa fa-rocket mr-10"></i> Microsoft
                                    </a>
                                    <a class="btn btn-hero btn-noborder btn-rounded btn-primary min-width-175 min-height-10 mb-10 mx-5 invisible" data-toggle="appear" data-class="animated fadeInUp" href="<?php echo e(route('google')); ?>">
                                        <i class="fa fa-fw fa-google"></i> Google
                                    </a>

                                    <form style="margin-left:200px" class="contact">
                                        <div class="invisible" data-toggle="appear" data-class="animated fadeInRight" data-timeout="300">
                                            <a class="txt2" href="{{ url('/support2') }}">
                                                Have trouble with login?
                                                <i class="fa fa-long-arrow-right m-l-5" aria-hidden="true"></i>
                                            </a>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            <!-- END Header -->

                            <!-- Sign In Form -->
                            <!-- jQuery Validation functionality is initialized with .js-validation-signin class in js/pages/op_auth_signin.min.js which was auto compiled from _es6/pages/op_auth_signin.js -->


                            <!-- END Sign In Form -->
                        </div>
                    </div>
                </div>
            </div>
            <!-- END Page Content -->
        </main>
        <!-- END Main Container -->
    </div>
    <!-- END Page Container -->


   




@endsection

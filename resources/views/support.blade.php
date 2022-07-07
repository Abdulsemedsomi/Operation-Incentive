@extends('layouts.backend')
@section('content')
<div class="bg-primary">
    <div class="bg-pattern bg-black-op-25" style="background-image: url('images/bg-pattern.png');">
        <div class="content content-top text-center">
            <div class="py-50">
                <h1 class="font-w700 text-white mb-10">Do you have any questions?</h1>
                <h4 class="h4 font-w300 text-white-op" >Get in touch</h4>
            </div>
        </div>
    </div>
</div>
<div class="content content-full">
    <div class="block">
        <div class="block-content">
            <h2 class="content-heading">Contact Support</h2>
            <div class="row py-10 justify-content-center">
                <div class="col-lg-8 col-xl-6">

                    <form method="POST" id="contact" class="">
                        @csrf
                        <div class="form-group row">
                            <label class="col-12" for="name">Name</label>
                            <div class="col-12">
                                <input type="text" class="form-control form-control-lg round" id="name" name="name" placeholder="Full name" value="{{Auth::user()->fname . " " . Auth::user()->lname }}">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-12" for="email">Email</label>
                            <div class="col-12">
                                <input type="email" class="form-control form-control-lg round" id="email" name="email" placeholder="Enter your email" value="{{Auth::user()->email}}">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-12" for="subject">Type?</label>
                            <div class="col-12">
                                <select class="form-control form-control-lg round" id="subject" name="subject">
                                    <option value="Bug">Bug</option>
                                    <option value="Critical Problem">Critical Problem</option>
                                    <option value="Recommendation">Recommendation</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-12" for="Where">Where?</label>
                            <div class="col-12 round">
                                <select class="form-control form-control-lg" id="Where" name="Where">
                                    <option value="Check-in">Check-in</option>
                                    <option value="OKR">OKR</option>
                                    <option value="Login">Login</option>
                                    <option value="Engagement">Engagement</option>
                                    <option value="KPI">KPI</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-12" for="message">Message</label>
                            <div class="col-12">
                                <textarea class="form-control form-control-lg round" id="message" name="message" rows="10" placeholder="Enter your message"></textarea>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-12 text-center">
                                <button type="submit" class="btn btn-hero btn-rounded btn-alt-primary min-width-175">
                                    <i class="fa fa-send mr-5"></i> Send Message
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@extends('layouts.backend')
@section('content')
<div class="bg-primary">
    <div class="bg-pattern bg-black-op-25" style="background-image: url('images/bg-pattern.png');">
        <div class="content content-top text-center">
            <div class="py-50">
                <h1 class="font-w700 text-white mb-10">Video Tutorial </h1>
                <h4 class="h4 font-w300 text-white-op" >Check out our <a href="#faq">FAQ</a></h4>
            </div>
        </div>
    </div>
</div>
<div class="content content-full" style="text-align:center">
    <div class="block">
        <div class="block-content">
          <video  width="90%" controls autoplay>
             <source src="media/videos/pms.mp4" type="video/mp4">
                 Your browser does not support the video tag.
          </video>
           <br>
           <br>
        </div>
    </div>
</div>
<div class="content content-full">
    <h2 class="content-heading"><a id="faq"> Frequently Asked Questions </a></h2>
    <div class="block">
        <div class="block-header block-header-default">
            <h3 class="block-title">
                <strong>How-to Guides</strong> 
            </h3>
            <div class="block-options">
                <button type="button" class="btn-block-option">
                    <i class="si si-question"></i>
                </button>
            </div>
        </div>
        <div class="block-content block-content-full">
            <div id="faq1" role="tablist" aria-multiselectable="true">
                <div class="block block-bordered block-rounded mb-5">
                    <div class="block-header" role="tab" id="faq1_h1">
                        <a class="font-w600 text-body-color-dark" data-toggle="collapse" href="#faq1_q1" aria-expanded="true" aria-controls="faq1_q1">How to create an Objective ?</a>
                    </div>
                    <div id="faq1_q1" class="collapse show" role="tabpanel" aria-labelledby="faq1_h1" data-parent="#faq1">
                        <div class="block-content border-t">
                            <p>  To create an Objective, you need to make sure that there is a created OKR session with status Open.<br><br>

                         1. From the OKRs drop-down menu choose the respective session where you'd like to create the objective. <br>
                         2. click the Add new objective button on the upper right corner. A form for creating a new Objective will appear. </p>
                        </div>
                    </div>
                </div>
                <div class="block block-bordered block-rounded mb-5">
                    <div class="block-header" role="tab" id="faq1_h2">
                        <a class="font-w600 text-body-color-dark" data-toggle="collapse" href="#faq1_q2" aria-expanded="true" aria-controls="faq1_q2">How to define Key Results ?</a>
                    </div>
                    <div id="faq1_q2" class="collapse" role="tabpanel" aria-labelledby="faq1_h2" data-parent="#faq1">
                        <div class="block-content border-t"> 
                         <p> To define a manual KR, start by opening an objective you've created or by creating an objective. You should be on this screen so that you can click on the button + Add key result.</p>
                        </div>
                    </div>
                </div>
                <div class="block block-bordered block-rounded mb-5">
                    <div class="block-header" role="tab" id="faq1_h3">
                        <a class="font-w600 text-body-color-dark" data-toggle="collapse" href="#faq1_q3" aria-expanded="true" aria-controls="faq1_q3">What is the purpose of to milestone ? </a>
                    </div>
                    <div id="faq1_q3" class="collapse" role="tabpanel" aria-labelledby="faq1_h3" data-parent="#faq1">
                        <div class="block-content border-t">
                            <p> A milestone is a specific point in time within a project lifecycle used to measure the progress of a project toward its ultimate goal. ... Simply put, it's a reference point that marks a major event or a branching decision point within a project. </p>
                            <p> In PMS also milestone is a bridge between a task and a key result. They will be defined at the beginning of every quarter and helps us to reduce the fluctuation of the performance evaluation. </p>
                        </div>
                    </div>
                </div>
                <div class="block block-bordered block-rounded mb-5">
                    <div class="block-header" role="tab" id="faq1_h4">
                        <a class="font-w600 text-body-color-dark" data-toggle="collapse" href="#faq1_q4" aria-expanded="true" aria-controls="faq1_q4">How do I update my profile?</a>
                    </div>
                    <div id="faq1_q4" class="collapse" role="tabpanel" aria-labelledby="faq1_h4" data-parent="#faq1">
                        <div class="block-content border-t">
                           <p> Update your profile in a snap! Click your avatar in the upper right corner of pms and select 'Profile' from the menu that populates. For now you only can update your profile picture. </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div> 
@endsection

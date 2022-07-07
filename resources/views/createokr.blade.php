@extends('layouts.backend')
@section('content')
<style>
@import url(https://fonts.googleapis.com/css?family=Montserrat);


* {
    margin: 0;
    padding: 0;
}

html {
    height: 100%;
}


#grad1 {
    background: #f0f2f5;

}

#msform {
    text-align: center;
    position: relative;
    margin-top: 20px
}

#msform fieldset .form-card {
    background: white;
    border: 0 none;
    border-radius: 0px;
    box-shadow: 0 2px 2px 2px rgba(0, 0, 0, 0.2);
    padding: 20px 40px 30px 40px;
    box-sizing: border-box;
    width: 94%;
    margin: 0 3% 20px 3%;
    position: relative;
}

#msform fieldset {
    background: white;
    border: 0 none;
    border-radius: 0.5rem;
    box-sizing: border-box;
    width: 100%;
    margin: 0;
    padding-bottom: 20px;
    position: relative
}

#msform fieldset:not(:first-of-type) {
    display: none
}

#msform fieldset .form-card {
    text-align: left;
    color: #9E9E9E
}





#msform .action-button {
    width: 100px;
    background: skyblue;
    font-weight: bold;
    color: white;
    border: 0 none;
    border-radius: 0px;
    cursor: pointer;
    padding: 10px 5px;
    margin: 10px 5px
}

#msform .action-button:hover,
#msform .action-button:focus {
    box-shadow: 0 0 0 2px white, 0 0 0 3px skyblue
}

#msform .action-button-previous {
    width: 100px;
    background: #616161;
    font-weight: bold;
    color: white;
    border: 0 none;
    border-radius: 0px;
    cursor: pointer;
    padding: 10px 5px;
    margin: 10px 5px
}

#msform .action-button-previous:hover,
#msform .action-button-previous:focus {
    box-shadow: 0 0 0 2px white, 0 0 0 3px #616161
}

select.list-dt {
    border: none;
    outline: 0;
    border-bottom: 1px solid #ccc;
    padding: 2px 5px 3px 5px;
    margin: 2px
}

select.list-dt:focus {
    border-bottom: 2px solid skyblue
}

.card {
    z-index: 0;
    border: none;
    border-radius: 0.5rem;
    position: relative
}

.fs-title {
    font-size: 20px;
    color: #2C3E50;
    margin-bottom: 10px;
    font-weight: bold;
    text-align: center
}

#progressbar {
    margin-bottom: 30px;
    overflow: hidden;
    color: lightgrey
}

#progressbar .active {
    color: #000000
}

#progressbar li {
    list-style-type: none;
    font-size: 12px;
    width: 33.3%;
    float: left;
    position: relative
}

#progressbar #obj:before {
    font-family: FontAwesome;
    content: "\f192"
}

#progressbar #krs:before {
    font-family: FontAwesome;
    content: "\f201"
}

#progressbar #mile:before {
    font-family: FontAwesome;
    content: "\f0ae"
}

#progressbar #confirm:before {
    font-family: FontAwesome;
    content: "\f00c"
}

#kr-wight-btn:hover {
    background-color: #eee;
}

#kr-wight-checkbox:checked~#kr-wight-btn {
    color: #3f9ce8 !important;
    background-color: #f11;
}

#progressbar li:before {
    width: 50px;
    height: 50px;
    line-height: 45px;
    display: block;
    font-size: 18px;
    color: #ffffff;
    background: lightgray;
    border-radius: 50%;
    margin: 0 auto 10px auto;
    padding: 2px
}

#progressbar li:after {
    content: '';
    width: 100%;
    height: 2px;
    background: lightgray;
    position: absolute;
    left: 0;
    top: 25px;
    z-index: -1
}

#progressbar li.active:before,
#progressbar li.active:after {
    background: skyblue
}

.radio-group {
    position: relative;
    margin-bottom: 25px
}

.radio {
    display: inline-block;
    width: 204;
    height: 104;
    border-radius: 0;
    background: lightblue;
    box-shadow: 0 2px 2px 2px rgba(0, 0, 0, 0.2);
    box-sizing: border-box;
    cursor: pointer;
    margin: 8px 2px
}

.radio:hover {
    box-shadow: 2px 2px 2px 2px rgba(0, 0, 0, 0.3)
}

.radio.selected {
    box-shadow: 1px 1px 2px 2px rgba(0, 0, 0, 0.1)
}

.fit-image {
    width: 100%;
    object-fit: cover
}
</style>
<!-- MultiStep Form -->

<div class="container-fluid" id="grad1">
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
    <div class="row justify-content-center mt-0">
        <div class="col-11 col-sm-10 col-md-9 col-lg-9 text-center p-0 mt-3 mb-2">
            <div class="card px-0 pt-4 pb-0 mt-3 mb-3">
                <h3><strong>Create new objective</strong></h3>
                <p>Fill all form field to go to next step</p>
                <div class="row">
                    <div class="col-md-12 mx-0">
                        <form id="msform" action={{ route('okr.store') }} method="post" novalidate
                            onsubmit="return validateOKR(this)">
                            @csrf
                            <input type="hidden" value="{{$id}}" id="session_id" name="session_id">
                            <!-- kr id used to set keyresult id even we delete the kr in the middle in <fieldset2> -->
                            <input type="hidden" value="" id="kr_id" name="kr_id">
                            <!-- progressbar -->
                            <ul id="progressbar">
                                <li class="active" id="obj"><strong>Objective</strong></li>
                                <li id="krs"><strong>Keyresults</strong></li>
                                <li id="confirm"><strong>Checking</strong></li>
                            </ul>
                            <fieldset>
                                <div class="col-lg-11 addobjbod">
                                    <h6 class="fs-title">Add objective</h6>

                                    <div class="form-group row mb-30 mt-20 ">
                                        <label for="ownervalue" class="col-3 col-sm-3 col-md-2 col-lg-2 col-form-label">
                                            Title</label>
                                        <input type="text" class="form-control round col-9 col-sm-9 col-md-10 col-lg-10"
                                            id="okrobjective_name" name="objective_name"
                                            placeholder="Example: Achieve revenue of 100m birr this quarter"
                                            autocomplete="off" required />
                                        <div id="val-objective-error"
                                            class="invalid-feedback animated fadeInDown  col-9 col-sm-9 col-md-10 col-lg-10">
                                            Objective title is required</div>
                                    </div>
                                    <!-- // TODO implement priority change listner on custom.js -->
                                    <div class="form-group row mb-30 mt-10 ">
                                        <label for="obj-priority"
                                            class="col-3 col-sm-3 col-md-2 col-lg-2 col-form-label"> Priority</label>
                                        <select id='0bj-priority'
                                            class="col-6 col-sm-6 col-md-5 col-lg-5 form-control round "
                                            name="obj-priority">
                                            <option value=3>High</option>
                                            <option selected value=2>Medium</option>
                                            <option value=1>Low</option>
                                        </select>
                                    </div>
                                    <div id='obj-value-container' class="form-group row mb-30 mt-10 d-none">
                                        <label for="obj-value"
                                            class="col-3 col-sm-3 col-md-2 col-lg-2  col-form-label">Value</label>
                                        <input type="number" class="form-control round col-9 col-sm-6 col-md-5 col-lg-5"
                                            id="obj-value" name="obj-value" placeholder="Objective Value"
                                            autocomplete="off" required />
                                    </div>
                                    @php
                                    $class = "";
                                    $val ="";
                                    if(Auth::user()->avatar == null){
                                    $class = "av-circle";
                                    $val = 'data-letters='.Auth::user()->fname[0] . Auth::user()->lname[0];
                                    }
                                    @endphp
                                    <!--Special privilege user-->
                                    @if(Gate::any(['okr', 'assignokr']))
                                    <div class="form-group ownerslist autocomplete row mb-30">
                                        <label for="ownervalue" class="col-3 col-sm-3 col-md-2 col-lg-2 col-form-label">
                                            <i class="fa fa-user"></i> Owner</label>
                                        <div class=" col-5 col-sm-4 col-md-4 col-lg-4  row" id="ownervalue">


                                            <div {{$val}} class="{{$class}} form-control round col-md-11" id="ownerlist"
                                                data-id='{{Auth::user()->id}}' style="background-color:#ececec">
                                                @if(Auth::user()->avatar != null)<img
                                                    src="https://ienetworks.co/pms/uploads/avatars/{{ Auth::user()->avatar }}"
                                                    style="width:20px; height:20px; border-radius:20%; -webkit-border-radius: 50%">
                                                @endif
                                                {{Auth::user()->fname . " " .Auth::user()->lname}}
                                                <a class="float-right col-md-1 "
                                                    onMouseOver="this.style.color='#dc3545'"
                                                    onMouseOut="this.style.color='#000'" id="removeuser"> <i
                                                        class=" fa fa-times-circle "></i></a>
                                            </div>
                                            <div id="val-user-error"
                                                class="invalid-feedback animated fadeInDown  col-9 col-sm-9 col-md-10 col-lg-10">
                                                Owner name is required</div>
                                        </div>

                                        <div id="userdiv"
                                            class="mt-6 ml-7 col-5 col-sm-4 col-md-4 col-lg-4 autocomplete"></div>
                                        <input type="hidden" name="user_id" value="{{Auth::user()->id}}"
                                            id="userinput" />
                                    </div>
                                    <!--ordinary user-->
                                    @else
                                    <div class="form-group ownerslist autocomplete row mb-30">
                                        <label for="ownervalue" class="col-3 col-sm-3 col-md-2 col-lg-2 col-form-label">
                                            <i class="fa fa-user"></i> Owner</label>
                                        <div id="ownervalue">
                                            <div {{$val}} class="{{$class}} pinitals form-control round" id="ownerlist"
                                                data-id='{{Auth::user()->id}}' style="background-color:#ececec">
                                                @if(Auth::user()->avatar != null)<img
                                                    src="https://ienetworks.co/pms/uploads/avatars/{{ Auth::user()->avatar }}"
                                                    style="width:20px; height:20px; border-radius:20%; -webkit-border-radius: 50%">
                                                @endif
                                                {{Auth::user()->fname . " " .Auth::user()->lname}}
                                            </div>
                                        </div>
                                        <input type="hidden" name="user_id" value="{{Auth::user()->id}}" />
                                    </div>
                                    @endif

                                    <div class=" form-group row ">
                                        <label for="launchalign"
                                            class="col-3 col-sm-3 col-md-2 col-lg-2 col-form-label"> </i>
                                            Alignment</label>
                                        <a class="launchalignobj col-7 col-sm-6 col-md-6 col-lg-6" id="launchalignobj"
                                            style="color:#6bb2ed; cursor:pointer"
                                            onMouseOver="this.style.color='#3490dc'"
                                            onMouseOut="this.style.color='#6bb2ed'" data-id="{{Auth::user()->id}}">
                                            + Align this objective with another objective
                                        </a>

                                        <div class='row okralignedobj form-group col-9 col-sm-9 col-md-10 col-lg-10'
                                            hidden data-obj="0" id="okralignedobj"></div>
                                    </div>

                                </div>

                                <input type="button" name="next"
                                    class="next action-button btn btn-rounded btn-outline-info" value="Next Step" />
                            </fieldset>
                            <fieldset>
                                <h6 class="fs-title">Add Keyresults</h6>
                                <div id="keyresultlist">
                                    <div class="block block-bordered  ml-10 mr-10" id="keyresult1">

                                        <div id="krbutton"></div>

                                        <div class="block-content ">
                                            <div class="form-group row mb-5 mt-5 ">
                                                <label class="col-4 col-sm-4 col-md-3 col-lg-3 col-form-label">
                                                    Keyresult </label>
                                            </div>
                                            <div class="form-group row mb-30 mt-20">
                                                <label for="ownervalue"
                                                    class="col-3 col-sm-3 col-md-2 col-lg-2 col-form-label">
                                                    Title</label>
                                                <input type="text" oninput='handleKRinput(this,1)'
                                                    class="keyresult_name form-control round col-9 col-sm-9 col-md-10 col-lg-10"
                                                    id="keyresult_name1" name="keyresult_name1"
                                                    placeholder="Example: Achieve revenue of 100m birr this quarter"
                                                    autocomplete="off" />
                                                <div id="val-kr-error1"
                                                    class="val-kr-error invalid-feedback animated fadeInDown  col-9 col-sm-9 col-md-10 col-lg-10">
                                                    Keyresult title is required</div>
                                            </div>
                                            <div class="form-group row mb-30 mt-20">
                                                <label for="ownervalue"
                                                    class="col-3 col-sm-3 col-md-2 col-lg-2 col-form-label">
                                                    Type</label>
                                                <select
                                                    class="krstatus col-6 col-sm-6 col-md-5 col-lg-5 form-control round "
                                                    id="krstatus1" name="krstatus1"
                                                    onchange="changestatus(this.value,1)">
                                                    <option value=0>Achieved or not</option>
                                                    <option value=2>Shoud increase to</option>
                                                    <option value=3>Shoud decrease to</option>
                                                    <option value=4>Percentage tracked</option>
                                                </select>
                                                <div id="val-target-error1"
                                                    class="val-target-error invalid-feedback animated fadeInDown offset-3 offset-md-2 col-md-offset-2 text-left col-6 col-sm-6 col-md-5 col-lg-5">
                                                    Invalid target value!
                                                </div>
                                            </div>
                                            <div id="krtype1"></div>
                                            <!--<div class="form-group"> -->
                                            <!--    <a type="button" class="text-success mt-5 col-md-4" id="addmilestone1" onclick="addMilestone(1)"><i class="fa fa-plus "></i> Add Milestone</a>-->
                                            <!--</div>-->
                                        </div>
                                    </div>
                                </div>
                                <div class="block-content ">
                                    <div class="form-group  mb-5 mt-5 col-4 col-sm-4 col-md-3 col-lg-3 float-right">
                                        <a class=" btn btn-rounded btn-success" onclick="addkeyresult()" data-count=1
                                            id="keyresultadd">Add Key result</a>
                                        <input type="hidden" id="krcount" name="krcount" value="1" />
                                    </div>
                                </div>
                                <input type="button" name="previous"
                                    class="previous action-button-previous btn btn-rounded" value="Previous" />
                                <input type="button" name="next" class="next action-button btn btn-rounded"
                                    value="Next" />
                            </fieldset>
                            <!--<fieldset>-->
                            <!--    <h6 class="fs-title">Confirm</h6>-->

                            <!--    <input type="button" name="previous" class="previous action-button-previous btn btn-rounded" value="Previous" /> -->
                            <!--     <input type="button" name="next" class="next action-button btn btn-rounded" value="Submit" />-->
                            <!--</fieldset>-->

                            <fieldset>

                                <!-- <div class="block  content pull-left" /> -->
                                <h4 class="fs-title">Addx key result weight</h4>
                                <div class="block block-bordered p-10 pb-1 rounded">
                                    <div id='kr-weight-container'>

                                    </div>
                                    <div class="alert alert-info mt-20">
                                        <h5 class="text-left">Checklist</h5>
                                        <ol type='1' class='ml-20'>
                                            <li class="text-left">Make sure your goal is measurable</li>
                                            <li class="text-left">Make sure you have aligned your objective
                                                under your manager's objective </li>
                                            <li class="text-left">Make sure your Key results are short, precise
                                                and attainable </li>
                                        </ol>
                                    </div>
                                    <div id='objective-cal-error' class="alert alert-danger d-none mt-10">
                                        <h6 class='m-1'>Error, Objective Should be 100% !</h6>
                                    </div>

                                </div>
                                <input type="button" name="previous"
                                    class="previous action-button-previous btn btn-rounded" value="Previous" />
                                <input type="submit" name="next" class="next action-button btn btn-rounded"
                                    value="Submit" />
                            </fieldset>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="okrobjectivelist" role="dialog" aria-hidden="true">
    <div class="modal-dialog" style="width:1250px;">
        <div class="modal-content">
            <div class="block block-themed block-transparent mb-0">
                <div class="block-header bg-info">
                    <h3 class="block-title">Choose Objective</h3>
                    <div class="block-options">
                        <button type="button" class="btn-block-option" data-dismiss="modal" aria-label="Close">
                            <i class="si si-close"></i>
                        </button>
                    </div>
                </div>
            </div>
            <div class="modal-body">
                <div class="input-group">
                    <div class="col-md-7 autocomplete">
                        <input type="text" class="form-control round okralignobjchoice" placeholder="Owner"
                            id="okralignobjchoice" autocomplete="off">
                    </div>
                    <button class="btn btn-circle btn-outline-info okrsearchowner" type="button" id="okrsearchowner">
                        <i class="fa fa-search"></i>
                    </button>
                </div>
                <div class="container my-3">
                    <ul id="okrobjectives_list"></ul>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
let OBJECTIVE_VALUE = 5
$(document).ready(function() {
    let CURRENT_PAGE = 1
    console.log('object value when dom reandering', OBJECTIVE_VALUE)
    //prevent kr form submmiting on pressing enter
    $('#msform').on('keypress', function(e) {
        return e.which !== 13;
    });
    var current_fs, next_fs, previous_fs; //fieldsets
    var opacity;
    $(".next").click(function() {
        console.log("next : CURRENT_PAGE ", CURRENT_PAGE)
        var objname = document.getElementById('okrobjective_name')
        if (objname.value.trim() == "") {
            objname.classList.add('is-invalid');
        } else if (document.getElementById("assignuser")) {
            document.getElementById("ownerlist").classList.add('is-invalid');
        } else if (!isValidKR($(this).parent())) {
            console.log('kr errror')
        } else {
            current_fs = $(this).parent();
            next_fs = $(this).parent().next();
            previous_fs = $(this).parent().prev();
            //Add Class Active
            $("#progressbar li").eq($("fieldset").index(next_fs)).addClass("active");
            //show the next fieldset
            if (next_fs.length != 0) {
                CURRENT_PAGE++
                if (CURRENT_PAGE == 3) {
                    console.count('kr weight rendering')
                    $('#kr-weight-container').html(krWeightlayout())
                }
                next_fs.show();
                current_fs.animate({
                    opacity: 0
                }, {
                    step: function(now) {
                        // for making fielset appear animation
                        opacity = 1 - now;

                        current_fs.css({
                            'display': 'none',
                            'position': 'relative'
                        });
                        next_fs.css({
                            'opacity': opacity
                        });
                    },
                    duration: 600
                });
            } else {
                console.log('final stage view: ', next_fs.length)
            }
            //hide the current fieldset with style

        }

    });

    $(".previous").click(function() {
        CURRENT_PAGE--
        current_fs = $(this).parent();
        previous_fs = $(this).parent().prev();
        $("#progressbar li").eq($("fieldset").index(current_fs)).removeClass("active");

        //show the previous fieldset
        previous_fs.show();
        console.log(current_fs, 'current fs previous btn click');
        //hide the current fieldset with style
        current_fs.animate({
            opacity: 0
        }, {
            step: function(now) {
                // for making fielset appear animation
                opacity = 1 - now;

                current_fs.css({
                    'display': 'none',
                    'position': 'relative'
                });
                previous_fs.css({
                    'opacity': opacity
                });
            },
            duration: 600
        });
    });

    $('.radio-group .radio').click(function() {
        $(this).parent().find('.radio').removeClass('selected');
        $(this).addClass('selected');
    });

});


function krWeightlayout() {
    const ids = getKrIdIndex();
    const allkrWeight = _calculateDefautKRWeight()
    const objectValue = $("#obj-value").val() ? $("#obj-value").val() : 1;
    const objectName = $("#okrobjective_name").val();
    const krPercentages = allkrWeight.map(weight => ((weight / objectValue) * 100.0).toFixed(1))
    const objPercentage = ((allkrWeight.reduce((ac, w) => w + ac, 0) / objectValue) * 100).toFixed(1)


    // console.log('allkrWeight',allkrWeight,'krCount',krCount)
    let view =
        `<div class='form-group d-flex my-0  p-5 flex-row align-items-center flex-center'>
            <h5 class='text-left my-1 col-7'>${objectName}</h5>
            <div class="progress col-4 progress-bar-striped p-0">
              <div class="progress-bar" id='obj-progress' role="progressbar" style="width:${objPercentage}%;" aria-valuenow="${objPercentage}" aria-valuemin="0" aria-valuemax="100">${objPercentage}%</div>
            </div>
            <label id='kr-wight-btn' class="btn rounded-circle p-2 ml-2">
            <input id='kr-wight-checkbox' type="checkbox" onchange="handleCustomWightCheckbox(this.checked)" style='width:0; height:0;opacity:0'/>
      <svg xmlns="http://www.w3.org/2000/svg" version="1.0" width="30.000000px" height="30.000000px"
        viewBox="0 0 1280.000000 1264.000000" preserveAspectRatio="xMidYMid meet">
        <g transform="translate(0.000000,1264.000000) scale(0.100000,-0.100000)" fill="currentColor" stroke="none"
          class="__web-inspector-hide-shortcut__">
          <path
            d="M6305 12342 c-22 -11 -49 -29 -60 -42 -20 -22 -20 -38 -23 -780 -2 -584 -5 -760 -15 -768 -6 -5 -43 -22 -82 -37 -111 -44 -264 -123 -343 -178 -133 -92 -239 -234 -369 -494 -41 -83 -83 -155 -92 -160 -9 -4 -605 -10 -1326 -13 -720 -3 -1318 -9 -1328 -14 -10 -4 -30 -29 -45 -55 -22 -38 -27 -58 -26 -111 1 -88 9 -114 39 -128 19 -9 363 -12 1365 -12 l1339 0 11 -42 c18 -72 51 -160 87 -233 120 -240 361 -437 698 -570 l80 -31 3 -357 2 -357 -130 0 -130 0 0 -2869 0 -2868 38 -7 c20 -3 222 -6 448 -6 366 0 413 2 418 16 8 21 8 5662 0 5703 l-6 31 -154 0 -154 0 2 351 3 351 87 33 c199 77 390 188 490 283 103 98 208 278 274 467 15 44 32 86 37 93 7 9 291 12 1356 12 1496 0 1380 -6 1401 68 15 56 12 132 -9 177 -34 76 94 69 -1392 75 -1173 4 -1340 7 -1352 20 -7 8 -24 42 -37 75 -37 96 -116 260 -156 327 -113 185 -260 298 -529 403 -66 26 -137 54 -157 63 l-38 16 0 776 0 777 -27 12 c-50 23 -153 24 -198 3z m-85 -2162 l0 -310 -267 0 c-148 0 -284 3 -304 6 l-35 6 8 71 c16 133 90 256 215 357 91 74 184 126 276 156 119 38 107 69 107 -286z m471 294 c192 -57 383 -221 473 -407 32 -65 60 -179 48 -192 -8 -7 -579 -6 -624 1 l-38 7 0 303 0 304 43 0 c24 0 68 -7 98 -16z m-471 -1259 l0 -324 -32 5 c-126 23 -223 72 -322 165 -130 121 -227 285 -244 412 l-9 67 304 0 303 0 0 -325z m990 285 c0 -21 -10 -67 -23 -102 -80 -221 -384 -478 -584 -496 l-53 -4 0 321 0 321 330 0 330 0 0 -40z m-695 -1562 c3 -13 4 -722 3 -1576 l-3 -1554 -24 -19 c-37 -30 -164 -25 -201 8 -3 3 -7 715 -10 1584 l-5 1579 117 0 c114 0 118 -1 123 -22z" />
          <path
            d="M2626 9118 c-7 -18 -253 -555 -546 -1193 -1147 -2499 -1414 -3082 -1436 -3133 l-24 -52 181 2 181 3 264 565 c146 311 520 1111 831 1778 312 666 572 1212 578 1212 15 0 -26 85 850 -1800 430 -927 791 -1702 801 -1722 l18 -38 179 0 c139 0 178 3 174 13 -3 6 -161 354 -352 772 -191 418 -637 1396 -991 2172 -354 777 -649 1422 -656 1433 -18 29 -36 25 -52 -12z" />
          <path
            d="M10146 9123 c-28 -56 -2009 -4349 -2013 -4365 -5 -17 8 -18 179 -18 l184 0 106 228 c95 204 921 1971 1377 2945 98 208 182 379 187 379 5 0 380 -799 834 -1776 l825 -1776 173 0 172 0 0 38 c0 29 -61 171 -246 573 -1754 3811 -1748 3799 -1759 3799 -2 0 -11 -12 -19 -27z" />
          <path
            d="M365 4513 c-23 -22 -53 -95 -61 -143 -9 -55 11 -103 60 -148 36 -33 53 -41 97 -47 30 -3 1035 -5 2234 -3 l2180 3 37 23 c20 12 51 44 68 70 29 42 31 53 27 103 -5 59 -25 107 -58 140 -19 19 -53 19 -2293 19 -2121 0 -2276 -1 -2291 -17z" />
          <path
            d="M7874 4522 c-23 -15 -35 -55 -40 -135 -5 -73 -3 -87 18 -126 13 -25 36 -54 51 -65 l28 -21 2217 0 c2508 0 2251 -9 2312 85 46 72 51 134 16 194 -14 25 -36 52 -48 61 -20 13 -260 15 -2283 15 -1243 0 -2265 -4 -2271 -8z" />
          <path
            d="M635 3943 c8 -46 67 -174 134 -291 27 -48 52 -98 56 -112 9 -30 100 -172 164 -255 301 -393 773 -646 1376 -736 59 -9 176 -14 345 -13 240 1 262 3 375 29 535 121 983 414 1317 859 137 182 268 443 268 533 l0 23 -2021 0 -2021 0 7 -37z" />
          <path
            d="M8110 3976 c0 -20 76 -229 108 -299 107 -231 311 -476 552 -664 249 -194 593 -350 920 -417 182 -37 276 -46 545 -53 231 -5 245 -5 370 20 418 84 763 274 1071 589 166 170 282 330 386 534 59 115 118 254 118 279 0 13 -249 15 -2035 15 -1119 0 -2035 -2 -2035 -4z" />
          <path
            d="M5608 2041 c-93 -19 -168 -124 -168 -238 0 -71 66 -177 127 -202 21 -8 252 -11 850 -9 l822 3 22 21 c23 24 59 139 59 191 0 18 -10 62 -21 97 -25 76 -69 123 -125 137 -46 10 -1512 10 -1566 0z" />
          <path
            d="M4624 1393 c-68 -13 -114 -171 -83 -288 15 -59 28 -81 56 -94 17 -8 516 -11 1786 -11 2007 0 1809 -10 1860 90 23 45 27 65 24 112 -4 63 -42 136 -90 172 l-28 21 -1752 1 c-964 1 -1762 -1 -1773 -3z" />
          <path
            d="M3581 790 c-18 -4 -52 -21 -74 -36 -135 -95 -113 -364 32 -404 23 -6 1029 -10 2854 -10 3161 0 2871 -8 2909 77 17 39 22 68 22 148 0 114 -21 180 -70 212 -27 17 -131 18 -2834 20 -1543 0 -2820 -3 -2839 -7z" />
        </g>
      </svg>
    </label>
        </div>
        <hr class="my-2">`;
    const element = (krName, weight, count, progress) =>
        `<div class='form-group d-flex p-5 mb-0 mx-2 flex-row align-items-center flex-center'>
            <label for="kr_weight${count}" class=" text-left col-7 col-form-label flex-stretch">
                ${krName}
            </label>
           <div id='kr-progress-container${count}' class="progress col-4 p-0 h-30">
              <div class="progress-bar progress-bar-striped" id='kr-progress${count}' role="progressbar" style="width:${progress}%;" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100">${progress}%</div>
            </div>
            <input type="number" oninput='updateKRWightView()' step='0.01' name='kr_weight${count}' value='${weight}' class="d-none col-2 ml-10 text-center form-control round" id="kr_weight${count}" min="0.01" name="krw" placeholder="weight" autocomplete="off" />
        </div>`;

    for (count = 0; count < ids.length; count++) {
        const krName = $(`#keyresult_name${ids[count]}`).val();
        const krType = $(`#krstatus`).val();
        console.log('*** Printing krElements ***', 'krName', krName, 'weight : ', allkrWeight[count],
            'percentage',
            krPercentages[count])
        const weight =
            view += element(krName, allkrWeight[count], ids[count], krPercentages[count]);
    }
    return `<div id='eeeee'>${view}</div>`;
}

const _krData = (i) => {
    const kr = document.getElementById(`keyresult_name${i}`);
    const krType = document.getElementById(`krstatus${i}`);
    const krTypeError = document.getElementById(`val-target-error${i}`);
    const initialv = document.getElementById(`initialv${i}`);
    const targetv = document.getElementById(`targetv${i}`);
    const initialPercentage = document.getElementById(`percentageKr-initalValue${i}`);
    return {
        kr: kr,
        krTypeError,
        krv: kr ? kr.value : '',
        krTypev: krType ? krType.value : '',
        krType: krType,
        initialv: initialv ? parseFloat(initialv.value) : 0,
        targetv: targetv ? parseFloat(targetv.value) : 0,
        initialPercentagev: initialPercentage ? parseFloat(initialPercentage.value) : 0
    }
}
const getKrIdIndex = () => {
    let IDs = []
    const d = document.getElementsByClassName("keyresult_name")
    for (let i = 0; i < d.length; i++) {
        id = d[i].id.toString().match(/[0-9]/)[0]
        if (id) IDs.push(id)
    }
    $('#kr_id').val(IDs.toString())
    return IDs
}

function isValidKR(parrent) {
    const fieldset = parrent[0].innerText
    // ignore the key result validation in "create Objective" page (fieldset 1) 
    if (fieldset.toLowerCase().includes('objective')) return true
    const krCount = document.getElementById("krcount");
    const ids = getKrIdIndex()
    console.log('avaliable kr list by class', ids)
    for (i = 0; i < ids.length; i++) {
        const {
            kr,
            krv,
            krTypev,
            krType,
            krTypeError,
            initialv,
            targetv,
            initialPercentagev
        } = _krData(ids[i])

        const difference = targetv - initialv;
        const inValidKr = krv.trim().length < 5; // key result title should be greterthan 5 characters
        const inValidShouldIncrease = krTypev == 2 && !(difference > 0)
        const inValidShouldDecrease = krTypev == 3 && !(difference < 0)
        const inValidInitialPercentage = krTypev == 4 && !(Math.abs(initialPercentagev) < 100)
        const removeErrorClass = (el) => el ? el.classList.remove('is-invalid') : null
        const addErrorClass = (el) => el ? el.classList.add('is-invalid') : null

        if (inValidKr || inValidShouldIncrease || inValidShouldDecrease || inValidInitialPercentage) {
            console.log('krError :', i, "inValidKr", inValidKr, "inValidShouldIncrease", inValidShouldIncrease,
                "inValidShouldDecrease", inValidShouldDecrease, "inValidInitialPercentage",
                inValidInitialPercentage, _krData(i));
            let errorMsg = 'Invalid value,'
            if (inValidKr)
                addErrorClass(kr);
            else {
                removeErrorClass(kr);
                addErrorClass(krType);
            }
            // show error message
            if (inValidShouldIncrease)
                errorMsg = 'Target value must be greater than Initial value.'
            else if (inValidShouldDecrease)
                errorMsg = 'Initial value must be greater than Target value.'
            else if (inValidInitialPercentage)
                errorMsg = 'Initial value must be less than 100%'
            if (krTypeError) krTypeError.innerText = (errorMsg);
            return false;
        } else {
            removeErrorClass(kr);
            removeErrorClass(krType);
        }

    }
    return true
}

const _getAllKrWeight = () => {
    let KRweights = []
    for (count = 0; count < getKrIdIndex().length; count++) {
        let x = document.getElementById(`kr_weight${getKrIdIndex()[count]}`);
        console.log('element', x, );
        KRweights.push(parseFloat(x ? x.value : 0))
    }
    return KRweights;
}

const _calculateDefautKRWeight = () => {
    console.log("_calculateDefautKRWeight ", OBJECTIVE_VALUE);
    const ids = getKrIdIndex()
    let KRweights = []
    for (count = 0; count < ids.length; count++) {
        const krtype = $(`#krstatus${ids[count]}`).val();
        const targetv = $(`#targetv${ids[count]}`).val();
        const initialv = $(`#initialv${ids[count]}`).val();
        //const objectValue = $("#obj-value").val();
        const difference = targetv - initialv;
        if ((!(krtype == 2 || krtype == 3)) || OBJECTIVE_VALUE == 1) {
            const x = new Array(parseInt(ids.length)).fill(1 / ids.length, 0, parseInt(ids.length))
            $("#obj-value").val(1);
            return x
        } else KRweights.push(Math.abs(difference))
    }
    console.log("objective value changed to previous value", OBJECTIVE_VALUE);
    $("#obj-value").val(OBJECTIVE_VALUE);
    return KRweights
}




function _calculateKRWeightUpdates() {
    const objectValue = $("#obj-value").val() ? $("#obj-value").val() : 1;
    const weights = _getAllKrWeight();
    const krWeightSum = weights.reduce((kr, acumulator) => kr - -acumulator, 0)
    const krPercentages = weights.map(kr => (kr / objectValue) * 100)
    const objPercentage = (krWeightSum / objectValue) * 100
    return {
        objPercentage,
        krPercentages
    }
}

function validateOKR(e) {
    const {
        objPercentage,
        krPercentages
    } = _calculateKRWeightUpdates()
    if (objPercentage !== 100) {
        $('#objective-cal-error').removeClass('d-none')
        return false;
    } else
        $('#objective-cal-error').addClass('d-none')
    return true;
}

function updateKRWightView() {
    // debugger
    const ids = getKrIdIndex()
    const {
        objPercentage,
        krPercentages
    } = _calculateKRWeightUpdates()

    const obP = document.getElementById('obj-progress')
    obP.style.width = `${objPercentage}%`
    obP.innerText = `${objPercentage.toFixed(1)}%`

    for (i = 0; i < krPercentages.length; i++) {
        let krP = document.getElementById('kr-progress' + ids[i])
        krP.style.width = `${krPercentages[i]}%`
        krP.innerText = `${krPercentages[i].toFixed(1)}%`
    }
}
const handleCustomWightCheckbox = (checked) => {
    const ids = getKrIdIndex()
    for (i = 0; i < ids.length; i++) {
        const input = document.getElementById(`kr_weight${ids[i]}`)
        const progress = document.getElementById(`kr-progress-container${ids[i]}`)
        console.log('jhhhhhhhhhhhh', checked)
        if (checked) {
            input.classList.remove('d-none')
            progress.classList.add('col-3')
            progress.classList.remove('col-4')
        } else {
            //reset weight to default value
            $('#kr-weight-container').html(krWeightlayout())
            input.classList.add('d-none')
            progress.classList.add('col-4')
            progress.classList.remove('col-3')
        }

    }
}
</script>
@endsection
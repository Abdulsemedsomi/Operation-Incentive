{{-- edit objective  --}}
<div class="modal right fade" id="editobjective{{$objective->id}}"  role="dialog"  aria-hidden="true">
    <div class="modal-dialog modal-dialog-slideout modal-lg" role="document">
        <div class="modal-content">
            <form class="eobjectivemodalFormData" id="eobjectivemodalFormData{{$objective->id}}" action = "{{ route('objectives.update', $objective->id) }}"   method="post" data-id="{{$objective->id}}">
                @csrf
                <input type="hidden" name="_method" value="put" />
                <div class="modal-body">
                    <div class="col-lg-11 editobjbod">
                        <div class="modal-top">
                            <h2>Edit objective</h2>
                        </div>
                        <hr style="text-align:center;margin-left:0;">
                        <div class="form-group">
                            <label for="objective" >Objective</label>
                            <input type = "text" class="form-control round" name="objective_name" aria-describedby="objective" required value="{{$objective->objective_name}}">
                        </div>
                        @if(Gate::any(['okr', 'assignokr']))
                            <div class="form-group ownerslist autocomplete" >
                                <label for="objective"> <i class="fa fa-user"></i> Owner</label>
                                <input type="text" class="form-control round"  placeholder = "Owner" autocomplete="off" required value="{{$objective->fname . " ". $objective->lname}}" id="ownerlist{{$objective->id}}">
                                <input type="hidden" class="form-control round" id="idholder{{$objective->id}}"  autocomplete="off"  required name="user_id" value="{{$objective->uid}}">

                            </div>
                        @else
                            <div class="form-group " >
                                <label for="objective"> <i class="fa fa-user"></i> Owner</label>
                                <p data-letters= "{{$objective->fname[0] . $objective->lname[0]}}" class="objvalue">
                                    {{$objective->fname . " " .$objective->lname}}
                                </p>
                            </div>
                        @endif
                        <div class="modal-top my-2">
                            <h5>Alignment</h5>
                        </div>
                        <hr style="text-align:center;margin-left:0">
                        <div id="alignmentobj{{$objective->id}}">
                            <div class="alignlink" {{$objective->aligned_to == null?"":"hidden"}} id="alignlink{{$objective->id}}">
                                <a href="#"  id="launchalign{{$objective->id}}" data-id={{Auth::user()->id}}  >
                                    + Align this objective with another objective
                                </a>
                            </div>
                            <div class="row alignedobj"   data-obj ="0" id="alignedobj{{$objective->id}}" data-id="{{$objective->id}}" {{$objective->aligned_to != null?"":"hidden"}}>
                                <li class=" row col-md-10"  id="okrchosen{{$objective->id}}">
                                    <p data-letters= "{{App\Objective::find($objective->aligned_to)?(App\User::find(App\Objective::find($objective->aligned_to)->user_id)? App\User::find(App\Objective::find($objective->aligned_to)->user_id)->fname[0] . App\User::find(App\Objective::find($objective->aligned_to)->user_id)->lname[0]: ""):"" }}" class="objvalue">
                                        {{App\Objective::find($objective->aligned_to)? App\Objective::find($objective->aligned_to)->objective_name:"" }}
                                    </p>
                                </li><div class="col-md-2 text-right"> <span id="removealign{{$objective->id}}"><i class="fa fa-remove " id="remove-alig{{$objective->id}}" style="font-size:22px; color:red"></i></span>  </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit"  class="btn btn-rounded btn-outline-info" >Done</button>
                    <button type="button " class="btn btn-rounded btn-outline-secondary" data-dismiss="modal">Close</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- display objective details --}}
<div class="modal right fade" id="objectivedetails{{$objective->id}}"  role="dialog"  aria-hidden="true">
    <div class="modal-dialog modal-dialog-slideout modal-lg" role="document">
        <div class="modal-content">
            <form id="dobjectivemodalFormData{{$objective->id}}" name="obj-form"  method="">
                <div class="modal-body">
                    <div class="container">
                        <div class="col-lg-11">
                            <div class="block-options">

                            </div>
                            <div class="modal-top">
                                <h2> {{$objective->objective_name}}</h2>
                            </div>
                            <div class="form-group row" >
                                <p data-letters= "{{$objective->fname[0] . $objective->lname[0]}}" class="objvalue col-md-10">
                                    {{$objective->fname . " " .$objective->lname}}
                                </p>
                                <p class="col-md-2 float-right objattainment" id="objattainment">{{round($objective->attainment, 2)}}% </p>
                            </div>
                            <div class="modal-top my-2">
                                <h5>Alignment</h5>
                            </div>
                            <hr style="border-top: 5px solid #ccc">
                            @if($objective->aligned_to != null)
                            <div class="row mb-5 col-md-12"   data-obj ="0" id="dalignedobj{{$objective->id}}" data-id="{{$objective->id}}" {{$objective->aligned_to != null?"":"hidden"}}>
                                <li class=" row col-md-10"  id="dokrchosen{{$objective->id}}">
                                    <p data-letters= "{{App\Objective::find($objective->aligned_to)?(App\User::find(App\Objective::find($objective->aligned_to)->user_id)? App\User::find(App\Objective::find($objective->aligned_to)->user_id)->fname[0] . App\User::find(App\Objective::find($objective->aligned_to)->user_id)->lname[0]: ""):"" }}" class="objvalue">
                                        {{App\Objective::find($objective->aligned_to)? App\Objective::find($objective->aligned_to)->objective_name:"" }}
                                    </p>
                                </li>
                            </div>
                            @else
                                <div class="alignlink col-md-8">No Aligned Objective</div>
                            @endif
                            <div class="modal-top my-2">
                                 @php
                                  $keyresults = App\Keyresult::where('objective_id',$objective->id)->get();
                                  $count = 1;
                                @endphp
                                <h5 >Key results</h5>
                                @if((Gate::any(['okr', 'assignokr']) || $objective->uid == Auth::user()->id) && $keyresults->count() < 6 )
                                    <a class="addkeyresult" href="#" class="float-right" data-id="{{$objective->id}}" data-toggle="modal" data-target="#addkrmodal">+ Add key result </a>

                                @endif

                            </div>
                            <hr style="border-top: 5px solid #ccc">
                            <div class=col-md-11>
                                <table  style="width:100%">
                                    <tbody  class="keyresultlist{{$objective->id}}" >
                               

                                @foreach($keyresults as $kr)
                                    <tr id="krls{{$kr->id}}">
                                        <td class = "row" >
                                        <div class="col-md-10">
                                            <span style="margin-left: 30px; margin-bottom: 20px; color:#5F9EA0" class="Check-ins"></span>
                                            <b data-letters= "{{$objective->fname[0] . $objective->lname[0]}}" class="objvalue">{{$kr->keyresult_name}}</b>
                                        </div>
                                        <div class="col-md-1" id="krattainment{{$kr->id}}">{{round($kr->attainment * 100, 2) }}%</div>
                                        @if(Gate::any(['okr', 'assignokr']) || $objective->uid == Auth::user()->id)
                                            <div class="col-md-1 pull-right"><a href="#" class="neonav-link  caret-off" data-toggle="dropdown" id="navbarDropdown{{$kr->id}}"  data-toggle="dropdown" ><i class="fa fa-ellipsis-v"></i></a><div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown{{$kr->id}}">
                                                @if($kr->keyresult_type ==1)<li><a id="updatekr{{$kr->id}}" class="dropdown-item updatekeyresult" data-id= '{{$kr->id}}'>Update</a></li> @endif
                                                <li><a  id ="editkr{{$kr->id}}" class="dropdown-item editkr" data-id= '{{$kr->id}}' >Edit</a></li>
                                                <li><a id="deletekr{{$kr->id}}" class="dropdown-item deletekr" data-id= '{{$kr->id}}'>Delete</a></li></div>
                                            </div>
                                        @endif

                                    {{-- @if($keyresults->count() > $count++ )<hr  id ="hrt{{$kr->id}}" style="text-align:center;margin-left:0"> @endif --}}
                                        </td>
                                    </tr>
                                @endforeach
                                    </tbody>
                                 </table>
                            </div>
                        </div>
                    </div>
                </div>

            </form>
        </div>
    </div>
</div>
<!-- delete objective modal -->
<div class="modal fade" id="deleteobjective{{$objective->id}}" aria-hidden="true">
    <div class="modal-dialog">
        <form id="deleteobj{{$objective->id}}" action = "{{ route('objectives.destroy', $objective->id) }}"   method="post">
            @csrf
            <input type="hidden" name="_method" value="delete" />
            <div class="block block-themed block-transparent mb-0">
                <div class="block-header bg-danger">
                    <h3 class="block-title">Delete objective</h3>
                    <div class="block-options">
                        <button type="button" class="btn-block-option" data-dismiss="modal" aria-label="Close">
                            <i class="si si-close"></i>
                        </button>
                    </div>
                </div>
                <div class="modal-content">
                    <div class="modal-body">
                        <label for="inputLink" class=" control-label col-md-push-1" >Are you sure you want to delete the objective: {{$objective->objective_name}}</label>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-rounded btn-outline-danger" value="">Delete</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
{{-- Add alignment --}}
<div class="modal fade objectivelist" id="objectivelist{{$objective->id}}" role="dialog"  aria-hidden="true" >
    <div class="modal-dialog modal-lg">
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
                        <input type="text" class = "form-control round alignobjchoice" placeholder="Owner" id="alignobjchoice{{$objective->id}}" autocomplete="off">
                    </div>
                    <button class="btn btn-circle btn-outline-info searchowner" type="button" id="searchowner{{$objective->id}}">
                        <i class="fa fa-search"></i>
                    </button>
                </div>
                <div class="container my-3">
                    <ul id="objectives_list{{$objective->id}}"></ul>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function($) {
    var objid = {!! $objective->id !!};
    var baseurl = window.location.protocol + "//" + window.location.host + '/pms/';
        var element =   document.getElementById("ownerlist"+ objid);
       if($('#editobjective' + objid).hasClass('in')){
 
     if (typeof(element) != 'undefined' && element != null)
        {
            $.get(baseurl + "allusers", function(data) {
                var users_data = [];
                var id_data = [];
                for (var i = 0; i < data.length; i++) {
                    users_data[i] = data[i].fname + " " + data[i].lname;
                    id_data[i] = data[i].id;
                }
                autocomplete(
                    document.getElementById("ownerlist"+ objid),
                    users_data,
                    id_data
                );
            });
        }
        
       }
    jQuery("body").on("click", "#launchalign"+objid, function() {
        var id = $(this).data("id");
        if ($("#eownerlist"+objid).val()) {
            id = document.getElementById("eownerlist"+objid).dataset.id;
        }

        console.log(id);
        $.get(baseurl + "objectivebymanager/" + id, function(data) {
            link = "";
            if (data == "no") {
                link = "<li style='list-style-type:none'>No results</li>";
            } else {
                for (var i = 0; i < data.length; i++) {
                    link +=
                        '<li class="aclickable-row row" value=' +
                        data[i].id +
                        ' id="okrchoose'+objid+'">';
                    link +=
                        "<p data-letters=" +
                        data[i].fname[0] +
                        data[i].lname[0] +
                        '  data-toggle="tooltip"  data-placement="top" title=' +
                        data[i].fname +
                        " " +
                        data[i].lname +
                        "> " +
                        data[i].objective_name +
                        "</p> </li>";
                }
            }
            $("#objectives_list").html(link);
        });
        jQuery("#objectivelist"+objid).modal("show");
    
    $.get(baseurl + "allusers", function(data) {
                var users_data = [];
                var id_data = [];
                for (var i = 0; i < data.length; i++) {
                    users_data[i] = data[i].fname + " " + data[i].lname;
                    id_data[i] = data[i].id;
                }
      autocomplete(
                    document.getElementById("alignobjchoice" + objid),
                    users_data,
                    id_data,
                    document.getElementById("idholder" + objid),
                );
            });
    });
    
    jQuery("body").on("click", "#searchowner"+objid, function() {
        var user_id = document.getElementById("alignobjchoice" + objid ).dataset.id;
        var user_name = document.getElementById("alignobjchoice" + objid).value;
        $.ajax({
            url: baseurl + "eobjectivebyuser",
            type: "get", //send it through get method
            data: {
                user_id: user_id,
                objective_id: objid
            },
            success: function(data) {
                //Do Something
                link = "";
            if (data == "no") {
                link = "<li style='list-style-type:none'>No results</li>";
            } else {
                for (var i = 0; i < data.length; i++) {
                    link +=
                        '<li class="aclickable-row row" value=' +
                        data[i].id +
                        ' id="okrchoose'+objid+'">';
                    link +=
                        "<p data-letters=" +
                        data[i].fname[0] +
                        data[i].lname[0] +
                        '  data-toggle="tooltip"  data-placement="top" title=' +
                        data[i].fname +
                        " " +
                        data[i].lname +
                        "> " +
                        data[i].objective_name +
                        "</p> </li>";
                }
            }

            $("#objectives_list"+objid).html(link);
  },
  error: function(xhr) {
    //Do Something to handle error
  }
    });
});
    jQuery("body").on("click", "#okrchoose" +objid, function() {
        $.get(baseurl + "objectives/" + $(this).val(), function(data) {
            var name = data[0].fname[0] + data[0].lname[0];
            var alignedto =
                '<li class=" row col-md-10"  id="okrchosen'+objid+'"><p data-letters="' +
                name +
                '" id="objvalue" class="objvalue">' +
                data[0].objective_name +
                '</p></li><div class="col-md-2 text-right"><span id="removealign'+objid+'"><i class="fa fa-remove " id="remove-alig'+objid+'" style="font-size:22px; color:red"></i></span></div><input type="hidden" name="aligned_to" value="' + data[0].id + '" />';
            $(".alignedobj").html(alignedto);
            // autocomplete(document.getElementById("myInput"), userarr);
        });

        jQuery("#objectivelist" + objid).modal("hide");

        document.getElementById("alignobjchoice" +objid).value="";
        document.getElementById("objectives_list" +objid).innerHTML="";

        $("#alignlink"+ objid).prop("hidden", true);
        $("#alignedobj"+ objid).removeAttr("hidden");
        $("#okrchosen" + objid).val($(this).val());
        $("#alignedobj"+ objid).attr("data-obj", $(this).val());
    });

    jQuery("body").on("click", "#removealign" + objid, function() {
        $("#alignedobj"+ objid).html("");
        $("#alignlink"+ objid).removeAttr("hidden");
        $("#alignedobj"+ objid).attr("data-obj", "0");
    });

//     $("#eobjectivemodalFormData" + objid).submit(function(e) {
//         e.preventDefault();
//         if(document.getElementById("ownerlist" + objid)){
//         var ownerlist = document.getElementById("ownerlist" + objid).value
//         console.log(ownerlist)
//         if (/\s/g.test(ownerlist.trim())) {
//             $.get(baseurl + "checkuser/" + ownerlist, function(data) {
//                 console.log(data);
//                 if (data == 0) {
//                     var message =
//                         '<div class="alert alert-danger alert-dismissible fade show "><strong>Error!</strong> User doesn\'t exist<button type="button" class="close" data-dismiss="alert"> &times;</button><div class="alert alert-danger alert-dismissible fade show eerrorM" hidden></div>';
//                     //document.getElementById("errorM").innerHTML = message;
//                     $(".editobjbod").append(message);
//                 } else {
//                     document.getElementById("idholder" + objid).value = data.id
//                     document.getElementById("eobjectivemodalFormData" + objid).submit();
//                 }
//             });
//         } else {
//             var message =
//                 '<div class="alert alert-danger alert-dismissible fade show "><strong>Error!</strong> User doesn\'t exist<button type="button" class="close" data-dismiss="alert"> &times;</button><div class="alert alert-danger alert-dismissible fade show eerrorM" hidden></div>';
//             //document.getElementById("errorM").innerHTML = message;
//             $(".editobjbod").append(message);
//         }
//     }
//     else{
//         document.getElementById("eobjectivemodalFormData" + objid).submit();
//     }
//     });


 });

</script>

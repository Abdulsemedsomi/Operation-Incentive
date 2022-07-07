$(document).ready(function($) {
    var baseurl = window.location.protocol + "//" + window.location.host + '/pms/';
    jQuery("body").on("click", ".clickable-field", function() {
        window.location = $(this).data("href");
    });

     

    //launch session modal
    jQuery("body").on("click", "#addSession", function() {
        jQuery(".addSessionmodal").modal("show");
    });

    //add session
    $("#smodalFormData").submit(function(e) {
        var start = $("#startdate").val();
        var end = $("#enddate").val();
        e.preventDefault();

        if (start > end) {
            var message =
                '<div class="alert alert-danger alert-dismissible fade show "><strong>Error!</strong> Start date cannot be greater than end date<button type="button" class="close" data-dismiss="alert"> &times;</button><div class="alert alert-danger alert-dismissible fade show eerrorM" hidden></div>';
            //document.getElementById("errorM").innerHTML = message;
            $("#addbod").append(message);
            // console.log(start.getMonth() + 3);
        } else {
            if (
                $("#statusselect")
                .children("option:selected")
                .val() == "Active"
            ) {
                $.get(baseurl + "activesessions", function(data) {
                    console.log(data);
                    if (data > 0) {
                        var message =
                            '<div class="alert alert-danger alert-dismissible fade show "><strong>Error!</strong> There cannot be more than one active session<button type="button" class="close" data-dismiss="alert"> &times;</button><div class="alert alert-danger alert-dismissible fade show eerrorM" hidden></div>';
                        //document.getElementById("errorM").innerHTML = message;
                        $("#addbod").append(message);
                    } else {
                       document.getElementById('smodalFormData').submit();
                    }
                });
            } else {
                document.getElementById('smodalFormData').submit();
            }
        }
    });

    //edit session
    $(".editsmodalFormData").submit(function(e) {
        var id = $(this).data('id');
        console.log(id);
        var start = $("#estartdate" + id).val();
        var end = $("#eenddate" + id).val();
        e.preventDefault();

        if (start > end) {
            var mess =
                '<div class="alert alert-danger alert-dismissible fade show "><strong>Error!</strong> Start date cannot be greater than end date<button type="button" class="close" data-dismiss="alert"> &times;</button><div class="alert alert-danger alert-dismissible fade show eerrorM" hidden></div>';
            //document.getElementById("errorM").innerHTML = message;
            $(".editbod").append(mess);

            // console.log(start.getMonth() + 3);
        } else {
            if (
                $("#estatus" + id)
                .children("option:selected")
                .val() == "Active"
            ) {
                $.get(baseurl + "activesessions", function(data) {
                    console.log(data);
                    if (data > 0) {
                        var message =
                            '<div class="alert alert-danger alert-dismissible fade show "><strong>Error!</strong> There cannot be more than one active session<button type="button" class="close" data-dismiss="alert"> &times;</button><div class="alert alert-danger alert-dismissible fade show eerrorM" hidden></div>';
                        //document.getElementById("errorM").innerHTML = message;
                        $(".editbod").append(message);
                    } else {
                        document.getElementById('editsmodalFormData' + id).submit();
                    }
                });
            } else {
                document.getElementById('editsmodalFormData' + id).submit();
            }
        }
    });
    // add objective
    // $("#objectivemodalFormData").submit(function(e) {
    //     e.preventDefault();
    //     if (document.getElementById("ownerlist")) {
    //         var ownerlist = document.getElementById("ownerlist").value

    //         if (/\s/g.test(ownerlist.trim())) {
    //             $.get(baseurl + "checkuser/" + ownerlist, function(data) {
    //                 console.log(data);
    //                 if (data == 0) {
    //                     var message =
    //                         '<div class="alert alert-danger alert-dismissible fade show "><strong>Error!</strong> User doesn\'t exist<button type="button" class="close" data-dismiss="alert"> &times;</button><div class="alert alert-danger alert-dismissible fade show eerrorM" hidden></div>';
    //                     //document.getElementById("errorM").innerHTML = message;
    //                     $(".addobjbod").append(message);
    //                 } else {
    //                     document.getElementById("idholder").value = data.id
    //                     document.getElementById('objectivemodalFormData').submit();
    //                 }
    //             });
    //         } else {
    //             var message =
    //                 '<div class="alert alert-danger alert-dismissible fade show "><strong>Error!</strong> User doesn\'t exist<button type="button" class="close" data-dismiss="alert"> &times;</button><div class="alert alert-danger alert-dismissible fade show eerrorM" hidden></div>';
    //             //document.getElementById("errorM").innerHTML = message;
    //             $(".addobjbod").append(message);
    //         }
    //     } else {
    //         document.getElementById('objectivemodalFormData').submit();
    //     }
    // });
    
    jQuery("body").on("click", ".launchalignobj", function() {
        var id = $(this).data("id");
       
        
        sessionid = document.getElementById("session_id").value;
        var result = id + "m" + sessionid
        $.get(baseurl + "objectivesbymanager/" + result, function(data) {
            link = "";
            if (data == "no") {

            } else {
                for (var i = 0; i < data.length; i++) {
                    link +=
                        '<li class="aclickable-row row" value=' +
                        data[i].id +
                        ' id="okrchose">';
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
            $("#okrobjectives_list").html(link);
            $.get(baseurl + "allusers", function(data) {
                var users_data = [];
                var id_data = [];
                for (var i = 0; i < data.length; i++) {
                    users_data[i] = data[i].fname + " " + data[i].lname;
                    id_data[i] = data[i].id;
                }
                autocomplete(
                    document.getElementById("okralignobjchoice"),
                    users_data,
                    id_data,
                    document.getElementById("okridholder")
                );

            });
        });
        jQuery("#okrobjectivelist").modal("show");
    });
      jQuery("body").on("click", ".okrsearchowner", function() {
        var user_id = document.getElementById("okralignobjchoice").dataset.id;
        var user_name = document.getElementById("okralignobjchoice").value;
         sessionid = document.getElementById("session_id").value;
        var result = user_id + "m" + sessionid
        $.get(baseurl + "objectivesbyuser/" + result, function(data) {
            link = "";
            if (data == "no") {
                link = "<li style='list-style-type:none'>No results</li>";
            } else {
                for (var i = 0; i < data.length; i++) {
                    link +=
                        '<li class="aclickable-row row" value=' +
                        data[i].id +
                        ' id="okrchose">';
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

            $("#okrobjectives_list").html(link);
        });
    });

    //choose objective to align
    jQuery("body").on("click", "#okrchose", function() {
        $.get(baseurl + "objective/" + $(this).val(), function(data) {
            console.log(data)
            var name = data[0].fname[0] + data[0].lname[0];
            var alignedto =
                '<li class=" row col-md-10"  id="okrchosen"><p data-letters="' +
                name +
                '" id="objvalue" class="objvalue">' +
                data[0].objective_name +
                '</p></li><div class="col-md-2 text-right"><span id="okrremovealign"><i class="fa fa-remove " id="okrremove-alig" style="font-size:22px; color:red"></i></span></div><input type="hidden" name="aligned_to" value="' + data[0].id + '" />';
            $(".okralignedobj").html(alignedto);
            // autocomplete(document.getElementById("myInput"), userarr);
        });

        jQuery("#okrobjectivelist").modal("hide");
        document.getElementById("okralignobjchoice").value = "";
        document.getElementById("okrobjectives_list").innerHTML = "";
        $("#okrobjectives_list").html("");
        
        $("#launchalignobj").prop("hidden", true);
        $(".okralignedobj").removeAttr("hidden");
        $("#okrchosen").val($(this).val());
        $(".okralignedobj").attr("data-obj", $(this).val());
    });
    
     jQuery("body").on("click", "#okrremovealign", function() {
        $(".okralignedobj").html("");
        $(".okralignedobj").prop("hidden", true);
        $("#launchalignobj").removeAttr("hidden");
        $(".okralignedobj").attr("data-obj", "0");
    });
    
    
    
    
    
    
    
    
    
//launch objective align modal
    jQuery("body").on("click", ".launchalign", function() {
        var id = $(this).data("id");
        // if ($("#ownerlist").val()) {
        //     id = document.getElementById("ownerlist").dataset.id;
        // }
        // if ($("#eownerlist").val()) {
        //     id = document.getElementById("eownerlist").dataset.id;
        // }
        console.log(id);
        $.get(baseurl + "objectivebymanager/" + id, function(data) {
            link = "";
            if (data == "no") {

            } else {
                for (var i = 0; i < data.length; i++) {
                    link +=
                        '<li class="aclickable-row row" value=' +
                        data[i].id +
                        ' id="okrchoose">';
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
            $.get(baseurl + "allusers", function(data) {
                var users_data = [];
                var id_data = [];
                for (var i = 0; i < data.length; i++) {
                    users_data[i] = data[i].fname + " " + data[i].lname;
                    id_data[i] = data[i].id;
                }
                console.log(users_data)
                autocomplete(
                    document.getElementById("alignobjchoice"),
                    users_data,
                    id_data,
                    document.getElementById("idholder")
                );

            });
        });
        jQuery("#objectivelist").modal("show");
    });
    jQuery("body").on("click", ".searchowner", function() {
        var user_id = document.getElementById("alignobjchoice").dataset.id;
        console.log(user_id)
        var user_name = document.getElementById("alignobjchoice").value;
        $.get(baseurl + "objectivebyuser/" + user_id, function(data) {
            link = "";
            if (data == "no") {
                link = "<li style='list-style-type:none'>No results</li>";
            } else {
                for (var i = 0; i < data.length; i++) {
                    link +=
                        '<li class="aclickable-row row" value=' +
                        data[i].id +
                        ' id="okrchoose">';
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
    });

    //choose objective to align
    jQuery("body").on("click", "#okrchoose", function() {
        $.get(baseurl + "objectives/" + $(this).val(), function(data) {
            var name = data[0].fname[0] + data[0].lname[0];
            var alignedto =
                '<li class=" row col-md-10"  id="okrchosen"><p data-letters="' +
                name +
                '" id="objvalue" class="objvalue">' +
                data[0].objective_name +
                '</p></li><div class="col-md-2 text-right"><span id="removealign"><i class="fa fa-remove " id="remove-alig" style="font-size:22px; color:red"></i></span></div><input type="hidden" name="aligned_to" value="' + data[0].id + '" />';
            $(".alignedobj").html(alignedto);
            // autocomplete(document.getElementById("myInput"), userarr);
        });
        jQuery("#objectivelist").modal("hide");
        document.getElementById("alignobjchoice").value = "";
        document.getElementById("objectives_list").innerHTML = "";
        $("#objectives_list").html("");
        $(".alignlink").prop("hidden", true);
        $(".alignedobj").removeAttr("hidden");
        $("#okrchosen").val($(this).val());
        $(".alignedobj").attr("data-obj", $(this).val());
    });


    jQuery("body").on("click", "#removealign", function() {
        $(".alignedobj").html("");
        $(".alignedobj").prop("hidden", true);
        $("#launchalign").removeAttr("hidden");
        $(".alignedobj").attr("data-obj", "0");
    });
    jQuery("body").on("click", "#removeuser", function() {
       
        var l = '<a id="assignuser" style="cursor:pointer" onclick="addText();"> Assign user </i></a>'
        document.getElementById("ownerlist").classList.remove('av-circle');
        var ds =  document.getElementById("ownerlist")
        ds.removeAttribute('data-letters')
         $("#ownerlist").html(l);
    });
    
    //launch edit objective

    jQuery("body").on("click", ".objectivedatanoadmin", function() {
        var obj_id = $(this).data("id");

        // jQuery("#addobjbutton").val($(this).val())
        $.get(baseurl + "objectives/" + obj_id + "/edit", function(data) {
            $("#dobjective_name").text("Title: " + data[0].objective_name);
            var link = '<div class="alignlink">No Aligned Objective</div>';
            console.log(data[1].length);
            if (data[1].length !== 0) {
                link =
                    '<div class="alignlink" hidden><a href="#" class="launchalign" href="#" data-id=' +
                    data[0].user_id +
                    ' id="launchalign">+ Align this objective with another objective</a></div>';
                link +=
                    '<div class="row alignedobj " data-obj =' +
                    data[1].id +
                    ' id="alignedobj"><li class=" row col-md-10"  id="okrchosen"><p data-letters=' +
                    data[1].fname[0] +
                    data[1].lname[0] +
                    ' id="objvalue" class="objvalue">' +
                    data[1].objective_name +
                    "</p></li>  </div>";
            }

            $("#dalignmentobj").html(link);
            $("#eclbutton").val(data[0].id);
        });

        jQuery("#displayobjective").modal("show");
    });





    jQuery("body").on("click", ".addtask", function() {
        var id = $(this).data("id");
        console.log(id)
        addMilestone(id)
       
    });
    jQuery("body").on("click", ".addkeyresult", function() {
        $("#addkrbutton").val($(this).data("id"));
        console.log($("#addkrbutton").val());
        // jQuery("#addkrmodal").modal('show');


    });
    jQuery("body").on("click", ".updatekeyresult", function() {
        $("#updatekrbutton").val($(this).data("id"));
        $(".updatebody").html("");
        $.get(baseurl + "keyresults/" + $(this).data("id"), function(data) {


            var stat =
                '<label for="targetv" class=" col-form-label col-md-right mx-2">What is your status</label><div class="form-group row initalv"><label for="initial" class="col-md-4 col-form-label text-md-right">Target value</label>';
            stat +=
                '<div class="form-group col-md-4"><input type="number" id="targetv" value="' +
                data.targetValue +
                '" disabled></div></div>';


            stat +=
                '<div class="form-group row targetv"><label for="Target" class="col-md-4 col-form-label text-md-right">Current state</label><div class="form-group col-md-4"><input type="number" step=0.01 id="currentv" value="' +
                data.currentState +
                '"required></div></div>';
            $(".updatebody").html(stat);

        });

        jQuery("#updatekrmodal").modal("show");
    });

    jQuery("body").on("click", ".updatetask", function() {
        $("#updatetaskbutton").val($(this).data("id"));

        $.get(baseurl + "tasks/" + $(this).data("id"), function(data) {
            console.log();
            $("#tasku").text("Task: " + data.taskname);
        });

        jQuery("#updatetaskmodal").modal("show");
    });



    $("#keyresulttype").change(function() {
        if ($(this).children("option:selected").val() == 0) {
            $(".targetnumber").prop("hidden", true);
            $(".initialnumber").prop("hidden", true);

            $("#targetnumber").removeAttr("required");
            $("#initialnumber").removeAttr("required");
        } else if ($(this).children("option:selected").val() == 1) {
            $(".targetnumber").removeAttr("hidden");
            $(".initialnumber").removeAttr("hidden");
            $("#targetnumber").prop("required", true);
            $("#initialnumber").prop("required", true);
        }
    });
    $("#addkrmodalFormData").submit(function(e) {
        $.ajaxSetup({
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
        });
        e.preventDefault();

        var formData = {
            keyresult_type: $("#keyresulttype")
                .children("option:selected")
                .val(),
            keyresult_name: jQuery("#keyresult_name").val(),
            targetValue: jQuery("#targetnumber").val(),
            initialValue: jQuery("#initialnumber").val(),
            objective_id: $("#addkrbutton").val(),
        };
        console.log(formData);

        var type = "POST";

        var ajaxurl = baseurl + "keyresults";

        $.ajax({
            type: type,
            url: ajaxurl,
            data: formData,
            dataType: "json",
            success: function(rdata) {
                data = rdata[0];
                var percent = data.keyresult_type == 0 ? 0 : (parseFloat(data.attainment) * 100).toFixed(2)


                var kr =
                    '<tr  id="krls' + data.id + '"><td class = "row"><div class="col-md-10"><span style="margin-left: 30px; margin-bottom: 20px; color:#5F9EA0" class=""></span><b data-letters= "' + rdata[3] + '" class="objvalue">' +
                    data.keyresult_name +
                    '</b></div><div class="col-md-1" id="krattainment' + data.id + '">' + percent + '%</div>';
                kr +=
                    '<div class="col-md-1 pull-right"> <a href="#" class="neonav-link  caret-off" data-toggle="dropdown" id="navbarDropdown' + data.id + '"  data-toggle="dropdown" ><i class="fa fa-ellipsis-v"></i></a> <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown' + data.id + '">'
                if (data.keyresult_type == 1) {
                    kr += '<li><a id="updatekr' + data.id + '" class="dropdown-item updatekeyresult" data-id= "' + data.id + '">Update</a></li>'

                }
                kr += '<li><a  id ="editkr' + data.id + '" class="dropdown-item editkr" data-id="' +
                    data.id +
                    '">Edit</a></li><li><a id="deletekr' + data.id + '" class="dropdown-item deletekr" data-id="' +
                    data.id +
                    '">Delete</a></li></div></div></td</tr>';



                var outerkr = '<tr id="krval' + data.id + '"><td><li class = "row" ><div class= "col-md-8 aclickable-row eachkr" data-id="' + data.id + '"> ' + data.keyresult_name + '</div><div class= "col-md-2" id="frontkratt' + data.id + '">' + (data.keyresult_type == 0 ? 0 : parseFloat(data.attainment) * 100).toFixed(2) + '%</div></li></td></tr>'
                jQuery(".keyresultlist" + data.objective_id).append(kr);
                jQuery(".krlistokr" + data.objective_id).append(outerkr);
                jQuery(".numberkr" + data.objective_id).text(rdata[2]);
                var percent = rdata[1] == 0 ? 0 : (parseFloat(rdata[1]) * 100).toFixed(2)

                jQuery(".objattainment").text(percent + "%");
                jQuery("#frontobj" + data.objective_id).text(percent + "%");

                $(".targetnumber").prop("hidden", true);
                $(".initialnumber").prop("hidden", true);

                $("#targetnumber").removeAttr("required");
                $("#initialnumber").removeAttr("required");
                document.getElementById("krclbutton").click();
            },
            error: function(data) {
                console.log("Error:", data.responseText);
            },
        });
    });

    $("#editkrmodalFormData").submit(function(e) {

        jQuery("#editkrmodal").modal("hide");
        $.ajaxSetup({
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
        });
        e.preventDefault();
        console.log(document.getElementById("editkrbutton").dataset.id);
        var formData = {
            keyresult_type: $("#ekeyresulttype")
                .children("option:selected")
                .val(),
            keyresult_name: jQuery("#ekeyresult_name").val(),
            targetValue: jQuery("#etargetnumber").val(),
            initialValue: jQuery("#einitialnumber").val(),
            id: document.getElementById("editkrbutton").dataset.id,
        };
        console.log(formData);

        var type = "PUT";

        var ajaxurl = baseurl + "keyresults/" + document.getElementById("editkrbutton").dataset.id;

        $.ajax({
            type: type,
            url: ajaxurl,
            data: formData,
            dataType: "json",
            success: function(rdata) {
                data = rdata[0];


                var kr =
                    '<tr  id="krls' + data.id + '"><td class = "row"><div class="col-md-10"><span style="margin-left: 30px; margin-bottom: 20px; color:#5F9EA0" class=""></span><b data-letters= "' + rdata[3] + '" class="objvalue">' +
                    data.keyresult_name +
                    '</b></div><div class="col-md-1" "krattainment' + data.id + '">' +
                    (data.attainment == 0 ? 0 : (parseFloat(data.attainment * 100)).toFixed(2)) +
                    "%</div>";
                kr +=
                    '<div class="col-md-1 pull-right"> <a href="#" class="neonav-link  caret-off" data-toggle="dropdown" id="navbarDropdown' + data.id + '"  data-toggle="dropdown" ><i class="fa fa-ellipsis-v"></i></a> <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown' + data.id + '">'
                if (data.keyresult_type == 1) {
                    kr += '<li><a id="updatekr' + data.id + '" class="dropdown-item updatekeyresult" data-id= "' + data.id + '">Update</a></li>'
                }
                kr += '<li><a  id ="editkr' + data.id + '" class="dropdown-item editkr" data-id="' +
                    data.id +
                    '">Edit</a></li><li><a id="deletekr' + data.id + '" class="dropdown-item deletekr" data-id="' +
                    data.id +
                    '">Delete</a></li></div></div></td></tr>';
                var outerkr = '<tr id="krval' + data.id + '"><td><li class = "row" ><div class= "col-md-8 aclickable-row eachkr" data-id="' + data.id + '"> ' + data.keyresult_name + '</div><div class= "col-md-2" id="frontkratt' + data.id + '">' + (data.attainment == 0 ? 0 : (parseFloat(data.attainment * 100)).toFixed(2)) + '%</div></li></td></tr>'


                $(".keyresultlist" + data.objective_id + " #krls" + data.id).replaceWith(kr);

                var percent = rdata[1] == 0 ? 0 : (parseFloat(rdata[1]) * 100).toFixed(2)
                console.log($(".keyresultlist" + data.objective_id + " #krls" + data.id))
                jQuery("#krval" + data.id).replaceWith(outerkr);
                jQuery(".objattainment").text(percent + "%");
                jQuery("#frontobj" + data.objective_id).text(percent + "%");

                jQuery("#editkrmodalFormData").trigger("reset");
            },
            error: function(data) {
                console.log("Error:", data.responseText);
            },
        });
    });



    jQuery("body").on("click", ".editkr", function() {

        jQuery("#editkrmodal").modal('show')
        document.getElementById("editkrbutton").dataset.id = $(this).data("id")
    
        $.get(baseurl + "keyresults/" + $(this).data("id"), function(data) {
            console.log(data);
            var kr = '<div class="form-group row"><label for="ekeyresult_name" class="col-12 col-form-label">Key Result</label><input type="text" class="form-control mx-20 round" id="ekeyresult_name" aria-describedby="objective" autocomplete="off" placeholder="Key result" value="' + data.keyresult_name + '" required></div>'
            kr += '<div class="form-group row"><label for="ekeyresulttype" class="col-12 col-form-label">Key result type</label><div class="form-group col-md-12">'

            kr += '<select id="ekeyresulttype" class="form-control" ><option value=1 ' + (data.keyresult_type == 1 ? "selected" : "") + '>Should increase to</option><option value=0 ' + (data.keyresult_type == 0 ? "selected" : "") + '>Achieved or not</option></select></div></div>'

            var message = data.keyresult_type == 1 ? "" : "hidden"
            var required = data.keyresult_type == 1 ? "required" : ""
            var target = data.keyresult_type == 1 ? data.targetValue : ""
            var initial = data.keyresult_type == 1 ? data.initialValue : ""


            kr += '<div class="row" ><div class="form-group col-md-6 etargetnumber" ' + message + '><label for="etargetnumber" class="col-12 col-form-label">Target Number</label><div class="form-group col-md-12"><input type="number" id="etargetnumber"  class="form-control round" min="0" max="1000" value="' + target + '" ' + required + ' ></div></div><div class="form-group col-md-6 row einitialnumber" ' + message + '>'
            kr += '<label for="einitialnumber" class="col-12 col-form-label">Initial Number</label><div class="form-group col-md-12"><input type="number" id="einitialnumber"  class="form-control round" min="0" max="1000" value="' + initial + '" ' + required + ' ></div></div></div>'

            document.getElementById('krbody').innerHTML = kr
        });

    });
    jQuery("body").on("click", ".deletekr", function() {
        var kr_id = $(this).data("id");
        // $("#deletetaskbutton").val($(this).data('id'))

        jQuery("#deletekrmodal").modal("show");

        $.get(baseurl + "keyresults/" + kr_id, function(data) {
            jQuery("#deletekrlabel").text(
                "Are you sure you want to delete the keyresult: " +
                data.keyresult_name +
                "?"
            );
            document.getElementById('kr-delete').value = data.id

        });
    });
    //delete a keyresult
    jQuery("#kr-delete").click(function() {
        var kr_id = $(this).val();

        $.ajaxSetup({
            headers: {
                "X-CSRF-TOKEN": jQuery('meta[name="csrf-token"]').attr(
                    "content"
                ),
            },
        });
        $.ajax({
            type: "DELETE",
            url: baseurl + "keyresults/" + kr_id,
            success: function(data) {
                $(".keyresultlist" + data[0].id + " #krls" + kr_id).remove();


                jQuery("#krval" + kr_id).remove();
                var percent = data[0].attainment == 0 ? 0 : (parseFloat(data[0].attainment) * 100).toFixed(2)
                document.getElementById("frontobj" + data[0].id).innerHTML = percent + "%"
                $(".numberkr" + data[0].id).text(data[1]);
                $(".objattainment").text(percent + "%")

                jQuery("#deletekrmodal").modal("hide");
            },
            error: function(data) {
                console.log("Error:", data);
            },
        });
    });
    jQuery("body").on("change", "#ekeyresulttype", function() {
        if ($(this).children("option:selected").val() == 0) {

            $(".etargetnumber").prop("hidden", true);
            $(".einitialnumber").prop("hidden", true);

            $("#etargetnumber").removeAttr("required");
            $("#einitialnumber").removeAttr("required");
        } else if ($(this).children("option:selected").val() == 1) {
            $(".etargetnumber").removeAttr("hidden");
            $(".einitialnumber").removeAttr("hidden");
            $("#etargetnumber").prop("required", true);
            $("#einitialnumber").prop("required", true);
        }
    });
    $("#updatekrmodalFormData").submit(function(e) {
        jQuery("#updatekrmodal").modal("hide");
        $.ajaxSetup({
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
        });
        e.preventDefault();

        var formData = {
            id: document.getElementById("updatekrbutton").value,
            currentState: document.getElementById("currentv").value,

        };
        console.log(formData)
        var type = "POST";

        var ajaxurl = baseurl + "updatestatus";

        $.ajax({
            type: type,
            url: ajaxurl,
            data: formData,
            dataType: "json",
            success: function(data) {
                console.log(data[0]);
                $(".objattainment").html((data[1].attainment * 100).toFixed(2) + "%");
                $("#krattainment" + data[0].id).html(
                    (data[0].attainment * 100).toFixed(2) + "%"
                );
                jQuery("#frontkratt" + data[0].id).text((data[0].attainment * 100).toFixed(2) + "%");
                jQuery("#frontobj" + data[1].id).text((data[1].attainment * 100).toFixed(2) + "%");

                jQuery("#objectivemodalFormData").trigger("reset");
            },
            error: function(data) {
                console.log("Error:", data.responseText);
            },
        });
    });
    //add milestone
   // $("#addtaskmodalFormData").submit(function(e) {
        jQuery("body").on("click", ".addmilestonetask", function() {
       // jQuery("#addtaskmodal").modal("hide");
        $.ajaxSetup({
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
        });
        e.preventDefault();

        var formData = {
            taskname: document.getElementById("taskname").value,
            isMilestone: 1,
            keyresultid: document.getElementById("addtaskbutton").value,
        };
        console.log(formData);

        var type = "POST";

        var ajaxurl = baseurl + "tasks";

        $.ajax({
            type: type,
            url: ajaxurl,
            data: formData,
            dataType: "json",
            success: function(data) {
                console.log(data);
                var stat = "Achieved";
                if (data.status == 0) {
                    stat = "Not Achieved";
                }
                var task = '<li id="taskl' + data.id + '"><div class = "row" ><div class="col-md-8"><span style="margin-left: 30px; margin-bottom: 20px; color:#5F9EA0" class="Check-ins"></span>'
                task += '<b class="milevalue">' + data.taskname + '</b></div><div class="col-md-3" id="taskstat' + data.id + '">' + stat + '</div>'

                task += '<div class="col-md-1 pull-right"><a href="#" class="neonav-link  caret-off" data-toggle="dropdown" id="navbardrop' + data.id + '"  data-toggle="dropdown" ><i class="fa fa-ellipsis-v"></i></a><div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbardrop' + data.id + '"><a  id ="edittask' + data.id + '" class="dropdown-item editmile" data-id= "' + data.id + '" >Edit</a><a id="deletetask' + data.id + '" class="dropdown-item deletetask" data-id= "' + data.id + '">Delete</a></div></div></div></li>'

                jQuery(".milestonelist" + data.keyresultid).append(task);

                jQuery("#addtaskmodalFormData").trigger("reset");
            },
            error: function(data) {
                console.log("Error:", data.responseText);
            },
        });
    });

    $("#edittaskmodalFormData").submit(function(e) {
        jQuery("#edittaskmodal").modal("hide");
        $.ajaxSetup({
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
        });
        e.preventDefault();

        var formData = {
            id: document.getElementById("edittaskbutton").value,
            taskname: document.getElementById("emtaskname").value,
        };
        console.log(formData);

        var type = "PUT";

        var ajaxurl = baseurl + "tasks/" + formData.id;

        $.ajax({
            type: type,
            url: ajaxurl,
            data: formData,
            dataType: "json",
            success: function(data) {
                console.log(data);
                var stat = "Achieved";
                if (data.status == 0) {
                    stat = "Not Achieved";
                }
                var task = '<li id="taskl' + data.id + '"><div class = "row" ><div class="col-md-8"><span style="margin-left: 30px; margin-bottom: 20px; color:#5F9EA0" class="Check-ins"></span>'
                task += '<b class="milevalue">' + data.taskname + '</b></div><div class="col-md-3" id="taskstat' + data.id + '">' + stat + '</div>'

                task += '<div class="col-md-1 pull-right"><a href="#" class="neonav-link  caret-off" data-toggle="dropdown" id="navbardrop' + data.id + '"  data-toggle="dropdown" ><i class="fa fa-ellipsis-v"></i></a><div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbardrop' + data.id + '"><a  id ="edittask' + data.id + '" class="dropdown-item editmile" data-id= "' + data.id + '" >Edit</a><a id="deletetask' + data.id + '" class="dropdown-item deletetask" data-id= "' + data.id + '">Delete</a></div></div></div></li>'




                jQuery(".milestonelist" + data.keyresultid + " #taskl" + data.id).replaceWith(task);


            },
            error: function(data) {
                console.log("Error:", data.responseText);
            },
        });
    });
    //launch the delete milestone modal
    jQuery("body").on("click", ".deletetask", function() {
        var task_id = $(this).data("id");
        // $("#deletetaskbutton").val($(this).data('id'))

        jQuery("#deletetaskmodal").modal("show");

        $.get(baseurl + "tasks/" + task_id, function(data) {
            jQuery("#deletemlabel").text(
                "Are you sure you want to delete the task: " +
                data.taskname +
                "?"
            );
            jQuery("#task-delete").val(data.id);
        });
    });
    jQuery("body").on("click", ".editmile", function() {
        var id = $(this).data("id");
        editMilestone(id)
    });
    //delete a milestone
    jQuery("#task-delete").click(function() {
        var task_id = $(this).val();

        $.ajaxSetup({
            headers: {
                "X-CSRF-TOKEN": jQuery('meta[name="csrf-token"]').attr(
                    "content"
                ),
            },
        });
        $.ajax({
            type: "DELETE",
            url: baseurl + "tasks/" + task_id,
            success: function(data) {
                $("#miletaskl" + data.id).remove();
                console.log(data);
                jQuery("#deletetaskmodal").modal("hide");
            },
            error: function(data) {
                console.log("Error:", data);
            },
        });
    });


    $(".sessionselect").on("change", function() {
        var sessionid = $(this).val();
        if ($(this).val() != 0) {
            $(".empselect").on("change", function() {
                var userid = $(this).val();
                if ($(this).val() != 0) {
                    $(".objselect").removeAttr("disabled");
                    $.get(
                        "objectiveslist/" + userid + "m" + sessionid,
                        function(data) {
                            var link =
                                "<option selected value=0>Select Objective</option>";
                            for (var i = 0; i < data.length; i++) {
                                link +=
                                    "<option  value=" +
                                    data[i].id +
                                    ">" +
                                    data[i].objective_name +
                                    "</option>";
                            }
                            $(".objselect").html(link);
                        }
                    );
                } else {
                    $(".objselect").attr("disabled", "disabled").off("click");
                }
            });
        } else {
            $(".objselect").prop("disabled", true);
        }
    });
    $(".objectivechoice").on("change", function() {
        if ($(this).children("option:selected").val() == 0) {
            $(".krchoice").val("0");
            $(".krchoice").prop("disabled", true);
            $(".milechoice").val("0");
            $(".milechoice").prop("disabled", true);
            $(".setmilestone").prop("disabled", true);
        } else {
            var objid = $(this).val();
            $(".krchoice").removeAttr("disabled");
            $.get(baseurl + "krslist/" + objid, function(data) {
                var link = "<option selected value=0>Select Keyresult</option>";
                for (var i = 0; i < data.length; i++) {
                    link +=
                        "<option  value=" +
                        data[i].id +
                        ">" +
                        data[i].keyresult_name +
                        "</option>";
                }
                $(".krchoice").html(link);
            });
        }
    });

    $(".krchoice").on("change", function() {
        if ($(this).children("option:selected").val() == 0) {
            $(".milechoice").val("0");
            $(".milechoice").prop("disabled", true);
            $(".setmilestone").prop("disabled", true);
        } else {
            var kid = $(this).val();
            $(".milechoice").removeAttr("disabled");
            $.get(baseurl + "milelist/" + kid, function(data) {
                var link = "<option selected value=0>Select Milestone</option>";
                for (var i = 0; i < data.length; i++) {
                    link +=
                        "<option  value=" +
                        data[i].id +
                        ">" +
                        data[i].taskname +
                        "</option>";
                }
                $(".milechoice").html(link);
            });
        }
    });

    jQuery("body").on("click", ".setmilestone", function() {
        var id = $(this).data('id')
        if ($(this).is(":checked")) {
            $("#addtaskvalue"+id).prop("disabled", true);
        } else {
            $("#addtaskvalue"+id).removeAttr("disabled");
        }
    });

    $(".tasksforsub").on("change", function() {
        if ($(this).children("option:selected").val() == 0) {
            $("#subtask").prop("disabled", true);
        } else {
            var kid = $(this).val();
            $("#subtask").removeAttr("disabled");
        }
    });

    $(".empselect ").on("change", function() {
        if ($(this).val() != 0) {
            $(".sessionselect").on("change", function() {
                if ($(this).val() != 0) {
                    $(".objselect").removeAttr("disabled");
                } else {
                    $(".objselect").prop("disabled", true);
                }
            });
        } else {
            $(".objselect").prop("disabled", true);
        }
    });
    //edit percent button clicked
    jQuery("body").on("click", ".editpercent", function() {
        var id = $(this).data('id')
        var value = parseInt(document.getElementById("editpercent" + id).dataset.value)

        console.log(id)
            //when edit percent is clicked
        if ($(this).hasClass('text-success') && !$(this).hasClass('text-danger')) {
            $('#percentc' + id).removeAttr("readonly");

            $('#editpercent' + id + '> i').removeClass('fa-pencil');
            $('#editpercent' + id).removeClass('text-success');
            $('#editpercent' + id + '> i ').addClass('fa-times');
            $('#editpercent' + id).addClass('text-danger');
            $('#editpercent' + id).addClass('removepercent');

            $('#tickpercent' + id).removeAttr('hidden');



        }
    });
    //remove percent button clicked
    jQuery("body").on("click", ".removepercent", function() {
        var id = $(this).data('id')
        var value = parseInt($(this).data('value'))
        console.log(id)


        $('#percentc' + id).val(value);
        $('#percentc' + id).prop("readonly", true);
        $('#editpercent' + id + '> i').addClass('fa-pencil');
        $('#editpercent' + id).addClass('text-success');
        $('#editpercent' + id + '> i ').removeClass('fa-times');
        $('#editpercent' + id).removeClass('text-danger');
        $('#editpercent' + id).addClass('editpercent');
        $('#editpercent' + id).removeClass('removepercent');
        $('#tickpercent' + id).prop("hidden", true);

    });

    //move away from input percent field

    jQuery("body").on("click", ".tickpercent", function() {
        var id = $(this).data('id')
        var value = parseInt($('#percentc' + id).val())
        console.log(value)
        console.log(document.getElementById('percentc' + id).value)

        $('#editpercent' + id).attr('data-value', value)

        $('#percentc' + id).prop("readonly", true);


        $('#editpercent' + id).removeClass('removepercent');
        $('#editpercent' + id + '> i').addClass('fa-pencil');
        $('#editpercent' + id).addClass('text-success');
        $('#editpercent' + id + '> i ').removeClass('fa-times');
        $('#editpercent' + id).removeClass('text-danger');
        $('#tickpercent' + id).prop("hidden", true);

    });


    jQuery("body").on("click", ".teditpercent", function() {
        var id = $(this).data('id')
        var value = parseInt(document.getElementById("teditpercent" + id).dataset.value)
        console.log(value)
        console.log(id)
            //when edit percent is clicked
        if ($(this).hasClass('text-success') && !$(this).hasClass('text-danger')) {

            $('#tpercentc' + id).removeAttr("readonly");
            $('#tpercentc' + id).val(value);
            $('#teditpercent' + id + '> i').removeClass('fa-pencil');
            $('#teditpercent' + id).removeClass('text-success');
            $('#teditpercent' + id + '> i ').addClass('fa-times');
            $('#teditpercent' + id).addClass('text-danger');
            $('#teditpercent' + id).addClass('tremovepercent');

            $('#ttickpercent' + id).removeAttr('hidden');

        }
    });
    jQuery("body").on("click", ".tremovepercent", function() {
        var id = $(this).data('id')
        var value = parseInt($(this).data('value'))
        console.log(id)
            //when remove percent change is clicked

        $('#tpercentc' + id).val(value);
        $('#tpercentc' + id).prop("readonly", true);
        $('#teditpercent' + id + '> i').addClass('fa-pencil');
        $('#teditpercent' + id).addClass('text-success');
        $('#teditpercent' + id + '> i ').removeClass('fa-times');
        $('#teditpercent' + id).removeClass('text-danger');
        $('#teditpercent' + id).removeClass('tremovepercent');

        $('#ttickpercent' + id).prop("hidden", true);




    });

    jQuery("body").on("click", ".addweeklytask", function() {
    var id = $(this).data('id');
              $.get(baseurl + "milelist/" + id, function(data) {
          var task = '<div class="block-content addtaskblock row" id="addtaskblock'+id+'"><input type="text" class="form-control taskvalue col-md-6" id="addtaskvalue'+id+'"  placeholder=" Add Task name"><div class="col-md-4"><select class="form-control milechoice" id="milechoice'+id+'">'
                                    for(var i = 0; i < data.length; i++){
						task += '<option value="'+data[i].id+'">'+ data[i].taskname+'</option>'
					}

                              task +=  '</select><div class="mt-10 ml-10"><input class="form-check-input setmilestone ml-10" type="checkbox" id="setmilestone'+id+'" data-id='+ id+'><label class="form-check-label ml-30" for="setmilestone'+id+'">Add Milestone as a task</label></div></div><div class="col-md-1"> <button type="button" class="btn btn-sm btn-rounded btn-outline-success wtaskadd mt-5" value="'+id+'"><i class="fa fa-plus" ></i></button></div><div class="col-md-1"><button type="button" class="btn btn-sm btn-rounded btn-outline-danger taskremove mt-5" value="'+id+'"><i class="fa fa-times"></i></button></div></div></div>'
                    
                        jQuery("#tasks-list" + id).prepend(task);
                       
                      
                      if (document.getElementById("weeklyaccordionExample" + id).classList.contains('collapsed')){
                           document.getElementById("weeklyaccordionExample" + id).click();
                      }
                      
                          $("#addweeklytask" +id).addClass('ancdisabled');
                        
       });
    });
     jQuery("body").on("click", ".addmytask", function() {
    var id = $(this).data('id');
              $.get(baseurl + "milelist/" + id, function(data) {
          var task = '<div class="block-content addtaskblock row" id="addtaskblock'+id+'"><input type="text" class="form-control taskvalue col-md-6" id="addtaskvalue'+id+'"  placeholder=" Add Task name"><div class="col-md-4"><select class="form-control milechoice" id="milechoice'+id+'">'
                                    for(var i = 0; i < data.length; i++){
						task += '<option value="'+data[i].id+'">'+ data[i].taskname+'</option>'
					}

                              task +=  '</select></div><div class="col-md-1"> <button type="button" class="btn btn-sm btn-rounded btn-outline-success wmytaskadd mt-5" value="'+id+'"><i class="fa fa-plus" ></i></button></div><div class="col-md-1"><button type="button" class="btn btn-sm btn-rounded btn-outline-danger taskremove mt-5" value="'+id+'"><i class="fa fa-times"></i></button></div></div></div>'
                    
                        jQuery("#tasks-list" + id).prepend(task);
                       
                      
                     document.getElementById("addweeklytask" + id).disabled = true
                      
                         
                        
       });
    });
     jQuery("body").on("click", ".adddailysubtask", function() {
         console.log("hello")
    var id = $(this).data('id');

        
          var task = '<div class="block-content addsubtaskblock row" id="addsubtaskblock'+id+'"><input type="text" class="form-control subtaskvalue col-md-7" id="subtaskvalue'+id+'"  placeholder=" Add Sub Task name">'
                                   
					
					

                              task +=  '<div class="col-md-1"> <button type="button" class="btn btn-sm btn-rounded btn-outline-success wsubtaskadd mt-5" value="'+id+'"><i class="fa fa-plus" ></i></button></div><div class="col-md-1"><button type="button" class="btn btn-sm btn-rounded btn-outline-danger subtaskremove mt-5" value="'+id+'"><i class="fa fa-times"></i></button></div></div></div>'
                    
                        jQuery("#subtasks-list" + id).prepend(task);
                       
                      
                     
                      
                          $("#adddailysubtask" +id).addClass('ancdisabled');
                        
       
    });
     jQuery("body").on("click", ".editweeklytask", function() {
    var id = $(this).data('id');
    var checked = document.getElementById('taskcheckv' +id).checked? 1: 0
    var percent = parseFloat(document.getElementById('tpercentc' + id).value);
    console.log(checked);
              $.get(baseurl + "tasks/" + id, function(data) {
          var task = '<div class="block-content edittaskblock row" id="edittaskblock'+id+'"><input type="text" class="form-control taskvalue col-md-6" id="edittaskvalue'+id+'"  placeholder=" Add Task name" value="'+data.taskname+ '"><div class="col-md-4"><select class="form-control milechoice" id="emilechoice'+id+'">'
                $.get(baseurl + "milelist/" + data.keyresultid, function(rdata) {
                                    for(var i = 0; i < rdata.length; i++){
                                        var message = rdata[i].id == data.parent_task ?'selected':"";
						task += '<option value="'+rdata[i].id+'" '+message+'>'+ rdata[i].taskname+'</option>'
					                }
                                      

                              task +=  '</select></div><div class="col-md-1"> <button type="button" class="btn btn-sm btn-rounded btn-outline-success wtaskedit mt-5" value="'+id+'" data-checked='+checked+' data-percent ='+percent+'><i class="fa fa-pencil" ></i></button></div><div class="col-md-1"><button type="button" class="btn btn-sm btn-rounded btn-outline-danger etaskremove mt-5" value="'+id+'" data-checked='+checked+' data-percent ='+percent+'><i class="fa fa-times"></i></button></div></div></div>'
                    
                        jQuery("#taskl" + id).replaceWith(task);
                       
                      
                  
                      
                        
       });
              });
    });
    jQuery("body").on("click", ".editmytask", function() {
    var id = $(this).data('id');
   
   
              $.get(baseurl + "tasks/" + id, function(data) {
          var task = '<div class="block-content edittaskblock row" id="edittaskblock'+id+'"><input type="text" class="form-control taskvalue col-md-6" id="edittaskvalue'+id+'"  placeholder=" Add Task name" value="'+data.taskname+ '"><div class="col-md-4"><select class="form-control milechoice" id="emilechoice'+id+'">'
                $.get(baseurl + "milelist/" + data.keyresultid, function(rdata) {
                                    for(var i = 0; i < rdata.length; i++){
                                        var message = rdata[i].id == data.parent_task ?'selected':"";
						task += '<option value="'+rdata[i].id+'" '+message+'>'+ rdata[i].taskname+'</option>'
					                }
                                      

                              task +=  '</select></div><div class="col-md-1"> <button type="button" class="btn btn-sm btn-rounded btn-outline-success wmytaskedit mt-5" value="'+id+'" ><i class="fa fa-pencil" ></i></button></div><div class="col-md-1"><button type="button" class="btn btn-sm btn-rounded btn-outline-danger emytaskremove mt-5" value="'+id+'" data-checked='+checked+' data-percent ='+percent+'><i class="fa fa-times"></i></button></div></div></div>'
                    
                        jQuery("#taskl" + id).replaceWith(task);
                       
                      
                  
                      
                        
       });
              });
    });
    
     jQuery("body").on("click", ".editdailysubtask", function() {
    var id = $(this).data('id');
    var checked = document.getElementById('subtaskcheckv' +id).checked? 1: 0
   
    
              $.get(baseurl + "subtasks/" + id, function(data) {
          var task = '<div class="block-content editsubtaskblock row" id="editsubtaskblock'+id+'"><input type="text" class="form-control editsubtaskvalue col-md-6" id="editsubtaskvalue'+id+'"  placeholder=" Add Task name" value="'+data.subtask_name+ '">'
               task +=  '<div class="col-md-1"> <button type="button" class="btn btn-sm btn-rounded btn-outline-success wsubtaskedit mt-5" value="'+id+'" data-checked='+checked+'><i class="fa fa-pencil" ></i></button></div><div class="col-md-1"><button type="button" class="btn btn-sm btn-rounded btn-outline-danger esubtaskremove mt-5" value="'+id+'" data-checked='+checked+'><i class="fa fa-times"></i></button></div></div></div>'
                                      

                     
                        jQuery("#subtaskl" + id).replaceWith(task);
                       
                      
                  
                      
                        
       });
             
    });
     jQuery("body").on("click", ".taskremove", function() {
          var id = $(this).val();
         $("#addtaskblock" +id).remove();
        $("#addweeklytask" +id).removeClass('ancdisabled');
          document.getElementById("addweeklytask" + id).disabled = false
         
     });
     jQuery("body").on("click", ".subtaskremove", function() {
          var id = $(this).val();
         $("#addsubtaskblock" +id).remove();
         
          $("#adddailysubtask" +id).removeClass('ancdisabled');
         
     });
     jQuery("body").on("click", ".miletaskremove", function() {
          var id = $(this).val();
          var mid = $(this).data('mid');
         $("#taskl" +id).remove();
        getTotalTask(mid)
         
         
     });
       jQuery("body").on("click", ".etaskremove", function() {
          var id = $(this).val();
          var checked = $(this).data('checked') == '1'? "checked":"";
           var percent = parseFloat($(this).data('percent'));
        console.log($(this).data('checked')=='1')
            $.get(baseurl + "tasks/" + id, function(data) {
                   $.get(baseurl + "tasks/" + data.parent_task, function(tdata) {
         var task = '<div class="container" id = "taskl' + data.id + '"><div class="row mb-10"><div class="col-md-4 mt-5"><label class="css-control-sm css-control-info css-checkbox css-checkbox-rounded"><input type="checkbox" class="css-control-input" name="taskcheck[]" value=' + data.id + ' '+checked+' id="taskcheckv'+data.id+'"><span class="css-control-indicator"></span>' + data.taskname + '</label></div>';
                        task += '<div class="col-md-3"><input type=number step=0.01 name="taskoptions' + data.id + '" class="mt-5 col-md-5 round tpercentc" id="tpercentc' + data.id + '" value= '+percent+' data-id= ' + data.id + '  min=0 max=100 >'
                        task += '</div><div class ="col-md-3"><a class="mt-5"><span class="dot mt-5"></span> ' + tdata.taskname + '</a></div>'
                        task += '<a class="col-md-1 col-sm-1" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true"><i class="fa fa-bars"></i></a><div class="dropdown-menu dropdown-menu-right min-width-200 " aria-labelledby="page-header-user-dropdown" style="position: absolute; transform: translate3d(-103px, 34px, 0px); top: 0px; left: 0px; will-change: transform;" x-placement="bottom-end">'
                        task += '<a class="dropdown-item d-flex align-items-center justify-content-between editweeklytask"  data-id= ' + data.id + ' >Edit</a><a class="dropdown-item  deletewtask" data-id= ' + data.id + '>Delete</a></div></div></div>'
                        $("#edittaskblock" +id).replaceWith(task);
              });
            });
      
         
         
     });
       jQuery("body").on("click", ".emytaskremove", function() {
          var id = $(this).val();
       
      
        
            $.get(baseurl + "tasks/" + id, function(data) {
                   $.get(baseurl + "tasks/" + data.parent_task, function(tdata) {
         var task = '<div class="container" id = "taskl' + data.id + '"><div class="row mb-10"><div class="col-md-7 mt-5"><label class="css-control-sm css-control-info css-checkbox css-checkbox-rounded"><span class="css-control-indicator"></span>' + data.taskname + '</label></div>';
                        
                        task += '<div class ="col-md-3"><a class="mt-5"><span class="dot mt-5"></span> ' + tdata.taskname + '</a></div>'
                        task += '<a class="col-md-1 col-sm-1" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true"><i class="fa fa-bars"></i></a><div class="dropdown-menu dropdown-menu-right min-width-200 " aria-labelledby="page-header-user-dropdown" style="position: absolute; transform: translate3d(-103px, 34px, 0px); top: 0px; left: 0px; will-change: transform;" x-placement="bottom-end">'
                        task += '<a class="dropdown-item d-flex align-items-center justify-content-between editmytask"  data-id= ' + data.id + ' >Edit</a><a class="dropdown-item  deletewtask" data-id= ' + data.id + '>Delete</a></div></div></div>'
                        $("#edittaskblock" +id).replaceWith(task);
              });
            });
      
         
         
     });
     jQuery("body").on("click", ".esubtaskremove", function() {
          var id = $(this).val();
          var checked = $(this).data('checked') == '1'? "checked":"";
        
            $.get(baseurl + "subtasks/" + id, function(data) {
                var task = '<div class="container" id="subtaskl' + data.id + '"><div class="row"><div class="col-md-5"><label class="css-control-sm css-control-info css-checkbox css-checkbox-rounded" ><input type="checkbox" class="css-control-input" name="subtaskcheck[]" value= "' + data.id + '" checked id="subtaskcheckv' + data.id + '" '+checked+'>'
                task += '<span class="css-control-indicator"></span>' + data.subtask_name + '</label></div><p class="mb-5 col-md-1"><a class="font-w600" href="javascript:void(0)"></p><a class="col-md-1 col-sm-1" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true" style="margin-left: 23em;"><i class="fa fa-bars"></i></a>'
                task += '<div class="dropdown-menu dropdown-menu-right min-width-200" aria-labelledby="page-header-user-dropdown" style="position: absolute; transform: translate3d(-103px, 34px, 0px); top: 0px; left: 0px; will-change: transform;" x-placement="bottom-end"><a class="dropdown-item d-flex align-items-center justify-content-between editdailysubtask" data-id=' + data.id + '>Edit</a>'
                task += '<a class="dropdown-item deletedsubtask" data-id=' + data.id + '>Delete</a></div></div></div>'
               
                        $("#editsubtaskblock" +id).replaceWith(task);
              
            });
            
              
      
         
         
     });
     
      jQuery("body").on("click", ".wmytaskedit", function() {
          var id = $(this).val();
         
             $.ajaxSetup({
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
        });
         var formData = {
            taskname: document.getElementById('edittaskvalue' + id).value,
            parent_task: $("#emilechoice" + id).children("option:selected").val(),
        };
        console.log(formData);
            
        var type = "PUT";

        var ajaxurl = baseurl + "tasks/" + id;

        $.ajax({
            type: type,
            url: ajaxurl,
            data: formData,
            dataType: "json",
            success: function(data) {
             

               
                  
                    $.get(baseurl + "tasks/" + data.parent_task, function(tdata) {
                        var task = '<div class="container" id = "taskl' + data.id + '"><div class="row mb-10"><div class="col-md-7 mt-5"><label class="css-control-sm css-control-info css-checkbox css-checkbox-rounded"><span class="css-control-indicator"></span>' + data.taskname + '</label></div>';
                      
                        task += '<div class ="col-md-3"><a class="mt-5"><span class="dot mt-5"></span> ' + tdata.taskname + '</a></div>'
                        task += '<a class="col-md-1 col-sm-1" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true"><i class="fa fa-bars"></i></a><div class="dropdown-menu dropdown-menu-right min-width-200 " aria-labelledby="page-header-user-dropdown" style="position: absolute; transform: translate3d(-103px, 34px, 0px); top: 0px; left: 0px; will-change: transform;" x-placement="bottom-end">'
                        task += '<a class="dropdown-item d-flex align-items-center justify-content-between editmytask"  data-id= ' + data.id + ' >Edit</a><a class="dropdown-item deletewtask" data-id= ' + data.id + '>Delete</a></div></div></div>'
                       
                         $("#edittaskblock" +id).replaceWith(task);

                    
                    });
                
                
                    
         
 
            },
            error: function(data) {
                console.log("Error:", data.responseText);
            },
        });
         
     })
     jQuery("body").on("click", ".wtaskedit", function() {
          var id = $(this).val();
          var checked = $(this).data('checked') == '1'? "checked":"";
         var percent = parseFloat($(this).data('percent'));
             $.ajaxSetup({
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
        });
         var formData = {
            taskname: document.getElementById('edittaskvalue' + id).value,
            parent_task: $("#emilechoice" + id).children("option:selected").val(),
        };
        console.log(formData);
            
        var type = "PUT";

        var ajaxurl = baseurl + "tasks/" + id;

        $.ajax({
            type: type,
            url: ajaxurl,
            data: formData,
            dataType: "json",
            success: function(data) {
             

               
                  
                    $.get(baseurl + "tasks/" + data.parent_task, function(tdata) {
                        var task = '<div class="container" id = "taskl' + data.id + '"><div class="row mb-10"><div class="col-md-4 mt-5"><label class="css-control-sm css-control-info css-checkbox css-checkbox-rounded"><input type="checkbox" class="css-control-input taskcheck tc'+data.keyresultid+'" name="taskcheck[]" value=' + data.id + ' id="taskcheckv'+data.id+'" '+checked+' data-mid="'+data.keyresultid+'"><span class="css-control-indicator"></span>' + data.taskname + '</label></div>';
                        task += '<div class="col-md-3"><input type=number step=0.01 name="taskoptions' + data.id + '" class="mt-5 col-md-5 round tpercentc tasklist'+data.keyresultid+'" id="tpercentc' + data.id + '" value= '+percent+' data-id= ' + data.id + '  min=0 max=100 onkeyup="getTotalTask('+data.keyresultid+')">'
                        task += '</div><div class ="col-md-3"><a class="mt-5"><span class="dot mt-5"></span> ' + tdata.taskname + '</a></div>'
                        task += '<a class="col-md-1 col-sm-1" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true"><i class="fa fa-bars"></i></a><div class="dropdown-menu dropdown-menu-right min-width-200 " aria-labelledby="page-header-user-dropdown" style="position: absolute; transform: translate3d(-103px, 34px, 0px); top: 0px; left: 0px; will-change: transform;" x-placement="bottom-end">'
                        task += '<a class="dropdown-item d-flex align-items-center justify-content-between editweeklytask"  data-id= ' + data.id + ' >Edit</a><a class="dropdown-item deletewtask" data-id= ' + data.id + '>Delete</a></div></div></div>'
                       
                         $("#edittaskblock" +id).replaceWith(task);

                    
                    });
                
                
                    
         
 
            },
            error: function(data) {
                console.log("Error:", data.responseText);
            },
        });
         
     })
       jQuery("body").on("click", ".wmytaskadd", function() {
          var id = $(this).val();
         
             $.ajaxSetup({
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
        });
         var formData = {
            taskname: document.getElementById('addtaskvalue' + id).value,
            isMilestone: 0 ,
            keyresultid: id,
            parent_task: $("#milechoice" + id).children("option:selected").val(),
        };
        console.log(formData);
            
        var type = "POST";

        var ajaxurl = baseurl + "tasks";

        $.ajax({
            type: type,
            url: ajaxurl,
            data: formData,
            dataType: "json",
            success: function(data) {
                console.log(data);

               
                    var val = parseInt($("#taskno" + id).text())
                    $("#taskno" + id).text(val + 1);
                    $.get(baseurl + "tasks/" + data.parent_task, function(tdata) {
                        var task = '<div class="container" id = "taskl' + data.id + '"><div class="row mb-10"><div class="col-md-7 mt-5"><span class="css-control-indicator"></span>' + data.taskname + '</label></div>';
                       
                        task += '<div class ="col-md-3"><a class="mt-5"><span class="dot mt-5"></span> ' + tdata.taskname + '</a></div>'
                        task += '<a class="col-md-1 col-sm-1" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true"><i class="fa fa-bars"></i></a><div class="dropdown-menu dropdown-menu-right min-width-200 " aria-labelledby="page-header-user-dropdown" style="position: absolute; transform: translate3d(-103px, 34px, 0px); top: 0px; left: 0px; will-change: transform;" x-placement="bottom-end">'
                        task += '<a class="dropdown-item d-flex align-items-center justify-content-between editmytask"  data-id= ' + data.id + ' >Edit</a><a class="dropdown-item deletewtask" data-id= ' + data.id + '>Delete</a></div></div></div>'
                        $("#addtaskblock" +id).remove();
                        $("#addweeklytask" +id).removeClass('ancdisabled');
                        jQuery("#tasks-list" + data.keyresultid).append(task);
                      
                      
                    });
                
                
                    
         

            },
            error: function(data) {
                console.log("Error:", data.responseText);
            },
        });
         
     });
     
     
      jQuery("body").on("click", ".wtaskadd", function() {
          var id = $(this).val();
         
             $.ajaxSetup({
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
        });
         var formData = {
            taskname: document.getElementById("setmilestone" + id).checked ?
                "" : document.getElementById('addtaskvalue' + id).value,
            isMilestone: document.getElementById("setmilestone" + id).checked?1:0 ,
            keyresultid: id,
            parent_task: $("#milechoice" + id).children("option:selected").val(),
        };
        console.log(formData);
            
        var type = "POST";

        var ajaxurl = baseurl + "tasks";

        $.ajax({
            type: type,
            url: ajaxurl,
            data: formData,
            dataType: "json",
            success: function(data) {
                console.log(data);

                if (data.isMilestone == 1) {
                    var task = '<div class="container" id = "taskl' + data.id + '"><div class="row mb-10"><div class="col-md-4 mt-5"><label class="css-control-sm css-control-info css-checkbox css-checkbox-rounded"><input type="checkbox" class="css-control-input taskcheck tc'+data.keyresultid+'" name="taskcheck[]" value=' + data.id + ' checked id="taskcheckv'+data.id+'" data-mid="'+data.keyresultid+'"><span class="css-control-indicator"></span>' + data.taskname + '</label></div>';
                    task += '<div class="col-md-3"><input type=number step=0.01 name="taskoptions' + data.id + '" class="mt-5 col-md-5 round tpercentc tasklist'+data.keyresultid+'" id="tpercentc' + data.id + '" value= 0 data-id= ' + data.id + '  min=0 max=100 onkeyup="getTotalTask('+data.keyresultid+')" >'
                    task += '</div><div class="col-md-1"><button type="button" class="btn btn-sm btn-rounded btn-outline-danger miletaskremove" value="'+data.id+'" data-mid="'+data.keyresultid+'"><i class="fa fa-times"></i></button></div></div></div>'
                    $("#addtaskblock" +id).remove();
                    $("#addweeklytask" +id).removeClass('ancdisabled');
                    jQuery("#tasks-list" + data.keyresultid).append(task);
                    document.getElementById("selectallb").removeAttribute("hidden");
                   document.getElementById("tasktotal" + data.keyresultid).removeAttribute("hidden")

                } else {
                    var val = parseInt($("#taskno" + id).text())
                    $("#taskno" + id).text(val + 1);
                    $.get(baseurl + "tasks/" + data.parent_task, function(tdata) {
                        var task = '<div class="container" id = "taskl' + data.id + '"><div class="row mb-10"><div class="col-md-4 mt-5"><label class="css-control-sm css-control-info css-checkbox css-checkbox-rounded"><input type="checkbox" class="css-control-input taskcheck tc'+data.keyresultid+'" name="taskcheck[]" value=' + data.id + ' id="taskcheckv'+data.id+'" checked data-mid="'+data.keyresultid+'"><span class="css-control-indicator"></span>' + data.taskname + '</label></div>';
                        task += '<div class="col-md-3"><input type=number step=0.01 name="taskoptions' + data.id + '" class="mt-5 col-md-5 round tpercentc tasklist'+data.keyresultid+'" id="tpercentc' + data.id + '" value= 0 data-id= ' + data.id + '  min=0 max=100 onkeyup="getTotalTask('+data.keyresultid+')">'
                        task += '</div><div class ="col-md-3"><a class="mt-5"><span class="dot mt-5"></span> ' + tdata.taskname + '</a></div>'
                        task += '<a class="col-md-1 col-sm-1" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true"><i class="fa fa-bars"></i></a><div class="dropdown-menu dropdown-menu-right min-width-200 " aria-labelledby="page-header-user-dropdown" style="position: absolute; transform: translate3d(-103px, 34px, 0px); top: 0px; left: 0px; will-change: transform;" x-placement="bottom-end">'
                        task += '<a class="dropdown-item d-flex align-items-center justify-content-between editweeklytask"  data-id= ' + data.id + ' >Edit</a><a class="dropdown-item deletewtask" data-id= ' + data.id + '>Delete</a></div></div></div>'
                        $("#addtaskblock" +id).remove();
                        $("#addweeklytask" +id).removeClass('ancdisabled');
                        jQuery("#tasks-list" + data.keyresultid).append(task);
                        document.getElementById("selectallb").removeAttribute("hidden");
                        document.getElementById("tasktotal" + data.keyresultid).removeAttribute("hidden")
                    });
                }
                
                    
         

            },
            error: function(data) {
                console.log("Error:", data.responseText);
            },
        });
         
     });
      jQuery("body").on("click", ".wsubtaskadd", function() {
          var id = $(this).val();
         
             $.ajaxSetup({
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
        });
        var formData = {
            subtask_name: jQuery("#subtaskvalue" + id).val(),

            taskid: id,
        };
        console.log(formData);

        var type = "POST";

        var ajaxurl = baseurl + "subtasks";

        $.ajax({
            type: type,
            url: ajaxurl,
            data: formData,
            dataType: "json",
            success: function(data) {
                console.log(data);
                $.get(baseurl + "tasks/" + id, function(tdata) {
                    var val = parseInt($("#subtaskno" + tdata.keyresultid).text())
                    $("#subtaskno" + tdata.keyresultid).text(val + 1);
                });
                console.log(data);
                var task = '<div class="container" id="subtaskl' + data.id + '"><div class="row"><div class="col-md-5"><label class="css-control-sm css-control-info css-checkbox css-checkbox-rounded" ><input type="checkbox" class="css-control-input" name="subtaskcheck[]" value= "' + data.id + '" checked id="subtaskcheckv' + data.id + '">'
                task += '<span class="css-control-indicator"></span>' + data.subtask_name + '</label></div><p class="mb-5 col-md-1"><a class="font-w600" href="javascript:void(0)"></p><a class="col-md-1 col-sm-1" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true" style="margin-left: 23em;"><i class="fa fa-bars"></i></a>'
                task += '<div class="dropdown-menu dropdown-menu-right min-width-200" aria-labelledby="page-header-user-dropdown" style="position: absolute; transform: translate3d(-103px, 34px, 0px); top: 0px; left: 0px; will-change: transform;" x-placement="bottom-end"><a class="dropdown-item d-flex align-items-center justify-content-between editdailysubtask" data-id=' + data.id + '>Edit</a>'
                task += '<a class="dropdown-item deletedsubtask" data-id=' + data.id + '>Delete</a></div></div></div>'
                jQuery("#subtasks-list" + data.taskid).append(task);
                $("#addsubtaskblock" +id).remove();
                $("#adddailysubtask" +id).removeClass('ancdisabled');
                document.getElementById("taskcheckc" + id).checked = false;
            },
            error: function(data) {
                console.log("Error:", data.responseText);
            },
        });
         
     });
      jQuery("body").on("click", ".wsubtaskedit", function() {
          var id = $(this).val();
          var checked = $(this).data('checked') == '1'? "checked":"";
        
             $.ajaxSetup({
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
        });
        var formData = {
            id: id,
            subtask_name: jQuery("#editsubtaskvalue" + id).val(),

        };
        console.log(formData);
            
        var type = "PUT";

        var ajaxurl = baseurl + "subtasks/" + id;

        $.ajax({
            type: type,
            url: ajaxurl,
            data: formData,
            dataType: "json",
            success: function(data) {
             

               
                  
                   var task = '<div class="container" id="subtaskl' + data.id + '"><div class="row"><div class="col-md-5"><label class="css-control-sm css-control-info css-checkbox css-checkbox-rounded" ><input type="checkbox" class="css-control-input" name="subtaskcheck[]" value= "' + data.id + '" checked id="subtaskcheckv' + data.id + '">'
                task += '<span class="css-control-indicator"></span>' + data.subtask_name + '</label></div><p class="mb-5 col-md-1"><a class="font-w600" href="javascript:void(0)"></p><a class="col-md-1 col-sm-1" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true" style="margin-left: 23em;"><i class="fa fa-bars"></i></a>'
                task += '<div class="dropdown-menu dropdown-menu-right min-width-200" aria-labelledby="page-header-user-dropdown" style="position: absolute; transform: translate3d(-103px, 34px, 0px); top: 0px; left: 0px; will-change: transform;" x-placement="bottom-end"><a class="dropdown-item d-flex align-items-center justify-content-between editdailysubtask" data-id=' + data.id + '>Edit</a>'
                task += '<a class="dropdown-item deletedsubtask" data-id=' + data.id + '>Delete</a></div></div></div>'
                        
                         $("#editsubtaskblock" +id).replaceWith(task);

                    
                   
                
                
                    
         
 
            },
            error: function(data) {
                console.log("Error:", data.responseText);
            },
        });
      })
      
       jQuery("body").on("click", ".deleteteammember", function() {
        var task_id = $(this).data("id");
        // $("#deletetaskbutton").val($(this).data('id'))

        jQuery("#deleteMembermodal").modal("show");

        $.get(baseurl + "members/" + task_id, function(data) {
            jQuery("#deletememberlabel").html(
                "Are you sure you want to remove <em>" +
                data.fname + " " + data.lname +
                "</em> from this team?"
            );
            jQuery("#tmember-delete").val(data.id);
        });
    });
     jQuery("body").on("click", "#tmember-delete", function() {
    
        var task_id = $(this).val();
           
        $.ajaxSetup({
            headers: {
                "X-CSRF-TOKEN": jQuery('meta[name="csrf-token"]').attr(
                    "content"
                ),
            },
        });
        $.ajax({
            type: "DELETE",
            url: baseurl + "members/" + task_id,
            success: function(data) {
                
              
                
                $("#team-m" + data.id).remove();
                jQuery("#deleteMembermodal").modal("hide");
                 
               
                
            },
            error: function(data) {
                console.log("Error:", data);
            },
        });
    }); 
      
    jQuery("body").on("click", ".deletewtask", function() {
        var task_id = $(this).data("id");
        // $("#deletetaskbutton").val($(this).data('id'))

        jQuery("#deleteweektask").modal("show");

        $.get(baseurl + "tasks/" + task_id, function(data) {
            jQuery("#deletewlabel").html(
                "Are you sure you want to delete the task: <em>" +
                data.taskname +
                "</em>?"
            );
            jQuery("#taskweekdelete").val(data.id);
        });
    });
    jQuery("body").on("click", "#taskweekdelete", function() {
    
        var task_id = $(this).val();
           
        $.ajaxSetup({
            headers: {
                "X-CSRF-TOKEN": jQuery('meta[name="csrf-token"]').attr(
                    "content"
                ),
            },
        });
        $.ajax({
            type: "DELETE",
            url: baseurl + "tasks/" + task_id,
            success: function(data) {
                 if(typeof data.id != 'undefined' ){
                     
                 
                var val = parseInt($("#taskno" + data.keyresultid).text())
                $("#taskno" + data.keyresultid).text(val - 1);
                }
                else{
                      var val = parseInt($("#taskno" + data).text())
                $("#taskno" + data).text(val - 1);
                }
                $("#taskl" + task_id).remove();
                jQuery("#deleteweektask").modal("hide");
                 
               
                
            },
            error: function(data) {
                console.log("Error:", data);
            },
        });
    });
    jQuery("body").on("click", ".deletedsubtask", function() {
        var task_id = $(this).data("id");
        // $("#deletetaskbutton").val($(this).data('id'))
       
        jQuery("#deletedaysubtask").modal("show");

        $.get(baseurl + "subtasks/" + task_id, function(data) {
            jQuery("#deletestlabel").html(
                "Are you sure you want to delete the sub task: <em>" +
                data.subtask_name +
                "</em>?"
            );
            jQuery("#subtaskdaydelete").val(data.id);
        });
    });
    
      jQuery("body").on("click", "#subtaskdaydelete", function() {
    
        var task_id = $(this).val();
           
        $.ajaxSetup({
            headers: {
                "X-CSRF-TOKEN": jQuery('meta[name="csrf-token"]').attr(
                    "content"
                ),
            },
        });
        $.ajax({
            type: "DELETE",
            url: baseurl + "subtasks/" + task_id,
            success: function(data) {
                 
                var val = parseInt($("#subtaskno" + data).text())
                $("#subtaskno" + data).text(val - 1);

                $("#subtaskl" + task_id).remove();
                jQuery("#deletedaysubtask").modal("hide");
                 
               
                
            },
            error: function(data) {
                console.log("Error:", data);
            },
        });
    });
    jQuery("body").on("click", ".deleteweeklyplan", function() {
        var pl_id = $(this).data("id");
        // $("#deletetaskbutton").val($(this).data('id'))

        jQuery("#deleteweeklyplan").modal("show");

            document.getElementById('wplandelete').value = pl_id


    });
    //delete a keyresult
    jQuery("#wplandelete").click(function() {
        var pl_id = $(this).val();

        $.ajaxSetup({
            headers: {
                "X-CSRF-TOKEN": jQuery('meta[name="csrf-token"]').attr(
                    "content"
                ),
            },
        });
        $.ajax({
            type: "DELETE",
            url: baseurl + "teamweeklyplan/" + pl_id + "/delete",
            success: function(data) {
                $("#weekplandisplay" + pl_id).remove();



                jQuery("#deleteweeklyplan").modal("hide");
            },
            error: function(data) {
                console.log("Error:", data);
            },
        });
    });
 $("#submitweeklyplan").submit(function(e) {
     e.preventDefault();
      var checkboxes = document.querySelectorAll('input[name="taskcheck[]"]:checked')
      $("#percentc" + count).removeClass('errorinput');
       var valueArr = [];
       var keyresultids = [];
       var count =0
          document.querySelectorAll('.percentc').forEach(function(el){
              var id = el.dataset.id
             
              if(  parseFloat(getTotalTask(id)) > 0 && parseFloat(getTotalTask(id)) != parseFloat(el.value)){
                   count = id;
              }
            valueArr.push(el.value);
             keyresultids.push(id);
          });
     
      if(checkboxes.length > 0 && count == 0){
          
          var sum =0;
          for (var checkbox of checkboxes) {
                var percent = parseFloat(document.getElementById('tpercentc' + checkbox.value).value);
                if(percent == 0){
                  count = -1
                }
                sum += percent;
                console.log(document.getElementById('tpercentc' + checkbox.value).value)
                console.log(checkbox.value + ' ');
        }
        console.log(sum)
        if(sum != 100){
             var error = '<div class="alert alert-danger" role="alert">Error! Wrong percent calculation</div>'
           $("#weeklyplanerrorbod").html(error);
        }
        else{
              $("#weeklyplanerrorbod").html("");
              this.submit();
        }
      }
      else if(count == -1){
          var error = '<div class="alert alert-danger" role="alert">Error! Percent of a selected task cannot be zero</div>'
                    $("#weeklyplanerrorbod").html(error);
      }
      else if (count != 0){
           var error = '<div class="alert alert-danger" role="alert">Error! Wrong percent calculation</div>'
                    $("#weeklyplanerrorbod").html(error);
                   
      }
      else{
           var error = '<div class="alert alert-danger" role="alert">Error! No task selected</div>'
           $("#weeklyplanerrorbod").html(error);
      }
     
    console.log(checkboxes)
     
 });

 $("#submiteditweeklyplan").submit(function(e) {
     e.preventDefault();
      var checkboxes = document.querySelectorAll('input[name="taskcheck[]"]:checked')
      $("#percentc" + count).removeClass('errorinput');
       var valueArr = [];
       var keyresultids = [];
       var count =0
          document.querySelectorAll('.percentc').forEach(function(el){
              var id = el.dataset.id
             
              if(  parseFloat(getTotalTask(id)) > 0 && parseFloat(getTotalTask(id)) != parseFloat(el.value)){
                   count = id;
              }
            valueArr.push(el.value);
             keyresultids.push(id);
          });
     
      if(checkboxes.length > 0 && count == 0){
          
          var sum =0;
          for (var checkbox of checkboxes) {
                var percent = parseFloat(document.getElementById('tpercentc' + checkbox.value).value);
                sum += percent;
                console.log(document.getElementById('tpercentc' + checkbox.value).value)
                console.log(checkbox.value + ' ');
        }
        console.log(sum)
        if(sum != 100){
             var error = '<div class="alert alert-danger" role="alert">Error! Wrong percent calculation</div>'
           $("#weeklyplanerrorbod").html(error);
        }
        else{
              $("#weeklyplanerrorbod").html("");
              this.submit();
        }
      }
      else if (count != 0){
           var error = '<div class="alert alert-danger" role="alert">Error! Wrong percent calculation</div>'
                    $("#weeklyplanerrorbod").html(error);
                    $("#percentc" + count).addClass('errorinput');
      }
      else{
           var error = '<div class="alert alert-danger" role="alert">Error! No task selected</div>'
           $("#weeklyplanerrorbod").html(error);
      }
     
    console.log(checkboxes)
     
 });


    //daily plan
    jQuery("body").on("click", ".subtaskadd", function() {

        var id = $(this).data('task');
        jQuery("#addsubtask" + id).modal("hide")
        $.ajaxSetup({
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
        });


        var formData = {
            subtask_name: jQuery("#subtaskvalue" + id).val(),

            taskid: id,
        };
        console.log(formData);

        var type = "POST";

        var ajaxurl = baseurl + "subtasks";

        $.ajax({
            type: type,
            url: ajaxurl,
            data: formData,
            dataType: "json",
            success: function(data) {
                $.get(baseurl + "tasks/" + id, function(tdata) {
                    var val = parseInt($("#subtaskno" + tdata.keyresultid).text())
                    $("#subtaskno" + tdata.keyresultid).text(val + 1);
                });
                console.log(data);
                var task = '<div class="container" id="subtaskl' + data.id + '"><div class="row"><div class="col-md-5"><label class="css-control-sm css-control-info css-checkbox css-checkbox-rounded"><input type="checkbox" class="css-control-input" name="subtaskcheck[]" value= "' + data.id + '">'
                task += '<span class="css-control-indicator"></span>' + data.subtask_name + '</label></div><p class="mb-5 col-md-1"><a class="font-w600" href="javascript:void(0)"></p><a class="col-md-1 col-sm-1" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true" style="margin-left: 23em;"><i class="fa fa-bars"></i></a>'
                task += '<div class="dropdown-menu dropdown-menu-right min-width-200" aria-labelledby="page-header-user-dropdown" style="position: absolute; transform: translate3d(-103px, 34px, 0px); top: 0px; left: 0px; will-change: transform;" x-placement="bottom-end"><a class="dropdown-item d-flex align-items-center justify-content-between" data-toggle="modal" data-target="#editsubtask' + data.id + '">Edit</a>'
                task += '<a class="dropdown-item" data-toggle="modal" data-target="#deletesubtask' + data.id + '">Delete</a></div></div></div>'
                jQuery("#subtasks-list" + data.taskid).append(task);

                jQuery("#addtasktokrform").trigger("reset");
                 document.getElementById("krclbutton").click();
            },
            error: function(data) {
                console.log("Error:", data.responseText);
            },
        });
    });
    //edit subtask

    jQuery("body").on("click", ".subtaskedit", function() {
        var id = $(this).data('id');
        jQuery("#editsubtask" + id).modal("hide")
        $.ajaxSetup({
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
        });


        var formData = {
            id: id,
            subtask_name: jQuery("#esubtaskvalue" + id).val(),

        };
        console.log(formData);

        var type = "PUT";

        var ajaxurl = baseurl + "subtasks/" + formData.id;

        $.ajax({
            type: type,
            url: ajaxurl,
            data: formData,
            dataType: "json",
            success: function(data) {
                console.log(data);
                var task = '<div class="container" id="subtaskl' + data.id + '"><div class="row"><div class="col-md-5"><label class="css-control-sm css-control-info css-checkbox css-checkbox-rounded"><input type="checkbox" class="css-control-input" name="subtaskcheck[]" value= "' + data.id + '">'
                task += '<span class="css-control-indicator"></span>' + data.subtask_name + '</label></div><p class="mb-5 col-md-1"><a class="font-w600" href="javascript:void(0)"></p><a class="col-md-1 col-sm-1" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true" style="margin-left: 23em;"><i class="fa fa-bars"></i></a>'
                task += '<div class="dropdown-menu dropdown-menu-right min-width-200" aria-labelledby="page-header-user-dropdown" style="position: absolute; transform: translate3d(-103px, 34px, 0px); top: 0px; left: 0px; will-change: transform;" x-placement="bottom-end"><a class="dropdown-item d-flex align-items-center justify-content-between" data-toggle="modal" data-target="#editsubtask' + data.id + '">Edit</a>'
                task += '<a class="dropdown-item" data-toggle="modal" data-target="#deletesubtask' + data.id + '">Delete</a></div></div></div>'
                jQuery("#subtaskl" + data.id).replaceWith(task);

                jQuery("#editsubtaskmodalFormData").trigger("reset");
            },
            error: function(data) {
                console.log("Error:", data.responseText);
            },
        });
    });
    //deletesubtask
    jQuery("body").on("click", ".subtaskdelete", function() {
        var id = $(this).data('id')



        $.ajaxSetup({
            headers: {
                "X-CSRF-TOKEN": jQuery('meta[name="csrf-token"]').attr(
                    "content"
                ),
            },
        });
        $.ajax({
            type: "DELETE",
            url: baseurl + "subtasks/" + id,
            success: function(data) {

                var val = parseInt($("#subtaskno" + data).text())
                $("#subtaskno" + data).text(val - 1);

                $("#subtaskl" + id).remove();
                jQuery("#deletesubtask" + id).modal("hide");
            },
            error: function(data) {
                console.log("Error:", data);
            },
        });
    });


    jQuery("body").on("click", ".addweekplanbutton", function() {
        $(this).prop("hidden", true);
        $(".addweeklancard").removeAttr("hidden");
    });
    jQuery("body").on("click", ".cancelweeklyplan", function() {
        $(".addweekplanbutton").removeAttr("hidden");
        $(".addweeklancard").prop("hidden", true);
    });
    jQuery("body").on("click", ".adddailyplanbutton", function() {
        $(this).prop("hidden", true);
        $(".adddailycard").removeAttr("hidden");
    });
    jQuery("body").on("click", ".canceldayplan", function() {
        $(".adddailyplanbutton").removeAttr("hidden");
        $(".adddailycard").prop("hidden", true);
    });
    jQuery("body").on("click", ".adddailyreportbutton", function() {
        $(this).prop("hidden", true);
        $(".addreportcard").removeAttr("hidden");
    });
    jQuery("body").on("click", ".cancelreportplan", function() {
        $(".adddailyreportbutton").removeAttr("hidden");
        $(".addreportcard").prop("hidden", true);
    });
    // $("input[name='taskcheck']").on('change', function() {
    //     console.log("hey")
    //     $(".submitweekplan").prop('disabled', !$("input[name='taskcheck']:checked").length);
    // })

    //add comment
    jQuery("body").on("click", "#addComment", function() {
        $.ajaxSetup({
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
        });
        console.log($("#commentbody").val())

        var type = $(this).data("type");
        formData = [];
        if (type == "plan") {
            formData = {
                plan_id: $(this).data("id"),
                comment: $("#commentbody").val(),
            };

        } else if (type == "report") {
            formData = {
                report_id: $(this).data("id"),
                comment: $("#commentbody").val(),
            };
        } else if (type == "project") {
            formData = {
                project_id: $(this).data("id"),
                comment: $("#commentbody").val(),
            };

        }

        console.log(formData);

        var type = "POST";

        var ajaxurl = baseurl + "comments";

        $.ajax({
            type: type,
            url: ajaxurl,
            data: formData,
            dataType: "json",
            success: function(data) {
                console.log(data);
                var comment =
                    '<div class="media mb-15"><div class="media-body mx-5 my-5"><div class="row"><p class="mb-5 col-md-11"><a class="font-w600" href="javascript:void(0)">' +
                    data.fname +
                    " " +
                    data.lname +
                    "</a> &nbsp; <label id='commentdata"+data.id+"'>" 
                    data.comment +
                    "</p>";
                comment +=
                    '<a class="col-md-1" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true"><i class="fa fa-bars"></i></a><div class="dropdown-menu dropdown-menu-right min-width-200 " aria-labelledby="page-header-user-dropdown" style="position: absolute; transform: translate3d(-103px, 34px, 0px); top: 0px; left: 0px; will-change: transform;" x-placement="bottom-end">';
                comment +=
                    '<a class="dropdown-item d-flex align-items-center justify-content-between editcomment" href="#" data-id = ' +
                    data.id +
                    ">Edit</a>";
                comment +=
                    '<a class="dropdown-item deletecomment" href="#" data-id = ' +
                    data.id +
                    ">Delete</a></div></div></div></div>";

                jQuery(".commentarea").append(comment);

                $("#commentbody").val(" ");
            },
            error: function(data) {
                console.log("Error:", data.responseText);
            },
        });
    });
     jQuery("body").on("click", ".editComment", function() {
       
        var id = $(this).data("id");
        console.log(id)
        var token = $('meta[name="csrf-token"]').attr("content")
        var comment = $("#commenttext"+ $(this).data("id")).text()
       console.log (comment)
       var url = baseurl + 'comments/'+ id
       
        value = '<div class="media mb-30 col-md-12"><div class="media-body"><form action="'+url+'" method="post"> <input type="hidden" name="_token" value="'+ token+'" /> <input type="hidden" name="_method" value="put" /><textarea class="form-control mb-5 tribute-demo-input " contenteditable="true" rows="5"  id="commentbody" name = "comment">'+comment+'</textarea>'
        value += '<button type="submit" class="btn btn-rounded btn-outline-secondary" data-id="{{$plan->id}}" data-type={{$type}} onclick="Codebase.loader("show", "bg-gd-sea");setTimeout(function () { Codebase.loader("hide"); });">Edit comment</button> <button type="button" class="btn btn-rounded btn-outline-danger" onclick = "location.reload(); Codebase.loader("show", "bg-gd-sea");setTimeout(function () { Codebase.loader("hide"); });">Discard changes</button></form></div></div>'
        //document.getElementById('commentarea' + id).innerHTML = value;
      $('#commentarea' + id).replaceWith(value)
      
    });

    //add Kpi
    $("#KpimodalFormData").submit(function(e) {
        jQuery("#KpiModal").modal("hide");
        $.ajaxSetup({
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
        });
        e.preventDefault();
        var formData = {
            Kpi_name: document.getElementById("kpi_name").value,
            department_id: $("#department_name")
                .children("option:selected")
                .val(),
        };

        var type = "POST";

        var ajaxurl = baseurl + "kpis";

        $.ajax({
            type: type,
            url: ajaxurl,
            data: formData,
            dataType: "json",
            success: function(data) {
                var kpi =
                    '<div class="col-xl-4 " id="kpilist' +
                    data.id +
                    '"><div class="block "><div class="block-header block-header-default"><div class="block-options"><div class="btn-group show" role="group"><button type="button" class="btn btn-rounded btn-dual-secondary" id="page-header-user-dropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">';
                kpi +=
                    '<i class="fa fa-user d-sm-none"></i><span class="d-none d-sm-inline-block"> <i class="fa fa-bars"></i></span><i class="fa fa-angle-down ml-5"></i></button><div class="dropdown-menu dropdown-menu-right min-width-200 " aria-labelledby="page-header-user-dropdown" style="position: absolute; transform: translate3d(-103px, 34px, 0px); top: 0px; left: 0px; will-change: transform;" x-placement="bottom-end">';
                kpi +=
                    '<a class="dropdown-item d-flex align-items-center justify-content-between editkpi" href="#" data-id = ' +
                    data.id +
                    '>Edit</a><a class="dropdown-item deletekpi" data-id = ' +
                    data.id +
                    ' href="#">Delete</a></div></div></div></div>';
                var route = "/addkpi/" + data.id;
                kpi +=
                    '<div class="block-content block-content-full clickable-field" data-href =' +
                    route +
                    '><h1 class="text-left">' +
                    data.Kpi_name +
                    "</h1> </div></div></div>";
                $(".kpidata").append(kpi);
                jQuery("#KpimodalFormData").trigger("reset");
            },
            error: function(data) {
                console.log("Error:", data.responseText);
            },
        });
    });

    //launch edit kpi modal

    jQuery("body").on("click", ".editkpi", function() {
        var kpi_id = $(this).data("id");
        $.get(baseurl + "kpis/" + kpi_id + "/edit", function(data) {
            jQuery("#edit_kpi_name").val(data.Kpi_name);
            $(
                "#edit_department_name option[value=" + data.department_id + "]"
            ).attr("selected", "selected");
            jQuery("#editkpibutton").val(data.id);
        });

        jQuery("#editKpiModal").modal("show");
    });

    //edit kpi

    $("#editKpimodalFormData").submit(function(e) {
        var kpi_id = jQuery("#editkpibutton").val();
        e.preventDefault();

        jQuery("#editKpiModal").modal("hide");
        $.ajaxSetup({
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
        });
        var formData = {
            Kpi_name: jQuery("#edit_kpi_name").val(),
            department_id: $("#edit_department_name")
                .children("option:selected")
                .val(),
        };

        var type = "PUT";

        var ajaxurl = baseurl + "kpis/" + kpi_id;

        $.ajax({
            type: type,
            url: ajaxurl,
            data: formData,
            dataType: "json",
            success: function(data) {
                var kpi =
                    '<div class="col-xl-4 " id="kpilist' +
                    data.id +
                    '"><div class="block "><div class="block-header block-header-default"><div class="block-options"><div class="btn-group show" role="group"><button type="button" class="btn btn-rounded btn-dual-secondary" id="page-header-user-dropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">';
                kpi +=
                    '<i class="fa fa-user d-sm-none"></i><span class="d-none d-sm-inline-block"> <i class="fa fa-bars"></i></span><i class="fa fa-angle-down ml-5"></i></button><div class="dropdown-menu dropdown-menu-right min-width-200 " aria-labelledby="page-header-user-dropdown" style="position: absolute; transform: translate3d(-103px, 34px, 0px); top: 0px; left: 0px; will-change: transform;" x-placement="bottom-end">';
                kpi +=
                    '<a class="dropdown-item d-flex align-items-center justify-content-between editkpi" href="#" data-id = ' +
                    data.id +
                    '>Edit</a><a class="dropdown-item deletekpi" data-id = ' +
                    data.id +
                    ' href="#">Delete</a></div></div></div></div>';
                var route = "/addkpi/" + data.id;
                kpi +=
                    '<div class="block-content block-content-full clickable-field" data-href =' +
                    route +
                    '><h1 class="text-left">' +
                    data.Kpi_name +
                    "</h1></div></div></div>";
                $("#kpilist" + data.id).replaceWith(kpi);
                jQuery("#KpimodalFormData").trigger("reset");
            },
            error: function(data) {
                console.log("Error:", data.responseText);
            },
        });
    });

    // launch add objective modal
    jQuery("body").on("click", "#addObjective", function() {
        jQuery("#addobjbutton").val($(this).val());
        if (document.getElementById("ownerlist")) {
              var baseurl = window.location.protocol + "//" + window.location.host + '/pms/';
            $.get(baseurl + "allusers", function(data) {
                var users_data = [];
                var id_data = [];
                for (var i = 0; i < data.length; i++) {
                    users_data[i] = data[i].fname + " " + data[i].lname;
                    id_data[i] = data[i].id;
                }
                autocomplete(
                    document.getElementById("ownerlist"),
                    users_data,
                    id_data
                );
            });
        }
        jQuery("#exampleModal3").modal("show");
    });
    jQuery("body").on("click", "#addb", function() {
        jQuery("#KpiModal").modal("show");
    });
    jQuery("body").on("click", "#addsubcriteria", function() {
        var count = parseInt(
            document.getElementById("subcriteriadata").dataset.count
        );
        count = count + 1;
        document.getElementById("subcriteriadata").dataset.count = count;
        var sub =
            '<div class="form-group row" id = "criteriadiv' +
            count +
            '"><div class="col-md-10"><input type="text" class="form-control" id="example-text-input" name="sub_criteria' +
            count +
            '"  placeholder="Sub-criteria"></div>';
        sub +=
            '<div class="col-1 text-center mt-5"><a href="#" class="deletesubc" id="deletesubcriteria' +
            count +
            '" data-count = ' +
            count +
            '><i class="fa fa-minus text-danger" aria-hidden="true"></i></a></div></div>';

        jQuery(".subcriteriadata").append(sub);
    });
    jQuery("body").on("click", ".deletesubc", function() {
        var count = $(this).data("count");
        console.log(count);

        jQuery("#criteriadiv" + count).remove();
    });

    //launch the delete kpi modal
    jQuery("body").on("click", ".deletefkpi", function() {
        var fkpi_id = $(this).data("id");
        console.log(fkpi_id);

        jQuery("#deletefilledkpimodal").modal("show");

        $.get(baseurl + "filledkpi/" + fkpi_id, function(data) {
            jQuery("#deletefilledlabel").text(
                "Are you sure you want to delete KPI filled for: " +
                data.fname +
                " " +
                data.lname +
                "?"
            );
            jQuery("#fkpi-delete").val(fkpi_id);
        });
    });
    //delete a kpi
    jQuery("#fkpi-delete").click(function() {
        var kpi_id = $(this).val();

        $.ajaxSetup({
            headers: {
                "X-CSRF-TOKEN": jQuery('meta[name="csrf-token"]').attr(
                    "content"
                ),
            },
        });
        $.ajax({
            type: "DELETE",
            url: baseurl + "scorekpis/" + kpi_id,
            success: function(data) {
                $("#filleddata" + kpi_id).remove();
                jQuery("#deletefilledkpimodal").modal("hide");
            },
            error: function(data) {
                console.log("Error:", data);
            },
        });
    });
    jQuery("body").on("click", "#clear", function() {
        $("#formula").text("");
    });
    jQuery("body").on("click", "#backspace", function() {
        values = $("#formula").text();
        values = values.slice(0, -1);

        valuesarr = values.split(" ");
        valuesarr.pop();

        var items = "";
        valuesarr.forEach(function(item) {
            items += item + " ";
        });
        $("#formula").text(items);
    });
    jQuery("body").on("click", "#addNeutralComment", function() {
        $.ajaxSetup({
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
        });

        var type = $(this).data("type");
        formData = [];
        if (type == "plan") {
            formData = {
                plan_id: $(this).data("id"),
                comment: "Noted",
            };
        } else if (type == "report") {
            formData = {
                report_id: $(this).data("id"),
                comment: "Noted",
            };
        } else if (type == "project") {
            formData = {
                project_id: $(this).data("id"),
                comment: "Noted",
            };

        }

        console.log(formData);

        var type = "POST";

        var ajaxurl = baseurl + "comments";

        $.ajax({
            type: type,
            url: ajaxurl,
            data: formData,
            dataType: "json",
            success: function(data) {
                console.log(data);
                var comment =
                    '<div class="media mb-15"><div class="media-body mx-5 my-5"><div class="row"><p class="mb-5 col-md-11"><a class="font-w600" href="javascript:void(0)">' +
                    data.fname +
                    " " +
                    data.lname +
                    "</a> &nbsp;" +
                    data.comment +
                    "</p>";
                comment +=
                    '<a class="col-md-1" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true"><i class="fa fa-bars"></i></a><div class="dropdown-menu dropdown-menu-right min-width-200 " aria-labelledby="page-header-user-dropdown" style="position: absolute; transform: translate3d(-103px, 34px, 0px); top: 0px; left: 0px; will-change: transform;" x-placement="bottom-end">';
                comment +=
                    '<a class="dropdown-item d-flex align-items-center justify-content-between editcomment" href="#" data-id = ' +
                    data.id +
                    ">Edit</a>";
                comment +=
                    '<a class="dropdown-item deletecomment" href="#" data-id = ' +
                    data.id +
                    ">Delete</a></div></div></div></div>";

                jQuery(".commentarea").append(comment);

                $("#commentbody").val(" ");
            },
            error: function(data) {
                console.log("Error:", data.responseText);
            },
        });
    });

    jQuery("body").on("click", "#addFormula", function() {
        values = $("#formula").text();
        values = values.slice(0, -1);

        valuesarr = values.split(" ");
        // if (valuesarr[0] == "+" || valuesarr[0] == "-" || valuesarr[0] == "÷" || valuesarr[0] == "x" || valuesarr[0] == ")" || valuesarr[valuesarr.length - 1] == "(" || valuesarr[valuesarr.length - 1] == "x" || valuesarr[valuesarr.length - 1] == "+" || valuesarr[valuesarr.length - 1] == "-" || valuesarr[valuesarr.length - 1] == "÷") {
        //     $("#errormessage").text("Invalid formula")
        // }

        (actual = 1), (target = 2), (weight = 3);
        var err = 0;
        for (var i = 0; i < valuesarr.length; i++) {
            if (valuesarr[i] == "actual") {
                if (
                    (valuesarr[i + 1] >= "0" && valuesarr[i + 1] <= "9") ||
                    (valuesarr[i] != 0 &&
                        valuesarr[i - 1] >= "0" &&
                        valuesarr[i - 1] <= "9")
                ) {
                    err = 1;
                }
                valuesarr[i] = actual;
            } else if (valuesarr[i] == "target") {
                if (
                    (valuesarr[i + 1] >= "0" && valuesarr[i + 1] <= "9") ||
                    (valuesarr[i] != 0 &&
                        valuesarr[i - 1] >= "0" &&
                        valuesarr[i - 1] <= "9")
                ) {
                    err = 1;
                }
                valuesarr[i] = target;
            } else if (valuesarr[i] == "weight") {
                if (
                    (valuesarr[i + 1] >= "0" && valuesarr[i + 1] <= "9") ||
                    (valuesarr[i] != 0 &&
                        valuesarr[i - 1] >= "0" &&
                        valuesarr[i - 1] <= "9")
                ) {
                    err = 1;
                }
                valuesarr[i] = weight;
            } else if (valuesarr[i] == "x") {
                valuesarr[i] = "*";
            } else if (valuesarr[i] == "÷") {
                valuesarr[i] = "/";
            }
        }
        var items = "";
        valuesarr.forEach(function(item) {
            items += item;
        });

        console.log(items);
        var evaluatedValue = "";
        try {
            evaluatedValue = eval(items);
        } catch (e) {
            if (e instanceof SyntaxError) {
                $("#errormessage").text("Invalid formula");
            }
        }

        if (evaluatedValue == "" || err == 1) {
            $("#errormessage").text("Invalid formula");
        } else {
            $("#errormessage").text("");
            console.log(evaluatedValue);
            jQuery("#addFormulaModal").modal("hide");

            $.ajaxSetup({
                headers: {
                    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr(
                        "content"
                    ),
                },
            });
            valuesn = $("#formula").text();
            valuesn = valuesn.slice(0, -1);
            var type = 0;
            if ($("#kpiformula").data("id")) {
                type = 1;
            }
            if ($("#engageformula").data("id")) {
                type = 0;
            }

            var formData = {
                formula: valuesn,
                type: type,
            };

            var type = "POST";

            var ajaxurl = baseurl + "formulas";

            $.ajax({
                type: type,
                url: ajaxurl,
                data: formData,
                dataType: "json",
                success: function(data) {
                    console.log(data);
                },
                error: function(data) {
                    console.log("Error:", data.responseText);
                },
            });
        }
    });

    jQuery("body").on("click", "#addProjectMember", function() {
        var count = parseInt(
            document.getElementById("addProjectMember").dataset.count
        );
        console.log(count);
        count = count + 1;
        document.getElementById("addProjectMember").dataset.count = count;
        document.getElementById("memebercount").value = count;
        var member =
            '<div class="form-group row" id = "memberdiv' +
            count +
            '"><div class="col-md-6"><input type="text" class="form-control"  name="member' +
            count +
            '"  placeholder="Member name" id= "member' +
            count +
            '"></div><div class="col-md-4"><select class="form-control" id="positionlist' +
            count +
            '" name="positionlist' +
            count +
            '"><option disabled>Please select</option><option value="PMOM">PMOM</option><option value="TL">TL</option><option value="FE">FE</option><option value="OFE">OFE</option><option value="PFO">PFO</option></select></div>';
        member +=
            '<div class="col-1 text-center mt-5"><a href="#" class="deletemember" id="deletemember' +
            count +
            '" data-count = ' +
            count +
            '><i class="fa fa-minus text-danger" aria-hidden="true"></i></a></div></div>';

        $(".memberslist").append(member);
          var baseurl = window.location.protocol + "//" + window.location.host + '/pms/';
        $.get(baseurl + "allusers", function(data) {
            var users_data = [];
            var id_data = [];
            for (var i = 0; i < data.length; i++) {
                users_data[i] = data[i].fname + " " + data[i].lname;
                id_data[i] = data[i].id;
            }
            autocomplete(
                document.getElementById("member" + count),
                users_data,
                id_data
            );
        });
    });
    jQuery("body").on("click", ".deletemember", function() {
        var count = $(this).data("count");
        console.log(count);
        var high = parseInt(
            document.getElementById("addProjectMember").dataset.count
        );
        if (high == count) {
            document.getElementById("addProjectMember").dataset.count =
                count - 1;
        }
        jQuery("#memberdiv" + count).remove();
    });
    //Add bid member
    jQuery("body").on("click", "#addbidmember", function () {
        var count = parseInt(
            document.getElementById("addbidmember").dataset.count
        );
        console.log(count);
        count = count + 1;
        document.getElementById("addbidmember").dataset.count = count;
        document.getElementById("memebercount").value = count;
        var member =
            '<div class="form-group row" id = "memberdiv' +
            count +
            '"><div class="col-md-6"><input type="text" class="form-control round"  name="member' +
            count +
            '"  placeholder="Member name" id= "member' +
            count +
            '"></div><div class="col-md-4"><select class="form-control round" id="positionlist' +
            count +
            '" name="positionlist' +
            count +
            '"><option disabled>Please select</option><option value="STL">STL</option><option value="PSE">PSE</option><option value="HoS">HoS</option><option value="SA">SA</option></select></div>';
        member +=
            '<div class="col-1 text-center mt-5"><a href="#" class="deletebidmember" id="deletebidmember' +
            count +
            '" data-count = ' +
            count +
            '><i class="fa fa-minus text-danger" aria-hidden="true"></i></a></div></div>';

        $(".memberslist").append(member);
        var baseurl = window.location.protocol + "//" + window.location.host;
        $.get("allusers", function (data) {
            var users_data = [];
            var id_data = [];
            for (var i = 0; i < data.length; i++) {
                users_data[i] = data[i].fname + " " + data[i].lname;
                id_data[i] = data[i].id;
            }
            autocomplete(
                document.getElementById("member" + count),
                users_data,
                id_data
            );
        });
    });
    //Bid member delete
    jQuery("body").on("click", ".deletebidmember", function () {
        var count = $(this).data("count");
        console.log(count);
        var high = parseInt(
            document.getElementById("addbidmember").dataset.count
        );
        if (high == count) {
            document.getElementById("addbidmember").dataset.count =
                count - 1;
        }
        jQuery("#memberdiv" + count).remove();
    });


    jQuery("body").on("click", ".okrr", function() {

        if (!this.checked) {

            $(".eokr").prop("checked", false)
        } else {

            $(".eokr").prop("checked", true)
        }
    });
    jQuery("body").on("click", ".eokr", function() {

        if (!this.checked) {

            $("input[name='eokr']").prop("checked", false)
        } else {
            $("input[name='eaddSession']")
        }
    });
    jQuery("body").on("click", "input[name='okr']", function() {
        if (!this.checked) {
            $(".okr").prop("checked", false)
        } else {
            $(".okr").prop("checked", true)
        }
    });
    jQuery("body").on("click", ".okr", function() {

        if (!this.checked) {

            $("input[name='okr']").prop("checked", false)
        } else {

        }
    });
    jQuery("body").on("click", ".ehr", function() {

        if (!this.checked) {

            $("input[name='eotherhr']").prop("checked", false)
        } else {

        }
    });

    jQuery("body").on("click", ".otherhrr", function() {
        if (!this.checked) {
            $(".ehr").prop("checked", false)
        } else {
            $(".ehr").prop("checked", true)
        }
    });
    jQuery("body").on("click", "input[name='otherhr']", function() {
        if (!this.checked) {
            $(".hr").prop("checked", false)
        } else {
            $(".hr").prop("checked", true)
        }
    });
    jQuery("body").on("click", ".hr", function() {

        if (!this.checked) {

            $("#otherhr").prop("checked", false)
        } else {

        }
    });

    // jQuery("body").on("change", "#apptype", function() {
    //     if (
    //         $(this)
    //         .children("option:selected")
    //         .val() == 1
    //     ) {
    //         //engagement
    //         document.getElementById("positionchoice").disabled = true;
    //         document.getElementById("perspectivechoice").disabled = true;
    //         document.getElementById("objectivesel").removeAttribute("disabled");
    //         document
    //             .getElementById("detailedreason")
    //             .removeAttribute("disabled");
    //         document.getElementById("cc").removeAttribute("disabled");
    //         $.get(baseurl + "getexcellence", function(data) {
    //             select = document.getElementById("objectivesel");

    //             for (var i = 0; i < data.length; i++) {
    //                 var opt = document.createElement("option");
    //                 opt.value = data[i].id;
    //                 opt.innerHTML = data[i].Objective;
    //                 select.appendChild(opt);
    //             }
    //         });
    //     } else if ($(this)
    //         .children("option:selected")
    //         .val() == 2) {

    //         document.getElementById("perspectivechoice").disabled = true;
    //         document.getElementById("positionchoice").removeAttribute("disabled");

    //         document.getElementById("perspectivechoice").disabled = true;
    //         document.getElementById("objectivesel").disabled = true;
    //         document
    //             .getElementById("detailedreason").disabled = true;
    //         document.getElementById("cc").disabled = true;
    //         $.get(baseurl + "getposition", function(data) {
    //             select = document.getElementById("positionchoice");

    //             for (var i = 0; i < data.length; i++) {
    //                 var opt = document.createElement("option");
    //                 opt.value = data[i].id;
    //                 opt.innerHTML = data[i].position;
    //                 select.appendChild(opt);
    //             }
    //         });
    //     }
    // });
    // jQuery("body").on("change", "#positionchoice", function() {
    //     var kpiid = $(this).val();
    //     document.getElementById("perspectivechoice").removeAttribute("disabled");
    //     $.get(baseurl + "perspectivefromid/" + kpiid, function(data) {
    //         select = document.getElementById("perspectivechoice");

    //         for (var i = 0; i < data.length; i++) {
    //             var opt = document.createElement("option");
    //             opt.value = data[i].kpi_id;
    //             opt.innerHTML = data[i].perspective;
    //             select.appendChild(opt);
    //         }
    //     });
    // });
    // jQuery("body").on("change", "#perspectivechoice", function() {
    //     var kpiid = $('#perspectivechoice option:selected').val();
    //     var perspective = $('#perspectivechoice option:selected').text();
    //     console.log(kpiid)
    //     console.log(perspective)
    //     var res =kpiid + "m"+ "app";
    //     document.getElementById("objectivesel").removeAttribute("disabled");
    //     $.get(baseurl + "kpiformfromid/" + kpiid, function(data) {
    //         select = document.getElementById("objectivesel");

    //         for (var i = 0; i < data.length; i++) {

    //             if (data[i].perspective == perspective && data[i].type == 1) {
    //                 var opt = document.createElement("option");
    //                 opt.value = data[i].id;
    //                 opt.innerHTML = data[i].objective;
    //                 select.appendChild(opt);
    //             }
    //         }
    //     });
    // });
    // jQuery("body").on("change", "#positionchoicer", function() {
    //     var kpiid = $(this).val();
    //     document.getElementById("perspectivechoicer").removeAttribute("disabled");
    //     $.get(baseurl + "perspectivefromid/" + kpiid, function(data) {
    //         select = document.getElementById("perspectivechoicer");

    //         for (var i = 0; i < data.length; i++) {
    //             var opt = document.createElement("option");
    //             opt.value = data[i].kpi_id;
    //             opt.innerHTML = data[i].perspective;
    //             select.appendChild(opt);
    //         }
    //     });
    // });
    // jQuery("body").on("change", "#perspectivechoicer", function() {
    //     var kpiid = $('#perspectivechoicer option:selected').val();
    //     var perspective = $('#perspectivechoicer option:selected').text();
    //     console.log(kpiid)
    //     console.log(perspective)
        
    //     document.getElementById("objectiveselr").removeAttribute("disabled");
    //     var res =kpiid + "m"+ "rep";
    //     $.get(baseurl + "kpiformfromid/" + kpiid, function(data) {
    //         select = document.getElementById("objectiveselr");

    //         for (var i = 0; i < data.length; i++) {

    //             if (data[i].perspective == perspective && data[i].type == 2) {
    //                 var opt = document.createElement("option");
    //                 opt.value = data[i].id;
    //                 opt.innerHTML = data[i].objective;
    //                 select.appendChild(opt);
    //             }
    //         }
    //     });
    // });
    // jQuery("body").on("change", "#objectivesel", function() {
    //     document
    //         .getElementById("detailedreason")
    //         .removeAttribute("disabled");
    //     document.getElementById("cc").removeAttribute("disabled");
    // });
    //   jQuery("body").on("change", "#objectiveselr", function() {
    //     document.getElementById("detailedreasonr")
    //         .removeAttribute("disabled");
    //     document.getElementById("ccr").removeAttribute("disabled");
    //     document.getElementById("improve").removeAttribute("disabled");
    //     document.getElementById("actionr").removeAttribute("disabled");
    // });

    // jQuery("body").on("change", "#reptype", function() {
    //     console.log("hello");
    //     if (
    //         $(this)
    //         .children("option:selected")
    //         .val() == 1
    //     ) {
    //         //engagement
    //         document.getElementById("positionchoicer").disabled = true;
    //         document.getElementById("perspectivechoicer").disabled = true;
    //         document.getElementById("objectiveselr").removeAttribute("disabled");
    //         document
    //             .getElementById("detailedreasonr")
    //             .removeAttribute("disabled");
    //         document.getElementById("ccr").removeAttribute("disabled");
    //         document.getElementById("improve").removeAttribute("disabled");
    //         document.getElementById("actionr").removeAttribute("disabled");
    //         $.get(baseurl + "getdiscipline", function(data) {
    //             select = document.getElementById("objectiveselr");

    //             for (var i = 0; i < data.length; i++) {
    //                 var opt = document.createElement("option");
    //                 opt.value = data[i].id;
    //                 opt.innerHTML = data[i].Objective;
    //                 select.appendChild(opt);
    //             }
    //         });
    //     } else if ($(this)
    //         .children("option:selected")
    //         .val() == 2) {
    //         console.log("hello");
          
    //         document.getElementById("positionchoicer").removeAttribute("disabled");

    //         document.getElementById("perspectivechoicer").disabled = true;
    //         document.getElementById("objectiveselr").disabled = true;
    //         document
    //             .getElementById("detailedreasonr").disabled = true;
    //         document.getElementById("improve").disabled = true;
    //         document.getElementById("actionr").disabled = true;
    //         document.getElementById("ccr").disabled = true;
    //         $.get(baseurl + "getposition", function(data) {
    //             select = document.getElementById("positionchoicer");

    //             for (var i = 0; i < data.length; i++) {
    //                 var opt = document.createElement("option");
    //                 opt.value = data[i].id;
    //                 opt.innerHTML = data[i].position;
    //                 select.appendChild(opt);
    //             }
    //         });

    //     }
    // });


 jQuery("body").on("click", "#addFailuep", function() {
        var count = parseInt(
            document.getElementById("addFailuep").dataset.count
        );
        console.log(count);
        count = count + 1;
        document.getElementById("addFailuep").dataset.count = count;
        document.getElementById("fmemebercount").value = count;
        var member ='<div class="col-md-6" id="fmemberdiv'+count+'"><select class="form-control round" id="freason'+count+'" name="freason'+count+'"><option disabled>Please select</option><option value="Poor performance of my own team member">Poor performance of my own team member</option><option value="Poor performance of responsible person from another department">Poor performance of responsible person from another department</option><option value="Over ambitious planning">Over ambitious planning</option>'
         member +='<option value="Some targets took more time than planned affecting other targets (poor planning)">Some targets took more time than planned affecting other targets (poor planning)</option><option value="Unplanned urgent tasks">Unplanned urgent tasks</option><option value="Delay from government entity (Customs)">Delay from government entity (Customs)</option><option value="Unavailability of required items/tools">Unavailability of required items/tools</option></select></div>'
         member += '<div class="col-md-2"><input type="number" class="form-control round" id="reasoncount'+count+'" name="reasoncount'+count+'" value=0></div>' 
      
         member +='<div class="col-1 text-center mt-5"><a href="#" class="deletefmember" id="deletefmember' +count +'" data-count = ' +count +'><i class="fa fa-minus text-danger" aria-hidden="true"></i></a></div></div>';

        $(".failurememberslist").append(member);
         
       
    });
   
$(".taskcheck").change(function() {
   var id= $(this).data('mid');
    getTotalTask(id);
})

  jQuery("body").on("click", ".editfailuretarget", function() {
      var id = $(this).val();
       $.get(baseurl + "failures/" + id , function(data){
            document.getElementById("editfailure").value = data.target
            document.getElementById('editfmodalFormData').action = baseurl + 'failures/' + id;
         jQuery("#editFailuremodal").modal("show");
       });
         
     });
      jQuery("body").on("click", ".deletefailuretarget", function() {
     
        var id = $(this).val();
       $.get(baseurl + "failures/" + id , function(data){
            document.getElementById("deletefailurelabel").innerHTML = "Are you sure you want to delete the target: <em>" + data.target + "</em>"
            document.getElementById('deletefmodalFormData').action = baseurl + 'failures/' + id;
         jQuery("#deleteFailuremodal").modal("show");
       });
         
     });
     

     
     
}); //end of jquery
 function substatuscheck(id){
     if(this.checked && this.value == 0){
          $('#subfeedback' + id).prop("required", true)
     }
     else{
          $('#subfeedback' + id).removeAttr("required", true)
     }
 }
 function taskstatuscheck(id){
     if(this.checked && this.value == 0){
          $('#tfeedback' + id).prop("required", true)
     }
     else{
          $('#tfeedback' + id).removeAttr("required", true)
     }
 }

function addVal(id) {
    $("#formula").append($("#" + id).val() + " ");
}

//function for autocomplete search data

function autocomplete(inp, arr, idarr) {
    /*the autocomplete function takes two arguments,
    the text field element and an array of possible autocompleted values:*/
    var currentFocus;
    /*execute a function when someone writes in the text field:*/
    inp.addEventListener("input", function(e) {
        var a,
            b,
            i,
            val = this.value;
        /*close any already open lists of autocompleted values*/
        closeAllLists();
        if (!val) {
            return false;
        }
        currentFocus = -1;
        /*create a DIV element that will contain the items (values):*/
        a = document.createElement("DIV");
        a.setAttribute("id", this.id + "autocomplete-list");
        a.setAttribute("class", "autocomplete-items");
        /*append the DIV element as a child of the autocomplete container:*/
        this.parentNode.appendChild(a);
        /*for each item in the array...*/
        for (i = 0; i < arr.length; i++) {
            /*check if the item starts with the same letters as the text field value:*/

            if (
                arr[i].substr(0, val.length).toUpperCase() == val.toUpperCase()
            ) {
                /*create a DIV element for each matching element:*/
                b = document.createElement("DIV");
                /*make the matching letters bold:*/
                b.innerHTML =
                    "<strong>" + arr[i].substr(0, val.length) + "</strong>";
                b.innerHTML += arr[i].substr(val.length);
                /*insert a input field that will hold the current array item's value:*/
                b.innerHTML +=
                    "<input type='hidden' value='" +
                    arr[i] +
                    "' data-id=" +
                    idarr[i] +
                    ">";
                /*execute a function when someone clicks on the item value (DIV element):*/
                b.addEventListener("click", function(e) {
                    /*insert the value for the autocomplete text field:*/
                    inp.value = this.getElementsByTagName("input")[0].value;
                    inp.dataset.id = this.getElementsByTagName(
                        "input"
                    )[0].dataset.id;
                    /*close the list of autocompleted values,
                    (or any other open lists of autocompleted values:*/
                    closeAllLists();
                });
                a.appendChild(b);
            }
        }
    });
    /*execute a function presses a key on the keyboard:*/
    inp.addEventListener("keydown", function(e) {
        var x = document.getElementById(this.id + "autocomplete-list");
        if (x) x = x.getElementsByTagName("div");
        if (e.keyCode == 40) {
            /*If the arrow DOWN key is pressed,
            increase the currentFocus variable:*/
            currentFocus++;
            /*and and make the current item more visible:*/
            addActive(x);
        } else if (e.keyCode == 38) {
            //up
            /*If the arrow UP key is pressed,
            decrease the currentFocus variable:*/
            currentFocus--;
            /*and and make the current item more visible:*/
            addActive(x);
        } else if (e.keyCode == 13) {
            /*If the ENTER key is pressed, prevent the form from being submitted,*/
            e.preventDefault();
            if (currentFocus > -1) {
                /*and simulate a click on the "active" item:*/
                if (x) x[currentFocus].click();
            }
        }
    });

    function addActive(x) {
        /*a function to classify an item as "active":*/
        if (!x) return false;
        /*start by removing the "active" class on all items:*/
        removeActive(x);
        if (currentFocus >= x.length) currentFocus = 0;
        if (currentFocus < 0) currentFocus = x.length - 1;
        /*add class "autocomplete-active":*/
        x[currentFocus].classList.add("autocomplete-active");
    }

    function removeActive(x) {
        /*a function to remove the "active" class from all autocomplete items:*/
        for (var i = 0; i < x.length; i++) {
            x[i].classList.remove("autocomplete-active");
        }
    }

    function closeAllLists(elmnt) {
        /*close all autocomplete lists in the document,
        except the one passed as an argument:*/
        var x = document.getElementsByClassName("autocomplete-items");
        for (var i = 0; i < x.length; i++) {
            if (elmnt != x[i] && elmnt != inp) {
                x[i].parentNode.removeChild(x[i]);
            }
        }
    }
    /*execute a function when someone clicks in the document:*/
    document.addEventListener("click", function(e) {
        closeAllLists(e.target);
    });
}

/*initiate the autocomplete function on the "myInput" element, and pass along the countries array as possible autocomplete values:*/

function PreviewImage() {
    var oFReader = new FileReader();
    oFReader.readAsDataURL(document.getElementById("profile-settings-avatar").files[0]);

    oFReader.onload = function(oFREvent) {
        if(document.getElementById("initaldiv")){
        document.getElementById("initaldiv").setAttribute("hidden", "hidden");}
        document.getElementById("displayimg").removeAttribute("hidden")
        document.getElementById("displayimg").src = oFREvent.target.result;



    };
};


function toggle(source) {
    
  checkboxes = document.querySelectorAll('.tc' + source);

  for(var i=0, n=checkboxes.length;i<n;i++) {
    checkboxes[i].checked = document.querySelector('.selectall' + source).checked;
    getTotalTask(source);
  }
}
function getTotalKr(){
 var valueArr = [];
 var sum = 0
       var keyresultids = [];
          document.querySelectorAll('.percentc').forEach(function(el){
              var id = el.dataset.id
              sum +=parseFloat(el.value)
            valueArr.push(parseFloat(el.value));
             keyresultids.push(id);
          }); 
          console.log(sum);
          document.getElementById("ktotalvalue").innerHTML  = sum;
    }
function getTotalTask(id){
 
 var sum = 0
      
          document.querySelectorAll('.tasklist' +id).forEach(function(el){
              
              var id = el.dataset.id
              if(document.getElementById('taskcheckv' + id).checked){
                  console.log(el.value)
                  if(el.value === ""){
                     
                  }
                  else{
                      sum +=parseFloat(el.value)
                  }
                      
                  
                    
              }
            
            
          }); 
          console.log(sum);
        document.getElementById("tasktotalvalue"+id).innerHTML  = sum;
        document.getElementById("percentc"+id).value  = sum;
           var sum2 = 0
      
          document.querySelectorAll('.tpercentc').forEach(function(el){
              
              var id = el.dataset.id
              if(document.getElementById('taskcheckv' + id).checked){
                  console.log(el.value =="")
                   if(el.value === ""){
                    
                   }
                   else{
                        sum2 +=parseFloat(el.value)
                   }
                  
              }
            
            
          }); 
          document.getElementById("ktotalvalue").innerHTML  = sum2;
          return sum;
    }
    
    function autocomplete2(inp, arr, idarr, avarr, inp2) {
    /*the autocomplete function takes two arguments,
    the text field element and an array of possible autocompleted values:*/
    var currentFocus;
    /*execute a function when someone writes in the text field:*/
    inp.addEventListener("input", function(e) {
        var a,
            b,
            i,
            val = this.value;
        /*close any already open lists of autocompleted values*/
        closeAllLists();
        if (!val) {
            return false;
        }
        currentFocus = -1;
        /*create a DIV element that will contain the items (values):*/
        a = document.createElement("DIV");
        a.setAttribute("id", this.id + "autocomplete-list");
        a.setAttribute("class", "autocomplete-items");
        /*append the DIV element as a child of the autocomplete container:*/
        this.parentNode.appendChild(a);
        /*for each item in the array...*/
        for (i = 0; i < arr.length; i++) {
            /*check if the item starts with the same letters as the text field value:*/

            if (
                arr[i].substr(0, val.length).toUpperCase() == val.toUpperCase()
            ) {
                /*create a DIV element for each matching element:*/
                b = document.createElement("DIV");
                /*make the matching letters bold:*/
                b.innerHTML =
                    "<strong>" + arr[i].substr(0, val.length) + "</strong>";
                b.innerHTML += arr[i].substr(val.length);
                /*insert a input field that will hold the current array item's value:*/
                b.innerHTML +=
                    "<input type='hidden' value='" +
                    arr[i] +
                    "' data-id=" +
                    idarr[i] +
                    " data-avatar = "+avarr[i]+">";
                /*execute a function when someone clicks on the item value (DIV element):*/
                b.addEventListener("click", function(e) {
                    /*insert the value for the autocomplete text field:*/
                    var res = this.getElementsByTagName("input")[0].value.split(" ")
                    var result =' <a class="float-right col-md-1 " onMouseOver="this.style.color=#dc3545"  onMouseOut="this.style.color=#000" id="removeuser"> <i class=" fa fa-times-circle "></i></a>'
                   
                    inp2.dataset.id = this.getElementsByTagName("input")[0].dataset.id;
                    
                    if(this.getElementsByTagName("input")[0].dataset.avatar == 1){
                       
                        inp2.classList.add('av-circle')
                        inp2.innerHTML = this.getElementsByTagName("input")[0].value + " " + result;
                        inp2.dataset.letters = res[0][0]+res[1][0]
                    }
                    else{
                        var img = '<img src="https://ienetworks.co/pms/uploads/avatars/'+ this.getElementsByTagName("input")[0].dataset.avatar+'" style="width:20px; height:20px; border-radius:20%; -webkit-border-radius: 50%">'
                        inp2.innerHTML =img + " " + this.getElementsByTagName("input")[0].value + " " + result;
                    }
                    document.getElementById('userinput').value = this.getElementsByTagName("input")[0].dataset.id;
                    inp.style.visibility = "hidden";
                    /*close the list of autocompleted values,
                    (or any other open lists of autocompleted values:*/
                    closeAllLists();
                });
                a.appendChild(b);
            }
        }
    });
    /*execute a function presses a key on the keyboard:*/
    inp.addEventListener("keydown", function(e) {
        var x = document.getElementById(this.id + "autocomplete-list");
        if (x) x = x.getElementsByTagName("div");
        if (e.keyCode == 40) {
            /*If the arrow DOWN key is pressed,
            increase the currentFocus variable:*/
            currentFocus++;
            /*and and make the current item more visible:*/
            addActive(x);
        } else if (e.keyCode == 38) {
            //up
            /*If the arrow UP key is pressed,
            decrease the currentFocus variable:*/
            currentFocus--;
            /*and and make the current item more visible:*/
            addActive(x);
        } else if (e.keyCode == 13) {
            /*If the ENTER key is pressed, prevent the form from being submitted,*/
            e.preventDefault();
            if (currentFocus > -1) {
                /*and simulate a click on the "active" item:*/
                if (x) x[currentFocus].click();
            }
        }
    });

    function addActive(x) {
        /*a function to classify an item as "active":*/
        if (!x) return false;
        /*start by removing the "active" class on all items:*/
        removeActive(x);
        if (currentFocus >= x.length) currentFocus = 0;
        if (currentFocus < 0) currentFocus = x.length - 1;
        /*add class "autocomplete-active":*/
        x[currentFocus].classList.add("autocomplete-active");
    }

    function removeActive(x) {
        /*a function to remove the "active" class from all autocomplete items:*/
        for (var i = 0; i < x.length; i++) {
            x[i].classList.remove("autocomplete-active");
        }
    }

    function closeAllLists(elmnt) {
        /*close all autocomplete lists in the document,
        except the one passed as an argument:*/
        var x = document.getElementsByClassName("autocomplete-items");
        for (var i = 0; i < x.length; i++) {
            if (elmnt != x[i] && elmnt != inp) {
                x[i].parentNode.removeChild(x[i]);
            }
        }
    }
    /*execute a function when someone clicks in the document:*/
    document.addEventListener("click", function(e) {
        closeAllLists(e.target);
    });
}

    function addText()
        {
        var div1=document.getElementById("userdiv");
        div1.innerHTML="<input class='form-control round' type='text' placeholder='Search Owner'  id='attachowner' autocomplete='off' />";
        var baseurl = window.location.protocol + "//" + window.location.host + '/pms/';
        $.get(baseurl + "allusers", function(data) {
                var users_data = [];
                var id_data = [];
                var av = []
                for (var i = 0; i < data.length; i++) {
                    users_data[i] = data[i].fname + " " + data[i].lname;
                    id_data[i] = data[i].id;
                    av[i] = data[i].avatar == null ? 1: data[i].avatar
                }
                autocomplete2(
                    document.getElementById("attachowner"),
                    users_data,
                    id_data,
                    av, document.getElementById("ownerlist")
                );
              
            });
        }

function filterteam(id){
      var baseurl = window.location.protocol + "//" + window.location.host + '/pms/';
        var teamid = $("#teamengagement").children("option:selected").val()
        console.log(teamid)
        $.get(baseurl + "teamfilter/"+ teamid +"?sessionid="+id, function(data) {
            var team = ""
            var count = 1;
            
            data.forEach(function(item) {
             
                var stat= item.mostengaged == 1?'background-color:#C9B037;': ''
                
                team += '<tr style ="'+ stat +'"><th class="text-center" scope="row">'+ count++ + '</th><td>'+ item.user+'</td>'
                team+='<td class="d-none d-sm-table-cell">'+item.appcount+'</td><td class="d-none d-sm-table-cell">'+item.repcount+'</td></tr>'
            });

            $("#teamfilterlist").html(team)
        });

  
}

// Top Score Filter
    function avgtopfilter(id) {
     var baseurl = window.location.protocol + "//" + window.location.host + '/pms/';
        var teamid = $("#topscore").children("option:selected").val()
         var type = $("#typescore").children("option:selected").val()
        console.log(teamid)
        $.get("topscore/"+ teamid +"?sessionid="+id + "&type="+type, function(data) {
            console.log(data);
            var team = ""
            var count = 1;
            $tpscore = 0;
            data.forEach(function(item) {
                var message = count < 4? 'table-success':''
                team += '<tr class="'+message+'"><th class="text-center" scope="row">'+ count++ + '</th><td>'+ item.user+'</td>'
                team+='<td class="d-none d-sm-table-cell">'+item.tpscore+'%</td>'
            });
               
        
            $("#topscorelist").html(team)
           
        });
    }
     function launchdriverengage (username, userid, type){
          var baseurl = window.location.protocol + "//" + window.location.host + '/pms/';
       
     if(type == 2){
    
            document.getElementById('engrepuname').innerHTML = username
          document.getElementById('engrepplan').value = userid;
    
           jQuery("#repengmodal").modal('show');
       }
       else{
           document.getElementById('engappuname').innerHTML = username
           
           document.getElementById('engappplan').value = userid;
           jQuery("#appengmodal").modal('show');
       }
           

      
    
       
      }
    function launchprojectReprimandmodal(projectid){
       
          var baseurl = window.location.protocol + "//" + window.location.host + '/pms/';
       
       //if engagement
       if(document.getElementById('typetoggle').checked){
    
           
           document.getElementById('engrepplan').value = projectid;
    
           jQuery("#repengmodal").modal('show');
       }
       //if kpi
       else{
            
            
             document.getElementById('kpirepplan').value = projectid;
            
             
              $.get(baseurl + "getposition", function(data) {
                select = document.getElementById("positionchoicer");
  var length = select.options.length;
                for (i = length-1; i >= 0; i--) {
                  select.options[i] = null;
                }
                
              
                  var opt = document.createElement("option");
                opt.disabled = true
                opt.selected = true
                opt.innerHTML = "Select Position";
                select.appendChild(opt);
                for (var i = 0; i < data.length; i++) {
                    var opt = document.createElement("option");
                    opt.value = data[i].id;
                    opt.innerHTML = data[i].position;
                    select.appendChild(opt);
                }
            });
            jQuery("#repkpimodal").modal('show');
       }
    
       
      }
        function launchprojectAppreciationmodal (projectid){
          var baseurl = window.location.protocol + "//" + window.location.host + '/pms/';
       
       //if engagement
       if(document.getElementById('typetoggle').checked){
          
           document.getElementById('engappplan').value = projectid;
           jQuery("#appengmodal").modal('show');
       }
       //if kpi
       else{
             
             
              document.getElementById('kpiappplan').value = projectid;
              $.get(baseurl + "getposition", function(data) {
                select = document.getElementById("positionchoice");
                var length = select.options.length;
                for (i = length-1; i >= 0; i--) {
                  select.options[i] = null;
                }
                
                  var opt = document.createElement("option");
                opt.disabled = true
                opt.selected = true
                opt.innerHTML = "Select Position";
                select.appendChild(opt);
              
                for (var i = 0; i < data.length; i++) {
                    var opt = document.createElement("option");
                    opt.value = data[i].id;
                    opt.innerHTML = data[i].position;
                    select.appendChild(opt);
                }
            });
            jQuery("#appkpimodal").modal('show');
       }
    
       
      }
   function launchReprimandmodal(planid, name, type){
       
          var baseurl = window.location.protocol + "//" + window.location.host + '/pms/';
       
       //if engagement
       if(type==2){
           document.getElementById('engrepuname').innerHTML = name
           document.getElementById('engrepplan').value = planid;
    
           jQuery("#repengmodal").modal('show');
       }
       //if kpi
       else{
             document.getElementById('kpirepuname').innerHTML = name
             document.getElementById('kpirepplan').value = planid;
            
             
              $.get(baseurl + "getposition", function(data) {
                select = document.getElementById("positionchoicer");
  var length = select.options.length;
                for (i = length-1; i >= 0; i--) {
                  select.options[i] = null;
                }
                
              
                  var opt = document.createElement("option");
                opt.disabled = true
                opt.selected = true
                opt.innerHTML = "Select Position";
                select.appendChild(opt);
                for (var i = 0; i < data.length; i++) {
                    var opt = document.createElement("option");
                    opt.value = data[i].id;
                    opt.innerHTML = data[i].position;
                    select.appendChild(opt);
                }
            });
            jQuery("#repkpimodal").modal('show');
       }
    
       
      }
      
       function launchAppreciationmodal (planid, name, type){
          var baseurl = window.location.protocol + "//" + window.location.host + '/pms/';
       
       //if engagement
       if(type == 2){
           document.getElementById('engappuname').innerHTML = name
           
           document.getElementById('engappplan').value = planid;
           jQuery("#appengmodal").modal('show');
       }
       //if kpi
       else{
             document.getElementById('kpiappuname').innerHTML = name
              
              document.getElementById('kpiappplan').value = planid;
              $.get(baseurl + "getposition", function(data) {
                select = document.getElementById("positionchoice");
                var length = select.options.length;
                for (i = length-1; i >= 0; i--) {
                  select.options[i] = null;
                }
                
                  var opt = document.createElement("option");
                opt.disabled = true
                opt.selected = true
                opt.innerHTML = "Select Position";
                select.appendChild(opt);
              
                for (var i = 0; i < data.length; i++) {
                    var opt = document.createElement("option");
                    opt.value = data[i].id;
                    opt.innerHTML = data[i].position;
                    select.appendChild(opt);
                }
            });
            jQuery("#appkpimodal").modal('show');
       }
    
       
      }
      
      function positionchoicerep(){
          var baseurl = window.location.protocol + "//" + window.location.host + '/pms/';
          var kpiid = document.getElementById("positionchoicer").value;
            document.getElementById("objectiveselr").disabled = true;
             var length = document.getElementById("objectiveselr").options.length;
                for (i = length-1; i >= 0; i--) {
                  document.getElementById("objectiveselr").options[i] = null;
                }
                document.getElementById("objectiveselr").required = false;
                 document.getElementById("detailedreasonkr").disabled = true;
                  document.getElementById("detailedreasonkr").innerHTML = ""
                 document.getElementById("cckr").disabled = true; 
                 document.getElementById("actionr").disabled = true;
                  document.getElementById("actionr").innerHTML = ""
                 document.getElementById("improver").disabled = true; 
                  document.getElementById("improver").innerHTML = ""
                
        document.getElementById("perspectivechoicer").removeAttribute("disabled");
        $.get(baseurl + "perspectivefromid/" + kpiid, function(data) {
            select = document.getElementById("perspectivechoicer");
 var length = select.options.length;
                for (i = length-1; i >= 0; i--) {
                  select.options[i] = null;
                }
                   var opt = document.createElement("option");
                opt.disabled = true
                opt.selected = true
                opt.innerHTML = "Select Perspective";
                 select.appendChild(opt);
            for (var i = 0; i < data.length; i++) {
                var opt = document.createElement("option");
                opt.value = data[i].kpi_id;
                opt.innerHTML = data[i].perspective;
                select.appendChild(opt);
            }
        });
      }
      
       function positionchoiceapp(){
           var baseurl = window.location.protocol + "//" + window.location.host + '/pms/';
           var kpiid = document.getElementById("positionchoice").value;
        document.getElementById("objectivesel").disabled = true;
             var length = document.getElementById("objectivesel").options.length;
                for (i = length-1; i >= 0; i--) {
                  document.getElementById("objectivesel").options[i] = null;
                }
                document.getElementById("objectivesel").required = false;
                document.getElementById("detailedreasonk").disabled = true;
                 document.getElementById("detailedreasonk").innerHTML = ""
                document.getElementById("cck").disabled = true;  
                
        document.getElementById("perspectivechoice").removeAttribute("disabled");
        $.get(baseurl + "perspectivefromid/" + kpiid, function(data) {
            select = document.getElementById("perspectivechoice");
              var length = select.options.length;
                for (i = length-1; i >= 0; i--) {
                  select.options[i] = null;
                }
                 var opt = document.createElement("option");
                opt.disabled = true
                opt.selected = true
                opt.innerHTML = "Select Perspective";
                select.appendChild(opt);
            for (var i = 0; i < data.length; i++) {
                var opt = document.createElement("option");
                opt.value = data[i].kpi_id;
                opt.innerHTML = data[i].perspective;
                select.appendChild(opt);
            }
        });
      }
      
      function perspectivechoicerep(){
           var baseurl = window.location.protocol + "//" + window.location.host + '/pms/';
          var kpiid = document.getElementById("perspectivechoicer").value;
        var perspective = document.getElementById("perspectivechoicer").options[document.getElementById("perspectivechoicer").selectedIndex].text
        console.log(perspective)
        console.log(kpiid)
        document.getElementById("objectiveselr").removeAttribute("disabled");
          document.getElementById("objectiveselr").required = true;
        $.get(baseurl + "kpiformfromid/" + kpiid, function(data) {
           
            select = document.getElementById("objectiveselr");
            var length = select.options.length;
           for (i = length-1; i >= 0; i--) {
                  select.options[i] = null;
                }
                  var opt = document.createElement("option");
                opt.disabled = true
                opt.selected = true
                opt.innerHTML = "Select Objective";
                select.appendChild(opt);
            for (var i = 0; i < data.length; i++) {

                if (data[i].perspective == perspective && data[i].type == 2) {
                     
                    var opt = document.createElement("option");
                    opt.value = data[i].id;
                    opt.innerHTML = data[i].objective;
                    select.appendChild(opt);
                     
                }
            }
         document.getElementById("detailedreasonkr").removeAttribute("disabled");
          
         document.getElementById("cckr").removeAttribute("disabled");  
         document.getElementById("actionr").removeAttribute("disabled");
         document.getElementById("improver").removeAttribute("disabled");  
         document.getElementById("detailedreasonkr").required = true;
         document.getElementById("actionr").required = true;
         document.getElementById("improver").required = true;
        });
      }
      
       function perspectivechoiceapp(){
          var baseurl = window.location.protocol + "//" + window.location.host + '/pms/';
           var kpiid = document.getElementById("perspectivechoice").value;
             var perspective = document.getElementById("perspectivechoice").options[document.getElementById("perspectivechoice").selectedIndex].text
        document.getElementById("objectivesel").removeAttribute("disabled");
        document.getElementById("objectivesel").required = true;
        $.get(baseurl + "kpiformfromid/" + kpiid, function(data) {
            select = document.getElementById("objectivesel");
            
             var length = select.options.length;
                for (i = length-1; i >= 0; i--) {
                  select.options[i] = null;
                }
                  var opt = document.createElement("option");
                opt.disabled = true
                opt.selected = true
                opt.innerHTML = "Select Objective";
                select.appendChild(opt);
            for (var i = 0; i < data.length; i++) {
                 if (data[i].perspective == perspective && data[i].type == 1) {
                var opt = document.createElement("option");
                opt.value = data[i].id;
                opt.innerHTML = data[i].objective;
                select.appendChild(opt);
                }
            }
             document.getElementById("detailedreasonk").removeAttribute("disabled");
             document.getElementById("cck").removeAttribute("disabled");
              document.getElementById("detailedreasonk").required = true;
       
        });
      }
      function addkeyresult(){
         var count = parseFloat(document.getElementById('keyresultadd').dataset.count)
         count = count + 1
         
         var kr = '<div class="block block-bordered  ml-10 mr-10" id="keyresult'+count+'">  <a class=" btn btn-rounded float-right " id="removekr'+count+'" onclick="removekr('+count+')"> <i class="fa fa-2x fa-times-circle text-danger"></i></a><div class="block-content "><div class="form-group row mb-5 mt-5 "><label  class="col-4 col-sm-4 col-md-3 col-lg-3 col-form-label"> Keyresult </label></div>'
            kr += '<div class="form-group row mb-50 mt-20 "><label for="ownervalue" class="col-3 col-sm-3 col-md-2 col-lg-2 col-form-label"> Title</label><input type="text" class="form-control round col-9 col-sm-9 col-md-10 col-lg-10" id="okrkeyresult_name" name="keyresult_name'+count+'" placeholder="Example: Achieve revenue of 100m birr this quarter"  autocomplete="off" />'
            kr += '<div id="val-kr-error" class="invalid-feedback animated fadeInDown  col-9 col-sm-9 col-md-10 col-lg-10" >Keyresult title is required</div></div><div class="form-group row mb-50 mt-20"><label for="ownervalue" class="col-3 col-sm-3 col-md-2 col-lg-2 col-form-label"> Type</label><select  class="col-6 col-sm-6 col-md-5 col-lg-5 form-control round " id="krstatus'+count+'" name="krstatus'+count+'" onchange="changestatus(this, '+count+')"><option value=0>Achieved or not</option><option value=1>Shoud increase to</option></select> </div> <div id="krtype'+count+'"></div></div></div>'
         var theDiv = document.getElementById('keyresultlist')
           if(count >= 6){
               document.getElementById('keyresultadd').classList.add('ancdisabled')
           }
          
            $('#keyresultlist').append(kr)
            document.getElementById('keyresultadd').dataset.count = count
           document.getElementById('krcount').value = count
      }
      function removekr(id){
          $('#keyresult' + id).remove()
          
      }
      function changestatus(sel, count){
          
          if(sel.options[sel.selectedIndex].value == 0){
              document.getElementById("krtype" + count).innerHTML = ""
          }
          else{
              var element = '<div class="form-group row mb-50 mt-20"><label for="ownervalue" class="col-3 col-sm-3 col-md-2 col-lg-2 col-form-label"> Initial Value</label><input type="number" step=0.01 value=0 class="col-4 col-sm-4 col-md-3 col-lg-3 form-control round " name="initialv'+count+'"/><label for="ownervalue" class="col-3 col-sm-3 col-md-2 col-lg-2 col-form-label"> Target Value</label><input type="number" step=0.01 value=0 class="col-4 col-sm-4 col-md-3 col-lg-3 form-control round" name="targetv'+count+'" /></div>'
              document.getElementById("krtype" + count).innerHTML = element
          }
         
      }
        function addMile(id){
           
         
             $.ajaxSetup({
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
        });
         var formData = {
            taskname: document.getElementById('addmtaskvalue' +id).value,
            isMilestone: 1 ,
            keyresultid: id,
          
        };
        console.log(formData);
            
        var type = "POST";

        var ajaxurl = baseurl + "tasks";

        $.ajax({
            type: type,
            url: ajaxurl,
            data: formData,
            dataType: "json",
            success: function(data) {
                console.log(data);
                var stat = "Achieved";
                if (data.status == 0) {
                    stat = "Not Achieved";
                }
                var task = '<li id="miletaskl' + data.id + '" class="container"><div class = "row" ><div class="col-md-7">'
                task += '<b class="milevalue">' + data.taskname + '</b></div><div class="col-md-4" id="taskstat' + data.id + '"><p>' + stat + '</p></div>'

                task += '<div class="col-md-1 pull-right"><a href="#" class="neonav-link  caret-off" data-toggle="dropdown" id="navbardrop' + data.id + '"  data-toggle="dropdown" ><i class="fa fa-ellipsis-v"></i></a><div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbardrop' + data.id + '"><a  id ="editmiletask' + data.id + '" class="dropdown-item editmile" data-id= "' + data.id + '" >Edit</a><a id="deletetask' + data.id + '" class="dropdown-item deletetask" data-id= "' + data.id + '">Delete</a></div></div></div></li>'

                jQuery("#milestonelist" + id).append(task);

               
                
                
                    
         

            },
            error: function(data) {
                console.log("Error:", data.responseText);
            },
        });
        }
        function editMile(id){
           
         
             $.ajaxSetup({
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
        });
         var formData = {
            taskname: document.getElementById('editmtaskvalue' +id).value,
        
          
          
        };
        console.log(formData);
            
        var type = "PUT";

        var ajaxurl = baseurl + "tasks/" + id;

        $.ajax({
            type: type,
            url: ajaxurl,
            data: formData,
            dataType: "json",
            success: function(data) {
                console.log(data);
                var stat = "Achieved";
                if (data.status == 0) {
                    stat = "Not Achieved";
                }
                var task = '<li id="miletaskl' + data.id + '" class="container"><div class = "row" ><div class="col-md-7">'
                task += '<b class="milevalue">' + data.taskname + '</b></div><div class="col-md-4" id="taskstat' + data.id + '"><p>' + stat + '</p></div>'

                task += '<div class="col-md-1 pull-right"><button type="button" id ="editmiletask'+ data.id+'" class="btn btn-sm btn-rounded btn-outline-success  editmile" data-id= "'+ data.id+'" ><i class="si si-pencil"></i></button></div></div></div></li>'

                 $("#editmtaskblock" +id).replaceWith(task);

               
                
                
                    
         

            },
            error: function(data) {
                console.log("Error:", data.responseText);
            },
        });
        }
        function removeMile(id){
           
         $("#addmtaskblock" + id).remove();
      //  $("#addweeklytask" +id).removeClass('ancdisabled');
        }
         function removeEMile(id){
           
         
          $.get(baseurl + "tasks/" + id, function(data) {
                  var stat = "Achieved";
                if (data.status == 0) {
                    stat = "Not Achieved";
                }
              var task = '<li id="miletaskl' + data.id + '" class="container"><div class = "row" ><div class="col-md-7">'
                task += '<b class="milevalue">' + data.taskname + '</b></div><div class="col-md-4" id="taskstat' + data.id + '"><p>' + stat + '</p></div>'

                task += '<div class="col-md-1 pull-right"><button type="button" id ="editmiletask'+ data.id+'" class="btn btn-sm btn-rounded btn-outline-success  editmile" data-id= "'+ data.id+'" ><i class="si si-pencil"></i></button></div></div></div></li>'

                 $("#editmtaskblock" +id).replaceWith(task);
        
              });
      //  $("#addweeklytask" +id).removeClass('ancdisabled');
        }
      function addMilestone(id){
              var task = '<div class="block-content row" id="addmtaskblock'+ id + '" ><input type="text" class="form-control taskvalue col-md-10" id="addmtaskvalue'+ id + '"  placeholder=" Add milestone name">'
                task +=  '<div class="col-md-1"> <button type="button" class="btn btn-sm btn-rounded btn-outline-success  mt-5" onclick="addMile('+id+')"><i class="fa fa-plus" ></i></button></div><div class="col-md-1"><button type="button" class="btn btn-sm btn-rounded btn-outline-danger  mt-5" onclick="removeMile('+id+')" ><i class="fa fa-times"></i></button></div></div>'
                    
                        jQuery("#addmilestoneblock" + id).prepend(task);
      }
      
         function editMilestone(id){

              $.get(baseurl + "tasks/" + id, function(data) {
                  
                   var task = '<div class="block-content row" id="editmtaskblock'+ id + '" ><input type="text" class="form-control  col-md-10" id="editmtaskvalue'+ id + '"  placeholder=" Add milestone name" value="'+data.taskname+ '">'
                task +=  '<div class="col-md-1"> <button type="button" class="btn btn-sm btn-rounded btn-outline-success  mt-5" onclick="editMile('+id+')"><i class="fa fa-pencil" ></i></button></div><div class="col-md-1"><button type="button" class="btn btn-sm btn-rounded btn-outline-danger  mt-5" onclick="removeEMile('+id+')" ><i class="fa fa-times"></i></button></div></div>'
               
                  jQuery("#miletaskl" + id).replaceWith(task);
        
              });
    }
$(document).ready(function($) {
    //add Kpi
 var baseurl = window.location.protocol + "//" + window.location.host + '/pms';
    jQuery("body").on("click", ".addObjKpi", function() {
        var pcount = $(this).data("pers");
        var count = parseInt(
            document.getElementById("addObjKpi" + pcount).dataset.count
        );
        var max = parseInt(document.getElementById("objcount").value);

        count = count + 1;
        if (max < count) {
            $("#objcount").val(max + 1);
        }
        document.getElementById("addObjKpi" + pcount).dataset.count = count;

        $.get(baseurl +"/formulas", function(data) {
            var kpi =
                '<div id="objblock' +
                count +
                pcount +
                '"> <hr style="color:blue;" ><div class="row mb-20"><div class="col-md-6"><div class="form-material floating open"><textarea class="form-control" id="kpiobj' +
                count +
                '" name="kpiobj' +
                count +
                " " +
                pcount +
                '" rows="3"></textarea><label for="kpiobj' +
                count +
                '">Objective</label></div></div><div class="col-md-6">';
            kpi +=
                '<div class="form-material floating open"><textarea class="form-control" id="measure' +
                count +
                '" name="measure' +
                count +
                " " +
                pcount +
                '" rows="3"></textarea><label for="measure' +
                count +
                '">Measure</label></div></div></div><div class="row mb-20"><div class="col-md-3"><div class="form-material floating open"><input type="number" class="form-control" id="target' +
                count +
                '" name="target' +
                count +
                " " +
                pcount +
                '" min=0><label for="target' +
                count +
                '">Target</label></div></div>';
            kpi +=
                '<div class="col-md-2"><div class="form-material floating open"><input type="number" class="form-control" id="weight' +
                count +
                '" name="weight' +
                count +
                " " +
                pcount +
                '" min=0><label for="weight' +
                count +
                '">Weight</label></div></div><div class="col-md-2"><div class="form-material floating"><select class="form-control" id="type' + count + '" name="type' + count + " " + pcount + '"><option disabled selected>Select Type</option><option value = 1 >Appreciation</option><option value = 2>Reprimand</option></select></div></div>'
            kpi +=
                '<div class="col-md-4"><div class="form-material floating open"><select class="form-control" id="formula' +
                count +
                '" name="formula' +
                count +
                " " +
                pcount +
                '"><option disabled selected>Select Formula</option><option value="0">Yes or No</option>';

            for (var i = 0; i < data.length; i++) {
                kpi +=
                    "<option value=" +
                    data[i].id +
                    ">" +
                    data[i].formula +
                    "</option>";
            }
            kpi +=
                '</select></div></div><div class="col-md-2 mt-20"> <button type="button" class="btn btn-outline-danger mr-5 mb-5 removeObjKpi"  data-count =' +
                count +
                " data-pers = " +
                pcount +
                '><i class="fa fa-minus mr-5"></i></button></div></div></div>';

            jQuery(".addkpidata" + pcount).append(kpi);
        });
    });
    jQuery("body").on("click", ".removeObjKpi", function() {
        var count = parseInt($(this).data("count"));

        console.log($(this).data("pers"));
        var high = parseInt(
            document.getElementById("addObjKpi" + $(this).data("pers")).dataset
            .count
        );
        console.log("#objblock" + count + " " + $(this).data("pers"));

        jQuery("#objblock" + count + "" + $(this).data("pers")).remove();
        if (high == count) {
            count = count - 1;
        }
        document.getElementById(
            "addObjKpi" + $(this).data("pers")
        ).dataset.count = count;
    });
    jQuery("body").on("click", "#addPersKpi", function() {
        var pcount = parseInt(
            document.getElementById("addPersKpi").dataset.count
        );
        var max = parseInt(document.getElementById("perscount").value);

        $("#perscount").val(max + 1);

        pcount = pcount + 1;

        document.getElementById("addPersKpi").dataset.count = pcount;

        ocount = 1;

        $.get(baseurl + "/formulas", function(data) {
            var kpi =
                '<div class="block block-bordered" id="persblock' +
                pcount +
                '"><div class="block-content "><div class="row mb-20"><div class="col-md-12"><div class="form-material floating open"><input type="text" class="form-control" id="perspective' +
                pcount +
                '" name="perspective' +
                pcount +
                '"><label for="perspective' +
                pcount +
                '">Perspective</label></div></div></div>';
            kpi +=
                '<div class="block block-bordered"><div class="block-content"><div class="container addkpidata' +
                pcount +
                '">';
            kpi +=
                '<div class="row mb-20"><div class="col-md-6"><div class="form-material floating open"><textarea class="form-control" id="kpiobj' +
                ocount +
                '" name="kpiobj' +
                ocount +
                " " +
                pcount +
                '" rows="3"></textarea><label for="kpiobj' +
                ocount +
                '">Objective</label></div></div><div class="col-md-6">';
            kpi +=
                '<div class="form-material floating open"><textarea class="form-control" id="measure' +
                ocount +
                '" name="measure' +
                ocount +
                " " +
                pcount +
                '" rows="3"></textarea><label for="measure' +
                ocount +
                '">Measure</label></div></div></div><div class="row mb-20"><div class="col-md-3"><div class="form-material floating open"><input type="number" class="form-control" id="target' +
                ocount +
                '" name="target' +
                ocount +
                " " +
                pcount +
                '" min=0><label for="target' +
                ocount +
                '">Target</label></div></div>';
            kpi +=
                '<div class="col-md-2"><div class="form-material floating open"><input type="number" class="form-control" id="weight' +
                ocount +
                '" name="weight' +
                ocount +
                " " +
                pcount +
                '" min=0><label for="weight' +
                ocount +
                '">Weight</label></div></div><div class="col-md-2"><div class="form-material floating"><select class="form-control" id="type' + ocount + '" name="type' + ocount + " " + pcount + '"><option disabled selected>Select Type</option><option value = 1 >Appreciation</option><option value = 2>Reprimand</option></select></div></div>';
            kpi +=
                '<div class="col-md-4"><div class="form-material floating open"><select class="form-control" id="formula' +
                ocount +
                '" name="formula' +
                ocount +
                " " +
                pcount +
                '"><option disabled selected>Select Formula</option><option value="0">Yes or No</option>';

            for (var i = 0; i < data.length; i++) {
                kpi +=
                    "<option value=" +
                    data[i].id +
                    ">" +
                    data[i].formula +
                    "</option>";
            }
            kpi +=
                '</select></div></div></div></div></div></div><div class="col-md-3 mt-20"><button type="button" class="btn btn-rounded btn-outline-info mr-5 mb-5 addObjKpi" id="addObjKpi' +
                pcount +
                '" data-count =' +
                ocount +
                " data-pers = " +
                pcount +
                '><i class="fa fa-plus mr-5"></i>Add Objective</button></div></div></div>';

            jQuery(".addpersdata").append(kpi);
        });
    });

    //launch the delete session modal
    jQuery("body").on("click", ".deletekpi", function() {
        var kpi_id = $(this).data("id");

        jQuery("#deletekpimodal").modal("show");

        $.get(baseurl +"/kpishow/" + kpi_id, function(data) {
            jQuery("#deletelabel").text(
                "Are you sure you want to delete the kpi: " +
                data.kpi_name +
                "?"
            );
            console.log(data);
            jQuery("#kpi-delete").val(data.id);
        });
    });
    //delete a session
    jQuery("#kpi-delete").click(function() {
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
            url: baseurl +"/kpis/" + kpi_id,
            success: function(data) {
                $("#kpidata" + kpi_id).remove();
                jQuery("#deletekpimodal").modal("hide");
            },
            error: function(data) {
                console.log("Error:", data);
            },
        });
    });
    jQuery("body").on("click", "#chooseposition", function() {
        var position = $("#positionchoice").children("option:selected").val();
        $.get(baseurl +"/kpifromposition/" + position, function(data) {
            console.log(data);
            if (data.length > 0) {
                var info =
                    '<ul class="nav nav-tabs nav-tabs-block mx-20" data-toggle="tabs" role="tablist"><input type="hidden" name="kpi_id" value=' +
                    data[1].kpi_id +
                    ">";
                for (var i = 0; i < data[0].length; i++) {
                    if (i == 0) {
                        info +=
                            '<li class="nav-item "><a class="nav-link active tabclick" data-href="#perspective1" style="cursor: pointer;">' +
                            data[0][i].perspective +
                            "</a></li>";
                    } else {
                        info +=
                            '<li class="nav-item "><a class="nav-link tabclick" data-href="#perspective' +
                            (i + 1) +
                            '" style="cursor: pointer;" >' +
                            data[0][i].perspective +
                            "</a></li>";
                    }
                }

                info += '</ul> <div class="block-content tab-content">';
                var pcount = 1;
                for (var i = 0; i < data[0].length; i++) {
                    var count = 1;
                    var message = pcount == 1 ? "active" : "";
                    info +=
                        '<div class="tab-pane ' +
                        message +
                        '" id="perspective' +
                        pcount++ +
                        '" role="tabpanel">';
                    info +=
                        '<table class="table table-bordered table-vcenter"><thead><tr><th class="text-center" style="width: 50px;">#</th><th>Objective</th><th>Measure</th><th class="d-none d-sm-table-cell" style="width: 15%;">Target</th><th class="d-none d-sm-table-cell" style="width: 15%;">Weight (%)</th>';
                    info +=
                        '<th class="d-none d-sm-table-cell" style="width: 15%;">Actual</th><th class="d-none d-sm-table-cell" style="width: 15%;">Score (%)</th></tr></thead><tbody>';
                    for (var j = 0; j < data[1].length; j++) {
                        if (data[0][i].perspective == data[1][j].perspective) {
                            info +=
                                '<tr><th class="text-center" scope="row">' +
                                count++ +
                                "</th><td>" +
                                data[1][j].objective +
                                '</td><td class="d-none d-sm-table-cell">' +
                                data[1][j].measure +
                                "</td>";
                            info +=
                                '<td class="d-none d-sm-table-cell">' +
                                data[1][j].target +
                                '</td><td class="d-none d-sm-table-cell">' +
                                data[1][j].weight +
                                "</td>";
                            if (data[1][j].formula_id == null) {
                                info +=
                                    '<td class="d-none d-sm-table-cell"><label class="css-control css-control-sm css-control-success css-radio"><input type="radio" class="css-control-input actualradio" name="actual' +
                                    data[1][j].id +
                                    '" ><span class="css-control-indicator"></span> Yes</label>';
                                info +=
                                    '<label class="css-control css-control-sm css-control-danger css-radio "><input type="radio" class="css-control-input actualradio" name="actual' +
                                    data[1][j].id +
                                    '" checked="checked"><span class="css-control-indicator"></span> No</label></td>';
                            } else {
                                info +=
                                    '<td class="d-none d-sm-table-cell"><input type="number" class="form-control actualinput" vaue=0 name="actual' +
                                    data[1][j].id +
                                    '"></td>';
                            }
                            info +=
                                '<td id="score' +
                                data[1][j].id +
                                +'" class="d-none d-sm-table-cell" data-value=' +
                                (data[1][j].target == 0 &&
                                    data[1][j].actual == 0 ?
                                    data[1][j].weight :
                                    0) +
                                ">" +
                                (data[1][j].target == 0 &&
                                    data[1][j].actual == 0 ?
                                    data[1][j].weight :
                                    0) +
                                "</td></tr>";
                        }
                    }
                    info += "</tbody></table></div>";
                }
                info += "</div>";
                $(".kpitab").html(info);
            } else {
                $(".kpitab").html("");
            }
        });
    });
    jQuery("body").on("click", ".tabclick", function() {
        $(".tabclick").removeClass("active");
        var loadurl = $(this).data("href");

        $(".tab-pane").removeClass("active");
        $(loadurl).addClass("active");
        $("[data-href='" + loadurl + "']").addClass("active");
    });
    jQuery("body").on("blur", ".actualinput", function() {
        console.log("hhh");
        var id = $(this).data("id");

        var actual = $(this).val();
        $.get(baseurl +"/kpiform/" + id, function(data) {
            var formula = data.formula;
            var target = data.target;
            var weight = data.weight;
            values = formula.slice(0, -1);
            console.log(values);
            valuesarr = formula.split(" ");

            var err = 0;
            for (var i = 0; i < valuesarr.length; i++) {
                if (valuesarr[i] == "actual") {
                    valuesarr[i] = actual;
                } else if (valuesarr[i] == "target") {
                    valuesarr[i] = target;
                } else if (valuesarr[i] == "weight") {
                    valuesarr[i] = weight / 100;
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
                console.log(evaluatedValue * 100);
            } catch (e) {
                if (e instanceof SyntaxError) {}
            }

            console.log(document.getElementById("totalv").dataset.total);
            console.log(
                (evaluatedValue * 100).toFixed(1) -
                document.getElementById("score" + id).dataset.value
            );
            var total = parseFloat(
                parseFloat(document.getElementById("totalv").dataset.total) +
                ((evaluatedValue * 100).toFixed(1) -
                    document.getElementById("score" + id).dataset.value)
            );

            $("#totalv").html("Total = " + total.toFixed(1) + "%");
            $("#totalv").attr("data-total", total.toFixed(1));
            $("#score" + id).html((evaluatedValue * 100).toFixed(1));
            $("#score" + id).attr(
                "data-value",
                (evaluatedValue * 100).toFixed(1)
            );
        });
    });
}); //end of jquery
jQuery("body").on("change", ".actualradio", function() {
    var id = $(this).data("id");
    var actual = $(this).val();

    $.get(baseurl +"/kpiform/" + id, function(data) {
        if (actual == 1) {
            var total = parseFloat(
                parseFloat(document.getElementById("totalv").dataset.total) +
                data.weight
            );
            $("#score" + id).html(data.weight);
            $("#totalv").html("Total = " + total.toFixed(1) + "%");
            $("#totalv").attr("data-total", total.toFixed(1));
        } else if (actual == 0) {
            var total = parseFloat(
                parseFloat(document.getElementById("totalv").dataset.total) -
                data.weight
            );
            $("#score" + id).html(0);
            $("#totalv").html("Total = " + total.toFixed(1) + "%");
            $("#totalv").attr("data-total", total.toFixed(1));
        }
    });



});

/*initiate the autocomplete function on the "myInput" element, and pass along the countries array as possible autocomplete values:*/

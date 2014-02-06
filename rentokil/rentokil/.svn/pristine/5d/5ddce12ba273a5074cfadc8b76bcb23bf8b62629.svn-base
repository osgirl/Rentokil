// ***************Daily Meal collection*************** //
function LoadDailyMealColl()
{
    $("#tabDailyMeal").bind("click", serviceMealCollSchools);
    $("#btnDailyMealPrint").bind("click", SrvprintDailyMeal);
    $("#chkShowCollected", "#mydailymeal").bind("click", chkMealComplete);
    $("#btnDMCGo", "#mydailymeal").bind("click", serviceDailyMealColl);
    $('#ddlSchoolsMealcoll', "#mydailymeal").on('change', function() {
        serviceDailyMealColl();
    });
}

function serviceMealCollSchools() {
    var url = BACKENDURL + "user/get_schools_admins";
    var data = {
        session_id: localStorage["SESSIONID"],
        contract_id: localStorage["contractid"]
    };
    MakeAjaxCall(url, data, loadMealCollSchools);
}

function loadMealCollSchools(data)
{
    if (data.error == 0) {
        $('#ddlSchoolsMealcoll', "#mydailymeal").empty();
        var schoolStr = "";
        if (data.schools_res.length > 0) {
            for (var nCount = 0; nCount < data.schools_res.length; nCount++) {
                var disabled = (data.schools_res[nCount].status == "0") ? " (disabled)" : "";
                schoolStr += "<option value=" + data.schools_res[nCount].school_id + ">" + data.schools_res[nCount].school_name + disabled + "</option>";
            }
            $('#ddlSchoolsMealcoll', "#mydailymeal").append(schoolStr);
            if (data.schools_res.length == 1) {
                $(".select-wrap", "#dailymeal").hide();
                $("#lblSchool", "#dailymeal").hide();
            }
            $("#validdailyMeal").removeClass("hide");
            if (data.schools_res.length == 1) {
                $("#tdSchoolSelect", "#mydailymeal").hide();
                $("#tdSchoolLabel", "#mydailymeal").hide();
            }
            serviceDailyMealColl();
        } else
        {
            $("#InvaliddailyMeal").removeClass("hide");
        }

    }
}

function serviceDailyMealColl()
{
    var ff_date = $("#dp_DMCdate").val().split("/");
    var url = BACKENDURL + "user/get_daily_meal_collection_year_class";
    var data = {
        session_id: localStorage["SESSIONID"],
        school_id: $('#ddlSchoolsMealcoll', "#mydailymeal").val(),
        fulfilment_date: ff_date[2] + "-" + ff_date[1] + "-" + ff_date[0]

    };
    MakeAjaxCall(url, data, getDailyMealcoll);
}

function getDailyMealcoll(data)
{
    $.support.cors = true;
    $.ajax({
        url: BACKENDURL + "common/get_current_date_time",
        type: "post",
        dataType: "json",
        crossDomain: true,
        async: false,
        success: function(data_tm) {
            $("#lblDMC_rt").text(data_tm.shour + ":" + data_tm.smin + ":" +data_tm.ssec);
            $("#lblDMC_rd").text(data_tm.sday + "/" + data_tm.smonth + "/" + (data_tm.syear));
        }
    });
    if (data.error == 0) {
        // To check School Status
        var Status_Msg = "";
        $("#lblCloseSchlDMCWarn").text('');
        if (data.meal_res[4].close_details.closed_status == 1)
        {
            Status_Msg = "<strong>Remember! </strong>" + $("#ddlSchoolsMealcoll option:selected").text() + " was closed by " + data.meal_res[4].close_details.closed_by + ", because '" + data.meal_res[4].close_details.closed_reason + "'. Last day of closure is " + data.meal_res[4].close_details.closed_till;
            $("#lblCloseSchlDMCWarn").removeClass("hide").append(Status_Msg);
        }
        else
        {
            if (!$("#lblCloseSchlDMCWarn").hasClass("hide"))
                $("#lblCloseSchlDMCWarn").addClass("hide");
        }
        var dailyMealColl = "", dailyMealdata = "", pupilName = "", mainMeal = "", snacks = "", adultFlag = "", inStr = "", tmpSchDet = "", tmpSchId = "";
        $("#lblmealorderSchool").text($('#ddlSchoolsMealcoll option:selected', "#mydailymeal").text());
        if (data.meal_res[0].school_res.length > 0)
        {
            localStorage["fulfilment_date"] = data.meal_res[1].fulfilment_date;
            $("#lblfulfillmentdate").text(data.meal_res[3].fulfilment_date_format);
            for (var jCount = 0; jCount < data.meal_res[0].school_res.length; jCount++) {
                if (jCount == 0)
                {
                    inStr = " in ";
                    if (data.meal_res[2].student_res[0].student_res.length > 0)
                    {

                        for (var kCount = 0; kCount < data.meal_res[2].student_res[0].student_res.length; kCount++) {
                            var collectedBtn = "", uncollectedBtn = "";
                            pupilName = data.meal_res[2].student_res[0].student_res[kCount].fname + " " + data.meal_res[2].student_res[0].student_res[kCount].mname + " " + data.meal_res[2].student_res[0].student_res[kCount].lname;
                            (data.meal_res[2].student_res[0].student_res[kCount].collect_status == 1) ? collectedBtn = " hide " : uncollectedBtn = " hide ";
                            (data.meal_res[2].student_res[0].student_res[kCount].adult == 0) ? adultFlag = "" : "icon-user";
                            mainMeal = data.meal_res[2].student_res[0].student_res[kCount].main_meal;
                            tmpSchDet = data.meal_res[2].student_res[0].student_res[kCount].pupil_id + "_" + data.meal_res[2].student_res[0].student_res[kCount].order_id + "_" + data.meal_res[2].student_res[0].student_res[kCount].collect_status;
                            tmpSchId = (data.meal_res[2].student_res[0].student_res[kCount].pupil_id).replace("/", "");
                            if (mainMeal != "")
                                mainMeal += " (Desert Included) .";
                            snacks = (data.meal_res[2].student_res[0].student_res[kCount].snacks).replace(/\s+/g, ' ');
                            dailyMealdata += "<div class='media' style='margin-top:15px;'><a class='pull-left' href='#'><img class='media-object' src='img/photo-placeholder.png'></a><div class='media-body'><input type='hidden' id='hdnMCSD-" + tmpSchId + "' value='" + tmpSchDet + "'><h4 class='media-heading' style='display: inline;'>" + pupilName + "</h4>&nbsp;<i class='" + adultFlag + "'></i>&nbsp;<a class='btn btn-success btn-small" + collectedBtn + "' id='btn_1_collsts" + tmpSchId + "' onClick='javascript:return changeMealCollectionStatus(this,0);'>Collect</a><a class='btn btn-danger btn-small" + uncollectedBtn + "' id='btn_0_collsts" + tmpSchId + "' onClick='javascript:return changeMealCollectionStatus(this,1);'>Un-Collect</a><p><b>Main Meal: </b>" + mainMeal + "<br /><b>Snack Option: </b>" + snacks + "</p></div></div>";
                        }
                    }
                    else
                    {
                        dailyMealdata += "<div class='alert alert-error'>No orders have been placed for this class</div>";
                    }
                }
                else
                {
                    dailyMealdata = "";
                    inStr = "";
                }
                if (data.meal_res[0].school_res[jCount].class_res.length > 0)
                {
                    for (var nCount = 0; nCount < data.meal_res[0].school_res[jCount].class_res.length; nCount++) {
                        var temphdn = data.meal_res[0].school_res[jCount].year_no + "," + data.meal_res[0].school_res[jCount].class_res[nCount].class_key + "," + data.meal_res[0].school_res[jCount].class_res[nCount].class_name;
                        var tempid = data.meal_res[0].school_res[jCount].year_no + "-" + data.meal_res[0].school_res[jCount].class_res[nCount].class_key + "-" + (data.meal_res[0].school_res[jCount].class_res[nCount].class_name).split(" ").join("");
                        var tempdisplay = data.meal_res[0].school_res[jCount].year_label + ", " + data.meal_res[0].school_res[jCount].class_res[nCount].class_name;
                        dailyMealColl += "<div class='accordion-group'><div class='accordion-heading'><input type='hidden' id='hdnMC-" + tempid + "' value='" + temphdn + "'><a class='accordion-toggle' data-toggle='collapse' data-parent='#divAccordMealOrderColl' href='#divMC-" + tempid + "' style='text-decoration: none !important;'><span class='icon-th-list icon-white'></span> " + tempdisplay + "<span id='spnGen" + tempid + "' class='hide'>&nbsp;&nbsp;Loading&nbsp;<img src='img/ajax-loader2.gif'/></span></a></div><div id='divMC-" + tempid + "' class='accordion-body collapse" + inStr + "'> <div class='accordion-inner'><div class='accordion' style='margin-bottom:0px;'>" + dailyMealdata + "</div></div></div></div>";
                        inStr = "";
                    }
                }
            }
        }
        $("#divAccordMealOrderColl", "#mydailymeal").html(dailyMealColl);
        if (data.meal_res[2].student_res[1].meal_status == 0)
        {
            $(".btn.btn-success").attr('disabled', 'disabled').removeAttr('onclick');
            $(".btn.btn-danger").attr('disabled', 'disabled').removeAttr('onclick');
        }
        $("div[id^=divMC-]", "#mydailymeal").on('show', function(e) {
            e.stopPropagation();
            serviceDailyMealAccordion(this, 0);
        });
    } else {
        logout(1);
        /* $("#validdailyMeal").hide();
         $("#InvaliddailyMeal").html(data.error_msg).show(); */}
}

function chkMealComplete()
{
    var chkMealActiveAccord = $(".accordion-body.collapse.in", "#divAccordMealOrderColl").attr("id");
    serviceDailyMealAccordion(chkMealActiveAccord, 1);
}

function serviceDailyMealAccordion(divYC, chkAccord)
{
    var YearClassId = "";
    (chkAccord == 0) ? YearClassId = $(divYC).attr("id") : YearClassId = divYC;
    var spnid = YearClassId.replace("divMC-", "spnGen");
    $("#" + spnid).show();
    localStorage["activeAccord"] = YearClassId;
    var newid = YearClassId.replace("divMC-", "hdnMC-");
    var hdnbkvalue = ($("#" + newid).val()).split(",");
    var schoolId = $('#ddlSchoolsMealcoll', "#mydailymeal").val();
    //var school_name = $('#ddlSchoolsMealcoll option:selected', "#mydailymeal").text();
    var MealsCollected = $("#chkShowCollected").is(':checked') ? "1" : "0";
    var ff_date = $("#dp_DMCdate").val().split("/");
    var url = BACKENDURL + "user/get_daily_meal_collection_students";
    var data = {
        session_id: localStorage["SESSIONID"],
        school_id: schoolId,
        year_no: hdnbkvalue[0],
        class_key: hdnbkvalue[1],
        class_name: hdnbkvalue[2],
        collect_status: MealsCollected,
        fulfilment_date: ff_date[2] + "-" + ff_date[1] + "-" + ff_date[0]

    };
    MakeAjaxCall(url, data, getDailyMealcollection);

}

function getDailyMealcollection(data)
{
    var dailyMealRecords = "", pupilName = "", mainMeal = "", snacks = "", adultFlag = "", tmpSchDet = "", tmpSchId = "";
    var localdivspinner = localStorage["activeAccord"];
    var spnid = localdivspinner.replace("divMC-", "spnGen");
    if (data.error == 0) {
        if (data.meal_res[0].student_res.length > 0)
        {
            for (var kCount = 0; kCount < data.meal_res[0].student_res.length; kCount++) {
                var collectedBtn = "", uncollectedBtn = "";
                pupilName = data.meal_res[0].student_res[kCount].fname + " " + data.meal_res[0].student_res[kCount].mname + " " + data.meal_res[0].student_res[kCount].lname;
                (data.meal_res[0].student_res[kCount].collect_status == 1) ? collectedBtn = " hide " : uncollectedBtn = " hide ";
                (data.meal_res[0].student_res[kCount].adult == 0) ? adultFlag = "" : "icon-user";
                mainMeal = data.meal_res[0].student_res[kCount].main_meal;
                tmpSchDet = data.meal_res[0].student_res[kCount].pupil_id + "_" + data.meal_res[0].student_res[kCount].order_id + "_" + data.meal_res[0].student_res[kCount].collect_status;
                tmpSchId = (data.meal_res[0].student_res[kCount].pupil_id).replace("/", "");
                if (mainMeal != "")
                    mainMeal += " (Desert Included) .";
                snacks = (data.meal_res[0].student_res[kCount].snacks).replace(/\s+/g, ' ');
                dailyMealRecords += "<div class='media' style='margin-top:15px;'><a class='pull-left' href='#'><img class='media-object' src='img/photo-placeholder.png'></a><div class='media-body'><input type='hidden' id='hdnMCSD-" + tmpSchId + "' value='" + tmpSchDet + "'><h4 class='media-heading' style='display: inline;'>" + pupilName + "</h4>&nbsp;<i class='" + adultFlag + "'></i>&nbsp;<a class='btn btn-success btn-small" + collectedBtn + "' id='btn_1_collsts" + tmpSchId + "' onClick='javascript:return changeMealCollectionStatus(this,0);'>Collect</a><a class='btn btn-danger btn-small" + uncollectedBtn + "' id='btn_0_collsts" + tmpSchId + "' onClick='javascript:return changeMealCollectionStatus(this,1);'>Un-Collect</a><p><b>Main Meal: </b>" + mainMeal + "<br /><b>Snack Option: </b>" + snacks + "</p></div></div>";

            }
            dailyMealRecords = "<div class='accordion-inner'><div class='accordion' style='margin-bottom:0px;'>" + dailyMealRecords + "</div>";

        } else
        {
            dailyMealRecords += "<div class='accordion-inner'><div class='accordion'><div class='alert alert-error'>No orders have been placed for this class</div></div></div>";
        }
        $("#" + localStorage["activeAccord"], "#divAccordMealOrderColl").html(dailyMealRecords);
        if (data.meal_res[1].meal_status == 0)
        {
            $(".btn.btn-success").attr('disabled', 'disabled').removeAttr('onclick');
            $(".btn.btn-danger").attr('disabled', 'disabled').removeAttr('onclick');
        }
        $("#" + spnid).hide();
    } else {
        logout(1);
        /* dailyMealRecords += "<div class='accordion-inner'><div class='accordion'><div class='alert alert-error'>"+data.error_msg+"</div></div></div>";
         $("#" + localStorage["activeAccord"], "#divAccordMealOrderColl").html(dailyMealRecords);
         $("#" + spnid).hide(); */
    }

}

function SrvprintDailyMeal()
{
    $("#spnGenPrint").removeClass('hide');
    var schoolId = $('#ddlSchoolsMealcoll', "#mydailymeal").val();
    var ff_date = $("#dp_DMCdate").val().split("/");
    var url = BACKENDURL + "user/print_daily_meal_collection";
    var data = {
        session_id: localStorage["SESSIONID"],
        school_id: schoolId,
        fulfilment_date: ff_date[2] + "-" + ff_date[1] + "-" + ff_date[0]
    };
    MakeAjaxCall(url, data, printDailyMeal);

}
function printDailyMeal(data)
{

    if (data.error == 0) {
        var stdDetails = 0;
        var dailyMealColl = "", dailyMealdata = "", pupilName = "", mainMeal = "", snacks = "", adultFlag = "", inStr = "", tmpSchDet = "", tmpSchId = "";
        $("#lblmealorderSchool").text($('#ddlSchoolsMealcoll option:selected', "#mydailymeal").text());
        if (data.meal_res.length > 0) {
            if (data.meal_res[0].school_res.length > 0)
            {
                localStorage["fulfilment_date"] = data.meal_res[1].fulfilment_date;
                $("#lblfulfillmentdate").text(data.meal_res[3].fulfilment_date_format);
                for (var jCount = 0; jCount < data.meal_res[0].school_res.length; jCount++) {
                    if (data.meal_res[0].school_res[jCount].class_res.length > 0)
                    {
                        for (var nCount = 0; nCount < data.meal_res[0].school_res[jCount].class_res.length; nCount++) {

                            var temphdn = data.meal_res[0].school_res[jCount].year_no + "," + data.meal_res[0].school_res[jCount].class_res[nCount].class_key + "," + data.meal_res[0].school_res[jCount].class_res[nCount].class_name;
                            var tempid = data.meal_res[0].school_res[jCount].year_no + "-" + data.meal_res[0].school_res[jCount].class_res[nCount].class_key + "-" + (data.meal_res[0].school_res[jCount].class_res[nCount].class_name).split(" ").join("");
                            var tempdisplay = data.meal_res[0].school_res[jCount].year_label + ", " + data.meal_res[0].school_res[jCount].class_res[nCount].class_name;
                            if (data.meal_res[2].student_res[stdDetails].length > 0)
                            {

                                var dailyMealRecords = "", pupilName = "", mainMeal = "", snacks = "", adultFlag = "", tmpSchDet = "", tmpSchId = "";
                                for (var kCount = 0; kCount < data.meal_res[2].student_res[stdDetails].length; kCount++) {
                                    var collectedBtn = "", uncollectedBtn = "";
                                    pupilName = data.meal_res[2].student_res[stdDetails][kCount].fname + " " + data.meal_res[2].student_res[stdDetails][kCount].mname + " " + data.meal_res[2].student_res[stdDetails][kCount].lname;
                                    (data.meal_res[2].student_res[stdDetails][kCount].collect_status == 1) ? collectedBtn = " hide " : uncollectedBtn = " hide ";
                                    (data.meal_res[2].student_res[stdDetails][kCount].adult == 0) ? adultFlag = "" : "icon-user";
                                    mainMeal = data.meal_res[2].student_res[stdDetails][kCount].main_meal;
                                    tmpSchDet = data.meal_res[2].student_res[stdDetails][kCount].pupil_id + "_" + data.meal_res[2].student_res[stdDetails][kCount].order_id + "_" + data.meal_res[2].student_res[stdDetails][kCount].collect_status;
                                    tmpSchId = (data.meal_res[2].student_res[stdDetails][kCount].pupil_id).replace("/", "");
                                    if (mainMeal != "")
                                        mainMeal += " (Desert Included) .";
                                    snacks = (data.meal_res[2].student_res[stdDetails][kCount].snacks).replace(/\s+/g, ' ');
                                    dailyMealRecords += "<div class='media' style='margin-top:15px;'><a class='pull-left' href='#'><img class='media-object' src='img/photo-placeholder.png'></a><div class='media-body'><input type='hidden' id='hdnMCSD-" + tmpSchId + "' value='" + tmpSchDet + "'><h4 class='media-heading' style='display: inline;'>" + pupilName + "</h4>&nbsp;<i class='" + adultFlag + "'></i>&nbsp;<a class='btn btn-success btn-small" + collectedBtn + "' id='btn_1_collsts" + tmpSchId + "' onClick='javascript:return changeMealCollectionStatus(this,0);'>Collect</a><a class='btn btn-danger btn-small" + uncollectedBtn + "' id='btn_0_collsts" + tmpSchId + "' onClick='javascript:return changeMealCollectionStatus(this,1);'>Un-Collect</a><p><b>Main Meal: </b>" + mainMeal + "<br /><b>Snack Option: </b>" + snacks + "</p></div></div>";

                                }
                                stdDetails++;
                            }
                            else
                            {
                                stdDetails++;
                                dailyMealRecords = "<div class='alert alert-error'>No orders have been placed for this class</div>";
                            }
                            dailyMealdata += "<div class='accordion-group'><div class='accordion-heading'><input type='hidden' id='hdnMC-" + tempid + "' value='" + temphdn + "'><a class='accordion-toggle' data-toggle='collapse' data-parent='#divAccordMealOrderColl' href='#divMC-" + tempid + "' style='text-decoration: none !important;'><span class='icon-th-list icon-white'></span> " + tempdisplay + "<span id='spnGen" + tempid + "' class='hide'>&nbsp;&nbsp;Loading&nbsp;<img src='img/ajax-loader2.gif'/></span></a></div><div id='divMC-" + tempid + "' class='accordion-body in collapse" + inStr + "'> <div class='accordion-inner'><div class='accordion' style='margin-bottom:0px;'>" + dailyMealRecords + "</div></div></div></div>";
                            inStr = "";
                        }
                    }
                }
            }
            else {
                dailyMealdata += "<div class='alert alert-error'>No pupils assigned to this account / school</div>";
            }

        }
        else {
            dailyMealdata += "<div class='alert alert-error'>No pupils assigned to this account / school</div>";
        }
        $("#spnGenPrint").addClass('hide');
        $("#divAccordMealOrderColl", "#mydailymeal").html(dailyMealdata);
        window.print();
        setTimeout(function() {
            serviceDailyMealColl();
        }, 1000);

    } else {
        logout(1);
    }


}
function changeMealCollectionStatus(f, opts)
{
    var btnCollectNewId = "", btnHdnCollectId = "";
    var btnCollectId = $(f).attr('id');
    if (opts == 1)
    {
        btnHdnCollectId = btnCollectId.replace("btn_0_collsts", "hdnMCSD-");
        btnCollectNewId = btnCollectId.replace("_0_", "_1_");
    }
    else
    {
        btnHdnCollectId = btnCollectId.replace("btn_1_collsts", "hdnMCSD-");
        btnCollectNewId = btnCollectId.replace("_1_", "_0_");
    }
    var hdnCsValue = ($("#" + btnHdnCollectId).val()).split("_");
    var coll_Status = (hdnCsValue[2] == 0) ? 1 : 0;
    var url = BACKENDURL + "user/update_daily_meal_collection_status";
    var data = {
        session_id: localStorage["SESSIONID"],
        collect_status: coll_Status,
        school_id: $('#ddlSchoolsMealcoll', "#mydailymeal").val(),
        pupil_id: hdnCsValue[0],
        order_id: hdnCsValue[1],
        fulfilment_date: localStorage["fulfilment_date"]
    };
    MakeAjaxCall(url, data, getDailyMealcoll1(f, btnCollectNewId));

}

function getDailyMealcoll1(oldBtn, newBtn)
{
    $(oldBtn).addClass("hide");
    $("#" + newBtn).removeClass("hide");
    $(oldBtn).parents().eq(1).css("display", "none"); // Added by Santhosh for Summary collection defect

}

// ***************Daily Meal Summary*************** //

function loadMealOrderSummary() {
    $("#txtWeekPicker").weekpicker();
    $("#tabMealOrder").bind("click", populateMealOrderSchools);
    $("#btnOrderSmryGo").click(function(e) {
        e.preventDefault();
        loadKitchenSummary();
    });
}
function populateMealOrderSchools() {
    var url = BACKENDURL + "user/get_schools_admins";
    var data = {
        session_id: localStorage["SESSIONID"],
        contract_id: localStorage.getItem("contractid"),
    };
    MakeAjaxCall(url, data, loadOrderSchools);
}

function loadOrderSchools(data) {
    if (data.error == 0) {
        if (data.schools_res.length == 0)
        {
            $('#NoUserSChoolMsg').show();
            $('#divUserMealOrder').hide();
            $('#MealordersummaryPrint').hide();
        }
        else {
            $('#NoUserSChoolMsg').hide();
            $('#divUserMealOrder').show();
            $('#MealordersummaryPrint').show();
            $('#ddlMealOrderSchools', "#divMealOrders").empty();
            var schoolStr = "";
            //populating prod school
            for (var nCount = 0; nCount < data.schools_res.length; nCount++) {
                var disabled = (data.schools_res[nCount].status == "0") ? " (disabled)" : "";
                schoolStr += "<option value=" + data.schools_res[nCount].school_id + ">" + data.schools_res[nCount].school_name + disabled + "</option>";
            }
            //$('#ddlMealOrderSchools', "#divMealOrders").append("<option value='0'>All Schools</option>");
            $('#ddlMealOrderSchools', "#divMealOrders").append(schoolStr);
            if (data.schools_res.length > 1)
                $('#ddlMealOrderSchools', "#divMealOrders").show();

            var curr = new Date;
            /*var firstday = new Date(curr.setDate(curr.getDate() - curr.getDay()));
             var lastday = new Date(curr.setDate(curr.getDate() - curr.getDay()+6));
             alert(firstday +","+lastday );*/
            var startDay = 1; //0=sunday, 1=monday etc.
            var d = curr.getDay(); //get the current day
            var weekStart = new Date(curr.valueOf() - (d <= 0 ? 7 - startDay : d - startDay) * 86400000); //rewind to start day
            var weekEnd = new Date(weekStart.valueOf() + 4 * 86400000);
            var firstWStart = getRoundDate(weekStart) + "/" + getRoundMonth(weekStart) + "/" + weekStart.getFullYear();
            //var firstWStart = getRoundMonth(weekStart) + "/" + getRoundDate(weekStart) + "/" + weekStart.getFullYear();
            var lastWEnd = getRoundDate(weekEnd) + "/" + getRoundMonth(weekEnd) + "/" + weekEnd.getFullYear();
            //var lastWEnd = getRoundMonth(weekEnd) + "/" + getRoundDate(weekEnd) + "/" + weekEnd.getFullYear();
            var dateformat = firstWStart + "-" + lastWEnd;
            $("#txtWeekPicker").val(dateformat);
            loadKitchenSummary();
        }
    }
}
function loadKitchenSummary()
{
    if ($('#ddlMealOrderSchools', "#divMealOrders").val() != "null")
    {
        var weekStartEndArr = $("#txtWeekPicker").val().split("-");
        var weekStartArr = weekStartEndArr[0];
        var weekEndArr = weekStartEndArr[1];
        var weekStartDate = weekStartArr.split("/");
        var weekEndDate = weekEndArr.split("/");
        var url = BACKENDURL + "user/get_meal_order_summary";
        var data = {
            session_id: localStorage["SESSIONID"],
            school_id: $('#ddlMealOrderSchools', "#divMealOrders").val(),
            start_date: weekStartDate[2] + "-" + weekStartDate[1] + "-" + weekStartDate[0],
            end_date: weekEndDate[2] + "-" + weekEndDate[1] + "-" + weekEndDate[0]
        };
        MakeAjaxCall(url, data, loadKitchenMealOrderSmry);
    }
}
function loadKitchenMealOrderSmry(data) {
    if (data.error == 0) {
        var Status_Msg = "";
        $("#lblStatusSchlSumryWarn").text('');
        if (data.meal_summary_res.close_details.closed_status == 1)
        {
            Status_Msg = "<strong>Remember! </strong>" + $("#ddlMealOrderSchools option:selected").text() + " was closed by " + data.meal_summary_res.close_details.closed_by + ", because '" + data.meal_summary_res.close_details.closed_reason + "'. Last day of closure is " + data.meal_summary_res.close_details.closed_till;
            $("#lblStatusSchlSumryWarn").removeClass("hide").append(Status_Msg);
        }
        else
        {
            if (!$("#lblStatusSchlSumryWarn").hasClass("hide"))
                $("#lblStatusSchlSumryWarn").addClass("hide");
        }
        if (data.meal_summary_res.total_res != undefined && data.meal_summary_res.total_res.length > 0) {
            $("#divMealOrderSmry", "#divMealOrders").show();
            $("#divMOMsg", "#divMealOrders").hide();
            $("#divOrderReportMsg", "#divMealOrders").html("<label>Kitchen Meal Order Summary as of " + data.meal_summary_res.date_time + " at School: " + $('#ddlMealOrderSchools option:selected', "#divMealOrders").text() + "</label>").show();
            $("#divMealSummaryData", "#divMealOrders").html('').show();
            //Populate the data...
            //Fix for defect 401 Meal order summary alignment table layout has been made fixed
            var mealData = "<table class='table table-hover table-striped' style=' table-layout: fixed; '><thead><tr>";
            for (var i = 0; i < data.meal_summary_res.total_res.length; i++) {


                var tot_msg = (data.meal_summary_res.total_res[i]['mt'] == 11) ? "Total Main Meals" : "Total Snacks";
                mealData += "<th colspan='2'>" + data.meal_summary_res.total_res[i]['d'] + " - " + tot_msg + " <span class='badge badge-info'>" + data.meal_summary_res.total_res[i]['t1'] + "</span> <small>vs</small> <span class='badge'>" + data.meal_summary_res.total_res[i]['t2'] + "</span></td><th style='min-width: 100px; text-align: center;'>Totals</td></tr></thead>";

                if ((i + 1) == data.meal_summary_res.total_res.length || data.meal_summary_res.total_res[i + 1]['d'] != data.meal_summary_res.total_res[i]['d'] || data.meal_summary_res.total_res[i + 1]['mt'] != data.meal_summary_res.total_res[i]['mt'])
                {
                    //Loop through and add the sub rows.
                    mealData += "<tbody>";
                    for (var j = 0; j < data.meal_summary_res.detail_res.length; j++) {
                        if (data.meal_summary_res.detail_res[j]['d'] == data.meal_summary_res.total_res[i]['d'] && data.meal_summary_res.total_res[i]['mt'] == data.meal_summary_res.detail_res[j]['mt']) {
                            var optTxt = toWords(data.meal_summary_res.detail_res[j]['os']);
                            optTxt = optTxt[0].toUpperCase() + optTxt.substring(1);
                            mealData += "<tr><td><strong>Option " + optTxt + "</strong></td><td>" + data.meal_summary_res.detail_res[j]['od'] + "</td><td style='text-align: center;'><span class='badge badge-info'>" + data.meal_summary_res.detail_res[j]['t1'] + "</span> <small>vs</small> <span class='badge'>" + data.meal_summary_res.detail_res[j]['t2'] + "</span></td></tr>";
                        }
                    }
                    mealData += "</tbody></table>";
                    $("#divMealSummaryData", "#divMealOrders").append(mealData);
                    //Fix for defect 401 Meal order summary alignment table layout has been made fixed
                    mealData = "<table class='table table-hover table-striped' style=' table-layout: fixed; '><thead><tr>";
                }
            }

            $("#MealordersummaryPrint").show();
        }
        else {
            $("#divMealOrderSmry", "#divMealOrders").hide();
            $("#divMealSummaryData", "#divMealOrders").html('').hide();
            $("#divMOMsg", "#divMealOrders").addClass("alert alert-error").html("Kitchen Meal Order Summary as of " + data.meal_summary_res.date_time + " at School: " + $('#ddlMealOrderSchools option:selected', "#divMealOrders").text() + " is not available.").show();
            $("#MealordersummaryPrint").hide();
        }
    } else
        logout(1)
}

function printMealordersummary()
{
    window.print();
}


/*function DMC_TimeCheck()
{
    var DMCDate = new Date();
    var DMC_day = DMCDate.getDate();
    var DMC_month = DMCDate.getMonth() + 1;
    var DMC_year = DMCDate.getFullYear();
    var DMC_CD = DMC_year + "-" + DMC_month + "-" + DMC_day;
    var ff_date = $("#dp_DMCdate").val().split("/");
    var DMC_SPD = ff_date[2] + "-" + ff_date[1] + "-" + ff_date[0];
    var cl_dt = new Date(DMC_CD);
    var cu_dt = new Date(DMC_SPD);
    return (cl_dt - cu_dt) / (1000 * 24 * 60 * 60);
}*/

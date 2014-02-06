var myOrderItems = [];
var myInitalOrderids = [];
var st_ids = [], chkTempPupil = [], chkTempPP = [];
var weekArr = ["", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday"];
var invoice_check = false;
var imp_msg = "";
function loadMyOrders() {
    $("#tabMyOrders").bind("click", populateMyOrderSchools);
    Date.prototype.addDays = function(days) {
        this.setDate(this.getDate() + days);
        return this;
    };
    $("#myorderNext", "#divMyOrders").click(function() {
        if (!$("#btnSaveMyOrders", "#divMyOrders").is(":disabled"))
            $("#btnSaveMyOrders", "#divMyOrders").click();
        if ($("#btnSaveMyOrders", "#divMyOrders").is(":disabled")) {
            var startDateArr = $("#hdnStartDate", "#divMyOrders").val().split("-");
            var newDate = new Date(startDateArr[0], (startDateArr[1] - 1), startDateArr[2], 0, 0, 0, 0).addDays(7);
            var nextweekDate = newDate.getFullYear() + "-" + pad((newDate.getMonth()) + 1) + "-" + pad(newDate.getDate());
            $("#hdnStartDate", "#divMyOrders").val(nextweekDate);
            getOrderPupils(1, nextweekDate, nextweekDate);
        }
    });
    // Add Temporary Pupils - Meal orders 
    $("#btnTempPupilClose", "#divOrderTempPupil").bind("click", cancelPupil_data);
    $("#xCloseTempPupil", "#divOrderTempPupil").bind("click", cleardata);
    $("#btnTempPupilSearch", "#divOrderTempPupil").bind("click", Srv_SrchTempPPl);
    $("#btnTempPupilSubmit", "#divOrderTempPupil").bind("click", Srv_SaveTempPPl);
}
function populateMyOrderSchools() {
    chkTempPupil = [];
    localStorage["act_orderweek"] = "1";
    $("#btnSaveMyOrders", "#divMyOrders").attr("disabled", "disabled").text("Saved");
    var url, data;
    if (localStorage["sch_adm"] == "1")
    {
        url = BACKENDURL + "user/get_schools_admins";
        data = {
            session_id: localStorage["SESSIONID"],
            contract_id: localStorage["contractid"]
        };
    }
    else
    {
        $("#btnAddtempPupil", "#divOrderSchools").addClass("hide");
        url = BACKENDURL + "user/get_schools_orders";
        data = {
            session_id: localStorage["SESSIONID"],
            contract_id: localStorage["contractid"]
        };
    }
    MakeAjaxCall(url, data, loadMyOrderSchools);
}
function loadMyOrderSchools(data) {
    if (data.error == 0) {
        $('#ddlOrderSchools', "#divMyOrders").empty();
        var schoolStr = "";
        //populating prod school
        for (var nCount = 0; nCount < data.schools_res.length; nCount++) {
            var disabled = (data.schools_res[nCount].status == "0") ? " (disabled)" : "";
            schoolStr += "<option value=" + data.schools_res[nCount].school_id + ">" + data.schools_res[nCount].school_name + disabled + "</option>";
        }
        var lastSelValue;
        $('#ddlOrderSchools', "#divMyOrders").append(schoolStr).bind("click", function(e) {
            lastSelValue = $(this).val();
        }).on('change', function() {
            chkTempPupil = [];
            localStorage["act_orderweek"] = "1";
            if (!$("#btnSaveMyOrders", "#divMyOrders").is(":disabled"))
                $("#btnSaveMyOrders", "#divMyOrders").click();
            if (!$("#btnSaveMyOrders", "#divMyOrders").is(":disabled"))
                $(this).val(lastSelValue);
            else
                getOrderPupils(1);
        });
        getOrderPupils(1);
        if (data.schools_res.length > 1)
        {
//$('#divOrderSchools', "#divMyOrders").show();
            $("#lblSchlOrder", "#divOrderSchools").show();
            $("#Spn_OrderSchools", "#divOrderSchools").show();
        }
        else
        {
//$('#divOrderSchools', "#divMyOrders").hide();
            $("#lblSchlOrder", "#divOrderSchools").hide();
            $("#Spn_OrderSchools", "#divOrderSchools").hide();
        }
    }
}

function pad(n) {
    return n < 10 ? '0' + n : n;
}

function getOrderPupils(week_day, current_date, current_week) {
    var schoolId = $('#ddlOrderSchools', "#divMyOrders").val();
    if (schoolId != null) {
        $("#lblErrSaveMyOrders", "#divMyOrders").hide().html('');
        $("#divOrderWeekHeading", "#divMyOrders").show();
        $("#btnSaveMyOrders", "#divMyOrders").show();
        $("#accMainMyorders", "#divMyOrders").show();
        week_day = (week_day == undefined) ? 1 : week_day;
        if (current_date == undefined) {
            var dif, today = new Date(); // Today's date
            dif = (today.getDay() + 6) % 7; // Number of days to subtract
            d = new Date(today - dif * 24 * 60 * 60 * 1000); // Do the subtraction
            current_date = d.getFullYear() + "-" + pad((d.getMonth() + 1)) + "-" + pad(d.getDate());
            $("#hdnStartDate", "#divMyOrders").val(current_date);
        }
        current_week = (current_week == undefined) ? current_date : current_week;
        $.support.cors = true;
        $.ajax({
            url: BACKENDURL + "user/get_pupils_order_menu",
            type: "post",
            data: {
                session_id: localStorage["SESSIONID"],
                contract_id: localStorage.getItem("contractid"),
                school_id: schoolId,
                week_day: week_day,
                current_date: current_date,
                current_week: current_week,
                temp_pupil: chkTempPupil
            },
            dataType: "json",
            crossDomain: true,
            success: function(data) {
                if (data.session_status) {
                    var Status_Msg = "";
                    $("#lblCloseSchlWarn").text('');
                    /*var d = new Date();
                     var nowDate = d.getFullYear()+ "-" + (d.getMonth()+1) + "-" + d.getDate() ;
                     var cur_diff = date_diff(current_date, nowDate);*/
                    if (data.order_menu_res.close_details.closed_status == 1)
                    {
                        var date1 = (data.order_menu_res.close_details.closed_till).split("/");
                        var close_till = date1[2] + "-" + date1[1] + "-" + date1[0];
                        var diff = date_diff(close_till, current_date)
                        if (diff >= 0)
                        {
                            Status_Msg = "<strong>Remember! </strong>" + $("#ddlOrderSchools option:selected").text() + " was closed by " + data.order_menu_res.close_details.closed_by + ", because '" + data.order_menu_res.close_details.closed_reason + "'. Last day of closure is " + data.order_menu_res.close_details.closed_till;
                            $("#lblCloseSchlWarn").removeClass("hide").append(Status_Msg);
                            localStorage["closed_stat"] = "1";
                            //$("#btnAddtempPupil", "#divOrderSchools").attr("disabled", true);
                        }
                        else
                        {
                            localStorage["closed_stat"] = "0";
                            //$("#btnAddtempPupil", "#divOrderSchools").attr("disabled", false);
                            if (!$("#lblCloseSchlWarn").hasClass("hide"))
                                $("#lblCloseSchlWarn").addClass("hide");
                        }
                    }
                    else
                    {
                        localStorage["closed_stat"] = "0";
                        //$("#btnAddtempPupil", "#divOrderSchools").attr("disabled", false);
                        if (!$("#lblCloseSchlWarn").hasClass("hide"))
                            $("#lblCloseSchlWarn").addClass("hide");
                    }
                    populatePupilDetails(data, week_day, current_date, schoolId);
                }
                else
                    logout();
            },
            error: function(xhr, textStatus, error) {
                alert(error);
            }
        });
    } else {
//$("#divOrderSchools", "#divMyOrders").hide();
        $("#lblSchlOrder", "#divOrderSchools").hide();
        $("#Spn_OrderSchools", "#divOrderSchools").hide();
        $("#divOrderWeekHeading", "#divMyOrders").hide();
        $("#btnSaveMyOrders", "#divMyOrders").hide();
        $("#accMainMyorders", "#divMyOrders").hide();
        $("#lblErrSaveMyOrders", "#divMyOrders").show().html("No pupils have been assigned to this account");
    }

}

function populatePupilDetails(data, week_day, current_date, schoolId) {
    $("#lblErrSaveMyOrders", "#divMyOrders").hide();
    if (data.error == 0) {
        var school_name = $('#ddlOrderSchools option:selected', "#divMyOrders").text();
        var tminus = data['order_menu_res'].tminus;
        var tminusTxt = "";
        if (tminus == 24)
            tminusTxt = "12:00 Noon on before day";
        else if (12 - tminus < 0)
            tminusTxt = parseInt(24 - tminus) + ":00 PM before day";
        else
            tminusTxt = parseInt(12 - tminus) + ":00 AM on day";
        $("#lblMyOrdersHead", "#divMyOrders").html(school_name.replace(" (disabled)", "") + " Deadline for changes is " + tminusTxt + " of cooking<input type='hidden' id='hdnVat' name='hdnVat' value='" + data['order_menu_res'].vat + "'>");
        if (school_name.substring(school_name.length - 10) == "(disabled)") {
            var orderStr = "";
            for (var week = 1; week <= 5; week++) {
                var inStr = (week == week_day) ? " in " : "";
                //localStorage["act_orderweek"] == (week == week_day) ? " in " : "";
                orderStr += "<div class='accordion-group'><div class='accordion-heading'><a class='accordion-toggle collapsed' href='javascript:void(0);' style='text-decoration: none !important;background-color: #bebebe; background-image: -moz-linear-gradient(top, #cecece, #bebebe);background-image: -webkit-gradient(linear, 0 0, 0 100%, from(#cecece), to(#bebebe));background-image: -webkit-linear-gradient(top, #cecece, #bebebe);background-image: -o-linear-gradient(top, #cecece, #bebebe);background-image: linear-gradient(to bottom, #cecece, #bebebe);background-repeat: repeat-x;'><span class='icon-calendar icon-white'></span>  " + weekArr[week] + " <span id='spnW" + week + "Myorder'></span></a></div></div>";
            }
            $("#accMainMyorders", "#divMyOrders").html(orderStr);
            $("#spnW1Myorder", "#divMyOrders").html(data['order_menu_res'].mon);
            $("#spnW2Myorder", "#divMyOrders").html(data['order_menu_res'].tue);
            $("#spnW3Myorder", "#divMyOrders").html(data['order_menu_res'].wed);
            $("#spnW4Myorder", "#divMyOrders").html(data['order_menu_res'].thu);
            $("#spnW5Myorder", "#divMyOrders").html(data['order_menu_res'].fri);
        }
        else {
            var orderStr = "";
            for (var week = 1; week <= 5; week++) {
                var inStr = (week == week_day) ? " in " : "";
                orderStr += "<div class='accordion-group'><div class='accordion-heading'><a class='accordion-toggle' data-toggle='collapse' data-parent='#accMainMyorders' href='#divaccMyOrders" + week + "' style='text-decoration: none !important;'><span class='icon-calendar icon-white'></span>  " + weekArr[week] + " <span id='spnW" + week + "Myorder'></span></a></div><div id='divaccMyOrders" + week + "' class='accordion-body collapse" + inStr + "'><div class='accordion-inner'><div class='accordion' id='divPupMyOrders" + week + "'></div></div></div></div>";
            }
            $("#accMainMyorders", "#divMyOrders").html(orderStr);
            $("div[id^=divaccMyOrders]", "#divMyOrders").on('show', function(e) {
                var week_day = $(this).attr("id").replace("divaccMyOrders", "");
                localStorage["act_orderweek"] = week_day;
                var startDateArr = $("#hdnStartDate", "#divMyOrders").val().split("-");
                var newDate = new Date(startDateArr[0], (startDateArr[1] - 1), startDateArr[2], 0, 0, 0, 0).addDays(parseInt(week_day) - 1);
                var thisDate = newDate.getFullYear() + "-" + pad((newDate.getMonth()) + 1) + "-" + pad(newDate.getDate());
                if (!$("#btnSaveMyOrders", "#divMyOrders").is(":disabled"))
                    $("#btnSaveMyOrders", "#divMyOrders").click();
                getOrderPupils(week_day, thisDate, $("#hdnStartDate", "#divMyOrders").val());
            });
            $("div[id^=divaccMyOrders]", "#divMyOrders").on('hidden', function(e) {

            });
            //populate the date next to monday
            $("#spnW1Myorder", "#divMyOrders").html(data['order_menu_res'].mon);
            $("#spnW2Myorder", "#divMyOrders").html(data['order_menu_res'].tue);
            $("#spnW3Myorder", "#divMyOrders").html(data['order_menu_res'].wed);
            $("#spnW4Myorder", "#divMyOrders").html(data['order_menu_res'].thu);
            $("#spnW5Myorder", "#divMyOrders").html(data['order_menu_res'].fri);
            if (data['order_menu_res'].pre_exits == 1)
                $("#myorderPrevious", "#divMyOrders").removeClass('disabled').off('click').on('click', function() {
//If btn save is enabled then save the information
                    if (!$("#btnSaveMyOrders", "#divMyOrders").is(":disabled"))
                        $("#btnSaveMyOrders", "#divMyOrders").click();
                    if ($("#btnSaveMyOrders", "#divMyOrders").is(":disabled")) {
                        var startDateArr = $("#hdnStartDate", "#divMyOrders").val().split("-");
                        var newDate = new Date(startDateArr[0], (startDateArr[1] - 1), startDateArr[2], 0, 0, 0, 0).addDays(-7);
                        var nextweekDate = newDate.getFullYear() + "-" + pad((newDate.getMonth()) + 1) + "-" + pad(newDate.getDate());
                        $("#hdnStartDate", "#divMyOrders").val(nextweekDate);
                        getOrderPupils(1, nextweekDate, nextweekDate);
                    }
                });
            else
                $("#myorderPrevious", "#divMyOrders").addClass('disabled').off('click');
            //Populate the students information
            var pupilstr = "";
            var st = 0, inStr = "";
            st_ids = [];
            if (data['order_menu_res'].res_temp_pupils != undefined)
            {
                for (var i = 0; i < data['order_menu_res'].res_temp_pupils.length; i++) {
                    var pupil_id = data['order_menu_res'].res_temp_pupils[i].pupil_id.replace("/", "");
                    var st_class = data['order_menu_res'].res_temp_pupils[i].class_name.replace("_name", "_status");
                    var icon_show = (data['order_menu_res'].res_temp_pupils[i].order_exists == 1) ? '' : 'hide';
                    //US 312 Various UI Tidies item 22 icon position change
                    var disabledHeading = "<a class='accordion-toggle' data-toggle='collapse' data-parent='#divPupMyOrders" + week_day + "' href='#collapse_" + week_day + "_" + pupil_id + "' style='text-decoration: none !important;'><i id='ico_" + week_day + "_" + pupil_id + "' class='icon-check icon-white " + icon_show + "' ></i>" + " " + data['order_menu_res'].res_temp_pupils[i].fname + " " + data['order_menu_res'].res_temp_pupils[i].lname + " (£<span id='spnBal_" + week_day + "_" + pupil_id + "'>" + data['order_menu_res'].res_temp_pupils[i].balance + "</span> balance) <span id='spnGen_" + week_day + "_" + pupil_id + "' class='hide'>&nbsp;&nbsp;Loading&nbsp;<img src='img/ajax-loader2.gif' /></span></a>";
                    if (data['order_menu_res'].res_temp_pupils[i].student_status == 0 || data['order_menu_res'].res_temp_pupils[i].year_status == 0 || data['order_menu_res'].res_temp_pupils[i][st_class] == 0)
                        disabledHeading = "<a class='accordion-toggle collapsed' href='javascript:void(0);' style='text-decoration: none !important; background-color: #bebebe; background-image: -moz-linear-gradient(top, #cecece, #bebebe);background-image: -webkit-gradient(linear, 0 0, 0 100%, from(#cecece), to(#bebebe));background-image: -webkit-linear-gradient(top, #cecece, #bebebe);background-image: -o-linear-gradient(top, #cecece, #bebebe);background-image: linear-gradient(to bottom, #cecece, #bebebe);background-repeat: repeat-x;'>" + data['order_menu_res'].res_temp_pupils[i].fname + " " + data['order_menu_res'].res_temp_pupils[i].lname + " (£" + data['order_menu_res'].res_temp_pupils[i].balance + " balance)</a>";
                    else {
                        inStr = (st == 0) ? " in " : "";
                        st_ids[st++] = pupil_id;
                    }

                    pupilstr += "<div class='accordion-group'><div class='accordion-heading'>" + disabledHeading + "</div>";
                    pupilstr += "<input type='hidden' id='hdnFsm_" + week_day + "_" + pupil_id + "' value='" + data['order_menu_res'].res_temp_pupils[i].fsm + "'><input type='hidden' id='hdnAdult_" + week_day + "_" + pupil_id + "' value='" + data['order_menu_res'].res_temp_pupils[i].adult + "'><input type='hidden' id='hdnInv_" + week_day + "_" + pupil_id + "' value='" + data['order_menu_res'].invoice + "'/><input type='hidden' id='hdnBal_" + week_day + "_" + pupil_id + "' value='" + data['order_menu_res'].res_temp_pupils[i].balance + "'><input type='hidden' id='hdnOrderid_" + week_day + "_" + pupil_id + "' />";
                    pupilstr += "<div id='collapse_" + week_day + "_" + pupil_id + "' class='accordion-body collapse " + inStr + "'><div class='accordion-inner'>";
                    //pupilstr +="<button class='btn btn-primary' id='btnContinue_"+week_day+"' name='btnContinue_"+week_day+"' disabled='disabled' style='float:right'>Continue</button><br/>";
                    pupilstr += "</div></div></div>";
                }
            }
            for (var i = 0; i < data['order_menu_res'].res_pupils.length; i++) {
                var pupil_id = data['order_menu_res'].res_pupils[i].pupil_id.replace("/", "");
                var st_class = data['order_menu_res'].res_pupils[i].class_name.replace("_name", "_status");
                var icon_show = (data['order_menu_res'].res_pupils[i].order_exists == 1) ? '' : 'hide';
                //US 312 Various UI Tidies item 22 icon position change
                var disabledHeading = "<a class='accordion-toggle' data-toggle='collapse' data-parent='#divPupMyOrders" + week_day + "' href='#collapse_" + week_day + "_" + pupil_id + "' style='text-decoration: none !important;'><i id='ico_" + week_day + "_" + pupil_id + "'  class='icon-check icon-white " + icon_show + "' ></i>" + " " + data['order_menu_res'].res_pupils[i].fname + " " + data['order_menu_res'].res_pupils[i].lname + " (£<span id='spnBal_" + week_day + "_" + pupil_id + "'>" + data['order_menu_res'].res_pupils[i].balance + "</span> balance) </span><span id='spnGen_" + week_day + "_" + pupil_id + "' class='hide'>&nbsp;&nbsp;Loading&nbsp;<img src='img/ajax-loader2.gif' /></span></a>";
                if (data['order_menu_res'].res_pupils[i].student_status == 0 || data['order_menu_res'].res_pupils[i].year_status == 0 || data['order_menu_res'].res_pupils[i][st_class] == 0)
                    disabledHeading = "<a class='accordion-toggle collapsed' href='javascript:void(0);' style='text-decoration: none !important; background-color: #bebebe; background-image: -moz-linear-gradient(top, #cecece, #bebebe);background-image: -webkit-gradient(linear, 0 0, 0 100%, from(#cecece), to(#bebebe));background-image: -webkit-linear-gradient(top, #cecece, #bebebe);background-image: -o-linear-gradient(top, #cecece, #bebebe);background-image: linear-gradient(to bottom, #cecece, #bebebe);background-repeat: repeat-x;'>" + data['order_menu_res'].res_pupils[i].fname + " " + data['order_menu_res'].res_pupils[i].lname + " (£" + data['order_menu_res'].res_pupils[i].balance + " balance)</a>";
                else {
                    inStr = (st == 0) ? " in " : "";
                    st_ids[st++] = pupil_id;
                }

                pupilstr += "<div class='accordion-group'><div class='accordion-heading'>" + disabledHeading + "</div>";
                pupilstr += "<input type='hidden' id='hdnFsm_" + week_day + "_" + pupil_id + "' value='" + data['order_menu_res'].res_pupils[i].fsm + "'><input type='hidden' id='hdnAdult_" + week_day + "_" + pupil_id + "' value='" + data['order_menu_res'].res_pupils[i].adult + "'><input type='hidden' id='hdnInv_" + week_day + "_" + pupil_id + "' value='" + data['order_menu_res'].invoice + "'/><input type='hidden' id='hdnBal_" + week_day + "_" + pupil_id + "' value='" + data['order_menu_res'].res_pupils[i].balance + "'><input type='hidden' id='hdnOrderid_" + week_day + "_" + pupil_id + "' />";
                pupilstr += "<div id='collapse_" + week_day + "_" + pupil_id + "' class='accordion-body collapse " + inStr + "'><div class='accordion-inner'>";
                //pupilstr +="<button class='btn btn-primary' id='btnContinue_"+week_day+"' name='btnContinue_"+week_day+"' disabled='disabled' style='float:right'>Continue</button><br/>";
                pupilstr += "</div></div></div>";
            }
            if (data['order_menu_res'].res_temp_pupils != undefined)
            {
                if ((data['order_menu_res'].res_pupils.length > 0) || (data['order_menu_res'].res_temp_pupils.length > 0))
                    $("#divPupMyOrders" + week_day, "#divMyOrders").html(pupilstr);
                else
                    $("#divPupMyOrders" + week_day, "#divMyOrders").html("<div class='alert alert-error'>No Pupils have assigned for this school</div>");
            }
            else
            {
                if (data['order_menu_res'].res_pupils.length > 0)
                    $("#divPupMyOrders" + week_day, "#divMyOrders").html(pupilstr);
                else
                    $("#divPupMyOrders" + week_day, "#divMyOrders").html("<div class='alert alert-error'>No Pupils have assigned for this school</div>");
            }
            $("div[id^=collapse]", "#divMyOrders").on('show', function(e) {
                e.stopPropagation();
                if (!$("#btnSaveMyOrders", "#divMyOrders").is(":disabled"))
                    $("#btnSaveMyOrders", "#divMyOrders").click();
                getOrderMenuDetails(this, data['order_menu_res'].active_menu, data['order_menu_res'].week_cycle, schoolId, week_day, current_date, data['order_menu_res'].iseditable);
            });
            $("div[id^=collapse]", "#divMyOrders").on('hidden', function(e) {
                var divid = $(this).attr("id");
                $("#" + divid.replace("collapse", "spnGen")).hide();
                e.stopPropagation();
                if (!$("#btnSaveMyOrders", "#divMyOrders").is(":disabled"))
                    $("#btnSaveMyOrders", "#divMyOrders").click();
                $("#btnSaveMyOrders", "#divMyOrders").attr("disabled", "disabled").text('Saved');
                $(this).html("<div class='accordion-inner'></div>");
            });
            if (st_ids.length > 0)
                getOrderMenuDetails("#collapse_" + week_day + "_" + st_ids[0], data['order_menu_res'].active_menu, data['order_menu_res'].week_cycle, schoolId, week_day, current_date, data['order_menu_res'].iseditable);
            //$(".accordion-body[id='collapse_"+week_day+"_"+st_ids[0]+"']","#divPupMyOrders"+ week_day).collapse('show');
        }
    } else
        logout(1);
}

function getOrderMenuDetails(f, menu_id, week_cycle, schoolId, week_day, current_date, iseditable) {
    $("#lblErrSaveMyOrders", "#divMyOrders").hide();
    var divid = $(f).attr("id");
    var spnid = divid.replace("collapse", "spnGen");
    $("#" + spnid, "#divMyOrders").show();
    var pupil_id = divid.split("_")[2];
    var curr_tm = "";
    // to check temporary pupil or not
    var pupilid_fmt = pupil_id.substring(0, 3) + "/" + pupil_id.substring(3);
    $.support.cors = true;
    $.ajax({
        url: BACKENDURL + "common/get_current_date_time",
        type: "post",
        dataType: "json",
        crossDomain: true,
        async: false,
        success: function(data) {
            curr_tm = data.syear + "-" + data.smonth + "-" + (data.sday) + "T" + data.shour + ":" + data.smin;
        }
    });
    if (chkTempPupil.length > 0)
    {
        if ($.inArray(pupilid_fmt, chkTempPupil) > -1)
        {
            var deadline_tm = current_date + "T24:00";
            if (curr_tm < deadline_tm)
                iseditable = "yes";
        }
    }
    if (localStorage["sch_adm"] == "1")
    {
        var deadline_tm = current_date + "T24:00";
        if (curr_tm < deadline_tm)
            iseditable = "yes";
    }
    $.support.cors = true;
    $.ajax({
        url: BACKENDURL + "user/get_pupils_order_menu_details",
        type: "post",
        data: {
            session_id: localStorage["SESSIONID"],
            contract_id: localStorage.getItem("contractid"),
            menu_id: menu_id,
            week_cycle: week_cycle,
            school_id: schoolId,
            week_day: week_day,
            current_date: current_date,
            pupil_id: pupilid_fmt,
            iseditable: iseditable,
        },
        dataType: "json",
        crossDomain: true,
        success: function(data) {
            if (data.session_status) {
                if (data.error == 0) {
                    $("#" + spnid).hide();
                    var isAdult = $("#" + divid.replace("collapse", "hdnAdult")).val();
                    var vat = $("#hdnVat").val();
                    var isInvoice = $("#" + divid.replace("collapse", "hdnInv")).val();
                    var isFsm = $("#" + divid.replace("collapse", "hdnFsm")).val();
                    var stBal = $("#" + divid.replace("collapse", "hdnBal")).val();
                    //Clear the cart and disable the save button
                    myOrderItems = [];
                    $("#btnSaveMyOrders", "#divMyOrders").attr("disabled", "disabled").text('Saved');
                    //If the order is edited then display for cancellation only.
                    if (data.order_menu_res.order.length > 0 && data.order_menu_res.order[0].oe == 1 && iseditable == 'yes') {
                        var menuStr = "";
                        var mainMealStr = "";
                        var snackStr = "";
                        for (var k = 0; k < data.order_menu_res.order.length; k++) {
                            localStorage["Sts_msg"] = data.order_menu_res.order[k].oem;
                            if (data.order_menu_res.order[k].m == 11)
                                mainMealStr += data.order_menu_res.order[k].d + "<br/>";
                            else if (data.order_menu_res.order[k].m == 12)
                                snackStr += data.order_menu_res.order[k].d + "<br/>";
                        }
                        if (localStorage["Sts_msg"] != "")
                            imp_msg = "This order can not be edited because : '" + localStorage["Sts_msg"] + "' Please cancel and reorder";
                        else
                            imp_msg = "This order can not be edited . Please cancel and reorder";
                        mainMealStr = (mainMealStr.length > 0) ? "<div class='row-fluid'><div class='span2'><label style='font-weight: bold;'>Main Meal:</label></div><div class='span10 display-values'><label>" + mainMealStr.substring(0, mainMealStr.length - 5) + " (desert included)</label></div></div>" : "";
                        snackStr = (snackStr.length > 0) ? "<div class='row-fuild'><div class='span2'><label style='font-weight: bold;'>Snack Option: </label></div><div class='span10 display-values'><label>" + snackStr.substring(0, snackStr.length - 5) + "</label></div></div>" : "";
                        menuStr = "<div class='accordion-inner'><div style='width:100%;'>" + mainMealStr + snackStr + "<br/><div id='ordercl_" + week_day + "_" + pupil_id + "' class='alert alert-error' style='margin-top:10px;'>" + imp_msg + "</div><div class='row' style='float:right;'><button class='btn' id='btnCancel_" + week_day + "_" + pupil_id + "' onClick='javascript:return false;'>Cancel Order</button></div></div></div>";
                        $(f).html(menuStr);
                        var order_id = (data.order_menu_res.order.length > 0) ? data.order_menu_res.order[0].order_id : "";
                        $("#hdnOrderid_" + week_day + "_" + pupil_id, "#divMyOrders").val(order_id);
                        $("#ico_" + week_day + "_" + pupil_id, "#divMyOrders").show();
                        $("#btnCancel_" + week_day + "_" + pupil_id, "#divMyOrders").click(function() {
                            cancelMyOrders(week_day, pupil_id, current_date);
                        });
                    }
                    else {
                        if (iseditable == 'yes') {
                            myInitalOrderids = [];
                            if (data.order_menu_res.menu.length > 0) {
//if order exists then populate in inital order items array.
                                for (var k = 0; k < data.order_menu_res.order.length; k++) {
                                    myInitalOrderids[k] = data.order_menu_res.order[k].smid;
                                }

//if the order contains main meal then disable all main meal items.
                                var isMainExists = (data.order_menu_res.order.length > 0 && data.order_menu_res.order[0].m == 11) ? 1 : 0;
                                var menuStr = "<div class='accordion-inner'>";
                                for (var i = 0; i < data.order_menu_res.menu.length; i++) {
                                    if (i == 0 || data.order_menu_res.menu[i].m != data.order_menu_res.menu[i - 1].m) {
                                        if (data.order_menu_res.menu[i].m == 13) // For desert
                                            menuStr += "<h3>" + MealArray[data.order_menu_res.menu[i].m] + " (one of)</h3>";
                                        else
                                            menuStr += "<h3>" + MealArray[data.order_menu_res.menu[i].m] + "</h3>";
                                    }
                                    menuStr += "<ul class='thumbnails'>";
                                    for (var j = i; j < (i + 4); j++) {
                                        if (j == data.order_menu_res.menu.length || data.order_menu_res.menu[i].m != data.order_menu_res.menu[j].m)
                                            break;
                                        menuStr += "<li class='span3'><div class='thumbnail'><p><label class='aligncenter' style='width: 94%;height:90px ;resize: none;'>" + data.order_menu_res.menu[j].d + "</label></p>";
                                        if (data.order_menu_res.menu[i].m != 13) {// For desert
                                            var cost = (isAdult == 0) ? data.order_menu_res.menu[j].c : (parseFloat(data.order_menu_res.menu[j].c) + (data.order_menu_res.menu[j].c * vat / 100)).toFixed(2);
                                            var disabledBtn = "";
                                            //Disable the items where the price is greater than the available balance if FSM is not set
                                            if (isFsm == 0 && parseFloat(stBal) < parseFloat(cost))
                                                disabledBtn = "disabled ='disabled'";
                                            else if (data.order_menu_res.menu[i].m != 11 && isFsm == 1 && parseFloat(stBal) < parseFloat(cost)) //For FSM main meal is free and other need to be deduct from account
                                                disabledBtn = "disabled ='disabled'";
                                            //Disable all main meals if the order contains main meals.
                                            if (data.order_menu_res.menu[i].m == 11 && isMainExists == 1)
                                                disabledBtn = "disabled ='disabled'";
                                            var isAddBtn = "";
                                            var isAddedBtn = " hide ";
                                            var isInvoice_school = "";
                                            for (var k = 0; k < data.order_menu_res.order.length; k++) {
                                                isInvoice_school = (data.order_menu_res.order[k].inv == 1) ? "checked" : isInvoice_school;
                                                if (data.order_menu_res.menu[j].mid == data.order_menu_res.order[k].smid) {
                                                    isAddBtn = " hide ";
                                                    isAddedBtn = " ";
                                                    //Need to add the items in myorder items and process that....
                                                    myOrderItems.push({
                                                        scmid: data.order_menu_res.order[k].smid,
                                                        mtype: data.order_menu_res.order[k].m,
                                                        netAmt: cost
                                                    });
                                                    break;
                                                }
                                            }
                                            var order_id = (data.order_menu_res.order.length > 0) ? data.order_menu_res.order[0].order_id : "";
                                            $("#hdnOrderid_" + week_day + "_" + pupil_id, "#divMyOrders").val(order_id);
                                            if (data.order_menu_res.order.length > 0)
                                                $("#ico_" + week_day + "_" + pupil_id, "#divMyOrders").show();
                                            else
                                                $("#ico_" + week_day + "_" + pupil_id, "#divMyOrders").hide();
                                            //If the invoce to school is checked then we shouldn't disable any of the add buttons
                                            disabledBtn = (isInvoice_school == "checked" && !(data.order_menu_res.menu[i].m == 11 && isMainExists == 1)) ? "" : disabledBtn;
                                            var display_cost = (data.order_menu_res.menu[i].m == 11 && isFsm == 1) ? "<label class='control-label span3'>Free</label>" : "<label class='control-label span3' id='lblC_" + data.order_menu_res.menu[j].m + "_" + data.order_menu_res.menu[j].mid + "'>£" + cost + "</label>";
                                            menuStr += "<table style='width:100%'><tr><td><label class='control-label span3 hide' id='lblCost_" + data.order_menu_res.menu[j].m + "_" + data.order_menu_res.menu[j].mid + "'>£" + cost + "</label><input type='hidden' id='hdnCo_" + data.order_menu_res.menu[j].mid + "' value='" + data.order_menu_res.menu[j].c + "'/>" + display_cost + "<input type='hidden' id='hdnVat_" + data.order_menu_res.menu[j].mid + "' value='" + (data.order_menu_res.menu[j].c * vat / 100).toFixed(2) + "'/><input type='hidden' id='hdnAmt_" + data.order_menu_res.menu[j].mid + "' value='" + cost + "'/> </td>";
                                            menuStr += "<td class='alignright' nowrap='nowrap'>";
                                            menuStr += "<button class='btn btn-primary btn-small " + isAddBtn + "' " + disabledBtn + " id='btnadd_" + data.order_menu_res.menu[j].m + "_" + data.order_menu_res.menu[j].mid + "' style='margin-top:0px;' onClick='javascript:return addOrders(this," + week_day + ",\"" + pupil_id + "\"," + isAdult + "," + isFsm + ");return false;'><i class='icon-shopping-cart icon-white'></i> Add</button>";
                                            menuStr += "<button class='btn btn-success btn-small " + isAddedBtn + "' id='btnadded_" + data.order_menu_res.menu[j].m + "_" + data.order_menu_res.menu[j].mid + "' style='margin-top:0px;' onmouseOver='javascript:changeAdded(this,1);' onmouseOut='javascript:changeAdded(this,0);' onClick='javascript:removeOrders(this," + week_day + ",\"" + pupil_id + "\"," + isAdult + "," + isFsm + ");'><i class='icon-ok icon-white'></i> Added</button>";
                                            menuStr += "</td></tr></table>";
                                        } else {
                                            var showDesert = (isMainExists == 1) ? "" : "hide";
                                            menuStr += "<p class='alignright'><label class='" + showDesert + "' id='lbldes_" + data.order_menu_res.menu[j].mid + "'><img style='width:20px;margin:0 auto;' src='img/pass.png'> Included</label></p>";
                                        }
                                    }
                                    i = (j - 1);
                                    menuStr += "</ul>";
                                }
                                menuStr += "<div id='lblErr_" + week_day + "_" + pupil_id + "' class='alert alert-error hide'>Order rejected because of insufficient funds.</div>";
                                menuStr += "<div class='control-group' style='float:right;'><div class='controls'>";
                                isInvoice_school = (invoice_check) ? "" : isInvoice_school;
                                //var chkInvoice = (order_id!="" && )
                                if (isAdult == 1 && isInvoice == 1)
                                    menuStr += "<label class='checkbox' style='float:right;margin-bottom:15px;'><input type='checkbox' " + isInvoice_school + " value='1' onchange='javascript:disableAdd(this," + week_day + ",\"" + pupil_id + "\");' id='chkInvoice_" + week_day + "_" + pupil_id + "'>Invoice to school</label><br />";
                                var cancelShow = (order_id == "") ? "hide" : "";
                                menuStr += "<button class='btn " + cancelShow + "' id='btnCancel_" + week_day + "_" + pupil_id + "' onClick='javascript:return false;'>Cancel Order</button>&nbsp;&nbsp;";
                                var btnConHide = (week_day == 5 && pupil_id == st_ids[st_ids.length - 1]) ? " hide " : "";
                                menuStr += "<button class='btn btn-primary " + btnConHide + "' id='btnContinue_" + week_day + "_" + pupil_id + "' style='float:right' disabled='disabled' onClick='javascript:return false;'>Continue</button>";
                                menuStr += "</div></div></div>";
                                $(f).html(menuStr);
                                if (invoice_check && order_id != "") {
                                    $("#btnContinue_" + week_day + "_" + pupil_id, "#divMyOrders").removeAttr("disabled");
                                    $("#btnSaveMyOrders", "#divMyOrders").removeAttr("disabled").text("Save");
                                }

                                invoice_check = false;
                                $("#btnContinue_" + week_day + "_" + pupil_id, "#divMyOrders").click(function() {
                                    var rflag = saveMyOrders(week_day, pupil_id, current_date, isAdult);
                                    if (rflag) {
                                        for (var stid = 0; stid < st_ids.length; stid++) {
                                            if (pupil_id == st_ids[stid])
                                                if (stid == st_ids.length - 1) {
//collapse entire day and open next day.
                                                    $(".accordion-body[id='divaccMyOrders" + week_day + "']", "#accMainMyorders").collapse('hide');
                                                    setTimeout(function() {
                                                        $(".accordion-body[id='divaccMyOrders" + (parseInt(week_day) + 1) + "']", "#accMainMyorders").collapse('show');
                                                    }, 500);
                                                    break;
                                                } else {
                                                    $(".accordion-body[id='collapse_" + week_day + "_" + pupil_id + "']", "#divPupMyOrders" + week_day).collapse('hide');
                                                    setTimeout(function() {
                                                        $(".accordion-body[id='collapse_" + week_day + "_" + st_ids[stid + 1] + "']", "#divPupMyOrders" + week_day).collapse('show');
                                                    }, 500);
                                                    break;
                                                }
                                        }
                                    }
                                });
                                $("#btnCancel_" + week_day + "_" + pupil_id, "#divMyOrders").click(function() {
                                    cancelMyOrders(week_day, pupil_id, current_date);
                                });
                                $("#btnSaveMyOrders", "#divMyOrders").off('click').bind("click", function() {
                                    saveMyOrders(week_day, pupil_id, current_date, isAdult);
                                });
                            } else
                                $(f).html("<div class='accordion-inner'><div class='alert alert-block'><h4>Warning!</h4>No Menu available to order.</div></div>");
                        }
                        else {
//If the order exists and cannot be edited.
                            if (data.order_menu_res.order.length > 0) {
                                var menuStr = "";
                                var mainMealStr = "";
                                var snackStr = "";
                                for (var k = 0; k < data.order_menu_res.order.length; k++) {
                                    if (data.order_menu_res.order[k].m == 11)
                                        mainMealStr += data.order_menu_res.order[k].d + "<br/>";
                                    else if (data.order_menu_res.order[k].m == 12)
                                        snackStr += data.order_menu_res.order[k].d + "<br/>";
                                }
                                mainMealStr = (mainMealStr.length > 0) ? "<div class='row-fluid'><div class='span2'><label style='font-weight: bold;'>Main Meal:</label></div><div class='span10 display-values'><label>" + mainMealStr.substring(0, mainMealStr.length - 5) + " (desert included)</label></div></div>" : "";
                                snackStr = (snackStr.length > 0) ? "<div class='row-fluid'><div class='span2'><label style='font-weight: bold;'>Snack Option: </label></div><div class='span10 display-values'><label>" + snackStr.substring(0, snackStr.length - 5) + "</label></div></div>" : "";
                                $(f).html("<div class='accordion-inner'><div>" + mainMealStr + snackStr + "<br/><div class='alert alert-error'>This order can not be edited.</div></div></div>");
                                $("#ico_" + week_day + "_" + pupil_id, "#divMyOrders").show();
                            } else
                                $(f).html("<div class='accordion-inner'><div class='alert alert-error'>No order was placed for this day</div></div>");
                        }
                        if ((localStorage["closed_stat"] == "1") && (iseditable == 'yes'))
                        {
                            $("button[id^=btnadd]", "#" + divid).attr("disabled", true);
                            $("input[id^=chkInvoice]", "#" + divid).attr("disabled", true);
                            //$("button[id^=btnCancel]","#"+divid).attr("disabled",true);
                            //$("#lblSchSts_" + week_day + "_" + pupil_id).append(localStorage["Sts_msg"] + ". Please cancel and if appropriate reorder.").removeClass("hide");
                        }
                    }
                } else {
                    logout(1);
                }
            }
            else
                logout();
        },
        error: function(xhr, textStatus, error) {
            alert(error);
        }
    });
}


function addOrders(f, week_day, pupil_id, isAdult, isFsm) {
    var btnId = $(f).attr("id");
    var btnIdArr = btnId.split("_");
    //Check whether the selected amount is available with pupil or not.
    var balanceAmt = $("#hdnBal_" + week_day + "_" + pupil_id).val();
    var netCost = $("#hdnCo_" + btnIdArr[2]).val();
    var netVAT = $("#hdnVat_" + btnIdArr[2]).val();
    var netAmt = $("#hdnAmt_" + btnIdArr[2]).val();
    myOrderItems.push({
        scmid: btnIdArr[2],
        mtype: btnIdArr[1],
        netAmt: netAmt
    });
    $(f).hide().attr("disabled", "disabled");
    $("#" + btnId.replace("btnadd", "btnadded")).show();
    if (btnIdArr[1] == 11) { // for main menu, only one meal will be selected and select all desert options.
        $(":button[id^=btnadd_11]").attr("disabled", "disabled");
        $("label[id^=lbldes_]").show();
    }

// if FSM is true and not main meal selected then we need to deduct the money from the balance amount.
    if (isFsm == 1) {
        var iniIds = "," + myInitalOrderids.join(',') + ",";
        for (obj in myOrderItems) {
            if (myOrderItems[obj].mtype == 12 && iniIds.indexOf("," + myOrderItems[obj].scmid + ",") < 0)  //For snack options
                balanceAmt = parseFloat(balanceAmt) - parseFloat(myOrderItems[obj].netAmt);
        }
//disable the items which are less than the balance Amt
        $(":button[id^=btnadd_12_]").each(function() {
            if (!$(this).is(":disabled")) {
                if (parseFloat(balanceAmt) < parseFloat($("#" + $(this).attr("id").replace("btnadd", "lblCost")).text().replace("£", "")))
                    $(this).attr("disabled", "disabled");
            }
        });
    }
//We need to deduct the balance amount if not FSM, not Audlt, if audlt and not FSM and invoce to school is not checked.
    else if (isAdult == 0 || (isAdult == 1 && !$("#chkInvoice_" + week_day + "_" + pupil_id).is(":checked")))
    {
        var iniIds = "," + myInitalOrderids.join(',') + ",";
        for (obj in myOrderItems) {
            if (iniIds.indexOf("," + myOrderItems[obj].scmid + ",") < 0)
                balanceAmt = parseFloat(balanceAmt) - parseFloat(myOrderItems[obj].netAmt);
        }
//disable the items which are less than the balance Amt
        $(":button[id^=btnadd_]").each(function() {
            if (!$(this).is(":disabled")) {
                if (parseFloat(balanceAmt) < parseFloat($("#" + $(this).attr("id").replace("btnadd", "lblCost")).text().replace("£", "")))
                    $(this).attr("disabled", "disabled");
            }
        });
    }
    var cartIds = [], k = 0;
    for (obj in myOrderItems) {
        cartIds[k++] = myOrderItems[obj].scmid;
    }
//Check whether order is changed or not with inital values.
    if (myInitalOrderids.sort().join(',') == cartIds.sort().join(','))
    {
        $("#btnContinue_" + week_day + "_" + pupil_id, "#divMyOrders").attr("disabled", "disabled");
        $("#btnSaveMyOrders", "#divMyOrders").attr("disabled", "disabled").text("Saved");
    }
    else {
        $("#btnContinue_" + week_day + "_" + pupil_id, "#divMyOrders").removeAttr("disabled");
        $("#btnSaveMyOrders", "#divMyOrders").removeAttr("disabled").text("Save");
    }



    return false;
}
function changeAdded(f, chgType) {
    if (chgType == 1)
        $(f).removeClass("btn-success").addClass("btn-danger").html("<i class='icon-remove icon-white'></i> Remove");
    else
        $(f).removeClass("btn-danger").addClass("btn-success").html("<i class='icon-ok icon-white'></i> Added");
}
function removeOrders(f, week_day, pupil_id, isAdult, isFsm) {
    var btnId = $(f).attr("id");
    var btnIdArr = btnId.split("_");
    var cartAmt = 0;
    //Remove the item from myOrderItems(cart)
    var myOrderItems1 = [];
    var isMainMeal = 0;
    var cartIds = [], k = 0;
    for (obj in myOrderItems) {
        if (myOrderItems[obj].scmid != btnIdArr[2]) {
            myOrderItems1[obj] = myOrderItems[obj];
            // if FSM is true and not main meal selected then we need to deduct the money from the balance amount.
            if (isFsm == 1 && myOrderItems[obj].mtype == 11)
                cartAmt = cartAmt;
            else if (isAdult == 1 && $("#chkInvoice_" + week_day + "_" + pupil_id).is(":checked"))
                cartAmt = cartAmt;
            else
                cartAmt += parseFloat(myOrderItems[obj].netAmt);
            isMainMeal = (myOrderItems[obj].mtype == 11) ? 1 : isMainMeal;
            cartIds[k++] = myOrderItems[obj].scmid;
        }
    }
    myOrderItems = [];
    myOrderItems = myOrderItems1;
    //Check whether order is changed or not with inital values.
    if (myInitalOrderids.sort().join(',') == cartIds.sort().join(','))
    {
        $("#btnContinue_" + week_day + "_" + pupil_id, "#divMyOrders").attr("disabled", "disabled");
        $("#btnSaveMyOrders", "#divMyOrders").attr("disabled", "disabled").text("Saved");
    }
    else {
        $("#btnContinue_" + week_day + "_" + pupil_id, "#divMyOrders").removeAttr("disabled");
        $("#btnSaveMyOrders", "#divMyOrders").removeAttr("disabled").text("Save");
    }
//Hide the remove/added button and enable the add button
    $(f).hide();
    $("#" + btnId.replace("btnadded", "btnadd")).show().removeAttr("disabled");
    var balanceAmt = $("#hdnBal_" + week_day + "_" + pupil_id).val();
    balanceAmt = parseFloat(balanceAmt) - parseFloat(cartAmt);
    if (btnIdArr[1] == 11) // for main menu de-select all desert options.
        $("label[id^=lbldes_]").hide();
    //Enable the Add buttons based on the balance amount
    if (isMainMeal == 1) {
        $(":button[id^=btnadd_12]").each(function() {
            if ($(this).is(":disabled")) {
                if (parseFloat(balanceAmt) >= parseFloat($("#" + $(this).attr("id").replace("btnadd", "lblCost")).text().replace("£", "")))
                    $(this).removeAttr("disabled");
            }
        });
    } else {
        if (isFsm == 1 || (isAdult == 1 && $("#chkInvoice_" + week_day + "_" + pupil_id).is(":checked")))
            $(":button[id^=btnadd_11]").each(function() {
                $(this).removeAttr("disabled");
            });
        $(":button[id^=btnadd_]").each(function() {
            if ($(this).is(":disabled")) {
                if (parseFloat(balanceAmt) >= parseFloat($("#" + $(this).attr("id").replace("btnadd", "lblCost")).text().replace("£", "")))
                    $(this).removeAttr("disabled");
            }
        });
    }

}

function disableAdd(f, week_day, pupil_id) {
    if ($(f).is(":checked")) {
        var isMainMeal = 0;
        if (myOrderItems.length > 0) {
            $("#btnContinue_" + week_day + "_" + pupil_id, "#divMyOrders").removeAttr("disabled");
            $("#btnSaveMyOrders", "#divMyOrders").removeAttr("disabled").text("Save");
        }
        for (obj in myOrderItems)
            isMainMeal = (myOrderItems[obj].mtype == 11) ? 1 : isMainMeal;
        if (isMainMeal == 1)
        { ////Enable only snack options
            $(":button[id^=btnadd_12_]").each(function() {
                if ($(this).is(":disabled") && !$(this).is(":hidden"))
                    $(this).removeAttr("disabled");
            });
        } else { //Enable all options
            $(":button[id^=btnadd_]").each(function() {
                if ($(this).is(":disabled") && !$(this).is(":hidden"))
                    $(this).removeAttr("disabled");
            });
        }
    } else { //If the invoice to school checkbox is unchecked then resetting the options to default and user has to select manually.
//Reloading the section because no initial data available(myorderitems will be deleted on removal so, net amt won't be available)'.
        invoice_check = true;
        $(".accordion-body[id='collapse_" + week_day + "_" + pupil_id + "']", "#divPupMyOrders" + week_day).collapse('hide');
        setTimeout(function() {
            $(".accordion-body[id='collapse_" + week_day + "_" + pupil_id + "']", "#divPupMyOrders" + week_day).collapse('show');
        }, 500);
//		myOrderItems=[];
//		$("#btnContinue_"+week_day+"_"+pupil_id,"#divMyOrders").attr("disabled","disabled");
//		$("#btnSaveMyOrders","#divMyOrders").attr("disabled","disabled").text("Saved");
//		$("label[id^=lbldes_]").hide();
//		
//		var balanceAmt = $("#hdnBal_"+week_day+"_"+pupil_id).val();
//		$(":button[id^=btnadded_]").each(function(){
//			$(this).hide();
//		});
//		$(":button[id^=btnadd_]").each(function(){
//			if(parseFloat(balanceAmt) < parseFloat($("#"+$(this).attr("id").replace("btnadd","lblCost")).text().replace("£","")))
//				$(this).attr("disabled","disabled").show();
//			else
//				$(this).removeAttr("disabled").show();
//		});
    }
}

function saveMyOrders(week_day, pupil_id, current_date, isAdult) {
//Need to check the available balance and selected balance if adult and invoice to school is not checking.
//If invoice to school is checked and add items and uncheck..
    var balanceAmt = parseFloat(($("#hdnBal_" + week_day + "_" + pupil_id).val()).replace(/\,/g, ''));
    if (isAdult == 1 && !$("#chkInvoice_" + week_day + "_" + pupil_id).is(":checked")) {
        for (obj in myOrderItems)
            balanceAmt = parseFloat(balanceAmt) - parseFloat(myOrderItems[obj].netAmt);
    }
    if (balanceAmt >= 0) {
        $("#lblErr_" + week_day + "_" + pupil_id, "#divMyOrders").hide();
        $("#lblErrSaveMyOrders", "#divMyOrders").hide();
        $("#btnContinue_" + week_day + "_" + pupil_id, "#divMyOrders").attr("disabled", "disabled");
        $("#btnSaveMyOrders", "#divMyOrders").attr("disabled", "disabled").text("Saving");
        $.support.cors = true;
        $.ajax({
            url: BACKENDURL + "user/save_order_items",
            type: "post",
            data: {
                session_id: localStorage["SESSIONID"],
                contract_id: localStorage["contractid"],
                school_id: $('#ddlOrderSchools', "#divMyOrders").val(),
                pupil_id: pupil_id.substring(0, 3) + "/" + pupil_id.substring(3),
                current_date: current_date,
                order_details: myOrderItems,
                isAdult: $("#hdnAdult_" + week_day + "_" + pupil_id).val(),
                isFsm: $("#hdnFsm_" + week_day + "_" + pupil_id).val(),
                isInvoice: $("#chkInvoice_" + week_day + "_" + pupil_id).is(":checked"),
                order_id: $("#hdnOrderid_" + week_day + "_" + pupil_id).val(),
                myInOrdids: "," + myInitalOrderids.join(',') + ",",
            },
            dataType: "json",
            crossDomain: true,
            async: false,
            success: function(data) {
                if (data.session_status) {
                    if (data.error == 0) {
                        if (data.history_res['error'] == 0) {
                            $("#btnContinue_" + week_day + "_" + pupil_id, "#divMyOrders").attr("disabled", "disabled");
                            $("#btnSaveMyOrders", "#divMyOrders").attr("disabled", "disabled").text("Saved");
                            myInitalOrderids = [];
                            k = 0;
                            for (obj in myOrderItems) {
                                myInitalOrderids[k++] = myOrderItems[obj].scmid;
                            }
                            if (myInitalOrderids.length > 0) {
                                $("#btnCancel_" + week_day + "_" + pupil_id, "#divMyOrders").show();
                                $("#hdnOrderid_" + week_day + "_" + pupil_id).val(data.history_res['order_id']);
                                $("#ico_" + week_day + "_" + pupil_id, "#divMyOrders").removeClass("hide").show();
                            }

                            else {
                                $("#btnCancel_" + week_day + "_" + pupil_id, "#divMyOrders").hide();
                                $("#hdnOrderid_" + week_day + "_" + pupil_id).val('');
                                $("#ico_" + week_day + "_" + pupil_id, "#divMyOrders").hide();
                            }

                            $("#spnBal_" + week_day + "_" + pupil_id).html(data.history_res['balance']);
                            $("#hdnBal_" + week_day + "_" + pupil_id).val(data.history_res['balance']);
                        } else {
//For two window scenarios...
                            $("#lblErrSaveMyOrders", "#divMyOrders").show().html(data.history_res['error_msg']);
                            $("#btnContinue_" + week_day + "_" + pupil_id, "#divMyOrders").removeAttr("disabled");
                            $("#btnSaveMyOrders", "#divMyOrders").removeAttr("disabled").text("Save");
                        }
//myOrderItems = [];
                    } else
                        logout(1);
                }
                else
                    logout();
            },
            error: function(xhr, textStatus, error) {
                alert("Something went wrong with your request. Please try again and if it still doesn't work ask your administrator for help");
            }
        });
        return true;
    } else
        $("#lblErr_" + week_day + "_" + pupil_id, "#divMyOrders").show();
//event.stopPropagation();
    return false;
}
function cancelMyOrders(week_day, pupil_id, current_date) {
    $.support.cors = true;
    $.ajax({
        url: BACKENDURL + "user/cancel_order_items",
        type: "post",
        data: {
            session_id: localStorage["SESSIONID"],
            contract_id: localStorage["contractid"],
            school_id: $('#ddlOrderSchools', "#divMyOrders").val(),
            pupil_id: pupil_id.substring(0, 3) + "/" + pupil_id.substring(3),
            current_date: current_date,
            order_id: $("#hdnOrderid_" + week_day + "_" + pupil_id).val(),
        },
        dataType: "json",
        crossDomain: true,
        success: function(data) {
            if (data.session_status) {
                if (data.error == 0) {
                    $("#spnBal_" + week_day + "_" + pupil_id).html(data.history_res['balance']);
                    $("#hdnBal_" + week_day + "_" + pupil_id).val(data.history_res['balance']);
                    $("#hdnOrderid_" + week_day + "_" + pupil_id).val('');
                    //Collapse and open the student accordion 
                    myOrderItems = [];
                    myInitalOrderids = [];
                    $(".accordion-body[id='collapse_" + week_day + "_" + pupil_id + "']", "#divPupMyOrders" + week_day).collapse('hide');
                    setTimeout(function() {
                        $(".accordion-body[id='collapse_" + week_day + "_" + pupil_id + "']", "#divPupMyOrders" + week_day).collapse('show');
                    }, 500);
                }
            }
            else
                logout();
        },
        error: function(xhr, textStatus, error) {
            alert("Something went wrong with your request. Please try again and if it still doesn't work ask your administrator for help");
        }
    });
    return false;
}

// Add Temporary Pupils - Meal orders 

function cancelPupil_data()
{
    cleardata();
    if (chkTempPP.length > 0)
    {
        for (var nCount = 0; nCount <= chkTempPP.length; nCount++)
        {
            if ($.inArray(chkTempPP[nCount], chkTempPupil) > -1)
            {
                chkTempPupil.pop(chkTempPP[nCount]);
            }
        }
    }
}

function cleardata()
{
    if (!$("#lblTempPupilerr").hasClass("hide"))
        $("#lblTempPupilerr").text("").addClass("hide");
    $("#txtSearchPupilId", "#divOrderTempPupil").val("");
    $("#txtSearchFname", "#divOrderTempPupil").val("");
    $("#txtSearchMname", "#divOrderTempPupil").val("");
    $("#txtSearchLname", "#divOrderTempPupil").val("");
    $("#divTempPupil").hasClass("hide")
    $("#divTempPupil").removeClass("hide");
    $("#btnTempPupilSearch").hasClass("hide")
    $("#btnTempPupilSearch").removeClass("hide");
    $("#divPopulateTempPupils").addClass("hide");
    $("#btnTempPupilSubmit").addClass("hide");
    $("#divOrderTempPupil").modal('hide');
    $("#tablePagination").remove();
}

function Srv_SrchTempPPl()
{
    chkTempPP = [];
    var pupilId = $("#txtSearchPupilId", "#divOrderTempPupil").val();
    var Fname = $("#txtSearchFname", "#divOrderTempPupil").val();
    var Mname = $("#txtSearchMname", "#divOrderTempPupil").val();
    var Lname = $("#txtSearchLname", "#divOrderTempPupil").val();
    if ((pupilId == "") && (Fname == "") && (Mname == "") && (Lname == ""))
    {
        $("#lblTempPupilerr").text("Please enter Pupil ID or Name").removeClass("hide");
        return false;
    }
    if (!$("#lblTempPupilerr").hasClass("hide"))
        $("#lblTempPupilerr").addClass("hide");
    var url = BACKENDURL + "user/get_order_search_pupils";
    var data = {
        session_id: localStorage["SESSIONID"],
        pupil_id: pupilId,
        fname: Fname,
        mname: Mname,
        lname: Lname,
        school_id: $("#ddlOrderSchools").val()
    };
    MakeAjaxCall(url, data, SrchTempPPl);
}

function SrchTempPPl(data)
{
    var tblPupil = "";
    var nCurrRecRound = 0;
    if (data.error == 0) {
        if (data.search_pupils_res.length > 0) {
            $("#tbltmpPupilData  tbody:last").empty();
            for (var nCount = 0; nCount < data.search_pupils_res.length; nCount++) {
                var studentId = data.search_pupils_res[nCount].students_id;
                var pupilId = data.search_pupils_res[nCount].pupil_id;
                var freeMeals = (data.search_pupils_res[nCount].fsm == 0) ? "<img src='img/fail.png' style='width:20px;display:block;margin:0 auto;' >" : "<img src='img/pass.png' style='width:20px;display:block;margin:0 auto;'>";
                var adult = (data.search_pupils_res[nCount].adult == 1) ? "<i class='icon-user' ></i>" : "";
                if (!($.inArray(pupilId, chkTempPupil) > -1))
                {
                    tblPupil += "<tr><td><div style= 'padding-top:6px' id='pupilName" + studentId + "'>" + data.search_pupils_res[nCount].fname + " " + data.search_pupils_res[nCount].mname + " " + data.search_pupils_res[nCount].lname + "</div></td>";
                    tblPupil += "<td nowrap='nowrap'><div style= 'padding-top:6px' id='pupilid" + studentId + "'>" + data.search_pupils_res[nCount].pupil_id + "  " + adult + "</div></td>";
                    tblPupil += "<td ><label style= 'padding-top: 6px;' id='freemeals" + studentId + "'>" + freeMeals + "</label></td>";
                    tblPupil += "<td><div style='padding-top: 6px;' id='SchoolNameLabel" + studentId + "'>" + data.search_pupils_res[nCount].school_name + "</div></td>";
                    tblPupil += "<td><div style='text-align: center;padding-top: 6px;'id='YearLabel" + studentId + "'>" + data.search_pupils_res[nCount].year_label + "</div></td>";
                    tblPupil += "<td><div style='text-align: center;padding-top: 6px;'id='ClassLabel" + studentId + "'>" + data.search_pupils_res[nCount].class_name + "</div></td>";
                    tblPupil += "<td><label style='display:inline;'><input type='checkbox' id='chkSelect_" + pupilId + "' name='chkSelect_" + pupilId + "' style='display:inline;margin-top:0px;' ><span style='vertical-align:middle;display:inline;'> Select</span></label></td></tr>";
                    $("#tbltmpPupilData  tbody:last").append(tblPupil);
                    tblPupil = "";
                }
            }
            var tblLen = parseInt($("#tbltmpPupilData  tr").length) - parseInt(1);
            if (tblLen > 0)
            {
                $("#divTempPupil").addClass("hide");
                $("#btnTempPupilSearch").addClass("hide");
                $("#divPopulateTempPupils").removeClass("hide");
                $("#btnTempPupilSubmit").removeClass("hide");
                if (tblLen > 3) {
                    $("#tablePagination").html('');
                    $("#tempPupil_Pag").tablePagination({
                        rowsPerPage: 3,
                        currPage: nCurrRecRound + 1
                    });
                }
            }
            else
            {
                $("#lblTempPupilerr").text("No relevant pupil found").removeClass("hide");
                return false;
            }

            $("input[type=checkbox]", "#tbltmpPupilData").click(function()
            {
                var tmpPupilId = $(this).attr('id').slice(10);
                if ($.inArray(tmpPupilId, chkTempPupil) > -1)
                {
                    chkTempPupil.pop(tmpPupilId);
                }
                else
                {
                    chkTempPupil.push(tmpPupilId);
                    chkTempPP.push(tmpPupilId);
                }
            });
        }
        else
        {
            $("#lblTempPupilerr").text("Pupil does not exist").removeClass("hide");
            return false;
        }
    }
    else
    {
        alert(data.error_msg);
        alert(data.error);
    }
}

function Srv_SaveTempPPl()
{
    cleardata();
    $("#divOrderTempPupil").modal('hide');
    var week_day = localStorage["act_orderweek"];
    var startDateArr = $("#hdnStartDate", "#divMyOrders").val().split("-");
    var newDate = new Date(startDateArr[0], (startDateArr[1] - 1), startDateArr[2], 0, 0, 0, 0).addDays(parseInt(week_day) - 1);
    var thisDate = newDate.getFullYear() + "-" + pad((newDate.getMonth()) + 1) + "-" + pad(newDate.getDate());
    if (!$("#btnSaveMyOrders", "#divMyOrders").is(":disabled"))
        $("#btnSaveMyOrders", "#divMyOrders").click();
    getOrderPupils(week_day, thisDate, $("#hdnStartDate", "#divMyOrders").val());
    //getOrderPupils(1);
}



$.validator.addMethod("validateCost", function(value, element) {
    return this.optional(element) || /^\d+(\.\d{1,2})?$/.test(value);
}, "Please enter valid cost.");
var cost_regex = /^\d+(\.\d{1,2})?$/;

function loadMyMenus(menu_seq, week_cycle) {
    menu_seq = (menu_seq == undefined) ? 1 : menu_seq;
    week_cycle = (week_cycle == undefined) ? 1 : week_cycle;
    var url = BACKENDURL + "customeradmin/get_menu_details";
    var data = {
        session_id: localStorage["SESSIONID"],
        contract_id: localStorage.getItem("contractid"),
        menu_seq: menu_seq,
        week_cycle: week_cycle,
    };
    MakeAjaxCall(url, data, loadMenus);
}

var MealArray = new Array();
MealArray[11] = "Main Meal";
MealArray[12] = "Snack Options";
MealArray[13] = "Desert";


var optionStatus = new Array();
function loadMenus(data) {
    if (data.error == 0) {
        var menuCycles = data.menus_res[0].mc[0].c;
        var menuTxt = "";
        for (var i = 1; i <= menuCycles; i++) {
            if (i != data.menus_res[0].ms[0].mseq)
                menuTxt += "<a href='javascript:void(0);' onClick='javascript:return saveMenuDetails(" + i + ",1,1);return false;'>switch to Menu " + i + "</a> , ";
        }
        menuTxt = menuTxt.substr(0, menuTxt.length - 3);

        var activeTxt = (data.menus_res[0].ma.length > 0 && data.menus_res[0].ms[0].menu_id == data.menus_res[0].ma[0].menu_id) ? "active" : "inactive"; // Need to get from db.
        $("#lblHeading", "#MenusTab").html("Viewing Week " + data.menus_res[0].ms[0].w + " - Menu " + data.menus_res[0].ms[0].mseq + " (" + activeTxt + ") <br /> Active from " + data.menus_res[0].ms[0].mdate + " - (" + menuTxt + ")");
        $("#spnWeekId1", "#MenusTab").html(data.menus_res[0].ms[0].w);
        $("#spnWeekId2", "#MenusTab").html(data.menus_res[0].ms[0].w);
        $("#spnWeekId3", "#MenusTab").html(data.menus_res[0].ms[0].w);
        $("#spnWeekId4", "#MenusTab").html(data.menus_res[0].ms[0].w);
        $("#spnWeekId5", "#MenusTab").html(data.menus_res[0].ms[0].w);
        if (data.menus_res[0].ms[0].w == 1)
            $("#pgrPrevious", "#MenusTab").addClass("disabled");
        else
            $("#pgrPrevious", "#MenusTab").removeClass("disabled").off('click').on('click', function() {
                saveMenuDetails(data.menus_res[0].ms[0].mseq, parseInt(data.menus_res[0].ms[0].w) - 1, 1);
            });
        if (data.menus_res[0].ms[0].w == data.menus_res[0].ms[0].wc)
            $("#pgrNext", "#MenusTab").addClass("disabled");
        else
            $("#pgrNext", "#MenusTab").removeClass("disabled").off('click').on('click', function() {
                saveMenuDetails(data.menus_res[0].ms[0].mseq, parseInt(data.menus_res[0].ms[0].w) + 1, 1);

            });
        var tot = data.menus_res[0].md.length;
        var mealStr = "";
        var curWeek = 1;
        var preMeal = 0;
        var i = 0;
        var hideCost;
        while (i < tot) {
            //for(var i=0; i<tot; i++){
            curWeek = data.menus_res[0].md[i].w;

            var instr = (i == 0) ? " in " : "";

            if (preMeal != data.menus_res[0].md[i].m) {
                mealStr += "<div class='accordion-group'><div class='accordion-heading'><a class='accordion-toggle' data-toggle='collapse' data-parent='#accordion" + curWeek + "' href='#collapse_" + curWeek + "_" + data.menus_res[0].md[i].m + "' id='id_" + curWeek + "_" + data.menus_res[0].md[i].m + "' style='text-decoration: none !important;'>" + MealArray[data.menus_res[0].md[i].m] + "</a></div>";
                mealStr += "<div id='collapse_" + curWeek + "_" + data.menus_res[0].md[i].m + "' class='accordion-body collapse " + instr + "'><div class='accordion-inner'>";
            }

            var k = 0, opt = 1;
            for (var j = i + 1; j <= tot; j++) {
                if (k == 0)
                    mealStr += "<ul class='thumbnails'>";
                optTxt = toWords(opt);
                optTxt = optTxt[0].toUpperCase() + optTxt.substring(1);
                hideCost = (data.menus_res[0].md[i].m == 13) ? " hide " : ""; // For desert
                mealStr += "<li class='span3'><div class='thumbnail'><h3 class='aligncenter'>Option " + optTxt + "</h3><p id='txt_menu'><textarea id='txtO_" + data.menus_res[0].md[j - 1].mid + "' name ='txtO_" + data.menus_res[0].md[j - 1].mid + "' class='txt_menu' maxlength='200'>" + data.menus_res[0].md[j - 1].d + "</textarea></p>";
                mealStr += "<div class='form-horizontal'><div class='control-group' style='margin-bottom:-15px;'><label class='control-label span3" + hideCost + "'>Net Cost</label><div class='controls' style='padding-bottom: 10px;margin-bottom:15px;'>&nbsp;<div class='input-prepend txt_prepend " + hideCost + "' id='test'><span class='add-on' style='height:16px;'>£</span><input maxlength='10' class='txt_netcost' name='txtOVal_" + data.menus_res[0].md[j - 1].mid + "' id='txtOVal_" + data.menus_res[0].md[j - 1].mid + "' type='text' value='" + data.menus_res[0].md[j - 1].c + "'></div>";
                var dBtn = "", eBtn = "";
                if (data.menus_res[0].md[j - 1].s == 1)
                    dBtn = " hide ";
                else
                    eBtn = " hide ";
                optionStatus[data.menus_res[0].md[j - 1].mid] = data.menus_res[0].md[j - 1].s;
                optBtText = "<button class='btn btn-success btn-small" + eBtn + "' style='margin-top:0px;' id='btnOSt_1_" + data.menus_res[0].md[j - 1].mid + "' onClick='javascript:return changeMenuOptionStatus(this,0);'><i class='icon-ok-circle icon-white'></i> Enabled</button><button class='btn btn-danger btn-small" + dBtn + "' style='margin-top:0px;' onClick='javascript:return changeMenuOptionStatus(this,1);' id='btnOSt_0_" + data.menus_res[0].md[j - 1].mid + "'><i class='icon-ban-circle icon-white'></i> Disabled</button>";
                mealStr += "<div class='control-group pull-right'><input type=hidden name='hdnOSt_" + data.menus_res[0].md[j - 1].mid + "' id='hdnOSt_" + data.menus_res[0].md[j - 1].mid + "' value='" + data.menus_res[0].md[j - 1].s + "'/>" + optBtText + "</div></div>";
                mealStr += "<div class='alert alert-error err_menu' id='MenuErr" + data.menus_res[0].md[j - 1].mid + "'></div></div>";
                mealStr += "</li>";

                k++;
                opt++;
                if (k == 4) {
                    mealStr += "</ul>";
                    k = 0;
                }
                preMeal = data.menus_res[0].md[j - 1].m;
                if (j == tot || preMeal != data.menus_res[0].md[j].m) {
                    mealStr += "<button class='btn btn-primary' id='btnConttemp_" + curWeek + "_" + data.menus_res[0].md[i].m + "' name='btnConttemp_" + curWeek + "_" + data.menus_res[0].md[i].m + "' disabled='disabled' style='float:right;display:none;'>Continue</button><button class='btn btn-primary' id='btnContinue_" + curWeek + "_" + data.menus_res[0].md[i].m + "' name='btnContinue_" + curWeek + "_" + data.menus_res[0].md[i].m + "' disabled='disabled' style='float:right' onClick='javascript:return saveMenuDetails(" + data.menus_res[0].ms[0].mseq + "," + data.menus_res[0].ms[0].w + ",0,this);return false;'>Continue</button><br/>";
                    mealStr += "</div></div></div>";
                    break;
                }
            }
            i = j;
            if (i == tot || curWeek != data.menus_res[0].md[i + 1].w) {
                $("#accordion" + curWeek, "#MenusTab").html(mealStr);
                mealStr = "";
            }
        }
        $("#btnSaveMenus", "#MenusTab").attr("disabled", "disabled").text('Saved');
        $(":button[name^='btnContinue']", "#MenusTab").attr("disabled", "disabled");
        frmMenusDirtyCheck();
        $("#btnSaveMenus", "#MenusTab").off('click').on('click', function() {
            saveMenuDetails(data.menus_res[0].ms[0].mseq, data.menus_res[0].ms[0].w, 0);
            return false;
        });

        $("#frmMenus").validate({
            errorClass: "help-inline",
            errorElement: "span",
            highlight: function(element, errorClass, validClass) {
                $(element).parents('.control-group').addClass('error');
            },
            unhighlight: function(element, errorClass, validClass) {
                $(element).parents('.control-group').removeClass('error');
            },
            errorPlacement: function(error, element) {
                if (element.parent().hasClass("input-append") || element.parent().hasClass("input-prepend")) {
                    error.insertAfter(element.parent().parent().parent()).css('color', 'rgb(185, 74, 72)');
                } else {
                    error.insertAfter(element);
                }
            }
        });
        customerModuleAccess("AL4MNUS", 1);
    } else
        logout(1);
    $('[id^="txtO_"]').keydown(function(e) {
        var txtkey = $(this).attr('id');
        var btntoggle = $("#" + txtkey).parents().eq(4).children(':nth-child(5)').attr('id');
        $("#" + btntoggle).attr("disabled", false);
        $("#btnSaveMenus", "#MenusTab").attr("disabled", false).text('Save');
    });
    $('[id^="txtOVal_"]').keydown(function(e) {
        var txtkey = $(this).attr('id');
        var btntoggle = $("#" + txtkey).parents().eq(7).children(':nth-child(5)').attr('id');
        $("#" + btntoggle).attr("disabled", false);
        $("#btnSaveMenus", "#MenusTab").attr("disabled", false).text('Save');
    });

}

var menuids = [];
var menuDetailsArr = [];
function frmMenusDirtyCheck() {
    var Settings = {
        denoteDirtyForm: true,
        dirtyFormClass: false,
        includeHidden: true,
        dirtyOptionClass: "dirtyChoice",
        trimText: true,
        formChangeCallback: function(result, dirtyFieldsArray) {
            menuids = [];
            menuDetailsArr = [];
            if (result)
            {
                $("#btnSaveMenus", "#MenusTab").attr("disabled", false).text('Save');
                $(":button[name^='btnContinue']", "#MenusTab").attr("disabled", false);
                $.each(dirtyFieldsArray, function(index, value) {
                    if (value.match("^txtO_"))
                        menuids.push(value.substring(5));
                    if (value.match("^txtOVal_"))
                        menuids.push(value.substring(8));
                    if (value.match("^hdnOSt_"))
                        menuids.push(value.substring(7));
                });

                var uniqueMenuIds = $.unique(menuids);
                $.each(uniqueMenuIds, function(i, id) {
                    menuDetailsArr.push(
                            {
                                cmid: id,
                                od: $("#txtO_" + id).val(),
                                oc: $("#txtOVal_" + id).val(),
                                os: $("#hdnOSt_" + id).val()
                            });
                });
            }
            else
                setTimeout(function() {
                    $("#btnSaveMenus", "#MenusTab").attr("disabled", "disabled").text('Saved');
                    $(":button[name^='btnContinue']", "#MenusTab").attr("disabled", "disabled");
                }, 500);
        }
    };
    $("#frmMenus").dirtyFields(Settings);
}

function changeMenuOptionStatus(f, opt) {
    var id = $(f).attr('id');
    var idtmp = id;
    var newid = (opt == 1) ? id.replace("_0_", "_1_") : id.replace("_1_", "_0_");
    var validid = id.slice(9);
    $("#txtO_" + validid).css('border-color', '#cccccc');
    $("#txtOVal_" + validid).css('border-color', '#cccccc');
    $("#MenuErr" + validid).css('display', 'none');
    if (newid.search("_1_") > 0)
    {
        //$("#txtO_"+validid).removeAttr("readonly");
        //$("#txtOVal_"+validid).removeAttr("readonly");
        if ($("#txtO_" + validid).val() == "")
        {
            $("#txtO_" + validid).css('border-color', '#b94a48');
            $("#MenuErr" + validid).text("Please enter menu data").show();
            return false;
        }
        if ($("#txtOVal_" + validid).val() == "")
        {
            $("#txtOVal_" + validid).css('border-color', '#b94a48');
            $("#MenuErr" + validid).text("Please enter net cost").show();
            return false;
        }
        if (!cost_regex.test($("#txtOVal_" + validid).val()))
        {
            $("#txtOVal_" + validid).css('border-color', '#b94a48');
            $("#MenuErr" + validid).text("Please enter valid cost").show();
            return false;
        }
    }
    else
    {
        $("#txtO_" + validid).css('border-color', '#cccccc');
        $("#txtOVal_" + validid).css('border-color', '#cccccc');
        //$("#txtO_"+validid).attr("readonly", "readonly");
        //$("#txtOVal_"+validid).attr("readonly", "readonly");
    }
    var btntoggle = $("#" + newid).parents().eq(7).children(':nth-child(5)').attr('id');
    $("#" + btntoggle).attr("disabled", false);
    $("#btnSaveMenus", "#MenusTab").attr("disabled", false).text('Save');
    $("#" + newid).removeClass('hide');
    $("#" + idtmp).addClass('hide');
    //$("#"+newid).show();
    //$(f).hide();
    var mid = (id.split("_"))[2];
    $("#hdnOSt_" + mid).val(opt);
    $.fn.dirtyFields.updateTextState($("#hdnOSt_" + mid), $("#frmMenus"));

    return false;
}

function chkMandatorydata(validid)
{
    $("#txtO_" + validid).css('border-color', '#cccccc');
    $("#txtOVal_" + validid).css('border-color', '#cccccc');
    $("#MenuErr" + validid).css('display', 'none');
    if ($("#txtO_" + validid).val() == "")
    {
        $("#txtO_" + validid).css('border-color', '#b94a48');
        $("#MenuErr" + validid).text("Please enter menu data").show();
        $("#txtO_" + validid).parents().eq(9).addClass("in").attr("style", "height:auto");
        $("#txtO_" + validid).parents().eq(5).addClass("in").attr("style", "height:auto");
        $("#txtO_" + validid).focus();
        return false;
    }
    if ($("#txtOVal_" + validid).val() == "")
    {
        $("#txtOVal_" + validid).css('border-color', '#b94a48');
        //$("#txtOVal_"+validid).parents().eq(14).addClass("in").attr("style","height:auto");
        $("#txtOVal_" + validid).parents().eq(12).addClass("in").attr("style", "height:auto");
        $("#txtOVal_" + validid).parents().eq(8).addClass("in").attr("style", "height:auto");
        $("#txtOVal_" + validid).focus();
        $("#MenuErr" + validid).text("Please enter net cost").show();
        return false;
    }
    if (!cost_regex.test($("#txtOVal_" + validid).val()))
    {
        $("#txtOVal_" + validid).css('border-color', '#b94a48');
        //$("#txtOVal_"+validid).parents().eq(14).addClass("in").attr("style","height:auto");
        $("#txtOVal_" + validid).parents().eq(12).addClass("in").attr("style", "height:auto");
        $("#txtOVal_" + validid).parents().eq(8).addClass("in").attr("style", "height:auto");
        $("#txtOVal_" + validid).focus();
        $("#MenuErr" + validid).text("Please enter valid cost").show();
        return false;
    }
    return true;
}
function saveMenuDetails(menu_seq, week_cycle, reload, ctlContinue) {
    var validFlag = true;
    $(":hidden[id^=hdnOSt_][value=1]").each(function() {
        var id = $(this).attr("id").replace("hdnOSt_", "");
        if (!chkMandatorydata(id)) {
            validFlag = false;
            return false;
        }
    });

    if (validFlag) {
        if (menuDetailsArr.length > 0) {
            //Confirm_box("Warning, this may impact existing meal orders", "Warning!", SaveMenu_confirm, "");
           bootbox.dialog({
                message: "Warning, this may impact existing meal orders",
                title: "Warning!",
                buttons: {
                    danger: {
                        label: "Cancel",
                        className: "btn",
                        callback: function() {
                            //alert("uh oh, look out!");
                        }
                    },
                    success: {
                        label: "Continue",
                        className: "btn-primary",
                        callback: function()
                        {
                            $("#btnSaveMenus").attr("disabled", "disabled").text('Saving');
                            $(":button[name^='btnContinue']", "#MenusTab").attr("disabled", "disabled");
                            $.support.cors = true;
                            $.ajax({
                                url: BACKENDURL + "customeradmin/save_menu_details",
                                type: "post",
                                data: {session_id: localStorage["SESSIONID"],
                                    contract_id: localStorage.getItem("contractid"),
                                    menu_seq: menu_seq,
                                    week_cycle: week_cycle,
                                    menu_details: menuDetailsArr,
                                },
                                dataType: "json",
                                crossDomain: true,
                                success: function(data) {
                                    if (data.session_status) {
                                        if (data.error == 0) {
                                            if (reload == 1)
                                                loadMyMenus(menu_seq, week_cycle);
                                            else
                                            {
                                                $.fn.dirtyFields.formSaved($("#frmMenus"));
                                                $("#btnSaveMenus").attr("disabled", "disabled").text('Saved');
                                                $(":button[name^='btnContinue']", "#MenusTab").attr("disabled", "disabled");
                                                if (ctlContinue != undefined) {
                                                    var continueId = $(ctlContinue).attr("id");
                                                    $("#" + continueId.replace('btnContinue_', 'collapse_')).collapse("hide");

                                                    var continueIdArr = continueId.split("_");
                                                    continueIdArr[0] = "collapse";
                                                    if (continueIdArr[1] == 5 && continueIdArr[2] == 13) {
                                                        $("#collapse" + continueIdArr[1]).removeClass("in").attr("style", "style=height: 0px;");

                                                        continueIdArr[1] = 1;
                                                        continueIdArr[2] = 11;
                                                        $("#collapse" + continueIdArr[1]).addClass("in").attr("style", "height:auto");
                                                    }
                                                    else if (continueIdArr[2] == 13) {
                                                        $("#collapse" + continueIdArr[1]).removeClass("in").attr("style", "style=height: 0px;");
                                                        continueIdArr[1] = parseInt(continueIdArr[1]) + 1;
                                                        continueIdArr[2] = 11;
                                                        $("#collapse" + continueIdArr[1]).addClass("in").attr("style", "height:auto");
                                                    } else {
                                                        continueIdArr[2] = parseInt(continueIdArr[2]) + 1;
                                                    }
                                                    $("#" + continueIdArr.join("_")).addClass("in").attr("style", "height:auto");
                                                }
                                            }
                                            menuDetailsArr = [];
                                        }
                                    } else
                                        logout();
                                },
                                error: function(xhr, textStatus, error) {
                                    alert("Something went wrong with your request. Please try again and if it still doesn't work ask your administrator for help");
                                }
                            });
                        }
                    }
                }
            });
        }
        else if (reload == 1)
            loadMyMenus(menu_seq, week_cycle);
        return false;
    } else
        return false;
}
function changeMenuOptions() {
    $("#SchoolAdminSettingsTab").click();
}


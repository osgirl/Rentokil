var windowURL = window.location.toString().split('/')
var URL_load = windowURL[0] + "//" + windowURL[2];
var usersArray = [];
var usersProfileArray = [];
var time_hrs = ["00", "01", "02", "03", "04", "05", "06", "07", "08", "09", "10", "11", "12", "13", "14", "15", "16", "17", "18", "19", "20", "21", "22", "23"];
var time_mins = ["00", "15", "30", "45"];
var isAvailable = 0;
var unAthMsg = "Unauthorized access.";
var digitalpen_Pag;
var Addeditacct_pag;
var spt_ind = [];//selected points indicators
localStorage["zipUpld_sts"] = "0";
var grpChanged = 0;
$.browser.safari = ($.browser.webkit && !(/chrome/.test(navigator.userAgent.toLowerCase())));
function LoadPageData() {
    LoadDashboard();
    LoadConfigureContract();
    LoadSystemAdmin();
    LoadSafety();
    $("#spnContractName").html(localStorage["contractname"]);
}
function LoadDashboard() {
}
function LoadConfigureContract() {
    $("#lnkConfigureContract").bind("click", loadUsers);
    LoadContractAdministrators();
    LoadContractSettings();
    LoadUserProfile();
    LoadUserSkin();
    LoadDigitalPens();
    LoadQualityAudits();
}

function LoadContractAdministrators()
{
    $("#lnkAdministrators").bind("click", SrvUserAdmin);
    $("#btnAddAdmin").bind("click", AddContractAdmin);
    $("#btnRemoveAdmin").bind("click", RemoveContractAdmin);
    $("#btnSaveContractAdmin").bind("click", SrvSaveContractAdmin);
}

function LoadUserSkin()
{
    customerModuleAccess('AL3SKN', 0);
    localStorage["txtHdrLnkColor"] = "77caff";
    localStorage["txtHdrLnkHoverColor"] = "000000";
    localStorage["txtHeadingsColor"] = "004976";
    localStorage["txtPageLnkColor"] = "004976";
    localStorage["txtPgeLnkHoverColor"] = "000000";
    localStorage["txtPgeBgdColor"] = "ebebeb";
    var url = window.URL || window.webkitURL;
    $(".pick-a-color").pickAColor();
    $("#lnkSkins").bind("click", SrvSkinCheck);
    $("#btnSkinSave").bind("click", ValidSkinSave);
    $("#btnSkinDelete").bind("click", function() {
        $("#divSkinDelete").modal('show');
    });
    $("#btnconfirmDelete").bind("click", SrvSkinDelete);
    $("#btnNewSkinSubmit", "#divNewSkin").bind("click", SrvSkinCreate);
    $('#ddlUserSkin', "#SkinExist").on('change', SkinChange);
    $('[name^="txt"]', "#SkinExist").bind("keypress keyup", function() {
        $("#btnSkinSave", "#SkinExist").text('Save').attr("disabled", false);
    });
    $(".color-dropdown", "#SkinExist").bind("click", function() {
        $("#btnSkinSave", "#SkinExist").text('Save').attr("disabled", false);
    });
    $('[id="txtSkinName"]', "#SkinExist").bind("keypress keyup", function() {
        $("#txtSkinName").parents('.control-group').removeClass('error');
        $("#spnSkinName").css('display', 'none');
        $("#lblSaveSkin").removeClass("alert alert-danger").text("");
    });
    $('[id="txtLftFtr1"]', "#SkinExist").bind("keypress keyup", function() {
        $("#txtLftFtr1", "#divSkins").css('border-color', '#cccccc');
        $("#lblLftFtr1").css('color', '#333333');
        $("#spnLftFtr1").css('display', 'none');
        $("#lblSaveSkin").hasClass("alert alert-danger");
        $("#lblSaveSkin").removeClass("alert alert-danger").text("");
    });
    $('[id="txtLftFtr2"]', "#SkinExist").bind("keypress keyup", function() {
        $("#txtLftFtr2", "#divSkins").css('border-color', '#cccccc');
        $("#lblLftFtr2").css('color', '#333333');
        $("#spnLftFtr2").css('display', 'none');
        $("#lblSaveSkin").hasClass("alert alert-danger")
        $("#lblSaveSkin").removeClass("alert alert-danger").text("");
    });
    $('[id="txtLftFtr3"]', "#SkinExist").bind("keypress keyup", function() {
        $("#txtLftFtr3", "#divSkins").css('border-color', '#cccccc');
        $("#lblLftFtr3").css('color', '#333333');
        $("#spnLftFtr3").css('display', 'none');
        $("#lblSaveSkin").hasClass("alert alert-danger");
        $("#lblSaveSkin").removeClass("alert alert-danger").text("");
    });
    $('[id="txtLftFtr4"]', "#SkinExist").bind("keypress keyup", function() {
        $("#txtLftFtr4", "#divSkins").css('border-color', '#cccccc');
        $("#lblLftFtr4").css('color', '#333333');
        $("#spnLftFtr4").css('display', 'none');
        $("#lblSaveSkin").hasClass("alert alert-danger")
        $("#lblSaveSkin").removeClass("alert alert-danger").text("");
    });
    $('[id="txtRightFtr1"]', "#SkinExist").bind("keypress keyup", function() {
        $("#txtRightFtr1", "#divSkins").css('border-color', '#cccccc');
        $("#lblRightFtr1").css('color', '#333333');
        $("#spnRightFtr1").css('display', 'none');
        $("#lblSaveSkin").hasClass("alert alert-danger")
        $("#lblSaveSkin").removeClass("alert alert-danger").text("");
    });
    $('[id="txtRightFtr2"]', "#SkinExist").bind("keypress keyup", function() {
        $("#txtRightFtr2", "#divSkins").css('border-color', '#cccccc');
        $("#lblRightFtr2").css('color', '#333333');
        $("#spnRightFtr2").css('display', 'none');
        $("#lblSaveSkin").hasClass("alert alert-danger")
        $("#lblSaveSkin").removeClass("alert alert-danger").text("");
    });
    $('[id="txtRightFtr3"]', "#SkinExist").bind("keypress keyup", function() {
        $("#txtRightFtr3", "#divSkins").css('border-color', '#cccccc');
        $("#lblRightFtr3").css('color', '#333333');
        $("#spnRightFtr3").css('display', 'none');
        $("#lblSaveSkin").hasClass("alert alert-danger")
        $("#lblSaveSkin").removeClass("alert alert-danger").text("");
    });
    $('[id="txtRightFtr4"]', "#SkinExist").bind("keypress keyup", function() {
        $("#txtRightFtr4", "#divSkins").css('border-color', '#cccccc');
        $("#lblRightFtr4").css('color', '#333333');
        $("#spnRightFtr4").css('display', 'none');
        $("#lblSaveSkin").hasClass("alert alert-danger")
        $("#lblSaveSkin").removeClass("alert alert-danger").text("");
    });
    $("#hoverevent_Def,#hoverevent_Hide,#hoverevent_Desktop,#hoverevent_Tablet,#hoverevent_HDivider,#hoverevent_L12,#hoverevent_L2,#hoverevent_Nav,#hoverevent_Widget,#hoverevent_ddImage,#hoverevent_tout,#hoverevent_tout_pen,#hoverevent_nloc,#hoverevent_zipUpld").tooltip({
        'selector': '',
        'placement': 'right',
        'width': '400px'
    });
    $("#frmSkinTheme").validate({
        rules: {
            txtfooterCPR: {
                required: true
            },
            txtLftFtrTitle: {
                required: true
            },
            txtRightFtrTitle: {
                required: true
            }
        },
        messages: {
            txtfooterCPR: {
                required: "Please enter the Footer copyright text"
            },
            txtLftFtrTitle: {
                required: "Please enter the Left Footer title"
            },
            txtRightFtrTitle: {
                required: "Please enter the Right Footer title"
            }
        },
        submitHandler: function(form) {
            form.submit();
        },
        errorClass: "help-inline",
        errorElement: "span",
        highlight: function(element, errorClass, validClass) {
            $(element).parents('.control-group').addClass('error');
        },
        unhighlight: function(element, errorClass, validClass) {
            $(element).parents('.control-group').removeClass('error');
        },
        errorPlacement: function(error, element) {
            error.insertAfter(element).css("margin-bottom", "0px");
        }
    });
    $("#logo,#smartphone,#header_div,#level12_bg,#level2_bg,#no_nav,#widget_header,#select_bg", "#divSkinTheme").fileupload({
        dataType: 'json',
        url: BACKENDURL + "customeradmin/skin_image_upload",
        add: function(e, data) {
            var goUpload = true;
            var Imgchosen = data.files[0];
            if (!(/\.(png)$/i).test(Imgchosen.name)) {
                $("#Errformat_" + localStorage["FileUpload"]).text("You must select an 'PNG' image file only").show();
                $("#" + localStorage["FileUpload"]).val("");
                goUpload = false;
                return false;
            }
            var image = new Image();
            if ($.browser.safari) {
                $("#Errformat_" + localStorage["FileUpload"]).hide();
                data.formData = {
                    "session_id": localStorage["SESSIONID"],
                    "img_type": localStorage["FileUpload"],
                    "min_w": localStorage["FileMinWidth"],
                    "max_w": localStorage["FileMaxWidth"],
                    "min_h": localStorage["FileMinHeight"],
                    "max_h": localStorage["FileMaxHeight"]
                };
                data.submit();
            }
            else
            {
                $("#Errformat_" + localStorage["FileUpload"]).hide();
                image.src = url.createObjectURL(Imgchosen);
                $("#img_" + localStorage["FileUpload"]).prop("src", url.createObjectURL(Imgchosen));
                /*if (Math.round(Imgchosen.size / 1024) > localStorage["FileSize"])
                 {
                 $("#Err_" + localStorage["FileUpload"]).text("Please upload a smaller file, max size is " + localStorage["FileSize"] + "KB").show();
                 $("#" + localStorage["FileUpload"]).val("");
                 goUpload = false;
                 return false;
                 }*/
                image.onload = function() {
                    // alert(image.height +","+ image.width);
                    if ((image.width < localStorage["FileMinWidth"]) || (image.width > localStorage["FileMaxWidth"]))
                    {
                        $("#Err_" + localStorage["FileUpload"]).show();
                        goUpload = false;
                    }
                    if (image.height < localStorage["FileMinHeight"] || image.height > localStorage["FileMaxHeight"])
                    {
                        $("#Err_" + localStorage["FileUpload"]).show();
                        goUpload = false;
                    }
                    if (goUpload == true)
                    {
                        $("#Err_" + localStorage["FileUpload"]).hide();
                        data.formData = {
                            "session_id": localStorage["SESSIONID"],
                            "img_type": localStorage["FileUpload"]
                        };
                        data.submit();
                    }
                    else
                    {
                        $("#" + localStorage["FileUpload"]).val("");
                        return false;
                    }

                };
            }
        },
        success: function(data) {
            if (data.error)
            {
                if (data.error_msg = "Error in file upload")
                {
                    $("#Err_" + localStorage["FileUpload"]).show();
                    $("#" + localStorage["FileUpload"]).val("");
                }
                else
                {
                    alert(data.error_msg)
                }

            }
            else
            {
                if ($.browser.safari) {
                    var tmpurl = BACKENDURL + "c_view_assets?id=" + localStorage["SESSIONID"] + "&img_name=" + localStorage["FileUpload"] + ".png";
                    $("#img_" + localStorage["FileUpload"]).prop("src", tmpurl);
                    $("#Err_" + localStorage["FileUpload"]).hide();
                }
                $("#div_" + localStorage["FileUpload"]).css('display', 'block');
                $("#Rem_" + localStorage["FileUpload"]).removeAttr("disabled");
                $("#img_" + localStorage["FileUpload"]).show();
                $("#btnSkinSave").text('Save').attr("disabled", false);
            }
        },
        done: function(e, data) {

        }
    });

}
function clr_ProfileJunkData()
{
    $("#lblSavealert").hasClass("alert alert-success")
    $("#lblSavealert").removeClass('alert alert-success').text('');
    $("#lblSavealert").hasClass("alert alert-danger")
    $("#lblSavealert").removeClass('alert alert-danger').text('');
    $("#spnProfileName").css('display', 'none');
    $("#txtProfileName").css('border-color', '#cccccc');
    $("#lblProfilename").css('color', '#333333');
    $("#btnProfileSave").text('Save').attr("disabled", true);
    $("#divProfileErrMsg").hide();
}

function LoadUserProfile()
{
    customerModuleAccess('AL3PRO', 1);
    formDirtyCheck();
    $('input[id="txtProfileName"]', "#divNewProfile").bind("keyup", function() {
        $("#lblSavealert").removeClass('alert alert-success').text('');
        $("#spnProfileName").css('display', 'none');
        $("#txtProfileName").css('border-color', '#cccccc');
        $("#lblProfilename").css('color', '#333333');
        $("#btnProfileSave").text('Save').attr("disabled", false);
    });
    $("#lnkProfiles").bind("click", SrvUserProfile);
    $("#btnProfileAddUsers", "#divProfileUserList").bind("click", ProfileAddUsers);
    $("#btnProfileRemoveUsers", "#divProfileUserList").bind("click", ProfileRemoveUsers);
    $("#btnProfileDelete", "#divProfiles").bind("click", modalalert);
    $("#btnYesAlert", "#divAlertDelete").bind("click", SrvProfileDelete);
    $("#btnProfileSave", "#divProfiles").bind("click", SrvProfileSave);
    $('#ddlMasterModule', "#divNewProfile").on('change', function() {
        ddlChangeModules();
    });
    $('#ddlUserProfile', "#ProfileExist").on('change', function() {
        ddlChangeProfile(0);
    });
}

function LoadContractSettings()
{
    $('input[id="InputContractName"]').bind("keyup", function() {
        $("#btnSaveContractSettings").text('Save').attr("disabled", false);
    });
    $("#frmContractSettings").validate({
        rules: {
            InputContractName: {
                required: true
            }
        },
        messages: {
            InputContractName: {
                required: "Please enter contract name"
            }
        },
        submitHandler: function(form) {
            form.submit();
        },
        errorClass: "help-inline",
        errorElement: "span",
        highlight: function(element, errorClass, validClass) {
            $(element).parents('.control-group').addClass('error')
            return false;
        },
        unhighlight: function(element, errorClass, validClass) {
            $(element).parents('.control-group').removeClass('error')
            return false;
        },
        errorPlacement: function(error, element) {

            error.insertAfter(element);
            return false;
        }
    });
    $("#btnSaveContractSettings").bind("click", ContractNameSave);
    $("#lnkSettings").click(function() {
        customerModuleAccess('AL3HSET', 0);
        $("#InputContractID").val(localStorage["contractkey"]);
        $("#InputContractName").val(localStorage["contractname"]);
        $("#frmContractSettings").data('validator').resetForm();
        $(".error").removeClass("error");
        $("#idContractError").hide();

    });
}

function LoadSystemAdmin() {
    if (localStorage["Session_logs"] == 0)
        $("#divNotify").addClass("hide");
    $("#lnkSysAdmin").bind("click", ServiceSessLogUsers);
    $("#lnkPhpInfo").bind("click", phpinfo);
    $("#btnSessRefresh", "#divSessionLogs").bind("click", SessRefresh);
    $("#btnSessPurge", "#divSessionLogs").bind("click", ServiceSessPurge);
    $("#btnSessAddUsers", "#divSessionLogs").bind("click", SessAddUsers);
    $("#btnSessRemoveUsers", "#divSessionLogs").bind("click", SessRemoveUsers);
    $("#btnSessionSave", "#divSessionLogs").bind("click", ServiceSessSave);
    customerModuleAccess('AL3SLG', 1);

}

function LoadSafety() {
}

function LoadQualityAudits()
{
    $("#tabQA").bind("click", loadQAAccounts_Srv);
    $("#btnSave_Addacct").bind("click", saveAccounts_srv);
    $("#hoversyncStartDate").tooltip({
        'selector': '',
        'placement': 'right',
        'width': '400px'
    });
    $("#btnAddPoint", "#divAddGroup").click(function() {
        grpChanged = 1;
        $("#btnSave_QAGrp").text('Save').attr("disabled", false);
        var errMsg = "Please select from available Points";
        addTable(lstAvailablePointInd, lstSelectedPointInd, "", divPointIndErr, errMsg);
    });
    $("#btnRemovePoint", "#divAddGroup").click(function() {
        grpChanged = 1;
        $("#btnSave_QAGrp").text('Save').attr("disabled", false);
        var errMsg = "Please select from selected Points";
        remTable(lstAvailablePointInd, lstSelectedPointInd, "", divPointIndErr, errMsg);
    });
    $("#frmEditGrp").validate({
        rules: {
            txtGroupName: {required: true}
        },
        messages: {
            txtGroupName: {required: "Please enter the Group name"},
        },
        submitHandler: function(form) {
            form.submit();
        },
        errorClass: "help-inline",
        errorElement: "span",
        highlight: function(element, errorClass, validClass) {
            $(element).parents('.control-group').addClass('error')
        },
        unhighlight: function(element, errorClass, validClass) {
            $(element).parents('.control-group').removeClass('error')
        },
        errorPlacement: function(error, element) {

            if (element.parent().parent().children().hasClass("inline")) {
                error.insertAfter(element.parent().parent().children('span.inline'));
                return false;
            }

            else if (element.parent().children().hasClass("inline")) {
                error.insertAfter(element.parent().children('span.inline'));
                return false;
            }
            else {
                error.insertAfter(element);
                return false;
            }
        }
    });

    $("#frmEditAccount").validate({
        rules: {
            txtAccName: {required: true},
            txtAccCode: {required: true},
            txtAccDesc: {required: true}
        },
        messages: {
            txtAccName: {required: "Please enter the Account name"},
            txtAccCode: {required: "Please enter the Account Code"},
            txtAccDesc: {required: "Please enter the Account Description"}
        },
        submitHandler: function(form) {
            form.submit();
        },
        errorClass: "help-inline",
        errorElement: "span",
        highlight: function(element, errorClass, validClass) {
            $(element).parents('.control-group').addClass('error')
        },
        unhighlight: function(element, errorClass, validClass) {
            $(element).parents('.control-group').removeClass('error')
        },
        errorPlacement: function(error, element) {

            if (element.parent().parent().children().hasClass("inline")) {
                error.insertAfter(element.parent().parent().children('span.inline'));
                return false;
            }

            else if (element.parent().children().hasClass("inline")) {
                error.insertAfter(element.parent().children('span.inline'));
                return false;
            }
            else {
                error.insertAfter(element);
                return false;
            }
        }
    });
    $('input[id="txtAccName"]').bind("keyup", function() {
        $("#btnSave_Addacct").text('Save').removeAttr("disabled", "disabled");
    });
    $('input[id="txtAccCode"]').bind("keyup", function() {
        $("#btnSave_Addacct").text('Save').removeAttr("disabled", "disabled");
    });
    $('textarea[id="txtAccDesc"]').bind("keyup", function() {
        $("#btnSave_Addacct").text('Save').removeAttr("disabled", "disabled");
    });
    $('input[id="txtGroupAccName"]').bind("keyup", function() {
        $("#btnSave_QAGrp").text('Save').removeAttr("disabled", "disabled");
    });
    $('input[id="txtGroupName"]').bind("keyup", function() {
        $("#btnSave_QAGrp").text('Save').removeAttr("disabled", "disabled");
    });
    $('input[id^="txtRed"]').bind("keyup", function() {
        $("#btnSave_QAGrp").text('Save').removeAttr("disabled", "disabled");
        grpChanged = 1;
    });
    $('input[id^="txtAmber"]').bind("keyup", function() {
        $("#btnSave_QAGrp").text('Save').removeAttr("disabled", "disabled");
        grpChanged = 1;
    });
    $('input[id^="txtGrn"]').bind("keyup", function() {
        $("#btnSave_QAGrp").text('Save').removeAttr("disabled", "disabled");
        grpChanged = 1;
    });
    $('input[id^="txtPurple"]').bind("keyup", function() {
        $("#btnSave_QAGrp").text('Save').removeAttr("disabled", "disabled");
        grpChanged = 1;
    });
    $('input[id^="txtblue"]').bind("keyup", function() {
        $("#btnSave_QAGrp").text('Save').removeAttr("disabled", "disabled");
        grpChanged = 1;
    });
    var todayDate = new Date();
    todayDate.setDate(todayDate.getDate() - 1);
    var RB_date = new Date();
    RB_date.setDate(todayDate.getDate());
    RB_date.setMonth(todayDate.getMonth() - 6);
    RB_date.getFullYear(todayDate.getFullYear());
    $("#divRBEndDate").datepicker({startDate: RB_date, endDate: todayDate}).on(
            "changeDate", function() {
        $("#div_SchEnddate").parent().hasClass("control-group error")
        $("#div_SchEnddate").parents('.control-group').removeClass('error');
        $(".datepicker").hide();
    });

    todayDate.setDate(todayDate.getDate() + 1);
    $("#txtRBED").val(todayDate.getDate() + "/" + (todayDate.getMonth() + 1) + "/" + todayDate.getFullYear());
    // Quality Groups
    $("#btnSave_QAGrp").bind("click", saveQAGrp_srv);

    $("#btnPointIndicatorDown").click(function() {
        $("#lstSelectedPointInd").find("tr").each(function(index) {
            var id = $(this).index();
            var last_tr = $('#lstSelectedPointInd tr:last').index();
            if ($(this).hasClass('alert-success') && $('#lstSelectedPointInd tr.alert-success').length == 1)
                {
                    if (id != last_tr)
                    {
                       $("#lstSelectedPointInd").moveRow(id, id + 1);
                       $("#btnSave_QAGrp").text('Save').removeAttr("disabled", "disabled");
                    }
                }
                
        });
    });

    $("#btnPointIndicatorUp").click(function() {
        $("#lstSelectedPointInd").find("tr").each(function(index) {
            var id = $(this).index();
            if ($(this).hasClass('alert-success') && $('#lstSelectedPointInd tr.alert-success').length == 1)
               {
                    if (id != 0) {
                       $("#lstSelectedPointInd").moveRow(id, id - 1);
                       $("#btnSave_QAGrp").text('Save').removeAttr("disabled", "disabled");
                    }
               }
        });
    });
}

$.fn.extend({
    moveRow: function(oldPosition, newPosition) {
        return this.each(function() {
            var row = $(this).find('tr').eq(oldPosition).detach();
            if (newPosition == $(this).find('tr').length) {
                $(this).find('tr').eq(newPosition - 1).after(row);
            }
            else {
                $(this).find('tr').eq(newPosition).before(row);
            }
        });
    }
});

function LoadDigitalPens()
{
    $("#lnkDgPens").bind("click", loadDgPens);
    $("#lnkDgPens").bind("click", loadDigitalPens_Srv);
    $("#btnclearApp,#btncancelApp", "#divNewAddApp").bind("click", ClearAddApp);
    $("#btnNewAppSubmit", "#divNewAddApp").bind("click", SubmitAddApp);
    $("#btnSave_DigApp", "#divDgApp_edit").bind("click", SaveAddApp);

    $("#btnSave_DigPen", "#divDgPen_edit").bind("click", SaveAddPen);

    $("#btnDel", "#divDgApp_edit").bind("click", deleteApp);
    $("#btnDownload", "#divDgApp_edit").bind("click", downloadtmplate);
    $("#btnClose_DigApp", "#divDgApp_edit").click(function() {
        localStorage['prv_url'] = "";
        localStorage["zipUpld_sts"] = "0";
        $("#txt_zipUpld").val("Choose...");
        $("#zipUpld").val("");
        $("#Errt_zipUpld").hide();
        $("#frmEditApp").data('validator').resetForm();
        $(".error").removeClass("error");
        $("#txtAppName", "#divDgApp_edit").val("");
        $("#txtAppLbl", "#divDgApp_edit").val("");
        $("#txtTimeout", "#divDgApp_edit").val("");
        $("#txtDesc", "#divDgApp_edit").val("");
        $("#divDgApp_edit", "#divDgPens").addClass("hide");
        $("#divDg_add", "#divDgPens").removeClass("hide");
        $("#divtbl_Apps").removeClass("hide");
        $("#lblDigAppError", "#divDgApp_edit").hasClass("alert-success")
        $("#lblDigAppError", "#divDgApp_edit").text("").removeClass("alert-success").hide();
        $("#lblDigAppError", "#divDgApp_edit").hasClass("alert-danger")
        $("#lblDigAppError", "#divDgApp_edit").text("").removeClass("alert-danger").hide();
        $("#btnPreview").attr("disabled", "disabled");
        $("#btnDel").attr("disabled", "disabled");
        $("#btnDownload").attr("disabled", "disabled");
        ClearAddApp();
        loadDgPens();
        loadDigitalPens_Srv();
    });
    $("#btnClose_DigPen", "#divDgPen_edit").click(function() {
        $("#txtPenId", "#divDgPen_edit").val("");
        $("#txtPenLbl", "#divDgPen_edit").val("");
        $("#txtPenTimeout", "#divDgPen_edit").val("");
        $("#txtPenDesc", "#divDgPen_edit").val("");
        $("#divDgPen_edit", "#divDgPens").addClass("hide");
        $("#divDg_add", "#divDgPens").removeClass("hide");
        $("#lblDigPenError", "#divDgPen_edit").hasClass("alert-success")
        $("#lblDigPenError", "#divDgPen_edit").text("").removeClass("alert-success").hide();
        $("#lblDigPenError", "#divDgPen_edit").hasClass("alert-danger")
        $("#lblDigPenError", "#divDgPen_edit").text("").removeClass("alert-danger").hide();
        $("#divtbl_Apps").removeClass("hide");
        ClearAddPen();
        loadDigitalPens_Srv();
    });
    $("#txtTimeout,#txtAppName,#txtAppLbl,#txtDesc", "#divDgPens").keypress(function(e) {
        $("#btnSave_DigApp", "#divDgApp_edit").removeAttr("disabled").text("Save");
        $("#lblDigAppError", "#divDgApp_edit").hasClass("alert-success")
        $("#lblDigAppError", "#divDgApp_edit").text("").removeClass("alert-success").hide();
        $("#lblDigAppError", "#divDgApp_edit").hasClass("alert-danger")
        $("#lblDigAppError", "#divDgApp_edit").text("").removeClass("alert-danger").hide();
    });
    $("#txtPenId,#txtPenLbl,#txtPenTimeout,#txtPenDesc", "#divDgPens").keypress(function(e) {
        $("#btnSave_DigPen", "#divDgPen_edit").removeAttr("disabled").text("Save");
        $("#lblDigPenError", "#divDgPen_edit").hasClass("alert-success")
        $("#lblDigPenError", "#divDgPen_edit").text("").removeClass("alert-success").hide();
        $("#lblDigPenError", "#divDgPen_edit").hasClass("alert-danger")
        $("#lblDigPenError", "#divDgPen_edit").text("").removeClass("alert-danger").hide();
    });
    $("#ddlNavLoc,#ddlForm,#ddlfromhrs,#ddltohrs,#ddlfrommins,#ddltomins", "#divDgPens").on('change', function() {
        $("#btnSave_DigApp", "#divDgApp_edit").removeAttr("disabled").text("Save");
        $("#lblDigAppError", "#divDgApp_edit").hasClass("alert-success")
        $("#lblDigAppError", "#divDgApp_edit").text("").removeClass("alert-success").hide();
        $("#lblDigAppError", "#divDgApp_edit").hasClass("alert-danger")
        $("#lblDigAppError", "#divDgApp_edit").text("").removeClass("alert-danger").hide();

    });
    $("#txtTimeout,#txtPenTimeout", "#divDgPens").keypress(function(e) {
        if (e.which != 8 && e.which != 0 && e.which != 46 && (e.which < 48 || e.which > 57)) {
            return false;
        }
    });
    $("#btnclearPen,#btncancelPen", "#divNewAddPen,#btnAddDigitalPen").bind("click", ClearAddPen);
    $("#btnNewPenSubmit", "#divNewAddPen").bind("click", SubmitAddPen);
    $("#frmNewAddApp").validate({
        rules: {
            txtnewAppName: {required: true},
            txtnewAppLbl: {required: true}
        },
        messages: {
            txtnewAppName: {required: "Please enter the App name"},
            txtnewAppLbl: {required: "Please enter the App label name"}
        },
        submitHandler: function(form) {
            form.submit();
        },
        errorClass: "help-inline",
        errorElement: "span",
        highlight: function(element, errorClass, validClass) {
            $(element).parents('.control-group').addClass('error')
        },
        unhighlight: function(element, errorClass, validClass) {
            $(element).parents('.control-group').removeClass('error')
        },
        errorPlacement: function(error, element) {
            error.insertAfter(element).css("margin-bottom", "0px");
        }
    });
    $("#frmNewAddPen").validate({
        rules: {
            txtnewPenName: {required: true},
            txtnewPenLbl: {required: true}
        },
        messages: {
            txtnewPenName: {required: "Please enter the Pen name"},
            txtnewPenLbl: {required: "Please enter the Pen label name"}
        },
        submitHandler: function(form) {
            form.submit();
        },
        errorClass: "help-inline",
        errorElement: "span",
        highlight: function(element, errorClass, validClass) {
            $(element).parents('.control-group').addClass('error')
        },
        unhighlight: function(element, errorClass, validClass) {
            $(element).parents('.control-group').removeClass('error')
        },
        errorPlacement: function(error, element) {
            error.insertAfter(element).css("margin-bottom", "0px");
        }
    });
    $("#frmEditApp").validate({
        rules: {
            txtAppName: {required: true},
            txtAppLbl: {required: true},
            txtTimeout: {required: true},
            txtDesc: {required: true}

        },
        messages: {
            txtAppName: {required: "Please enter the App name"},
            txtAppLbl: {required: "Please enter the App label name"},
            txtTimeout: {required: "Please enter the Timeout value"},
            txtDesc: {required: "Please enter the App Description"}
        },
        submitHandler: function(form) {
            form.submit();
        },
        errorClass: "help-inline",
        errorElement: "span",
        highlight: function(element, errorClass, validClass) {
            $(element).parents('.control-group').addClass('error')
        },
        unhighlight: function(element, errorClass, validClass) {
            $(element).parents('.control-group').removeClass('error')
        },
        errorPlacement: function(error, element) {

            if (element.parent().parent().children().hasClass("inline")) {
                error.insertAfter(element.parent().parent().children('span.inline'));
                return false;
            }

            else if (element.parent().children().hasClass("inline")) {
                error.insertAfter(element.parent().children('span.inline'));
                return false;
            }
            else {
                error.insertAfter(element);
                return false;
            }
        }
    });
    $("#frmEditPen").validate({
        rules: {
            txtPenId: {required: true},
            txtPenLbl: {required: true},
            txtPenTimeout: {required: true},
            txtPenDesc: {required: true}

        },
        messages: {
            txtPenId: {required: "Please enter the Pen ID"},
            txtPenLbl: {required: "Please enter the Pen label name"},
            txtPenTimeout: {required: "Please enter the Timeout value"},
            txtPenDesc: {required: "Please enter the Pen Description"}
        },
        submitHandler: function(form) {
            form.submit();
        },
        errorClass: "help-inline",
        errorElement: "span",
        highlight: function(element, errorClass, validClass) {
            $(element).parents('.control-group').addClass('error')
        },
        unhighlight: function(element, errorClass, validClass) {
            $(element).parents('.control-group').removeClass('error')
        },
        errorPlacement: function(error, element) {

            if (element.parent().parent().children().hasClass("inline")) {
                error.insertAfter(element.parent().parent().children('span.inline'));
                return false;
            }

            else if (element.parent().children().hasClass("inline")) {
                error.insertAfter(element.parent().children('span.inline'));
                return false;
            }
            else {
                error.insertAfter(element);
                return false;
            }
        }
    });
    $("#zipUpld").fileupload({
        dataType: 'json',
        url: BACKENDURL + "customeradmin/upload_zip_file",
        dataType: "json",
                crossDomain: true,
        add: function(e, data) {
            var goUpload = true;
            var Filechosen = data.files[0];
            if (!(/\.(zip)$/i).test(Filechosen.name)) {
                $("#Errt_zipUpld").show().text("You must select an 'Zip' file only");
                goUpload = false;
                return false;
            }
            if (goUpload == true)
            {
                $("#Errt_zipUpld").hide();
                data.formData = {
                    session_id: localStorage["SESSIONID"],
                    digital_form_id: "1"
                };
                data.submit();
            }
        },
        success: function(data) {
            if (data.error)
            {
                $("#Errt_zipUpld").show().text(data.error_msg);
            }
            else
            {
                localStorage['prv_url'] = data.link;
                $("#txt_zipUpld").val(data.upload_file_name);
                localStorage["zipUpld_sts"] = "1";
                $("#btnPreview").removeAttr("disabled");
                $("#btnSave_DigApp", "#divDgApp_edit").removeAttr("disabled").text("Save");
                $("#lblDigAppError", "#divDgApp_edit").hasClass("alert-success")
                $("#lblDigAppError", "#divDgApp_edit").text("").removeClass("alert-success").hide();
                $("#lblDigAppError", "#divDgApp_edit").hasClass("alert-danger")
                $("#lblDigAppError", "#divDgApp_edit").text("").removeClass("alert-danger").hide();
            }

        },
        done: function(e, data) {

        }
    });
}
//Load values in phpinfo screen 
function NewUserProfile()
{

    $("#NewUserLabelBtn").text("");
    $(txtUserFName).removeAttr('style');
    $(txtUserLName).removeAttr('style');
    $(txtUseremail).removeAttr('style');
    $(txtUserCemail).removeAttr('style');
    $(txtUserFName).val('');
    $(txtUserLName).val('');
    $(txtUseremail).val('');
    $(txtUserCemail).val('');
}
function phpinfo() {
    var url = BACKENDURL + "customeradmin/get_php_info";
    var data = {
        session_id: localStorage["SESSIONID"]
    };
    MakeAjaxCall(url, data, loadPhpinfo);
}
function loadPhpinfo(data) {
    if (data.error == 0) {
        $("#phpinfo").append(data.info_res)
        $("#phpinfo").find("img").remove();
    } else
        logout(1);
}
// Loading the page with the customer details...
function loadUsers() {
    customerModuleAccess('AL3USR', 0);
    $.ajax({
        url: BACKENDURL + "customeradmin/get_users",
        type: "post",
        data: {
            session_id: localStorage["SESSIONID"],
            customer_id: localStorage["CUSTOMERID"],
            contract_id: localStorage["contractid"]
        },
        dataType: "json",
        crossDomain: true,
        success: function(data) {
            if (data.session_status) {
                if (data.error == 0) {
                    var nCurrRecRound = 0;
                    var nCount = 0;
                    $("#tblPupils  tbody:last").empty();
                    var hdnCurrPage = $("#currPageNumber", "#tablePagination").val();
                    if (hdnCurrPage != undefined) {
                        nCurrRecRound = hdnCurrPage - 1;
                    }
                    $("#tablePagination").remove();
                    //Get the customer data from the database.
                    //display customers....
                    //var border = "border-top: 0px;";
                    $("#tblUsers tbody").children().remove();
                    if (data.users_res.length > 0)
                        $("#tblUsers").show();
                    else
                        $("#tblUsers").hide();
                    $.each(data.users_res, function() {
                        var disablebtn = "<a class='btn btn-small btn-danger' data-toggle='modal' href='#BtnToggleModal' id='btnDisableUser' name='btnDisableUser' style='width: 98px;' onclick='javascript:changeStatus(this,\"D\"," + this.user_id + ")'><i class='icon-remove-sign icon-white'></i> Disable</a>";

                        if (this.status == 0) {
                            disablebtn = "<a class='btn btn-small btn-success' data-toggle='modal' href='#BtnToggleModal' id='btnDisableUser' name='btnDisableUser' style='width: 98px;' onclick='javascript:changeStatus(this,\"E\"," + this.user_id + ")'><i class='icon-ok-sign icon-white'></i> Enable</a>";
                        }

                        var userprofile = (!this.profile_name) ? "-" : this.profile_name;
                        $("#tblUsers  tbody:last").append("<tr><td><span>" + this.first_name + "</span></td><td><span>" + this.last_name + "</span></td><td><span>" + this.username + "</span></td><td><span>" + userprofile + "</span></td><td style='text-align:right'>" + disablebtn + "</td></tr>");
                        border = "";
                        //alert(this.customer_id + " " + this.customer_name);
                        if (this.first_name == $("#txtUserFName").val()) {
                            nCurrRecRound = Math.floor(nCount / 10);
                            $("#txtUserFName").val("");
                        }
                        nCount++;
                    });
                    customerModuleAccess('AL3USR', 1);
                    if (data.users_res.length > 10)
                        $("#tblUsers").tablePagination({
                            currPage: nCurrRecRound + 1
                        });
                } else
                    logout(1);
            } else {
                logout();
                // localStorage.clear();
                // location.href = 'index.html';
            }
        },
        error: function(xhr, textStatus, error) {
            alert(error);
            //$("#result").html('there is error while submit');
        }
    });
}

//New user modal click
$("#btnNewUser").click(function() {
    $("input[type='text']", "#divNewUser").each(function() {
        $(this).val('');
    });
});
function ContractNameSave()
{

    if ($("#frmContractSettings").valid()) {
        $("#btnSaveContractSettings").attr("disabled", true).text('Saving');
        var url = BACKENDURL + "customeradmin/edit_contract";
        var data = {
            session_id: localStorage["SESSIONID"],
            contract_id: localStorage["contractid"],
            contract_name: $("#InputContractName").val()
        };
        MakeAjaxCall(url, data, Savecontract);
    }
}
function Savecontract(data)
{
    if ($("#frmContractSettings").valid()) {
        if (data.error == 1)
        {
            if (data.error_msg == unAthMsg)
                logout(1)
            else {
                $("#btnSaveContractSettings").attr("disabled", false).text('Save');
                $("#idContractError").addClass('alert alert-error').text(data.error_msg).show();
            }
        }
        $('input[id^="InputContractName"]').bind("keypress keyup", function() {
            $("#idContractError").removeClass('alert alert-error')
            $("#idContractError").text('').show();
        });
        if (data.error == 0)
        {
            localStorage.setItem("contractname", $("#InputContractName").val());
            $("#divLoggedAs").html(" You are administering" + " " + localStorage["contractname"])
            $("#btnSaveContractSettings").text('Saved');
        }
    }

}


//New user modal key press.
$("input[type='text']", "#divNewUser").keypress(function(e) {
    if ((e.which && e.which == 13) || (e.keyCode && e.keyCode == 13)) {
        $("#btnNewUserSubmit", "#divNewUser").click();
        return false;
    } else
        return true;
});


// New customer admin submit button
$("#btnNewUserSubmit", "#divNewUser").click(function() {


    if ($("#txtUserFName", "#divNewUser").val() == "")
    {
        $("#txtUserFName", "#divNewUser").focus();
        ModalFieldUI(txtUserFName);
        $("#NewUserLabelBtn").text("Please enter the first name");
        return false;
    } else if ($("#txtUserLName", "#divNewUser").val() == "")
    {
        $(txtUserFName).removeAttr('style');
        $("#txtUserLName", "#divNewUser").focus();
        ModalFieldUI(txtUserLName);
        $("#NewUserLabelBtn").text("Please enter last name");
        return false;
    } else if ($("#txtUseremail", "#divNewUser").val() == "")
    {
        $(txtUserLName).removeAttr('style');
        $("#txtUseremail", "#divNewUser").focus();
        ModalFieldUI(txtUseremail);
        $("#NewUserLabelBtn").text("Please enter email address");
        return false;
    } else if (!isEmail($("#txtUseremail", "#divNewUser").val())) {
        $(txtUserLName).removeAttr('style');
        $("#txtUseremail", "#divNewUser").focus();
        ModalFieldUI(txtUseremail);
        $("#NewUserLabelBtn").text("Please enter a valid email address");
        return false;
    }
    else if ($("#txtUserCemail", "#divNewUser").val() == "")
    {
        $(txtUseremail).removeAttr('style');
        $("#txtUserCemail", "#divNewUser").focus();
        ModalFieldUI(txtUserCemail);
        $("#NewUserLabelBtn").text("Please confirm new email address");
        return false;
    } else if ($("#txtUseremail", "#divNewUser").val() != $("#txtUserCemail", "#divNewUser").val())
    {
        $(txtUseremail).removeAttr('style');
        $("#txtUseremail", "#divNewUser").focus();
        ModalFieldUI(txtUseremail);
        $("#NewUserLabelBtn").text("New and confirm email address are not the same");
        return false;
    } else {
        $("#btnNewUserSubmit").attr("disabled", "disabled");
        $("#txtUserFName").css("border", "");
        $("#txtUserLName").css("border", "");
        $("#txtUseremail").css("border", "");
        $("#txtUserCemail").css("border", "");
        $("#NewUserLabelBtn").html('');
        //Save the information...

        $.ajax({
            url: BACKENDURL + "customeradmin/create_user",
            type: "post",
            data: {
                session_id: localStorage["SESSIONID"],
                customer_id: localStorage["CUSTOMERID"],
                contract_id: localStorage["contractid"],
                user_fname: $("#txtUserFName", "#divNewUser").val(),
                user_lname: $("#txtUserLName", "#divNewUser").val(),
                user_email: $("#txtUseremail", "#divNewUser").val()
            },
            dataType: "json",
            crossDomain: true,
            success: function(data) {

                if (data.session_status) {
                    if (data.error) {
                        if (data.error_msg == unAthMsg) {
                            logout(1);
                        } else {
                            $("#NewUserLabelBtn").html(data.error_msg);
                            $("#btnNewUserSubmit").removeAttr('disabled');
                        }
                    } else {

                        //close the modal 
                        $("#xNewUser", "#divNewUser").click();
                        $("#lblSuccessHeading", "#divSuccess").html("Create a new user");
                        $("#lblSuccessMessage", "#divSuccess").html("Success. New user created.");
                        $("#btnNewUserSubmit").removeAttr('disabled');
                        $("#btnSuccess").click();
                        loadUsers();
                    }
                } else {
                    logout();
                    // localStorage.clear();
                    // location.href = 'index.html'
                }
            },
            error: function(xhr, textStatus, error) {

                $("#NewUserLabelBtn").html("there is error while submit");
            }
        });
    }

});




//Activate/inactiving customers.
function changeStatus(f, statusType, user_id) {
    //$("#BtnToggleModal").modal('show');
    var status = (statusType == 'D') ? 0 : 1;

    if (status == 1) {
        $("#ToggleBtnLabel").text('Are you sure you want to Enable?');
    }
    else if (status == 0) {
        $("#ToggleBtnLabel").text('Are you sure you want to Disable?');
    }
    $("#ToggleCancelBtn").click(function() {
        $("#BtnToggleModal").modal('hide');
    });

    $("#ToggleOkBtn").click(function() {



        $.ajax({
            url: BACKENDURL + "customeradmin/update_user_status",
            type: "post",
            //contentType: "text/xml; charset=utf-8",
            data: {
                session_id: localStorage["SESSIONID"],
                customer_id: localStorage["CUSTOMERID"],
                contract_id: localStorage["contractid"],
                user_id: user_id,
                status: status
            },
            dataType: "json",
            crossDomain: true,
            success: function(data) {

                if (data.session_status) {

                    if (data.error) {
                        logout(1);
                    } else {

                        if (statusType == 'D') {

                            $(f).html('<span class="icon-ok-sign icon-white"></span> Enable').addClass('btn-success').attr('onclick', 'javascript:changeStatus(this,"E",' + user_id + ')');
                        }
                        else {
                            $(f).html('<span class="icon-remove-sign icon-white"></span> Disable').removeClass('btn-success').addClass('btn-danger').attr('onclick', 'javascript:changeStatus(this,"D",' + user_id + ')');

                        }
                        $("#ToggleOkBtn").off('click');
                        $("#BtnToggleModal").modal('hide');

                    }
                } else {
                    logout();
                    // localStorage.clear();
                    // location.href = 'index.html'
                }
            },
            error: function(xhr, textStatus, error) {

                alert(error);

            }
        });

    });
}

/********* Session Log **************/



function ServiceSessLogUsers()
{
    var url = BACKENDURL + "customeradmin/get_session_log_contract";
    var data = {
        contract_id: localStorage["contractid"],
        session_id: localStorage["SESSIONID"]
    };
    MakeAjaxCall(url, data, loadSessLogUsers);

}

function loadSessLogUsers(data)
{
    if (data.session_status) {
        if (data.error == 0) {
            $("#spnGenRefresh").addClass("hide");
            if ($("#divDataSessionLogs").hasClass("hide"))
                $("#divDataSessionLogs").removeClass("hide");
            if ($("#divWarningMsg").hasClass("hide"))
                $("#divWarningMsg").removeClass("hide");
            $("#divPurgeMsg").addClass("hide");
            var tblSessionlogs = "";
            $("#lstAvailableUsr").empty();
            $("#lstSelectedUsr").empty();
            (data.session_res[0].contract_session) == "0" ? $('#chkEnableSession').prop('checked', false) : $('#chkEnableSession').prop('checked', true);
            var data_availableusers = "", data_selectedusers = "", data_populate = "", data_profilename = "";
            for (var nCount = 0; nCount < data.session_res[0].available_users.length; nCount++) {
                data_profilename = (data.session_res[0].available_users[nCount].profile_name != '') ? ' (' + data.session_res[0].available_users[nCount].profile_name + ') ' : '';
                data_populate = data.session_res[0].available_users[nCount].first_name + " " + data.session_res[0].available_users[nCount].last_name + ", " + data.session_res[0].available_users[nCount].username + data_profilename;
                data_availableusers += "<option value=" + data.session_res[0].available_users[nCount].user_id + ">" + data_populate + "</option>";
            }
            for (var nCount = 0; nCount < data.session_res[0].selected_users.length; nCount++) {
                data_profilename = (data.session_res[0].selected_users[nCount].profile_name != '') ? ' (' + data.session_res[0].selected_users[nCount].profile_name + ') ' : '';
                data_populate = data.session_res[0].selected_users[nCount].first_name + " " + data.session_res[0].selected_users[nCount].last_name + ", " + data.session_res[0].selected_users[nCount].username + data_profilename;
                data_selectedusers += "<option value=" + data.session_res[0].selected_users[nCount].user_id + ">" + data_populate + "</option>";
            }
            $("#lstAvailableUsr").append(data_availableusers);
            $("#lstSelectedUsr").append(data_selectedusers);
            $("#lstAvailableUsr option").length == 0 ? $("#btnSessAddUsers").attr("disabled", "disabled") : $("#btnSessAddUsers").removeAttr("disabled");
            $("#lstSelectedUsr option").length == 0 ? $("#btnSessRemoveUsers").attr("disabled", "disabled") : $("#btnSessRemoveUsers").removeAttr("disabled");
            $("#tblDataSessionLogs tbody tr").remove();
            for (var nCount = 0; nCount < data.session_res[0].log_records.length; nCount++)
            {
                tblSessionlogs += "<tr><td nowrap='nowrap'>" + data.session_res[0].log_records[nCount].username + "</td>";
                tblSessionlogs += "<td nowrap='nowrap'>" + data.session_res[0].log_records[nCount].ip_address + "</td>";
                tblSessionlogs += "<td nowrap='nowrap'>" + data.session_res[0].log_records[nCount].cdate + "</td>";
                tblSessionlogs += "<td nowrap='nowrap'>" + data.session_res[0].log_records[nCount].message + "</td></tr>";
                $("#tblDataSessionLogs  tbody:last").append(tblSessionlogs);
                $("#tblDataSessionLogs").show();
                tblSessionlogs = "";
            }
            var total_sessionlogs = data.session_res[0].total_logs;
            var total_page = Math.ceil(total_sessionlogs / 50);
            var no_pages = total_page > 2 ? 2 : 1;
            if (total_sessionlogs > 50)
            {
                var options = {
                    currentPage: 1,
                    alignment: "right",
                    totalPages: total_page,
                    //numberOfPages: no_pages,
                    pageUrl: "javascript:void(0)",
                    itemTexts: function(type, page, current) {
                        switch (type) {
                            case "first":
                                return "First";
                            case "prev":
                                return "Prev";
                            case "next":
                                return "Next";
                            case "last":
                                return "Last";
                            case "page":
                                return page;
                        }
                    },
                    onPageClicked: function(e, originalEvent, type, page) {
                        ServiceNavSessLogUsers(page);
                    }
                };
                $("#Sessionlogs_pag").bootstrapPaginator(options);
                $("#Sessionlogs_pag").show();
            } else {
                $("#Sessionlogs_pag").hide();
            }


        } else {
            logout(1);
        }
        $("#btnSessRefresh").removeAttr("disabled");
    }
}
function ServiceNavSessLogUsers(pageNo)
{
    var url = BACKENDURL + "customeradmin/get_session_log_navigation";
    var data = {
        contract_id: localStorage["contractid"],
        session_id: localStorage["SESSIONID"],
        page_no: pageNo
    };
    MakeAjaxCall(url, data, NavSessLogUsers);
}
function NavSessLogUsers(data)
{
    if (data.session_status) {
        if (data.error == 0) {
            var tblSessionlogs = "";
            $("#tblDataSessionLogs tbody tr").remove();
            for (var nCount = 0; nCount < data.log_records.length; nCount++)
            {
                tblSessionlogs += "<tr><td nowrap='nowrap'>" + data.log_records[nCount].username + "</td>";
                tblSessionlogs += "<td nowrap='nowrap'>" + data.log_records[nCount].ip_address + "</td>";
                tblSessionlogs += "<td nowrap='nowrap'>" + data.log_records[nCount].cdate + "</td>";
                tblSessionlogs += "<td nowrap='nowrap'>" + data.log_records[nCount].message + "</td></tr>";
                $("#tblDataSessionLogs  tbody:last").append(tblSessionlogs);
                $("#tblDataSessionLogs").show();
                tblSessionlogs = "";
            }
        }
        else
        {
            alert(data.error_msg);
        }
    }

}

function SessAddUsers()
{
    var selectedOpts = $('#lstAvailableUsr option:selected');
    if (selectedOpts.length == 0) {
        $("#divUserErrMsg").html("Please select from available users").show();
    }
    else
    {
        $("#divUserErrMsg").hide();
        $("#btnSessionSave").removeAttr("disabled").text('Save');
        for (var i = 0; i < selectedOpts.length; i++) {
            if (selectedOpts[i].selected == true) {
                usersArray.push({
                    "user_id": $(selectedOpts[i]).val(),
                    "session_log": "1"
                });
            }
        }
        $("#lstSelectedUsr").prepend($(selectedOpts).clone());
        $(selectedOpts).remove();
        SortOptions("#lstSelectedUsr");
        $("#lstAvailableUsr option").length == 0 ? $("#btnSessAddUsers").attr("disabled", "disabled") : $("#btnSessAddUsers").removeAttr("disabled");
        $("#lstSelectedUsr option").length == 0 ? $("#btnSessRemoveUsers").attr("disabled", "disabled") : $("#btnSessRemoveUsers").removeAttr("disabled");
    }
}

function SessRemoveUsers()
{
    var selectedOpts = $('#lstSelectedUsr option:selected');
    if (selectedOpts.length == 0) {
        $("#divUserErrMsg").html("Please select from selected users").show();
    }
    else
    {
        $("#divUserErrMsg").hide();
        $("#btnSessionSave").removeAttr("disabled").text('Save');
        for (var i = 0; i < selectedOpts.length; i++) {
            if (selectedOpts[i].selected == true) {
                usersArray.push({
                    "user_id": $(selectedOpts[i]).val(),
                    "session_log": "0"
                });
            }
        }
        $("#lstAvailableUsr").prepend($(selectedOpts).clone());
        $(selectedOpts).remove();
        SortOptions("#lstAvailableUsr");
        $("#lstAvailableUsr option").length == 0 ? $("#btnSessAddUsers").attr("disabled", "disabled") : $("#btnSessAddUsers").removeAttr("disabled");
        $("#lstSelectedUsr option").length == 0 ? $("#btnSessRemoveUsers").attr("disabled", "disabled") : $("#btnSessRemoveUsers").removeAttr("disabled");
    }
}

function chkSession()
{
    $("#btnSessionSave").removeAttr("disabled").text('Save');
}

function ServiceSessSave()
{
    $("#btnSessionSave").attr("disabled", true);
    var EnableSession = $("#chkEnableSession").is(':checked') ? "1" : "0";
    if (EnableSession == "0")
    {
        localStorage["Session_logs"] = 0;
        $("#divNotify").addClass("hide");
    }
    else
    {
        localStorage["Session_logs"] = 1;
        $("#divNotify").hasClass("hide");
        $("#divNotify").removeClass("hide");
    }
    var url = BACKENDURL + "customeradmin/save_session_log_contract";
    var data = {
        contract_id: localStorage["contractid"],
        session_id: localStorage["SESSIONID"],
        session_log_contract: EnableSession,
        user_data: usersArray
    };
    MakeAjaxCall(url, data, SessSave);
}

function SessSave(data)
{
    if (data.error == 0)
        $("#btnSessionSave").text('Saved').attr("disabled");
    else
        logout(1)
}

function SessRefresh()
{
    $("#btnSessRefresh").attr("disabled", true);
    $("#spnGenRefresh").removeClass("hide");
    $("#tblDataSessionLogs tbody tr").empty();
    $("#tblDataSessionLogs").hide();
    ServiceSessLogUsers();
    $("#Sessionlogs_pag").hide();

}

function ServiceSessPurge()
{
    if ($("#tblDataSessionLogs tbody tr").length > 0)
    {
        var url = BACKENDURL + "customeradmin/purge_session_log_contract";
        var data = {
            session_id: localStorage["SESSIONID"],
            contract_id: localStorage["contractid"]
        };
        $("#spnGenPurge").removeClass("hide");
        MakeAjaxCall(url, data, SessPurge);
    }
    else
    {
        $("#divPurgeMsg").removeClass("hide").text(" No session logs have been captured for contract " + localStorage["contractname"]);

    }
}

function SessPurge(data)
{
    if (data.session_status) {
        if (data.error == 0) {
            $("#divDataSessionLogs").addClass("hide");
            $("#divPurgeMsg").removeClass("hide").text("Session logs has been deleted for the contract " + localStorage["contractname"]);
            $("#divWarningMsg").addClass("hide");
            $("#spnGenPurge").addClass("hide");
        }
        else {
            logout(1);
        }
    }
}
/*********************** Administrators ******************************/
function SrvUserAdmin()
{
    var url = BACKENDURL + "customeradmin/get_users_configure_contract";
    var data = {
        session_id: localStorage["SESSIONID"],
        contract_id: localStorage["contractid"]
    };
    MakeAjaxCall(url, data, loadAdministrators);
}
function loadAdministrators(data) {
    customerModuleAccess('AL3ADM', 0);
    if (data.session_status) {
        if (data.error == 0) {
            $("#lstAvailableAdmin > tbody > tr").remove();
            $("#lstSelectedAdmin > tbody > tr").remove();
            var dataadmin_availableusers = "";
            var dataadmin_selectedusers = "";

            for (var nCount = 0; nCount < data.user_res[0].available_users.length; nCount++) {
                data_profilename = (data.user_res[0].available_users[nCount].profile_name != '') ? ' (' + data.user_res[0].available_users[nCount].profile_name + ') ' : '';
                data_populate = data.user_res[0].available_users[nCount].first_name + " " + data.user_res[0].available_users[nCount].last_name + ", " + data.user_res[0].available_users[nCount].username + data_profilename;
                if (localStorage["USERID"] == data.user_res[0].available_users[nCount].user_id)
                    dataadmin_availableusers += "<tr id='" + data.user_res[0].available_users[nCount].user_id + "'><td style='border:1px solid #d3d3d3;background-color:#d3d3d3;color:black;'>" + data_populate + "</td></tr>";
                else
                    dataadmin_availableusers += "<tr id='" + data.user_res[0].available_users[nCount].user_id + "' onClick='addRemoveUsers(this);'><td style='border:1px solid #d3d3d3'>" + data_populate + "</td></tr>";
                //dataadmin_availableusers += "<tr title='title here.' content='content goes here...' id='" + data.user_res[0].available_users[nCount].user_id + "' onmouseover='showPop(this);' onmouseout='hidePop(this);' onClick='addRemoveUsers(this);'><td style='border:1px solid #d3d3d3'>" + data_populate + "</td></tr>";
            }
            for (var nCount = 0; nCount < data.user_res[0].selected_users.length; nCount++) {
                data_profilename = (data.user_res[0].selected_users[nCount].profile_name != '') ? ' (' + data.user_res[0].selected_users[nCount].profile_name + ') ' : '';
                data_populate = data.user_res[0].selected_users[nCount].first_name + " " + data.user_res[0].selected_users[nCount].last_name + ", " + data.user_res[0].selected_users[nCount].username + data_profilename;
                if (localStorage["USERID"] == data.user_res[0].selected_users[nCount].user_id)
                    dataadmin_selectedusers += "<tr id='" + data.user_res[0].selected_users[nCount].user_id + "'><td style='border:1px solid #d3d3d3;background-color:#d3d3d3;color:black;'>" + data_populate + "</td></tr>";
                else
                    dataadmin_selectedusers += "<tr id='" + data.user_res[0].selected_users[nCount].user_id + "' onClick='addRemoveUsers(this);'><td style='border:1px solid #d3d3d3'>" + data_populate + "</td></tr>";
            }
            $("#lstAvailableAdmin  tbody:last").append(dataadmin_availableusers);
            $("#lstSelectedAdmin  tbody:last").append(dataadmin_selectedusers);
            customerModuleAccess('AL3ADM', 1);
        } else
            logout(1);
    }
}
function showPop(f) {
    var tblName = $(f).parent().parent().attr("id");
    var placement = "left";
    if (tblName == 'lstAvailableAdmin')
        placement = "right";
    $(f).popover({
        placement: placement,
        title: "<p style='word-wrap:break-word;'>" + $(f).attr("title") + "</p>",
        content: "<p style='word-wrap:break-word;'>" + $(f).attr("content") + "</p>"
    }).popover('show');
}
function hidePop(f) {
    $(f).popover('destroy');
}

function addRemoveUsers(f) {
    if ($(f).hasClass("alert alert-success"))
        $(f).removeClass("alert alert-success");
    else
        $(f).addClass("alert alert-success");
}
function AddContractAdmin() {
    var count = 0;
    $("#lstAvailableAdmin tr").each(function(e) {
        if ($(this).hasClass("alert alert-success")) {
            count++;
            $("#lstSelectedAdmin tbody").append($(this).removeClass("alert alert-success").clone());
            $(this).detach();
        }
    });

    if (count == 0)
        $("#divAdminErrMsg").html("Please select from available administrators").show();
    else {
        $("#divAdminErrMsg").hide();
        $("#btnSaveContractAdmin").removeAttr("disabled").text('Save');
        Sort_table("lstSelectedAdmin");
    }
}
function RemoveContractAdmin() {
    var count = 0;
    $("#lstSelectedAdmin tr").each(function(e) {
        if ($(this).hasClass("alert alert-success")) {
            count++;
            $("#lstAvailableAdmin tbody").append($(this).removeClass("alert alert-success").clone());
            $(this).detach();
        }
    });

    if (count == 0)
        $("#divAdminErrMsg").html("Please select from available administrators").show();
    else {
        $("#divAdminErrMsg").hide();
        $("#btnSaveContractAdmin").removeAttr("disabled").text('Save');
        Sort_table("lstAvailableAdmin");
    }
}
function SrvSaveContractAdmin() {
    var contractUsersArray = [];
    $("#lstAvailableAdmin tr").each(function(e) {
        contractUsersArray.push({
            "user_id": $(this).attr("id"),
            "status": "0"
        });
    });
    $("#lstSelectedAdmin tr").each(function(e) {
        contractUsersArray.push({
            "user_id": $(this).attr("id"),
            "status": "1"
        });
    });

    if (contractUsersArray.length > 0) {
        $("#btnSaveContractAdmin").text('Saving').attr("disabled", true);
        var url = BACKENDURL + "customeradmin/save_users_configure_contract";
        var data = {
            session_id: localStorage["SESSIONID"],
            contract_id: localStorage["contractid"],
            user_data: contractUsersArray
        };
        MakeAjaxCall(url, data, ContractAdminSave);
    } else {
        ContractAdminSave();
    }
}

function ContractAdminSave() {
    $("#btnSaveContractAdmin").text('Saved').attr("disabled", true);
}
/*********************** User Profile ******************************/


function SrvUserProfile() {
    var url = BACKENDURL + "customeradmin/get_profile_master_details";
    var data = {
        session_id: localStorage["SESSIONID"],
        contract_id: localStorage["contractid"]
    };
    MakeAjaxCall(url, data, UserProfile);
}
function UserProfile(data) {
    if (data.error == 0) {
        localStorage["profile_length"] = data.profile_res[0].profiles.length;
        if (data.profile_res[0].profiles.length > 0)
        {
            $("#NewProfile").addClass("hide");
            $("#ProfileExist").show();
            var data_profile = "";
            $("#ddlUserProfile", "#ProfileExist").empty();
            $("#ProfileExist").removeClass("hide");
            var selectedStr = " Selected ";
            if (data.profile_res[0].profiles.length == 1)
            {
                $("#lblProfile").hide();
                $("#spnUserprofile").hide();
            }
            else
            {
                $("#lblProfile").show();
                $("#spnUserprofile").show();
            }
            for (var nCount = 0; nCount < data.profile_res[0].profiles.length; nCount++) {
                data_profile += "<option value=" + data.profile_res[0].profiles[nCount].profile_id + " " + selectedStr + " >" + data.profile_res[0].profiles[nCount].profile_name + "</option>";
                selectedStr = "";
            }
            $("#ddlUserProfile", "#ProfileExist").append(data_profile);
            MasterPageData(data);
            var url = BACKENDURL + "customeradmin/get_profile_details_contract";
            var data = {
                session_id: localStorage["SESSIONID"],
                contract_id: localStorage["contractid"],
                profile_id: $("#ddlUserProfile").val()
            };

            MakeAjaxCall(url, data, UserProfileData);
        }
        else
        {
            $("#NewProfile").removeClass("hide");
            $("#ProfileExist").hide();

        }
    } else
        logout(1);
}

function UserProfileData(data)
{
    if (data.error == 0) {
        var data_ProfAvailUsers = "", data_ProfSelectedUsers = "", data_ProfDet = "";
        var ProfileName = data.profile_res[0].profile_res[0].profile_name;
        localStorage["profile_id"] = data.profile_res[0].profile_res[0].profile_id;
        localStorage["skin_id"] = data.profile_res[0].profile_res[0].skin_id;
        (data.profile_res[0].profile_res[0].self_registration) == "0" ? $('#chkSelfReg').prop('checked', false) : $('#chkSelfReg').prop('checked', true);
        $("#txtProfileName", "#ProfileExist").val(ProfileName);
        $("#ddlMasterModule").val(data.profile_res[0].profile_res[0].m_module_id);
        $("#ddlSkinProfiles").val(data.profile_res[0].profile_res[0].skin_id);
        if ((data.profile_res[0].profile_res[0].hide_main_nav) == "0")
        {
            $('#chkHideMainNav').prop('checked', false);
            $("#ddlMasterModule").prop("disabled", true);
        }
        else
        {
            $('#chkHideMainNav').prop('checked', true);
            $("#ddlMasterModule").prop("disabled", false);
            ddlChangeModules();
        }

        for (var optsCount = 0; optsCount < data.profile_res[0].profile_main_modules.length; optsCount++)
        {
            $("#opt_" + data.profile_res[0].profile_main_modules[optsCount].s_module_id + "_Active").prop('checked', true);
            //$("#tbl_Mod_"+data.profile_res[0].profile_main_modules[optsCount].s_module_id+" :input").attr('disabled', true);
            //$("#data-submodules #tbl_Mod_+ data.profile_res[0].profile_main_modules[optsCount].s_module_id").attr("disabled", false);
            //$("#tbl_Mod_" + data.profile_res[0].profile_main_modules[optsCount].s_module_id).attr("disabled", false);
            //console.log($("#tbl_Mod_" + data.profile_res[0].profile_main_modules[optsCount].s_module_id));
            //$("#tbl_Mod_" + data.profile_res[0].profile_main_modules[optsCount].s_module_id).attr('disabled', false);
        }
        for (var SSMCount = 0; SSMCount < data.profile_res[0].profile_sub_modules.length; SSMCount++)
        {
            $("[name$=SSM" + data.profile_res[0].profile_sub_modules[SSMCount].ss_module_id + "]").prop('checked', true);
            // $("#opt_"+data.profile_res[0].profile_main_modules[optsCount].s_module_id+"_Active").prop('checked',true);
        }
        $('#divAccordProfile input[type="radio"]:checked').each(function() {
            var activeSubmod = $(this).attr('id');
            if (activeSubmod.indexOf("Inactive") >= 0)
                optLevelPermission(this);
        });
        /**** User list ****/

        $("#lstProfileAvailableUsr", "#divProfileUserList").empty();
        $("#lstProfileSelectedUsr", "#divProfileUserList").empty();
        var data_userprofilename = "";

        for (var nCount = 0; nCount < data.profile_res[0].available_users.length; nCount++) {
            var disabled = (data.profile_res[0].available_users[nCount].profile_name) == "" ? "" : "disabled";
            if (data.profile_res[0].available_users[nCount].profile_name != '')
            {
                data_userprofilename = '(' + data.profile_res[0].available_users[nCount].profile_name + ')'
            }
            else
            {
                data_userprofilename = ""
            }
            data_ProfDet = data.profile_res[0].available_users[nCount].first_name + " " + data.profile_res[0].available_users[nCount].last_name + ", " + data.profile_res[0].available_users[nCount].username + " " + data_userprofilename;
            data_ProfAvailUsers += "<option value=" + data.profile_res[0].available_users[nCount].user_id + " " + disabled + " >" + data_ProfDet + "</option>";
        }
        for (var nCount = 0; nCount < data.profile_res[0].selected_users.length; nCount++) {
            data_ProfDet = data.profile_res[0].selected_users[nCount].first_name + " " + data.profile_res[0].selected_users[nCount].last_name + ", " + data.profile_res[0].selected_users[nCount].username;
            data_ProfSelectedUsers += "<option value=" + data.profile_res[0].selected_users[nCount].user_id + ">" + data_ProfDet + "</option>";
        }
        $("#lstProfileAvailableUsr").append(data_ProfAvailUsers);
        $("#lstProfileSelectedUsr").append(data_ProfSelectedUsers);
        $("#lstProfileAvailableUsr option").length == 0 ? $("#btnProfileAddUsers").attr("disabled", "disabled") : $("#btnProfileAddUsers").removeAttr("disabled");
        $("#lstProfileSelectedUsr option").length == 0 ? $("#btnProfileRemoveUsers").attr("disabled", "disabled") : $("#btnProfileRemoveUsers").removeAttr("disabled");
    }
    else
        logout(1)
}
$("#btnclearprofile").click(function() {
    $("#Profilenameerror").removeClass('alert alert-error')
    $("#Profilenameerror").text('').show();
    $("#frmCreateform").data('validator').resetForm();
    $(".error").removeClass("error");
});
$("#btncancelprofile").click(function() {
    $("#Profilenameerror").removeClass('alert alert-error')
    $("#Profilenameerror").text('').show();
    $("#frmCreateform").data('validator').resetForm();
    $(".error").removeClass("error");
});
$('input[id^="txtnewProfileName"]').bind("keypress keyup", function() {
    $("#Profilenameerror").removeClass('alert alert-error')
    $("#Profilenameerror").text('').show();
})
$("#frmCreateform").validate({
    rules: {
        txtnewProfileName: {
            required: true
        }
    },
    messages: {
        txtnewProfileName: {
            required: "Please enter the profile name"
        }
    },
    submitHandler: function(form) {
        form.submit();
    },
    errorClass: "help-inline",
    errorElement: "span",
    highlight: function(element, errorClass, validClass) {
        $(element).parents('.control-group').addClass('error')

    },
    unhighlight: function(element, errorClass, validClass) {
        $(element).parents('.control-group').removeClass('error')

    },
    errorPlacement: function(error, element) {
        error.insertAfter(element).css("margin-bottom", "0px");

    }
});
$("#btnNewProfileUserSubmit", "#divNewProfileUser").click(function() {
    clr_ProfileJunkData();
    $("#Profilenameerror").removeClass('alert alert-error')
    $("#Profilenameerror").text('').show();
    $("#txtnewProfileName").text('').show();

    var newProfile = $("#txtnewProfileName", "#divNewProfileUser").val();
    if ($("#frmCreateform").valid()) {
        if (newProfile.length > 0)
        {
            $("#txtProfileName").val(newProfile);
            var url = BACKENDURL + "customeradmin/create_profile_contract";
            var data = {
                session_id: localStorage["SESSIONID"],
                contract_id: localStorage["contractid"],
                profile_name: newProfile
            };
            MakeAjaxCall(url, data, createNewProfile);
        }
        else
        {
            $("#errorProfileName").removeClass("hide");
        }

    }
});
function createNewProfile(data)
{
    if (data.error)
    {
        if (data.error_msg == unAthMsg)
            logout(1);
        else {
            $("#Profilenameerror").addClass("alert alert-error")
            $("#Profilenameerror").text(data.error_msg)
            return false;
        }
    }
    if ($("#ProfileExist").hasClass("hide"))
    {
        $("#NewProfile").addClass("hide");
        $("#ProfileExist").removeClass("hide");
    }
    if (localStorage["profile_length"] == 0)
    {
        SrvUserProfile();
        $("#xNewUser", "#divNewProfileUser").click();
    }
    else
    {
        var data_profile = "<option value=" + data.create_profile_res + ">" + $("#txtnewProfileName", "#divNewProfileUser").val() + "</option>";
        $("#ddlUserProfile", "#ProfileExist").prepend(data_profile);
        $("#ddlUserProfile", "#ProfileExist").val(data.create_profile_res);
        clearProfile();
        ddlChangeProfile(data.create_profile_res);
        $("#xNewUser", "#divNewProfileUser").click();
    }
    $("#txtnewProfileName").val("");
    $("#divNewProfileUser").modal('hide');
    $("#lblProfile").show();
    $("#spnUserprofile").show();
}

function clearProfile()
{
    $("#errorProfileName").addClass("hide");
    $("#txtnewProfileName").val("");
}

function MasterPageData(data)
{
    var data_skin = "", data_modules = "", data_AccordModules = "", L1Navcode = "", L1NavName = "", L1NavId = "", inStr = " in ", data_innervalue = "";
    if (data.profile_res[0].skin.length > 0)
    {
        $("#ddlSkinProfiles", "#ProfileExist").empty();
        var selectedStr = " Selected ";
        for (var nCount = 0; nCount < data.profile_res[0].skin.length; nCount++) {
            data_skin += "<option value=" + data.profile_res[0].skin[nCount].skin_id + " " + selectedStr + " >" + data.profile_res[0].skin[nCount].skin_name + "</option>";
            selectedStr = "";
        }
        $("#ddlSkinProfiles", "#ProfileExist").append(data_skin);
    }
    if (data.profile_res[0].m_modules.length > 0)
    {
        $("#ddlMasterModule", "#ProfileExist").empty();
        var selectedStr = " Selected ";
        var L1Opts = 0, L2Opts = 0;
        for (var nCount = 0; nCount < data.profile_res[0].m_modules.length; nCount++) {
            L1NavName = data.profile_res[0].m_modules[nCount].m_module_name;
            L1NavId = data.profile_res[0].m_modules[nCount].m_module_id;
            data_modules += "<option value=" + L1NavId + " " + selectedStr + " >" + L1NavName + "</option>";
            if (data.profile_res[0].m_modules[nCount].hierarchy > 0) // Level1 Hierarchy is avalable and it has level 2
            {

                var L1Obj = data.profile_res[0].sub_modules[L1Opts];
                for (var L1key in L1Obj) { //Level1 check
                    if (L1key === L1NavName)
                    {
                        var data_L1length = L1Obj[L1key].length;
                        for (var jCount = 0; jCount < data_L1length; jCount++) {// Level 2 check
                            var tblSubmodules = '<table class="no-more-tables table tSubmod" id="data-submodules" style="border-top:0px !important;margin-left:-30px;"><tbody><tr>';
                            var L2NavName = L1Obj[L1key][jCount].s_module_name;
                            var L2NavID = L1Obj[L1key][jCount].s_module_id;
                            if (L1Obj[L1key][jCount].hierarchy > 0)
                            {
                                var L2Obj = data.profile_res[0].sub_sub_modules[L2Opts];
                                for (var L2key in L2Obj) // Level 3 check
                                {
                                    if (L2key === L2NavName)
                                    {
                                        var refArray = chkL3Modules(L2Obj[L2key]);
                                        for (var kCount = 0; kCount < refArray.length; kCount++) {
                                            var tbl_modules = "";
                                            if (kCount == 0)
                                            {
                                                for (var mCount = 0; mCount < refArray[kCount].length; mCount++)
                                                {
                                                    var mod_s = refArray[kCount][mCount].L2Id;
                                                    tbl_modules += '<ul><label class="checkbox" ><input type="checkbox" value=' + mod_s + ' id=chkCL_' + kCount + "_" + L2NavID + "_SSM" + mod_s + ' name=chkCL_' + kCount + "_" + L2NavID + "_SSM" + mod_s + '>' + refArray[kCount][mCount].L2Data + '</label></ul>';
                                                }
                                                tblSubmodules += '<td id=tbl_Mod_' + L2NavID + ' style="border-top:0px !important">' + tbl_modules + '</td>';
                                            }
                                            else
                                            {
                                                for (var mCount = 0; mCount < refArray[kCount].L2Data.length; mCount++)
                                                {
                                                    //var SubMod  = refArray[kCount].L2Data[mCount].s_module_id;
                                                    var SSubmod = refArray[kCount].L2Data[mCount].ss_module_id;
                                                    var SSName = refArray[kCount].L2Data[mCount].ss_module_name;
                                                    tbl_modules += '<ul><label class="checkbox" ><input type="checkbox" value=' + SSubmod + ' onchange="SelSubSubmenus(this);" id=chkCL_' + kCount + "_" + L2NavID + "_SSM" + SSubmod + ' name=chkCL_' + kCount + "_" + L2NavID + "_SSM" + SSubmod + '>' + SSName + '</label></ul>';
                                                }
                                                tblSubmodules += '<td id=tbl_Mod_' + L2NavID + ' style="border-top:0px !important"><li style="list-style:none;"><label class="checkbox" style="font-weight: bold"><input type="checkbox" value=' + refArray[kCount].L2HeaderMod + ' onchange="SelSubmenus(this);" id=chkCL_' + kCount + "_" + L2NavID + ' name=chkCL_' + kCount + "_" + L2NavID + "_SSM" + refArray[kCount].L2HeaderMod + '>' + refArray[kCount].L2Header + '</label></li>' + tbl_modules + '</td>';
                                            }
                                        }
                                        tblSubmodules += "</tr></tbody></table>";
                                        data_innervalue = "<div class='form-horizontal span12'><label class='span3'>" + L2NavName + " Module Status" + "</label><div class='span8'><label class='radio span2'><input type='radio' name='opt_" + L2NavID + "' onchange='optLevelPermission(this);' value='" + L2NavID + "' id='opt_" + L2NavID + "_Active'>Active</label><label class='radio span2'><input type='radio' onchange='optLevelPermission(this);' name='opt_" + L2NavID + "' value='" + L2NavID + "' id='opt_" + L2NavID + "_Inactive' checked>Inactive</label></div>" + tblSubmodules + "</div>"
                                        //US 312 item 21 adding respective icons before accordian headings
                                        //data_AccordModules += "<div id=Accord" + L1NavId + " class='accordion-group show'><div class='accordion-heading'><a class='accordion-toggle' data-toggle='collapse' data-parent='#divAccordProfile' style='text-decoration: none !important;padding-left: 7px;'><i class='icon-home icon-white'></i>" + L1NavName + ", " + L2NavName + "</a></div><div id='divMC-" + L1Navcode + "' class='accordion-body collapse" + inStr + "'> <div class='accordion-inner'><div class='accordion' style='margin-bottom:0px;'>" + data_innervalue + "</div></div></div></div>";
                                        switch (L1NavId) {
                                            case '1':
                                                data_AccordModules += "<div id=Accord" + L1NavId + " class='accordion-group show'><div class='accordion-heading'><a class='accordion-toggle' data-toggle='collapse' data-parent='#divAccordProfile' style='text-decoration: none !important;padding-left: 7px;'><i class='icon-home icon-white'></i>" + " " + L1NavName + ", " + L2NavName + "</a></div><div id='divMC-" + L1Navcode + "' class='accordion-body collapse" + inStr + "'> <div class='accordion-inner'><div class='accordion' style='margin-bottom:0px;'>" + data_innervalue + "</div></div></div></div>";
                                                break;
                                            case '2':
                                                data_AccordModules += "<div id=Accord" + L1NavId + " class='accordion-group show'><div class='accordion-heading'><a class='accordion-toggle' data-toggle='collapse' data-parent='#divAccordProfile' style='text-decoration: none !important;padding-left: 7px;'><i class='icon-user icon-white'></i>" + " " + L1NavName + ", " + L2NavName + "</a></div><div id='divMC-" + L1Navcode + "' class='accordion-body collapse" + inStr + "'> <div class='accordion-inner'><div class='accordion' style='margin-bottom:0px;'>" + data_innervalue + "</div></div></div></div>";
                                                break;
                                            case '3':
                                                data_AccordModules += "<div id=Accord" + L1NavId + " class='accordion-group show'><div class='accordion-heading'><a class='accordion-toggle' data-toggle='collapse' data-parent='#divAccordProfile' style='text-decoration: none !important;padding-left: 7px;'><i class='icon-wrench icon-white'></i>" + " " + L1NavName + ", " + L2NavName + "</a></div><div id='divMC-" + L1Navcode + "' class='accordion-body collapse" + inStr + "'> <div class='accordion-inner'><div class='accordion' style='margin-bottom:0px;'>" + data_innervalue + "</div></div></div></div>";
                                                break;
                                            case '4':
                                                data_AccordModules += "<div id=Accord" + L1NavId + " class='accordion-group show'><div class='accordion-heading'><a class='accordion-toggle' data-toggle='collapse' data-parent='#divAccordProfile' style='text-decoration: none !important;padding-left: 7px;'><i class='icon-briefcase icon-white'></i>" + " " + L1NavName + ", " + L2NavName + "</a></div><div id='divMC-" + L1Navcode + "' class='accordion-body collapse" + inStr + "'> <div class='accordion-inner'><div class='accordion' style='margin-bottom:0px;'>" + data_innervalue + "</div></div></div></div>";
                                                break;
                                            case '5':
                                                data_AccordModules += "<div id=Accord" + L1NavId + " class='accordion-group show'><div class='accordion-heading'><a class='accordion-toggle' data-toggle='collapse' data-parent='#divAccordProfile' style='text-decoration: none !important;padding-left: 7px;'><i class='icon-signal icon-white'></i>" + " " + L1NavName + ", " + L2NavName + "</a></div><div id='divMC-" + L1Navcode + "' class='accordion-body collapse" + inStr + "'> <div class='accordion-inner'><div class='accordion' style='margin-bottom:0px;'>" + data_innervalue + "</div></div></div></div>";
                                                break;
                                        }
                                        L2Opts += 1;
                                    }
                                    else
                                    {
                                        L2Opts += 1;
                                    }
                                }
                            }
                            else
                            {
                                data_innervalue = "<div class='form-horizontal span12'><label class='span3'>" + L2NavName + " Module Status" + "</label><div class='span8'><label class='radio span2'><input type='radio' name='opt_" + L2NavID + "' value='" + L2NavID + "' id='opt_" + L2NavID + "_Active'>Active</label><label class='radio span2'><input type='radio' name='opt_" + L2NavID + "' value='" + L2NavID + "' id='opt_" + L2NavID + "_Inactive' checked>Inactive</label></div></div>"
                                //US 312 item 21 adding respective icons before accordian headings
                                //data_AccordModules += "<div id=Accord" + L1NavId + " class='accordion-group show'><div class='accordion-heading'><a class='accordion-toggle' data-toggle='collapse' data-parent='#divAccordProfile' style='text-decoration: none !important;padding-left: 7px;'><i class='icon-wrench icon-white'></i>" + L1NavName + ", " + L2NavName + "</a></div><div id='divMC-" + L1Navcode + "' class='accordion-body collapse" + inStr + "'> <div class='accordion-inner'><div class='accordion' style='margin-bottom:0px;'>" + data_innervalue + "</div></div></div></div>";
                                switch (L1NavId) {
                                    case '1':
                                        data_AccordModules += "<div id=Accord" + L1NavId + " class='accordion-group show'><div class='accordion-heading'><a class='accordion-toggle' data-toggle='collapse' data-parent='#divAccordProfile' style='text-decoration: none !important;padding-left: 7px;'><i class='icon-home icon-white'></i>" + " " + L1NavName + ", " + L2NavName + "</a></div><div id='divMC-" + L1Navcode + "' class='accordion-body collapse" + inStr + "'> <div class='accordion-inner'><div class='accordion' style='margin-bottom:0px;'>" + data_innervalue + "</div></div></div></div>";
                                        break;
                                    case '2':
                                        data_AccordModules += "<div id=Accord" + L1NavId + " class='accordion-group show'><div class='accordion-heading'><a class='accordion-toggle' data-toggle='collapse' data-parent='#divAccordProfile' style='text-decoration: none !important;padding-left: 7px;'><i class='icon-user icon-white'></i>" + " " + L1NavName + ", " + L2NavName + "</a></div><div id='divMC-" + L1Navcode + "' class='accordion-body collapse" + inStr + "'> <div class='accordion-inner'><div class='accordion' style='margin-bottom:0px;'>" + data_innervalue + "</div></div></div></div>";
                                        break;
                                    case '3':
                                        data_AccordModules += "<div id=Accord" + L1NavId + " class='accordion-group show'><div class='accordion-heading'><a class='accordion-toggle' data-toggle='collapse' data-parent='#divAccordProfile' style='text-decoration: none !important;padding-left: 7px;'><i class='icon-wrench icon-white'></i>" + " " + L1NavName + ", " + L2NavName + "</a></div><div id='divMC-" + L1Navcode + "' class='accordion-body collapse" + inStr + "'> <div class='accordion-inner'><div class='accordion' style='margin-bottom:0px;'>" + data_innervalue + "</div></div></div></div>";
                                        break;
                                    case '4':
                                        data_AccordModules += "<div id=Accord" + L1NavId + " class='accordion-group show'><div class='accordion-heading'><a class='accordion-toggle' data-toggle='collapse' data-parent='#divAccordProfile' style='text-decoration: none !important;padding-left: 7px;'><i class='icon-briefcase icon-white'></i>" + " " + L1NavName + ", " + L2NavName + "</a></div><div id='divMC-" + L1Navcode + "' class='accordion-body collapse" + inStr + "'> <div class='accordion-inner'><div class='accordion' style='margin-bottom:0px;'>" + data_innervalue + "</div></div></div></div>";
                                        break;
                                    case '5':
                                        data_AccordModules += "<div id=Accord" + L1NavId + " class='accordion-group show'><div class='accordion-heading'><a class='accordion-toggle' data-toggle='collapse' data-parent='#divAccordProfile' style='text-decoration: none !important;padding-left: 7px;'><i class='icon-signal icon-white'></i>" + " " + L1NavName + ", " + L2NavName + "</a></div><div id='divMC-" + L1Navcode + "' class='accordion-body collapse" + inStr + "'> <div class='accordion-inner'><div class='accordion' style='margin-bottom:0px;'>" + data_innervalue + "</div></div></div></div>";
                                        break;
                                }

                            }
                        }
                        L1Opts += 1;
                    }
                    else
                    {
                        L1Opts += 1;
                    }
                }
            }
            //data_AccordModules += "<div class='accordion-group'><div class='accordion-heading'><a class='accordion-toggle' data-toggle='collapse' data-parent='#divAccordProfile' style='text-decoration: none !important;'>" + L1NavName + "</a></div><div id='divMC-" + L1Navcode + "' class='accordion-body collapse" + inStr + "'> <div class='accordion-inner'><div class='accordion' style='margin-bottom:0px;'>" + L1NavName + "</div></div></div></div>";
            selectedStr = "";
        }
        $("#ddlMasterModule", "#ProfileExist").append(data_modules);
        $("#divAccordProfile", "#ProfileExist").html(data_AccordModules);
    }
    formDirtyCheck();
    $('#chkCL_0_11_SSM33').on('change', function() {
        if ($(this).is(":checked")) {
            $('#chkCL_0_11_SSM34').attr('checked', true);
        }
    });
    $('#chkCL_0_11_SSM37').on('change', function() {
        if ($(this).is(":checked")) {
            $('#chkCL_0_11_SSM34').attr('checked', true);
        }
    });

}

function SelSubmenus(data)
{
    var tmpData = $(data).attr('id');
    $("#" + tmpData).is(":checked") ? $("[id^=" + tmpData + "]").prop('checked', true) : $("[id^=" + tmpData + "]").prop('checked', false);

}

function optLevelPermission(chkbox)
{

    var strID = "_" + $(chkbox).val() + "_SSM";
    if ($(chkbox).attr('id').indexOf("Inactive") >= 0) {
        $("input[type='checkbox'][name*=" + strID + "]").attr('disabled', true);
        $("input[type='checkbox'][name*=" + strID + "]").prop('checked', false);
    } else {
        $("input[type='checkbox'][name*=" + strID + "]").attr('disabled', false);
    }
}

function SelSubSubmenus(data)
{
    // if all the sub menus is un checked then need to remove the header check box checked
    var tmpData = $(data).attr('id');
    var output = getNth(tmpData, '_', 3);
    if (!$("#" + tmpData).is(":checked"))
    {
        if ($("input[type='checkbox'][id*=" + output + "_" + "]:checked").length == 0)
            $("#" + output).is(":checked") ? $("#" + output).prop('checked', false) : "";
    }
//$("#" + output).prop('checked', true); to check the header item of the list 
}

function getNth(s, c, n) {
    var idx;
    var i = 0;
    var newS = '';
    do {
        idx = s.indexOf(c);
        newS += s.substring(0, idx);
        s = s.substring(idx + 1);
    } while (++i < n && (newS += c))
    return newS;
}

function ddlChangeProfile(profid)
{
    clr_ProfileJunkData();
    if (profid == 0)
        profid = $("#ddlUserProfile").val();
    $("[id*=Accord]").removeClass("hide");
    $('input[type=checkbox]:checked').removeAttr('checked');
    $('input[type=checkbox]').removeAttr('disabled', false);
    $('input[type=radio]:checked').removeAttr('checked');
    $("[id*=Inactive]").attr('checked', true);
    var url = BACKENDURL + "customeradmin/get_profile_details_contract";
    var data = {
        session_id: localStorage["SESSIONID"],
        contract_id: localStorage["contractid"],
        profile_id: profid
    };
    MakeAjaxCall(url, data, UserProfileData);
    $("#btnProfileSave").text('Saved').attr("disabled", true);
// $("#btnProfileDelete").text('Deleted').attr("disabled", true);
}

function chkL3Modules(L2dataObj)
{
    var L1Array = [], L2Array = [], L3Opts = 0;
    for (var SCount = 0; SCount < L2dataObj.length; SCount++) {
        for (var L3key in L2dataObj[SCount])
        {
            if (L2dataObj[SCount][L3key][1].length > 0)
            {
                L2Array.push({
                    "L2Data": L2dataObj[SCount][L3key][1],
                    "L2Header": L3key,
                    "L2HeaderMod": L2dataObj[SCount][L3key][0].ss_module_id
                });
            }
            else
            {

                L1Array.push({
                    "L2Data": L3key,
                    "L2Id": L2dataObj[SCount][L3key][0].ss_module_id
                });
            }
        }

    }
    L2Array.reverse();
    L2Array.push(L1Array);
    //L3Array.push(L2Array);
    L3Opts += 1;
    return L2Array.reverse();
}

function chkLevelPermission()
{
    if ($("#chkHideMainNav", "#ProfileExist").is(":checked")) {
        $("#ddlMasterModule").prop("disabled", false);
        ddlChangeModules();
    } else {
        $("#ddlMasterModule").prop("disabled", true);
        $("div[id^=Accord]").removeClass("hide");
    }
}

function ddlChangeModules()
{


    var mstrModule = $("#ddlMasterModule").val();
    for (var ddlCount = 1; ddlCount <= 10; ddlCount++)
    {
        if (ddlCount == mstrModule)
        {
            $("div[id^=Accord" + ddlCount + "]").removeClass("hide");
            $("div[id^=Accord" + ddlCount + "]").addClass("show");
        }
        else
        {
            $("div[id^=Accord" + ddlCount + "]").removeClass("show");
            $("div[id^=Accord" + ddlCount + "]").addClass("hide");
        }

    }

}


function ProfileAddUsers(e)
{
    //Added in support of US 312 item 2 moving users div inside form
    e.preventDefault();
    $.support.cors = true;
    $("#lblSavealert").hasClass("alert alert-success")
    $("#lblSavealert").removeClass('alert alert-success').text('');
    $("#lblSavealert").hasClass("alert alert-danger")
    $("#lblSavealert").removeClass('alert alert-danger').text('');
    var selectedOpts = $('#lstProfileAvailableUsr option:selected');
    if (selectedOpts.length == 0) {
        $("#divProfileErrMsg").html("Please select from available users").show()
    }
    else
    {
        $("#divProfileErrMsg").hide();
        for (var i = 0; i < selectedOpts.length; i++) {
            if (selectedOpts[i].selected == true) {
                usersProfileArray.push({
                    "user_id": $(selectedOpts[i]).val(),
                    "profile_id": localStorage["profile_id"]
                });
            }
        }
        $("#lstProfileSelectedUsr").prepend($(selectedOpts).clone());
        $(selectedOpts).remove();
        $("#lstProfileAvailableUsr option").length == 0 ? $("#btnProfileAddUsers").attr("disabled", "disabled") : $("#btnProfileAddUsers").removeAttr("disabled");
        $("#lstProfileSelectedUsr option").length == 0 ? $("#btnProfileRemoveUsers").attr("disabled", "disabled") : $("#btnProfileRemoveUsers").removeAttr("disabled");
        $("#btnProfileSave").text('Save').attr("disabled", false);
        //$("#btnProfileDelete").text('Delete').attr("disabled", false);
    }
}

function ProfileRemoveUsers(e)
{
    ////Added in support of US 312 item 2 moving users div inside form
    e.preventDefault();
    $.support.cors = true;
    $("#lblSavealert").hasClass("alert alert-success")
    $("#lblSavealert").removeClass('alert alert-success').text('');
    $("#lblSavealert").hasClass("alert alert-danger")
    $("#lblSavealert").removeClass('alert alert-danger').text('');
    var selectedOpts = $('#lstProfileSelectedUsr option:selected');
    if (selectedOpts.length == 0) {
        $("#divProfileErrMsg").html("Please select from selected users").show()
    }
    else
    {
        $("#divProfileErrMsg").hide();
        for (var i = 0; i < selectedOpts.length; i++) {
            if (selectedOpts[i].selected == true) {
                usersProfileArray.push({
                    "user_id": $(selectedOpts[i]).val(),
                    "profile_id": "0"
                });
            }
        }
        $("#lstProfileAvailableUsr").prepend($(selectedOpts).clone());
        $(selectedOpts).remove();
        $("#lstProfileAvailableUsr option").length == 0 ? $("#btnProfileAddUsers").attr("disabled", "disabled") : $("#btnProfileAddUsers").removeAttr("disabled");
        $("#lstProfileSelectedUsr option").length == 0 ? $("#btnProfileRemoveUsers").attr("disabled", "disabled") : $("#btnProfileRemoveUsers").removeAttr("disabled");
        $("#btnProfileSave").text('Save').attr("disabled", false);
        //$("#btnProfileDelete").text('Delete').attr("disabled", false);
    }
}

function modalalert()
{

    $("#divAlertDelete").modal('show');

}

function SrvProfileDelete()
{

    $("#divAlertDelete").modal('hide');
    $("#DivDeleteProfile").modal('show');
    // $("#btnProfileDelete").attr("disabled", true);
    var url = BACKENDURL + "customeradmin/delete_profile_details";
    var data = {
        session_id: localStorage["SESSIONID"],
        contract_id: localStorage["contractid"],
        profile_id: localStorage["profile_id"]
    };
    MakeAjaxCall(url, data, ProfileDelete);

}

function ProfileDelete(data)
{
    if (data.error == 0) {
        // $("#btnProfileDelete").text('Deleted');
        SrvUserProfile();
        //alert("Delete Profile");
    } else
        logout(1);
}

function SrvProfileSave()
{
    var max_modules = 8;
    var indexlen = 0;
    var tmp_val = -1;
    profile_ss_module_data = [];
    profile_s_module_data = [];
    ss_module_items = [];
    ss_modsel_items = [];
    ss_each_item = [[], [], [], [], [], [], [], []];
    tmp_array = [];
    var Module_Id = $("#chkHideMainNav").is(":checked") ? $("#ddlMasterModule").val() : 0;
    if ($("#txtProfileName").val().length == 0)
    {
        $("#spnProfileName").css('display', 'inline');
        $("#txtProfileName").css('border-color', '#b94a48');
        $("#lblProfilename").css('color', '#b94a48');
        $("#txtProfileName").focus();
        return false;
    }
    if (usersProfileArray.length == 0)
    {
        $('select#lstProfileSelectedUsr option').each(function(idx, val)
        {
            usersProfileArray.push({
                "user_id": $(val).val(),
                "profile_id": localStorage["profile_id"]
            });
        });
    }
    if (Module_Id != 0)
    {
        var ActiveAccord = $('#divAccordProfile').children('.show').attr('id');
        $('#' + ActiveAccord + ' input[type="checkbox"]:checked').each(function() {
            ss_module_items.push($(this).attr('id'));
            ss_modsel_items.push($(this).attr('id'));
            profile_ss_module_data.push($(this).val());
        });

        $('#' + ActiveAccord + ' input[type="radio"]:checked').each(function() {
            var activeSubmod = $(this).attr('id');
            if (activeSubmod.indexOf("Active") >= 0)
                profile_s_module_data.push($(this).val());
        });
    }
    else
    {
        $('#divAccordProfile input[type="checkbox"]:checked').each(function() {
            ss_module_items.push($(this).attr('id'));
            ss_modsel_items.push($(this).attr('id'));
            profile_ss_module_data.push($(this).val());
        });

        $('#divAccordProfile input[type="radio"]:checked').each(function() {
            var activeSubmod = $(this).attr('id');
            if (activeSubmod.indexOf("Active") >= 0)
                profile_s_module_data.push($(this).val());

        });
    }

    // Maximum selection of submodules 
    if (ss_module_items.length > 0)
    {
        for (var mod_count = 0; mod_count < ss_module_items.length; mod_count++)
        {
            if (ss_module_items[mod_count].indexOf("SSM") < 0)
            {
                for (var pop_count = 0; pop_count < ss_module_items.length; pop_count++)
                {
                    if ((ss_module_items[pop_count].indexOf(ss_module_items[mod_count]) >= 0) && (pop_count != mod_count))
                    {
                        ss_modsel_items.splice(ss_modsel_items.indexOf(ss_module_items[pop_count]), 1);
                    }
                }

            }

        }
    }
    // Seperating based on the individual modules
    for (var tot_module = 0; tot_module < ss_modsel_items.length; tot_module++)
    {
        tmp_array = "";
        tmp_array = ss_modsel_items[tot_module].split("_");
        if (tot_module == 0 || tmp_val == tmp_array[2])
        {
            ss_each_item[indexlen].push(ss_modsel_items[tot_module]);
            tmp_val = tmp_array[2];
        }
        else
        {
            indexlen = parseInt(indexlen) + 1;
            ss_each_item[indexlen].push(ss_modsel_items[tot_module]);
            tmp_val = tmp_array[2];
        }
    }
    // check max tabs of sub menus
    function Check8Mod()
    {
        for (var mod_length = 0; mod_length < ss_each_item.length; mod_length++)
        {
            if (ss_each_item[mod_length].length > max_modules)
            {
                return false;
            }
        }
        return true;
    }
    if (Check8Mod())
    {
        $("#btnProfileSave").attr("disabled", true).text('Saving');
        $("#btnProfileSave").attr("disabled", true);
        var url = BACKENDURL + "customeradmin/save_profile_details";
        var data = {
            session_id: localStorage["SESSIONID"],
            contract_id: localStorage["contractid"],
            profile_id: localStorage["profile_id"],
            profile_name: $("#txtProfileName").val(),
            skin_id: $("#ddlSkinProfiles").val(),
            self_registration: $("#chkSelfReg").is(":checked") ? 1 : 0,
            hide_main_nav: $("#chkHideMainNav").is(":checked") ? 1 : 0,
            m_module_id: Module_Id,
            profile_ss_module_data: profile_ss_module_data,
            profile_s_module_data: profile_s_module_data,
            user_data: usersProfileArray
        };
        MakeAjaxCall(url, data, ProfileSave);
    }
    else
    {
        $("#lblSavealert").addClass('alert alert-danger').text('A maximum of 8 sub-modules can be dispayed on the screen')
    }
}

function ProfileSave(data)
{
    if (data.error)
    {
        if (data.error_msg == unAthMsg)
            logout(1)
        else {
            $("#lblSavealert").addClass('alert alert-danger').text(data.error_msg);
            $("#btnProfileSave").text('Save').attr("disabled", false);
        }
    }
    else
    {
        var data_profile = "";
        $("#ddlUserProfile", "#ProfileExist").empty();
        for (var nCount = 0; nCount < data.profile_res.length; nCount++) {
            data_profile += "<option value=" + data.profile_res[nCount].profile_id + ">" + data.profile_res[nCount].profile_name + "</option>";
        }
        $("#ddlUserProfile", "#ProfileExist").append(data_profile);
        $("#ddlUserProfile", "#ProfileExist").val(localStorage["profile_id"]);
        $("#btnProfileSave").text('Saved');
        $("#divProfileErrMsg").hide();
        $("#lblSavealert").hasClass('alert alert-danger')
        $("#lblSavealert").removeClass('alert alert-danger').addClass('alert alert-success').text('Your profile has been saved');
    }
}

function formDirtyCheck() {
    var Settings = {
        denoteDirtyForm: true,
        dirtyFormClass: false,
        // denoteDirtyOptions:true,
        dirtyOptionClass: "dirtyChoice",
        trimText: true,
        formChangeCallback: function(result, dirtyFieldsArray) {
            if (result)
            {
                $("#lblSavealert").removeClass('alert alert-success').text('');
                $("#btnProfileSave").text('Save').attr("disabled", false);
            }
            else
            {
                $("#lblSavealert").removeClass('alert alert-success').text('');
                $("#btnProfileSave").text('Save').attr("disabled", false);
            }
        }
    };

    $("#frmUserprofile").dirtyFields(Settings);
}

// Skin Theming

// Check it contains existing skin or not 
function SrvSkinCheck()
{
    var url = BACKENDURL + "customeradmin/get_skins";
    var data = {
        session_id: localStorage["SESSIONID"],
        contract_id: localStorage["contractid"]
    };
    MakeAjaxCall(url, data, UserSkins);
}

function UserSkins(data)
{
    if (data.error == 0) {
        if (data.get_skins_res.length > 0)
        {
            var data_skin = "", selectedStr = " Selected ";
            $("#ddlUserSkin", "#SkinExist").empty();
            for (var nCount = 0; nCount < data.get_skins_res.length; nCount++) {
                data_skin += "<option value=" + data.get_skins_res[nCount].skin_id + " " + selectedStr + " >" + data.get_skins_res[nCount].skin_name + "</option>";
                selectedStr = "";
            }
            $("#ddlUserSkin", "#SkinExist").append(data_skin);
            SrvloadSkindata($("#ddlUserSkin", "#SkinExist").val());
            $("#SkinExist").removeClass("hide");
        }
        else
        {
            $("#ddlUserSkin", "#SkinExist").empty();
            $("#NewSkin").removeClass("hide");
            $("#SkinExist").addClass("hide");
        }
    } else
        logout(1);
}

function SrvloadSkindata(skinid)
{
    var url = BACKENDURL + "customeradmin/get_skin_details";
    var data = {
        session_id: localStorage["SESSIONID"],
        contract_id: localStorage["contractid"],
        skin_id: skinid
    };
    localStorage["skin_id"] = skinid;
    MakeAjaxCall(url, data, loadSkinData);
}

function SkinChange()
{
    clear_junkdata();
    clrInlineErrors();
    SrvloadSkindata($("#ddlUserSkin", "#SkinExist").val());
    $("#btnSkinSave", "#SkinExist").text('Save').attr("disabled", true);
    $("#lblSaveSkin").removeClass('alert alert-success').text('');
}
function loadSkinData(data)
{
    if (data.error == 0) {
        localStorage["Rst_logo"] = 0;
        localStorage["Rst_smartphone"] = 0;
        localStorage["Rst_header_div"] = 0;
        localStorage["Rst_level12_bg"] = 0;
        localStorage["Rst_level2_bg"] = 0;
        localStorage["Rst_no_nav"] = 0;
        localStorage["Rst_widget_header"] = 0;
        localStorage["Rst_select_bg"] = 0;
        if (data.get_skin_details_res.length > 0)
        {
            $("#txtSkinName", "#divSkinTheme").val(data.get_skin_details_res[0].sn);
            $("#txtfooterCPR", "#divSkinTheme").val(data.get_skin_details_res[0].fct);
            //color codes
            $("#appendedPrependedDropdownButton", "#txtHdrLnkColor").val(data.get_skin_details_res[0].hlc);
            $("#appendedPrependedDropdownButton", "#txtHdrLnkColor").blur();
            $("#appendedPrependedDropdownButton", "#txtHdrLnkHoverColor ").val(data.get_skin_details_res[0].hlhc);
            $("#appendedPrependedDropdownButton", "#txtHdrLnkHoverColor ").blur();
            $("#appendedPrependedDropdownButton", "#txtHeadingsColor ").val(data.get_skin_details_res[0].hc);
            $("#appendedPrependedDropdownButton", "#txtHeadingsColor ").blur();
            $("#appendedPrependedDropdownButton", "#txtPageLnkColor ").val(data.get_skin_details_res[0].plc);
            $("#appendedPrependedDropdownButton", "#txtPageLnkColor ").blur();
            $("#appendedPrependedDropdownButton", "#txtPgeLnkHoverColor ").val(data.get_skin_details_res[0].plhc);
            $("#appendedPrependedDropdownButton", "#txtPgeLnkHoverColor ").blur();
            $("#appendedPrependedDropdownButton", "#txtPgeBgdColor ").val(data.get_skin_details_res[0].pb);
            $("#appendedPrependedDropdownButton", "#txtPgeBgdColor ").blur();
            // Logo updated images 
            $("#img_logo").prop("src", location.protocol + '//' + location.host + '/assets/skins/' + localStorage["skin_id"] + '/logo.png');
            $("#img_smartphone").prop("src", location.protocol + '//' + location.host + '/assets/skins/' + localStorage["skin_id"] + '/smartphone.png');
            $("#img_header_div").prop("src", location.protocol + '//' + location.host + '/assets/skins/' + localStorage["skin_id"] + '/header_div.png');
            $("#img_level12_bg").prop("src", location.protocol + '//' + location.host + '/assets/skins/' + localStorage["skin_id"] + '/level12_bg.png');
            $("#img_level2_bg").prop("src", location.protocol + '//' + location.host + '/assets/skins/' + localStorage["skin_id"] + '/level2_bg.png');
            $("#img_no_nav").prop("src", location.protocol + '//' + location.host + '/assets/skins/' + localStorage["skin_id"] + '/no_nav.png');
            $("#img_widget_header").prop("src", location.protocol + '//' + location.host + '/assets/skins/' + localStorage["skin_id"] + '/widget_header.png');
            $("#img_select_bg").prop("src", location.protocol + '//' + location.host + '/assets/skins/' + localStorage["skin_id"] + '/select_bg.png');
            // Left footer Description 
            $("#txtLftFtrTitle", "#divSkins").val(data.get_skin_details_res[0].lfbt);
            $("#txtLftFtr1", "#divSkins").val(data.get_skin_details_res[0].lfbd1);
            $("#txtLftFtr2", "#divSkins").val(data.get_skin_details_res[0].lfbd2);
            $("#txtLftFtr3", "#divSkins").val(data.get_skin_details_res[0].lfbd3);
            $("#txtLftFtr4", "#divSkins").val(data.get_skin_details_res[0].lfbd4);
            // Left footer Link 
            $("#txtLftFtrLnk1", "#divSkins").val(data.get_skin_details_res[0].lfbl1);
            $("#txtLftFtrLnk2", "#divSkins").val(data.get_skin_details_res[0].lfbl2);
            $("#txtLftFtrLnk3", "#divSkins").val(data.get_skin_details_res[0].lfbl3);
            $("#txtLftFtrLnk4", "#divSkins").val(data.get_skin_details_res[0].lfbl4);
            // Left footer Disable
            data.get_skin_details_res[0].lfbs1 == "0" ? DisableChk("chkLftFtrLnk1", true) : DisableChk("chkLftFtrLnk1", false);
            data.get_skin_details_res[0].lfbs2 == "0" ? DisableChk("chkLftFtrLnk2", true) : DisableChk("chkLftFtrLnk2", false);
            data.get_skin_details_res[0].lfbs3 == "0" ? DisableChk("chkLftFtrLnk3", true) : DisableChk("chkLftFtrLnk3", false);
            data.get_skin_details_res[0].lfbs4 == "0" ? DisableChk("chkLftFtrLnk4", true) : DisableChk("chkLftFtrLnk4", false);
            // Right footer Description
            $("#txtRightFtrTitle", "#divSkins").val(data.get_skin_details_res[0].rfbt);
            $("#txtRightFtr1", "#divSkins").val(data.get_skin_details_res[0].rfbd1);
            $("#txtRightFtr2", "#divSkins").val(data.get_skin_details_res[0].rfbd2);
            $("#txtRightFtr3", "#divSkins").val(data.get_skin_details_res[0].rfbd3);
            $("#txtRightFtr4", "#divSkins").val(data.get_skin_details_res[0].rfbd4);
            // Right footer Link
            $("#txtRightFtrLnk1", "#divSkins").val(data.get_skin_details_res[0].rfbl1);
            $("#txtRightFtrLnk2", "#divSkins").val(data.get_skin_details_res[0].rfbl2);
            $("#txtRightFtrLnk3", "#divSkins").val(data.get_skin_details_res[0].rfbl3);
            $("#txtRightFtrLnk4", "#divSkins").val(data.get_skin_details_res[0].rfbl4);
            // Right footer Disable
            data.get_skin_details_res[0].rfbs1 == "0" ? DisableChk("chkRightFtrLnk1", true) : DisableChk("chkRightFtrLnk1", false);
            data.get_skin_details_res[0].rfbs2 == "0" ? DisableChk("chkRightFtrLnk2", true) : DisableChk("chkRightFtrLnk2", false);
            data.get_skin_details_res[0].rfbs3 == "0" ? DisableChk("chkRightFtrLnk3", true) : DisableChk("chkRightFtrLnk3", false);
            data.get_skin_details_res[0].rfbs4 == "0" ? DisableChk("chkRightFtrLnk4", true) : DisableChk("chkRightFtrLnk4", false);
            if ($("#ddlUserSkin option", "#SkinExist").length == "1")
            {
                $("#lblSkin").css("display", "none");
                $("#spnUserSkin").css("display", "none");
            }
            else
            {
                $("#lblSkin").css("display", "inline");
                $("#spnUserSkin").css("display", "block");
            }


        }
    } else
        logout(1);
}

function downloadlogo(id) {
    var img_dld = id + "-" + localStorage["skin_id"];
    var url = BACKENDURL + "common/download_file";
    window.open(url + "/" + localStorage["SESSIONID"] + "/skin_img/" + img_dld);
}

function clear_junkdata()
{
    //$("#div_logo,#div_smartphone,#div_header_div,#div_level12_bg,#div_level2_bg,#div_no_nav,#div_widget_header,#div_select_bg", "#divSkinTheme").css('display', 'none');
    $("#txt_logo,#txt_smartphone,#txt_header_div,#txt_level12_bg,#txt_level2_bg,#txt_no_nav,#txt_widget_header,#txt_select_bg", "#divSkinTheme").val("Select logo...");
    $("#logo,#smartphone,#header_div,#level12_bg,#level2_bg,#no_nav,#widget_header,#select_bg", "#divSkinTheme").val("");
    //$('[id^="Rem_"]', "#SkinExist").attr("disabled", "disabled");
    //$('[id^="img_"]', "#SkinExist").prop("src", "#");
    $('[id^="Err_"]').hide();
    $('[id^="Errformat_"]').hide();

}

function DisableChk(id, status)
{
    $("#" + id, "#divSkins").prop('checked', status);
    var LinkID = id.replace("chk", "txt");
    var Link = LinkID.replace("Lnk", "");
    if (status)
    {
        $("#" + LinkID).attr("disabled", "disabled");
        $("#" + Link).attr("disabled", "disabled");
    }
    else
    {
        $("#" + LinkID).removeAttr("disabled");
        $("#" + Link).removeAttr("disabled");
    }
}

function clearSkin()
{
    $("#frmCreateSkin").data('validator').resetForm();
    $(".error").removeClass("error");
    $("#txtnewSkinName").val("");
    $("#Skinerror").hide();
}

function SrvSkinCreate()
{
    if ($("#frmCreateSkin").valid()) {
        clrInlineErrors();
        clear_junkdata();
        $("#lblSaveSkin").removeClass('alert alert-success').text('');
        var url = BACKENDURL + "customeradmin/create_skin";
        var data = {
            session_id: localStorage["SESSIONID"],
            contract_id: localStorage["contractid"],
            skin_name: $("#txtnewSkinName").val()
        };
        MakeAjaxCall(url, data, createNewSkin);
    }
}

function createNewSkin(data)
{
    if (data.error)
    {
        if (data.error_msg == unAthMsg)
            logout(1);
        else
            $("#Skinerror").text(data.error_msg).show();
    }
    else
    {
        $("#ddlUserSkin", "#SkinExist").prepend("<option value=" + data.skin_id + ">" + $("#txtnewSkinName").val() + "</option>");
        $("#ddlUserSkin", "#SkinExist").val(data.skin_id);
        $("#divNewSkin").modal('hide');
        $("#txtnewSkinName").val("");
        SrvloadSkindata(data.skin_id);
        $("#NewSkin").addClass("hide");
        $("#SkinExist").hasClass("hide")
        $("#SkinExist").removeClass("hide");
        $("#btnSkinSave").text('Save').attr("disabled", true);
    }
}

$("#frmCreateSkin").validate({
    rules: {
        txtnewSkinName: {
            required: true
        }
    },
    messages: {
        txtnewSkinName: {
            required: "Please enter the skin name"
        }
    },
    submitHandler: function(form) {
        form.submit();
    },
    errorClass: "help-inline",
    errorElement: "span",
    highlight: function(element, errorClass, validClass) {
        $(element).parents('.control-group').addClass('error');
    },
    unhighlight: function(element, errorClass, validClass) {
        $(element).parents('.control-group').removeClass('error');
    },
    errorPlacement: function(error, element) {
        error.insertAfter(element).css("margin-bottom", "0px");
    }
});

function setFileNameFromPath(imgFile, imgMinWidth, imgMaxWidth, imgMinHeight, imgMaxHeight) {
    var uploadtxtbox = imgFile.id;
    if (imgFile.value != "")
    {
        $("#txt_" + uploadtxtbox).val(imgFile.value);
        $("#div_" + uploadtxtbox).css('display', 'none');
        localStorage["FileUpload"] = uploadtxtbox;
        //localStorage["FileSize"] = imgSize;
        localStorage["FileMaxWidth"] = imgMaxWidth;
        localStorage["FileMaxHeight"] = imgMaxHeight;
        localStorage["FileMinWidth"] = imgMinWidth;
        localStorage["FileMinHeight"] = imgMinHeight;
        $("#btnSkinSave", "#SkinExist").text('Save').attr("disabled", false);
    }
}



function RemoveLogo(data)
{
    var tmpid = $(data).attr('id');
    var txtid = tmpid.replace("Rem_", "");
    $("#txt_" + txtid).val("Select logo....");
    $("#" + txtid).val("");
    var img_tmp = location.protocol + '//' + location.host + '/assets/skins/default/' + txtid + '.png';
    $("#img_" + txtid).prop("src", img_tmp); // prop("src", "#"); //
    $("#div_" + txtid).css('display', 'inline');
    localStorage["Rst_" + txtid] = "1";
    $("#btnSkinSave", "#SkinExist").text('Save').attr("disabled", false);
    //$("#" + tmpid).attr("disabled", "disabled");
}

function SelDisable(data)
{
    var tmpData = $(data).attr('id');
    var LinkID = tmpData.replace("chk", "txt");
    var Link = LinkID.replace("Lnk", "");
    var LabelID = Link.replace("txt", "lbl");
    var SpanID = Link.replace("txt", "spn");
    if ($("#" + tmpData).is(":checked"))
    {
        $("#" + LinkID).attr("disabled", "disabled");
        $("#" + Link).attr("disabled", "disabled");
        $("#" + Link, "#divSkins").css('border-color', '#cccccc');
        $("#" + LabelID).css('color', '#333333');
        $("#" + SpanID).css('display', 'none');
        $("#lblSaveSkin").hasClass("alert alert-danger")
        $("#lblSaveSkin").removeClass("alert alert-danger").text("");
    }
    else
    {
        $("#" + LinkID).removeAttr("disabled");
        $("#" + Link).removeAttr("disabled");
    }
    $("#btnSkinSave").text('Save').attr("disabled", false);


}

function btnResetColor(resetid)
{
    var resetbtnid = $(resetid).attr('id');
    var resettxtid = resetbtnid.replace("btn", "txt");
    $("#appendedPrependedDropdownButton", "#" + resettxtid).val(localStorage[resettxtid]);
    $("#appendedPrependedDropdownButton", "#" + resettxtid).blur();
    $("#btnSkinSave").text('Save').attr("disabled", false);
}

$("#frmSkinTheme").validate({
    rules: {
        txtSkinName: {
            required: true
        }
    },
    messages: {
        txtSkinName: {
            required: "Please enter the skin name"
        }
    },
    submitHandler: function(form) {
        form.submit();
    },
    errorClass: "help-inline",
    errorElement: "span",
    highlight: function(element, errorClass, validClass) {
        $(element).parents('.control-group').addClass('error');
    },
    unhighlight: function(element, errorClass, validClass) {
        $(element).parents('.control-group').removeClass('error');
    },
    errorPlacement: function(error, element) {
        error.insertAfter(element).css("margin-bottom", "0px");
    }
});

function ValidSkinSave()
{
    if ($("#frmSkinTheme").valid())
    {
        if ($("#lblSaveSkin").hasClass('alert alert-danger'))
            $("#lblSaveSkin").removeClass('alert alert-danger').text("");
        if ($("#lblSaveSkin").hasClass('alert alert-success'))
            $("#lblSaveSkin").removeClass('alert alert-success').text("");
        var Skin_name = $("#txtSkinName", "#divSkins").val();
        var chk_Mandatory = "true";
        if ($.trim(Skin_name).length == "0")
        {
            $("#txtSkinName").parents('.control-group').addClass('error');
            $("#spnSkinName").css('display', 'inline');
            chk_Mandatory = "false";
        }
        var logo_desktop = $("#Rem_logo", "#divSkins").is(":disabled") ? "0" : "1";
        var logo_Smartphone = $("#Rem_smartphone", "#divSkins").is(":disabled") ? "0" : "1";
        var logo_HeaderDiv = $("#Rem_header_div", "#divSkins").is(":disabled") ? "0" : "1";
        var HLink_Color = $("#appendedPrependedDropdownButton", "#txtHdrLnkColor ").val();
        var HLinkHOver_Color = $("#appendedPrependedDropdownButton", "#txtHdrLnkHoverColor ").val();
        var Heading_Color = $("#appendedPrependedDropdownButton", "#txtHeadingsColor ").val();
        var PLink_Color = $("#appendedPrependedDropdownButton", "#txtPageLnkColor ").val();
        var PLinkHover_Color = $("#appendedPrependedDropdownButton", "#txtPgeLnkHoverColor ").val();
        var PBg_Color = $("#appendedPrependedDropdownButton", "#txtPgeBgdColor ").val();
        var logo_Level12Bg = $("#Rem_level12_bg", "#divSkins").is(":disabled") ? "0" : "1";
        var logo_Level2Bg = $("#Rem_level2_bg", "#divSkins").is(":disabled") ? "0" : "1";
        var logo_NoNav = $("#Rem_no_nav", "#divSkins").is(":disabled") ? "0" : "1";
        var logo_Widget = $("#Rem_widget_header", "#divSkins").is(":disabled") ? "0" : "1";
        var logo_ddl = $("#Rem_select_bg", "#divSkins").is(":disabled") ? "0" : "1";
        var Leftfooter_title = $("#txtLftFtrTitle", "#divSkins").val();
        var Leftfooter1 = $("#txtLftFtr1", "#divSkins").val();
        if ($.trim($("#txtLftFtrLnk1", "#divSkins").val()).length == "0")
            $("#txtLftFtrLnk1", "#divSkins").val("#");
        var LeftfooterLink1 = $("#txtLftFtrLnk1", "#divSkins").val();
        var Leftfooter1disable = $("#chkLftFtrLnk1", "#divSkins").is(":checked") ? "0" : "1";
        if (Leftfooter1disable == "1" && $.trim(Leftfooter1).length == "0")
        {
            $("#txtLftFtr1", "#divSkins").css('border-color', '#b94a48');
            $("#lblLftFtr1").css('color', '#b94a48');
            $("#spnLftFtr1").css('display', 'inline');
            chk_Mandatory = "false";
        }
        var Leftfooter2 = $("#txtLftFtr2", "#divSkins").val();
        if ($.trim($("#txtLftFtrLnk2", "#divSkins").val()).length == "0")
            $("#txtLftFtrLnk2", "#divSkins").val("#");
        var LeftfooterLink2 = $("#txtLftFtrLnk2", "#divSkins").val();
        var Leftfooter2disable = $("#chkLftFtrLnk2", "#divSkins").is(":checked") ? "0" : "1";
        if (Leftfooter2disable == "1" && $.trim(Leftfooter2).length == "0")
        {
            $("#txtLftFtr2", "#divSkins").css('border-color', '#b94a48');
            $("#lblLftFtr2").css('color', '#b94a48');
            $("#spnLftFtr2").css('display', 'inline');
            chk_Mandatory = "false";
        }
        var Leftfooter3 = $("#txtLftFtr3", "#divSkins").val();
        if ($.trim($("#txtLftFtrLnk3", "#divSkins").val()).length == "0")
            $("#txtLftFtrLnk3", "#divSkins").val("#");
        var LeftfooterLink3 = $("#txtLftFtrLnk3", "#divSkins").val();
        var Leftfooter3disable = $("#chkLftFtrLnk3", "#divSkins").is(":checked") ? "0" : "1";
        if (Leftfooter3disable == "1" && $.trim(Leftfooter3).length == "0")
        {
            $("#txtLftFtr3", "#divSkins").css('border-color', '#b94a48');
            $("#lblLftFtr3").css('color', '#b94a48');
            $("#spnLftFtr3").css('display', 'inline');
            chk_Mandatory = "false";
        }
        var Leftfooter4 = $("#txtLftFtr4", "#divSkins").val();
        if ($.trim($("#txtLftFtrLnk4", "#divSkins").val()).length == "0")
            $("#txtLftFtrLnk4", "#divSkins").val("#");
        var LeftfooterLink4 = $("#txtLftFtrLnk4", "#divSkins").val();
        var Leftfooter4disable = $("#chkLftFtrLnk4", "#divSkins").is(":checked") ? "0" : "1";
        if (Leftfooter4disable == "1" && $.trim(Leftfooter3).length == "0")
        {
            $("#txtLftFtr4", "#divSkins").css('border-color', '#b94a48');
            $("#lblLftFtr4").css('color', '#b94a48');
            $("#spnLftFtr4").css('display', 'inline');
            chk_Mandatory = "false";
        }
        var Rightfooter_title = $("#txtRightFtrTitle", "#divSkins").val();
        var Rightfooter1 = $("#txtRightFtr1", "#divSkins").val();
        if ($.trim($("#txtRightFtrLnk1", "#divSkins").val()).length == "0")
            $("#txtRightFtrLnk1", "#divSkins").val("#");
        var RightfooterLink1 = $("#txtRightFtrLnk1", "#divSkins").val();
        var Rightfooter1disable = $("#chkRightFtrLnk1", "#divSkins").is(":checked") ? "0" : "1";
        if (Rightfooter1disable == "1" && $.trim(Rightfooter1).length == "0")
        {
            $("#txtRightFtr1", "#divSkins").css('border-color', '#b94a48');
            $("#lblRightFtr1").css('color', '#b94a48');
            $("#spnRightFtr1").css('display', 'inline');
            chk_Mandatory = "false";
        }
        var Rightfooter2 = $("#txtRightFtr2", "#divSkins").val();
        if ($.trim($("#txtRightFtrLnk2", "#divSkins").val()).length == "0")
            $("#txtRightFtrLnk2", "#divSkins").val("#");
        var RightfooterLink2 = $("#txtRightFtrLnk2", "#divSkins").val();
        var Rightfooter2disable = $("#chkRightFtrLnk2", "#divSkins").is(":checked") ? "0" : "1";
        if (Rightfooter2disable == "1" && $.trim(Rightfooter2).length == "0")
        {
            $("#txtRightFtr2", "#divSkins").css('border-color', '#b94a48');
            $("#lblRightFtr2").css('color', '#b94a48');
            $("#spnRightFtr2").css('display', 'inline');
            chk_Mandatory = "false";
        }
        var Rightfooter3 = $("#txtRightFtr3", "#divSkins").val();
        if ($.trim($("#txtRightFtrLnk3", "#divSkins").val()).length == "0")
            $("#txtRightFtrLnk3", "#divSkins").val("#");
        var RightfooterLink3 = $("#txtRightFtrLnk3", "#divSkins").val();
        var Rightfooter3disable = $("#chkRightFtrLnk3", "#divSkins").is(":checked") ? "0" : "1";
        if (Rightfooter3disable == "1" && $.trim(Rightfooter3).length == "0")
        {
            $("#txtRightFtr3", "#divSkins").css('border-color', '#b94a48');
            $("#lblRightFtr3").css('color', '#b94a48');
            $("#spnRightFtr3").css('display', 'inline');
            chk_Mandatory = "false";
        }
        var Rightfooter4 = $("#txtRightFtr4", "#divSkins").val();
        if ($.trim($("#txtRightFtrLnk4", "#divSkins").val()).length == "0")
            $("#txtRightFtrLnk4", "#divSkins").val("#");
        var RightfooterLink4 = $("#txtRightFtrLnk4", "#divSkins").val();
        var Rightfooter4disable = $("#chkRightFtrLnk4", "#divSkins").is(":checked") ? "0" : "1";
        if (Rightfooter4disable == "1" && $.trim(Rightfooter2).length == "0")
        {
            $("#txtRightFtr4", "#divSkins").css('border-color', '#b94a48');
            $("#lblRightFtr4").css('color', '#b94a48');
            $("#spnRightFtr4").css('display', 'inline');
            chk_Mandatory = "false";
        }
        var footerCpr = $("#txtfooterCPR", "#divSkins").val();
        if (chk_Mandatory == "false")
        {
            $("#lblSaveSkin").addClass('alert alert-danger').text("Mandatory details are missing");
            return false;
        }
        $("#btnSkinSave").text('Saving').attr("disabled", true);
        var url = BACKENDURL + "customeradmin/edit_skin";
        var data = {
            session_id: localStorage["SESSIONID"],
            contract_id: localStorage["contractid"],
            sid: localStorage["skin_id"],
            sn: Skin_name,
            logo: logo_desktop,
            smartphone: logo_Smartphone,
            header_div: logo_HeaderDiv,
            level12_bg: logo_Level12Bg,
            level2_bg: logo_Level2Bg,
            no_nav: logo_NoNav,
            widget_header: logo_Widget,
            select_bg: logo_ddl,
            hlc: HLink_Color,
            hlhc: HLinkHOver_Color,
            hc: Heading_Color,
            plc: PLink_Color,
            plhc: PLinkHover_Color,
            pb: PBg_Color,
            lfbt: Leftfooter_title,
            lfbd1: Leftfooter1,
            lfbl1: LeftfooterLink1,
            lfbd2: Leftfooter2,
            lfbl2: LeftfooterLink2,
            lfbd3: Leftfooter3,
            lfbl3: LeftfooterLink3,
            lfbd4: Leftfooter4,
            lfbl4: LeftfooterLink4,
            rfbt: Rightfooter_title,
            rfbd1: Rightfooter1,
            rfbl1: RightfooterLink1,
            rfbd2: Rightfooter2,
            rfbl2: RightfooterLink2,
            rfbd3: Rightfooter3,
            rfbl3: RightfooterLink3,
            rfbd4: Rightfooter4,
            rfbl4: RightfooterLink4,
            lfbs1: Leftfooter1disable,
            lfbs2: Leftfooter2disable,
            lfbs3: Leftfooter3disable,
            lfbs4: Leftfooter4disable,
            rfbs1: Rightfooter1disable,
            rfbs2: Rightfooter2disable,
            rfbs3: Rightfooter3disable,
            rfbs4: Rightfooter4disable,
            fct: footerCpr,
            logo_def: localStorage["Rst_logo"],
            smartphone_def: localStorage["Rst_smartphone"],
            header_div_def: localStorage["Rst_header_div"],
            level12_bg_def: localStorage["Rst_level12_bg"],
            level2_bg_def: localStorage["Rst_level2_bg"],
            no_nav_def: localStorage["Rst_no_nav"],
            widget_header_def: localStorage["Rst_widget_header"],
            select_bg_def: localStorage["Rst_select_bg"]
        };
        MakeAjaxCall(url, data, SkinSaveConfirm);
    }
    else
    {
        $("#lblSaveSkin").addClass('alert alert-danger').text(" Mandatory details are missing");
        return false;
    }
}

function SkinSaveConfirm(data)
{
    if (data.error)
    {
        if (data.error_msg == unAthMsg) {
            logout(1)
        }
        else {
            $("#lblSaveSkin").addClass('alert alert-danger').text(data.error_msg);
            $("#btnSkinSave").text('Save').attr("disabled", false);
        }
    }
    else
    {
        var data_skin = "";
        clear_junkdata();
        $("#ddlUserSkin", "#SkinExist").empty();
        for (var nCount = 0; nCount < data.edit_skin_res.length; nCount++) {
            data_skin += "<option value=" + data.edit_skin_res[nCount].skin_id + " >" + data.edit_skin_res[nCount].skin_name + "</option>";
        }
        $("#ddlUserSkin", "#SkinExist").append(data_skin);
        $("#ddlUserSkin", "#SkinExist").val(localStorage["skin_id"]);
        $("#btnSkinSave").text('Saved').attr("disabled", true);
        $("#lblSaveSkin").addClass('alert alert-success').text("Skin has been Saved.");
    }
}


function SrvSkinDelete()
{
    clrInlineErrors();
    $("#divSkinDelete").modal('hide');
    if ($("#lblSaveSkin").hasClass('alert alert-danger'))
        $("#lblSaveSkin").removeClass('alert alert-danger').text("");
    if ($("#lblSaveSkin").hasClass('alert alert-success'))
        $("#lblSaveSkin").removeClass('alert alert-success').text("");
    var url = BACKENDURL + "customeradmin/delete_skin";
    var data = {
        session_id: localStorage["SESSIONID"],
        contract_id: localStorage["contractid"],
        sid: localStorage["skin_id"]
    };
    MakeAjaxCall(url, data, SkinDelete);
}
function SkinDelete(data)
{
    if (data.error)
    {
        $('#DivDeleteProfile').find('label').html('<span style="color:#4F2817;">' + data.error_msg + '</span>');
        $("#DivDeleteProfile").modal('show');
    }
    else
    {
        $("#btnSkinSave").text('Saved').attr("disabled", true);
        $('#DivDeleteProfile').find('label').html('<span style="color:#4F2817;">' + " Success. The skin has been deleted" + '</span>');
        $("#DivDeleteProfile").modal('show');
        SrvSkinCheck();
    }
}

function clrInlineErrors()
{
    $("#txtSkinName").parents('.control-group').removeClass('error');
    $("#spnSkinName").css('display', 'none');
    $("#lblSaveSkin").removeClass("alert alert-danger").text("");
    $("#txtLftFtr1", "#divSkins").css('border-color', '#cccccc');
    $("#lblLftFtr1").css('color', '#333333');
    $("#spnLftFtr1").css('display', 'none');
    $("#lblSaveSkin").hasClass("alert alert-danger")
    $("#lblSaveSkin").removeClass("alert alert-danger").text("");
    $("#txtLftFtr2", "#divSkins").css('border-color', '#cccccc');
    $("#lblLftFtr2").css('color', '#333333');
    $("#spnLftFtr2").css('display', 'none');
    $("#lblSaveSkin").hasClass("alert alert-danger")
    $("#lblSaveSkin").removeClass("alert alert-danger").text("");
    $("#txtLftFtr3", "#divSkins").css('border-color', '#cccccc');
    $("#lblLftFtr3").css('color', '#333333');
    $("#spnLftFtr3").css('display', 'none');
    $("#lblSaveSkin").hasClass("alert alert-danger")
    $("#lblSaveSkin").removeClass("alert alert-danger").text("");
    $("#txtLftFtr4", "#divSkins").css('border-color', '#cccccc');
    $("#lblLftFtr4").css('color', '#333333');
    $("#spnLftFtr4").css('display', 'none');
    $("#lblSaveSkin").hasClass("alert alert-danger")
    $("#lblSaveSkin").removeClass("alert alert-danger").text("");
    $("#txtRightFtr1", "#divSkins").css('border-color', '#cccccc');
    $("#lblRightFtr1").css('color', '#333333');
    $("#spnRightFtr1").css('display', 'none');
    $("#lblSaveSkin").hasClass("alert alert-danger")
    $("#lblSaveSkin").removeClass("alert alert-danger").text("");
    $("#txtRightFtr2", "#divSkins").css('border-color', '#cccccc');
    $("#lblRightFtr2").css('color', '#333333');
    $("#spnRightFtr2").css('display', 'none');
    $("#lblSaveSkin").hasClass("alert alert-danger")
    $("#lblSaveSkin").removeClass("alert alert-danger").text("");
    $("#txtRightFtr3", "#divSkins").css('border-color', '#cccccc');
    $("#lblRightFtr3").css('color', '#333333');
    $("#spnRightFtr3").css('display', 'none');
    $("#lblSaveSkin").hasClass("alert alert-danger")
    $("#lblSaveSkin").removeClass("alert alert-danger").text("");
    $("#txtRightFtr4", "#divSkins").css('border-color', '#cccccc');
    $("#lblRightFtr4").css('color', '#333333');
    $("#spnRightFtr4").css('display', 'none');
    $("#lblSaveSkin").hasClass("alert alert-danger")
    $("#lblSaveSkin").removeClass("alert alert-danger").text("");
    $("#txtLftFtrTitle").parents('.control-group').removeClass('error');
    $("#txtRightFtrTitle").parents('.control-group').removeClass('error');
    $(".help-inline").css("display", "none");
}

// Digital Add Apps

function ClearAddApp()
{
    $("#txtnewAppName", "#divNewAddApp").val("");
    $("#txtnewAppLbl", "#divNewAddApp").val("");
    $("#ddlNavLoc,#ddlForm,#ddlfromhrs,#ddlfrommins,#ddltohrs,#ddltomins").empty();
    $("#chkDisallowforms").prop("checked", false);
    $("#frmNewAddApp").data('validator').resetForm();
    $(".error").removeClass("error");
}

function SubmitAddApp()
{
    if ($("#frmNewAddApp").valid())
    {
        var url = BACKENDURL + "customeradmin/get_navigation_form_details";
        var data = {
            session_id: localStorage["SESSIONID"]
        };
        MakeAjaxCall(url, data, Add_Appdetails);
    }
}

function Add_Appdetails(data)
{
    var data_NavLoc, data_formTy, selectedNavStr = "selected";
    localStorage["hdnAppid"] = "";
    localStorage['prv_url'] = "";
    var jCount = 1;
    $("#zipUpld").removeAttr("disabled");
    for (var nCount = 0; nCount < data.add_app[0].navLoc.length; nCount++) {
        data_NavLoc += "<option value=" + data.add_app[0].navLoc[nCount].mid + " " + selectedNavStr + " >" + data.add_app[0].navLoc[nCount].mn + "</option>";
        selectedNavStr = "";
    }
    $("#ddlNavLoc", "#divDgApp_edit").append(data_NavLoc);
    for (var nCount = 0; nCount < data.add_app[0].formTyp.length; nCount++) {
        data_formTy += "<option value=" + data.add_app[0].formTyp[nCount].did + " " + selectedNavStr + " >" + data.add_app[0].formTyp[nCount].tn + "</option>";
        selectedNavStr = "";
    }
    $("#ddlForm", "#divDgApp_edit").append(data_formTy);
    var data_time = "", data_mins = "", selectedStr = "selected", selectedmins = "selected";
    for (var nCount = 0; nCount < time_hrs.length; nCount++) {
        data_time += "<option value=" + jCount + " " + selectedStr + " >" + time_hrs[nCount] + "</option>";
        selectedStr = "";
        jCount++;
    }
    $("#ddlfromhrs", "#divDgApp_edit").append(data_time);
    $("#ddltohrs", "#divDgApp_edit").append(data_time);
    jCount = 1;
    for (var nCount = 0; nCount < time_mins.length; nCount++) {
        data_mins += "<option value=" + jCount + " " + selectedmins + " >" + time_mins[nCount] + "</option>";
        selectedmins = "";
        jCount++;
    }
    $("#ddlfrommins", "#divDgApp_edit").append(data_mins);
    $("#ddltomins", "#divDgApp_edit").append(data_mins);
    $("#divNewAddApp").modal("hide");
    $("#divDg_add").addClass("hide");
    $("#divtbl_Apps").addClass("hide");
    $("#divDgApp_edit").removeClass("hide");
    $("#txtAppName", "#divDgApp_edit").val($("#txtnewAppName", "#divNewAddApp").val());
    $("#txtAppLbl", "#divDgApp_edit").val($("#txtnewAppLbl", "#divNewAddApp").val());
    $("#btnPreview").attr("disabled", "disabled");
    $("#btnDel").attr("disabled", "disabled");
    $("#btnDownload").attr("disabled", "disabled");
}

function SaveAddApp()
{

    $("#Errt_zipUpld").hide();
    if ($("#frmEditApp").valid())
    {
        var disallow_frm, st_hr, st_min, end_hr, end_min, t_out = "";
        t_out = $("#txtTimeout", "#divDgApp_edit").val();
        if ($("#chkDisallowforms", "#divDgApp_edit").is(":checked")) {
            disallow_frm = "1";
            st_hr = $("#ddlfromhrs", "#divDgApp_edit").val();
            st_min = $("#ddlfrommins", "#divDgApp_edit").val();
            end_hr = $("#ddltohrs", "#divDgApp_edit").val();
            end_min = $("#ddltomins", "#divDgApp_edit").val();
            if (parseInt(end_hr) < parseInt(st_hr))
            {
                $("#lblDigAppError", "#divDgApp_edit").text("End Time should be greater than start time").addClass("alert-danger").show();
                return false;
            }
            else if (parseInt(end_hr) == parseInt(st_hr))
            {
                if (parseInt(end_min) < parseInt(st_min))
                {
                    $("#lblDigAppError", "#divDgApp_edit").text("End Time should be greater than start time").addClass("alert-danger").show();
                    return false;
                }
            }

        }
        else
        {
            disallow_frm = "0";
            st_hr = "0";
            st_min = "0";
            end_hr = "0";
            end_min = "0";
        }

        var url = BACKENDURL + "customeradmin/add_edit_digital_apps";
        var data = {
            session_id: localStorage["SESSIONID"],
            contract_id: localStorage["contractid"],
            app_id: localStorage["hdnAppid"],
            app_name: $("#txtAppName", "#divDgApp_edit").val(),
            app_label: $("#txtAppLbl", "#divDgApp_edit").val(),
            timeout: t_out,
            description: $("#txtDesc", "#divDgApp_edit").val(),
            nav_loc: $("#ddlNavLoc", "#divDgApp_edit").val(),
            frm_type: $("#ddlForm", "#divDgApp_edit").val(),
            upld_tplate: localStorage["zipUpld_sts"],
            template_name: $("#txt_zipUpld", "#divDgApp_edit").val(),
            disallow_forms: disallow_frm,
            start_hour: st_hr,
            start_min: st_min,
            end_hour: end_hr,
            end_min: end_min
        };
        MakeAjaxCall(url, data, Save_Appdetails);
    }
}
function Save_Appdetails(data)
{
    if (data.error == true)
    {
        $("#btnSave_DigApp", "#divDgApp_edit").removeAttr("disabled").text("Save");
        $("#lblDigAppError", "#divDgApp_edit").text(data.error_msg).addClass("alert-danger").show();
    }
    else
    {
        $("#btnSave_DigApp", "#divDgApp_edit").attr("disabled", "disabled").text("Saving");
        digitalpen_Pag = data.add_edit_app;
        localStorage["hdnAppid"] = data.add_edit_app;
        if (localStorage["zipUpld_sts"] == "1")
        {
            $("#btnDel").removeAttr("disabled");
            $("#btnDownload").removeAttr("disabled");
            localStorage["zipUpld_sts"] = "0";
            localStorage['prv_url'] = data.preview_link;
            $("#zipUpld").attr("disabled", "disabled");
        }
        $("#btnSave_DigApp", "#divDgApp_edit").attr("disabled", "disabled").text("Saved");
        $("#lblDigAppError", "#divDgApp_edit").text("App has been saved.").addClass("alert-success").show();
    }
}

function loadDgPens()
{
    var url = BACKENDURL + "customeradmin/get_digital_app_details";
    var data = {
        session_id: localStorage["SESSIONID"]
    };
    MakeAjaxCall(url, data, data_DgPens);
}
function data_DgPens(data)
{
    $("#tbldigitalApps  tbody:last").empty();
    if (data.app_data[0].app_data.length > 0)
    {
        var nCurrRecRound = 0;
        var tblApps = "", app_id = "";
        var hdnCurrPage = $("#currPageNumber", "#tablePagination").val();
        if (hdnCurrPage != undefined) {
            nCurrRecRound = hdnCurrPage - 1;
        }
        for (var nCount = 0; nCount < data.app_data[0].app_data.length; nCount++) {
            app_id = data.app_data[0].app_data[nCount].da_id;
            tblApps += "<tr><td nowrap='nowrap'><div style= 'padding-top:6px'>" + data.app_data[0].app_data[nCount].an + "</div></td>";
            tblApps += "<td nowrap='wrap'><div style= 'padding-top:6px'>" + data.app_data[0].app_data[nCount].lbl + "</div></td>";
            tblApps += "<td><div style= 'padding-top:6px'>" + data.app_data[0].app_data[nCount].dsc + "</div></td>";
            tblApps += "<td nowrap='wrap'><div style= 'padding-top:6px'>" + data.app_data[0].app_data[nCount].tout + "</div></td>";
            tblApps += "<td nowrap='wrap' style='text-align: right;'><button class='btn btn-danger btn-small' id=btnapp_del" + app_id + " onclick='javascript:return delete_app(" + app_id + ")' style='margin-right:10px;'><i class='icon-white icon-trash'></i> Delete</button><button class='btn btn-small' id=btnapp_edit" + app_id + " onclick='javascript:return edit_app(" + app_id + ")'><i class='icon-pencil'></i> Edit</button></td>";
            tblApps += "</tr>";
            $("#tbldigitalApps  tbody:last").append(tblApps);
            $("#tbldigitalApps").show();
            $("#divtbl_Appslist").show();
            tblApps = "";
            if (data.app_data[0].app_data[nCount].da_id == digitalpen_Pag) {
                nCurrRecRound = Math.floor(nCount / 5);
                $("#txtnewAssetTagNumber").val("");
                digitalpen_Pag = 0;
            }
        }
        customerModuleAccess('AL3DIGPEN', 0);

    } else {
        customerModuleAccess('AL3DIGPEN', 0);
        $("#divtbl_Appslist").hide();
    }

    if (data.app_data[0].app_data.length > 5)
    {
        $("#tablePagination", "#divpag_Appslist").remove();
        $("#divtbl_Appslist").tablePagination({
            rowsPerPage: 5,
            currPage: nCurrRecRound + 1
        });
    }
    else
    {
        $("#tablePagination", "#divpag_Appslist").remove();

    }
}

function delete_app(id)
{
    bootbox.dialog({
        message: "Are you sure you want to delete this app?",
        title: "Delete App?",
        buttons: {
            danger: {
                label: "Cancel",
                className: "btn",
                callback: function() {
                }
            },
            success: {
                label: "Delete",
                className: "btn btn-primary",
                callback: function() {
                    var url = BACKENDURL + "customeradmin/delete_digital_app_details";
                    var data = {
                        session_id: localStorage["SESSIONID"],
                        app_id: id,
                        contract_id: localStorage["contractid"]
                    };
                    MakeAjaxCall(url, data, loadDgPens);
                }
            }
        }
    });
}

function edit_app(id)
{
    $("#btnSave_DigApp", "#divDgApp_edit").attr("disabled", "disabled").text("Saved");
    localStorage["hdnAppid"] = id;
    var url = BACKENDURL + "customeradmin/get_digital_app_info";
    var data = {
        session_id: localStorage["SESSIONID"],
        app_id: id
    };
    MakeAjaxCall(url, data, edit_DgApp);
}

function edit_DgApp(data)
{

    var data_NavLoc = "", data_formTy = "", selectedNavStr = "selected";
    for (var nCount = 0; nCount < data.add_app[0].navLoc.length; nCount++) {
        data_NavLoc += "<option value=" + data.add_app[0].navLoc[nCount].mid + " " + selectedNavStr + " >" + data.add_app[0].navLoc[nCount].mn + "</option>";
        selectedNavStr = "";
    }
    $("#ddlNavLoc", "#divDgApp_edit").append(data_NavLoc);
    for (var nCount = 0; nCount < data.add_app[0].formTyp.length; nCount++) {
        data_formTy += "<option value=" + data.add_app[0].formTyp[nCount].did + " " + selectedNavStr + " >" + data.add_app[0].formTyp[nCount].tn + "</option>";
        selectedNavStr = "";
    }
    $("#ddlForm", "#divDgApp_edit").append(data_formTy);
    var data_time = "", data_mins = "", selectedStr = "selected", selectedmins = "selected";
    for (var nCount = 0; nCount < time_hrs.length; nCount++) {
        data_time += "<option value=" + time_hrs[nCount] + " " + selectedStr + " >" + time_hrs[nCount] + "</option>";
        selectedStr = "";
    }
    $("#ddlfromhrs", "#divDgApp_edit").append(data_time);
    $("#ddltohrs", "#divDgApp_edit").append(data_time);
    for (var nCount = 0; nCount < time_mins.length; nCount++) {
        data_mins += "<option value=" + time_mins[nCount] + " " + selectedmins + " >" + time_mins[nCount] + "</option>";
        selectedmins = "";
    }
    $("#ddlfrommins", "#divDgApp_edit").append(data_mins);
    $("#ddltomins", "#divDgApp_edit").append(data_mins);

    $("#divNewAddApp").modal("hide");
    $("#divDg_add").addClass("hide");
    $("#divtbl_Apps").addClass("hide");
    $("#divDgApp_edit").removeClass("hide");
    $("#txtAppName", "#divDgApp_edit").val(data.add_app[0].appdata[0].an);
    $("#txtAppLbl", "#divDgApp_edit").val(data.add_app[0].appdata[0].lbl);
    $("#txtTimeout", "#divDgApp_edit").val(data.add_app[0].appdata[0].tout);
    $("#txtDesc", "#divDgApp_edit").val(data.add_app[0].appdata[0].dsc);
    $("#ddlNavLoc", "#divDgApp_edit").val(data.add_app[0].appdata[0].nl);
    $("#ddlForm", "#divDgApp_edit").val(data.add_app[0].appdata[0].fid);
    $("#txt_zipUpld", "#divDgApp_edit").val(data.add_app[0].appdata[0].tp);
    if (data.add_app[0].appdata[0].df == "1")
    {
        $("#chkDisallowforms", "#divDgApp_edit").prop("checked", true);
        $("#ddlfromhrs", "#divDgApp_edit").val(data.add_app[0].appdata[0].st_hr);
        $("#ddltohrs", "#divDgApp_edit").val(data.add_app[0].appdata[0].end_hr);
        $("#ddlfrommins", "#divDgApp_edit").val(data.add_app[0].appdata[0].st_min);
        $("#ddltomins", "#divDgApp_edit").val(data.add_app[0].appdata[0].end_min);
        $("#ddlfromhrs").prop("disabled", false);
        $("#ddlfrommins").prop("disabled", false);
        $("#ddltohrs").prop("disabled", false);
        $("#ddltomins").prop("disabled", false);
    }
    if (data.add_app[0].appdata[0].tp != "" && data.add_app[0].appdata[0].tp != "Choose...") // Need to check if it has existing templates
    {
        $("#zipUpld").attr("disabled", "disabled");
        $("#btnPreview").removeAttr("disabled");
        $("#btnDel").removeAttr("disabled");
        $("#btnDownload").removeAttr("disabled");
        localStorage['prv_url'] = data.add_app[0].appdata[0].link;
    }
    else
    {
        $("#zipUpld").removeAttr("disabled");
        localStorage['prv_url'] = "";
    }
}

function deleteApp() {

    var url = BACKENDURL + "customeradmin/delete_digital_zip_file";
    var data = {
        session_id: localStorage["SESSIONID"],
        app_id: localStorage["hdnAppid"]
    };
    MakeAjaxCall(url, data, deleteAppSuccess);
}

function deleteAppSuccess(data)
{
    $("#txt_zipUpld").val("Choose...");
    $("#zipUpld").val("");
    localStorage['prv_url'] = "";
    localStorage["zipUpld_sts"] = "0";
    $("#zipUpld").removeAttr("disabled");
    $("#btnPreview").attr("disabled", "disabled");
    $("#btnDel").attr("disabled", "disabled");
    $("#btnDownload").attr("disabled", "disabled");
    $("#lblDigAppError", "#divDgApp_edit").text("Selected file has been deleted.").addClass("alert-success").show();
}

function chkDforms()
{
    if ($("#chkDisallowforms", "#divDgApp_edit").is(":checked")) {
        $("#ddlfromhrs").prop("disabled", false);
        $("#ddlfrommins").prop("disabled", false);
        $("#ddltohrs").prop("disabled", false);
        $("#ddltomins").prop("disabled", false);
    }
    else
    {
        $("#ddlfromhrs").prop("disabled", true);
        $("#ddlfrommins").prop("disabled", true);
        $("#ddltohrs").prop("disabled", true);
        $("#ddltomins").prop("disabled", true);
    }
    $("#btnSave_DigApp", "#divDgApp_edit").removeAttr("disabled").text("Save");
    $("#lblDigAppError", "#divDgApp_edit").hasClass("alert-success")
    $("#lblDigAppError", "#divDgApp_edit").text("").removeClass("alert-success").hide();
    $("#lblDigAppError", "#divDgApp_edit").hasClass("alert-danger")
    $("#lblDigAppError", "#divDgApp_edit").text("").removeClass("alert-danger").hide();
}

function downloadtmplate() {
    var file_dld = $("#txt_zipUpld", "#divDgApp_edit").val();
    var url = URL_load + "/digital_forms";
    window.open(url + "/" + localStorage["contractid"] + "/" + localStorage["hdnAppid"] + "/" + file_dld);
}


// Digital Add Pens

function loadDigitalPens_Srv()
{
    var url = BACKENDURL + "customeradmin/get_digital_pens";
    var data = {
        session_id: localStorage["SESSIONID"]
    };
    MakeAjaxCall(url, data, data_DigitalPens);
}

function data_DigitalPens(data)
{
    $("#tbldigitalPens  tbody:last").empty();
    if (data.digital_pens_res.length > 0)
    {
        var nCurrRecRound = 0;
        var tblPens = "", dp_id = "";
        var hdnCurrPage = $("#currPageNumber", "#tablePagination").val();
        if (hdnCurrPage != undefined) {
            nCurrRecRound = hdnCurrPage - 1;
        }
        for (var nCount = 0; nCount < data.digital_pens_res.length; nCount++) {
            dp_id = data.digital_pens_res[nCount].dp_id;
            tblPens += "<tr><td nowrap='nowrap'><div style= 'padding-top:6px'>" + data.digital_pens_res[nCount].pid + "</div></td>";
            tblPens += "<td nowrap='wrap'><div style= 'padding-top:6px'>" + data.digital_pens_res[nCount].ln + "</div></td>";
            tblPens += "<td><div style= 'padding-top:6px'>" + data.digital_pens_res[nCount].pd + "</div></td>";
            tblPens += "<td nowrap='wrap'><div style= 'padding-top:6px'>" + data.digital_pens_res[nCount].tout + "</div></td>";
            tblPens += "<td nowrap='wrap' style='text-align: right;'><button class='btn btn-danger btn-small' id=btnpen_del" + dp_id + " onclick='javascript:return delete_pen(" + dp_id + ")' style='margin-right:10px;'><i class='icon-white icon-trash'></i> Delete</button><button class='btn btn-small' id=btnpen_edit" + dp_id + " onclick='javascript:return edit_pen(" + dp_id + ")'><i class='icon-pencil'></i> Edit</button></td>";
            tblPens += "</tr>";
            $("#tbldigitalPens  tbody:last").append(tblPens);
            $("#divtbl_Penslist").show();
            $("#tbldigitalPens").show();
            tblPens = "";
            if (data.digital_pens_res[nCount].dp_id == digitalpen_Pag) {
                nCurrRecRound = Math.floor(nCount / 10);
                $("#txtnewAssetTagNumber").val("");
                digitalpen_Pag = 0;
            }
        }
        customerModuleAccess('AL3DIGPEN', 1);

    } else {
        customerModuleAccess('AL3DIGPEN', 1);
        $("#divtbl_Penslist").hide();
    }

    if (data.digital_pens_res.length > 10)
    {
        $("#tablePagination", "#divpag_Penslist").remove();
        $("#divtbl_Penslist").tablePagination({
            rowsPerPage: 10,
            currPage: nCurrRecRound + 1
        });
    }
    else
    {
        $("#tablePagination", "#divpag_Penslist").remove();

    }
}

function ClearAddPen()
{
    $("#txtnewPenName", "#divNewAddPen").val("");
    $("#txtnewPenLbl", "#divNewAddPen").val("");
    $("#frmNewAddPen").data('validator').resetForm();
    $(".error").removeClass("error");
}

function SubmitAddPen()
{
    //$("#tablePagination").hide();
    if ($("#frmNewAddPen").valid())
    {
        $("#divNewAddPen").modal("hide");
        $("#divDg_add").addClass("hide");
        $("#divtbl_Apps").addClass("hide");
        localStorage["hdnPenid"] = "";
        $("#divDgPen_edit").removeClass("hide");
        $("#btnSave_DigPen", "#divDgPen_edit").attr("disabled", "disabled").text("Save");
        $("#txtPenId", "#divDgPen_edit").val($("#txtnewPenName", "#divNewAddPen").val());
        $("#txtPenLbl", "#divDgPen_edit").val($("#txtnewPenLbl", "#divNewAddPen").val());
    }
}

function SaveAddPen()
{
    if ($("#frmEditPen").valid())
    {
        var url = BACKENDURL + "customeradmin/add_edit_digital_pen";
        var data = {
            session_id: localStorage["SESSIONID"],
            dp_id: localStorage["hdnPenid"],
            pen_id: $("#txtPenId", "#divDgPen_edit").val(),
            label: $("#txtPenLbl", "#divDgPen_edit").val(),
            timeout: $("#txtPenTimeout", "#divDgPen_edit").val(),
            description: $("#txtPenDesc", "#divDgPen_edit").val()
        };
        MakeAjaxCall(url, data, Save_Pendetails);
    }
}

function Save_Pendetails(data)
{
    if (data.error == true)
    {
        $("#lblDigPenError", "#divDgPen_edit").text(data.error_msg).addClass("alert-danger").show();
    }
    else
    {
        digitalpen_Pag = data.dp_id;
        localStorage["hdnPenid"] = data.dp_id;
        $("#btnSave_DigPen", "#divDgPen_edit").attr("disabled", "disabled").text("Saved");
        $("#lblDigPenError", "#divDgPen_edit").text("Pen has been saved.").addClass("alert-success").show();
    }
}

function edit_pen(id)
{
    var url = BACKENDURL + "customeradmin/get_digital_pen_details";
    localStorage["hdnPenid"] = id;
    var data = {
        session_id: localStorage["SESSIONID"],
        dp_id: localStorage["hdnPenid"]
    };
    MakeAjaxCall(url, data, edit_Dgpen);
}

function edit_Dgpen(data)
{
    if (data.error == true)
    {
        $("#lblDigPenError", "#divDgPen_edit").text(data.error_msg).addClass("alert-danger").show();
    }
    else
    {
        localStorage["hdnPenid"] = data.digital_pen_details[0].dp_id;
        $("#divNewAddPen").modal("hide");
        $("#divtbl_Apps").addClass("hide");
        $("#divtbl_Pens").addClass("hide");
        $("#divDg_add").addClass("hide");
        $("#divDgPen_edit").removeClass("hide");
        $("#txtPenId", "#divDgPen_edit").val(data.digital_pen_details[0].pen_id);
        $("#txtPenLbl", "#divDgPen_edit").val(data.digital_pen_details[0].label);
        $("#txtPenTimeout", "#divDgPen_edit").val(data.digital_pen_details[0].timeout);
        $("#txtPenDesc", "#divDgPen_edit").val(data.digital_pen_details[0].description);
        $("#btnSave_DigPen", "#divDgPen_edit").attr("disabled", "disabled").text("Saved");
    }

}

function delete_pen(id)
{
    bootbox.dialog({
        message: "Are you sure you want to delete this pen?",
        title: "Delete Pen?",
        buttons: {
            danger: {
                label: "Cancel",
                className: "btn",
                callback: function() {
                }
            },
            success: {
                label: "Delete",
                className: "btn btn-primary",
                callback: function() {
                    var url = BACKENDURL + "customeradmin/delete_digital_pen";
                    var data = {
                        session_id: localStorage["SESSIONID"],
                        dp_id: id
                    };
                    MakeAjaxCall(url, data, loadDigitalPens_Srv);
                }
            }
        }
    });
}

//QA Accounts table
function loadQAAccounts_Srv()
{
    var url = BACKENDURL + "customeradmin/get_qa_accounts";
    var data = {
        session_id: localStorage["SESSIONID"]
    };
    MakeAjaxCall(url, data, loadQAAccounts);
}

function loadQAAccounts(data)
{
    $("#lblAccountEmptyError", "#divAccountList").text("No records available.").addClass("alert-danger").hide();
    $("#divtblGroupslist").hide();
    $("#divBtnAddGroup").hide();
    $("#divAddGroup").hide();
    $("#divBtnAddAccount").show();
    $("#divAddAccount").hide();
    $("#divAccountList").show();
    $("#tblQAAccounts tbody:last").empty();
    if (data.qa_accounts_res.length > 0)
    {
        var nCurrRecRound = 0;
        var grpClickEvent = '';
        var tblPens = "", ac_id = "";
        $("div[id^=tablePagination]").hide();
        var hdnCurrPage = $("#currPageNumber", "#tablePagination").val();
        if (hdnCurrPage != undefined) {
            nCurrRecRound = hdnCurrPage - 1;
        }
        customerModuleAccess('AL3QTYAUD', 1);
        for (var nCount = 0; nCount < data.qa_accounts_res.length; nCount++) {
            ac_id = data.qa_accounts_res[nCount].aid;
            tblPens += "<tr><td data-title='Account Name' nowrap='nowrap'><div style= 'padding-top:6px'>" + data.qa_accounts_res[nCount].an + "</div></td>";
            tblPens += "<td nowrap='wrap' data-title='Code'><div style= 'padding-top:6px'>" + data.qa_accounts_res[nCount].ac + "</div></td>";
            tblPens += "<td data-title='Description'><div style= 'padding-top:6px'>" + data.qa_accounts_res[nCount].ad + "</div></td>";
            tblPens += "<td data-title='Last status'><div style= 'padding-top:6px'>" + data.qa_accounts_res[nCount].als + "</div></td>";
            tblPens += "<td data-title='Custom Groups'><div style= 'padding-top:6px'>" + data.qa_accounts_res[nCount].acg + "</div></td>";
            if ((localStorage['AccessAllowed'] == 0) && (data.qa_accounts_res[nCount].acg == 0)) {
                grpClickEvent = 'disabled';
            } else {
                grpClickEvent = "onclick='javascript:return get_acctgrp_srv(" + ac_id + " ,\"" + data.qa_accounts_res[nCount].an + "\", " + data.qa_accounts_res[nCount].acg + ")'";
            }
            if (data.qa_accounts_res[nCount].alm === null)
                tblPens += "<td data-title='Last modified' nowrap='wrap'><div style= 'padding-top:6px'></div></td>";
            else
                tblPens += "<td data-title='Last modified' nowrap='wrap'><div style= 'padding-top:6px'>" + data.qa_accounts_res[nCount].alm + "</div></td>";
            tblPens += "<td nowrap='wrap' style='text-align: right;'><button class='btn btn-primary btn-small' id=btn_account_groups style='margin-right:10px;'" + grpClickEvent + "><i class='icon-white icon-list'></i> Groups</button><button class='btn btn-danger btn-small' id=btnacc_del" + ac_id + " onclick='javascript:return delete_account(" + ac_id + ")' style='margin-right:10px;'><i class='icon-white icon-trash'></i> Delete</button><button class='btn btn-small' id=btnacc_edit" + ac_id + " onclick='javascript:return addEditAccount(" + ac_id + ")'><i class='icon-pencil'></i> Edit</button></td>";
            tblPens += "</tr>";
            $("#tblQAAccounts tbody:last").append(tblPens);
            $("#divtblAccountlist").show();
            $("#tblQAAccounts").show();
            tblPens = "";
            if (data.qa_accounts_res[nCount].aid == Addeditacct_pag) {
                nCurrRecRound = Math.floor(nCount / 2);
                $("#txtnewAssetTagNumber").val("");
                Addeditacct_pag = 0;
            }
        }
        customerModuleAccess('AL3QTYAUD', 1);

    } else {
        customerModuleAccess('AL3QTYAUD', 1);
        $("#lblAccountEmptyError").text("No records available.").addClass("alert-danger").show();
        $("#divtblAccountlist").hide();
        //$("#divpag_Accountlist").hide();
    }

    if (data.qa_accounts_res.length > 10)
    {
        $("#tablePagination").html('');
        $("#tablePagination", "#divpag_Accountlist").remove();
        if ($("#txtAddEdit").val() == 'edit')
            nCurrRecRound = 0
        $("#divpag_Accountlist").tablePagination({
            rowsPerPage: 10,
            currPage: nCurrRecRound + 1
        });
    }
    else
    {
        $("#tablePagination", "#divpag_Accountlist").remove();

    }
}

function addEditAccount(f) {
    $("#btnSave_Addacct").text("Save").attr("disabled", "disabled");
    $("#divNewAddApp").modal("hide");
    $("#divBtnAddAccount").hide();
    $("#divAddAccount").show();
    $("#divBtnAddGroup").hide();
    $("#divAccountList").hide();
    $("#bcHome").empty();
    $("#bcHome").append("<li>You are here: <a href='javascript:showAccounts()'>Quality Audit</a></li>");
    $("#chkSync").change(function() {
        if ($("#chkSync").is(":checked"))
            $("#btnSave_Addacct").removeAttr("disabled").text('Save');
        else
            $("#btnSave_Addacct").removeAttr("disabled").text('Save');
    });
    if (f == undefined) {
        $("#btnSave_Addacct").text("Save").attr("disabled", "disabled");
        $("#hdnAccountid").val("");
        $("#addAcct_status").hide();
        $("#divStatus").hide();
        $("#divSync").show();
        $("#chkSelfReg").removeAttr("checked");
        $("#bcHome").append("<li><span class='divider'>/</span>&nbsp;Add Account</li>");
        var url = BACKENDURL + "customeradmin/get_qa_user_access_details";
        var data = {
            session_id: localStorage["SESSIONID"]
        };
        MakeAjaxCall(url, data, addAccount_data);
    }
    else
    {
        $("#btnSave_Addacct").text("Saved").attr("disabled", "disabled");
        $("#bcHome").append("<li><span class='divider'>/</span>&nbsp;Account 1</li>");
        var url = BACKENDURL + "customeradmin/get_qa_account_details";
        var data = {
            session_id: localStorage["SESSIONID"],
            account_id: f
        };
        MakeAjaxCall(url, data, EditAccount_data);
    }
    $("#divSyncDate").hide();

}

function EditAccount_data(data)
{
    $("div[id^=tablePagination]").hide();
    $("#addAcct_status").hide();
    $("#txtAddEdit").val('edit');
    $("#txtAccName").val(data.acc_res[0].account_name);
    $("#txtAccCode").val(data.acc_res[0].account_code);
    $("#txtAccDesc").val(data.acc_res[0].description);
    $("#hdnAccountid").val(data.acc_res[0].aid);
    if (data.acc_res[0].sync_status == 0)
    {
        $("#divStatus").hide();
        $("#divSync").show();
    }
    else
    {
        $("#divStatus").show();
        $("#lblLastStatus").text(data.acc_res[0].last_status);
        $("#divSync").hide();
    }
    $("#tblUsers_QAct  tbody:last").empty();
    // var tbl_userlist = "";
    if (data.user_res.length > 0)
    {

        var nCurrRecRound = 0;
        var hdnCurrPage = $("#currPageNumber", "#tablePagination").val();
        if (hdnCurrPage != undefined) {
            nCurrRecRound = hdnCurrPage - 1;
        }
        for (var nCount = 0; nCount < data.user_res.length; nCount++)
        {
            $("#tblUsers_QAct tbody:last").append("<tr id=" + data.user_res[nCount].user_id + "><td data-title='Name'>" + data.user_res[nCount].first_name + " " + data.user_res[nCount].last_name + "</td><td data-title='Username'>" + data.user_res[nCount].username + "</td><td data-title='Create/Modify SLA Reports'><input type='checkbox' id='chk_" + nCount + "_1' value='0' name='chk_" + nCount + "_1' onchange='chkAcc_user(" + nCount + ",1);'></td><td data-title='Run Ad-hoc Report'><input type='checkbox' id='chk_" + nCount + "_2' value='0' name='chk_" + nCount + "_2' onchange='chkAcc_user(" + nCount + ",2);'></td><td data-title='View SLA Reports'><input type='checkbox' id='chk_" + nCount + "_3' value='0' name='chk_" + nCount + "_3' onchange='chkAcc_user(" + nCount + ",3);'></td></tr>");
            if ((data.user_res[nCount].create_sla_report == "1") && (data.user_res[nCount].run_adhoc_report == "1") && (data.user_res[nCount].view_sla_report == "1"))
            {
                $("#chk_" + nCount + "_1").prop('checked', true);
                $("#chk_" + nCount + "_2").prop('checked', true).attr("disabled", "disabled");
                $("#chk_" + nCount + "_3").prop('checked', true).attr("disabled", "disabled");
            }
            else if ((data.user_res[nCount].run_adhoc_report == "1") && (data.user_res[nCount].view_sla_report == "1"))
            {
                $("#chk_" + nCount + "_1").prop('checked', false);
                $("#chk_" + nCount + "_2").prop('checked', true);
                $("#chk_" + nCount + "_3").prop('checked', true).attr("disabled", "disabled");
            }
            else if (data.user_res[nCount].view_sla_report == "1")
            {
                $("#chk_" + nCount + "_1").prop('checked', false);
                $("#chk_" + nCount + "_2").prop('checked', false);
                $("#chk_" + nCount + "_3").prop('checked', true);
            }
            else
            {
                $("#chk_" + nCount + "_1").prop('checked', false);
                $("#chk_" + nCount + "_2").prop('checked', false);
                $("#chk_" + nCount + "_3").prop('checked', false);
            }
        }
    }
    if (data.user_res.length > 10)
    {
        $("#tablePagination", "#divpag_UsersList").remove();
        $("#divpag_UsersList").tablePagination({
            rowsPerPage: 10,
            currPage: nCurrRecRound + 1
        });
    }
    else
    {
        $("#tablePagination", "#divpag_UsersList").remove();
    }
}

function addAccount_data(data)
{
    $("#txtAddEdit").val('add');
    $("div[id^=tablePagination]").hide();
    $("#tblUsers_QAct  tbody:last").empty();
    var tbl_userlist = "";
    if (data.user_res.length > 0)
    {
        $("#tablePagination").hide();
        var nCurrRecRound = 0;
        var hdnCurrPage = $("#currPageNumber", "#tablePagination").val();
        if (hdnCurrPage != undefined) {
            nCurrRecRound = hdnCurrPage - 1;
        }
        for (var nCount = 0; nCount < data.user_res.length; nCount++)
        {
            tbl_userlist += "<tr id=" + data.user_res[nCount].user_id + "><td data-title='Name'>" + data.user_res[nCount].first_name + " " + data.user_res[nCount].last_name + "</td><td data-title='Username'>" + data.user_res[nCount].username + "</td><td data-title='Create/Modify SLA Reports'><input type='checkbox' id='chk_" + nCount + "_1' value='0' name='chk_" + nCount + "_1' onchange='chkAcc_user(" + nCount + ",1);'></td><td data-title='Run Ad-hoc Report'><input type='checkbox' id='chk_" + nCount + "_2' value='0' name='chk_" + nCount + "_2' onchange='chkAcc_user(" + nCount + ",2);'></td><td data-title='View SLA Reports'><input type='checkbox' id='chk_" + nCount + "_3' value='0' name='chk_" + nCount + "_3' onchange='chkAcc_user(" + nCount + ",3);'></td></tr>";
        }
        $("#tblUsers_QAct tbody:last").append(tbl_userlist);
        $("#lbluserstatus").hide();
        $("#tblUsers_QAct").show();

    }
    else
    {
        $("#lbluserstatus").html("No Users available").show();
        $("#tblUsers_QAct").hide();
    }
    if (data.user_res.length > 10)
    {
        $("#tablePagination", "#divpag_UsersList").remove();
        $("#divpag_UsersList").tablePagination({
            rowsPerPage: 10,
            currPage: nCurrRecRound + 1
        });
    }
    else
    {
        $("#tablePagination", "#divpag_UsersList").remove();

    }


}

function chkAcc_user(row_id, col)
{
    $("#btnSave_Addacct").text('Save').removeAttr("disabled", "disabled");
    if (col == 1)
    {
        if ($("#chk_" + row_id + "_1").is(':checked'))
        {
            $("#chk_" + row_id + "_2").prop('checked', true).attr("disabled", "disabled");
            $("#chk_" + row_id + "_3").prop('checked', true).attr("disabled", "disabled");
        }
        else
        {
            $("#chk_" + row_id + "_2").prop('checked', false).removeAttr("disabled", "disabled");
            $("#chk_" + row_id + "_3").prop('checked', false).removeAttr("disabled", "disabled");
        }
    }
    else if (col == 2)
    {
        if ($("#chk_" + row_id + "_2").is(':checked'))
        {
            $("#chk_" + row_id + "_3").prop('checked', true).attr("disabled", "disabled");
        }
        else
        {
            $("#chk_" + row_id + "_1").removeAttr("disabled", "disabled");
            $("#chk_" + row_id + "_3").prop('checked', false).removeAttr("disabled", "disabled");
        }
    }
    else
    {
        if (!($("#chk_" + row_id + "_3").is(':checked')))
        {
            $("#chk_" + row_id + "_1").removeAttr("disabled", "disabled");
            $("#chk_" + row_id + "_2").removeAttr("disabled", "disabled");
        }
    }
}

function showSyncDate(f) {
    if ($(f).attr('checked'))
        $("#divSyncDate").show();
    else
        $("#divSyncDate").hide();
}

function saveAccounts_srv()
{
    if ($("#frmEditAccount").valid())
    {   
        var user_dt = [];
        var iCount = 0;
        var syncst = "";
        $('#tblUsers_QAct > tbody  > tr').each(function(i, row) {
            var c_SLA = $("#chk_" + iCount + "_1", this).is(':checked') ? "1" : "0";
            var R_adh = $("#chk_" + iCount + "_2", this).is(':checked') ? "1" : "0";
            var R_SLA = $("#chk_" + iCount + "_3", this).is(':checked') ? "1" : "0";
            user_dt.push({"user_id": row.id, "cr_sla": c_SLA, "ad_hoc": R_adh, "v_sla": R_SLA});
            iCount++;
        });
        var dp_date = $("#chkSync").is(':checked') ? $("#txtRBED").val() : "";
        if ($("#chkSync").is(":checked"))
            $("#btnSave_Addacct").removeAttr("disabled").text('Save');
        else
            $("#btnSave_Addacct").removeAttr("disabled").text('Save');
        if (dp_date != "")
        {
            var dpdate_ar = dp_date.split("/");
            dp_date = dpdate_ar[2] + "-" + dpdate_ar[1] + "-" + dpdate_ar[0];
        }
        else
        {
            dp_date = "";
        }
        if ($("#hdnAccountid").val() == "")
            syncst = $("#chkSync").is(':checked') ? "1" : "0";
        else
            syncst = $("#chkSync").is(':checked') ? "1" : "";
        $("#btnSave_Addacct").text("Saving").attr("disabled", "disabled");
        var url = BACKENDURL + "customeradmin/add_edit_qa_account";
        var data = {
            session_id: localStorage["SESSIONID"],
            account_id: $("#hdnAccountid").val(),
            account_name: $("#txtAccName").val(),
            code: $("#txtAccCode").val(),
            desc: $("#txtAccDesc").val(),
            sync_status: syncst,
            user_data: user_dt,
            start_date: dp_date
        };
        MakeAjaxCall(url, data, saveAccounts);
    }
}

function saveAccounts(data)
{
    if (data.error)
    {
        $("#addAcct_status").html(data.error_msg).show();
        $("#btnSave_Addacct").text("Save").removeAttr("disabled", "disabled");
    }
    else
    {
        Addeditacct_pag = data.add_edit_res;
        $("#btnSave_Addacct").text("Saved").removeAttr("disabled", "disabled");
        showAccounts();
    }
}

function showAccounts() {
    // $("#tblQAAccounts").show();
    $("#frmEditAccount").data('validator').resetForm();
    $(".error").removeClass("error");
    $("#txtAccName").val("");
    $("#txtAccCode").val("");
    $("#txtAccDesc").val("");
    $("#hdnAccountid").val("");
    $("#chkSync").prop("checked", false);
    $('input[id^="chk_"]', "#tblUsers_QAct").prop("checked", false);
    $("#addAcct_status").hide();
    $("#divtblAccountlist").show();
    $("#divtblGroupslist").hide();
    $("#divBtnAddAccount").show();
    $("#divAccountList").show();
    $("#divAddAccount").hide();
    $("#divBtnAddGroup").hide();
    $("#divAddGroup").hide();
    $("#bcHome").empty();
    $("#bcHome").append("<li>You are here: Quality Audit </li>");
    loadQAAccounts_Srv();
}

function delete_account(id)
{
    bootbox.dialog({
        message: "Are you sure you want to delete this account?",
        title: "Delete Account?",
        buttons: {
            danger: {
                label: "Cancel",
                className: "btn",
                callback: function() {
                }
            },
            success: {
                label: "Delete",
                className: "btn btn-primary",
                callback: function() {
                    //add service
                    var url = BACKENDURL + "customeradmin/delete_qa_account";
                    var data = {
                        session_id: localStorage["SESSIONID"],
                        account_id: id
                    };
                    MakeAjaxCall(url, data, loadQAAccounts_Srv);
                }
            }
        }
    });
}

// QA Account Groups
function get_acctgrp_srv(account_id, ac_name, grp_cnt) {
    if (ac_name != undefined)
    {
        localStorage["btn_click"] = "btn_grp";
        $("#txthdnacname").val(ac_name);
    }
    if (grp_cnt != undefined)
    $("#txthdngrpcnt").val(grp_cnt);
    $("#txthdnacid").val(account_id);
    var url = BACKENDURL + "customeradmin/get_qa_groups";
    var data = {
        session_id: localStorage["SESSIONID"],
        account_id: account_id
    };
    MakeAjaxCall(url, data, get_acctgrp);
}

function get_acctgrp(data)
{
    $("#divBtnAddAccount").hide();
    $("#divtblAccountlist").hide();
    $("#tablePagination", "#divpag_Accountlist").remove();
    $("#divBtnAddGroup").show();
    $("#btnClose_Group").removeClass('hide');
    $("#tblGroupslist tbody:last").empty();
    $("#btngrp").remove();
    var ac_id = $("#txthdnacid").val();
    $("#divBtnAddGroup").append("<div id='btngrp' class='control-group'><div style='margin-top: 15px!important;' class='btn-group segment-button pull-right'><button id='btnAddGroup' class='btn btn-primary' type='button' onClick='javascript:addEditGroup(" + ac_id + ",\"0\");'>Add Group</button></div></div>");
    if (data.qa_groups_res.length > 0)
    {
        var nCurrRecRound = 0;
        var tblPens, gr_id, pt_id = "";
        $("#lblAccountEmptyError").hide();
        var hdnCurrPage = $("#currPageNumber", "#tablePagination").val();
        if (hdnCurrPage != undefined) {
            nCurrRecRound = hdnCurrPage - 1;
        }
        for (var nCount = 0; nCount < data.qa_groups_res.length; nCount++) {
            gr_id = data.qa_groups_res[nCount].gid;
            pt_id = (data.qa_groups_res[nCount].pin == null) ? "-" : data.qa_groups_res[nCount].pin;
            tblPens += "<tr><td data-title='Group Name' nowrap='nowrap'><div style= 'padding-top:6px'>" + data.qa_groups_res[nCount].gn + "</div></td>";
            tblPens += "<td data-title='Point Indicators'  nowrap='wrap'><div style= 'padding-top:6px'>" + pt_id + "</div></td>";
            if (data.qa_groups_res[nCount].alm === null)
                tblPens += "<td data-title='Last Modified' nowrap='wrap'><div style= 'padding-top:6px'></div></td>";
            else
                tblPens += "<td data-title='Last Modified' nowrap='wrap'><div style= 'padding-top:6px'>" + data.qa_groups_res[nCount].alm + "</div></td>";
            tblPens += "<td nowrap='wrap' style='text-align: right;'><button class='btn btn-danger btn-small' id=btngroup_del" + gr_id + " onclick='javascript:return delete_group(" + gr_id + "," + ac_id + ")' style='margin-right:10px;'><i class='icon-white icon-trash'></i> Delete</button><button class='btn btn-small' id=btngroup_edit" + gr_id + " onclick='javascript:return addEditGroup(" + ac_id + "," + gr_id + ")'><i class='icon-pencil'></i> Edit</button></td>";
            tblPens += "</tr>";
            $("#tblGroupslist tbody:last").append(tblPens);
            $("#divtblGroupslist").show();
            $("#tblGroupslist").show();
            tblPens = "";
        }
        customerModuleAccess('AL3QTYAUD', 1);

    } else {
        customerModuleAccess('AL3QTYAUD', 1);
        //$("#btnAddGroup", "#divBtnAddGroup").click();
        //$("#lblAccountEmptyError").text("No records available.").addClass("alert-danger").show();
        $("#divtblGroupslist").hide();
        //$("#divAddGroup").show();
    }

    if (data.qa_groups_res.length > 10)
    {
        $("#tablePagination", "#divtblGroupslist").remove();
        //$("#tablePagination", "#divpag_Grplist").remove();
        $("#divpag_Grplist").tablePagination({
            rowsPerPage: 10,
            currPage: nCurrRecRound + 1
        });
    }
    else
    {
        //$("#tablePagination", "#divpag_Grplist").remove();
        $("#tablePagination", "#divtblGroupslist").remove();

    }

    if (data.qa_groups_res.length == 0)
    {
        if (localStorage["btn_click"] == "btn_grp_del")
            loadQAAccounts_Srv();
        else
            $("#btnAddGroup", "#divBtnAddGroup").click();
    }
}

function addEditGroup(accId, grpid) {
    $("#lblgrps_err").hide();
    $("#divBtnAddAccount").hide();
    $("#divAccountList").hide();
    $("#divBtnAddGroup").hide();
    $("#divAddAccount").hide();
    $("#divtblGroupslist").hide();
    $("#divAddGroup").show();
    $("#txthdngrpid").val(grpid);
    $("#txtGroupAccName").html($("#txthdnacname").val());
    var data = {
        session_id: localStorage["SESSIONID"],
        account_id: accId,
        group_id: grpid
    };
    if (grpid != "0")
    {
        $("#btnSave_QAGrp").text('Saved').attr("disabled", true);
        var url = BACKENDURL + "customeradmin/get_qa_group_details";
        MakeAjaxCall(url, data, addEgrp_dt);
    }
    else
    {
        $("#txthdngrpid").val('0');
        $("#btnSave_QAGrp").text('Save').attr("disabled", true);
        $("#txtRedfrom").val("0");
        $("#txtRedto").val("70");
        $("#txtAmberfrom").val("80");
        $("#txtAmberto").val("90");
        $("#txtGrnfrom").val("91");
        $("#txtGrnto").val("100");
        $("#txtPurplefrom").val("");
        $("#txtPurpleto").val("");
        $("#txtbluefrom").val("");
        $("#txtblueto").val("");
        $("#lblrag_err").hide();
        var url = BACKENDURL + "customeradmin/get_qa_group_indicators";
        localStorage["GROUPNAME"] = '';
        MakeAjaxCall(url, data, load_ptind);
    }

    /*$("#bcHome").empty();
     $("#bcHome").append("<li>You are here: <a href='javascript:showAccounts()'>Quality Audit</a></li>");
     $("#bcHome").append("<li><span class='divider'>/</span>&nbsp;<a href='javascript:showGroups()'>Groups</a></li>");
     if (f == undefined)
     $("#bcHome").append("<li><span class='divider'>/</span>&nbsp;Add Group</li>");
     else
     $("#bcHome").append("<li><span class='divider'>/</span>&nbsp;Group 1</li>");*/
}

function load_ptind(data)
{

    //data = '{"qa_groups_res":{"selected_res":[],"available_res":[{"ind_id":"16","qa_account_group_id":"2","ind_name":"Yes","p_val":"9","gr_name":"default"},{"ind_id":"17","qa_account_group_id":"3","ind_name":"No","p_val":"22","gr_name":"default"},{"ind_id":"21","qa_account_group_id":"5","ind_name":"Pass","p_val":"21","gr_name":"Group1"},{"ind_id":"290","qa_account_group_id":"85","ind_name":"7","p_val":"21","gr_name":"default"},{"ind_id":"296","qa_account_group_id":"7","ind_name":"70","p_val":"90","gr_name":"default"}]},"error":false,"session_status":true}';
    //data = JSON.parse(data);
    if (data.session_status) {
        if (data.error == 0) {
            $("#lstAvailablePointInd > tbody > tr").remove();
            $("#lstSelectedPointInd > tbody > tr").remove();
            var dataadmin_availableptind = "", dataadmin_selectedptind = "";
            for (var nCount = 0; nCount < data.qa_groups_res.available_res.length; nCount++) {
                var data_populate = data.qa_groups_res.available_res[nCount].ind_name + " ( " + data.qa_groups_res.available_res[nCount].gr_name + " ) " + " Value : " + data.qa_groups_res.available_res[nCount].p_val;
                //alert(data.qa_groups_res.available_res.length);
                if ((data.qa_groups_res.available_res[nCount].gr_name != 'default') && (data.qa_groups_res.available_res[nCount].gr_name != localStorage["GROUPNAME"]))
                    dataadmin_availableptind += "<tr id='" + data.qa_groups_res.available_res[nCount].ind_id + "'><td style='border:1px solid #d3d3d3;background-color:#d3d3d3;color:black;'>" + data_populate + "</td></tr>";
                else
                    dataadmin_availableptind += "<tr id='" + data.qa_groups_res.available_res[nCount].ind_id + "' onClick='addRemoveUsers(this);'><td style='border:1px solid #d3d3d3'>" + data_populate + "</td></tr>";
            }
            if (data.qa_groups_res.selected_res != undefined) {
                for (var nCount = 0; nCount < data.qa_groups_res.selected_res.length; nCount++) {
                    var data_populate = data.qa_groups_res.selected_res[nCount].ind_name + " ( " + data.qa_groups_res.selected_res[nCount].gr_name + " ) " + " Value : " + data.qa_groups_res.selected_res[nCount].p_val;
                    dataadmin_selectedptind += "<tr id='" + data.qa_groups_res.selected_res[nCount].ind_id + "' onClick='addRemoveUsers(this);'><td style='border:1px solid #d3d3d3'>" + data_populate + "</td></tr>";
                    spt_ind[nCount] = data.qa_groups_res.selected_res[nCount].ind_id;
                }

            } else {
                spt_ind = [];
            }

            $("#lstAvailablePointInd tbody:last").append(dataadmin_availableptind);
            $("#lstSelectedPointInd tbody:last").append(dataadmin_selectedptind);
        } else
            logout(1);
    }

}
function addEgrp_dt(data) {
    localStorage["GROUPNAME"] = data.qa_groups_res[0].gr_name;
    $("#txtGroupName").val(data.qa_groups_res[0].gr_name);
    $("#txtRedfrom").val(data.qa_groups_res[0].red_from);
    $("#txtRedto").val(data.qa_groups_res[0].red_to);
    $("#txtAmberfrom").val(data.qa_groups_res[0].amber_from);
    $("#txtAmberto").val(data.qa_groups_res[0].amber_to);
    $("#txtGrnfrom").val(data.qa_groups_res[0].green_from);
    $("#txtGrnto").val(data.qa_groups_res[0].green_to);
    $("#txtPurplefrom").val(data.qa_groups_res[0].purple_from);
    $("#txtPurpleto").val(data.qa_groups_res[0].purple_to);
    $("#txtbluefrom").val(data.qa_groups_res[0].blue_from);
    $("#txtblueto").val(data.qa_groups_res[0].blue_to);
    var data = {
        session_id: localStorage["SESSIONID"],
        account_id: $("#txthdnacid").val(),
        group_id: $("#txthdngrpid").val()
    };
    var url = BACKENDURL + "customeradmin/get_qa_group_indicators";
    MakeAjaxCall(url, data, load_ptind);

}

function saveQAGrp_srv()
{
    var pt_ind = [], pre_ind = [], rem_ind = [];
    var i = 1;
    $("#lstSelectedPointInd tr").each(function(e) {
        pt_ind.push({
            "ind_id": $(this).attr("id"),
            "seq_no": i
        });
        pre_ind[i - 1] = $(this).attr("id");
        i++;
    });

    if (pt_ind.length == 0) {
        pre_ind = '';
        pt_ind = '';
    }

    if (spt_ind.length == 0) {
        rem_ind = '';
    }

    /* console.log(pre_ind);
     console.log(spt_ind);
     console.log(spt_ind.length);*/
    for (var sptCount = 0; sptCount < spt_ind.length; sptCount++) {
        if ($.inArray(spt_ind[sptCount], pre_ind) == -1) {
            rem_ind[sptCount] = spt_ind[sptCount];
        }
    }
    //console.log(rem_ind);
    if ($("#frmEditGrp").valid())
    {
        var resetRGB = calculateRGBPercentage();
        if (resetRGB == true)
        {
            if ((localStorage["GROUPNAME"] != $("#txtGroupName").val()) && (grpChanged == 1) && ($("#txthdngrpid").val() != 0) ) {
               slaWarning(pt_ind,rem_ind);       
            } else if ((localStorage["GROUPNAME"] == $("#txtGroupName").val()) && (grpChanged == 1) && ($("#txthdngrpid").val() != 0)) {
               slaWarning(pt_ind,rem_ind);   
            } else {
               saveGrps(pt_ind,rem_ind);
            }                     
        }
        else
        {
            $("#lblrag_err").text("RAG should be 0% - 100%").show();

        }
    }
    
     
}

function slaWarning(pt_ind,rem_ind) {
                bootbox.dialog({
                        message: "Warning, SLA reports will be deleted",
                        title: "Warning!",
                        buttons: {
                        danger: {
                        label: "Cancel",
                        className: "btn",
                        callback: function() {

                        }
                        },
                        success: {
                        label: "Continue",
                        className: "btn-primary",
                        callback: function() {
                           saveGrps(pt_ind,rem_ind);
                        }
                        }
                        }
                });  
}

function saveGrps (pt_ind,rem_ind)
{
    $("#btnSave_QAGrp").attr("disabled", true).text('Saving');
            var url = BACKENDURL + "customeradmin/add_edit_qa_groups";
            var data = {
                session_id: localStorage["SESSIONID"],
                account_id: $("#txthdnacid").val(),
                group_id: $("#txthdngrpid").val(),
                group_name: $("#txtGroupName").val(),
                select_point_ind: pt_ind,
                remove_point_ind: rem_ind,
                red_from: $("#txtRedfrom").val(),
                red_to: $("#txtRedto").val(),
                amber_from: $("#txtAmberfrom").val(),
                amber_to: $("#txtAmberto").val(),
                green_from: $("#txtGrnfrom").val(),
                green_to: $("#txtGrnto").val(),
                purple_from: $("#txtPurplefrom").val(),
                purple_to: $("#txtPurpleto").val(),
                blue_from: $("#txtbluefrom").val(),
                blue_to: $("#txtblueto").val()
            };
            MakeAjaxCall(url, data, saveQAGrp);
}

function saveQAGrp(data)
{
    grpChanged = 0;
    if (data.error) {
        $("#btnSave_QAGrp").text('Save').attr("disabled", false);
        $("#lblgrps_err").text(data.error_msg).show();
    }
    else
    {
        $("#btnSave_QAGrp").text('Saved').attr("disabled", true);
        showGroups(1);
        //get_acctgrp_srv($("#txthdnacid").val()); // Group Table
    }
}

function showGroups(st) {
    $("#frmEditGrp").data('validator').resetForm();
    $(".error").removeClass("error");
    $("#lblgrps_err").hide();
    $("#lblrag_err").hide();
    $("#txtGroupName").val("");
    $("#txtRedfrom").val("");
    $("#txtRedto").val("");
    $("#txtAmberfrom").val("");
    $("#txtAmberto").val("");
    $("#txtGrnfrom").val("");
    $("#txtGrnto").val("");
    $("#txtPurplefrom").val("");
    $("#txtPurpleto").val("");
    $("#txtbluefrom").val("");
    $("#txtblueto").val("");
    $("#divtblAccountlist").hide();
    $("#divtblGroupslist").show();
    $("#divBtnAddAccount").hide();
    $("#divBtnAddGroup").show();
    $("#divAddGroup").hide();
    $("#divAccountList").show();
    $("#bcHome").empty();
    $("#bcHome").append("<li>You are here: <a href='javascript:showAccounts()'>Quality Audit</a></li>");
    $("#bcHome").append("<li><span class='divider'>/</span>&nbsp;Groups</li>");
    /*if ($("#txthdngrpcnt").val() == 0)
        loadQAAccounts_Srv(); // Account Table
    else
        get_acctgrp_srv($("#txthdnacid").val()); // Group Table*/
    if (st == 1) {
        get_acctgrp_srv($("#txthdnacid").val());
    } else {
        if ($("#txthdngrpcnt").val() == 0)
            loadQAAccounts_Srv();
        else
            get_acctgrp_srv($("#txthdnacid").val()); 
    }    
}

function calculateRGBPercentage() {
    var rlist = [], alist = [], glist = [], plist = [], blist = [];
    if (($("#txtRedfrom").val() != "") || ($("#txtRedto").val() != ""))
        rlist = calculateRGB($("#txtRedfrom").val(), $("#txtRedto").val(), 1);
    if (($("#txtAmberfrom").val() != "") || ($("#txtAmberto").val() != ""))
        alist = calculateRGB($("#txtAmberfrom").val(), $("#txtAmberto").val(), 1);
    if (($("#txtGrnfrom").val() != "") || ($("#txtGrnto").val() != ""))
        glist = calculateRGB($("#txtGrnfrom").val(), $("#txtGrnto").val(), 1);
    if (($("#txtPurplefrom").val() != "") || ($("#txtPurpleto").val() != ""))
        plist = calculateRGB($("#txtPurplefrom").val(), $("#txtPurpleto").val(), 1);
    if (($("#txtbluefrom").val() != "") || ($("#txtblueto").val() != ""))
        blist = calculateRGB($("#txtbluefrom").val(), $("#txtblueto").val(), 1);
    var rgbsts = "0";
    //console.log(rlist);
    if (alist != undefined)
    {
        $.each(alist, function(i, val) {
            if ($.inArray(val, rlist) < 0)
                rlist.push(val);
            else
            {
                rgbsts = "1";
                return false;
            }
        });
    }
    if ((rgbsts == "0") && (glist != undefined))
    {
        $.each(glist, function(i, val) {
            if ($.inArray(val, rlist) < 0)
                rlist.push(val);
            else
            {
                rgbsts = "1";
                return false;
            }
        });
    }
    if ((rgbsts == "0") && (plist != undefined))
    {
        $.each(plist, function(i, val) {
            if ($.inArray(val, rlist) < 0)
                rlist.push(val);
            else
            {
                rgbsts = "1";
                return false;
            }
        });
    }
    if ((rgbsts == "0") && (blist != undefined))
    {
        $.each(blist, function(i, val) {
            if ($.inArray(val, rlist) < 0)
            {
                rlist.push(val);
            }
            else
            {
                rgbsts = "1";
                return false;
            }
        });
    }
    if (rgbsts == "1")
        return false;
    else
    {
        rlist.sort(function(a, b) {
            return a - b
        });
        if ((rlist.length == 101) && (rlist[0] == 0) && (rlist[100] == 100))
            return true;
        else
            return false;
    }
}

function calculateRGB(from, to, step) {
    from = parseInt(from);
    to   = parseInt(to);

    if (isNaN(from) || isNaN(to) ) {
         var A = [-1];
         return A;
    } else if (typeof from == 'number') {
        if ((from < 0) || (to < 0)) {
            var A = [-1];
            return A;
        } else {
             var A = [from];
            step = typeof step == 'number' ? Math.abs(step) : 1;
            if (from > to) {
                while ((from -= step) >= to)
                    A.push(from);
            }
            else {
                while ((from += step) <= to)
                    A.push(from);
            }
            return A;
        }       
    }
}

function delete_group(gid, aid)
{
    bootbox.dialog({
        message: "Are you sure you want to delete this Group?",
        title: "Delete Group?",
        buttons: {
            danger: {
                label: "Cancel",
                className: "btn",
                callback: function() {
                }
            },
            success: {
                label: "Delete",
                className: "btn btn-primary",
                callback: function() {
                    localStorage["btn_click"] = "btn_grp_del";
                    var url = BACKENDURL + "customeradmin/delete_qa_group";
                    var data = {
                        session_id: localStorage["SESSIONID"],
                        account_id: aid,
                        group_id: gid
                    };
                    MakeAjaxCall(url, data, del_grp_conf);
                }
            }
        }
    });
}

function del_grp_conf(data)
{
    if (data.error == false)
        get_acctgrp_srv($("#txthdnacid").val());        
}

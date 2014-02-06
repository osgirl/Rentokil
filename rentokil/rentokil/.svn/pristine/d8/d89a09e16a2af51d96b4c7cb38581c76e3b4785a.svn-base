$(document).ready(function() {
    $.support.cors = true;
    $("#divProfileEdit").load("commonmyprofile.html", function() {
        $("#divLoggedAs").html(" You are administering " + localStorage.getItem("contractname"));
        //  $("#divcontainer").load(loadFileName+"?x="+(new Date()).getTime(), function () {
        $("#divcontainer").load(loadFileName, function() {
            $("#caheader").load("caheader.html", function() {
                $('#btnLogout').bind("click", logout);
                $("#btnManageContract").click(function() {
                    $("#CreateNewContract").val('');
                    $("#SelectOrCreateContractNewLabel").text('').removeClass('alert alert-error');
                });

                $("#divProfileEdit").load("commonmyprofile.html", function() {
                    $("#btnProfileEdit").click(function() {
                        $("#txtUserSchoolAdminFName", "#divProfileEdit").val((localStorage["FNAME"] == "undefined") ? "" : localStorage["FNAME"]);
                        $("#txtUserSchoolAdminLName", "#divProfileEdit").val((localStorage["LNAME"] == "undefined") ? "" : localStorage["LNAME"]);
                        $("#txtUserSchoolAdminemail", "#divProfileEdit").val((localStorage["EMAIL"] == "undefined") ? "" : localStorage["EMAIL"]);
                        $("#txtUserSchoolAdminphone", "#divProfileEdit").val((localStorage["TELEPHONE"] == "null") ? "" : localStorage["TELEPHONE"]);
                        $("#txtAdminWorkTelephone", "#divProfileEdit").val((localStorage["WorkTel"] == "null") ? "" : localStorage["WorkTel"]);
                        $("#txtAdminMobileNumber", "#divProfileEdit").val((localStorage["MobileTel"] == "null") ? "" : localStorage["MobileTel"]);
                        if (localStorage["ChkEmail"] == 1)
                            $("#chkAdminEmail", "#divProfileEdit").attr('checked', true);
                        else
                            $("#chkAdminEmail", "#divProfileEdit").attr('checked', false);
                        if (localStorage["ChkSms"] == 1)
                            $("#chkAdminSms", "#divProfileEdit").attr('checked', true);
                        else
                            $("#chkAdminSms", "#divProfileEdit").attr('checked', false);
                        LoadProfileData_Admin();
                        // Update Profile 
                        //changed for detecting paste event along with keypress
                        $("#txtAdminOldpwd", "#frmUpdateAdminProfile").on('paste keypress', function() {
                            $("#txtAdminnewpwd", "#frmUpdateAdminProfile").removeAttr("disabled");
                            $("#txtAdminrenternewpwd", "#frmUpdateAdminProfile").removeAttr("disabled");
                        });

                        $("#txtAdminOldpwd", "#frmUpdateAdminProfile").keyup(function() {
                            if ($("#txtAdminOldpwd", "#frmUpdateAdminProfile").val().length == 0) {
                                $("#txtAdminnewpwd", "#frmUpdateAdminProfile").attr('disabled', 'disabled');
                                $("#txtAdminrenternewpwd", "#frmUpdateAdminProfile").attr('disabled', 'disabled');
                            }
                        });
                        $("#txtAdminnewpwd", "#frmUpdateAdminProfile").keypress(function() {
                            //$("#pwdStrengthify").addClass("span3 offset2");
                            $("#pwdStrengthify").css("display", "inline-block");
                        });
                        $('input[id=txtAdminnewpwd]', "#frmUpdateAdminProfile").passStrengthify();
                        $.ajax({
                            url: BACKENDURL + "common/get_data_any",
                            type: "post",
                            data: {
                                session_id: localStorage["SESSIONID"],
                                data_ref: "user_titles"
                            },
                            dataType: "json",
                            crossDomain: true,
                            success: function(data) {
                                if (data.session_status) {
                                    if (data.error == 0) { // session true error false
                                        $("#ddlUserTitle", "#divProfileEdit").empty();
                                        var titleVal;
                                        for (var nCount = 0; nCount < data.data_any_res.length; nCount++) {
                                            var selectedText = (data.data_any_res[nCount].data_value == localStorage["TITLE"]) ? "selected" : "";
                                            titleVal = "<option value=" + data.data_any_res[nCount].data_value_id + " " + selectedText + " >" + data.data_any_res[nCount].data_value + "</option>";
                                            $("#ddlUserTitle", "#divProfileEdit").append(titleVal);
                                        }
                                        $("#txtUserSchoolAdminFName", "#divProfileEdit").val((localStorage["FNAME"] == "undefined") ? "" : localStorage["FNAME"]);
                                        $("#txtUserSchoolAdminLName", "#divProfileEdit").val((localStorage["LNAME"] == "undefined") ? "" : localStorage["LNAME"]);
                                        $("#txtUserSchoolAdminemail", "#divProfileEdit").val((localStorage["EMAIL"] == "undefined") ? "" : localStorage["EMAIL"]);
                                        $("#txtUserSchoolAdminphone", "#divProfileEdit").val((localStorage["TELEPHONE"] == "null") ? "" : localStorage["TELEPHONE"]);
                                        $("#txtAdminWorkTelephone", "#divProfileEdit").val((localStorage["WorkTel"] == "null") ? "" : localStorage["WorkTel"]);
                                        $("#txtAdminMobileNumber", "#divProfileEdit").val((localStorage["MobileTel"] == "null") ? "" : localStorage["MobileTel"]);
                                        if (localStorage["ChkEmail"] == 1)
                                            $("#chkAdminEmail", "#divProfileEdit").attr('checked', true);
                                        else
                                            $("#chkAdminEmail", "#divProfileEdit").attr('checked', false);
                                        if (localStorage["ChkSms"] == 1)
                                            $("#chkAdminSms", "#divProfileEdit").attr('checked', true);
                                        else
                                            $("#chkAdminSms", "#divProfileEdit").attr('checked', false);
                                        $("#btnUserProfileSubmit", " #divProfileEdit").bind("click", updateContactDetails);
                                        $("#xNewCustomerAdmin", "#divProfileEdit").bind("click", clearContactDetails);
                                        $("#xcloseCustomerAdmin", "#divProfileEdit").bind("click", clearContactDetails);
                                    } else {
                                        alert(data.error_msg);
                                    }
                                } else {
                                    localStorage.clear();
                                    location.href = 'index.html';
                                }
                            },
                            error: function(xhr, textStatus, error) {
                                alert(error);
                            }
                        });
                    });
                });
                $("#btnManageContract").click(function() {
                    (localStorage["cacontract_status"] == 0) ? $("#CreateContractbtnCreate").attr('disabled', true) : $("#CreateContractbtnCreate").attr('disabled', false);
                    $.ajax({
                        url: BACKENDURL + "customeradmin/get_contracts",
                        type: "post",
                        data: {
                            session_id: localStorage["SESSIONID"]
                        },
                        dataType: "json",
                        crossDomain: true,
                        success: function(data) {
                            if (data.session_status) {
                                if (data.error == 0) { // session true error false
                                    $("#selectContract").empty();
                                    var contractVal;
                                    for (var nCount = 0; nCount < data.contracts_res.length; nCount++) {
                                        contractVal = "<option value='" + data.contracts_res[nCount].contract_id + "_" + data.contracts_res[nCount].contract_key + "_" + data.contracts_res[nCount].session_log + "'>" + data.contracts_res[nCount].contract_name + "</option>";
                                        $("#selectContract").append(contractVal);
                                    }
                                    $("#btnGo", " #selectCreate").off('click').bind("click", Gobtn);
                                    $("#CreateContractbtnCreate", "#selectCreate").off('click').bind("click", createcontractBtn);
                                } else {
                                    //alert(error);
                                }
                            } else {
                                localStorage.clear();
                            }
                        },
                        error: function(xhr, textStatus, error) {
                            //alert(error);
                        }
                    });
                });

                $("#selectCreate").load("camanagecontract.html", function() {
                });
                $("#ContractName").html(localStorage.getItem("contractname"));
                $("#cafooter").load("cafooter.html");
                $("#divWelcome").html("Welcome " + localStorage["FNAME"] + "  " + localStorage["LNAME"]);
                $("#divLoggedAs").html(" You are administering " + localStorage["contractname"]);
            })
            //This is for specific to page and need to define in every page.
            LoadPageData();
        });

    });
});

//updating profile

function updateContactDetails() {
    if ($("#txtAdminOldpwd", "#divProfileEdit").val() != "")
    {
        if ($("#txtAdminnewpwd", "#divProfileEdit").val() == "")
        {
            $("#updateProfileLabelAdmin").text("Please enter new password");
            return false;
        }
        else
        {
            $("#updateProfileLabelAdmin").text("");
        }
    }
    if (($("#txtAdminOldpwd", "#divProfileEdit").val() == $("#txtAdminnewpwd", "#divProfileEdit").val()) && ($("#txtAdminOldpwd", "#divProfileEdit").val() != ""))
    {
        $("#updateProfileLabelAdmin").text("New password can not be the same as the old password");
        return false;
    }
    if ($("#frmUpdateAdminProfile").valid()) {
        $.ajax({
            url: BACKENDURL + "common/edit_profile",
            type: "post",
            data: {
                session_id: localStorage["SESSIONID"],
                user_id: localStorage["USERID"],
                title_id: $("#ddlUserTitle").val(),
                title: $("#ddlUserTitle :selected", "#divProfileEdit").text(),
                first_name: $("#txtUserSchoolAdminFName", "#divProfileEdit").val(),
                last_name: $("#txtUserSchoolAdminLName", "#divProfileEdit").val(),
                user_email: $("#txtUserSchoolAdminemail", "#divProfileEdit").val(),
                telephone: $("#txtUserSchoolAdminphone", "#divProfileEdit").val(),
                work_tel: $("#txtAdminWorkTelephone", "#divProfileEdit").val(),
                mobile_tel: $("#txtAdminMobileNumber", "#divProfileEdit").val(),
                current_pwd: escape($("#txtAdminOldpwd", "#divProfileEdit").val()),
                new_pwd: escape($("#txtAdminnewpwd", "#divProfileEdit").val()),
                renew_pwd: escape($("#txtAdminrenternewpwd", "#divProfileEdit").val()),
                check_email: ($("#chkAdminEmail", "#divProfileEdit").is(":checked") ? 1 : 0),
                check_sms: ($("#chkAdminSms", "#divProfileEdit").is(":checked") ? 1 : 0)
            },
            dataType: "json",
            crossDomain: true,
            success: function(data) {
                if (data.session_status) {
                    if (data.error == 0) { // session true error false

                        $("#editBtnClose").load("commonsuccessmodal.html", function() {
                            $("#headermsg").html("Update settings");
                            $("#sucmsg").html("Success, you've succesfully updated your profile settings!");
                        });
                        localStorage["TITLE"] = $("#ddlUserTitle :selected", "#divProfileEdit").text();
                        localStorage["FNAME"] = $("#txtUserSchoolAdminFName", "#divProfileEdit").val();
                        localStorage["LNAME"] = $("#txtUserSchoolAdminLName", "#divProfileEdit").val();
                        localStorage["EMAIL"] = $("#txtUserSchoolAdminemail", "#divProfileEdit").val();
                        localStorage["TELEPHONE"] = $("#txtUserSchoolAdminphone", "#divProfileEdit").val();
                        localStorage["WorkTel"] = $("#txtAdminWorkTelephone", "#divProfileEdit").val();
                        localStorage["MobileTel"] = $("#txtAdminMobileNumber", "#divProfileEdit").val();
                        localStorage["ChkEmail"] = ($("#chkAdminEmail", "#divProfileEdit").is(":checked") ? 1 : 0);
                        localStorage["ChkSms"] = ($("#chkAdminSms", "#divProfileEdit").is(":checked") ? 1 : 0);
                        $("#pwdStrengthify").css("display", "none");
                        $("#updateProfileLabelAdmin").text("");
                        // updating the headwer with the current name
                        $("#divWelcome").html("Welcome " + localStorage["FNAME"] + " " + localStorage["LNAME"]);
                    } else { // session true error true
                        $("#txtAdminOldpwd", "#divProfileEdit").val("");
                        $("#txtAdminnewpwd", "#divProfileEdit").val("");
                        $("#txtAdminrenternewpwd", "#divProfileEdit").val("");
                        $("#pwdStrengthify").css("display", "none");
                        $("#updateProfileLabelAdmin").text("");
                        $("#txtAdminnewpwd", "#frmUpdateAdminProfile").attr('disabled', 'disabled');
                        $("#txtAdminrenternewpwd", "#frmUpdateAdminProfile").attr('disabled', 'disabled');
                        $("#editBtnClose").load("commonFailuremodal.html", function() {
                            $("#headermsg").html("Update settings");
                            $("#sucmsg").html("Failure," + data.error_msg);
                        });
                        //alert(data.error_msg);
                        //alert(data.error);
                    }

                } else {
                    localStorage.clear();
                    location.href = 'index.html';
                }
            },
            error: function(xhr, textStatus, error) {

                alert(error);
            }
        });
    } else
        return false;
}

function Gobtn() {
    localStorage.setItem("contractid", " ");
    var contArr = $("#selectContract").val().split('_');
    localStorage.setItem("contractid", contArr[0]);
    localStorage.setItem("contractkey", contArr[1]);
    localStorage["Session_logs"] = contArr[2];
    localStorage["contractname"] = $("#selectContract :selected").text();
    $("#ContractName").html($("#selectContract :selected").text());
    $("#divLoggedAs").html("You are administering " + $("#selectContract :selected").text());
    $("#xNewSelectCreate", "#selectCreate").click();
    var url = BACKENDURL + "customeradmin/save_contract_session";
    var data = {
        session_id: localStorage["SESSIONID"],
        contract_id: localStorage["contractid"]
    };
    MakeAjaxCall(url, data, SaveManageContractSession);

}
function SaveManageContractSession(data) {
    if (data.session_status) {
        if (data.error == 0) {
            location.href = "customerpage.html";
        }
        else
        {
            $("#SelectOrCreateContractNewLabel").text(data.error_msg).show();
        }
    }
}

function createcontractBtn() {
    if ($("#CreateNewContract", "#selectCreate").val() == "") {
        $("#SelectOrCreateContractNewLabel").text("Please enter contract name").addClass('alert alert-error');
        return false;
    } else {
        $.ajax({
            url: BACKENDURL + "customeradmin/create_contract",
            type: "post",
            data: {
                session_id: localStorage["SESSIONID"],
                customer_id: localStorage["CUSTOMERID"],
                user_id: localStorage["USERID"],
                contract_name: $("#CreateNewContract", "#selectCreate").val()
            },
            dataType: "json",
            crossDomain: true,
            success: function(data) {
                if (data.session_status) {
                    if (data.error == 0) { // session true error 					
                        $("#selectCreate").modal('hide');
                        localStorage.setItem("contractid", " ");
                        localStorage.setItem("contractid", data.contract_id);
                        localStorage.setItem("contractkey", data.contract_key);
                        localStorage["contractname"] = $("#CreateNewContract", "#selectCreate").val();
                        //getSchool();
                        $("#ContractName").html($("#CreateNewContract", "#selectCreate").val());
                        $("#divLoggedAs").html("You are administering " + $("#CreateNewContract", "#selectCreate").val());
                        location.href = "customerpage.html";
                    } else {
                        if (data.error_msg == "Unauthorized access.")
                            logout(1)
                        else {
                            var NewLabel = data.error_msg;
                            $("#SelectOrCreateContractNewLabel").text(NewLabel).addClass('alert alert-error');
                        }
                    }
                } else
                    logout();
            },
            error: function(xhr, textStatus, error) {
                alert(error);
            }
        });
    }
}

function LoadProfileData_Admin() {
    $("#frmUpdateAdminProfile").validate({
        rules: {
            txtUserSchoolAdminFName: {
                regAlphaNumeric: true,
                required: true
            },
            txtUserSchoolAdminLName: {
                regAlphaNumeric: true,
                required: true
            },
            txtUserSchoolAdminemail: {
                required: true,
                customemail: true
            },
            txtUserSchoolAdminphone: {
                regTelephone: true
            },
            txtAdminWorkTelephone: {
                regTelephone: true
            },
            txtAdminMobileNumber: {
                regTelephone: true
            },
            txtAdminnewpwd: {
                regPwd: true
            },
            txtAdminrenternewpwd: {
                equalTo: "#txtAdminnewpwd"
            }
        },
        messages: {
            txtUserSchoolAdminFName: {
                required: "Please enter the first name"
            },
            txtUserSchoolAdminLName: {
                required: "Please enter last name"
            },
            txtUserSchoolAdminemail: {
                required: "Please enter valid email ID"
            },
            txtAdminrenternewpwd: {
                equalTo: "New and confirm passwords are not the same"
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
        }
    });
}

function clearContactDetails() {
    //location.reload();
    $("#txtAdminOldpwd", "#divProfileEdit").val("");
    $("#txtAdminnewpwd", "#divProfileEdit").val("");
    $("#txtAdminrenternewpwd", "#divProfileEdit").val("");
    $("#pwdStrengthify").css("display", "none");
    $("#updateProfileLabelAdmin").text("");
    $("#txtAdminnewpwd", "#frmUpdateAdminProfile").attr('disabled', 'disabled');
    $("#txtAdminrenternewpwd", "#frmUpdateAdminProfile").attr('disabled', 'disabled');
    if ($('#newpass').hasClass('control-group error'))
    {
        $('#newpass').removeClass("control-group error").addClass('control-group');
    }
    if ($('#repass').hasClass('control-group error'))
    {
        $('#repass').removeClass("control-group error").addClass('control-group');
        $('#repass span:first').remove();
    }
}

function customerModuleAccess(modObjKey, n)
{
    //localStorage["caprofile"] = ["AL3DBD","AL3USR","AL3ADM","AL3HSET","AL3PRO","AL3SKN","AL3SLG","AL3PHP","AL3PIMP","AL3PDOC","AL3PSET","AL3ACCT","AL4REPT","AL4CDRS","AL4CDRH","AL3SCOF","AL4MYSC","AL4MORD","AL3SCON","AL4SVYS","AL4MNUS","AL3SETD","AL3SETD","AL3HHD","AL3NHHD","AL3PRG","AL3BDOC","AL3ZDBD","AL3ADBD"]
    if ((localStorage["ROLEID"] == 2) && (localStorage["caprofile_status"] == 1)) {
        if (localStorage["caprofile"].match(modObjKey) == null) //AD_PROF_MOD
        {
            $("#divProfileNotify").removeClass("hide");
            localStorage['AccessAllowed'] = 0; 
            switch (modObjKey)
            {
                case 'AL3USR' :
                    customerModuleUsers(n);
                    break;
                case 'AL3PRO':
                    customerModuleProfile();
                    break;
                case 'AL3SKN':
                    customerModuleSkin();
                    break;
                case 'AL3ADM':
                    customerModuleAdmnistrators();
                    break;
                case 'AL3HSET':
                    customerModuleSettings();
                    break;
                case 'AL3SLG':
                    customerModuleSessionlogs();
                    break;
                case 'AL4SVYS':
                    customerModuleServery();
                    break;
                case 'AL3PIMP':
                    customerModulePupilImport();
                    break;
                case 'AL3PDOC':
                    customerModulePplDocuments(n);
                    break;
                case 'AL3PSET':
                    customerModulePplSettings();
                    break;
                case 'AL3SED':
                    BldSrv_Setup();
                    break;
                case 'AL3SETD':
                    BldSrv_TargetData();
                    break;
                case 'AL3HHD':
                    BldSrv_HHData();
                    break;
                case 'AL3NHHD':
                    BldSrv_NHHData();
                    break;
                case 'AL3PRG':
                    BldSrv_PurgeData();
                    break;
                case 'AL3BDOC':
                    BldSrv_Documents();
                    break;
                case 'AL4CDRS':
                    PPlSrv_CardRef();
                    break;
                case 'AL4MYSC':
                    PPlSrv_mySchools();
                    break;
                case 'AL3SCON':
                    PPlSrv_SchoolConfig(n);
                    break;
                case 'AL4MNUS':
                    PPlSrv_Menus();
                    break;
                case 'AL3ZDBD':
                    customermoduleZoneDashboard();
                    break;
                case 'AL3ADBD':
                    customermoduleAssetDashboard();
                    break;
                case 'AL3DIGPEN':
                    customermoduleDigitalPen(n);
                    break;
                case 'AL3QTYAUD':
                    customermoduleAccountTable();
                    break;
            }
        }
        else
        {
            $("#divProfileNotify").addClass("hide");
            localStorage['AccessAllowed'] = 1; 
        }

    }
}

/***** start --- Building Services ******/
function BldSrv_Setup()
{
    $("#btnImport", "#setup").attr('disabled', true);
}

function BldSrv_TargetData()
{
    $("#btnImport", "#targetdata").attr('disabled', true);
}

function BldSrv_HHData()
{
    $("#btnImport", "#hhreports").attr('disabled', true);
}

function BldSrv_NHHData()
{
    $("#btnImport", "#nhhreports").attr('disabled', true);
}

function BldSrv_PurgeData()
{
    $("#btnPurge", "#divPurgedata").attr('disabled', true);
}

function BldSrv_Documents()
{
    $("#btnImport", "#documents").attr('disabled', true);
    $("a[id^=adminbuildingupdate]").attr({
        "disabled": true,
        "href": "#"
    });
    $("a[id^=adminbuildingupdate]").removeAttr("onclick").unbind("click");
    $("a[id^=adminbuildingdelete]").attr({
        "disabled": true,
        "href": '#'
    });
    $("a[id^=adminbuildingdelete]").removeAttr("onclick").unbind("click");
}

/***** End--- Building Services ******/

/**** Start --- People Service *******/
function PPlSrv_CardRef()
{
    $("#btnRefundsGo", "#validationgobutton").css('display', 'none');
    $("#btnRefundsGo_temp", "#validationgobutton").css('display', 'inline');
}

function PPlSrv_mySchools()
{
    $("#btnSaveSchool", "#SchoolTab").css('display', 'none');
    $("#btnSaveSchool_temp", "#SchoolTab").css('display', 'inline');
}

function PPlSrv_SchoolConfig(state)
{
    if (state == "1")
    {
        $("button[id^=AddschoolDisablebtn]").attr({
            "href": "#",
            "disabled": true
        });
        $("#btnSave", "#schoolconfig").css('display', 'none');
        $("#btnSave_temp", "#schoolconfig").css('display', 'inline');
        $("#btnAddSchool", "#schoolconfig").attr({
            "href": "#",
            "disabled": true
        });
    }
    else
    {
        $("#btnAddSchool", "#schoolconfig").attr({
            "href": "#",
            "disabled": true
        });
    }
}

function PPlSrv_Menus()
{
    $("button[id^=btnOSt_]").removeAttr("onclick").unbind("click");
    $("button[id^=btnOSt_]").attr("disabled", true);
    $("button[id^=btnContinue_]").css('display', 'none');
    $("button[id^=btnConttemp_]").css('display', 'inline');
    $("#btnSaveMenus").css('display', 'none');
    $("#btnSaveMenus_tmp").css('display', 'inline');
}
/**** End --- People Service *******/

function customerModuleUsers(n)
{
    if (n == 0) {
        $('#btnNewUser').removeAttr("onclick").unbind("click");
        $('#btnNewUser').attr({
            "disabled": true,
            "href": "#"
        });
    } else {
        $("a[id^=btnDisableUser]").attr({
            "disabled": true,
            "href": "#"
        });
        $("a[id^=btnDisableUser]").removeAttr("onclick").unbind("click");
    }
}

function customerModuleProfile()
{
    $("#btnCreateProfile", "#NewProfile").attr({
        "href": "#",
        "disabled": true
    });
    $("#btnCreateProfile", "#ProfileExist").attr({
        "href": "#",
        "disabled": true
    });
    $("#btnProfileDelete").attr({
        "disabled": true
    });
    $("#btnProfileSave").css({
        "display": "none"
    });
    $("#btnProfileSave_temp").css({
        "display": "inline"
    });

}

function customerModuleSkin()
{
    $("#btnCreateSkin", "#NewSkin").attr({
        "href": "#",
        "disabled": true
    });
    $("#btnCreateSkin", "#SkinExist").attr({
        "href": "#",
        "disabled": true
    });
    $("#btnSkinDelete").attr({
        "disabled": true
    });
    $("#btnSkinSave").css({
        "display": "none"
    });
    $("#btnSkinSave_temp").css({
        "display": "inline"
    });
    $(".reset-button").attr({
        "disabled": true
    });
}

function customerModuleAdmnistrators()
{
    //$('#btnAddAdmin').attr('disabled',true);
    //$('#btnRemoveAdmin').attr('disabled',true);
    $('#btnSaveContractAdmin').css('display', 'none');
    $('#btnSaveContractAdminTemp').css('display', 'inline');
}

function customerModuleSettings()
{
    $('#btnSaveContractSettings').css('display', 'none');
    $('#btnSaveContractSettingsTemp').css('display', 'inline');
}

function customerModuleSessionlogs()
{
    $("#btnSessPurge").css({
        "display": "none"
    });
    $("#btnSessPurge_temp").css({
        "display": "inline"
    });
    $("#btnSessionSave").css({
        "display": "none"
    });
    $("#btnSessionSave_temp").css({
        "display": "inline"
    });
    $('#btnSessionSave').attr('onclick', '').unbind('click');
}

function customerModuleServery()
{

}

function customerModulePupilImport()
{
    $('#btnImportPupil').attr({
        "disabled": true,
        "href": "#"
    });
    $('#btnImportPupil').removeAttr("onclick").unbind("click");
}

function customerModulePplDocuments(n)
{
    if (n == 0) {
        $('#btnUploadNewImport').attr('disabled', true);
    } else {
        $('a[id^=adminupdate]').attr({
            "disabled": true,
            "href": "#"
        });
        $("a[id^=adminupdate]").removeAttr("onclick").unbind("click");
        $("a[id^=admindelete]").attr({
            "disabled": true,
            "href": "#"
        });
        $("a[id^=admindelete]").removeAttr("onclick").unbind("click");
    }
}

function customerModulePplSettings()
{
    $('#btnSaveSettings').css('display', 'none');
    $("#btnSaveSettingsTemp").css({
        "display": "inline"
    });
}
function customermoduleZoneDashboard()
{
$("button[id^=btn_edit]").attr("disabled",true);
$("button[id^=btn_delete]").attr("disabled",true);
$('#btnNewZone').attr({
        "disabled": true,
        "href": "#"
    });
    $('#btnNewZone').removeAttr("onclick").unbind("click");
	
        //$("button[id^=btn_graph]").removeAttr("onclick").unbind("click");
	 
}
function customermoduleAssetDashboard()
{
 $("a[id^=btn_edit_asset]").removeAttr("onclick").unbind("click");
        $("a[id^=btn_edit_asset]").attr({
            "disabled": true,
            "href": "#"
        });
$("a[id^=btn_delete_asset]").removeAttr("onclick").unbind("click");
        $("a[id^=btn_delete_asset]").attr({
            "disabled": true,
            "href": "#"
        });
$('#btnNewAsset').attr({
        "disabled": true,
        "href": "#"
    });
    $('#btnNewAsset').removeAttr("onclick").unbind("click");
}
function customermoduleDigitalPen(n)
{
    
if (n == 0) { // Digital App
    $("button[id^=btnapp]").removeAttr("onclick").unbind("click");
        $("button[id^=btnapp]").attr({
            "disabled": true,
            "href": "#"
        });
    $("button[id^=btnapp_edit]").removeAttr("onclick").unbind("click");
        $("button[id^=btnapp_edit]").attr({
            "disabled": true,
            "href": "#"
        });
} else {  // Digital Pen
     $("button[id^=btnpen_del]").removeAttr("onclick").unbind("click");
        $("button[id^=btnpen_del]").attr({
            "disabled": true,
            "href": "#"
        });
    $("button[id^=btnpen_edit]").removeAttr("onclick").unbind("click");
        $("button[id^=btnpen_edit]").attr({
            "disabled": true,
            "href": "#"
        });
    
}
    $('#btnAddDigitalApp').attr({
        "disabled": true,
        "href": "#"
    });
    
    $('#btnAddDigitalApp').removeAttr("onclick").unbind("click");
	
    $('#btnAddDigitalPen').attr({
        "disabled": true,
        "href": "#"
    });
    
    $('#btnAddDigitalPen').removeAttr("onclick").unbind("click");
}
function customermoduleAccountTable()
{
	$('#btnAddAccount').attr({
        "disabled": true,
        "href": "#"
    });
	$('button[id^=btnacc_del]').removeAttr('onclick').unbind('click');
        $('button[id^=btnacc_del]').attr({
            "disabled": true,
            "href": "#"
    });
	$('button[id^=btnacc_edit]').removeAttr('onclick').unbind('click');
        $('button[id^=btnacc_edit]').attr({
            "disabled": true,
            "href": "#"
    });
	$('button[id^=btngroup_edit]').removeAttr('onclick').unbind('click');
        $('button[id^=btngroup_edit]').attr({
            "disabled": true,
            "href": "#"
    });
	$('button[id^=btngroup_del]').removeAttr('onclick').unbind('click');
        $('button[id^=btngroup_del]').attr({
            "disabled": true,
            "href": "#"
    });
	$('#btnAddGroup').attr({
        "disabled": true,
        "href": "#"
    });
}

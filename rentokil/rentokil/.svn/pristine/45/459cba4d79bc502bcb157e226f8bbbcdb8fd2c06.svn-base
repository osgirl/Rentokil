var ruleTelephoneNum = {
    regex: /^[0-9 ]+$/
};
var regAddress = {
    regex: /^[a-zA-Z0-9-\/] ?([a-zA-Z0-9-\/]|[a-zA-Z0-9-\/] )*[a-zA-Z0-9-\/]$/
};
var addressErrorMsg = {
    regex: "Please provide a valid address"
};
var regCity = {
    regex: /^[a-zA-z] ?([a-zA-z]|[a-zA-z] )*[a-zA-z]$/
};
var cityErrorMsg = {
    regex: "Please provide a valid city"
};
var countryErrorMsg = {
    regex: "Please provide a valid country"
};
var regschoolId = {
    regex: /^[a-zA-Z0-9/]+$/
};
var regschoolMsg = {
    regex: "Please provide a valid school ID"
};
var minimumcardpaymentrule = {
    regex: /^[0-9]+([\,\.][0-9]+)?$/g
};
var minimumcardpaymentMsg = {
    regex: "Please enter a valid number"
};
var unspecifiedcostrule = {
	    regex: /^[0-9]+([\,\.][0-9]+)?$/g
	};
var unspecifiedcostMsg = {
	    regex: "Please enter a valid number"
	};
var vatrule = {
    regex: /(^100(\.0{1,2})?$)|(^([1-9]([0-9])?|0)(\.[0-9]{1,2})?$)/
};
var vatMsg = {
    regex: "Please enter VAT amount"
};
var dcfrule = {
    regex: /^[0-9]+([\,\.][0-9]+)?$/g
};
var dcfMsg = {
    regex: "Please enter debit card fee"
};
var ccfrule = {
    regex: /^[0-9]+([\,\.][0-9]+)?$/g
};
var ccfMsg = {
    regex: "Please enter credit card fee"
};
var page_num = 1;
$.validator.addMethod("validateDupYear", function(value, element) {
    var v = $(element).val();
    for (var i = 0; i < 7; i++) {
        if ($(element).attr("id") != $("#txtY" + i).attr("id") && v.toLowerCase() == $("#txtY" + i).val().toLowerCase())
            return false;
    }
    return true;
}, "Please enter a unique year label.");
$.validator.addMethod("validateDupClass", function(value, element) {
    var id = $(element).attr("id").substr(4, 1);
    var v = $(element).val();
    for (var i = 1; i < 7; i++) {
        if ($(element).attr("id") != $("#txtY" + id + "C" + i).attr("id") && v.toLowerCase() == $("#txtY" + id + "C" + i).val().toLowerCase())
            return false;
    }
    return true;
}, "Please enter a unique class name for this year group.");
var regAlphaNumericReqYear = {
    required: true,
    validateDupYear: true,
    regex: /^[A-Za-z0-9 _.-]*[A-Za-z0-9][A-Za-z0-9 _.-]*$/
};
var regAlphaNumericReq = {
    required: true,
    validateDupClass: true,
    regex: /^[A-Za-z0-9 _.-]*[A-Za-z0-9][A-Za-z0-9 _.-]*$/
};
var regAlphaNumeric = {
    regex: /^[A-Za-z0-9 _.-]*[A-Za-z0-9][A-Za-z0-9 _.-]*$/
};
var OffErrorMsg = {
    regex: "Please provide a valid name"
};
var YearErrorMsg = {
    regex: "Please enter a valid year label"
};
var ClassErrorMsg = {
    regex: "Please enter a valid class name"
};
var regPostalCode = {
    regex: /^([A-PR-UWYZ0-9][A-HK-Y0-9][AEHMNPRTVXY0-9]?[ABEHMNPRVWXY0-9]? {1,2}[0-9][ABD-HJLN-UW-Z]{2}|GIR 0AA)$/i
};
var postalCodeErrorMsg = {
    regex: "Please provide a valid postcode"
};
var ruleTelephoneErrorMsg = {
    regex: "Please enter a valid phone number"
};
var minValue;
var Sett_Confirm = [];
localStorage["PSChng"] = "0";
//on load dynamic updation of contract name from db
$(document).ready(function() {
    $("#tab2").show();
    $.support.cors = true;
    $("#ContractName").html(localStorage.getItem("contractname"));
    $("#divWelcome").html("Welcome " + localStorage["FNAME"] + " " + localStorage["LNAME"]);
    $("#divLoggedAs").html("You are administering " + localStorage.getItem("contractname"));
})
var unAthMsg = 'Unauthorized access.';
function LoadPageData() {
    //Check the session exists
    //Get the customer data from the database.
    $("#spnContractName").html(localStorage["CUSTOMERNAME"]);
    //$("#divLoading").modal('hide');
    $("#btnSave").hide();
    getSchool();
    $("#btnAddSchool").bind("click", addSchoolClick);
    $("#btnSaveSettings").click(function() {
        savesettingstab();
    })

    $("#frmUpdateSettings").validate({
        rules: {
            minimumcardpayment: minimumcardpaymentrule,
            vatinputvalue: vatrule,
            dcfvalueinput: dcfrule,
            ccfvalueinput: ccfrule,
            txtUnspecifiedCost: unspecifiedcostrule
        },
        messages: {
            minimumcardpayment: minimumcardpaymentMsg,
            vatinputvalue: vatMsg,
            dcfvalueinput: dcfMsg,
            ccfvalueinput: ccfMsg,
            txtUnspecifiedCost: unspecifiedcostMsg
        },
        errorClass: "help-inline",
        errorElement: "span",
        highlight: function(element, errorClass, validClass) {
            $(element).parents('.control-group').addClass('error');
            return false;
        },
        unhighlight: function(element, errorClass, validClass) {

            $(element).parents('.control-group').removeClass('error');
            return false;
        },
        errorPlacement: function(error, element) {
            if (element.parent().parent().children().hasClass("inline")) {
                error.insertAfter(element.parent().parent().children('span.inline'));
                return false;
            }
            else if (element.parent().hasClass("input-append") || element.parent().hasClass("input-prepend")) {

                error.insertAfter(element.parent());
                return false;
            }
        }
    });
    $("#frmCloseSchl").validate({
        errorClass: "help-inline",
        errorElement: "span",
        highlight: function(element, errorClass, validClass) {
            $(element).parents('.control-group').addClass('error');
            return false;
        },
        unhighlight: function(element, errorClass, validClass) {
            $(element).parents('.control-group').removeClass('error');
            return false;
        },
        errorPlacement: function(error, element) {
            if (element.parent().hasClass("input-append") || element.parent().hasClass("input-prepend")) {
//error.insertAfter(element.parent());
                return false;
            }
            else {
                error.insertAfter(element);
                return false;
            }
        }
    });
    $("#schoolEdit").validate({
        errorClass: "help-inline",
        errorElement: "span",
        highlight: function(element, errorClass, validClass) {
            $(element).parents('.control-group').addClass('error');
        },
        unhighlight: function(element, errorClass, validClass) {
            $(element).parents('.control-group').removeClass('error');
        }
    });
    $("#btnSave").click(function() {
        if (localStorage["PSChng"] == "1")
        {
            Confirm_box("Warning, this may impact existing meal orders", "Warning!", passSchoolValToDb, getSchool);
            /*bootbox.dialog({
             message: "Warning, this may impact existing meal orders",
             title: "Warning!",
             buttons: {
             danger: {
             label: "Cancel",
             className: "btn",
             callback: function() {
             getSchool();
             //alert("uh oh, look out!");
             }
             },
             success: {
             label: "Continue",
             className: "btn-primary",
             callback: function() {
             $("#btnSave").attr("disabled", true);
             passSchoolValToDb();
             }
             }
             }
             });*/
        }
        else
            passSchoolValToDb();
    });
    $("#SchoolAdminTab").bind("click", loadMySchools);
    $("#chkHideComplete", "#documents").bind("click", loadMyDocuments);
    $("#selectAllSchools", "#documents").bind("change", loadMyDocuments);
    $('input[id^="txt"]', "#frmMySchools").bind("keypress keyup focus", function() {
        $("#btnSaveSchool").removeAttr("disabled").text('Save');
    });
    $("input:checkbox", "#frmMySchools").change(function() {
        $("#btnSaveSchool").removeAttr("disabled").text('Save');
        var txtId = $(this).attr("id").replace("chk", "txt");
        if ($(this).is(":checked"))
            $("#" + txtId).removeAttr("disabled");
        else
        {
            $("#" + txtId).parents('.control-group').removeClass('error').find("span").remove();
            $("#" + txtId).attr("disabled", "disabled");
        }
    });
    $("#frmMySchools").validate({
        rules: {
            txtSchoolId: regschoolId,
            txtOffC2Telephone: ruleTelephoneNum,
            txtOffC1Telephone: ruleTelephoneNum,
            txtAddress1: regAddress,
            txtAddress2: regAddress,
            txtAddress3: regAddress,
            txtCity: regCity,
            txtCounty: regCity,
            txtOffC1Name: regAlphaNumeric,
            txtOffC2Name: regAlphaNumeric,
            txtOffC1Email: {
                customemail: true
            },
            txtOffC2Email: {
                customemail: true
            },
            txtPostcode: regPostalCode,
            txtY0: regAlphaNumericReqYear,
            txtY0C1: regAlphaNumericReq,
            txtY0C2: regAlphaNumericReq,
            txtY0C3: regAlphaNumericReq,
            txtY0C4: regAlphaNumericReq,
            txtY0C5: regAlphaNumericReq,
            txtY0C6: regAlphaNumericReq,
            txtY1: regAlphaNumericReqYear,
            txtY1C1: regAlphaNumericReq,
            txtY1C2: regAlphaNumericReq,
            txtY1C3: regAlphaNumericReq,
            txtY1C4: regAlphaNumericReq,
            txtY1C5: regAlphaNumericReq,
            txtY1C6: regAlphaNumericReq,
            txtY2: regAlphaNumericReqYear,
            txtY2C1: regAlphaNumericReq,
            txtY2C2: regAlphaNumericReq,
            txtY2C3: regAlphaNumericReq,
            txtY2C4: regAlphaNumericReq,
            txtY2C5: regAlphaNumericReq,
            txtY2C6: regAlphaNumericReq,
            txtY3: regAlphaNumericReqYear,
            txtY3C1: regAlphaNumericReq,
            txtY3C2: regAlphaNumericReq,
            txtY3C3: regAlphaNumericReq,
            txtY3C4: regAlphaNumericReq,
            txtY3C5: regAlphaNumericReq,
            txtY3C6: regAlphaNumericReq,
            txtY4: regAlphaNumericReqYear,
            txtY4C1: regAlphaNumericReq,
            txtY4C2: regAlphaNumericReq,
            txtY4C3: regAlphaNumericReq,
            txtY4C4: regAlphaNumericReq,
            txtY4C5: regAlphaNumericReq,
            txtY4C6: regAlphaNumericReq,
            txtY5: regAlphaNumericReqYear,
            txtY5C1: regAlphaNumericReq,
            txtY5C2: regAlphaNumericReq,
            txtY5C3: regAlphaNumericReq,
            txtY5C4: regAlphaNumericReq,
            txtY5C5: regAlphaNumericReq,
            txtY5C6: regAlphaNumericReq,
            txtY6: regAlphaNumericReqYear,
            txtY6C1: regAlphaNumericReq,
            txtY6C2: regAlphaNumericReq,
            txtY6C3: regAlphaNumericReq,
            txtY6C4: regAlphaNumericReq,
            txtY6C5: regAlphaNumericReq,
            txtY6C6: regAlphaNumericReq
        },
        messages: {
            txtSchoolId: regschoolMsg,
            txtAddress1: addressErrorMsg,
            txtAddress2: addressErrorMsg,
            txtAddress3: addressErrorMsg,
            txtCity: cityErrorMsg,
            txtCounty: countryErrorMsg,
            txtOffC1Name: OffErrorMsg,
            txtOffC2Name: OffErrorMsg,
            txtPostcode: postalCodeErrorMsg,
            txtY0: YearErrorMsg,
            txtY0C1: ClassErrorMsg,
            txtY0C2: ClassErrorMsg,
            txtY0C3: ClassErrorMsg,
            txtY0C4: ClassErrorMsg,
            txtY0C5: ClassErrorMsg,
            txtY0C6: ClassErrorMsg,
            txtY1: YearErrorMsg,
            txtY1C1: ClassErrorMsg,
            txtY1C2: ClassErrorMsg,
            txtY1C3: ClassErrorMsg,
            txtY1C4: ClassErrorMsg,
            txtY1C5: ClassErrorMsg,
            txtY1C6: ClassErrorMsg,
            txtY2: YearErrorMsg,
            txtY2C1: ClassErrorMsg,
            txtY2C2: ClassErrorMsg,
            txtY2C3: ClassErrorMsg,
            txtY2C4: ClassErrorMsg,
            txtY2C5: ClassErrorMsg,
            txtY2C6: ClassErrorMsg,
            txtY3: YearErrorMsg,
            txtY3C1: ClassErrorMsg,
            txtY3C2: ClassErrorMsg,
            txtY3C3: ClassErrorMsg,
            txtY3C4: ClassErrorMsg,
            txtY3C5: ClassErrorMsg,
            txtY3C6: ClassErrorMsg,
            txtY4: YearErrorMsg,
            txtY4C1: ClassErrorMsg,
            txtY4C2: ClassErrorMsg,
            txtY4C3: ClassErrorMsg,
            txtY4C4: ClassErrorMsg,
            txtY4C5: ClassErrorMsg,
            txtY4C6: ClassErrorMsg,
            txtY5: YearErrorMsg,
            txtY5C1: ClassErrorMsg,
            txtY5C2: ClassErrorMsg,
            txtY5C3: ClassErrorMsg,
            txtY5C4: ClassErrorMsg,
            txtY5C5: ClassErrorMsg,
            txtY5C6: ClassErrorMsg,
            txtY6: YearErrorMsg,
            txtY6C1: ClassErrorMsg,
            txtY6C2: ClassErrorMsg,
            txtY6C3: ClassErrorMsg,
            txtY6C4: ClassErrorMsg,
            txtY6C5: ClassErrorMsg,
            txtY6C6: ClassErrorMsg,
            txtOffC2Telephone: ruleTelephoneErrorMsg,
            txtOffC1Telephone: ruleTelephoneErrorMsg
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
            if (element.parent().hasClass("input-append") || element.parent().hasClass("input-prepend")) {
                error.insertAfter(element.parent());
            } else {
                error.insertAfter(element);
            }
        }
    });
    $("#ddlSchools").bind("change", populateSchoolDetails);
    $("#btnSaveSchool").bind("click", saveSchoolDetails);
    $("#txtOrderValue").bind("keypress keyup", function() {

        $("#btnSaveSettings").removeAttr("disabled");
        $("#btnSaveSettings").text("Save");
    })
    $("#btnAdminExportPupilIds").bind("click", exportAdminPupilIdS);
    $("#ddlAvailableUsers option").mouseover(function(e) {
//alert($(e.target).attr("data-content"));
        $(e.target).tooltip('show');
    });
    $("#ddlAvailableUsers option").mouseout(function(e) {
        $(e.target).popover('hide');
    });
    $("#tblAvailable > tr").click(function(e) {

        alert("HERE");
    });
    //add users in school admin
    $("#btnAddUsers").click(function(e) {

        e.preventDefault();
        var availableUser = $('#ddlAvailableUsers option:selected');
        if (availableUser.length == 0) {
            $("#divSelfUser").html("Please select from available users").show();
        }
        else
        {
            $("#btnSaveSchool").removeAttr("disabled").text('Save');
            $("#divSelfUser").hide();
        }
        $("#ddlSelectedUsers").prepend($(availableUser).clone());
        $(availableUser).remove();
        SortOptions("#ddlSelectedUsers");
        $("#ddlAvailableUsers option").length == 0 ? $("#btnAddUsers").attr("disabled", "disabled") : $("#btnAddUsers").removeAttr("disabled");
        $("#ddlSelectedUsers option").length == 0 ? $("#btnRemoveUsers").attr("disabled", "disabled") : $("#btnRemoveUsers").removeAttr("disabled");
        $("#ddlSelectedUsers option").mouseover(function(e) {
            showPopOver("#ddlSelectedUsers", "left", e.target);
        });
        $("#ddlSelectedUsers option").mouseout(function(e) {
            $("#ddlSelectedUsers").popover('destroy');
        });
    })
    //remove users in school admin
    $("#btnRemoveUsers").click(function(e) {
        e.preventDefault();
        var selectedUser = $('#ddlSelectedUsers option:selected');
        if (selectedUser.length == 0) {
            $("#divSelfUser").html("Please select from selected users").show();
        }
        else
        {
            $("#btnSaveSchool").removeAttr("disabled").text('Save');
            $("#divSelfUser").hide();
        }
        $('#ddlAvailableUsers').prepend($(selectedUser).clone());
        $(selectedUser).remove()
        SortOptions("#ddlAvailableUsers");
        $("#ddlAvailableUsers option").length == 0 ? $("#btnAddUsers").attr("disabled", "disabled") : $("#btnAddUsers").removeAttr("disabled");
        $("#ddlSelectedUsers option").length == 0 ? $("#btnRemoveUsers").attr("disabled", "disabled") : $("#btnRemoveUsers").removeAttr("disabled");
        $("#ddlSelectedUsers option").mouseover(function(e) {
            showPopOver("#ddlSelectedUsers", "left", e.target);
        });
        $("#ddlSelectedUsers option").mouseout(function(e) {
            $("#ddlSelectedUsers").popover('destroy');
        });
    })
    $("#SchoolPupilImportSection").hide();
    $("#divNewSchoolAdmin").hide();
    $("#divSchoolAdminDocuments").hide();
    $("#divSchoolAdminSettings").hide();
    $("#divSchoolAdminMenus").hide();
    $("#divSchoolAdminAccounts").hide();
    $("#SchoolAdminImportTab").click(function() {
        getSchool();
        $("#SchoolPupilImportSection").hide();
        $("#divSchoolAdminDocuments").hide();
        $("#divNewSchoolAdmin").hide();
        $("#divSchoolAdminSettings").hide();
        $("#SchoolImportSection").show();
        $("#divSchoolAdminMenus").hide();
        $("#divSchoolAdminAccounts").hide();
    });
    $("#SchoolAdminPupilImport").click(function() {
        $("#divSchoolAdminMenus").hide();
        $("#SchoolImportSection").hide();
        $("#divSchoolAdminDocuments").hide();
        $("#divNewSchoolAdmin").hide();
        $("#divSchoolAdminSettings").hide();
        $("#SchoolPupilImportSection").show();
        $("#divSchoolAdminAccounts").hide();
        customerModuleAccess('AL3PIMP', 0);
        loadImportPupilsData();
    });
    $("#SchoolAdminTab").click(function() {
        $("#SchoolImportSection").hide();
        $("#SchoolPupilImportSection").hide();
        $("#divSchoolAdminDocuments").hide();
        $("#divSchoolAdminSettings").hide();
        $("#divNewSchoolAdmin").show();
        $("#divSchoolAdminMenus").hide();
        $("#divSchoolAdminAccounts").hide();
        $("#frmMySchools").data('validator').resetForm();
        $(".error").removeClass("error");
        $("#divSelfUser").hide();
    });
    $("#SchoolAdminDocumentsTab").click(function() {
        $("#divNewSchoolAdmin").hide();
        $("#SchoolPupilImportSection").hide();
        $("#SchoolImportSection").hide();
        $("#divSchoolAdminSettings").hide();
        $("#divSchoolAdminDocuments").show();
        $("#divSchoolAdminMenus").hide();
        $("#divSchoolAdminAccounts").hide();
        customerModuleAccess('AL3PDOC', 0);
        loadMyDocuments();
    });
    $("#SchoolAdminSettingsTab").click(function() {
        $("#divsettingsErr").hide();
        customerModuleAccess("AL3PSET", 0);
        $('#datepicker1').datepicker({
            daysOfWeekDisabled: "0,2,3,4,5,6"
        }).on('changeDate', function() {
            $('#datepicker1').datepicker('hide');
            $("#btnSaveSettings").removeAttr("disabled").text('Save');
        });
        $('#datepicker2').datepicker({
            daysOfWeekDisabled: "0,2,3,4,5,6"
        }).on('changeDate', function() {
            $('#datepicker2').datepicker('hide');
            $("#btnSaveSettings").removeAttr("disabled").text('Save');
        });
        $("#divSchoolAdminMenus").hide();
        $("#divSchoolAdminDocuments").hide();
        $("#divNewSchoolAdmin").hide();
        $("#SchoolPupilImportSection").hide();
        $("#SchoolImportSection").hide();
        $("#divSchoolAdminSettings").show();
        $("#divSchoolAdminAccounts").hide();
        $("#frmUpdateSettings").data('validator').resetForm();
        $(".error").removeClass("error");
        saveGlobalSettings();
    });
    $("#SchoolAdminMenuTab").click(function() {
        $("#divSchoolAdminMenus").show();
        $("#divSchoolAdminDocuments").hide();
        $("#divNewSchoolAdmin").hide();
        $("#SchoolPupilImportSection").hide();
        $("#SchoolImportSection").hide();
        $("#divSchoolAdminSettings").hide();
        loadMyMenus();
    });
    $("#btnChangeMenu", "#divSchoolAdminMenus").click(changeMenuOptions);
    $("#divPeopleImportFinishBtn").click(function() {
        $("#tblSchoolYears").show();
    });
    /*  $("#SchoolAdminSettingsTab").click(function () {
     
     $("#SettingsSavebtnLabel").text('');
     
     }); */
    //checkboxStatus();
    loadAReports();
    loadACardRefunds();
    $("#tabMealOrder").click(function() {
        loadMealOrderSummary();
    });
    // Open & Close schools 
    var todayDate = new Date();
    var t_date = todayDate.getDate() + "/" + (todayDate.getMonth() + 1) + "/" + todayDate.getFullYear();
    todayDate.setDate(todayDate.getDate() - 1);
    $("#div_SchEnddate").datepicker({startDate: todayDate}).on(
            "changeDate", function() {
        $("#div_SchEnddate").parent().hasClass("control-group error")
        $("#div_SchEnddate").parents('.control-group').removeClass('error');
        $(".datepicker").hide();
    });
    $('#div_SchEnddate').data({
        date: t_date
    }).datepicker('update');
    $("#btnSubmitSchl", "#divCloseSchl").bind("click", Sub_CloseSchl);
    $("#btnCancelSchl", "#divCloseSchl").bind("click", Sub_CancelSchl);
    $("#xdivCloseSchl", "#divCloseSchl").bind("click", Sub_CancelSchl);
    $("#btnSubmit_OpenSchl", "#divOpenSchl").bind("click", Sub_OpenSchl);
    $("#btnCan_OpenSchl", "#divOpenSchl").bind("click", SubOpen_CclSchl);
    $("#dp_SchEnddate").val(t_date);
}

function Sub_OpenSchl()
{
    var url = BACKENDURL + "customeradmin/school_open";
    var data = {
        session_id: localStorage["SESSIONID"],
        school_id: $("#ddlSchools").val()
    };
    MakeAjaxCall(url, data, Comfirm_OpenSchl);
}
function Comfirm_OpenSchl(data) {
    if (data.error == 0 || (data.error_msg == "Unable to send email")) {
        $("#lblCloseSchoolWarn").addClass("hide");
        $("#divOpenSchl").modal("hide");
        $("#btnAdminCloseSchool").removeClass("hide");
        $("#btnAdminOpenSchool").addClass("hide");
        if (data.error_msg == "Unable to send email")
            $("#lblCloseSchoolMail").removeClass('hide');
    } else
        logout(1)
}

function SubOpen_CclSchl()
{
    $("#divOpenSchl").modal("hide");
}

function Sub_CloseSchl()
{
    if ($("#frmCloseSchl").valid())
    {
        var war_Mes, War_date, War_Schoolname, War_Reason = "";
        $("#divCloseSchl").modal("hide");
        $("#lblCloseSchoolWarn").text('');
        War_date = $("#dp_SchEnddate", "#divCloseSchl").val();
        War_Schoolname = $("#ddlSchools option:selected").text();
        War_Reason = $("#txtReason", "#divCloseSchl").val();
        war_Mes = "<strong>Remember! </strong>" + War_Schoolname + " was closed by " + localStorage["FNAME"] + " " + localStorage["LNAME"] + ", because '" + War_Reason + "'. Last day of closure is " + War_date;
        $("#lblCloseSchoolWarn").append(war_Mes);
        var date1 = War_date.split("/");
        var url = BACKENDURL + "customeradmin/school_close";
        var data = {
            session_id: localStorage["SESSIONID"],
            school_id: $("#ddlSchools").val(),
            close_till: date1[2] + "-" + date1[1] + "-" + date1[0],
            reason: War_Reason
        };
        MakeAjaxCall(url, data, Comfirm_CloseSchl);
    }
}

function Comfirm_CloseSchl(data)
{
    if (data.error == 0 || (data.error_msg == "Unable to send email")) {
        $("#dp_SchEnddate", "#divCloseSchl").val("");
        $("#txtReason", "#divCloseSchl").val("");
        $("#btnAdminCloseSchool").addClass("hide");
        $("#btnAdminOpenSchool").removeClass("hide");
        $("#lblCloseSchoolWarn").removeClass("hide");
        if (data.error_msg == "Unable to send email")
            $("#lblCloseSchoolMail").removeClass('hide');
    } else
        logout(1);
}

function Sub_CancelSchl()
{
    $("#dp_SchEnddate", "#divCloseSchl").val("");
    $("#txtReason", "#divCloseSchl").val("");
    $("#frmCloseSchl").data('validator').resetForm();
    $("#dp_SchEnddate").datepicker('update');
    $(".error").removeClass("error");
    $("#divCloseSchl").modal("hide");
}
//After importing the files....
function finishImport(event) {
    var formtype = event.data.f;
    if (formtype == "school_document_admin") {
        loadMyDocuments();
    }
    if (formtype == "pupils") {
        loadImportPupilsData();
    }
}

$("#headerMyProfile").click(function() {
    $("#divProfileEdit").modal('show');
    $("#ProfileModalBoxSubmitBtnLabel").text("");
    $("#txtCustomerAdminFName").each(function() {

        $(this).css({
            'border': '1px solid #CCC'
        });
    });
    $("#txtCustomerAdminLName").each(function() {

        $(this).css({
            'border': '1px solid #CCC'
        });
    });
    $("#txtCustomerAdminemail").each(function() {

        $(this).css({
            'border': '1px solid #CCC'
        });
    });
    $("#txtCustomerAdminCemail").each(function() {

        $(this).css({
            'border': '1px solid #CCC'
        });
    });
    $.ajax({
        url: BACKENDURL + "customeradmin/get_user_titles",
        type: "post",
        data: {
            session_id: localStorage["SESSIONID"]
        },
        dataType: "json",
        crossDomain: true,
        success: function(data) {


            if (data.session_status) {
                if (data.error == 0) { // session true error false
                    $("#selectTitle").empty();
                    var titleId;
                    var titleVal;
                    for (var nCount = 0; nCount < data.user_titles_res.length; nCount++) {

                        titleVal = "<option value=" + data.user_titles_res[nCount].data_value_id + ">" + data.user_titles_res[nCount].data_value + "</option>";
                        $("#selectTitle").append(titleVal);
                    }

                    $("#txtCustomerAdminFName").val(localStorage["FNAME"]);
                    $("#txtCustomerAdminLName").val(localStorage["LNAME"]);
                    $("#txtCustomerAdminemail").val(localStorage["EMAIL"]);
                    $("#selectTitle").val(localStorage["TITLE"]);
                    $("#txtCustomerAdminCemail").val(localStorage["TELEPHONE"]);
                } else { // session true error true
                    alert("error true")
                    alert(data.error_msg);
                    alert(data.error);
                }

            } else {
                logout();
            }
        },
        error: function(xhr, textStatus, error) {
            alert(error);
        }
    });
});
$("#btnNewAddYear").click(function() {

    $("#tab3").show();
}) /******************* adding new contract ****************/

//New customer modal key press.
$("#CreateNewContract", "#selectCreate").keypress(function(e) {
    if ((e.which && e.which == 13) || (e.keyCode && e.keyCode == 13)) {
        $("#btnCreateContract", "#selectCreate").click();
        return false;
    } else
        return true;
});
$("#CreateContractbtnCreate", "#selectCreate").click(function() {

    if ($("#CreateNewContract", "#selectCreate").val() == "") {
//alert("Please enter contract name!")
        $("#CreateNewContract", "#selectCreate").focus();
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
                        localStorage["contractname"] = $("#CreateNewContract", "#selectCreate").val();
                        getSchool();
                        $("#ContractName").html($("#CreateNewContract", "#selectCreate").val());
                        $("#divLoggedAs").html("You are administering " + $("#CreateNewContract", "#selectCreate").val());
                    } else {
                        alert(data.error_msg);
                    }
                } else {
                    alert(data.error_msg)
                    var NewLabel = data.error_msg;
                    $("#SelectOrCreateContractNewLabel").text("*" + NewLabel);
                    $("#CreateNewContract").focus();
                    $("#CreateNewContract").each(function() {
                        ModalFieldUI(this)
                    });
                    alert(data.error_msg);
                }
            },
            error: function(xhr, textStatus, error) {
            }
        });
    }
// $("#xNewSelectCreate" ,"#selectCreate").click();
});
/********btnSelectCreate start**************/
$("#headerManageContract").click(function() {


    $("#selectCreate").modal('show');
    $("#CreateNewContract").val("");
    $("#SelectOrCreateContractNewLabel").text("");
    $("#CreateNewContract").each(function() {
        $(this).css({
            'border': '1px solid #CCC'
        });
    });
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
                        contractVal = "<option value=" + data.contracts_res[nCount].contract_id + ">" + data.contracts_res[nCount].contract_name + "</option>";
                        $("#selectContract").append(contractVal);
                    }
                } else {
//alert(error);
                }
            } else {
                logout();
            }
        },
        error: function(xhr, textStatus, error) {
//alert(error);
        }
    });
}); /********** go button create contract display school *********************/

$("#btnGo").click(function() {
    localStorage.setItem("contractid", " ");
    localStorage.setItem("contractid", $("#selectContract").val());
    localStorage["contractname"] = $("#selectContract :selected").text();
    getSchool();
    $("#ContractName").html($("#selectContract :selected").text());
    $("#xNewSelectCreate", "#selectCreate").click();
}); /********************** end ofgo button create contract display school********************/

/************************************* add school ****************************************************/
/***** in add school modal to handle the default checkbox selection ,years to display****/

function addSchoolClick() {
    $("#AddSchoolFooterLabel").removeClass('alert alert-error').text('');
    $("input[type='text']", "#divAddSchool").each(function() {
        $(this).val('');
    });
    $("#AddSchoolCustomerTxt1").each(function() {

        $(this).css({
            'border': '1px solid #CCC'
        });
    });
    $("#AddSchoolCustomerTxt2").each(function() {

        $(this).css({
            'border': '1px solid #CCC'
        });
    });
    $('#AddSchoolCheckBox').attr('checked', false);
    $("#AddSchoolFooterLabel").text("");
    $("#AddProdSchoolDrop").each(function() {

        $(this).css({
            'border': '1px solid #CCC'
        });
    });
    $("#AddSchoolDynamicLabel").text("");
    $("#AddSchoolCustomerTxt1").each(function() {

        $(this).css({
            'border': '1px solid #CCC'
        });
    });
    $("#AddSchoolCustomerTxt1").each(function() {
        $(this).focus(function() {
            $(this).css({
                'border': ''
            });
        });
    });
    $.ajax({
        url: BACKENDURL + "customeradmin/get_data_any",
        type: "post",
        data: {
            session_id: localStorage["SESSIONID"],
            data_ref: "school_years"
        },
        dataType: "json",
        crossDomain: true,
        success: function(data) {

            if (data.session_status) {
                if (data.error == 0) { // session true error false
                    $("#AddSchoolCustomerDrop").empty();
                    $('#AddSchoolCustomerDrop').append(' <option id="" value="">Number of years</option>');
                    var schoolyrs;
                    for (var nCount = 0; nCount < data.data_any_res.length; nCount++) {
                        schoolyrs = "<option value=" + data.data_any_res[nCount].data_value_id + ">" + data.data_any_res[nCount].data_value + "</option>";
                        $("#AddSchoolCustomerDrop").append(schoolyrs);
                    }
                    populateProdSchool();
                } else {
                    alert(data.error);
                }
            } else {
                logout();
            }
        },
        error: function(xhr, textStatus, error) {
            //alert(error);
        }
    });
}

/****************** populate prod school ****************/

function populateProdSchool() {
    var url = BACKENDURL + "customeradmin/get_schools";
    var data = {
        session_id: localStorage["SESSIONID"],
        contract_id: localStorage.getItem("contractid")
    };
    MakeAjaxCall(url, data, loadProdSchool);
}
function loadProdSchool(data) {
    if (data.error == 0) {
        var prodschool = "";
        $('#AddProdSchoolDrop').empty();
        //populating prod school
        for (var nCount = 0; nCount < data.schools_res.length; nCount++) {
            var st = (data.schools_res[nCount].status == "0") ? " (disabled)" : "";
            prodschool += "<option value=" + data.schools_res[nCount].school_id + ">" + data.schools_res[nCount].school_name + st + "</option>";
        }
        $('#AddProdSchoolDrop').append(' <option  value="0">I am a production school</option>');
        $('#AddProdSchoolDrop').append(prodschool);
    } else
        logout(1);
}
/************************************************/


$("input[type='text']").keypress(function(e) {
    if ((e.which && e.which == 13) || (e.keyCode && e.keyCode == 13)) {
        $("#btnNewAddSchoolAdminSubmit").click();
        return false;
    } else
        return true;
});
$("#btnNewAddSchoolAdminSubmit").click(function() {
    localStorage["Data"] = $("#AddProdSchoolDrop :selected").text();
    $("#tab2").show();
    if ($("#AddSchoolCustomerTxt1").val() == "") {
        $("#AddSchoolFooterLabel").text("Please enter school name").addClass('alert alert-error');
        return false;
    } else if ($("#AddProdSchoolDrop").val() == "") {

        $("#AddSchoolFooterLabel").text("Please select prod school name");
        $("#AddProdSchoolDrop").each(function() {
            ModalFieldUI(this);
        });
        return false;
    } else {

        if ($("#AddProdSchoolDrop :selected").text() == ('I am a production school')) {
            nValid = "1";
        } else {
            nValid = "0";
        }
        $.ajax({
            url: BACKENDURL + "customeradmin/create_school",
            type: "post",
            data: {
                session_id: localStorage["SESSIONID"],
                customer_id: localStorage["CUSTOMERID"],
                school_name: $("#AddSchoolCustomerTxt1").val(),
                contract_id: localStorage.getItem("contractid"),
                user_id: localStorage["USERID"],
                production_id: $("#AddProdSchoolDrop :selected").val(),
                production_status: nValid
            },
            dataType: "json",
            crossDomain: true,
            success: function(data) {

                if (data.session_status) {
                    if (data.error) {

                        var get = data.error_msg;
                        if (get == unAthMsg)
                            logout(1)
                        else {
                            $("#AddSchoolFooterLabel").text(get).addClass('alert alert-error');
                        }
                    } else {
                        getSchool();
                        $("#divAddSchool").modal('hide');
                        $("#AddSchoolSubmitBtn").modal('show');
                        $("#AddClassCloseBtn1").html("OK");
                    }
                } else {
                    logout();
                }
            },
            error: function(xhr, textStatus, error) {
                //alert(error);
            }
        });
    }
});
/************** table formation with get school *****************/
var SchoolArray;
var getData;
var prodschool;
function getSchool() {
    SchoolArray = [];
    $.ajax({
        url: BACKENDURL + "customeradmin/get_schools",
        type: "post",
        data: {
            session_id: localStorage["SESSIONID"],
            contract_id: localStorage.getItem("contractid")
        },
        dataType: "json",
        crossDomain: true,
        success: function(data) {
            getData = data;
            //minValue = data.contract_res[0].min_order;

            if (data.session_status) {
                if (data.error == 0) {
                    localStorage["PSChng"] = "0";
                    var nCurrentSchool, nCurrRecRound = 0;
                    prodschool = "";
                    var st;
                    var hdnCurrPage = $("#currPageNumber", "#tablePagination").val();
                    if (hdnCurrPage != undefined) {
                        nCurrRecRound = hdnCurrPage - 1;
                    }
                    $("#tablePagination").remove();
                    //populating prod school
                    for (var nCount = 0; nCount < data.schools_res.length; nCount++) {
                        //if(data.schools_res[nCount].production_status == "1")
                        // {
                        if (data.schools_res[nCount].status == "0") {
                            st = "disabled";
                            prodschool += "<option value=" + data.schools_res[nCount].school_id + ">" + data.schools_res[nCount].school_name + " (" + st + ")" + "</option>";
                        } else {
                            prodschool += "<option value=" + data.schools_res[nCount].school_id + ">" + data.schools_res[nCount].school_name + "</option>";
                        }
                        //pagination track for current adding school	
                        if (data.schools_res[nCount].school_name == $("#AddSchoolCustomerTxt1").val()) {
                            nCurrRecRound = Math.floor(nCount / 10);
                            $("#AddSchoolCustomerTxt1").val("");
                        }
                    }

                    // avoid header repeatation
                    $("#tblCustomers tbody").children().remove();
                    if (data.schools_res.length > 0) {
                        $("#tblCustomers").show();
                        $("#btnSave").show();
                        $("#btnSave").attr("disabled", true);
                        $("#addSchoolMsg").hide();
                    } else {
                        $("#tblCustomers").hide();
                        $("#btnSave").hide();
                    }
                    // creating dynamic table with prod school dropdwon list		 
                    for (var nCount = 0; nCount < data.schools_res.length; nCount++) {
                        var tblCust = "";
                        var disablebtn;
                        if (data.schools_res[nCount].status == "0") {
                            disablebtn = "<button class='btn btn-small btn-success' data-toggle='modal'  id='AddschoolDisablebtn" + nCount + "' style=' margin-top:3%;'><i class='icon-ok-sign icon-white'></i> Enable Servery</button>";
                        } else
                            disablebtn = "<button class='btn btn-small btn-danger' data-toggle='modal'  id='AddschoolDisablebtn" + nCount + "' style=' margin-top:3%;'><i class='icon-remove-sign icon-white'></i> Disable Servery</button>";
                        var AddId = "<a class='btn' href='#AddID' data-toggle='modal'  style='background:#004D7C; color:#ffffff; margin-top:2%;'>Add IDs</a>";
                        var checkedStr = (data.schools_res[nCount].production_status == "1") ? "checked" : "";
                        var schName = "<div class='control-group'><div id='SchoolNameTxtFieldLabel" + nCount + "'><input type='text' maxlength='200' style='width: 80%;margin-top: 10px;' required validateDupSchool='true' id='schoolname" + nCount + "' name='schoolname" + nCount + "'></input><span class='help-inline' id='schoolAlreadyExist" + nCount + "'></span></div></div>";
                        tblCust = "<tr><td style='display:none;'><input type='text' id='schoolid" + nCount + "' value=" + data.schools_res[nCount].school_id + "></input></td><td>" + schName + "</td><td ><span style='width: 100%;margin-top: 10px;' class='select-wrap'><select style='width: 110%;' id='selectprod" + nCount + "'>" + prodschool + "</select></span></td><td id='box12345' style='text-align: right;' >" + disablebtn + "</td></tr>";
                        $("#tblCustomers  tbody:last").append(tblCust);
                        $("#schoolname" + nCount, "#tblCustomers").val(data.schools_res[nCount].school_name);
                        $("#selectprod" + [nCount] + " option[value=" + data.schools_res[nCount].production_id + "]").attr("selected", "selected").text();
                        if (data.schools_res[nCount].production_status == "1") {
                            $("#selectprod" + [nCount] + " option[value=" + data.schools_res[nCount].school_id + "]").attr("selected", "selected").text();
                        }
                        SchoolArray.push({
                            "school_id": data.schools_res[nCount].school_id,
                            "schoolname": data.schools_res[nCount].school_name,
                            "productionstatus": data.schools_res[nCount].production_status,
                            "prodid": data.schools_res[nCount].production_id,
                            "status": data.schools_res[nCount].status
                        });
                    }
                    $('[id^="selectprod"]').change(function() {
                        localStorage["PSChng"] = "1";
                    });
                    editSchool();
                    var nRows = 10;
                    //pagination for more than 2 schools added
                    if (data.schools_res.length > nRows) {
                        $("#tablePagination").html('');
                        $("#tab2").tablePagination({
                            rowsPerPage: nRows,
                            currPage: nCurrRecRound + 1 //$("#currPageNumber", "#tablePagination").val()
                        });
                    }
                    $.validator.addMethod("validateDupSchool", function(value, element) {
                        var v = $(element).val();
                        for (var i = 0; i < data.schools_res.length; i++) {
                            if ($(element).attr("id") != $("#schoolname" + i).attr("id") && v.toLowerCase() == $("#schoolname" + i).val().toLowerCase()) {
                                $("#btnSave").attr("disabled", true);
                                return false;
                            }
                        }
                        return true;
                    }, "Please enter a unique school name");
                    //For Global Settings

                    $("#txtContractName").val(data.contract_res[0].contract_name);
                    $("#txtOrderValue").val(data.contract_res[0].min_order);
                    $('input[name=radStatus][value=' + data.contract_res[0].status + ']').attr('checked', 'checked');
                    //SplOffers table
                    if (data.contract_res[0].contract_offers.length > 0) {
                        for (var i = 1; i <= data.contract_res[0].contract_offers.length; i++) {
                            var showplus = "";
                            if (i == data.contract_res[0].contract_offers.length - 1)
                                showplus = "display:none";
                            var newrow = "<tr id=" + i + "><td><span class='select-wrap'><select name='ddlSpend" + i + "' id='ddlSpend" + i + "' style='width:150px'></span><option value=0>- Choose spend</option><option value=10>£ 10</option><option value=20>£ 20</option><option value=30>£ 30</option><option value=50>£ 50</option><option value=75>£ 75</option><option value=100>£ 100</option></select></td>";
                            newrow += "<td><span class='select-wrap'><select name='ddlReward" + i + "' id='ddlReward" + i + "' style='width:150px'></span><option value=0>- Choose reward</option><option value=1>1 free meal</option><option value=3>3 free meal</option><option value=5>5 free meal</option><option value=7>7 free meal</option><option value=9>9 free meal</option><option value=10>10 free meal</option></select></td>";
                            newrow += "<td><a class='btn btn-primary' href='#' onclick='javascript:return addOffer(" + i + ")'  id='btnPlus" + i + "' name='btnPlus" + i + "'  style='width:3px; " + showplus + "'>+</a>&nbsp;<a class='btn btn-primary' style='width:3px;' href='#' onclick='javascript:return removeOffer(" + i + ")'  id='btnMinus" + i + "' name='btnMinus" + i + "' >-</a></td></tr>";
                            $("#tblOffers  tbody:last").append(newrow);
                            $("#ddlSpend" + i + " option[value=" + data.contract_res[0].contract_offers[i - 1].spend + "]").attr('selected', 'selected');
                            $("#ddlReward" + i + " option[value=" + data.contract_res[0].contract_offers[i - 1].reward + "]").attr('selected', 'selected');
                        }
                    } else {
                        var newrow = "<tr id=1><td><span class='select-wrap'><select name='ddlSpend1' id='ddlSpend1' style='width:150px'></span><option value=0 selected>- Choose spend</option><option value=10>£ 10</option><option value=20>£ 20</option><option value=30>£ 30</option><option value=50>£ 50</option><option value=75>£ 75</option><option value=100>£ 100</option></select></td>";
                        newrow += "<td><span class='select-wrap'><select name='ddlReward1' id='ddlReward1' style='width:150px'></span><option value=0 selected>- Choose reward</option><option value=1>1 free meal</option><option value=3>3 free meal</option><option value=5>5 free meal</option><option value=7>7 free meal</option><option value=9>9 free meal</option><option value=10>10 free meal</option></select></td>";
                        newrow += "<td><a class='btn btn-primary' href='#'  onclick='javascript:return addOffer(1);' id='btnPlus1' name='btnPlus1'  style='width:3px;'>+</a>&nbsp;<a class='btn btn-primary' style='width:3px; display:none;' href='#' onclick='javascript:return removeOffer(1);'  id='btnMinus1' name='btnMinus1'>-</a></td></tr>";
                        $("#tblOffers  tbody:last").append(newrow);
                    }
                    (data.schools_res.length) > 0 ? customerModuleAccess("AL3SCON", 1) : customerModuleAccess("AL3SCON", 0);
                } else
                    logout(1);
            } else {
                logout();
            }
        },
        error: function(xhr, textStatus, error) {
            //  alert(error);
        }
    });
}

/****************** eof getschool**************/
/****************** Bof GlobalSettings ***********/

function addOffer(rowid) {
    if ($("#ddlSpend" + rowid).val() == "0") {
        alert("Please select spend value");
        $("#ddlSpend" + rowid).focus();
        return false;
    } else if ($("#ddlReward" + rowid).val() == "0") {
        alert("Please select reward value");
        $("#ddlReward" + rowid).focus();
        return false;
    } else {
        //Adding a row
        rowCount = rowid + 1;
        var newrow = "<tr id=" + rowCount + "><td><select name='ddlSpend" + rowCount + "' id='ddlSpend" + rowCount + "' style='width:150px'><option value=0>- Choose spend</option><option value=10>£ 10</option><option value=20>£ 20</option><option value=30>£ 30</option><option value=50>£ 50</option><option value=75>£ 75</option><option value=100>£ 100</option></select></td>";
        newrow += "<td><select name='ddlReward" + rowCount + "' id='ddlReward" + rowCount + "' style='width:150px'><option value=0>- Choose reward</option><option value=1>1 free meal</option><option value=3>3 free meal</option><option value=5>5 free meal</option><option value=7>7 free meal</option><option value=9>9 free meal</option><option value=10>10 free meal</option></select></td>";
        newrow += "<td><a class='btn btn-primary' href='#' onclick='javascript:return addOffer(" + rowCount + ");'   id='btnPlus" + rowCount + "' name='btnPlus" + rowCount + "'  style='width:3px;'>+</a>&nbsp;<a class='btn btn-primary' style='width:3px;' href='#'  onclick='javascript:return removeOffer(" + rowCount + ");' id='btnMinus" + rowCount + "' name='btnMinus" + rowCount + "' >-</a></td></tr>";
        $("#tblOffers  tbody:last").append(newrow);
        for (var i = 1; i < rowCount; i++) {
            $("#btnPlus" + i).hide();
            $("#btnMinus" + i).show();
        }
        if (rowCount == 3) {
            $("#btnPlus3").hide();
        }
    }
}

function removeOffer(rowid) {
    //Removing a row
    $('#' + rowid).remove();
    if ($('#tblOffers  tr').length == 1) {
        $("#btnPlus1").show();
        $("#btnMinus1").hide();
    }
    if ($('#tblOffers  tr').length == (rowid - 1)) {
        $("#btnPlus" + (rowid - 1)).show();
    }
}

var contractsettingid;
var contractsettingid2;
var sequence1;
var sequence2;
var isValid = true;
var curDate = new Date;
var startDay = 1; //0=sunday, 1=monday etc.
var day = curDate.getDay(); //get the current day
var weeksMonday = new Date(curDate.valueOf() - (day <= 0 ? 7 - startDay : day - startDay) * 86400000); //rewind to start day

function saveGlobalSettings() {

    $.ajax({
        url: BACKENDURL + "customeradmin/get_contract_settings",
        type: "post",
        data: {
            session_id: localStorage["SESSIONID"],
            contract_id: localStorage.getItem("contractid")
        },
        dataType: "json",
        crossDomain: true,
        success: function(data) {
            if (data.session_status) {
                if (data.error == 0) { // session true error false
                    //Need to show success modal...
                    //$("#btnSaveSettingsSuccess").click();

                    $("#minimumcardpayment").val(data.contracts_res[0].min_card_pay);
                    $("#vatinputvalue").val(data.contracts_res[0].vat);
                    $("#dcfvalueinput").val(data.contracts_res[0].dc_fee);
                    $("#ccfvalueinput").val(data.contracts_res[0].cc_fee);
                    $("#txtStsRefundFee").val(data.contracts_res[0].refund_fee);
                    $("#txtUnspecifiedCost").val(data.contracts_res[0].usmc);
                    $('#datepicker1').data({
                        date: data.contracts_res[0].menu[0].menu_start_date
                    }).datepicker('update');
                    $("#datepickerinput1").val(data.contracts_res[0].menu[0].menu_start_date);
                    $('#datepicker2').data({
                        date: data.contracts_res[0].menu[1].menu_start_date
                    }).datepicker('update');
                    $("#datepickerinput2").val(data.contracts_res[0].menu[1].menu_start_date)

                    $("#timunsinput").val(data.contracts_res[0].tminus + "" + "hour");
                    $("#menucycle1").val(data.contracts_res[0].menu[0].menu_cycles);
                    $("menucycle2").val(data.contracts_res[0].menu[1].menu_cycles);
                    Sett_Confirm.push({
                        "Menu_Cycle1": data.contracts_res[0].menu[0].menu_cycles,
                        "Menu_Cycle2": data.contracts_res[0].menu[1].menu_cycles,
                        "Menu_start1": data.contracts_res[0].menu[0].menu_start_date,
                        "Menu_start2": data.contracts_res[0].menu[1].menu_start_date
                    });
                    if (data.contracts_res[0].trans_fee_status == 0)
                    {
                        $("#ctfcheckbox").attr('checked', false);
                        $("#dcfvalueinput").attr("disabled", true);
                        $("#ccfvalueinput").attr("disabled", true);
                        $("#txtStsRefundFee").attr("disabled", true);
                    }
                    else
                    {
                        $("#ctfcheckbox").attr('checked', true);
                        $("#dcfvalueinput").attr("disabled", false);
                        $("#ccfvalueinput").attr("disabled", false);
                        $("#txtStsRefundFee").attr("disabled", false);
                    }

                    if (data.contracts_res[0].adult_invoice == 0)
                    {
                        $("#invoicecheckbox").attr('checked', false);
                    }
                    else
                    {
                        $("#invoicecheckbox").attr('checked', true);
                    }
                    $("#invoicecheckbox").change(function() {
                        $("#btnSaveSettings").removeAttr("disabled").text('Save');
                    });
                    $('select[id^="timunsinput"]').change(function() {

                        $("#btnSaveSettings").removeAttr("disabled").text('Save');
                    });
                    $('select[id^="menucycle1"]').change(function() {

                        $("#btnSaveSettings").removeAttr("disabled").text('Save');
                    });
                    $('select[id^="menucycle2"]').change(function() {
                        $("#btnSaveSettings").removeAttr("disabled").text('Save');
                    });
                    $("#minimumcardpayment,#vatinputvalue,#dcfvalueinput,#ccfvalueinput,#txtStsRefundFee,#txtUnspecifiedCost").bind("keypress keyup", function() {

                        $("#btnSaveSettings").removeAttr("disabled").text('Save')

                    });
                    $("#ctfcheckbox").change(function() {


                        if ($("#ctfcheckbox").is(":checked")) {


                            $("#dcfvalueinput").removeAttr("disabled");
                            $("#ccfvalueinput").removeAttr("disabled");
                            $("#txtStsRefundFee").removeAttr("disabled");
                            $("#btnSaveSettings").removeAttr("disabled").text('Save');
                        }
                        else
                        {

                            $("#dcfvalueinput").attr("disabled", true);
                            $("#ccfvalueinput").attr("disabled", true);
                            $("#txtStsRefundFee").attr("disabled", true);
                            $("#btnSaveSettings").removeAttr("disabled").text('Save');
                        }
                    });
                    /* $("#dcfvalueinput").bind("keypress", function () {
                     
                     
                     $("#btnSaveSettings").removeAttr("disabled").text('Save');
                     });
                     $("#ccfvalueinput").bind("keypress", function () {
                     
                     $("#btnSaveSettings").removeAttr("disabled").text('Save');
                     }); */


                    $("#menucycle1").empty();
                    $("#menucycle2").empty();
                    $("#timunsinput").empty();
                    var timunsTxt = "<option value='1'>1 hour</option>";
                    for (var i = 2; i <= 24; i++)
                        timunsTxt += "<option value='" + i + "'>" + i + " hours</option>";
                    $("#timunsinput").append(timunsTxt);
                    $("#timunsinput").val(data.contracts_res[0].tminus);
                    var menucycle1Txt;
                    for (var i = 1; i <= 6; i++)
                        menucycle1Txt += "<option value='" + i + "'>" + i + "</option>";
                    $("#menucycle1").append(menucycle1Txt);
                    $("#menucycle1").val(data.contracts_res[0].menu[0].menu_cycles);
                    var menucycle2Txt;
                    for (var i = 1; i <= 6; i++)
                        menucycle2Txt += "<option value='" + i + "'>" + i + "</option>";
                    $("#menucycle2").append(menucycle2Txt);
                    $("#menucycle2").val(data.contracts_res[0].menu[1].menu_cycles);
                    $("#datepickerinput1").change(function() {
                        var datechange1 = $("#datepickerinput1").val();
                        var datechange2 = $("#datepickerinput2").val();
                        var menu1StrtDate = datechange1.split("/");
                        var menu1StrtValue = (new Date(menu1StrtDate[2], (menu1StrtDate[1] - 1), menu1StrtDate[0]));
                        var menu2StrtDate = datechange2.split("/");
                        var menu2StrtValue = (new Date(menu2StrtDate[2], (menu2StrtDate[1] - 1), menu2StrtDate[0]));
                        if (datechange1 == datechange2)
                        {
                            $("#divsettingsErr").html("Both the menu1 and menu2 start date should not be the same").show();
                            $("#datepickerinputError1").addClass('control-group error');
                            $("#datepickerinputError2").addClass('control-group error');
                            isValid = false;
                        }
                        else if ((menu1StrtValue > weeksMonday) && (menu2StrtValue > weeksMonday)) {
                            $("#divsettingsErr").html("Either menu 1 or menu 2 start date needs to be before the current date").show();
                            isValid = false;
                            $("#datepickerinputError1").addClass('control-group error');
                            $("#datepickerinputError2").addClass('control-group error');
                        }
                        else {
                            $("#divsettingsErr").hide();
                            isValid = true;
                            $("#datepickerinputError1").removeClass('control-group error');
                            $("#datepickerinputError2").removeClass('control-group error');
                        }
                    });
                    $("#datepickerinput2").change(function() {
                        var datechange1 = $("#datepickerinput1").val();
                        var datechange2 = $("#datepickerinput2").val();
                        var menu1StrtDate = datechange1.split("/");
                        var menu1StrtValue = (new Date(menu1StrtDate[2], (menu1StrtDate[1] - 1), menu1StrtDate[0]));
                        var menu2StrtDate = datechange2.split("/");
                        var menu2StrtValue = (new Date(menu2StrtDate[2], (menu2StrtDate[1] - 1), menu2StrtDate[0]));
                        if (datechange1 == datechange2)
                        {
                            $("#divsettingsErr").html("Both the menu1 and menu2 start date should not be the same").show();
                            $("#datepickerinputError1").addClass('control-group error');
                            $("#datepickerinputError2").addClass('control-group error');
                            isValid = false;
                        }
                        else if ((menu1StrtValue > weeksMonday) && (menu2StrtValue > weeksMonday)) {
                            $("#divsettingsErr").html("Either menu 1 or menu 2 start date needs to be before the current date").show();
                            $("#datepickerinputError1").addClass('control-group error');
                            $("#datepickerinputError2").addClass('control-group error');
                            isValid = false;
                        }
                        else {
                            $("#divsettingsErr").hide();
                            isValid = true;
                            $("#datepickerinputError1").removeClass('control-group error');
                            $("#datepickerinputError2").removeClass('control-group error');
                        }
                    });
                    contractsettingid1 = data.contracts_res[0].menu[0].con_cater_menu_settings_id;
                    contractsettingid2 = data.contracts_res[0].menu[1].con_cater_menu_settings_id;
                    sequence1 = data.contracts_res[0].menu[0].menu_sequence;
                    sequence2 = data.contracts_res[0].menu[1].menu_sequence;
                    console.log(Sett_Confirm);
                    /* SettingsAray.push();  */
                } else { // session true error true
                    logout(1);
                }
            } else {
                logout();
            }
        },
        error: function(xhr, textStatus, error) {
            alert(error);
        }
    });
}

var SettingsAray;

function Settings_FSave()
{
    $("#menucycle1").val(Sett_Confirm[0].Menu_Cycle1);
    $("#menucycle2").val(Sett_Confirm[0].Menu_Cycle2);
    $("#datepickerinput1").val(Sett_Confirm[0].Menu_start1);
    $("#datepickerinput2").val(Sett_Confirm[0].Menu_start2);
}
function savesettingstab()
{
    if (isValid) {
        if ($("#frmUpdateSettings").valid()) {
            SettingsAray = [];
            if ($("#ctfcheckbox").is(":checked"))
            {
                $("#ctfcheckbox").attr('value', 1);
            }
            else
            {
                $("#ctfcheckbox").attr('value', 0);
            }

            if ($("#invoicecheckbox").is(":checked"))
            {
                $("#invoicecheckbox").attr('value', 1);
            }
            else
            {
                $("#invoicecheckbox").attr('value', 0);
            }
            if ($("#minimumcardpayment").val() == "")
                $("#minimumcardpayment").val(0);
            if ($("#vatinputvalue").val() == "")
                $("#vatinputvalue").val(0);
            if ($("#dcfvalueinput").val() == "")
                $("#dcfvalueinput").val(0);
            if ($("#ccfvalueinput").val() == "")
                $("#ccfvalueinput").val(0);
            if ($("#txtStsRefundFee").val() == "")
                $("#txtStsRefundFee").val(0);
            if($("#txtUnspecifiedCost").val() == "")
            	$("#txtUnspecifiedCost").val(0);
            //  var datechange1 = $("#datepickerinput1").val()
            // var datechange2 = $("#datepickerinput2").val();
            //  if (datechange1 == datechange2)
            //      return false
            var date1 = $("#datepickerinput1").val().split("/");
            var date2 = $("#datepickerinput2").val().split("/");
            SettingsAray.push({
                "con_cater_menu_settings_id": contractsettingid1,
                "menu_cycles": $("#menucycle1").val(),
                "menu_start_date": date1[2] + "-" + date1[1] + "-" + date1[0],
                "menu_sequence": sequence1
            }, {
                "con_cater_menu_settings_id": contractsettingid2,
                "menu_cycles": $("#menucycle2").val(),
                "menu_start_date": date2[2] + "-" + date2[1] + "-" + date2[0],
                "menu_sequence": sequence2
            });
            if (($("#menucycle1").val() != Sett_Confirm[0].Menu_Cycle1) || ($("#menucycle2").val() != Sett_Confirm[0].Menu_Cycle2) || ($("#datepickerinput1").val() != Sett_Confirm[0].Menu_start1) || ($("#datepickerinput2").val() != Sett_Confirm[0].Menu_start2))
            {
                Confirm_box("Warning, this may impact existing meal orders", "Warning!", Settings_Save, Settings_FSave);
                /*bootbox.dialog({
                 message: "Warning, this may impact existing meal orders",
                 title: "Warning!",
                 buttons: {
                 danger: {
                 label: "Cancel",
                 className: "btn",
                 callback: function() {
                 $("#menucycle1").val(Sett_Confirm[0].Menu_Cycle1);
                 $("#menucycle2").val(Sett_Confirm[0].Menu_Cycle2);
                 $("#datepickerinput1").val(Sett_Confirm[0].Menu_start1);
                 $("#datepickerinput2").val(Sett_Confirm[0].Menu_start2);
                 }
                 },
                 success: {
                 label: "Continue",
                 className: "btn-primary",
                 callback: function() {
                 Settings_Save();
                 }
                 }
                 }
                 });*/
            }
            else
                Settings_Save();
        }
    }
}

function Settings_Save()
{
    $("#btnSaveSettings").attr("disabled", true).text('Saving');
    var url = BACKENDURL + "customeradmin/update_contract_settings";
    var data = {
        session_id: localStorage["SESSIONID"],
        contract_id: localStorage["contractid"],
        tminus: $("#timunsinput").val(),
        min_card_pay: $("#minimumcardpayment").val(),
        trans_fee_status: $("#ctfcheckbox").val(),
        vat: $("#vatinputvalue").val(),
        dc_fee: $("#dcfvalueinput").val(),
        cc_fee: $("#ccfvalueinput").val(),
        refund_fee: $("#txtStsRefundFee").val(),
        adult_invoice: $("#invoicecheckbox").val(),
        us_mealcost: $("#txtUnspecifiedCost").val(),
        menu_data: SettingsAray
    };
    MakeAjaxCall(url, data, settingscheck);
}
function settingscheck(data)
{
    $("#divsettingsErr").hide();
    if (data.error == 0)
        $("#btnSaveSettings").text('Saved');
    else if (data.error_msg == unAthMsg)
        logout(1);
    else {
        $("#divsettingsErr").html(data.error_msg).show();
        $("#btnSaveSettings").attr("disabled", true).text('Save');
    }

}
/****************** eof GlobalSettings ***********/
var schoolnamevalue;
var optionArr = [];
var id;
var production_status;
var status;
var schoolid;
var prodOrigValue;
/************** edit school****************/
var nIncrement = 0;
var tabschoolid, prodid;
var bStatus;
function editSchool() {
    $('input[id^="schoolname"]').bind("keypress keyup", function() {
        $("#btnSave").removeAttr("disabled");
        $("#btnSave").text("Save");
    })
    var ncount = 0;
    $('input[id^="schoolname"]').bind("blur", function() {
        schoolnamevalue = "";
        id = "";
        id = $(this).attr("id");
        schoolnamevalue = $(this).val();
        appendToArray(10);
    })

    $('select[id^="selectprod"]').change(function() {
        $("#btnSave").text("Save");
        $("#btnSave").removeAttr("disabled");
        id = $(this).attr("id");
        appendToArray(10);
    });
    $('button[id^="AddschoolDisablebtn"]').click(function() {
        bStatus = "";
        id = $(this).attr("id");
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
                    callback: function() {
                        var getValue = id.substring(19);
                        tabschoolid = $("#schoolid" + getValue + "").val();
                        prodid = $("#selectprod" + getValue + "").val();
                        nIncrement = 0;
                        $.each(SchoolArray, function(index, value) {
                            if ((tabschoolid == value.prodid) && (index != getValue)) {
                                nIncrement++;
                            }
                        })
                        if (nIncrement >= 1) {
                            if ($("#" + id).text() == " Disable Servery") {
                                $("#disableBtnDialogBox").modal('show');
                                //return false;
                            }
                            else {
                                bStatus = 1;
                                updateSchoolstatus();
                            }
                        } else {
                            if ($("#" + id).text() == " Enable Servery") {
                                bStatus = 1;
                            } else {
                                bStatus = 0;
                            }
                            updateSchoolstatus();
                        }
                    }
                }
            }
        });
    });
}

function updateSchoolstatus() {

    $.ajax({
        url: BACKENDURL + "customeradmin/update_school_status",
        type: "post",
        data: {
            session_id: localStorage["SESSIONID"],
            school_id: tabschoolid,
            contract_id: localStorage.getItem("contractid"),
            status: bStatus
        },
        dataType: "json",
        crossDomain: true,
        success: function(data) {
            if (data.session_status) {
                if (data.error == 0) {
                    // value.status = bStatus;
                } else {
                    logout(1);
                }
            } else
                logout();
            getSchool();
        },
        error: function(xhr, textStatus, error) {
            //alert(error);
        }
    });
}

var nFirst = 0;
function appendToArray(nVal) {
    //alert("---------appendToArray-------");
    getRow(nVal);
    if (!change) {
        commonArray();
    }
}

var change;
function getRow(count) {

    change = false;
    var numb = id.substring(count);
    var NewID = "schoolname" + numb;
    contract_id = localStorage["contractid"]
    school_name = $("#schoolname" + numb + "").val();
    selectpro = $("#selectprod" + numb + "").val();
    schoolid = $("#schoolid" + numb + "").val();
    if (schoolid == selectpro) {
        production_status = 1;
    } else {
        production_status = 0;
    }
    if ($("#AddschoolDisablebtn" + numb + "").text() == "Disable") {
        status = 0;
    } else {
        status = 1; //enable
    }
    prodOrigValue = $("#selectprod" + numb + " :selected").text();
    $.each(optionArr, function(index, value) {
        if (schoolid == value.school_id) {
            value.contract_id = contract_id;
            value.school_name = school_name;
            value.production_id = selectpro;
            value.production_status = production_status;
            value.status = status;
            change = true;
        }
    })

}
var bajax = false;
function commonArray(count) {

    if ((schoolid != null) || (school_name != null) || (selectpro != null)) {
        optionArr.push({
            "school_id": schoolid,
            "contract_id": contract_id,
            "school_name": school_name,
            "production_id": selectpro,
            "production_status": production_status
        });
    }
}
var svalue, sname, sprodschool, sStatus, sprodstatus;
function passSchoolValToDb() {
    if ($("#schoolEdit").valid()) {
        $("#btnSave").attr("disabled", true);
        $("#btnSave").text('Saving').attr("disabled", true);
        $.ajax({
            url: BACKENDURL + "customeradmin/edit_schools",
            type: "post",
            data: {
                session_id: localStorage["SESSIONID"],
                contract_id: localStorage["contractid"],
                schools_edit: optionArr
            },
            dataType: "json",
            crossDomain: true,
            success: function(data) {
                if (data.session_status) {
                    if (data.error == 0) {
                        $("#btnSave").text('Saved').attr("disabled", true);
                        bajax = false;
                    } else {
                        logout(1);
                    }
                } else
                    logout();
                getSchool();
                optionArr = [];
            },
            error: function(xhr, textStatus, error) {
                alert(error);
            }
        });
    }
}
/********** MySchools ***********/

function loadMySchools() {
    $("#frmMySchools").data('validator').resetForm();
    $(".error").removeClass("error");
    $("#lblExportAdminError", "#SchoolTab").hide();
    $.support.cors = true;
    //First get all schools by passing the contract id.
    $.ajax({
        url: BACKENDURL + "customeradmin/get_schools",
        type: "post",
        data: {
            session_id: localStorage["SESSIONID"],
            contract_id: localStorage["contractid"]
        },
        dataType: "json",
        crossDomain: true,
        success: function(data) {
            if (data.session_status) {
                if (data.error == 0) {
                    if (data.schools_res.length > 0) {
                        $('#ddlSchools').empty();
                        $('#selectschool').empty();
                        var schoolsStr = "";
                        var selectedStr = " Selected ";
                        for (var nCount = 0; nCount < data.schools_res.length; nCount++) {
                            disableStr = (data.schools_res[nCount].status == "0") ? " (disabled)" : "";
                            schoolsStr += "<option value=" + data.schools_res[nCount].school_id + " " + selectedStr + ">" + data.schools_res[nCount].school_name + disableStr + "</option>";
                            selectedStr = "";
                        }
                        $('#ddlSchools').append(schoolsStr);
                        populateSchoolDetails();
                    }
                    else
                    {
                        $("#frmMySchools", "#SchoolTab").hide();
                        $(".form-inline", "#SchoolTab").hide();
                        $("#btnSaveSchool", "#SchoolTab").hide();
                        $("#lblSchoolError", "#SchoolTab").html("Administrator is not assigned to any schools").show();
                    }
                } else {
                    logout(1);
                }
            } else {
                logout();
            }
        },
        error: function(xhr, textStatus, error) {
            alert(error);
        }
    });
}

function populateSchoolDetails() {
    $("#frmMySchools").data('validator').resetForm();
    $(".error").removeClass("error");
    $("#lblCloseSchoolMail").addClass('hide');
    //$("#btnSaveSchool").text('Save');
    var school_id = $('#ddlSchools').val();
    $.support.cors = true;
    $.ajax({
        url: BACKENDURL + "customeradmin/get_school_details",
        type: "post",
        data: {
            session_id: localStorage["SESSIONID"],
            contract_id: localStorage["contractid"],
            school_id: school_id
        },
        dataType: "json",
        crossDomain: true,
        success: function(data) {
            if (data.session_status) {
                if (data.error == 0) {
                    // School status check
                    var Schl_status = data.schools_res[0].closed_status;
                    if (Schl_status == 0)
                    {

                        $("#btnAdminOpenSchool").addClass("hide");
                        $("#btnAdminCloseSchool").removeClass("hide");
                        $("#lblCloseSchoolWarn").text("").addClass("hide");
                        $("#dp_SchEnddate", "#divCloseSchl").val("");
                        $("#txtReason", "#divCloseSchl").val("");
                    }
                    else
                    {
                        var Status_Msg = "";
                        $("#btnAdminCloseSchool").addClass("hide");
                        $("#btnAdminOpenSchool").removeClass("hide");
                        $("#lblCloseSchoolWarn").text("").removeClass("hide");
                        Status_Msg = "<strong>Remember! </strong>" + data.schools_res[0].psn + " was closed by " + data.schools_res[0].closed_by + ", because '" + data.schools_res[0].closed_reason + "'. Last day of closure is " + data.schools_res[0].closed_till;
                        $("#lblCloseSchoolWarn").append(Status_Msg);
                    }
                    customerModuleAccess("AL4MYSC", 1);
                    $('#ddlAvailableUsers', "#divAUsers").empty();
                    $('#ddlSelectedUsers', "#divSelUsers").empty();
                    var Users = "";
                    var selectedUsers = "";
                    $("#lblSchoolError").html('').hide();
                    $("#btnSaveSchool").attr("disabled", true).text('Saved');
                    $("#hdnSchoolId").val(data.schools_res[0].sid);
                    $("#spnTxtSchoolId").text(localStorage["contractkey"] + "/");
                    $("#txtSchoolId").val(data.schools_res[0].sk);
                    $("#txtSchoolName").val(data.schools_res[0].sn);
                    $("#txtProdSchoolName").val(data.schools_res[0].psn);
                    $("#txtAddress1").val(data.schools_res[0].a1);
                    $("#txtAddress2").val(data.schools_res[0].a2);
                    $("#txtAddress3").val(data.schools_res[0].a3);
                    $("#txtCity").val(data.schools_res[0].c);
                    $("#txtCounty").val(data.schools_res[0].co);
                    $("#txtPostcode").val(data.schools_res[0].po);
                    $("#txtOffC1Name").val(data.schools_res[0].c1n);
                    $("#txtOffC2Name").val(data.schools_res[0].c2n);
                    $("#txtOffC1Email").val(data.schools_res[0].c1e);
                    $("#txtOffC2Email").val(data.schools_res[0].c2e);
                    $("#txtOffC1Telephone").val(data.schools_res[0].c1p);
                    $("#txtOffC2Telephone").val(data.schools_res[0].c2p);
                    for (var nCount = 0; nCount < data.schools_res[0].school_admins.length; nCount++)
                    {
                        var telephoneNum = (data.schools_res[0].school_admins[nCount].telephone != null) ? data.schools_res[0].school_admins[nCount].telephone : "";
                        var workTelphone = (data.schools_res[0].school_admins[nCount].work_telephone != null) ? data.schools_res[0].school_admins[nCount].work_telephone : "";
                        var mobNumber = (data.schools_res[0].school_admins[nCount].mobile_number != null) ? data.schools_res[0].school_admins[nCount].mobile_number : "";
                        var profileName = (data.schools_res[0].school_admins[nCount].profile_name != null) ? "(" + data.schools_res[0].school_admins[nCount].profile_name + ")" : "";
                        if (data.schools_res[0].school_admins[nCount].school_admin == null) {
                            Users += "<option value=" + data.schools_res[0].school_admins[nCount].user_id + " msg='Email:" + data.schools_res[0].school_admins[nCount].user_email + "<br>" + telephoneNum + "<br>" + workTelphone + "<br>" + mobNumber + "' >" + data.schools_res[0].school_admins[nCount].first_name + " " + data.schools_res[0].school_admins[nCount].last_name + "," + " " + data.schools_res[0].school_admins[nCount].username + profileName + "</option>";
                        }
                        else
                            selectedUsers += "<option value=" + data.schools_res[0].school_admins[nCount].user_id + " msg='Email:" + data.schools_res[0].school_admins[nCount].user_email + "<br>" + telephoneNum + "<br>" + workTelphone + "<br>" + mobNumber + "' >" + data.schools_res[0].school_admins[nCount].first_name + " " + data.schools_res[0].school_admins[nCount].last_name + "," + " " + data.schools_res[0].school_admins[nCount].username + profileName + "</option>";
                    }

                    $('#ddlAvailableUsers', "#divAUsers").append(Users);
                    $('#ddlSelectedUsers', "#divSelUsers").append(selectedUsers);
                    $("#ddlAvailableUsers option").length == 0 ? $("#btnAddUsers").attr("disabled", "disabled") : $("#btnAddUsers").removeAttr("disabled");
                    $("#ddlSelectedUsers option").length == 0 ? $("#btnRemoveUsers").attr("disabled", "disabled") : $("#btnRemoveUsers").removeAttr("disabled");
                    $("#ddlAvailableUsers option").mouseover(function(e) {
                        showPopOver("#ddlAvailableUsers", "right", e.target)
                    });
                    $("#ddlAvailableUsers option").mouseout(function(e) {
                        hidePopOver("#ddlAvailableUsers");
                    });
                    $("#ddlSelectedUsers option").mouseover(function(e) {
                        showPopOver("#ddlSelectedUsers", "left", e.target);
                    });
                    $("#ddlSelectedUsers option").mouseout(function(e) {
                        hidePopOver("#ddlSelectedUsers");
                    });
                    for (var i = 0; i < 7; i++) {
                        if (data.schools_res[0].school_classes[i] != undefined) {
                            if (data.schools_res[0].school_classes[i].ys == 1) {
                                //$("#txtY" +i).val(data.schools_res[0].school_classes[i].yl);
                                $("#chkY" + i).attr('checked', true);
                                if (i == 0)
                                    $("#aY" + i).html(" Reception").prepend('<li class="icon-ok-sign icon-white"></i>');
                                else
                                    $("#aY" + i).text(" Year " + i).prepend('<li class="icon-ok-sign icon-white"></i>');
                                if ($("#txtY" + i).attr('disabled')) {
                                    $("#txtY" + i).prop('disabled', false);
                                }
                                $("#txtY" + i).val(data.schools_res[0].school_classes[i].yl);
                            } else {
                                //$("#txtY" +i).val(data.schools_res[0].school_classes[i].yl).attr('disabled','disabled');
                                $("#chkY" + i).attr('checked', false);
                                if (i == 0) {
                                    $("#txtY" + i).val("Reception").attr('disabled', 'disabled');
                                    $("#aY" + i).text(" Reception (inactive)").prepend('<i class="icon-remove-sign icon-white"></i>');
                                } else {
                                    $("#txtY" + i).val("Year " + i).attr('disabled', 'disabled');
                                    $("#aY" + i).text(" Year " + i + " (inactive)").prepend('<li class="icon-remove-sign icon-white"></i>');
                                }



                            }

                            for (var j = 1; j <= 6; j++) {
                                if (data.schools_res[0].school_classes[i]['c' + j + 's'] == 1) {
                                    // $("#txtY" +i +"C"+j).val(data.schools_res[0].school_classes[i]['c' +j+ 'n']);
                                    $("#chkY" + i + "C" + j).attr('checked', true);
                                    if ($("#txtY" + i + "C" + j).attr('disabled')) {
                                        $("#txtY" + i + "C" + j).prop('disabled', false);
                                        $("#txtY" + i + "C" + j).val(data.schools_res[0].school_classes[i]['c' + j + 'n'])
                                    } else {
                                        $("#txtY" + i + "C" + j).val(data.schools_res[0].school_classes[i]['c' + j + 'n'])
                                    }
                                } else {
                                    if (data.schools_res[0].school_classes[i]['c' + j + 'n'] == null)
                                        $("#txtY" + i + "C" + j).val("Class " + j).attr('disabled', 'disabled');
                                    else
                                        $("#txtY" + i + "C" + j).val(data.schools_res[0].school_classes[i]['c' + j + 'n']).attr('disabled', 'disabled');
                                    $("#chkY" + i + "C" + j).attr('checked', false);
                                }
                            }
                        } else {
                            //$("#txtY" +i).val("").attr('disabled','disabled');
                            $("#chkY" + i).attr('checked', false);
                            if (i == 0) {
                                $("#txtY" + i).val("Reception").attr('disabled', 'disabled');
                                $("#aY" + i).text(" Reception (inactive)").prepend('<li class="icon-remove-sign icon-white"></i>');
                            } else {
                                $("#txtY" + i).val("Year " + i).attr('disabled', 'disabled');
                                $("#aY" + i).text(" Year " + i + " (inactive)").prepend('<li class="icon-remove-sign icon-white"></i>');
                            }

                            $("#txtY" + i + "C1").val("Class 1").attr('disabled', 'disabled');
                            $("#chkY" + i + "C1").attr('checked', false);
                            $("#txtY" + i + "C2").val("Class 2").attr('disabled', 'disabled');
                            $("#chkY" + i + "C2").attr('checked', false);
                            $("#txtY" + i + "C3").val("Class 3").attr('disabled', 'disabled');
                            $("#chkY" + i + "C3").attr('checked', false);
                            $("#txtY" + i + "C4").val("Class 4").attr('disabled', 'disabled');
                            $("#chkY" + i + "C4").attr('checked', false);
                            $("#txtY" + i + "C5").val("Class 5").attr('disabled', 'disabled');
                            $("#chkY" + i + "C5").attr('checked', false)
                            $("#txtY" + i + "C6").val("Class 6").attr('disabled', 'disabled');
                            $("#chkY" + i + "C6").attr('checked', false)
                        }
                    }


                    //alert("Populating the details....");
                } else {
                    logout(1);
                }
            } else {
                logout();
            }
        },
        error: function(xhr, textStatus, error) {
            // alert(error);
        }
    });
}
function showPopOver(ctl, placement, opt) {
    $(ctl).popover({
        placement: placement,
        title: "<p style='word-wrap:break-word;'>" + $(opt).text() + "</p>",
        content: "<p style='word-wrap:break-word;'>" + $(opt).attr("msg") + "</p>"
    }).popover('show');
}
function hidePopOver(ctl) {
    $(ctl).popover('destroy');
}
function saveSchoolDetails() {
    if ($("#frmMySchools").valid()) {
        $.support.cors = true;
        $("#lblSchoolError").html('').hide();
        $("#btnSaveSchool").attr("disabled", true).text('Saving');
        var formdata = $('#frmMySchools').serialize(); // url encoded string
        $('input[disabled]').each(function() {
            formdata = formdata + '&' + $(this).attr('name') + '=' + $(this).val();
        });
        selUsers = "";
        $('#ddlSelectedUsers option', "#frmMySchools").each(function() {
            selUsers = selUsers + $(this).attr('value') + ",";
        });
        selUsers = (selUsers.length > 0) ? selUsers.substr(0, selUsers.length - 1) : "";
        formdata = formdata + '&' + 'selUsers=' + selUsers;
        //sf = sf.replace(/"/g, '\"');         // be sure all double codes  are escaped
        //sf = '{"' + sf.replace(/&/g, '","'); // start "object", replace tupel delimiter &
        //sf = sf.replace(/=/g, '":"') + '"}';
        //var formdata = eval("(" + sf + ")"); 

        $.ajax({
            url: BACKENDURL + "customeradmin/save_school_details",
            type: "post",
            data: {
                session_id: localStorage["SESSIONID"],
                contract_id: localStorage["contractid"],
                form_data: formdata
            },
            dataType: "json",
            crossDomain: true,
            success: function(data) {
                if (data.session_status) {
                    if (data.error == 0) {
                        $("#btnSaveSchool").attr("disabled", true).text('Saved');
                        for (var i = 0; i < 7; i++) {
                            if ($("#chkY" + i).is(":checked")) {
                                if (i == 0)
                                    $("#aY" + i).text(" Reception").prepend('<li class="icon-ok-sign icon-white"></i>');
                                else
                                    $("#aY" + i).text(" Year " + i).prepend('<li class="icon-ok-sign icon-white"></i>');
                            } else {
                                if (i == 0)
                                    $("#aY" + i).text(" Reception (inactive)").prepend('<li class="icon-remove-sign icon-white"></i>');
                                else
                                    $("#aY" + i).text(" Year " + i + " (inactive)").prepend('<li class="icon-remove-sign icon-white"></i>');
                            }
                        }
                    } else if (data.error_msg == unAthMsg)
                        logout(1)
                    else
                    {
                        $("#lblSchoolError").html(data.error_msg).show();
                        $("#btnSaveSchool").removeAttr("disabled").text('Save');
                    }
                } else {
                    logout();
                }
            },
            error: function(xhr, textStatus, error) {
                alert(error);
            }
        });
    }
}
function exportAdminPupilIdS(e) {
    e.preventDefault();
    $("#lblExportAdminError", "#SchoolTab").hide();
    var schoolId = $('#ddlSchools', "#SchoolTab").val();
    $("#spnGenExportAdmin", "#SchoolTab").show();
    $.support.cors = true;
    $.ajax({
        url: BACKENDURL + "data_upload/export_data",
        type: "post",
        data: {
            session_id: localStorage["SESSIONID"],
            contract_id: localStorage["contractid"],
            school_id: schoolId,
            export_type: "pupils"
        },
        dataType: "json",
        async: false,
        crossDomain: true,
        success: function(data) {
            if (data.session_status) {
                if (data.error == 0) {
                    var url = BACKENDURL + "common/download_file";
                    window.open(url + "/" + localStorage["SESSIONID"] + "/pupils/" + data.temp_file);
                    $("#spnGenExportAdmin", "#SchoolTab").hide();
                } else {
                    $("#lblExportAdminError", "#SchoolTab").html(data.error_msg).show();
                    $("#spnGenExportAdmin", "#SchoolTab").hide();
                }
            } else
                logout();
        },
        error: function(xhr, textStatus, error) {
            alert(error);
        }
    });
}
/************************ End of Schools My Schools**********************/
/************************Pupil Import**********************/

function setPupilFileNameFromPath(path) {
    $("#txtFakeFile").val(path);
}

function openImportPupil(type) {
    var activeFormType = 'pupils';
    var isUploadSuccess = false;
    $("#lblImportError", "#divActionImportPupil").text('').hide();
    $("#lblImportSuccess", "#divActionImportPupil").text('').hide();
    $("#divUploadfile", "#divActionImportPupil").show().html("<table><tr><td >Upload File:</td><td nowrap='nowrap'><div style='position:relative'><input id='fileupload' type='file' name='files[]' onchange='setPupilFileNameFromPath(this.value);' style='width:350px;position:relative; -moz-opacity:0; text-align: right;opacity: 0; filter:alpha(opacity: 0);z-index:2'><div style='position:absolute;top:0px;left:0px;z-index:1'><input readonly type='text' id='txtFakeFile' value='Select file...'/> &nbsp;<button class='btn btn-primary' style='margin-top:-10px;'>Choose ...</button></div></div></td></tr></table>");
    $("#divProgressBar", "#divActionImportPupil").hide();
    $("#btnImportPupilFinish", "#divActionImportPupil").hide();
    $("#btnImportPupilSubmit", "#divActionImportPupil").show();
    $("#btnImportPupilCancel", "#divActionImportPupil").show();
    $("#xdivImportPupil", "#divActionImportPupil").show();
    $('#divProgressBar .bar').css('width', '0%');
    $('#fileupload table tbody tr.template-download').remove();
    $('#fileupload').fileupload({
        dataType: 'json',
        url: BACKENDURL + "data_upload/import_data",
        formData: {
            "session_id": localStorage["SESSIONID"],
            "import_type": activeFormType,
            "customer_id": localStorage["CUSTOMERID"],
            "user_id": localStorage["USERID"],
            "contract_id": localStorage.getItem("contractid")
        },
        add: function(e, data) {
            $("#btnImportPupilSubmit", "#divActionImportPupil").off('click').on('click', function() {
                // alert(data.error_msg);
                var goUpload = true;
                var uploadFile = data.files[0];
                if (uploadFile == "") {
                    $("#lblImportError", "#divActionImportPupil").text('Please select a file to upload').show();
                    goUpload = false;
                }
                if (!(/\.(xlsx|xls|csv)$/i).test(uploadFile.name)) {
                    $("#lblImportError", "#divActionImportPupil").text('You must select an excel (.xlsx or .xls or .csv) file only').show();
                    goUpload = false;
                }
                if (uploadFile.size > 1000000) { // 1mb
                    $("#lblImportError", "#divActionImportPupil").text('Please upload a smaller file, max size is 1 MB').show();
                    goUpload = false;
                }
                if (goUpload == true) {
                    $("#lblImportError", "#divActionImportPupil").text('').hide();
                    $("#divProgressBar", "#divActionImportPupil").show();
                    $("#divUploadfile", "#divActionImportPupil").hide();
                    $("#divProgressVal", "#divActionImportPupil").attr('style', 'width: 0%');
                    $("#btnImportPupilSubmit", "#divActionImportPupil").hide();
                    $("#btnImportPupilCancel", "#divActionImportPupil").hide();
                    $("#xdivImportPupil", "#divActionImportPupil").hide();
                    $('#divProgressBar .bar').css('width', '0%');
                    data.submit();
                }
                //else {
                //    alert(error);
                //}
            });
        },
        progressall: function(e, data) {
            // alert(data.error_msg);
            var progress = parseInt(data.loaded / data.total * 100, 10);
            //if(progress <=80)
            $('#divProgressBar .bar').css('width', progress + '%');
            $("#btnImportPupilFinish", "#divActionImportPupil").addClass("disabled").show().off('click');
            if (progress == 100 && isUploadSuccess) {
                $("#btnImportPupilFinish", "#divActionImportPupil").removeClass("disabled").off('click').on('click', {
                    f: activeFormType
                }, finishImport);
                isUploadSuccess = false;
            }
        },
        success: function(data) {
            if (data.error) {
                $("#lblImportSuccess", "#divActionImportPupil").text("").hide();
                $("#lblImportError", "#divActionImportPupil").text("Unsuccessful. Failure reason: " + data.error_msg).show();
            } else {
                $("#lblImportError", "#divActionImportPupil").text("").hide();
                $("#lblImportSuccess", "#divActionImportPupil").text("Success. File was succesfully uploaded and imported into the system").show();
            }
            $("#btnImportPupilFinish", "#divActionImportPupil").removeClass("disabled").off('click').on('click', {
                f: activeFormType
            }, finishImport);
            isUploadSuccess = true;
        },
        done: function(e, data) {
            if (data.result.error) {
                $("#lblImportSuccess", "#divActionImportPupil").text("").hide();
                $("#lblImportError", "#divActionImportPupil").text("Unsuccessful. Failure reason: " + data.result.error_msg).show();
            } else {
                $("#lblImportError", "#divActionImportPupil").text("").hide();
                $("#lblImportSuccess", "#divActionImportPupil").text("Success. File was succesfully uploaded and imported into the system").show();
            }
            $("#btnImportPupilFinish", "#divActionImportPupil").removeClass("disabled").off('click').on('click', {
                f: activeFormType
            }, finishImport);
            isUploadSuccess = true;
            if (data.result.error) {
                $("#lblImportSuccess", "#divActionImportPupil").text("").hide();
                $("#lblImportError", "#divActionImportPupil").text("Unsuccessful. Failure reason: " + data.result.error_msg).show();
            } else {
                $("#lblImportError", "#divActionImportPupil").text("").hide();
                $("#lblImportSuccess", "#divActionImportPupil").text("Success. File was succesfully uploaded and imported into the system").show();
            }
            $("#btnImportPupilFinish", "#divActionImportPupil").removeClass("disabled").off('click').on('click', {
                f: activeFormType
            }, finishImport);
            isUploadSuccess = true;
            // alert(data.error_msg);
            $("#btnImportPupilSubmit", "#divActionImportPupil").off('click').on('click', function() {
                $("#lblImportError", "#divActionImportPupil").text('Please select a file to upload').show();
            });
        }
    });
}
$("#btnImportPupilSubmit", "#divActionImportPupil").click(function() {
    $("#lblImportError", "#divActionImportPupil").text('Please select a file to upload').show();
});
function loadImportPupilsData() {
    $.support.cors = true;
    $.ajax({
        url: BACKENDURL + "customeradmin/get_pupil_import",
        type: "post",
        data: {
            session_id: localStorage["SESSIONID"],
            contract_id: localStorage["contractid"]
        },
        dataType: "json",
        crossDomain: true,
        success: function(data) {
            if (data.session_status) {
                if (data.error == 0) { // session true error false
                    if (data.pupil_import_res.length > 0)
                    {
                        $("#btnExportPupilId", "#PupilImport").attr("href", "#divActionExportPupil");
                        $("#divActionImportPupil").modal('hide');
                        $("#tablePagination", "#PupilImport").remove();
                        var nCurrRecRound = ($("#currPageNumber", "#divPupilImportData").val() != undefined) ? ($("#currPageNumber", "#divPupilImportData").val() - 1) : 0;
                        $("#tablePagination", "#divPupilImportData").remove();
                        var content = "You must first add a set of pupils to the system by using the import button on the right hand side.";
                        if (data.pupil_import_res.length > 0)
                            //US 312 item 7 space alignment
                            content = "<table class='table table-hover table-striped edfm-bordered-table' id='pupiltblHeader' style='margin-bottom: 10px;'><thead><tr><th>Filename</th><th>File Imported Date</th><th>Imported By</th><th>Pupil IDs Added</th><th>Pupil IDs Updated</th></tr></thead><tbody>";
                        for (var nCount = 0; nCount < data.pupil_import_res.length; nCount++)
                            content += "<tr ><td>" + data.pupil_import_res[nCount].file_name + "</td> <td>" + data.pupil_import_res[nCount].cdate + "</td><td>" + data.pupil_import_res[nCount].importedby + "</td><td>" + data.pupil_import_res[nCount].New_Records_Added + "</td><td>" + data.pupil_import_res[nCount].Existing_Records_Amended + "</td></tr>";
                        if (data.pupil_import_res.length > 0)
                            content += "</tbody></table>";
                        $("#divPupilImportData", "#PupilImport").html(content);
                        if (data.pupil_import_res.length > 10)
                            $("#divPupilImportData").tablePagination({
                                currPage: nCurrRecRound + 1
                            });
                    }
                    else
                    {
                        $("#btnExportPupilId", "#PupilImport").attr("disabled", true);
                    }
                } else { // session true error true
                    logout(1);
                }
            } else {
                logout();
            }
        },
        error: function(xhr, textStatus, error) {
            alert(error);
        }
    });
}

function openExportPupilids() {
    $("#btnExportPupilSubmit", "#divActionExportPupil").removeAttr('disabled');
    $('#ddlSchoolExport', "#divActionExportPupil").removeAttr('disabled');
    $('#btnCancelExport', "#divActionExportPupil").removeAttr('disabled');
    $('#xdivExportPupil', "#divActionExportPupil").show();
    $("#spnGen", "#divActionExportPupil").hide();
    $("#lblExportError", "#divActionExportPupil").html('').hide();
    $.support.cors = true;
    //First get all schools by passing the contract id.
    $.ajax({
        url: BACKENDURL + "customeradmin/get_schools",
        type: "post",
        data: {
            session_id: localStorage["SESSIONID"],
            contract_id: localStorage["contractid"]
        },
        dataType: "json",
        crossDomain: true,
        success: function(data) {
            if (data.session_status) {
                if (data.error == 0) {
                    $('#ddlSchoolExport', "#divActionExportPupil").empty();
                    var schoolsStr = "";
                    var selectedStr = " Selected ";
                    for (var nCount = 0; nCount < data.schools_res.length; nCount++) {
                        disableStr = (data.schools_res[nCount].status == "0") ? " (disabled)" : "";
                        schoolsStr += "<option value=" + data.schools_res[nCount].school_id + " " + selectedStr + ">" + data.schools_res[nCount].school_name + disableStr + "</option>";
                        selectedStr = "";
                    }
                    $('#ddlSchoolExport', "#divActionExportPupil").append(schoolsStr);
                } else {
                    logout(1);
                }
            } else {
                logout();
            }
        },
        error: function(xhr, textStatus, error) {
            alert(error);
        }
    });
}

$("#btnExportPupilSubmit", "#divActionExportPupil").click(function() {
    var schoolId = $('#ddlSchoolExport', "#divActionExportPupil").val();
    $("#btnExportPupilSubmit", "#divActionExportPupil").attr('disabled', 'disabled');
    $('#ddlSchoolExport', "#divActionExportPupil").attr('disabled', 'disabled');
    $('#btnCancelExport', "#divActionExportPupil").attr('disabled', 'disabled');
    $('#xdivExportPupil', "#divActionExportPupil").hide();
    $("#spnGen", "#divActionExportPupil").show();
    $.support.cors = true;
    $.ajax({
        url: BACKENDURL + "data_upload/export_data",
        type: "post",
        data: {
            session_id: localStorage["SESSIONID"],
            contract_id: localStorage["contractid"],
            school_id: schoolId,
            export_type: "pupils"
        },
        dataType: "json",
        async: false,
        crossDomain: true,
        success: function(data) {
            if (data.session_status) {
                if (data.error == 0) {
                    var url = BACKENDURL + "common/download_file";
                    window.open(url + "/" + localStorage["SESSIONID"] + "/pupils/" + data.temp_file);
                    $("#divActionExportPupil").modal("hide");
                } else {
                    $("#lblExportError", "#divActionExportPupil").html(data.error_msg).show();
                    $("#btnExportPupilSubmit", "#divActionExportPupil").removeAttr('disabled');
                    $('#ddlSchoolExport', "#divActionExportPupil").removeAttr('disabled');
                    $('#btnCancelExport', "#divActionExportPupil").removeAttr('disabled');
                    $('#xdivExportPupil', "#divActionExportPupil").show();
                    $("#spnGen", "#divActionExportPupil").hide();
                }
            } else {
                logout();
            }
        },
        error: function(xhr, textStatus, error) {
            alert(error);
        }
    });
});
/************************End of Pupil Import**********************/


/************************* School Documents **********************/
var schools_documents_str = "";
var schools_rep_comm_stats = "";
var Document_def_status = 0;
var isNewUpload = false;
function loadMyDocuments() {
    $.support.cors = true;
    var school_id = $("#selectAllSchools").val();
    //First get all school documents and populate it
    $.ajax({
        url: BACKENDURL + "customeradmin/get_school_documents",
        type: "post",
        data: {
            session_id: localStorage["SESSIONID"],
            contract_id: localStorage["contractid"],
            school_id: $("#selectAllSchools").val(),
            hide_comp: $("#chkHideComplete").is(':checked')
        },
        dataType: "json",
        crossDomain: true,
        success: function(data) {
            if (data.session_status) {
                if (data.error == 0) {
                    //Populate the schools...
                    if (data.schools_documents_res[0].schools_res.length > 0) {
                        schools_documents_str = "";
                        var selectedStr = " Selected ";
                        $('#selectAllSchools', "#documents").empty();
                        for (var nCount = 0; nCount < data.schools_documents_res[0].schools_res.length; nCount++) {
                            disableStr = (data.schools_documents_res[0].schools_res[nCount].status == "0") ? " (disabled)" : "";
                            schools_documents_str += "<option value=" + data.schools_documents_res[0].schools_res[nCount].school_id + " " + selectedStr + ">" + data.schools_documents_res[0].schools_res[nCount].school_name + disableStr + "</option>";
                            selectedStr = "";
                        }
                        $('#selectAllSchools', "#documents").append("<option value='0'>All Schools</option>");
                        $('#selectAllSchools', "#documents").append(schools_documents_str);
                        $('#selectAllSchools', "#documents").val(school_id);
                    } else {
                        $(".form-inline", "#documents").hide();
                        $("#WelcomeNoteDocuments", "#documents").hide();
                        $("#divDocumentErr", "#documents").show();
                    }
                    //Populate the document status....
                    schools_rep_comm_stats = "";
                    for (var nCount = 0; nCount < data.schools_documents_res[1].school_document_status_res.length; nCount++) {
                        if (nCount == 0)
                            Document_def_status = data.schools_documents_res[1].school_document_status_res[nCount].data_value_id;
                        schools_rep_comm_stats += "<option value=" + data.schools_documents_res[1].school_document_status_res[nCount].data_value_id + ">" + data.schools_documents_res[1].school_document_status_res[nCount].data_value + "</option>";
                    }
                    $("#ddlDocumentStatus", "#divUploadUpdate").empty().append(schools_rep_comm_stats).unbind('change');
                    //populate the document list....
                    var nCurrRecRound = ($("#currPageNumber", "#documents").val() != undefined) ? ($("#currPageNumber", "#documents").val() - 1) : 0;
                    $("#tablePagination", "#documents").remove();
                    $("#tblUploaddashBoard > tbody > tr").remove();
                    if (data.schools_documents_res[0].schools_res.length > 0) {
                        if (data.schools_documents_res[2].school_rep.length > 0) {
                            $("#WelcomeNoteDocuments", "#documents").hide();
                            $("#divfilter", "#documents").show();
                            for (var nCount = 0; nCount < data.schools_documents_res[2].school_rep.length; nCount++) {
                                var documentId = data.schools_documents_res[2].school_rep[nCount].school_documents_id;
                                var commentText = (data.schools_documents_res[2].school_rep[nCount].comm_status == 0) ? "<span id='new" + documentId + "'><i class='icon-envelope'></i><span style='color:black;font-weight:bold;'> New! </span></span>" + data.schools_documents_res[2].school_rep[nCount].comment : data.schools_documents_res[2].school_rep[nCount].comment;
                                var deleteModalText = (data.schools_documents_res[2].school_rep[nCount].status == "Completed") ? "divUploadDelete" : "divUploadDeleteFail";
                                var btn = "";
                                if (data.schools_documents_res[2].school_rep[nCount].status == "Not Started")
                                {
                                    btn += "<span class='label label-important' id='rpSt" + documentId + "'>" + data.schools_documents_res[2].school_rep[nCount].status + "</span> ";
                                }
                                else if (data.schools_documents_res[2].school_rep[nCount].status == "Completed")
                                {
                                    btn += "<span class='label label-success' id='rpSt" + documentId + "'>" + data.schools_documents_res[2].school_rep[nCount].status + "</span> ";
                                }
                                else if (data.schools_documents_res[2].school_rep[nCount].status == "In Progress")
                                {
                                    btn += "<span class='label label-info' id='rpSt" + documentId + "'>" + data.schools_documents_res[2].school_rep[nCount].status + "</span> ";
                                }

                                var tblUpload = "<tr><td><h3 style='margin:0;'>" + data.schools_documents_res[2].school_rep[nCount].file_name + "</h3><p style='font-size:14px;color:black;margin-bottom:0px'>Uploaded on " + data.schools_documents_res[2].school_rep[nCount].cdate + " by " + data.schools_documents_res[2].school_rep[nCount].username + " in " + data.schools_documents_res[2].school_rep[nCount].school_name + "&nbsp&nbsp" + btn + "</p></i><p style='color:black'>" + commentText + "</p> </td><td style='text-align:right;'><a href='javascript:void(0);' onclick='javascript:downloadDocument(this," + documentId + ");' style='text-decoration: none !important;' id='admindownload'>Download &nbsp;&nbsp;<i class='icon-download'> </i></a><br /><span id='upRe_" + documentId + "'><a href='#divUploadUpdate' data-toggle='modal' onclick='javascript:loadUpdateDocument(" + documentId + ", " + data.schools_documents_res[2].school_rep[nCount].document_status + ");' style='text-decoration: none !important;' id='adminupdate'>Update &nbsp;&nbsp;<i class='icon-edit'></i></a></span><br /><span id='dlRe_" + documentId + "'><a href='#" + deleteModalText + "' onclick='javascript:loadDeleteDocument(" + documentId + ",\"" + deleteModalText + "\");' style='text-decoration: none !important;' data-toggle='modal' id='admindelete'>Delete &nbsp;&nbsp;<i class='icon-remove'></i></a></span></td></tr>";
                                $("#tblUploaddashBoard  tbody:last").append(tblUpload);
                            }
                            $("#tblUploaddashBoard").show();
                            if (data.schools_documents_res[2].school_rep.length > 10) {
                                if (isNewUpload)
                                {
                                    nCurrRecRound = 0;
                                }
                                $("#divfilter").tablePagination({
                                    currPage: nCurrRecRound + 1
                                });
                            }
                            $("#UploadDeleteYes", "").off('click').bind("click", deleteDocument);
                            isNewUpload = false;
                            customerModuleAccess('AL3PDOC', 1);
                        } else
                            $("#WelcomeNoteDocuments", "#documents").show();
                    }
                } else
                    logout(1);
            } else {
                logout();
            }
        },
        error: function(xhr, textStatus, error) {
            alert(error);
        }
    });
}

function openDocumentImport() {
    $("#UploadDocumentClose").show();
    var isUploadSuccess = false;
    var activeFormType = 'school_document_admin';
    $("#lblImportError", "#divUploadNewImport").text('').hide();
    $("#lblImportSuccess", "#divUploadNewImport").text('').hide();
    $("#divUploadNewdocuments", "#divUploadNewImport").show().html("<table cellpadding='4'><tr><td >Upload File</td><td nowrap='nowrap'><div style='position:relative'><input id='fileupload_document' type='file' name='files[]' onchange='setFileNameFromPath(this.value);' style='width:350px;position:relative; -moz-opacity:0; text-align: right;opacity: 0; filter:alpha(opacity: 0);z-index:2'><div style='position:absolute;top:0px;left:0px;z-index:1'><input readonly type='text' id='txtFakeFile_document' value='Select file...'/> &nbsp;<button class='btn btn-primary' style='margin-top:-10px;'>Choose ...</button></div></div></td></tr><tr><td >School</td><td nowrap='nowrap'><span class='select-wrap' ><select id='selectSchool'></select></span></td></tr><tr><td >Comment</td><td nowrap='nowrap'><textarea id='txaComments' placeholder='Enter text....' style='width:88%;height:100%;resize:none;margin-top:15px;'></textarea></td></tr></table>");
    $('#selectSchool', "#divUploadNewdocuments").append(schools_documents_str);
    $("#divProgressBar", "#divUploadNewImport").hide();
    $("#btnDocumentImportFinish", "#divUploadNewImport").hide();
    $("#btnDocumentImportSubmit", "#divUploadNewImport").show();
    $("#xdivUploadNewImport", "#divUploadNewImport").show();
    $('#divProgressBar .bar').css('width', '0%');
    $('#fileupload_document table tbody tr.template-download').remove();
    $('#fileupload_document').fileupload({
        dataType: 'json',
        url: BACKENDURL + "data_upload/import_data",
        add: function(e, data) {
            $("#btnDocumentImportSubmit", "#divUploadNewImport").off('click').on('click', function() {
                var goUpload = true;
                var uploadFile = data.files[0];
                if (uploadFile == "") {
                    $("#lblImportError", "#divUploadNewImport").text('Please select a file to upload').show();
                    goUpload = false;
                }
                if (uploadFile.size > 1000000) { // 1mb
                    $("#lblImportError", "#divUploadNewImport").text('Please upload a smaller file, max size is 1 MB').show();
                    goUpload = false;
                }
                if (goUpload == true) {
                    $("#lblImportError", "#divUploadNewImport").text('').hide();
                    $("#lblImportSuccess", "#divUploadNewImport").hide();
                    $("#divProgressBar", "#divUploadNewImport").show();
                    $("#divUploadNewdocuments", "#divUploadNewImport").hide();
                    $("#divProgressVal", "#divUploadNewImport").attr('style', 'width: 0%');
                    $("#btnDocumentImportSubmit", "#divUploadNewImport").hide();
                    $("#xdivUploadNewImport", "#divUploadNewImport").hide();
                    $('#divProgressBar .bar').css('width', '0%');
                    data.formData = {
                        "session_id": localStorage["SESSIONID"],
                        "import_type": activeFormType,
                        "customer_id": localStorage["CUSTOMERID"],
                        "user_id": localStorage["USERID"],
                        "contract_id": localStorage["contractid"],
                        "school_id": $("#selectSchool", "#divUploadNewdocuments").val(),
                        "comments": $("#txaComments", "#divUploadNewdocuments").val(),
                        "document_status": Document_def_status
                    };
                    data.submit();
                }
                //else {
                //    alert(error);
                //}
            });
        },
        progressall: function(e, data) {
            $("#UploadDocumentClose").hide();
            var progress = parseInt(data.loaded / data.total * 100, 10);
            $('#divProgressBar .bar').css('width', progress + '%');
            $("#btnDocumentImportFinish", "#divUploadNewImport").addClass("disabled").show().off('click');
            if (progress == 100 && isUploadSuccess) {
                $("#btnDocumentImportFinish", "#divUploadNewImport").removeClass("disabled").off('click').on('click', {
                    f: activeFormType
                }, finishImport);
                isUploadSuccess = false;
            }
        },
        success: function(data) {
            isNewUpload = true;
            if (data.error) {
                $("#lblImportSuccess", "#divUploadNewImport").text("").hide();
                $("#lblImportError", "#divUploadNewImport").text("Unsuccessful. Failure reason: " + data.error_msg).show();
            } else {
                $("#lblImportError", "#divUploadNewImport").text("").hide();
                $("#lblImportSuccess", "#divUploadNewImport").text("Success. File was succesfully uploaded and imported into the system").show();
            }
            $("#btnDocumentImportFinish", "#divUploadNewImport").removeClass("disabled").off('click').on('click', {
                f: activeFormType
            }, finishImport);
            isUploadSuccess = true;
        },
        done: function(e, data) {
            if (data.result.error) {
                $("#lblImportSuccess", "#divUploadNewImport").text("").hide();
                $("#lblImportError", "#divUploadNewImport").text("Unsuccessful. Failure reason: " + data.result.error_msg).show();
            } else {
                $("#lblImportError", "#divUploadNewImport").text("").hide();
                $("#lblImportSuccess", "#divUploadNewImport").text("Success. File was succesfully uploaded and imported into the system").show();
            }
            $("#btnDocumentImportFinish", "#divUploadNewImport").removeClass("disabled").off('click').on('click', {
                f: activeFormType
            }, finishImport);
            isUploadSuccess = true;
            // alert(data.error_msg);
            $("#btnDocumentImportSubmit", "#divUploadNewImport").off('click').on('click', function() {
                $("#lblImportError", "#divUploadNewImport").text('Please select a file to upload').show();
            });
        }
    });
}
$("#btnDocumentImportSubmit", "#divUploadNewImport").click(function() {
    $("#lblImportError", "#divUploadNewImport").text('Please select a file to upload').show();
});
function setFileNameFromPath(path) {

    $("#txtFakeFile_document").val(path);
}

function loadUpdateDocument(documentId, document_status) {
    $.support.cors = true;
    $.ajax({
        url: BACKENDURL + "customeradmin/get_school_document_comments",
        type: "post",
        data: {
            session_id: localStorage["SESSIONID"],
            contract_id: localStorage["contractid"],
            school_document_id: documentId
        },
        dataType: "json",
        crossDomain: true,
        success: function(data) {
            if (data.session_status) {
                if (data.error == 0) {
                    $("#hdnDocumentId", "#divUploadUpdate").val(documentId);
                    $("#btnDocumentSave", "#divUploadUpdate").attr("disabled", "disabled").text('Saved');
                    //Populate the status
                    $("#ddlDocumentStatus", "#divUploadUpdate").val(document_status).unbind('change').change(function() {
                        if ($(this).val() != document_status)
                            $("#btnDocumentSave", "#divUploadUpdate").removeAttr("disabled").text('Save').unbind("click").bind("click", updateDocumentStatus);
                        else
                            $("#btnDocumentSave", "#divUploadUpdate").attr("disabled", "disabled").unbind("click").text('Saved');
                    });
                    var comm_str = "";
                    var pull_str = "pull-left";
                    var color = "alert alert-success";
                    var textcolor = "text_success";
                    //populate comments.
                    for (var i = 0; i < data.schools_rep_comm_res.length; i++) {
                        pull_str = (data.schools_rep_comm_res[i].role_name == "User") ? "pull-left" : "pull-right";
                        color = (data.schools_rep_comm_res[i].role_name == "User") ? "alert alert-success" : "alert alert-info";
                        textcolor = (data.schools_rep_comm_res[i].role_name == "User") ? "text_success" : "text_info";
                        comm_str += "<div class='row-fluid'><div class='span9 " + pull_str + "'><pre class='" + color + "'><label class='" + textcolor + "' >" + data.schools_rep_comm_res[i].comment_text + "</label><i class='icon-comment'></i> <label class='" + textcolor + "'  style='font-size:11px;margin-top: -20px;margin-left: 22px;'>By " + data.schools_rep_comm_res[i].username + " on " + data.schools_rep_comm_res[i].cdate + "</label></pre></div></div>";
                    }
                    $("#divDocumentComments", "#divUploadUpdate").html(comm_str);
                    //updating new icon...
                    $("#new" + documentId, "#documents").hide();
                    $("#btnSaveComments", "#divUploadUpdate").attr("disabled", "disabled").unbind("click");
                    //bind the comments textbox.
                    $("#txaComments", "#divUploadUpdate").val('').bind("keypress keyup focus", function() {
                        if ($("#txaComments", "#divUploadUpdate").val() != "")
                            $("#btnSaveComments", "#divUploadUpdate").removeAttr("disabled").text('Post').unbind("click").bind("click", insertDocumentComments);
                        else
                            $("#btnSaveComments", "#divUploadUpdate").attr("disabled", "disabled").unbind("click");
                    });
                } else
                    logout(1);
            } else
                logout();
        },
        error: function(xhr, textStatus, error) {
            alert(error);
        }
    });
}

function updateDocumentStatus() {
    $.support.cors = true;
    $("#btnDocumentSave", "#divUploadUpdate").attr("disabled", true).text('Saving');
    var documentId = $("#hdnDocumentId", "#divUploadUpdate").val();
    var document_status = $("#ddlDocumentStatus", "#divUploadUpdate").val();
    $.ajax({
        url: BACKENDURL + "customeradmin/update_school_document_status",
        type: "post",
        data: {
            session_id: localStorage["SESSIONID"],
            contract_id: localStorage["contractid"],
            school_document_id: documentId,
            status: document_status
        },
        dataType: "json",
        crossDomain: true,
        success: function(data) {
            if (data.session_status) {
                if (data.error == 0) {
                    //	        			$("#divUploadUpdate").modal('hide');
                    //	        			loadMyDocuments();
                    //Update the document status....

                    var rpStatus = $("#ddlDocumentStatus", "#divUploadUpdate").children(':selected').text();
                    $("#rpSt" + documentId, "#documents").text(rpStatus);
                    if (rpStatus == "Not Started")
                    {

                        $("#rpSt" + documentId, "#documents").removeClass();
                        $("#rpSt" + documentId, "#documents").addClass('label label-important');
                    }
                    else if (rpStatus == "Completed")
                    {
                        if ($("#chkHideComplete").is(':checked')) {
                            loadMyDocuments();
                        }
                        $("#rpSt" + documentId, "#documents").removeClass();
                        $("#rpSt" + documentId, "#documents").addClass('label label-success');
                    }

                    else if (rpStatus == "In Progress")
                    {

                        $("#rpSt" + documentId, "#documents").removeClass();
                        $("#rpSt" + documentId, "#documents").addClass('label label-info');
                    }

                    var deleteModalText = (rpStatus == "Completed") ? "divUploadDelete" : "divUploadDeleteFail";
                    $("#dlRe_" + documentId, "#documents").html("<a href='#" + deleteModalText + "' onclick='javascript:loadDeleteDocument(" + documentId + ",\"" + deleteModalText + "\");' style='text-decoration: none !important;' data-toggle='modal'>Delete &nbsp;&nbsp;<i class='icon-remove'></i></a>");
                    $("#upRe_" + documentId, "#documents").html("<a href='#divUploadUpdate' data-toggle='modal' onclick='javascript:loadUpdateDocument(" + documentId + ", " + document_status + ");' style='text-decoration: none !important;'>Update &nbsp;&nbsp;<i class='icon-edit'></i></a>");
                    $("#btnDocumentSave", "#divUploadUpdate").attr("disabled", true).text('Saved');
                    $("#ddlDocumentStatus", "#divUploadUpdate").unbind('change').change(function() {
                        if ($(this).val() != document_status)
                            $("#btnDocumentSave", "#divUploadUpdate").removeAttr("disabled").text('Save').bind("click", updateDocumentStatus);
                        else
                            $("#btnDocumentSave", "#divUploadUpdate").attr("disabled", "disabled").unbind("click").text('Saved');
                    });
                } else
                    logout(1);
            } else
                logout();
        },
        error: function(xhr, textStatus, error) {
            alert(error);
        }
    });
}

function insertDocumentComments() {

    $.support.cors = true;
    var document_status = $("#ddlDocumentStatus", "#divUploadUpdate").val();
    $.ajax({
        url: BACKENDURL + "customeradmin/insert_document_comments",
        type: "post",
        data: {
            session_id: localStorage["SESSIONID"],
            contract_id: localStorage["contractid"],
            school_document_id: $("#hdnDocumentId", "#divUploadUpdate").val(),
            comments: $("#txaComments", "#divUploadUpdate").val()
        },
        dataType: "json",
        crossDomain: true,
        success: function(data) {
            if (data.session_status) {
                if (data.error == 0) {
                    $("#divUploadUpdate").modal('hide');
                    loadMyDocuments();
                } else
                    logout(1);
            } else
                logout();
        },
        error: function(xhr, textStatus, error) {
            alert(error);
        }
    });
}


function loadDeleteDocument(documentId, modalId) {
    $("#hdnDocumentId", "#" + modalId).val(documentId);
}

function deleteDocument() {
    var documentId = $("#hdnDocumentId", "#divUploadDelete").val();
    $.support.cors = true;
    $.ajax({
        url: BACKENDURL + "customeradmin/delete_document",
        type: "post",
        data: {
            session_id: localStorage["SESSIONID"],
            contract_id: localStorage["contractid"],
            school_document_id: documentId
        },
        dataType: "json",
        crossDomain: true,
        success: function(data) {
            if (data.session_status) {
                if (data.error == 0) {
                    $("#divUploadDelete").modal('hide');
                    $("#UploadDeleteIdY").modal('show');
                    loadMyDocuments();
                }
            } else
                logout();
        },
        error: function(xhr, textStatus, error) {
            alert(error);
        }
    });
}

function downloadDocument(f, documentId) {
    var url = BACKENDURL + "common/download_file";
    window.open(url + "/" + localStorage["SESSIONID"] + "/school_document/" + documentId);
}

/************************* end of School Documents **********************/
/************************* start Account Reports*************************/
function loadAReports() {
    var PaymentStartDate, PaymentEndDate, activeTab, temp_file;
    var today = new Date();
    var t = today.getDate() + "/" + (today.getMonth() + 1) + "/" + today.getFullYear();
    /* $('#divFStartdate').on('changeDate', function(ev) {
     
     }).data('datepicker'); */
    $('#divFStartdate').datepicker().on('changeDate', function(ev) {
        $('#divFStartdate').datepicker('hide');
    });
    $('#divFEnddate').datepicker().on('changeDate', function(ev) {
        $('#divFEnddate').datepicker('hide');
    });
    $('#divPStartdate').datepicker({
        endDate: new Date()
    }).on('changeDate', function(ev) {
        $('#divPStartdate').datepicker('hide');
    });
    ;
    $('#divPEnddate').datepicker().on('changeDate', function(ev) {
        $('#divPEnddate').datepicker('hide');
    });
    ;
    //Assigning values to date picker
    $('#divPStartdate').data({
        date: t
    }).datepicker('update');
    $('#divPEnddate').data({
        date: t
    }).datepicker('update');
    $('#divFStartdate').data({
        date: t
    }).datepicker('update');
    $('#divFEnddate').data({
        date: t
    }).datepicker('update');
    //today date asigning
    $("#txtPStart").val(t);
    $("#txtPEnd").val(t);
    $("#txtFStart").val(t);
    $("#txtFEnd").val(t);
    $("#alert").hide();
    /* $("#SchoolAdminRefundsTab").bind("click",AccountRefundTabClick); */
    //on click of refunds in accounts
    $("#SchoolAdminRefundsTab").click(function() {
        customerModuleAccess("AL4CDRS", 1);
        $("#cardRefundsfrm").data('validator').resetForm();
        $(".error").removeClass("error");
        $("#btnRefundsGo", "#divAssignRefund").attr("disabled", "disabled");
        AccountRefundTabClick();
    });
    /*$('#divPStartdate').datepicker().on('changeDate', function(ev){
     PaymentStartDate=ev.date.valueOf();
     PstartDate= $("#txtPStart").val().split("/");
     });
     $('#divPEnddate').datepicker().on('changeDate', function(ev){
     PaymentEndDate=ev.date.valueOf();
     PendDate= $("#txtPEnd").val().split("/");
     });*/
    //on click of export pupil balance
    $("#btnExp_pplbal").on("click", function(e) {
        $("#spnPplBalGen", "#divReports").show();
        //e.preventDefault(); 
setTimeout(function(e) {		
	   $.support.cors = true;
        $.ajax({
            url: BACKENDURL + "data_upload/export_data",
            type: "post",
            data: {
                session_id: localStorage["SESSIONID"],
                export_type: "pupil_balances"
            },
            dataType: "json",
            async: false,
            crossDomain: true,
            success: function(data) {
                if (data.session_status) {
                    if (data.error == 0) {
                        $("#spnPplBalGen", "#divReports").hide();
                        $("#divReportMsg").hide();
                        var url = BACKENDURL + "common/download_file";
                        document.location=url + "/" + localStorage["SESSIONID"] + "/pupil_balances/" + data.temp_file;
                    } else {
                        $("#spnPplBalGen", "#divReports").hide();
                        $("#divReportMsg").show().text(data.error_msg).css("display","inline-block");;
                    }
                } else {
                    logout();
                }
            },
            error: function(xhr, textStatus, error) {
                alert(error);
            }
        });
	}, 1000)	
    });
    //on click of export payment items
    $("#btnPaymentItem").on("click", function(e) {
        //$("#divExportPaymentItems").append('<img src="img/ajax-loader2.gif" style="display:inline;margin:0px"/>');
        $("#spnGen", "#divReports").show();
        //e.preventDefault();

        setTimeout(function(e) {
            var PstartDate = $("#txtPStart").val().split("/");
            var PaymentStartDate = (new Date(PstartDate[2], (PstartDate[1] - 1), PstartDate[0])).valueOf();
            var PendDate = $("#txtPEnd").val().split("/");
            var PaymentEndDate = (new Date(PendDate[2], (PendDate[1] - 1), PendDate[0])).valueOf();
            //comparing start and end dates for validation
            if (PaymentEndDate < PaymentStartDate) {
                $("#divReportMsg").show().text('The end date can not be earlier than the start date').css("display","inline-block");
            } else {
                $("#divReportMsg").hide();
                $.support.cors = true;
                $.ajax({
                    url: BACKENDURL + "data_upload/export_data",
                    type: "post",
                    data: {
                        session_id: localStorage["SESSIONID"],
                        contract_id: localStorage.getItem("contractid"),
                        start_date: PstartDate[2] + "-" + PstartDate[1] + "-" + PstartDate[0],
                        end_date: PendDate[2] + "-" + PendDate[1] + "-" + PendDate[0],
                        export_type: "payment_items"
                    },
                    dataType: "json",
                    async: false,
                    crossDomain: true,
                    success: function(data) {
                        if (data.session_status) {
                            if (data.error == 0) {
                                $("#divReportMsg").hide();
                                var url = BACKENDURL + "common/download_file";
                                window.open(url + "/" + localStorage["SESSIONID"] + "/payment_items/" + data.temp_file);
                            } else {
                                $("#divReportMsg").show().text(data.error_msg).css("display","inline-block");;
                            }
                        } else {
                            logout();
                        }
                    },
                    error: function(xhr, textStatus, error) {
                        alert(error);
                    }
                });
            }
            $("#spnGen", "#divReports").hide();
        }, 1000)
    });
    /* var FstartDate= $("#txtFStart").val().split("/");
     var FullfilmentStartDate=(new Date(FstartDate[2],(FstartDate[1]-1),FstartDate[0])).valueOf();
     var FendDate= $("#txtFEnd").val().split("/");
     var FullfilmentEndDate=(new Date(FendDate[2],(FendDate[1]-1),FendDate[0])).valueOf(); */
    /* $('#divFStartdate').datepicker().on('changeDate', function(ev){
     FullfilmentStartDate=ev.date.valueOf();
     FstartDate= $("#txtFStart").val().split("/");
     });
     $('#divFEnddate').datepicker().on('changeDate', function(ev){
     FullfilmentEndDate=ev.date.valueOf();
     FendDate= $("#txtFEnd").val().split("/");
     }); */
    //onclick of export order items
    $("#btnExportOrder").on("click", function(e) {
        $("#spnGenorder", "#divReports").show();
        //e.preventDefault();
        setTimeout(function(e) {
            var FstartDate = $("#txtFStart").val().split("/");
            var FullfilmentStartDate = (new Date(FstartDate[2], (FstartDate[1] - 1), FstartDate[0])).valueOf();
            var FendDate = $("#txtFEnd").val().split("/");
            var FullfilmentEndDate = (new Date(FendDate[2], (FendDate[1] - 1), FendDate[0])).valueOf();
            //comparing start and end dates for validation
            if (FullfilmentEndDate < FullfilmentStartDate) {
                $("#divReportMsg").show().text('The end date can not be earlier than the start date').css("display","inline-block");;
            } else {
                $("#divReportMsg").hide();
                $.support.cors = true;
                $.ajax({
                    url: BACKENDURL + "data_upload/export_data",
                    type: "post",
                    data: {
                        session_id: localStorage["SESSIONID"],
                        contract_id: localStorage.getItem("contractid"),
                        start_date: FstartDate[2] + "-" + FstartDate[1] + "-" + FstartDate[0],
                        end_date: FendDate[2] + "-" + FendDate[1] + "-" + FendDate[0],
                        export_type: "order_items"
                    },
                    dataType: "json",
                    async: false,
                    crossDomain: true,
                    success: function(data) {
                        if (data.session_status) {
                            if (data.error == 0) {
                                $("#divReportMsg").hide();
                                var url = BACKENDURL + "common/download_file";
                                $('<form action="' + url + "/" + localStorage["SESSIONID"] + "/order_items/" + data.temp_file + '" style="display:none;"></form>').appendTo('body').submit();
                                //window.open(url + "/" + localStorage["SESSIONID"] + "/order_items/" + data.temp_file);
                            } else {
                                $("#divReportMsg").show().text(data.error_msg).css("display","inline-block");;
                            }
                        } else {
                            logout();
                        }
                    },
                    error: function(xhr, textStatus, error) {
                        alert(error);
                    }
                });
            }
            $("#spnGenorder", "#divReports").hide();
        }, 1000)
    });
    function AccountRefundTabClick() {
        //DE 522 removing button on initial load of the page
        //$("#btnContinue", "#divAssignRefund").attr("disabled", "disabled").text('Confirm Refund').show();
        if (setTimeout(function() {
            $('#divCardFullHistory', '#divRefunds').is(':visible')
        }, 0.5)) {
            $('#divCardFullHistory').css("display", "none");
            assignRefundDisplay();
        }
        else if ($('#divFinishRefund').is(':visible')) {
            $('#divFinishRefund').hide();
            assignRefundDisplay();
        }
        else if ($("#noHistoryMsg", "#divRefunds").is(':visible')) {
            assignRefundDisplay();
        }
        if ($("#divAssignRefund #divARefund>div[id='divARtablePagination']>table tbody tr").length > 0) {
            $("#tblAssignRefund tbody tr").remove();
            $("#divARefund", "#divAssignRefund").hide();
            $("#txtAvailableRefund").val('');
            $('#divFinishRefund').hide();
        }
        $("#tablePagination").remove();
    }

    //assige refund display
    function assignRefundDisplay() {
	/*  console.log("here")  */
		$("#spnAssignRefundPhone").addClass('visible-phone');
		$("#spnAssignRefundPhone").removeClass('hidden-phone');
		$("#spnFinishedPhone").addClass('hidden-phone');
		$("#spnFinishedPhone").removeClass('visible-phone');
        $("#refundStatus").show();
        $("#divAssignRefund").show();
        $("#spnAssignRefund", '#divRefunds').css({
            "font-weight": "bold",
            "color": "black"
        });
        $("#spnFinished", '#divRefunds').css({
            "font-weight": "normal",
            "color": "#8e8e8e"
        });
        $("#divProgresBar", "#divRefunds").css('width', '4%');
        $("#txtTransactionID").val('');
        $('#noHistoryMsg', '#divRefunds').hide();
        $('#noRecordsMsg', '#divAssignRefund').hide();
    }
}
/************************* end Account Reports*************************/
/*************************start Account Refund*************************/
function loadACardRefunds() {
    var tmpres;
    var currentbal = 0;
    var amount = 0, availableRefund = 0;
    var rfdFee = 0;
    $("#cardRefundsfrm").validate({
        rules: {
            txtTransactionID:
                    {
                        required: true
                    }
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

            error.insertAfter("#validationgobutton");
        }

    });
    $("#txtTransactionID", "#divAssignRefund").bind("keyup keydown keypress paste", function() {
        $("#btnRefundsGo", "#divAssignRefund").removeAttr("disabled");
    })
    if ($("#txtTransactionID").val() != "") {
        $("#btnRefundsGo", "#divAssignRefund").removeAttr("disabled");
    }
    $("#btnRefundsGo").click(function(e) {
        if ($("#cardRefundsfrm").valid()) {
            e.preventDefault();
            var url = BACKENDURL + "customeradmin/get_card_search_pupils";
            var data = {
                session_id: localStorage["SESSIONID"],
                transaction_id: $("#txtTransactionID").val(),
                contract_id: localStorage.getItem("contractid")
            };
            MakeAjaxCall(url, data, searchUserPupilsSuccess);
        }
    })
    function searchUserPupilsSuccess(data) {
        if (data.error == 0) {
            $("#tablePagination", "#divAssignRefund").remove();
            $("#tblAssignRefund  tbody:last").empty();
            var tblPupil = "";
            $("#btnRefundsGo", "#divAssignRefund").attr("disabled", "disabled");
            currentbal = 0;
            amount = 0;
            tmpres = JSON.stringify(data);
            //tmpresPupils = JSON.parse(tmpres);
            if (data.search_pupils_res.length > 0) {
                var pupilCount = data.search_pupils_res.length;
                refundDataArr = [];
                //trnFee = (data.search_pupils_res[0].trans_fee_status == "0") ? 0 : data.search_pupils_res[0].transaction_fee;
                rfdFee = (data.search_pupils_res[0].trans_fee_status == 0) ? 0 : data.search_pupils_res[0].refund_fee;
                for (var nCount = 0; nCount < data.search_pupils_res.length; nCount++) {
                    //currentbal+=parseFloat((data.search_pupils_res[nCount].trans_amount<data.search_pupils_res[nCount].card_balance)?data.search_pupils_res[nCount].trans_amount:data.search_pupils_res[nCount].card_balance);
                    //alert(currentbal)
                    currentbal += parseFloat(data.search_pupils_res[nCount].card_balance);
                    amount += parseFloat(data.search_pupils_res[nCount].trans_amount);
                    availableRefund = (currentbal > amount) ? amount : currentbal;
                    var pupilId = data.search_pupils_res[nCount].pupil_id;
                    var freeMeals = (data.search_pupils_res[nCount].fsm == 0) ? "<img src='img/fail.png' style='width:20px;display:block;margin:0 auto;' >" : "<img src='img/pass.png' style='width:20px;display:block;margin:0 auto;'>";
                    var adult = (data.search_pupils_res[nCount].adult == 1) ? "<i class='icon-user' ></i>" : "";
                    //var fee = (data.search_pupils_res[nCount].trans_fee_status == 0) ? 0 : data.search_pupils_res[nCount].refund_fee;
                    tblPupil += "<tr><td nowrap='nowrap'><div style= 'padding-top:6px' id='transId" + pupilId + "'>" + data.search_pupils_res[nCount].transaction_id + "</div></td>";
                    tblPupil += "<td nowrap='nowrap'><div style= 'padding-top:6px' id='date" + pupilId + "'>" + data.search_pupils_res[nCount].date + "</div></td>";
                    tblPupil += "<td nowrap='nowrap'><div style= 'padding-top:6px' id='pupilmName" + pupilId + "'>" + data.search_pupils_res[nCount].fname + " " + data.search_pupils_res[nCount].mname + "" + data.search_pupils_res[nCount].lname + "</div></td>";
                    tblPupil += "<td nowrap='nowrap'><div style= 'padding-top:6px' id='pupilid" + pupilId + "'>" + data.search_pupils_res[nCount].pupil_id + "&nbsp;&nbsp;" + adult + "</div></td>";
                    tblPupil += "<td ><label style= 'padding-top: 6px;' id='freemeals" + pupilId + "'>" + freeMeals + "</label></td>";
                    tblPupil += "<td><div style='padding-top: 6px;'id='SchoolNameLabel" + pupilId + "'>" + data.search_pupils_res[nCount].school_name + "</div></td>";
                    tblPupil += "<td><div style='padding-top: 6px;'id='username" + pupilId + "'>" + data.search_pupils_res[nCount].username + "</div></td>";
                    tblPupil += "<td nowrap='nowrap'><div style= 'padding-top:6px' id='AMOUNT" + pupilId + "'>£" + data.search_pupils_res[nCount].trans_amount + "</div></td>";
                    tblPupil += "<td nowrap='nowrap'><div style= 'padding-top:6px' id='fee" + pupilId + "'>£" + data.search_pupils_res[0].transaction_fee + "</div></td>";
                    tblPupil += "<td nowrap='nowrap'><div style= 'padding-top:6px' class='input-prepend' id='cardbal" + pupilId + "'><span class='add-on' >&#163;</span><input  style='width: 85px'  type='text' name='cardBal' id='cardBal' value=" + data.search_pupils_res[nCount].card_balance + " disabled/></div></td>";
                    tblPupil += "</tr>";
                }
                $("#tblAssignRefund  tbody:last").append(tblPupil);
                if (data.search_pupils_res.length > 10) {
                    $("#tablePagination").html('');
                    $("#divARtablePagination").tablePagination({
                        rowsPerPage: 10
                    });
                }
                if (rfdFee == 0)
                    $("#divRefundFee").hide();
                else
                    $("#divRefundFee").show();
                //if (data.search_pupils_res[nCount].card_balance > 0) {
                /*refundDataArr.push({
                 'transaction_id': $("#txtTransactionID").val(),
                 'refund_amount': currentbal.toFixed(2),
                 'refund_fee': rfdFee
                 });*/
                // }
                $("#txtRefundFee").val(rfdFee);
                // $("#txtAvailableRefund").val(availableRefund.toFixed(2));
                $("#txtAvailableRefund").val(data.available_refund);
                // $("#txtTRefund").val(((availableRefund.toFixed(2)) - rfdFee).toFixed(2));
                $("#txtTRefund").val(((data.available_refund) - rfdFee).toFixed(2));
                $("#divARefund", '#divAssignRefund').show();
                $('#noRecordsMsg').hide();
                $("#btnContinue", "#divAssignRefund").attr("disabled", "disabled").show()
                if ($("#txtTRefund").val() > 0)
                    $("#btnContinue", "#divAssignRefund").removeAttr("disabled");
                if ($("#txtAvailableRefund").val() <= 0) {
                    $("#txtRefundFee").val(0);
                    $("#txtTRefund").val(0);
                    $("#btnContinue", "#divAssignRefund").attr("disabled", "disabled");
                }

            } else {
                $("#divARefund", '#divAssignRefund').hide();
                $('#noRecordsMsg').show();
                $("#btnContinue", "#divAssignRefund").attr("disabled", "disabled").hide();
            }

        } else
            logout(1);
    }
    $("#btnContinue", "#divAssignRefund").bind("click", cardRefund);
    function cardRefund() {
        $("#btnContinue", "#divAssignRefund").attr('disabled', 'disabled').text("Processing...");
        var url = BACKENDURL + "customeradmin/make_card_refund";
        var data = {
            session_id: localStorage["SESSIONID"],
            transaction_id: $("#txtTransactionID").val()
        };
        MakeAjaxCall(url, data, cardRefundSuccess);
    }
    function cardRefundSuccess(data) {
        if ($("#divFinishSuccrErrMsg").hasClass('alert-success'))
            $("#divFinishSuccrErrMsg").removeClass('alert-success');
        if ($("#divFinishSuccrErrMsg").hasClass('alert-error'))
            $("#divFinishSuccrErrMsg").removeClass('alert-error');
        if (data.error == 0) {
            $("#tblFinishRefund  tbody:last").empty();
            var tblPupil = " ";
            $("#divAssignRefund").hide();
            $("#divProgresBar", "#divRefunds").css('width', '100%');
            $("#spnFinished", '#divRefunds').css({
                "font-weight": "bold",
                "color": "black"
            });
            $("#spnPaymentDetails", '#divRefunds').css({
                "font-weight": "normal",
                "color": "#8e8e8e"
            });
			$("#spnAssignRefundPhone").removeClass('visible-phone');
			$("#spnAssignRefundPhone").addClass('hidden-phone');
			$("#spnFinishedPhone").removeClass('hidden-phone');
			$("#spnFinishedPhone").addClass('visible-phone');
            //trnFee = (data.card_refund_res[0].trans_fee_status == "0") ? 0 : data.card_refund_res[0].transaction_fee;
            if (data.card_refund_res.length > 0) {
                for (var nCount = 0; nCount < data.card_refund_res.length; nCount++) {
                    var pupilId = data.card_refund_res[nCount].pupil_id;
                    var freeMeals = (data.card_refund_res[nCount].fsm == 0) ? "<img src='img/fail.png' style='width:20px;display:block;margin:0 auto;' >" : "<img src='img/pass.png' style='width:20px;display:block;margin:0 auto;'>";
                    var adult = (data.card_refund_res[nCount].adult == 1) ? "<i class='icon-user' ></i>" : "";
                    tblPupil += "<tr><td nowrap='nowrap'><div style= 'padding-top:6px' id='tId" + pupilId + "'>" + data.card_refund_res[nCount].payment_id + "</div></td>";
                    tblPupil += "<td nowrap='nowrap'><div style= 'padding-top:6px' id='dt" + pupilId + "'>" + data.card_refund_res[nCount].cdate + "</div></td>";
                    tblPupil += "<td nowrap='nowrap'><div style= 'padding-top:6px' id='pupilN" + pupilId + "'>" + data.card_refund_res[nCount].fname + " " + data.card_refund_res[nCount].mname + "" + data.card_refund_res[nCount].lname + "</div></td>";
                    tblPupil += "<td nowrap='nowrap'><div style= 'padding-top:6px' id='pid" + pupilId + "'>" + data.card_refund_res[nCount].pupil_id + "&nbsp;&nbsp;" + adult + "</div></td>";
                    tblPupil += "<td ><label style= 'padding-top: 6px;' id='freeM" + pupilId + "'>" + freeMeals + "</label></td>";
                    tblPupil += "<td><div style='padding-top: 6px;'id='SchoolName" + pupilId + "'>" + data.card_refund_res[nCount].school_name + "</div></td>";
                    tblPupil += "<td ><div style= 'padding-top: 6px;' id='userN" + pupilId + "'>" + data.card_refund_res[nCount].username + "</div></td>";
                    tblPupil += "<td nowrap='nowrap'><div style= 'padding-top:6px' id='amount" + pupilId + "'>" + "-£" + data.card_refund_res[nCount].amount + "</div></td>";
                    tblPupil += "<td nowrap='nowrap'><div style= 'padding-top:6px' id='pFee" + pupilId + "'>£" + data.card_refund_res[nCount].transaction_fee + "</div></td>";
                    tblPupil += "</tr>";
                }
                $("#tblFinishRefund  tbody:last").append(tblPupil);
                if (data.card_refund_res.length > 10) {
                    $("#tablePagination").html('');
                    $("#divCRtablePagination").tablePagination({
                        rowsPerPage: 10
                    });
                }
                $("#tblFinishRefund", '#divFinishRefund').show();
            }
            $("#divFinishRefund").show();
            if (data.yp_status == 0) {
                $("#divFinishSuccrErrMsg").html("Thank you, your refund has been successful.  A record of your refund is shown below.").addClass('alert-success').show();
            } else
                $("#divFinishSuccrErrMsg").html("Sorry, but there was an error" + "(" + data.yp_msg + ")" + "processing your refund.  No refund has been issued.").addClass('alert-error').show();
        } else
            $("#divFinishSuccrErrMsg").html(data.error_msg).addClass('alert-error').show();
    }
    $("#btnRefundHistory", "#divFinishRefund").click(function() {
        getFullRefundhistory(page_num);
        $('#widgetnav a[href="#divRefundsHistory"]').tab('show');
    });
    $("#btnRefundHistory", "#divAssignRefund").bind("click", getFullRefundhistory);
    $("#SchoolAdminRefundsHistoryTab").bind("click", function() {
        getFullRefundhistory(1);
    });
    //on click of refunds history page
    $("#SchoolAdminReportsTab").bind("click", reports)
    function reports()
    {
        if (setTimeout(function() {
            $('#divCardFullHistory', '#divRefunds').is(':visible')
        }, 0.5)) {
            $('#divCardFullHistory').css("display", "none");
        }
    }
    function getFullRefundhistory(page_num) {
        var url = BACKENDURL + "customeradmin/get_card_full_history";
        var data = {
            session_id: localStorage["SESSIONID"],
            contract_id: localStorage.getItem("contractid"),
            page_no: page_num
        };
        MakeAjaxCall(url, data, fullHistorySuccess);
    }
    function fullHistorySuccess(data) {
        if (data.error == 0) {
            var tblPupil = "";
            $("#tblCardFullHistory  tbody:last").empty();
            $("#tablePagination", "#divCardFullHistory").remove();
            if (data.full_history_res.history_records.length > 0) {
                for (var nCount = 0; nCount < data.full_history_res.history_records.length; nCount++) {
                    var pupilId = data.full_history_res.history_records[nCount].pupil_id;
                    var freeMeals = (data.full_history_res.history_records[nCount].fsm == 0) ? "<img src='img/fail.png' style='width:20px;display:block;margin:0 auto;' >" : "<img src='img/pass.png' style='width:20px;display:block;margin:0 auto;'>";
                    var adult = (data.full_history_res.history_records[nCount].adult == 1) ? "<i class='icon-user' ></i>" : "";
                    var refundStatus = (data.full_history_res.history_records[nCount].yp_code == 0) ? "" : 'alert-info';
                    var ypTransId = (data.full_history_res.history_records[nCount].pgtr_id == null) ? "-" : data.full_history_res.history_records[nCount].pgtr_id;
                    tblPupil += "<tr class='" + refundStatus + "'><td nowrap='nowrap'><div style= 'padding-top:6px' id='transactionID" + pupilId + "'>" + data.full_history_res.history_records[nCount].payment_id + "</div></td>";
                    tblPupil += "<td><div style= 'padding-top:6px;width:50%' id='status" + pupilId + "'>" + data.full_history_res.history_records[nCount].description + "</div></td>";
                    tblPupil += "<td nowrap='nowrap'><div style= 'padding-top:6px' id='ypId" + pupilId + "'>" + ypTransId + "</div></td>";
                    tblPupil += "<td nowrap='nowrap'><div style= 'padding-top:6px' id='cdate" + pupilId + "'>" + data.full_history_res.history_records[nCount].cdate + "</div></td>";
                    tblPupil += "<td nowrap='nowrap'><div style= 'padding-top:6px' id='pupilfmlName" + pupilId + "'>" + data.full_history_res.history_records[nCount].fname + " " + data.full_history_res.history_records[nCount].mname + " " + data.full_history_res.history_records[nCount].lname + "</div></td>";
                    tblPupil += "<td nowrap='nowrap'><div style= 'padding-top:6px' id='pupilid" + pupilId + "'>" + data.full_history_res.history_records[nCount].pupil_id + "&nbsp;&nbsp;" + adult + "</div></td>";
                    tblPupil += "<td ><label style= 'padding-top: 6px;' id='freemeals" + pupilId + "'>" + freeMeals + "</label></td>";
                    tblPupil += "<td><div style='padding-top: 6px;' 'id='SchoolNameLabel" + pupilId + "'>" + data.full_history_res.history_records[nCount].school_name + "</div></td>";
                    tblPupil += "<td nowrap='nowrap'><div style= 'padding-top:6px' id='username" + pupilId + "'>" + data.full_history_res.history_records[nCount].username + "</div></td>";
                    tblPupil += "<td><div style='text-align: center;padding-top: 6px;'id='amount" + pupilId + "'>" + "-£" + data.full_history_res.history_records[nCount].amount + "</div></td>";
                    tblPupil += "</tr>";
                    $("#tblCardFullHistory  tbody:last").append(tblPupil);
                    tblPupil = "";
                }
                if (data.full_history_res.total_history_records > 0)
                {
                    var total_PaymentHistory = data.full_history_res.total_history_records;
                    var total_page = Math.ceil(total_PaymentHistory / 20);
                    var no_pages = total_page > 3 ? 3 : 1;
                    if (total_PaymentHistory > 20) {
                        var options = {
                            currentPage: 1,
                            alignment: "right",
                            totalPages: total_page,
                            numberOfPages: no_pages,
                            pageUrl: "javascript:void(0)",
                            itemTexts: function(type, page, current) {
                                switch (type) {
                                    case "first":
                                        return "<<";
                                    case "prev":
                                        return "<";
                                    case "next":
                                        return ">";
                                    case "last":
                                        return ">>";
                                    case "page":
                                        return page;
                                }
                            },
                            onPageClicked: function(e, originalEvent, type, page) {
                                getFullRefundhistory(page);
                            }
                        }
                        $("#RefundHistory_pag").bootstrapPaginator(options);
                        $("#RefundHistory_pag").show();
                    }
                    else
                    {
                        $("#RefundHistory_pag").hide();
                    }
                }
                $('#divCardFullHistory').css("display", "inline");
            }
            else {
                $('#divCardFullHistory').css("display", "none");
                $('#noHistoryMsg').show();
            }
            $("#divFinishRefund").hide();
            $("#btnSwitchReports").hide();
            $("#divAssignRefund").hide();
            $("#refundStatus").hide();
        } else
            logout(1);
    }
}
/*************************end Account Refund*************************/

/************************start meal order summary ******************/
function loadMealOrderSummary() {
    $("#txtWeekPicker", "#divTabMealOrder").weekpicker();
    $("#btnOrderSmryGo", "#divTabMealOrder").click(function(e) {
        e.preventDefault();
        loadKitchenSummary();
    });
    populateMealOrderSchools();
}

function populateMealOrderSchools() {
    var url = BACKENDURL + "customeradmin/get_schools";
    var data = {
        session_id: localStorage["SESSIONID"],
        contract_id: localStorage.getItem("contractid")
    };
    MakeAjaxCall(url, data, loadOrderSchools);
}
function loadOrderSchools(data) {

    if (data.error == 0) {
        if (data.schools_res.length == 0)
        {
            $('#NoSchoolMsg').show();
            $('#divMealOrder').hide();
            $('#MealordersummaryPrint').hide();
        }
        else {
            $('#NoSchoolMsg').hide();
            $('#divMealOrder').show();
            $('#MealordersummaryPrint').show();
            $('#ddlMealOrderSchools', "#divTabMealOrder").empty();
            var schoolStr = "";
            //populating prod school
            for (var nCount = 0; nCount < data.schools_res.length; nCount++) {
                var disabled = (data.schools_res[nCount].status == "0") ? " (disabled)" : "";
                schoolStr += "<option value=" + data.schools_res[nCount].school_id + ">" + data.schools_res[nCount].school_name + disabled + "</option>";
            }
            $('#ddlMealOrderSchools', "#divTabMealOrder").append(schoolStr);
            if (data.schools_res.length > 1)
                $('#ddlMealOrderSchools', "#divTabMealOrder").show();
            var curr = new Date;
            var startDay = 1; //0=sunday, 1=monday etc.
            var d = curr.getDay(); //get the current day
            var weekStart = new Date(curr.valueOf() - (d <= 0 ? 7 - startDay : d - startDay) * 86400000); //rewind to start day
            var weekEnd = new Date(weekStart.valueOf() + 4 * 86400000);
            var firstWStart = getRoundDate(weekStart) + "/" + getRoundMonth(weekStart) + "/" + weekStart.getFullYear();
            //var firstWStart = getRoundMonth(weekStart) + "/" + getRoundDate(weekStart) + "/" + weekStart.getFullYear();
            var lastWEnd = getRoundDate(weekEnd) + "/" + getRoundMonth(weekEnd) + "/" + weekEnd.getFullYear();
            //var lastWEnd = getRoundMonth(weekEnd) + "/" + getRoundDate(weekEnd) + "/" + weekEnd.getFullYear();
            var dateformat = firstWStart + "-" + lastWEnd;
            $("#txtWeekPicker", "#divTabMealOrder").val(dateformat);
            loadKitchenSummary();
        }
    } else
        logout(1);
}
function loadKitchenSummary()
{
    if ($('#ddlMealOrderSchools', "#divTabMealOrder").val() != "null")
    {
        var weekStartEndArr = $("#txtWeekPicker", "#divTabMealOrder").val().split("-");
        var weekStartArr = weekStartEndArr[0];
        var weekEndArr = weekStartEndArr[1];
        var weekStartDate = weekStartArr.split("/");
        var weekEndDate = weekEndArr.split("/");
        var url = BACKENDURL + "customeradmin/get_meal_order_summary";
        var data = {
            session_id: localStorage["SESSIONID"],
            school_id: $('#ddlMealOrderSchools', "#divTabMealOrder").val(),
            contract_id: localStorage.getItem("contractid"),
            start_date: weekStartDate[2] + "-" + weekStartDate[1] + "-" + weekStartDate[0],
            end_date: weekEndDate[2] + "-" + weekEndDate[1] + "-" + weekEndDate[0]
        };
        MakeAjaxCall(url, data, loadKitchenMealOrderSmry);
    }
}

function loadKitchenMealOrderSmry(data) {
    if (data.error == 0) {
        // To check School Status
        var Status_Msg = "";
        $("#lblStatusSchlMOSWarn").text('');
        if (data.meal_summary_res.close_details.closed_status == 1)
        {
            Status_Msg = "<strong>Remember! </strong>" + $("#ddlMealOrderSchools option:selected").text() + " was closed by " + data.meal_summary_res.close_details.closed_by + ", because '" + data.meal_summary_res.close_details.closed_reason + "'. Last day of closure is " + data.meal_summary_res.close_details.closed_till;
            $("#lblStatusSchlMOSWarn").removeClass("hide").append(Status_Msg);
        }
        else
        {
            if (!$("#lblStatusSchlMOSWarn").hasClass("hide"))
                $("#lblStatusSchlMOSWarn").addClass("hide");
        }
        if (data.meal_summary_res.total_res != undefined && data.meal_summary_res.total_res.length > 0) {
            $("#divMealOrderSmry", "#divTabMealOrder").show();
            $("#divMOMsg", "#divTabMealOrder").hide();
            $("#divOrderReportMsg", "#divTabMealOrder").html("<label>Kitchen Meal Order Summary as of " + data.meal_summary_res.date_time + " at School: " + $('#ddlMealOrderSchools option:selected', "#divTabMealOrder").text() + "</label>").show();
            $("#divMealSummaryData", "#divTabMealOrder").html('').show();
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
                    $("#divMealSummaryData", "#divTabMealOrder").append(mealData);
                    //Fix for defect 401 Meal order summary alignment table layout has been made fixed
                    mealData = "<table class='table table-hover table-striped' style=' table-layout: fixed; '><thead><tr>";
                }
            }

            $("#MealordersummaryPrint", "#divTabMealOrder").show();
        }
        else {
            $("#divMealOrderSmry", "#divTabMealOrder").hide();
            $("#divMealSummaryData", "#divTabMealOrder").html('').hide();
            $("#divMOMsg", "#divTabMealOrder").addClass("alert alert-error").html("Meal order summary not available for the selected week").show();
            $("#MealordersummaryPrint", "#divTabMealOrder").hide();
        }
    } else

    {

        logout(1)

    }
}

function printMealordersummary()
{
    window.print();
}
/************************end meal order summary ******************/
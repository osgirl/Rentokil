$.validator.addMethod("validateDupYear", function(value, element) {
    var v = $(element).val();
    for (var i = 0; i < 7; i++) {
        if ($(element).attr("id") != $("#txtY" + i).attr("id") && v.toLowerCase() == $("#txtY" + i).val().toLowerCase()) {
            return false;
        }
    }
    return true;
}, "Please enter a unique year label.");
$.validator.addMethod("validateDupClass", function(value, element) {
    var id = $(element).attr("id").substr(4, 1);
    var v = $(element).val();
    for (var i = 1; i < 7; i++) {
        if ($(element).attr("id") != $("#txtY" + id + "C" + i).attr("id") && v.toLowerCase() == $("#txtY" + id + "C" + i).val().toLowerCase()) {
            return false;
        }
    }
    return true;
}, "Please enter a unique class name for this year group.");
var page_num = 1, cashOptions, tmpaymentResult, tmpRefundResult, jsonObj = [], tmppplIO = [], t = 0, jsonObjRefund = [], k = 0;
var jsonCardPayObj = [], tmpallocation = 0, chkCashPay = [], chkCashRefund = [], payArray = [], RefundArray = [], chkPupilIO = [];
var cardPayArray = [], RefundAmt = 0, card_transactionamt = 0;
var tmpresPupils;
var regPupilId = {regex: /^\w{3}\/\w{6}$/};
//var regAlphaNumeric = /^[A-Za-z0-9 _.-]*[A-Za-z0-9][A-Za-z0-9 _.-]*$/i;
var ruleTelephoneNum = {regex: /^[0-9 ]+$/};
var regAddress = {regex: /^[a-zA-Z0-9-\/] ?([a-zA-Z0-9-\/]|[a-zA-Z0-9-\/] )*[a-zA-Z0-9-\/]$/};
var addressErrorMsg = {regex: "Please provide a valid Address"};
var regCity = {regex: /^[a-zA-z] ?([a-zA-z]|[a-zA-z] )*[a-zA-z]$/};
var cityErrorMsg = {regex: "Please provide a valid City"};
var countryErrorMsg = {regex: "Please provide a valid Country"};
var regAlphaNumeric = {regex: /^[A-Za-z0-9 _.-]*[A-Za-z0-9][A-Za-z0-9 _.-]*$/};
var OffErrorMsg = {regex: "Please provide a valid name"};
var YearErrorMsg = {regex: "Please enter a valid year label"};
var ClassErrorMsg = {regex: "Please enter a valid Class Name"};
var regPostalCode = {regex: /^([A-PR-UWYZ0-9][A-HK-Y0-9][AEHMNPRTVXY0-9]?[ABEHMNPRVWXY0-9]? {1,2}[0-9][ABD-HJLN-UW-Z]{2}|GIR 0AA)$/i};
var postalCodeErrorMsg = {regex: "Please provide a valid Postal Code"};
var ruleTelephoneErrorMsg = {regex: "Please enter a valid phone number"};
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
/************************ Page load **********************/
function LoadPageData() {
//Get the customer data from the database.
    $("#spnContractName").html(localStorage["CUSTOMERNAME"]);
    loadMyPupils();
    loadMyOrders();
    loadMySchools();
    loadMyMenus();
    loadMyDocuments();
    LoadPupilSearch();
    loadMealOrderSummary();
    LoadDailyMealColl();
    loadBOC();
    LoadHospOrder();
	LoadManagePupils();
    /***** Card /Cash Payments ********/
    $("#txtCardPaymentAmt").keypress(function(e) {
        if (e.which != 8 && e.which != 0 && e.which != 46 && (e.which < 48 || e.which > 57)) {
            return false;
        }
        $("#spnCardPaymentAmt").hasClass('error')
        {
            $("#spnCardPaymentAmt").removeClass('error');
            $("#lblcardPayerror").text("");
        }
        $("#btnPayCardContinue").removeAttr("disabled");
    });
    $('#txtCardPaymentAmt').keyup(function() {
        if ($('input[name=paymentOptions]:checked', '#CardpaymentOptions').val() == "cc")
        {
            if ($("#txtCardPaymentAmt").val() == "")
            {
                $("#txtCardTransFee").val("0.00");
            }
            else
            {
                var trans_fee = (parseInt($("#txtCardPaymentAmt").val()) * parseInt(localStorage["cc_fee"])) / 100;
                $("#txtCardTransFee").val(trans_fee);
            }
        }
    });
    $('#CardpaymentOptions input[type="radio"]').click(function() {
        if ($(this).attr('id') == "payOptionDebit")
        {
            $("#txtCardTransFee").val(localStorage["dc_fee"]);
        }
        else
        {
            var card_amt = ($("#txtCardPaymentAmt").val() == "") ? "0" : $("#txtCardPaymentAmt").val();
            var trans_fee = (parseInt(card_amt) * parseInt(localStorage["cc_fee"])) / 100;
            $("#txtCardTransFee").val(trans_fee);
        }
    });
    $("#tabCardPaymentHistory").bind("click", PaymentCardHistoryNav);
    $("#tabCashMakePayment").bind("click", setCashPayment);
    $("#tabCashPaymentHistory").bind("click", LoadPayHistorySchools);
    $("#tabCashMakeRefund").bind("click", setCashRefund);
    $("#tabCardMakePayment").bind("click", loadCardPayDetails);
    $("#btnPupilSearchClose").unbind("click").bind("click", clearData);
    $("#divheaderpupilsearch").unbind("click").bind("click", clearData);

    $("#ddlPaymentHistorySchools").bind("change", function() {
        LoadPaymentHistory(page_num)
    });
    $("#hoverevent").tooltip({
        'selector': '',
        'placement': 'left'
    });
    var todayDate = new Date();
    todayDate.setDate(todayDate.getDate() - 1);
    $("#div_SchEnddate").datepicker({startDate: todayDate}).on(
            "changeDate", function() {
        $("#div_SchEnddate").parent().hasClass("control-group error")
        $("#div_SchEnddate").parents('.control-group').removeClass('error');
        $(".datepicker").hide();
    });
    var DMCDate = new Date();
    var DMC_day = todayDate.getDate() + 1;
    var DMC_month = todayDate.getMonth() + 1;
    var DMC_year = todayDate.getFullYear();
    DMCDate.setDate(todayDate.getDate() + 1);
    $("#dp_DMCdate").val(DMC_day + "/" + DMC_month + "/" + DMC_year);
    $("#div_DMCdate").datepicker({endDate: DMCDate}).on(
            "changeDate", function() {
        $("#div_DMCdate").parent().hasClass("control-group error")
        $("#div_DMCdate").parents('.control-group').removeClass('error');
        $(".datepicker").hide();
    });
    $("#div_InvoiceDate").datepicker({startDate: todayDate,endDate: '+1m'}).on(
            "changeDate", function() {
        $("#div_InvoiceDate").parent().hasClass("control-group error")
        $("#div_InvoiceDate").parents('.control-group').removeClass('error');
        $("#btnPlaceOrder").removeAttr("disabled", "disabled");
        $(".datepicker").hide();
        $("#btnPlaceNewMealDebtOdr").removeAttr("disabled", "disabled");
    });
	
}
function SrvFailureCall(data)
{
    $("#loaderIcon_Card", "#divCardPayConfirmpupils").show();
    $("#payOptionDebit").attr('checked', true);
    $("#divCardPayment form").remove();
    $('#inputPay').remove();
    $.ajax({
        url: BACKENDURL + "user/cancel_card_payment",
        type: "post",
        data: {
            session_id: localStorage["SESSIONID"],
            mtr: localStorage["MTR"],
            yp_code: "555"
        },
        dataType: "json",
        crossDomain: true,
        async: false,
        success: function(data) {
            CardPaymentCancel(true);
            $('#widgetnav li > a').prop('disabled', false);
        },
        error: function(xhr, textStatus, error) {
            alert(error);
        }
    });
    /*$('#widgetnav li > a').off('click', function(event) {
     event.preventDefault();
     return true;
     });*/
}

function SrvPaybackCall(data)
{
    $.ajax({
        url: BACKENDURL + "user/save_card_payment",
        type: "post",
        data: {
            session_id: localStorage["SESSIONID"],
            mtr: data.MTR,
            pgtr: data.PGTR,
            auth_id: data.AUTHID,
            yp_code: data.result
        },
        dataType: "json",
        crossDomain: true,
        async: false,
        success: function(data) {
            if (data.card_payment_res.length > 0) {
                var tblPupil = "";
                var cardTransactionFee = data.card_payment_res[0].transaction_fee;
                for (var nCount = 0; nCount < data.card_payment_res.length; nCount++) {
                    var studentId = data.card_payment_res[nCount].students_id;
                    var pupilId = data.card_payment_res[nCount].pupil_id;
                    var freeMeals = (data.card_payment_res[nCount].fsm == 0) ? "<img src='img/fail.png' style='width:20px;display:block;margin:0 auto;' >" : "<img src='img/pass.png' style='width:20px;display:block;margin:0 auto;'>";
                    var adult = (data.card_payment_res[nCount].adult == 1) ? "<i class='icon-user' ></i>" : "";
                    tblPupil += "<tr><td data-title='Transaction ID' nowrap='nowrap'><div style= 'padding-top:6px' id='paymentid" + studentId + "'>" + data.card_payment_res[nCount].payment_id + "</div></td>";
                    tblPupil += "<td data-title='Status' nowrap='wrap'><div style= 'padding-top:6px' id='paymentstatus" + studentId + "'>" + data.yp_msg + "</div></td>";
                    tblPupil += "<td data-title='Date' nowrap='nowrap'><div style= 'padding-top:6px' id='date" + studentId + "'>" + data.card_payment_res[nCount].cdate + "</div></td>";
                    tblPupil += "<td data-title='Pupil Name' nowrap='nowrap'><div style= 'padding-top:6px' id='pupilfName" + studentId + "'>" + data.card_payment_res[nCount].fname + " " + data.card_payment_res[nCount].mname + " " + data.card_payment_res[nCount].lname + "</div></td>";
                    tblPupil += "<td data-title='Pupil ID' nowrap='nowrap'><div style= 'padding-top:6px' id='pupilid" + studentId + "'>" + data.card_payment_res[nCount].pupil_id + "  " + adult + "</div></td>";
                    tblPupil += "<td data-title='Free Meals?'><label style= 'padding-top: 6px;' id='freemeals" + studentId + "'>" + freeMeals + "</label></td>";
                    tblPupil += "<td data-title='School'><div style='padding-top: 6px;' id='SchoolNameLabel" + studentId + "'>" + data.card_payment_res[nCount].school_name + "</div></td>";
                    tblPupil += "<td data-title='Username' nowrap='nowrap'><div style= 'padding-top:6px' id='pupilmName" + studentId + "'>" + localStorage["USER_NAME_PAYMENT"] + "</div></td>";
                    tblPupil += "<td data-title='Amount' nowrap='nowrap'><div style= 'padding-top:6px' id='pupilmName" + studentId + "'>£" + data.card_payment_res[nCount].amount + "</div></td>";
                    if (cardTransactionFee > 0)
                        tblPupil += "<td data-title='Fee' nowrap='nowrap'><div style= 'padding-top:6px' id='pupilmName" + studentId + "'>£" + data.card_payment_res[nCount].transaction_fee + "</div></td>";
                    tblPupil += "</tr>";
                    $("#tblCardPayConfirmationData  tbody:last").append(tblPupil);
                    $("#tblCardPayConfirmationData").show();
                    tblPupil = "";
                }
                if (cardTransactionFee == 0)
                    $("#tblCardPayConfirmationData thead tr th :last").remove();
            }
            $("#divCardPayConfirmpupils").css("display", "none");
            $("#divCardPayConfirmationData").css("display", "inline");
            $("#divCardPayConfirmation", "divCardMakePayment").css("display", "inline");
            $("#divProgresBar_Card").css('width', '100%');
            $("#spnConfirmPayCard").css({"font-weight": "normal", "color": "#8e8e8e"});
            $("#spnFinishedPayCard").css({"font-weight": "bold", "color": "black"});
            $("#spnFinishedPayCardPhone").css({"font-weight": "bold", "color": "black"});
            $("#spnAssignPayCardPhone").addClass('hidden-phone');
            $("#spnAssignPayCardPhone").removeClass('visible-phone');
            $("#spnConfirmPayCardPhone").addClass('hidden-phone');
            $("#spnConfirmPayCardPhone").removeClass('visible-phone');
            $("#spnFinishedPayCardPhone").removeClass('hidden-phone');
            $("#spnFinishedPayCardPhone").addClass('visible-phone');
            $("#loaderIcon_Card", "#divCardPayConfirmpupils").show();
            $("#divbtncardPaymentHistory").removeClass("hide");
            if (data.yp_status == "0")
            {
                $("#lblCardConfmMessage").hasClass("alert alert-danger")
                $("#lblCardConfmMessage").removeClass("alert alert-danger");
                $("#lblCardConfmMessage").text("Thank you, your payment has been successful.  A record of your payment is shown below.").removeClass("hide").addClass("alert alert-success");
            }
            else
            {
                $("#lblCardConfmMessage").hasClass("alert alert-success")
                $("#lblCardConfmMessage").removeClass("alert alert-success");
                var tmperr_msg = "Sorry, but there was an error processing your order (" + data.yp_msg + "). No payment has been collected.";
                $("#lblCardConfmMessage").text(tmperr_msg).removeClass("hide").addClass("alert alert-danger");
            }
            $("#divCardPayment form").remove();
            $('#inputPay').remove();
            $("#payOptionDebit").attr('checked', true);
            $('#widgetnav li > a').prop('disabled', false);
        },
        error: function(xhr, textStatus, error) {
            alert(error);
        }
    });
}

/************************ End of Page load**********************/

/************************ Schools My Pupils **********************/
//Method load for my pupils
function loadMyPupils() {
    //getPupils();
    $("#btnAddPupil").bind("click", addPupilClick);
    $("#btnPupilSave").bind("click", savePupilDetails);
    $("#pupilInfo").validate({
        errorClass: "help-inline",
        errorElement: "span",
        highlight: function(element, errorClass, validClass) {
            $(element).parents('.control-group').addClass('error');
        },
        unhighlight: function(element, errorClass, validClass) {
            $(element).parents('.control-group').removeClass('error');
        }
    });
    $("#tabMyPupils").click(function() {

        $("#pupilInfo").data('validator').resetForm();
        $(".error").removeClass("error");
        getPupils();
    });
}

function addPupilClick() {
    $("#PupilIdError").removeClass('control-group error');
    $("#AddPupilIdError").removeClass('control-group error');
    $("#AddPupilFooterLabelError").removeClass('control-group error');
    $("#AddPupilFooterLabel").empty();
    $("#AddPupilFooterLabel").removeClass('alert alert-error')
    $("#AddPupilFooterLabel").text('').show();
    $("#AddPupilId", "#divAddPupil").val('');
    $("#WelcomeText").hide();
    $("#frmAddpupil").data('validator').resetForm();
    $(".error").removeClass("error");
}
/* $("#btnNewAddPupilAdminClose").click(function(){
 $("#AddPupilFooterLabel").removeClass('alert alert-error')			
 $("#AddPupilFooterLabel").text('').show();
 $("#spandynamictag").text('') 
 $("#AddPupilId").val('')
 $("#frmAddpupil").data('validator').resetForm(); 
 $(".error").removeClass("error"); 
 }); */

$('input[id^="AddPupilId"]').bind("keypress keyup", function() {
    $("#AddPupilFooterLabel").removeClass('alert alert-error')
    $("#AddPupilFooterLabel").text('').show();
})
/* $("#xNewCustomerAdmin").click(function(){
 $("#AddPupilFooterLabel").removeClass('alert alert-error')			
 $("#AddPupilFooterLabel").text('').show();
 $("#spandynamictag").text('') 
 $("#AddPupilId").val('')
 $("#frmAddpupil").data('validator').resetForm(); 
 $(".error").removeClass("error"); 
 
 });
 
 */
$("#frmAddpupil").validate({
    rules: {
        AddPupilId: {
            required: true
        }
    },
    messages: {
        AddPupilId: {required: "Please enter the PupilID"}
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
        error.insertAfter(element).css("margin-bottom", "10px");
    }
});
$("#btnNewAddPupilAdminSubmit").click(function()
{

    var pupilID = $("#AddPupilId").val();
    $.support.cors = true;
    if ($("#frmAddpupil").valid()) {
        $.ajax({
            url: BACKENDURL + "user/add_pupil",
            type: "post",
            data: {
                session_id: localStorage["SESSIONID"],
                pupil_id: pupilID
            },
            dataType: "json",
            crossDomain: true,
            success: function(data) {
                if (data.session_status) {
                    if (data.error == 0) {
                        $("#divAddPupil").modal('hide');
                        //US 312 item 24 Subsequent 'Pupil added' message modal removed
                        //$('#addchild').modal('show');

                        getPupils();
                    } else {
                        $("#PupilIdError").addClass('control-group error');
                        $("#AddPupilIdError").addClass('control-group error');
                        $("#AddPupilFooterLabel").addClass('alert alert-error');
                        $("#AddPupilFooterLabel").text(data.error_msg).show();
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
});
var yearsArr = new Array();
var classesArr = new Array();
function getPupils()
{
    var tblPupil = "";
    $.support.cors = true;
    $.ajax({
        url: BACKENDURL + "user/get_pupils",
        type: "post",
        data: {
            session_id: localStorage["SESSIONID"]
        },
        dataType: "json",
        crossDomain: true,
        success: function(data) {

            if (data.session_status) {
                if (data.error == 0) {
                    var nCurrRecRound = 0;
                    $("#tblPupils  tbody:last").empty();
                    var hdnCurrPage = $("#currPageNumber", "#tablePagination").val();
                    if (hdnCurrPage != undefined) {
                        nCurrRecRound = hdnCurrPage - 1;
                    }
                    $("#tablePagination", "#mypupils").remove();
                    if (data.get_pupils_res.pupils_res.length > 0) {
                        $("#WelcomeText", "#mypupils").hide();
                        $("#btnPupilSave").attr("disabled", 'disabled').show();
                        yearsArr = new Array();
                        classesArr = new Array();
                        var school_id = 0, yearOptionsStr = "";
                        for (var i = 0; i < data.get_pupils_res.year_class_res[0].length; i++) {
                            school_id = data.get_pupils_res.year_class_res[0][i].school_id;
                            yearOptionsStr += "<option value='" + data.get_pupils_res.year_class_res[0][i].year_Id + "'>" + data.get_pupils_res.year_class_res[0][i].year_val + "</option>";
                            classesArr[school_id + "_" + data.get_pupils_res.year_class_res[0][i].year_Id] = "<option value='class1_name'>" + data.get_pupils_res.year_class_res[0][i].class1_name + "</option><option value='class2_name'>" + data.get_pupils_res.year_class_res[0][i].class2_name + "</option><option value='class3_name'>" + data.get_pupils_res.year_class_res[0][i].class3_name + "</option><option value='class4_name'>" + data.get_pupils_res.year_class_res[0][i].class4_name + "</option><option value='class5_name'>" + data.get_pupils_res.year_class_res[0][i].class5_name + "</option><option value='class6_name'>" + data.get_pupils_res.year_class_res[0][i].class6_name + "</option>";
                            if (i == data.get_pupils_res.year_class_res[0].length - 1 || school_id != data.get_pupils_res.year_class_res[0][i + 1].school_id) {							
                                yearsArr[school_id] = yearOptionsStr;
                                yearOptionsStr = "";
                            }
                        }
                        var tblPupil = "";
                        for (var nCount = 0; nCount < data.get_pupils_res.pupils_res.length; nCount++) {
                            var studentId = data.get_pupils_res.pupils_res[nCount].students_id;
                            var pupilId = data.get_pupils_res.pupils_res[nCount].pupil_id;
                            var freeMeals = (data.get_pupils_res.pupils_res[nCount].fsm == 0) ? "<img src='img/fail.png' style='width:20px;display:block;margin:0 auto;' >" : "<img src='img/pass.png' style='width:20px;display:block;margin:0 auto;'>";
                            var adult = (data.get_pupils_res.pupils_res[nCount].adult == 1) ? "<i class='icon-user'></i>" : "";
                            var years = "";
                            tblPupil += "<tr><td data-title='Pupil First Name'><div class='control-group'><input type='text' name='PupilFirstName" + studentId + "' required id='PupilFirstName" + studentId + "' value='" + data.get_pupils_res.pupils_res[nCount].fname + "' maxlength='50' style='width: 80px;'></input></div></td>";
                            tblPupil += "<td data-title='Pupil Middle Name'><div class='control-group'><input type='text' name='PupilMiddleName" + studentId + "' id='PupilMiddleName" + studentId + "' value='" + data.get_pupils_res.pupils_res[nCount].mname + "' maxlength='50' style='width: 80px;'></div></td>";
                            tblPupil += "<td data-title='Pupil Last Name'><div class='control-group'><input type='text' name='PupilLastName" + studentId + "'  required id='PupilLastName" + studentId + "' value='" + data.get_pupils_res.pupils_res[nCount].lname + "' maxlength='50' style='width: 80px;'></input></div></td>";
                            tblPupil += "<td nowrap='nowrap' data-title='Pupil ID'><div style= 'padding-top:6px' id='pupilid" + studentId + "'>" + data.get_pupils_res.pupils_res[nCount].pupil_id + "  " + adult + "</div></td>";
                            tblPupil += "<td data-title='Free Meals?'><label style= 'padding-top: 6px;' id='freemeals" + studentId + "'>" + freeMeals + "</label></td>";
                            tblPupil += "<td data-title='School'><div style='padding-top: 6px;'id='SchoolNameLabel" + studentId + "'>" + data.get_pupils_res.pupils_res[nCount].school_name + "</div></td>";
                            tblPupil += "<td data-title='Year'><span class='select-wrap' style='width:130px;'><select id='selectYear_" + studentId + "' name='selectYear_" + studentId + "' onchange='javascript:return populateClasses(this, " + data.get_pupils_res.pupils_res[nCount].school_id + ");'>" + yearsArr[data.get_pupils_res.pupils_res[nCount].school_id] + "</select></span></td>";
                            tblPupil += "<td data-title='Class'><span class='select-wrap' style='width:130px;'><select id='selectClass_" + studentId + "' name='selectClass_" + studentId + "'></select></span></td>";
                            tblPupil += "<td nowrap='nowrap' ><button id='pupilIdUnassign" + nCount + "' class='btn btn-small' style=' display: block;   margin-left: auto;   margin-right: auto;' type='button' " + status + " value='' onclick='UnassignPupilId(this,\"" + pupilId + "\"  )'><i class='icon-trash' ></i> Unassign</button></td></tr>";
                            $("#tblPupils  tbody:last").append(tblPupil);
                            $("#selectYear_" + studentId, "#tblPupils").val(data.get_pupils_res.pupils_res[nCount].year_Id);
                            $("#selectClass_" + studentId, "#tblPupils").html(classesArr[data.get_pupils_res.pupils_res[nCount].school_id + "_" + data.get_pupils_res.pupils_res[nCount].year_Id]);
                            $("#selectClass_" + studentId, "#tblPupils").val(data.get_pupils_res.pupils_res[nCount].class_name);
                            tblPupil = "";
                            //pagination track for current adding pupil	
                            if (data.get_pupils_res.pupils_res[nCount].pupil_id == $("#AddPupilId").val()) {
                                nCurrRecRound = Math.floor(nCount / 10);
                                $("#AddPupilId").val("");
                            }
                        }
                        $("#tblPupils").show();
                        if (data.get_pupils_res.pupils_res.length > 10) {
                            $("#tablePagination").html('');
                            $("#pagination").tablePagination({
                                rowsPerPage: 10,
                                currPage: nCurrRecRound + 1
                            });
                        }
                        formDirtyCheck();
                    }
                    else {
                        $("#WelcomeText", "#mypupils").show();
                        $("#btnPupilSave").attr("disabled", 'disabled').hide();
                        $("#tblPupils").hide();
                    }
                } else {
                    alert(data.error_msg);
                    alert(data.error);
                }
                pupilEdit();
            } else {
                logout();
            }
        },
        error: function(xhr, textStatus, error) {
            alert(error);
        }
    });
}
var idCollect = [], pfId, pmId, plyd, plId, plcd, uniqueId = [], pupilUpdatedArray = [];
function formDirtyCheck() {
    var Settings = {
        denoteDirtyForm: true,
        dirtyFormClass: false,
        dirtyOptionClass: "dirtyChoice",
        trimText: true,
        formChangeCallback: function(result, dirtyFieldsArray) {
            pupilUpdatedArray = [];
            idCollect = [];
            uniqueId = [];
            if (result)
            {
                $("#pupilInfo").valid();
                $.each(dirtyFieldsArray, function(index, value) {
                    if (value.match("^PupilFirstName")) {
                        pfId = value.substring(14);
                        idCollect.push(pfId);
                    }
                    if (value.match("^PupilMiddleName")) {
                        pmId = value.substr(15);
                        idCollect.push(pmId);
                    }
                    if (value.match("^PupilLastName")) {
                        plId = value.substr(13);
                        idCollect.push(plId);
                    }
                    if (value.match("^selectYear")) {
                        plyd = value.substr(11);
                        idCollect.push(plyd);
                    }
                    if (value.match("^selectClass")) {
                        plcd = value.substr(12);
                        idCollect.push(plcd);
                    }
                    $.each(idCollect, function(i, el) {
                        if ($.inArray(el, uniqueId) === -1)
                            uniqueId.push(el);
                    });
                })
                $.each(uniqueId, function(i, id) {
                    pupilUpdatedArray.push({"pupil_id": $("#pupilid" + id + "").text(), "fname": $("#PupilFirstName" + id + "").val(), "lname": $("#PupilLastName" + id + "").val(), "mname": $("#PupilMiddleName" + id + "").val(), "year": $("#selectYear_" + id + "").val(), 'class': $("#selectClass_" + id + "").val()})
                })
            }
            else
            {
                setTimeout(function() {
                    $("#btnPupilSave").attr("disabled", true).text('Saved')
                }, 500);
            }

        }
    };
    $("#pupilInfo").dirtyFields(Settings);
}
function pupilEdit() {
    $('input[id^="PupilFirstName"]', "#tblPupils").bind("keypress keyup", function() {
        $("#btnPupilSave").removeAttr("disabled").text('Save');
    });
    $('input[id^="PupilMiddleName"]', "#tblPupils").bind("keypress keyup", function() {
        $("#btnPupilSave").removeAttr("disabled").text('Save');
    });
    $('input[id^="PupilLastName"]', "#tblPupils").bind("keypress keyup", function() {
        $("#btnPupilSave").removeAttr("disabled").text('Save');
    });
    $('select[id^="selectYear"]', "#tblPupils").change(function() {
        $("#btnPupilSave").removeAttr("disabled").text('Save');
    });
    $('select[id^="selectClass"]', "#tblPupils").change(function() {
        $("#btnPupilSave").removeAttr("disabled").text('Save');
    });
}
function populateClasses(f, school_id) {
    var year_id = $(f).val();
    var classCtlId = $(f).attr("id").replace("selectYear", "selectClass");
    $("#" + classCtlId, "#tblPupils").html(classesArr[school_id + "_" + year_id]);
}
function UnassignPupilId(f, pupilid) {
    $.support.cors = true;
    $.ajax({
        url: BACKENDURL + "user/pupil_unassign",
        type: "post",
        data: {
            session_id: localStorage["SESSIONID"],
            pupil_id: pupilid
        },
        dataType: "json",
        crossDomain: true,
        success: function(data) {
            if (data.session_status) {
                if (data.error == 0) {
                    getPupils();
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
function savePupilDetails() {
    if ($("#pupilInfo").valid()) {
        if (pupilUpdatedArray != "") {
            var url = BACKENDURL + "user/edit_pupils";
            var data = {
                session_id: localStorage["SESSIONID"],
                pupils_data: pupilUpdatedArray
            };
            MakeAjaxCall(url, data, updatePupils);
        } else {
            $("#btnPupilSave").attr("disabled", true).text('Saving')
            $("#btnPupilSave").attr("disabled", true).text('Saved')
        }
    }
}
function updatePupils(data) {
    if (data.error == 0) {
        $.fn.dirtyFields.formSaved($("#pupilInfo"));
        $("#btnPupilSave").text('Saving');
        $("#btnPupilSave").attr("disabled", true);
        setTimeout(function() {
            $("#btnPupilSave").text('Saved');
        }, 1000)
    } else {
        logout(1);
    }
}
/************************ End of Schools My Pupils **********************/

/************************ Schools My Schools**********************/
function loadMySchools() {
    $("#tabMySchools").click(function() {
        $("#frmMySchools").data('validator').resetForm();
        $(".error").removeClass("error");
        populateMySchools();
        $("#divSelfUser").hide();
    });
    $("#ddlSchools").bind("change", populateSchoolDetails);
    $("#btnSaveSchool").bind("click", saveSchoolDetails);
    $("#btnUserExportPupilIds").bind("click", exportPupilIds);
    $("#btnAddUsers").click(function(e) {
        e.preventDefault();
        var availableUser = $('#ddlAvailableUsers option:selected');
        if (availableUser.length == 0)
            $("#divSelfUser").html("Please select from Available users").show();
        else {
            $("#btnSaveSchool").removeAttr("disabled").text('Save');
            $("#divSelfUser").hide();
            $("#ddlSelectedUsers").prepend($(availableUser).clone());
            $(availableUser).remove();
            SortOptions("#ddlSelectedUsers");
        }
        $("#ddlSelectedUsers option").mouseover(function(e) {
            showPopOver("#ddlSelectedUsers", "left", e.target);
        });
        $("#ddlSelectedUsers option").mouseout(function(e) {
            $("#ddlSelectedUsers").popover('destroy');
        });
    });
    $("#btnRemoveUsers").click(function(e) {
        e.preventDefault();
        var selectedUser = $('#ddlSelectedUsers option:selected');
        if (selectedUser.length == 0)
            $("#divSelfUser").html("Please select from Selected users").show();
        else {
            $("#btnSaveSchool").removeAttr("disabled").text('Save');
            $("#divSelfUser").hide();
        }
        if (selectedUser.val() == localStorage["USERID"]) {
            $("#divSelfUser").html("Current user can not be removed").show();
        } else {
            $('#ddlAvailableUsers').prepend($(selectedUser).clone());
            $(selectedUser).remove();
            SortOptions("#ddlAvailableUsers");
        }
        $("#ddlAvailableUsers option").mouseover(function(e) {
            showPopOver("#ddlAvailableUsers", "right", e.target)
        });
        $("#ddlAvailableUsers option").mouseout(function(e) {
            hidePopOver("#ddlAvailableUsers");
        });
    });
    $('input[id^="txt"]', "#frmMySchools").bind("keypress keyup focus", function() {
        $("#btnSaveSchool").removeAttr("disabled").text('Save');
    });
    $("input:checkbox", "#frmMySchools").change(function() {
        $("#btnSaveSchool").removeAttr("disabled").text('Save');
    });
    $("input:checkbox", "#frmMySchools").change(function() {
        $("#btnSaveSchool").removeAttr("disabled").text('Save');
        var txtId = $(this).attr("id").replace("chk", "txt");
        if ($(this).is(":checked"))
            $("#" + txtId).removeAttr("disabled");
        else
            $("#" + txtId).attr("disabled", "disabled");
    });
    $("#frmMySchools").validate({
        rules: {
            txtOffC2Telephone: ruleTelephoneNum, txtOffC1Telephone: ruleTelephoneNum, txtAddress1: regAddress, txtAddress2: regAddress, txtAddress3: regAddress, txtCity: regCity, txtCounty: regCity, txtOffC1Name: regAlphaNumeric, txtOffC2Name: regAlphaNumeric, txtOffC1Email: {customemail: true}, txtOffC2Email: {customemail: true}, txtPostcode: regPostalCode,
            txtY0: regAlphaNumericReqYear, txtY0C1: regAlphaNumericReq, txtY0C2: regAlphaNumericReq, txtY0C3: regAlphaNumericReq, txtY0C4: regAlphaNumericReq, txtY0C5: regAlphaNumericReq, txtY0C6: regAlphaNumericReq,
            txtY1: regAlphaNumericReqYear, txtY1C1: regAlphaNumericReq, txtY1C2: regAlphaNumericReq, txtY1C3: regAlphaNumericReq, txtY1C4: regAlphaNumericReq, txtY1C5: regAlphaNumericReq, txtY1C6: regAlphaNumericReq,
            txtY2: regAlphaNumericReqYear, txtY2C1: regAlphaNumericReq, txtY2C2: regAlphaNumericReq, txtY2C3: regAlphaNumericReq, txtY2C4: regAlphaNumericReq, txtY2C5: regAlphaNumericReq, txtY2C6: regAlphaNumericReq,
            txtY3: regAlphaNumericReqYear, txtY3C1: regAlphaNumericReq, txtY3C2: regAlphaNumericReq, txtY3C3: regAlphaNumericReq, txtY3C4: regAlphaNumericReq, txtY3C5: regAlphaNumericReq, txtY3C6: regAlphaNumericReq,
            txtY4: regAlphaNumericReqYear, txtY4C1: regAlphaNumericReq, txtY4C2: regAlphaNumericReq, txtY4C3: regAlphaNumericReq, txtY4C4: regAlphaNumericReq, txtY4C5: regAlphaNumericReq, txtY4C6: regAlphaNumericReq,
            txtY5: regAlphaNumericReqYear, txtY5C1: regAlphaNumericReq, txtY5C2: regAlphaNumericReq, txtY5C3: regAlphaNumericReq, txtY5C4: regAlphaNumericReq, txtY5C5: regAlphaNumericReq, txtY5C6: regAlphaNumericReq,
            txtY6: regAlphaNumericReqYear, txtY6C1: regAlphaNumericReq, txtY6C2: regAlphaNumericReq, txtY6C3: regAlphaNumericReq, txtY6C4: regAlphaNumericReq, txtY6C5: regAlphaNumericReq, txtY6C6: regAlphaNumericReq
        },
        messages: {
            txtAddress1: addressErrorMsg, txtAddress2: addressErrorMsg, txtAddress3: addressErrorMsg, txtCity: cityErrorMsg, txtCounty: countryErrorMsg, txtOffC1Name: OffErrorMsg, txtOffC2Name: OffErrorMsg, txtPostcode: postalCodeErrorMsg,
            txtY0: YearErrorMsg, txtY0C1: ClassErrorMsg, txtY0C2: ClassErrorMsg, txtY0C3: ClassErrorMsg, txtY0C4: ClassErrorMsg, txtY0C5: ClassErrorMsg, txtY0C6: ClassErrorMsg,
            txtY1: YearErrorMsg, txtY1C1: ClassErrorMsg, txtY1C2: ClassErrorMsg, txtY1C3: ClassErrorMsg, txtY1C4: ClassErrorMsg, txtY1C5: ClassErrorMsg, txtY1C6: ClassErrorMsg,
            txtY2: YearErrorMsg, txtY2C1: ClassErrorMsg, txtY2C2: ClassErrorMsg, txtY2C3: ClassErrorMsg, txtY2C4: ClassErrorMsg, txtY2C5: ClassErrorMsg, txtY2C6: ClassErrorMsg,
            txtY3: YearErrorMsg, txtY3C1: ClassErrorMsg, txtY3C2: ClassErrorMsg, txtY3C3: ClassErrorMsg, txtY3C4: ClassErrorMsg, txtY3C5: ClassErrorMsg, txtY3C6: ClassErrorMsg,
            txtY4: YearErrorMsg, txtY4C1: ClassErrorMsg, txtY4C2: ClassErrorMsg, txtY4C3: ClassErrorMsg, txtY4C4: ClassErrorMsg, txtY4C5: ClassErrorMsg, txtY4C6: ClassErrorMsg,
            txtY5: YearErrorMsg, txtY5C1: ClassErrorMsg, txtY5C2: ClassErrorMsg, txtY5C3: ClassErrorMsg, txtY5C4: ClassErrorMsg, txtY5C5: ClassErrorMsg, txtY5C6: ClassErrorMsg,
            txtY6: YearErrorMsg, txtY6C1: ClassErrorMsg, txtY6C2: ClassErrorMsg, txtY6C3: ClassErrorMsg, txtY6C4: ClassErrorMsg, txtY6C5: ClassErrorMsg, txtY6C6: ClassErrorMsg,
            txtOffC2Telephone: ruleTelephoneErrorMsg, txtOffC1Telephone: ruleTelephoneErrorMsg
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
    // Open & Close schools 
    $("#btnSubmitSchl", "#divCloseSchl").bind("click", Sub_CloseSchl);
    $("#btnCancelSchl", "#divCloseSchl").bind("click", Sub_CancelSchl);
    $("#xdivCloseSchl", "#divCloseSchl").bind("click", Sub_CancelSchl);
    $("#btnSubmit_OpenSchl", "#divOpenSchl").bind("click", Sub_OpenSchl);
    $("#btnCan_OpenSchl", "#divOpenSchl").bind("click", SubOpen_CclSchl);
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
}

function populateMySchools() {
    $("#lblExportUserError", "#myschools").html('').hide();
    $.support.cors = true;
//First get all schools by passing the contract id.
    $.ajax({
        url: BACKENDURL + "user/get_schools_admins",
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
                    $('#ddlSchools').empty();
                    $('#selectschool').empty();
                    var schoolsStr = "";
                    var selectedStr = " Selected ";
                    for (var nCount = 0; nCount < data.schools_res.length; nCount++)
                    {
                        disableStr = (data.schools_res[nCount].status == "0") ? " (disabled)" : "";
                        schoolsStr += "<option value=" + data.schools_res[nCount].school_id + " " + selectedStr + ">" + data.schools_res[nCount].school_name + disableStr + "</option>";
                        selectedStr = "";
                    }
                    $('#ddlSchools').append(schoolsStr);
                    populateSchoolDetails();
                    if (data.schools_res.length > 1)
                        $('#divMySchools', "#myschools").show();
                    else {
                        $('#selectMyschoolsspanhide', "#myschools").hide();
                        $('#managelabelhide', "#myschools").hide();
                    }


                } else {
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
}
function populateSchoolDetails() {
    $("#frmMySchools").data('validator').resetForm();
    $(".error").removeClass("error");
    $("#lblCloseSchoolMail").addClass('hide');
    var school_id = $('#ddlSchools').val();
    if (school_id != null) {
        $.support.cors = true;
        $.ajax({
            url: BACKENDURL + "user/get_school_details",
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
                        var Schl_status = data.schools_res[0].closed_status;
                        if (Schl_status == "0")
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
                        $('#ddlAvailableUsers', "#divAUsers").empty();
                        $('#ddlSelectedUsers', "#divSelUsers").empty();
                        var Users = "";
                        var selectedUsers = "";
                        $("#btnSaveSchool").attr("disabled", true).text('Saved');
                        $("#hdnSchoolId").val(data.schools_res[0].sid)
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
                        for (var nCount = 0; nCount < data.schools_res[0].school_admins.length; nCount++) {
                            var telephoneNum = (data.schools_res[0].school_admins[nCount].telephone != null) ? data.schools_res[0].school_admins[nCount].telephone : "";
                            var workTelphone = (data.schools_res[0].school_admins[nCount].work_telephone != null) ? data.schools_res[0].school_admins[nCount].work_telephone : "";
                            var mobNumber = (data.schools_res[0].school_admins[nCount].mobile_number != null) ? data.schools_res[0].school_admins[nCount].mobile_number : "";
                            var profileName = (data.schools_res[0].school_admins[nCount].profile_name != null) ? "(" + data.schools_res[0].school_admins[nCount].profile_name + ")" : "";
                            if (data.schools_res[0].school_admins[nCount].school_admin == null) {
                                Users += "<option value=" + data.schools_res[0].school_admins[nCount].user_id + " msg='Email:" + data.schools_res[0].school_admins[nCount].user_email + "<br>" + telephoneNum + "<br>" + workTelphone + "<br>" + mobNumber + "' >" + data.schools_res[0].school_admins[nCount].first_name + " " + data.schools_res[0].school_admins[nCount].last_name + "," + data.schools_res[0].school_admins[nCount].username + profileName + "</option>";
                            }
                            else
                                selectedUsers += "<option value=" + data.schools_res[0].school_admins[nCount].user_id + "  msg='Email:" + data.schools_res[0].school_admins[nCount].user_email + "<br>" + telephoneNum + "<br>" + workTelphone + "<br>" + mobNumber + "' >" + data.schools_res[0].school_admins[nCount].first_name + " " + data.schools_res[0].school_admins[nCount].last_name + "," + data.schools_res[0].school_admins[nCount].username + profileName + "</option>";
                        }

                        $('#ddlAvailableUsers', "#divAUsers").append(Users);
                        $('#ddlSelectedUsers', "#divSelUsers").append(selectedUsers);
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
                    } else {
                        logout(1);
                        /* $("#divSaveSchoolErrMsg","#myschools").html(data.error_msg).show();  
                         $("#btnSaveSchool","#myschools").hide();
                         $("#btnUserExportPupilIds","#divMySchools").hide();
                         $("#frmMySchools","#myschools").hide();
                         $("#spanhide").hide(); */
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
    else {
        $("#accordion_MySchools").hide();
        $("#lblErrMySchools", "#myschools").show();
        $("#btnSaveSchool", "#myschools").hide();
        $("#btnUserExportPupilIds", "#divMySchools").hide();
        $("#divMySchools", "#myschools").hide();
        $("#frmMySchools", "#myschools").hide();
        $("#spanhide").hide();
    }
}

function showPopOver(ctl, placement, opt) {
    $(ctl).popover({
        placement: placement,
        title: "<p style='word-wrap:break-word;'>" + $(opt).text() + "</p>", content: "<p style='word-wrap:break-word;'>" + $(opt).attr("msg") + "</p>"
    }).popover('show');
}
function hidePopOver(ctl) {
    $(ctl).popover('destroy');
}

function saveSchoolDetails() {
    if ($("#frmMySchools").valid()) {
        $.support.cors = true;
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
        $.ajax({
            url: BACKENDURL + "user/save_school_details",
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
                            if ($("#chkY" + i).is(":checked"))
                            {
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
                    } else {
                        logout(1)
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
function exportPupilIds(e) {
    e.preventDefault()
    $("#lblExportUserError", "#myschools").html('').hide();
    var schoolId = $('#ddlSchools', "#myschools").val();
    $("#spnGenExport", "#myschools").show();
    $.ajax({
        url: BACKENDURL + "data_upload/export_data",
        type: "post",
        data: {
            session_id: localStorage["SESSIONID"],
            school_id: schoolId, export_type: "export_school_pupils"
        },
        dataType: "json",
        async: false,
        crossDomain: true,
        success: function(data) {
            if (data.session_status) {
                if (data.error == 0) {
                    var url = BACKENDURL + "common/download_file";
                    window.open(url + "/" + localStorage["SESSIONID"] + "/export_school_pupils/" + data.temp_file);
                    $("#spnGenExport", "#myschools").hide();
                } else {
                    $("#lblExportUserError", "#myschools").html(data.error_msg).show();
                    $("#spnGenExport", "#myschools").hide();
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
function Sub_OpenSchl()
{
    var url = BACKENDURL + "user/school_open";
    var data = {
        session_id: localStorage["SESSIONID"],
        school_id: $("#ddlSchools").val()
    };
    MakeAjaxCall(url, data, Comfirm_OpenSchl);
}
function Comfirm_OpenSchl(data)
{
    if (data.error == 0 || (data.error_msg == "Unable to send email")) {
        $("#lblCloseSchoolWarn").addClass("hide");
        $("#divOpenSchl").modal("hide");
        $("#btnAdminCloseSchool").removeClass("hide");
        $("#btnAdminOpenSchool").addClass("hide");
        if (data.error_msg == "Unable to send email")
            $("#lblCloseSchoolMail").removeClass('hide');
    } else
        logout(1);
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
        var url = BACKENDURL + "user/school_close";
        var data = {
            session_id: localStorage["SESSIONID"],
            school_id: $("#ddlSchools").val(),
            close_till: date1[2] + "-" + date1[1] + "-" + date1[0], reason: War_Reason
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
    $(".error").removeClass("error");
    $("#divCloseSchl").modal("hide");
}

/************************ End of Schools My Schools**********************/
/************************* School Documents **********************/
function loadMyDocuments() {
    $("#tabDocuments").bind("click", populateDocumentSchools);
    $("#chkHideComplete", "#documents").bind("click", populateMyDocuments);
    $("#selectAllSchools", "#documents").bind("change", populateMyDocuments);
}
var schools_documents_str = "";
var schools_rep_comm_stats = "";
var Document_def_status = 0;
var isNewUpload = false;
var isCountOne = false;
var tab;
function populateMyDocuments() {
    $.support.cors = true;
    var school_id = $("#selectAllSchools").val();
    if (school_id != null) {
        $("#lblErrMyDocuments").hide();
        //First get all school Documents and populate it
        $.ajax({
            url: BACKENDURL + "user/get_school_documents",
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
                        //Populate the document status....
                        schools_rep_comm_stats = "";
                        for (var nCount = 0; nCount < data.schools_documents_res[1].school_document_status_res.length; nCount++) {
                            if (nCount == 0)
                                Document_def_status = data.schools_documents_res[1].school_document_status_res[nCount].data_value_id;
                            schools_rep_comm_stats += "<option value=" + data.schools_documents_res[1].school_document_status_res[nCount].data_value_id + ">" + data.schools_documents_res[1].school_document_status_res[nCount].data_value + "</option>";
                        }
                        $("#ddlDocumentStatus", "#divUploadUpdate").empty().append(schools_rep_comm_stats).unbind('change');
                        //populate the document list....
                        var nCurrRecRound = ($("#currPageNumber", "#tablePagination").val() != undefined) ? ($("#currPageNumber", "#tablePagination").val() - 1) : 0;
                        $("#tablePagination", "#documents").remove();
                        $("#tblUploaddashBoard > tbody > tr").remove();
                        if (data.schools_documents_res[2].school_rep.length > 0) {
                            if (isCountOne == true) {
                                $(".form-inline", "#divfilter").show();
                                $(".checkbox", ".form-inline").show();
                            } else
                                $(".select-wrap", ".form-inline").show();
                            $("#WelcomeNoteDocuments", "#documents").hide();
                            $("#divfilter", "#documents").show();
                            for (var nCount = 0; nCount < data.schools_documents_res[2].school_rep.length; nCount++) {
                                var documentId = data.schools_documents_res[2].school_rep[nCount].school_documents_id;
                                var commentText = (data.schools_documents_res[2].school_rep[nCount].comm_status == 0) ? "<span id='new" + documentId + "'><i class='icon-envelope'></i><span style='color:black;font-weight:bold;'> New! </span></span>" + data.schools_documents_res[2].school_rep[nCount].comment : data.schools_documents_res[2].school_rep[nCount].comment;
                                var deleteModalText = (data.schools_documents_res[2].school_rep[nCount].status == "Completed") ? "divUploadDelete" : "divUploadDeleteFail";
                                //var content = "<table class='table table-hover table-striped edfm-bordered-table' id='NhhtblHeader' ><thead><tr><th>Filename</th><th>File Imported Date</th><th>Last Modified On</th><th>Last Modified By</th><th>New Records Added</th><th>Existing Records Amended</th><th></th></tr></thead><tbody>";
                                var tblUpload = "<tr><td style='width:400px'><h3 style='margin:0;'>" + data.schools_documents_res[2].school_rep[nCount].file_name + "</h3><p style='font-size:14px;color:black;margin-bottom:0px'>Uploaded on " + data.schools_documents_res[2].school_rep[nCount].cdate + " by " + data.schools_documents_res[2].school_rep[nCount].username + " in " + data.schools_documents_res[2].school_rep[nCount].school_name + " </p></i>" + commentText + "</td><td><br/><span class='label label-important' id='rpSt" + documentId + "'>" + data.schools_documents_res[2].school_rep[nCount].status + "</span></td> <td style='text-align:right;'><a href='javascript:void(0);' onclick='javascript:downloadDocument(this," + documentId + ");' style='text-decoration: none !important;' id='userdownload'>Download &nbsp;&nbsp;<i class='icon-download'> </i></a><br /><span id='upRe_" + documentId + "'><a href='#divUploadUpdate' data-toggle='modal' onclick='javascript:loadUpdateDocument(" + documentId + ", " + data.schools_documents_res[2].school_rep[nCount].document_status + ");' style='text-decoration: none !important;' id='userupdate'>Update &nbsp;&nbsp;<i class='icon-edit'></i></a></span><br /> <span id='dlRe_" + documentId + "'><a href='#" + deleteModalText + "' onclick='javascript:loadDeleteDocument(" + documentId + ",\"" + deleteModalText + "\");' style='text-decoration: none !important;' data-toggle='modal' id='userdelete'>Delete &nbsp;&nbsp;<i class='icon-remove'></i></a></span></td></tr>";
                                var btn = "";
                                if (data.schools_documents_res[2].school_rep[nCount].status == "Not Started")
                                {

                                    btn += "<span class='label label-important' id='rpSt" + documentId + "'>" + data.schools_documents_res[2].school_rep[nCount].status + "</span>";
                                }
                                else if (data.schools_documents_res[2].school_rep[nCount].status == "Completed")
                                {
                                    btn += "<span class='label label-success' id='rpSt" + documentId + "'>" + data.schools_documents_res[2].school_rep[nCount].status + "</span> ";
                                }
                                else if (data.schools_documents_res[2].school_rep[nCount].status == "In Progress")
                                {
                                    btn += "<span class='label label-info' id='rpSt" + documentId + "'>" + data.schools_documents_res[2].school_rep[nCount].status + "</span> ";
                                }
                                var tblUpload = "<tr><td><h3 style='margin:0;'>" + data.schools_documents_res[2].school_rep[nCount].file_name + "</h3><p style='font-size:14px;color:black;margin-bottom:0px'>Uploaded on " + data.schools_documents_res[2].school_rep[nCount].cdate + " by " + data.schools_documents_res[2].school_rep[nCount].username + " in " + data.schools_documents_res[2].school_rep[nCount].school_name + "&nbsp&nbsp" + btn + " </p></i><p style='color:black;'>" + commentText + "</p></td> <td style='text-align:right;'><a href='javascript:void(0);' onclick='javascript:downloadDocument(this," + documentId + ");' style='text-decoration: none !important;' id='userdownload'>Download &nbsp;&nbsp;<i class='icon-download'> </i></a><br /><span id='upRe_" + documentId + "'><a href='#divUploadUpdate' data-toggle='modal' onclick='javascript:loadUpdateDocument(" + documentId + ", " + data.schools_documents_res[2].school_rep[nCount].document_status + ");' style='text-decoration: none !important;' id='userupdate'>Update &nbsp;&nbsp;<i class='icon-edit'></i></a></span><br /> <span id='dlRe_" + documentId + "'><a href='#" + deleteModalText + "' onclick='javascript:loadDeleteDocument(" + documentId + ",\"" + deleteModalText + "\");' style='text-decoration: none !important;' data-toggle='modal' id='userdelete'>Delete &nbsp;&nbsp;<i class='icon-remove'></i></a></span></td></tr>";
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
                        } else
                            $("#WelcomeNoteDocuments", "#documents").show();
                    } else {
                        logout(1);
                        //alert(data.error_msg);
                        //alert(data.error); 
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
function populateDocumentSchools() {
    var url = BACKENDURL + "user/get_schools_admins";
    var data = {
        session_id: localStorage["SESSIONID"],
        contract_id: localStorage.getItem("contractid")
    };
    MakeAjaxCall(url, data, loadDocumentSchools);
}
function loadDocumentSchools(data) {
    if (data.error == 0) {
        //Populate the schools....
        schools_documents_str = "";
        if (data.schools_res.length > 0) {
            //$('.form-inline', "#divfilter").show();
            $('#selectAllSchools', "#documents").empty();
            tab = "documents";
            allSchoolCheck();
            for (var nCount = 0; nCount < data.schools_res.length; nCount++) {
                var disabled = (data.schools_res[nCount].status == "0") ? " (disabled)" : "";
                schools_documents_str += "<option value=" + data.schools_res[nCount].school_id + ">" + data.schools_res[nCount].school_name + disabled + "</option>";
            }
            $('#selectAllSchools', "#documents").append(schools_documents_str);
            if (data.schools_res.length == 1) {
                isCountOne = true;
                $(".checkbox", "#divfilter").hide();
                $('.select-wrap', "#documents").hide();
                $("#btnUploadNewImport", "#documents").show();
            } else
                isCountOne = false;
        }
        else
            $('.form-inline', "#divfilter").hide();
    }
}
function allSchoolCheck() {
    var url = BACKENDURL + "user/check_all_schools_status";
    var data = {
        session_id: localStorage["SESSIONID"],
        contract_id: localStorage.getItem("contractid")
    };
    MakeAjaxCall(url, data, allSchoolsCheckSuccess);
}
function allSchoolsCheckSuccess(data) {
    if (data.error == 0) {
        if (data.check_res == true) {
            if (tab == "documents") {
                var schools_documents_str = "<option value='0' selected>All Schools</option>";
                $('#selectAllSchools', "#documents").prepend(schools_documents_str);
                $('#selectAllSchools', "#documents").val(0);
            }
            else if (tab == "paymentHistory") {
                $('#ddlPaymentHistorySchools').prepend("<option value='' selected>All Schools</option>");
                $('#ddlPaymentHistorySchools').val('');
            }
        }
        if (tab == "documents")
            populateMyDocuments();
        else if (tab == "paymentHistory") {
            LoadPaymentHistory(page_num);
        }
    }
}
function openDocumentImport()
{
    $("#UploadDocumentClose").show();
    var isUploadSuccess = false;
    var activeFormType = 'school_document';
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
                } else {
                    alert('error');
                }
            });
        },
        progressall: function(e, data) {
            $("#UploadDocumentClose").hide();
            var progress = parseInt(data.loaded / data.total * 100, 10);
            $('#divProgressBar .bar').css('width', progress + '%');
            $("#btnDocumentImportFinish", "#divUploadNewImport").addClass("disabled").show().off('click');
            if (progress == 100 && isUploadSuccess) {
                $("#btnDocumentImportFinish", "#divUploadNewImport").removeClass("disabled").off('click').on('click', {f: activeFormType}, finishImport);
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
                $("#lblImportSuccess", "#divUploadNewImport").text("Success. File was succesfully uploaded and imported into the system!").show();
            }
            $("#btnDocumentImportFinish", "#divUploadNewImport").removeClass("disabled").off('click').on('click', {f: activeFormType}, finishImport);
            isUploadSuccess = true;
        },
        done: function(e, data) {
            if (data.result.error) {
                $("#lblImportSuccess", "#divUploadNewImport").text("").hide();
                $("#lblImportError", "#divUploadNewImport").text("Unsuccessful. Failure reason: " + data.result.error_msg).show();
            } else {
                $("#lblImportError", "#divUploadNewImport").text("").hide();
                $("#lblImportSuccess", "#divUploadNewImport").text("Success. File was succesfully uploaded and imported into the system!").show();
            }
            $("#btnDocumentImportFinish", "#divUploadNewImport").removeClass("disabled").off('click').on('click', {f: activeFormType}, finishImport);
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
function finishImport(event) {
    var formtype = event.data.f;
    if (formtype == "school_document") {
        populateMyDocuments();
    }
}

function loadUpdateDocument(documentId, document_status) {
    $.support.cors = true;
    $.ajax({
        url: BACKENDURL + "user/get_school_document_comments",
        type: "post",
        data: {
            session_id: localStorage["SESSIONID"],
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
                        comm_str += "<div class='row-fluid' style='padding: 2px;'><div class='span9 " + pull_str + "'><pre class='" + color + "' ><label class='" + textcolor + "' >" + data.schools_rep_comm_res[i].comment_text + "</label><i class='icon-comment'></i> <label class='" + textcolor + "' style='font-size:11px;margin-top: -20px;margin-left: 22px;' >By " + data.schools_rep_comm_res[i].username + " on " + data.schools_rep_comm_res[i].cdate + "</label></pre></div></div>";
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
        }, error: function(xhr, textStatus, error) {
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
        url: BACKENDURL + "user/update_school_document_status",
        type: "post",
        data: {
            session_id: localStorage["SESSIONID"],
            school_document_id: documentId,
            status: document_status
        },
        dataType: "json",
        crossDomain: true,
        success: function(data) {
            if (data.session_status) {
                if (data.error == 0) {
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
                            populateMyDocuments();
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
            }
            else
                logout();
        }, error: function(xhr, textStatus, error) {
            alert(error);
        }
    });
}

function insertDocumentComments() {

    $.support.cors = true;
    var document_status = $("#ddlDocumentStatus", "#divUploadUpdate").val();
    $.ajax({
        url: BACKENDURL + "user/insert_document_comments",
        type: "post",
        data: {
            session_id: localStorage["SESSIONID"],
            school_document_id: $("#hdnDocumentId", "#divUploadUpdate").val(),
            comments: $("#txaComments", "#divUploadUpdate").val()
        },
        dataType: "json",
        crossDomain: true,
        success: function(data) {
            if (data.session_status) {
                if (data.error == 0) {
                    $("#divUploadUpdate").modal('hide');
                    populateMyDocuments();
                } else
                    logout(1);
            }
            else
                logout();
        }, error: function(xhr, textStatus, error) {
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
        url: BACKENDURL + "user/delete_document",
        type: "post",
        data: {
            session_id: localStorage["SESSIONID"],
            school_document_id: documentId
        },
        dataType: "json",
        crossDomain: true,
        success: function(data) {
            if (data.session_status) {
                if (data.error == 0) {
                    $("#divUploadDelete").modal('hide');
                    $("#UploadDeleteIdY").modal('show');
                    populateMyDocuments();
                } else
                    logout(1);
            }
            else
                logout();
        }, error: function(xhr, textStatus, error) {
            alert(error);
        }
    });
}
function downloadDocument(f, documentId) {
    var url = BACKENDURL + "common/download_file";
    window.open(url + "/" + localStorage["SESSIONID"] + "/school_document/" + documentId);
}

/************************* end of School Documents **********************/

/************************* start of My Menus ****************************/
function loadMyMenus() {
    $("#tabMyMenus").bind("click", populateMyMenus);
    $('#ddlMenuSchools', "#mymenus").bind("change", function() {
        getMenuDetails(1, 1);
    });
}

function populateMyMenus() {
//first load the school dropdown.
    var url = BACKENDURL + "user/get_schools_admins";
    var data = {
        session_id: localStorage["SESSIONID"],
        contract_id: localStorage.getItem("contractid")
    };
    MakeAjaxCall(url, data, loadMenus);
}

function loadMenus(data) {
    if (data.error == 0) {
        $('#ddlMenuSchools', "#mymenus").empty();
        var schoolStr = "";
        if (data.schools_res.length > 0) {
            $("#lblErrMyMenus", "#mymenus").hide();
            //populating prod school
            for (var nCount = 0; nCount < data.schools_res.length; nCount++) {
                var disabled = (data.schools_res[nCount].status == "0") ? " (disabled)" : "";
                schoolStr += "<option value=" + data.schools_res[nCount].school_id + ">" + data.schools_res[nCount].school_name + disabled + "</option>";
            }
            $('#ddlMenuSchools', "#mymenus").append(schoolStr);
            if (data.schools_res.length == 1)
                $("#tblManage", "#mymenus").hide();
            getMenuDetails(1, 1);
        } else {
            $("#frmMenus", "#mymenus").hide();
            $("#tblManage", "#mymenus").hide();
            $("#lblErrMyMenus", "#mymenus").show();
        }

    }
}


function getMenuDetails(menu_seq, week_cycle) {
    menu_seq = (menu_seq == undefined) ? 1 : menu_seq;
    week_cycle = (week_cycle == undefined) ? 1 : week_cycle;
    var url = BACKENDURL + "user/get_school_menu_details";
    var data = {
        session_id: localStorage["SESSIONID"],
        contract_id: localStorage.getItem("contractid"),
        school_id: $('#ddlMenuSchools', "#mymenus").val(),
        menu_seq: menu_seq, week_cycle: week_cycle
    };
    MakeAjaxCall(url, data, populateMenuDetails);
}

var MealArray = new Array();
MealArray[11] = "Main Meal";
MealArray[12] = "Snack Options";
MealArray[13] = "Desert";
var optionStatus = new Array();
function populateMenuDetails(data) {
    $("#divErrMyMenus", "#mymenus").hide();
    if (data.error == 0) {
        // To check School Status
        var Status_Msg = "";
        $("#lblCloseSchlMenuWarn").text('');
        if (data.menus_res[0].scd.closed_status == 1)
        {
            Status_Msg = "<strong>Remember! </strong>" + $("#ddlMenuSchools option:selected").text() + " was closed by " + data.menus_res[0].scd.closed_by + ", because '" + data.menus_res[0].scd.closed_reason + "'. Last day of closure is " + data.menus_res[0].scd.closed_till;
            $("#lblCloseSchlMenuWarn").removeClass("hide").append(Status_Msg);
        }
        else
        {
            if (!$("#lblCloseSchlMenuWarn").hasClass("hide"))
                $("#lblCloseSchlMenuWarn").addClass("hide");
        }
        var menuCycles = data.menus_res[0].mc[0].c;
        var menuTxt = "";
        for (var i = 1; i <= menuCycles; i++) {
            if (i != data.menus_res[0].ms[0].mseq)
                menuTxt += "<a href='javascript:void(0);' onClick='javascript:return saveMenuDetails(" + i + ",1,1);return false;'>switch to Menu " + i + "</a> , ";
        }
        menuTxt = menuTxt.substr(0, menuTxt.length - 3);
        var school_name = $('#ddlMenuSchools option:selected', "#mymenus").text();
        var activeTxt = (data.menus_res[0].ma.length > 0 && data.menus_res[0].ms[0].menu_id == data.menus_res[0].ma[0].menu_id) ? "active" : "inactive"; // Need to get from db.
        $("#lblHeading", "#mymenus").html(school_name.replace(" (disabled)", "") + " Viewing Week " + data.menus_res[0].ms[0].w + " - Menu " + data.menus_res[0].ms[0].mseq + " (" + activeTxt + ") <br /> Active from " + data.menus_res[0].ms[0].mdate + " - (" + menuTxt + ")");
        $("#spnWeekId1", "#mymenus").html(data.menus_res[0].ms[0].w);
        $("#spnWeekId2", "#mymenus").html(data.menus_res[0].ms[0].w);
        $("#spnWeekId3", "#mymenus").html(data.menus_res[0].ms[0].w);
        $("#spnWeekId4", "#mymenus").html(data.menus_res[0].ms[0].w);
        $("#spnWeekId5", "#mymenus").html(data.menus_res[0].ms[0].w);
        if (data.menus_res[0].ms[0].w == 1)
            $("#pgrPrevious", "#mymenus").addClass("disabled");
        else
            $("#pgrPrevious", "#mymenus").removeClass("disabled").off('click').on('click', function() {
                saveMenuDetails(data.menus_res[0].ms[0].mseq, parseInt(data.menus_res[0].ms[0].w) - 1, 1);
            });
        if (data.menus_res[0].ms[0].w == data.menus_res[0].ms[0].wc)
            $("#pgrNext", "#mymenus").addClass("disabled");
        else
            $("#pgrNext", "#mymenus").removeClass("disabled").off('click').on('click', function() {
                saveMenuDetails(data.menus_res[0].ms[0].mseq, parseInt(data.menus_res[0].ms[0].w) + 1, 1);
            });
        var tot = data.menus_res[0].md.length;
        var schoolDetails = [];
        var prodSchoolDetails = [];
        for (var i = 0; i < data.menus_res[0].sd.length; i++)
            schoolDetails[data.menus_res[0].sd[i].mid] = data.menus_res[0].sd[i].s;
        for (var i = 0; i < data.menus_res[0].spd.length; i++)
            prodSchoolDetails[data.menus_res[0].spd[i].mid] = data.menus_res[0].spd[i].s;
        var mealStr = "";
        var curWeek = 1;
        var preMeal = 0;
        var i = 0;
        var hideCost;
        while (i < tot) {
            var instr = (i == 0) ? " in " : "";
            //for(var i=0; i<tot; i++){
            curWeek = data.menus_res[0].md[i].w;
            if (preMeal != data.menus_res[0].md[i].m) {
                mealStr += "<div class='accordion-group'><div class='accordion-heading'><a class='accordion-toggle' data-toggle='collapse' data-parent='#accordion" + curWeek + "' href='#collapse_" + curWeek + "_" + data.menus_res[0].md[i].m + "' style='text-decoration: none !important;'>" + MealArray[data.menus_res[0].md[i].m] + "</a></div>";
                mealStr += "<div id='collapse_" + curWeek + "_" + data.menus_res[0].md[i].m + "' class='accordion-body collapse " + instr + "'><div class='accordion-inner'>";
            }

            var k = 0, opt = 1;
            for (var j = i + 1; j <= tot; j++) {
                if (k == 0)
                    mealStr += "<ul class='thumbnails'>";
                optTxt = toWords(opt);
                optTxt = optTxt[0].toUpperCase() + optTxt.substring(1);
                hideCost = (data.menus_res[0].md[i].m == 13) ? " hide " : ""; // For desert
                mealStr += "<li class='span3'><div class='thumbnail'><h3 class='aligncenter'>Option " + optTxt + "</h3><p><textarea class='txt_menu' disabled id='txtO_" + data.menus_res[0].md[j - 1].mid + "' name ='txtO_" + data.menus_res[0].md[j - 1].mid + "'>" + data.menus_res[0].md[j - 1].d + "</textarea></p>";
                mealStr += "<div class='form-horizontal'><div class='control-group'><label class='control-label span3 " + hideCost + " '>Net Cost</label><div class='controls'>&nbsp;<div class='input-prepend txt_prepend txt_margin " + hideCost + " ' ><span class='add-on' style='height:16px;'>£</span><input class='txt_netcost' disabled name='txtOVal_" + data.menus_res[0].md[j - 1].mid + "' id='txtOVal_" + data.menus_res[0].md[j - 1].mid + "' type='text' value='" + data.menus_res[0].md[j - 1].c + "'></div>";
                var btnStr = "";
                if (data.menus_res[0].md[j - 1].s == 0)
                    optBtText = "<button class='btn btn-danger btn-small disabled' disabled='disabled' style='margin-top:0px;' onClick='javascript:return false;'><i class='icon-ban-circle icon-white'></i> Disabled</button>";
                else {

                    var dBtn = "", eBtn = "";
                    var prodschoolStatus = (prodSchoolDetails[data.menus_res[0].md[j - 1].mid] != undefined) ? prodSchoolDetails[data.menus_res[0].md[j - 1].mid] : 0;
                    if (prodschoolStatus == 0 && data.menus_res[0].p == "0")
                        optBtText = "<button class='btn btn-danger btn-small disabled' disabled='disabled' style='margin-top:0px;' onClick='javascript:return false;'><i class='icon-ban-circle icon-white'></i> Disabled</button>";
                    else {
                        var schoolStatus = (schoolDetails[data.menus_res[0].md[j - 1].mid] != undefined) ? schoolDetails[data.menus_res[0].md[j - 1].mid] : 0;
                        if (schoolStatus == 1)
                            dBtn = " hide ";
                        else
                            eBtn = " hide ";
                        optBtText = "<input type=hidden name='hdnOSt_" + data.menus_res[0].md[j - 1].mid + "' id='hdnOSt_" + data.menus_res[0].md[j - 1].mid + "' value='" + schoolStatus + "'/><button class='btn btn-success btn-small" + eBtn + "' style='margin-top:0px;' id='btnOSt_1_" + data.menus_res[0].md[j - 1].mid + "' onClick='javascript:return changeMenuOptionStatus(this,0);'><i class='icon-ok-circle icon-white'></i> Enabled</button><button class='btn btn-danger btn-small" + dBtn + "' style='margin-top:0px;' onClick='javascript:return changeMenuOptionStatus(this,1);' id='btnOSt_0_" + data.menus_res[0].md[j - 1].mid + "'><i class='icon-ban-circle icon-white'></i> Disabled</button>";
                        optionStatus[data.menus_res[0].md[j - 1].mid] = schoolStatus;
                    }
                }

                mealStr += "<span class='help-inline' style='float:right'>" + optBtText + "</span></div></div></div></div>";
                mealStr += "</li>";
                k++;
                opt++;
                if (k == 4) {
                    mealStr += "</ul>";
                    k = 0;
                }
                preMeal = data.menus_res[0].md[j - 1].m;
                if (j == tot || preMeal != data.menus_res[0].md[j].m) {
                    mealStr += "<button class='btn btn-primary' id='btnContinue_" + curWeek + "_" + data.menus_res[0].md[i].m + "' name='btnContinue_" + curWeek + "_" + data.menus_res[0].md[i].m + "' disabled='disabled' style='float:right' onClick='javascript:return saveMenuDetails(" + data.menus_res[0].ms[0].mseq + "," + data.menus_res[0].ms[0].w + ",0,this);return false;'>Continue</button><br/>";
                    mealStr += "</div></div></div>";
                    break;
                }
            }
            i = j;
            if (i == tot || curWeek != data.menus_res[0].md[i + 1].w) {
                $("#accordion" + curWeek, "#mymenus").html(mealStr);
                mealStr = "";
            }
        }

        $("#btnSaveMenus", "#mymenus").attr("disabled", "disabled").text('Saved');
        $(":button[name^='btnContinue']", "#mymenus").attr("disabled", "disabled");
        //frmMenusDirtyCheck();
        $("#btnSaveMenus", "#mymenus").off('click').on('click', function() {
            saveMenuDetails(data.menus_res[0].ms[0].mseq, data.menus_res[0].ms[0].w, 0);
            return false;
        });
    } else
        logout(1);
}

function changeMenuOptionStatus(f, opt) {
    var id = $(f).attr('id');
    var newid = (opt == 1) ? id.replace("_0_", "_1_") : id.replace("_1_", "_0_");
    $("#" + newid).show();
    $(f).hide();
    var mid = (id.split("_"))[2];
    $("#hdnOSt_" + mid).val(opt);
    var saveFlag = false;
    $(":hidden[name^='hdnOSt_']").each(function() {
        if (optionStatus[$(this).attr('id').replace('hdnOSt_', '')] != $(this).val()) {
            saveFlag = true;
            return false;
        }
    });
    if (saveFlag) {
        $("#btnSaveMenus", "#mymenus").attr("disabled", false).text('Save');
        $(":button[name^='btnContinue']", "#mymenus").attr("disabled", false);
    } else {
        $("#btnSaveMenus", "#mymenus").attr("disabled", "disabled").text('Saved');
        $(":button[name^='btnContinue']", "#mymenus").attr("disabled", "disabled");
    }
    return false;
}

function saveMenuDetails(menu_seq, week_cycle, reload, ctlContinue) {
    var schoolMenuDetails = [];
    $(":hidden[name^='hdnOSt_']").each(function() {
        schoolMenuDetails.push({
            cmid: $(this).attr('id').replace('hdnOSt_', ''),
            os: $(this).val()
        });
    });
    //alert("Form validated... and need to save");
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
                    $("#btnSaveMenus", "#mymenus").attr("disabled", "disabled").text('Saving');
                    $(":button[name^='btnContinue']", "#mymenus").attr("disabled", "disabled");
                    $.support.cors = true;
                    $.ajax({
                        url: BACKENDURL + "user/save_school_menu_details",
                        type: "post",
                        data: {session_id: localStorage["SESSIONID"],
                            contract_id: localStorage["contractid"],
                            school_id: $('#ddlMenuSchools', "#mymenus").val(),
                            menu_seq: menu_seq, week_cycle: week_cycle,
                            school_menu_details: schoolMenuDetails
                        },
                        dataType: "json",
                        crossDomain: true,
                        success: function(data) {
                            if (data.session_status) {
                                if (data.error == 0) {
                                    optionStatus = new Array();
                                    if (reload == 1)
                                        getMenuDetails(menu_seq, week_cycle);
                                    else
                                    {
                                        $("#btnSaveMenus", "#mymenus").attr("disabled", "disabled").text('Saved');
                                        $(":button[name^='btnContinue']", "#mymenus").attr("disabled", "disabled");
                                        if (ctlContinue != undefined) {
                                            var continueId = $(ctlContinue).attr("id");
                                            $("#" + continueId.replace('btnContinue_', 'collapse_')).removeClass("in").attr("style", "style=height: 0px;");
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

                                } else
                                    logout(1);
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
    return false;
}
/************************* end of My Menus ****************************/

/************************* Cash Payment Mode **************************/
function LoadPupilSearch()
{
    $("#txtPaymentAmt").keypress(function(e) {
        if (e.which != 8 && e.which != 0 && e.which != 46 && (e.which < 48 || e.which > 57)) {
            return false;
        }
    });
    $("#frmPupilSearch").validate({
        rules: {
            //txtSearchPupilId: {regPupilId: true},
            txtSearchFname: {regAlphaNumeric: true},
            txtSearchMname: {regAlphaNumeric: true},
            txtSearchLname: {regAlphaNumeric: true}
        },
        messages: {
            //txtSearchPupilId: {regPupilId: "Please enter valid PupilID"},
            txtSearchFname: {regAlphaNumeric: "Special characters are not allowed"},
            txtSearchMname: {regAlphaNumeric: "Special characters are not allowed"},
            txtSearchLname: {regAlphaNumeric: "Special characters are not allowed"}
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
$("#btnPupilSearch").click(function()
{
    var pupilId = $("#txtSearchPupilId", "#divPupilSearch").val();
    var Fname = $("#txtSearchFname", "#divPupilSearch").val();
    var Mname = $("#txtSearchMname", "#divPupilSearch").val();
    var Lname = $("#txtSearchLname", "#divPupilSearch").val();
    if ((pupilId == "") && (Fname == "") && (Mname == "") && (Lname == ""))
    {
        $("#lblpupilsearcherror").text("Please enter Pupil ID or Name").removeClass("hide");
        return false;
    }
    if (!$("#lblpupilsearcherror").hasClass("hide"))
        $("#lblpupilsearcherror").addClass("hide");
    var url = BACKENDURL + "user/search_pupils";
    var data = {
        session_id: localStorage["SESSIONID"],
        pupil_id: pupilId,
        fname: Fname,
        mname: Mname,
        lname: Lname
    };
    MakeAjaxCall(url, data, userSearchpayment);
});
function userSearchpayment(data)
{
    if (RefundAmt == 0)
    {
        $("#tblRefundPupilSearch  tbody tr").remove();
        $("#tblRefundPupilSearch").hide();
        $("#txtPaymentAmt").val("");
    }
    var tblPupil = "";
    if (data.error == 0) {
        var nCurrRecRound = 0;
        $("#tblPupilSearch  tbody:last").empty();
        var hdnCurrPage = $("#currPageNumber", "#tablePagination").val();
        if (hdnCurrPage != undefined) {
            nCurrRecRound = hdnCurrPage - 1;
        }
        $("#tablePagination").remove();
        tmpaymentResult = JSON.stringify(data.search_pupils_res);
        localStorage["USER_NAME_PAYMENT"] = data.username;
        if (data.search_pupils_res.length > 0) {
            $("#divPupilSearch").css('display', 'none');
            var tblPupil = "";
            for (var nCount = 0; nCount < data.search_pupils_res.length; nCount++) {
                var studentId = data.search_pupils_res[nCount].students_id;
                var pupilId = data.search_pupils_res[nCount].pupil_id;
                var freeMeals = (data.search_pupils_res[nCount].fsm == 0) ? "<img src='img/fail.png' style='width:20px;display:block;margin:0 auto;' >" : "<img src='img/pass.png' style='width:20px;display:block;margin:0 auto;'>";
                var adult = (data.search_pupils_res[nCount].adult == 1) ? "<i class='icon-user' ></i>" : "";
                // var className = data.search_pupils_res[nCount].class_col;
                tblPupil += "<tr><td><div style= 'padding-top:6px' id='pupilName" + studentId + "'>" + data.search_pupils_res[nCount].fname + " " + data.search_pupils_res[nCount].mname + " " + data.search_pupils_res[nCount].lname + "</div></td>";
                tblPupil += "<td nowrap='nowrap'><div style= 'padding-top:6px' id='pupilid" + studentId + "'>" + data.search_pupils_res[nCount].pupil_id + "  " + adult + "</div></td>";
                tblPupil += "<td ><label style= 'padding-top: 6px;' id='freemeals" + studentId + "'>" + freeMeals + "</label></td>";
                tblPupil += "<td><div style='padding-top: 6px;' id='SchoolNameLabel" + studentId + "'>" + data.search_pupils_res[nCount].school_name + "</div></td>";
                tblPupil += "<td><div style='text-align: center;padding-top: 6px;'id='YearLabel" + studentId + "'>" + data.search_pupils_res[nCount].year_label + "</div></td>";
                tblPupil += "<td><div style='text-align: center;padding-top: 6px;'id='ClassLabel" + studentId + "'>" + data.search_pupils_res[nCount].class_name + "</div></td>";
                tblPupil += "<td><label style='display:inline;'><input type='checkbox' id='chkSelect_" + pupilId + "' name='chkSelect_" + pupilId + "' style='display:inline;margin-top:0px;' ><span style='vertical-align:middle;display:inline;'> Select</span></label></td></tr>";
                $("#tblPupilSearch  tbody:last").append(tblPupil);
                tblPupil = "";
                //pagination track for current adding pupil	
                if (data.search_pupils_res[nCount].pupil_id == $("#AddPupilId").val()) {
                    nCurrRecRound = Math.floor(nCount / 10);
                    $("#AddPupilId").val("");
                }
            }
            $("#tblPupilSearch").show();
            $("input[type=checkbox]", "#tblPupilSearch").click(function()
            {
                var tmpPupilId = $(this).attr('id').slice(10);
                if (cashOptions == 1)
                {
                    if ($.inArray(tmpPupilId, chkCashPay) > -1)
                    {
                        chkCashPay.pop(tmpPupilId);
                    }
                    else
                    {
                        chkCashPay.push(tmpPupilId);
                    }
                }
                else
                {
                    if ($.inArray(tmpPupilId, chkCashRefund) > -1)
                    {
                        chkCashRefund.pop(tmpPupilId);
                    }
                    else
                    {
                        chkCashRefund.push(tmpPupilId);
                    }
                }
            });
            if (data.search_pupils_res.length > 4) {
                $("#tablePagination").html('');
                $("#AddPupil_pagination").tablePagination({
                    rowsPerPage: 3,
                    currPage: nCurrRecRound + 1
                });
            }
            $("#tablePagination").css({"margin-top": "0px", "margin-bottom": "0px", "height": "40px"});
            $("#divPopulatepupils").css('display', 'inline');
            $("#btnPupilSearch").addClass("hide");
            if (cashOptions == 1)
            {
                $("#btnPupilSubmit").removeClass("hide");
            }
            else
            {
                $("#btnPupilRefundSubmit").removeClass("hide");
            }
        }
        else {
            $("#lblpupilsearcherror").text("Pupil does not exist").removeClass("hide");
            return false;
        }
    } else {
        alert(data.error_msg);
        alert(data.error);
    }
}

$("#btnPupilSubmit").click(function()
{
    var chkboxAction = 0;
    $("input[type=checkbox]", "#tblPupilSearch").each(function()
    {
        if ($(this).is(':checked'))
        {
            chkboxAction = 1;
        }
    });
    if (chkboxAction == 0)
    {
        $("#lblpupilsearcherror").text("Please select pupil to continue the payment process").removeClass("hide");
        return false;
    }

    var tmpresPupils = JSON.parse(tmpaymentResult);
    if (tmpresPupils.length > 0) {
        var tblPupil = "";
        for (var nCount = 0; nCount < tmpresPupils.length; nCount++) {
            var studentId = tmpresPupils[nCount].students_id;
            var pupilId = tmpresPupils[nCount].pupil_id;
            var replacedpupilId = pupilId.replace("/", "_");
            var freeMeals = (tmpresPupils[nCount].fsm == 0) ? "<img src='img/fail.png' style='width:20px;display:block;margin:0 auto;' >" : "<img src='img/pass.png' style='width:20px;display:block;margin:0 auto;'>";
            var adult = (tmpresPupils[nCount].adult == 1) ? "<i class='icon-user' ></i>" : "";
            var years = "";
            var objexists = 0;
            for (var tmpCount = 0; tmpCount < jsonObj.length; tmpCount++)
            {
                if (pupilId == jsonObj[tmpCount].pupilId)
                {
                    objexists = 1;
                }
            }

            if ($.inArray(pupilId, chkCashPay) > -1 && (objexists == 0))
            {
                var dataObj = new Object();
                dataObj.students_id = tmpresPupils[nCount].students_id;
                dataObj.fname = tmpresPupils[nCount].fname;
                dataObj.mname = tmpresPupils[nCount].mname;
                dataObj.lname = tmpresPupils[nCount].lname;
                dataObj.pupilId = tmpresPupils[nCount].pupil_id;
                dataObj.Meals = tmpresPupils[nCount].fsm;
                dataObj.school = tmpresPupils[nCount].school_name;
                dataObj.CurrentBal = tmpresPupils[nCount].cash_balance;
                dataObj.userid = localStorage["USER_NAME_PAYMENT"];
                dataObj.adult = tmpresPupils[nCount].adult;
                dataObj.allocation = 0;
                dataObj.netamount = 0;
                jsonObj[t] = dataObj;
                t += 1;
                tblPupil += "<tr><td data-title='Pupil Name' nowrap='nowrap'><div style= 'padding-top:6px' id='pupilfName" + studentId + "'>" + tmpresPupils[nCount].fname + " " + tmpresPupils[nCount].mname + " " + tmpresPupils[nCount].lname + "</div></td>";
                tblPupil += "<td data-title='Pupil ID' nowrap='nowrap'><div style= 'padding-top:6px' id='pupilid" + studentId + "'>" + tmpresPupils[nCount].pupil_id + "  " + adult + "</div></td>";
                tblPupil += "<td data-title='Free Meals?'><label style= 'padding-top: 6px;' id='freemeals" + studentId + "'>" + freeMeals + "</label></td>";
                tblPupil += "<td data-title='School'><div style='padding-top: 6px;' id='SchoolNameLabel" + studentId + "'>" + tmpresPupils[nCount].school_name + "</div></td>";
                tblPupil += "<td data-title='Current Balance'><div class='controls'><div class='input-prepend'> <span class='add-on' id='spnCurrentBal'>£</span><input name='txtCurrentBal' class='input-small' id='txtCurrentBal_" + tmpresPupils[nCount].pupil_id + "' type='text' disabled value=' " + tmpresPupils[nCount].cash_balance + " '></div></div></td>";
                tblPupil += "<td data-title='Allocation'><div class='control-group' id='divAllocCash" + tmpresPupils[nCount].pupil_id + "'><div class='input-append'> <input class='input-small' name='txtAllocation" + tmpresPupils[nCount].pupil_id + "' maxlength='3' value='" + dataObj.allocation + "' id='txtAllocation_" + replacedpupilId + "' type='text'><span class='add-on' id='spnAllocation'>%</span></div></div></td>";
                tblPupil += "</tr>";
                $("#tblPayPupilSearch  tbody:last").append(tblPupil);
                $("#tblPayPupilSearch").show();
                tblPupil = "";
            }
        }

    }
    var alloc_cashlength = $("input[id^='txtAllocation']").length;
    var cur_length = 1;
    var totalallocation = 0;
    var allocationpercentage = parseInt(100 / alloc_cashlength);
    $("input[id^='txtAllocation']").each(function() {
        if (cur_length == alloc_cashlength)
        {
            allocationpercentage = 100 - totalallocation;
            $(this).val(allocationpercentage);
        }
        else
        {
            cur_length++;
            totalallocation += allocationpercentage;
            $(this).val(allocationpercentage);
        }
    });
    $("input[id^='txtAllocation']").keypress(function(e) {
        if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
            return false;
        }
    });
    if (!$("#lblpupilsearcherror").hasClass("hide"))
        $("#lblpupilsearcherror").text("").addClass("hide");
    $("#divPayPopulatepupils").css("display", "inline");
    $("#divPupilSearch").css('display', 'inline');
    $("#divPopulatepupils").css('display', 'none');
    $("#btnPupilSubmit").addClass('hide');
    //$("#btnPupilRefundSubmit").addClass("hide");
    $("#btnPupilSearch").removeClass('hide');
    $("#btnPayContinue").removeAttr("disabled", "disabled");
    $("#divAddPupilPay").modal('hide');
    $("#txtSearchPupilId", "#divPupilSearch").val("");
    $("#txtSearchFname", "#divPupilSearch").val("");
    $("#txtSearchMname", "#divPupilSearch").val("");
    $("#txtSearchLname", "#divPupilSearch").val("");
    //$("#cashmakePayment").show();
    chkCashPay = [];
});
function PayContinue()
{
    var tempAllocationAmt = 0;
    var PaymentAmt = $("#txtPaymentAmt").val();
    if (PaymentAmt == "")
    {
        $("#txtPaymentAmt").focus();
        $("#spntxtPaymentAmt").addClass('error');
        $("#lblcashPayerror").text("Please enter the Payment amount.");
        return false;
    }
    if (PaymentAmt == 0)
    {
        $("#txtPaymentAmt").focus();
        $("#spntxtPaymentAmt").addClass('error');
        $("#lblcashPayerror").text("Please enter the amount greater than 0.");
        return false;
    }


    $("#lblcashPayerror").text("");
    if ($("#spntxtPaymentAmt").hasClass("error"))
        $("#spntxtPaymentAmt").removeClass('error');
    $("input[id^='txtAllocation']").each(function() {
        var AllocAmt = $(this).val();
        if (AllocAmt == "")
        {
            AllocAmt = 0;
        }
        tempAllocationAmt += parseInt(AllocAmt);
    });
    if (tempAllocationAmt != 100)
    {
        $("#lblerror").removeClass("hide");
        $("div[id^='divAllocCash']").addClass("error");
        return false;
    }
    if (!$("#lblerror").hasClass("hide"))
    {
        $("#lblerror").addClass("hide");
        $("div[id^='divAllocCash']").removeClass("error");
    }
    var tmpdataset = jsonObj;
    if (tmpdataset.length > 0)
    {
        for (var jcount = 0; jcount < tmpdataset.length; jcount++) {
            var txtAllocAmt = parseInt($("#txtAllocation_" + (tmpdataset[jcount].pupilId).replace("/", "_") + "").val());
            tmpdataset[jcount].allocation = txtAllocAmt;
            tmpdataset[jcount].netamount = ((txtAllocAmt * PaymentAmt) / 100).toFixed(2);
            if (txtAllocAmt > 0)
                payArray.push({"pupil_id": tmpdataset[jcount].pupilId, "amount": tmpdataset[jcount].netamount});
        }
    }
    $("#divAddpupilspayment").css("display", "none");
    $("#divPayPopulatepupils").css("display", "none");
    $("#btnPayCancel").removeClass("hide");
    $("#btnConfirmPay").removeClass("hide");
    $("#btnPayContinue").addClass("hide");
    $("#divProgresBar").css('width', '50%');
    $("#spnAssignPay").css({"font-weight": "normal", "color": "#8e8e8e"});
    $("#spnConfirmPay").css({"font-weight": "bold", "color": "black"});
    $("#spnConfirmPayPhone").css({"font-weight": "bold", "color": "black"});
    $("#spnAssignPayPhone").removeClass('visible-phone');
    $("#spnAssignPayPhone").addClass('hidden-phone');
    $("#spnConfirmPayPhone").removeClass('hidden-phone');
    $("#spnConfirmPayPhone").addClass('visible-phone');
    $("#spnFinishedPhone").removeClass('visible-phone');
    $("#spnFinishedPhone").addClass('hidden-phone');
    $("#lblTotal_Pay").text("£" + parseFloat(PaymentAmt).toFixed(2));
    $("#lblDescriptionPay").text("");
    var tmpresPupils = tmpdataset;
    if (tmpresPupils.length > 0) {
        var tblPupil = "";
        for (var nCount = 0; nCount < tmpresPupils.length; nCount++) {
            if (tmpresPupils[nCount].netamount > 0)
            {
                var pupilId = tmpresPupils[nCount].pupilId;
                var freeMeals = (tmpresPupils[nCount].Meals == 0) ? "<img src='img/fail.png' style='width:20px;display:block;margin:0 auto;' >" : "<img src='img/pass.png' style='width:20px;display:block;margin:0 auto;'>";
                var adult = (tmpresPupils[nCount].adult == 1) ? "<i class='icon-user' ></i>" : "";
                var years = "";
                tblPupil += "<tr><td data-title='Pupil Name' nowrap='nowrap'><div style= 'padding-top:6px' id='pupilfName" + pupilId + "'>" + tmpresPupils[nCount].fname + " " + tmpresPupils[nCount].mname + " " + tmpresPupils[nCount].lname + "</div></td>";
                tblPupil += "<td data-title='Pupil ID' nowrap='nowrap'><div style= 'padding-top:6px' id='pupilid" + pupilId + "'>" + tmpresPupils[nCount].pupilId + "  " + adult + "</div></td>";
                tblPupil += "<td data-title='Free Meals?'><label style= 'padding-top: 6px;' id='freemeals" + pupilId + "'>" + freeMeals + "</label></td>";
                tblPupil += "<td data-title='School'><div style='padding-top: 6px;' id='SchoolNameLabel" + pupilId + "'>" + tmpresPupils[nCount].school + "</div></td>";
                tblPupil += "<td data-title='User Name' nowrap='nowrap'><div style= 'padding-top:6px' id='pupilmName" + pupilId + "'>" + tmpresPupils[nCount].userid + "</div></td>";
                tblPupil += "<td data-title='Amount' nowrap='nowrap'><div style= 'padding-top:6px' id='pupilmName" + pupilId + "'>£" + tmpresPupils[nCount].netamount + "</div></td>";
                tblPupil += "</tr>";
                $("#tblpayConfirmSearch  tbody:last").append(tblPupil);
                $("#tblpayConfirmSearch").show();
                $("#divPayConfirmpupils").css("display", "inline");
                tblPupil = "";
            }
        }
    }
}

function ConfirmPayProcess()
{
    var url = BACKENDURL + "user/make_cash_payment";
    var data = {
        session_id: localStorage["SESSIONID"],
        pupils_res: payArray
    };
    MakeAjaxCall(url, data, ConfirmPay);
}

function ConfirmPay(data)
{
    if (data.error == 0) {
        payArray = [];
        $("#divPayConfirmpupils").css("display", "none");
        $("#divbtncontrolsforpay").css("display", "none");
        $("#divPayConfirmation").css("display", "inline");
        $("#divProgresBar").css('width', '100%');
        $("#spnConfirmPay").css({"font-weight": "normal", "color": "#8e8e8e"});
        $("#spnFinished").css({"font-weight": "bold", "color": "black"});
        $("#spnFinishedPhone").css({"font-weight": "bold", "color": "black"});
        $("#spnAssignPayPhone").removeClass('visible-phone');
        $("#spnAssignPayPhone").addClass('hidden-phone');
        $("#spnConfirmPayPhone").addClass('hidden-phone');
        $("#spnConfirmPayPhone").removeClass('visible-phone');
        $("#spnFinishedPhone").addClass('visible-phone');
        $("#spnFinishedPhone").removeClass('hidden-phone');
        $("#lblConfirmationMessage").text("Thank you, your payment has been successful.  A record of your payment is shown below.").addClass("alert alert-success");
        if (data.cash_payment_res.length > 0) {
            var tblPupil = "";
            for (var nCount = 0; nCount < data.cash_payment_res.length; nCount++) {
                var studentId = data.cash_payment_res[nCount].students_id;
                var pupilId = data.cash_payment_res[nCount].pupil_id;
                var freeMeals = (data.cash_payment_res[nCount].fsm == 0) ? "<img src='img/fail.png' style='width:20px;display:block;margin:0 auto;' >" : "<img src='img/pass.png' style='width:20px;display:block;margin:0 auto;'>";
                var adult = (data.cash_payment_res[nCount].adult == 1) ? "<i class='icon-user' ></i>" : "";
                var years = "";
                tblPupil += "<tr><td data-title='Transaction ID' nowrap='nowrap'><div style= 'padding-top:6px' id='paymentid" + studentId + "'>" + data.cash_payment_res[nCount].payment_id + "</div></td>";
                tblPupil += "<td data-title='Date' nowrap='nowrap'><div style= 'padding-top:6px' id='date" + studentId + "'>" + data.cash_payment_res[nCount].cdate + "</div></td>";
                tblPupil += "<td data-title='Pupil Name' nowrap='nowrap'><div style= 'padding-top:6px' id='pupilfName" + studentId + "'>" + data.cash_payment_res[nCount].fname + " " + data.cash_payment_res[nCount].mname + " " + data.cash_payment_res[nCount].lname + "</div></td>";
                tblPupil += "<td data-title='Pupil ID' nowrap='nowrap'><div style= 'padding-top:6px' id='pupilid" + studentId + "'>" + data.cash_payment_res[nCount].pupil_id + "  " + adult + "</div></td>";
                tblPupil += "<td data-title='Free Meals?'><label style= 'padding-top: 6px;' id='freemeals" + studentId + "'>" + freeMeals + "</label></td>";
                tblPupil += "<td data-title='School'><div style='padding-top: 6px;' id='SchoolNameLabel" + studentId + "'>" + data.cash_payment_res[nCount].school_name + "</div></td>";
                tblPupil += "<td data-title='User Name' nowrap='nowrap'><div style= 'padding-top:6px' id='pupilmName" + studentId + "'>" + localStorage["USER_NAME_PAYMENT"] + "</div></td>";
                tblPupil += "<td data-title='Amount' nowrap='nowrap'><div style= 'padding-top:6px' id='pupilmName" + studentId + "'>£" + data.cash_payment_res[nCount].amount + "</div></td>";
                tblPupil += "</tr>";
                $("#tblPayConfirmationData  tbody:last").append(tblPupil);
                $("#tblPayConfirmationData").show();
                tblPupil = "";
            }
        }
    }
    else
    {
        logout(1);
    }
}

function PaymentHistoryNav()
{
    $("#divPayConfirmation").css("display", "none");
    $("#divbtncontrolsforpay").css("display", "inline");
    $("#tblPaymentHistory tbody tr").remove();
    $("#tblPayConfirmationData tbody tr").remove();
    $("#spnFinished").css({"font-weight": "normal", "color": "#8e8e8e"});
    $("#spnAssignPay").css({"font-weight": "bold", "color": "black"});

    PaymentCancel();
    LoadPayHistorySchools();
    $('#widgetnav a[href="#cashpaymentHistory"]').tab('show');
    $("#lid_dmPayments").addClass("active");
}


function setCashPayment()
{
    cashOptions = 1;
    if ($('#divPayConfirmation').css('display') == 'inline') {
        $("#divPayConfirmation").css("display", "none");
        $("#divbtncontrolsforpay").css("display", "inline");
        $("#tblPaymentHistory tbody tr").remove();
        $("#tblPayConfirmationData tbody tr").remove();
        $("#spnFinished").css({"font-weight": "normal", "color": "#8e8e8e"});
        PaymentCancel();
    }
}

function setCashRefund()
{
    cashOptions = 2;
    if ($('#divRefundConfirmation').css('display') == 'inline') {
        $("#divRefundConfirmation").css("display", "none");
        $("#divbtncontrolsforRefund").css("display", "inline");
        $("#tblPaymentHistory tbody tr").remove();
        $("#tblRefundConfirmationData tbody tr").remove();
        $("#spnFinishedRefund").css({"font-weight": "normal", "color": "#8e8e8e"});
        RefundCancel();
    }
    if ($('#tabCashPaymentHistory').length > 0)
        $("#btnPaymentHistory", "#divbtnRefundHistory").show()
    else
        $("#btnPaymentHistory", "#divbtnRefundHistory").hide()
}

function PaymentCancel()
{
    $("#spnAssignPayPhone").addClass('visible-phone');
    $("#spnAssignPayPhone").removeClass('hidden-phone');
    $("#spnConfirmPayPhone").addClass('hidden-phone');
    $("#spnConfirmPayPhone").removeClass('visible-phone');
    $("#spnFinishedPhone").removeClass('visible-phone');
    $("#spnFinishedPhone").addClass('hidden-phone');
    $("#spnAssignPayPhone").css({"font-weight": "bold", "color": "black"});
    $("#spnAssignPay").css({"font-weight": "bold", "color": "black"});
    $("#spnConfirmPay").css({"font-weight": "normal", "color": "#8e8e8e"});
    $("input[id^='txtAllocation']").each(function() {
        $(this).val("");
    });
    $("#divPayConfirmpupils").css("display", "none");
    $("#divAddpupilspayment").css("display", "inline");
    $("#btnConfirmPay").addClass("hide");
    $("#btnPayCancel").addClass("hide");
    $("#btnPayContinue").removeClass("hide").attr("disabled", "disabled");
    $("#txtPaymentAmt").val("");
    $("#divProgresBar").css("width", "4%");
    jsonObj = [];
    $("#tblPayPupilSearch  tbody tr").remove();
    $("#tblpayConfirmSearch  tbody tr").remove();
    tmpaymentResult = [];
    chkCashPay = [];
    payArray = [];
    tmpaymentResult = "";
    t = 0;
}

function RefundCancel()
{
    $("#spnAssignRefundPhone").addClass('visible-phone');
    $("#spnAssignRefundPhone").removeClass('hidden-phone');
    $("#spnConfirmRefundPhone").addClass('hidden-phone');
    $("#spnConfirmRefundPhone").removeClass('visible-phone');
    $("#spnFinishedRefundPhone").removeClass('visible-phone');
    $("#spnFinishedRefundPhone").addClass('hidden-phone');
    $("#spnAssignRefund").css({"font-weight": "bold", "color": "black"});
    $("#spnConfirmRefund").css({"font-weight": "normal", "color": "#8e8e8e"});
    $("#txtRefundAmt").val("");
    RefundAmt = 0;
    $("#divRefundConfirmpupils").css("display", "none");
    $("#divAddpupilsRefund").css("display", "inline");
    $("#btnConfirmRefund").addClass("hide");
    $("#btnRefundCancel").addClass("hide");
    $("#btnRefundContinue").removeClass("hide").attr("disabled", "disabled");
    $("#divProgresBar_Refund").css("width", "4%");
    jsonObjRefund = [];
    $("#tblRefundPupilSearch  tbody tr").remove();
    $("#tblRefundConfirmSearch  tbody tr").remove();
    tmpRefundResult = [];
    chkCashRefund = [];
    RefundArray = [];
    tmpRefundResult = "";
    k = 0;
}


function clearData()
{
    if (!$("#lblpupilsearcherror").hasClass("hide"))
        $("#lblpupilsearcherror").text("").addClass("hide");
    $("#txtSearchPupilId", "#divPupilSearch").val("");
    $("#txtSearchFname", "#divPupilSearch").val("");
    $("#txtSearchMname", "#divPupilSearch").val("");
    $("#txtSearchLname", "#divPupilSearch").val("");
    $("#divPopulatepupils").css("display", "none");
    $("#divPupilSearch").css("display", "inline");
    $("#btnPupilSearch").removeClass("hide");
    $("#btnPupilSubmit").addClass("hide");
    $("#btnPupilRefundSubmit").addClass("hide");
    $("#divAddPupilPay").modal('hide');
}

/************************* Cash Payment History **************************/

function LoadPayHistorySchools()
{
    $.support.cors = true;
    $.ajax({
        url: BACKENDURL + "user/get_schools_admins",
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
                    $('#ddlPaymentHistorySchools').empty();
                    // $('#selectschool').empty();
                    var schoolsStr = "";
                    for (var nCount = 0; nCount < data.schools_res.length; nCount++) {
                        var disabled = (data.schools_res[nCount].status == "0") ? " (disabled)" : "";
                        schoolsStr += "<option value=" + data.schools_res[nCount].school_id + ">" + data.schools_res[nCount].school_name + disabled + "</option>";
                    }
                    $('#ddlPaymentHistorySchools').append(schoolsStr);
                    tab = "paymentHistory";
                    allSchoolCheck();
                    if (data.schools_res.length == 1) {
                        $("#divhistorySchools", "#cashpaymentHistory").hide();
                    }
                    if (data.schools_res.length > 1) {
                        $("#divhistorySchools", "#cashpaymentHistory").show();
                    } else
                        $("#divhistorySchools", "#cashpaymentHistory").hide();
                } else {
                    alert(data.error_msg);
                    alert(data.error);
                }
            }
        },
        error: function(xhr, textStatus, error) {
            alert(error);
        }
    });
}

function LoadPaymentHistory(page_num)
{
    $("#tblPaymentHistory tbody tr").remove();
    var tblPupil = "";
    var school_id = $('#ddlPaymentHistorySchools').val();
    if (school_id != null) {
        $.support.cors = true;
        $.ajax({
            url: BACKENDURL + "user/get_full_history",
            type: "post",
            data: {
                session_id: localStorage["SESSIONID"],
                school_id: school_id,
                page_no: page_num
            },
            dataType: "json",
            crossDomain: true,
            success: function(data) {
                if (data.session_status) {
                    if (data.error == 0) {
                        $("#tablePagination").remove();
                        $("#frmPaymentHistory").css("display", "inline");
                        $("#lblpaymentHistory").addClass("hide");
                        if (data.full_history_res.history_records.length > 0) {
                            for (var nCount = 0; nCount < data.full_history_res.history_records.length; nCount++) {
                                var studentId = data.full_history_res.history_records[nCount].students_id;
                                var pupilId = data.full_history_res.history_records[nCount].pupil_id;
                                var freeMeals = (data.full_history_res.history_records[nCount].fsm == 0) ? "<img src='img/fail.png' style='width:20px;display:block;margin:0 auto;' >" : "<img src='img/pass.png' style='width:20px;display:block;margin:0 auto;'>";
                                var adult = (data.full_history_res.history_records[nCount].adult == 1) ? "<i class='icon-user' ></i>" : "";
                                var paymentflag = (data.full_history_res.history_records[nCount].pay_refund == 1) ? "alert-line" : "alert-pagination";
                                var refundsign = (data.full_history_res.history_records[nCount].pay_refund == 1) ? "" : "-";
                                tblPupil += "<tr class='" + paymentflag + "'><td nowrap='nowrap'><div style= 'padding-top:6px' id='paymentid" + studentId + "'>" + data.full_history_res.history_records[nCount].payment_id + "</div></td>";
                                tblPupil += "<td nowrap='nowrap'><div style= 'padding-top:6px' id='paydate" + studentId + "'>" + data.full_history_res.history_records[nCount].cdate + "</div></td>";
                                tblPupil += "<td nowrap='nowrap'><div style= 'padding-top:6px' id='pupilfName" + studentId + "'>" + data.full_history_res.history_records[nCount].fname + " " + data.full_history_res.history_records[nCount].mname + " " + data.full_history_res.history_records[nCount].lname + "</div></td>";
                                tblPupil += "<td nowrap='nowrap'><div style= 'padding-top:6px' id='pupilid" + studentId + "'>" + data.full_history_res.history_records[nCount].pupil_id + "  " + adult + "</div></td>";
                                tblPupil += "<td ><label style= 'padding-top: 6px;' id='freemeals" + studentId + "'>" + freeMeals + "</label></td>";
                                tblPupil += "<td><div style='padding-top: 6px;' id='SchoolNameLabel" + studentId + "'>" + data.full_history_res.history_records[nCount].school_name + "</div></td>";
                                tblPupil += "<td nowrap='nowrap'><div style= 'padding-top:6px' id='pupilmName" + studentId + "'>" + data.full_history_res.history_records[nCount].username + "</div></td>";
                                tblPupil += "<td nowrap='nowrap'><div style= 'padding-top:6px' id='pupilmName" + studentId + "'>£" + refundsign + data.full_history_res.history_records[nCount].amount + "</div></td>";
                                tblPupil += "</tr>";
                                $("#tblPaymentHistory  tbody:last").append(tblPupil);
                                $("#tblPaymentHistory").show();
                                tblPupil = "";
                                if (data.full_history_res.history_records[nCount].pupil_id == $("#AddPupilId").val()) {
                                    nCurrRecRound = Math.floor(nCount / 10);
                                }
                            }
                            if (data.full_history_res.total_history_records > 10) {
                                var total_CashPayHistory = data.full_history_res.total_history_records;
                                var total_page = Math.ceil(total_CashPayHistory / 20);
                                var no_pages = total_page > 3 ? 3 : 1;
                                if (total_CashPayHistory > 20) {
                                    var options = {
                                        currentPage: 1,
                                        alignment: "right",
                                        totalPages: total_page,
                                        numberOfPages: no_pages, pageUrl: "javascript:void(0)",
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
                                            LoadPaymentHistory(page);
                                        }
                                    }
                                    $("#CashPayHistory_pag").bootstrapPaginator(options);
                                    $("#CashPayHistory_pag").show();
                                }
                                else
                                {
                                    $("#CashPayHistory_pag").hide();
                                }
                            }
                            //$("#tablePagination").css({"margin-top": "10px", "margin-bottom": "3px"});
                        }
                        else
                        {
                            $("#CashPayHistory_pag").hide();
                            $("#frmPaymentHistory").css("display", "none");
                            $("#lblpaymentHistory").text("No payment history found").removeClass("hide");
                        }
                    } else
                        logout(1);
                }
            }

        })
    } else
    {
        $("#divPaymentHistErr").show();
    }
}

/************************* Cash Refund Mode ******************************/
$("#btnPupilRefundSubmit").click(function()
{
    var chkboxAction = 0;
    $("input[type=checkbox]", "#tblPupilSearch").each(function()
    {
        if ($(this).is(':checked'))
        {
            chkboxAction = 1;
        }
    });
    if (chkboxAction == 0)
    {
        $("#lblpupilsearcherror").text("Please select pupil to continue the payment process").removeClass("hide");
        return false;
    }
    var tmpresPupils = JSON.parse(tmpaymentResult);
    if (tmpresPupils.length > 0) {
        var tblPupil = "";
        for (var nCount = 0; nCount < tmpresPupils.length; nCount++) {
            var studentId = tmpresPupils[nCount].students_id;
            var pupilId = tmpresPupils[nCount].pupil_id;
            var replacedpupilId = pupilId.replace("/", "_");
            var freeMeals = (tmpresPupils[nCount].fsm == 0) ? "<img src='img/fail.png' style='width:20px;display:block;margin:0 auto;' >" : "<img src='img/pass.png' style='width:20px;display:block;margin:0 auto;'>";
            var adult = (tmpresPupils[nCount].adult == 1) ? "<i class='icon-user' ></i>" : "";
            var objexists = 0;
            for (var tmpCount = 0; tmpCount < jsonObjRefund.length; tmpCount++)
            {
                if (pupilId == jsonObjRefund[tmpCount].pupilId)
                {
                    objexists = 1;
                }
            }
            if ($.inArray(pupilId, chkCashRefund) > -1 && (objexists == 0))
            {
                RefundAmt += parseFloat(tmpresPupils[nCount].cash_balance);
                localStorage["Refund_Amt"] = RefundAmt;
                var dataObj = new Object();
                if (tmpresPupils[nCount].cash_balance > 0)
                {
                    dataObj.students_id = tmpresPupils[nCount].students_id;
                    dataObj.fname = tmpresPupils[nCount].fname;
                    dataObj.mname = tmpresPupils[nCount].mname;
                    dataObj.lname = tmpresPupils[nCount].lname;
                    dataObj.pupilId = tmpresPupils[nCount].pupil_id;
                    dataObj.Meals = tmpresPupils[nCount].fsm;
                    dataObj.school = tmpresPupils[nCount].school_name;
                    dataObj.CurrentBal = tmpresPupils[nCount].cash_balance;
                    dataObj.userid = tmpresPupils[nCount].fname;
                    dataObj.adult = tmpresPupils[nCount].adult;
                    dataObj.userid = localStorage["USER_NAME_PAYMENT"];
                    dataObj.allocation = 0;
                    dataObj.netamount = tmpresPupils[nCount].cash_balance;
                    jsonObjRefund[k] = dataObj;
                    RefundArray.push({"pupil_id": tmpresPupils[nCount].pupil_id, "cash_balance": tmpresPupils[nCount].cash_balance});
                    k += 1;
                }
                tblPupil += "<tr><td data-title='Pupil Name' nowrap='nowrap'><div style= 'padding-top:6px' id='pupilfName" + studentId + "'>" + tmpresPupils[nCount].fname + " " + tmpresPupils[nCount].mname + " " + tmpresPupils[nCount].lname + "</div></td>";
                tblPupil += "<td data-title='Pupil ID' nowrap='nowrap'><div style= 'padding-top:6px' id='pupilid" + studentId + "'>" + tmpresPupils[nCount].pupil_id + "  " + adult + "</div></td>";
                tblPupil += "<td data-title='Free Meals?'><label style= 'padding-top: 6px;' id='freemeals" + studentId + "'>" + freeMeals + "</label></td>";
                tblPupil += "<td data-title='School'><div style='padding-top: 6px;' id='SchoolNameLabel" + studentId + "'>" + tmpresPupils[nCount].school_name + "</div></td>";
                tblPupil += "<td data-title='username'><div style='padding-top: 6px;' id='username" + studentId + "'>" + localStorage["USER_NAME_PAYMENT"] + "</div></td>";
                tblPupil += "<td data-title='Current Balance'><div class='controls'><div class='input-prepend'> <span class='add-on' id='spnCurrentBal'>£</span><input class='input-small' name='txtCurrentBal' id='txtCurrentBal_" + tmpresPupils[nCount].pupil_id + "' type='text' disabled value=' " + tmpresPupils[nCount].cash_balance + " '></div></div></td>";
                tblPupil += "</tr>";
                $("#tblRefundPupilSearch  tbody:last").append(tblPupil);
                $("#tblRefundPupilSearch").show();
                tblPupil = "";
            }
        }

    }
    if (!$("#lblpupilsearcherror").hasClass("hide"))
        $("#lblpupilsearcherror").text("").addClass("hide");
    $("#txtRefundAmt").val(RefundAmt);
    $("#divRefundPopulatepupils").css("display", "inline");
    //$("#lblpupilsearcherror").text("");
    $("#divPupilSearch").css('display', 'inline');
    $("#divPopulatepupils").css('display', 'none');
    $("#btnPupilRefundSubmit").addClass("hide");
    $("#btnPupilSearch").removeClass("hide");
    $("#divAddPupilPay").modal('hide');
    // $("#lblpupilsearcherror").text("");
    $("#txtSearchPupilId", "#divPupilSearch").val("");
    $("#txtSearchFname", "#divPupilSearch").val("");
    $("#txtSearchMname", "#divPupilSearch").val("");
    $("#txtSearchLname", "#divPupilSearch").val("");
    chkCashRefund = [];
    if (RefundAmt == 0)
    {
        $("#txtRefundAmt").val("0.00");
        return false;
    }
    $("#btnRefundContinue").removeAttr("disabled", "disabled");
});
function RefundContinue()
{
    var tmpdataset = jsonObjRefund;
    $("#lblTotal_Refund").text("£" + parseFloat($("#txtRefundAmt").val()).toFixed(2));
    $("#divAddpupilsRefund").css("display", "none");
    $("#divRefundPopulatepupils").css("display", "none");
    $("#btnRefundCancel").removeClass("hide");
    $("#btnConfirmRefund").removeClass("hide");
    $("#btnRefundContinue").addClass("hide");
    $("#divProgresBar_Refund").css('width', '50%');
    $("#spnAssignRefund").css({"font-weight": "normal", "color": "#8e8e8e"});
    $("#spnConfirmRefund").css({"font-weight": "bold", "color": "black"});
    $("#spnConfirmRefundPhone").css({"font-weight": "bold", "color": "black"});
    $("#spnAssignRefundPhone").removeClass('visible-phone');
    $("#spnAssignRefundPhone").addClass('hidden-phone');
    $("#spnConfirmRefundPhone").removeClass('hidden-phone');
    $("#spnConfirmRefundPhone").addClass('visible-phone');
    $("#spnFinishedRefundPhone").removeClass('visible-phone');
    $("#spnFinishedRefundPhone").addClass('hidden-phone');
    var tmpresPupils = tmpdataset;
    if (tmpresPupils.length > 0) {
        var tblPupil = "";
        for (var nCount = 0; nCount < tmpresPupils.length; nCount++) {
            var pupilId = tmpresPupils[nCount].pupilId;
            var freeMeals = (tmpresPupils[nCount].Meals == 0) ? "<img src='img/fail.png' style='width:20px;display:block;margin:0 auto;' >" : "<img src='img/pass.png' style='width:20px;display:block;margin:0 auto;'>";
            var adult = (tmpresPupils[nCount].adult == 1) ? "<i class='icon-user' ></i>" : "";
            tblPupil += "<tr><td data-title='Pupil Name' nowrap='nowrap'><div style= 'padding-top:6px' id='pupilfName" + pupilId + "'>" + tmpresPupils[nCount].fname + " " + tmpresPupils[nCount].mname + " " + tmpresPupils[nCount].lname + "</div></td>";
            tblPupil += "<td data-title='Pupil ID' nowrap='nowrap'><div style= 'padding-top:6px' id='pupilid" + pupilId + "'>" + tmpresPupils[nCount].pupilId + "  " + adult + "</div></td>";
            tblPupil += "<td data-title='Free Meals?'><label style= 'padding-top: 6px;' id='freemeals" + pupilId + "'>" + freeMeals + "</label></td>";
            tblPupil += "<td data-title='School'><div style='padding-top: 6px;' id='SchoolNameLabel" + pupilId + "'>" + tmpresPupils[nCount].school + "</div></td>";
            tblPupil += "<td data-title='User Name' nowrap='nowrap'><div style= 'padding-top:6px' id='pupilmName" + pupilId + "'>" + tmpresPupils[nCount].userid + "</div></td>";
            tblPupil += "<td data-title='Amount' nowrap='nowrap'><div style= 'padding-top:6px' id='pupilmName" + pupilId + "'>-£" + tmpresPupils[nCount].netamount + "</div></td>";
            tblPupil += "</tr>";
            $("#tblRefundConfirmSearch  tbody:last").append(tblPupil);
            $("#tblRefundConfirmSearch").show();
            $("#divRefundConfirmpupils").css("display", "inline");
            tblPupil = "";
        }
    }
}

function ConfirmRefundProcess()
{
    var url = BACKENDURL + "user/save_cash_refund";
    var data = {
        session_id: localStorage["SESSIONID"],
        refund_data: RefundArray
    };
    MakeAjaxCall(url, data, confirmRefund);
}

function confirmRefund(data)
{
    if (data.error == 0) {
        $("#divRefundConfirmpupils").css("display", "none");
        $("#divbtncontrolsforRefund").css("display", "none");
        $("#divRefundConfirmation").css("display", "inline");
        $("#divProgresBar_Refund").css('width', '100%');
        $("#spnConfirmRefund").css({"font-weight": "normal", "color": "#8e8e8e"});
        $("#spnFinishedRefund").css({"font-weight": "bold", "color": "black"});
        $("#spnFinishedRefundPhone").css({"font-weight": "bold", "color": "black"});
        $("#spnAssignRefundPhone").removeClass('visible-phone');
        $("#spnAssignRefundPhone").addClass('hidden-phone');
        $("#spnConfirmRefundPhone").addClass('hidden-phone');
        $("#spnConfirmRefundPhone").removeClass('visible-phone');
        $("#spnFinishedRefundPhone").addClass('visible-phone');
        $("#spnFinishedRefundPhone").removeClass('hidden-phone');
        $("#lblconfirm_Refund").text("-£" + parseFloat(localStorage["Refund_Amt"]).toFixed(2));
        $("#lblRefundConfirmationMessage").text("Thank you, your refund has been successful.  A record of your refund is shown below.").addClass("alert alert-success")
        if (data.save_cash_refund_res.length > 0) {
            var tblPupil = "";
            for (var nCount = 0; nCount < data.save_cash_refund_res.length; nCount++) {
                var studentId = data.save_cash_refund_res[nCount].students_id;
                var pupilId = data.save_cash_refund_res[nCount].pupil_id;
                var freeMeals = (data.save_cash_refund_res[nCount].fsm == 0) ? "<img src='img/fail.png' style='width:20px;display:block;margin:0 auto;' >" : "<img src='img/pass.png' style='width:20px;display:block;margin:0 auto;'>";
                var adult = (data.save_cash_refund_res[nCount].adult == 1) ? "<i class='icon-user' ></i>" : "";
                tblPupil += "<tr><td data-title='Transaction ID' nowrap='nowrap'><div style= 'padding-top:6px' id='payment_id" + studentId + "'>" + data.save_cash_refund_res[nCount].payment_id + "</div></td>";
                tblPupil += "<td data-title='Date' nowrap='nowrap'><div style= 'padding-top:6px' id='date" + studentId + "'>" + data.save_cash_refund_res[nCount].cdate + "</div></td>";
                tblPupil += "<td data-title='Pupil First Name' nowrap='nowrap'><div style= 'padding-top:6px' id='pupilfName" + studentId + "'>" + data.save_cash_refund_res[nCount].fname + " " + data.save_cash_refund_res[nCount].mname + " " + data.save_cash_refund_res[nCount].lname + "</div></td>";
                tblPupil += "<td data-title='Pupil ID' nowrap='nowrap'><div style= 'padding-top:6px' id='pupilid" + studentId + "'>" + data.save_cash_refund_res[nCount].pupil_id + "  " + adult + "</div></td>";
                tblPupil += "<td data-title='Free Meals?'><label style= 'padding-top: 6px;' id='freemeals" + studentId + "'>" + freeMeals + "</label></td>";
                tblPupil += "<td data-title='School'><div style='padding-top: 6px;' id='SchoolNameLabel" + studentId + "'>" + data.save_cash_refund_res[nCount].school_name + "</div></td>";
                tblPupil += "<td data-title='User Name' nowrap='nowrap'><div style= 'padding-top:6px' id='pupilmName" + studentId + "'>" + data.save_cash_refund_res[nCount].username + "</div></td>";
                tblPupil += "<td data-title='Amount' nowrap='nowrap'><div style= 'padding-top:6px' id='cash_balance" + studentId + "'> -£" + data.save_cash_refund_res[nCount].amount + "</div></td>";
                tblPupil += "</tr>";
                $("#tblRefundConfirmationData  tbody:last").append(tblPupil);
                $("#tblRefundConfirmationData").show();
                tblPupil = "";
            }
        }
    }
    else
    {
        logout(1);
        /* $("#divRefundConfirmpupils").css("display", "none");
         $("#divbtncontrolsforRefund").css("display", "none");
         $("#divRefundConfirmation").css("display", "inline");
         $("#divProgresBar_Refund").css('width', '100%');
         $("#spnConfirmRefund").css({"font-weight": "normal", "color": "#8e8e8e"});
         $("#spnFinishedRefund").css({"font-weight": "bold", "color": "black"});
         $("#lblTotal_Refund").text("£" + parseFloat($("#txtRefundAmt").val()).toFixed(2));
         $("#lblRefundConfirmationMessage").text("Your Payment transaction has been failed due to"+" "+data.error_msg).addClass("alert alert-error");
         $(".form-horizontal","#divRefundConfirmation").hide();  */
    }
}

function RefundHistoryNav()
{
    $("#spnAssignRefund").css({"font-weight": "bold", "color": "black"});
    $("#spnAssignRefundPhone").css({"font-weight": "bold", "color": "black"});
    $("#spnAssignRefundPhone").addClass('visible-phone');
    $("#spnAssignRefundPhone").removeClass('hidden-phone');
    $("#spnConfirmRefundPhone").addClass('hidden-phone');
    $("#spnConfirmRefundPhone").removeClass('visible-phone');
    $("#spnFinishedRefundPhone").removeClass('visible-phone');
    $("#spnFinishedRefundPhone").addClass('hidden-phone');
    $("#divRefundConfirmation").css("display", "none");
    $("#divbtncontrolsforRefund").css("display", "inline");
    $("#tblPaymentHistory tbody tr").remove();
    $("#tblRefundConfirmationData tbody tr").remove();
    $("#spnFinishedRefund").css({"font-weight": "normal", "color": "#8e8e8e"});
    RefundCancel();
    LoadPayHistorySchools();
    $('#widgetnav a[href="#cashpaymentHistory"]').tab('show');
}

/*********************** Card Payment Mode ******************************/

function loadCardPayDetails()
{
    setCardPayment();
    var url = BACKENDURL + "user/get_pupils_topay";
    var data = {
        session_id: localStorage["SESSIONID"]
    };
    MakeAjaxCall(url, data, loadPupils_topay);
}

function loadPupils_topay(data)
{

    if (data.error == 0)
    {
        if (data.get_pupils_res.length == 0)
        {
            $("#NoPupilMsg").show();
            $("#divCardMakePayment").hide();
        }
        else {
            $("#NoPupilMsg").hide();
            $("#divCardMakePayment").show();
            $("#spnAssignPayCardPhone").addClass('visible-phone');
            $("#spnAssignPayCardPhone").removeClass('hidden-phone');
            $("#spnConfirmPayCardPhone").addClass('hidden-phone');
            $("#spnConfirmPayCardPhone").removeClass('visible-phone');
            $("#spnFinishedPayCardPhone").removeClass('visible-phone');
            $("#spnFinishedPayCardPhone").addClass('hidden-phone');
            var tblPupil = "";
            localStorage["USER_NAME_PAYMENT"] = data.username;
            var allocpercent = parseInt(100 / data.get_pupils_res.length);
            $("#tblCardpaypupilData  tbody:last").empty();
            for (var nCount = 0; nCount < data.get_pupils_res.length; nCount++) {
                if (data.get_pupils_res[nCount].trans_fee_status == "0")
                {
                    $("#divcardType", "#divCardPayment").addClass("hide");
                    $("#divcardFee", "#divCardPayment").addClass("hide");
                }
                if (nCount == 0)
                {
                    localStorage["dc_fee"] = data.get_pupils_res[nCount].dc_fee;
                    localStorage["cc_fee"] = data.get_pupils_res[nCount].cc_fee;
                    localStorage["min_card_pay"] = data.get_pupils_res[nCount].min_card_pay;
                    $("#txtCardTransFee").val(localStorage["dc_fee"]);
                }
                if (nCount == data.get_pupils_res.length - 1)
                    allocpercent = (100 - allocpercent * (data.get_pupils_res.length - 1));
                var studentId = data.get_pupils_res[nCount].students_id;
                var pupilId = data.get_pupils_res[nCount].pupil_id;
                var replacedpupilId = pupilId.replace("/", "_");
                var freeMeals = (data.get_pupils_res[nCount].fsm == 0) ? "<img src='img/fail.png' style='width:20px;display:block;margin:0 auto;' >" : "<img src='img/pass.png' style='width:20px;display:block;margin:0 auto;'>";
                var adult = (data.get_pupils_res[nCount].adult == 1) ? "<i class='icon-user' ></i>" : "";
                var dataObj = new Object();
                dataObj.students_id = data.get_pupils_res[nCount].students_id;
                dataObj.fname = data.get_pupils_res[nCount].fname;
                dataObj.mname = data.get_pupils_res[nCount].mname;
                dataObj.lname = data.get_pupils_res[nCount].lname;
                dataObj.pupilId = data.get_pupils_res[nCount].pupil_id;
                dataObj.fsm = data.get_pupils_res[nCount].fsm;
                dataObj.school = data.get_pupils_res[nCount].school_name;
                dataObj.CurrentBal = data.get_pupils_res[nCount].card_balance;
                dataObj.adult = data.get_pupils_res[nCount].adult;
                dataObj.username = data.username;
                dataObj.allocation = allocpercent;
                dataObj.netamount = 0;
                dataObj.dc_fee = data.get_pupils_res[nCount].dc_fee;
                dataObj.cc_fee = data.get_pupils_res[nCount].cc_fee;
                dataObj.trans_status = data.get_pupils_res[nCount].trans_fee_status;
                jsonCardPayObj[nCount] = dataObj;
                tblPupil += "<tr><td data-title='Pupil Name' nowrap='nowrap'><div style= 'padding-top:6px' id='pupilfName" + studentId + "'>" + data.get_pupils_res[nCount].fname + " " + data.get_pupils_res[nCount].mname + " " + data.get_pupils_res[nCount].lname + "</div></td>";
                tblPupil += "<td data-title='Pupil ID' nowrap='nowrap'><div style= 'padding-top:6px' id='pupilid" + studentId + "'>" + data.get_pupils_res[nCount].pupil_id + "  " + adult + "</div></td>";
                tblPupil += "<td data-title='Free Meals?'><label style= 'padding-top: 6px;' id='freemeals" + studentId + "'>" + freeMeals + "</label></td>";
                tblPupil += "<td data-title='School'><div style='padding-top: 6px;' id='SchoolNameLabel" + studentId + "'>" + data.get_pupils_res[nCount].school_name + "</div></td>";
                tblPupil += "<td data-title='Current Balance'><div class='controls'><div class='input-prepend'> <span class='add-on' id='spnCurrentBal'>£</span><input class='input-small' name='txtCurrentBal' id='txtCurrentBal_" + data.get_pupils_res[nCount].pupil_id + "' type='text' disabled value=' " + data.get_pupils_res[nCount].card_balance + " '></div></div></td>";
                tblPupil += "<td data-title='Allocation'><div class='control-group' id='divallocCard" + data.get_pupils_res[nCount].pupil_id + " '><div class='input-append'> <input name='txtAllocation" + data.get_pupils_res[nCount].pupil_id + "' maxlength='3' id='txtCardAllocation_" + replacedpupilId + "' value= '" + allocpercent + "' type='text' class='input-small'><span class='add-on' id='spnAllocation'>%</span></div></div></td>";
                tblPupil += "</tr>";
                $("#tblCardpaypupilData  tbody:last").append(tblPupil);
                $("#tblCardpaypupilData").show();
                tblPupil = "";
            }
            $("input[id^='txtCardAllocation_']").keypress(function(e) {
                if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
                    return false;
                }
            });
            if ($("#inputPay").length == "0")
            {
                $("#divCardPayment").append("<form target='inputData' method='post'><div id='paymentConfirm'></div><input type='submit' value='Continue' class='btn btn-primary pull-right' id='btnPayCardContinue' name='btnPayCardContinue'  onclick='ctncardPayment();' disabled='disabled' /></form>");
                $("#btnPayCardContinue", "#divbtncontrolsforCardPay").removeAttr("disabled");
                $("#divCardPayConfirmpupils").append("<iframe id='inputPay' name='inputData' seamless='seamless' style='height: 400px; width: 100%; border: none; frameborder: 0; '></iframe>");
            }
        }
    }
    else
    {
        alert(data.error_msg);
        alert(data.error);
    }

}

function ctncardPayment()
{
    var tempcardAllocAmt = 0, PupilConfirm = "", pay_mode = "", item_count = 1;
    var CardPaymentAmt = $("#txtCardPaymentAmt").val();
    if (CardPaymentAmt == "")
    {
        $("#txtCardPaymentAmt").focus();
        $("#spnCardPaymentAmt").addClass('error');
        $("#lblcardPayerror").text("Please enter the Payment amount.");
        return false;
    }
    if (CardPaymentAmt == 0)
    {
        $("#txtCardPaymentAmt").focus();
        $("#spnCardPaymentAmt").addClass('error');
        $("#lblcardPayerror").text("Please enter the amount greater than 0.");
        return false;
    }

    if (CardPaymentAmt < parseFloat(localStorage["min_card_pay"]))
    {
        $("#txtCardPaymentAmt").focus();
        $("#spnCardPaymentAmt").addClass('error');
        $("#lblcardPayerror").text("Minimum card payment is £" + parseFloat(localStorage["min_card_pay"]));
        return false;
    }

    $("#lblcardPayerror").text("");
    if ($("#spnCardPaymentAmt").hasClass("error"))
        $("#spnCardPaymentAmt").removeClass('error');
    $("input[id^='txtCardAllocation']").each(function() {
        var CardAllocAmt = $(this).val();
        if (CardAllocAmt == 0)
        {
            CardAllocAmt = 0;
        }
        tempcardAllocAmt += parseInt(CardAllocAmt);
    });
    if (tempcardAllocAmt != 100)
    {
        $("#lblCarderror").removeClass("hide");
        $("div[id^='divallocCard']").addClass("error");
        return false;
    }
    if (!$("#lblCarderror").hasClass("hide"))
    {
        $("#lblCarderror").addClass("hide");
        $("div[id^='divallocCard']").removeClass("error");
    }
    var cardPay_type = $("[type=radio][name='paymentOptions']:checked").val();
    var tmpresPupils = jsonCardPayObj;
    if (tmpresPupils.length > 0)
    {
        if (tmpresPupils[0].trans_status == 1) {
            $("#divTransFee").css('display', 'block !important');
        } else {
            $("#divTransFee").css('display', 'none');
        }
        for (var jcount = 0; jcount < tmpresPupils.length; jcount++) {
            var txtAllocAmt = parseInt($("#txtCardAllocation_" + (tmpresPupils[jcount].pupilId).replace("/", "_") + "").val());
            tmpresPupils[jcount].allocation = txtAllocAmt;
            tmpresPupils[jcount].netamount = ((txtAllocAmt * CardPaymentAmt) / 100).toFixed(2);
            if (txtAllocAmt > 0)
                cardPayArray.push({"pupil_id": tmpresPupils[jcount].pupilId, "amount": tmpresPupils[jcount].netamount, "card_type": cardPay_type, "allocation_percentage": tmpresPupils[jcount].allocation});
        }
    }
    //$("#lblCarderror").text("");
    $("#divCardPayment").css("display", "none");
    $("#btnPayCardCancel").removeClass("hide");
    $("#btnPayCardConfirm").removeClass("hide");
    $("#btnPayCardContinue").addClass("hide");
    $("#divProgresBar_Card").css('width', '50%');
    $("#spnAssignPayCard").css({"font-weight": "normal", "color": "#8e8e8e"});
    $("#spnConfirmPayCard").css({"font-weight": "bold", "color": "black"});
    $("#spnConfirmPayCardPhone").css({"font-weight": "bold", "color": "black"});
    $("#spnAssignPayCardPhone").removeClass('visible-phone');
    $("#spnAssignPayCardPhone").addClass('hidden-phone');
    $("#spnConfirmPayCardPhone").removeClass('hidden-phone');
    $("#spnConfirmPayCardPhone").addClass('visible-phone');
    $("#spnFinishedPayCardPhone").removeClass('visible-phone');
    $("#spnFinishedPayCardPhone").addClass('hidden-phone');
    $.ajax({
        url: BACKENDURL + "user/initiate_card_payment",
        type: "post",
        data: {
            session_id: localStorage["SESSIONID"],
            pupils_res: cardPayArray
        },
        dataType: "json",
        async: false,
        crossDomain: true,
        success: function(data) {
            if (data.error == 0) {
                localStorage["MTR"] = data.payment_res.mtr;
                for (var nCount = 0; nCount < tmpresPupils.length; nCount++) {
                    if (tmpresPupils[nCount].netamount > 0)
                    {
                        var freeMeals = (tmpresPupils[nCount].fsm == 0) ? " " : "Free School Meals, ";
                        var adult = (tmpresPupils[nCount].adult == 1) ? "(Adult)" : "";
                        var cash_pay = parseFloat(tmpresPupils[nCount].netamount) * 100;
                        PupilConfirm += "<input type='hidden' name='item_name_" + item_count + "' value='" + tmpresPupils[nCount].fname + " " + tmpresPupils[nCount].mname + " " + tmpresPupils[nCount].lname + ", " + tmpresPupils[nCount].pupilId + adult + ", " + freeMeals + tmpresPupils[nCount].school + "'>";
                        PupilConfirm += " <input type='hidden' name='item_value_" + item_count + "' value='" + cash_pay + "'>";
                        PupilConfirm += " <input type='hidden' name='item_number_" + item_count + "' value=" + item_count + ">";
                        PupilConfirm += " <input type='hidden' name='item_quantity_" + item_count + "' value='1'>";
                        PupilConfirm += " <input type='hidden' name='item_tax_" + item_count + "' value='0'>";
                    }
                    item_count = parseInt(item_count) + 1;
                }
                if (!$("#divcardType", "#divCardPayment").hasClass("hide"))
                {
                    if ($('input[name=paymentOptions]:checked', '#CardpaymentOptions').val() == "dc")
                    {
                        pay_mode = "Debit Card charges";
                        cash_pay = parseFloat(tmpresPupils[0].dc_fee) * 100;
                    }
                    else
                    {
                        pay_mode = "Credit Card charges";
                        cash_pay = parseFloat($("#txtCardPaymentAmt").val()) * parseInt(localStorage["cc_fee"]);
                        //cash_pay = parseInt(tmpresPupils[0].cc_fee) * 100;
                    }
                    PupilConfirm += "<input type='hidden' name='item_name_" + item_count + "' value='" + pay_mode + "'>";
                    PupilConfirm += " <input type='hidden' name='item_value_" + item_count + "' value='" + cash_pay + "'>";
                    PupilConfirm += " <input type='hidden' name='item_number_" + item_count + "' value=" + item_count + ">";
                    PupilConfirm += " <input type='hidden' name='item_quantity_" + item_count + "' value='1'>";
                    PupilConfirm += " <input type='hidden' name='item_tax_" + item_count + "' value='0'>";
                }
                if (location.host == "localhost")
                {
                    PupilConfirm += " <input type='hidden' name='callbackSuccess' value='" + location.protocol + '//' + location.host + '/rentokil/codebase/ri_frontend/statusYesPay.html?' + "'>";
                    PupilConfirm += " <input type='hidden' name='callbackFailure' value='" + location.protocol + '//' + location.host + '/rentokil/codebase/ri_frontend/statusYesPay.html?' + "'>";
                }
                else
                {
                    PupilConfirm += " <input type='hidden' name='callbackSuccess' value='" + location.protocol + '//' + location.host + '/ri_frontend/statusYesPay.html?' + "'>";
                    PupilConfirm += " <input type='hidden' name='callbackFailure' value='" + location.protocol + '//' + location.host + '/ri_frontend/statusYesPay.html?' + "'>";
                }
                PupilConfirm += " <input type='hidden' name='amount' value='" + data.payment_res.amount + "'>";
                PupilConfirm += " <input type='hidden' name='merchantID' value='" + data.payment_res.merchant_id + "'>";
                //PupilConfirm += " <input type='hidden' name='password' value='" + data.payment_res.password + "'>";
                PupilConfirm += " <input type='hidden' name='MTR' value='" + data.payment_res.mtr + "'>";
                PupilConfirm += " <input type='hidden' name='digest' value='" + data.payment_res.digest + "'>";
                //PupilConfirm += " <input type='hidden' name='terminalIds' value='22980091'>";
                PupilConfirm += "<input type='hidden' name='version' value='5000'>";
                PupilConfirm += " <input type='hidden' name='currency' value='GBP'>";
                PupilConfirm += " <input type='hidden' name='proceedToACS' value='true'>";
                $("#paymentConfirm").append(PupilConfirm);
                $('#widgetnav li > a').prop('disabled', true);
                $("#paymentConfirm").closest("form").attr("action", "https://www.yes-pay.net/embossServlet/payment");
                $("#inputPay").load(function() {
                    $("#loaderIcon_Card").hide();
                });
            }
            else {
                console.log("Error case - continue parent pay");
            }
        },
        error: function(xhr, textStatus, error) {
            alert(error);
        }
    });
    $("#divCardPayConfirmpupils").css("display", "inline");
}

/*function PaymentCardDetails()
 {
 $("#divProgresBar_Card").css('width', '60%');
 $("#spnConfirmPayCard").css({"font-weight": "normal", "color": "#8e8e8e"});
 $("#spnPaymentcard").css({"font-weight": "bold", "color": "black"});
 $("#divCardPayConfirmpupils").css("display", "none");
 $("#divCardPaymentDetails").css("display", "inline");
 $("#btnPayCardCancel").addClass("hide");
 $("#btnPayCardConfirm").addClass("hide");
 $("#btnPaymentDetails").removeClass("hide");
 }
 
 function CardPaymentProcess()
 {
 var url = BACKENDURL + "user/make_card_payment";
 var data = {
 session_id: localStorage["SESSIONID"],
 pupils_res: cardPayArray
 };
 MakeAjaxCall(url, data, ConfirmCardPay);
 }
 
 function ConfirmCardPay(data)
 {
 $("#btnPaymentDetails", "#divbtncontrolsforCardPay").addClass("hide");
 if (data.error == 0) {
 cardPayArray = [];
 $("#divCardPaymentDetails").css("display", "none");
 $("#divbtncontrolsforCardPay").css("display", "none");
 $("#divCardPayConfirmation").css("display", "inline");
 $("#divProgresBar_Card").css('width', '100%');
 $("#spnPaymentcard").css({"font-weight": "normal", "color": "#8e8e8e"});
 $("#spnFinishedPayCard").css({"font-weight": "bold", "color": "black"});
 $("#lblCardConfmMessage").text("Thank you, your payment has been successful.  A record of your payment is shown below.").addClass("alert alert-success");
 if (data.card_payment_res.length > 0) {
 var tblPupil = "";
 var cardTransactionFee = data.card_payment_res[0].transaction_fee;
 for (var nCount = 0; nCount < data.card_payment_res.length; nCount++) {
 var studentId = data.card_payment_res[nCount].students_id;
 var pupilId = data.card_payment_res[nCount].pupil_id;
 var freeMeals = (data.card_payment_res[nCount].fsm == 0) ? "<img src='img/fail.png' style='width:20px;display:block;margin:0 auto;' >" : "<img src='img/pass.png' style='width:20px;display:block;margin:0 auto;'>";
 var adult = (data.card_payment_res[nCount].adult == 1) ? "<i class='icon-user' ></i>" : "";
 tblPupil += "<tr><td nowrap='nowrap'><div style= 'padding-top:6px' id='paymentid" + studentId + "'>" + data.card_payment_res[nCount].payment_id + "</div></td>";
 tblPupil += "<td nowrap='nowrap'><div style= 'padding-top:6px' id='date" + studentId + "'>" + data.card_payment_res[nCount].cdate + "</div></td>";
 tblPupil += "<td nowrap='nowrap'><div style= 'padding-top:6px' id='pupilfName" + studentId + "'>" + data.card_payment_res[nCount].fname + " " + data.card_payment_res[nCount].mname + " " + data.card_payment_res[nCount].lname + "</div></td>";
 tblPupil += "<td nowrap='nowrap'><div style= 'padding-top:6px' id='pupilid" + studentId + "'>" + data.card_payment_res[nCount].pupil_id + "  " + adult + "</div></td>";
 tblPupil += "<td ><label style= 'padding-top: 6px;' id='freemeals" + studentId + "'>" + freeMeals + "</label></td>";
 tblPupil += "<td><div style='padding-top: 6px;' id='SchoolNameLabel" + studentId + "'>" + data.card_payment_res[nCount].school_name + "</div></td>";
 tblPupil += "<td nowrap='nowrap'><div style= 'padding-top:6px' id='pupilmName" + studentId + "'>" + localStorage["USER_NAME_PAYMENT"] + "</div></td>";
 tblPupil += "<td nowrap='nowrap'><div style= 'padding-top:6px' id='pupilmName" + studentId + "'>£" + data.card_payment_res[nCount].amount + "</div></td>";
 if (cardTransactionFee > 0)
 tblPupil += "<td nowrap='nowrap'><div style= 'padding-top:6px' id='pupilmName" + studentId + "'>£" + data.card_payment_res[nCount].transaction_fee + "</div></td>";
 tblPupil += "</tr>";
 $("#tblCardPayConfirmationData  tbody:last").append(tblPupil);
 $("#tblCardPayConfirmationData").show();
 tblPupil = "";
 }
 if (cardTransactionFee == 0)
 $("#tblCardPayConfirmationData thead tr th :last").remove();
 }
 }
 else
 logout(1);
 }*/

function cardPaymentHistory(page_num)
{
    var url = BACKENDURL + "user/get_card_payment_history";
    var data = {
        session_id: localStorage["SESSIONID"],
        page_no: page_num
    };
    MakeAjaxCall(url, data, cardPayHistoryDetails);
}

function cardPayHistoryDetails(data)
{
    var tblPupil = "";
    var pgtr_id, refundsign, transcationfee, paymentflag, Statusflag = "";
    if (data.error == 0) {
        $("#frmCardPaymentHistory").css("display", "inline");
        $("#lblCardpayHistory").text("");
        $("#tblCardPaymentHistory tbody tr").remove();
        if (data.history_res.history_records.length > 0) {
            for (var nCount = 0; nCount < data.history_res.history_records.length; nCount++) {
                var freeMeals = (data.history_res.history_records[nCount].fsm == 0) ? "<img src='img/fail.png' style='width:20px;display:block;margin:0 auto;' >" : "<img src='img/pass.png' style='width:20px;display:block;margin:0 auto;'>";
                var adult = (data.history_res.history_records[nCount].adult == 1) ? "<i class='icon-user' ></i>" : "";
                if (data.history_res.history_records[nCount].pay_refund == 1)
                {
                    paymentflag = (data.history_res.history_records[nCount].yp_code == 0) ? "" : "alert-info";
                    refundsign = " ";
                    transcationfee = data.history_res.history_records[nCount].transaction_fee;
                }
                else
                {
                    paymentflag = (data.history_res.history_records[nCount].yp_code == 0) ? "alert-pagination" : "alert-info";
                    refundsign = "-";
                    transcationfee = ((data.history_res.history_records[nCount].transaction_fee == "0.00") && (data.history_res.history_records[nCount].pay_refund == 0)) ? "n/a" : "£" + data.history_res.history_records[nCount].transaction_fee;
                }
                pgtr_id = ((data.history_res.history_records[nCount].pgtr_id == null) || (data.history_res.history_records[nCount].pgtr_id == "")) ? "-" : data.history_res.history_records[nCount].pgtr_id;
                transcationfee = ((data.history_res.history_records[nCount].transaction_fee == "0.00") && (data.history_res.history_records[nCount].pay_refund == 0)) ? "n/a" : "£" + data.history_res.history_records[nCount].transaction_fee;
                tblPupil += "<tr class='" + paymentflag + " " + Statusflag + "'><td data-title='Transaction ID' nowrap='nowrap'><div style= 'padding-top:6px'>" + data.history_res.history_records[nCount].payment_id + "</div></td>";
                tblPupil += "<td data-title='Status'><div style= 'padding-top:6px'>" + data.history_res.history_records[nCount].description + "</div></td>";
                tblPupil += "<td data-title='Payment ID' nowrap='nowrap' style='text-align:center'><div style= 'padding-top:6px'>" + pgtr_id + "</div></td>";
                tblPupil += "<td data-title='Date' nowrap='nowrap'><div style= 'padding-top:6px'>" + data.history_res.history_records[nCount].trans_date + "</div></td>";
                tblPupil += "<td data-title='Pupil Name' nowrap='nowrap'><div style= 'padding-top:6px'>" + data.history_res.history_records[nCount].fname + " " + data.history_res.history_records[nCount].mname + " " + data.history_res.history_records[nCount].lname + "</div></td>";
                tblPupil += "<td data-title='Pupil ID' nowrap='nowrap'><div style= 'padding-top:6px'>" + data.history_res.history_records[nCount].pupil_id + "  " + adult + "</div></td>";
                tblPupil += "<td data-title='Free Meals?'><label style= 'padding-top: 6px;'>" + freeMeals + "</label></td>";
                tblPupil += "<td data-title='School'><div style='padding-top: 6px;'>" + data.history_res.history_records[nCount].school_name + "</div></td>";
                tblPupil += "<td data-title='User Name' nowrap='nowrap'><div style= 'padding-top:6px'>" + data.history_res.history_records[nCount].username + "</div></td>";
                tblPupil += "<td data-title='Amount' nowrap='nowrap'><div style= 'padding-top:6px'>£" + refundsign + data.history_res.history_records[nCount].amount + "</div></td>";
                tblPupil += "<td data-title='Fee' nowrap='nowrap'><div style= 'padding-top:6px'> " + transcationfee + "</div></td>";
                tblPupil += "</tr>";
                $("#tblCardPaymentHistory  tbody:last").append(tblPupil);
                $("#tblCardPaymentHistory").show();
                tblPupil = "";
            }
            if (data.history_res.total_history_records > 0)
            {
                var total_PaymentHistory = data.history_res.total_history_records;
                var total_page = Math.ceil(total_PaymentHistory / 20);
                var no_pages = total_page > 3 ? 3 : 1;
                if (total_PaymentHistory > 20) {
                    var options = {
                        currentPage: 1,
                        alignment: "right",
                        totalPages: total_page,
                        numberOfPages: no_pages, pageUrl: "javascript:void(0)",
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
                            cardPaymentHistory(page);
                        }
                    }
                    $("#PaymentHistory_pag").bootstrapPaginator(options);
                    $("#PaymentHistory_pag").show();
                }
                else
                {
                    $("#PaymentHistory_pag").hide();
                }
            }
            //$("#tablePagination").css({"margin-top": "10px", "margin-bottom": "3px"});
        }
        else
        {
            //$("#PaymentHistory_pag").hide();
            $("#frmCardPaymentHistory").css("display", "none");
            $("#lblCardpayHistory").text("No payment history found");
        }
    }
}

function PaymentCardHistoryNav()
{
    $("#divCardPayConfirmationData").css("display", "none");
    $("#divCardPayConfirmation").css("display", "none");
    $("#divbtncontrolsforCardPay").css("display", "inline");
    $("#tblCardPaymentHistory tbody tr").remove();
    $("#tblCardPayConfirmationData tbody tr").remove();
    $("#spnFinishedPayCard").css({"font-weight": "normal", "color": "#8e8e8e"});
    CardPaymentCancel(false);
    cardPaymentHistory(page_num);
    $('#widgetnav a[href="#cardpaymentHistory"]').tab('show');
    $("#lid_dmPayments").addClass("active");
}

function setCardPayment()
{
    if ($('#divCardPayConfirmationData').css('display') == 'inline') {
        $("#divCardPayConfirmationData").css("display", "none");
        $("#divbtncontrolsforCardPay").css("display", "inline");
        $("#spnFinishedPayCard").css({"font-weight": "normal", "color": "#8e8e8e"});
        $("#tblCardPaymentHistory tbody tr").remove();
        $("#tblCardPayConfirmationData tbody tr").remove();
        CardPaymentCancel(false);
    }
}

function CardPaymentCancel(flag)
{
    $("input[id^='txtCardAllocation_']").each(function() {
        $(this).val("");
    });
    $("#divCardPayConfirmpupils").css("display", "none");
    $("#divCardPayment").css("display", "inline");
    $("#btnPayCardCancel").addClass("hide");
    $("#btnPayCardConfirm").addClass("hide");
    $("#btnPayCardContinue").removeClass("hide");
    $("#txtCardPaymentAmt").val("");
    $("#divProgresBar_Card").css("width", "4%");
    $("#spnConfirmPayCard").css({"font-weight": "normal", "color": "#8e8e8e"});
    $("#spnAssignPayCard").css({"font-weight": "bold", "color": "black"});
    $("#spnAssignPayCard").removeClass('hidden-phone');
    $("#spnConfirmPayCard").addClass('hidden-phone');
    $("#spnFinishedPayCard").addClass('hidden-phone');
    $("#tblCardpaypupilData  tbody tr").remove();
    $("#tblCardPayConfirm  tbody tr").remove();
    jsonCardPayObj = [];
    cardPayArray = [];
    if (flag)
        loadCardPayDetails();
}

/***********************batch order cancellation ******************************/
var action;
var curr;
function loadBOC() {
    var cancelOrderID;
    var emailPID;
    var tblBoc = "";
    $("#tabCancelOrders").bind("click", populateBOCSchools);
    $("#chkShowCleared", "#divCancelOrderstab").change(function() {
        localStorage['Pagination'] = 'CURR1';
        getBOC(page_num);
    })
    $("#ddlCancelOrders", "#divCancelOrderstab").change(function() {
        localStorage['Pagination'] = 'CURR1';
        getBOC(page_num);
    })
}
function populateBOCSchools() {
    localStorage['Pagination'] = 'CURR1';
//first load the school dropdown.
    var url = BACKENDURL + "user/get_schools_admins";
    var data = {
        session_id: localStorage["SESSIONID"],
        contract_id: localStorage.getItem("contractid")
    };
    MakeAjaxCall(url, data, loadBOCSchools);
}

function loadBOCSchools(data) {
    if (data.error == 0) {
        $('#ddlCancelOrders', "#divCancelOrderstab").empty();
        var schoolStr = "";
        if (data.schools_res.length > 0) {
            $("#lblErrBOC", "#divCancelOrderstab").addClass('hide');
            //populating schools
            for (var nCount = 0; nCount < data.schools_res.length; nCount++) {
                var disabled = (data.schools_res[nCount].status == "0") ? " (disabled)" : "";
                schoolStr += "<option value=" + data.schools_res[nCount].school_id + ">" + data.schools_res[nCount].school_name + disabled + "</option>";
            }
            $('#ddlCancelOrders', "#divCancelOrderstab").append(schoolStr);
            getBOC(page_num);
        } else {
            $("#accMainBatchordersCancel", "#divCancelOrderstab").hide();
            $("#divCancelOrdersSchools", "#divCancelOrderstab").hide();
            $("#lblErrBOC", "#divCancelOrderstab").removeClass('hide');
        }

    } else
        alert(data.error_msg)
}

function getBOC(page_num) {
    var clearStatus = (($('#chkShowCleared', "#divCancelOrderstab").is(':checked')) == true) ? 1 : 0;
    $.support.cors = true;
    $.ajax({
        url: BACKENDURL + "user/get_batch_order_cancellation",
        type: "post",
        data: {
            session_id: localStorage["SESSIONID"],
            school_id: $('#ddlCancelOrders', "#divCancelOrderstab").val(),
            clear: clearStatus,
            page: page_num
        },
        dataType: "json",
        crossDomain: true,
        async: false,
        success: function(data) {
            if (data.session_status) {
                getBOCSuccess(data);
            }
        }
    });
}
function getBOCSuccess(data) {
    if (data.error == 0) {
        var clearStatus = (($('#chkShowCleared', "#divCancelOrderstab").is(':checked')) == true) ? 1 : 0;
        $("#tblBoc  tbody:last").empty();
        if (data.batch_res.batch_order_res.length > 0) {
            for (var nCount = 0; nCount < data.batch_res.batch_order_res.length; nCount++) {
                var actionStatus = " ", emailStatus = "", cancelStatus = "";
                var tblBoc = "";
                var actionStatus = (data.batch_res.batch_order_res[nCount].future > 0) ? " " : "disabled";
                if (actionStatus == " ")
                    emailStatus = (data.batch_res.batch_order_res[nCount].email_status == 0) ? " " : "disabled";
                cancelStatus = (data.batch_res.batch_order_res[nCount].cancel_status == 0) ? " " : "disabled";
                var emailCkd = (data.batch_res.batch_order_res[nCount].email_status == 0) ? " " : "icon-ok";
                var cancelCkd = (data.batch_res.batch_order_res[nCount].cancel_status == 0) ? " " : "icon-ok";

                var clearLink = (clearStatus == 0) ? "Clear" : "Unclear";
                var clearFlag = (clearLink == "Clear") ? 1 : 0;
                var sysMsg = data.batch_res.batch_order_res[nCount].system_msg;
                var usrMsg = (data.batch_res.batch_order_res[nCount].user_msg == null) ? "-" : data.batch_res.batch_order_res[nCount].user_msg;
                var batch_cancel_id = data.batch_res.batch_order_res[nCount].batch_cancel_id;

                tblBoc += "<tr><td  data-title='System Message'><span>" + data.batch_res.batch_order_res[nCount].system_msg + "</span></td>";
                tblBoc += "<td data- title='User Message'><span>" + usrMsg + "</span></td>";
                tblBoc += "<td data- title='Impacted Orders (past)'>" + data.batch_res.batch_order_res[nCount].past + "</td>";
                tblBoc += "<td data- title='Impacted Orders (today & future)'>" + data.batch_res.batch_order_res[nCount].future + "</td>";
                tblBoc += "<td class='alignright' ><div class='btn-group'> <button type='button' class='btn btn-default dropdown-toggle' data-toggle='dropdown'> Action <span class='caret'></span></button><ul class='dropdown-menu' role='menu'><li><a href='javascript:void(0);' onClick='exportOrders(" + data.batch_res.batch_order_res[nCount].batch_cancel_id + ");'>Export Orders</a></li>";
                tblBoc += "<li id='status" + nCount + "' class=" + actionStatus + emailStatus + "> <a href='javascript:void(0);' id='lnkemailParents" + nCount + "' ><i class=" + emailCkd + "></i> Email Parents</a></li>";
                tblBoc += "<li><a href='javascript:void(0);' id='lnkUpdateMessage" + nCount + "'>Update Message</a></li>";
                tblBoc += "<li class=" + cancelStatus + " id='listlnkCancelOrder" + nCount + "' > <a href='javascript:void(0);' id='lnkCancelOrder" + nCount + "'  onClick ='cancelOrder(" + data.batch_res.batch_order_res[nCount].batch_cancel_id + ")'><i class=" + cancelCkd + "></i> Cancel Orders</a></li>";
                tblBoc += "<li class='divider'></li>";
                tblBoc += "<li><a  href='javascript:void(0);' onclick='clearOrders(" + data.batch_res.batch_order_res[nCount].batch_cancel_id + "," + clearFlag + ")'>" + clearLink + "</a></li></ul></div></td></tr>";

                $("#tblBoc tbody:last").append(tblBoc);
                $("#lnkUpdateMessage" + nCount, "#tblBoc").bind("click", {cancel_id: batch_cancel_id, sysMsg: sysMsg, usrMsg: usrMsg}, function(event) {
                    updateMsg(event.data.cancel_id, event.data.sysMsg, event.data.usrMsg);
                });
                $("#lnkemailParents" + nCount, "#tblBoc").bind("click", {cancel_id: batch_cancel_id, sysMsg: sysMsg, usrMsg: usrMsg, emailStatus: data.batch_res.batch_order_res[nCount].email_status}, function(event) {
                    EmailP(event.data.cancel_id, event.data.sysMsg, event.data.usrMsg, event.data.emailStatus, event.target.id);
                });
            }
            $("#tblBoc").show();
            $("#divErrRecords").hide();
            $("#divBOCH").show();
            if (data.batch_res.count > 0) {
                var totalRecords = data.batch_res.count;
                var total_page = Math.ceil(totalRecords / 10);
                var no_pages = total_page > 2 ? 2 : 1;
                if (totalRecords > 10) {
                    var options = {
                        //currentPage:1,
                        alignment: "right",
                        totalPages: total_page,
                        numberOfPages: no_pages, pageUrl: "javascript:void(0)",
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
                                    options.currentPage = page;
                                    return page;
                            }
                        },
                        onPageClicked: function(e, originalEvent, type, page) {
                            localStorage['Pagination'] = 'NTCURR1';
                            curr = page;
                            getBOC(page);
                        }
                    }

                    if ((localStorage['Pagination'] == 'CURR1') || (action == "clear") || (action == "cancel")) {
                        options.currentPage = 1;
                    }
                    $("#divBOC_pag").bootstrapPaginator(options);
                    $("#divBOC_pag").show();
                    action = " ";
                }
                else
                {
                    $("#divBOC_pag").hide();
                }
            }
        } else {
            $("#tblBoc").hide();
            $("#divErrRecords").show();
            $("#divBOC_pag").hide();
            $("#divBOCH").hide();
        }
    }
}
function cancelOrder(cancelID) {
    $("#divCancelOrders").modal('show');
    var url = BACKENDURL + "user/batch_cancel_order_items";
    var data = {
        session_id: localStorage["SESSIONID"],
        school_id: $('#ddlCancelOrders', "#divCancelOrderstab").val(),
        batch_cancel_id: cancelID
    }
    $("#orderOkBtn").off('click').on('click', function() {
        MakeAjaxCall(url, data, cancelOrderSuccess);

    })
}
function cancelOrderSuccess(data) {
    if (data.error == 0) {
        action = "cancel";
        $("#divCancelOrders").modal('hide');
        getBOC(page_num);
    } else if (data.error_msg == "Unauthorized access.") {
        logout(1);
    } else
        $("#divBOCErr").html(data.error_msg).removeClass('hide')
}
function EmailP(cancelID, sysMsg, usrMsg, emailstatus, e) {
    var ddlSchoolVal = $("#ddlCancelOrders").val();
    var schoolName = $("#ddlCancelOrders option[value='" + ddlSchoolVal + "']").text();
    var statusID = e.replace("lnkemailParents", "status");
    if ($("#" + statusID).hasClass('disabled'))
        $("#divEmailParents").modal('hide');
    else {
        $("#divEmailParents").modal('show');
        if (usrMsg == '-')
            $("#txaEmailMsg", "#divEmailParents").val("\nThe meal order has been updated due to the reason:" + sysMsg + ".\n\nPlease review these orders online. Do not respond to this email address because the inbox is not monitored.\n\nAny orders that are cancelled will have the value refunded to the account balance.\n\nRegards,\n\nThe Eden Team");
        else
            $("#txaEmailMsg", "#divEmailParents").val("\nThe meal order has been updated due to the reason:" + usrMsg + ".\n\nPlease review these orders online. Do not respond to this email address because the inbox is not monitored.\n\nAny orders that are cancelled will have the value refunded to the account balance.\n\nRegards,\n\nThe Eden Team")
    }
    $("#btnEmailParentsSubmit").off('click').on('click', function() {
        $("#divEmailParents").modal('hide');
        var url = BACKENDURL + "user/batch_email_parents";
        var data = {
            session_id: localStorage["SESSIONID"],
            email_from: localStorage["EMAIL"],
            email_sub: "Meal orders for " + schoolName,
            email_msg: $("textarea#txaEmailMsg").val(),
            batch_cancel_id: cancelID
        };
        MakeAjaxCall(url, data, SubmitEmailParentSuccess);
    });
}

function SubmitEmailParentSuccess(data) {
    if (data.error == 0) {
        if (curr == undefined)
            getBOC(page_num);
        else
            getBOC(curr);
    } else if (data.error_msg == "Unauthorized access.") {
        logout(1);
    } else
        $("#divBOCErr").html(data.error_msg).removeClass('hide')
}
function exportOrders(cancelID) {
    $("#spnGenExportOrders" + cancelID).removeClass('hide');
    $.ajax({
        url: BACKENDURL + "data_upload/export_data",
        type: "post",
        data: {
            session_id: localStorage["SESSIONID"],
            export_type: "batch_order_items",
            batch_cancel_id: cancelID
        },
        dataType: "json",
        async: false,
        crossDomain: true,
        success: function(data) {
            if (data.session_status) {
                if (data.error == 0) {
                    $("#spnGenExportOrders" + cancelID).addClass('hide');
                    var url = BACKENDURL + "common/download_file";
                    window.open(url + "/" + localStorage["SESSIONID"] + "/batch_order_items/" + data.temp_file);
                } else if (data.error_msg == "Unauthorized access.") {
                    logout(1);
                } else
                    $("#divBOCErr").html(data.error_msg).removeClass('hide');
            }
            else {
                logout();
            }
        },
        error: function(xhr, textStatus, error) {
            alert(error);
        }
    });
}
function clearOrders(cancelID, clearFlag) {
    var url = BACKENDURL + "user/update_batch_order_clear_flag";
    var data = {
        session_id: localStorage["SESSIONID"],
        batch_cancel_id: cancelID,
        clear: clearFlag
    };
    MakeAjaxCall(url, data, clearOrderSuccess);
}
function clearOrderSuccess(data) {
    if (data.error == 0) {
        action = "clear";
        getBOC(page_num);
    } else if (data.error_msg == "Unauthorized access.") {
        logout(1);
    } else
        $("#divBOCErr").html(data.error_msg).removeClass('hide')
}
function updateMsg(cancelID, sysMsg, usrMsg) {
    if (usrMsg == '-')
        $("#txaComments", "#divUpdateMessage").val(sysMsg);
    else
        $("#txaComments", "#divUpdateMessage").val(usrMsg);

    $("#divUpdateMessage").modal("show");

    $("#btnUpdateSubmit").off('click').on('click', function() {
        var updatedMsg = $("#txaComments", "#divUpdateMessage").val();
        var url = BACKENDURL + "user/update_batch_order_user_msg";
        var data = {
            session_id: localStorage["SESSIONID"],
            batch_cancel_id: cancelID,
            user_msg: updatedMsg
        };
        MakeAjaxCall(url, data, updateSubmitSuccess);
    })

}
function updateSubmitSuccess(data) {
    if (data.error == 0) {
        $("#divUpdateMessage").modal('hide');
        if (curr == undefined)
            getBOC(page_num);
        else
            getBOC(curr);
    } else if (data.error_msg == "Unauthorized access.") {
        logout(1);
    } else
        $("#divBOCErr").html(data.error_msg).removeClass('hide');
}

function LoadHospOrder() {
    var winW = $(window).width();
    if (winW < '768') {
        $("#btngroupNHO_NPMDO").addClass('btn-group-vertical');
    }
    else {
        $("#btngroupNHO_NPMDO").removeClass('btn-group-vertical');
    }

    $("#btnNHO").bind("click", load_NewHospOrders);
    $("#btnPlaceOrder").bind("click", Srv_SaveIVords);
    $("#btnCloseNewHospOrder").unbind("click").bind("click", closeHospOrder);
    //$("#btnOrderEdit").unbind("click").bind("click", SrvNewHospOrder);
    //$("#btnOrderVFO").unbind("click").bind("click", SrvNewHospOrder);
    $("#tabMyInvOrders").bind("click", Srv_InvoiceOrds);
    $("#btnCloseNewMealDebt").bind("click", closeMealDebtOrder);
    $("#btnPrintDebtOrder").unbind("click").bind("click", printHospOrder);
    $("#hoverInvoice").tooltip({
        'selector': '',
        'placement': 'right',
        'width': '400px'
    });
    $("#hoverAmt").tooltip({
        'selector': '',
        'placement': 'right',
        'width': '400px'
    });
    $("#hoverVat").tooltip({
        'selector': '',
        'placement': 'right',
        'width': '400px'
    });
}
function printHospOrder() {
    window.print();
}
function closeHospOrder() {
    $("#frmNewHospOrder").data('validator').resetForm();
    $(".error").removeClass("error");
    $("#InvOrds_pag").hide();
    $("#frmInvoiceOrder").removeClass('hide');
    $("#divInvoiceOrders").removeClass('hide');
    $("#tblInvoiceOrders").removeClass('hide');
    $("#divNewHospOrder").addClass('hide');
    $("#txtNHOReference").val("");
    $("#txtinvAmt").val("");
    $("#lbl_stsIVords").hide();
    $("#txtNHOReference", "#divNewHospOrder").removeAttr("disabled", "disabled");
    $("#txtinvAmt", "#divNewHospOrder").removeAttr("disabled", "disabled");
    $("#btnPlaceOrder").show();
    //Srv_InvoiceOrds();	
    InvoiceHistory();
}
function Srv_InvoiceOrds() {
    //first load the school dropdown.

    $("#txthdnPgid").val("1");
    var todayDate = new Date();
    var IV_day = todayDate.getDate() + 1;
    var IV_month = todayDate.getMonth() + 1;
    var IV_year = todayDate.getFullYear();
    $("#dp_EventDate").val(IV_day + "/" + IV_month + "/" + IV_year);
    $("#div_EventDate").datepicker({startDate: todayDate}).on(
            "changeDate", function() {
        $("#div_EventDate").parent().hasClass("control-group error")
        $("#div_EventDate").parents('.control-group').removeClass('error');
        $("#btnPlaceOrder").removeAttr("disabled", "disabled");
        $(".datepicker").hide();
    });

    $("#frmNewHospOrder").validate({
        rules: {
            txtNHOReference: {required: true},
            txtinvAmt: {required: true}
        },
        messages: {
            txtNHOReference: {required: "Please enter the Reference details"},
            txtinvAmt: {required: "Please enter the Amount value"}
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
            if (element.parent().hasClass("input-append") || element.parent().hasClass("input-prepend")) {
                error.insertAfter(element.parent());
            } else {
                error.insertAfter(element);
            }
        }
    });
    $("#txtinvAmt").keypress(function(e) {
        if (e.which != 8 && e.which != 0 && e.which != 46 && (e.which < 48 || e.which > 57)) {
            return false;
        }
    });
    $('#txtinvAmt').keyup(function(e) {
        if (e.which != 8 && e.which != 0 && e.which != 46 && (e.which < 48 || e.which > 57)) {
            return false;
        }
        else
        {
            var vat_fee = (parseFloat($("#txtinvAmt").val()) * parseFloat(localStorage["vat"])) / 100;
            $("#txtinvVat").val(vat_fee);
            var trans_fee = parseFloat($("#txtinvAmt").val()) + parseFloat($("#txtinvVat").val());
            $("#txtinvTot").val(trans_fee);
            if ($("#txthdnIVid").val() == "")
                $("#btnPlaceOrder").html("Place Order").removeAttr("disabled", "disabled");
            else
                $("#btnPlaceOrder").html("Update Order").removeAttr("disabled", "disabled");
        }
    });
    $('textarea[id="txtNHOReference"]').bind("keyup", function() {
        if ($("#txthdnIVid").val() == "")
            $("#btnPlaceOrder").html("Place Order").removeAttr("disabled", "disabled");
        else
            $("#btnPlaceOrder").html("Update Order").removeAttr("disabled", "disabled");
    });
    var url = BACKENDURL + "user/get_schools_admins";
    var data = {
        session_id: localStorage["SESSIONID"],
        contract_id: localStorage.getItem("contractid")
    };
    MakeAjaxCall(url, data, loadInvoiceOrds);
}


function loadInvoiceOrds(data) {
    if (data.error == 0) {
        $("#divErrInvoice", "#divInvoice").hide();
        $('#ddlHO', "#divInvoice").empty();
        $("#ddlIVSchools", "#divNewHospOrder").empty();
        $("#ddlMealDebtSchools", "#divMealDebtSchools").empty();
        var schoolStr = "";
        if (data.schools_res.length > 0) {
            //  $("#lblErrBOC", "#divCancelOrderstab").addClass('hide');
            //populating schools
            for (var nCount = 0; nCount < data.schools_res.length; nCount++) {
                var disabled = (data.schools_res[nCount].status == "0") ? " (disabled)" : "";
                schoolStr += "<option value=" + data.schools_res[nCount].school_id + ">" + data.schools_res[nCount].school_name + disabled + "</option>";
            }
            $('#ddlHO', "#divInvoice").append(schoolStr);			
            $('#ddlIVSchools', "#divNewHospOrder").append(schoolStr);
            $("#ddlMealDebtSchools", "#divMealDebtSchools").append(schoolStr);
            if (data.schools_res.length == 1) {
                $("#lblManageInv").hide();
                $(".select-wrap", "#frmInvoiceOrder").hide()
            }
            InvoiceHistory();
        } else {
            $("#divErrInvoice", "#divInvoice").show();
            $("#frmInvoiceOrder", "#divInvoice").hide();
            $("#divInvoiceOrders", "#divInvoice").hide();
        }
    }
}
function InvoiceHistory() {
    $("#lbl_stsIVords").hide();
    var url = BACKENDURL + "user/get_invoice_orders";
    var data = {
        session_id: localStorage["SESSIONID"],
        school_id: $('#ddlHO', "#divInvoice").val(),
        page_no: $("#txthdnPgid").val()
    };
    MakeAjaxCall(url, data, loadInvoiceHistory);
}
function load_NewHospOrders()
{
    $("#divErrInvoice", "#divInvoice").hide();
    $("#ddlIVSchools").val($("#ddlHO").val());
    $("#txthdnIVid").val("");
    $("#frmInvoiceOrder").addClass('hide');
    $("#divInvoiceOrders").addClass('hide');
    $("#tblInvoiceOrders").addClass('hide');
    $("#divNewHospOrder").removeClass('hide');
    $("#btnPlaceOrder").html("Place Order");
    $("#frmNewHospOrder").data('validator').resetForm();
    $(".error").removeClass("error");
    $("#InvOrds_pag").hide();
    $("#lbl_stsIVords").hide();
    $("#txtinvVat", "#divNewHospOrder").val("0");
    $("#txtinvTot", "#divNewHospOrder").val("0");
    var todayDate = new Date();
    var IV_day = todayDate.getDate();
    var IV_month = todayDate.getMonth() + 1;
    var IV_year = todayDate.getFullYear();
    $("#dp_EventDate").val(IV_day + "/" + IV_month + "/" + IV_year);
    $('#div_EventDate').data({
        date: IV_day + "/" + IV_month + "/" + IV_year
    }).datepicker('update');
    $("#txthdnPgid").val("1");
}

function Srv_SaveIVords()
{
    if ($("#frmNewHospOrder").valid())
    {
        if ($("#txthdnIVid").val() == "")
            $("#btnPlaceOrder").html("Placing Order").attr("disabled", "disabled");
        else
            $("#btnPlaceOrder").html("Updating Order").attr("disabled", "disabled");
        var dpdate_ar = $("#dp_EventDate").val().split("/");
        var url = BACKENDURL + "user/save_inv_order_details";
        var data = {
            session_id: localStorage["SESSIONID"],
            invid: $("#txthdnIVid").val(),
            school_id: $("#ddlIVSchools").val(),
            event_date: dpdate_ar[2] + "-" + dpdate_ar[1] + "-" + dpdate_ar[0],
            d: $("#txtNHOReference").val(),
            amt: $("#txtinvAmt").val()
        };
        MakeAjaxCall(url, data, SaveIVords);
    }
}

function SaveIVords(data)
{
    if (data.error)
    {
        $("#btnPlaceOrder").html("Place Order").removeattr("disabled", "disabled");
    }
    else
    {
        $("#txthdnPgid").val("1");
        if ($("#txthdnIVid").val() == "")
        {		
			$("#ddlHO").val($("#ddlIVSchools").val());
            $("#btnCloseNewHospOrder").click();
            $("#lbl_stsIVords").html("Thank you, your order has been succesfully created.").show();
            $("#btnPlaceOrder").html("Placed Order").attr("disabled", "disabled");
				
        }
        else
        {			
            $("#btnCloseNewHospOrder").click();
            $("#lbl_stsIVords").html("Thank you, your order has been succesfully updated.").show();
            $("#btnPlaceOrder").html("Updated Order").attr("disabled", "disabled");
        }
    }
}

function loadInvoiceHistory(data) {
    $("#divErrInvoice", "#divInvoice").hide();
    if (data.error == 0) {
        var tblInvOrder = "";
        var is_nonedit = "2";	
        $("#tblInvoiceOrders", "#divInvoice").hide();
        $("#tblInvoiceOrders  tbody:last").empty();
        if (data.ino.hosp_rows.length > 0) {
            for (var nCount = 0; nCount < data.ino.hosp_rows.length; nCount++) {
                var cancelStatus = (data.ino.hosp_rows[nCount].status == 1) ? "alert-pagination" : "";
                var cancelDisable = (data.ino.hosp_rows[nCount].status == 1) ? "disabled" : "";
                var view = (data.ino.hosp_rows[nCount].is_edit == 0) ? "hide" : " ";
                var edit = (data.ino.hosp_rows[nCount].is_edit == 1) ? "hide" : " ";
                tblInvOrder += "<tr class='" + cancelStatus + "'><td data-title='Order ID' nowrap='nowrap'><div style='padding-top:6px'>" + data.ino.hosp_rows[nCount].oid + "</div></td>";
                tblInvOrder += "<td data-title='Last Updated On' nowrap='nowrap'><div style='padding-top:6px'>" + data.ino.hosp_rows[nCount].lmdate + "</div></td>";
                tblInvOrder += "<td data-title='Last Updated By'><div style='padding-top:6px'>" + data.ino.hosp_rows[nCount].lmuser + "</div></td>";
                tblInvOrder += "<td data-title='Invoice Date'><div style='padding-top: 6px;'>" + data.ino.hosp_rows[nCount].odate + "</div></td>";
                tblInvOrder += "<td data-title='Total'><div style='padding-top: 6px;'>" + data.ino.hosp_rows[nCount].oc + "</div></td>";
                tblInvOrder += "<td data-title='Reference'><div style='padding-top: 6px;'>" + (data.ino.hosp_rows[nCount].d).substr(0, 50) + "</div></td>";
                tblInvOrder += "<td nowrap='wrap' style='text-align: left;'><button class='btn btn-small " + edit + " " + cancelDisable + "' id='btnOrderEdit' onclick='javascript:return edit_order(" + data.ino.hosp_rows[nCount].invid + "," + data.ino.hosp_rows[nCount].status + "," + data.ino.hosp_rows[nCount].otypeid + ")' style='margin-right:10px;'><i class='icon-pencil'></i> Edit</button><button class='btn btn-danger btn-small " + edit + " " + cancelDisable + "' id='btnOrderCancel' onclick='javascript:return cancelH_Order(" + data.ino.hosp_rows[nCount].invid + "," + data.ino.hosp_rows[nCount].status + ")'><i class='icon-white icon-trash'></i> Cancel</button><button class='btn btn-success btn-small " + view + "'  onclick='javascript:return edit_order(" + data.ino.hosp_rows[nCount].invid + "," + is_nonedit + "," + data.ino.hosp_rows[nCount].otypeid + ")' style='margin-right:10px;width:145px!important;'> View fulfilled order</button></td></tr>";
            }
            if (data.ino.tot_cnt > 10) {
                var total_InvOrds = data.ino.tot_cnt;
                var total_page = Math.ceil(total_InvOrds / 10);
                var no_pages = total_page > 3 ? 3 : 1;
                if (total_InvOrds > 10) {
                    var options = {
                        currentPage: $("#txthdnPgid").val(),
                        alignment: "right",
                        totalPages: total_page,
                        numberOfPages: no_pages,
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
                                    options.currentPage = page;
                                    return page;
                            }
                        },
                        onPageClicked: function(e, originalEvent, type, page) {
                            $("#txthdnPgid").val(page);
                            InvoiceHistory();
                        }
                    }
                    $("#InvOrds_pag").bootstrapPaginator(options);
                    $("#InvOrds_pag").css('cursor', 'pointer');
                    $("#InvOrds_pag").show();
                }
                else
                {
                    $("#InvOrds_pag").hide();
                }
            }
            else
            {
                $("#InvOrds_pag").hide();
            }
            $("#tblInvoiceOrders tbody:last").append(tblInvOrder);
            $("#tblInvoiceOrders", "#divInvoice").show();
        }
        else {
            $("#tblInvoiceOrders", "#divInvoice").hide();
            $("#divErrInvoice", "#divInvoice").html("No records found").show();
        }


    } else if (data.error_msg == "Unauthorized access.") {
        logout(1);
    } else
        $("#divErrInvoice", "#divInvoice").html(data.error_msg).show();
}
function cancelH_Order(id, status) {
    if (status == "0")
    {
        bootbox.dialog({
            message: "Are you sure you want to cancel this order?",
            title: "Cancel order?",
            buttons: {
                danger: {
                    label: "Close",
                    className: "btn",
                    callback: function() {
                    }
                },
                success: {
                    label: "Submit",
                    className: "btn btn-primary",
                    callback: function() {
                        //add service
                        var url = BACKENDURL + "user/cancel_inv_order_details";
                        var data = {
                            session_id: localStorage["SESSIONID"],
                            invid: id
                        };
                        MakeAjaxCall(url, data, CancelInv_order);
                    }
                }
            }
        });
    }
}

function CancelInv_order(data)
{
    $("#txthdnPgid").val("1");
    InvoiceHistory();
    setTimeout(function() {
        $("#lbl_stsIVords").html("Thank you, Your order succesfully cancelled.").show();
    }, 500);
}

function edit_order(id, status, view)
{
    if (status == "0" || status == "2")
    {
        tmppplIO = [];
        chkPupilIO.length = 0;
        localStorage["delfn"] = "0";
        $("#lbl_stsIVords").hide();
        $("#InvOrds_pag").hide();
        $("#txthdnIVid").val(id);
        $("#frmInvoiceOrder").addClass('hide');
        $("#divInvoiceOrders").addClass('hide');
        $("#tblInvoiceOrders").addClass('hide');
        if (view == 0) {
            $("#divNewHospOrder").removeClass('hide');
            var url = BACKENDURL + "user/get_inv_order_details";
            var data = {
                session_id: localStorage["SESSIONID"],
                invid: id,
            };
            MakeAjaxCall(url, data, editIVords);
            if (status == "2")
            {
                $("#txtNHOReference", "#divNewHospOrder").attr("disabled", "disabled");
                $("#txtinvAmt", "#divNewHospOrder").attr("disabled", "disabled");
                $("#btnPlaceOrder").hide();

            }
        } else
        {
            $("#divNewPupilMealDebtOrder").removeClass('hide');
            $("#btnPrintDebtOrder").show();
            var url = BACKENDURL + "user/get_pupil_inv_order_details";
            var data = {
                session_id: localStorage["SESSIONID"],
                invid: id,
            };
            MakeAjaxCall(url, data, editMDOrder);
            if (status == "2")
            {
                $("#btnPlaceNewMealDebtOdr", "#divNewPupilMealDebtOrder").addClass('hide');
                $("#btnAddPupilPay_IO", "#divNewPupilMealDebtOrder").addClass('disabled');
				$("#btnAddPupilPay_IO", "#divNewPupilMealDebtOrder").hide();
				$("#btnAddPupilPayView_IO", "#divNewPupilMealDebtOrder").show();				
                $("#divPupilNtAddedErr").addClass('hide');
                localStorage["delfn"] = "1";

            } else
            {
                $("#btnPlaceNewMealDebtOdr", "#divNewPupilMealDebtOrder").removeClass('hide');
                $("#btnAddPupilPay_IO", "#divNewPupilMealDebtOrder").removeClass('disabled');
				$("#btnAddPupilPay_IO", "#divNewPupilMealDebtOrder").show();
				$("#btnAddPupilPayView_IO", "#divNewPupilMealDebtOrder").hide();
                $("#divPupilNtAddedErr").addClass('hide');
            }
        }
        $("#btnPlaceOrder").html("Update Order");
    }
}

function editIVords(data)
{
    if (data.error == 0) {
        $("#dp_EventDate").val(data.ino.hosp_res[0].odate);
        $('#div_EventDate').data({
            date: data.ino.hosp_res[0].odate
        }).datepicker('update');
        $("#ddlIVSchools", "#divNewHospOrder").val(data.ino.hosp_res[0].sid);
        $("#txtNHOReference", "#divNewHospOrder").val(data.ino.hosp_res[0].d);
        $("#txtinvAmt", "#divNewHospOrder").val(data.ino.hosp_res[0].oc);
        $("#txtinvVat", "#divNewHospOrder").val(data.ino.hosp_res[0].ov);
        $("#txtinvTot", "#divNewHospOrder").val(parseFloat(data.ino.hosp_res[0].ov) + parseFloat(data.ino.hosp_res[0].oc));
    }
}

function editMDOrder(data)
{
    if (data.error == 0) {
        $("#btnPlaceNewMealDebtOdr").html("Update Order").attr("disabled", "disabled");
        $("#dp_InvoiceDate").val(data.ino.hosp_res[0].odate);
        $('#div_InvoiceDate').data({
            date: data.ino.hosp_res[0].odate
        }).datepicker('update');
        $("#ddlMealDebtSchools", "#divNewPupilMealDebtOrder").val(data.ino.hosp_res[0].sid).attr("disabled", "disabled");
        $("#txtMealDebtAmt", "#divNewPupilMealDebtOrder").val(parseFloat(data.ino.hosp_res[0].oc));
        $("#txtMealDebtVat", "#divNewPupilMealDebtOrder").val(parseFloat(data.ino.hosp_res[0].ov));
        $("#txtMealDebT", "#divNewPupilMealDebtOrder").val(parseFloat(data.ino.hosp_res[0].ov) + parseFloat(data.ino.hosp_res[0].oc));
        loadpupils_IO(data.ino.p);
    }
}

// Pupil Meal Debit Order

function newPupilMealDebtOrder() {
    localStorage["delfn"] = "0";
	$("#btnAddPupilPayView_IO").hide();
	$("#btnAddPupilPay_IO").show();
    $("#btnPlaceNewMealDebtOdr").attr("disabled", "disabled");
    $("#ddlMealDebtSchools").val($("#ddlHO").val());
    $("#frmInvoiceOrder").addClass('hide');
    $("#divInvoiceOrders").addClass('hide');
    $("#tblInvoiceOrders").addClass('hide');
    $("#divNewPupilMealDebtOrder").removeClass('hide');
    $("#lbl_stsIVords").hide();
    $("#btnPlaceNewMealDebtOdr").html("Place Order");
    $("#btnPlaceNewMealDebtOdr").hasClass("hide")
    $("#btnPlaceNewMealDebtOdr").removeClass("hide");
    $("#txthdnIVid").val("");
    $("#divErrInvoice", "#divInvoice").hide();
    $("#btnPrintDebtOrder").hide();
    $("#divPupilNtAddedErr").addClass('hide');
    $("#ddlMealDebtSchools").removeAttr("disabled", "disabled");
    tmppplIO = [];
    chkPupilIO.length = 0;
    var todayDate = new Date();
    var IV_day = todayDate.getDate();
    var IV_month = todayDate.getMonth() + 1;
    var IV_year = todayDate.getFullYear();
    $("#dp_InvoiceDate").val(IV_day + "/" + IV_month + "/" + IV_year);
    $('#div_InvoiceDate').data({
        date: IV_day + "/" + IV_month + "/" + IV_year
    }).datepicker('update');
    $("#div_InvoiceDate").datepicker().on(
            "changeDate", function() {
        $("#div_InvoiceDate").parent().hasClass("control-group error")
        $("#div_InvoiceDate").parents('.control-group').removeClass('error');
        $("#btnPlaceOrder").removeAttr("disabled", "disabled");
        $(".datepicker").hide();
        $("#btnPlaceNewMealDebtOdr").removeAttr("disabled", "disabled");
    });
    //$("#btnPlaceNewMealDebtOdr").bind("click", srv_savePupilMealOrd);
    /* if ($("#btnPlaceNewMealDebtOdr", "#divNewPupilMealDebtOrder").hasClass('disabled')) {
     $("#btnPlaceNewMealDebtOdr", "#divNewPupilMealDebtOrder").removeClass('disabled')
     } */
    if ($("#btnAddPupilPay_IO", "#divNewPupilMealDebtOrder").hasClass('disabled')) {
        $("#btnAddPupilPay_IO", "#divNewPupilMealDebtOrder").removeClass('disabled');
    }
    if ($("#btnPlaceNewMealDebtOdr", "#divNewPupilMealDebtOrder").hasClass('hide')) {
        $("#btnPlaceNewMealDebtOdr", "#divNewPupilMealDebtOrder").removeClass('disabled');
    }

}
function chng_MDO() {
    $('#divAddPupilInvoice').empty();
    chkPupilIO.length = 0;
    $("#btnPlaceNewMealDebtOdr", "#divNewPupilMealDebtOrder").removeAttr("disabled", "disabled");
    $("#txtMealDebtAmt", "#divNewPupilMealDebtOrder").val("");
    $("#txtMealDebtVat", "#divNewPupilMealDebtOrder").val("");
    $("#txtMealDebT", "#divNewPupilMealDebtOrder").val("");

}
function closeMealDebtOrder() {
    $("#frmInvoiceOrder").removeClass('hide');
    $("#divInvoiceOrders").removeClass('hide');
    $("#tblInvoiceOrders").removeClass('hide');
    $("#divNewPupilMealDebtOrder").addClass('hide');
    $('#divAddPupilInvoice').empty();
    $("#txtMealDebtAmt").val("");
    $("#txtMealDebtVat").val("");
    $("#txtMealDebT").val("");
    InvoiceHistory();
}

$("#btnPupilSearch_IO").click(function()
{
    var pup_ass = [];
    var pupilId = $("#txtIOPupilId", "#divPPl_IO").val();
    var Fname = $("#txtIOFname", "#divPPl_IO").val();
    var Mname = $("#txtIOMname", "#divPPl_IO").val();
    var Lname = $("#txtIOLname", "#divPPl_IO").val();
    if ((pupilId == "") && (Fname == "") && (Mname == "") && (Lname == ""))
    {
        $("#lblppl_IOerr").text("Please enter Pupil ID or Name").removeClass("hide");
        return false;
    }
    if (!$("#lblppl_IOerr").hasClass("hide"))
        $("#lblppl_IOerr").addClass("hide");
    var url = BACKENDURL + "user/search_pupil_debt_order";
    var data = {
        session_id: localStorage["SESSIONID"],
        school_id: $("#ddlMealDebtSchools").val(),
        pupil_id: pupilId,
        fname: Fname,
        mname: Mname,
        lname: Lname,
        pupils: pup_ass
    };
    MakeAjaxCall(url, data, userSearchIO);
});

function userSearchIO(data)
{
    var tblPupil = "";
    if (data.error == 0) {
        var nCurrRecRound = 0;
        $("#tblPupilSearchIO  tbody:last").empty();
        var hdnCurrPage = $("#currPageNumber", "#tablePagination").val();
        if (hdnCurrPage != undefined) {
            nCurrRecRound = hdnCurrPage - 1;
        }
        $("#tablePagination").remove();
        tmppplIO = JSON.stringify(data.pup.p);
        var tt_pag = 0;
        for (var nCount = 0; nCount < data.pup.p.length; nCount++) {
            var pupilId = data.pup.p[nCount].pupil_id;
            localStorage["UMC"] = data.pup.cs[0].mc;
            if ($.inArray(pupilId, chkPupilIO) == -1)
            {
                tt_pag++;
                var studentId = data.pup.p[nCount].students_id;
                var freeMeals = (data.pup.p[nCount].fsm == 0) ? "<img src='img/fail.png' style='width:20px;display:block;margin:0 auto;' >" : "<img src='img/pass.png' style='width:20px;display:block;margin:0 auto;'>";
                var adult = (data.pup.p[nCount].adult == 1) ? "<i class='icon-user' ></i>" : "";
                tblPupil += "<tr><td><div style= 'padding-top:6px' id='pupilName" + studentId + "'>" + data.pup.p[nCount].fname + " " + data.pup.p[nCount].mname + " " + data.pup.p[nCount].lname + "</div></td>";
                tblPupil += "<td nowrap='nowrap'><div style= 'padding-top:6px' id='pupilid" + studentId + "'>" + data.pup.p[nCount].pupil_id + "  " + adult + "</div></td>";
                tblPupil += "<td ><label style= 'padding-top: 6px;' id='freemeals" + studentId + "'>" + freeMeals + "</label></td>";
                tblPupil += "<td><div style='padding-top: 6px;' id='SchoolNameLabel" + studentId + "'>" + data.pup.p[nCount].school_name + "</div></td>";
                //tblPupil += "<td><div style='text-align: center;padding-top: 6px;'id='YearLabel" + studentId + "'>" + data.search_pupils_res[nCount].year_label + "</div></td>";
                //tblPupil += "<td><div style='text-align: center;padding-top: 6px;'id='ClassLabel" + studentId + "'>" + data.search_pupils_res[nCount].class_name + "</div></td>";
                tblPupil += "<td><label style='display:inline;'><input type='checkbox' id='chkSelect_" + pupilId + "' name='chkSelect_" + pupilId + "' style='display:inline;margin-top:0px;' ><span style='vertical-align:middle;display:inline;'> Select</span></label></td></tr>";
                $("#tblPupilSearchIO  tbody:last").append(tblPupil);
                tblPupil = "";
            }
        }
        if ($("#tblPupilSearchIO > tbody > tr").length > 0) {
            $("#divPPl_IO").css('display', 'none');
            $("#tblPupilSearchIO").show();
            if (tt_pag > 4) {
                $("#tablePagination").html('');
                $("#AddPPl_IOpag").tablePagination({
                    rowsPerPage: 3,
                    currPage: nCurrRecRound + 1
                });
            }
            $("input[type=checkbox]", "#tblPupilSearchIO").click(function()
            {
                var tmpPupilId = $(this).attr('id').slice(10);

                if ($.inArray(tmpPupilId, chkPupilIO) > -1)
                    chkPupilIO.pop(tmpPupilId);
                else
                    chkPupilIO.push(tmpPupilId);

            });
            $("#tablePagination").css({"margin-top": "0px", "margin-bottom": "0px", "height": "40px"});
            $("#divPopulatepupils_IO").css('display', 'inline');
            $("#btnPupilSearch_IO").addClass("hide");
            $("#btnPupilSubmit_IO").removeClass("hide");
        }
        else
        {
            $("#lblppl_IOerr").text("Pupil does not exist").removeClass("hide");
            return false;
        }
    }
    else
    {
        alert(data.error_msg);
        alert(data.error);
    }
}

$("#btnPupilSubmit_IO").click(function()
{
    var chkboxAction = 0;
    $("input[type=checkbox]", "#tblPupilSearchIO").each(function()
    {
        if ($(this).is(':checked'))
        {
            chkboxAction = 1;
        }
    });
    if (chkboxAction == 0)
    {
        $("#lblppl_IOerr").text("Please select pupil to continue the order process").removeClass("hide");
        return false;
    }
    $("#btnPlaceNewMealDebtOdr").removeAttr("disabled", "disabled");
    loadpupils_IO();
    clearData_IO();

});

$("#btnPupilSearchClose_IO", "#divAddPPl_IO").click(function() {
    clearData_IO()
});
$("#divhdrPPlClose", "#divAddPPl_IO").click(function() {
    clearData_IO()
});

function clearData_IO()
{
    if (!$("#lblppl_IOerr").hasClass("hide"))
        $("#lblppl_IOerr").text("").addClass("hide");
    $("#txtIOPupilId", "#divPPl_IO").val("");
    $("#txtIOFname", "#divPPl_IO").val("");
    $("#txtIOMname", "#divPPl_IO").val("");
    $("#txtIOLname", "#divPPl_IO").val("");
    $("#divPopulatepupils_IO").css("display", "none");
    $("#divPPl_IO").css("display", "inline");
    $("#btnPupilSearch_IO").removeClass("hide");
    $("#btnPupilSubmit_IO").addClass("hide");
    $("#divAddPPl_IO").modal('hide');
}

function loadpupils_IO(dt_ppls)
{
    var tmpresPupils = [];
    if (dt_ppls == undefined)
    {
        tmpresPupils = JSON.parse(tmppplIO);
    }
    else
    {
        tmpresPupils = dt_ppls;
        tmppplIO = JSON.stringify(dt_ppls);
        for (var nCount = 0; nCount < tmpresPupils.length; nCount++) {
            chkPupilIO.push(tmpresPupils[nCount].pupil_id);
        }

    }
    //var tmpresPupils = (dt_ppls == undefined) ? JSON.parse(tmppplIO) : dt_ppls;
    var vat_IV = 0;
    var amt_IV = 0;
    var total_IV = 0;
    if (tmpresPupils.length > 0) {
        $('#divAddPupilInvoice').empty();
        var AddpupilStr = "";
        for (var nCount = 0; nCount < tmpresPupils.length; nCount++) {
            var pupilId = tmpresPupils[nCount].pupil_id;
            var replacedpupilId = pupilId.replace("/", "_");
            var freeMeals = (tmpresPupils[nCount].fsm == 0) ? "<img src='img/fail.png' style='width:20px;' class='pull-left'>" : "<img src='img/pass.png' style='width:20px;' class='pull-left'>";
            var pupilName = tmpresPupils[nCount].fname + " " + tmpresPupils[nCount].mname + " " + tmpresPupils[nCount].lname;
            var ad_flag = tmpresPupils[nCount].adult;
            var adult_flg = "";

            if ($.inArray(pupilId, chkPupilIO) > -1)
            {
                if (ad_flag == "1")
                {
                    adult_flg = "<i class='icon-user' ></i>";
                    vat_IV = parseFloat(vat_IV) + (localStorage["UMC"] * (parseFloat(localStorage["vat"] / 100)));
                }
                amt_IV = parseFloat(amt_IV) + parseFloat(localStorage["UMC"]);
                if (dt_ppls == undefined)
                {
                    $("#txtMealDebtAmt").val(amt_IV);
                    $("#txtMealDebtVat").val(vat_IV);
                    $("#txtMealDebT").val(parseFloat(amt_IV) + parseFloat(vat_IV));
                }
                AddpupilStr += "<div class='span3' style='border: 1px solid #8E8E8E;margin-bottom: 20px;'><!-- right column --><!-- Row --><div class='row-fluid printspan'><!-- widget: energy use --><div class='span12' style='padding: 20px;><div class='row-fluid'><div class='span12'><div class='row-fluid'><div class='span6'><a class='pull-left' href='#'><img class='media-object' src='img/photo-placeholder.png'></a></div><div class='span6'><strong>" + pupilName + "</strong></div></div><div class='row-fluid'><div class='span6'><strong>Pupil ID:</strong></div><div class='span6'>" + pupilId + " " + adult_flg + "</div></div><div class='row-fluid'><div class='span6'><strong>Free Meals?</strong></div><div class='span6'>" + freeMeals + "</div></div><a class='btn btn-danger btn-small' id='remIO_" + replacedpupilId + "' onclick='javascript:removePplIO(\"" + replacedpupilId + "\"," + ad_flag + ");' style='margin-top:0px;'>Remove</a></div></div><!-- /row --></div></div>";
            }
        }
        $('#divAddPupilInvoice').append(AddpupilStr);
        if (localStorage["delfn"] == "1")
        {
            $('[id^="remIO_"]').removeAttr('onclick').unbind("click").attr("disabled", "disabled");
        }
    }
}

function removePplIO(id, adf)
{
    if ($("#btnPlaceNewMealDebtOdr", "#divNewPupilMealDebtOrder").is(':disabled')) {
        $("#btnPlaceNewMealDebtOdr", "#divNewPupilMealDebtOrder").removeAttr("disabled", "disabled");
    }
    if (adf == "1")
    {
        var vat_diff = localStorage["UMC"] * (parseFloat(localStorage["vat"] / 100));
        $("#txtMealDebtVat").val(parseFloat($("#txtMealDebtVat").val()) - parseFloat(vat_diff));
    }
    $("#txtMealDebtAmt").val(parseFloat($("#txtMealDebtAmt").val()) - parseFloat(localStorage["UMC"]));
    $("#txtMealDebT").val(parseFloat($("#txtMealDebtAmt").val()) + parseFloat($("#txtMealDebtVat").val()));
    var index = chkPupilIO.indexOf(id.replace("_", "/"));
    if (index != -1) {
        chkPupilIO.splice(index, 1);
    }
    loadpupils_IO();

}


function srv_savePupilMealOrd() {
    var str_pupils = "";
    if (chkPupilIO.length > 0)
    {
        for (var nCount = 0; nCount < chkPupilIO.length; nCount++)
        {
            str_pupils = str_pupils + chkPupilIO[nCount] + ",";
        }
        str_pupils = (str_pupils.length > 0) ? str_pupils.substr(0, str_pupils.length - 1) : "";
    }
    var dpdate_ar = $("#dp_InvoiceDate").val().split("/");
    var url = BACKENDURL + "user/save_pupil_meal_order_details";
    var data = {
        session_id: localStorage["SESSIONID"],
        invid: $("#txthdnIVid").val(),
        school_id: $("#ddlMealDebtSchools").val(),
        event_date: dpdate_ar[2] + "-" + dpdate_ar[1] + "-" + dpdate_ar[0],
        pupils: str_pupils
    };
    if (str_pupils != "") {
        if ($("#txthdnIVid").val() == "")
            $("#btnPlaceNewMealDebtOdr").html("Placing Order").attr("disabled", "disabled");
        else
            $("#btnPlaceNewMealDebtOdr").html("Updating Order").attr("disabled", "disabled");
        MakeAjaxCall(url, data, savePupilMealOrd);
        $("#divPupilNtAddedErr").addClass('hide');
    }
    else
        $("#divPupilNtAddedErr").html("Please select Pupils to proceed order").removeClass('hide');
}

function savePupilMealOrd(data) {
    if (data.error)
    {
        $("#btnPlaceNewMealDebtOdr").html("Place Order");
    }
    else
    {
        $("#txthdnPgid").val("1");
        if ($("#txthdnIVid").val() == "")
        {
			$("#ddlHO").val($("#ddlMealDebtSchools").val());
            $("#btnCloseNewMealDebt").click();
            $("#lbl_stsIVords").html("Thank you, your order has been succesfully created.").show();
            $("#btnPlaceNewMealDebtOdr").html("Placed Order");
        }
        else
        {
            $("#btnCloseNewMealDebt").click();
            $("#lbl_stsIVords").html("Thank you, your order has been succesfully updated.").show();
            $("#btnPlaceNewMealDebtOdr").html("Updated Order");
        }

    }
}
function LoadManagePupils(){
var page_num = 1;
srv_ManagePupils();
$("#btnAddPupil_MP").unbind("click").bind("click", SrvAddPupilYearClass);
$("#btnPupilSearch_MP").unbind("click").bind("click", SrvPupilSearch);
$("#btnManagePupilSave").unbind("click").bind("click", savePupilDetailsMP); 

$("#tabMngPupils").click(function(){
/* $("#tblManagePupils thead").empty(); 
$("#tblManagePupils tbody:last").empty(); */
$("#btnManagePupilSave").hide();
$("#divManagePupil_pag").hide();
 
});
$("#ddlManagePupilsDD").change(function(){
/* $("#tblManagePupils thead").empty(); 
$("#tblManagePupils tbody:last").empty();  */
$("#btnManagePupilSave").hide();
$("#divManagePupil_pag").hide();
});
$("#txtMPPupilId", "#divPPl_MP").bind("keypress keyup focus", function() {
$("#lblppl_MPerr").text("").addClass("hide");
       if(this.value.length>=1){
	   $("#txtMPFname","#divPPl_MP").attr('disabled', true);
	   $("#txtMPMname","#divPPl_MP").attr('disabled', true);
	   $("#txtMPLname","#divPPl_MP").attr('disabled', true);
	   $("#selectYearMMP","#divPPl_MP").attr('disabled', true);
	   $("#selectClassMMP","#divPPl_MP").attr('disabled', true);
	   }
	   else{
	   $("#txtMPFname","#divPPl_MP").attr('disabled', false);
	   $("#txtMPMname","#divPPl_MP").attr('disabled', false);
	   $("#txtMPLname","#divPPl_MP").attr('disabled', false);
	   $("#selectYearMMP","#divPPl_MP").attr('disabled', false);
	   $("#selectClassMMP","#divPPl_MP").attr('disabled', false);
	   }
    });	
	$("#txtMPFname", "#divPPl_MP").bind("keypress keyup focus", function() {
	$("#lblppl_MPerr").text("").addClass("hide");
       if(this.value.length>=1||($("#txtMPMname").val().length)>=1||($("#txtMPLname").val().length)>=1){
	   $("#txtMPPupilId","#divPPl_MP").attr('disabled', true);	   
	   $("#selectYearMMP","#divPPl_MP").attr('disabled', true);
	   $("#selectClassMMP","#divPPl_MP").attr('disabled', true);
	   }
	   else{
	   $("#txtMPPupilId","#divPPl_MP").attr('disabled', false);	  
	   $("#selectYearMMP","#divPPl_MP").attr('disabled', false);
	   $("#selectClassMMP","#divPPl_MP").attr('disabled', false);
	   }
    });
	$("#txtMPMname", "#divPPl_MP").bind("keypress keyup focus", function() {
	$("#lblppl_MPerr").text("").addClass("hide");
       if(this.value.length>=1||($("#txtMPFname").val().length)>=1||($("#txtMPLname").val().length)>=1){
	   $("#txtMPPupilId","#divPPl_MP").attr('disabled', true);	   
	   $("#selectYearMMP","#divPPl_MP").attr('disabled', true);
	   $("#selectClassMMP","#divPPl_MP").attr('disabled', true);
	   }
	   else{
	   $("#txtMPPupilId","#divPPl_MP").attr('disabled', false);	  
	   $("#selectYearMMP","#divPPl_MP").attr('disabled', false);
	   $("#selectClassMMP","#divPPl_MP").attr('disabled', false);
	   }
    });
	$("#txtMPLname", "#divPPl_MP").bind("keypress keyup focus", function() {
	$("#lblppl_MPerr").text("").addClass("hide");
       if(this.value.length>=1||($("#txtMPFname").val().length)>=1||($("#txtMPMname").val().length)>=1){
	   $("#txtMPPupilId","#divPPl_MP").attr('disabled', true);	   
	   $("#selectYearMMP","#divPPl_MP").attr('disabled', true);
	   $("#selectClassMMP","#divPPl_MP").attr('disabled', true);
	   }
	   else{
	   $("#txtMPPupilId","#divPPl_MP").attr('disabled', false);	  
	   $("#selectYearMMP","#divPPl_MP").attr('disabled', false);
	   $("#selectClassMMP","#divPPl_MP").attr('disabled', false);
	   }
    });
	$('select[id^="selectYearMMP"]').change(function() {
	$("#lblppl_MPerr").text("").addClass("hide");
       if($("#selectYearMMP option:selected").text()!="Select Year"||$("#selectClassMMP option:selected").text()!="Select Class"){
	   $("#txtMPPupilId","#divPPl_MP").attr('disabled', true);	   
	   $("#txtMPFname","#divPPl_MP").attr('disabled', true);
	   $("#txtMPMname","#divPPl_MP").attr('disabled', true);
	   $("#txtMPLname","#divPPl_MP").attr('disabled', true);
	   }
	   else{
	   $("#txtMPPupilId","#divPPl_MP").attr('disabled', false);	  
	    $("#txtMPFname","#divPPl_MP").attr('disabled', false);
	   $("#txtMPMname","#divPPl_MP").attr('disabled', false);
	   $("#txtMPLname","#divPPl_MP").attr('disabled', false);
	   }
       });
	$('select[id^="selectClassMMP"]').change(function() {
	$("#lblppl_MPerr").text("").addClass("hide");
	if($("#selectYearMMP option:selected").text()!="Select Year"||$("#selectClassMMP option:selected").text()!="Select Class"){
	   $("#txtMPPupilId","#divPPl_MP").attr('disabled', true);	   
	    $("#txtMPFname","#divPPl_MP").attr('disabled', true);
	   $("#txtMPMname","#divPPl_MP").attr('disabled', true);
	   $("#txtMPLname","#divPPl_MP").attr('disabled', true);
	   }
	   else{
	   $("#txtMPPupilId","#divPPl_MP").attr('disabled', false);	  
	    $("#txtMPFname","#divPPl_MP").attr('disabled', false);
	   $("#txtMPMname","#divPPl_MP").attr('disabled', false);
	   $("#txtMPLname","#divPPl_MP").attr('disabled', false);
	   }       
       });
}
function srv_ManagePupils(){
 var url = BACKENDURL + "user/get_schools_admins";
    var data = {
        session_id: localStorage["SESSIONID"]
        /* contract_id: localStorage.getItem("contractid") */
    };
    MakeAjaxCall(url, data, loadManagePupilsDD);
}
function loadManagePupilsDD(data){
if (data.error == 0) {
        /* $("#divErrInvoice", "#divInvoice").hide();
        $('#ddlHO', "#divInvoice").empty();
        $("#ddlIVSchools", "#divNewHospOrder").empty();*/
        $("#ddlManagePupilsDD", "#tblManagePupilsDD").empty(); 
        var schoolStr = "";
        if (data.schools_res.length > 0) {
            //  $("#lblErrBOC", "#divCancelOrderstab").addClass('hide');
            //populating schools
            for (var nCount = 0; nCount < data.schools_res.length; nCount++) {
                var disabled = (data.schools_res[nCount].status == "0") ? " (disabled)" : "";
                schoolStr += "<option value=" + data.schools_res[nCount].school_id + ">" + data.schools_res[nCount].school_name + disabled + "</option>";
            }          
            $("#ddlManagePupilsDD", "#tblManagePupilsDD").append(schoolStr);
            if (data.schools_res.length == 1) {
                $("#lblManageMP").hide();
                $(".select-wrap", "#tblManagePupilsDD").hide()
            }
            /* InvoiceHistory(); */
        } else {
            /* $("#divErrInvoice", "#divInvoice").show();
            $("#frmInvoiceOrder", "#divInvoice").hide();
            $("#divInvoiceOrders", "#divInvoice").hide(); */
        }
    }
}
function SrvAddPupilYearClass(){
	   $("#txtMPPupilId","#divPPl_MP").attr('disabled', false).val('');
	   $("#txtMPFname","#divPPl_MP").attr('disabled', false).val('');
	   $("#txtMPMname","#divPPl_MP").attr('disabled', false).val('');
	   $("#txtMPLname","#divPPl_MP").attr('disabled', false).val('');
	   $("#selectYearMMP","#divPPl_MP").attr('disabled', false);
	   $("#selectClassMMP","#divPPl_MP").attr('disabled', false);
	   var url = BACKENDURL + "user/get_schools_year_class_details";
	   var data = {
        session_id:localStorage["SESSIONID"],
        school_id:$('#ddlManagePupilsDD option:selected').val()
		};
		MakeAjaxCall(url, data, loadMPYearClass);
}
var yearsArrMP = new Array();
var classesArrMP = new Array();
function loadMPYearClass(data){
if (data.error == 0) { 
       
        if (data.yc_res.length > 0) {           
            yearsArrMP = new Array();
                        classesArrMP = new Array();
                        var school_id = 0,year_id = 0, yearOptionsStr = "",classOptionStr="";
                        for (var i = 0; i < data.yc_res.length; i++) {						
                             school_id = data.yc_res[i].school_id;
							 year_id =data.yc_res[i].year_Id;
                            yearOptionsStr += "<option value='" + data.yc_res[i].year_Id + "'>" + data.yc_res[i].year_val + "</option>";							
                            classesArrMP[school_id + "_" + data.yc_res[i].year_Id] = "<option value='class1_name'>" + data.yc_res[i].class1_name + "</option><option value='class2_name'>" + data.yc_res[i].class2_name + "</option><option value='class3_name'>" + data.yc_res[i].class3_name + "</option><option value='class4_name'>" + data.yc_res[i].class4_name + "</option><option value='class5_name'>" + data.yc_res[i].class5_name + "</option><option value='class6_name'>" + data.yc_res[i].class6_name + "</option>";                           							
							yearsArrMP[school_id] = yearOptionsStr;
							
							 /* if (i == data.yc_res[i].length - 1 || school_id != data.yc_res[i + 1].school_id) {
                                yearsArrMP[school_id] = yearOptionsStr;
                                yearOptionsStr = "";
                            }  */
							/* yearStr="<select id='selectYear' name='selectYear' onchange='javascript:return populateClassesMP(this, " + data.yc_res[i].school_id + ");'><option value='selectYear'>Select Year</option><option value='allYear'>All Year</option>" + yearsArrMP[data.yc_res[i].school_id] + "</select>"
							classStr=classesArrMP[school_id + "_" + year_id]; */
                        }									
					 $("#selectYearMMP", "#divPPl_MP").html("<option value='SelectYear'>Select Year</option><option value='allYear'>All Year</option>"+yearsArrMP[school_id]);							
                     $("#selectClassMMP", "#divPPl_MP").html("<option value='SelectClass'>Select Class</option><option value='allClass'>All Class</option>"+classesArrMP[school_id + "_" + year_id]);                   					  			
                     $("#selectYearMMP", "#divPPl_MP").change(function() {
					  populateClassesMP(this,school_id)
					});
		   
		   /*  if (data.schools_res.length == 1) {
                $("#lblManageInv").hide();
                $(".select-wrap", "#tblManagePupilsDD").hide()
            }
            InvoiceHistory(); */
        } else {
            /* $("#divErrInvoice", "#divInvoice").show();
            $("#frmInvoiceOrder", "#divInvoice").hide();
            $("#divInvoiceOrders", "#divInvoice").hide(); */
        }
    }
}
function populateClassesMP(f, school_id) {
console.log(school_id)
    var year_id = $(f).val();
	console.log(year_id)
    var classCtlId = $(f).attr("id").replace("selectYearMMP", "selectClassMMP");
    $("#" + classCtlId, "#divPPl_MP").html("<option value='selectClass'>Select Class</option><option value='allClass'>All Class</option>"+classesArrMP[school_id + "_" + year_id]);
}
var pupil_list=[];
function SrvPupilSearch()
{

    var pup_ass = [];
    var pupilId = $("#txtMPPupilId", "#divPPl_MP").val();
    var Fname = $("#txtMPFname", "#divPPl_MP").val();
    var Mname = $("#txtMPMname", "#divPPl_MP").val();
    var Lname = $("#txtMPLname", "#divPPl_MP").val();
    if (((pupilId == "")&&((Fname == "") && (Mname == "") && (Lname == ""))&&(($("#selectYearMMP option:selected").text() == "Select Year")&&($("#selectClassMMP option:selected").text() == "Select Class"))))
    {
        $("#lblppl_MPerr").text("Please enter Pupil ID or Name").removeClass("hide");
        return false;
    } 
    if (!$("#lblppl_MPerr").hasClass("hide"))
        $("#lblppl_MPerr").addClass("hide");
	var all_year=$("#selectYearMMP option:selected").text() == "All Year" ? 1 : '';
	var all_class=$("#selectClassMMP option:selected").text() == "All Class" ? 1 : '';
	var year_id,class_col,class_name;
	if($("#selectYearMMP option:selected").text() == "Select Year"||$("#selectYearMMP option:selected").text() == "All Year")
	year_id = '';
	else
	year_id =$("#selectYearMMP").val();
	if($("#selectClassMMP option:selected").text() == "Select Class"||$("#selectClassMMP option:selected").text() == "All Class")
	class_col = '';
	else
	class_col =$("#selectClassMMP").val();
	if($("#selectClassMMP option:selected").text() == "Select Class"||$("#selectClassMMP option:selected").text() == "All Class")
	class_name = '';
	else
	class_name =$("#selectClassMMP option:selected").text();
    var url = BACKENDURL + "user/get_school_pupil_search";
    var data = {       
		session_id: localStorage["SESSIONID"],
        school_id :$('#ddlManagePupilsDD option:selected').val(),
        pupil_list :pupil_list,
        pupil_id : pupilId,
        fname :Fname,
        mname :Mname,
        lname :Lname,
        year_id :year_id,
        class_col:class_col,
        class_name:class_name,
        all_year: all_year,
        all_class:all_class,
        page:page_num

    };
    MakeAjaxCall(url, data, userSearchMP);
}
var yearsArrtblMP = new Array();
var classesArrtblMP = new Array();
function userSearchMP(data)
{
$("#divAddPPl_MP").modal('hide');
    var tblManagePupil = "";
    if (data.error == 0) {
                    $("#tblManagePupils  tbody:last").empty();                   		   
                    if (data.search_pupils_res.pupils_res.length > 0) {
                        /* $("#WelcomeText", "#mypupils").hide(); */
                        $("#btnManagePupilSave").attr("disabled", 'disabled').show();
                        yearsArrtblMP = new Array();
                        classesArrtblMP = new Array();
                        var school_id = 0, yearOptionsStr = "";
                        for (var i = 0; i < data.search_pupils_res.year_class_res.length; i++) {
                            school_id = data.search_pupils_res.year_class_res[i].school_id;
                            yearOptionsStr += "<option value='" + data.search_pupils_res.year_class_res[i].year_Id + "'>" + data.search_pupils_res.year_class_res[i].year_val + "</option>";
                            classesArrtblMP[school_id + "_" + data.search_pupils_res.year_class_res[i].year_Id] = "<option value='class1_name'>" + data.search_pupils_res.year_class_res[i].class1_name + "</option><option value='class2_name'>" + data.search_pupils_res.year_class_res[i].class2_name + "</option><option value='class3_name'>" + data.search_pupils_res.year_class_res[i].class3_name + "</option><option value='class4_name'>" + data.search_pupils_res.year_class_res[i].class4_name + "</option><option value='class5_name'>" + data.search_pupils_res.year_class_res[i].class5_name + "</option><option value='class6_name'>" + data.search_pupils_res.year_class_res[i].class6_name + "</option>";
                            yearsArrtblMP[school_id] = yearOptionsStr;
							/* if (i == data.search_pupils_res.year_class_res[i].length - 1 ) {
                                yearsArrtblMP[school_id] = yearOptionsStr;
                                yearOptionsStr = "";
                            }  */
                        }
                        var tblManagePupil = "";
                        for (var nCount = 0; nCount < data.search_pupils_res.pupils_res.length; nCount++) {
						
                            var studentId = data.search_pupils_res.pupils_res[nCount].s_id;
							
							pupil_list=studentId;							
                            var pupilId = data.search_pupils_res.pupils_res[nCount].pupil_id;
                            var freeMeals = (data.search_pupils_res.pupils_res[nCount].fsm == 0) ? "<img src='img/fail.png' style='width:20px;display:block;margin:0 auto;' >" : "<img src='img/pass.png' style='width:20px;display:block;margin:0 auto;'>";
                            var adult = (data.search_pupils_res.pupils_res[nCount].adult == 1) ? "<i class='icon-user'></i>" : "";
							var assingnedParent= (data.search_pupils_res.pupils_res[nCount].pname == null) ? "-": data.search_pupils_res.pupils_res[nCount].pname;
                            var years = ""; 
                            tblManagePupil += "<tr><td data-title='Pupil First Name'><div class='control-group'><input type='text' name='PupilFirstNameMP" + studentId + "' required id='PupilFirstNameMP" + studentId + "' value='" + data.search_pupils_res.pupils_res[nCount].fname + "' maxlength='50' style='width: 80px;'></input></div></td>";
                            tblManagePupil += "<td data-title='Pupil Middle Name'><div class='control-group'><input type='text' name='PupilMiddleNameMP" + studentId + "' id='PupilMiddleNameMP" + studentId + "' value='" + data.search_pupils_res.pupils_res[nCount].mname + "' maxlength='50' style='width: 80px;'></div></td>";
                            tblManagePupil += "<td data-title='Pupil Last Name'><div class='control-group'><input type='text' name='PupilLastNameMP" + studentId + "'  required id='PupilLastNameMP" + studentId + "' value='" + data.search_pupils_res.pupils_res[nCount].lname + "' maxlength='50' style='width: 80px;'></input></div></td>";
                            tblManagePupil += "<td nowrap='nowrap' data-title='Pupil ID'><div style= 'padding-top:6px' id='pupilidMP" + studentId + "'>" + data.search_pupils_res.pupils_res[nCount].pup_id + "  " + adult + "</div></td>";
                            tblManagePupil += "<td data-title='Free Meals?'><label style= 'padding-top: 6px;' id='freemeals" + studentId + "'>" + freeMeals + "</label></td>";
                            tblManagePupil += "<td data-title='School'><div style='padding-top: 6px;'id='AssignedParentMP" + studentId + "'>" + assingnedParent + "</div></td>";
                            tblManagePupil += "<td data-title='Year'><span class='select-wrap' style='width:130px;'><select id='selectYear_MP" + studentId + "' name='selectYear_" + studentId + "' onchange='javascript:return populateClassestblMP(this, " + data.search_pupils_res.pupils_res[nCount].school_id + ");'>" + yearsArrtblMP[data.search_pupils_res.pupils_res[nCount].school_id] + "</select></span></td>";
                            tblManagePupil += "<td data-title='Class'><span class='select-wrap' style='width:130px;'><select id='selectClass_MP" + studentId + "' name='selectClass_" + studentId + "'></select></span></td>";                           							
							tblManagePupil += "<td class='alignright'><div class='btn-group'><button data-toggle='dropdown' class='btn btn-default dropdown-toggle' type='button'> Options <span class='caret'></span></button>";
							tblManagePupil += "<ul role='menu' class='dropdown-menu'>";
							tblManagePupil += "<li><a onclick='javascript:SrvUpdatePupil();' href='javascript:void(0);'>Update Pupil</a></li>";
							tblManagePupil += "<li> <a href='javascript:void(0);' onClick='SrvUnassignParent()'>Unassign Parent</a></li>";
							tblManagePupil += "<li> <a href='javascript:void(0);'  onClick='SrvRemovePupil()'>Remove Pupil</a></li></div></td></tr>";										
                            $("#tblManagePupils  tbody:last").append(tblManagePupil);
                            $("#selectYear_MP" + studentId, "#tblManagePupils").val(data.search_pupils_res.pupils_res[nCount].year_Id);							
                            $("#selectClass_MP" + studentId, "#tblManagePupils").html(classesArrtblMP[data.search_pupils_res.pupils_res[nCount].school_id + "_" + data.search_pupils_res.pupils_res[nCount].year_Id]);
                            $("#selectClass_MP" + studentId, "#tblManagePupils").val(data.search_pupils_res.pupils_res[nCount].class_name);
                            tblManagePupil = "";                          
                        }
						pupileditMP();
                        $("#tblManagePupils").show();                       
						if (data.search_pupils_res.pupils_cnt > 10) {
						var total_Pupils_count = data.search_pupils_res.pupils_cnt;
						var total_page = Math.ceil(total_Pupils_count/ 10);
						var no_pages = total_page > 1 ? 3 : 1;
						if (total_Pupils_count > 10) {
                        var options = {
                            currentPage: page_num,
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
                                page_num = page;
                                SrvPupilSearch(page_num);
                            }
                        };
                        $("#divManagePupil_pag").bootstrapPaginator(options);
                        $("#divManagePupil_pag").show();
                    }
                    else
                    {
                        $("#divManagePupil_pag").hide();
                    }
					} 
                        formDirtyCheckMP();
                    }
                    else {
                       /*  $("#WelcomeText", "#mypupils").show(); */
                        $("#btnManagePupilSave").attr("disabled", 'disabled').hide();
                        $("#tblManagePupils").hide();
                    }
    }
    else
    {
        alert(data.error_msg);
        alert(data.error);
    }
}
function pupileditMP(){
$('input[id^="PupilFirstNameMP"]', "#tblManagePupils").bind("keypress keyup", function() {
        $("#btnManagePupilSave").removeAttr("disabled").text('Save');
    });
    $('input[id^="PupilMiddleNameMP"]', "#tblManagePupils").bind("keypress keyup", function() {
        $("#btnManagePupilSave").removeAttr("disabled").text('Save');
    });
    $('input[id^="PupilLastNameMP"]', "#tblManagePupils").bind("keypress keyup", function() {
        $("#btnManagePupilSave").removeAttr("disabled").text('Save');
    });
    $('select[id^="selectYear"]', "#tblManagePupils").change(function() {
        $("#btnManagePupilSave").removeAttr("disabled").text('Save');
    });
    $('select[id^="selectClass"]', "#tblManagePupils").change(function() {
        $("#btnManagePupilSave").removeAttr("disabled").text('Save');
    });	
}
var idMPCollect = [], pfIdMP, pmIdMP, plydMP, plIdMP, plcdMP, uniqueIdMP = [], pupilUpdatedArrayMP = [];
function formDirtyCheckMP() {
    var Settings = {
        denoteDirtyForm: true,
        dirtyFormClass: false,
        dirtyOptionClass: "dirtyChoice",
        trimText: true,
        formChangeCallback: function(result, dirtyFieldsArray) {
            pupilUpdatedArrayMP = [];
            idMPCollect = [];
            uniqueIdMP = [];
            if (result)
            {
                /* $("#pupilInfo").valid(); */
                $.each(dirtyFieldsArray, function(index, value) {
                    if (value.match("^PupilFirstNameMP")) {
                        pfIdMP = value.substring(14);
                        idMPCollect.push(pfIdMP);
                    }
                    if (value.match("^PupilMiddleNameMP")) {
                        pmIdMP = value.substr(15);
                        idMPCollect.push(pmIdMP);
                    }
                    if (value.match("^PupilLastNameMP")) {
                        plIdMP = value.substr(13);
                        idMPCollect.push(plIdMP);
                    }
                    if (value.match("^selectYear")) {
                        plydMP = value.substr(11);
                        idMPCollect.push(plydMP);
                    }
                    if (value.match("^selectClass")) {
                        plcdMP = value.substr(12);
                        idMPCollect.push(plcdMP);
                    }
                    $.each(idMPCollect, function(i, el) {
                        if ($.inArray(el, uniqueIdMP) === -1)
                            uniqueIdMP.push(el);
                    });
                })
                $.each(uniqueIdMP, function(i, id) {
				console.log(id)
                    pupilUpdatedArrayMP.push({"pupil_id": $("#pupilid" + id + "").text(), "fname": $("#PupilFirstName" + id + "").val(), "lname": $("#PupilLastName" + id + "").val(), "mname": $("#PupilMiddleName" + id + "").val(), "year": $("#selectYear_" + id + "").val(), 'class': $("#selectClass_" + id + "").val()})
                })
				console.log(pupilUpdatedArrayMP)
            }
            else
            {
                setTimeout(function() {
                    $("#btnManagePupilSave").attr("disabled", true).text('Saved')
                }, 500);
            }

        }
    };
    $("#frmManagePupil").dirtyFields(Settings);
}
 function savePupilDetailsMP() {
   if ($("#frmManagePupil").valid()) { 
        if (pupilUpdatedArrayMP != "") {
            var url = BACKENDURL + "user/edit_pupils";
            var data = {
                session_id: localStorage["SESSIONID"],
                pupils_data: pupilUpdatedArrayMP
            };
            MakeAjaxCall(url, data, updatePupilsMP);
        } else {
            $("#btnManagePupilSave").attr("disabled", true).text('Saving')
            $("#btnManagePupilSave").attr("disabled", true).text('Saved')
        }
    } 
}
function updatePupilsMP(data) {
    if (data.error == 0) {
        $.fn.dirtyFields.formSaved($("#frmManagePupil"));
        $("#btnManagePupilSave").text('Saving');
        $("#btnManagePupilSave").attr("disabled", true);
        setTimeout(function() {
            $("#btnManagePupilSave").text('Saved');
        }, 1000)
    } else {
        logout(1);
    }
}
function populateClassestblMP(f, school_id) {
console.log(school_id)
    var year_id = $(f).val();
    var classCtlId = $(f).attr("id").replace("selectYear", "selectClass");
    $("#" + classCtlId, "#tblManagePupils").html(classesArrtblMP[school_id + "_" + year_id]);
}

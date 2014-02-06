var windowURL = window.location.toString().split('/').reverse();
windowURL[0] = 'index.php/';
windowURL[1] = 'ri_backend';
var BACKENDURL = windowURL.reverse().join('/');
//var SKINREF = 'c_view_assets?skin_id=';
var L1_menu = {
    'L1HME': 'userpage.html',
    'L1PSS': 'userpeopleservices.html',
    'L1BSS': 'userbuildingservices.html',
    'L1RMT': 'userresourcemanagement.html',
    'L1PRS': 'userperformancereports.html'
};
var L2_menu = {
    'L2DBD': 'userpage.html',
    'L2STY': 'userpagesafety.html',
    'L2CAT': 'userpeopleservices.html',
    'L2CLN': 'userpeopleservicesclean.html',
    'L2EGY': 'userbuildingservices.html',
    'L2MAE': 'userbuildingservicesme.html',
    'L2AST': 'userresourcemanagement.html',
    'L2SSLA': 'userperformancereportsservicesla.html',
    'L2SPE': 'userperformancereportssafety.html',
    'L2PCAT': 'userpeopleservicespc.html',
    'L2PQA': 'userperformancereports.html'
};
var L3_menu = {
    'L3MPS_H': '#',
    'L4MYPPL_H': '#mypupils',
    'L4MGPPL_H': '#managepupils',
    'L3MOS_H': '#divMyOrders',
    'L3SOE_H': '#',
    'L3PYT_H': '#',
    'L3PDOC_H': '#documents',
    'L3PSDF_H': '#catDigitalForms',
    'L3PCTDM_H': '#pcatDigitalFormReports',
    'L3PCDMO_H': '#pcatDailyMealOrders',
    'L3PCDF_H': '#pcatDigitalForms',
    'L4MSL_H': '#myschools',
    'L4MMU_H': '#mymenus',
    'L4IORD_H':'#divInvoice',
    //'L4HOR_H': '#myhosorders',
    //'L4HHY_H': '#myhoshistory',
    'L4MOS_H': '#divMealOrders',
    'L4DMC_H': '#mydailymeal',
    'L4BOC_H': '#divCancelOrderstab',
    'L4MCDP_H': '#cardmakePayment',
    'L4PPH_H': '#cardpaymentHistory',
    'L4MCP_H': '#cashmakePayment',
    'L4SPH_H': '#cashpaymentHistory',
    'L4MCR_H': '#CashMakeRefund',
    'L3SUY_H': "#",
    'L4SAL_H': '#summaryMonthly',
    'L4SMY_H': '#summaryDaily',
    'L4SHH_H': '#summaryHalfHourly',
    'L3ELY_H': '#',
    'L4EAL_H': '#eleMonthly',
    'L4EMY_H': '#eleDaily',
    'L4EHH_H': '#eleHalfHourly',
    'L3GAS_H': '#',
    'L4GAL_H': '#gasMonthly',
    'L4GMY_H': '#gasDaily',
    'L3BDOC_H': '#documents',
    'L3BSDF_H': '#eDigitalForms',
    'L3ZODB_H': '#ivUserZoneDashboard',
    'L3ASDB_H': '#divUserAssetDashboard',
    'L3ATDF_H': '#divATDigitalForms',
    'L3QARG_H' : '#divReportGraphs',
    
    'L3MPS_I': 'dmPupils',
    'L4MYPPL_I': 'tabMyPupils',
    'L4MGPPL_I': 'tabMngPupils',
    
    'L3MOS_I': 'tabMyOrders',
    'L3SOE_I': 'dmSchoolOffice',
    'L3PYT_I': 'dmPayments',
    'L3PDOC_I': 'tabDocuments',
    'L3PSDF_I': 'tabPSCDigital',
    'L3PCTDM_I': 'tabPCTotalDailyMealNumbers',
    'L3PCDMO_I': 'tabPCDailyMealOrders',
    'L3PCDF_I': 'tabPCDigitalForms',
    'L4MSL_I': 'tabMySchools',
    'L4MMU_I': 'tabMyMenus',
    'L4IORD_I':'tabMyInvOrders',
    //'L4HOR_I': 'tabMyHosOrders',
    //'L4HHY_I': 'tabMyHosOrders',
    'L4MOS_I': 'tabMealOrder',
    'L4DMC_I': 'tabDailyMeal',
    'L4BOC_I': 'tabCancelOrders',
    'L4MCDP_I': 'tabCardMakePayment',
    'L4PPH_I': 'tabCardPaymentHistory',
    'L4MCP_I': 'tabCashMakePayment',
    'L4SPH_I': 'tabCashPaymentHistory',
    'L4MCR_I': 'tabCashMakeRefund',
    'L3BDOC_I': 'energyDocumentTab',
    'L3BSDF_I': 'tabEnergyDigitalForms',
    'L3SUY_I': 'dmSummary',
    'L4SAL_I': 'divSumMonthly',
    'L4SMY_I': 'divSumDaily',
    'L4SHH_I': 'divSumHH',
    'L3ELY_I': 'dmElectricity',
    'L4EAL_I': 'divEleMonthly',
    'L4EMY_I': 'divEleDaily',
    'L4EHH_I': 'divEleHH',
    'L3GAS_I': 'dmGas',
    'L4GAL_I': 'divGasMonthly',
    'L4GMY_I': 'divGasDaily',
    'L3ZODB_I': 'zonedashboard',
    'L3ASDB_I': 'assetdashboard',
    'L3ATDF_I': 'tabATDigitalForms',
    'L3QARG_I' : 'tabReportGraphs',
}
//var AD_PROF_MOD = ['AL3DBD','AL3USR','AL3ADM','AL3HSET','AL3PRO','AL3SKN','AL3SLG','AL3PHP','AL3PIMP','AL3PDOC','AL3PSET','AL3SCON','AL4SVYS','AL4MNUS','AL3SCOF','AL4MYSC','AL4MORD','AL3ACCT','AL4REPT','AL4CDRS','AL4CDRH','AL3SETD','AL3SETD','AL3HHD','AL3NHHD','AL3PRG','AL3BDOC','AL3ZDBD','AL3ADBD','AL3STY','AL3CLN','AL3ME','AL3SLA','AL3SFTYPER'];

$("#btnLogout").click(function() {
    // make an ajax call to remove the session and redirect to login page.
    logout();
});
function logout(n) {
    $.support.cors = true;
    $.ajax({
        url: BACKENDURL + "common/remove_session",
        type: "post",
        data: {
            session_id: localStorage["SESSIONID"]
        },
        dataType: "json",
        crossDomain: true,
        success: function(data) {
            //alert("success....");
            localStorage.clear();
            if (n == 1)
                location.href = "error.html";
            else
                location.href = 'index.html';
        },
        error: function(xhr, textStatus, error) {
            localStorage.clear();
            location.href = 'index.html';
        }
    });
}
// is email common method
function isEmail(email) {
    var pattern = /^(([A-Za-z0-9]+_)|([A-Za-z0-9]+\.)|([A-Za-z0-9]+\+))*[A-Za-z0-9]+@((\w+\-+)|(\w+\.))*\w{1,63}\.[a-zA-Z]{2,6}$/;
    return pattern.test(email);
}

function postForm(url, value) {
    $('#hdnFormPost').val(value);
    $('#formpost').attr('target', '').attr('action', url).submit();
    return false;
}

function isNumber(n) {
    return !isNaN(parseFloat(n)) && isFinite(n);
}
function isDecimal(n) {
    return /^[0-9]+(\.[0-9]{1,2})?$/.test(n);
}
function isAlphaOrParen(str) {
    return /^[a-zA-Z()]+$/.test(str);
}

/*
 * function isPhone(value){
 * 
 * return /[0-9-()+]{3,20}/(value); }
 */

function isEven(value) {
    if (value % 2 == 0)
        return true;
    else
        return false;
}

function isOdd(n) {
    return isNumber(n) && (n % 2 == 1);
}
function ModalFieldUI(val) {
    $(val).css('border', "1px solid red");
}
function ModalFieldUINew(val) {
    $(val).removeAttr('style');
}

function MakeAjaxCall(url, input_data, callbackfn) {
    $.support.cors = true;
    $.ajax({
        url: url,
        type: "post",
        data: input_data,
        dataType: "json",
        crossDomain: true,
        success: function(data) {
            if (data.session_status) {
                var args = new Array();
                args[0] = data;
                callbackfn.apply(null, args);
            } else
                logout();
        },
        error: function(xhr, textStatus, error) {
            alert("Something went wrong with your request. Please try again and if it still doesn't work ask your administrator for help");

        }
    });
}

function Confirm_box(CB_message, CB_title, callbackScsfn, callbackFlrfn)
{
    bootbox.dialog({
        message: CB_message,
        title: CB_title,
        buttons: {
            danger: {
                label: "Cancel",
                className: "btn",
                callback: function() {
                    if (callbackFlrfn != "")
                        callbackFlrfn.apply(null);
                }
            },
            success: {
                label: "Continue",
                className: "btn-primary",
                callback: function() {
                    callbackScsfn.apply(null);
                }
            }
        }
    });
}
//For sorting the mulitple select boxes after adding/removing.
function SortOptions(id) {
    $(id).html($(id + " option").sort(function(a, b) {
        var x = a.text.toUpperCase();
        y = b.text.toUpperCase();
        return x == y ? 0 : x < y ? -1 : 1
    }));
}

// sorting tables 
function Sort_table(id)
{
    var $table = $('#' + id);
    var $rows = $('tbody > tr', $table);
    $rows.sort(function(a, b) {
        var keyA = $('td', a).text().toUpperCase();
        var keyB = $('td', b).text().toUpperCase();	
        return (keyA == keyB) ? 0: keyA < keyB ? -1 : 1; 
    });
    $.each($rows, function(index, row) {
        $table.append(row);
    });
    //e.preventDefault();
}
//add and remove common functionality
function addTable(avlTable, selTable, iconPH, errId, err_msg)
{
    var count = 0;
    $("#" + avlTable.id + "  tr").each(function(e) {
        if ($(this).hasClass("alert alert-success")) {
            count++;
            $("#" + selTable.id + " tbody").append($(this).removeClass("alert alert-success").clone());
            $(this).detach();
        }
    });
    if (($("#" + avlTable.id + " tr").length) > 0) {
        $("#" + iconPH.id + " li:first").remove();
        $("#" + iconPH.id).prepend('<li class="icon-ok"></i>');
    }
    else {
        $('#' + iconPH.id + ' li:first').remove();
    }
    if (count == 0)
        $('#' + errId.id).html(err_msg).show();
    else {
        $('#' + errId.id).hide();
        Sort_table(selTable.id);
    }

}
function remTable(avlTable, selTable, iconPH, errId, err_msg)
{
    var count = 0;
    $("#" + selTable.id + "  tr").each(function(e) {
        if ($(this).hasClass("alert alert-success")) {
            count++;
            $("#" + avlTable.id + " tbody").append($(this).removeClass("alert alert-success").clone());
            $(this).detach();
        }
    });
    if (($("#" + avlTable.id + " tr").length) > 0) {
        $("#" + iconPH.id + " li:first").remove();
        $("#" + iconPH.id).prepend('<li class="icon-ok"></i>');
    }
    else {
        $('#' + iconPH.id + ' li:first').remove();
    }
    if (count == 0)
        $('#' + errId.id).html(err_msg).show();
    else {
        $('#' + errId.id).hide();
        Sort_table(avlTable.id);
    }

}
/* function Addfunction(avlTable, selTable, savebuttonId,errId, err_msg) {

    var count = 0;
    $("#" + avlTable.id + "  tr").each(function(e) {
        if ($(this).hasClass("alert alert-success")) {
            count++;
            $("#" + selTable.id + " tbody").append($(this).removeClass("alert alert-success").clone());
            $(this).detach();
        }
    });
     
    if (count == 0)
        $('#' + errId.id).html(err_msg).show();
    else {
        $('#' + errId.id).hide();
        $('#' + savebuttonId.id).removeAttr("disabled").text('Save');
        Sort_table(selTable.id);
    }
}

function Removefunction(avlTable, selTable, savebuttonId, errId, err_msg) {
    var count = 0;
    $("#" + selTable.id + "  tr").each(function(e) {
        if ($(this).hasClass("alert alert-success")) {
            count++;
            $("#" + avlTable.id + " tbody").append($(this).removeClass("alert alert-success").clone());
            $(this).detach();
        }
    });

    if (count == 0)
        $('#' + errId.id).html(err_msg).show();
    else {
        $('#' + errId.id).hide();
        $('#' + savebuttonId.id).removeAttr("disabled").text('Save');
        Sort_table(avlTable.id);
    }
}
 */
function getRoundMonth(date) {
    var month = date.getMonth() + 1;
    return month < 10 ? '0' + month : month;
}

function getRoundDate(date) {
    var day = date.getDate()
    return day < 10 ? '0' + day : day;
}

function date_diff(cl_dte, cu_dte)
{
    var cl_dt = parseDate(cl_dte);
    var cu_dt = parseDate(cu_dte);
    return Math.ceil((cl_dt - cu_dt) / (1000 * 24 * 60 * 60));
}

function parseDate(input) {
    var parts = input.match(/(\d+)/g);
    return new Date(parts[0], parts[1] - 1, parts[2]);
}

// date format conversion (yyyy -mm-dd) to dd mon
function convertDate(date) {
    var mydate = new Date(date);
    var month = ["Jan", "Feb", "Mar", "Apr", "May", "June",
        "July", "Aug", "Sept", "Oct", "Nov", "Dec"][mydate.getMonth()];
    var Suffix = new Array("th", "st", "nd", "rd", "th", "th", "th", "th", "th", "th");
    var day = mydate.getDate();
    if (day % 100 >= 11 && day % 100 <= 13)
        today = day + "th";
    else
        today = day + Suffix[day % 10];
    return today + ' ' + month;
}

function convertDateToDMY(dateStr) { // ex input "2013-11-18"
    dArr = dateStr.split("-");  
    return dArr[2]+ "/" +dArr[1]+ "/" +dArr[0];
}

//for centering the modal...
(function($) {
    $.fn.extend({
        center: function() {
            return this.each(function() {
                var top = ($(window).height() - $(this).outerHeight()) / 2;
                var left = ($(window).width() - $(this).outerWidth()) / 2;
                $(this).css({
                    position: 'absolute',
                    margin: 0,
                    top: (top > 0 ? top : 0) + 'px',
                    left: (left > 0 ? left : 0) + 'px'
                });
            });
        }
    });
})(jQuery);


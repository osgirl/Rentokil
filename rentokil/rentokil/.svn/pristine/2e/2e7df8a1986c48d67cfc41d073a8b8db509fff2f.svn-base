var json_TDM = [];
var initWards_TDM = [];
var initWards_DMO = [];
var rowArray = [];
var autoRefreshIntervalID = null;
function LoadPageData() {
    var winW = $(window).width();
    if (winW < '768') {
        $("#tabbable_TDM").removeClass('tabs-left');
        $("#tabbable_DMO").removeClass('tabs-left');
        $("#tabbable_DF").removeClass('tabs-left');
    }
    else {
        $("#tabbable_TDM").addClass('tabs-left');
        $("#tabbable_DMO").addClass('tabs-left');
        $("#tabbable_DF").addClass('tabs-left');
    }
    persistentheaders();
    DateFunction();
    HideErrMsg();
    $('td', "#tblTDMReport").filter(function() {
        var ht = this.innerHTML.replace('<a href="javascript:navigateToDigitalForms();">', '').replace('</a>', '').replace('<a href="javascript:navigateToDigitalForms();" style="color:#FFFFFF">', '');
        return ht.match(/^[0-9\s\.,]+$/);
    }).css('text-align', 'center');
    $('td', "#tblTDMReport2").filter(function() {
        var ht = this.innerHTML.replace('<a href="javascript:navigateToDigitalForms();">', '').replace('</a>', '').replace('<a href="javascript:navigateToDigitalForms();" style="color:#FFFFFF">', '');
        return ht.match(/^[0-9\s\.,]+$/);
    }).css('text-align', 'center');
    $("#tabPCDigitalForms").bind("click", digitalFormsClick);
    $("#btnResetAll").click(function() {
        DateFunction();
        SrvLoadDigitalforms();
        HideErrMsg();
        $("#chkSEFD").attr('checked', false);
        $("#divFilterErr").hide();
    });
    $("#btnDigitalFormFilter").click(function() {
	
        srv_showDFForms(page)
    });
    $("#tabPCTotalDailyMealNumbers").click(function() {
        $("#accReportsForms").collapse('hide');
        setTimeout(function() {

            $("#accReportsFilter").collapse('show');
        }, 200);
        reset_TDM();
    });
    $("#tabPCDailyMealOrders").click(function() {
	$("#btnDMORefresh").removeClass('hide');
	$("#accReportsFilter2").collapse('hide');
     setTimeout(function() {
        $("#accReportsForms2").collapse('show');
        $("#divRefresh").show();
    }, 100); 
	Srv_showDMOReport();
        SrvLoadDailyMealOrders();		
        HideErrMsg_DMO();
    });
    $("#btnReset_TDM").bind("click", reset_TDM);
    $("#btnReset_DMO").bind("click", reset_DMO);
    $("#btn_genreport").bind("click", srv_showTDMReport);
    $("#btnAddPens", "#divFilPens").click(function() {
        var errMsg = "Please select from available Pens";
        addTable(lstAvailablePens, lstSelectedPens, divPenTab, divUserPensErrMsg, errMsg);
    });
    $("#btnRemovePens", "#divFilPens").click(function() {
        var errMsg = "Please select from selected Pens";
        remTable(lstAvailablePens, lstSelectedPens, divPenTab, divUserPensErrMsg, errMsg);
    });
    $("#btnAddForms", "#divFilApps").click(function() {
        var errMsg = "Please select from available Forms";
        addTable(lstAvailableForm, lstSelectedForm, divFormsTab, divUserFormsErrMsg, errMsg);
    });
    $("#btnRemoveForms", "#divFilApps").click(function() {
        var errMsg = "Please select from selected Forms";
        remTable(lstAvailableForm, lstSelectedForm, divFormsTab, divUserFormsErrMsg, errMsg);
    });
    $("#btnAddWards", "#divFilWards").click(function() {
        var errMsg = "Please select from available Wards";
        addTable(lstAvailableWards, lstSelectedWards, divWardsTab, divUserWardsErrMsg, errMsg);
    });
    $("#btnRemoveWards", "#divFilWards").click(function() {
        var errMsg = "Please select from selected Wards";
        remTable(lstAvailableWards, lstSelectedWards, divWardsTab, divUserWardsErrMsg, errMsg);
    });
    $("#btnAddDOW", "#divFilDay").click(function() {
        var errMsg = "Please select from available days of weeks";
        addTable(lstAvailableDOW, lstSelectedDOW, divDOWTab, divUserDOWErrMsg, errMsg);
    });
    $("#btnRemoveDOW", "#divFilDay").click(function() {
        var errMsg = "Please select from selected days of weeks";
        remTable(lstAvailableDOW, lstSelectedDOW, divDOWTab, divUserDOWErrMsg, errMsg);
    });
    $("#btnAddMenuType", "#divMenus").click(function() {
        var errMsg = "Please select from available menutype";
        addTable(lstAvailableMenutype, lstSelectedMenuType, divMenuTypeTab, divUserMenuTypeErrMsg, errMsg);
    });
    $("#btnRemoveMenuType", "#divMenus").click(function() {
        var errMsg = "Please select from selected menutype";
        remTable(lstAvailableMenutype, lstSelectedMenuType, divMenuTypeTab, divUserMenuTypeErrMsg, errMsg);
    });
    /*$("#btnAddPens_TDM", "#divFilPens_r").bind("click", addPen_TDM);
     $("#btnRemovePens_TDM", "#divFilPens_r").bind("click", remPen_TDM);
     $("#btnAddWards_TDM", "#divFilWards_r").bind("click", addWard_TDM);
     $("#btnRemoveWards_TDM", "#divFilWards_r").bind("click", remWard_TDM);
     $("#btnAddDOW_TDM", "#divFilDay_r").bind("click", addDay_TDM);
     $("#btnRemoveDOW_TDM", "#divFilDay_r").bind("click", remDay_TDM);
     $("#btnAddMenuType_TDM", "#divMenus_r").bind("click", addMenus_TDM);
     $("#btnRemoveMenuType_TDM", "#divMenus_r").bind("click", remMenus_TDM);*/
    $("#btnAddPens_TDM", "#divFilPens_r").click(function() {
        var errMsg = "Please select from available Pens";
        addTable(lstAvailablePens_TDM, lstSelectedPens_TDM, diviconPens, divPensErrMsg_TDM, errMsg);
    });
    $("#btnRemovePens_TDM", "#divFilPens_r").click(function() {
        var errMsg = "Please select from selected Pens";
        remTable(lstAvailablePens_TDM, lstSelectedPens_TDM, diviconPens, divPensErrMsg_TDM, errMsg);
    });
    $("#btnAddWards_TDM", "#divFilWards_r").click(function() {
        var errMsg = "Please select from available Wards";
        addTable(lstAvailableWards_TDM, lstSelectedWards_TDM, diviconWard, divWardsErrMsg_TDM, errMsg);
    });
    $("#btnRemoveWards_TDM", "#divFilWards_r").click(function() {
        var errMsg = "Please select from selected Wards";
        remTable(lstAvailableWards_TDM, lstSelectedWards_TDM, diviconWard, divWardsErrMsg_TDM, errMsg);
    });
    $("#btnAddDOW_TDM", "#divFilDay_r").click(function() {
        var errMsg = "Please select from available days of weeks";
        addTable(lstAvailableDOW_TDM, lstSelectedDOW_TDM, diviconDay, divDayErrMsg_TDM, errMsg);
    });
    $("#btnRemoveDOW_TDM", "#divFilDay_r").click(function() {
        var errMsg = "Please select from selected days of weeks";
        remTable(lstAvailableDOW_TDM, lstSelectedDOW_TDM, diviconDay, divDayErrMsg_TDM, errMsg);
    });
    $("#btnAddMenuType_TDM", "#divMenus_r").click(function() {
        var errMsg = "Please select from available menutype";
        addTable(lstAvailableMenutype_TDM, lstSelectedMenuType_TDM, diviconMenu, divMenuErrMsg_TDM, errMsg);
    });
    $("#btnRemoveMenuType_TDM", "#divMenus_r").click(function() {
        var errMsg = "Please select from selected menutype";
        remTable(lstAvailableMenutype_TDM, lstSelectedMenuType_TDM, diviconMenu, divMenuErrMsg_TDM, errMsg);
    });
    /***************** Add Remove DMO ***************************/
    $("#btnAddPens_DMO", "#divFilPens_r2").click(function() {
        var errMsg = "Please select from available Pens";
        addTable(lstAvailablePens_DMO, lstSelectedPens_DMO, diviconPens_DMO, divPensErrMsg_DMO, errMsg);
    });
    $("#btnRemovePens_DMO", "#divFilPens_r2").click(function() {
        var errMsg = "Please select from selected Pens";
        remTable(lstAvailablePens_DMO, lstSelectedPens_DMO, diviconPens_DMO, divPensErrMsg_DMO, errMsg);
    });
    $("#btnAddWards_DMO", "#divFilWards_r2").click(function() {
        var errMsg = "Please select from available Wards";
        addTable(lstAvailableWards_DMO, lstSelectedWards_DMO, diviconWards_DMO, divWardsErrMsg_DMO, errMsg);
    });
    $("#btnRemoveWards_DMO", "#divFilWards_r2").click(function() {
        var errMsg = "Please select from selected Wards";
        remTable(lstAvailableWards_DMO, lstSelectedWards_DMO, diviconWards_DMO, divWardsErrMsg_DMO, errMsg);
    });
    $("#btnAddDOW_DMO", "#divFilDay_r2").click(function() {
        var errMsg = "Please select from available days of weeks";
        addTable(lstAvailableDOW_DMO, lstSelectedDOW_DMO, diviconDOW_DMO, divDOWErrMsg_DMO, errMsg);
    });
    $("#btnRemoveDOW_DMO", "#divFilDay_r2").click(function() {
        var errMsg = "Please select from selected days of weeks";
        remTable(lstAvailableDOW_DMO, lstSelectedDOW_DMO, diviconDOW_DMO, divDOWErrMsg_DMO, errMsg);
    });
    $("#btnAddMenuType_DMO", "#divMenus_r2").click(function() {
        var errMsg = "Please select from available menutype";
        addTable(lstAvailableMenuType_DMO, lstSelectedMenuTypes_DMO, diviconMenuType_DMO, divMenuTypeErrMsg_DMO, errMsg);
    });
    $("#btnRemoveMenuType_DMO", "#divMenus_r2").click(function() {
        var errMsg = "Please select from selected menutype";
        remTable(lstAvailableMenuType_DMO, lstSelectedMenuTypes_DMO, diviconMenuType_DMO, divMenuTypeErrMsg_DMO, errMsg);
    });
    $('#ddlForms_DMO').on('change', function() {
        if (!$("#diviconFroms_DMO li:first").hasClass("icon-ok"))
            $("#diviconFroms_DMO").prepend('<li class="icon-ok"></i>');
        if ($('#ddlForms_DMO').val() == "0")
        {
            $("#diviconFroms_DMO li:first").hasClass("icon-ok");
            $("#diviconFroms_DMO li:first").remove();
        }
    });
    /****************************************************************/

    /*  $("#btnTDMPrint").click(function() {
     PrintTDM();
     }); */
    /* $("#btnDailyMealOrders").click(function() {
     PrintDailyMealOrders();
     });
     */
    $('#ddlForm_TDM').on('change', function() {
        if (!$("#diviconApps li:first").hasClass("icon-ok"))
            $("#diviconApps").prepend('<li class="icon-ok"></i>');
        if ($('#ddlForm_TDM').val() == "0")
        {
            $("#diviconApps li:first").hasClass("icon-ok");
            $("#diviconApps li:first").remove();
        }
    });
    $("#btnFilter_TDM").unbind("click").bind("click", SrvshowTDMForms);
    // $("#btnManageExceptionSubmit").bind("click", manageExceptionSubmit);
    //$("#btnLateNumSubmit").bind("click",lateNumbersSubmit);
    $("#frmLateNumbers").validate({
        errorClass: "help-inline",
        errorElement: "span",
        highlight: function(element, errorClass, validClass) {
            $(element).parents('.control-group').addClass('warning');
        },
        unhighlight: function(element, errorClass, validClass) {
            $(element).parents('.control-group').removeClass('warning');
        }
    });
    $("#btnDMOFilter").click(function(){
	$("#btnDMORefresh").removeClass('hide');
	$("#accReportsFilter2").collapse('hide');
     setTimeout(function() {
        $("#accReportsForms2").collapse('show');
        $("#divRefresh").show();
    }, 100); 
	Srv_showDMOReport();
	})
	$("#btnDMORefresh").click(function(){
	$("#spnDMORefresh").removeClass('hide');
	localStorage.setItem("userrefreshbutton_DMO", "true");
	Srv_showDMOReport()
	});	
	 $("#ctfusercheckbox").change(function() {    
        if (this.checked) {         
			$('#countdown_user_dmo').show();
			localStorage.setItem("userautorefresh_DMO", "true");
			AutoRefreshDMO();          
        }
        else
        {
			$("#countdown_user_dmo span").countdown('destroy');
			$('#countdown_user_dmo').hide();
            localStorage.setItem("userautorefresh_DMO", "false");
            AutoRefreshDMO();
        }                
    });
}
function AutoRefreshDMO(){
 var autorefresh = localStorage.getItem('userautorefresh_DMO');    
    if (autorefresh == 'true') {
        //interval set to 5 minutes
        var interval = 1000 * 60 * 5; // where X is your every X minutes
		$("#countdown_user_dmo span").countdown({until: 300, format: 'MS', compact: true});
        if (!autoRefreshIntervalID) {
            autoRefreshIntervalID = window.setInterval(function() {
                if (localStorage["userautorefresh_DMO"] == "true") {                   
						Srv_showDMOReport();
						$("#countdown_user_dmo span").countdown('option', {until: +300}); 

                }
            }, interval);
        }
    } else {
        if (autoRefreshIntervalID) {
            window.clearInterval(autoRefreshIntervalID);
            autoRefreshIntervalID = null;
        }
    }
}
$.validator.addMethod("highlight", function(value, element) {
    higlight = true;
    var newValueID = $(element).attr("id").replace("newLateValue", "orig")
    var newValue = $("#" + newValueID).html();
    if (value != newValue) {
        return false;
    }
    return true;
}, "");
function persistentheaders()
{
    var clonedHeaderRow;
    $(".persist-area").each(function() {
        clonedHeaderRow = $(".persist-header", this);
        if (!$(".persist-header", this).hasClass("floatingHeader"))
        {
            clonedHeaderRow
                    .before(clonedHeaderRow.clone())
                    .css("width", 100)//clonedHeaderRow.width()
                    .addClass("floatingHeader");
        }
    });

    $(window)
            .scroll(UpdateTableHeaders)
            .trigger("scroll");
}
function UpdateTableHeaders() {
    $(".persist-area").each(function() {

        var el = $(this),
                offset = el.offset(),
                scrollTop = $(window).scrollTop(),
                floatingHeader = $(".floatingHeader", this)
        persistheader = $(".persist-header th", this)

        if ((scrollTop > offset.top) && (scrollTop < offset.top + el.height())) {
            floatingHeader.css({
                "visibility": "visible",
                'width': $("table[id^=tblTDMReport] tr").width(),
                'overflow': 'hidden'
            });
            persistheader.css({
                'width': $("table[id^=tblTDMReport] th").width(),
            });
        } else {
            floatingHeader.css({
                "visibility": "hidden",
                'width': '75%',
                'overflow': 'hidden'
            });
        }
        ;
    });
}

var page = 1;
var digitalpenvar = "", digitalformvar = "", digitalwardvar = "", digitalDOWvar = "", digitalmenutypevar = "";
var pens = "", form = "", wards = "", DOW = "", menutype = "";
var lateDetails = [];
var curr;
var isFilterClick;
var isExclude;
function HideErrMsg() {
    $("#divUserPensErrMsg").hide();
    $("#divUserFormsErrMsg").hide();
    $("#divUserWardsErrMsg").hide();
    $("#divUserDOWErrMsg").hide();
    $("#divUserMenuTypeErrMsg").hide();
}
function DateFunction() {
    var today = new Date();
    var t = today.getDate() + "/" + (today.getMonth() + 1) + "/" + today.getFullYear();
    $("#dtDigitalFilterStart").datepicker({endDate: new Date()}).on('changeDate', function(ev) {
        $('#dtDigitalFilterStart').datepicker('hide');
    });
    $("#txtDigitalFilterStart").val(t);
    $('#dtDigitalFilterStart').data({
        date: t
    }).datepicker('update');
    $("#dtDigitalFilterEnd").datepicker({endDate: new Date()}).on('changeDate', function(ev) {
        $('#dtDigitalFilterEnd').datepicker('hide');
    });
    ;
    $("#txtDigitalFilterEnd").val(t);
    $('#dtDigitalFilterEnd').data({
        date: t
    }).datepicker('update');

}
function showCount() {
	$("#accFilter").collapse('hide');
    $("#divRefresh1").show();
    srv_showDFForms(page);
}
function showTDMReport2(f) {
    $("#divTDMReportHeading2").show();
    $("#tblTDMReport2").show();
    $("#divTDMCustom2").show();
    $("#divTDMButtons2").show();
    $(f).html('Refresh');
    return false;
}

function showDFFilter() {
    $("#accForms").collapse('hide');
    /*  setTimeout(function() {
     $("#accFilter").collapse('show');
     }, 500);  */
    /* SrvLoadDigitalforms(); */
}
function showTDMFilter() {
    $("#accReportsForms").collapse('hide');
    /*  $("#accReportsFilter").collapse('show'); */
    /*  setTimeout(function() {
     $("#accReportsForms").collapse('show');
     }, 500); */
}
function showDForms() {
$("#accFilter").collapse('hide');
        setTimeout(function() {
            $("#accForms").collapse('show');
            $("#divRefresh1").show();
        }, 200);
    isFilterClick = true;
    srv_showDFForms(page)
}

function srv_showDFForms(page) {
    var digitalpenvar = "", digitalformvar = "", digitalwardvar = "", digitalDOWvar = "", digitalmenutypevar = "";
    pens = "", form = "", wards = "", DOW = "", menutype = "";
    $("#lstSelectedPens tr").each(function(e) {
        digitalpenvar = digitalpenvar + $(this).attr("id") + ",";
        pens = digitalpenvar.replace(/^,|,$/g, '');

    });
    $("#lstSelectedForm tr").each(function(e) {
        digitalformvar = digitalformvar + $(this).attr("id") + ",";
        form = digitalformvar.replace(/^,|,$/g, '');
    });
    $("#lstSelectedWards tr").each(function(e) {
        digitalwardvar = digitalwardvar + $(this).attr("id") + ",";
        wards = digitalwardvar.replace(/^,|,$/g, '');
    });
    $("#lstSelectedDOW tr").each(function(e) {
        digitalDOWvar = digitalDOWvar + $(this).attr("id") + ",";
        DOW = digitalDOWvar.replace(/^,|,$/g, '');
    });
    $("#lstSelectedMenuType tr").each(function(e) {
        digitalmenutypevar = digitalmenutypevar + $(this).attr("id") + ",";
        menutype = digitalmenutypevar.replace(/^,|,$/g, '');
    });
    var startdate = $("#txtDigitalFilterStart").val().split("/");
    var enddate = $("#txtDigitalFilterEnd").val().split("/");
    var url = BACKENDURL + "user/digital_form_filter";
    var data = {
        session_id: localStorage["SESSIONID"],
        start_date: startdate[2] + "-" + startdate[1] + "-" + startdate[0],
        end_date: enddate[2] + "-" + enddate[1] + "-" + enddate[0],
        ft: form,
        p: pens,
        w: wards,
        dw: DOW,
        mt: menutype,
        isex: $("#chkSEFD").is(":checked") ? 1 : 0,
        page_no: page,
        custom: '0'
    };
    var formStartDate = (new Date(startdate[2], (startdate[1] - 1), startdate[0])).valueOf();
    var formEndDate = (new Date(enddate[2], (enddate[1] - 1), enddate[0])).valueOf();
    if (formEndDate < formStartDate)
        $("#divFilterErr").html('The end date can not be earlier than the start date').removeClass('hide')
    else {
        $("#divFilterErr").addClass('hide');
        
        MakeAjaxCall(url, data, showDFForms);
    }
}
function showDFForms(data) {
    var formCount = data.df.tc;
    $("#divErrForms").hide();
    $("#lblFormsCount").html("Total " + formCount + " Forms available");
    if (data.error == 0) {
        $("#tblForms  tbody:last").empty();
        var tblForms = "";
        if (data.df.fd.length > 0) {
            for (var nCount = 0; nCount < data.df.fd.length; nCount++) {
                var appLabel = (data.df.fd[nCount].al == "") ? '-' : data.df.fd[nCount].al;
                var penLabel = (data.df.fd[nCount].pl == "") ? '-' : data.df.fd[nCount].pl;
                var ward = (data.df.fd[nCount].w == "") ? '-' : data.df.fd[nCount].w;
                var dayOfWeek = (data.df.fd[nCount].dw == "") ? '-' : data.df.fd[nCount].dw;
                var notes = (data.df.fd[nCount].ex == "-") ? "" : data.df.fd[nCount].ex;
                var viewFormDisplay = (data.df.fd[nCount].fx == 0) ? "hide" : "";
                var lateNumDisplay = ((data.df.fd[nCount].ft == "L") || (data.df.fd[nCount].ft == "D")) ? "" : "hide";
                var lateNumMsgDisplay = (data.df.fd[nCount].ln == 0) ? " " : "icon-time";
                var notesDisplay = "";
                var latenumbMsg = (lateNumMsgDisplay == "icon-time") ? "This form has late change numbers." : " ";
                var br = (latenumbMsg == " ") ? " " : "<br>";
                if (notes == data.df.fd[nCount].ex) {
                    if (data.df.fd[nCount].es == 2)
                        notesDisplay = "icon-ok-circle";
                    else if (data.df.fd[nCount].es == 1)
                        notesDisplay = "icon-warning-sign";
                    else
                        notesDisplay = "";
                }
                if ((latenumbMsg == " ") && (notes == "")) {
                    latenumbMsg = '-';
                }
                tblForms += "<tr><td data-title='Date'>" + data.df.fd[nCount].d + "</td>"
                tblForms += "<td data-title='App label'>" + appLabel + "</td>";
                tblForms += "<td data-title='Pen label'>" + penLabel + "</td>";
                tblForms += "<td data-title='Ward'>" + ward + "</td>";
                tblForms += "<td data-title='Day of week'>" + dayOfWeek + "</td>";
                tblForms += "<td data-title='Notes'><i class='" + lateNumMsgDisplay + "'></i> " + latenumbMsg + br + "<i class='" + notesDisplay + "'></i> " + notes + "</td>";
                tblForms += "<td class='alignright'><div class='btn-group'><button data-toggle='dropdown' class='btn btn-default dropdown-toggle' type='button'> Action <span class='caret'></span></button>";
                tblForms += "<ul role='menu' class='dropdown-menu'>";
                tblForms += "<li class=" + viewFormDisplay + "><a onclick='javascript:SrvViewDigitalForm(" + data.df.fd[nCount].dfid + ");' href='javascript:void(0);'><i class='icon-eye-open'></i>  View form</a></li>";
                tblForms += "<li class=''> <a href='javascript:void(0);' onClick='downloadFormXml(" + data.df.fd[nCount].dfid + ")'><i class='icon-download-alt'></i> Download XML</a></li>";
                tblForms += "<li class=" + lateNumDisplay + "> <a href='javascript:void(0);'  onClick='lateNumbers(" + data.df.fd[nCount].dfid + ",\"" + data.df.fd[nCount].ft + "\")'> <i class='icon-time'></i> Late Numbers</a></li>";
                tblForms += "<li class='divider'></li><li><a href='javascript:void(0);'  onclick='manageException(" + data.df.fd[nCount].dfid + ",\"" + data.df.fd[nCount].ft + "\")'><i class='icon-flag'></i> Manage Exception</a></li></ul></div></td>";

            }
            $("#tblForms tbody:last").append(tblForms);
            $("#tblForms").show();
            $("#divExportExcel").show();
            $("#divActionHeight").show();
            if (data.df.tc > 0) {
                var totalRecords = data.df.tc;
                var total_page = Math.ceil(totalRecords / 50);
                var no_pages = total_page > 2 ? 2 : 1;
                if (totalRecords > 50) {
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
                            curr = page;
                            srv_showDFForms(page)
                        }
                    }
                    if ((isFilterClick == true) || (isExclude == true))
                        options.currentPage = 1;
                    $("#divForms_pag").bootstrapPaginator(options);
                    $("#divForms_pag").show();

                }
                else
                {
                    $("#divForms_pag").hide();
                }
                isFilterClick = false;
                isExclude = false;
            }
        }
        else {
            $("#tblForms").hide();
            $("#divErrForms").html('No Records found').show();
            $("#divExportExcel").hide();
            $("#divForms_pag").hide();
            $("#divActionHeight").hide();
        }
    }
}
function getValues_DMO()
{
    var digitalpenvar_DMO = "", digitalformvar_DMO = "", digitalwardvar_DMO = "", digitalDOWvar_DMO = "", digitalmenutypevar_DMO = "";
    var pens_DMO = "", form_DMO = "", wards_DMO = "", DOW_DMO = "", menutype_DMO = "";
    var aForm_DMO = [], sForm_DMO = [], sP_DMO = [], aP_DMO = [], aW_DMO = [], aDOW_DMO = [], aMT_DMO = [], sW_DMO = [], sDOW_DMO = [], sMT_DMO = [], sPCount = 0, sWCount = 0, sMTCount = 0, sDOWCount = 0, aPCount = 0, aWCount = 0, aMTCount = 0, aDOWCount = 0, aFCount = 0, sFCount = 0;
    $("#lstSelectedPens_DMO tr").each(function(e) {
        var dataSp = new Object();
        dataSp.id = $(this).attr("id");
        dataSp.name = $(this).text();
        sP_DMO[sPCount] = dataSp;
        sPCount++;
        digitalpenvar_DMO = digitalpenvar_DMO + $(this).attr("id") + ",";
        pens_DMO = digitalpenvar_DMO.replace(/^,|,$/g, '');
    });
    $("#lstSelectedWards_DMO tr").each(function(e) {
        var dataSp = new Object();
        dataSp.id = $(this).attr("id");
        dataSp.name = $(this).text();
        sW_DMO[sWCount] = dataSp;
        sWCount++;
        digitalwardvar_DMO = digitalwardvar_DMO + $(this).attr("id") + ",";
        wards_DMO = digitalwardvar_DMO.replace(/^,|,$/g, '');
    });
    $("#lstSelectedDOW_DMO tr").each(function(e) {
        var dataSp = new Object();
        dataSp.id = $(this).attr("id");
        dataSp.name = $(this).text();
        sDOW_DMO[sDOWCount] = dataSp;
        sDOWCount++;
        digitalDOWvar_DMO = digitalDOWvar_DMO + $(this).attr("id") + ",";
        DOW_DMO = digitalDOWvar_DMO.replace(/^,|,$/g, '');
    });
    $("#lstSelectedMenuTypes_DMO tr").each(function(e) {
        var dataSp = new Object();
        dataSp.id = $(this).attr("id");
        dataSp.name = $(this).text();
        sMT_DMO[sMTCount] = dataSp;
        sMTCount++;
        digitalmenutypevar_DMO = digitalmenutypevar_DMO + $(this).attr("id") + ",";
        menutype_DMO = digitalmenutypevar_DMO.replace(/^,|,$/g, '');
    });
    $("#lstAvailablePens_DMO tr").each(function(e) {
        var dataSp = new Object();
        dataSp.id = $(this).attr("id");
        dataSp.name = $(this).text();
        aP_DMO[aPCount] = dataSp;
        aPCount++;
    });
    $("#lstAvailableWards_DMO tr").each(function(e) {
        var dataSp = new Object();
        dataSp.id = $(this).attr("id");
        dataSp.name = $(this).text();
        aW_DMO[aWCount] = dataSp;
        aWCount++;
    });
    $("#lstAvailableDOW_DMO tr").each(function(e) {
        var dataSp = new Object();
        dataSp.id = $(this).attr("id");
        dataSp.name = $(this).text();
        aDOW_DMO[aDOWCount] = dataSp;
        aDOWCount++;
    });
    $("#lstAvailableMenuType_DMO tr").each(function(e) {
        var dataSp = new Object();
        dataSp.id = $(this).attr("id");
        dataSp.name = $(this).text();
        aMT_DMO[aMTCount] = dataSp;
        aMTCount++;
    });
    $('#ddlForms_DMO option:not(:selected)').each(function(e) {
        var dataSp = new Object();
        dataSp.id = $(this).val();
        dataSp.name = $(this).text();
        aForm_DMO[aFCount] = dataSp;
        aFCount++;
    });
    $('#ddlForms_DMO option:selected').each(function(e) {
        var dataSp = new Object();
        dataSp.id = $(this).val();
        dataSp.name = $(this).text();
        sForm_DMO[sFCount] = dataSp;
        sFCount++;
    });
    json_DMO = [];
    json_DMO["Avl_Forms"] = aForm_DMO;
    json_DMO["Sel_Forms"] = sForm_DMO;
    json_DMO["Avl_Pens"] = aP_DMO;
    json_DMO["Sel_Pens"] = sP_DMO;
    json_DMO["Avl_Wards"] = aW_DMO;
    json_DMO["Sel_Wards"] = sW_DMO;
    json_DMO["Avl_DOW"] = aDOW_DMO;
    json_DMO["Sel_DOW"] = sDOW_DMO;
    json_DMO["Avl_MT"] = aMT_DMO;
    json_DMO["Sel_MT"] = sMT_DMO;
    json_TDM["Start_DMO"] = $("#txtDate_DMO").val();
    json_TDM["End_DMO"] = $("#txtDate_DMO").val();
    /*  json_DMO["Date_TDM"] = $("#txtDate_DMO").val(); */
    json_DMO["Init_Wards"] = initWards_DMO;
    return [pens_DMO, wards_DMO, DOW_DMO, menutype_DMO];
}
function Srv_showDMOReport() {

    var vales_DMO = getValues_DMO();
    var startdate_DMO = $("#txtDate_DMO").val().split("/");
    var url = BACKENDURL + "user/get_daily_meal_orders";
    var data = {
        session_id: localStorage["SESSIONID"],
        start_date: startdate_DMO[2] + "-" + startdate_DMO[1] + "-" + startdate_DMO[0],
        ft: $('#ddlForms_DMO').val(),
        p: vales_DMO[0],
        w: vales_DMO[1],
        dw: vales_DMO[2],
        mt: vales_DMO[3],
    };
    MakeAjaxCall(url, data, showDMOReport);	 
}


function showDMOReport(data)
{
$("#spnDMORefresh").addClass('hide');
if(data.df.rt !=undefined){
    var WardValues, WardHighlight = [];
	var date_tdm = $("#txtDate_DMO").val().split("/");
    /* var date_len = data.df.dm.length; */
    var tbl_DMOlogs = "";
	
    var total_DMOlogs = "<tr class='totalColumn'><td><b>Total</b></td>";
    var DateArr_DMO = $("#txtDate_DMO").val().split("/");
    var startDate_DMO = DateArr_DMO[2] + "/" + DateArr_DMO[1] + "/" + DateArr_DMO[0];
    var header_DMOlogs = "<tr class='wardName'><th style='text-align:center;'><div class='rotateText hide'><a href='javascript:navigateToDigitalForms_DMO();'>Days</a></div></th>";
    $("#lbl_DMOHeading1").html(""+$('#ddlForms_DMO option:selected').text()+" orders " + convertDate(startDate_DMO) + " " + DateArr_DMO[2]);
    $("#lbl_DMOHeading2").html("Report time: " + data.df.rt[0].rt);
    $("#tblDMOReport tbody tr").remove();
    $("#tblDMOReport thead tr").remove();
    $("#divErrForms_DMO").hide();
	 if (data.df.dm.length > 0)
	 {
	 $("#lblUserZonechkbx").removeClass('hide');
	 $("#divDMOButtons2").removeClass('hide');
	 $("#divDMOReportHeading").removeClass('hide');
	 $("#divDMOCustom2").removeClass('hide');         
    if (data.df.wln.length > 0)
    {
        for (var jCount = 0; jCount < data.df.wln.length; jCount++)
		
        header_DMOlogs += "<th style='text-align:center;'><div class='rotateText'><a href='javascript:navigateToDigitalForms_DMO(" + data.df.wln[jCount].wid + "," + date_tdm[2] + "," + date_tdm[1] + "," + date_tdm[0] + ",null,0" + ");'>" + data.df.wln[jCount].wn + "</a></div></th>";
        header_DMOlogs += "<th style='text-align:center;'><div class='rotateText'>Total</div></th>";
        header_DMOlogs += "</tr>";
        $("#tblDMOReport  thead:last").append(header_DMOlogs);
        for (var nCount = 0; nCount < data.df.dm.length; nCount++)
        {
           
            tbl_DMOlogs += "<tr><td class='menuDesc'><a href=javascript:navigateToDigitalForms_DMO(null," + date_tdm[2] + "," + date_tdm[1] + "," + date_tdm[0] + ",null,0"+");>" + data.df.dm[nCount][0] + "</a></td>";
            var sum = 0, sumCount = 0;
            for (var jCount = 0; jCount < data.df.wln.length; jCount++) {
                var s = "";

                if (data.df.ln[nCount] != undefined && data.df.ln[nCount][data.df.wln[jCount].wid] != undefined)
                    s = "background-color: rgb(255, 255, 0);";
                if (data.df.mr[nCount] != undefined && data.df.mr[nCount][data.df.wln[jCount].wid] != undefined)
                    s = "background-color: red;";
                WardValues = (data.df.dm[nCount][data.df.wln[jCount].wid] == undefined) ? "0" : data.df.dm[nCount][data.df.wln[jCount].wid];
                tbl_DMOlogs += "<td style='" + s + " text-align:center;' data-title=" + data.df.wln[jCount].wn + "><a href=javascript:navigateToDigitalForms_DMO(" + data.df.wln[jCount].wid + "," + date_tdm[2] + "," + date_tdm[1] + "," + date_tdm[0] + ",null,0"+");>" + WardValues + "</a></td>";
                sum = parseInt(sum) + parseInt(WardValues); 
            }
            tbl_DMOlogs += "<td style='" + s + " text-align:center;'>" + sum + "</td>";
            tbl_DMOlogs += "</tr>";
            $("#tblDMOReport  tbody:last").append(tbl_DMOlogs);
            $("#tblDMOReport").show();
            tbl_DMOlogs = "";
        }
        
        var tmp_len = parseInt(data.df.wln.length) + 1;
        for (var jCount = 0; jCount < tmp_len; jCount++) {
            var index = jCount + 1;
            sum = 0;
            $('#tblDMOReport tbody tr').each(function() {
                sum += parseInt($.trim($('td:eq(' + index + ')', this).text()), 10);
            });
            total_DMOlogs += "<td style='text-align:center;'>" + sum + "</td>";
             if (sum == 0) {
                    WardHighlight[sumCount] = jCount;
                    sumCount++;
             }
        }
        total_DMOlogs += "<td style='text-align:center;' data-title='Total'></td>";
        total_DMOlogs += "</tr>";
        $("#tblDMOReport  tbody:last").append(total_DMOlogs);
        $("#tblDMOReport").show();
        for(var colPosition = 0; colPosition < WardHighlight.length; colPosition++) {
            columnIndex = WardHighlight[colPosition]+2;
            $("#tblDMOReport  thead tr th:nth-child("+columnIndex+")").css('background-color','red');
        }
        /* $("#divTDMReportHeading").show();
         $("#tblDMOReport").show(); */
       /*  if (data.df.cus_cnt != "null")
        {
            $("#lblCustOrders_DMO").html(data.df.cus_cnt);
            $("#divTDMCustom2").show();
            $("#divTDMButtons2").show();
        }
        else
        {
            $("#divTDMButtons2").show();
        } */
		if (data.df.cus_cnt != "null")
        {
            $("#lblCustOrders_DMO").html(data.df.cus_cnt);
            $("#divDMOCustom2").show();
            $("#divDMOButtons2").show();
        }
        else
        {
            $("#divDMOButtons2").show();
        }
		if($('#ddlForms_DMO option:selected').text()=="Ad-hoc")		
		 $("#divDMOCustom2").hide();		
		else
		 $("#divDMOCustom2").show();	
		

    }
    else
     {
     $("#divErrForms_DMO").html('No Records found').show();     
	 $("#lblUserZonechkbx").addClass('hide');
	 $("#divDMOButtons2").addClass('hide');
	 $("#divDMOReportHeading").addClass('hide');
	 $("#divDMOCustom2").addClass('hide');
     }
	 }
	 else
     {
     $("#divErrForms_DMO").html('No Records found').show();
     $("#lblUserZonechkbx").addClass('hide');
	 $("#divDMOButtons2").addClass('hide');
	 $("#divDMOReportHeading").addClass('hide');
	 $("#divDMOCustom2").addClass('hide');
     }
	 }
	 else
     {
     $("#divErrForms_DMO").html('No Records found').show();
     $("#lblUserZonechkbx").addClass('hide');
	 $("#divDMOButtons2").addClass('hide');
	 $("#divDMOReportHeading").addClass('hide');
	 $("#divDMOCustom2").addClass('hide');
     }
    
}
 function SrvshowDMOReport() {
 $("#btnDMORefresh").removeClass('hide');
 $("#accReportsFilter2").collapse('hide');
 Srv_showDMOReport();
 
 } 
function navigateToDigitalForms_DMO(Ward_id, dmo_y, dmo_m, dmo_d,menudescription,custom) {

    var vales_DMO = getValues_DMO();
    var form_type = "";
    var date_similar = 0;
    if (Ward_id != null)
    {
        vales_DMO[1] = Ward_id;
    }
    if (dmo_y == null)
    {
        var startdate_DMO = $("#txtDate_DMO").val().split("/");
        var startdate_DMO = startdate_DMO[2] + "-" + startdate_DMO[1] + "-" + startdate_DMO[0];
        var enddate_DMO = $("#txtDate_DMO").val().split("/");
        var enddate_DMO = enddate_DMO[2] + "-" + enddate_DMO[1] + "-" + enddate_DMO[0];
    }
    else
    {
        var startdate_DMO = dmo_y + "-" + dmo_m + "-" + dmo_d;
        var enddate_DMO = dmo_y + "-" + dmo_m + "-" + dmo_d;
        date_similar = dmo_d + "/" + dmo_m + "/" + dmo_y;
    }
    form_type = $("#ddlForms_DMO").val();
    $('#widgetnav a[href="#pcatDigitalForms"]').tab('show');
    $("#divFilterErr").addClass('hide');
    $("#accFilter").collapse('hide');
    setTimeout(function() {
        $("#accForms").collapse('show');
        $("#divRefresh1").show();
    }, 200);
    navigateToDigitalFilters_DMO(date_similar, Ward_id);
    var url = BACKENDURL + "user/digital_form_filter";
    var data = {
        session_id: localStorage["SESSIONID"],
        start_date: startdate_DMO,
        end_date: enddate_DMO,
        ft: form_type,
        p: vales_DMO[0],
        w: vales_DMO[1],
        dw: vales_DMO[2],
        mt: vales_DMO[3],
        custom: custom,
        //isex: $("#chk_TDM").is(":checked") ? 1 : 0,
        page_no: 1,
    };
    MakeAjaxCall(url, data, showDFForms);
}

function navigateToDigitalFilters_DMO(dt_s, sl_ward) {
    //$("#tabPCDigitalForms").click();
    //setTimeout(function() {
    $("#lstAvailableForm > tbody > tr").remove();
    $("#lstSelectedForm > tbody > tr").remove();
    $("#lstAvailablePens > tbody > tr").remove();
    $("#lstAvailableWards > tbody > tr").remove();
    $("#lstAvailableDOW > tbody > tr").remove();
    $("#lstAvailableMenutype > tbody > tr").remove();
    $("#lstSelectedPens > tbody > tr").remove();
    $("#lstSelectedWards > tbody > tr").remove();
    $("#lstSelectedDOW > tbody > tr").remove();
    $("#lstSelectedMenuType > tbody > tr").remove();
    var availablePens = " ", selectedPens = " ", availableWards = "", selectedWards = "", availableDOW = "", selectedDOW = "", availableMT = "", selectedMT = "", availableforms = "", selectedforms = "";
    var availableWards = "<tr id='0' onClick='addRemoveHl(this);'><td style='border:1px solid #d3d3d3'>No Ward</td></tr>";
    /*for(var nCount = 0; nCount < json_TDM.Avl_Forms.length; nCount++){
     if(json_TDM.Sel_Forms[nCount].id == "0")
     {
     availableforms += "<tr id='3' onClick='addRemoveHl(this);'><td style='border:1px solid #d3d3d3'>Adhoc</td></tr><tr id='4' onClick='addRemoveHl(this);'><td style='border:1px solid #d3d3d3'>Other</td></tr>";
     if(json_TDM.Avl_Forms[nCount].id != "0")
     {
     availableforms += "<tr id='" + json_TDM.Avl_Forms[nCount].id + "' onClick='addRemoveHl(this);'><td style='border:1px solid #d3d3d3'>" + json_TDM.Avl_Forms[nCount].name + "</td></tr>";
     }
     }
     
     }*/
    //for(var nCount = 0; nCount < json_TDM.Sel_Forms.length; nCount++){      
    selectedforms += "<tr id='" + json_DMO.Sel_Forms[0].id + "' onClick='addRemoveHl(this);'><td style='border:1px solid #d3d3d3'>" + json_DMO.Sel_Forms[0].name + "</td></tr>";
    for (var nCount = 0; nCount < json_DMO.Avl_Forms.length; nCount++) {
        if (json_DMO.Avl_Forms[nCount].id != "0")
        {
            availableforms += "<tr id='" + json_DMO.Avl_Forms[nCount].id + "' onClick='addRemoveHl(this);'><td style='border:1px solid #d3d3d3'>" + json_DMO.Avl_Forms[nCount].name + "</td></tr>";
        }
    }
    //}
    for (var nCount = 0; nCount < json_DMO.Avl_Pens.length; nCount++) {
        availablePens += "<tr id='" + json_DMO.Avl_Pens[nCount].id + "' onClick='addRemoveHl(this);'><td style='border:1px solid #d3d3d3'>" + json_DMO.Avl_Pens[nCount].name + "</td></tr>";
    }
    for (var nCount = 0; nCount < json_DMO.Sel_Pens.length; nCount++) {
        selectedPens += "<tr id='" + json_DMO.Sel_Pens[nCount].id + "'  onClick='addRemoveHl(this);'><td style='border:1px solid #d3d3d3'>" + json_DMO.Sel_Pens[nCount].name + "</td></tr>";
    }
    if (sl_ward == null)
    {
        for (var nCount = 0; nCount < json_DMO.Avl_Wards.length; nCount++) {
            availableWards += "<tr id='" + json_DMO.Avl_Wards[nCount].id + "'  onClick='addRemoveHl(this);'><td style='border:1px solid #d3d3d3'>" + json_DMO.Avl_Wards[nCount].name + "</td></tr>";
        }
        for (var nCount = 0; nCount < json_DMO.Sel_Wards.length; nCount++) {
            selectedWards += "<tr id='" + json_DMO.Sel_Wards[nCount].id + "'  onClick='addRemoveHl(this);'><td style='border:1px solid #d3d3d3'>" + json_DMO.Sel_Wards[nCount].name + "</td></tr>";
        }
    }
    else
    {
        var ward_nm = "";
        for (var nCount = 0; nCount < json_DMO.Init_Wards.length; nCount++) {
            if (json_DMO.Init_Wards[nCount].id != sl_ward)
            {
                availableWards += "<tr id='" + json_DMO.Init_Wards[nCount].id + "'  onClick='addRemoveHl(this);'><td style='border:1px solid #d3d3d3'>" + json_DMO.Init_Wards[nCount].name + "</td></tr>";
            }
            else
            {
                ward_nm = json_DMO.Init_Wards[nCount].name;
            }
        }
        selectedWards += "<tr id='" + sl_ward + "'  onClick='addRemoveHl(this);'><td style='border:1px solid #d3d3d3'>" + ward_nm + "</td></tr>";

    }
    for (var nCount = 0; nCount < json_DMO.Avl_DOW.length; nCount++) {
        availableDOW += "<tr id='" + json_DMO.Avl_DOW[nCount].id + "' onClick='addRemoveHl(this);'><td style='border:1px solid #d3d3d3'>" + json_DMO.Avl_DOW[nCount].name + "</td></tr>";
    }
    for (var nCount = 0; nCount < json_DMO.Sel_DOW.length; nCount++) {
        selectedDOW += "<tr id='" + json_DMO.Sel_DOW[nCount].id + "' onClick='addRemoveHl(this);'><td style='border:1px solid #d3d3d3'>" + json_DMO.Sel_DOW[nCount].name + "</td></tr>";
    }
    for (var nCount = 0; nCount < json_DMO.Avl_MT.length; nCount++) {
        availableMT += "<tr id='" + json_DMO.Avl_MT[nCount].id + "' onClick='addRemoveHl(this);'><td style='border:1px solid #d3d3d3'>" + json_DMO.Avl_MT[nCount].name + "</td></tr>";
    }
    for (var nCount = 0; nCount < json_DMO.Sel_MT.length; nCount++) {
        selectedMT += "<tr id='" + json_DMO.Sel_MT[nCount].id + "' onClick='addRemoveHl(this);'><td style='border:1px solid #d3d3d3'>" + json_DMO.Sel_MT[nCount].name + "</td></tr>";
    }
    availableforms += "<tr id='4' onClick='addRemoveHl(this);'><td style='border:1px solid #d3d3d3'>Other</td></tr>"
    $("#lstAvailableForm  tbody:last").append(availableforms);
    $("#lstSelectedForm tbody:last").append(selectedforms);
    $("#lstAvailablePens  tbody:last").append(availablePens);
    $("#lstSelectedPens  tbody:last").append(selectedPens);
    $("#lstAvailableWards  tbody:last").append(availableWards);
    $("#lstSelectedWards  tbody:last").append(selectedWards);
    $("#lstAvailableDOW  tbody:last").append(availableDOW);
    $("#lstSelectedDOW  tbody:last").append(selectedDOW);
    $("#lstAvailableMenutype  tbody:last").append(availableMT);
    $("#lstSelectedMenuType  tbody:last").append(selectedMT);
    $("#chkSEFD", "#accFilter").attr('checked', 'checked');
    if (dt_s == "0")
    {
        $("#txtDigitalFilterStart").val(json_DMO.Start_DMO);
        $("#txtDigitalFilterEnd").val(json_DMO.End_DMO);
        $('#dtDigitalFilterStart').data({
            date: json_DMO.Start_DMO
        }).datepicker('update');
        $('#dtDigitalFilterEnd').data({
            date: json_DMO.End_DMO
        }).datepicker('update');
    }
    else
    {
        $("#txtDigitalFilterStart").val(dt_s);
        $("#txtDigitalFilterEnd").val(dt_s);
        $('#dtDigitalFilterStart').data({
            date: dt_s
        }).datepicker('update');
        $('#dtDigitalFilterEnd').data({
            date: dt_s
        }).datepicker('update');
    }
    //$("#accFilter").collapse('hide');
    //$("#btnFilter").click();
    //$("#accForms").collapse('show');
    //}, 200);
}

function showDMOFilter() {
    $("#accReportsForms2").collapse('hide');
    /*  setTimeout(function() {
     $("#accReportsFilter2").collapse('show');
     
     }, 500); */
}
function showRefresh() {

    $("#divRefresh").show();
}

var is_child_loaded = false;
var wndDigitalForm = null;

function SrvViewDigitalForm(digitalFormID) {
    wndDigitalForm = window.open();
    var url = BACKENDURL + "user/view_digital_form";
    var data = {
        session_id: localStorage["SESSIONID"],
        dfid: digitalFormID
    };
    MakeAjaxCall(url, data, viewForm);
}

function viewForm(data) {
    is_child_loaded = false;

    //wndDigitalForm.location.href = "1.html";	For localhost
    wndDigitalForm.location.href = data.link;	//For QA

    $(wndDigitalForm).ready(function() {
        is_child_loaded = true;
    });
    //timeout set to 1000 for debugging issue - form not loading in IE 
    setTimeout(loadChildForm, 1000, wndDigitalForm, data.xml_data);
}

function loadChildForm(w, xml_string) {
    if (is_child_loaded) {
        $($.parseXML(xml_string)).find("page").children().each(function() {
            var tagname = this.tagName;
            var val = $(this).text();
            var ctl = w.document.getElementById(tagname);
            if (ctl != null) {

                if (ctl.type == "text")
                    ctl.value = val;
                else
                    ctl.innerHTML = val;

            } else {
                //For checkboxes
                var chk_ctl = w.document.getElementById(tagname + "_" + val.replace(" ", "_").replace("/", "_"));
                if (chk_ctl != null && chk_ctl.type == "checkbox")
                    chk_ctl.checked = true;
            }
        });

    } else {
        setTimeout(loadChildForm, 100, w, xml_string);
    }
    //window.open('digital_form1.html');
}

function digitalFormsClick() {
 $("#accForms").collapse('hide');
        setTimeout(function() {
           $("#accFilter").collapse('show');
            $("#divRefresh1").show();
        }, 200);
    SrvLoadDigitalforms();
    DateFunction();
    changeStartDate();
    changeEndDate();
}
function SrvLoadDigitalforms() {
    var url = BACKENDURL + "user/digital_form_load";
    var data = {
        session_id: localStorage["SESSIONID"],
        type: 'df'
    };
    MakeAjaxCall(url, data, loadDigitalFormFilter);
}
function loadDigitalFormFilter(data) {
    //customerModuleAccess('AL3ADM', 0);
    if (data.session_status) {
        if (data.error == 0) {
            $("#lstAvailableForm > tbody > tr").remove();
            $("#lstSelectedForm > tbody > tr").remove();
            $("#lstAvailablePens > tbody > tr").remove();
            $("#lstAvailableWards > tbody > tr").remove();
            $("#lstAvailableDOW > tbody > tr").remove();
            $("#lstAvailableMenutype > tbody > tr").remove();
            $("#lstSelectedPens > tbody > tr").remove();
            $("#lstSelectedWards > tbody > tr").remove();
            $("#lstSelectedDOW > tbody > tr").remove();
            $("#lstSelectedMenuType > tbody > tr").remove();

            var datauser_availableforms = "", datauser_selectedForms = "", datauser_selectedPens = "";
            var datauser_selectedWards = "<tr id='0' onClick='addRemoveHl(this);'><td style='border:1px solid #d3d3d3'>No Ward</td></tr>";
            var datauser_selectedDOW = "<tr id='0' onClick='addRemoveHl(this);'><td style='border:1px solid #d3d3d3'>No Day of week</td></tr>";
            var datauser_selectedMenuType = "<tr id='0' onClick='addRemoveHl(this);'><td style='border:1px solid #d3d3d3'>No Menu Type</td></tr>";

            for (var nCount = 0; nCount < data.df.ft.length; nCount++) {
                if (data.df.ft[nCount].tn == "Other")
                    datauser_availableforms += "<tr id='" + data.df.ft[nCount].ftid + "' onClick='addRemoveHl(this);'><td style='border:1px solid #d3d3d3'>" + data.df.ft[nCount].tn + "</td></tr>";
                else
                    datauser_selectedForms += "<tr id='" + data.df.ft[nCount].ftid + "' onClick='addRemoveHl(this);'><td style='border:1px solid #d3d3d3'>" + data.df.ft[nCount].tn + "</td></tr>";
            }
            for (var nCount = 0; nCount < data.df.p.length; nCount++) {
                datauser_selectedPens += "<tr id='" + data.df.p[nCount].dpid + "'  onClick='addRemoveHl(this);'><td style='border:1px solid #d3d3d3'>" + data.df.p[nCount].pn + "</td></tr>";
            }
            for (var nCount = 0; nCount < data.df.w.length; nCount++) {
                datauser_selectedWards += "<tr id='" + data.df.w[nCount].wid + "'  onClick='addRemoveHl(this);'><td style='border:1px solid #d3d3d3'>" + data.df.w[nCount].wn + "</td></tr>";
            }
            for (var nCount = 0; nCount < data.df.d.length; nCount++) {
                datauser_selectedDOW += "<tr id='" + data.df.d[nCount].did + "'  onClick='addRemoveHl(this);'><td style='border:1px solid #d3d3d3'>" + data.df.d[nCount].dn + "</td></tr>";
            }
            //datauser_availableMenuType +=" <tr id='0' onClick='addRemoveMenuType(this);'><td style='border:1px solid #d3d3d3'>No indicators</td></tr>";
            for (var nCount = 0; nCount < data.df.mt.length; nCount++) {
                var hiphen = (data.df.mt[nCount].mtd == "") ? "" : "-";
                datauser_selectedMenuType += "<tr id='" + data.df.mt[nCount].mtid + "' onClick='addRemoveHl(this);'><td style='border:1px solid #d3d3d3'>" + '[' + data.df.mt[nCount].mttype + ']' + hiphen + data.df.mt[nCount].mtd + "</td></tr>";
            }

            $("#lstAvailableForm  tbody:last").append(datauser_availableforms);
            $("#lstSelectedForm tbody:last").append(datauser_selectedForms);
            $("#lstSelectedPens  tbody:last").append(datauser_selectedPens);
            $("#lstSelectedWards  tbody:last").append(datauser_selectedWards);
            $("#lstSelectedDOW  tbody:last").append(datauser_selectedDOW);
            $("#lstSelectedMenuType  tbody:last").append(datauser_selectedMenuType);
            iconoksign();
            srv_showDFForms(page);
        } else
            logout(1);
    }

}

function iconoksign() {
    $("#divDatesTab li:first").remove();
    $("#txtDigitalFilterStart").change(function() {
        changeStartDate();
        var today = new Date();
        var t = today.getDate() + "/" + (today.getMonth() + 1) + "/" + today.getFullYear();
        if (($("#txtDigitalFilterStart").val() == t) && ($("#txtDigitalFilterEnd").val() == t)) {
            $("#divDatesTab li:first").remove();
        } else {
            $("#divDatesTab li:first").remove();
            $("#divDatesTab").prepend('<li class="icon-ok"></i>');
        }
    });
    $("#txtDigitalFilterEnd").change(function() {
        changeEndDate();
        var today = new Date();
        var t = today.getDate() + "/" + (today.getMonth() + 1) + "/" + today.getFullYear();
        if (($("#txtDigitalFilterStart").val() == t) && ($("#txtDigitalFilterEnd").val() == t)) {
            $("#divDatesTab li:first").remove();
        } else {
            $("#divDatesTab li:first").remove();
            $("#divDatesTab").prepend('<li class="icon-ok"></i>');
        }
    });
    if (($('#lstAvailableForm tr td').text()) != "Other") {
        $("#divFormsTab li:first").remove();
        $("#divFormsTab").prepend('<li class="icon-ok"></i>');
    }
    else {
        $("#divFormsTab li:first").remove();
    }
    if (($('#lstAvailablePens tr').length) > 0) {
        $("#divPenTab li:first").remove();
        $("#divPenTab").prepend('<li class="icon-ok"></i>');
    }
    else {
        $("#divPenTab li:first").remove();
    }
    if (($('#lstAvailableWards tr').length) > 0) {
        $("#divWardsTab li:first").remove();
        $("#divWardsTab").prepend('<li class="icon-ok"></i>');
    }
    else {
        $("#divWardsTab li:first").remove();
    }
    if (($('#lstAvailableDOW tr').length) > 0) {
        $("#divDOWTab li:first").remove();
        $("#divDOWTab").prepend('<li class="icon-ok"></i>');
    }
    else {
        $("#divDOWTab li:first").remove();
    }
    if (($('#lstAvailableMenutype tr').length) > 0) {
        $("#divMenuTypeTab li:first").remove();
        $("#divMenuTypeTab").prepend('<li class="icon-ok"></i>');
    }
    else {
        $("#divMenuTypeTab li:first").remove();
    }
}
function changeStartDate() {
    var today = new Date();
    var t = today.getDate() + "/" + (today.getMonth() + 1) + "/" + today.getFullYear();
    if ($("#txtDigitalFilterStart").val() == t) {
        $("#divDatesTab li:first").remove();
    } else {
        $("#divDatesTab li:first").remove();
        $("#divDatesTab").prepend('<li class="icon-ok"></i>');
    }
}
function changeEndDate() {
    var today = new Date();
    var t = today.getDate() + "/" + (today.getMonth() + 1) + "/" + today.getFullYear();
    if ($("#txtDigitalFilterEnd").val() == t) {
        $("#divDatesTab li:first").remove();
    } else {
        $("#divDatesTab li:first").remove();
        $("#divDatesTab").prepend('<li class="icon-ok"></i>');
    }
}

function addRemoveHl(f) {
    if ($(f).hasClass("alert alert-success"))
        $(f).removeClass("alert alert-success");
    else
        $(f).addClass("alert alert-success");
}


function downloadFormXml(formID) {

    $.support.cors = true;
    $.ajax({
        url: BACKENDURL + "data_upload/export_data",
        type: "post",
        data: {
            session_id: localStorage["SESSIONID"],
            export_type: "digital_xml_file",
            dfid: formID
        },
        dataType: "json",
        async: false,
        crossDomain: true,
        success: function(data) {
            if (data.session_status) {
                $("#divErrForms").hide();
                if (data.error == 0) {
                    var url = BACKENDURL + "common/download_file";
                    $('<form action="' + url + "/" + localStorage["SESSIONID"] + "/digital_xml_file/" + data.temp_file + "/" + data.app_name + '" style="display:none;"></form>').appendTo('body').submit();
                    //window.open(url + "/" + localStorage["SESSIONID"] + "/order_items/" + data.temp_file);
                } else if (data.error_msg == "Unauthorized access.") {
                    logout(1);
                } else
                    $("#divErrForms").html(data.error_msg).show();
            } else {
                logout();
            }
        },
        error: function(xhr, textStatus, error) {
            alert(error);
        }
    });



}
var expSatus;
function manageException(formID, formType) {
    $("#ManageExpErr").addClass('hide');
    var DFormid = formID;
    var DFormType = formType;
    var url = BACKENDURL + "user/get_digital_form_exception_details";
    var data = {
        session_id: localStorage["SESSIONID"],
        dfid: DFormid,
        ft: DFormType
    };
    MakeAjaxCall(url, data, srv_manageException);
    $("#btnManageExceptionSubmit").off('click').on('click', function() {
        if (($("#txaReason", "#divManageException").val()) == "")
            $("#ManageExpErr").html('Please enter a Reason').removeClass('hide');
        else {
            expSatus = $("input:radio[name=rdoManageException]:checked").val();
            var expReason = $("#txaReason", "#divManageException").val();
            var url = BACKENDURL + "user/save_digital_form_exception_details";
            var data = {
                session_id: localStorage["SESSIONID"],
                dfid: DFormid,
                ft: DFormType,
                exp_status: expSatus,
                exp_reason: expReason
            };
            MakeAjaxCall(url, data, srv_manageExceptionSave);
        }
    })
}
function srv_manageException(data) {
    if (data.error == 0) {
        if (data.exception_details) {
            if (data.exception_details.exp_status == 0)
                $("#rdoIncludeData").attr('checked', true);

            else if (data.exception_details.exp_status == 1)
                $("#rdoExcludeData").attr('checked', true);
            else
                $("#rdoApproveException").attr('checked', true);
        }
        $("#txaReason", "#divManageException").val(data.exception_details.exp_reason);
        $("#divManageException").modal('show');
    } else if (data.error_msg == "Unauthorized access.") {
        logout(1);
    } else {
        $("#divManageException").modal('hide');
        var errMsg = data.error_msg;
        bootbox.dialog({
            message: errMsg,
            title: "Manage Exception",
            buttons: {
                main: {
                    label: "Ok",
                    className: "btn-primary",
                    callback: function() {
                    }
                }
            }
        });
    }
}
function srv_manageExceptionSave(data) {
    if (data.error == 0) {
        $("#divManageException").modal('hide');
        if (curr == undefined)
            srv_showDFForms(page);
        else if (($("#chkSEFD").is(":checked") == false) && (expSatus == 1)) {
            isExclude = true;
            srv_showDFForms(page);
        } else
            srv_showDFForms(curr);
    } else if (data.error_msg == "Unauthorized access.") {
        logout(1);
    } else
        $("#ManageExpErr").html(data.error_msg).removeClass('hide');
}

function lateNumbers(digitalFormID, formType) {
    $("#divLateNumErr").hide();
    var url = BACKENDURL + "user/get_digital_form_late_number_details";
    var data = {
        session_id: localStorage["SESSIONID"],
        dfid: digitalFormID,
        ft: formType
    };
    MakeAjaxCall(url, data, srv_lateNumbers);
    $("#btnLateNumSubmit").off('click').on('click', function() {
        $.fn.dirtyFields.formSaved($("#tblLateNums"));
        var url = BACKENDURL + "user/save_digital_form_late_numbers";
        var data = {
            session_id: localStorage["SESSIONID"],
            dfid: digitalFormID,
            ft: formType,
            wid: $("#ddlWardNames").val(),
            late_details: lateDetails
        };
        MakeAjaxCall(url, data, srv_lateNumbersSave);
    });
}
function srv_lateNumbers(data) {
    if (data.error == 0) {
        var ward = data.df_ward;
        var wardNames = " ";
        var selectedWard = " ";
        $("#tblLateNums  tbody:last").empty();
        var tblLateNum = "";
        $("#ddlWardNames", "#divLateNumbers").empty();
        for (var nCount = 0; nCount < data.ward_details.length; nCount++)
        {
            if (ward == data.ward_details[nCount].wid)
                selectedWard = "Selected";
            wardNames += "<option value=" + data.ward_details[nCount].wid + "  " + selectedWard + ">" + data.ward_details[nCount].wn + "</option>";
            selectedWard = " "
        }
        $("#ddlWardNames", "#divLateNumbers").append(wardNames);
        if (data.form_details.length > 0) {
            for (var nCount = 0; nCount < data.form_details.length; nCount++) {
                var warningSign = (data.form_details[nCount].oln == data.form_details[nCount].nln) ? " " : "warning";
                var menuType = (data.form_details[nCount].mt == null) ? "" : data.form_details[nCount].mt;
                tblLateNum += "<tr><td data-title='Menu Description'>" + data.form_details[nCount].md + "</td><td data-title='Original' id='orig" + nCount + "'>" + data.form_details[nCount].oln + "</td> <td data-title='New'><div class='control-group " + warningSign + "'><div class='controls'><input type='text' id='newLateValue" + nCount + "' name='" + data.form_details[nCount].ldfid + "'  value= '" + data.form_details[nCount].nln + "' style='width:60px;'  highlight='true'></div></div></td></tr>";
            }
            $("#tblLateNums  tbody:last").append(tblLateNum);
            lateNumDirtyFieldCheck();
            $("#tblLateNums", "#divLateNumbers").show();
            $("input[id^='newLateValue']").keypress(function(e) {
                if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
                    return false;
                }
            });
        } else
            $("#tblLateNums", "#divLateNumbers").hide();
        $("#divLateNumbers").modal('show');
    } else if (data.error_msg == "Unauthorized access.") {
        logout(1);
    } else {
        $("#divLateNumbers").modal('hide');
        var errMsg = data.error_msg;
        bootbox.dialog({
            message: errMsg,
            title: "Late Numbers",
            buttons: {
                main: {
                    label: "Ok",
                    className: "btn-primary",
                    callback: function() {
                    }
                }
            }
        });
    }

}

function lateNumDirtyFieldCheck() {
    var Settings = {
        denoteDirtyForm: true,
        dirtyFormClass: false,
        dirtyOptionClass: "dirtyChoice",
        trimText: true,
        formChangeCallback: function(result, dirtyFieldsArray) {
            lateDetails = [];
            if (result)
            {
                //$("#tblLateNums").find('.warning').removeClass('warning')
                $.each(dirtyFieldsArray, function(index, value) {
                    lateDetails.push({"ldfid": value, "nln": $("[name='" + value + "']").val()});
                    //$("#" + value).parents('.control-group').addClass('warning');
                });
            }
            //else
            //  $("#tblLateNums").find('.warning').removeClass('warning')
        }
    };

    $("#tblLateNums").dirtyFields(Settings);
}

/*function lateNumbersSubmit(){
 $.fn.dirtyFields.formSaved($("#tblLateNums"));
 $("#divLateNumbers").modal('hide');
 var url = BACKENDURL + "user/save_digital_form_late_numbers";
 var data = {
 session_id: localStorage["SESSIONID"],
 dfid: digitalFormID,
 ft: formType,
 late_details :lateDetails
 };
 MakeAjaxCall(url, data, srv_lateNumbersSave);
 
 }*/
function srv_lateNumbersSave(data) {
    if (data.error == 0) {
        $("#divLateNumbers").modal('hide');
        if (curr == undefined)
            srv_showDFForms(page);
        else
            srv_showDFForms(curr);
    } else if (data.error_msg == "Unauthorized access.") {
        logout(1);
    } else
        $("#divLateNumErr").html(data.error_msg).show();
}


// Total daily meal numbers

function SrvLoadTDM() {
    var url = BACKENDURL + "user/digital_form_load";
    var data = {
        session_id: localStorage["SESSIONID"],
        type: 'tdm'
    };
    MakeAjaxCall(url, data, LoadTDM);
}

function LoadTDM(data)
{
    if (data.session_status) {
        if (data.error == 0) {
            $("#lstAvailablePens_TDM > tbody > tr").remove();
            $("#lstSelectedPens_TDM > tbody > tr").remove();
            $("#lstAvailableWards_TDM > tbody > tr").remove();
            $("#lstSelectedWards_TDM > tbody > tr").remove();
            $("#lstAvailableDOW_TDM > tbody > tr").remove();
            $("#lstSelectedDOW_TDM > tbody > tr").remove();
            $("#lstAvailableMenutype_TDM > tbody > tr").remove();
            $("#lstSelectedMenuType_TDM > tbody > tr").remove();

            var data_forms = "", selectedNavStr = "selected", datauser_selectedPens = "", datauser_selectedWards = "";
            var datauser_selectedDOW = "<tr id='0' onClick='addRemoveHl(this);'><td style='border:1px solid #d3d3d3'>No Day of week</td></tr>";
            var datauser_selectedMenuType = "<tr id='0' onClick='addRemoveHl(this);'><td style='border:1px solid #d3d3d3'>No Menu Type</td></tr>";
            $("#ddlForm_TDM").empty();
            for (var nCount = 0; nCount < data.df.ft.length; nCount++) {
                if (data.df.ft[nCount].tn == "Other")
                    data_forms += "<option " + selectedNavStr + " value=0>Lunch & Dinner</option>";
                else
                    data_forms += "<option value=" + data.df.ft[nCount].ftid + " >" + data.df.ft[nCount].tn + "</option>";
            }
            $("#ddlForm_TDM", "#divFilApps_r").append(data_forms);
            for (var nCount = 0; nCount < data.df.p.length; nCount++) {
                datauser_selectedPens += "<tr id='" + data.df.p[nCount].dpid + "'  onClick='addRemoveHl(this);'><td style='border:1px solid #d3d3d3'>" + data.df.p[nCount].pn + "</td></tr>";
            }
            for (var nCount = 0; nCount < data.df.w.length; nCount++) {
                var dataiW = new Object();
                dataiW.id = data.df.w[nCount].wid;
                dataiW.name = data.df.w[nCount].wn;
                initWards_TDM[nCount] = dataiW;
                datauser_selectedWards += "<tr id='" + data.df.w[nCount].wid + "'  onClick='addRemoveHl(this);'><td style='border:1px solid #d3d3d3'>" + data.df.w[nCount].wn + "</td></tr>";
            }
            for (var nCount = 0; nCount < data.df.d.length; nCount++) {
                datauser_selectedDOW += "<tr id='" + data.df.d[nCount].did + "'  onClick='addRemoveHl(this);'><td style='border:1px solid #d3d3d3'>" + data.df.d[nCount].dn + "</td></tr>";
            }
            for (var nCount = 0; nCount < data.df.mt.length; nCount++) {
                var hiphen = (data.df.mt[nCount].mtd == "") ? "" : "-";
                datauser_selectedMenuType += "<tr id='" + data.df.mt[nCount].mtid + "' onClick='addRemoveHl(this);'><td style='border:1px solid #d3d3d3'>" + '[' + data.df.mt[nCount].mttype + ']' + hiphen + data.df.mt[nCount].mtd + "</td></tr>";
            }

            $("#lstSelectedPens_TDM  tbody:last").append(datauser_selectedPens);
            $("#lstSelectedWards_TDM  tbody:last").append(datauser_selectedWards);
            $("#lstSelectedDOW_TDM  tbody:last").append(datauser_selectedDOW);
            $("#lstSelectedMenuType_TDM  tbody:last").append(datauser_selectedMenuType);
            DateVal_TDM();
            //$("#btnFilter_TDM").click();
            //iconoksign();
        } else
            logout(1);
    }
}


/*function addPen_TDM()
 {
 var count = 0;
 $("#lstAvailablePens_TDM tr").each(function(e) {
 if ($(this).hasClass("alert alert-success")) {
 count++;
 $("#lstSelectedPens_TDM tbody").append($(this).removeClass("alert alert-success").clone());
 $(this).detach();
 }
 });
 if (($('#lstAvailablePens_TDM tr').length) > 0) {
 $("#diviconPens li:first").remove();
 $("#diviconPens").prepend('<li class="icon-ok"></i>');
 }
 else {
 $("#diviconPens li:first").remove();
 }
 if (count == 0)
 $("#divPensErrMsg_TDM").html("Please select from available pens").show();
 else {
 $("#divPensErrMsg_TDM").hide();
 Sort_table("lstSelectedPens_TDM");
 }
 }
 
 function remPen_TDM()
 {
 var count = 0;
 $("#lstSelectedPens_TDM tr").each(function(e) {
 if ($(this).hasClass("alert alert-success")) {
 count++;
 $("#lstAvailablePens_TDM tbody").append($(this).removeClass("alert alert-success").clone());
 $(this).detach();
 }
 });
 if (($('#lstAvailablePens_TDM tr').length) > 0) {
 
 $("#diviconPens li:first").remove();
 $("#diviconPens").prepend('<li class="icon-ok"></i>');
 }
 else {
 $("#diviconPens li:first").remove();
 }
 if (count == 0)
 $("#divPensErrMsg_TDM").html("Please select from selected pens").show();
 else {
 $("#divPensErrMsg_TDM").hide();
 Sort_table("lstAvailablePens_TDM");
 }
 }
 
 function addWard_TDM()
 {
 var count = 0;
 $("#lstAvailableWards_TDM tr").each(function(e) {
 if ($(this).hasClass("alert alert-success")) {
 count++;
 $("#lstSelectedWards_TDM tbody").append($(this).removeClass("alert alert-success").clone());
 $(this).detach();
 }
 });
 if (($('#lstAvailableWards_TDM tr').length) > 0) {
 $("#diviconWard li:first").remove();
 $("#diviconWard").prepend('<li class="icon-ok"></i>');
 }
 else {
 $("#diviconWard li:first").remove();
 }
 if (count == 0)
 $("#divWardsErrMsg_TDM").html("Please select from available wards").show();
 else {
 $("#divWardsErrMsg_TDM").hide();
 Sort_table("lstSelectedWards_TDM");
 }
 }
 
 function remWard_TDM()
 {
 var count = 0;
 $("#lstSelectedWards_TDM tr").each(function(e) {
 if ($(this).hasClass("alert alert-success")) {
 count++;
 $("#lstAvailableWards_TDM tbody").append($(this).removeClass("alert alert-success").clone());
 $(this).detach();
 }
 });
 if (($('#lstAvailableWards_TDM tr').length) > 0) {
 $("#diviconWard li:first").remove();
 $("#diviconWard").prepend('<li class="icon-ok"></i>');
 }
 else {
 $("#diviconWard li:first").remove();
 }
 if (count == 0)
 $("#divWardsErrMsg_TDM").html("Please select from selected wards").show();
 else {
 $("#divWardsErrMsg_TDM").hide();
 Sort_table("lstAvailableWards_TDM");
 }
 }
 
 function addDay_TDM()
 {
 var count = 0;
 $("#lstAvailableDOW_TDM tr").each(function(e) {
 if ($(this).hasClass("alert alert-success")) {
 count++;
 $("#lstSelectedDOW_TDM tbody").append($(this).removeClass("alert alert-success").clone());
 $(this).detach();
 }
 });
 if (($('#lstAvailableDOW_TDM tr').length) > 0) {
 $("#diviconDay li:first").remove();
 $("#diviconDay").prepend('<li class="icon-ok"></i>');
 }
 else {
 $("#diviconDay li:first").remove();
 }
 if (count == 0)
 $("#divDayErrMsg_TDM").html("Please select from available days of weeks").show();
 else {
 $("#divDayErrMsg_TDM").hide();
 Sort_table("lstSelectedDOW_TDM");
 }
 }
 
 function remDay_TDM()
 {
 var count = 0;
 $("#lstSelectedDOW_TDM tr").each(function(e) {
 if ($(this).hasClass("alert alert-success")) {
 count++;
 $("#lstAvailableDOW_TDM tbody").append($(this).removeClass("alert alert-success").clone());
 $(this).detach();
 }
 });
 if (($('#lstAvailableDOW_TDM tr').length) > 0) {
 $("#diviconDay li:first").remove();
 $("#diviconDay").prepend('<li class="icon-ok"></i>');
 }
 else {
 $("#diviconDay li:first").remove();
 }
 if (count == 0)
 $("#divDayErrMsg_TDM").html("Please select from selected days of weeks").show();
 else {
 $("#divDayErrMsg_TDM").hide();
 Sort_table("lstAvailableDOW_TDM");
 }
 }
 
 function addMenus_TDM()
 {
 var count = 0;
 $("#lstAvailableMenutype_TDM tr").each(function(e) {
 if ($(this).hasClass("alert alert-success")) {
 count++;
 $("#lstSelectedMenuType_TDM tbody").append($(this).removeClass("alert alert-success").clone());
 $(this).detach();
 }
 });
 if (($('#lstAvailableMenutype_TDM tr').length) > 0) {
 $("#diviconMenu li:first").remove();
 $("#diviconMenu").prepend('<li class="icon-ok"></i>');
 }
 else {
 $("#diviconMenu li:first").remove();
 }
 if (count == 0)
 $("#divMenuErrMsg_TDM").html("Please select from available menutype").show();
 else {
 $("#divMenuErrMsg_TDM").hide();
 Sort_table("lstSelectedMenuType_TDM");
 }
 }
 
 function remMenus_TDM()
 {
 var count = 0;
 $("#lstSelectedMenuType_TDM tr").each(function(e) {
 if ($(this).hasClass("alert alert-success")) {
 count++;
 $("#lstAvailableMenutype_TDM tbody").append($(this).removeClass("alert alert-success").clone());
 $(this).detach();
 }
 });
 if (($('#lstAvailableMenutype_TDM tr').length) > 0) {
 $("#diviconMenu li:first").remove();
 $("#diviconMenu").prepend('<li class="icon-ok"></i>');
 }
 else {
 $("#diviconMenu li:first").remove();
 }
 if (count == 0)
 $("#divMenuErrMsg_TDM").html("Please select from selected menutype").show();
 else {
 $("#divMenuErrMsg_TDM").hide();
 Sort_table("lstAvailableMenutype_TDM");
 }
 }*/

function DateVal_TDM() {
    $("#dtDgStart_TDM").datepicker({endDate: '-1d'}).on('changeDate', function(ev) {
        $('#dtDgStart_TDM').datepicker('hide');
        if (!$("#diviconDate li:first").hasClass("icon-ok"))
            $("#diviconDate").prepend('<li class="icon-ok"></i>');
    });
    $('#dtDgStart_TDM').data({
        date: '-32d'
    }).datepicker('update');
    $("#txtDgStart_TDM").val(convert($("#dtDgStart_TDM").datepicker("getDate")));
    $("#dtDgEnd_TDM").datepicker({endDate: '-1d'}).on('changeDate', function(ev) {
        $('#dtDgEnd_TDM').datepicker('hide');
        if (!$("#diviconDate li:first").hasClass("icon-ok"))
            $("#diviconDate").prepend('<li class="icon-ok"></i>');
    });
    $('#dtDgEnd_TDM').data({
        date: '-1d'
    }).datepicker('update');
    $("#txtDgEnd_TDM").val(convert($("#dtDgEnd_TDM").datepicker("getDate")));

}
function convert(date_ist) {
    var date = new Date(date_ist),
            mnth = ("0" + (date.getMonth() + 1)).slice(-2),
            day = ("0" + date.getDate()).slice(-2);
    return [day, mnth, date.getFullYear()].join("/");
}

function HideErrMsg_TDM() {
    $("#divPensErrMsg_TDM").hide();
    $("#divWardsErrMsg_TDM").hide();
    $("#divDayErrMsg_TDM").hide();
    $("#divMenuErrMsg_TDM").hide();
    $("#diviconMenu li:first").hasClass("icon-ok");
    $("#diviconMenu li:first").remove();
    $("#diviconDay li:first").hasClass("icon-ok");
    $("#diviconDay li:first").remove();
    $("#diviconWard li:first").hasClass("icon-ok");
    $("#diviconWard li:first").remove();
    $("#diviconPens li:first").hasClass("icon-ok");
    $("#diviconPens li:first").remove();
    $("#diviconDate li:first").hasClass("icon-ok");
    $("#diviconDate li:first").remove();
    $("#diviconApps li:first").hasClass("icon-ok");
    $("#diviconApps li:first").remove();
}

function reset_TDM()
{
    SrvLoadTDM();
    HideErrMsg_TDM();
    // $("#chk_TDM").attr('checked', false);

}
function reset_DMO() {
    SrvLoadDailyMealOrders();
    HideErrMsg_DMO();
    $("#chkShow_DMO").attr('checked', false);
}
function HideErrMsg_DMO()
{
    $("#divPensErrMsg_DMO").hide();
    $("#divWardsErrMsg_DMO").hide();
    $("#divDOWErrMsg_DMO").hide();
    $("#divMenuTypeErrMsg_DMO").hide();
    $("#diviconMenuType_DMO li:first").hasClass("icon-ok");
    $("#diviconMenuType_DMO li:first").remove();
    $("#diviconDOW_DMO li:first").hasClass("icon-ok");
    $("#diviconDOW_DMO li:first").remove();
    $("#diviconWards_DMO li:first").hasClass("icon-ok");
    $("#diviconWards_DMO li:first").remove();
    $("#diviconPens_DMO li:first").hasClass("icon-ok");
    $("#diviconPens_DMO li:first").remove();
    $("#diviconFroms_DMO li:first").hasClass("icon-ok");
    $("#diviconFroms_DMO li:first").remove();
    $("#diviconDates_DMO li:first").hasClass("icon-ok");
    $("#diviconDates_DMO li:first").remove();
}
function getValues_TDM()
{
    var digitalpenvar_TDM = "", digitalformvar_TDM = "", digitalwardvar_TDM = "", digitalDOWvar_TDM = "", digitalmenutypevar_TDM = "";
    var pens_TDM = "", form_TDM = "", wards_TDM = "", DOW_TDM = "", menutype_TDM = "";
    var aForm_TDM = [], sForm_TDM = [], sP_TDM = [], aP_TDM = [], aW_TDM = [], aDOW_TDM = [], aMT_TDM = [], sW_TDM = [], sDOW_TDM = [], sMT_TDM = [], sPCount = 0, sWCount = 0, sMTCount = 0, sDOWCount = 0, aPCount = 0, aWCount = 0, aMTCount = 0, aDOWCount = 0, aFCount = 0, sFCount = 0;
    $("#lstSelectedPens_TDM tr").each(function(e) {
        var dataSp = new Object();
        dataSp.id = $(this).attr("id");
        dataSp.name = $(this).text();
        sP_TDM[sPCount] = dataSp;
        sPCount++;
        digitalpenvar_TDM = digitalpenvar_TDM + $(this).attr("id") + ",";
        pens_TDM = digitalpenvar_TDM.replace(/^,|,$/g, '');
    });
    $("#lstSelectedWards_TDM tr").each(function(e) {
        var dataSp = new Object();
        dataSp.id = $(this).attr("id");
        dataSp.name = $(this).text();
        sW_TDM[sWCount] = dataSp;
        sWCount++;
        digitalwardvar_TDM = digitalwardvar_TDM + $(this).attr("id") + ",";
        wards_TDM = digitalwardvar_TDM.replace(/^,|,$/g, '');
    });
    $("#lstSelectedDOW_TDM tr").each(function(e) {
        var dataSp = new Object();
        dataSp.id = $(this).attr("id");
        dataSp.name = $(this).text();
        sDOW_TDM[sDOWCount] = dataSp;
        sDOWCount++;
        digitalDOWvar_TDM = digitalDOWvar_TDM + $(this).attr("id") + ",";
        DOW_TDM = digitalDOWvar_TDM.replace(/^,|,$/g, '');
    });
    $("#lstSelectedMenuType_TDM tr").each(function(e) {
        var dataSp = new Object();
        dataSp.id = $(this).attr("id");
        dataSp.name = $(this).text();
        sMT_TDM[sMTCount] = dataSp;
        sMTCount++;
        digitalmenutypevar_TDM = digitalmenutypevar_TDM + $(this).attr("id") + ",";
        menutype_TDM = digitalmenutypevar_TDM.replace(/^,|,$/g, '');
    });
    $("#lstAvailablePens_TDM tr").each(function(e) {
        var dataSp = new Object();
        dataSp.id = $(this).attr("id");
        dataSp.name = $(this).text();
        aP_TDM[aPCount] = dataSp;
        aPCount++;
    });
    $("#lstAvailableWards_TDM tr").each(function(e) {
        var dataSp = new Object();
        dataSp.id = $(this).attr("id");
        dataSp.name = $(this).text();
        aW_TDM[aWCount] = dataSp;
        aWCount++;
    });
    $("#lstAvailableDOW_TDM tr").each(function(e) {
        var dataSp = new Object();
        dataSp.id = $(this).attr("id");
        dataSp.name = $(this).text();
        aDOW_TDM[aDOWCount] = dataSp;
        aDOWCount++;
    });
    $("#lstAvailableMenutype_TDM tr").each(function(e) {
        var dataSp = new Object();
        dataSp.id = $(this).attr("id");
        dataSp.name = $(this).text();
        aMT_TDM[aMTCount] = dataSp;
        aMTCount++;
    });
    $('#ddlForm_TDM option:not(:selected)').each(function(e) {
        var dataSp = new Object();
        dataSp.id = $(this).val();
        dataSp.name = $(this).text();
        aForm_TDM[aFCount] = dataSp;
        aFCount++;
    });
    $('#ddlForm_TDM option:selected').each(function(e) {
        var dataSp = new Object();
        dataSp.id = $(this).val();
        dataSp.name = $(this).text();
        sForm_TDM[sFCount] = dataSp;
        sFCount++;
    });
    json_TDM = [];
    json_TDM["Avl_Forms"] = aForm_TDM;
    json_TDM["Sel_Forms"] = sForm_TDM;
    json_TDM["Sel_Pens"] = sP_TDM;
    json_TDM["Avl_Pens"] = aP_TDM;
    json_TDM["Sel_Wards"] = sW_TDM;
    json_TDM["Avl_Wards"] = aW_TDM;
    json_TDM["Sel_DOW"] = sDOW_TDM;
    json_TDM["Avl_DOW"] = aDOW_TDM;
    json_TDM["Sel_MT"] = sMT_TDM;
    json_TDM["Avl_MT"] = aMT_TDM;
    json_TDM["Start_TDM"] = $("#txtDgStart_TDM").val();
    json_TDM["End_TDM"] = $("#txtDgEnd_TDM").val();
    json_TDM["Init_Wards"] = initWards_TDM;
    return [pens_TDM, wards_TDM, DOW_TDM, menutype_TDM];
}

function SrvshowTDMForms() {
    var vales_TDM = getValues_TDM();
    var startdate_TDM = $("#txtDgStart_TDM").val().split("/");
    var enddate_TDM = $("#txtDgEnd_TDM").val().split("/");
    var url = BACKENDURL + "user/total_daily_meal_filter";
    var data = {
        session_id: localStorage["SESSIONID"],
        start_date: startdate_TDM[2] + "-" + startdate_TDM[1] + "-" + startdate_TDM[0],
        end_date: enddate_TDM[2] + "-" + enddate_TDM[1] + "-" + enddate_TDM[0],
        ft: $("#ddlForm_TDM").val(),
        p: vales_TDM[0],
        w: vales_TDM[1],
        dw: vales_TDM[2],
        mt: vales_TDM[3]
                //isex: $("#chk_TDM").is(":checked") ? 1 : 0,
    };
    var formStartDate = (new Date(startdate_TDM[2], (startdate_TDM[1] - 1), startdate_TDM[0])).valueOf();
    var formEndDate = (new Date(enddate_TDM[2], (enddate_TDM[1] - 1), enddate_TDM[0])).valueOf();
    if (formEndDate < formStartDate)
        $("#divPensErrMsg_TDM").html('The end date can not be earlier than the start date').removeClass('hide')
    else {
        $("#divTDMReportHeading").hide();
        $("#divTDMCustom").hide();
        $("#divTDMButtons").hide();
        $("#btn_genreport").html('Generate Report');
        $("#divPensErrMsg_TDM").addClass('hide');
        $("#accReportsFilter").collapse('hide');

        setTimeout(function() {
            $("#accReportsForms").collapse('show');
        }, 200);
        MakeAjaxCall(url, data, showTDMForms);
    }
}

function showTDMForms(data) {
    var ddlMenuTypesTxt = "";
    $("#ddlMenuTypes_TDM").empty();
    if (data.error == 0) {
        if (data.df.length > 0)
        {
            for (var i = 0; i < data.df.length; i++)
            {
                var tmp_mt = "[" + data.df[i].mt + "] - " + data.df[i].mtd;
                ddlMenuTypesTxt += "<option value=" + data.df[i].dfid + " >" + tmp_mt + "</option>";
            }
            $("#ddlMenuTypes_TDM").append(ddlMenuTypesTxt);
            $("#txtTDMIndicator").val(data.df[0].mtd);
            $("#divTotalIndicators").show();
            $("#divReportErr_TDM").hide();
            IndicatorOnChange($("#ddlMenuTypes_TDM"));
        }
        else
        {
            $("#divTotalIndicators").hide();
            $("#divReportErr_TDM").html('No Records found').show();
        }
    }
}

function srv_showTDMReport() {
    $("#spnGenerateReport_TDM").html('<img src="img/ajax-loader2.gif" style="margin-right:10px;" />' + $("#btn_genreport").text() + "......").removeClass("hide");
    $("#btn_genreport").html('Refresh').attr("disabled", "disabled");
    var tmp_dsc = $("#ddlMenuTypes_TDM option:selected").text().split("-");
    $("#ddlMenuTypes_TDM option:selected").text(tmp_dsc[0] + "-" + $("#txtTDMIndicator").val());
    var vales_TDM = getValues_TDM();
    var startdate_TDM = $("#txtDgStart_TDM").val().split("/");
    var enddate_TDM = $("#txtDgEnd_TDM").val().split("/");
    var url = BACKENDURL + "user/get_df_tdm_numbers ";
    var data = {
        session_id: localStorage["SESSIONID"],
        dfi_id: $("#ddlMenuTypes_TDM").val(),
        dfi_desc: $("#txtTDMIndicator").val(),
        dw: vales_TDM[2],
        start_date: startdate_TDM[2] + "-" + startdate_TDM[1] + "-" + startdate_TDM[0],
        end_date: enddate_TDM[2] + "-" + enddate_TDM[1] + "-" + enddate_TDM[0],
        ft: $("#ddlForm_TDM").val(),
        //isex: $("#chk_TDM").is(":checked") ? 1 : 0,
        mt: vales_TDM[3],
        p: vales_TDM[0],
        w: vales_TDM[1],
    };
    MakeAjaxCall(url, data, showTDMReport);
}

function showTDMReport(data)
{
    var date_len = data.dfi_res.tdm.length;
    var tbl_TDMlogs = "";
    var total_TDMlogs = "<tr><td><b>Total</b></td>";
    var header_TDMlogs = "<tr class='persist-header'><th style='text-align:center;'><div class='rotateText hide'><a href='javascript:navigateToDigitalForms();'>Days</a></div></th>";
    var date_yr = (data.dfi_res.tdm[0][0]).split("-");
    $("#date_TDM").html("Total Daily Meal Numbers " + convertDate(data.dfi_res.tdm[0][0]) + " - " + convertDate(data.dfi_res.tdm[date_len - 1][0]) + " " + date_yr[0]);
    $("#tStamp_TDM").html("Report time: " + data.dfi_res.rt[0].rt);
    $("#tblTDMReport tbody tr").remove();
    $("#tblTDMReport thead tr").remove();
    $("#divErrForms_TDM").hide();

    if (data.dfi_res.wln.length > 0)
    {
        for (var jCount = 0; jCount < data.dfi_res.wln.length; jCount++)
            header_TDMlogs += "<th style='text-align:center;'><div class='rotateText'><a href='javascript:navigateToDigitalForms(" + data.dfi_res.wln[jCount].wid + ",null,null,null,0" + ");'>" + data.dfi_res.wln[jCount].wn + "</a></div></th>";
        header_TDMlogs += "<th style='text-align:center;'><div class='rotateText'>Total</div></th>";
        header_TDMlogs += "</tr>";
        $("#tblTDMReport  thead:last").append(header_TDMlogs);
        for (var nCount = 0; nCount < data.dfi_res.tdm.length; nCount++)
        {
            var date_tdm = (data.dfi_res.tdm[nCount][0]).split("-");
            tbl_TDMlogs += "<tr><td><a href=javascript:navigateToDigitalForms(null," + date_tdm[0] + "," + date_tdm[1] + "," + date_tdm[2] + ",0"+");>" + convertDate(data.dfi_res.tdm[nCount][0]) + "</a></td>";
            for (var jCount = 0; jCount < data.dfi_res.wln.length; jCount++) {
                var s = "";
                if (data.dfi_res.ln != undefined && data.dfi_res.ln[data.dfi_res.tdm[nCount][0]] != undefined && data.dfi_res.ln[data.dfi_res.tdm[nCount][0]][data.dfi_res.wln[jCount].wid] != undefined)
                    s = "background-color: rgb(255, 255, 0);";
                if (data.dfi_res.mr != undefined && data.dfi_res.mr[data.dfi_res.tdm[nCount][0]] != undefined && data.dfi_res.mr[data.dfi_res.tdm[nCount][0]][data.dfi_res.wln[jCount].wid] != undefined)
                    s = "background-color: red;";
                tbl_TDMlogs += "<td style='" + s + " text-align:center;' data-title=" + data.dfi_res.wln[jCount].wn + "><a href=javascript:navigateToDigitalForms(" + data.dfi_res.wln[jCount].wid + "," + date_tdm[0] + "," + date_tdm[1] + "," + date_tdm[2] + ",0"+");>" + data.dfi_res.tdm[nCount][jCount + 1] + "</a></td>";
            }
            tbl_TDMlogs += "<td style='text-align:center;' data-title='Total'>" + data.dfi_res.tdm[nCount][jCount + 1] + "</td>";
            tbl_TDMlogs += "</tr>";
            $("#tblTDMReport  tbody:last").append(tbl_TDMlogs);
            $("#tblTDMReport").show();
            tbl_TDMlogs = "";
        }

        var tc = 0;
        for (var jCount = 0; jCount < data.dfi_res.wln.length; jCount++) {
            total_TDMlogs += "<td style='text-align:center;' data-title=" + data.dfi_res.wln[jCount].wn + ">" + data.dfi_res.wln[jCount].cnt + "</td>";
            tc = tc + data.dfi_res.wln[jCount].cnt;
        }
        total_TDMlogs += "<td style='text-align:center;' data-title='Total'>" + tc + "</td>";
        total_TDMlogs += "</tr>";
        $("#tblTDMReport  tbody:last").append(total_TDMlogs);
        $("#tblTDMReport").show();
        $("#divTDMReportHeading").show();
        $("#tblTDMReport").show();
        if (data.dfi_res.cus_cnt != undefined)
        {
            $("#lblCustOrders").html(data.dfi_res.cus_cnt);
            $("#divTDMCustom").show();
            $("#divTDMButtons").show();
        }
        else
        {
            $("#divTDMButtons").show();
        }

        persistentheaders();
    }
    else
    {
        $("#divErrForms_TDM").html('No Records found').show();
        $("#divTDMReportHeading").show();
    }
    $("#spnGenerateReport_TDM").addClass("hide");
    $("#btn_genreport").removeAttr("disabled");
}

function navigateToDigitalForms(Ward_id, tdm_y, tdm_m, tdm_d,custom) {
    var vales_TDM = getValues_TDM();
    var form_type = "";
    var date_similar = 0;
    if (Ward_id != null)
    {
        vales_TDM[1] = Ward_id;
    }
    if (tdm_y == null)
    {
        var startdate_TDM = $("#txtDgStart_TDM").val().split("/");
        var startdate_TDM = startdate_TDM[2] + "-" + startdate_TDM[1] + "-" + startdate_TDM[0];
        var enddate_TDM = $("#txtDgEnd_TDM").val().split("/");
        var enddate_TDM = enddate_TDM[2] + "-" + enddate_TDM[1] + "-" + enddate_TDM[0];
    }
    else
    {
        var startdate_TDM = tdm_y + "-" + tdm_m + "-" + tdm_d;
        var enddate_TDM = tdm_y + "-" + tdm_m + "-" + tdm_d;
        date_similar = tdm_d + "/" + tdm_m + "/" + tdm_y;
    }
    if ($("#ddlForm_TDM").val() == "0")
    {
        form_type = "1,2";
    }
    else
    {
        form_type = $("#ddlForm_TDM").val();
    }
    $('#widgetnav a[href="#pcatDigitalForms"]').tab('show');
    $("#divFilterErr").addClass('hide');
    $("#accFilter").collapse('hide');
    setTimeout(function() {
        $("#accForms").collapse('show');
        $("#divRefresh1").show();
    }, 200);
    navigateToDigitalFilters(date_similar, Ward_id);
    var url = BACKENDURL + "user/digital_form_filter";
    var data = {
        session_id: localStorage["SESSIONID"],
        start_date: startdate_TDM,
        end_date: enddate_TDM,
        ft: form_type,
        p: vales_TDM[0],
        w: vales_TDM[1],
        dw: vales_TDM[2],
        mt: vales_TDM[3],
        custom: custom,
        //isex: $("#chk_TDM").is(":checked") ? 1 : 0,
        page_no: 1,
    };
    MakeAjaxCall(url, data, showDFForms);
}

function navigateToDigitalFilters(dt_s, sl_ward) {
    //$("#tabPCDigitalForms").click();
    //setTimeout(function() {
    $("#lstAvailableForm > tbody > tr").remove();
    $("#lstSelectedForm > tbody > tr").remove();
    $("#lstAvailablePens > tbody > tr").remove();
    $("#lstAvailableWards > tbody > tr").remove();
    $("#lstAvailableDOW > tbody > tr").remove();
    $("#lstAvailableMenutype > tbody > tr").remove();
    $("#lstSelectedPens > tbody > tr").remove();
    $("#lstSelectedWards > tbody > tr").remove();
    $("#lstSelectedDOW > tbody > tr").remove();
    $("#lstSelectedMenuType > tbody > tr").remove();
    var availablePens = " ", selectedPens = " ", availableWards = "", selectedWards = "", availableDOW = "", selectedDOW = "", availableMT = "", selectedMT = "", availableforms = "", selectedforms = "";
    var availableWards = "<tr id='0' onClick='addRemoveHl(this);'><td style='border:1px solid #d3d3d3'>No Ward</td></tr>";
    /*for(var nCount = 0; nCount < json_TDM.Avl_Forms.length; nCount++){
     if(json_TDM.Sel_Forms[nCount].id == "0")
     {
     availableforms += "<tr id='3' onClick='addRemoveHl(this);'><td style='border:1px solid #d3d3d3'>Adhoc</td></tr><tr id='4' onClick='addRemoveHl(this);'><td style='border:1px solid #d3d3d3'>Other</td></tr>";
     if(json_TDM.Avl_Forms[nCount].id != "0")
     {
     availableforms += "<tr id='" + json_TDM.Avl_Forms[nCount].id + "' onClick='addRemoveHl(this);'><td style='border:1px solid #d3d3d3'>" + json_TDM.Avl_Forms[nCount].name + "</td></tr>";
     }
     }
     
     }*/
    //for(var nCount = 0; nCount < json_TDM.Sel_Forms.length; nCount++){
    if (json_TDM.Sel_Forms[0].id == "0")
    {
        selectedforms += "<tr id='1' onClick='addRemoveHl(this);'><td style='border:1px solid #d3d3d3'>Lunch</td></tr><tr id='2' onClick='addRemoveHl(this);'><td style='border:1px solid #d3d3d3'>Dinner</td></tr>";
        availableforms += "<tr id='3' onClick='addRemoveHl(this);'><td style='border:1px solid #d3d3d3'>Adhoc</td></tr>";
    }
    else
    {
        selectedforms += "<tr id='" + json_TDM.Sel_Forms[0].id + "' onClick='addRemoveHl(this);'><td style='border:1px solid #d3d3d3'>" + json_TDM.Sel_Forms[0].name + "</td></tr>";
        for (var nCount = 0; nCount < json_TDM.Avl_Forms.length; nCount++) {
            if (json_TDM.Avl_Forms[nCount].id != "0")
            {
                availableforms += "<tr id='" + json_TDM.Avl_Forms[nCount].id + "' onClick='addRemoveHl(this);'><td style='border:1px solid #d3d3d3'>" + json_TDM.Avl_Forms[nCount].name + "</td></tr>";
            }
        }
    }

    //}
    for (var nCount = 0; nCount < json_TDM.Avl_Pens.length; nCount++) {
        availablePens += "<tr id='" + json_TDM.Avl_Pens[nCount].id + "' onClick='addRemoveHl(this);'><td style='border:1px solid #d3d3d3'>" + json_TDM.Avl_Pens[nCount].name + "</td></tr>";
    }
    for (var nCount = 0; nCount < json_TDM.Sel_Pens.length; nCount++) {
        selectedPens += "<tr id='" + json_TDM.Sel_Pens[nCount].id + "'  onClick='addRemoveHl(this);'><td style='border:1px solid #d3d3d3'>" + json_TDM.Sel_Pens[nCount].name + "</td></tr>";
    }
    if (sl_ward == null)
    {
        for (var nCount = 0; nCount < json_TDM.Avl_Wards.length; nCount++) {
            availableWards += "<tr id='" + json_TDM.Avl_Wards[nCount].id + "'  onClick='addRemoveHl(this);'><td style='border:1px solid #d3d3d3'>" + json_TDM.Avl_Wards[nCount].name + "</td></tr>";
        }
        for (var nCount = 0; nCount < json_TDM.Sel_Wards.length; nCount++) {
            selectedWards += "<tr id='" + json_TDM.Sel_Wards[nCount].id + "'  onClick='addRemoveHl(this);'><td style='border:1px solid #d3d3d3'>" + json_TDM.Sel_Wards[nCount].name + "</td></tr>";
        }
    }
    else
    {
        var ward_nm = "";
        for (var nCount = 0; nCount < json_TDM.Init_Wards.length; nCount++) {
            if (json_TDM.Init_Wards[nCount].id != sl_ward)
            {
                availableWards += "<tr id='" + json_TDM.Init_Wards[nCount].id + "'  onClick='addRemoveHl(this);'><td style='border:1px solid #d3d3d3'>" + json_TDM.Init_Wards[nCount].name + "</td></tr>";
            }
            else
            {
                ward_nm = json_TDM.Init_Wards[nCount].name;
            }
        }
        selectedWards += "<tr id='" + sl_ward + "'  onClick='addRemoveHl(this);'><td style='border:1px solid #d3d3d3'>" + ward_nm + "</td></tr>";

    }
    for (var nCount = 0; nCount < json_TDM.Avl_DOW.length; nCount++) {
        availableDOW += "<tr id='" + json_TDM.Avl_DOW[nCount].id + "' onClick='addRemoveHl(this);'><td style='border:1px solid #d3d3d3'>" + json_TDM.Avl_DOW[nCount].name + "</td></tr>";
    }
    for (var nCount = 0; nCount < json_TDM.Sel_DOW.length; nCount++) {
        selectedDOW += "<tr id='" + json_TDM.Sel_DOW[nCount].id + "' onClick='addRemoveHl(this);'><td style='border:1px solid #d3d3d3'>" + json_TDM.Sel_DOW[nCount].name + "</td></tr>";
    }
    for (var nCount = 0; nCount < json_TDM.Avl_MT.length; nCount++) {
        availableMT += "<tr id='" + json_TDM.Avl_MT[nCount].id + "' onClick='addRemoveHl(this);'><td style='border:1px solid #d3d3d3'>" + json_TDM.Avl_MT[nCount].name + "</td></tr>";
    }
    for (var nCount = 0; nCount < json_TDM.Sel_MT.length; nCount++) {
        selectedMT += "<tr id='" + json_TDM.Sel_MT[nCount].id + "' onClick='addRemoveHl(this);'><td style='border:1px solid #d3d3d3'>" + json_TDM.Sel_MT[nCount].name + "</td></tr>";
    }
    availableforms += "<tr id='4' onClick='addRemoveHl(this);'><td style='border:1px solid #d3d3d3'>Other</td></tr>"
    $("#lstAvailableForm  tbody:last").append(availableforms);
    $("#lstSelectedForm tbody:last").append(selectedforms);
    $("#lstAvailablePens  tbody:last").append(availablePens);
    $("#lstSelectedPens  tbody:last").append(selectedPens);
    $("#lstAvailableWards  tbody:last").append(availableWards);
    $("#lstSelectedWards  tbody:last").append(selectedWards);
    $("#lstAvailableDOW  tbody:last").append(availableDOW);
    $("#lstSelectedDOW  tbody:last").append(selectedDOW);
    $("#lstAvailableMenutype  tbody:last").append(availableMT);
    $("#lstSelectedMenuType  tbody:last").append(selectedMT);
    $("#chkSEFD", "#accFilter").attr('checked', 'checked');
    if (dt_s == "0")
    {
        $("#txtDigitalFilterStart").val(json_TDM.Start_TDM);
        $("#txtDigitalFilterEnd").val(json_TDM.End_TDM);
        $('#dtDigitalFilterStart').data({
            date: json_TDM.Start_TDM
        }).datepicker('update');
        $('#dtDigitalFilterEnd').data({
            date: json_TDM.End_TDM
        }).datepicker('update');
    }
    else
    {
        $("#txtDigitalFilterStart").val(dt_s);
        $("#txtDigitalFilterEnd").val(dt_s);
        $('#dtDigitalFilterStart').data({
            date: dt_s
        }).datepicker('update');
        $('#dtDigitalFilterEnd').data({
            date: dt_s
        }).datepicker('update');
    }
    //$("#accFilter").collapse('hide');
    //$("#btnFilter").click();
    //$("#accForms").collapse('show');
    //}, 200);
}

function exportDFExcel() {
    $.support.cors = true;
    var startdate = $("#txtDigitalFilterStart").val().split("/");
    var enddate = $("#txtDigitalFilterEnd").val().split("/");
    $.ajax({
        url: BACKENDURL + "data_upload/export_data",
        type: "post",
        data: {
            session_id: localStorage["SESSIONID"],
            start_date: startdate[2] + "-" + startdate[1] + "-" + startdate[0],
            end_date: enddate[2] + "-" + enddate[1] + "-" + enddate[0],
            ft: "1,2,3,4",
            p: pens,
            w: wards,
            dw: DOW,
            mt: menutype,
            isex: $("#chkSEFD").is(":checked") ? 1 : 0,
            export_type: "export_digital_form"
        },
        dataType: "json",
        async: false,
        crossDomain: true,
        success: function(data) {
            if (data.session_status) {
                $("#divErrForms").hide();
                if (data.error == 0) {
                    var url = BACKENDURL + "common/download_file";
                    $('<form action="' + url + "/" + localStorage["SESSIONID"] + "/export_digital_form/" + data.temp_file + '" style="display:none;"></form>').appendTo('body').submit();
                    //window.open(url + "/" + localStorage["SESSIONID"] + "/order_items/" + data.temp_file);
                }
                else if (data.error_msg == "Unauthorized access.") {
                    logout(1);
                } else
                    $("#divErrForms").html(data.error_msg).show();
            } else {
                logout();
            }
        },
        error: function(xhr, textStatus, error) {
            alert(error);
        }
    });
}
function exportTDM() {
    $.support.cors = true;
    var vales_TDM = getValues_TDM();
    var startdate_TDM = $("#txtDgStart_TDM").val().split("/");
    var enddate_TDM = $("#txtDgEnd_TDM").val().split("/");
    $.ajax({
        url: BACKENDURL + "data_upload/export_data",
        type: "post",
        data: {
            session_id: localStorage["SESSIONID"],
            start_date: startdate_TDM[2] + "-" + startdate_TDM[1] + "-" + startdate_TDM[0],
            end_date: enddate_TDM[2] + "-" + enddate_TDM[1] + "-" + enddate_TDM[0],
            ft: $("#ddlForm_TDM").val(),
            dfi_id: $("#ddlMenuTypes_TDM").val(),
            p: vales_TDM[0],
            w: vales_TDM[1],
            dw: vales_TDM[2],
            mt: vales_TDM[3],
            //isex: $("#chk_TDM").is(":checked") ? 1 : 0,
            export_type: "export_df_tdm"
        },
        dataType: "json",
        async: false,
        crossDomain: true,
        success: function(data) {
            if (data.session_status) {
                // $("#divErrForms").hide();
                if (data.error == 0) {
                    var url = BACKENDURL + "common/download_file";
                    $('<form action="' + url + "/" + localStorage["SESSIONID"] + "/export_df_tdm/" + data.temp_file + '" style="display:none;"></form>').appendTo('body').submit();
                    //window.open(url + "/" + localStorage["SESSIONID"] + "/order_items/" + data.temp_file);
                }
                /*else if (data.error_msg == "Unauthorized access.") {
                 logout(1);
                 } else
                 //  $("#divErrForms").html(data.error_msg).show();*/
            } else {
                logout();
            }
        },
        error: function(xhr, textStatus, error) {
            alert(error);
        }
    });
}
function exportCustomTDM() {
    $.support.cors = true;
    var vales_TDM = getValues_TDM();
    var startdate_TDM = $("#txtDgStart_TDM").val().split("/");
    var enddate_TDM = $("#txtDgEnd_TDM").val().split("/");
    $.ajax({
        url: BACKENDURL + "data_upload/export_data",
        type: "post",
        data: {
            session_id: localStorage["SESSIONID"],
            start_date: startdate_TDM[2] + "-" + startdate_TDM[1] + "-" + startdate_TDM[0],
            end_date: enddate_TDM[2] + "-" + enddate_TDM[1] + "-" + enddate_TDM[0],
            ft: $("#ddlForm_TDM").val(),
            dfi_id: $("#ddlMenuTypes_TDM").val(),
            p: vales_TDM[0],
            w: vales_TDM[1],
            dw: vales_TDM[2],
            mt: vales_TDM[3],
            //isex: $("#chk_TDM").is(":checked") ? 1 : 0,
            export_type: "export_df_tdm_custom"
        },
        dataType: "json",
        async: false,
        crossDomain: true,
        success: function(data) {
            if (data.session_status) {
                // $("#divErrForms").hide();
                if (data.error == 0) {
                    var url = BACKENDURL + "common/download_file";
                    $('<form action="' + url + "/" + localStorage["SESSIONID"] + "/export_df_tdm_custom/" + data.temp_file + '" style="display:none;"></form>').appendTo('body').submit();
                    //window.open(url + "/" + localStorage["SESSIONID"] + "/order_items/" + data.temp_file);
                }
                /*else if (data.error_msg == "Unauthorized access.") {
                 logout(1);
                 } else
                 //  $("#divErrForms").html(data.error_msg).show();*/
            } else {
                logout();
            }
        },
        error: function(xhr, textStatus, error) {
            alert(error);
        }
    });
}
/* function PrintTDM() {
 $("#tblTDMReport").removeClass('no-more-tables');
 $("#tblTDMReport").addClass('nomoretables');
 window.print();
 }
 *//* function PrintDailyMealOrders()
  {
  $("#tblTDMReport4").removeClass('no-more-tables');
  $("#tblTDMReport4").addClass('nomoretables');
  window.print();
  } */
function IndicatorOnChange(dlv)
{
    $("#btn_genreport").html('Generate Report');
    var tmp_dsc = $("option:selected", dlv).text().split("-");
    $('#txtTDMIndicator').val(tmp_dsc[1]);
    if ($(dlv).val() == 0)
        $('#txtTDMIndicator').attr('readonly', true);
    else
        $('#txtTDMIndicator').attr('readonly', false);
}

/************************** Daily Meal Orders *******************************/
function SrvLoadDailyMealOrders() {
    var url = BACKENDURL + "user/digital_form_load";
    var data = {
        session_id: localStorage["SESSIONID"],
        type: 'dmo'
    };
    MakeAjaxCall(url, data, LoadDailyMealOrders);
}

function LoadDailyMealOrders(data)
{
    if (data.session_status) {
        if (data.error == 0) {
            $("#lstAvailablePens_DMO > tbody > tr").remove();
            $("#lstSelectedPens_DMO > tbody > tr").remove();
            $("#lstAvailableWards_DMO > tbody > tr").remove();
            $("#lstSelectedWards_DMO > tbody > tr").remove();
            $("#lstAvailableDOW_DMO > tbody > tr").remove();
            $("#lstSelectedDOW_DMO > tbody > tr").remove();
            $("#lstAvailableMenuType_DMO > tbody > tr").remove();
            $("#lstSelectedMenuTypes_DMO > tbody > tr").remove();

            var data_forms = "", selectedNavStr = "selected", datauser_selectedPens = "", datauser_selectedWards = "";
            var datauser_selectedDOW = "<tr id='0' onClick='addRemoveHl(this);'><td style='border:1px solid #d3d3d3'>No Day of week</td></tr>";
            var datauser_selectedMenuType = "<tr id='0' onClick='addRemoveHl(this);'><td style='border:1px solid #d3d3d3'>No Menu Type</td></tr>";
            $("#ddlForms_DMO").empty();            
                var date = new Date();
                var n = date.toDateString();
                var time = date.toLocaleTimeString();
                var selectedOption = (time.indexOf("AM") > 0)?'Lunch':'Dinner';
                for (var nCount = 0; nCount < data.df.ft.length; nCount++) {
                    if (data.df.ft[nCount].tn == "Other") 
                    {
                        data_forms += "";
                    }
                    else {
                        data_forms += "<option value="+ data.df.ft[nCount].ftid +" id=" + data.df.ft[nCount].tn + ">"+data.df.ft[nCount].tn+"</option>";
                    }  
                }
            	$("#ddlForms_DMO", "#divFilApps_r2").append(data_forms);
                $("#ddlForms_DMO > option").each(function() {
                    if($(this).text() == selectedOption) {
                        $(this).attr('selected', 'selected');            
                    } 
                });
                              
            for (var nCount = 0; nCount < data.df.p.length; nCount++) {
                datauser_selectedPens += "<tr id='" + data.df.p[nCount].dpid + "'  onClick='addRemoveHl(this);'><td style='border:1px solid #d3d3d3'>" + data.df.p[nCount].pn + "</td></tr>";
            }
            for (var nCount = 0; nCount < data.df.w.length; nCount++) {
                var dataiW = new Object();
                dataiW.id = data.df.w[nCount].wid;
                dataiW.name = data.df.w[nCount].wn;
                initWards_DMO[nCount] = dataiW;
                datauser_selectedWards += "<tr id='" + data.df.w[nCount].wid + "'  onClick='addRemoveHl(this);'><td style='border:1px solid #d3d3d3'>" + data.df.w[nCount].wn + "</td></tr>";
            }
            for (var nCount = 0; nCount < data.df.d.length; nCount++) {
                datauser_selectedDOW += "<tr id='" + data.df.d[nCount].did + "'  onClick='addRemoveHl(this);'><td style='border:1px solid #d3d3d3'>" + data.df.d[nCount].dn + "</td></tr>";
            }
            for (var nCount = 0; nCount < data.df.mt.length; nCount++) {
                var hiphen = (data.df.mt[nCount].mtd == "") ? "" : "-";
                datauser_selectedMenuType += "<tr id='" + data.df.mt[nCount].mtid + "' onClick='addRemoveHl(this);'><td style='border:1px solid #d3d3d3'>" + '[' + data.df.mt[nCount].mttype + ']' + hiphen + data.df.mt[nCount].mtd + "</td></tr>";
            }

            $("#lstSelectedPens_DMO  tbody:last").append(datauser_selectedPens);
            $("#lstSelectedWards_DMO  tbody:last").append(datauser_selectedWards);
            $("#lstSelectedDOW_DMO  tbody:last").append(datauser_selectedDOW);
            $("#lstSelectedMenuTypes_DMO  tbody:last").append(datauser_selectedMenuType);
            DateVal_DMO();
            //iconoksign();
        } else
            logout(1);
    }
}
function DateVal_DMO() {
    var today = new Date();
    var t = today.getDate() + "/" + (today.getMonth() + 1) + "/" + today.getFullYear();
    $("#dtDates_DMO").datepicker({endDate: new Date()}).on('changeDate', function(ev) {
        $('#dtDates_DMO').datepicker('hide');
		if (!$("#diviconDates_DMO li:first").hasClass("icon-ok"))
            $("#diviconDates_DMO").prepend('<li class="icon-ok"></i>');
    });
    $("#txtDate_DMO").val(t);
    $('#dtDates_DMO').data({
        date: t
    }).datepicker('update');
}
function exportDMO(expType){
    var vales_DMO = getValues_DMO();
    var startdate_DMO = $("#txtDate_DMO").val().split("/");
    $.support.cors = true;
    $.ajax({
        url: BACKENDURL + "data_upload/export_data",
        type: "post",
        data: {
            session_id: localStorage["SESSIONID"],
            export_type: expType,
             start_date: startdate_DMO[2] + "-" + startdate_DMO[1] + "-" + startdate_DMO[0],
        ft: $('#ddlForms_DMO').val(),
        p: vales_DMO[0],
        w: vales_DMO[1],
        dw: vales_DMO[2],
        mt: vales_DMO[3]
        },
        dataType: "json",
        async: false,
        crossDomain: true,
        success: function(data) {
            if (data.session_status) {
               if (data.error == 0) {
                    var url = BACKENDURL + "common/download_file";
                    $('<form action="' + url + "/" + localStorage["SESSIONID"] + "/"+expType+"/" + data.temp_file + '" style="display:none;"></form>').appendTo('body').submit();
                } else if(data.error_msg == "Unauthorized access.") 
                    logout(1);
                }else {
                logout();
            }
        },
        error: function(xhr, textStatus, error) {
            alert(error);
        }
    });
}
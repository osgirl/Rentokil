var endDateGraph;
var startDateGraph;
var json_res = [];
var json_Hdata = [];
var adhoc_report = false;
//For todays date;
Date.prototype.today = function() {
    return ((this.getDate() < 10) ? "0" : "") + this.getDate() + "/" + (((this.getMonth() + 1) < 10) ? "0" : "") + (this.getMonth() + 1) + "/" + this.getFullYear()
};
//For the time now
Date.prototype.timeNow = function() {
    return ((this.getHours() < 10) ? "0" : "") + this.getHours() + ":" + ((this.getMinutes() < 10) ? "0" : "") + this.getMinutes() + ":" + ((this.getSeconds() < 10) ? "0" : "") + this.getSeconds();
};

// To add n days
Date.prototype.addDays = function(days) {
    this.setDate(this.getDate() + days);
    return this;
};

function btn_active()
{
    var chart_type = 'p';
    if ($("#btnCount").hasClass("active"))
        chart_type = 'c';
    else if ($("#btnAverage").hasClass("active"))
        chart_type = 'a';
    return chart_type;
}
function showAccFilter() {
    $("#accCharts").collapse('hide');

}
/*function showGraph() {
 $("#accAccountFilter").collapse('hide');
 }
 function showQAFilter() {
 $("#accAccountChart").collapse('hide');
 }
 */
function LoadPageData() {
    var winW = $(window).width();
    if (winW < '768') {
        $("#tabbable_QAD").removeClass('tabs-left');
        $("#btnGroupQA").addClass('btn-group-vertical');			
        $("#btnGroupQA").css('margin-top', '20px');
        $("#btnGroupQA").css('margin-left', '10px');
		$("#btnGroupAdSla_report").addClass('btn-group-vertical');	
        $("#centerMonth3").removeClass('pull-right');
        $("#centerMonth3").addClass('pull-left')

    }
    else {

        $("#btnGroupQA").removeClass('btn-group-vertical');
		$("#btnGroupQA").css('margin-left', '10px');
        $("#tabbable_QAD").addClass('tabs-left');        
		 $("#btnGroupAdSla_report").removeClass('btn-group-vertical');
        $("#centerMonth3").addClass('pull-right');
        $("#centerMonth3").removeClass('pull-left')

    }
   // QA_RunAdhoc(); // renamed QA_Dashboard
    QA_ReportBuilder();
}

function QA_ReportBuilder()
{
    var url = BACKENDURL + "user/get_sla_report";
    var data = {
        session_id: localStorage["SESSIONID"]
    };
    MakeAjaxCall(url, data, LoadReportBuilder);
}
function LoadReportBuilder(data) {
    //data = '{"sla_res":{"access":[{"cr_sla_access":"0","adhoc_access":"1","view_sla_access":"1"}],"report":[{"id":"3","report_name":"Report30","account_id":"30","account_name":"Accone","lm":"2013-10-30 15:48:09","sla_mod_access":"0","adhoc_access":"0"}]},"error":false,"session_status":true}';
    //data = JSON.parse(data);
    $("#tblQADashboard tbody:last").empty();
    if (data.session_status) {
        if (data.error == 0) {
            var nCurrRecRound = 0;
            $("#tblQADashboard tbody:last").empty();
            var hdnCurrPage = $("#currPageNumber", "#tablePagination").val();
            if (hdnCurrPage != undefined) {
                nCurrRecRound = hdnCurrPage - 1;
            }
            $("#tablePagination", "#divReportBuilder").remove();
            $("#lblReportListEmptyError", "#divtbl_ReportList").hide();

            
            if ((data.sla_res.access[0].cr_sla_access == 1) && ((data.sla_res.access[0].adhoc_access == 1)) ) {
                $("#btnCreateReport").show();
                $("#btnCreateAdhoc").show();
            }
            else if (data.sla_res.access[0].cr_sla_access == 1) {
                $("#btnGroupAdSla_report").removeClass('btn-group segment-button');
                $("#btnCreateReport").show();
            }
            else if (data.sla_res.access[0].adhoc_access == 1) {
                $("#btnGroupAdSla_report").removeClass('btn-group segment-button');
                $("#btnCreateAdhoc").show();
            }

            if (data.sla_res.report.length > 0) {
                var tblQaDashBoard, lastModifiedDate, reportId = 0, lMDArr = [];
                for (var nCount = 0; nCount < data.sla_res.report.length; nCount++) {
                    lastModifiedDate = data.sla_res.report[nCount].lm;
                    reportId = data.sla_res.report[nCount].id;                    
                    if ((lastModifiedDate != '') && (lastModifiedDate != 'NULL')) {
                        lMDArr = lastModifiedDate.split(" ");
                        lastModifiedDate = convertDateToDMY(lMDArr[0]) + ' ' + lMDArr[1];
                    } else {
                        lastModifiedDate = '';
                    }
                    tblQaDashBoard += "<tr><td data-title='Report Name' nowrap='nowrap'><div style='padding-top: 6px'>" + data.sla_res.report[nCount].report_name + "</div></td>";
                    tblQaDashBoard += "<td data-title='Account' nowrap='wrap'><div style='padding-top: 6px'>" + data.sla_res.report[nCount].account_name + "</div></td>";
                    tblQaDashBoard += " <td data-title='Last Modified' nowrap='wrap'><div style='padding-top: 6px'>" + lastModifiedDate + "</div></td>";
                    if (data.sla_res.report[nCount].sla_mod_access == 1) {
                        tblQaDashBoard += "<td nowrap='wrap' style='text-align: right;'><button style='margin-right: 10px;' data-toggle='modal' href='#divRBDelete' onclick='javascript:return deleteSLAReports(" + data.sla_res.report[nCount].id + ',' + data.sla_res.report[nCount].account_id + ");return false;' id='btQADB_del" + data.sla_res.report[nCount].id + "' class='btn btn-danger btn-small'><i class='icon-white icon-trash'></i> Delete</button><button style='margin-right: 10px;' id='btQADB_edit" + data.sla_res.report[nCount].id + "' class='btn btn-small'><i class='icon-pencil'></i> Edit</button><button onclick='javascript:return ViewRB();return false;' id='btQADB_view" + data.sla_res.report[nCount].id + "' class='btn btn-small btn-success'><i class='icon-play-circle icon-white'></i> View</button></td>";
                    }
                    else {
                        tblQaDashBoard += "<td nowrap='wrap' style='text-align: right;'><button onclick='javascript:return ViewRB();return false;' id='btQADB_view" + data.sla_res.report[nCount].id + "' class='btn btn-small btn-success'><i class='icon-play-circle icon-white'></i> View</button></td>";
                    }
                    tblQaDashBoard += "</tr>";
                    $("#tblQADashboard tbody:last").append(tblQaDashBoard);
                    tblQaDashBoard = "";
                    if (data.sla_res.report[nCount].id == reportId) {
                        nCurrRecRound = Math.floor(nCount / 10);
                    }
                }
                $("#tblQADashboard").show();
                if (data.sla_res.report.length > 10) {
                    $("#tablePagination").html('');
                    $("#tblQADashboard").tablePagination({
                        rowsPerPage: 10,
                        currPage: nCurrRecRound
                    });
                }
            } else {
                if ((data.sla_res.report.length == 0) && (data.sla_res.access[0].adhoc_access == 1) && (data.sla_res.access[0].cr_sla_access == 0)) {
                        createAdhoc('DBD');
                }
                $("#tblQADashboard").hide();
                $("#lblReportListEmptyError", "#divtbl_ReportList").text('No records available').addClass("alert-danger").show();
            }
        } else if (data.error_msg == "Unauthorized access.") {
            logout(1);
        } else {
            $("#btnCreateAdhoc").hide();
            $("#btnCreateReport").hide();
            $("#tblQADashboard").hide();
            $("#lblReportListEmptyError", "#divtbl_ReportList").text(data.error_msg).addClass("alert-danger").show();
        }
    }
    else {
        localStorage.clear();
        location.href = 'index.html';
    }

}

function deleteSLAReports(reportid, accountid)
{
    bootbox.dialog({
        message: "Are you sure you want to delete this report?",
        title: "Delete Report?",
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
                    var url = BACKENDURL + "user/delete_sla_report";
                    var data = {
                        session_id: localStorage["SESSIONID"],
                        report_id: reportid,
                        account_id: accountid
                    };
                    MakeAjaxCall(url, data, LoadPageData);
                }
            }
        }
    });
}



function ff_days(f_day, t_day, pn)
{
    // Start date
    if (pn == 0)
        var selDateArr = $("#txtRBStartDate").val().split("/");
    else
        var selDateArr = $("#txtRBEndDate").val().split("/");
    var startDate = new Date(selDateArr[2].substring(0, 4), (selDateArr[1] - 1), selDateArr[0], 0, 0, 0, 0).addDays(f_day);
    var spicker = $("#divRBStartDate").data("datetimepicker");
    spicker.setLocalDate(new Date(startDate.getFullYear() + "/" + (startDate.getMonth() + 1) + "/" + startDate.getDate() + " 00:00:00"));
    $("#txtRBStartDate").val(startDate.getDate() + "/" + (startDate.getMonth() + 1) + "/" + startDate.getFullYear() + " 00:00:00");
    // End date
    var endDate = new Date(selDateArr[2].substring(0, 4), (selDateArr[1] - 1), selDateArr[0], 0, 0, 0, 0).addDays(t_day);
    var epicker = $("#divRBEndDate").data("datetimepicker");
    epicker.setLocalDate(new Date(endDate.getFullYear() + "/" + (endDate.getMonth() + 1) + "/" + endDate.getDate() + " 23:59:59"));
    $("#txtRBEndDate").val(endDate.getDate() + "/" + (endDate.getMonth() + 1) + "/" + endDate.getFullYear() + " 23:59:59");
    localStorage["fltr_acc"] = "0";
    srv_showAccount();
}

function QA_datetime()
{
    var currentdate = new Date();
    var datetime = currentdate.today() + " " + "00:00:00";
    var datetime2 = currentdate.today() + " " + "23:59:59";
    $('#divRBStartDate').datetimepicker().on('changeDate', function(ev) {
        //$('#divRBStartDate').datetimepicker('hide');
        if (($("#txtRBStartDate").val() == datetime) && ($("#txtRBEndDate").val() == datetime2)) {
            $("#diviconDates li:first").remove();
        } else {
            $("#diviconDates li:first").remove();
            $("#diviconDates").prepend('<li class="icon-ok"></i>');
        }
    });
    $("#txtRBStartDate").val(datetime);
    var selDateArr = $("#txtRBStartDate").val().split("/");
    var startDate = new Date(selDateArr[2].substring(0, 4), (selDateArr[1] - 1), selDateArr[0], 0, 0, 0, 0);
    var spicker = $("#divRBStartDate").data("datetimepicker");
    spicker.setLocalDate(new Date(startDate.getFullYear() + "/" + (startDate.getMonth() + 1) + "/" + startDate.getDate() + " " + selDateArr[2].substring(5)));

    $('#divRBEndDate').datetimepicker().on('changeDate', function(ev) {
        //$('#divRBEndDate').datetimepicker('hide');
        if (($("#txtRBStartDate").val() == datetime) && ($("#txtRBEndDate").val() == datetime2)) {
            $("#diviconDates li:first").remove();
        } else {
            $("#diviconDates li:first").remove();
            $("#diviconDates").prepend('<li class="icon-ok"></i>');
        }
    });
    $("#txtRBEndDate").val(datetime2);
    var selDateArr = $("#txtRBEndDate").val().split("/");
    var startDate = new Date(selDateArr[2].substring(0, 4), (selDateArr[1] - 1), selDateArr[0], 0, 0, 0, 0);
    var epicker = $("#divRBEndDate").data("datetimepicker");
    epicker.setLocalDate(new Date(startDate.getFullYear() + "/" + (startDate.getMonth() + 1) + "/" + startDate.getDate() + " " + selDateArr[2].substring(5)));

}

function QA_RunAdhoc() //renamed QA_Dashboard
{

    $("#txtRBdates").hide();
    $("#txtRBEd").hide();
    QA_datetime();
    loadQAAccFilter();
    $("#QAAccResetAll").bind("click", resetAll_Acc);
    $("#7daysPrev").bind("click", function() {
        ff_days(-7, -1, 0);
    });
    $("#7daysNext").bind("click", function() {
        ff_days(1, 7, 1);
    });
    $("#30daysPrev").bind("click", function() {
        ff_days(-30, -1, 0);
    });
    $("#30daysNext").bind("click", function() {
        ff_days(1, 30, 1);
    });
    $("#ddldashboard").change(function() {
        var btn_act = btn_active();
        viewRBDashboard(btn_act);
    });
    $("#lnkGraph").click(function() {
        $("#accAccountFilter").collapse('hide');
        localStorage["fltr_acc"] = "1";
        srv_showAccount();
    });
    var today = new Date();
    var t = today.getDate() + "/" + (today.getMonth() + 1) + "/" + today.getFullYear();
    var nextDay = new Date().addDays(1);
    var nD = nextDay.getDate() + "/" + (nextDay.getMonth() + 1) + "/" + nextDay.getFullYear();
    $("#divGraphInfo").append("The chart was last updated on " + t + " at 00:01. The next update is due on " + nD + " at 19:00");
    //Export QA
    $("#btnExportQA").bind("click", {exportType: "export_quality_auditor_filter"}, function(event) {
        exportQA(event.data.exportType);
    });
    $("#btnAddSite", "#divAccSite").click(function() {
        var errMsg = "Please select from available Sites";
        addTable_QA(lstAvailableSites, lstSelectedSites, diviconAccSites, divAccSiteErrMsg, errMsg, 1);
    })
    $("#btnRemoveSite", "#divAccSite").click(function() {
        var errMsg = "Please select from selected Sites";
        remTable_QA(lstAvailableSites, lstSelectedSites, diviconAccSites, divAccSiteErrMsg, errMsg, 1);
    })
    $("#btnAddArea", "#divAccArea").click(function() {
        var errMsg = "Please select from available Areas";
        addTable_QA(lstAvailableAreas, lstSelectedAreas, diviconAccArea, divAccAreaErrMsg, errMsg, 2);
    })
    $("#btnRemoveArea", "#divAccArea").click(function() {
        var errMsg = "Please select from selected Areas";
        remTable_QA(lstAvailableAreas, lstSelectedAreas, diviconAccArea, divAccAreaErrMsg, errMsg, 2);
    })
    $("#btnAddSubarea", "#divAccSubArea").click(function() {
        var errMsg = "Please select from available Sub areas";
        addTable_QA(lstAvailableSubareas, lstSelectedSubareas, diviconAccSubArea, divAccSubAreaErrMsg, errMsg, 3);
    })
    $("#btnRemoveSubarea", "#divAccSubArea").click(function() {
        var errMsg = "Please select from selected Sub areas";
        remTable_QA(lstAvailableSubareas, lstSelectedSubareas, diviconAccSubArea, divAccSubAreaErrMsg, errMsg, 3);
    })
    $("#btnAddPoint", "#divAccPoint").click(function() {
        var errMsg = "Please select from available Points";
        addTable(lstAvailablePoints, lstSelectedPoints, diviconAccPoint, divAccPointErrMsg, errMsg);
    })
    $("#btnRemovePoint", "#divAccPoint").click(function() {
        var errMsg = "Please select from selected Points";
        remTable(lstAvailablePoints, lstSelectedPoints, diviconAccPoint, divAccPointErrMsg, errMsg);
    })
    $("#btnAddAuditor", "#divAccAuditor").click(function() {
        var errMsg = "Please select from available Auditors";
        addTable(lstAvailableAuditors, lstSelectedAuditors, diviconAccAuditor, divAccAuditorErrMsg, errMsg);
    })
    $("#btnRemoveAuditor", "#divAccAuditor").click(function() {
        var errMsg = "Please select from selected Auditors";
        remTable(lstAvailableAuditors, lstSelectedAuditors, diviconAccAuditor, divAccAuditorErrMsg, errMsg);
    })
}

function loadQAAccFilter() {
    SrvLoadQualityAuditor();
}

// initial load service 
function SrvLoadQualityAuditor() {
    var url = BACKENDURL + "user/view_quality_auditor_load";
    var data = {
        session_id: localStorage["SESSIONID"]
    };
    MakeAjaxCall(url, data, loadQualityAuditor);
}

function loadQualityAuditor(data) {
    json_Hdata = data;
    if (data.session_status) {
        if (data.error == 0) {
            $('#ddlRP_Accts').empty();
            var acctsStr = "";
            var selectedStr = " Selected ";
            $("#lstAvailableAreas > tbody > tr").remove();
            $("#lstSelectedAreas > tbody > tr").remove();
            $("#lstAvailableSubareas > tbody > tr").remove();
            $("#lstSelectedSubareas > tbody > tr").remove();
            $("#lstAvailablePoints > tbody > tr").remove();
            $("#lstSelectedPoints > tbody > tr").remove();
            $("#lstAvailableAuditors > tbody > tr").remove();
            $("#lstSelectedAuditors > tbody > tr").remove();
            var datauser_selectedAuditors = "";
            for (var nCount = 0; nCount < data.qa_res.Account.length; nCount++)
            {
                acctsStr += "<option value=" + data.qa_res.Account[nCount].ac_id + " " + selectedStr + ">" + data.qa_res.Account[nCount].ac + "</option>";
                selectedStr = "";
            }
            $('#ddlRP_Accts').append(acctsStr);
            var accountSel = $("#ddlRP_Accts :selected").text();
            $("#ddlRP_Accts").change(function() {
               if ($("#ddlRP_Accts :selected").text() == accountSel) {
                    $("#diviconAccs li:first").remove();
                } else {
                    $("#diviconAccs li:first").remove();
                    $("#diviconAccs").prepend('<li class="icon-ok"></i>');
                }
               loadSites(json_Hdata.qa_res.Site[$('#ddlRP_Accts').val()]);
               loadAuditor(json_Hdata.qa_res.Auditor);
            })

            loadSites(data.qa_res.Site[$('#ddlRP_Accts').val()]);
            loadAuditor(data.qa_res.Auditor);
            $("#qa_filter").addClass('disabled');
        } else
            logout(1);
    }
}
// load Sites , Area, SubArea, Points, Audits 
function loadSites(data_site)
{
	$("#lstAvailableSites > tbody > tr").remove();
    $("#lstSelectedSites > tbody > tr").remove();
    $("#lstAvailableAreas > tbody > tr").remove();
    $("#lstSelectedAreas > tbody > tr").remove();
    $("#lstAvailableSubareas > tbody > tr").remove();
    $("#lstSelectedSubareas > tbody > tr").remove();
    $("#lstAvailablePoints > tbody > tr").remove();
    $("#lstSelectedPoints > tbody > tr").remove();

    if (data_site != undefined && data_site.length > 0)
    {
        var datauser_selectedSites = "";
        for (var nCount = 0; nCount < data_site.length; nCount++) {
            datauser_selectedSites += "<tr id='" + data_site[nCount].st_id + "'  onClick='addRemoveItem(this);'><td style='border:1px solid #d3d3d3'>" + data_site[nCount].st + "</td></tr>";
        }
        $("#lstAvailableSites  tbody:last").append(datauser_selectedSites);
    }
}

function loadArea(data_area, site_ids, add_remove)
{
	if(add_remove == 1) {//For adding new site
		var datauser_selectedAreas = "";
		for (var nCount = 0; nCount < site_ids.length; nCount++) {
			var data_a = data_area[site_ids[nCount]];
	        for (var jCount = 0; jCount < data_a.length; jCount++)
	            datauser_selectedAreas += "<tr id='" + data_a[jCount].arn_id + "'  onClick='addRemoveItem(this);'><td style='border:1px solid #d3d3d3'>" + data_a[jCount].arn + " (" + data_a[jCount].h1 + ")" + "</td></tr>";
	    }
		 $("#lstAvailableAreas  tbody:last").append(datauser_selectedAreas);
		 Sort_table("lstAvailableAreas");
	} else { // For remove
		var removed_ids = [];
		for (var nCount = 0; nCount < site_ids.length; nCount++) {
			var data_a = data_area[site_ids[nCount]];
	        for (var jCount = 0; jCount < data_a.length; jCount++) {
	        	 $("#lstSelectedAreas > tbody > tr").each(function (e){
	        		 if($(this).attr("id") == data_a[jCount].arn_id){
	        			 removed_ids.push(data_a[jCount].arn_id);
	        			 $(this).detach();
	        		 }
	        	 });
	        	 $("#lstAvailableAreas > tbody > tr").each(function (e){
	        		 if($(this).attr("id") == data_a[jCount].arn_id)
	        			 $(this).detach();
	        	 });
	        } 
	     }
		Sort_table("lstSelectedAreas");
		Sort_table("lstAvailableAreas");
		loadSubArea(json_Hdata.qa_res.SubArea, removed_ids, 0);
	}
}

function loadSubArea(data_sArea, area_ids, add_remove)
{
	
	if(add_remove == 1){ //For adding new site
		var datauser_selectedSubareas = "";
		for (var nCount = 0; nCount < area_ids.length; nCount++) {
        var data_sa = data_sArea[area_ids[nCount]];
        for (var jCount = 0; jCount < data_sa.length; jCount++)
            datauser_selectedSubareas += "<tr id='" + data_sa[jCount].sub_id + "'  onClick='addRemoveItem(this);'><td style='border:1px solid #d3d3d3'>" + data_sa[jCount].san + " (" + data_sa[jCount].h1 + ", " + data_sa[jCount].h2 + ")" + "</td></tr>";
		}
	    $("#lstAvailableSubareas  tbody:last").append(datauser_selectedSubareas);
	    Sort_table("lstAvailableSubareas");
	} else {
		var removed_ids = [];
		for (var nCount = 0; nCount < area_ids.length; nCount++) {
			var data_a = data_sArea[area_ids[nCount]];
	        for (var jCount = 0; jCount < data_a.length; jCount++) {
	        	 $("#lstSelectedSubareas > tbody > tr").each(function (e){
	        		 if($(this).attr("id") == data_a[jCount].sub_id){
	        			 removed_ids.push(data_a[jCount].sub_id);
	        			 $(this).detach();
	        		 }
	        	 });
	        	 $("#lstAvailableSubareas > tbody > tr").each(function (e){
	        		 if($(this).attr("id") == data_a[jCount].sub_id)
	        			 $(this).detach();
	        	 });
	        }
	     }
		Sort_table("lstSelectedSubareas");
		Sort_table("lstAvailableSubareas");
		loadPoints(json_Hdata.qa_res.Point, removed_ids, 0);
	}
}

function loadPoints(data_point,subarea_ids, add_remove){
		var sel_points = [], datauser_selectedPoints = "";
		subarea_ids = [];
		 $("#lstSelectedPoints > tbody > tr").remove();
		 $("#lstSelectedSubareas > tbody > tr").each(function (e){
			 subarea_ids.push($(this).attr("id"));
		 });
		for (var nCount = 0; nCount < subarea_ids.length; nCount++) {
			 var data_p = data_point[subarea_ids[nCount]];
			 for (var jCount = 0; jCount < data_p.length; jCount++)
	          {
				 if (!($.inArray(data_p[jCount].po, sel_points) > -1))
	                {
					 sel_points.push(data_p[jCount].po);
	                   datauser_selectedPoints += "<tr id='" + data_p[jCount].po + "'  onClick='addRemoveItem(this);'><td style='border:1px solid #d3d3d3'>" + data_p[jCount].po + "</td></tr>";
	                }
	          }
		}
		$("#lstSelectedPoints  tbody:last").append(datauser_selectedPoints);
	
		for(var i =0; i<sel_points.length;i++){
			$("#lstAvailablePoints  > tbody > tr").each(function (e){
				if($(this).attr("id") == sel_points[i])
					$(this).detach();
			});
		}
		Sort_table("lstSelectedPoints");
		Sort_table("lstAvailablePoints");
		$('#diviconAccPoint li:first').remove();
	    if (($("#lstAvailablePoints tr").length) > 0)
	        $("#" + iconPH.id).prepend('<li class="icon-ok"></i>');
}

function loadAuditor(data_account)
{
    var datauser_selaccts = "";
    $("#lstSelectedAuditors > tbody > tr").remove();
    $("#lstAvailableAuditors > tbody > tr").remove();
    for (var nCount = 0; nCount < data_account.length; nCount++) {
        if (data_account[nCount].ac_id == $('#ddlRP_Accts').val())
            datauser_selaccts += "<tr id='" + data_account[nCount].qaid + "'  onClick='addRemoveItem(this);'><td style='border:1px solid #d3d3d3'>" + data_account[nCount].audn + "</td></tr>";
    }
    $("#lstSelectedAuditors  tbody:last").append(datauser_selaccts);
}
// Add & Remove table functionality 
function addTable_QA(avlTable, selTable, iconPH, errId, err_msg, id)
{
    var count = 0,added_ids = [];
    $("#" + avlTable.id + "  tr").each(function(e) {
        if ($(this).hasClass("alert alert-success")) {
            count++;
            added_ids.push($(this).attr("id"));
            $("#" + selTable.id + " tbody").append($(this).removeClass("alert alert-success").clone());
            $(this).detach();
        }
    });
    $("#" + iconPH.id + " li:first").remove();
    if (($("#" + selTable.id + " tr").length) > 0) 
        $("#" + iconPH.id).prepend('<li class="icon-ok"></i>');

    if (count == 0)
        $('#' + errId.id).html(err_msg).show();
    else {
        $('#' + errId.id).hide();
        $("#divAccFilterErrMsg").hide();
        Sort_table(selTable.id);
        if (id == "1")
            loadArea(json_Hdata.qa_res.Area, added_ids,1);
        else if (id == "2")
            loadSubArea(json_Hdata.qa_res.SubArea, added_ids, 1);
        else
           loadPoints(json_Hdata.qa_res.Point, added_ids, 1);
    }
    
    if (($("#lstSelectedPoints  tr").length == 0) || ($("#lstSelectedAuditors  tr").length == 0)) {
        $("#qa_filter").addClass('disabled');
    }
    else
    {
        $("#qa_filter").removeClass('disabled');
        /* $("#qa_filter").bind("click", srv_showAccount); */
        $("#qa_filter").unbind("click").click(function() {
            localStorage["fltr_acc"] = "1";
            $("#accAccountFilter").collapse('hide');
            setTimeout(function() {
                $("#accCharts").collapse('show');
            }, 500);
            srv_showAccount();
        });
    }

}

function remTable_QA(avlTable, selTable, iconPH, errId, err_msg, id)
{
    var count = 0, removed_ids = [];
    $("#" + selTable.id + "  tr").each(function(e) {
        if ($(this).hasClass("alert alert-success")) {
            count++;
            removed_ids.push($(this).attr("id"));
            $("#" + avlTable.id + " tbody").append($(this).removeClass("alert alert-success").clone());
            $(this).detach();
        }
    });
    $('#' + iconPH.id + ' li:first').remove();
    if (($("#" + selTable.id + " tr").length) > 0)
        $("#" + iconPH.id).prepend('<li class="icon-ok"></i>');
    if (count == 0)
        $('#' + errId.id).html(err_msg).show();
    else {
        $('#' + errId.id).hide();
        $("#divAccFilterErrMsg").hide();
        Sort_table(avlTable.id);
        if (id == "1")
            loadArea(json_Hdata.qa_res.Area, removed_ids,0);
        else if (id == "2")
            loadSubArea(json_Hdata.qa_res.SubArea, removed_ids, 0);
        else
            loadPoints(json_Hdata.qa_res.Point, removed_ids, 0);
    }
    
    if (($("#lstSelectedPoints  tr").length == 0) || ($("#lstSelectedAuditors  tr").length == 0)) {
        $("#qa_filter").addClass('disabled');
    }
    else
    {
        $("#qa_filter").removeClass('disabled');
        /* $("#qa_filter").bind("click", srv_showAccount); */
        $("#qa_filter").unbind("click").click(function() {
            localStorage["fltr_acc"] = "1";
            $("#accAccountFilter").collapse('hide');
            setTimeout(function() {
                $("#accCharts").collapse('show');
            }, 500);
            srv_showAccount();
        });
    }
}

function addRemoveItem(f) {
    if ($(f).hasClass("alert alert-success"))
        $(f).removeClass("alert alert-success");
    else
        $(f).addClass("alert alert-success");
}

// reset all functionality 
function resetAll_Acc() {
    HideErrMsg_Acc();
    loadQAAccFilter();
}

function HideErrMsg_Acc() {
    QA_datetime();
    $("#divAccErrMsg").hide();
    $("#divAccSiteErrMsg").hide();
    $("#divAccAreaErrMsg").hide();
    $("#divAccSubAreaErrMsg").hide();
    $("#divAccPointErrMsg").hide();
    $("#divAccAuditorErrMsg").hide();
    $("#diviconDates li:first").hasClass("icon-ok");
    $("#diviconDates li:first").remove();
    $("#diviconAccs li:first").hasClass("icon-ok");
    $("#diviconAccs li:first").remove();
    $("#diviconAccSites li:first").hasClass("icon-ok");
    $("#diviconAccSites li:first").remove();
    $("#diviconAccArea li:first").hasClass("icon-ok");
    $("#diviconAccArea li:first").remove();
    $("#diviconAccSubArea li:first").hasClass("icon-ok");
    $("#diviconAccSubArea li:first").remove();
    $("#diviconAccPoint li:first").hasClass("icon-ok");
    $("#diviconAccPoint li:first").remove();
    $("#diviconAccAuditor li:first").hasClass("icon-ok");
    $("#diviconAccAuditor li:first").remove();
}

// Quality Dashboard - Graph 
function navigateChart(chart_type, parent_id) {
    var dashboard_type = $("#ddldashboard").val();
    var dashboard_type_up = (dashboard_type == "Account") ? "Site" : ((dashboard_type == "Site") ? "Area" : ((dashboard_type == "Area") ? "Subarea" : ((dashboard_type == "Subarea") ? "Point" : "")));
    $("#ddldashboard").val(dashboard_type_up);
    var data_val = json_res.qa_res[$("#ddldashboard").val()];
    populateAccount_count(chart_type, $("#ddldashboard").val(), data_val, json_res.qa_res["Indicator"],parent_id);
}

function populateAccount_count(chart_type, dashboard_type, data, dt_score,parent_id) {
	   var startDateArr = $("#txtRBStartDate").val().split("/");
	    var endDateArr = $("#txtRBEndDate").val().split("/");
	    var startDate = startDateArr[2].substring(0, 4) + "/" + startDateArr[1] + "/" + startDateArr[0];
	    var endDate = endDateArr[2].substring(0, 4) + "/" + endDateArr[1] + "/" + endDateArr[0];
	    var endDateGraph = convertDate(endDate);
	    var startDateGraph = convertDate(startDate);
	    endDateGraph = endDateGraph + "' " + endDateArr[2];
	    startDateGraph = startDateGraph + "' " + startDateArr[2];
	    var sub_tit = 'From ' + startDateGraph + ' to ' + endDateGraph + '. ';
	    
	if(dt_score.length == 0){
		$("#divAccountChart").attr("style","height:0px;").html("");
        $("#divAccountChartErr").html("Data not Available " + sub_tit + "").removeClass('hide');
        $("#divExportQAbtn").hide();
        return false;
	}

	var chktype = [],jsondataObj = [],dataArr = [], sno_arr = [];
    var max_points = 20; max_point_length = 20;
    if ((chart_type == "c") || (chart_type == "p"))
    {
    	var dashboard_type = $("#ddldashboard").val();
    	if(dashboard_type == "Site"){
    		for (var nCount = 0,sno=0; nCount < json_res.qa_res["Site_list"].length; nCount++) {
    			chktype.push({id: json_res.qa_res["Site_list"][nCount].sid, val: json_res.qa_res["Site_list"][nCount].sname});
    			sno_arr[json_res.qa_res["Site_list"][nCount].sid] = sno++;
    		}
    		for (var nCount = 0; nCount < dt_score.length; nCount++) {
        		dataArr[dt_score[nCount].ind_id] = [];
        		for (var j = 0; j < chktype.length; j++)
        			dataArr[dt_score[nCount].ind_id][j] = 0;
        	}
    		for(var i=0; i<data.length;i++)
        		dataArr[data[i]['indid']][sno_arr[data[i]['sid']]]= parseInt(data[i]['cnt']);
        	for (var nCount = 0; nCount < dt_score.length; nCount++)
        		jsondataObj[nCount] = {name: dt_score[nCount].ind_name, data: dataArr[dt_score[nCount].ind_id], stack: dt_score[nCount].group_id};
    	} 
    else if(dashboard_type == "Area"){
    	for (var nCount = 0,sno=0; nCount < json_res.qa_res["Area_list"].length; nCount++){
    		if(parent_id != "" && json_res.qa_res["Area_list"][nCount].pid != parent_id) continue;
    			chktype.push({id: json_res.qa_res["Area_list"][nCount].sid, val: json_res.qa_res["Area_list"][nCount].sname});
    			sno_arr[json_res.qa_res["Area_list"][nCount].sid] = sno++; 
    	}
    	for (var nCount = 0; nCount < dt_score.length; nCount++) {
    		dataArr[dt_score[nCount].ind_id] = [];
    		for (var j = 0; j < chktype.length; j++)
    			dataArr[dt_score[nCount].ind_id][j] = 0;
    	}
    	for(var i=0; i<data.length;i++) {
    		if(dataArr[data[i]['indid']][sno_arr[data[i]['sid']]] != undefined)
    		dataArr[data[i]['indid']][sno_arr[data[i]['sid']]]= parseInt(data[i]['cnt']);
    	}
    	for (var nCount = 0; nCount < dt_score.length; nCount++)
    		jsondataObj[nCount] = {name: dt_score[nCount].ind_name, data: dataArr[dt_score[nCount].ind_id], stack: dt_score[nCount].group_id};
    	} 
    else if(dashboard_type == "Subarea"){
    	for (var nCount = 0,sno=0; nCount < json_res.qa_res["Subarea_list"].length; nCount++){
    		if(parent_id != "" && json_res.qa_res["Subarea_list"][nCount].pid != parent_id) continue;
    			chktype.push({id: json_res.qa_res["Subarea_list"][nCount].sid, val: json_res.qa_res["Subarea_list"][nCount].sname});
    			sno_arr[json_res.qa_res["Subarea_list"][nCount].sid] = sno++; 
    	}
    	for (var nCount = 0; nCount < dt_score.length; nCount++) {
    		dataArr[dt_score[nCount].ind_id] = [];
    		for (var j = 0; j < chktype.length; j++)
    			dataArr[dt_score[nCount].ind_id][j] = 0;
    	}
    	for(var i=0; i<data.length;i++) {
    		if(dataArr[data[i]['indid']][sno_arr[data[i]['sid']]] != undefined)
    		dataArr[data[i]['indid']][sno_arr[data[i]['sid']]]= parseInt(data[i]['cnt']);
    	}
    	for (var nCount = 0; nCount < dt_score.length; nCount++)
    		jsondataObj[nCount] = {name: dt_score[nCount].ind_name, data: dataArr[dt_score[nCount].ind_id], stack: dt_score[nCount].group_id};
    	} 
    else if(dashboard_type == "Point"){
    	var point_names = [];
    	for(var i=0,sno=0,j=0; i<data.length; i++){
    		if(parent_id != "" && data[i]['pid'] != parent_id) continue;
    		if($.inArray(data[i]['sid'], point_names) >= 0) continue;
    		chktype.push({id: data[i]['sid'], val: data[i]['sid']});
    		sno_arr[data[i]['sid']] = sno++; 
			point_names[j++] = data[i]['sid'];
    	}
    	for (var nCount = 0; nCount < dt_score.length; nCount++) {
    		dataArr[dt_score[nCount].ind_id] = [];
    		for (var j = 0; j < chktype.length; j++)
    			dataArr[dt_score[nCount].ind_id][j] = 0;
    	}
    	
    	for(var i=0; i<data.length;i++){
    		if($.inArray(data[i]['sid'], point_names) < 0) continue;
    		dataArr[data[i]['indid']][sno_arr[data[i]['sid']]]= dataArr[data[i]['indid']][sno_arr[data[i]['sid']]] + parseInt(data[i]['cnt']);
    	}
    	
		for (var nCount = 0; nCount < dt_score.length; nCount++)
    			jsondataObj[nCount] = {name: dt_score[nCount].ind_name, data: dataArr[dt_score[nCount].ind_id], stack: dt_score[nCount].group_id};
    	}
    } 
    else {  // For Average chart....
    	var dashboard_type = $("#ddldashboard").val();
    	if(dashboard_type == "Site"){
    		for (var nCount = 0,sno=0; nCount < json_res.qa_res["Site_list"].length; nCount++) {
    			chktype.push({id: json_res.qa_res["Site_list"][nCount].sid, val: json_res.qa_res["Site_list"][nCount].sname});
    		}
    		for(var i=0; i<data.length;i++) {
    			if(dataArr[data[i]['sid']] == undefined) {
    				dataArr[data[i]['sid']] = [];
    				dataArr[data[i]['sid']]['tot'] = 0;
    				dataArr[data[i]['sid']]['avg'] = 0;
    			}
    			var tot_count = parseInt(dataArr[data[i]['sid']]['tot']) + 1;
    			var tot_avg =  parseFloat(dataArr[data[i]['sid']]['avg']) +parseFloat(data[i]['avg']);  
    			dataArr[data[i]['sid']]['avg'] = tot_avg;
    			dataArr[data[i]['sid']]['tot'] = tot_count;
    		}
    		for (var i = 0; i < chktype.length; i++) {
    			var avg = 0;
    			if(dataArr[chktype[i]['id']] != undefined && dataArr[chktype[i]['id']]['tot'] != 0) 
    				avg = parseFloat(dataArr[chktype[i]['id']]['avg'] / dataArr[chktype[i]['id']]['tot']).toFixed(2);
    			jsondataObj.push(parseFloat(avg));
    		}
    	} 
    	else if(dashboard_type == "Area"){
    	    	for (var nCount = 0,sno=0; nCount < json_res.qa_res["Area_list"].length; nCount++){
    	    		if(parent_id != "" && json_res.qa_res["Area_list"][nCount].pid != parent_id) continue;
    	    			chktype.push({id: json_res.qa_res["Area_list"][nCount].sid, val: json_res.qa_res["Area_list"][nCount].sname});
    	    	}
    	    	for(var i=0; i<data.length;i++) {
    	    		if(dataArr[data[i]['sid']] == undefined) {
        				dataArr[data[i]['sid']] = [];
        				dataArr[data[i]['sid']]['tot'] = 0;
        				dataArr[data[i]['sid']]['avg'] = 0;
        			}
        			var tot_count = parseInt(dataArr[data[i]['sid']]['tot']) + 1;
        			var tot_avg =  parseFloat(dataArr[data[i]['sid']]['avg']) +parseFloat(data[i]['avg']);  
        			dataArr[data[i]['sid']]['avg'] = tot_avg;
        			dataArr[data[i]['sid']]['tot'] = tot_count;
        		}
        		for (var i = 0; i < chktype.length; i++) {
        			var avg = 0;
        			if(dataArr[chktype[i]['id']] != undefined && dataArr[chktype[i]['id']]['tot'] != 0) 
        				avg = parseFloat(dataArr[chktype[i]['id']]['avg'] / dataArr[chktype[i]['id']]['tot']).toFixed(2);
        			jsondataObj.push(parseFloat(avg));
        		}
    	} 
    	else if(dashboard_type == "Subarea"){
        	for (var nCount = 0,sno=0; nCount < json_res.qa_res["Subarea_list"].length; nCount++){
        		if(parent_id != "" && json_res.qa_res["Subarea_list"][nCount].pid != parent_id) continue;
        			chktype.push({id: json_res.qa_res["Subarea_list"][nCount].sid, val: json_res.qa_res["Subarea_list"][nCount].sname});
        	}
        	
        	for(var i=0; i<data.length;i++) {
    			if(dataArr[data[i]['sid']] == undefined) {
    				dataArr[data[i]['sid']] = [];
    				dataArr[data[i]['sid']]['tot'] = 0;
    				dataArr[data[i]['sid']]['avg'] = 0;
    			}
    			var tot_count = parseInt(dataArr[data[i]['sid']]['tot']) + 1;
    			var tot_avg =  parseFloat(dataArr[data[i]['sid']]['avg']) +parseFloat(data[i]['avg']);  
    			dataArr[data[i]['sid']]['avg'] = tot_avg;
    			dataArr[data[i]['sid']]['tot'] = tot_count;
    		}
        	for (var i = 0; i < chktype.length; i++) {
    			var avg = 0;
    			if(dataArr[chktype[i]['id']] != undefined && dataArr[chktype[i]['id']]['tot'] != 0)
    				avg = parseFloat(dataArr[chktype[i]['id']]['avg'] / dataArr[chktype[i]['id']]['tot']).toFixed(2);
    			jsondataObj.push(parseFloat(avg));
    		}    
        	} 
        else if(dashboard_type == "Point"){
        	var point_names = [];
        	for(var i=0,sno=0,j=0; i<data.length; i++){
        		if(parent_id != "" && data[i]['pid'] != parent_id) continue;
        		if($.inArray(data[i]['sid'], point_names) >= 0) continue;
        		chktype.push({id: data[i]['sid'], val: data[i]['sid']});
        		point_names[j++] = data[i]['sid'];
        	}

        	for (var j = 0; j < chktype.length; j++) {
        		dataArr[chktype[j]['id']] = [];
        		dataArr[chktype[j]['id']]['tot'] = 0;
    			dataArr[chktype[j]['id']]['avg'] = 0;
        	}
        	
        	for(var i=0; i<data.length;i++) {
        		if(dataArr[data[i]['sid']] != undefined) {
    			dataArr[data[i]['sid']]['tot']= dataArr[data[i]['sid']]['tot'] + 1;
    			dataArr[data[i]['sid']]['avg']= dataArr[data[i]['sid']]['avg'] + parseFloat(data[i]['avg']);
        		}
    		}
        	for (var i = 0; i < chktype.length; i++) {
    			var avg = 0;
    			if(dataArr[chktype[i]['id']] != undefined && dataArr[chktype[i]['id']]['tot'] != 0) 
    				avg = parseFloat(dataArr[chktype[i]['id']]['avg'] / dataArr[chktype[i]['id']]['tot']).toFixed(2);
    			jsondataObj.push(parseFloat(avg));
    		}   
        	}
    }
    if (chktype.length > 0) {
    	
    	if (chart_type == "c") {
            $('#divAccountChart').attr("style","min-width: 800px;height: 650px; margin: 0 auto;overflow-x:auto").highcharts({
                chart: {type: 'column'},
                title: {text: 'Quality Auditor Dashboard. ' + dashboard_type + ' Level Counts', x: -20},
                subtitle: {text: sub_tit, x: -20},
                xAxis: {categories: chktype,
                    labels: {formatter: function() {
                    	if(chktype.length>max_points)
                    		return "";
                    	    if (dashboard_type == "Point")
                                return this.value['val'].substring(0,max_points);
                            return "<a href=\"javascript:navigateChart('c','" + this.value['id'] + "');\">" + this.value['val'].substring(0,max_points) + "</a>"
                        },
                        rotation: -90,
                        align: 'right',
                        style: {
                        	fontSize: '13px',
                            fontFamily: 'Verdana, sans-serif',
                        }}},
                yAxis: {allowDecimals: false, min: 0, title: {text: 'Count of scores'}},
                credits: {enabled: false},
                tooltip: {formatter: function() {
                        return '<b>' + this.x['val'] + '</b><br/>' + this.series.name + ': ' + this.y + '<br/>' + 'Total: ' + this.point.stackTotal;
                    }},
                plotOptions: {column: {stacking: 'normal'}},
                series:jsondataObj
            });
    } else if (chart_type == "a") {
            $('#divAccountChart').attr("style","min-width: 400px;height: 650px; margin: 0 auto").highcharts({
                chart: {type: 'column'},
                title: {text: 'Quality Auditor Dashboard. ' + dashboard_type + ' Level Averages', x: -20},
                subtitle: {text: sub_tit, x: -20},
                xAxis: {categories: chktype,
                    labels: {formatter: function() {
                    	if(chktype.length>max_points)
                    		return "";
                    	if (dashboard_type == "Point")
                            return this.value['val'].substring(0,max_points);
                        return "<a href=\"javascript:navigateChart('a','" + this.value['id'] + "');\">" + this.value['val'].substring(0,max_points) + "</a>"
                        },
                        rotation: -90,
                        align: 'right',
                        style: {
                            fontSize: '13px',
                            fontFamily: 'Verdana, sans-serif'
                        }}},
                yAxis: {min: 0, title: {text: 'Average of scores'},
                    stackLabels: {enabled: true, style: {fontWeight: 'bold', color: (Highcharts.theme && Highcharts.theme.textColor) || 'gray'}}
                },
                credits: {enabled: false},
                tooltip: {formatter: function() {
                        return '<b>' + this.x['val'] + '</b><br/>' + this.series.name + ': ' + this.y
                    }},
                plotOptions: {column: {stacking: 'normal'}, dataLabels: {enabled: true, color: (Highcharts.theme && Highcharts.theme.dataLabelsColor) || 'white'}},
                series: [{name: 'Average', data: jsondataObj}]
            });
    }
    else {
            $('#divAccountChart').attr("style","min-width: 400px;height: 650px; margin: 0 auto").highcharts({
                chart: {type: 'column'},
                title: {text: 'Quality Auditor Dashboard. ' + dashboard_type + ' Level Percentages', x: -20},
                subtitle: {text: sub_tit, x: -20},
                xAxis: {categories: chktype,
                    labels: {formatter: function() {
                    	if(chktype.length>max_points)
                    		return "";
                    	if (dashboard_type == "Point")
                            return this.value['val'].substring(0,max_points);
                        return "<a href=\"javascript:navigateChart('p','" + this.value['id'] + "');\">" + this.value['val'].substring(0,max_points) + "</a>"
                        },
                        rotation: -90,
                        align: 'right',
                        style: {
                            fontSize: '13px',
                            fontFamily: 'Verdana, sans-serif'
                        }}},
                yAxis: {min: 0, title: {text: 'Percentage split of scores'}},
                credits: {enabled: false},
                tooltip: {formatter: function() {
                        return '<b>' + this.x['val'] + '</b><br/>' + this.series.name + ': ' + this.y + '<br/>' + 'Total: ' + this.point.stackTotal;
                    }},
                plotOptions: {column: {stacking: 'percent'}},
                series: jsondataObj
            });
    }
    	$("#accCharts").show();
        $("#divAccountChartErr").addClass('hide');
        $("#divExportQAbtn").show();
    }
    else {
    	$("#divAccountChart").attr("style","height:0px;").html("");
        $("#divAccountChartErr").html("Data not Available " + sub_tit + "").removeClass('hide');
        $("#divExportQAbtn").hide();
    }
    return false;
}

function srv_showAccount() {
    var vales_QA = getValues_QA_Dashb();
    var startdate_QA = $("#txtRBStartDate").val().split("/");
    var enddate_QA = $("#txtRBEndDate").val().split("/");
    if (vales_QA[0].length == 0)
    {
        $("#divAccFilterErrMsg").html("Please select sites from the Available site list").show();
        $("#dvgraphSector").hide();
        return false;
    }
    if (vales_QA[1].length == 0)
    {
        $("#divAccFilterErrMsg").html("Please select area from the Available area list").show();
        $("#dvgraphSector").hide();
        return false;
    }
    if (vales_QA[2].length == 0)
    {
        $("#divAccFilterErrMsg").html("Please select subarea from the Available Subarea list").show();
        $("#dvgraphSector").hide();
        return false;
    }
    if (vales_QA[3].length == 0)
    {
        $("#divAccFilterErrMsg").html("Please select points from the Available Point list").show();
        $("#dvgraphSector").hide();
        return false;
    }
    if (vales_QA[4].length == 0)
    {
        $("#divAccFilterErrMsg").html("Please select auditors from the Available Auditor list").show();
        $("#dvgraphSector").hide();
        return false;
    }
    var url = BACKENDURL + "user/quality_audit_filter";
    var data = {
        session_id: localStorage["SESSIONID"],
        start_date: startdate_QA[2].substring(0, 4) + "-" + startdate_QA[1] + "-" + startdate_QA[0] + " " + startdate_QA[2].substring(5),
        end_date: enddate_QA[2].substring(0, 4) + "-" + enddate_QA[1] + "-" + enddate_QA[0] + " " + enddate_QA[2].substring(5),
        ac_id: $('#ddlRP_Accts').val(),
        st: vales_QA[0],
        ar: vales_QA[1],
        sar: vales_QA[2],
        p: vales_QA[3],
        au: vales_QA[4]
    };
//  var data = { /// For testing purpose
//  session_id: localStorage["SESSIONID"],
//  start_date: "2013-04-01 00:00:00",
//  end_date: "2014-01-07 23:59:59",
//  ac_id: "1",
//  st: "'1','2','3','4','5','6','7','8','9','10','11','12','12','13','14','16','17'",
//  ar: "1,2,3,4,5,6,15,16,17,18,19,20,21,22,23,24,25,26,27,28,29",
//  sar: "1,2,3,4,5,6,7,8,9,10,11,12,13,64,65,66,67,68,69,70,71,72,73,74,75,76,77,78,79,80,81,82,83,84,85,86,87,88,89,90,91,92,93,94,95,96,97,98,99,100,101,102,103,104,105,106,107,108,109,110",
//  p: "'Mop floor','Empty bins','Clear/clean work surfaces and tables','Deep clean to public toilets','Replenish consumables to public toilets','Polish Stainless Steel','Remove finger prints from glass','Clean lift runners','Desk','Windows','Floor','Litter Free','Gum Free','Stain Free','Free from cigarettes','Bins clean','Bins empty','Seats clean','Hoarding clean','Barriers clean','Well maintained plants','Well watered','Free from weeds','Free from litter','Litter','Chewing Gum','Graffiti & Stickers','Edges and Ledges','Poster Case','Floors','Walls & Columns','Railings','Mirrors & Glass','Walls','07. Patient washbowls','09. Patient fans','10. Bedside alcohol hand wash container, clipboards and notice boards','11. Notes and drugs trolley','14. Switches, sockets and data points','15. Walls','16. Ceiling','17. All doors','18. All internal glazing including partitions','19. All external glazing','20. Mirrors','21. Bedside patient TV including earpiece for bedside entertainment system','22. Radiators','23. Ventilation grilles extract and inlets','24. Floor ? polished','25. Floor ? non-slip','26. Soft floor','27. Pest control devices','28. Electrical items','30. Low surfaces','31. High surfaces','32. Chairs','35. Tables','36. Hand wash containers','38. Waste receptacles','39. Curtains and blinds','08. Medical gas equipment','12. Patient personal items e.g. cards and suitcase','13. Linen trolley','34. Lockers','37. Hand hygiene alcohol rub dispensers','47. Replenishment','48. Sinks','29. C equipment','Toilets','COSHH DATA FILE PRESENT','STAFF TRAINING RECORDS PRESENT AND UP TO DATE','STAFF UNIFORMS WORN BY ALL STAFF','STAFF SIGNING IN SHEETS COMPLETED DAILY','TEAM MEETINGS DOCUMENTED AND RETAINED','Is there a trained first aid representative','Are there adequate number of first aiders for the site','Are there suitable first aid / first aid location signs','Is there an on site Medical centre / first Aid Room','Is there a first aid box available & checked / indexed','Are staff following the cleaning schedule','Is sanitizer available in labelled containers','Are the facilities, including WC facilities, clean and tidy','Are the shut down checks carried out','Is equipment clean and tidy, including mops, cloths and transport containers','Are the work surfaces being cleaned after use','Has quarterly  cleaning been completed satisfactorily and recorded in DRB','Site Training Manual','Display Materials','Cleaning Materials','Cleaners Cupboard','Staff','Correct Signage','No Fire Hazards','Correct Mitigation in Place (eg H & S Tape)','No Trip Hazards','Living wall 100% alive','No Slip Hazards','No Broken Paving','All Lights Working','Drain Covers in Order','point_name',' ','All Posts in Place','Traffic management working','Signage level','All locks secured','Staff in place','Staff in correct uniform','All temp signage correct','No Illegal Parking','Contractors Using Correctly Branded Barriers','Trolley Points & railing','Cigarette Ends','Gates & Railings','Building Front Internal/External','Doors Internal/External','Pedestal Tables Clean','Fire extinguishers & Stands Clean','Hard Floor Areas & Skirting Clean','Desk Clean','Tables & chairs Clean','Waste Bins & Liners Clean','Cabinet tops Clean','Water Machine Clean','Water Cups available','Water Bottle available','Meeting room - Tables Clean','Meeting room - Chairs Clean','Meeting room - Floors Clean','Meeting room - Waste Bins & Liners Clean','Meeting room - Whiteboards Clean','Meeting room - Cupboard Tops Clean','Meeting room - Window ledges Clean','Meeting room - Tidy Cables','Meeting Room - Hospitality Clear Clean','Meeting Rooms - Doors & Handles Clean','Offices - Desks Clean','Offices - Chairs Clean','Offices - Floors Clean','Offices - Waste Bins & Liners Clean','Offices - Filing cabinet tops Clean','Offices - Window ledges Clean','Offices - Fire extinguishers & Stands Clean','Offices  - Tidy Cables','Offices - Doors & Handles Clean','Consulting - Rooms Floors Clean','Consulting - Rooms Walls Clean','Consulting - Rooms All surfaces Clean','Consulting - Doors & Handles Clean','Auditorium - Floors Clean','Auditorium - Walls Clean','Auditorium - Doors & Handles Clean','Auditorium - Hospitality Clear Clean','Auditorium - Waste Bins & liners Clean','Corridor - walls and floors Clean','Corridor - Swipe pad Clean','Corridors- Doors & Handles Clean','Corridor - Ledges Clean','Post room - Floors Clean','Post room - Doors & Handles Clean','Post room - Wipe down sides Clean','Facilities Office - Window Ledges Clean','Facilities office - Cupboard & Desktops Clean','Disabled Toilets - Waste bins & Liners Clean','Disabled Toilets - Shower Cubicle/Curtain Clean','Disabled Toilets - Sanitary Bins Clean','Disabled Toilets - Basins and Taps Clean','Disabled Toilets - Floor and Ledges  Clean','Disabled Toilets - Seats and Hinges Clean','Disabled Toilets - Toilet  & Seat Clean','Disabled Toilets - Walls  Clean','Disabled Toilets - Paper Towel Dispenser Clean','Disabled Toilets - Mirrors Clean','Disabled Toilets - Dispensers (soap, toilet rolls) Clean','Ladies Changing - Showers Cubicle, Walls & Doors Clean','Ladies Changing - Doors & handles Clean','Ladies Changing - Seating Clean','Ladies Changing - Lockers Clean','Ladies Changing - Drying Room Clean','Ladies Changing - Sanitary Bin Clean','Ladies Changing - Soap Dispensers Clean','Gents Changing - Showers Cubicle, Walls & Doors Clean','Gents Changing - Doors & handles Clean','Gents Changing - Basins and Taps Clean','Gents Changing - Seating Clean','Gents Changing - Lockers Clean','Gents Changing - Paper Towel Dispenser Clean','Gents Changing - Audit Checklist up to date Clean','Gents Changing - Soap Dispensers Clean','Gym - Floor Clean','Gym - Glass partition Clean','Gym - Bin & Liners Clean','Gym - Mirrors Clean','Gym - Towel Dispenser Clean','Gym Studio - Floor Clean','Gym Studio - Window Ledges Clean','Gym Studio - Glass Partition Clean','Gym Studio - Mirrors Clean','Gym Physic - Bin & Liners Clean','Gym Physic - Floor Clean','Gym Physic - Mirror Clean','Gym Physic - Desk Clean','point_name',' ','Gym Physic - Bench Clean','Gym Office - Desk Clean','Gym Office - Bin & Liners Clean','Gym Office - Cupboard Tops Clean','Quiet Room - Floors & Walls Clean','Print Room - Floors & Walls Clean','Print Room - Bin & Liners Clean','Staff Welfare - Floors & Walls Clean','Staff Welfare - Bench Clean','Staff Welfare - Desk Clean','Staff Welfare -Sink & Taps Clean','Staff Welfare -Towel Dispenser Clean','Staff Welfare -Soap Dispenser Clean','Pigeon excrement','Seating','Handrails/ Railing','Glass & Mirrors','Steps/stairs','Trolley Point','Treads/risers','Handrails','Bin area clean','Initial Health & Safety policies up to date?','Initial Employer Liability Insurance Policies up to date?','Is the current \"H&S Law\" poster being displayed on site?','A list of Fire Wardens, First Aiders and Evacuation Chair staff displayed?','Are the current Fire Evacuation Procedure records on site?','Current Disaster Plan on site?','Are the Risk Assessments on site?','Are the Visit Schedules on site?','Are there Permits to Work, Hot Works and other Permits on site?','Are there COSHH Records / Data sheets on site?','DSE Assessments?','Manual Handling Risk Assessment Records?','Health & Safety Audits (K. Filler reporting)','Annual PPM plan?','Monthly PPM Planner?','Monthly Reactive Reporting (Review 2 monthly, identify trends)','Fire Risk Assessments?','Fire Alarm Log Book - weekly records updated within?','Fire Alarm Maintenance Records (PPM Schedule)?','Fire Emergency Procedures?','Fire Evacuation Records (6 monthly)?','Five Year Periodic Inspection Records?','Fire Hose Reel/Dry Riser Maintenance Records?','Fire Extinguisher Test Records & Signage Is In Place & Current?','Current Floor Plans?','Fire Suppression System Test Records, including Kitchens, Comms Rooms etc.?','Fire Hydrant Test Records Their PPM Maintenance Schedule?','Ventilation Ductwork Fire Dampers Maintenance Records?','Environmental Policies including, Energy & Sustainability?','Indoor AIQ (Indoor Air Quality Audit) including: Dry bulb temperature','Indoor AIQ (Indoor Air Quality Audit) including: Relative humidity','Indoor AIQ (Indoor Air Quality Audit) including: Respired CO2','Indoor AIQ (Indoor Air Quality Audit) including: Formaldehyde','Respirable particles: Airborne bacteria','Respirable particles: Airborne moulds and yeasts','Respirable particles: Other specialist tests as necessary','Respirable particles: AHU Internal hygiene inspection','Respirable particles: Representative sections of ductwork and return air ductwork','Waste Stream Reviews?','Pest Control Log Book?','Water Coolers Filter Servicing PPM Schedules?','Asbestos register?','UPS Battery maintenance records?','M&E Annual Condition Report?','Generator Test Records & PPM Schedule?','Gas Safety Inspection Certificate?','Zurich Inspection Reports: Lifts, Lifting Equipment, Gas Boilers, Pressure Vessels?','Cradle Runway Window Cleaning Access Equipment PPM Schedule & Test Certificates','Portable Appliance (PAT) Testing Schedule & Records?','Other Lifting Equipment (Forklifts, Pallet Trucks etc.)?','Emergency Lighting PPM Records & Evidence that Failures Have Been Repaired?','Lightning Conductor Annual Service & Certification?','Catering Equipment Maintenance Records including Canopy Grease Removal & Cleaning Records?','Annual Maintenance Records & Test Inspections?','Thermal Imaging Inspection Records?','Water Quality Assessment?','Potable Water Chlorination Certificate?','Legionella Testing & Certification?','Monthly Tap Outlet Temperature Records (cold at least 20C, hot 50-55C)?','Shower Head Quality Clean & Chlorination Records?','Toilet Checks (Lights, consumables, cleanliness, disabled alarms work, etc.)?','Building Light Checks (any failures not reported)?','Cleaning: Wall washing, vacuuming, bin emptying, carpet spot-cleaning, centre feeds, dusting etc.?','Waste collections (have uplifts taken place, bin labelling correct and in place)?','Feminine hygiene units been serviced on time, mats replaced, air fresheners working?','Vending Machines, Water Coolers being replenished, all faults reported and filters checked/serviced?','External checks: lights working, bins emptied, litter collected and in good/tidy condition?','Meeting Rooms: are telephones and lite pro''s operational?','Meeting Rooms: are cables tidy and is the room clean and presentable ?','Meeting Rooms: are consumables stocked adequately?','Are all First Aid boxes and Eye Wash Stations stocked adequately?','Are all First Aid boxes goods therein within their safe usage date?','Have fridges been cleaned, in working-order and are items disposed of regularly?','Security: Has access control system been maintained and tested?','Security: Are all swipe cards/proximity readers in full working order?','Security: Is all CCTV monitoring and recording equipment fully operational ?','Are the site''s Primary Contact details known and displayed (notice board)?','point_name',' ','Are temperatures documented in the DRB','Are staff making others aware when they are opening steam cookers','Are there adequate facilities at the satellites for reheating','Are there appropriate hot liquid storage vessels','Are knives used safely and according to training given in tool box talks','Are food items finished within half an hour of service','Are food items kept at room temperature for the minimum time necessary','Clean surfaces','Scrub floor','Wipe clean posters','Mop steps','Clean glass','Clean rubber runners','Clean seating','Wipe clean posters and help points','Remove litter','Clean liquid spillages','Mop floor to locker, control and mess rooms','Clean all surfaces','Deep clean to male, female & disabled toilets','Stairway to overbridge'",
//  au: "1,2,3,4,5,7,8,9,10,11,12,13"
//};
    var formStartDate = (new Date(startdate_QA[2], (startdate_QA[1] - 1), startdate_QA[0])).valueOf();
    var formEndDate = (new Date(enddate_QA[2], (enddate_QA[1] - 1), enddate_QA[0])).valueOf();
    if (formEndDate < formStartDate)
    {
        alert("Date issues");
        //$("#divPensErrMsg_TDM").html('The end date can not be earlier than the start date').removeClass('hide');
    }
    else {
        MakeAjaxCall(url, data, showAccount);
    }
}

function showAccount(data)
{
    $("#accAccountFilter").collapse('hide');
    $("#divAccFilterErrMsg").hide();
    $("#dvgraphSector").show();
    json_res = data;
    
    data_dashboard = "<option value='Site' selected='selected'>Site</option><option value='Area'>Area</option><option value='Subarea'>Subarea</option><option value='Point'>Point</option>";
    $('#ddldashboard').empty();
    $("#ddldashboard").append(data_dashboard);
    var btn_act = btn_active();
    populateAccount_count(btn_act, $("#ddldashboard").val(), data.qa_res[$("#ddldashboard").val()], data.qa_res["Indicator"],"");
}

function viewRBDashboard(chart_type) {
    var data_val = json_res.qa_res[$("#ddldashboard").val()];
    populateAccount_count(chart_type, $("#ddldashboard").val(), data_val, json_res.qa_res["Indicator"],"");
    return false;
}

function getValues_QA_Dashb()
{
    var QA_Sites = "", QA_SA = "", QA_SSA = "", QA_SP = "", QA_Aud = "";
    var Sites_QA = "", SA_QA = "", SSA_QA = "", SP_QA = "", Aud_QA = "";
    $("#lstSelectedSites tr").each(function(e) {
        QA_Sites = QA_Sites + "'" + $(this).attr("id").toString() + "',";
        Sites_QA = QA_Sites.replace(/^,|,$/g, '');
    });
    $("#lstSelectedAreas tr").each(function(e) {
        QA_SA = QA_SA + "'" + $(this).attr("id").toString() + "',";
        SA_QA = QA_SA.replace(/^,|,$/g, '');
    });
    $("#lstSelectedSubareas tr").each(function(e) {
        QA_SSA = QA_SSA + "'" + $(this).attr("id").toString() + "',";
        SSA_QA = QA_SSA.replace(/^,|,$/g, '');
    });
    $("#lstSelectedPoints tr").each(function(e) {
        QA_SP = QA_SP + "'" + $(this).attr("id").toString() + "',";
        SP_QA = QA_SP.replace(/^,|,$/g, '');
    });
    $("#lstSelectedAuditors tr").each(function(e) {
        QA_Aud = QA_Aud + $(this).attr("id") + ",";
        Aud_QA = QA_Aud.replace(/^,|,$/g, '');
    });
    return [Sites_QA, SA_QA, SSA_QA, SP_QA, Aud_QA];
}
function createAdhoc(from) {
    $("#divtbl_ReportList").hide();
    $("#divreportBuilderAccordion").show();
    $("#accoRBFilter").show();
    $("#accoRBProp").hide();
    $("#frmRBGraph").show();
    $("#btnSaveRBGraph").hide();
    $("#divNextPrevWeek").show();
    //$("#btnCount").click();
    $("#divReportName").hide();
    $("#divSelStartDate").hide();
    $("#divSelEndDate").hide();
    $("#txtRBdates").hide();
    $("#txtRBEd").hide();
    $('#divRBStartDate').show();
    $('#divRBEndDate').show();
    $("#ddlRBStartDate").val("1");
    $("#ddlRBEndDate").val("1");
    if (from == 'DBD')
    $('#btnCloseRBGraph').hide();
    //showFilter();
    adhoc_report = true;
    //viewRBDashboard('c');
    
    QA_RunAdhoc();

}

function closeRBDashboard() {
    $("#divreportBuilderAccordion").hide();
    $("#divtbl_ReportList").show();
    $("#divrunslareports").hide();
    adhoc_report = false;
}

function createreport() {
    $("#divtbl_ReportList").hide();
    $("#divreportBuilderAccordion").show();
    $("#accoRBFilter").show();
    $("#accoRBProp").show();
    $("#frmRBGraph").show();
    $("#btnSaveRBGraph").show();
    $("#divNextPrevWeek").hide();
    //$("#btnCount").click();
    $("#divReportName").show();
    $("#divSelStartDate").show();
    $("#divSelEndDate").show();
    $("#txtRBdates").hide();
    $("#txtRBEd").hide();
    $('#divRBStartDate').show();
    $('#divRBEndDate').show();
    $("#ddlRBStartDate").val("1");
    $("#ddlRBEndDate").val("1");
    // showFilter();
    adhoc_report = false;
    // viewRBDashboard('c');
}

function ViewRB() {
    $("#divtbl_ReportList").hide();
    $("#divreportBuilderAccordion").hide();
    $("#divrunslareports").show();
    var regarray = new Array(5);
    for (var i = 0; i < 4; i++) {
        regarray[i] = new Array(3);
        regarray[i]['yes'] = '<i class="icon-remove-sign icon-red"></i>';
        regarray[i]['pass'] = '<i class="icon-ok-sign icon-green"></i>';
        regarray[i]['num'] = '<i class="icon-exclamation-sign icon-orange"></i>';
    }
    regarray[4] = new Array(3);
    regarray[4]['yes'] = '<i class="icon-remove-sign icon-red"></i>';
    regarray[4]['pass'] = '<i class="icon-info-sign icon-blue"></i>';
    regarray[4]['num'] = '<i class="icon-question-sign icon-purple"></i>';


    $('#divChart2').highcharts({
        chart: {type: 'column'},
        title: {text: 'Quality Auditor Dashboard. site Level Counts', x: -20},
        subtitle: {text: 'From 1st Jan 2013 to 11th Nov 2013. ', x: -20},
        xAxis: {categories: ['Site 1', 'Site 2', 'Site 3', 'Site 4', 'Site 5'],
            labels: {formatter: function() {
                    return this.value;
                }, useHTML: true}},
        yAxis: {allowDecimals: false, min: 0, title: {text: 'Count of scores'}, stackLabels: {enabled: true, useHTML: true, formatter: function() {
                    if (this.total > 0 && !adhoc_report)
                        return  regarray[this.x][this.stack];
                    else
                        return "";
                }}},
        credits: {enabled: false},
        tooltip: {formatter: function() {
                return '<b>' + this.x + '</b><br/>' + this.series.name + ': ' + this.y + '<br/>' + 'Total: ' + this.point.stackTotal + '<br/>' + 'RAG (%): 80 ';
            }},
        plotOptions: {column: {stacking: 'normal'}},
        series: [{name: 'N/A', data: [10, 14, 14, 12, 15], stack: 'yes'},
            {name: 'No', data: [50, 42, 44, 12, 25], stack: 'yes'},
            {name: 'Yes', data: [210, 245, 189, 246, 310], stack: 'yes'},
            {name: 'Pass', data: [120, 0, 136, 298, 134], stack: 'pass'},
            {name: 'fail', data: [25, 0, 22, 12, 0], stack: 'pass'},
            {name: '5', data: [120, 60, 0, 0, 27], stack: 'num'},
            {name: '4', data: [53, 0, 45, 64, 33], stack: 'num'},
            {name: '3', data: [43, 33, 74, 84, 35], stack: 'num'},
            {name: '2', data: [30, 180, 44, 46, 37], stack: 'num'},
            {name: '1', data: [31, 26, 84, 64, 32], stack: 'num'}
        ]
    });
    adhoc_report = false;
}
function exportQA(expType) {
    var vales_QA = getValues_QA_Dashb();
    var startdate_QA = $("#txtRBStartDate").val().split("/");
    var enddate_QA = $("#txtRBEndDate").val().split("/");
    $.support.cors = true;
    $.ajax({
        url: BACKENDURL + "data_upload/export_data",
        type: "post",
        data: {
            session_id: localStorage["SESSIONID"],
            export_type: expType,
            start_date: startdate_QA[2].substring(0, 4) + "-" + startdate_QA[1] + "-" + startdate_QA[0] + " " + startdate_QA[2].substring(5),
            end_date: enddate_QA[2].substring(0, 4) + "-" + enddate_QA[1] + "-" + enddate_QA[0] + " " + enddate_QA[2].substring(5),
            ac_id: $('#ddlRP_Accts').val(),
            st: vales_QA[0],
            ar: vales_QA[1],
            sar: vales_QA[2],
            p: vales_QA[3],
            au: vales_QA[4]
        },
        dataType: "json",
        async: false,
        crossDomain: true,
        success: function(data) {
            if (data.session_status) {
                if (data.error == 0) {
                    var url = BACKENDURL + "common/download_file";
                    $('<form action="' + url + "/" + localStorage["SESSIONID"] + "/" + expType + "/" + data.temp_file + '" style="display:none;"></form>').appendTo('body').submit();
                } else if (data.error_msg == "Unauthorized access.")
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
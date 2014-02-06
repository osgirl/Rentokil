var unAthMsg = "Unauthorized access.";
var page_num = 1;
var zone_name;
var autoRefreshIntervalID = null;
localStorage["PrevStatRefresh"] = 0;
localStorage["PrevStatRefreshAsset"] = 0;
function LoadPageData() {
    if (localStorage["autorefresh"] == "true") {
        $("#ctfcheckbox").prop("checked", true);
		$('#countdown').show();
        AutoRefreshDash();
    }
    if (localStorage["autorefreshasset"] == "true") {
        $("#ctfcheckboxasset").prop("checked", true);
		$('#countdown_asset').show();
        AutoRefreshDash();
    }
    $("#ResourceZoneDashboard").click(function() {
        $("#AssetDashboard").hide();
        $("#divZoneGraph").hide();
        $("#divNewZoneButton").show();
        $("#ZoneDashboard").show();
        $("#divZoneSecond").hide();
        SrvLoadZoneDashboard(page_num);
        if (localStorage["autorefresh"] == "true") {
            $("#ctfcheckbox").prop("checked", true);
            AutoRefreshDash();
        }
    });
    $("#ResourceAssetDashboard").click(function() {
        $("#ZoneDashboard").hide();
        $("#AssetDashboard").show();
        SrvLoadAssetDashboard();
        $("#divAssetNew").hide();
        $("#divAssetRefreshAddControls").show();
        if (localStorage["autorefreshasset"] == "true") {
            $("#ctfcheckboxasset").prop("checked", true);
            AutoRefreshDash();
        }
    });
    loadAssetTracking();	
}

function tableSearch()
{
	var numOfAssetsFound = 0;
	
    $("#tblAssetDashBrd").find("tr").each(function(index) {
        var id = $(this).find("td").text();
        var str = id.toLowerCase().replace('editdelete',' ').trim();
 		var isSearchValFound = false;
		
		if (isSearchValFound = (str.indexOf($("#search").val().toLowerCase()) !== -1))
		{
			numOfAssetsFound++;
		}
		
        $(this).toggle(isSearchValFound);
		
        if ($('tr:visible').length > 10)
        {
            $("#tablePagination").show();
        } else {
            $("#tablePagination").hide();
        }
    });
	
 	$("#assetZoneLabel").html('Showing a total of <strong>' + numOfAssetsFound + ' assets</strong>. Search and filter assets by their properties across <strong>' + localStorage["ZONENAME"] + '</strong>.');
	if($("#search").val() == "")
		SrvLoadAssetDashboard();
}
function loadAssetTracking() {
    $("#btnCloseZoneDashboard").bind("click", closeZoneDashBoard);
    $("#btnSaveZoneDashboard").bind("click", SrvZoneDashBoardSave);
    $("#btnSaveAssetDashboard").bind("click", SrvAssetDashBoardSave);
    $("#btnCloseAssetDashboard").bind("click", closeAssetDashBoard);
    $("#btnCloseAssetDetailsDashboard").bind("click", closeAssetMainDash);
    $("#btnZoneRefresh").click(function() {
        loadSpinner();
    });
    $("#btnZoneGraphEndDateGo").bind("click", function() {
        Srv_PopulateMonthCharts(localStorage["ZONEID"]);
    });
    $("#btnCloseZoneGraph").bind("click", closeZoneGraph);
	if($.browser.chrome || $.browser.safari) {
	   $("#countdown").css("margin-top","-20px");
	   $("#countdown_asset").css("margin-top","-20px");
	} 
	
    $("#ctfcheckbox, #ctfcheckboxasset").change(function() {
        var choice = 'null';
        if (this.checked) {
            // localStorage.setItem("autorefresh", "true");
            if (this.id == 'ctfcheckbox') {
				$('#countdown').show();
                localStorage.setItem("autorefresh", "true");
                AutoRefreshDash();
            }
            else {
				$('#countdown_asset').show();
                localStorage.setItem("autorefreshasset", "true");
                AutoRefreshDash();
            }
        }
        else
        {
            // localStorage.setItem("autorefresh", "false");
            if (this.id == 'ctfcheckbox') {
				$("#countdown span").countdown('destroy');
				$('#countdown').hide();
				localStorage.setItem("autorefresh", "false");
                AutoRefreshDash();
            }
            else {
				$("#countdown_asset span").countdown('destroy');
				$('#countdown_asset').hide();
				localStorage.setItem("autorefreshasset", "false");
                AutoRefreshDash();
            }
        }
    });
    //create zone form validation
    $("#frmCreateZone").validate({
        rules: {txtnewZoneName: {required: true}},
        messages: {txtnewZoneName: {required: "Please enter the zone name"}
        },
        submitHandler: function(form) {
            form.submit();
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

            error.insertAfter(element);
            return false;
        }
    });
    //create asset form validation
    $("#frmCreateAsset").validate({
        rules: {txtnewAssetTagNumber: {Number: true, required: true}, },
        messages: {txtnewAssetTagNumber: {required: "Please enter asset tag number"},
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
//new zone form validation
    $("#frmNewZone").validate({
        rules: {
            txtZoneName: {regAlphaNumeric: true, required: true},
            txtZoneDesc: {required: true},
            txtDeviceID: {required: true},
            txtSerialNo: {required: true},
            txtHighThreshold: {Number:true,required: true},
            txtLowThreshold: {Number:true,required: true},
            txtTimeout: {Number: true, required: true},
            txtTagTimeout:{Number: true, required: true}
        },
        messages: {
            txtZoneName: {required: "Please enter the zone name"},
            txtZoneDesc: {required: "Please enter the zone description"},
            txtDeviceID: {required: "Please enter the device ID"},
            txtSerialNo: {required: "Please enter the serial no"},
            txtHighThreshold: {required: "Please enter the high threshold value"},
            txtLowThreshold: {required: "Please enter the low threshold value"},
            txtTimeout: {required: "Please enter zone timeout value"},
            txtTagTimeout: {required: "Please enter tag read timeout value"}
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
//new asset form validation
    $("#frmNewAsset").validate({
        rules: {
            txtAssetTagNumber: {Number: true, required: true},
            txtAssetDesc: {required: true},
            txtAssetOEMCode: {required: true},
            txtAssetTimeout: {Number: true, required: true},
        },
        messages: {
            txtAssetTagNumber: {required: "Please enter the asset tag number"},
            txtAssetDesc: {required: "Please enter the asset description"},
            txtAssetOEMCode: {required: "Please enter the OEM value"},
            txtAssetTimeout: {required: "Please enter timeout value"},
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

    //Prev and Next Month functionality for Zone Graphs
    $("#idZoneGraphPrevMon").bind("click", function() {
        var selDateArr = $("#idZoneGraphEndDate").val().split("/");
        var dateMonthNYear = (selDateArr[0] == 1) ? (parseInt(selDateArr[0]) + 11) + "/" + (parseInt(selDateArr[1]) - 1) : (parseInt(selDateArr[0]) - 1) + "/" + (parseInt(selDateArr[1]));
        $('#idZoneGraphDate').data({date: dateMonthNYear}).datepicker('update');
        $("#idZoneGraphEndDate").val(dateMonthNYear);
        $('#idZoneGraphNextMon').attr('disabled', false);

        Srv_PopulateMonthCharts(localStorage["ZONEID"]);
    });

    $("#idZoneGraphNextMon").bind("click", function() {
        var selDateArr = $("#idZoneGraphEndDate").val().split("/");
        var dateMonthNYear = (selDateArr[0] == 12) ? (parseInt(selDateArr[0]) - 11) + "/" + (parseInt(selDateArr[1]) + 1) : (parseInt(selDateArr[0]) + 1) + "/" + (parseInt(selDateArr[1]));
        $('#idZoneGraphDate').data({date: dateMonthNYear}).datepicker('update');
        $("#idZoneGraphEndDate").val(dateMonthNYear);

        var today = new Date();

        if (dateMonthNYear == ((today.getMonth() + 1) + "/" + today.getFullYear()))
        {
            $("#idZoneGraphNextMon").attr('disabled', true);
        }

        Srv_PopulateMonthCharts(localStorage["ZONEID"]);
    });
    SrvLoadZoneDashboard(page_num);
}
function AutoRefreshDash() {
    var autorefresh = localStorage.getItem('autorefresh');
    var autorefreshasset = localStorage.getItem('autorefreshasset');
	if((autorefresh == 'true') && (autorefreshasset == 'true')){
		$("#countdown span").countdown({until: 300, format: 'MS', compact: true});
		$("#countdown_asset span").countdown({until: 300, format: 'MS', compact: true});
	}
	else if(autorefresh == 'true')
		$("#countdown span").countdown({until: 300, format: 'MS', compact: true});
	else
		$("#countdown_asset span").countdown({until: 300, format: 'MS', compact: true});

    if (autorefresh == 'true' || autorefreshasset == 'true') {
        //interval set to 5 minutes
        var interval = 1000 * 60 * 5; // where X is your every X minutes
		if (!autoRefreshIntervalID) {
            autoRefreshIntervalID = window.setInterval(function() {

                if (localStorage["autorefresh"] == "true" && localStorage["autorefreshasset"] == "true"){
							SrvLoadAssetDashboard();
							SrvLoadZoneDashboard(page_num);
							$("#countdown span").countdown('option', {until: +300}); 
							$("#countdown_asset span").countdown('option', {until: +300}); 
						}
				else if (localStorage["autorefresh"] == "true") {
                        SrvLoadZoneDashboard(page_num);
						$("#countdown span").countdown('option', {until: +300}); 
						}
                else if (localStorage["autorefreshasset"] == "true"){
							if(localStorage["ZONENAME"] != "All Zones")
							{				
							moreDet_Zone(localStorage["ZONEID"],localStorage["ZONENAME"]);
							}else{				
							SrvLoadAssetDashboard();
							$("#countdown_asset span").countdown('option', {until: +300});
							}
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
/*************Zone Dashboard***************/
function loadSpinner() {
    var activeTab = $("ul#widgetnav").children(".active").attr("id");
    if (activeTab == "li_ResourceZoneDashboard") {
        $("#spnZoneRefresh").removeClass("hide");
		setTimeout( "SrvLoadZoneDashboard(page_num);",1000 );
    }
    else{
        $("#spnAssetRefresh").removeClass("hide");
		if(localStorage["ZONENAME"] != "All Zones")
				{				
				moreDet_Zone(localStorage["ZONEID"],localStorage["ZONENAME"]);
				}else{				
				setTimeout("SrvLoadAssetDashboard();",1000 );
				}
		
		}
}
function SrvLoadZoneDashboard(page_num) {
    $.support.cors = true;
    $.ajax({
        url: BACKENDURL + "customeradmin/get_zone_dashboard",
        type: "post",
        data: {
            session_id: localStorage["SESSIONID"],
            contract_id: localStorage["contractid"],
            page_no: page_num
        },
        dataType: "json",
        crossDomain: true,
        async: false,
        success: function(data) {
            if (data.session_status) {
                loadZoneDashboard(data);
            }
        }
    });
    /*var url = BACKENDURL + "customeradmin/get_zone_dashboard";
     var data = {
     session_id: localStorage["SESSIONID"],
     contract_id: localStorage["contractid"],
     page_no: page_num
     };
     MakeAjaxCall(url, data, loadZoneDashboard);*/
    $("#btnNewZoneSubmit").unbind("click").bind("click", SrvNewZoneSubmit);
//$("#btnCloseZoneDashboard").bind("click",closeZoneDashBoard);
//$("#btnSaveZoneDashboard").bind("click",SrvZoneDashBoardSave);
    $("#btncancelZone").click(function() {
        $("#txtnewZoneName").val("");
    });
    $("#xNewZone").click(function() {
        $("#txtnewZoneName").val("");
    });
    $("#txtnewZoneName").val("");
    if ($("#sltNetwork").val() == "23") {
        $("#txtOther").removeAttr("disabled");
    }
    else
    {
        $("#txtOther").attr("disabled", true);
    }
    if (localStorage["PrevStatRefresh"] == "1")
    {
        localStorage["autorefresh"] = "true";
    }

}

function loadZoneDashboard(data) {
    if (data.session_status) {
        if (data.error == 0) {
            $("#spnZoneRefresh").addClass("hide");
            if (data.zond_dash.zlist.length == 0) {
                $("#divZonefirst").hide();
                $("#divZonedashErrMsg").removeClass("hide");
		        $("#divNewZoneButton").hide();
                $("#lblAutoRefresh").hide();
                $("#btnZoneRefresh").hide();
            }
            else
            {
                $("#btnZoneRefresh").show();
                $("#lblAutoRefresh").show();
                $('#divZonefirst').empty();
                var zoneDashStr = "";
                for (var nCount = 0; nCount < data.zond_dash.zlist.length; nCount++) {
                    var status = "";
					 var disabled = (data.zond_dash.zlist[nCount].lc == "0") ? "disabled" : "";					
                    if (data.zond_dash.zlist[nCount].zst == "g")
                    {
						
                        zoneDashStr += "<div class='panel panel-success span3'><div class='panel-heading'><div id='div_zone_dash' class='btn-group pull-right'><button class='btn btn-mini btn-success' id='btn_info" + data.zond_dash.zlist[nCount].zid + "' rel='popover' title='" + data.zond_dash.zlist[nCount].zn + "' data-content='" + data.zond_dash.zlist[nCount].d.replace(/\n/g, "<br />") + "'><i class='icon-info-sign icon-white'></i></button><button class='btn btn-mini btn-success' id='btn_graph" + data.zond_dash.zlist[nCount].zid + "' data-toggle='tooltip' data-placement='bottom' data-original-title='Graph' onclick='javascript:btnclick(" + data.zond_dash.zlist[nCount].zid + ")'><i class='icon-signal icon-white'></i></button><button class='btn btn-mini btn-success' id='btn_edit" + data.zond_dash.zlist[nCount].zid + "' data-toggle='tooltip' data-placement='bottom' data-original-title='Settings' onclick='javascript:SrvEditZone(" + data.zond_dash.zlist[nCount].zid + ")'><i class='icon-cog icon-white'></i></button><button class='btn btn-mini btn-success' id='btn_delete" + data.zond_dash.zlist[nCount].zid + "' data-toggle='tooltip' data-placement='bottom' data-original-title='Delete' onclick='javascript:modalDeleteZone(" + data.zond_dash.zlist[nCount].zid + ")'><i class='icon-trash icon-white'></i></button></div><h3 id='zone_heading_panel' class='panel-title'>" + data.zond_dash.zlist[nCount].zn + "</h3></div><div class='aligncenter'><p class='lead text-success'>" + data.zond_dash.zlist[nCount].lc + " Assets</p><p class='text-success' style='margin-bottom: 20px;'>Stock Levels Healthy</p><p><button class='btn btn-large btn-success' onclick='javascript:moreDet_Zone(" + data.zond_dash.zlist[nCount].zid + ",\""+ data.zond_dash.zlist[nCount].zn + "\");' "+disabled+">More Details</button></p></div></div>";

                    } else if (data.zond_dash.zlist[nCount].zst == "o")
                    {
						
                        zoneDashStr += "<div class='panel panel-warning span3'><div class='panel-heading'><div id='div_zone_dash' class='btn-group pull-right'><button class='btn btn-mini btn-warning' id='btn_info" + data.zond_dash.zlist[nCount].zid + "' rel='popover' title='" + data.zond_dash.zlist[nCount].zn + "' data-content='" + data.zond_dash.zlist[nCount].d.replace(/\n/g, "<br />") + "'><i class='icon-info-sign icon-white'></i></button><button class='btn btn-mini btn-warning' id='btn_graph" + data.zond_dash.zlist[nCount].zid + "' data-toggle='tooltip' data-placement='bottom' data-original-title='Graph' onclick='javascript:btnclick(" + data.zond_dash.zlist[nCount].zid + ")'><i class='icon-signal icon-white'></i></button><button class='btn btn-mini btn-warning' id='btn_edit" + data.zond_dash.zlist[nCount].zid + "' data-toggle='tooltip' data-placement='bottom' data-original-title='Settings' onclick='javascript:SrvEditZone(" + data.zond_dash.zlist[nCount].zid + ")'><i class='icon-cog icon-white'></i></button><button class='btn btn-mini btn-warning' id='btn_delete" + data.zond_dash.zlist[nCount].zid + "' data-toggle='tooltip' data-placement='bottom' data-original-title='Delete' onclick='javascript:modalDeleteZone(" + data.zond_dash.zlist[nCount].zid + ")'><i class='icon-trash icon-white'></i></button></div><h3 id='zone_heading_panel' class='panel-title'>" + data.zond_dash.zlist[nCount].zn + "</h3></div><div class='aligncenter'><p class='lead text-warning'>" + data.zond_dash.zlist[nCount].lc + " Assets</p><p class='text-warning' style='margin-bottom: 20px;'>Stock Levels High (Max " + data.zond_dash.zlist[nCount].ht + ")</p><p><button class='btn btn-large btn-warning'onclick='javascript:moreDet_Zone(" + data.zond_dash.zlist[nCount].zid + ",\"" + data.zond_dash.zlist[nCount].zn + "\");' "+disabled+">More Details</button></p></div></div>";
                    } else if (data.zond_dash.zlist[nCount].zst == "r")
                    {
						
                        zoneDashStr += "<div class='panel panel-danger span3'><div class='panel-heading'><div id='div_zone_dash' class='btn-group pull-right'><button class='btn btn-mini btn-danger' id='btn_info" + data.zond_dash.zlist[nCount].zid + "' rel='popover' title='" + data.zond_dash.zlist[nCount].zn + "' data-content='" + data.zond_dash.zlist[nCount].d.replace(/\n/g, "<br />") + "'><i class='icon-info-sign icon-white'></i></button><button class='btn btn-mini btn-danger' id='btn_graph" + data.zond_dash.zlist[nCount].zid + "' data-toggle='tooltip' data-placement='bottom' data-original-title='Graph' onclick='javascript:btnclick(" + data.zond_dash.zlist[nCount].zid + ")'><i class='icon-signal icon-white'></i></button><button class='btn btn-mini btn-danger' id='btn_edit" + data.zond_dash.zlist[nCount].zid + "' data-toggle='tooltip' data-placement='bottom' data-original-title='Settings' onclick='javascript:SrvEditZone(" + data.zond_dash.zlist[nCount].zid + ")'><i class='icon-cog icon-white'></i></button><button class='btn btn-mini btn-danger' id='btn_delete" + data.zond_dash.zlist[nCount].zid + "' data-toggle='tooltip' data-placement='bottom' data-original-title='Delete' onclick='javascript:modalDeleteZone(" + data.zond_dash.zlist[nCount].zid + ")'><i class='icon-trash icon-white'></i></button></div><h3 id='zone_heading_panel' class='panel-title'>" + data.zond_dash.zlist[nCount].zn + "</h3></div><div class='aligncenter'><p class='lead text-error'>" + data.zond_dash.zlist[nCount].lc + " Assets</p><p class='text-error' style='margin-bottom: 20px;'>Stock Levels Low (Min " + data.zond_dash.zlist[nCount].lt + ")</p><p><button class='btn btn-large btn-danger'onclick='javascript:moreDet_Zone(" + data.zond_dash.zlist[nCount].zid + ",\""+ data.zond_dash.zlist[nCount].zn + "\");' "+disabled+">More Details</button></p></div></div>";
                    } else if (data.zond_dash.zlist[nCount].zst == "b")
                    {	
						
                        zoneDashStr += "<div class='panel panel-info span3'><div class='panel-heading'><div id='div_zone_dash' class='btn-group pull-right'><button class='btn btn-mini btn-info' id='btn_info" + data.zond_dash.zlist[nCount].zid + "' rel='popover' title='" + data.zond_dash.zlist[nCount].zn + "' data-content='" + data.zond_dash.zlist[nCount].d.replace(/\n/g, "<br />") + "'><i class='icon-info-sign icon-white'></i></button><button class='btn btn-mini btn-info' id='btn_graph" + data.zond_dash.zlist[nCount].zid + "' data-toggle='tooltip' data-placement='bottom' data-original-title='Graph' onclick='javascript:btnclick(" + data.zond_dash.zlist[nCount].zid + ")'><i class='icon-signal icon-white'></i></button><button class='btn btn-mini btn-info' id='btn_edit" + data.zond_dash.zlist[nCount].zid + "' data-toggle='tooltip' data-placement='bottom' data-original-title='Settings' onclick='javascript:SrvEditZone(" + data.zond_dash.zlist[nCount].zid + ")'><i class='icon-cog icon-white'></i></button><button class='btn btn-mini btn-info' id='btn_delete" + data.zond_dash.zlist[nCount].zid + "' data-toggle='tooltip' data-placement='bottom' data-original-title='Delete' onclick='javascript:modalDeleteZone(" + data.zond_dash.zlist[nCount].zid + ")'><i class='icon-trash icon-white'></i></button></div><h3 id='zone_heading_panel' class='panel-title'>" + data.zond_dash.zlist[nCount].zn + "</h3></div><div class='aligncenter'><p class='lead text-info'>" + data.zond_dash.zlist[nCount].lc + " Assets</p><p class='text-info' style='margin-bottom: 20px;'>Reader Not Responding</p><p><button class='btn btn-large btn-info' onclick='javascript:moreDet_Zone("+ data.zond_dash.zlist[nCount].zid +",\"" +data.zond_dash.zlist[nCount].zn +"\");' "+disabled+">More Details</button></p></div></div>";

                    }
                }
                $('#divZonefirst').append(zoneDashStr);
                $("#divZonefirst").show();
                zoneDashStr = "";
                if (data.zond_dash.tc > 12) {
                    var total_zonedashtc = data.zond_dash.tc;
                    var total_page = Math.ceil(total_zonedashtc / 12);
                    var no_pages = total_page > 1 ? 3 : 1;
                    if (total_zonedashtc > 12) {
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
                                SrvLoadZoneDashboard(page_num);
                            }
                        };
                        $("#DivZoneFirst_pag").bootstrapPaginator(options);
                        $("#DivZoneFirst_pag").show();
                    }
                    else
                    {
                        $("#DivZoneFirst_pag").hide();
                    }
                }

            }
        } else {
            logout(1);
        }
        customerModuleAccess('AL3ZDBD', 0);
        $("button[id^=btn_graph],button[id^=btn_edit],button[id^=btn_delete]").tooltip({
            'selector': '',
            'placement': 'right',
            'width': '400px'
        });
        $("button[id^=btn_info]").popover({'placement': 'bottom'});

    }
}

function moreDet_Zone(zone_id,zone_name)
{
$("#divAssetNew").hide();
$("#btnNewAssetSubmit").unbind("click").bind("click", SrvNewAssetSubmit);
	localStorage["ZONENAME"] = zone_name;
	localStorage["ZONEID"] = zone_id;
	
    var url = BACKENDURL + "customeradmin/asset_dash_search";
    var data = {
        session_id: localStorage["SESSIONID"],
        zone_id: zone_id
    };
    MakeAjaxCall(url, data, loadAssetDashboard);
}

/*****************DropDown Dashboard*********************/
function SrvLoadZoneDropDown() {
    var url = BACKENDURL + "common/get_data_any";
    var data = {
        session_id: localStorage["SESSIONID"],
        data_ref: "network"
    };
    MakeAjaxCall(url, data, ZoneDashBrdDropdown);
}
function ZoneDashBrdDropdown(data)
{
    $("#sltNetwork", "#divZoneSecond").empty();
    for (var nCount = 0; nCount < data.data_any_res.length; nCount++) {
        titleVal = "<option value=" + data.data_any_res[nCount].data_value_id + " >" + data.data_any_res[nCount].data_value + "</option>";
        $("#sltNetwork", "#divZoneSecond").append(titleVal);
        $("#sltNetwork").change(function() {
            if ($("#sltNetwork").val() == "23") {
                $("#txtOther").removeAttr("disabled");
                $("#btnSaveZoneDashboard").removeAttr("disabled").text('Save');
            }
            else
            {
                $("#txtOther").val("");
                $("#txtOther").attr("disabled", true);
                $("#btnSaveZoneDashboard").removeAttr("disabled").text('Save');
            }
        });
    }
}

function SrvNewZoneSubmit() {

    if ($("#frmCreateZone").valid()) {
        $("#DivZoneFirst_pag").hide();
        $("#btnZoneRefresh").hide();
        SrvLoadZoneDropDown();
        $("#divZonedashSuccessMsg").addClass('hide');
        $("#divZonedashSuccessMsg").addClass('hide');
        $("#divZonedashBackendErrMsg").addClass('hide').text("").hide();
        $("#frmNewZone").data('validator').resetForm();
        $(".error").removeClass("error");
        $("#btnSaveZoneDashboard").text('Save').attr("disabled", true);
        var zoneName = $("#txtnewZoneName").val();
        $("#txtZoneid").val("");
        localStorage.setItem("zonename", " ");
        localStorage.setItem("zonename", zoneName);
        $("#txtZoneName").val(zoneName);
        $("#txtZoneDesc").val("");
        $("#txtDeviceID").val("");
        $("#txtSerialNo").val("");
        $("#txtHighThreshold").val("");
        $("#txtLowThreshold").val("");
        $("#sltNetwork").val("");
        $("#txtOther").val("");
        $("#txtTimeout").val("");
        $("#txtTagTimeout").val("");
        $("#txtDateTime").val("");
        $("#txtNoassets").val("");
        $("#txtFirmWare").val("");
        $("#divNewZoneButton").hide();
        $("#divZonedashErrMsg").addClass("hide");
        $("#divZonefirst").hide();
        $("#divNewZone").modal('hide');
        $("#divZoneSecond").show();

        $('input[id*="txt"]').bind("keyup", function() {

            $("#btnSaveZoneDashboard").text('Save').attr("disabled", false);
            $("#divZonedashSuccessMsg").addClass('hide');
            $("#divZonedashSuccessMsg").addClass('hide');
        });
        $("#txtZoneDesc").bind("keyup", function() {
            $("#btnSaveZoneDashboard").text('Save').attr("disabled", false);
            $("#divZonedashSuccessMsg").addClass('hide');
            $("#divZonedashSuccessMsg").addClass('hide');
        });
        $('select[id^="sltNetwork"]').change(function() {
            $("#btnSaveZoneDashboard").removeAttr("disabled").text('Save');
            $("#divZonedashSuccessMsg").addClass('hide');
            $("#divZonedashSuccessMsg").addClass('hide');
        });
		if ($("#sltNetwork").val() == "23") {
            $("#txtOther").removeAttr("disabled");
        }
        else
        {
            $("#txtOther").val("");
            $("#txtOther").attr("disabled", true);
        }

    }
    $("#btnZoneRefresh").addClass('hide');
    if (localStorage["autorefresh"] == "true")
    {
        localStorage["autorefresh"] = "false";
        localStorage["PrevStatRefresh"] = "1";
    }

}

/*****************Save Dashboard*********************/
function SrvZoneDashBoardSave() {

    if ($("#frmNewZone").valid()) {
        if (parseInt($("#txtHighThreshold", "#divZoneSecond").val()) <= parseInt($("#txtLowThreshold", "#divZoneSecond").val()))
        {
            $("#divZonedashBackendErrMsg").removeClass('hide').text("High Threshold should be maximum comparing to Low threshold.").show();
        }
        else
        {
            $("#btnSaveZoneDashboard").attr("disabled", true).text('Saving');
            var url = BACKENDURL + "customeradmin/add_edit_zone";
            var data = {
                session_id: localStorage["SESSIONID"],
                contract_id: localStorage["contractid"],
                zone_id: $("#txtZoneid").val(),
                zone_name: $("#txtZoneName").val(),
                description: $("#txtZoneDesc").val(),
                device_id: $("#txtDeviceID").val(),
                serial_no: $("#txtSerialNo").val(),
                high_threshold: $("#txtHighThreshold").val(),
                low_threshold: $("#txtLowThreshold").val(),
                network_id: $("#sltNetwork").val(),
                network_desc: $("#txtOther").val(),
                timeout: $("#txtTimeout").val(),
                tagtimeout:$("#txtTagTimeout").val(),
            };
            MakeAjaxCall(url, data, SaveZoneDashboard);
        }
    }

}
function SaveZoneDashboard(data) {
    if (data.error) {
        if (data.error_msg == unAthMsg)
            logout(1);
        else {
            $("#btnSaveZoneDashboard").text('Save').attr("disabled", false);
            $("#divZonedashBackendErrMsg").removeClass('hide').text(data.error_msg).show();
            $("#divZonedashSuccessMsg").addClass('hide');
            return false;
        }
    }
    else
    {
		$("#btnSaveZoneDashboard").text('Saved').attr("disabled", true);
        $("#txtZoneid").val(data.zone_details[0].zone_id);
        $("#divZonedashSuccessMsg").removeClass('hide');
        $("#divZonedashBackendErrMsg").addClass('hide').text("").hide();       
		closeZoneDashBoard();
        return false;
    }
}
/*****************Delete Dashboard*********************/
function modalDeleteZone(zid)
{
    localStorage.setItem("zid", " ");
    localStorage.setItem("zid", zid);
    $("#divAlertDelete").modal('show');
    $("#btnYesAlert").bind("click", SrvdeleteZone);
}
function SrvdeleteZone() {
    $("#divAlertDelete").modal('hide');
    $("#btnYesAlert").unbind("click");
    var url = BACKENDURL + "customeradmin/delete_zone";
    var data = {
        session_id: localStorage["SESSIONID"],
        contract_id: localStorage["contractid"],
        zone_id: localStorage["zid"]
    };
    MakeAjaxCall(url, data, deleteZoneDashboard);
}
function deleteZoneDashboard(data) {
    if (data.error == 0) {
        SrvLoadZoneDashboard(page_num);
    } else
        logout(1);
}
function closeZoneDashBoard() {
    $("#btnZoneRefresh").show();
    $("#divZonefirst").show();
    $("#divNewZoneButton").show();
    $("#divZoneSecond").hide();
    $("#divZonedashSuccessMsg").addClass('hide');
    $("#divZonedashBackendErrMsg").addClass('hide').text("").hide();
    $("#btnZoneRefresh").removeClass('hide');
    SrvLoadZoneDashboard(page_num);
}

function closeZoneGraph() {
    $("#btnZoneRefresh").show();
    $("#divZonefirst").show();
    $("#divNewZoneButton").show();
    $("#divZoneGraph").hide();
    SrvLoadZoneDashboard(page_num);
    $("#DivZoneFirst_pag").show();
}

/*****************Edit Dashboard*********************/

function SrvEditZone(zid) {
    /* $("#txtHighThreshold").change(function() {
     console.log("change1")
     funThreshold();
     });
     $("#txtLowThreshold").change(function() {
     console.log("change2")
     funThreshold();
     }); */
    $("#btnZoneRefresh").hide();
    $("#DivZoneFirst_pag").hide();
    SrvLoadZoneDropDown();
    $("#frmNewZone").data('validator').resetForm();
    $(".error").removeClass("error");
    $("#txtZoneid").val(zid);
    $("#btnSaveZoneDashboard").text('Saved').attr("disabled", true);
    if (localStorage["autorefresh"] == "true")
    {
        localStorage["autorefresh"] = "false";
        localStorage["PrevStatRefresh"] = "1";
    }
    var url = BACKENDURL + "customeradmin/get_zone_details";
    var data = {
        session_id: localStorage["SESSIONID"],
        contract_id: localStorage["contractid"],
        zone_id: zid

    };
    MakeAjaxCall(url, data, editZoneDashboard);

}
function editZoneDashboard(data) {

    if (data.error == 0) {
        $("#divNewZoneButton").hide();
        $("#divZonedashErrMsg").addClass("hide");
        $("#divZonefirst").hide();
        $("#divNewZone").modal('hide');
        $("#divZoneSecond").show();
        $('input[id*="txt"]').bind("keyup", function() {
            $("#btnSaveZoneDashboard").text('Save').attr("disabled", false);
            $("#divZonedashSuccessMsg").addClass('hide');
            $("#divZonedashSuccessMsg").addClass('hide');
        });
        $("#txtZoneDesc").bind("keyup", function() {
            $("#btnSaveZoneDashboard").text('Save').attr("disabled", false);
            $("#divZonedashSuccessMsg").addClass('hide');
            $("#divZonedashSuccessMsg").addClass('hide');
        });
        $('select[id^="sltNetwork"]').change(function() {
            $("#btnSaveZoneDashboard").removeAttr("disabled").text('Save');
            $("#divZonedashSuccessMsg").addClass('hide');
            $("#divZonedashSuccessMsg").addClass('hide');
        });

        $("#txtZoneName").val(data.zond_details[0].zn);
        $("#txtZoneDesc").val(data.zond_details[0].d);
        $("#txtDeviceID").val(data.zond_details[0].did);
        $("#txtSerialNo").val(data.zond_details[0].sid);
        $("#txtHighThreshold").val(data.zond_details[0].ht);
        $("#txtLowThreshold").val(data.zond_details[0].lt);
        $("#sltNetwork").val(data.zond_details[0].nid);
        $("#txtOther").val(data.zond_details[0].ndes);
        $("#txtTimeout").val(data.zond_details[0].t);
        $("#txtTagTimeout").val(data.zond_details[0].tt);
        $("#txtDateTime").val(data.zond_details[0].ld);
        $("#txtNoassets").val(data.zond_details[0].lc);
        $("#txtFirmWare").val(data.zond_details[0].f);
        if ($("#sltNetwork").val() == "23") {
            $("#txtOther").removeAttr("disabled");
        }
        else
        {
            $("#txtOther").val("");
            $("#txtOther").attr("disabled", true);
        }
    } else
        logout(1);

}
/* function funThreshold(){
 console.log("threshold")
 var highThresh = $("#txtHighThreshold").val();
 var Lowthresh = $("#txtLowThreshold").val();
 console.log('h'+highThresh)
 console.log('l'+Lowthresh)
 if(highThresh<Lowthresh)
 {
 console.log("greater")
 $("#divZonedashBackendErrMsg").html("please enter threshold value greater than low threshold value").show();
 $("#txtHighThreshold").addClass('control-group error');
 $("#txtLowThreshold").addClass('control-group error'); 
 alert("please enter threshold value greater than low threshold value")
 isvalid = false;
 }else
 {
 console.log("lesser")
 $("#divZonedashBackendErrMsg").addClass('hide').text("").hide();
 isvalid = true;		
 }
 } */
// Graph Chart 
function btnclick(Zoneid)
{
    $("#DivZoneFirst_pag").hide();
    $("#btnZoneRefresh").hide();
    localStorage["ZONEID"] = Zoneid;
    $("#divNewZoneButton").hide();
    $("#divZonefirst").hide();
    $("#divZoneGraph").show();
	$("#idZoneGraphNextMon").attr('disabled', true);
    var today = new Date();
    var m = (today.getMonth() + 1) + "/" + today.getFullYear();
    $('#idZoneGraphDate').datepicker({endDate: m, minViewMode: 1, placement: 'left', }).on(
            'changeDate', function() {
        $('#idZoneGraphDate').datepicker('hide');
        m == $("#idZoneGraphEndDate").val() ? $("#idZoneGraphNextMon").attr('disabled', true) : $("#idZoneGraphNextMon").attr('disabled', false);
    }
    );
    $('#idZoneGraphDate').data({date: m}).datepicker('update');
    $("#idZoneGraphEndDate").val(m);
    if (localStorage["autorefresh"] == "true")
    {
        localStorage["autorefresh"] = "false";
        localStorage["PrevStatRefresh"] = "1";
    }
    Srv_PopulateMonthCharts(Zoneid);
}
function Srv_PopulateMonthCharts(Zoneid)
{
    var url = BACKENDURL + "customeradmin/get_zone_chart_details";
    var today = new Date();

    var data = {
        session_id: localStorage["SESSIONID"],
        contract_id: localStorage["contractid"],
        zone_id: Zoneid,
        end_date: (today.getDate()) + "/" + $("#idZoneGraphEndDate").val()
    };
    MakeAjaxCall(url, data, PopulateMonthCharts);
}

function PopulateMonthCharts(data) {
    if (data.session_status) {
        if (data.error == 0) {
            var len = data.zone_details.zone_data.length;
            var ranges = [], mins = [], maxs = [], averages = [];

            for (i = 0; i < len; i++)
            {
                var da = data.zone_details.zone_data[i]['d'].split("-");
                var y = da[0];
                //var m = parseInt(da[1])-1;
                var m = parseInt(da[1], 10) - 1;
                var d = da[2];
                ranges[i] = [Date.UTC(y, m, d), parseInt(data.zone_details.zone_data[i]['lt']), parseInt(data.zone_details.zone_data[i]['ht'])];
                averages[i] = [Date.UTC(y, m, d), parseFloat(data.zone_details.zone_data[i]['avg'])];
                mins[i] = [Date.UTC(y, m, d), parseInt(data.zone_details.zone_data[i]['min'])];
                maxs[i] = [Date.UTC(y, m, d), parseInt(data.zone_details.zone_data[i]['max'])];
            }

            new Highcharts.Chart({
                chart: {
                    renderTo: 'divZoneMonthChart'
                },
                credits: {
                    enabled: false
                },
                title: {
                    text: 'Number of Assets in Zone ' + data.zone_details.zone_name,
                    x: -20 //center
                },
                subtitle: {
                    text: data.zone_details.sub_title,
                    x: -20
                },
                xAxis: {
                    type: 'datetime',
                    labels: {
                        formatter: function() {
                            return Highcharts.dateFormat('%d.%b', this.value);
                        }
                    }

                },
                yAxis: {
                    title: {
                        text: null
                    },
                    min:0
                },
                tooltip: {
                    crosshairs: true,
                    shared: true,
                    valueSuffix: ' assets'
                },
                legend: {},
                series: [
                    {
                        name: 'Average',
                        data: averages,
                        zIndex: 1,
                        marker: {
                            fillColor: 'white',
                            lineWidth: 2,
                            lineColor: Highcharts.getOptions().colors[0]
                        }
                    }, {
                        name: 'Min',
                        data: mins,
                        zIndex: 1,
                        marker: {
                            fillColor: 'red',
                            lineWidth: 2,
                            lineColor: Highcharts.getOptions().colors[0]
                        }
                    }, {
                        name: 'Max',
                        data: maxs,
                        zIndex: 1,
                        marker: {
                            fillColor: 'red',
                            lineWidth: 2,
                            lineColor: Highcharts.getOptions().colors[0]
                        }
                    }, {
                        name: 'Range',
                        data: ranges,
                        type: 'arearange',
                        lineWidth: 0,
                        linkedTo: ':previous',
                        color: Highcharts.getOptions().colors[0],
                        fillOpacity: 0.3,
                        zIndex: 0
                    }


                ]
            });
        } else {
            logout();
        }
    }
}
/************************Asset Dashboard**************************/
/*******************load asset dashboard *********************************/
function SrvLoadAssetDashboard() {
	localStorage["ZONENAME"] = "All Zones";

    $("#divZoneGraph").hide();
    var url = BACKENDURL + "customeradmin/asset_dash_search";
    var data = {
        session_id: localStorage["SESSIONID"],
        zone_id: null
    };
    MakeAjaxCall(url, data, loadAssetDashboard);
    $("#btnNewAssetSubmit").unbind("click").bind("click", SrvNewAssetSubmit);
//$("#btnCloseAssetDashboard").bind("click",closeAssetDashBoard);
    $("#btncancelAsset").click(function() {
        $("#txtnewAssetTagNumber").val("");
    });
    $("#xNewAsset").click(function() {
        $("#txtnewAssetTagNumber").val("");
    });
    $("#txtnewAssetTagNumber").val("");
    if ($("#sltAssetType").val() == "27") {
        $("#txtAssetOther").removeAttr("disabled");
    }
    else
    {
        $("#txtAssetOther").attr("disabled", true);
    }
    if (localStorage["PrevStatRefreshAsset"] == "1")
    {
        localStorage["autorefreshasset"] = "true";
    }
}
function loadAssetDashboard(data) {
    if (data.session_status) {
        if (data.error == 0) {
            $("#spnAssetRefresh").addClass("hide");
            if (data.asset_details.length == 0) {
                $("#ZoneDashboard").hide();
                $("#AssetDashboard").show();
                $('#widgetnav a[href="#AssetDashboard"]').tab('show');
                $("#divAssetDetails").hide();
                $("#divAssetCheckbox").hide();
                $("#divAssetdashErrMsg").removeClass("hide");
				$("#divAssetRefreshAddControls").hide();
                $("#lblAutoRefresh").hide();
                $("#btnZoneRefresh").hide();
            }
            else
            {
			 var nCurrRecRound = 0;
                $("#btnZoneRefresh").show();
                $("#divAssetCheckbox").show();
                $("#lblAutoRefresh").show();
				$("#divAssetdashErrMsg").addClass("hide");
                var assetDashStr = "";
				$("#tablePagination").hide();
				var hdnCurrPage = $("#currPageNumber", "#tablePagination").val();
                    if (hdnCurrPage != undefined) {
                        nCurrRecRound = hdnCurrPage - 1;
                    }
                $("#tblAssetDashBrd tbody").children().remove();
                for (var nCount = 0; nCount < data.asset_details.length; nCount++) {
				var zl = (data.asset_details[nCount].zl == null) ? 'N/A' : data.asset_details[nCount].zl;
				var lr = (data.asset_details[nCount].lr == null) ? 'N/A' : data.asset_details[nCount].lr;
				var zp = (data.asset_details[nCount].zp == null) ? 'N/A' : data.asset_details[nCount].zp;
				var pr = (data.asset_details[nCount].pr == null) ? 'N/A' : data.asset_details[nCount].pr;

				if (data.asset_details[nCount].ast == "g"){
                        assetDashStr += "<tr><td style='width:540px;font-size:14px;color:black;margin-bottom:0px;'><strong>" + data.asset_details[nCount].atyp + ": </strong>" + data.asset_details[nCount].anum + "." + data.asset_details[nCount].ades + "<strong><br>Latest Read: </strong>" + zl + ": " + lr + "<strong><br>Previous Read: </strong>" + zp + ": " + pr + "</td><td style='text-align:right;width: 10%;'>"
						if (data.asset_details[nCount].bs == "L") //td alone
                            assetDashStr += "<span class='label label-important' id='rpSt' style='margin-right:10px;'>Low Battery Alert (" + data.asset_details[nCount].lbd + " Days)</span>"
                        assetDashStr += "<span class='label label-success' id='rpSt'>Asset OK</span><br><span ><a id='btn_edit_asset' href='#' style='text-decoration:none;' onclick='javascript:SrvEditAsset(" + data.asset_details[nCount].aid + ")'>Edit<i class='icon-cog'></i></a></span><br><span ><a id='btn_delete_asset' href='#' style='text-decoration:none;' onclick='javascript:modalDeleteAsset(" + data.asset_details[nCount].aid + ")'>Delete<i class='icon-trash'></i></a></span></td></tr>";
                        
                    }                        
					else if (data.asset_details[nCount].ast == "o") {
                        assetDashStr += "<tr><td style='width:540px;font-size:14px;color:black;margin-bottom:0px;'><strong>" + data.asset_details[nCount].atyp + ": </strong>" + data.asset_details[nCount].anum + "." + data.asset_details[nCount].ades + "<strong><br>Latest Read: </strong>" + zl + ": " + lr + "<strong><br>Previous Read: </strong>" + zp + ": " + pr + "</td><td style='text-align:right;width: 10%;'>"
                        if (data.asset_details[nCount].bs == "L") //td alone
                            assetDashStr += "<span class='label label-important' id='rpSt' style='margin-right:10px;'>Low Battery Alert (" + data.asset_details[nCount].lbd + " Days)</span>"
                        assetDashStr += "<span class='label label-warning' id='rpSt'>Asset Not Responding</span><br><span ><a id='btn_edit_asset' href='#' style='text-decoration:none;' onclick='javascript:SrvEditAsset(" + data.asset_details[nCount].aid + ")'>Edit<i class='icon-cog'></i></a></span><br><span ><a id='btn_delete_asset' href='#' style='text-decoration:none;' onclick='javascript:modalDeleteAsset(" + data.asset_details[nCount].aid + ")'>Delete<i class='icon-trash'></i></a></span></td></tr>";
                    }
                    else if (data.asset_details[nCount].ast == "b") {
                        assetDashStr += "<tr><td style='width:440px;font-size:14px;color:black;margin-bottom:0px;'><strong>" + data.asset_details[nCount].atyp + ": </strong>" + data.asset_details[nCount].anum + "." + data.asset_details[nCount].ades + "<strong><br>Latest Read: </strong>" + zl + ": " + lr + "<strong><br>Previous Read: </strong>" + zp + ": " + pr + "</td><td style='text-align:right;width: 10%;'>"
                        if (data.asset_details[nCount].bs == "L") //td alone
                            assetDashStr += "<span class='label label-important' id='rpSt' style='margin-right:10px;'>Low Battery Alert (" + data.asset_details[nCount].lbd + " Days)</span>"
                        assetDashStr += "<span class='label label-info' id='rpSt'>Removed From Zone</span><br><span ><a id='btn_edit_asset' href='#' style='text-decoration:none;' onclick='javascript:SrvEditAsset(" + data.asset_details[nCount].aid + ")'>Edit<i class='icon-cog'></i></a></span><br><span ><a id='btn_delete_asset' href='#' style='text-decoration:none;' onclick='javascript:modalDeleteAsset(" + data.asset_details[nCount].aid + ")'>Delete<i class='icon-trash'></i></a></span></td></tr>";
                    }
                    else
                        assetDashStr += "<tr><td style='width:440px;font-size:14px;color:black;margin-bottom:0px;'><strong>" + data.asset_details[nCount].atyp + ": </strong>" + data.asset_details[nCount].anum + "." + data.asset_details[nCount].ades + "<strong><br>Latest Read: </strong>" + zl + ": " + lr + "<strong><br>Previous Read: </strong>" + zp + ": " + pr + "</td><td style='text-align:right;width: 10%;'><span class='label label-important' id='rpSt'>Low Battery Alert (" + data.asset_details[nCount].lbd + " Days)</span><br><span ><a id='btn_edit_asset' href='#' style='text-decoration:none;' onclick='javascript:SrvEditAsset(" + data.asset_details[nCount].aid + ")'>Edit<i class='icon-cog'></i></a></span><br><span ><a id='btn_delete_asset' href='#' style='text-decoration:none;' onclick='javascript:modalDeleteAsset(" + data.asset_details[nCount].aid + ")'>Delete<i class='icon-trash'></i></a></span></td></tr>"
					var assetName=data.asset_details[nCount].anum;
					var assetSearch=assetName.search("/");
					
					if (data.asset_details[nCount].aid == asset_Pag) {
                            nCurrRecRound = Math.floor(nCount / 10);
                            $("#txtnewAssetTagNumber").val("");
							asset_Pag = 0;
                        }	
				}
				

                $("#tblAssetDashBrd  tbody:last").append(assetDashStr);
				
				$("#search").val('');
				if(localStorage["ZONENAME"] != "All Zones")
				{
				
				$("#divCloseAssetButton").show();
				}else{
				
				$("#divCloseAssetButton").hide();
				}
				$("#assetZoneLabel").html('Showing a total of <strong>' + $("#tblAssetDashBrd tr").length + ' assets</strong>. Search and filter assets by their properties across <strong>' + localStorage["ZONENAME"] + '</strong>.');				
				
                $("#ZoneDashboard").hide();
                $("#AssetDashboard").show();
                $('#widgetnav a[href="#AssetDashboard"]').tab('show');

                $("#divAssetDetails").show();
                // zoneDashStr = "";
                var nRows = 10;
                //pagination for more than 2 schools added				
                if (data.asset_details.length > nRows) {
                    $("#tablePagination").html('');
                    $("#divAssetDashboardTable").tablePagination({
                        rowsPerPage: nRows,
						currPage: nCurrRecRound + 1
                    });
                }
            }
        } else {
            logout(1);
        }
        customerModuleAccess('AL3ADBD', 0);
    }
}
function SrvNewAssetSubmit() {
    $("#divAssetDetails").hide();
    $("#divNewAsset").modal('hide');
    $("#divAssetNew").show();
    $("#txtnewAssetTagNumber").val("");
    $("#btnZoneRefresh").hide();
    if ($("#sltAssetType").val() == "27") {
        $("#txtAssetOther").removeAttr("disabled");
    }
    else
    {
        $("#txtAssetOther").attr("disabled", true);
    }
    SrvLoadAssetDropDown();
}

/*****************Asset DropDown Dashboard*********************/
function SrvLoadAssetDropDown() {
    var url = BACKENDURL + "common/get_data_any";
    var data = {
        session_id: localStorage["SESSIONID"],
        data_ref: "asset_type"
    };
    MakeAjaxCall(url, data, AssetDashBrdDropdown);
}
function AssetDashBrdDropdown(data)
{
    $("#sltAssetType", "#divAssetNew").empty();
    for (var nCount = 0; nCount < data.data_any_res.length; nCount++) {
        titleVal = "<option value=" + data.data_any_res[nCount].data_value_id + " >" + data.data_any_res[nCount].data_value + "</option>";
        $("#sltAssetType", "#divAssetNew").append(titleVal);
        $("#sltAssetType").change(function() {
            if ($("#sltAssetType").val() == "27") {
                $("#txtAssetOther").removeAttr("disabled");
                $("#btnSaveAssetDashboard").removeAttr("disabled").text('Save');
            }
            else
            {
                $("#txtAssetOther").val("");
                $("#txtAssetOther").attr("disabled", true);
                $("#btnSaveAssetDashboard").removeAttr("disabled").text('Save');
            }
        });

    }
}
/*****************Asset Save Dashboard*********************/
function SrvAssetDashBoardSave() {
    if ($("#frmNewAsset").valid()) {
        $("#btnSaveAssetDashboard").attr("disabled", true).text('Saving');
        var assettagnumber = $("#txtnewAssetTagNumber").val();
        localStorage.setItem("assettagnumber", assettagnumber);

        var url = BACKENDURL + "customeradmin/add_edit_asset";
        var data = {
            session_id: localStorage["SESSIONID"],
			asset_id: $("#txtAssetid").val(),
            tag_no: $("#txtAssetTagNumber").val(),
            description: $("#txtAssetDesc").val(),
            asset_type_id: $("#sltAssetType").val(),
            oem_code: $("#txtAssetOEMCode").val(),
            asset_other_desc: $("#txtAssetOther").val(),
            timeout: $("#txtAssetTimeout").val()
            
        };
        MakeAjaxCall(url, data, SaveAssetDashboard);
    }
}
var asset_Pag;
function SaveAssetDashboard(data) {
    if (data.error) {
        if (data.error_msg == unAthMsg)
            logout(1);
        else {
            $("#btnSaveAssetDashboard").text('Save').attr("disabled", false);
            $("#divAssetdashBackendErrMsg").removeClass('hide').text(data.error_msg).show();
            $("#divAssetdashSuccessMsg").addClass('hide');
            return false;
        }
    }
    else
    {
	    asset_Pag=data.asset_details[0].asset_id;
        $("#txtAssetid").val(data.asset_details[0].asset_id);
        $("#divAssetdashSuccessMsg").removeClass('hide');
        $("#divAssetdashBackendErrMsg").addClass('hide').text("").hide();
        $("#btnSaveAssetDashboard").text('Saved').attr("disabled", true);
		closeAssetDashBoard();
        return false;
    }
}
/*****************Asset Edit Dashboard*********************/
function SrvEditAsset(assetid) {
//$("#btnCloseAssetDashboard").bind("click",closeAssetDashBoard)
    $("#divAssetRefreshAddControls").hide();
    $("#btnZoneRefresh").hide();
    $("#divAssetDetails").hide();
    SrvLoadAssetDropDown();
    $("#frmNewAsset").data('validator').resetForm();
    $(".error").removeClass("error");
    $("#txtAssetid").val(assetid);
    $("#btnSaveAssetDashboard").text('Saved').attr("disabled", true);
    if (localStorage["autorefreshasset"] == "true")
    {
        localStorage["autorefreshasset"] = "false";
        localStorage["PrevStatRefreshAsset"] = "1";
    }
    var url = BACKENDURL + "customeradmin/get_asset_details";
    var data = {
        session_id: localStorage["SESSIONID"],
        asset_id: assetid
    };
    MakeAjaxCall(url, data, editAssetDashboard);

}
function editAssetDashboard(data) {
    if (data.error == 0) {
        $("#divNewAssetButton").hide();
        $("#divAssetdashErrMsg").addClass("hide");
        $("#divAssetDetails").hide();
        $("#divNewAsset").modal('hide');
        $("#divAssetNew").show();
        $('input[id*="txt"]').bind("keyup", function() {
            $("#btnSaveAssetDashboard").text('Save').attr("disabled", false);
            $("#divAssetdashSuccessMsg").addClass('hide');
            $("#divAssetdashSuccessMsg").addClass('hide');
        });
        $("#txtAssetDesc").bind("keyup", function() {
            $("#btnSaveAssetDashboard").text('Save').attr("disabled", false);
            $("#divAssetdashSuccessMsg").addClass('hide');
            $("#divAssetdashSuccessMsg").addClass('hide');
        });
        $('select[id^="sltAssetType"]').change(function() {
            $("#btnSaveAssetDashboard").removeAttr("disabled").text('Save');
            $("#divAssetdashSuccessMsg").addClass('hide');
            $("#divAssetdashSuccessMsg").addClass('hide');
        });

        $("#txtAssetTagNumber").val(data.asset_details[0].tag_number);
        $("#txtAssetDesc").val(data.asset_details[0].description);
        $("#sltAssetType").val(data.asset_details[0].asset_type_id);
        $("#txtAssetOther").val(data.asset_details[0].asset_other_desc);
        $("#txtAssetOEMCode").val(data.asset_details[0].oem_code);
        $("#txtAssetTimeout").val(data.asset_details[0].timeout);
        //$("#assetBatteryStatus").val(data.asset_details[0].battery_status);
        if (data.asset_details[0].battery_status == 'H')
            $("#assetBatteryStatus").val("High");
        else if (data.asset_details[0].battery_status == 'L')
            $("#assetBatteryStatus").val("Low");		
			
					$("#assetLatestReading").val("");
					$("#assetZone").val("");
					$("#assetReadPoint").val("");
					$("#assetPreviousReading").val("");
					$("#assetZonePrevious").val("");
					$("#assetReadPointPrevious").val("");
		if (data.asset_read_res.length>0) {
			for(i=0;i<data.asset_read_res.length;i++) 
			{
				if (i==0) {
					$("#assetLatestReading").val(data.asset_read_res[i].read_date);
					$("#assetZone").val(data.asset_read_res[i].zone_name);
					$("#assetReadPoint").val(data.asset_read_res[i].read_point);
				}
				if (i==1) {
					$("#assetPreviousReading").val(data.asset_read_res[i].read_date);
					$("#assetZonePrevious").val(data.asset_read_res[i].zone_name);
					$("#assetReadPointPrevious").val(data.asset_read_res[i].read_point);
				}		
			}
		}

        if ($("#sltAssetType").val() == "27") {
            $("#txtAssetOther").removeAttr("disabled");
        }
        else
        {
            $("#txtAssetOther").val("");
            $("#txtAssetOther").attr("disabled", true);
        }
    } else
        logout(1);

}
/*****************Asset Delete Dashboard*********************/
function modalDeleteAsset(assetid)
{
    localStorage.setItem("assetid", " ");
    localStorage.setItem("assetid", assetid);
    $("#deleteZoneAssetSpan").val("asset");
    $("#divAssetAlertDelete").modal('show');
    $("#btnAssetYesAlert").unbind("click").bind("click", SrvdeleteAsset);
}
function SrvdeleteAsset() {
    $("#divAssetAlertDelete").modal('hide');
    $("#btnYesAlert").unbind("click");
    var url = BACKENDURL + "customeradmin/delete_asset";
    var data = {
        session_id: localStorage["SESSIONID"],
        asset_id: localStorage["assetid"]
    };
    MakeAjaxCall(url, data, deleteAssetDashboard);
}
function deleteAssetDashboard(data) {
    if (data.error == 0) {
	if(localStorage["ZONENAME"] != "All Zones")
				{				
				moreDet_Zone(localStorage["ZONEID"],localStorage["ZONENAME"])
				}else{				
				SrvLoadAssetDashboard();
				}

       /*  SrvLoadAssetDashboard(); */
    } else
        logout(1);
}
function closeAssetMainDash() {
    $("#AssetDashboard").hide();
    $("#ZoneDashboard").show();
    $('#widgetnav a[href="#ZoneDashboard"]').tab('show');
}
function closeAssetDashBoard() {
    $("#btnAssetRefresh").show();
    $("#divAssetDetails").show();
    $("#divNewAssetButton").show();
    $("#divAssetNew").hide();
    $("#divAssetdashSuccessMsg").addClass('hide');
    $("#divAssetdashBackendErrMsg").addClass('hide').text("").hide();
    $("#btnAssetRefresh").removeClass('hide');
    $("#divAssetRefreshAddControls").show();
    SrvLoadAssetDashboard();
}
/*******************new asset submit*********************************/
function SrvNewAssetSubmit() {
    if (localStorage["autorefreshasset"] == "true")
    {
        localStorage["autorefreshasset"] = "false";
        localStorage["PrevStatRefreshAsset"] = "1";
    }
    if ($("#frmCreateAsset").valid()) {
        $("#DivAssetFirst_pag").hide();
        $("#divAssetRefreshAddControls").hide();
        $("#btnZoneRefresh").hide();
        SrvLoadAssetDropDown();
        $("#divAssetdashSuccessMsg").addClass('hide');
        $("#divAssetdashSuccessMsg").addClass('hide');
        $("#divAssetdashBackendErrMsg").addClass('hide').text("").hide();
        $("#frmNewAsset").data('validator').resetForm();
        $(".error").removeClass("error");
        $("#btnSaveAssetDashboard").text('Save').attr("disabled", true);
        var assetTagNumber = $("#txtnewAssetTagNumber").val();
        $("#txtAssetid").val("");
        localStorage.setItem("assettagnumber", " ");
        localStorage.setItem("assettagnumber", assetTagNumber);
        $("#txtAssetTagNumber").val(assetTagNumber);
        $("#txtAssetDesc").val("");
        $("#sltAssetType").val("");
        $("#txtAssetOther").val("");
        $("#txtAssetTimeout").val("");
        $("#txtAssetOEMCode").val("");
        $("#assetBatteryStatus").val("");
        $("#assetLatestReading").val("");
        $("#assetZone").val("");
        $("#assetReadPoint").val("");
        $("#assetLatestReading").val("");
        $("#assetPreviousReading").val("");
        $("#assetZonePrevious").val("");
        $("#assetReadPointPrevious").val("");
        $("#divNewAssetButton").hide();
        $("#divAssetdashErrMsg").addClass("hide");
        $("#divAssetDetails").hide();
        $("#divNewAsset").modal('hide');
        $("#divAssetNew").show();
        $('input[id*="txt"]').bind("keyup", function() {
            $("#btnSaveAssetDashboard").text('Save').attr("disabled", false);
            $("#divAssetdashSuccessMsg").addClass('hide');
            $("#divAssetdashSuccessMsg").addClass('hide');
        });
        $("#txtAssetDesc").bind("keyup", function() {
            $("#btnSaveAssetDashboard").text('Save').attr("disabled", false);
            $("#divAssetdashSuccessMsg").addClass('hide');
            $("#divAssetdashSuccessMsg").addClass('hide');
        });
        $('select[id^="sltAssetType"]').change(function() {
            $("#btnSaveAssetDashboard").removeAttr("disabled").text('Save');
            $("#divAssetdashSuccessMsg").addClass('hide');
            $("#divAssetdashSuccessMsg").addClass('hide');
        });
		if ($("#sltAssetType").val() == "27") {
            $("#txtAssetOther").removeAttr("disabled");
        }
        else
        {
            $("#txtAssetOther").val("");
            $("#txtAssetOther").attr("disabled", true);
        }

    }
    $("#btnAssetRefresh").addClass('hide');

}

/****************Asset Dashboard********************************/


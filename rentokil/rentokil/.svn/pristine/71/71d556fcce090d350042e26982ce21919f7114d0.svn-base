var currentPage;
var current;
var unAthMsg = "Unauthorized access.";
var page_num = 1;
var zone_name;
var autoRefreshIntervalID = null;
localStorage["PrevStatRefreshUser"] = 0;
localStorage["PrevStatRefreshAssetUser"] = 0;
function LoadPageData() {
	$("#li_zonedashboard").click(function() {
		$("#divUserZoneDashboard").show();
		$("#divUserZonefirst").show();
		$("#divUserAssetDashboard").hide();
        SrvLoadUserZoneDashboard(page_num);
        if (localStorage["userautorefresh"] == "true") {
            $("#ctfusercheckbox").prop("checked", true);
			$('#countdown_user').show();
            AutoRefreshDashUser();
        }
    });
    $("#li_assetdashboard").click(function() {
	 $("#divUserAssetdashErrMsg").addClass("hide");
		$("#divUserZoneDashboard").hide();
        $("#divUserAssetDashboard").show();
		$("#divUserZoneGraph").hide();
		$("#btnUserZoneRefresh").show();
        SrvUserLoadAssetDashboard();
        $("#divAssetRefreshAddControls").show();
        if (localStorage["userautorefreshasset"] == "true") {
            $("#ctfcheckboxassetuser").prop("checked", true);
			$('#countdown_asset_user').show();
            AutoRefreshDashUser();
        }
    });
	$("#li_tabATDigitalForms").click(function(){
		$("#divUserZoneDashboard").hide();
        $("#divUserAssetDashboard").hide();
		$("#divUserZoneGraph").hide();
		$("#btnUserZoneRefresh").hide();
		
	});
    loadAssetTracking();
}
function loadAssetTracking(){
	$("#btnUserZoneRefresh").click(function(){loadSpinnerUser();});
	 $("#btnUserCloseAssetDetailsDashboard").bind("click", closeAssetMainDash);
	$("#btnUserZoneGraphEndDateGo").bind("click",function(){SrvUser_PopulateMonthCharts(localStorage["ZONEID"]);});
	$("#btnUserCloseZoneGraph").bind("click",closeUserZoneGraph);
	if($.browser.chrome || $.browser.safari) {
	   $("#countdown_user").css("margin-top","-20px");
	   $("#countdown_asset_user").css("margin-top","-20px");
	} 
	if (localStorage["userautorefresh"] == "true") {
        $("#ctfusercheckbox").prop("checked", true);
        AutoRefreshDashUser('Zone');
    }
    if (localStorage["userautorefreshasset"] == "true") {
        $("#ctfcheckboxassetuser").prop("checked", true);
        AutoRefreshDashUser('Asset');
    }
    $("#ctfusercheckbox, #ctfcheckboxassetuser").change(function() {
        if (this.checked) {
            if (this.id == 'ctfusercheckbox') {
				$('#countdown_user').show();
                localStorage.setItem("userautorefresh", "true");
                AutoRefreshDashUser();
            }
            else {
				$('#countdown_asset_user').show();
                localStorage.setItem("userautorefreshasset", "true");
                AutoRefreshDashUser();
            }
        }
        else
        {
            if (this.id == 'ctfusercheckbox') {
				$("#countdown_user span").countdown('destroy');
				$('#countdown_user').hide();
                localStorage.setItem("userautorefresh", "false");
                AutoRefreshDashUser();
            }
            else {
				$("#countdown_asset_user span").countdown('destroy');
				$('#countdown_asset_user').hide();
                localStorage.setItem("userautorefreshasset", "false");
                AutoRefreshDashUser();
            }
        }
    });

function AutoRefreshDashUser() {
    var autorefresh = localStorage.getItem('userautorefresh');
    var autorefreshasset = localStorage.getItem('userautorefreshasset');
	if((autorefresh == 'true') && (autorefreshasset == 'true')){
		$("#countdown_user span").countdown({until: 300, format: 'MS', compact: true});
		$("#countdown_asset_user span").countdown({until: 300, format: 'MS', compact: true});
	}
	else if(autorefresh == 'true')
		$("#countdown_user span").countdown({until: 300, format: 'MS', compact: true});
	else
		$("#countdown_asset_user span").countdown({until: 300, format: 'MS', compact: true});
		
    if (autorefresh == 'true' || autorefreshasset == 'true') {
        //interval set to 5 minutes
        var interval = 1000 * 60 * 5; // where X is your every X minutes
        if (!autoRefreshIntervalID) {
            autoRefreshIntervalID = window.setInterval(function() {
                
				if (localStorage["userautorefresh"] == "true" && localStorage["userautorefreshasset"] == "true"){
							SrvUserLoadAssetDashboard();
							SrvLoadUserZoneDashboard(page_num);
							$("#countdown_user span").countdown('option', {until: +300}); 
							$("#countdown_asset_user span").countdown('option', {until: +300}); 
						}
				else if (localStorage["userautorefresh"] == "true") {
						SrvLoadUserZoneDashboard(page_num);
						$("#countdown_user span").countdown('option', {until: +300}); 
						}
                else if (localStorage["userautorefreshasset"] == "true"){
							if(localStorage["ZONENAME"] != "All Zones")
							{				
							moreDet_Zone(localStorage["ZONEID"],localStorage["ZONENAME"]);
							}else{				
							SrvUserLoadAssetDashboard();
							$("#countdown_asset_user span").countdown('option', {until: +300});
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
 //Prev and Next Month functionality for User Zone Dash Charts
$("#btnUserZoneGraphPrevMon").bind("click",function(){
	var selDateArr = $("#idUserZoneGraphEndDate").val().split("/");
	var dateMonthNYear = (selDateArr[0] == 1) ? (parseInt(selDateArr[0]) +11) + "/" + (parseInt(selDateArr[1])-1): (parseInt(selDateArr[0]) -1) + "/" + (parseInt(selDateArr[1]));
	$('#idUserZoneGraphDate').data({date: dateMonthNYear}).datepicker('update');
	$("#idUserZoneGraphEndDate").val(dateMonthNYear);
	$('#idUserZoneGraphNextMon').attr('disabled', false);
	
	SrvUser_PopulateMonthCharts(localStorage["ZONEID"]);
});

  $("#idUserZoneGraphNextMon").bind("click",function(){
	var selDateArr = $("#idUserZoneGraphEndDate").val().split("/");
	var dateMonthNYear = (selDateArr[0] == 12) ? (parseInt(selDateArr[0]) -11) + "/" + (parseInt(selDateArr[1])+1): (parseInt(selDateArr[0]) +1) + "/" + (parseInt(selDateArr[1]));
	$('#idUserZoneGraphDate').data({date: dateMonthNYear}).datepicker('update');
	$("#idUserZoneGraphEndDate").val(dateMonthNYear);
	
	var today = new Date();
	
	if ( dateMonthNYear == ((today.getMonth() + 1) + "/" + today.getFullYear()) )
	{
		$("#idUserZoneGraphNextMon").attr('disabled', true);
	}
	
	SrvUser_PopulateMonthCharts(localStorage["ZONEID"]);
});
		
}

function loadSpinnerUser() {
    var activeTab = $("ul#widgetnav").children(".active").attr("id");
    if (activeTab == "li_zonedashboard") {
        $("#spnZoneUserRefresh").removeClass("hide");
		setTimeout( "SrvLoadUserZoneDashboard(page_num);",1000 );
    }
    else if(activeTab == "li_assetdashboard"){
        $("#spnAssetUserRefresh").removeClass("hide");
		if(localStorage["ZONENAME"] != "All Zones")
							{				
							moreDet_Zone(localStorage["ZONEID"],localStorage["ZONENAME"]);
							}else{				
							setTimeout( "SrvUserLoadAssetDashboard();",1000 );
							}
				}
}
function moreDet_Zone(zone_id,zone_name)
{
	localStorage["ZONENAME"] = zone_name;
	localStorage["ZONEID"] = zone_id;
	
    var url = BACKENDURL + "user/asset_dash_search";
    var data = {
        session_id: localStorage["SESSIONID"],
        zone_id: zone_id
    };
    MakeAjaxCall(url, data, loadUserAssetDashboard);
	if (localStorage["userautorefresh"] == "true")
    {
        localStorage["userautorefresh"] = "false";
        localStorage["PrevStatRefreshUser"] = "1";
    }
}
/*************User Zone Dashboard***************/
function SrvLoadUserZoneDashboard(page_num) {
 var url = BACKENDURL + "user/get_zone_dashboard";
    var data = {        
		session_id : localStorage["SESSIONID"],
		page_no : page_num
		};
    MakeAjaxCall(url, data, loadUserZoneDashboard);
}
function loadUserZoneDashboard(data){
if (data.session_status) {
         if (data.error == 0) { 
			$("#spnZoneUserRefresh").addClass("hide");		 
            if (data.zond_dash.zlist.length == 0) {
                $("#divUserZonefirst").hide(); 				
				$("#divUserZonedashErrMsg").removeClass("hide"); 
				$("#lblUserZonechkbx").hide();
				$("#btnUserZoneRefresh").hide();								
            }
            else
            {
			$("#btnUserZoneRefresh").show();
			$("#lblUserZonechkbx").show();
			$("#divUserNewZoneButton").show();
			$('#divUserZonefirst').empty();
			var zoneDashStr="";
			for (var nCount = 0; nCount < data.zond_dash.zlist.length; nCount++) {			
			var status="";
			 var disabled = (data.zond_dash.zlist[nCount].lc == "0") ? "disabled" : "";			
				if(data.zond_dash.zlist[nCount].zst=="g")
				{
				 zoneDashStr += "<div class='panel panel-success span3'><div class='panel-heading'><div class='btn-group pull-right'><button class='btn btn-mini btn-success' id='btn_info"+data.zond_dash.zlist[nCount].zid+"' rel='popover' title='"+data.zond_dash.zlist[nCount].zn+"' data-content='"+ data.zond_dash.zlist[nCount].d.replace(/\n/g, "<br />") +"'><i class='icon-info-sign icon-white'></i></button><button class='btn btn-mini btn-success' id='btn_graph"+data.zond_dash.zlist[nCount].zid+"'  data-original-title='Graph' onclick='javascript:btnclick(" + data.zond_dash.zlist[nCount].zid + ")'><i class='icon-signal icon-white'></i></button></div><h3 class='panel-title'>"+data.zond_dash.zlist[nCount].zn+"</h3></div><div class='aligncenter'><p class='lead text-success'>"+data.zond_dash.zlist[nCount].lc+" Assets</p><p class='text-success' style='margin-bottom: 20px;'>Stock Levels Healthy</p><p><button class='btn btn-large btn-success' onclick='javascript:moreDet_Zone("+ data.zond_dash.zlist[nCount].zid +",\"" +data.zond_dash.zlist[nCount].zn +"\");' "+disabled+">More Details</button></p></div></div>";			
				 }else if(data.zond_dash.zlist[nCount].zst=="o")
				{
				 zoneDashStr += "<div class='panel panel-warning span3'><div class='panel-heading'><div class='btn-group pull-right'><button class='btn btn-mini btn-warning' id='btn_info"+data.zond_dash.zlist[nCount].zid+"' rel='popover' title='"+data.zond_dash.zlist[nCount].zn+"' data-content='"+ data.zond_dash.zlist[nCount].d.replace(/\n/g, "<br />") +"'><i class='icon-info-sign icon-white'></i></button><button class='btn btn-mini btn-warning' id='btn_graph"+data.zond_dash.zlist[nCount].zid+"'  data-original-title='Graph' onclick='javascript:btnclick(" + data.zond_dash.zlist[nCount].zid + ")'><i class='icon-signal icon-white'></i></button></div><h3 class='panel-title'>"+data.zond_dash.zlist[nCount].zn+"</h3></div><div class='aligncenter'><p class='lead text-warning'>"+data.zond_dash.zlist[nCount].lc+" Assets</p><p class='text-warning' style='margin-bottom: 20px;'>Stock Levels High (Max "+data.zond_dash.zlist[nCount].ht+")</p><p><button class='btn btn-large btn-warning' onclick='javascript:moreDet_Zone("+ data.zond_dash.zlist[nCount].zid +",\"" +data.zond_dash.zlist[nCount].zn +"\");' "+disabled+">More Details</button></p></div></div>";
				}else if(data.zond_dash.zlist[nCount].zst=="r")
				{
				 zoneDashStr += "<div class='panel panel-danger span3'><div class='panel-heading'><div class='btn-group pull-right'><button class='btn btn-mini btn-danger' id='btn_info"+data.zond_dash.zlist[nCount].zid+"' rel='popover' title='"+data.zond_dash.zlist[nCount].zn+"' data-content='"+ data.zond_dash.zlist[nCount].d.replace(/\n/g, "<br />") +"'><i class='icon-info-sign icon-white'></i></button><button class='btn btn-mini btn-danger' id='btn_graph"+data.zond_dash.zlist[nCount].zid+"'  data-original-title='Graph' onclick='javascript:btnclick(" + data.zond_dash.zlist[nCount].zid + ")'><i class='icon-signal icon-white'></i></button></div><h3 class='panel-title'>"+data.zond_dash.zlist[nCount].zn+"</h3></div><div class='aligncenter'><p class='lead text-error'>"+data.zond_dash.zlist[nCount].lc+" Assets</p><p class='text-error' style='margin-bottom: 20px;'>Stock Levels Low (Min "+data.zond_dash.zlist[nCount].lt+")</p><p><button class='btn btn-large btn-danger' onclick='javascript:moreDet_Zone("+ data.zond_dash.zlist[nCount].zid +",\"" +data.zond_dash.zlist[nCount].zn +"\");' "+disabled+">More Details</button></p></div></div>";
				}else if(data.zond_dash.zlist[nCount].zst=="b")
				{
				 zoneDashStr += "<div class='panel panel-info span3'><div class='panel-heading'><div class='btn-group pull-right'><button class='btn btn-mini btn-info' id='btn_info"+data.zond_dash.zlist[nCount].zid+"' rel='popover' title='"+ data.zond_dash.zlist[nCount].zn +"' data-content='"+ data.zond_dash.zlist[nCount].d.replace(/\n/g, "<br />") +"'><i class='icon-info-sign icon-white'></i></button><button class='btn btn-mini btn-info' id='btn_graph"+data.zond_dash.zlist[nCount].zid+"'  data-original-title='Graph' onclick='javascript:btnclick(" + data.zond_dash.zlist[nCount].zid + ")'><i class='icon-signal icon-white'></i></button></div><h3 class='panel-title'>"+data.zond_dash.zlist[nCount].zn+"</h3></div><div class='aligncenter'><p class='lead text-info'>"+data.zond_dash.zlist[nCount].lc+" Assets</p><p class='text-info' style='margin-bottom: 20px;'>Reader Not Responding</p><p><button class='btn btn-large btn-info' onclick='javascript:moreDet_Zone("+ data.zond_dash.zlist[nCount].zid +",\"" +data.zond_dash.zlist[nCount].zn +"\");' "+disabled+">More Details</button></p></div></div>";
				}
                //contractStr += "<div class='panel-"+status+" span3'><div class='panel-heading'><div class='btn-group pull-right'><button class="btn btn-mini btn-warning" id="hoverevent1" data-toggle="tooltip" data-placement="bottom" data-original-title="This is a longer description of the zone to help describe and locate where it is in on site."><i class="icon-info-sign icon-white"></i></button><button class="btn btn-mini btn-warning" id="hoverevent2" data-toggle="tooltip" data-placement="bottom" data-original-title="Graph"><i class="icon-signal icon-white"></i></button><button class="btn btn-mini btn-warning" id="hoverevent3" data-toggle="tooltip" data-placement="bottom" data-original-title="Settings"><i class="icon-cog icon-white"></i></button><button class="btn btn-mini btn-warning" id="hoverevent4" data-toggle="tooltip" data-placement="bottom" data-original-title="Delete"><i class="icon-trash icon-white"></i></button></div><h3 class="panel-title">O-Ramp</h3></div><div class="aligncenter"><p class="lead text-warning">30 Assets</p><p class="text-warning" style="margin-bottom: 20px;">Stock Levels High (Max 15)</p><p><button class="btn btn-large btn-warning">More Details</button></p></div></div>";
                //selectedStr = "";
            }
			$('#divUserZonefirst').append(zoneDashStr);
			if (localStorage["PrevStatRefreshUser"] == "1")
			{
				localStorage["userautorefresh"] = "true";
			}
				if (data.zond_dash.tc > 12) {
						var zoneDashzlist = data.zond_dash.tc;
						var total_page = Math.ceil(zoneDashzlist / 12);
						var no_pages = total_page > 1 ? 3 : 1;
						if (zoneDashzlist > 12) {
							var options = {							
							alignment: "right",
							totalPages: total_page,
							numberOfPages: no_pages, pageUrl: "javascript:void(0)",
							itemTexts: function(type, page, current) {
														
							/* alert("pagefirst"+page)
							alert("current"+current)  */
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
							page_num = page;
							SrvLoadUserZoneDashboard(page);
                        }
                    }
                    $("#DivZoneUserFirst_pag").bootstrapPaginator(options);
                    $("#DivZoneUserFirst_pag").show();
                }
                else
                {
                    $("#DivZoneUserFirst_pag").hide();
                }
              } 
					$("button[id^=btn_graph]").tooltip({
                                 'selector': '',
                                 'placement': 'right',
                                 'width': '400px'
                              });
					$("button[id^=btn_info]").popover({'placement': 'bottom'});							  
            }                    
		
        } 
    }
}

// User Zone Dash Chart 
function btnclick(Zoneid)
{
$("#btnUserZoneRefresh").hide();
 $("#DivZoneUserFirst_pag").hide();
	localStorage["ZONEID"] 	= Zoneid;

	$("#divUserNewZoneButton").hide();
    $("#divUserZonefirst").hide();
    $("#divUserZoneGraph").show();
	$("#idUserZoneGraphNextMon").attr('disabled', true);
  	var today = new Date();
	var m = (today.getMonth() + 1) + "/" + today.getFullYear();
	$('#idUserZoneGraphDate').datepicker({endDate: m, minViewMode:1,placement:'left',}).on(
		'changeDate', function(){$('#idUserZoneGraphDate').datepicker('hide');
			m == $("#idUserZoneGraphEndDate").val() ? $("#idUserZoneGraphNextMon").attr('disabled', true) : $("#idUserZoneGraphNextMon").attr('disabled', false);
		}
	);
	$('#idUserZoneGraphDate').data({date: m}).datepicker('update');	

	$("#idUserZoneGraphEndDate").val(m);
	
	
    SrvUser_PopulateMonthCharts(Zoneid);
}
function SrvUser_PopulateMonthCharts(Zoneid)
{
    //var url = BACKENDURL + "customeradmin/get_zone_chart_details";
	var url = BACKENDURL + "user/get_zone_chart_details";
	var today = new Date();
	
    var data = {
        session_id: localStorage["SESSIONID"],
        //contract_id: localStorage["contractid"],
        zone_id: Zoneid,
		end_date: (today.getDate()) + "/" + $("#idUserZoneGraphEndDate").val()
    };
    MakeAjaxCall(url, data, PopulateMonthCharts);
}

function PopulateMonthCharts(data) {
    if (data.session_status) {
        if (data.error == 0) {
        	var len = data.zone_details.zone_data.length;
        	var ranges=[],mins=[],maxs=[],averages=[];
        	
        	for(i=0;i<len;i++)
        	{
        		var da = data.zone_details.zone_data[i]['d'].split("-");
        		var y = da[0];
        		//var m = parseInt(da[1])-1;
				var m = parseInt(da[1], 10)-1;
        		var d = da[2];
        		ranges[i] = [Date.UTC(y,m,d), parseInt(data.zone_details.zone_data[i]['lt']),parseInt(data.zone_details.zone_data[i]['ht'])];
        		averages[i] = [Date.UTC(y,m,d), parseFloat(data.zone_details.zone_data[i]['avg'])];
        		mins[i] = [Date.UTC(y,m,d), parseInt(data.zone_details.zone_data[i]['min'])];
        		maxs[i] = [Date.UTC(y,m,d), parseInt(data.zone_details.zone_data[i]['max'])];
        	}
        	
            new Highcharts.Chart({
                chart: {
                    renderTo: 'divUserZoneMonthChart'
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

function closeUserZoneGraph(){
	$("#divUserZonefirst").show(); 
	$("#divUserNewZoneButton").show();			 
	$("#divUserZoneGraph").hide();
	SrvLoadUserZoneDashboard(page_num);
	$("#DivZoneFirst_pag").show();
$("#btnUserZoneRefresh").show();	
}

/************************User Asset Dashboard**************************/
function SrvUserLoadAssetDashboard() {
	localStorage["ZONENAME"] = "All Zones";
    var url = BACKENDURL + "user/asset_dash_search";
    var data = {
        session_id: localStorage["SESSIONID"],
        zone_id: null
    };
    MakeAjaxCall(url, data, loadUserAssetDashboard);

    if (localStorage["PrevStatRefreshAsset"] == "1")
    {
        localStorage["autorefreshasset"] = "true";
    }
}
function loadUserAssetDashboard(data) {
    if (data.session_status) {
        if (data.error == 0) {
            $("#spnAssetUserRefresh").addClass("hide");
            if (data.asset_details.length == 0) {
                $("#divUserZoneDashboard").hide();
                $("#divUserAssetDashboard").show();
                $('#widgetnav a[href="#divUserAssetDashboard"]').tab('show');
                $("#divUserAssetDetails").hide();
                $("#divAssetCheckbox").hide();
                $("#divUserAssetdashErrMsg").removeClass("hide");
                $("#lblAutoRefresh").hide();
                $("#btnZoneRefresh").hide();
				$("#divUserAssetRefreshAddControls").hide();
            }
            else
            {
				$("#divUserAssetdashErrMsg").addClass("hide");
				var nCurrRecRound = 0;
                $("#btnZoneRefresh").show();
                $("#divAssetCheckbox").show();
                $("#lblAutoRefresh").show();
				$("#divUserAssetRefreshAddControls").show();
                var assetDashStr = "";
				$("#tablePagination").hide();
                $("#tblUserAssetDashBrd tbody").children().remove();
				
				var hdnCurrPage = $("#currPageNumber", "#tablePagination").val();
				if (hdnCurrPage != undefined) {
					nCurrRecRound = hdnCurrPage - 1;
				}
				
                for (var nCount = 0; nCount < data.asset_details.length; nCount++) {
				var zl = (data.asset_details[nCount].zl == null) ? 'N/A' : data.asset_details[nCount].zl;
				var lr = (data.asset_details[nCount].lr == null) ? 'N/A' : data.asset_details[nCount].lr;
				var zp = (data.asset_details[nCount].zp == null) ? 'N/A' : data.asset_details[nCount].zp;
				var pr = (data.asset_details[nCount].pr == null) ? 'N/A' : data.asset_details[nCount].pr;

				if (data.asset_details[nCount].ast == "g"){
                        assetDashStr += "<tr><td style='width:540px;font-size:14px;color:black;margin-bottom:0px;'><strong>" + data.asset_details[nCount].atyp + ": </strong>" + data.asset_details[nCount].anum + "." + data.asset_details[nCount].ades + "<strong><br>Latest Read: </strong>" + zl + ": " + lr + "<strong><br>Previous Read: </strong>" + zp + ": " + pr + "</td><td style='text-align:right;width: 10%;'>"
						if (data.asset_details[nCount].bs == "L") //td alone
                            assetDashStr += "<span class='label label-important' id='rpSt' style='margin-right:10px;'>Low Battery Alert (" + data.asset_details[nCount].lbd + " Days)</span>"
                        assetDashStr += "<span class='label label-success' id='rpSt'>Asset OK</span></td></tr>";
                    }                        
					else if (data.asset_details[nCount].ast == "o") {
                        assetDashStr += "<tr><td style='width:540px;font-size:14px;color:black;margin-bottom:0px;'><strong>" + data.asset_details[nCount].atyp + ": </strong>" + data.asset_details[nCount].anum + "." + data.asset_details[nCount].ades + "<strong><br>Latest Read: </strong>" + zl + ": " + lr + "<strong><br>Previous Read: </strong>" + zp + ": " + pr + "</td><td style='text-align:right;width: 10%;'>"
                        if (data.asset_details[nCount].bs == "L") //td alone
                            assetDashStr += "<span class='label label-important' id='rpSt' style='margin-right:10px;'>Low Battery Alert (" + data.asset_details[nCount].lbd + " Days)</span>"
                        assetDashStr += "<span class='label label-warning' id='rpSt'>Asset Not Responding</span></td></tr>";
                    }
                    else if (data.asset_details[nCount].ast == "b") {
                        assetDashStr += "<tr><td style='width:440px;font-size:14px;color:black;margin-bottom:0px;'><strong>" + data.asset_details[nCount].atyp + ": </strong>" + data.asset_details[nCount].anum + "." + data.asset_details[nCount].ades + "<strong><br>Latest Read: </strong>" + zl + ": " + lr + "<strong><br>Previous Read: </strong>" + zp + ": " + pr + "</td><td style='text-align:right;width: 10%;'>"
                        if (data.asset_details[nCount].bs == "L") //td alone
                            assetDashStr += "<span class='label label-important' id='rpSt' style='margin-right:10px;'>Low Battery Alert (" + data.asset_details[nCount].lbd + " Days)</span>"
                        assetDashStr += "<span class='label label-info' id='rpSt'>Removed From Zone</span></td></tr>";
                    }
                    else
                        assetDashStr += "<tr><td style='width:440px;font-size:14px;color:black;margin-bottom:0px;'><strong>" + data.asset_details[nCount].atyp + ": </strong>" + data.asset_details[nCount].anum + "." + data.asset_details[nCount].ades + "<strong><br>Latest Read: </strong>" + zl + ": " + lr + "<strong><br>Previous Read: </strong>" + zp + ": " + pr + "</td><td style='text-align:right;width: 10%;'><span class='label label-important' id='rpSt'>Low Battery Alert (" + data.asset_details[nCount].lbd + " Days)</span></td></tr>"
						
						if (data.asset_details[nCount].aid == data.asset_details[0].asset_id) {						
                            nCurrRecRound = Math.floor(nCount / 10);
                        }						
						
                }
				

                $("#tblUserAssetDashBrd  tbody:last").append(assetDashStr);
				
				$("#usersearch").val('');
				if(localStorage["ZONENAME"] != "All Zones")
				{
				
				$("#divUserCloseAssetButton").show();
				}else{
				
				$("#divUserCloseAssetButton").hide();
				}
				$("#assetZoneLabel").html('Showing a total of <strong>' + $("#tblUserAssetDashBrd tr").length + ' assets</strong>. Search and filter assets by their properties across <strong>' + localStorage["ZONENAME"] + '</strong>.');				
				
                $("#divUserZoneDashboard").hide();
                $("#divUserAssetDashboard").show();
                $('#widgetnav a[href="#divUserAssetDashboard"]').tab('show');

                $("#divUserAssetDetails").show();
                // zoneDashStr = "";
                var nRows = 10;
                //pagination for more than 2 schools added
                if (data.asset_details.length > nRows) {
                    $("#tablePagination").html('');
                    $("#divUserAssetDashboardTable").tablePagination({
                        rowsPerPage: nRows,
						currPage: nCurrRecRound + 1
                    });
                }
            }
        } else {
            logout(1);
        }
    }
}
function tableSearchUser()
{
	var numOfAssetsFound = 0;
	
    $("#tblUserAssetDashBrd").find("tr").each(function(index) {
        var id = $(this).find("td").text();
        var str = id.toLowerCase().replace('editdelete',' ').trim();
 		var isSearchValFound = false;
		
		if (isSearchValFound = (str.indexOf($("#usersearch").val().toLowerCase()) !== -1))
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
	if($("#usersearch").val() == "")
		SrvUserLoadAssetDashboard();
}
function closeAssetMainDash() {
				$("#divUserZoneDashboard").show();
                $("#divUserAssetDashboard").hide();
                $('#widgetnav a[href="#divUserZoneDashboard"]').tab('show');
}
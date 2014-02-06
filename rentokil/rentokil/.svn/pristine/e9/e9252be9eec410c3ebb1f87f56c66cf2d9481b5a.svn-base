Date.prototype.addDays = function(days) {
	this.setDate(this.getDate() + days);
	return this;
};
Date.prototype.addMonths = function(months) {
	this.setDate(this.getMonth() + months);
	return this;
};
Date.prototype.addYear = function(Year) {
	this.setDate(this.getFullYear() + Year);
	return this;
};
function LoadPageData(){
	var today = new Date();
	var t = today.getDate() + "/" + (today.getMonth()) + "/" + today.getFullYear();
	var m = (today.getMonth()) + "/" + today.getFullYear();
	if ( $(window).width() > 950) { 
		$("#centerMonth1").addClass('pull-left');
		$("#centerMonth3").addClass('pull-right');
		$("#centerMonth4").addClass('pull-left');
		$("#centerMonth6").addClass('pull-right');
		$("#centerMonth7").addClass('pull-left');
		$("#centerMonth9").addClass('pull-right');
		$("#centerMonth10").addClass('pull-left');
		$("#centerMonth12").addClass('pull-right');
		$("#centerMonth13").addClass('pull-left');
		$("#centerMonth15").addClass('pull-right');
		$("#centerMonth16").addClass('pull-left');
		$("#centerMonth18").addClass('pull-right');
		$("#centerMonth19").addClass('pull-left');
		$("#centerMonth21").addClass('pull-right');
		$("#centerMonth22").addClass('pull-left');
		$("#centerMonth24").addClass('pull-right');
		$("#centerMonth25").addClass('pull-left');
		$("#centerMonth27").addClass('pull-right');
		$("#btndtSumMonthly").css('margin-left', '-6px');
		$("#btndtSumDaily").css('margin-left', '-6px');
		$("#btndtSumHH").css('margin-left', '-6px');
		$("#btndtEleMonthly").css('margin-left', '-6px');
		$("#btndtEleDaily").css('margin-left', '-6px');
		$("#btndtEleHH").css('margin-left', '-6px');
		$("#btndtGasMonthly").css('margin-left', '-6px');
		$("#btndtGasDaily").css('margin-left', '-6px');
		$("#btndtGasHH").css('margin-left', '-6px');
    } 
	//Check the session exists
	//Get the customer data from the database.
  	$("#spnContractName").html(localStorage["CUSTOMERNAME"]);
  	// Summary Monthly-Daily-Halfy Yearly
	// Click action on each functionality
  	//Link click, Date go button, 
  	$("#divSumMonthly").bind("click",function(){PopulateMonthCharts("Sum");});
  	$("#divSumDaily").bind("click",function(){PopulateDailyCharts("Sum");});
  	$("#divSumHH").bind("click",function(){PopulateHHCharts("Sum");});
	$("#btndtSumMonthly").bind("click",function(){PopulateMonthCharts("Sum");});  	
	$("#btndtSumDaily").bind("click",function(){PopulateDailyCharts("Sum");});  	
	$("#btndtSumHH").bind("click",function(){PopulateHHCharts("Sum");});
	// Date Picker
	var dtSumMonthly = $('#dtSumMonthly').datepicker({minViewMode:1,placement:'left',}).on('changeDate', function(){$('#dtSumMonthly').datepicker('hide');});
  	var dtSumDaily = $('#dtSumDaily').datepicker({placement:'left',}).on('changeDate', function(){$('#dtSumDaily').datepicker('hide');}); 
	var dtSumHH = $('#dtSumHH').datepicker({placement:'left',}).on('changeDate', function(){$('#dtSumHH').datepicker('hide');});
	// Assigning value to date Picker
	$('#dtSumMonthly').data({date: m}).datepicker('update');
	$('#dtSumDaily').data({date: t}).datepicker('update');
	$('#dtSumHH').data({date: t}).datepicker('update');
	$("#txtSumMonthEndDate").val(m);
	$("#txtSumDailyEndDate").val(t);
	$("#txtSumHHEndDate").val(t);
		
  	// Electricity Monthly-Daily-Halfy Yearly
	// Click action on each functionality
	$("#divEleMonthly").bind("click",function(){PopulateMonthCharts("Ele");});
	$("#divEleDaily").bind("click",function(){PopulateDailyCharts("Ele");});
  	$("#divEleHH").bind("click",function(){PopulateHHCharts("Ele");});
	$("#btndtEleMonthly").bind("click",function(){PopulateMonthCharts("Ele");});
	$("#btndtEleDaily").bind("click",function(){PopulateDailyCharts("Ele");});
  	$("#btndtEleHH").bind("click",function(){PopulateHHCharts("Ele");});
  	// Date Picker
 var dtEleMonthly = $('#dtEleMonthly').datepicker({minViewMode:1,placement:'left',}).on('changeDate', function(){$('#dtEleMonthly').datepicker('hide');});
 var dtSumDaily = $('#dtEleDaily').datepicker({placement:'left',}).on('changeDate', function(){$('#dtEleDaily').datepicker('hide');});
 var dtSumHH = $('#dtEleHH').datepicker({placement:'left',}).on('changeDate', function(){$('#dtEleHH').datepicker('hide');});
 // Assigning value to date Picker
 $('#dtEleMonthly').data({date: m}).datepicker('update');
 $('#dtEleDaily').data({date: t}).datepicker('update');
 $('#dtEleHH').data({date: t}).datepicker('update');
 $("#txtEleMonthEndDate").val(m);
 $("#txtEleDailyEndDate").val(t);
 $("#txtEleHHEndDate").val(t);
 
 // Gas Monthly-Daily-Halfy Yearly
 // Click action on each functionality
 $("#divGasMonthly").bind("click",function(){PopulateMonthCharts("Gas");});
 $("#divGasDaily").bind("click",function(){PopulateDailyCharts("Gas");});
 $("#divGasHH").bind("click",function(){PopulateHHCharts("Gas");});
 $("#btndtGasMonthly").bind("click",function(){PopulateMonthCharts("Gas");});
 $("#btndtGasDaily").bind("click",function(){PopulateDailyCharts("Gas");});
 $("#btndtGasHH").bind("click",function(){PopulateHHCharts("Gas");});
 
 // Date Picker
 var dtEleMonthly = $('#dtGasMonthly').datepicker({minViewMode:1,placement:'left',}).on('changeDate', function(){$('#dtGasMonthly').datepicker('hide');});
 var dtSumDaily = $('#dtGasDaily').datepicker({placement:'left',}).on('changeDate', function(){$('#dtGasDaily').datepicker('hide');});
 var dtSumHH = $('#dtGasHH').datepicker({placement:'left',}).on('changeDate', function(){$('#dtGasHH').datepicker('hide');});
 // Assigning value to date Picker
 $('#dtGasMonthly').data({date: m}).datepicker('update');
 $('#dtGasDaily').data({date: t}).datepicker('update');
 $('#dtGasHH').data({date: t}).datepicker('update');
 $("#txtGasMonthEndDate").val(m);
 $("#txtGasDailyEndDate").val(t);
 $("#txtGasHHEndDate").val(t);

 //next & Prev functionality for Summary reprts
$("#SumMonfastPrev").bind("click",function(){
	var selDateArr = $("#txtSumMonthEndDate").val().split("/");
	var updateMonth = (selDateArr[0])+"/" + (parseInt(selDateArr[1])-1);
	$('#dtSumMonthly').data({date: updateMonth}).datepicker('update');
	$("#txtSumMonthEndDate").val(updateMonth);
	PopulateMonthCharts("Sum");
});
$("#SumMonfastNext").bind("click",function(){
	var selDateArr = $("#txtSumMonthEndDate").val().split("/");
	var updateMonth = (selDateArr[0])+"/" + (parseInt(selDateArr[1])+1);
	$('#dtSumMonthly').data({date: updateMonth}).datepicker('update');
	$("#txtSumMonthEndDate").val(updateMonth);
	PopulateMonthCharts("Sum");
});
$("#SumMonPrev").bind("click",function(){
	var selDateArr = $("#txtSumMonthEndDate").val().split("/");
	var updateMonth = (selDateArr[0] == 1) ? (parseInt(selDateArr[0]) +11) + "/" + (parseInt(selDateArr[1])-1): (parseInt(selDateArr[0]) -1) + "/" + (parseInt(selDateArr[1]));
	$('#dtSumMonthly').data({date: updateMonth}).datepicker('update');
	$("#txtSumMonthEndDate").val(updateMonth);
	PopulateMonthCharts("Sum");
});
$("#SumMonNext").bind("click",function(){
	var selDateArr = $("#txtSumMonthEndDate").val().split("/");
	var updateMonth = (selDateArr[0] == 12) ? (parseInt(selDateArr[0]) -11) + "/" + (parseInt(selDateArr[1])+1): (parseInt(selDateArr[0]) +1) + "/" + (parseInt(selDateArr[1]));
	$('#dtSumMonthly').data({date: updateMonth}).datepicker('update');
	$("#txtSumMonthEndDate").val(updateMonth);
	PopulateMonthCharts("Sum");
});
$("#SumDayFastPrev").bind("click",function(){
	var selDateArr = $("#txtSumDailyEndDate").val().split("/");
	var updateMonth = (selDateArr[1] == 1) ? selDateArr[0]+"/"+(parseInt(selDateArr[1])+11)+"/" + (parseInt(selDateArr[2])-1) : selDateArr[0]+"/"+(parseInt(selDateArr[1])-1)+"/" + selDateArr[2];
	$('#dtSumDaily').data({date: updateMonth}).datepicker('update');
	$("#txtSumDailyEndDate").val(updateMonth);
	PopulateDailyCharts("Sum");
});
$("#SumDayFastNext").bind("click",function(){
	var selDateArr = $("#txtSumDailyEndDate").val().split("/");
	var updateMonth = (selDateArr[1] == 12) ? selDateArr[0]+"/"+(parseInt(selDateArr[1])- 11)+"/" + (parseInt(selDateArr[2])+1) : selDateArr[0]+"/"+(parseInt(selDateArr[1])+1) +"/" + selDateArr[2]; 
	$('#dtSumDaily').data({date: updateMonth}).datepicker('update');
	$("#txtSumDailyEndDate").val(updateMonth);
	PopulateDailyCharts("Sum");
});
$("#SumDayPrev").bind("click",function(){
	var selDateArr = $("#txtSumDailyEndDate").val().split("/");
	var prevDate = new Date(selDateArr[2],(selDateArr[1]-1),selDateArr[0],0,0,0,0).addDays(-7);
	var calDate = prevDate.getDate() + "/" + (prevDate.getMonth()+1) + "/" + prevDate.getFullYear();
	$('#dtSumDaily').data({date: calDate}).datepicker('update');
	$("#txtSumDailyEndDate").val(calDate);
	PopulateDailyCharts("Sum");
});
$("#SumDayNext").bind("click",function(){
	var selDateArr = $("#txtSumDailyEndDate").val().split("/");
	var prevDate = new Date(selDateArr[2],(selDateArr[1]-1),selDateArr[0],0,0,0,0).addDays(7);
	var calDate = prevDate.getDate() + "/" + (prevDate.getMonth()+1) + "/" + prevDate.getFullYear();
	$('#dtSumDaily').data({date: calDate}).datepicker('update');
	$("#txtSumDailyEndDate").val(calDate);
	PopulateDailyCharts("Sum");
});
$("#SumHHPrev2Weeks").bind("click",function(){
	var selDateArr = $("#txtSumHHEndDate").val().split("/");
	var prevDate = new Date(selDateArr[2],(selDateArr[1]-1),selDateArr[0],0,0,0,0).addDays(-14);
	var calDate = prevDate.getDate() + "/" + (prevDate.getMonth()+1) + "/" + prevDate.getFullYear();
	$('#dtSumHH').data({date: calDate}).datepicker('update');
	$("#txtSumHHEndDate").val(calDate);
	PopulateHHCharts("Sum");
});
$("#SumHHPrev").bind("click",function(){
	var selDateArr = $("#txtSumHHEndDate").val().split("/");
	var prevDate = new Date(selDateArr[2],(selDateArr[1]-1),selDateArr[0],0,0,0,0).addDays(-1);
	var calDate = prevDate.getDate() + "/" + (prevDate.getMonth()+1) + "/" + prevDate.getFullYear();
	$('#dtSumHH').data({date: calDate}).datepicker('update');
	$("#txtSumHHEndDate").val(calDate);
	PopulateHHCharts("Sum");
});
$("#SumHHNext2Weeks").bind("click",function(){
	var selDateArr = $("#txtSumHHEndDate").val().split("/");
	var prevDate = new Date(selDateArr[2],(selDateArr[1]-1),selDateArr[0],0,0,0,0).addDays(14);
	var calDate = prevDate.getDate() + "/" + (prevDate.getMonth()+1) + "/" + prevDate.getFullYear();
	$('#dtSumHH').data({date: calDate}).datepicker('update');
	$("#txtSumHHEndDate").val(calDate);
	PopulateHHCharts("Sum");
});
$("#SumHHNext").bind("click",function(){
	var selDateArr = $("#txtSumHHEndDate").val().split("/");
	var prevDate = new Date(selDateArr[2],(selDateArr[1]-1),selDateArr[0],0,0,0,0).addDays(1);
	var calDate = prevDate.getDate() + "/" + (prevDate.getMonth()+1) + "/" + prevDate.getFullYear();
	$('#dtSumHH').data({date: calDate}).datepicker('update');
	$("#txtSumHHEndDate").val(calDate);
	PopulateHHCharts("Sum");
});
//next & Prev functionality for Energy documents
$("#eleMonfastPrev").bind("click",function(){
	var selDateArr = $("#txtEleMonthEndDate").val().split("/");
	var updateMonth = (selDateArr[0])+"/" + (parseInt(selDateArr[1])-1);
	$('#dtEleMonthly').data({date: updateMonth}).datepicker('update');
	$("#txtEleMonthEndDate").val(updateMonth);
	PopulateMonthCharts("Ele");
});
$("#eleMonfastNext").bind("click",function(){
	var selDateArr = $("#txtEleMonthEndDate").val().split("/");
	var updateMonth = (selDateArr[0])+"/" + (parseInt(selDateArr[1])+1);
	$('#dtEleMonthly').data({date: updateMonth}).datepicker('update');
	$("#txtEleMonthEndDate").val(updateMonth);
	PopulateMonthCharts("Ele");
});
$("#eleMonPrev").bind("click",function(){
	var selDateArr = $("#txtEleMonthEndDate").val().split("/");
	var updateMonth = (selDateArr[0] == 1)? (parseInt(selDateArr[0])+11)+"/" + (parseInt(selDateArr[1])-1) : (parseInt(selDateArr[0])-1)+"/" + selDateArr[1];
	$('#dtEleMonthly').data({date: updateMonth}).datepicker('update');
	$("#txtEleMonthEndDate").val(updateMonth);
	PopulateMonthCharts("Ele");
});
$("#eleMonNext").bind("click",function(){
	var selDateArr = $("#txtEleMonthEndDate").val().split("/");
	var updateMonth = (selDateArr[0] == 12)? (parseInt(selDateArr[0])- 11)+"/" + (parseInt(selDateArr[1])+1) :  (parseInt(selDateArr[0])+1)+"/" + selDateArr[1];   
	$('#dtEleMonthly').data({date: updateMonth}).datepicker('update');
	$("#txtEleMonthEndDate").val(updateMonth);
	PopulateMonthCharts("Ele");
});
$("#eleDayFastPrev").bind("click",function(){
	var selDateArr = $("#txtEleDailyEndDate").val().split("/");
	var updateMonth = (selDateArr[1] == 1) ? selDateArr[0]+"/"+(parseInt(selDateArr[1])+11)+"/" + (parseInt(selDateArr[2])-1) : selDateArr[0]+"/"+(parseInt(selDateArr[1])-1)+"/" + selDateArr[2];  
	$('#dtEleDaily').data({date: updateMonth}).datepicker('update');
	$("#txtEleDailyEndDate").val(updateMonth);
	PopulateDailyCharts("Ele");
});
$("#eleDayFastNext").bind("click",function(){
	var selDateArr = $("#txtEleDailyEndDate").val().split("/");
	var updateMonth = (selDateArr[1] == 12) ? selDateArr[0]+"/"+(parseInt(selDateArr[1]) - 11)+"/" + (parseInt(selDateArr[2])+1) : selDateArr[0]+"/"+(parseInt(selDateArr[1])+1)+"/" + selDateArr[2]; 
	$('#dtEleDaily').data({date: updateMonth}).datepicker('update');
	$("#txtEleDailyEndDate").val(updateMonth);
	PopulateDailyCharts("Ele");
});
$("#eleDayPrev").bind("click",function(){
	var selDateArr = $("#txtEleDailyEndDate").val().split("/");
	var prevDate = new Date(selDateArr[2],(selDateArr[1]-1),selDateArr[0],0,0,0,0).addDays(-7);
	var calDate = prevDate.getDate() + "/" + (prevDate.getMonth()+1) + "/" + prevDate.getFullYear();
	$('#dtEleDaily').data({date: calDate}).datepicker('update');
	$("#txtEleDailyEndDate").val(calDate);
	PopulateDailyCharts("Ele");
});
$("#eleDayNext").bind("click",function(){
	var selDateArr = $("#txtEleDailyEndDate").val().split("/");
	var prevDate = new Date(selDateArr[2],(selDateArr[1]-1),selDateArr[0],0,0,0,0).addDays(7);
	var calDate = prevDate.getDate() + "/" + (prevDate.getMonth()+1) + "/" + prevDate.getFullYear();
	$('#dtEleDaily').data({date: calDate}).datepicker('update');
	$("#txtEleDailyEndDate").val(calDate);
	PopulateDailyCharts("Ele");
});
$("#eleHHPrev2Weeks").bind("click",function(){
	var selDateArr = $("#txtEleHHEndDate").val().split("/");
	var prevDate = new Date(selDateArr[2],(selDateArr[1]-1),selDateArr[0],0,0,0,0).addDays(-14);
	var calDate = prevDate.getDate() + "/" + (prevDate.getMonth()+1) + "/" + prevDate.getFullYear();
	$('#dtEleHH').data({date: calDate}).datepicker('update');
	$("#txtEleHHEndDate").val(calDate);
	PopulateHHCharts("Ele");
});
$("#eleHHPrev").bind("click",function(){
	var selDateArr = $("#txtEleHHEndDate").val().split("/");
	var prevDate = new Date(selDateArr[2],(selDateArr[1]-1),selDateArr[0],0,0,0,0).addDays(-1);
	var calDate = prevDate.getDate() + "/" + (prevDate.getMonth()+1) + "/" + prevDate.getFullYear();
	$('#dtEleHH').data({date: calDate}).datepicker('update');
	$("#txtEleHHEndDate").val(calDate);
	PopulateHHCharts("Ele");
});
$("#eleHHNext2Weeks").bind("click",function(){
	var selDateArr = $("#txtEleHHEndDate").val().split("/");
	var prevDate = new Date(selDateArr[2],(selDateArr[1]-1),selDateArr[0],0,0,0,0).addDays(14);
	var calDate = prevDate.getDate() + "/" + (prevDate.getMonth()+1) + "/" + prevDate.getFullYear();
	$("#txtEleHHEndDate").val(calDate);
	$('#dtEleHH').data({date: calDate}).datepicker('update');
	PopulateHHCharts("Ele");
});
$("#eleHHNext").bind("click",function(){
	var selDateArr = $("#txtEleHHEndDate").val().split("/");
	var prevDate = new Date(selDateArr[2],(selDateArr[1]-1),selDateArr[0],0,0,0,0).addDays(1);
	var calDate = prevDate.getDate() + "/" + (prevDate.getMonth()+1) + "/" + prevDate.getFullYear();
	$("#txtEleHHEndDate").val(calDate);
	$('#dtEleHH').data({date: calDate}).datepicker('update');
	PopulateHHCharts("Ele");
});
//next & Prev functionality for Gas documents
$("#GasMonfastPrev").bind("click",function(){
	var selDateArr = $("#txtGasMonthEndDate").val().split("/");
	var updateMonth = (selDateArr[0])+"/" + (parseInt(selDateArr[1])-1);
	$("#txtGasMonthEndDate").val(updateMonth);
	$('#dtGasMonthly').data({date: updateMonth}).datepicker('update');
	PopulateMonthCharts("Gas");
});
$("#GasMonfastNext").bind("click",function(){
	var selDateArr = $("#txtGasMonthEndDate").val().split("/");
	var updateMonth = (selDateArr[0])+"/" + (parseInt(selDateArr[1])+1);
	$("#txtGasMonthEndDate").val(updateMonth);
	$('#dtGasMonthly').data({date: updateMonth}).datepicker('update');
	PopulateMonthCharts("Gas");
});
$("#GasMonPrev").bind("click",function(){
	var selDateArr = $("#txtGasMonthEndDate").val().split("/");
	var updateMonth = (selDateArr[0] == 1)? (parseInt(selDateArr[0]) +11)+"/" + (parseInt(selDateArr[1])-1) : (parseInt(selDateArr[0])-1)+"/" + selDateArr[1]; 
	$("#txtGasMonthEndDate").val(updateMonth);
	$('#dtGasMonthly').data({date: updateMonth}).datepicker('update');
	PopulateMonthCharts("Gas");
});
$("#GasMonNext").bind("click",function(){
	var selDateArr = $("#txtGasMonthEndDate").val().split("/");
	var updateMonth = (selDateArr[0] == 12) ? (parseInt(selDateArr[0]) -11)+"/" + (parseInt(selDateArr[1])+1) : (parseInt(selDateArr[0])+1)+"/" + selDateArr[1]  ; 
	$("#txtGasMonthEndDate").val(updateMonth);
	$('#dtGasMonthly').data({date: updateMonth}).datepicker('update');
	PopulateMonthCharts("Gas");
});
$("#GasDayFastPrev").bind("click",function(){
	var selDateArr = $("#txtGasDailyEndDate").val().split("/");
	var updateMonth = (selDateArr[1] == 1) ? selDateArr[0]+"/"+(parseInt(selDateArr[1]) +11)+"/" + (parseInt(selDateArr[2])-1) :  selDateArr[0]+"/"+(parseInt(selDateArr[1])-1)+"/" + selDateArr[2]; 
	$("#txtGasDailyEndDate").val(updateMonth);
	$('#dtGasDaily').data({date: updateMonth}).datepicker('update');
	PopulateDailyCharts("Gas");
});
$("#GasDayFastNext").bind("click",function(){
	var selDateArr = $("#txtGasDailyEndDate").val().split("/");
	var updateMonth = (selDateArr[1] == 12)? selDateArr[0]+"/"+(parseInt(selDateArr[1]) -11)+"/" + (parseInt(selDateArr[2])+1) : selDateArr[0]+"/"+(parseInt(selDateArr[1])+1)+"/" + selDateArr[2]; 
	$("#txtGasDailyEndDate").val(updateMonth);
	$('#dtGasDaily').data({date: updateMonth}).datepicker('update');
	PopulateDailyCharts("Gas");
});
$("#GasDayPrev").bind("click",function(){
	var selDateArr = $("#txtGasDailyEndDate").val().split("/");
	var prevDate = new Date(selDateArr[2],(selDateArr[1]-1),selDateArr[0],0,0,0,0).addDays(-7);
	var calDate = prevDate.getDate() + "/" + (prevDate.getMonth()+1) + "/" + prevDate.getFullYear();
	$("#txtGasDailyEndDate").val(calDate);
	$('#dtGasDaily').data({date: calDate}).datepicker('update');
	PopulateDailyCharts("Gas");
});
$("#GasDayNext").bind("click",function(){
	var selDateArr = $("#txtGasDailyEndDate").val().split("/");
	var prevDate = new Date(selDateArr[2],(selDateArr[1]-1),selDateArr[0],0,0,0,0).addDays(7);
	var calDate = prevDate.getDate() + "/" + (prevDate.getMonth()+1) + "/" + prevDate.getFullYear();
	$("#txtGasDailyEndDate").val(calDate);
	$('#dtGasDaily').data({date: calDate}).datepicker('update');
	PopulateDailyCharts("Gas");
});
$("#GasHHPrev2Weeks").bind("click",function(){
	var selDateArr = $("#txtGasHHEndDate").val().split("/");
	var prevDate = new Date(selDateArr[2],(selDateArr[1]-1),selDateArr[0],0,0,0,0).addDays(-14);
	var calDate = prevDate.getDate() + "/" + (prevDate.getMonth()+1) + "/" + prevDate.getFullYear();
	$("#txtGasHHEndDate").val(calDate);
	$('#dtGasHH').data({date: calDate}).datepicker('update');
	PopulateHHCharts("Gas");
});
$("#GasHHPrev").bind("click",function(){
	var selDateArr = $("#txtGasHHEndDate").val().split("/");
	var prevDate = new Date(selDateArr[2],(selDateArr[1]-1),selDateArr[0],0,0,0,0).addDays(-1);
	var calDate = prevDate.getDate() + "/" + (prevDate.getMonth()+1) + "/" + prevDate.getFullYear();
	$("#txtGasHHEndDate").val(calDate);
	$('#dtGasHH').data({date: calDate}).datepicker('update');
	PopulateHHCharts("Gas");
});
$("#GasHHNext2Weeks").bind("click",function(){
	var selDateArr = $("#txtGasHHEndDate").val().split("/");
	var prevDate = new Date(selDateArr[2],(selDateArr[1]-1),selDateArr[0],0,0,0,0).addDays(14);
	var calDate = prevDate.getDate() + "/" + (prevDate.getMonth()+1) + "/" + prevDate.getFullYear();
	$("#txtGasHHEndDate").val(calDate);
	$('#dtGasHH').data({date: calDate}).datepicker('update');
	PopulateHHCharts("Gas");
});
$("#GasHHNext").bind("click",function(){
	var selDateArr = $("#txtGasHHEndDate").val().split("/");
	var prevDate = new Date(selDateArr[2],(selDateArr[1]-1),selDateArr[0],0,0,0,0).addDays(1);
	var calDate = prevDate.getDate() + "/" + (prevDate.getMonth()+1) + "/" + prevDate.getFullYear();
	$("#txtGasHHEndDate").val(calDate);
	$('#dtGasHH').data({date: calDate}).datepicker('update');
	PopulateHHCharts("Gas");
});
$("#energyDocumentTab").bind('click',loadMyDocuments);
$("#chkHideComplete", "#documents").bind("click", loadMyDocuments);
}

function PopulateMonthCharts(utilityType){
	var selDateArr = $("#txt" + utilityType + "MonthEndDate").val().split("/");
	$.support.cors = true;
	$.ajax({
		url: BACKENDURL +"user/get_monthly_chart",
		type: "post",
		data:  {			
		  session_id: localStorage["SESSIONID"],
		  sel_date: "1/" + selDateArr[0]+"/" + selDateArr[1],
		  contract_id: localStorage["contractid"],
		  utility_type: utilityType,
		},
		dataType: "json",
		crossDomain: true,
		success: function(data){
			 if(data.session_status) {
				if(data.error == 0){
				var actualChartType =  (utilityType =="Sum") ? "line" : "column";
					new Highcharts.Chart({
						chart: {
							renderTo: 'div' + utilityType+ 'MonthChart',
							type: 'line',
							margin: [ 50, 15, 50, 80]
						},
						credits: {
							enabled: false
						},
						title: {
							text: 'Annual Energy Performance',
							x: -20 //center
						},
						subtitle: {
							text: 'Energy Usage Trend. ' + data.chart_data.month[0] + " - " + data.chart_data.month[data.chart_data.month.length-1],
							x: -20
						},
						xAxis: {
							categories: data.chart_data.month
						},
						yAxis: {
							title: {
								text: 'Energy (kWh)'
							},
							min:0,
							plotLines: [{
								value: 0,
								width: 1,
								color: '#808080'
							}]
						},
						tooltip: {
							formatter: function () {
								return '<b>' + this.series.name + '</b><br/>' + this.x + ': ' + this.y + 'kWh';
							}
						},
						legend: {
							y: 10,
							borderWidth: 0
						},
						series: [{
							type: actualChartType,
							name: 'Actual',
							data: JSON.parse("[" + data.chart_data.actual + "]")
						}, {
							name: 'Baseline',
							data: JSON.parse("[" + data.chart_data.baseline + "]")
						}, {
							name: 'EPC Contract',
							data: JSON.parse("[" + data.chart_data.epc + "]")
						}]
					});
					//alert(data.chart_data.es);
					//alert(data.chart_data.cs);
				var cst = 0; var est = 0;
				var cs = data.chart_data.cs[0].split(",");
				var es = data.chart_data.es[0].split(",");
				for (i=0;i<cs.length;i++)
				{
					cst = +cst + +cs[i];
				}
				for (j=0;j<es.length;j++)
				{
					est = +est + +es[j];
				}
				$("#lbl" +utilityType+ "MonGBP").html(addCommas(parseInt(cst)));
				$("#lbl" +utilityType+ "MonkWh").html(addCommas(parseInt(est)));
				if(data.chart_data.missing != null &&  data.chart_data.missing !="")
					$("#div" +utilityType +"MonthChartMissing").html("<div class='alert' style='text-align:justify;' ><button type='button' class='close' data-dismiss='alert'>&times;</button><h4>Warning!</h4>Some data is not available for " +data.chart_data.missing +".</div>").show();
				else 
					 $("#div" + utilityType+ "MonthChartMissing").html("").hide();
				}
				} else {
					logout();
				}
		},
		error:function(xhr, textStatus, error){
			alert("UBS_" + error);
			}   
		}
	); 
}
	

function PopulateDailyCharts(utilityType){
	$.support.cors = true;
	$.ajax({
        url: BACKENDURL +"user/get_daily_chart",
        type: "post",
        data:  {			
          session_id: localStorage["SESSIONID"],
          sel_date: $("#txt" + utilityType + "DailyEndDate").val(),
          contract_id: localStorage["contractid"],
          utility_type: utilityType,
        },
        dataType: "json",
        crossDomain: true,
        success: function(data){
        	//chart_data=>(actual,baseline,epc,month)
        	 if(data.session_status) {
                if(data.error == 0){
        	   		
					chart = new Highcharts.Chart({
		            chart: {
		                renderTo: 'div' + utilityType + 'DailyChart',
		                type: 'line',
		                margin: [ 50, 15, 100, 80]
		            },
		            credits: {
		                enabled: false
		            },
		            title: {
		                text: 'Monthly Energy Performance',
		                x: -20 //center
		            },
		            subtitle: {
		                text: 'Energy Usage Trend. ' + data.chart_data.day[0] + " - " + data.chart_data.day[data.chart_data.day.length-1],
		                x: -20
		            },
		            xAxis: {
		                categories:  data.chart_data.day,
						
		                labels: {
		                    rotation: -45,
		                    align: 'right',
		                    style: {
		                        fontSize: '13px',
		                        fontFamily: 'Verdana, sans-serif'
		                    }
		                }
		            },
		            yAxis: {
		                title: {
		                    text: 'Energy (kWh)'
		                },
		                min:0,
		                plotLines: [{
		                    value: 0,
		                    width: 1,
		                    color: '#808080'
		                }]
		            },
		            tooltip: {
		                formatter: function () {
		                    return '<b>' + this.series.name + '</b><br/>' + this.x + ': ' + this.y + 'kWh';
		                }
		            },
		            legend: {
		            	 y: 10,
		            	 borderWidth: 0
		            },
		            series: [{
		                name: 'Actual',
		                data: JSON.parse("[" + data.chart_data.actual + "]")
		            }, {
		                name: 'Baseline',
		                data: JSON.parse("[" + data.chart_data.baseline + "]")
		            }, {
		                name: 'EPC Contract',
		                data: JSON.parse("[" + data.chart_data.epc + "]")
		            }]
		        });
		        var cst = 0; var est = 0;
				var cs = data.chart_data.cs[0].split(",");
				var es = data.chart_data.es[0].split(",");
				for (i=0;i<cs.length;i++)
				{
					cst = +cst + +cs[i];
				}
				for (j=0;j<es.length;j++)
				{
					est = +est + +es[j];
				}
				$("#lbl" +utilityType+ "DailyGBP").html(addCommas(parseInt(cst)));
				$("#lbl" +utilityType+ "DailykWh").html(addCommas(parseInt(est)));
                }
                } else {
                	logout();
                }
        },
        error:function(xhr, textStatus, error){
      	  	alert("UBS2_" + error);
        	}   
        }); 
	}


function PopulateHHCharts(utilityType){
	$.support.cors = true;
	$.ajax({
            url: BACKENDURL +"user/get_halfhourly_chart",
            type: "post",
            data:  {			
              session_id: localStorage["SESSIONID"],
              sel_date: $("#txt" + utilityType + "HHEndDate").val(),
              contract_id: localStorage["contractid"],
              utility_type: utilityType,
            },
            dataType: "json",
            crossDomain: true,
            success: function(data){
            	 if(data.session_status) {
	                if(data.error == 0){
	                	var start_date_arr = data.chart_data.start_date.split("/");
	            		//alert(start_date_arr[0] + " " + start_date_arr[1] + " " + start_date_arr[2]);
	            		chart = new Highcharts.Chart({
	                        chart: {
	                            renderTo: 'div' + utilityType +'HHChart',
	                            zoomType: 'x',
	                            spacingRight: 20
	                        },
	                        credits: {
	                            enabled: false
	                        },
	                        title: {
	                            text: 'Half Hourly Energy Performance (Actual)'
	                        },
	                        subtitle: {
	                            text: document.ontouchstart === undefined ?
	                                'Click and drag in the plot area to zoom in' :
	                                'Drag your finger over the plot to zoom in'
	                        },
	                        xAxis: {
	                            type: 'datetime',
	                            maxZoom: 1 * 14 * 3600000,
	                            title: {
	                                text: null
	                            }
	                        },
	                        yAxis: {
	                            title: {
	                                text: 'Energy (kWh)'
	                            },
	                            min:0,
	                            showFirstLabel: false
	                        },
	                        tooltip: {
	                            shared: true
	                        },
	                        legend: {
	                            enabled: false
	                        },
	                        plotOptions: {
	                            area: {
	                                fillColor: {
	                                    linearGradient: { x1: 0, y1: 0, x2: 0, y2: 1},
	                                    stops: [
	                                        [0, Highcharts.getOptions().colors[0]],
	                                        [1, 'rgba(2,0,0,0)']
	                                    ]
	                                },
	                                lineWidth: 1,
	                                marker: {
	                                    enabled: false,
	                                    states: {
	                                        hover: {
	                                            enabled: true,
	                                            radius: 5
	                                        }
	                                    }
	                                },
	                                shadow: false,
	                                states: {
	                                    hover: {
	                                        lineWidth: 1
	                                    }
	                                },
	                                threshold: null
	                            }
	                        },
	                
	                        series: [{
	                            type: 'area',
	                            name: 'kWh',
	                            pointInterval: 1 * 3600 * 500,
	                            pointStart: Date.UTC(start_date_arr[0], (start_date_arr[1]-1), start_date_arr[2],0,30,0),//Date.UTC(2013, 2, 12),
	                            data: JSON.parse("[" + data.chart_data.actual + "]")
	                        }]
	                    });
	                 } 
	                } else {
	                	logout();
	                }
            }
            ,
            error:function(xhr, textStatus, error){
          	  	alert("UBS3_" + error);
            	}   
            }); 

	}
/************************* School Documents *******************/	
var Document_def_status = 0;
var energy_rep_comm_stats = "";
var isNewUpload=false;
function loadMyDocuments() {
$.support.cors = true;
	var school_id = $("#selectAllSchools").val();
	//First get all school documents and populate it
	$.ajax({
        url: BACKENDURL + "user/get_energy_documents",
        type: "post",
        data: {
            session_id: localStorage["SESSIONID"],
            contract_id: localStorage["contractid"],
			hide_comp: $("#chkHideComplete").is(':checked'),
        },
        dataType: "json",
        crossDomain: true,
        success: function (data) {
        	if (data.session_status) {
        		if(data.error == 0){
				$("#divUploadNewImport").modal('hide');
        			//Populate the document status....
					energy_rep_comm_stats ="";
					for (var nCount = 0; nCount < data.energy_documents_res[0].energy_document_status_res.length; nCount++) {
        			if(nCount == 0) Document_def_status = data.energy_documents_res[0].energy_document_status_res[nCount].data_value_id;
						energy_rep_comm_stats += "<option value=" +data.energy_documents_res[0].energy_document_status_res[nCount].data_value_id + ">"+ data.energy_documents_res[0].energy_document_status_res[nCount].data_value +"</option>";
        			}
					$("#ddlDocumentStatus","#divUploadUpdate").empty().append(energy_rep_comm_stats).unbind('change');

        			//populate the document list....
					var nCurrRecRound = ($("#currPageNumber","#tablePagination").val() !=undefined) ?  ($("#currPageNumber","#tablePagination").val()-1) : 0 ;
					$("#tablePagination" , "#documents").remove();
					$("#tblUploaddashBoard > tbody > tr").remove();
					if(data.energy_documents_res[1].energy_rep.length > 0) {
						$("#WelcomeNoteDocuments" ,"#documents").hide();
						$("#divfilter","#documents").show();
        			for(var nCount=0; nCount< data.energy_documents_res[1].energy_rep.length; nCount++){
        				var documentId = data.energy_documents_res[1].energy_rep[nCount].energy_documents_id;
        				var commentText  = (data.energy_documents_res[1].energy_rep[nCount].comm_status == 0) ? "<span id='new" +documentId+"'><i class='icon-envelope'></i><span style='color:black;font-weight:bold;'> New! </span></span>"  + data.energy_documents_res[1].energy_rep[nCount].comment: data.energy_documents_res[1].energy_rep[nCount].comment;
        				var deleteModalText = (data.energy_documents_res[1].energy_rep[nCount].status == "Completed")? "divUploadDelete" : "divUploadDeleteFail";
						var btn="";
					    if(data.energy_documents_res[1].energy_rep[nCount].status== "Not Started")
						{
        				btn+="<span class='label label-important' id='rpSt" +documentId+"'>"+data.energy_documents_res[1].energy_rep[nCount].status+"</span>";
						}
						else if(data.energy_documents_res[1].energy_rep[nCount].status== "Completed")
						{
						btn+="<span class='label label-success' id='rpSt" +documentId+"'>"+data.energy_documents_res[1].energy_rep[nCount].status+"</span>";
						
						}
						else if(data.energy_documents_res[1].energy_rep[nCount].status== "In Progress")
						{
						btn+="<span class='label label-info' id='rpSt" +documentId+"'>"+data.energy_documents_res[1].energy_rep[nCount].status+"</span>";
						
						} 
						var tblUpload = "<tr><td><h3 style='margin:0;'>"+data.energy_documents_res[1].energy_rep[nCount].file_name+"</h3><p style='font-size:14px;color:black;margin-bottom:0px'>Uploaded on "+data.energy_documents_res[1].energy_rep[nCount].cdate+" by "+data.energy_documents_res[1].energy_rep[nCount].username+"&nbsp&nbsp"+btn+" </p></i><p style='color:black;'>"+ commentText+ "</p></td><td style='text-align:right;'><a href='javascript:void(0);' onclick='javascript:downloadDocument(this,"+ documentId+");' style='text-decoration: none !important;' id='userbuildingdownload'>Download &nbsp;&nbsp;<i class='icon-download'> </i></a><br /><span id='upRe_" + documentId+"'><a href='#divUploadUpdate' data-toggle='modal' onclick='javascript:loadUpdateDocument(" + documentId +", "+data.energy_documents_res[1].energy_rep[nCount].document_status+");' style='text-decoration: none !important;' id='userbuildingupdate'>Update &nbsp;&nbsp;<i class='icon-edit'></i></a></span><br /> <span id='dlRe_" + documentId + "'><a href='#" + deleteModalText + "' onclick='javascript:loadDeleteDocument(" + documentId +",\"" + deleteModalText + "\");' style='text-decoration: none !important;' data-toggle='modal' id='userbuildingdelete'>Delete &nbsp;&nbsp;<i class='icon-remove'></i></a></span></td></tr>";
						$("#tblUploaddashBoard  tbody:last").append(tblUpload);
        				}
        				$("#tblUploaddashBoard").show();
					
        				if(data.energy_documents_res[1].energy_rep.length  >10){
						if(isNewUpload)
						{
						nCurrRecRound=0;
						}
						$("#tblUploaddashBoard").tablePagination({
                    		 currPage: nCurrRecRound+1	
                    	});
						}
                    	$("#UploadDeleteYes","").off('click').bind("click",deleteDocument);
						isNewUpload =false;
        			 }else
        				 $("#WelcomeNoteDocuments" ,"#documents").show();
        			}else 
					logout(1);
        		} else {
        		logout();
        		}
        	},
            error: function (xhr, textStatus, error) {
                alert("UBS4_" + error);
            }
			
	});
	
}

function openImportEnergyUser() {
	var isUploadSuccess = false;
	var activeFormType = 'energy_document';
	$('#UploadNewDocumentClose').show();
	$("#divUploadNewImport").modal('show');
    $("#lblImportError", "#divUploadNewImport").text('').hide();
    $("#lblImportSuccess", "#divUploadNewImport").text('').hide();
    $("#divUploadNewdocuments", "#divUploadNewImport").show().html("<table cellpadding='4'><tr><td >Upload File</td><td nowrap='nowrap'><div style='position:relative'><input id='fileupload_document' type='file' name='files[]' onchange='setDocumentFileNameFromPath(this.value);' style='width:350px;position:relative; -moz-opacity:0; text-align: right;opacity: 0; filter:alpha(opacity: 0);z-index:2'><div style='position:absolute;top:0px;left:0px;z-index:1'><input readonly type='text' id='txtFakeFile_document' value='Select file...'/> &nbsp;<button class='btn btn-primary' style='margin-top:-10px;'>Choose ...</button></div></div></td></tr><tr><td >Comment</td><td nowrap='nowrap'><textarea id='txaComments' placeholder='Enter text....' style='width:88%;height:100%;resize:none;margin-top:15px;'></textarea></td></tr></table>");
	$("#divProgressBar", "#divUploadNewImport").hide();
    $("#btnDocumentImportFinish", "#divUploadNewImport").hide();
    $("#btnDocumentImportSubmit", "#divUploadNewImport").show();
    $("#xdivUploadNewImport", "#divUploadNewImport").show();
    $('#divProgressBar .bar').css('width', '0%');
    $('#fileupload_document table tbody tr.template-download').remove();
    $('#fileupload_document').fileupload({
        dataType: 'json',
        url: BACKENDURL + "data_upload/import_data",
        add: function (e, data) {
            $("#btnDocumentImportSubmit", "#divUploadNewImport").off('click').on('click', function () {
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
                        "comments": $("#txaComments", "#divUploadNewdocuments").val(),
                        "document_status": Document_def_status,
                    };
                    data.submit();
                } else {
                    alert("UBS4_" + error);
                }
            });
        },
        progressall: function (e, data) {
		$('#UploadNewDocumentClose').hide();
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
        success: function (data) {
		 isNewUpload =true;
		if (data.error) {
                $("#lblImportSuccess", "#divUploadNewImport").text("").hide();
                $("#lblImportError", "#divUploadNewImport").text("Unsuccessful. Failure reason: " + data.error_msg).show();
            } else {
                $("#lblImportError", "#divUploadNewImport").text("").hide();
                $("#lblImportSuccess", "#divUploadNewImport").text("Success. File was succesfully uploaded and imported into the system.").show();
            }
            $("#btnDocumentImportFinish", "#divUploadNewImport").removeClass("disabled").off('click').on('click', {
                f: activeFormType
            }, finishImport);
            isUploadSuccess = true;
        },
        done: function (e, data) {
            if (data.result.error) {
                $("#lblImportSuccess", "#divUploadNewImport").text("").hide();
                $("#lblImportError", "#divUploadNewImport").text("Unsuccessful. Failure reason: " + data.result.error_msg).show();
            } else {
                $("#lblImportError", "#divUploadNewImport").text("").hide();
                $("#lblImportSuccess", "#divUploadNewImport").text("Success. File was succesfully uploaded and imported into the system.").show();
            }
            $("#btnDocumentImportFinish", "#divUploadNewImport").removeClass("disabled").off('click').on('click', {
                f: activeFormType
            }, finishImport);
            isUploadSuccess = true;

            // alert(data.error_msg);
            $("#btnDocumentImportSubmit", "#divUploadNewImport").off('click').on('click', function () {
                $("#lblImportError", "#divUploadNewImport").text('Please select a file to upload').show();
            });
        }
    });
}
function finishImport(event) {

	var formtype = event.data.f;
	if (formtype == "energy_document") {
		loadMyDocuments();
		
     }
}
$("#btnDocumentImportSubmit", "#divUploadNewImport").click(function () {
    $("#lblImportError", "#divUploadNewImport").text('Please select a file to upload').show();
});

function setDocumentFileNameFromPath(path) {

    $("#txtFakeFile_document").val(path);
}

function loadUpdateDocument(documentId, document_status) {
	$.support.cors = true;
    $.ajax({
        url: BACKENDURL + "user/get_energy_document_comments",
        type: "post",
        data: {
            session_id: localStorage["SESSIONID"],
            energy_documents_id: documentId,
        },
        dataType: "json",
        crossDomain: true,
        success: function (data) {
            if (data.session_status) {
                if (data.error == 0) {
                    $("#hdnDocumentId", "#divUploadUpdate").val(documentId);
                    $("#btnDocumentSave", "#divUploadUpdate").attr("disabled", "disabled").text('Saved');
                    //Populate the status
                    $("#ddlDocumentStatus", "#divUploadUpdate").val(document_status).unbind('change').change(function () {
                        if ($(this).val() != document_status)
                            $("#btnDocumentSave", "#divUploadUpdate").removeAttr("disabled").text('Save').unbind("click").bind("click", updateDocumentStatus);
                        else
                            $("#btnDocumentSave", "#divUploadUpdate").attr("disabled", "disabled").unbind("click").text('Saved');
                    });

                    var comm_str = "";
                    var pull_str = "pull-left";
					var color="alert alert-success";
					var textcolor="text_success";
                    //populate comments.
                    for (var i = 0; i < data.energy_rep_comm_res.length; i++) {
                    	pull_str = (data.energy_rep_comm_res[i].role_name == "User") ? "pull-left": "pull-right";
						color = (data.energy_rep_comm_res[i].role_name == "User") ? "alert alert-success": "alert alert-info"; 
						textcolor = (data.energy_rep_comm_res[i].role_name == "User") ? "text_success": "text_info";
        				comm_str += "<div class='row-fluid'><div class='span9 " + pull_str + "'><pre class='"+ color+"'><label  class='"+ textcolor+"' >" + data.energy_rep_comm_res[i].comment_text + "</label><i class='icon-comment'></i> <label  class='"+ textcolor+"'  style='font-size:11px;margin-top: -20px;margin-left: 22px;'>By " + data.energy_rep_comm_res[i].username + " on " + data.energy_rep_comm_res[i].cdate + "</label></pre></div></div>";
                    }
                    $("#divDocumentComments", "#divUploadUpdate").html(comm_str);
                    //updating new icon...
                    $("#new" + documentId, "#documents").hide();

                    $("#btnSaveComments", "#divUploadUpdate").attr("disabled", "disabled").unbind("click");
                    //bind the comments textbox.
                    $("#txaComments", "#divUploadUpdate").val('').bind("keypress keyup focus", function () {
                        if ($("#txaComments", "#divUploadUpdate").val() != "")
                            $("#btnSaveComments", "#divUploadUpdate").removeAttr("disabled").text('Post').unbind("click").bind("click", insertDocumentComments);
                        else
                            $("#btnSaveComments", "#divUploadUpdate").attr("disabled", "disabled").unbind("click");
                    });
                }else
				logout(1);
            } else
                logout();
        },
        error: function (xhr, textStatus, error) {
            alert("UBS5_" + error);
        }
    });
}

function updateDocumentStatus() {
    $.support.cors = true;
    $("#btnDocumentSave", "#divUploadUpdate").attr("disabled", true).text('Saving');
    var documentId = $("#hdnDocumentId", "#divUploadUpdate").val();
    var document_status = $("#ddlDocumentStatus", "#divUploadUpdate").val();
    $.ajax({
        url: BACKENDURL + "user/update_energy_document_status",
        type: "post",
        data: {
            session_id: localStorage["SESSIONID"],
            energy_documents_id: documentId,
            status: document_status
        },
        dataType: "json",
        crossDomain: true,
        success: function (data) {
            if (data.session_status) {
                if (data.error == 0) {
                    //	        			$("#divUploadUpdate").modal('hide');
                    //	        			loadMyDocuments();
                    //Update the document status....

                    var rpStatus = $("#ddlDocumentStatus", "#divUploadUpdate").children(':selected').text();
                    $("#rpSt" + documentId, "#documents").text(rpStatus);
					if(rpStatus=="Not Started")
						{
						
						$("#rpSt"+documentId,"#documents").removeClass();
						$("#rpSt"+documentId,"#documents").addClass('label label-important');
						}
						 else if(rpStatus=="Completed")
						{
						if($("#chkHideComplete").is(':checked')){
						loadMyDocuments();
						}
						$("#rpSt"+documentId,"#documents").removeClass();
						$("#rpSt"+documentId,"#documents").addClass('label label-success');
						
						}
						
						else if(rpStatus=="In Progress")
						{
						
						$("#rpSt"+documentId,"#documents").removeClass();
						$("#rpSt"+documentId,"#documents").addClass('label label-info');
						} 
                    var deleteModalText = (rpStatus == "Completed") ? "divUploadDelete" : "divUploadDeleteFail";
                    $("#dlRe_" + documentId, "#documents").html("<a href='#" + deleteModalText + "' onclick='javascript:loadDeleteDocument(" + documentId + ",\"" + deleteModalText + "\");' style='text-decoration: none !important;' data-toggle='modal'>Delete &nbsp;&nbsp;<i class='icon-remove'></i></a>");
                    $("#upRe_" + documentId, "#documents").html("<a href='#divUploadUpdate' data-toggle='modal' onclick='javascript:loadUpdateDocument(" + documentId + ", " + document_status + ");' style='text-decoration: none !important;'>Update &nbsp;&nbsp;<i class='icon-edit'></i></a>");
                    $("#btnDocumentSave", "#divUploadUpdate").attr("disabled", true).text('Saved');
                    $("#ddlDocumentStatus", "#divUploadUpdate").unbind('change').change(function () {
                        if ($(this).val() != document_status)
                            $("#btnDocumentSave", "#divUploadUpdate").removeAttr("disabled").text('Save').bind("click", updateDocumentStatus);
                        else
                            $("#btnDocumentSave", "#divUploadUpdate").attr("disabled", "disabled").unbind("click").text('Saved');
                    });
                }else
				logout(1);
            } else
                logout();
        },
        error: function (xhr, textStatus, error) {
            alert("UBS6_" + error);
        }
    });
}

function insertDocumentComments() {

    $.support.cors = true;
    var document_status = $("#ddlDocumentStatus", "#divUploadUpdate").val();
    $.ajax({
        url: BACKENDURL + "user/insert_energy_document_comments",
        type: "post",
        data: {
            session_id: localStorage["SESSIONID"],
             energy_documents_id: $("#hdnDocumentId", "#divUploadUpdate").val(),
            comments: $("#txaComments", "#divUploadUpdate").val()
        },
        dataType: "json",
        crossDomain: true,
        success: function (data) {
            if (data.session_status) {
                if (data.error == 0) {
                    $("#divUploadUpdate").modal('hide');
                    loadMyDocuments();
                }else
				logout(1);
            } else
                logout();
        },
        error: function (xhr, textStatus, error) {
            alert("UBS7_" + error);
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
        url: BACKENDURL + "user/delete_energy_document",
        type: "post",
        data: {
            session_id: localStorage["SESSIONID"],
            energy_documents_id: documentId,
        },
        dataType: "json",
        crossDomain: true,
        success: function (data) {
            if (data.session_status) {
                if (data.error == 0) {
                    $("#divUploadDelete").modal('hide');
                    $("#UploadDeleteIdY").modal('show');
                    loadMyDocuments();
                }else
				logout(1);
            } else
                logout();
        },
        error: function (xhr, textStatus, error) {
            alert("UBS8_" + error);
        }
    });
}

function downloadDocument(f, documentId) {
    var url = BACKENDURL + "common/download_file";
    window.open(url + "/" + localStorage["SESSIONID"] + "/energy_document/" + documentId);
}

function addCommas(str) {
    var amount = new String(str);
    amount = amount.split("").reverse();

    var output = "";
    for ( var i = 0; i <= amount.length-1; i++ ){
        output = amount[i] + output;
        if ((i+1) % 3 == 0 && (amount.length-1) !== i)output = ',' + output;
    }
    return output;
}

/************************* end of School Documents **********************/




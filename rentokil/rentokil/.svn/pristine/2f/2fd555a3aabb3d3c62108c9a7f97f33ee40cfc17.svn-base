$(document).ready(function () {
	if ((localStorage.skinId != undefined) && (localStorage.skinId != ''))	
    $('head').append('<link rel="stylesheet" href="'+location.protocol + '//' + location.host+ '/assets/skins/'+localStorage.skinId+'/'+localStorage.skinId+'.css" type="text/css" />');        
    $("#userfooter").load("userfooter.html");
    $.support.cors = true;
   // $("#divcontainer").load(loadFileName + "?x=" + (new Date()).getTime(), function () {
          $("#divcontainer").load(loadFileName, function () {
         //$("#userheader").load("userheader.html?x="+ (new Date()).getTime(), function () {
            $("#userheader").load("userheader.html", function () {
            //Make a Ajax call to get the user authorization details.
            getAuthorization();
			if ((localStorage.skinId != undefined) && (localStorage.skinId != ''))
            $("#header-divider img").attr("src",location.protocol + '//' + location.host + '/assets/skins/'+localStorage.skinId+'/header_div.png');
        });
    });    
});

function getAuthorization(){
//    var module_code = Object.keys(L1_menu).filter(function(key) {
//        return L1_menu[key] === loadFileName.replace("_body","")})[0];
    var submodule_code = Object.keys(L2_menu).filter(function(key){
    	return L2_menu[key] === loadFileName.replace("_body","");
    })[0];
    $.support.cors = true;
    $.ajax({
        url: BACKENDURL + "user/get_user_authroization",
        type: "post",
        data: {
            session_id: localStorage["SESSIONID"],
            module_code: module_code,
            submodule_code: submodule_code
        },
        dataType: "json",
        crossDomain: true,
        success: function (data) {
            if (data.session_status) {
                if (data.error == 0) {
                    if(data['auth_res'].sm.length == 0)
                        location.href="error.html";
                    //Load the Level1 menu and then call page specific second navigation function.
                    if(data['auth_res'].sm.length > 0 && data['auth_res'].sm[0]['hide_main_nav'] == 0 && data['auth_res'].m.length > 1){
                        document.body.className ="";
                        //Showing the main navigation
                        $("#navLevel1").addClass('navbar').show();
                        //Populating the main navigation links
                        $("#lnkHeaderLogo").attr("href",L2_menu[data['auth_res'].m[0]['m']]);
                        for(var i=0;i<data['auth_res'].m.length;i++){
                            var l1code =data['auth_res'].m[i]['m'];
                            var fname = (l1code == submodule_code) ? "javascript:void(0);" : L2_menu[l1code];
                            var showActive = (data['auth_res'].m[i]['mc'] == module_code) ? "active" : "";
                            $("#l1nav").append("<li class='"+showActive+"'><a href='"+fname+"' id='U"+data['auth_res'].m[i]['mname']+"' name='U"+data['auth_res'].m[i]['mname']+"'>"+ data['auth_res'].m[i]['mname'] +"</a></li>");
                            if(i != 4)
                                $("#l1nav").append("<li class='divider-vertical'></li>")
                        }
                    } else {
						//US 312 item 25 Adding 25px between header and content when only 1 module is active						
						$("#navLevel1").hide();
						$("#navLevel2").attr("style","margin-bottom:25px");
                        $("#lnkHeaderLogo").attr("href",L1_menu[module_code]);
                        document.body.className ="guest";
                    }
                    
                    if(data['auth_res'].sm.length == 1 && data['auth_res'].sm[0]['sm'] != submodule_code)
                    	location.href="error.html";
                		 
                    if(data['auth_res'].sm.length > 1) {
                        
                        //Don't show the secondary menu if only one submodule available. and populate the second level.
                        //US 312 item 25 setting 0px when level 2 navigation is not available
						$("#navLevel2").attr("style","margin-bottom:0px");
						var navAct ="",hlink= "",ispage=false;
                        for(var i=0;i<data['auth_res'].sm.length;i++){
                        	if(data['auth_res'].sm[i]['sm'] == submodule_code) {
                        		navAct = "active";
                        		hlink = "javascript:void(0);";
                        		ispage = true; 
                        	}
                        	else {
                        		navAct = "";
                        		hlink = L2_menu[data['auth_res'].sm[i]['sm']];
                        	}
                            $("#secondarynav").append("<li class='"+navAct+"'><a href='"+hlink+"'>" + data['auth_res'].sm[i]['smname']+ "</a></li>");
							
                        }
                        if(!ispage)
                        	location.href="error.html";
                    }
                    else {
                        if ((data['auth_res'].sm[0]['sm'] == 'L2AST') && (data['auth_res'].ssm.length == 0))  {
                            $("#btnUserZoneRefresh").hide();
                            $("#lblUserZonechkbx").hide();
                        } else if ((data['auth_res'].sm[0]['sm'] == 'L2AST') && (data['auth_res'].ssm.length == 1) && (data['auth_res'].ssm[0].sscode == 'L3ATDF')) {
                            $("#btnUserZoneRefresh").hide();
                        }
                        //document.body.className = (document.body.className == "") ? document.body.className : "dashboard";
                        document.body.className ="dashboard";
                    }

                   	
                    //Populate the 3rd level
                    var navAct ="active",L3href="",L3link="";
                    for(var i=0;i<data['auth_res'].ssm.length;i++){
                        var sscode = data['auth_res'].ssm[i]['sscode'];
                        if(i<data['auth_res'].ssm.length-1 && sscode == data['auth_res'].ssm[i+1]['pcode']){
                            //var liStr = "<li class='dropdown "+navAct+"'><a href='#' class='dropdown-toggle' data-toggle='dropdown'>"+ data['auth_res'].ssm[i]['ssmname'] +" <b class='caret'></b></a><ul class='dropdown-menu'>";
                            $("#lid_" +L3_menu[data['auth_res'].ssm[i]['sscode']+'_I']).attr("style","display:inline");
                            while(i<data['auth_res'].ssm.length-1 && sscode == data['auth_res'].ssm[i+1]['pcode']){
                                L3href = (L3href!="" && L3href!=undefined) ? L3href :L3_menu[data['auth_res'].ssm[i+1]['sscode']+'_H'];
                                L3link =(L3link!="" && L3link!=undefined) ? L3link: L3_menu[data['auth_res'].ssm[i+1]['sscode']+'_I'];
                                $("#lid_" +L3_menu[data['auth_res'].ssm[i+1]['sscode']+'_I']).attr("style","display:inline");
                                $("#d_" +L3_menu[data['auth_res'].ssm[i+1]['sscode']+'_I']).attr("id",L3_menu[data['auth_res'].ssm[i+1]['sscode']+'_I']);
                                //alert(data['auth_res'].ssm[i+1]['ssmname']);
                                //liStr +="<li><a href='"+L3_menu[data['auth_res'].ssm[i+1]['sscode']+'_H']+"' data-toggle='tab' id='"+L3_menu[data['auth_res'].ssm[i+1]['sscode']+'_I']+"'>"+data['auth_res'].ssm[i+1]['ssmname']+"</a></li>";
                                i++;
                            }
                        //liStr +="</ul></li>";
                        //$("#widgetnav").append(liStr);
                        }
                        else {
                            L3href = (L3href!="" && L3href!=undefined) ? L3href :L3_menu[data['auth_res'].ssm[i]['sscode']+'_H'];
                            L3link =(L3link!="" && L3link!=undefined) ? L3link: L3_menu[data['auth_res'].ssm[i]['sscode']+'_I'];
                            $("#li_" +L3_menu[data['auth_res'].ssm[i]['sscode']+'_I']).attr("style","display:inline");
                            $("#l_" +L3_menu[data['auth_res'].ssm[i]['sscode']+'_I']).attr("id",L3_menu[data['auth_res'].ssm[i]['sscode']+'_I']);
                        //$("#widgetnav").append("<li class='"+navAct+"'><a href='"+L3_menu[data['auth_res'].ssm[i]['sscode']+'_H']+"' data-toggle='tab' id='"+L3_menu[data['auth_res'].ssm[i]['sscode']+'_I']+"'>"+data['auth_res'].ssm[i]['ssmname']+"</a></li>");
                        }
                        navAct ="";
                    }
            			
                    if(data['auth_res'].ssm.length == 1)
                        $("#navLevel3").attr("style","display:none");//.hide();
                    else
                        $("#navLevel3").attr("style","");
                    LoadProfileInfo();
                    //$("#userfooter").load("userfooter.html");
                    $("#divWelcome").html("Welcome " + localStorage["FNAME"] + "  " + localStorage["LNAME"]);
                    $("#divLoggedAs").html(localStorage["CONTRACTNAME"]);

                    // This is for specific to page and need to define in every page.
                    LoadPageData();
                    LoadUserFooterContent(JSON.parse(localStorage.getItem("SkinInfo")));
                    $("#"+L3link).click();
                    $(L3href).addClass("active");
					
                    $('#widgetnav').each(function() {		  
                        if($('#tabCardPaymentHistory').length>0)
                        {		  
                            $("#btnCardPaymentHistory").show()
                        }
                        else
                        {
		  
                            $("#btnCardPaymentHistory").hide()
                        }
	  
                        if($('#tabCashPaymentHistory').length>0)
                        {		
                            $("#btnPaymentHistory").show()
			   
                        }
                        else
                        {	  
                            $("#btnPaymentHistory").hide()	  
                        }
                    });
					
                //$("#btnCardPaymentHistory").hide()
                } else { // session true error true
                    alert(data.error_msg);
                }
            } else {
                logout();
            }
        },
        error: function (xhr, textStatus, error) {
            alert(error);
        }
    });
}



//Will be called during the page load for profile modal load.
function LoadProfileInfo(){
    $('#btnLogout').bind("click", logout);
    $("#btnProfileEdit").click(function () {
        /* page load */
        $("#divProfileEdit").load("usermyprofile.html", function () {
        //$.getScript("js/jquery/jquery.passstrength.min.js").done(function(script, textStatus ) {
        //$('head').append('<link rel="stylesheet" href="css/edfm-custom.css" type="text/css" />'); 
        LoadProfileData();
        // Update Profile 
		//changed for detecting paste event along with keypress
        $("#txtOldpwd","#frmUpdateProfile").on('paste keypress', function() {
            $("#txtnewpwd","#frmUpdateProfile").removeAttr("disabled");
            $("#txtrenternewpwd","#frmUpdateProfile").removeAttr("disabled");
        });
        $("#txtOldpwd","#frmUpdateProfile").keyup(function() {
            if($("#txtOldpwd","#frmUpdateProfile").val().length == 0)
            {
                $("#txtnewpwd","#frmUpdateProfile").attr('disabled', 'disabled');
                $("#txtrenternewpwd","#frmUpdateProfile").attr('disabled', 'disabled');
            }
        });
        $("#txtnewpwd","#frmUpdateProfile").keypress(function() {
            //$("#pwdStrengthify").addClass("span3 offset2");
            $("#pwdStrengthify").css("display","inline-block");
        });
        $('input[id=txtnewpwd]',"#frmUpdateProfile").passStrengthify();
        $.ajax({
            url: BACKENDURL + "common/get_data_any",
            type: "post",
            data: {
                session_id: localStorage["SESSIONID"],
                data_ref: "user_titles"
            },
            dataType: "json",
            crossDomain: true,
            success: function (data) {
                if (data.session_status) {
                    if (data.error == 0) { // session true error
                        // false
                        $("#ddlUserTitle", "#divProfileEdit").empty();
                        var titleVal;
                        for (var nCount = 0; nCount < data.data_any_res.length; nCount++) {
                            var selectedText = (data.data_any_res[nCount].data_value == localStorage["TITLE"]) ? "selected" : "";
                            titleVal = "<option value=" + data.data_any_res[nCount].data_value_id + " " + selectedText + " >" + data.data_any_res[nCount].data_value + "</option>";
                            $("#ddlUserTitle", "#divProfileEdit").append(titleVal);
                        }
                        $("#txtNewUserFName", "#divProfileEdit").val((localStorage["FNAME"] == "undefined") ? "" : localStorage["FNAME"]);
                        $("#txtNewUserLName", "#divProfileEdit").val((localStorage["LNAME"] == "undefined") ? "" : localStorage["LNAME"]);
                        $("#txtNewUseremail", "#divProfileEdit").val((localStorage["EMAIL"] == "undefined") ? "" : localStorage["EMAIL"]);
                        $("#txtNewUserphone", "#divProfileEdit").val((localStorage["TELEPHONE"] == "null") ? "" : localStorage["TELEPHONE"]);
                        $("#txtWorkTelephone", "#divProfileEdit").val((localStorage["WorkTel"] == "null") ? "" : localStorage["WorkTel"]);
                        $("#txtMobileNumber", "#divProfileEdit").val((localStorage["MobileTel"] == "null") ? "" : localStorage["MobileTel"]);
                        var chkEmail = (localStorage["ChkEmail"] == 1)? true: false; 
                        $("#chkEmail","#divProfileEdit").attr('checked',chkEmail);
                        var chkSms = (localStorage["ChkSms"] == 1) ? true: false; 
                        $("#chkSms","#divProfileEdit").attr('checked',chkSms);
                        $("#btnUserProfileSubmit", " #divProfileEdit").bind("click", updateContactDetails);
                        $("#xNewCustomerAdmin","#divProfileEdit").bind("click",clearContactDetails);
                        $("#xcloseCustomerAdmin","#divProfileEdit").bind("click",clearContactDetails);
                    } else { // session true error true
                        //alert("error true")
                        alert(data.error_msg);
                        //alert(data.error);
                    }
                } else {
                    logout();
                }
            },
            error: function (xhr, textStatus, error) {
                alert(error);
            }
        });
    //});//get script
        })
  
        /* page load */        
        $("#txtNewUserFName", "#divProfileEdit").val((localStorage["FNAME"] == "undefined") ? "" : localStorage["FNAME"]);
        $("#txtNewUserLName", "#divProfileEdit").val((localStorage["LNAME"] == "undefined") ? "" : localStorage["LNAME"]);
        $("#txtNewUseremail", "#divProfileEdit").val((localStorage["EMAIL"] == "undefined") ? "" : localStorage["EMAIL"]);
        $("#txtNewUserphone", "#divProfileEdit").val((localStorage["TELEPHONE"] == "null") ? "" : localStorage["TELEPHONE"]);
        $("#txtWorkTelephone", "#divProfileEdit").val((localStorage["WorkTel"] == "null") ? "" : localStorage["WorkTel"]);
        $("#txtMobileNumber", "#divProfileEdit").val((localStorage["MobileTel"] == "null") ? "" : localStorage["MobileTel"]);
        var chkEmail = (localStorage["ChkEmail"] == 1) ? true : false;
        $("#chkEmail","#divProfileEdit").attr('checked',chkEmail);
        var chkSms =(localStorage["ChkSms"] == 1) ? true: false; 
        $("#chkSms","#divProfileEdit").attr('checked',chkSms);    
    });    
}

// clear contact details 

function clearContactDetails(){
    //location.reload();
    $("#txtOldpwd", "#divProfileEdit").val("");
    $("#txtnewpwd", "#divProfileEdit").val("");
    $("#txtrenternewpwd", "#divProfileEdit").val("");
    $("#pwdStrengthify").css("display","none");
    $("#updateProfileLabel").text("");
    $("#txtnewpwd","#frmUpdateProfile").attr('disabled', 'disabled');
    $("#txtrenternewpwd","#frmUpdateProfile").attr('disabled', 'disabled');
    if($('#newpass').hasClass('control-group error'))
    {
        $('#newpass').removeClass("control-group error").addClass('control-group');
    }
    if($('#repass').hasClass('control-group error'))
    {
        $('#repass').removeClass("control-group error").addClass('control-group');
        $('#repass span:first').remove();
    }
}

// updating profile
function updateContactDetails() {
    if($("#txtOldpwd", "#divProfileEdit").val() != "")
    {
        if($("#txtnewpwd", "#divProfileEdit").val() == "")
        {
            $("#updateProfileLabel").text("Please enter new password");
            return false;
        }
        else
        {
            $("#updateProfileLabelAdmin").text("");
        }
    }
    if(($("#txtOldpwd", "#divProfileEdit").val() == $("#txtnewpwd", "#divProfileEdit").val()) && ($("#txtOldpwd", "#divProfileEdit").val() != ""))
    {
        $("#updateProfileLabel").text("New password can not be the same as the old password");
        return false;
    }
		
    if($("#frmUpdateProfile").valid())
    {
        $.ajax({
            url: BACKENDURL + "common/edit_profile",
            type: "post",
            data: {
                session_id	: 	localStorage["SESSIONID"],
                user_id		: 	localStorage["USERID"],
                title_id 	: 	$("#ddlUserTitle").val(),
                title 		: 	$("#ddlUserTitle :selected", "#divProfileEdit").text(),
                first_name 	: 	$("#txtNewUserFName", "#divProfileEdit").val(),
                last_name 	: 	$("#txtNewUserLName", "#divProfileEdit").val(),
                user_email 	: 	$("#txtNewUseremail", "#divProfileEdit").val(),
                telephone 	: 	$("#txtNewUserphone", "#divProfileEdit").val(),
                work_tel 	: 	$("#txtWorkTelephone", "#divProfileEdit").val(),
                mobile_tel 	: 	$("#txtMobileNumber", "#divProfileEdit").val(),
                current_pwd :	escape($("#txtOldpwd", "#divProfileEdit").val()),
                new_pwd 	:	escape($("#txtnewpwd", "#divProfileEdit").val()),
                renew_pwd 	:	escape($("#txtrenternewpwd", "#divProfileEdit").val()),
                check_email :	($("#chkEmail","#divProfileEdit").is(":checked")?1:0),
                check_sms 	:	($("#chkSms","#divProfileEdit").is(":checked")?1:0)
            },
            dataType: "json",
            crossDomain: true,
            success: function (data) {
                if (data.session_status) {
                    if (data.error == 0) { // session true error false
                        $("#editBtnClose").load("commonsuccessmodal.html", function () {
                            $("#headermsg").html("Update settings");
                            $("#sucmsg").html("Success, you've succesfully updated your profile settings!");
                        });
                        localStorage["TITLE"] 		= $("#ddlUserTitle :selected", "#divProfileEdit").text();
                        localStorage["FNAME"] 		= $("#txtNewUserFName", "#divProfileEdit").val();
                        localStorage["LNAME"] 		= $("#txtNewUserLName", "#divProfileEdit").val();
                        localStorage["EMAIL"] 		= $("#txtNewUseremail", "#divProfileEdit").val();
                        localStorage["TELEPHONE"] 	= $("#txtNewUserphone", "#divProfileEdit").val();
                        localStorage["WorkTel"] 	= $("#txtWorkTelephone", "#divProfileEdit").val();
                        localStorage["MobileTel"] 	= $("#txtMobileNumber", "#divProfileEdit").val();
                        localStorage["ChkEmail"] 	=($("#chkEmail","#divProfileEdit").is(":checked")?1:0);
                        localStorage["ChkSms"] 		=($("#chkSms","#divProfileEdit").is(":checked")?1:0);
                        $("#pwdStrengthify").css("display","none");
                        $("#updateProfileLabel").text("");
                        //$("#editBtnClose").load("successprofile.html");
                        // updating the headwer with the current name
                        $("#divWelcome").html("Welcome " + localStorage["FNAME"] + " " + localStorage["LNAME"]);
                    } else { // session true error true
                        $("#txtOldpwd", "#divProfileEdit").val("");
                        $("#txtnewpwd", "#divProfileEdit").val("");
                        $("#txtrenternewpwd", "#divProfileEdit").val("");
                        $("#pwdStrengthify").css("display","none");
                        $("#updateProfileLabel").text("");
                        $("#txtnewpwd","#frmUpdateProfile").attr('disabled', 'disabled');
                        $("#txtrenternewpwd","#frmUpdateProfile").attr('disabled', 'disabled');
                        $("#editBtnClose").load("commonFailuremodal.html", function () {
                            $("#headermsg").html("Update settings");
                            $("#sucmsg").html("Failure," + data.error_msg);
                        });
                    //alert(data.error_msg);
                    //alert(data.error);
                    }

                } else {
                    logout();
                }
            },
            error: function (xhr, textStatus, error) {
                alert(error);
            }
        });
    }else
        return false;
}

// Validate Profile 

function LoadProfileData()
{
    $("#frmUpdateProfile").validate({
        rules: {
            txtNewUserFName:{
                regAlphaNumeric:true, 
                required: true
            },
            txtNewUserLName:{
                regAlphaNumeric:true, 
                required: true
            },
            txtNewUseremail:{
                customemail:true, 
                required: true
            },
            txtNewUserphone:{
                regTelephone:true
            },
            txtWorkTelephone:{
                regTelephone:true
            },
            txtMobileNumber:{
                regTelephone:true
            },
            txtnewpwd:{
                regPwd:true
            },
            txtrenternewpwd:{
                equalTo: "#txtnewpwd"
            }
        },
        messages: {
            txtNewUserFName:{
                required: "Please enter the first name"
            },
            txtNewUserLName:{
                required: "Please enter last name"
            },
            txtNewUseremail:{
                required: "Please enter email address"
            },
            txtnewpwd:{
                notEqualTo:"New password can not be the same as the old password"
            },
            txtrenternewpwd:{
                equalTo:"New and confirm passwords are not the same"
            }
        },
        submitHandler: function(form) {
            form.submit();
        },
        errorClass: "help-inline",
        errorElement: "span",
        highlight:function(element, errorClass, validClass){
            $(element).parents('.control-group').addClass('error');
        },
        unhighlight: function(element, errorClass, validClass){
            $(element).parents('.control-group').removeClass('error');
        }
    });
}
 
function LoadUserFooterContent(skininfo)
{
    var requestType = 'http://';
    $("#lfbt").text(skininfo.lfbt);
    (skininfo.lfbs1==1)?$("#lfbd1 a").attr('href',requestType+skininfo.lfbl1):$("#lfbd1 a").attr('href','#'); // 1 enable / 0 disable
    (skininfo.lfbs2==1)?$("#lfbd2 a").attr('href',requestType+skininfo.lfbl2):$("#lfbd2 a").attr('href','#');
    (skininfo.lfbs3==1)?$("#lfbd3 a").attr('href',requestType+skininfo.lfbl3):$("#lfbd3 a").attr('href','#');
    (skininfo.lfbs4==1)?$("#lfbd4 a").attr('href',requestType+skininfo.lfbl4):$("#lfbd4 a").attr('href','#');
    
    (skininfo.lfbs1==1)?$("#lfbd1 a").html(skininfo.lfbd1).show():$("#lfbi1").hide();  // 1 enable / 0 disable
    (skininfo.lfbs2==1)?$("#lfbd2 a").html(skininfo.lfbd2).show():$("#lfbi2").hide();
    (skininfo.lfbs3==1)?$("#lfbd3 a").html(skininfo.lfbd3).show():$("#lfbi3").hide();
    (skininfo.lfbs4==1)?$("#lfbd4 a").html(skininfo.lfbd4).show():$("#lfbi4").hide();    
    
    $("#rfbt").text(skininfo.rfbt);
    (skininfo.rfbs1==1)?$("#rfbd1 a").attr('href',requestType+skininfo.rfbl1):$("#rfbd1 a").attr('href','#');  // 1 enable / 0 disable
    (skininfo.rfbs2==1)?$("#rfbd2 a").attr('href',requestType+skininfo.rfbl2):$("#rfbd2 a").attr('href','#');
    (skininfo.rfbs3==1)?$("#rfbd3 a").attr('href',requestType+skininfo.rfbl3):$("#rfbd3 a").attr('href','#');
    (skininfo.rfbs4==1)?$("#rfbd4 a").attr('href',requestType+skininfo.rfbl4):$("#rfbd4 a").attr('href','#');
    
    (skininfo.rfbs1==1)?$("#rfbd1 a").html(skininfo.rfbd1).show():$("#rfbi1").hide();  // 0 enable / 1 disable
    (skininfo.rfbs2==1)?$("#rfbd2 a").html(skininfo.rfbd2).show():$("#rfbi2").hide();
    (skininfo.rfbs3==1)?$("#rfbd3 a").html(skininfo.rfbd3).show():$("#rfbi3").hide();
    (skininfo.rfbs4==1)?$("#rfbd4 a").html(skininfo.rfbd4).show():$("#rfbi4").hide();
    $("#fct").html(skininfo.fct);
}
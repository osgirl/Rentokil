var dataerror;
$(document).ready(function() {
    var windowURL = window.location.toString();
    if(windowURL.indexOf('riwebqa') > 0 || windowURL.indexOf('localhost') > 0   || windowURL.indexOf('fminsight.net') > 0){
        $("#divcontainer").load("index_fmbody.html", function () {
            document.title ="FM insight";
            $('head').append('<link rel="stylesheet" href="css/edfm-custom.css" type="text/css" />');
            $("#header-divider img").attr("src","img/header-logininfo-divider.png");
            $('head').append('<link href="img/apple-touch-icon.png" rel="apple-touch-icon">');
            loadPageControls();                        
        });
		$("#divContainerFooter").load("index_fmfooter.html");
    }else{
        $("#divcontainer").load("index_epbody.html", function () {
            document.title ="Edenpay";
            $('head').append('<link rel="stylesheet" href="css/edfm-custom.css" type="text/css" />');  
            $('head').append('<link rel="stylesheet" href="css/eden-setting.css" type="text/css" />');
            $("#header-divider img").attr("src","img/eden-header-logininfo-divider.png");
            $('head').append('<link href="img/eden-apple-touch-icon.png" rel="apple-touch-icon">');
            loadPageControls();
        });
		$("#divContainerFooter").load("index_epfooter.html");
    } 
});

function loadPageControls(){

    // Login Page 
    $("#txtUsername").keypress(function(e) {
	
        if ((e.which && e.which == 13) || (e.keyCode && e.keyCode == 13)) {
            $("#btnLogin").click();
            return false;
        } else
            return true;
    });
    $("#txtPassword").keypress(function(e) {
	  
        if ((e.which && e.which == 13) || (e.keyCode && e.keyCode == 13)) {
            $("#btnLogin").click();
            return false;
        } else
            return true;
    });

    $("#btnLogin").click(function(){
        $("#txtUsername").bind("keypress keyup focus", function() {
            $("#validateerrorvalue").removeClass('alert alert-error');  
            $("#validateerrorvalue").text('').show(); 
            $("#txtUsernameError").removeClass('control-group error');
            $("#txtPasswordError").removeClass('control-group error');	 
        });
        $("#txtPassword").bind("keypress keyup focus", function() {
            $("#validateerrorvalue").removeClass('alert alert-error');  
            $("#validateerrorvalue").text('').show(); 
            $("#txtUsernameError").removeClass('control-group error');
            $("#txtPasswordError").removeClass('control-group error');	 
        });
        var username = $("#txtUsername").val();
        var password = escape($("#txtPassword").val());
        if(username == ""  ||  password == "")
        {
            $("#txtUsernameError").addClass('control-group error');
            $("#txtPasswordError").addClass('control-group error');
            $("#validateerrorvalue").addClass('alert alert-error').text('Please enter both username and password').show();
            return false;
        } else {
            $.support.cors = true;
            $.ajax({
                url: BACKENDURL +"login/validate_login",
                type: "post",
                data:  {
                    username : username,
                    password : password
                },
                dataType: "json",
                crossDomain: true,
                success: function(data){
                    if(data.error) {
                        $("#txtUsernameError").addClass('control-group error');
                        $("#txtPasswordError").addClass('control-group error');
                        if(!data.auth)
                            $("#validateerrorvalue").addClass('alert alert-error').text('Invalid Credentials').show();                        
                        else if (!data.access)
                            $("#validateerrorvalue").addClass('alert alert-error').text('Your access is disabled').show();
                        else if (!data.pauth)
                            $("#validateerrorvalue").addClass('alert alert-error').text('No access profile assigned to user').show();
							else if (!data.mauth)
                            $("#validateerrorvalue").addClass('alert alert-error').text('No Module assigned in profile to user.').show();
						else
							 $("#validateerrorvalue").addClass('alert alert-error').text(" Something went wrong with your request. Please try again and if it still doesn't work ask your administrator for help").show();
                             return false;	
                        
                    } else {
                        if(localStorage){                    
                            localStorage["SESSIONID"] 	= data.session_id;
                            localStorage["USERID"] 	= data.user_info.user_id;
                            localStorage["USERNAME"] 	= data.user_info.username;							
                            localStorage["ROLENAME"] 	= data.user_info.role_name;
                            localStorage["ROLEID"] 	= data.user_info.role_id;
                            localStorage["FNAME"]       = data.user_info.first_name;
                            localStorage["LNAME"]       = data.user_info.last_name;
                            localStorage["TELEPHONE"]   = data.user_info.telephone;
                            localStorage["EMAIL"]       = data.user_info.user_email;
                            localStorage["TITLE"]       = data.user_info.title;
                            localStorage["CUSTOMERID"]  = data.user_info.customer_id;
                            localStorage["CUSTOMERNAME"]= data.user_info.customer_name;
                            localStorage["contractid"]	= data.user_info.contract_id;
                            localStorage["contractkey"]	= data.user_info.contract_key;
                            localStorage["vat"]		= data.user_info.vat;
                            localStorage["CONTRACTNAME"]= data.user_info.contract_name;
                            localStorage["WorkTel"] 	= data.user_info.work_telephone;
                            localStorage["MobileTel"] 	= data.user_info.mobile_number;
                            localStorage["ChkEmail"]	= data.user_info.mail_notification;	
                            localStorage["ChkSms"]	= data.user_info.sms_notification;	
                            localStorage['default_mod'] = data.user_info.default_mod;
                            localStorage['sch_adm']     = (data.user_info.sch_adm == true) ? "1" : "0"; 
                        }
                        // local store save all values
                        if(data.chg_pwd == true)
                        {
                            location.href="fpchange.html";
                        }
                        else if(data.chg_pwd == false)
                        {                        
							if(data.user_info.role_name== "Super Admin")
                                location.href="superadmin.html";
                            else if(data.user_info.role_name == "User") {                            
                                localStorage.setItem("SkinInfo", JSON.stringify(data.skin_info));
                                var dynCSS = JSON.parse(localStorage.getItem("SkinInfo"));
                                localStorage["skinId"] = dynCSS.sid;
                                if (L2_menu[data.user_info.default_mod] != undefined) {
									location.href=L2_menu[data.user_info.default_mod];//"userpage.html";
									}
								else { // modify the error msg
									$("#validateerrorvalue").addClass('alert alert-error').text('No Module assigned in profile to user.').show();
									return false;
									}
							}
                            else if (data.user_info.role_name=="Customer Admin") {
                                localStorage["caprofile_status"] = data.profiles.profile_status;
                                localStorage["caprofile"] = JSON.stringify(data.profiles.profile_res);
                                localStorage["cacontract_status"]  = data.profiles.cr_con;
                                location.href="contractadmin.html";
                            } else {
							 $("#validateerrorvalue").addClass('alert alert-error').text(" Something went wrong with your request. Please try again and if it still doesn't work ask your administrator for help").show();
                             return false;
							}
                        } else {
							 $("#validateerrorvalue").addClass('alert alert-error').text(" Something went wrong with your request. Please try again and if it still doesn't work ask your administrator for help").show();
                             return false;
                        }
                    }
                },
                error:function(xhr, textStatus, error){
                    alert(error);
                }   
            });
        }
    });
}




 
$("#btnForgotLogin").click(function(){
    location.href="index.html";
});

$('input[id^="txtforgetUserId"]').bind("keypress keyup", function () {
    $("#AddPupilFooterLabel").removeClass('alert alert-error')			
    $("#AddPupilFooterLabel").text('').show();
});
	  

$("#btnNewAddPupilAdminSubmit").click(function(){
 
    $("#frmForgotPassword").validate({
		
        rules: {			
            txtforgetUserId:{
                regEmail2:true,
                required:true						
            }		
        },
        messages: {			
            txtforgetUserId:{
                required: "Please enter a username"
            }			
        },
        submitHandler: function(form) {
            form.submit();
        },
        errorClass: "help-inline",
        errorElement: "span",
        highlight:function(element, errorClass, validClass){
            $(element).parents('.control-group').addClass('error')
            return false;
        },
        unhighlight: function(element, errorClass, validClass){
            $(element).parents('.control-group').removeClass('error')
            return false;
        },
        errorPlacement: function (error, element) { 
            error.insertAfter(element).css("margin-bottom","10px");
            return false;
        }
    });
    var username=$("#txtforgetUserId").val();	
    if ($("#frmForgotPassword").valid()) {
$("#spnForgotPswd", "#divForgerPwd").show();
$("#btnNewAddPupilAdminSubmit").attr("disabled", true);
$("#btnNewAddPupilAdminClose").attr("disabled", true); 	
        $.ajax({
            url: BACKENDURL +"common/forgot_password",
            type: "post",
            data:  {
                username : username         
            },
            dataType: "json",
            crossDomain: true,
            success: function(data){		
                if(data.error) {	
                    if(data.error_msg=="Unable to send email")
                    {
                        $("#AddPupilFooterLabel").addClass('alert alert-error')			
                        $("#AddPupilFooterLabel").text("Unable to send email").show();
                    }
                    else if(data.error_msg=="Database query execution failed.")
                    {
                        $("#AddPupilFooterLabel").addClass('alert alert-error')			
                        $("#AddPupilFooterLabel").text("Database query execution failed.").show();
                    }
                    else if(data.error_msg=="Some input data missing.")
                    {
                        $("#AddPupilFooterLabel").addClass('alert alert-error')			
                        $("#AddPupilFooterLabel").text("Some input data missing.").show();
                    }
                    else if(data.error_msg=="Account not found.")
                    {
                        $("#AddPupilFooterLabel").removeClass('alert alert-error')			
                        $("#AddPupilFooterLabel").text('').show();
                        $("#divForgerPwd").modal('hide');
                        $("#divForgerPwdSuccess").modal('show');
                        $("#lblsuccesspassword").text("An email has been sent to the registered email address. Please note the username and registered email address may be different depending on your profile settings").show();
                    }
                }			
                else {
					$("#spnForgotPswd", "#divForgerPwd").hide();
                    $("#AddPupilFooterLabel").removeClass('alert alert-error')			
                    $("#AddPupilFooterLabel").text('').show();
                    $("#divForgerPwd").modal('hide');
                    $("#divForgerPwdSuccess").modal('show');
                    $("#lblsuccesspassword").text("An email has been sent to the registered email address. Please note the username and registered email address may be different depending on your profile settings").show();
                //$("#lblsuccesspassword").text('Success! A new password has been sent to '+username+'').show();            	
                }
            },
            error:function(xhr, textStatus, error){
                alert(error);
            }   
        });
    }
	 
});
 
$("#btnNewAddPupilAdminClose").click(function(){
    $("#frmForgotPassword").data('validator').resetForm(); 
    $(".error").removeClass("error"); 
    $("#AddPupilFooterLabel").removeClass('alert alert-error')	
    $("#AddPupilFooterLabel").text('').show();						
    $("#txtforgetUserId").val('');
});
$("#xNewCustomerAdmin").click(function(){
    $("#frmForgotPassword").data('validator').resetForm(); 
    $(".error").removeClass("error"); 
    $("#AddPupilFooterLabel").removeClass('alert alert-error')	
    $("#AddPupilFooterLabel").text('').show();						
    $("#txtforgetUserId").val('');
});

				
				
			
				
				
				

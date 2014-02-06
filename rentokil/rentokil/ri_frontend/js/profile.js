var dataerror;
$(document).ready(function() {
    $.support.cors = true;
    $("#title").empty();  
    $("#fname").empty(); 
    $("#lname").empty(); 
    $("#email").empty(); 
    $("#selectTitle").empty();
    $("#telephone").empty();
    $("#WorkTel").empty();
    $("#Mobile").empty();	
    $("#divWelcome").html("Welcome " +localStorage["FNAME"]+ " " +localStorage["LNAME"]);
    $("#divLoggedAsCustomerAdmin").html("Logged in to Customer " + localStorage["CUSTOMERNAME"]);
    $("#title").append((localStorage["TITLE"] == "null") ? "-" : localStorage["TITLE"]);
    $("#fname").append((localStorage["FNAME"] == "undefined") ? "-" : localStorage["FNAME"]);						
    $("#lname").append((localStorage["LNAME"] == "undefined") ? "-" : localStorage["LNAME"]);
    $("#email").append((localStorage["EMAIL"] == "undefined") ? "-" : localStorage["EMAIL"]);	   
    $("#telephone").append((localStorage["TELEPHONE"] == "null") ? "-" : localStorage["TELEPHONE"]);
    $("#WorkTel").append((localStorage["WorkTel"] == "null") ? "-" : localStorage["WorkTel"]);
    $("#Mobile").append((localStorage["MobileTel"] == "null") ? "-" : localStorage["MobileTel"]);
});

function profileEdit(){
    $("#ProfileModalBoxSubmitBtnLabel").empty();
    $("#txtCustomerAdminNewFName").css("border", "");
    $("#txtCustomerAdminNewLName").css("border", "");
    $("#txtCustomerAdminNewemail").css("border", "");
    $("#txtCustomerAdminNewCemail").css("border", "");
    LoadProfileMaster_Admin();
    $("#txtCustomerAdminOldpwd","#frmUpdateAdminHomeProfile").keypress(function() {
        $("#txtCustomerAdminnewpwd","#frmUpdateAdminHomeProfile").removeAttr("disabled");
        $("#txtCustomerAdminrenternewpwd","#frmUpdateAdminHomeProfile").removeAttr("disabled");
    });
    $("#txtCustomerAdminOldpwd","#frmUpdateAdminHomeProfile").keyup(function() {
        if($("#txtCustomerAdminOldpwd","#frmUpdateAdminHomeProfile").val().length == 0)
        {
            $("#txtCustomerAdminnewpwd","#frmUpdateAdminHomeProfile").attr('disabled', 'disabled');
            $("#txtCustomerAdminrenternewpwd","#frmUpdateAdminHomeProfile").attr('disabled', 'disabled');
        }
    });
    $("#txtCustomerAdminnewpwd","#frmUpdateAdminHomeProfile").keypress(function() {
        //$("#pwdStrengthify").addClass("span3 offset2");
        $("#pwdStrengthify").css("display","inline-block");
    });
    $('input[id=txtCustomerAdminnewpwd]',"#frmUpdateAdminHomeProfile").passStrengthify();
    $.ajax({
        url: BACKENDURL +"customeradmin/get_user_titles",
        type: "post",
        data:  {			
            session_id: localStorage["SESSIONID"]
        },
        dataType: "json",
        crossDomain: true,
        success: function(data){
            if(data.session_status) {
                if(data.error == 0){ // session true error false
                    $("#selectTitle").empty();
                    var titleId;
                    var titleVal;				   
                    for(var nCount = 0; nCount < data.user_titles_res.length; nCount++) { 
                        var selectedText = (data.user_titles_res[nCount].data_value == localStorage["TITLE"]) ? "selected" : "";
                        titleVal = "<option value=" +data.user_titles_res[nCount].data_value_id +" " + selectedText+" >"+ data.user_titles_res[nCount].data_value +"</option>";
                        $("#selectTitle").append(titleVal);  
                    }
                    $("#txtCustomerAdminNewFName").val(localStorage["FNAME"]);
                    $("#txtCustomerAdminNewLName").val(localStorage["LNAME"]);
                    $("#txtCustomerAdminNewemail").val(localStorage["EMAIL"]);
                    $("#txtCustomerAdminNewCemail").val((localStorage["TELEPHONE"] == "null") ? "" : localStorage["TELEPHONE"]);
                    $("#txtCustomerAdminWorkTelephone").val((localStorage["WorkTel"] == "null") ? "" : localStorage["WorkTel"]);
                    $("#txtCustomerAdminMobileNumber").val((localStorage["MobileTel"] == "null") ? "" : localStorage["MobileTel"]);
                    if(localStorage["ChkEmail"] == 1)
                        $("#chkCustomerAdminEmail","#divProfileEdit").attr('checked',true);
                    else
                        $("#chkCustomerAdminEmail","#divProfileEdit").attr('checked',false);
                    if(localStorage["ChkSms"] == 1)
                        $("#chkCustomerAdminSms","#divProfileEdit").attr('checked',true);
                    else
                        $("#chkCustomerAdminSms","#divProfileEdit").attr('checked',false);
                } 
                else{// session true error true
                    alert (data.error_msg);
                }
            }
            else {
                localStorage.clear();
            }
        },
        error:function(xhr, textStatus, error){
            alert(error);
        }   
    });
}
function clearContactDetails(){
    //location.reload();
    $("#txtCustomerAdminOldpwd", "#divProfileEdit").val("");
    $("#txtCustomerAdminnewpwd", "#divProfileEdit").val("");
    $("#txtCustomerAdminrenternewpwd", "#divProfileEdit").val("");
    $("#pwdStrengthify").css("display","none");
    $("#updateProfileLabelContract").text("");
    $("#txtCustomerAdminnewpwd","#frmUpdateAdminHomeProfile").attr('disabled', 'disabled');
    $("#txtCustomerAdminrenternewpwd","#frmUpdateAdminHomeProfile").attr('disabled', 'disabled');
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
//updating profile
//$("#btnNewCustomerAdminSubmit", " #divProfileEdit").click(function(){
function updateProfile(){
    if($("#txtCustomerAdminOldpwd", "#divProfileEdit").val() != "")
    {
        if($("#txtCustomerAdminnewpwd", "#divProfileEdit").val() == "")
        {
            $("#updateProfileLabelContract").text("Please enter new password");
            return false;
        }
        else
        {
            $("#updateProfileLabelContract").text("");
        }
    }
    if(($("#txtCustomerAdminOldpwd", "#divProfileEdit").val() == $("#txtCustomerAdminnewpwd", "#divProfileEdit").val()) && ($("#txtCustomerAdminOldpwd", "#divProfileEdit").val() != ""))
    {
        $("#updateProfileLabelContract").text("New password can not be the same as the old password");
        return false;
    }
    if($("#frmUpdateAdminHomeProfile").valid())
    {
        $.ajax({
            url: BACKENDURL +"common/edit_profile",
            type: "post",
            data:  {
                session_id	: 	localStorage["SESSIONID"],
                user_id		: 	localStorage["USERID"],
                title_id	: 	$("#selectTitle").val(),
                title		:	$("#selectTitle :selected", "#divProfileEdit").text(),
                first_name	: 	$("#txtCustomerAdminNewFName").val(),
                last_name	:	$("#txtCustomerAdminNewLName").val(),
                user_email	:	$("#txtCustomerAdminNewemail").val(),
                telephone	:	$("#txtCustomerAdminNewCemail").val(),
                work_tel 	: 	$("#txtCustomerAdminWorkTelephone", "#divProfileEdit").val(),
                mobile_tel 	: 	$("#txtCustomerAdminMobileNumber", "#divProfileEdit").val(),
                current_pwd :	escape($("#txtCustomerAdminOldpwd", "#divProfileEdit").val()),
                new_pwd 	:	escape($("#txtCustomerAdminnewpwd", "#divProfileEdit").val()),
                renew_pwd 	:	escape($("#txtCustomerAdminrenternewpwd", "#divProfileEdit").val()),
                check_email :	($("#chkCustomerAdminEmail","#divProfileEdit").is(":checked")?1:0),
                check_sms 	:	($("#chkCustomerAdminSms","#divProfileEdit").is(":checked")?1:0)
            },
            dataType: "json",
            crossDomain: true,
            success: function(data){
                if(data.session_status) {
                    if(data.error == 0){ // session true error false
                        localStorage["FNAME"]  = $("#txtCustomerAdminNewFName ", "#divProfileEdit").val();
                        localStorage["LNAME"]  = $("#txtCustomerAdminNewLName ", "#divProfileEdit").val();
                        localStorage["EMAIL"]  = $("#txtCustomerAdminNewemail ", "#divProfileEdit").val();
                        localStorage["TITLE"]  = $("#selectTitle :selected", "#divProfileEdit").text();
                        localStorage["TELEPHONE"] = $("#txtCustomerAdminNewCemail", "#divProfileEdit").val();
                        localStorage["WorkTel"] 	= $("#txtCustomerAdminWorkTelephone", "#divProfileEdit").val();
                        localStorage["MobileTel"] 	= $("#txtCustomerAdminMobileNumber", "#divProfileEdit").val();
                        localStorage["ChkEmail"] 	=($("#chkCustomerAdminEmail","#divProfileEdit").is(":checked")?1:0);
                        localStorage["ChkSms"] 		=($("#chkCustomerAdminSms","#divProfileEdit").is(":checked")?1:0);
                        $("#fname").empty();
                        $("#lname").empty();
                        $("#email").empty();
                        $("#title").empty();
                        $("#telephone").empty();
                        $("#WorkTel").empty();
                        $("#Mobile").empty();
                        $("#fname").append(localStorage["FNAME"]); 
                        $("#lname").append(localStorage["LNAME"]);
                        $("#email").append(localStorage["EMAIL"]);
                        $("#title").append(localStorage["TITLE"]);
                        $("#telephone").append((localStorage["TELEPHONE"] == "") ? "-" : localStorage["TELEPHONE"]); 
                        $("#WorkTel").append((localStorage["WorkTel"] == "") ? "-" : localStorage["WorkTel"]);
                        $("#Mobile").append((localStorage["MobileTel"] == "") ? "-" : localStorage["MobileTel"]);
                        $("#divWelcome").html("Welcome " +localStorage["FNAME"]+" " +localStorage["LNAME"]);
                        $("#pwdStrengthify").css("display","none");
                        $("#updateProfileLabelContract").text("");
                    } 
                    else{
                        $("#txtCustomerAdminOldpwd", "#divProfileEdit").val("");
                        $("#txtCustomerAdminnewpwd", "#divProfileEdit").val("");
                        $("#txtCustomerAdminrenternewpwd", "#divProfileEdit").val("");
                        $("#pwdStrengthify").css("display","none");
                        $("#updateProfileLabelContract").text("");
                        $("#txtCustomerAdminnewpwd","#frmUpdateAdminHomeProfile").attr('disabled', 'disabled');
                        $("#txtCustomerAdminrenternewpwd","#frmUpdateAdminHomeProfile").attr('disabled', 'disabled');
                        $("#editBtnClose").load("commonFailuremodal.html", function () {
                            $("#headermsg").html("Update settings");
                            $("#sucmsg").html("Failure," + data.error_msg);
                        });
                    //alert (data.error_msg);
                    //alert (data.error);
                    }
                }
                else {
                    localStorage.clear();
                }
            },
            error:function(xhr, textStatus, error){
                alert(error);
            }   
        });
    }else
        return false;
}
function LoadProfileMaster_Admin()
{
    $("#frmUpdateAdminHomeProfile").validate({
        rules: {
            txtCustomerAdminNewFName:{
                regAlphaNumeric:true, 
                required: true
            },
            txtCustomerAdminNewLName:{
                regAlphaNumeric:true, 
                required: true
            },
            txtCustomerAdminNewemail:{
                regEmail:true, 
                required: true
            },
            txtCustomerAdminNewCemail:{
                regTelephone:true
            },
            txtCustomerAdminWorkTelephone:{
                regTelephone:true
            },
            txtCustomerAdminMobileNumber:{
                regTelephone:true
            },
            txtCustomerAdminnewpwd:{
                regPwd:true
            },
            txtCustomerAdminrenternewpwd:{
                equalTo: "#txtCustomerAdminnewpwd"
            }
        },
        messages: {
            txtCustomerAdminNewFName:{
                required: "Please enter the first name"
            },
            txtCustomerAdminNewLName:{
                required: "Please enter the last name"
            },
            txtCustomerAdminNewemail:{
                required: "Please enter valid email ID"
            },
            txtCustomerAdminrenternewpwd:{
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
 
function LoadPageData(){
    //Check the session exists
    //Get the customer data from the database.
    $("#spnContractName").html(localStorage["CUSTOMERNAME"]);
    // Loading the users with the user list fr contract ...
    (localStorage["cacontract_status"] == 0)?$("#btnAddContract").attr({"disabled":true, "href": "#"}):$("#btnAddContract").attr("disabled",false);
    populatecontract();
    $("#btnGoContract").bind("click",btnGoContract);
    $("#btnNewUser").bind("click",btnNewUser);
    $("#btnAddContract").bind("click",btnAddContract);
    $("#btnNewContractSubmit", "#divAddContract").bind("click",btnNewContractSubmit);
    //$("#newContractBtn").bind("click",newContractBtn);
    $("#btnProfileEdit").bind("click",profileEdit);
		
    $("#btnNewCustomerAdminSubmit", " #divProfileEdit").bind("click",updateProfile);
    $("#xNewCustomerAdmin","#divProfileEdit").bind("click",clearContactDetails);
    $("#xcloseCustomerAdmin","#divProfileEdit").bind("click",clearContactDetails);
    $("#title").empty();  
    $("#fname").empty(); 
    $("#lname").empty(); 
    $("#email").empty(); 
    $("#selectTitle").empty();
    $("#telephone").empty();
    $("#WorkTel").empty();
    $("#Mobile").empty();		
    $("#title").append((localStorage["TITLE"] == "null") ? "-" : localStorage["TITLE"]);
    $("#fname").append((localStorage["FNAME"] == "undefined") ? "-" : localStorage["FNAME"]);						
    $("#lname").append((localStorage["LNAME"] == "undefined") ? "-" : localStorage["LNAME"]);
    $("#email").append((localStorage["EMAIL"] == "undefined") ? "-" : localStorage["EMAIL"]);	   
    $("#telephone").append((localStorage["TELEPHONE"] == "null") ? "-" : localStorage["TELEPHONE"]);
    $("#WorkTel").append((localStorage["WorkTel"] == "null") ? "-" : localStorage["WorkTel"]);
    $("#Mobile").append((localStorage["MobileTel"] == "null") ? "-" : localStorage["MobileTel"]);	
	
}
$("#xNewCustomerAdmin").click(function(){
    $("#AddPupilFooterLabel").removeClass('alert alert-error')			
    $("#AddPupilFooterLabel").text('').show();
    $("#txtforgetUserId").val('')
    $("#frmForgotPassword").data('validator').resetForm(); 
    $(".error").removeClass("error"); 
});
$("#btnNewAddPupilAdminClose").click(function(){
    $("#AddPupilFooterLabel").removeClass('alert alert-error')			
    $("#AddPupilFooterLabel").text('').show();
    /* $("#spandynamictag").text('') */
    $("#txtforgetUserId").val('')
    $("#frmForgotPassword").data('validator').resetForm(); 
    $(".error").removeClass("error"); 
});
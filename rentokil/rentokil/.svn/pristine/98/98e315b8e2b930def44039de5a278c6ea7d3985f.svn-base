var regPupilId = /^\w{3}\/\w{6}$/;
var regEmail =/^(([A-Za-z0-9]+_)|([A-Za-z0-9]+\.)|([A-Za-z0-9]+\+))*[A-Za-z0-9]+@((\w+\-+)|(\w+\.))*\w{1,63}\.[a-zA-Z]{2,6}$/;
var regAlphaNumeric = /^[A-Za-z0-9 _.-]*[A-Za-z0-9][A-Za-z0-9 _.-]*$/i;
$(document).ready(function()
{
    $.support.cors = true;
    $("#btnRegister").click(function() {
        $('#chkAgree').css('border-color', '#cccccc');
        $("#txtFirstName").css('border-color', '#cccccc');
        $("#txtLastName").css('border-color', '#cccccc');
        $('#txtEmail').css('border-color', '#cccccc');
        $('#txtEmail').css('border-color', '#cccccc');
        $('#txtPupilId').css('border-color', '#cccccc');
        $("#lblFirstName").css('color', '#333333');
        $("#lblLastName").css('color', '#333333');
        $("#lblEmail").css('color', '#333333');
        $("#lblpupilid").css('color', '#333333');
        $("#chkAgree1").css('color', '#333333');
        var FirstName = $("#txtFirstName").val();
        var LastName = $("#txtLastName").val();
        var Email = $("#txtEmail").val();
        var pupilId = $("#txtPupilId").val();
		
		$("#txtFirstName").bind("keypress keyup focus", function() {
		$("#lblFirstName").css('color', '#333333');
		$('#txtFirstName').css('border-color', '#cccccc');
       $("#InvalidRegisterLabel").removeClass('alert alert-error');  
	 $("#InvalidRegisterLabel").text('').show();   
    });
	$("#txtLastName").bind("keypress keyup focus", function() {
	$("#lblLastName").css('color', '#333333');
	$('#txtLastName').css('border-color', '#cccccc');
       $("#InvalidRegisterLabel").removeClass('alert alert-error');  
	 $("#InvalidRegisterLabel").text('').show();   
    });
	$("#txtEmail").bind("keypress keyup focus", function() {
	$("#lblEmail").css('color', '#333333');
	$('#txtEmail').css('border-color', '#cccccc');
       $("#InvalidRegisterLabel").removeClass('alert alert-error');  
	 $("#InvalidRegisterLabel").text('').show();   
    });
	$("#txtPupilId").bind("keypress keyup focus", function() {
	$("#lblpupilid").css('color', '#333333');
	$('#txtPupilId').css('border-color', '#cccccc');
     $("#InvalidRegisterLabel").removeClass('alert alert-error');  
	 $("#InvalidRegisterLabel").text('').show();   
    });
	$("#chkAgree").change(function() {
	$("#chkAgree1").css('color', '#333333');
	//$('#txtEmail').css('border-color', '#cccccc');
      $("#InvalidRegisterLabel").removeClass('alert alert-error');  
	  $("#InvalidRegisterLabel").text('').show();     

    });
        if (FirstName == "" || LastName == "" || Email == "" || pupilId == "")
        {
            $("#InvalidRegisterLabel").addClass('alert alert-error').text("Please complete all fields").show();
            return false;
        }
        if (!regAlphaNumeric.test($("#txtFirstName").val()))
        {
            $("#InvalidRegisterLabel").addClass('alert alert-error').text("Special characters are not allowed").show();
            $("#lblFirstName").css('color', '#b94a48');
            $('#txtFirstName').css('border-color', '#b94a48');
            return false;
        }
        if (!regAlphaNumeric.test($("#txtLastName").val()))
        {
            $("#InvalidRegisterLabel").addClass('alert alert-error').text("Special characters are not allowed").show();
            $("#lblLastName").css('color', '#b94a48');
            $('#txtLastName').css('border-color', '#b94a48');
            return false;
        }
        if (!regEmail.test($("#txtEmail").val()))
        {
            $("#InvalidRegisterLabel").addClass('alert alert-error').text("Please enter a valid email address").show();
            $("#lblEmail").css('color', '#b94a48');
            $('#txtEmail').css('border-color', '#b94a48');
            return false;      	
        }
        if (!regPupilId.test($('#txtPupilId').val()))
        {
            $("#InvalidRegisterLabel").addClass('alert alert-error').text("Please enter a valid Pupil ID").show();
            $("#lblpupilid").css('color', '#b94a48');
            $('#txtPupilId').css('border-color', '#b94a48');
            return false;
        }
        if (!$('#chkAgree').is(':checked'))
        {
            $("#InvalidRegisterLabel").addClass('alert alert-error').text("Please read and agree to the T&C's").show();
            $('#chkAgree1').css('color', '#b94a48');
            return false;
        }

        $("#btnRegister").attr("disabled", "disabled");
        $("#spnGenRegister").show();
        $.support.cors = true;
        $.ajax({
            url: BACKENDURL + "common/parent_validation",
            type: "post",
            data: {
                firstName: FirstName,
                lastName: LastName,
                emailAddress: Email,
                sampleId: pupilId
            },
            dataType: "json",
            crossDomain: true,
            success: function(data)
            {
                msg = data.error_msg;
                if (data.error)
                {
                    $("#InvalidRegisterLabel").removeClass('alert alert-error').text = '';
                    $("#InvalidRegisterLabel").addClass('alert alert-error').text(msg).show();
                    $("#btnRegister").removeAttr("disabled");
                    $("#spnGenRegister").hide();
                }
                else
                {
                    $("#editBtnSuccessClose").modal('show');
                }
            },
            error: function(xhr, textStatus, error) {
                alert(error);
            }
        });
    });
});
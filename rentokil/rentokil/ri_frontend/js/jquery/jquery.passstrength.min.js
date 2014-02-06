(function($){  
  $.fn.passStrengthify = function(options) {
	$('#pwdStrengthify').css('display', 'none');
	$('#txtnewpwd').keyup(function(){
		$('#pwdStrengthify').html(checkStrength($('#txtnewpwd').val()))
	});	
	$('#txtAdminnewpwd').keyup(function(){
		$('#pwdStrengthify').html(checkStrength($('#txtAdminnewpwd').val()))
	});
	$('#txtCustomerAdminnewpwd').keyup(function(){
		$('#pwdStrengthify').html(checkStrength($('#txtCustomerAdminnewpwd').val()))
	});
	$('#txtfpchangenewpwd').keyup(function(){
		$('#pwdStrengthify').html(checkStrength($('#txtfpchangenewpwd').val()))
	});
	function checkStrength(password){
    
	//initial strength
    var strength = 0
	
	//if password contains lower characters, increase strength value
	if (password.match(/([a-z])/))  strength = 1
	if (password.match(/([A-Z])/))  strength = 1
	if (password.match(/([!,%,&,@,#,$,^,*,?,_,~])/)) strength = 1
	if (password.match(/([0-9])/)) strength = 1
	
	//if password contains both lower and uppercase characters, increase strength value
	if (password.match(/([a-z].*[A-Z])|([A-Z].*[a-z])/))  strength = 2
	
	//if it has numbers and characters, increase strength value
	if (password.match(/([a-zA-Z])/) && password.match(/([0-9])/))  strength = 3
	
	//if it has one special character, increase strength value
    if (password.match(/([!,%,&,@,#,$,^,*,?,_,~])/) && password.match(/([a-zA-Z])/) && password.match(/(?=^.{6,16}$)/))  strength = 4
	
	//if it has two special characters, increase strength value
    if (password.match(/(.*[!,%,&,@,#,$,^,*,?,_,~].*[!,%,&,@,#,$,^,*,?,_,~])/) && password.match(/([0-9])/) && password.match(/(?=^.{6,16}$)/))  strength = 4
	
	// if it has password matching chars 
	if(password.match(/(?=^.{6,16}$)(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[\W_])(?=^.*[^\s].*$).*$/)) strength =5
	
	if(password.match(/(?=^.{8,16}$)(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[\W_])(?=^.*[^\s].*$).*$/)) strength =6
	
	//now we have calculated strength value, we can return messages
	//if value is less than 2
	for(j=1;j<8;j++)
	{
		$("#pwdbar" +j).css('background-color','gray');
		$('#pwdquality').text("");
		$('#pwdStrengthify').css('display', 'none');
	}
	if (strength == 0 ) {
		$('#pwdStrengthify').css('display', 'none');
	}
	else if (strength < 2 ) {
		$('#pwdStrengthify').css('display', 'inline-block');
		$('#pwdquality').css('color','rgb(192,0,0)');
		$('#pwdquality').text("Weak");
		for(i=1;i<3;i++)
		{
			$("#pwdbar" +i).css('background-color','rgb(192,0,0)');
		}
	}
	else if (strength < 3 ) {
		$('#pwdStrengthify').css('display', 'inline-block');
		$('#pwdquality').css('color','orange');
		$('#pwdquality').text("Moderate");
		for(i=1;i<4;i++)
		{
			$("#pwdbar" +i).css('background-color','orange');
		}
	} else if (strength <= 4 ) {
		$('#pwdStrengthify').css('display', 'inline-block');
		$('#pwdquality').css('color','rgb(0,153,255)');
		$('#pwdquality').text("Good");
		for(i=1;i<5;i++)
		{
			$("#pwdbar" +i).css('background-color','rgb(0,153,255)');
		}
	} else if (strength <= 5 ){
		$('#pwdStrengthify').css('display', 'inline-block');
		$('#pwdquality').css('color','blue');
		$('#pwdquality').text("Strong");
		for(i=1;i<6;i++)
		{
			$("#pwdbar" +i).css('background-color','blue');
		}
	}else if (strength <= 6 ){
		$('#pwdStrengthify').css('display', 'inline-block');
		$('#pwdquality').css('color','green');
		$('#pwdquality').text("Very Strong");
		for(i=1;i<8;i++)
		{
			$("#pwdbar" +i).css('background-color','green');
		}
	}
}
};
})(jQuery);
$(document).ready(function() {
    $.support.cors = true;
    $("#divSuperLoggedAs").html(" You are administering " + localStorage["CUSTOMERNAME"]);
    //  $("#divcontainer").load(loadFileName+"?x="+(new Date()).getTime(), function () {
    $("#divcontainer").load(loadFileName, function() {
        $("#caheader").load("saheader.html", function() {
            $('#btnLogout').bind("click", logout);
            $("#btnManageCustomers").click(function() {
                $("#CreateNewCustomer").removeAttr('style');
                $("#SelectOrCreateContractNewLabel").removeClass('alert alert-error')			
                $("#SelectOrCreateContractNewLabel").text('').show();
                $("#CreateNewCustomer").css("border", "");
                $("#CreateNewCustomer").val('');
                // $("#SelectOrCreateContractNewLabel").text(''); 
                $.ajax({
                    url: BACKENDURL +"superadmin/get_customers",
                    type: "post",
                    data: {
                        session_id: localStorage["SESSIONID"]
                    },
                    dataType: "json",
                    crossDomain: true,
                    success: function(data) {
                        if (data.session_status) {
                            if (data.error == 0) { // session true error false
                                $("#selectSuperCustomer").empty();
                                if (data.customers_res.length > 0)
                                    $("#divSelectCustomers").show();

                                for (var nCount = 0; nCount < data.customers_res.length; nCount++) {
                                    contractVal = "<option value='" + data.customers_res[nCount].customer_id + "'>" + data.customers_res[nCount].customer_name + "</option>";
                                    $("#selectSuperCustomer").append(contractVal);
                                }		
                                $("#btnSuperadminGo").bind("click", GoSuperbtn);
                                $("#CreateCustomerbtnCreate").bind("click", createCustomerBtn);
                            } else {
                        //alert(error);
                        }
                        } else {
                            localStorage.clear();
                        }
                    },
                    error: function(xhr, textStatus, error) {
                    //alert(error);
                    }
                });
            });

                
            //Loading the sub page
            $("#selectCustomers").load("samanagecontract.html");
            $("#cafooter").load("cafooter.html");
            $("#divSuperWelcome").html("Welcome " + localStorage["FNAME"] + "  " + localStorage["LNAME"]);
            $("#divSuperLoggedAs").html(" You are administering " + localStorage["CUSTOMERNAME"]);
        })
        //This is for specific to page and need to define in every page.
        LoadPageData();
    });

    function GoSuperbtn() {
        localStorage.setItem("CUSTOMERID", " ");
        var contArr = $("#selectSuperCustomer").val();	
        localStorage.setItem("CUSTOMERID", contArr);   
        localStorage["CUSTOMERNAME"] = $("#selectSuperCustomer :selected").text();    
        $("#divSuperLoggedAs").html("You are administering " + $("#selectSuperCustomer :selected").text());   
        location.href = "sacustomeradmin.html";
    }


    function createCustomerBtn()
    {

        if ($("#CreateNewCustomer").val().trim() == "") {
            $("#CreateNewCustomer").focus();
            ModalFieldUI(CreateNewCustomer);		
            $("#SelectOrCreateContractNewLabel").addClass("alert alert-error").text("Please enter customer name");
            return false; 
        } else {
            $.ajax({
                url: BACKENDURL +"superadmin/create_customer",
                type: "post",
                data: {
                    session_id: localStorage["SESSIONID"],
                    customer_name: $("#CreateNewCustomer").val()               
                },
                dataType: "json",
                crossDomain: true,
                success: function(data) {
                    if (data.session_status) {
                        if (data.error == 0) { // session true error 
                            $("#selectCustomers").modal('hide');
                            localStorage.setItem("CUSTOMERID", " ");						
                            localStorage.setItem("CUSTOMERID", data.customer_id); 			
                            localStorage["CUSTOMERNAME"] = $("#CreateNewCustomer").val();
                            //getSchool();                       
                            $("#divSuperLoggedAs").html("You are administering " + $("#CreateNewCustomer").val());
                            location.href = "sacustomeradmin.html";
                        } else {                        
                            $("#SelectOrCreateContractNewLabel").addClass("alert alert-error").text(data.error_msg)
						
                        }
                    } else {
                        var NewLabel = data.error_msg;
                        $("#SelectOrCreateContractNewLabel").text("*" + NewLabel);
                        $("#CreateNewCustomer").focus();
                        $("#CreateNewCustomer").each(function() {
                            ModalFieldUI(this);
                        });
                    }
                },
                error: function(xhr, textStatus, error) {
                    alert(error);
                }
            });
        }
    }

});

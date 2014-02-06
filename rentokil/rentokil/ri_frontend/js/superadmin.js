var customerdata;
//Document ready funciton...
$(document).ready(function() {
    $.support.cors = true;

    loadpage();

});
// Loading the page with the customer details...
function loadpage() {
    $("#btnGoCustomer").bind("click", btnGoCustomer);
    $.ajax({
        url: BACKENDURL + "superadmin/get_customers",
        type: "post",
        //contentType: "text/xml; charset=utf-8",
        data: {
            session_id: localStorage["SESSIONID"]
        },
        dataType: "json",
        crossDomain: true,
        success: function(data) {
            if (data.session_status) {
                if (data.error == 0) {
                    $("#selectCustomer").empty();
                    var contractId, contractVal;
                    $("#divSuperWelcome").html("Welcome " + localStorage["FNAME"] + " " + localStorage["LNAME"]);
                    $("#divSuperLoggedAs").html("Logged in as super administrator");// + localStorage["ROLENAME"]);
                    if (data.customers_res.length > 0) {
                        for (var nCount = 0; nCount < data.customers_res.length; nCount++) {
                            contractVal = "<option value='" + data.customers_res[nCount].customer_id + "'>" + data.customers_res[nCount].customer_name + "</option>";
                            $("#selectCustomer").append(contractVal);
                        }
                        $("#divAddSelectMsg").html('Begin managing your customers by clicking the "Add Customer" button above or by selecting an existing customer from the drop down below.');
                        $("#divSelectCustomers").show();
                    } else{
                        $("#divAddSelectMsg").html('Begin managing your customers by clicking the "Add Customer" button above.');
                        $("#divSelectCustomers").hide();
                    }
                }
            } else {
                localStorage.clear();
                location.href = 'index.html'
            }
        },
        error: function(xhr, textStatus, error) {
            alert(error);
            //$("#result").html('there is error while submit');
        }
    });
}
function btnGoCustomer() {
    localStorage.setItem("CUSTOMERID", " ");
    var contArr = $("#selectCustomer").val();

    localStorage.setItem("CUSTOMERID", contArr);
    localStorage["CUSTOMERID"] = contArr;
    localStorage.setItem("CUSTOMERNAME", " ");
    localStorage.setItem("CUSTOMERNAME", $("#selectCustomer :selected").text());
    $("#divSuperLoggedAs").html("You are administering" + " " + localStorage["CUSTOMERNAME"])
    location.href = "sacustomeradmin.html";
}


//New customer modal click
$("#btnNewCustomer").click(function() {
    $("input[type='text']", "#divNewCustomer").each(function() {
        $(this).val('');
    });
});

//New customer modal key press.
$("#txtCustomerName", "#divNewCustomer").keypress(function(e) {
    if ((e.which && e.which == 13) || (e.keyCode && e.keyCode == 13)) {
        $("#btnNewCustomerSubmit", "#divNewCustomer").click();
        return false;
    } else
        return true;
});
$("#txtCustomerName").bind("keypress keyup", function() {
    $("#Customernameerror").removeClass('alert alert-error')
    $("#Customernameerror").text('').show();
});
$("#btnNewCustomer").click(function() {
    $("#frmCreateCustomer").validate({
        rules: {
            txtCustomerName: {
                required: true
            }
        },
        messages: {
            txtCustomerName: {required: "Please enter the Customer name"}
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
    $("#Customernameerror").removeClass('alert alert-error')
    $("#Customernameerror").text('').show();
    $("#txtCustomerName").val('')
    $("#frmCreateCustomer").data('validator').resetForm();
    $(".error").removeClass("error");
});
//New customer submit 
$("#btnNewCustomerSubmit", "#divNewCustomer").click(function() {
    localStorage.setItem("CUSTOMERNAME", " ");
    localStorage.setItem("CUSTOMERNAME", $("#txtCustomerName").val());
    $("#txtCustomerName").removeAttr('style');
    $("#Customernameerror").text(" ");

    if ($("#frmCreateCustomer").valid()) {
        //Make an Ajax call to save....
        $.ajax({
            url: BACKENDURL + "superadmin/create_customer",
            type: "post",
            //contentType: "text/xml; charset=utf-8",
            data: {
                session_id: localStorage["SESSIONID"],
                customer_name: $("#txtCustomerName", "#divNewCustomer").val()
            },
            dataType: "json",
            crossDomain: true,
            success: function(data) {
                if (data.session_status) {
                    if (data.error) {
                        $("#Customernameerror").addClass("alert alert-error").text(data.error_msg)
                    } else {
                        //close the modal 
                        //and repopulate the data
                        localStorage.setItem("CUSTOMERID", " ");
                        localStorage.setItem("CUSTOMERID", data.customer_id);
                        $("#xNewCustomer", "#divNewCustomer").click();
                        $("#lblSuccessHeading", "#divSuccess").html("Create a new customer");
                        $("#lblSuccessMessage", "#divSuccess").html("Success! New customer created.");
                        $("#btnCloseCust").bind("click", customerSubmitButton)
                        $("#btnSuccess").click();
                        loadpage();
                    }

                } else {
                    localStorage.clear();
                    location.href = 'index.html'
                }
            },
            error: function(xhr, textStatus, error) {
                alert(error);
                //$("#result").html('there is error while submit');
            }
        });
    }
});
function customerSubmitButton()
{
    location.href = "sacustomeradmin.html";
}

//New customer admin modal click
$("#btnNewCustomerAdmin").click(function() {
    $("input[type='text']", "#divNewCustomerAdmin").each(function() {
        $(this).val('');
    });
    $("#ddlCustomers", "#divNewCustomerAdmin").empty().append("<option value='0'>-- Select --</option>");
    //populate customers in ddlCustomers
    $.each(customerdata, function() {
        $("#ddlCustomers", "#divNewCustomerAdmin").append("<option value='" + this.customer_id + "'>" + this.customer_name + "</option>");
        //alert(this.customer_id + " " + this.customer_name);
    });
});

//New customer admin key press.
$("input[type='text']", "#divNewCustomerAdmin").keypress(function(e) {
    if ((e.which && e.which == 13) || (e.keyCode && e.keyCode == 13)) {
        $("#btnNewCustomerAdminSubmit", "#divNewCustomerAdmin").click();
        return false;
    } else
        return true;
});




//Edit customer modal click
function editCustomer(customerId, customerName) {
    $("#txtCustomerName", "#divEditCustomer").val(customerName);
    $("#hdnCustomerId", "#divEditCustomer").val(customerId);
    //Get customer admins and display in the modal...
    $.ajax({
        url: BACKENDURL + "superadmin/get_customer_admin",
        type: "post",
        //contentType: "text/xml; charset=utf-8",
        data: {
            session_id: localStorage["SESSIONID"],
            customer_id: customerId
        },
        dataType: "json",
        crossDomain: true,
        success: function(data) {
            if (data.session_status) {
                //display customers....
                var border = "border-top: 0px;";
                $("#tblCustomersAdmins tbody", "#divEditCustomer").children().remove();
                $.each(data.customer_admin_res, function() {
                    var disablebtn = "<a class='btn' data-toggle='modal' href='#' id='btnDisableCustomerAdmin' name='btnDisableCustomerAdmin' onclick='javascript:changeAdminStatus(this,\"D\"," + this.user_id + ")'>Disable</a>";
                    if (this.status == 0)
                        disablebtn = "<a class='btn btn-primary' data-toggle='modal' href='#' id='btnDisableCustomerAdmin' name='btnDisableCustomerAdmin' onclick='javascript:changeAdminStatus(this,\"E\"," + this.user_id + ")'>Enable</a>";
                    $("#tblCustomersAdmins  tbody:last", "#divEditCustomer").append("<tr><td style='color: #bc1718; font-size: 16px;" + border + "'><span style='font-size: 12px; color: #646464;'>" + this.first_name + " " + this.last_name + "</span></td><td>" + this.user_email + "</td><td style='text-align:right; " + border + "'>" + disablebtn + "</td></tr>");
                    border = "";
                    //alert(this.customer_id + " " + this.customer_name);
                });
            } else {
                localStorage.clear();
                location.href = 'index.html'
            }
        },
        error: function(xhr, textStatus, error) {
            alert(error);
            //$("#result").html('there is error while submit');
        }
    });
}


//Activate/inactiving customer admins
function changeAdminStatus(f, statusType, user_id) {
    var status = (statusType == 'D') ? 0 : 1;
    if ((status == 1 && confirm("Are you sure you want to Enable?")) ||
            (status == 0 && confirm("Are you sure you want to Disable?")))
    {
        $.ajax({
            url: BACKENDURL + "superadmin/update_customer_admin_status",
            type: "post",
            //contentType: "text/xml; charset=utf-8",
            data: {
                session_id: localStorage["SESSIONID"],
                customer_admin_id: user_id,
                status: status
            },
            dataType: "json",
            crossDomain: true,
            success: function(data) {
                if (data.session_status) {
                    if (data.error) {
                        alert(data.error_msg);
                    } else {
                        if (statusType == 'D')
                            $(f).text('Enable').addClass('btn-primary').attr('onclick', 'javascript:changeAdminStatus(this,"E",' + user_id + ')');
                        else
                            $(f).text('Disable').removeClass('btn-primary').attr('onclick', 'javascript:changeAdminStatus(this,"D",' + user_id + ')');
                    }
                } else {
                    localStorage.clear();
                    location.href = 'index.html'
                }
            },
            error: function(xhr, textStatus, error) {
                alert(error);
                //$("#result").html('there is error while submit');
            }
        });
    }
}

//Edit customer modal key press.
$("#txtCustomerName", "#divEditCustomer").keypress(function(e) {
    if ((e.which && e.which == 13) || (e.keyCode && e.keyCode == 13)) {
        $("#btnEditCustomerSubmit", "#divEditCustomer").click();
        return false;
    } else
        return true;
});

//Edit customer submit 
$("#btnEditCustomerSubmit", "#divEditCustomer").click(function() {
    if ($("#txtCustomerName", "#divEditCustomer").val() == "")
    {
        alert("Please Enter Customer Name!")
        $("#txtCustomerName", "#divEditCustomer").focus();
        return false;
    } else {
        //Update customer
        $.ajax({
            url: BACKENDURL + "superadmin/edit_customer",
            type: "post",
            //contentType: "text/xml; charset=utf-8",
            data: {
                session_id: localStorage["SESSIONID"],
                customer_id: $("#hdnCustomerId", "#divEditCustomer").val(),
                customer_name: $("#txtCustomerName", "#divEditCustomer").val()
            },
            dataType: "json",
            crossDomain: true,
            success: function(data) {
                if (data.session_status) {
                    $("#xEditCustomer", "#divEditCustomer").click();
                    $("#lblSuccessHeading", "#divSuccess").html("Edit a customer");
                    $("#lblSuccessMessage", "#divSuccess").html("Success! Customer details updated.");
                    $("#btnSuccess").click();
                    loadpage();
                    //alert(this.customer_id + " " + this.customer_name);
                } else {
                    localStorage.clear();
                    location.href = 'index.html'
                }
            },
            error: function(xhr, textStatus, error) {
                alert(error);
                //$("#result").html('there is error while submit');
            }
        });
    }

});

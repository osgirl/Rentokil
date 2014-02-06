$(document).ready(function() {
    var windowURL = window.location.toString();
    var key = windowURL.substring(windowURL.lastIndexOf('?key=') + 5)
    if (windowURL.indexOf('?key=') > 0)
    {
        if (windowURL.indexOf('riwebqa') > 0 || windowURL.indexOf('localhost') > 0 || windowURL.indexOf('fminsight.net') > 0) {
            $("#divcontainer").load("fpchange_fmbody.html", function() {
                document.title = "FM insight";
                $('head').append('<link rel="stylesheet" href="css/edfm-custom.css" type="text/css" />');
                $("#header-divider img").attr("src", "img/header-logininfo-divider.png");
                $('head').append('<link href="img/apple-touch-icon.png" rel="apple-touch-icon">');
                SrvChangePassword(key);

            });
        } else {
            $("#divcontainer").load("fpchange_epbody.html", function() {
                document.title = "Edenpay";
                $('head').append('<link rel="stylesheet" href="css/edfm-custom.css" type="text/css" />');
                $('head').append('<link rel="stylesheet" href="css/eden-setting.css" type="text/css" />');
                $("#header-divider img").attr("src", "img/eden-header-logininfo-divider.png");
                $('head').append('<link href="img/eden-apple-touch-icon.png" rel="apple-touch-icon">');
                SrvChangePassword(key);
            });
        }

    }
    else
    {
        if ((localStorage['SESSIONID'] == '') || (localStorage['SESSIONID'] == undefined))
        {
            location.href = "index.html";
        } else if (windowURL.indexOf('riwebqa') > 0 || windowURL.indexOf('localhost') > 0 || windowURL.indexOf('fminsight.net') > 0) {
            $("#divPasswordchangename").text("Welcome " + localStorage["FNAME"] + " " + localStorage["LNAME"]);
            $("#divpasswordlink").text("Not " + localStorage["FNAME"] + "?").attr("href", "index.html");
            $("#divusername").text(localStorage["USERNAME"]);
            $("#divcontainer").load("fpchange_fmbody.html", function() {
                document.title = "FM insight";
                $('head').append('<link rel="stylesheet" href="css/edfm-custom.css" type="text/css" />');
                $("#header-divider img").attr("src", "img/header-logininfo-divider.png");
                $('head').append('<link href="img/apple-touch-icon.png" rel="apple-touch-icon">');
                LoadPagecontrols();
            });
        }
        else {
            $("#divPasswordchangename").text("Welcome " + localStorage["FNAME"] + " " + localStorage["LNAME"]);
            $("#divpasswordlink").text("Not " + localStorage["FNAME"] + "?").attr("href", "index.html");
            $("#divusername").text(localStorage["USERNAME"]);
            $("#divcontainer").load("fpchange_epbody.html", function() {
                document.title = "Edenpay";
                $('head').append('<link rel="stylesheet" href="css/edfm-custom.css" type="text/css" />');
                $('head').append('<link rel="stylesheet" href="css/eden-setting.css" type="text/css" />');
                $("#header-divider img").attr("src", "img/eden-header-logininfo-divider.png");
                $('head').append('<link href="img/eden-apple-touch-icon.png" rel="apple-touch-icon">');
                LoadPagecontrols();
            });
        }
    }


});
function LoadPagecontrols() {
    $('input[id=txtfpchangenewpwd]', "#frmChangePassword").passStrengthify();
    $("#btnSubmit").bind("click", SrvSaveChangePassword);
    $("#txtfpchangenewpwd").bind("keypress keyup focus", function() {
        $("#validatefpchangeerrorvalue").removeClass('alert alert-error');
        $("#validatefpchangeerrorvalue").text('').show();
        $("#newpass").removeClass('control-group error');
        $("#repass").removeClass('control-group error');
    });
    $("#txtCustomerAdminrenternewpwd").bind("keypress keyup focus", function() {
        $("#validatefpchangeerrorvalue").removeClass('alert alert-error');
        $("#validatefpchangeerrorvalue").text('').show();
        $("#newpass").removeClass('control-group error');
        $("#repass").removeClass('control-group error');
    });
}
function SrvChangePassword(key)
{
    $.support.cors = true;
    $.ajax({
        url: BACKENDURL + "common/get_user_details",
        type: "post",
        data: {
            key: key
        },
        dataType: "json",
        crossDomain: true,
        success: function(data) {
            if (data.error) {
                location.href = "index.html";
            } else {
                for (var nCount = 0; nCount < data.user_res.length; nCount++)
                {
                    $("#divPasswordchangename").text("Welcome " + data.user_res[nCount].first_name + " " + data.user_res[nCount].last_name);
                    $("#divpasswordlink").text("Not " + data.user_res[nCount].first_name + "?").attr("href", "index.html");
                    $("#divusername").text(data.user_res[nCount].username);
                    localStorage.setItem("USERID", data.user_res[nCount].user_id);
                    localStorage.setItem("USERNAME", data.user_res[nCount].username);
                    LoadPagecontrols();
                }
            }
        },
        error: function(xhr, textStatus, error) {
            alert(error);
        }
    });
}
function SrvSaveChangePassword() {

    function isPassword(password) {
        var pattern = /^(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,16}$/;
        return pattern.test(password);
    }
    var password = $("#txtfpchangenewpwd").val();
    var confirmpassword = $("#txtCustomerAdminrenternewpwd").val();
    if (password == "" || confirmpassword == "")
    {
        $("#newpass").addClass('control-group error');
        $("#repass").addClass('control-group error');
        $("#validatefpchangeerrorvalue").addClass('alert alert-error').text('Please enter both New and confirm password').show();
        return false;
    }
    else if (password != confirmpassword)
    {

        $("#newpass").addClass('control-group error');
        $("#repass").addClass('control-group error');
        $("#validatefpchangeerrorvalue").addClass('alert alert-error').text('New and confirm passwords are not the same').show();
        return false;
    } else if (!isPassword($("#txtfpchangenewpwd").val())) {
        $("#newpass").addClass('control-group error');
        $("#repass").addClass('control-group error');
        $("#validatefpchangeerrorvalue").addClass('alert alert-error').text('Password does not match the criteria').show();
        return false;
    }
    else {
        $.support.cors = true;
        $.ajax({
            url: BACKENDURL + "common/save_change_password",
            type: "post",
            data: {
                user_id: localStorage["USERID"],
                username: localStorage["USERNAME"],
                password: $("#txtfpchangenewpwd").val()
            },
            dataType: "json",
            crossDomain: true,
            success: function(data) {
                if (data.error) {
                    if (!data.access)
                    {
                        $("#validatefpchangeerrorvalue").addClass('alert alert-error').text('The password has been reset successfully but your access is disabled by admin').show();
                        $("#btnSubmit").attr('disabled', true);
                        $("#btnSubmit").unbind("click");
                        $("#txtfpchangenewpwd").val('');
                        $("#txtCustomerAdminrenternewpwd").val('');
                        $("#txtfpchangenewpwd").attr('disabled', true);
                        $("#txtCustomerAdminrenternewpwd").attr('disabled', true);
                        $("#pwdStrengthify").css("display", "none");
                        return false;
                    }
                    else if (!data.pauth)
                    {
                        $("#validatefpchangeerrorvalue").addClass('alert alert-error').text('The password has been reset successfully but No access profile assigned to user').show();
                        $("#btnSubmit").attr('disabled', true);
                        $("#btnSubmit").unbind("click");
                        $("#txtfpchangenewpwd").val('');
                        $("#txtCustomerAdminrenternewpwd").val('');
                        $("#txtfpchangenewpwd").attr('disabled', true);
                        $("#txtCustomerAdminrenternewpwd").attr('disabled', true);
                        $("#pwdStrengthify").css("display", "none");
                        return false;
                    }
                    else if (data.error_msg == "Unauthorized access.")
                    {
                        location.href = "index.html";
                    }
                    else
                    {
                        $("#validatefpchangeerrorvalue").addClass('alert alert-error').text(data.error_msg).show();
                        $("#btnSubmit").attr('disabled', true);
                        $("#txtfpchangenewpwd").val('');
                        $("#txtCustomerAdminrenternewpwd").val('');
                        return false;
                    }
                } else {

                    /* Success */
                    if (localStorage) {
                        localStorage["SESSIONID"] = data.session_id;
                        localStorage["USERID"] = data.user_info.user_id;
                        localStorage["ROLENAME"] = data.user_info.role_name;
                        localStorage["ROLEID"] = data.user_info.role_id;
                        localStorage["FNAME"] = data.user_info.first_name;
                        localStorage["LNAME"] = data.user_info.last_name;
                        localStorage["TELEPHONE"] = data.user_info.telephone;
                        localStorage["EMAIL"] = data.user_info.user_email;
                        localStorage["TITLE"] = data.user_info.title;
                        localStorage["CUSTOMERID"] = data.user_info.customer_id;
                        localStorage["CUSTOMERNAME"] = data.user_info.customer_name;
                        localStorage["contractid"] = data.user_info.contract_id;
                        localStorage["contractkey"] = data.user_info.contract_key;
                        localStorage["CONTRACTNAME"] = data.user_info.contract_name;
                        localStorage["WorkTel"] = data.user_info.work_telephone;
                        localStorage["MobileTel"] = data.user_info.mobile_number;
                        localStorage["ChkEmail"] = data.user_info.mail_notification;
                        localStorage["ChkSms"] = data.user_info.sms_notification;
                        localStorage['default_mod'] = data.user_info.default_mod;
                    }
                    if (data.user_info.role_name == "Super Admin")
                        location.href = "superadmin.html";
                    else if (data.user_info.role_name == "User") {
                        localStorage.setItem("SkinInfo", JSON.stringify(data.skin_info));
                        var dynCSS = JSON.parse(localStorage.getItem("SkinInfo"));
                        localStorage["skinId"] = dynCSS.sid;
                        if (L2_menu[data.user_info.default_mod] != undefined) {
                            location.href = L2_menu[data.user_info.default_mod];//"userpage.html"; 
                        }
                        else {
                            $("#validatefpchangeerrorvalue").addClass('alert alert-error').text('No access profile assigned to user').show();
                            return false;
                        }
                    }
                    else if (data.user_info.role_name == "Customer Admin") {
                        localStorage["caprofile_status"] = data.profiles.profile_status;
                        localStorage["caprofile"] = JSON.stringify(data.profiles.profile_res);
                        localStorage["cacontract_status"] = data.profiles.cr_con;
                        location.href = "contractadmin.html";
                    }
                    else {
                        $("#validatefpchangeerrorvalue").addClass('alert alert-error').text(" Something went wrong with your request. Please try again and if it still doesn't work ask your administrator for help").show();
                        return false;
                    }
                    /* Success*/
                }
            },
            error: function(xhr, textStatus, error) {
                alert(error);
            }
        });
    }

}
 
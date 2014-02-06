var contractUsersArray = [], isAvailable = 0;
var usersProfileArray = [];
function LoadPageData() {
    loadAdministrators();
    loadContract();
    loadSettings();
    loadProfile();
}
/*************administrators***************/
function loadAdministrators() {
    LoadcustomerPage();
//New customer admin key press.
    $("input[type='text']", "#divNewCustomerAdmin").keypress(function(e) {
        if ((e.which && e.which == 13) || (e.keyCode && e.keyCode == 13)) {
            $("#btnNewCustomerAdminSubmit", "#divNewCustomerAdmin").click();
            return false;
        } else
            return true;
    });
    $("#btnNewCustomerAdmin").bind("click", newAdministrator);
}
function LoadcustomerPage()
{
    $("#hCustomernameadmin").html(localStorage["CUSTOMERNAME"] + " " + "Administrators")
    $("#hcustomernamesettings").html(localStorage["CUSTOMERNAME"] + " " + "Settings")
    $("#hcustomernameContracts").html(localStorage["CUSTOMERNAME"] + " " + "Contracts")
    $("#hCustomernameprofiles").html(localStorage["CUSTOMERNAME"] + " " + "Profiles")


    $('input[id="InputCustomerName"]').bind("keyup", function() {
        $("#btnSaveCustomerSettings").text('Save').attr("disabled", false);
    });
    $.ajax({
        url: BACKENDURL + "superadmin/get_customer_admin",
        type: "post",
        //contentType: "text/xml; charset=utf-8",
        data: {
            session_id: localStorage["SESSIONID"],
            customer_id: localStorage["CUSTOMERID"]
        },
        dataType: "json",
        crossDomain: true,
        success: function(data) {
            if (data.session_status) {
                if (data.error == 0) {
                    var nCurrRecRound = 0;
                    var hdnCurrPage = $("#currPageNumber", "#tablePagination").val();
                    if (hdnCurrPage != undefined) {
                        nCurrRecRound = hdnCurrPage - 1;
                    }

                    //display customers....

                    $("#tblAdmin tbody").children().remove();
                    if (data.customer_admin_res.length > 0)
                        $("#tblAdmin").show();

                    else
                        $("#tblAdmin").hide();
                    customerdata = data.customer_admin_res;
                    var nCount = 0;
                    $.each(data.customer_admin_res, function() {
                        if (this.first_name == $("#txtCustomerAdminFName").val()) {
                            nCurrRecRound = Math.floor(nCount / 10);
                            //$("#txtCustomerAdminFName").val("");
                        }
                        var disablebtn = "<a class='btn btn-small btn-danger' data-toggle='modal' href='#BtnToggleModal' id='btnDisableUser' name='btnDisableUser' style='width: 80px;' onclick='javascript:changeStatus(this,\"D\"," + this.user_id + ")'><i class='icon-remove-sign icon-white'></i> Disable</a>";
                        if (this.status == 0)
                            disablebtn = "<a class='btn btn-small btn-success' data-toggle='modal' href='#BtnToggleModal' id='btnDisableUser' name='btnDisableUser' style='width: 80px;' onclick='javascript:changeStatus(this,\"E\"," + this.user_id + ")'><i class='icon-ok-sign icon-white'></i> Enable</a>";
                        var userprofile = (!this.profile_name) ? "-" : this.profile_name;
                        $("#tblAdmin  tbody:last").append("<tr><td><span>" + this.first_name + "</span></td><td><span>" + this.last_name + "</span></td><td><span>" + this.username + "</span></td><td><span>" + userprofile + "</span></td><td style='text-align:right'>" + disablebtn + "</td></tr>");
                        nCount++;

                    });
                    if (data.customer_admin_res.length > 10) {
                        $("#tablePagination").html('');
                        $("#tblAdmin").tablePagination({
                            currPage: nCurrRecRound + 1});
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
function changeStatus(f, statusType, customer_id) {
    var status = (statusType == 'D') ? 0 : 1;

    if (status == 1) {
        $("#ToggleBtnLabel").text('Are you sure you want to Enable?');
    }
    else if (status == 0) {
        $("#ToggleBtnLabel").text('Are you sure you want to Disable?');
    }
    $("#ToggleCancelBtn").click(function() {
        $("#BtnToggleModal").modal('hide');
    });

    $("#ToggleOkBtn").click(function() {
        $("#BtnToggleModal").modal('hide');


        $.ajax({
            url: BACKENDURL + "superadmin/update_customer_admin_status", // update_customer_status
            type: "post",
            //contentType: "text/xml; charset=utf-8",
            data: {
                session_id: localStorage["SESSIONID"],
                customer_admin_id: customer_id, //customer_id
                status: status
            },
            dataType: "json",
            crossDomain: true,
            success: function(data) {
                if (data.session_status) {
                    if (data.error) {
                        alert(data.error_msg);
                    } else {
                        //close the modal 
                        //loadpage();

                        if (statusType == 'D') {

                            $(f).html('<span class="icon-ok-sign icon-white"></span> Enable').addClass('btn-success').attr('onclick', 'javascript:changeStatus(this,"E",' + customer_id + ')');
                        }
                        else
                            $(f).html('<span class="icon-remove-sign icon-white"></span> Disable').removeClass('btn-success').addClass('btn-danger').attr('onclick', 'javascript:changeStatus(this,"D",' + customer_id + ')');
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

    });
}
// New customer admin submit button
function newAdministrator() {
    $("#frmCreateAdmin").validate({
        rules: {
            txtCustomerAdminFName: {required: true},
            txtCustomerAdminLName: {required: true},
            txtCustomerAdminemail: {regEmail4: true, required: true},
            txtCustomerAdminCemail: {equalTo: "#txtCustomerAdminemail", required: true}
        },
        messages: {
            txtCustomerAdminFName: {required: "Please enter the First name"},
            txtCustomerAdminLName: {required: "Please enter the Last name"},
            txtCustomerAdminemail: {required: "Please enter email address"},
            txtCustomerAdminCemail: {required: "Please enter email address", equalTo: "Email address does not match"}
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
    $("#CustomerAdminerror").removeClass('alert alert-error')
    $("#CustomerAdminerror").text('').show();
    $("#txtCustomerAdminFName").val('')
    $("#txtCustomerAdminLName").val('')
    $("#txtCustomerAdminemail").val('')
    $("#txtCustomerAdminCemail").val('')
    $("#frmCreateAdmin").data('validator').resetForm();
    $(".error").removeClass("error");
}
$("#btnNewCustomerAdminSubmit", "#divNewCustomerAdmin").click(function() {
    if ($("#frmCreateAdmin").valid()) {
        $("#btnNewCustomerAdminSubmit").attr("disabled", true);
        //Save the information...		
        $.ajax({
            url: BACKENDURL + "superadmin/create_customer_admin",
            type: "post",
            //contentType: "text/xml; charset=utf-8",
            data: {
                session_id: localStorage["SESSIONID"],
                customer_id: localStorage["CUSTOMERID"],
                admin_fname: $("#txtCustomerAdminFName", "#divNewCustomerAdmin").val(),
                admin_lname: $("#txtCustomerAdminLName", "#divNewCustomerAdmin").val(),
                admin_email: $("#txtCustomerAdminemail", "#divNewCustomerAdmin").val()
            },
            dataType: "json",
            crossDomain: true,
            success: function(data) {
                if (data.session_status) {
                    if (data.error) {
                        $("#CustomerAdminerror").addClass("alert alert-error").text(data.error_msg);
                        $("#btnNewCustomerAdminSubmit").removeAttr("disabled");
                    } else {
                        //close the modal 
                        $("#btnNewCustomerAdminSubmit").removeAttr("disabled");
                        $('#divNewCustomerAdmin').modal('hide');
                        $("#xNewCustomerAdmin", "#divNewCustomerAdmin").click();
                        $("#lblSuccessHeading", "#divSuccess").html("Create a new customer administrator");
                        $("#lblSuccessMessage", "#divSuccess").html("Success! New customer administrator created.");
                        $("#btnSuccess").click();
                        LoadcustomerPage();
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
/*************administrators end***************/
/*************contracts***************/
function loadContract() {
    $("#lnkContracts").click(function() {
        getContracts();
    });

    $("#selectSuperContract").bind("change", populateContractdetails);
    $("#btnAddCustomers").bind("click", ContractAddUsers);
    $("#btnRemoveCustomers").bind("click", ContractRemoveUsers);
    $("#btnSaveCustomerContract").bind("click", ContractServiceSave);
}
function getContracts() {
    var url = BACKENDURL + "superadmin/sa_get_contracts";
    var data = {
        session_id: localStorage["SESSIONID"],
        customer_id: localStorage["CUSTOMERID"]
    };
    MakeAjaxCall(url, data, loadContracts);

}
function loadContracts(data) {
    if (data.session_status) {
        if (data.error == 0) {
            $('#selectSuperContract').empty();
            var contractStr = "";
            var selectedStr = " Selected ";
            if (data.customer_admin_res.length == 0) {
                $("#divSelectcontract").hide();
                $("#frmContract").hide();
                $("#btnSaveCustomerContract").hide();
                $("#lblCustomerContractlabel").html("There is no contract available for this customer").show();
            }
            else
            {
                $("#divSelectcontract").show();
                $("#frmContract").show();
                $("#btnSaveCustomerContract").show();
                $("#lblCustomerContractlabel").html("").hide();

            }
            for (var nCount = 0; nCount < data.customer_admin_res.length; nCount++) {
                contractStr += "<option value=" + data.customer_admin_res[nCount].contract_id + " " + selectedStr + ">" + data.customer_admin_res[nCount].contract_name + "</option>";
                selectedStr = "";
            }
            $('#selectSuperContract').append(contractStr);
            populateContractdetails();
        }
    }
}
function populateContractdetails() {

    var contract_id = $('#selectSuperContract').val();
    localStorage.setItem("contractid", contract_id);
    var url = BACKENDURL + "superadmin/sa_get_users_configure_contract";
    var data = {
        session_id: localStorage["SESSIONID"],
        customer_id: localStorage["CUSTOMERID"],
        contract_id: contract_id
    };
    contractUsersArray = [];
    MakeAjaxCall(url, data, getContractUsers);
}
function getContractUsers(data) {

    if (data.session_status) {
        if (data.error == 0) {
            $('#ddlAvailableCustomers').empty();
            $('#ddlSelectedCustomers').empty();
            var data_availableusers = "";
            var data_selectedusers = "";

            for (var nCount = 0; nCount < data.user_res[0].available_users.length; nCount++) {
                data_profilename = (data.user_res[0].available_users[nCount].profile_name != '') ? ' (' + data.user_res[0].available_users[nCount].profile_name + ') ' : '';
                data_populate = data.user_res[0].available_users[nCount].first_name + " " + data.user_res[0].available_users[nCount].last_name + ", " + data.user_res[0].available_users[nCount].username + data_profilename;
                data_availableusers += "<option value=" + data.user_res[0].available_users[nCount].user_id + ">" + data_populate + " </option>";
            }
            for (var nCount = 0; nCount < data.user_res[0].selected_users.length; nCount++) {
                data_profilename = (data.user_res[0].selected_users[nCount].profile_name != '') ? ' (' + data.user_res[0].selected_users[nCount].profile_name + ') ' : '';
                data_populate = data.user_res[0].selected_users[nCount].first_name + " " + data.user_res[0].selected_users[nCount].last_name + ", " + data.user_res[0].selected_users[nCount].username + data_profilename;
                data_selectedusers += "<option value=" + data.user_res[0].selected_users[nCount].user_id + ">" + data_populate + " </option>";
            }
            $('#ddlAvailableCustomers').append(data_availableusers);
            $('#ddlSelectedCustomers').append(data_selectedusers);
            $("#ddlAvailableCustomers option").length == 0 ? $("#btnAddCustomers").attr("disabled", "disabled") : $("#btnAddCustomers").removeAttr("disabled");
            $("#ddlSelectedCustomers option").length == 0 ? $("#btnRemoveCustomers").attr("disabled", "disabled") : $("#btnRemoveCustomers").removeAttr("disabled");
        }
    }
}

function ContractAddUsers()
{
    var selectedOpts = $('#ddlAvailableCustomers option:selected');
    if (selectedOpts.length == 0) {
        $("#divCustomerHide").html("Please select from Available Administrators").show();
    }
    else
    {
        $("#divCustomerHide").hide();
        $("#btnSaveCustomerContract").removeAttr("disabled").text('Save');
        for (var i = 0; i < selectedOpts.length; i++) {
            if (selectedOpts[i].selected == true) {
                for (var userArrCnt = 0; userArrCnt < contractUsersArray.length; userArrCnt++)
                {
                    $.each(contractUsersArray[userArrCnt], function(key, value) {
                        if ((key == 'user_id') && (value == $(selectedOpts[i]).val())) {
                            contractUsersArray.splice(userArrCnt, 1);
                            isAvailable = 1;
                        }
                    });
                }
                if (isAvailable == 0) {
                    contractUsersArray.push({
                        "user_id": $(selectedOpts[i]).val(),
                        "status": "1"
                    });
                }
                isAvailable = 0;
            }
        }
        $("#ddlSelectedCustomers").prepend($(selectedOpts).clone());
        $(selectedOpts).remove();
        SortOptions("#ddlSelectedCustomers");
        $("#ddlAvailableCustomers option").length == 0 ? $("#btnAddCustomers").attr("disabled", "disabled") : $("#btnAddCustomers").removeAttr("disabled");
        $("#ddlSelectedCustomers option").length == 0 ? $("#btnRemoveCustomers").attr("disabled", "disabled") : $("#btnRemoveCustomers").removeAttr("disabled");
    }
}

function ContractRemoveUsers()
{

    var selectedOpts2 = $('#ddlSelectedCustomers option:selected');
    if (selectedOpts2.length == 0) {
        $("#divCustomerHide").html("Please select from Selected Administrators").show();
    }
    else
    {
        $("#divCustomerHide").hide();
        $("#btnSaveCustomerContract").removeAttr("disabled").text('Save');
        for (var i = 0; i < selectedOpts2.length; i++) {
            if (selectedOpts2[i].selected == true) {
                for (var userArrCnt = 0; userArrCnt < contractUsersArray.length; userArrCnt++)
                {
                    $.each(contractUsersArray[userArrCnt], function(key, value) {
                        if ((key == 'user_id') && (value == $(selectedOpts2[i]).val())) {
                            contractUsersArray.splice(userArrCnt, 1);
                            isAvailable = 1;
                        }
                    });
                }

                if (isAvailable == 0) {
                    contractUsersArray.push({
                        "user_id": $(selectedOpts2[i]).val(),
                        "status": "0"
                    });
                }
                isAvailable = 0;
            }
        }
        $("#ddlAvailableCustomers").prepend($(selectedOpts2).clone());
        $(selectedOpts2).remove();
        SortOptions("#ddlAvailableCustomers");
        $("#ddlAvailableCustomers option").length == 0 ? $("#btnAddCustomers").attr("disabled", "disabled") : $("#btnAddCustomers").removeAttr("disabled");
        $("#ddlSelectedCustomers option").length == 0 ? $("#btnRemoveCustomers").attr("disabled", "disabled") : $("#btnRemoveCustomers").removeAttr("disabled");
    }
}

function ContractServiceSave()
{
    if (contractUsersArray.length > 0) {
        $("#btnSaveCustomerContract").text('Saving').attr("disabled", true);
        var url = BACKENDURL + "superadmin/sa_save_configure_contract";
        var data = {
            session_id: localStorage["SESSIONID"],
            contract_id: localStorage["contractid"],
            customer_id: localStorage["CUSTOMERID"],
            user_data: contractUsersArray
        };
        MakeAjaxCall(url, data, ContractSave);
    } else {
        $("#btnSaveCustomerContract").text('Saving').attr("disabled", true);
        ContractSave();
    }

}
function ContractSave() {
    $("#btnSaveCustomerContract").text('Saved').attr("disabled", true);
    contractUsersArray = [];
}
/*************************contracts end*********************************/
/*************************settings*********************************/
function loadSettings() {
    $("#lnkSettings").click(function() {
        getCustomerName();
        $("#frmCustomerSettings").data('validator').resetForm();
        $(".error").removeClass("error");
    });
    $("#btnSaveCustomerSettings").bind("click", Customernamesave);
}

function getCustomerName()
{
    $("#lblCustomerlabel").removeClass('alert alert-success')
    $("#lblCustomerlabel").removeClass('alert alert-error')
    $("#lblCustomerlabel").text('').show();
    $("#frmCustomerSettings").validate({
        rules: {
            InputCustomerName: {
                required: true
            }
        },
        messages: {
            InputCustomerName: {required: "Please enter the Customer name"}
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
    $("#InputCustomerID").val(localStorage["CUSTOMERID"])
    $("#InputCustomerName").val(localStorage["CUSTOMERNAME"])
}
function Customernamesave()
{
    if ($("#frmCustomerSettings").valid()) {
        $("#btnSaveCustomerSettings").attr("disabled", true).text('Saving');
        var url = BACKENDURL + "superadmin/edit_customer";

        var data = {
            session_id: localStorage["SESSIONID"],
            customer_id: localStorage["CUSTOMERID"],
            customer_name: $("#InputCustomerName").val()
        };
        MakeAjaxCall(url, data, SaveCustomers);
    }
}


function SaveCustomers(data)
{
    if ($("#frmCustomerSettings").valid()) {

        if (data.error == 1)
        {

            $("#btnSaveCustomerSettings").attr("disabled", false).text('Save');
            $("#lblCustomerlabel").addClass('alert alert-error').text(data.error_msg)
        }
        $('input[id^="InputCustomerName"]').bind("keypress keyup", function() {
            $("#lblCustomerlabel").removeClass('alert alert-success')
            $("#lblCustomerlabel").removeClass('alert alert-error')
            $("#lblCustomerlabel").text('').show();
        });
        if (data.error == 0)
        {
            localStorage.setItem("CUSTOMERNAME", $("#InputCustomerName").val());
            $("#divSuperLoggedAs").html(" You are administering" + " " + localStorage["CUSTOMERNAME"]);
            $("#hCustomernameadmin").html(localStorage["CUSTOMERNAME"] + " " + "Administrators");
            $("#hcustomernamesettings").html(localStorage["CUSTOMERNAME"] + " " + "Settings");
            $("#hcustomernameContracts").html(localStorage["CUSTOMERNAME"] + " " + "Contracts");
            $("#hCustomernameprofiles").html(localStorage["CUSTOMERNAME"] + " " + "Profiles");
            $("#btnSaveCustomerSettings").text('Saved');
            $("#lblCustomerlabel").addClass('alert alert-success').text("Customer name has been updated");
        }

    }
}
/*************************settings end*********************************/
/*************************Profile**********************************/

function loadProfile()
{
    $("#lnkProfiles").bind("click", SrvCustomerProfile);
    $("#btnProfileAddUsers", "#divProfileUserList").bind("click", ProfileAddUsers);
    $("#btnProfileRemoveUsers", "#divProfileUserList").bind("click", ProfileRemoveUsers);
    $("#btnProfileSave").bind("click", SrvProfileSave);
    $("#btnProfileDelete").bind("click", function()
    {
        $("#divSAProfileDelete").modal('show');
    });
    $('#ddlUserProfile').on('change', function() {
        ddlChangeProfile(0);
    });
    $("#btnSAProfileDelete").bind("click", SrvProfileDelete);
    $("#hoverevent_Def").tooltip({
        'selector': '',
        'placement': 'right',
        'width': '400px'
    });
    $('input[id="txtProfileName"]', "#divNewProfile").bind("keyup", function() {
        $("#lblSavealert").removeClass('alert alert-success').text('');
        $("#spnProfileName").css('display', 'none');
        $("#txtProfileName").css('border-color', '#cccccc');
        $("#lblProfilename").css('color', '#333333');
        $("#btnProfileSave").text('Save').attr("disabled", false);
    });
}
// get all the profile details
function SrvCustomerProfile() {
    var url = BACKENDURL + "superadmin/get_admin_profile_master_details";
    var data = {
        session_id: localStorage["SESSIONID"],
        customer_id: localStorage["CUSTOMERID"]
    };
    MakeAjaxCall(url, data, CustomerProfile);
}
// check existing profiles ?
function CustomerProfile(data) {
    var data_profile = "", selectedStr = " Selected ";
    if (data.error == 0) {
        localStorage["profile_length"] = data.profile_res[0].profiles.length;
        if (data.profile_res[0].profiles.length > 0)
        {
            $("#NewProfile").addClass("hide");
            $("#ProfileExist").show();
            $("#ddlUserProfile", "#ProfileExist").empty();
            $("#ProfileExist").removeClass("hide");
            if (data.profile_res[0].profiles.length == 1)
            {
                $("#lblProfile").hide();
                $("#spnUserprofile").hide();
            }
            else
            {
                $("#lblProfile").show();
                $("#spnUserprofile").show();
            }
            for (var nCount = 0; nCount < data.profile_res[0].profiles.length; nCount++) {
                data_profile += "<option value=" + data.profile_res[0].profiles[nCount].profile_id + " " + selectedStr + " >" + data.profile_res[0].profiles[nCount].profile_name + "</option>";
                selectedStr = "";
            }
            $("#ddlUserProfile", "#ProfileExist").append(data_profile);
            MasterPageData(data);
            var url = BACKENDURL + "superadmin/get_admin_profile_details_contract";
            var data = {
                session_id: localStorage["SESSIONID"],
                customer_id: localStorage["CUSTOMERID"],
                ad_profile_id: $("#ddlUserProfile").val()

            };
            MakeAjaxCall(url, data, UserProfileData);
        }
        else
        {
            $("#NewProfile").removeClass("hide");
            $("#ProfileExist").hide();

        }
    }
}

// Load Master Page data
function MasterPageData(data)
{
    var data_modules = "", data_AccordModules = "", L1Navcode = "", L1NavName = "", L1NavId = "", inStr = " in ", data_innervalue = "";
    if (data.profile_res[0].m_modules.length > 0)
    {
        $("#ddlMasterModule", "#ProfileExist").empty();
        var selectedStr = " Selected ";
        var L1Opts = 0, L2Opts = 0;
        for (var nCount = 0; nCount < data.profile_res[0].m_modules.length; nCount++) {
            L1NavName = data.profile_res[0].m_modules[nCount].m_module_name;
            L1NavId = data.profile_res[0].m_modules[nCount].m_module_id;
            data_modules += "<option value=" + L1NavId + " " + selectedStr + " >" + L1NavName + "</option>";
            if (data.profile_res[0].m_modules[nCount].hierarchy > 0) // Level1 Hierarchy is avalable and it has level 2
            {
                var L1Obj = data.profile_res[0].sub_modules[L1Opts];
                for (var L1key in L1Obj) { //Level1 check
                    if (L1key === L1NavName)
                    {
                        var data_L1length = L1Obj[L1key].length;
                        for (var jCount = 0; jCount < data_L1length; jCount++) {// Level 2 check
                            var L2NavName = L1Obj[L1key][jCount].s_module_name;
                            var L2NavID = L1Obj[L1key][jCount].s_module_id;
                            if (L1Obj[L1key][jCount].hierarchy > 0)
                            {
                                var L2Obj = data.profile_res[0].sub_sub_modules[L2Opts];
                                for (var L2key in L2Obj) // Level 3 check
                                {
                                    var tblSubmodules = '<table class="no-more-tables table tSubmod" id="data-submodules" style="border-top:0px !important;margin-left:-30px;"><tbody><tr>';
                                    if (L2key === L2NavName)
                                    {
                                        var refArray = chkL3Modules(L2Obj[L2key]);
                                        for (var kCount = 0; kCount < refArray.length; kCount++) {
                                            var tbl_modules = "";
                                            if (kCount == 0)
                                            {
                                                for (var mCount = 0; mCount < refArray[kCount].length; mCount++)
                                                {
                                                    var mod_s = refArray[kCount][mCount].L2Id;
                                                    tbl_modules += '<ul><label class="checkbox" ><input type="checkbox" value=' + mod_s + ' id=chkCL_' + kCount + "_" + L2NavID + "_SSM" + mod_s + ' name=chkCL_' + kCount + "_" + L2NavID + "_SSM" + mod_s + '>' + refArray[kCount][mCount].L2Data + '</label></ul>';
                                                }
                                                tblSubmodules += '<td id=tbl_Mod_' + L2NavID + ' style="border-top:0px !important">' + tbl_modules + '</td>';
                                            }
                                            else
                                            {
                                                for (var mCount = 0; mCount < refArray[kCount].L2Data.length; mCount++)
                                                {
                                                    var SSubmod = refArray[kCount].L2Data[mCount].ss_module_id;
                                                    var SSName = refArray[kCount].L2Data[mCount].ss_module_name;
                                                    tbl_modules += '<ul><label class="checkbox" ><input type="checkbox" value=' + SSubmod + ' onchange="SelSubSubmenus(this);" id=chkCL_' + kCount + "_" + L2NavID + "_SSM" + SSubmod + ' name=chkCL_' + kCount + "_" + L2NavID + "_SSM" + SSubmod + '>' + SSName + '</label></ul>';
                                                }
                                                tblSubmodules += '<td id=tbl_Mod_' + L2NavID + ' style="border-top:0px !important"><li style="list-style:none;"><label class="checkbox" style="font-weight: bold"><input type="checkbox" value=' + refArray[kCount].L2HeaderMod + ' onchange="SelSubmenus(this);" id=chkCL_' + kCount + "_" + L2NavID + ' name=chkCL_' + kCount + "_" + L2NavID + "_SSM" + refArray[kCount].L2HeaderMod + '>' + refArray[kCount].L2Header + '</label></li>' + tbl_modules + '</td>';
                                            }
                                        }

                                    }
                                    else
                                    {
                                        L2Opts += 1;
                                    }
                                    tblSubmodules += "</tr></tbody></table>";
                                    data_innervalue = "<div class='form-horizontal span12'>" + tblSubmodules + "</div>"
                                    //US 312 item 21 adding respective icons before accordian headings
									//data_AccordModules += "<div id=Accord" + L1NavId + " class='accordion-group show'><div class='accordion-heading'><a class='accordion-toggle' data-toggle='collapse' data-parent='#divAccordProfile' style='text-decoration: none !important;padding-left: 7px;'>" + L1NavName + ", " + L2NavName + "</a></div><div id='divMC-" + L1Navcode + "' class='accordion-body collapse" + inStr + "'> <div class='accordion-inner'><div class='accordion' style='margin-bottom:0px;'>" + data_innervalue + "</div></div></div></div>";
									//DE 527 changing hand icon to default cursor on empty accordion
								   switch(L1NavId){
												case '1':
												data_AccordModules += "<div id=Accord" + L1NavId + " class='accordion-group show'><div class='accordion-heading'><a class='accordion-toggle' data-toggle='collapse' data-parent='#divAccordProfile' style='text-decoration: none !important;padding-left: 7px;cursor: default;'><i class='icon-home icon-white'></i>" +" "+ L1NavName + ", " + L2NavName + "</a></div><div id='divMC-" + L1Navcode + "' class='accordion-body collapse" + inStr + "'> <div class='accordion-inner'><div class='accordion' style='margin-bottom:0px;'>" + data_innervalue + "</div></div></div></div>";												
												break;
												case '2':
												data_AccordModules += "<div id=Accord" + L1NavId + " class='accordion-group show'><div class='accordion-heading'><a class='accordion-toggle' data-toggle='collapse' data-parent='#divAccordProfile' style='text-decoration: none !important;padding-left: 7px;cursor: default;'><i class='icon-user icon-white'></i>" +" "+ L1NavName + ", " + L2NavName + "</a></div><div id='divMC-" + L1Navcode + "' class='accordion-body collapse" + inStr + "'> <div class='accordion-inner'><div class='accordion' style='margin-bottom:0px;'>" + data_innervalue + "</div></div></div></div>";																								
												break;
												case '3':
												data_AccordModules += "<div id=Accord" + L1NavId + " class='accordion-group show'><div class='accordion-heading'><a class='accordion-toggle' data-toggle='collapse' data-parent='#divAccordProfile' style='text-decoration: none !important;padding-left: 7px;cursor: default;'><i class='icon-wrench icon-white'></i>" +" "+ L1NavName + ", " + L2NavName + "</a></div><div id='divMC-" + L1Navcode + "' class='accordion-body collapse" + inStr + "'> <div class='accordion-inner'><div class='accordion' style='margin-bottom:0px;'>" + data_innervalue + "</div></div></div></div>";																								
												break;
												case '4':
												data_AccordModules += "<div id=Accord" + L1NavId + " class='accordion-group show'><div class='accordion-heading'><a class='accordion-toggle' data-toggle='collapse' data-parent='#divAccordProfile' style='text-decoration: none !important;padding-left: 7px;cursor: default;'><i class='icon-briefcase icon-white'></i>" +" "+ L1NavName + ", " + L2NavName + "</a></div><div id='divMC-" + L1Navcode + "' class='accordion-body collapse" + inStr + "'> <div class='accordion-inner'><div class='accordion' style='margin-bottom:0px;'>" + data_innervalue + "</div></div></div></div>";																								
												break;
												case '5':
												data_AccordModules += "<div id=Accord" + L1NavId + " class='accordion-group show'><div class='accordion-heading'><a class='accordion-toggle' data-toggle='collapse' data-parent='#divAccordProfile' style='text-decoration: none !important;padding-left: 7px;cursor: default;'><i class='icon-signal icon-white'></i>"+" " + L1NavName + ", " + L2NavName + "</a></div><div id='divMC-" + L1Navcode + "' class='accordion-body collapse" + inStr + "'> <div class='accordion-inner'><div class='accordion' style='margin-bottom:0px;'>" + data_innervalue + "</div></div></div></div>";																								
												break;
												}
									L2Opts += 1;
                                }
                            }
                            else
                            {
                                data_innervalue = "<div class='form-horizontal span12'></div>"
                                //US 312 item 21 adding respective icons before accordian headings
								//data_AccordModules += "<div id=Accord" + L1NavId + " class='accordion-group show'><div class='accordion-heading'><a class='accordion-toggle' data-toggle='collapse' data-parent='#divAccordProfile' style='text-decoration: none !important;padding-left: 7px;'>" + L1NavName + ", " + L2NavName + "</a></div><div id='divMC-" + L1Navcode + "' class='accordion-body collapse" + inStr + "'> <div class='accordion-inner'><div class='accordion' style='margin-bottom:0px;'>" + data_innervalue + "</div></div></div></div>";
								switch(L1NavId){
												case '1':
												data_AccordModules += "<div id=Accord" + L1NavId + " class='accordion-group show'><div class='accordion-heading'><a class='accordion-toggle' data-toggle='collapse' data-parent='#divAccordProfile' style='text-decoration: none !important;padding-left: 7px;'><i class='icon-home icon-white'></i>" +" "+ L1NavName + ", " + L2NavName + "</a></div><div id='divMC-" + L1Navcode + "' class='accordion-body collapse" + inStr + "'> <div class='accordion-inner'><div class='accordion' style='margin-bottom:0px;'>" + data_innervalue + "</div></div></div></div>";												
												break;
												case '2':
												data_AccordModules += "<div id=Accord" + L1NavId + " class='accordion-group show'><div class='accordion-heading'><a class='accordion-toggle' data-toggle='collapse' data-parent='#divAccordProfile' style='text-decoration: none !important;padding-left: 7px;'><i class='icon-user icon-white'></i>" +" "+ L1NavName + ", " + L2NavName + "</a></div><div id='divMC-" + L1Navcode + "' class='accordion-body collapse" + inStr + "'> <div class='accordion-inner'><div class='accordion' style='margin-bottom:0px;'>" + data_innervalue + "</div></div></div></div>";																								
												break;
												case '3':
												data_AccordModules += "<div id=Accord" + L1NavId + " class='accordion-group show'><div class='accordion-heading'><a class='accordion-toggle' data-toggle='collapse' data-parent='#divAccordProfile' style='text-decoration: none !important;padding-left: 7px;'><i class='icon-wrench icon-white'></i>" +" "+ L1NavName + ", " + L2NavName + "</a></div><div id='divMC-" + L1Navcode + "' class='accordion-body collapse" + inStr + "'> <div class='accordion-inner'><div class='accordion' style='margin-bottom:0px;'>" + data_innervalue + "</div></div></div></div>";																								
												break;
												case '4':
												data_AccordModules += "<div id=Accord" + L1NavId + " class='accordion-group show'><div class='accordion-heading'><a class='accordion-toggle' data-toggle='collapse' data-parent='#divAccordProfile' style='text-decoration: none !important;padding-left: 7px;'><i class='icon-briefcase icon-white'></i>" +" "+ L1NavName + ", " + L2NavName + "</a></div><div id='divMC-" + L1Navcode + "' class='accordion-body collapse" + inStr + "'> <div class='accordion-inner'><div class='accordion' style='margin-bottom:0px;'>" + data_innervalue + "</div></div></div></div>";																								
												break;
												case '5':
												data_AccordModules += "<div id=Accord" + L1NavId + " class='accordion-group show'><div class='accordion-heading'><a class='accordion-toggle' data-toggle='collapse' data-parent='#divAccordProfile' style='text-decoration: none !important;padding-left: 7px;'><i class='icon-signal icon-white'></i>"+" " + L1NavName + ", " + L2NavName + "</a></div><div id='divMC-" + L1Navcode + "' class='accordion-body collapse" + inStr + "'> <div class='accordion-inner'><div class='accordion' style='margin-bottom:0px;'>" + data_innervalue + "</div></div></div></div>";																								
												break;
												}
                            }
                        }
                        L1Opts += 1;
                    }
                    else
                    {
                        L1Opts += 1;
                    }
                }
            }
            //data_AccordModules += "<div class='accordion-group'><div class='accordion-heading'><a class='accordion-toggle' data-toggle='collapse' data-parent='#divAccordProfile' style='text-decoration: none !important;'>" + L1NavName + "</a></div><div id='divMC-" + L1Navcode + "' class='accordion-body collapse" + inStr + "'> <div class='accordion-inner'><div class='accordion' style='margin-bottom:0px;'>" + L1NavName + "</div></div></div></div>";
            selectedStr = "";
        }
        $("#ddlMasterModule", "#ProfileExist").append(data_modules);
        $("#divAccordProfile", "#ProfileExist").html(data_AccordModules);
    }
    formDirtyCheck();
}

// Dirty Check Validations
function formDirtyCheck() {
    var Settings = {
        denoteDirtyForm: true,
        dirtyFormClass: false,
        // denoteDirtyOptions:true,
        dirtyOptionClass: "dirtyChoice",
        trimText: true,
        formChangeCallback: function(result, dirtyFieldsArray) {
            if (result)
            {
                $("#lblSavealert").removeClass('alert alert-success').text('');
                $("#btnProfileSave").text('Save').attr("disabled", false);
            }
            else
            {
                $("#lblSavealert").removeClass('alert alert-success').text('');
                $("#btnProfileSave").text('Save').attr("disabled", false);
            }
        }
    };

    $("#frmUserprofile").dirtyFields(Settings);
}

// Check L3 Modules 
function chkL3Modules(L2dataObj)
{
    var L1Array = [], L2Array = [], L3Opts = 0;
    for (var SCount = 0; SCount < L2dataObj.length; SCount++) {
        for (var L3key in L2dataObj[SCount])
        {
            if (L2dataObj[SCount][L3key][1].length > 0)
            {
                L2Array.push({
                    "L2Data": L2dataObj[SCount][L3key][1],
                    "L2Header": L3key,
                    "L2HeaderMod": L2dataObj[SCount][L3key][0].ss_module_id
                });
            }
            else
            {

                L1Array.push({
                    "L2Data": L3key,
                    "L2Id": L2dataObj[SCount][L3key][0].ss_module_id
                });
            }
        }

    }
    L2Array.reverse();
    L2Array.push(L1Array);
    L3Opts += 1;
    return L2Array.reverse();
}

// select Hierarchy Check selection

function SelSubmenus(data)
{
    var tmpData = $(data).attr('id');
    $("#" + tmpData).is(":checked") ? $("[id^=" + tmpData + "]").prop('checked', true) : $("[id^=" + tmpData + "]").prop('checked', false);

}

function UserProfileData(data)
{
    var data_ProfAvailUsers = "", data_ProfSelectedUsers = "", data_ProfDet = "";
    var ProfileName = data.profile_res[0].profile_res[0].profile_name;
    localStorage["profile_id"] = data.profile_res[0].profile_res[0].profile_id;
    (data.profile_res[0].profile_res[0].create_con) == "0" ? $('#chkCreateContract').prop('checked', false) : $('#chkCreateContract').prop('checked', true);
    (data.profile_res[0].profile_res[0].self_registration) == "0" ? $('#chkSelfReg').prop('checked', false) : $('#chkSelfReg').prop('checked', true);
    $("#txtProfileName", "#ProfileExist").val(ProfileName);
    for (var SSMCount = 0; SSMCount < data.profile_res[0].profile_sub_modules.length; SSMCount++)
    {
        $("[name$=SSM" + data.profile_res[0].profile_sub_modules[SSMCount].ss_module_id + "]").prop('checked', true);
    }
    /**** User list ****/

    $("#lstProfileAvailableUsr", "#divProfileUserList").empty();
    $("#lstProfileSelectedUsr", "#divProfileUserList").empty();
    var data_userprofilename = "";

    for (var nCount = 0; nCount < data.profile_res[0].available_users.length; nCount++) {
        var disabled = (data.profile_res[0].available_users[nCount].profile_name) == "" ? "" : "disabled";
        if (data.profile_res[0].available_users[nCount].profile_name != '')
        {
            data_userprofilename = '(' + data.profile_res[0].available_users[nCount].profile_name + ')'
        }
        else
        {
            data_userprofilename = ""
        }
        data_ProfDet = data.profile_res[0].available_users[nCount].first_name + " " + data.profile_res[0].available_users[nCount].last_name + ", " + data.profile_res[0].available_users[nCount].username + " " + data_userprofilename;
        data_ProfAvailUsers += "<option value=" + data.profile_res[0].available_users[nCount].user_id + " " + disabled + " >" + data_ProfDet + "</option>";
    }
    for (var nCount = 0; nCount < data.profile_res[0].selected_users.length; nCount++) {
        data_ProfDet = data.profile_res[0].selected_users[nCount].first_name + " " + data.profile_res[0].selected_users[nCount].last_name + ", " + data.profile_res[0].selected_users[nCount].username;
        data_ProfSelectedUsers += "<option value=" + data.profile_res[0].selected_users[nCount].user_id + ">" + data_ProfDet + "</option>";
    }
    $("#lstProfileAvailableUsr").append(data_ProfAvailUsers);
    $("#lstProfileSelectedUsr").append(data_ProfSelectedUsers);
    $("#lstProfileAvailableUsr option").length == 0 ? $("#btnProfileAddUsers").attr("disabled", "disabled") : $("#btnProfileAddUsers").removeAttr("disabled");
    $("#lstProfileSelectedUsr option").length == 0 ? $("#btnProfileRemoveUsers").attr("disabled", "disabled") : $("#btnProfileRemoveUsers").removeAttr("disabled");
}

// Profile Add users
function ProfileAddUsers(e)
{
    //Added in support of US 312 item 13 moving users div inside form
	e.preventDefault();
	$.support.cors = true;
	$("#lblSavealert").hasClass("alert alert-success")
    $("#lblSavealert").removeClass('alert alert-success').text('');
    $("#lblSavealert").hasClass("alert alert-danger")
    $("#lblSavealert").removeClass('alert alert-danger').text('');
    var selectedOpts = $('#lstProfileAvailableUsr option:selected');
    if (selectedOpts.length == 0) {
        $("#divProfileErrMsg").html("Please select from available users").show()
    }
    else
    {
        $("#divProfileErrMsg").hide();
        for (var i = 0; i < selectedOpts.length; i++) {
            if (selectedOpts[i].selected == true) {
                usersProfileArray.push({
                    "user_id": $(selectedOpts[i]).val(),
                    "ad_profile_id": localStorage["profile_id"]
                });
            }
        }
        $("#lstProfileSelectedUsr").prepend($(selectedOpts).clone());
        $(selectedOpts).remove();
        $("#lstProfileAvailableUsr option").length == 0 ? $("#btnProfileAddUsers").attr("disabled", "disabled") : $("#btnProfileAddUsers").removeAttr("disabled");
        $("#lstProfileSelectedUsr option").length == 0 ? $("#btnProfileRemoveUsers").attr("disabled", "disabled") : $("#btnProfileRemoveUsers").removeAttr("disabled");
        $("#btnProfileSave").text('Save').attr("disabled", false);
        //$("#btnProfileDelete").text('Delete').attr("disabled", false);
    }
}

function ProfileRemoveUsers(e)
{
    //Added in support of US 312 item 13 moving users div inside form
	e.preventDefault();
	$.support.cors = true;
	$("#lblSavealert").hasClass("alert alert-success")
    $("#lblSavealert").removeClass('alert alert-success').text('');
    $("#lblSavealert").hasClass("alert alert-danger")
    $("#lblSavealert").removeClass('alert alert-danger').text('');
    var selectedOpts = $('#lstProfileSelectedUsr option:selected');
    if (selectedOpts.length == 0) {
        $("#divProfileErrMsg").html("Please select from selected users").show()
    }
    else
    {
        $("#divProfileErrMsg").hide();
        for (var i = 0; i < selectedOpts.length; i++) {
            if (selectedOpts[i].selected == true) {
                usersProfileArray.push({
                    "user_id": $(selectedOpts[i]).val(),
                    "ad_profile_id": "0"
                });
            }
        }
        $("#lstProfileAvailableUsr").prepend($(selectedOpts).clone());
        $(selectedOpts).remove();
        $("#lstProfileAvailableUsr option").length == 0 ? $("#btnProfileAddUsers").attr("disabled", "disabled") : $("#btnProfileAddUsers").removeAttr("disabled");
        $("#lstProfileSelectedUsr option").length == 0 ? $("#btnProfileRemoveUsers").attr("disabled", "disabled") : $("#btnProfileRemoveUsers").removeAttr("disabled");
        $("#btnProfileSave").text('Save').attr("disabled", false);
        //$("#btnProfileDelete").text('Delete').attr("disabled", false);
    }
}

// Profile Save 

function SrvProfileSave()
{
    //var indexlen = 0;
    //var tmp_val = -1;
    profile_ss_module_data = [];
    profile_s_module_data = [];
    ss_module_items = [];
    ss_modsel_items = [];
    //ss_each_item = [[], [], [], [], [], [], [], []];
    tmp_array = [];
    if ($("#txtProfileName").val().length == 0)
    {
        $("#spnProfileName").css('display', 'inline');
        $("#txtProfileName").css('border-color', '#b94a48');
        $("#lblProfilename").css('color', '#b94a48');
        $("#txtProfileName").focus();
        return false;
    }
    //var ActiveAccord = $('#divAccordProfile').children('.show').attr('id');
    $('#divAccordProfile input[type="checkbox"]:checked').each(function() {
        ss_module_items.push($(this).attr('id'));
        ss_modsel_items.push($(this).attr('id'));
        profile_ss_module_data.push($(this).val());
    });
    /* Maximum selection of submodules 
     if (ss_module_items.length > 0)
     {
     for (var mod_count = 0; mod_count < ss_module_items.length; mod_count++)
     {
     if (ss_module_items[mod_count].indexOf("SSM") < 0)
     {
     for (var pop_count = 0; pop_count < ss_module_items.length; pop_count++)
     {
     if ((ss_module_items[pop_count].indexOf(ss_module_items[mod_count]) >= 0) && (pop_count != mod_count))
     {
     ss_modsel_items.splice(ss_modsel_items.indexOf(ss_module_items[pop_count]), 1);
     }
     }
     
     }
     
     }
     }*/
    // Seperating based on the individual modules
    for (var tot_module = 0; tot_module < ss_modsel_items.length; tot_module++)
    {
        tmp_array = "";
        tmp_array = ss_modsel_items[tot_module].split("_");
        if (profile_s_module_data.indexOf(tmp_array[2]) < 0)
        {
            profile_s_module_data.push(tmp_array[2]);
        }
        /*if (tot_module == 0 || tmp_val == tmp_array[2])
         {
         ss_each_item[indexlen].push(ss_modsel_items[tot_module]);
         tmp_val = tmp_array[2];
         }
         else
         {
         indexlen = parseInt(indexlen) + 1;
         ss_each_item[indexlen].push(ss_modsel_items[tot_module]);
         tmp_val = tmp_array[2];
         }*/
    }
    //if (Check8Mod()){
    $("#btnProfileSave").attr("disabled", true).text('Saving');
    $("#btnProfileSave").attr("disabled", true);
    var url = BACKENDURL + "superadmin/save_admin_profile_details";
    var data = {
        session_id: localStorage["SESSIONID"],
        customer_id: localStorage["CUSTOMERID"],
        ad_profile_id: localStorage["profile_id"],
        profile_name: $("#txtProfileName").val(),
        self_reg: $("#chkSelfReg").is(":checked") ? 1 : 0,
        create_con: $("#chkCreateContract").is(":checked") ? 1 : 0,
        ad_m_mod_id: "0",
        profile_s_module_data: profile_s_module_data,
        profile_ss_module_data: profile_ss_module_data,
        user_data: usersProfileArray
    };
    MakeAjaxCall(url, data, ProfileSave);
    /*}
     else
     {
     $("#lblSavealert").addClass('alert alert-danger').text('Maximum you can select 8 Individual Sub Modules')
     }*/
}

/* check max tabs of sub menus
 function Check8Mod()
 {
 for (var mod_length = 0; mod_length < ss_each_item.length; mod_length++)
 {
 if (ss_each_item[mod_length].length > max_modules)
 {
 return false;
 }
 }
 return true;
 }*/

function SelSubSubmenus(data)
{
    // if all the sub menus is un checked then need to remove the header check box checked
    var tmpData = $(data).attr('id');
    var output = getNth(tmpData, '_', 3);
    if (!$("#" + tmpData).is(":checked"))
    {
        if ($("input[type='checkbox'][id*=" + output + "_" + "]:checked").length == 0)
            $("#" + output).is(":checked") ? $("#" + output).prop('checked', false) : "";
    }
//$("#" + output).prop('checked', true); to check the header item of the list 
}

function getNth(s, c, n) {
    var idx;
    var i = 0;
    var newS = '';
    do {
        idx = s.indexOf(c);
        newS += s.substring(0, idx);
        s = s.substring(idx + 1);
    } while (++i < n && (newS += c))
    return newS;
}

function ProfileSave(data)
{
    if (data.error)
    {
        $("#lblSavealert").addClass('alert alert-danger').text(data.error_msg);
        $("#btnProfileSave").text('Save').attr("disabled", false);
    }
    else
    {
        var data_profile = "";
        $("#ddlUserProfile", "#ProfileExist").empty();
        for (var nCount = 0; nCount < data.profile_res.length; nCount++) {
            data_profile += "<option value=" + data.profile_res[nCount].ad_profile_id + ">" + data.profile_res[nCount].profile_name + "</option>";
        }
        $("#ddlUserProfile", "#ProfileExist").append(data_profile);
        $("#ddlUserProfile", "#ProfileExist").val(localStorage["profile_id"]);
        $("#btnProfileSave").text('Saved');
        $("#divProfileErrMsg").hide();
        $("#lblSavealert").hasClass('alert alert-danger')
        $("#lblSavealert").removeClass('alert alert-danger').addClass('alert alert-success').text('Your profile has been saved');
    }
}
$("#frmCreateform").validate({
    rules: {
        txtnewProfileName: {
            required: true
        }
    },
    messages: {
        txtnewProfileName: {
            required: "Please enter the profile name"
        }
    },
    submitHandler: function(form) {
        form.submit();
    },
    errorClass: "help-inline",
    errorElement: "span",
    highlight: function(element, errorClass, validClass) {
        $(element).parents('.control-group').addClass('error')

    },
    unhighlight: function(element, errorClass, validClass) {
        $(element).parents('.control-group').removeClass('error')

    },
    errorPlacement: function(error, element) {
        error.insertAfter(element).css("margin-bottom", "0px");

    }
});

// Create new profile button action 
$("#btnNewProfileUserSubmit", "#divNewProfileUser").click(function() {
    clear_junkdata();
    $("#Profilenameerror").removeClass('alert alert-error')
    $("#Profilenameerror").text('').show();
    $("#txtnewProfileName").text('').show();
    var newProfile = $("#txtnewProfileName", "#divNewProfileUser").val();
    if ($("#frmCreateform").valid()) {
        if (newProfile.length > 0)
        {
            $("#txtProfileName").val(newProfile);
            var url = BACKENDURL + "superadmin/admin_create_profile";
            var data = {
                session_id: localStorage["SESSIONID"],
                customer_id: localStorage["CUSTOMERID"],
                profile_name: newProfile

            };
            MakeAjaxCall(url, data, createNewProfile);
        }
        else
        {
            $("#errorProfileName").removeClass("hide");
        }

    }
});

// Delete profile 

function SrvProfileDelete()
{
    $("#divSAProfileDelete").modal('hide');
    var url = BACKENDURL + "superadmin/delete_admin_profile_details";
    var data = {
        session_id: localStorage["SESSIONID"],
        customer_id: localStorage["CUSTOMERID"],
        ad_profile_id: localStorage["profile_id"]
    };
    MakeAjaxCall(url, data, ProfileDelete);
}

function ProfileDelete(data)
{
    if (data.error)
    {
        $('#DivSADeleteProfile').find('label').html('<span style="color:#4F2817;">' + data.error_msg + '</span>');
        $("#DivSADeleteProfile").modal('show');
    }
    else
    {
        //$("#btnSkinSave").text('Saved').attr("disabled", true);
        $('#DivSADeleteProfile').find('label').html('<span style="color:#4F2817;">' + " Success. The profile has been deleted." + '</span>');
        $("#DivSADeleteProfile").modal('show');
        SrvCustomerProfile();
    }
}

//New profile create

function createNewProfile(data)
{
    if (data.error)
    {
        $("#Profilenameerror").addClass("alert alert-error")
        $("#Profilenameerror").text(data.error_msg)
        return false;
    }
    if ($("#ProfileExist").hasClass("hide"))
    {
        $("#NewProfile").addClass("hide");
        $("#ProfileExist").removeClass("hide");
    }
    if (localStorage["profile_length"] == 0)
    {
        SrvCustomerProfile();
        $("#xNewUser", "#divNewProfileUser").click();
    }
    else
    {
        var data_profile = "<option value=" + data.create_profile_res + ">" + $("#txtnewProfileName", "#divNewProfileUser").val() + "</option>";
        $("#ddlUserProfile", "#ProfileExist").prepend(data_profile);
        $("#ddlUserProfile", "#ProfileExist").val(data.create_profile_res);
        clearProfile();
        ddlChangeProfile(data.create_profile_res);
        $("#xNewUser", "#divNewProfileUser").click();
    }
    $("#txtnewProfileName").val("");
    $("#divNewProfileUser").modal('hide');
    $("#lblProfile").show();
    $("#spnUserprofile").show();
}

function clearProfile()
{
    $("#Profilenameerror").text('').hide();
    $("#txtnewProfileName").val("");
}

function clear_junkdata()
{
    $("#lblSavealert").hasClass("alert alert-success")
    $("#lblSavealert").removeClass('alert alert-success').text('');
    $("#lblSavealert").hasClass("alert alert-danger")
    $("#lblSavealert").removeClass('alert alert-danger').text('');
    $("#spnProfileName").css('display', 'none');
    $("#txtProfileName").css('border-color', '#cccccc');
    $("#lblProfilename").css('color', '#333333');
    $("#btnProfileSave").text('Save').attr("disabled", true);
    $("#divProfileErrMsg").hide();
}

function ddlChangeProfile(profid)
{
    clear_junkdata();
    if (profid == 0)
        profid = $("#ddlUserProfile").val();
    $("[id*=Accord]").removeClass("hide");
    $('input[type=checkbox]:checked').removeAttr('checked');
    $('input[type=checkbox]').removeAttr('disabled', false);
    $('input[type=radio]:checked').removeAttr('checked');
    $("[id*=Inactive]").attr('checked', true);
    var url = BACKENDURL + "superadmin/get_admin_profile_details_contract";
    var data = {
        session_id: localStorage["SESSIONID"],
        customer_id: localStorage["CUSTOMERID"],
        ad_profile_id: $("#ddlUserProfile").val()

    };
    MakeAjaxCall(url, data, UserProfileData);
    $("#btnProfileSave").text('Saved').attr("disabled", true);
}

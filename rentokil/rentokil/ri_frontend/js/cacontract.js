$(document).ready(function() {
    $.support.cors = true;
    $("#AddContractModalSubmitBtnLabel").text("");
    $("#txtContractname").removeAttr('style');
//        populatecontract();
});

function populatecontract() {
    $.ajax({
        url: BACKENDURL + "customeradmin/get_contracts",
        type: "post",
        data: {
            session_id: localStorage["SESSIONID"]
        },
        dataType: "json",
        crossDomain: true,
        success: function(data) {
            if (data.session_status) {
                if (data.error == 0) { // session true error false
                    $("#selectContract").empty();
                    var contractVal;

                    if (data.contracts_res.length > 0) {
                        $("#divSelectContracts").show();
                        $("#divContractMsgBdy").html('Begin managing your contracts by creating a new contract above or selecting an existing contract from the drop down below');
                    } else {
                        $("#divContractMsgBdy").html('Begin managing your contracts by creating a new contract above');
                    }

                    for (var nCount = 0; nCount < data.contracts_res.length; nCount++) {
                        contractVal = "<option value='" + data.contracts_res[nCount].contract_id + "_" + data.contracts_res[nCount].contract_key + "_" + data.contracts_res[nCount].session_log + "'>" + data.contracts_res[nCount].contract_name + "</option>";
                        $("#selectContract").append(contractVal);
                    }
                } else {
                    alert(data.error);
                }
            } else {
                localStorage.clear();
            }
        },
        error: function(xhr, textStatus, error) {

            alert(error);
        }
    });
}

//Add contract modal click
function btnNewUser() {
    $("input[type='text']", "#divAddContract").each(function() {
        $(this).val('');

    });
}

//Add contract modal key press.
$("input[type='text']", "#divAddContract").keypress(function(e) {
    if ((e.which && e.which == 13) || (e.keyCode && e.keyCode == 13)) {
        $("#btnNewContractSubmit", "#divAddContract").click();
        return false;
    } else
        return true;
});
/*********************** go contract ******************/

function btnGoContract() {

    localStorage.setItem("contractid", " ");
    var contArr = $("#selectContract").val().split('_');
    localStorage.setItem("contractid", contArr[0]);
    localStorage.setItem("contractkey", contArr[1]);
    localStorage["Session_logs"] = contArr[2];
    localStorage.setItem("contractname", " ");
    localStorage.setItem("contractname", $("#selectContract :selected").text());
    var url = BACKENDURL + "customeradmin/save_contract_session";
    var data = {
        session_id: localStorage["SESSIONID"],
        contract_id: localStorage["contractid"]
    };
    MakeAjaxCall(url, data, SaveContractSession);
}
function SaveContractSession(data) {
    if (data.session_status) {
        if (data.error == 0) {
            location.href = "customerpage.html";
        }
        else
        {
            $("#SaveContractError").text(data.error_msg).show()
        }
    }
}

/*******************ADD contract btn clcik************************/
function btnAddContract() {
    if(localStorage["cacontract_status"] == 0){
	$("#btnAddContract").attr({"disabled":true, "href": "#"});
	bootbox.dialog({
        message: "Sorry, but your access profile does not allow you to create contracts.",
        title: "Notification!",
        buttons: {
             main: {
			  label: "Ok",
			  className: "btn-primary",
			  callback: function() {
			}
        }
    }
	});
	}else{
	$("#btnAddContract").attr("disabled",false);
	}
    $("#txtContractname").val("");
    $("#AddContractModalSubmitBtnLabel").empty();
    $("#txtContractname").removeAttr('style');
}


/**************** adding new contract ****************/
function btnNewContractSubmit() {
    localStorage.setItem("contractname", " ");
    localStorage.setItem("contractname", $("#txtContractname").val());
    $("#txtContractname").removeAttr('style');
    $("#AddContractModalSubmitBtnLabel").text(" ");
    if ($("#txtContractname", "#divAddContract").val() == "") {
        $("#txtContractname", "#divAddContract").focus();
        $("#AddContractModalSubmitBtnLabel").text("Please enter contract name");
        $("#txtContractname").each(function() {
            ModalFieldUI(this);
        });
        return false;
    } else {
        $.ajax({
            url: BACKENDURL + "customeradmin/create_contract",
            type: "post",
            data: {
                session_id: localStorage["SESSIONID"],
                customer_id: localStorage["CUSTOMERID"],
                user_id: localStorage["USERID"],
                contract_name: $("#txtContractname", "#divAddContract").val()
            },
            dataType: "json",
            crossDomain: true,
            success: function(data) {
                if (data.session_status) {
                    if (data.error == 0) {
                        $("#divAddContract").modal('hide');
                        $("#CreateContractModal").modal('show');
                        $("#CreateSubmitButton").bind("click", contractSubmitButton)
                        localStorage.setItem("contractid", " ");
                        localStorage.setItem("contractid", data.contract_id);
                        localStorage.setItem("contractkey", data.contract_key);

                    } else {
                        var getLabel = data.error_msg;
                        $("#AddContractModalSubmitBtnLabel").text("*" + getLabel);
                        $("#txtContractname").focus();
                        $("#txtContractname").each(function() {
                            ModalFieldUI(this);
                        });
                    }

                } else {
                    logout();
                }
            },
            error: function(xhr, textStatus, error) {
                alert(error);

            }
        });
    }
}

function contractSubmitButton() {
    location.href = "customerpage.html";
}
$(document).ready(function() {
    $.support.cors = true;
    
});

function setFileNameForEnergy(path) {
    $("#txtFakeFile").val(path);
}
var isUploadSuccess = false;
var activeFormType = '';
function openImportEnergy(formType) {
    activeFormType = formType;
    if (activeFormType == 'energy_document_admin') {
        $("#UploadDocumentClose").show();
        openDocumentImport();
    }
    else {
        $("#UploadDataClose").show();
        $("#divImport").modal('show');
        $("#lblImportError", "#divImport").text('').hide();
        $("#lblImportSuccess", "#divImport").text('').hide();
        $("#divImport").center();
        $("#divUploadfile", "#divImport").show().html("<table><tr><td >Upload File:</td><td nowrap='nowrap'><div style='position:relative'><input id='fileupload' type='file' name='files[]' onchange='setFileNameForEnergy(this.value);' style='width:350px;position:relative; -moz-opacity:0; text-align: right;opacity: 0; filter:alpha(opacity: 0);z-index:2'><div style='position:absolute;top:0px;left:0px;z-index:1'><input readonly type='text' id='txtFakeFile' value='Select file...'/> &nbsp;<button class='btn btn-primary' style='margin-top:-10px;'>Choose ...</button></div></div></td></tr></table>");
        $("#divProgressBar", "#divImport").hide();
        $("#btnImportFinish", "#divImport").hide();
        $("#btnImportSubmit", "#divImport").show();
        //$("#btnClose").show();

        $("#xdivImport", "#divImport").show();
        $('#divProgressBar .bar').css('width', '0%');

        $('#fileupload table tbody tr.template-download').remove();

        $('#fileupload').fileupload({
            dataType: 'json',
            url: BACKENDURL + "data_upload/import_data",
            formData: {
                "session_id": localStorage["SESSIONID"],
                "import_type": activeFormType,
                "customer_id": localStorage["CUSTOMERID"],
                "user_id": localStorage["USERID"],
                "contract_id": localStorage.getItem("contractid")
            },
            add: function(e, data) {
                $("#btnImportSubmit", "#divImport").off('click').on('click', function() {
                    // alert(data.error_msg);
                    var goUpload = true;
                    var uploadFile = data.files[0];

                    if (uploadFile == "") {
                        $("#lblImportError", "#divImport").text('Please select a file to upload').show();
                        goUpload = false;
                    }
                    if (!(/\.(xlsx|xls|csv)$/i).test(uploadFile.name)) {
                        $("#lblImportError", "#divImport").text('You must select an excel (.xlsx or .xls or .csv) file only').show();
                        goUpload = false;
                    }
                    if (uploadFile.size > 1000000) { // 1mb
                        $("#lblImportError", "#divImport").text('Please upload a smaller file, max size is 1 MB').show();
                        goUpload = false;
                    }
                    if (goUpload == true) {
                        $("#lblImportError", "#divImport").text('').hide();
                        $("#divProgressBar", "#divImport").show();
                        $("#divUploadfile", "#divImport").hide();
                        $("#divProgressVal", "#divImport").attr('style', 'width: 0%');
                        $("#btnImportSubmit", "#divImport").hide();
                        $("#xdivImport", "#divImport").hide();
                        $('#divProgressBar .bar').css('width', '0%');
                        data.submit();
                    } 
					//DE 528 same behaviour as in people services ->import pupils
					// else {
                        // alert('error');
                    // }
                });
            },
            progressall: function(e, data) {
                $("#UploadDataClose").hide();
                // alert(data.error_msg);
                var progress = parseInt(data.loaded / data.total * 100, 10);
                //if(progress <=80)
                $('#divProgressBar .bar').css('width', progress + '%');
                $("#btnImportFinish", "#divImport").addClass("disabled").show().off('click');
                if (progress == 100 && isUploadSuccess) {
                    $("#btnImportFinish", "#divImport").removeClass("disabled").off('click').on('click', {f: activeFormType}, finishImport);
                    isUploadSuccess = false;
                }
            },
            success: function(data) {
                if (data.error) {
                    $("#lblImportSuccess", "#divImport").text("").hide();
                    $("#lblImportError", "#divImport").text("Unsuccessful. Failure reason: " + data.error_msg).show();
                } else {
                    $("#lblImportError", "#divImport").text("").hide();
                    $("#lblImportSuccess", "#divImport").text("Success. File was succesfully uploaded and imported into the system.").show();
                }
                $("#btnImportFinish", "#divImport").removeClass("disabled").off('click').on('click', {f: activeFormType}, finishImport);
                isUploadSuccess = true;
            },
            done: function(e, data) {
                if (data.result.error) {
                    $("#lblImportSuccess", "#divImport").text("").hide();
                    $("#lblImportError", "#divImport").text("Unsuccessful. Failure reason: " + data.result.error_msg).show();
                } else {
                    $("#lblImportError", "#divImport").text("").hide();
                    $("#lblImportSuccess", "#divImport").text("Success. File was succesfully uploaded and imported into the system.").show();
                }
                $("#btnImportFinish", "#divImport").removeClass("disabled").off('click').on('click', {f: activeFormType}, finishImport);
                isUploadSuccess = true;

                // alert(data.error_msg);
                $("#btnImportSubmit", "#divImport").off('click').on('click', function() {
                    $("#lblImportError", "#divImport").text('Please select a file to upload').show();
                });

                //$("#lblImportError", "#divImport").text('Success or failure based on the status');
                // Need to enable the finish button
                // alert("Done completed.....");
                // $.each(data.result.files, function (index, file) {
                // alert(file.name);
                // $('<p/>').text(file.name).appendTo(document.body);
                // });
            }
        });
    }
}

$("#btnImportSubmit", "#divImport").click(function() {
    $("#lblImportError", "#divImport").text('Please select a file to upload').show();
});
function finishImport(event) {
    var formtype = event.data.f;
    if (formtype == "hh") {
        loadHHreport();
    } else if (formtype == "nhh") {
        loadNHHreport();
    } else if (formtype == "target") {
        loadTargetData();
    } else if (formtype == "setup") {
        loadSetUpEntities();
    } else if (formtype == "energy_document_admin") {
        loadMyDocuments();
    }
}
function loadHHreport() {
    $.ajax({
        url: BACKENDURL + "customeradmin/get_HH_reports",
        type: "post",
        data: {
            session_id: localStorage["SESSIONID"],
            contract_id: localStorage["contractid"],
        },
        dataType: "json",
        crossDomain: true,
        success: function(data) {
            if (data.session_status) {
                if (data.error == 0) { // session true error false
                    $("#divImport").modal('hide');
                    $("#tablePagination", "#hhreports").remove();
                    var content;
                    if (data.HH_reports_res.length > 0) {
                        content = "<table class='no-more-tables table table-hover table-striped  edfm-bordered-table' id='HhtblHeader' ><thead><tr><th>Filename</th><th>File Imported Date</th><th>Last Modified On</th><th>Last Modified By</th><th>New Records Added</th><th>Existing Records Amended</th><th></th></tr></thead><tbody>";
                    }
                    for (var nCount = 0; nCount < data.HH_reports_res.length; nCount++) {
                        var btn = "";
                        if (data.HH_reports_res[nCount].upload_status == "0") {
                            btn = "<button class='btn btn-danger btn-small' onclick='javascript:return downloadFile(" + data.HH_reports_res[nCount].file_id + ",\"hh\");' id='HHfailedBtn" + data.HH_reports_res[nCount].file_id + "' style='margin-top: 0px;width:190px'><i class='icon-circle-arrow-down icon-white' ></i> Download Failed Import</button>";
                        } else {
                            //btn = "<button class='btn btn-small' style='margin-top: 0px;width:176px' onclick='javascript:return openHistory(" + data.HH_reports_res[nCount].file_id + ",\"hh\",\"" + data.HH_reports_res[nCount].file_name + "\");' id='HHhistoryBtn" + data.HH_reports_res[nCount].file_id + "' ><i class='icon-time' ></i> View Revision History</button>"
                            btn = "<button class='btn btn-success btn-small' onclick='javascript:return downloadFile(" + data.HH_reports_res[nCount].file_id + ",\"hh\");' id='HHfailedBtn" + data.HH_reports_res[nCount].file_id + "' style='margin-top: 0px;width:190px'><i class='icon-circle-arrow-down icon-white' ></i> Download Successful Import</button>"
                        }
                        var lstModified = ((data.HH_reports_res[nCount].last_mod_date).length > 1) ? data.HH_reports_res[nCount].last_mod_date : "-";
                        content += "<tr ><td>" + data.HH_reports_res[nCount].file_name + "</td> <td>" + data.HH_reports_res[nCount].cdate + "</td><td style='text-align: center;'>" + lstModified + "</td><td>" + data.HH_reports_res[nCount].last_mod_by + "</td><td>" + data.HH_reports_res[nCount].New_Records_Added + "</td><td>" + data.HH_reports_res[nCount].Existing_Records_Amended + "</td><td nowrap='nowrap'>" + btn + "</td></tr>"
                    }
                    if (data.HH_reports_res.length > 0) {
                        content += "</tbody></table>";
                    }
                    $("#divhhreports", "#hhreports").html(content);
                    if (data.HH_reports_res.length > 10)
                        $("#divhhreports").tablePagination({});
                } else { // session true error true
                    logout(1);
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

function loadNHHreport() {
    $.ajax({
        url: BACKENDURL + "customeradmin/get_NHH_reports",
        type: "post",
        data: {
            session_id: localStorage["SESSIONID"],
            contract_id: localStorage["contractid"],
        },
        dataType: "json",
        crossDomain: true,
        success: function(data) {
            if (data.session_status) {
                if (data.error == 0) { // session true error false
                    $("#divImport").modal('hide');
                    $("#tablePagination", "#nhhreports").remove();
                    var content;
                    if (data.NHH_reports_res.length > 0) {
                        content = "<table class=' no-more-tables table table-hover table-striped edfm-bordered-table' id='NhhtblHeader' ><thead><tr><th>Filename</th><th>File Imported Date</th><th>Last Modified On</th><th>Last Modified By</th><th>New Records Added</th><th>Existing Records Amended</th><th></th></tr></thead><tbody>";
                    }
                    for (var nCount = 0; nCount < data.NHH_reports_res.length; nCount++) {
                        var btn = "";

                        if (data.NHH_reports_res[nCount].upload_status == "0") {
                            btn = "<button class='btn btn-danger btn-small' onclick='javascript:return downloadFile(" + data.NHH_reports_res[nCount].file_id + ",\"nhh\");' style='margin-top: 0px;width:190px'><i class='icon-circle-arrow-down icon-white' ></i> Download Failed Import</button>";

                        } else {
                            //btn = "<button class='btn btn-small' style='margin-top: 0px;width:176px' onclick='javascript:return openHistory(" + data.NHH_reports_res[nCount].file_id + ",\"nhh\",\"" + data.NHH_reports_res[nCount].file_name + "\");' id='HHhistoryBtn" + data.NHH_reports_res[nCount].file_id + "' ><i class='icon-time' ></i> View Revision History</button>"
                            btn = "<button class='btn btn-success btn-small' onclick='javascript:return downloadFile(" + data.NHH_reports_res[nCount].file_id + ",\"nhh\");' style='margin-top: 0px;width:190px'><i class='icon-circle-arrow-down icon-white' ></i> Download Successful Import</button>";
                        }
                        var lstModified = ((data.NHH_reports_res[nCount].last_mod_date).length > 1) ? data.NHH_reports_res[nCount].last_mod_date : "-";
                        content += "<tr ><td>" + data.NHH_reports_res[nCount].file_name + "</td> <td>" + data.NHH_reports_res[nCount].cdate + "</td><td style='text-align: center;'>" + lstModified + "</td><td>" + data.NHH_reports_res[nCount].last_mod_by + "</td><td>" + data.NHH_reports_res[nCount].New_Records_Added + "</td><td>" + data.NHH_reports_res[nCount].Existing_Records_Amended + "</td><td nowrap='nowrap'>" + btn + "</td></tr>"
                    }
                    if (data.NHH_reports_res.length > 0) {
                        content += "</tbody></table>";
                    }
                    $("#divnhhreports", "#nhhreports").html(content);
                    if (data.NHH_reports_res.length > 10)
                        $("#divnhhreports").tablePagination({});
                } else { // session true error true
                    logout(1);
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


function loadTargetData() {
    $.ajax({
        url: BACKENDURL + "customeradmin/get_target_data",
        type: "post",
        data: {
            session_id: localStorage["SESSIONID"],
            contract_id: localStorage["contractid"],
        },
        dataType: "json",
        crossDomain: true,
        success: function(data) {
            if (data.session_status) {
                if (data.error == 0) { // session true error false
                    $("#divImport").modal('hide');
                    $("#tablePagination", "#targetdata").remove();
                    var content;
                    if (data.target_data_res.length > 0) {
                        content = "<table class='no-more-tables table table-hover table-striped edfm-bordered-table' id='TargettblHeader' ><thead><tr><th>Filename</th><th>File Imported Date</th><th>Last Modified On</th><th>Last Modified By</th><th>New Records Added</th><th>Existing Records Amended</th><th></th></tr></thead><tbody>";
                    }
                    for (var nCount = 0; nCount < data.target_data_res.length; nCount++) {
                        var btn = "";

                        if (data.target_data_res[nCount].upload_status == "0") {
                            btn = "<button class='btn btn-danger btn-small' onclick='javascript:return downloadFile(" + data.target_data_res[nCount].file_id + ",\"target\");' style='margin-top: 0px;width:190px'><i class='icon-circle-arrow-down icon-white' ></i> Download Failed Import</button>";
                        } else {
                            //btn = "<button class='btn btn-small' style='margin-top: 0px;width:176px' onclick='javascript:return openHistory(" + data.target_data_res[nCount].file_id + ",\"target\",\"" + data.target_data_res[nCount].file_name + "\");' id='HHhistoryBtn" + data.target_data_res[nCount].file_id + "' ><i class='icon-time' ></i> View Revision History</button>";
                            btn = "<button class='btn btn-success btn-small' onclick='javascript:return downloadFile(" + data.target_data_res[nCount].file_id + ",\"target\");' style='margin-top: 0px;width:190px'><i class='icon-circle-arrow-down icon-white' ></i> Download Successful Import</button>";

                        }
                        var lstModified = ((data.target_data_res[nCount].last_mod_date).length > 1) ? data.target_data_res[nCount].last_mod_date : "-";
                        content += "<tr ><td>" + data.target_data_res[nCount].file_name + "</td> <td>" + data.target_data_res[nCount].cdate + "</td><td style='text-align: center;'>" + lstModified + "</td><td>" + data.target_data_res[nCount].last_mod_by + "</td><td>" + data.target_data_res[nCount].New_Records_Added + "</td><td>" + data.target_data_res[nCount].Existing_Records_Amended + "</td><td nowrap='nowrap'>" + btn + "</td></tr>"
                    }
                    if (data.target_data_res.length > 0) {
                        content += "</tbody></table>";
                    }
                    $("#divtargetimport", "#targetdata").html(content);

                    if (data.target_data_res.length > 10)
                    {
                        $("#tablePagination").html('');
                        $("#divtargetimport").tablePagination({});
                    }
                } else { // session true error true
                    logout(1);
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


function loadSetUpEntities() {
    $.ajax({
        url: BACKENDURL + "customeradmin/get_setup_entities",
        type: "post",
        data: {
            session_id: localStorage["SESSIONID"],
            contract_id: localStorage["contractid"],
        },
        dataType: "json",
        crossDomain: true,
        success: function(data) {
            if (data.session_status) {
                if (data.error == 0) { // session true error false
                    $("#divImport").modal('hide');
                    $("#tablePagination", "#setup").remove();
                    var content;
                    if (data.setup_entities_res.length > 0) {
                        content = "<table class='no-more-tables table table-hover table-striped edfm-bordered-table' id='tblsetupHeader' ><thead><tr><th>Filename</th><th>File Imported Date</th><th>Last Modified On</th><th>Last Modified By</th><th>New Records Added</th><th>Existing Records Amended</th><th></th></tr></thead><tbody>";
                    }

                    for (var nCount = 0; nCount < data.setup_entities_res.length; nCount++) {
                        var btn = "";
                        if (data.setup_entities_res[nCount].upload_status == "0") {
                            btn = "<button class='btn btn-danger btn-small' onclick='javascript:return downloadFile(" + data.setup_entities_res[nCount].file_id + ",\"setup\");' style='margin-top: 0px;width:190px'><i class='icon-circle-arrow-down icon-white' ></i> Download Failed Import</button>";
                        } else {
                            //btn = "<button class='btn btn-small' style='margin-top: 0px;width:176px' onclick='javascript:return openHistory(" + data.setup_entities_res[nCount].file_id + ",\"setup\",\"" + data.setup_entities_res[nCount].file_name + "\");' id='historyBtn" +  data.setup_entities_res[nCount].file_id  + "'  ><i class='icon-time' ></i> View Revision History</button>"
                            btn = "<button class='btn btn-success btn-small' onclick='javascript:return downloadFile(" + data.setup_entities_res[nCount].file_id + ",\"setup\");' style='margin-top: 0px;width:190px'><i class='icon-circle-arrow-down icon-white' ></i> Download Successful Import</button>";
                        }
                        var lstModified = ((data.setup_entities_res[nCount].last_mod_date).length > 1) ? data.setup_entities_res[nCount].last_mod_date : "-";
                        content += "<tr ><td>" + data.setup_entities_res[nCount].file_name + "</td> <td > " + data.setup_entities_res[nCount].cdate + "</td><td style='text-align: center;' >" + lstModified + "</td><td>" + data.setup_entities_res[nCount].last_mod_by + "</td><td >" + data.setup_entities_res[nCount].New_Records_Added + "</td><td>" + data.setup_entities_res[nCount].Existing_Records_Amended + "</td><td nowrap='nowrap'>" + btn + "</td></tr>"
                    }
                    if (data.setup_entities_res.length > 0) {
                        content += "</tbody></table>";
                    }
                    $("#divsetupentities", "#setup").html(content);
                    if (data.setup_entities_res.length > 10)
                    {
                        $("#tablePagination").html('');
                        $("#divsetupentities").tablePagination({});
                    }

                } else { // session true error true
                    logout(1);
                    // $("#lblImportError").text(error);
                    //$("#divImport").modal('hide');
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

function openHistory(fileId, formType, fileName) {

    $.ajax({
        url: BACKENDURL + "customeradmin/get_data_history",
        type: "post",
        data: {
            session_id: localStorage["SESSIONID"],
            contract_id: localStorage["contractid"],
            form_type: formType,
            file_id: fileId,
        },
        dataType: "json",
        crossDomain: true,
        success: function(data) {
            if (data.session_status) {
                if (data.error == 0) { // session true error false
                    $("#HistoryLabel", "#HistoryModalBox").text("Revision history for " + fileName);
                    var content = "";
                    if (data.history_data_res.length > 0) {
                        content = "<table class='table table-hover table-striped  edfm-bordered-table' id='tblHistory' name='tblHistory'><thead><tr><th >Modified date/time</th><th >Records since ammended</th><th >By User</th></tr></thead><tbody>";
                    }
                    for (var nCount = 0; nCount < data.history_data_res.length; nCount++) {
                        content += "<tr><td>" + data.history_data_res[nCount].modified_date + "</td><td>" + data.history_data_res[nCount].Records_ammended + "</td><td>" + data.history_data_res[nCount].Modified_user + "</td></tr>";
                    }
                    if (data.history_data_res.length > 0) {
                        content += "</tbody></table>";
                    } else {
                        content = "<div id='lblImportWell' class='well well-small'>No history found.</div>";
                    }

                    $("#divHistory", "#HistoryModalBox").html(content);
                    $("#HistoryModalBox").modal('show');

                } else { // session true error true
                    alert(data.error);
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

function downloadFile(fileId, formType, fileName) {
    var url = BACKENDURL + "common/download_file";
    window.open(url + "/" + localStorage["SESSIONID"] + "/" + formType + "/" + fileId);
}
/*********************start admin energy document******************/
var energy_rep_comm_stats = "";
var Document_def_status = 0;
var isNewUpload = false;
function loadMyDocuments() {
    $.support.cors = true;
    //First get all school documents and populate it
    $.ajax({
        url: BACKENDURL + "customeradmin/get_energy_documents",
        type: "post",
        data: {
            session_id: localStorage["SESSIONID"],
            contract_id: localStorage["contractid"],
            hide_comp: $("#chkHideComplete").is(':checked'),
        },
        dataType: "json",
        crossDomain: true,
        success: function(data) {
            if (data.session_status) {
                if (data.error == 0) {
                    $("#divImport").modal('hide');
                    //Populate the document status....
                    energy_rep_comm_stats = "";

                    for (var nCount = 0; nCount < data.energy_documents_res[0].energy_document_status_res.length; nCount++) {
                        if (nCount == 0)
                            Document_def_status = data.energy_documents_res[0].energy_document_status_res[nCount].data_value_id;
                        energy_rep_comm_stats += "<option value=" + data.energy_documents_res[0].energy_document_status_res[nCount].data_value_id + ">" + data.energy_documents_res[0].energy_document_status_res[nCount].data_value + "</option>";
                    }
                    $("#ddlDocumentStatus", "#divUploadUpdate").empty().append(energy_rep_comm_stats).unbind('change');

                    //populate the document list....
                    var nCurrRecRound = ($("#currPageNumber", "#documents").val() != undefined) ? ($("#currPageNumber", "#documents").val() - 1) : 0;

                    $("#tablePagination", "#documents").remove();
                    $("#tblUploaddashBoard > tbody > tr").remove();
                    if (data.energy_documents_res[1].energy_rep.length > 0) {
                        $("#divdocuments", "#documents").hide();
                        $("#divfilter", "#documents").show();
                        for (var nCount = 0; nCount < data.energy_documents_res[1].energy_rep.length; nCount++) {
                            var documentId = data.energy_documents_res[1].energy_rep[nCount].energy_documents_id;
                            var commentText = (data.energy_documents_res[1].energy_rep[nCount].comm_status == 0) ? "<span id='new" + documentId + "'><i class='icon-envelope'></i><span style='color:black;font-weight:bold;'> New! </span></span>" + data.energy_documents_res[1].energy_rep[nCount].comment : data.energy_documents_res[1].energy_rep[nCount].comment;
                            var deleteModalText = (data.energy_documents_res[1].energy_rep[nCount].status == "Completed") ? "divUploadDelete" : "divUploadDeleteFail";
                            var btn = "";


                            if (data.energy_documents_res[1].energy_rep[nCount].status == "Not Started")
                            {
                                btn += "<span class='label label-important' id='rpSt" + documentId + "'>" + data.energy_documents_res[1].energy_rep[nCount].status + "</span>";
                            }
                            else if (data.energy_documents_res[1].energy_rep[nCount].status == "Completed")
                            {
                                btn += "<span class='label label-success' id='rpSt" + documentId + "'>" + data.energy_documents_res[1].energy_rep[nCount].status + "</span> ";

                            }
                            else if (data.energy_documents_res[1].energy_rep[nCount].status == "In Progress")
                            {
                                btn += "<span class='label label-info' id='rpSt" + documentId + "'>" + data.energy_documents_res[1].energy_rep[nCount].status + "</span> ";

                            }

                            var tblUpload = "<tr><td style='width:440px'><h3 style='margin:0;'>" + data.energy_documents_res[1].energy_rep[nCount].file_name + "</h3><p style='font-size:14px;color:black;margin-bottom:0px'>Uploaded on " + data.energy_documents_res[1].energy_rep[nCount].cdate + " by " + data.energy_documents_res[1].energy_rep[nCount].username + "&nbsp&nbsp" + btn + " </p></i><p style='color:black;'>" + commentText + "</p></td> <td style='text-align:right;'><a href='javascript:void(0);' onclick='javascript:downloadDocument(this," + documentId + ");' style='text-decoration: none !important;' id='adminbuildingdownload'>Download &nbsp;&nbsp;<i class='icon-download'> </i></a><br /><span id='upRe_" + documentId + "'><a href='#divUploadUpdate' data-toggle='modal' onclick='javascript:loadUpdateDocument(" + documentId + ", " + data.energy_documents_res[1].energy_rep[nCount].document_status + ");' style='text-decoration: none !important;' id='adminbuildingupdate'>Update &nbsp;&nbsp;<i class='icon-edit'></i></a></span><br /><span id='dlRe_" + documentId + "'><a href='#" + deleteModalText + "' onclick='javascript:loadDeleteDocument(" + documentId + ",\"" + deleteModalText + "\");' style='text-decoration: none !important;' data-toggle='modal' id='adminbuildingdelete'>Delete &nbsp;&nbsp;<i class='icon-remove'></i></a></span></td></tr>";

                            $("#tblUploaddashBoard  tbody:last").append(tblUpload);
                        }
                        $("#tblUploaddashBoard").show();
                        if (data.energy_documents_res[1].energy_rep.length > 10) {
                            if (isNewUpload)
                            {
                                nCurrRecRound = 0;
                            }
                            $("#divfilter").tablePagination({
                                currPage: nCurrRecRound + 1
                            });
                        }
                        $("#UploadDeleteYes", "").off('click').bind("click", deleteDocument);
                        isNewUpload = false;
                    } else
                        $("#divdocuments", "#documents").show();
                } else
                    logout(1);
            } else {
                logout();
            }
            customerModuleAccess('AL3BDOC', 1);
        },
        error: function(xhr, textStatus, error) {
            alert(error);
        }
    });

}
function openDocumentImport() {
    var isUploadSuccess = false;
    var activeFormType = 'energy_document_admin';
    $("#divUploadNewImport").modal('show');
    $("#lblImportError", "#divUploadNewImport").text('').hide();
    $("#lblImportSuccess", "#divUploadNewImport").text('').hide();
    $("#divUploadNewdocuments", "#divUploadNewImport").show().html("<table cellpadding='4'><tr><td >Upload File</td><td nowrap='nowrap'><div style='position:relative'><input id='fileupload_document' type='file' name='files[]' onchange='setDocumentFileNameFromPath(this.value);' style='width:350px;position:relative; -moz-opacity:0; text-align: right;opacity: 0; filter:alpha(opacity: 0);z-index:2'><div style='position:absolute;top:0px;left:0px;z-index:1'><input readonly type='text' id='txtFakeFile_document' value='Select file...'/> &nbsp;<button class='btn btn-primary' style='margin-top:-10px;'>Choose ...</button></div></div></td></tr><tr><td >Comment</td><td nowrap='nowrap'><textarea id='txaComments' placeholder='Enter text....' style='width:88%;height:100%;resize:none;margin-top:15px;'></textarea></td></tr></table>");
    $("#divProgressBar", "#divUploadNewImport").hide();
    $("#btnDocumentImportFinish", "#divUploadNewImport").hide();
    $("#btnDocumentImportSubmit", "#divUploadNewImport").show();
    $("#xdivUploadNewImport", "#divUploadNewImport").show();
    $('#divProgressBar .bar').css('width', '0%');
    $('#fileupload_document table tbody tr.template-download').remove();

    $('#fileupload_document').fileupload({
        dataType: 'json',
        url: BACKENDURL + "data_upload/import_data",
        add: function(e, data) {
            $("#btnDocumentImportSubmit", "#divUploadNewImport").off('click').on('click', function() {
                var goUpload = true;
                var uploadFile = data.files[0];
                if (uploadFile == "") {
                    $("#lblImportError", "#divUploadNewImport").text('Please select a file to upload').show();
                    goUpload = false;
                }
                if (uploadFile.size > 1000000) { // 1mb
                    $("#lblImportError", "#divUploadNewImport").text('Please upload a smaller file, max size is 1 MB').show();
                    goUpload = false;
                }
                if (goUpload == true) {
                    $("#lblImportError", "#divUploadNewImport").text('').hide();
                    $("#lblImportSuccess", "#divUploadNewImport").hide();
                    $("#divProgressBar", "#divUploadNewImport").show();
                    $("#divUploadNewdocuments", "#divUploadNewImport").hide();
                    $("#divProgressVal", "#divUploadNewImport").attr('style', 'width: 0%');
                    $("#btnDocumentImportSubmit", "#divUploadNewImport").hide();
                    $("#xdivUploadNewImport", "#divUploadNewImport").hide();
                    $('#divProgressBar .bar').css('width', '0%');
                    data.formData = {
                        "session_id": localStorage["SESSIONID"],
                        "import_type": activeFormType,
                        "customer_id": localStorage["CUSTOMERID"],
                        "user_id": localStorage["USERID"],
                        "contract_id": localStorage["contractid"],
                        "comments": $("#txaComments", "#divUploadNewdocuments").val(),
                        "document_status": Document_def_status,
                    };
                    data.submit();
                } else {
                    alert('error');
                }
            });
        },
        progressall: function(e, data) {
            $("#UploadDocumentClose").hide();
            var progress = parseInt(data.loaded / data.total * 100, 10);
            $('#divProgressBar .bar').css('width', progress + '%');
            $("#btnDocumentImportFinish", "#divUploadNewImport").addClass("disabled").show().off('click');
            if (progress == 100 && isUploadSuccess) {
                $("#btnDocumentImportFinish", "#divUploadNewImport").removeClass("disabled").off('click').on('click', {
                    f: activeFormType
                }, finishImport);
                isUploadSuccess = false;
            }
        },
        success: function(data) {
            isNewUpload = true;
            if (data.error) {
                $("#lblImportSuccess", "#divUploadNewImport").text("").hide();
                $("#lblImportError", "#divUploadNewImport").text("Unsuccessful. Failure reason: " + data.error_msg).show();
            } else {
                $("#lblImportError", "#divUploadNewImport").text("").hide();
                $("#lblImportSuccess", "#divUploadNewImport").text("Success. File was succesfully uploaded and imported into the system.").show();
            }
            $("#btnDocumentImportFinish", "#divUploadNewImport").removeClass("disabled").off('click').on('click', {
                f: activeFormType
            }, finishImport);
            isUploadSuccess = true;
        },
        done: function(e, data) {
            if (data.result.error) {
                $("#lblImportSuccess", "#divUploadNewImport").text("").hide();
                $("#lblImportError", "#divUploadNewImport").text("Unsuccessful. Failure reason: " + data.result.error_msg).show();
            } else {
                $("#lblImportError", "#divUploadNewImport").text("").hide();
                $("#lblImportSuccess", "#divUploadNewImport").text("Success. File was succesfully uploaded and imported into the system.").show();
            }
            $("#btnDocumentImportFinish", "#divUploadNewImport").removeClass("disabled").off('click').on('click', {
                f: activeFormType
            }, finishImport);
            isUploadSuccess = true;

            // alert(data.error_msg);
            $("#btnDocumentImportSubmit", "#divUploadNewImport").off('click').on('click', function() {
                $("#lblImportError", "#divUploadNewImport").text('Please select a file to upload').show();
            });
        }
    });
}
$("#btnDocumentImportSubmit", "#divUploadNewImport").click(function() {
    $("#lblImportError", "#divUploadNewImport").text('Please select a file to upload').show();
});

function setDocumentFileNameFromPath(path) {

    $("#txtFakeFile_document").val(path);
}
function loadUpdateDocument(documentId, document_status) {
    $.support.cors = true;
    $.ajax({
        url: BACKENDURL + "customeradmin/get_energy_document_comments",
        type: "post",
        data: {
            session_id: localStorage["SESSIONID"],
            contract_id: localStorage["contractid"],
            energy_documents_id: documentId,
        },
        dataType: "json",
        crossDomain: true,
        success: function(data) {
            if (data.session_status) {
                if (data.error == 0) {
                    $("#hdnDocumentId", "#divUploadUpdate").val(documentId);
                    $("#btnDocumentSave", "#divUploadUpdate").attr("disabled", "disabled").text('Saved');
                    //Populate the status
                    $("#ddlDocumentStatus", "#divUploadUpdate").val(document_status).unbind('change').change(function() {
                        if ($(this).val() != document_status)
                            $("#btnDocumentSave", "#divUploadUpdate").removeAttr("disabled").text('Save').unbind("click").bind("click", updateDocumentStatus);
                        else
                            $("#btnDocumentSave", "#divUploadUpdate").attr("disabled", "disabled").unbind("click").text('Saved');
                    });

                    var comm_str = "";
                    var pull_str = "pull-left";
                    var color = "alert alert-success";
                    var textcolor = "text_success";
                    //populate comments.
                    for (var i = 0; i < data.energy_rep_comm_res.length; i++) {
                        pull_str = (data.energy_rep_comm_res[i].role_name == "User") ? "pull-left" : "pull-right";
                        color = (data.energy_rep_comm_res[i].role_name == "User") ? "alert alert-success" : "alert alert-info";
                        textcolor = (data.energy_rep_comm_res[i].role_name == "User") ? "text_success" : "text_info";
                        comm_str += "<div class='row-fluid'><div class='span9 " + pull_str + "'><pre class='" + color + "'><label class='" + textcolor + "' >" + data.energy_rep_comm_res[i].comment_text + "</label><i class='icon-comment'></i> <label class='" + textcolor + "'  style='font-size:11px;margin-top: -20px;margin-left: 22px;' >By " + data.energy_rep_comm_res[i].username + " on " + data.energy_rep_comm_res[i].cdate + "</i></pre></div></div>";
                    }
                    $("#divDocumentComments", "#divUploadUpdate").html(comm_str);
                    //updating new icon...
                    $("#new" + documentId, "#documents").hide();

                    $("#btnSaveComments", "#divUploadUpdate").attr("disabled", "disabled").unbind("click");
                    //bind the comments textbox.
                    $("#txaComments", "#divUploadUpdate").val('').bind("keypress keyup focus", function() {
                        if ($("#txaComments", "#divUploadUpdate").val() != "")
                            $("#btnSaveComments", "#divUploadUpdate").removeAttr("disabled").text('Post').unbind("click").bind("click", insertDocumentComments);
                        else
                            $("#btnSaveComments", "#divUploadUpdate").attr("disabled", "disabled").unbind("click");
                    });
                } else
                    logout(1);
            } else
                logout();
        },
        error: function(xhr, textStatus, error) {
            alert(error);
        }
    });
}

function updateDocumentStatus() {
    $.support.cors = true;
    $("#btnDocumentSave", "#divUploadUpdate").attr("disabled", true).text('Saving');
    var documentId = $("#hdnDocumentId", "#divUploadUpdate").val();
    var document_status = $("#ddlDocumentStatus", "#divUploadUpdate").val();
    $.ajax({
        url: BACKENDURL + "customeradmin/update_energy_document_status",
        type: "post",
        data: {
            session_id: localStorage["SESSIONID"],
            contract_id: localStorage["contractid"],
            energy_documents_id: documentId,
            status: document_status
        },
        dataType: "json",
        crossDomain: true,
        success: function(data) {
            if (data.session_status) {
                if (data.error == 0) {
                    //	        			$("#divUploadUpdate").modal('hide');
                    //	        			loadMyDocuments();
                    //Update the document status....

                    var rpStatus = $("#ddlDocumentStatus", "#divUploadUpdate").children(':selected').text();
                    $("#rpSt" + documentId, "#documents").text(rpStatus);
                    if (rpStatus == "Not Started")
                    {

                        $("#rpSt" + documentId, "#documents").removeClass();
                        $("#rpSt" + documentId, "#documents").addClass('label label-important');
                    }
                    else if (rpStatus == "Completed")
                    {
                        if ($("#chkHideComplete").is(':checked')) {
                            loadMyDocuments();
                        }
                        $("#rpSt" + documentId, "#documents").removeClass();
                        $("#rpSt" + documentId, "#documents").addClass('label label-success');

                    }

                    else if (rpStatus == "In Progress")
                    {

                        $("#rpSt" + documentId, "#documents").removeClass();
                        $("#rpSt" + documentId, "#documents").addClass('label label-info');
                    }

                    var deleteModalText = (rpStatus == "Completed") ? "divUploadDelete" : "divUploadDeleteFail";
                    $("#dlRe_" + documentId, "#documents").html("<a href='#" + deleteModalText + "' onclick='javascript:loadDeleteDocument(" + documentId + ",\"" + deleteModalText + "\");' style='text-decoration: none !important;' data-toggle='modal'>Delete &nbsp;&nbsp;<i class='icon-remove'></i></a>");
                    $("#upRe_" + documentId, "#documents").html("<a href='#divUploadUpdate' data-toggle='modal' onclick='javascript:loadUpdateDocument(" + documentId + ", " + document_status + ");' style='text-decoration: none !important;'>Update &nbsp;&nbsp;<i class='icon-edit'></i></a>");
                    $("#btnDocumentSave", "#divUploadUpdate").attr("disabled", true).text('Saved');
                    $("#ddlDocumentStatus", "#divUploadUpdate").unbind('change').change(function() {
                        if ($(this).val() != document_status)
                            $("#btnDocumentSave", "#divUploadUpdate").removeAttr("disabled").text('Save').bind("click", updateDocumentStatus);
                        else
                            $("#btnDocumentSave", "#divUploadUpdate").attr("disabled", "disabled").unbind("click").text('Saved');
                    });
                } else
                    logout(1);
            } else
                logout();
        },
        error: function(xhr, textStatus, error) {
            alert(error);
        }
    });
}

function insertDocumentComments() {

    $.support.cors = true;
    var document_status = $("#ddlDocumentStatus", "#divUploadUpdate").val();
    $.ajax({
        url: BACKENDURL + "customeradmin/insert_energy_document_comments",
        type: "post",
        data: {
            session_id: localStorage["SESSIONID"],
            contract_id: localStorage["contractid"],
            energy_documents_id: $("#hdnDocumentId", "#divUploadUpdate").val(),
            comments: $("#txaComments", "#divUploadUpdate").val()
        },
        dataType: "json",
        crossDomain: true,
        success: function(data) {
            if (data.session_status) {
                if (data.error == 0) {
                    $("#divUploadUpdate").modal('hide');
                    loadMyDocuments();
                } else
                    logout(1);
            } else
                logout();
        },
        error: function(xhr, textStatus, error) {
            alert(error);
        }
    });
}


function loadDeleteDocument(documentId, modalId) {
    $("#hdnDocumentId", "#" + modalId).val(documentId);
}

function deleteDocument() {
    var documentId = $("#hdnDocumentId", "#divUploadDelete").val();
    $.support.cors = true;
    $.ajax({
        url: BACKENDURL + "customeradmin/delete_energy_document",
        type: "post",
        data: {
            session_id: localStorage["SESSIONID"],
            contract_id: localStorage["contractid"],
            energy_documents_id: documentId,
        },
        dataType: "json",
        crossDomain: true,
        success: function(data) {
            if (data.session_status) {
                if (data.error == 0) {
                    $("#divUploadDelete").modal('hide');
                    $("#UploadDeleteIdY").modal('show');
                    loadMyDocuments();
                } else
                    logout(1);
            } else
                logout();
        },
        error: function(xhr, textStatus, error) {
            alert(error);
        }
    });
}

function downloadDocument(f, documentId) {
    var url = BACKENDURL + "common/download_file";
    window.open(url + "/" + localStorage["SESSIONID"] + "/energy_document/" + documentId);
}
/*********************end admin energy report******************/
/************************Purge data****************************/
function purgeData() {
    customerModuleAccess('AL3PRG', 1);
    var today = new Date();
    var t = today.getDate() + "/" + (today.getMonth()) + "/" + today.getFullYear();
    var m = (today.getMonth() + 1) + "/" + today.getFullYear();
    //month view and hiding picker on change
    $('#divPurgeStartdate').datepicker({minViewMode: 1, placement: 'left', }).on('changeDate', function() {
        $('#divPurgeStartdate').datepicker('hide');
    });
    $('#divPurgeEnddate').datepicker({minViewMode: 1, placement: 'left', }).on('changeDate', function() {
        $('#divPurgeEnddate').datepicker('hide');
    });
    //Assigning values to picker
    $('#divPurgeStartdate').data({date: m}).datepicker('update');
    $('#divPurgeEnddate').data({date: m}).datepicker('update');
    $("#txtPurgeStart").val(m);
    $("#txtPurgeEnd").val(m);
    $("#btnPurge", "#divPurgedata").bind("click", btnPurgeClick);
}
function btnPurgeClick(e) {
    $("#spnGenPurge", "#divPurgedata").show();
    e.preventDefault();
    var purgeStartDate = $("#txtPurgeStart").val().split("/");
    var startDateValue = (new Date(purgeStartDate[1], (purgeStartDate[0] - 1))).valueOf();
    var PurgeEndDate = $("#txtPurgeEnd").val().split("/");
    var endDateValue = (new Date(PurgeEndDate[1], (PurgeEndDate[0] - 1))).valueOf();
    if (endDateValue < startDateValue) {
        $("#divPurgeDataErr", "#divPurgedata").show().text('The end date can not be earlier than the start date');
        $("#spnGenPurge", "#divPurgedata").hide();
    } else {
        $("#divPurgeDataErr", "#divPurgedata").hide();
        $.support.cors = true;
        var url = BACKENDURL + "customeradmin/purge_energy_data";
        var data = {
            session_id: localStorage["SESSIONID"],
            contract_id: localStorage.getItem("contractid"),
            start_month: purgeStartDate[0],
            start_year: purgeStartDate[1],
            end_month: PurgeEndDate[0],
            end_year: PurgeEndDate[1]
        };
        MakeAjaxCall(url, data, purgeDataSuccess);
    }
}
function purgeDataSuccess(data) {
    if (data.error == 0) {
        if (data.purge_res) {
            $("#divPurgeDataErr", "#divPurgedata").removeClass("alert-error").addClass("alert-success").show().text("Success. Data purged from  " + $("#txtPurgeStart").val() + " to " + $("#txtPurgeEnd").val());
            $("#spnGenPurge", "#divPurgedata").hide();
        }
    } else {
        $("#divPurgeDataErr", "#divPurgedata").show().text("Error! " + data.error_msg).removeClass("alert-success").addClass("alert-error");
        $("#spnGenPurge", "#divPurgedata").hide();
    }
}
/************************End Purge data****************************/
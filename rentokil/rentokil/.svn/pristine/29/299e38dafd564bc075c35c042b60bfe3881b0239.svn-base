$(document).ready(function() {
    $.support.cors = true;
    $("#divcontainer").load(loadFileName, function () {
        $("#caselectionheader").load("caselectionheader.html",function(){
            $("#cafooter").load("cafooter.html");
            $("#divWelcome").html("Welcome " +localStorage["FNAME"]+"  " +localStorage["LNAME"]);
            $("#divLoggedAsCustomerAdmin").html("Logged in as customer administrator");
            $('#btnLogout').bind("click",logout);
        });
        //This is for specific to page and need to define in every page.
        LoadPageData();		
    });
});
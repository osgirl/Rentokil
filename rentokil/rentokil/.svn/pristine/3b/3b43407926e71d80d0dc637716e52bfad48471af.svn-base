$(document).ready(function () {
    function e(e) { // nudge widget titles up a little if they flow on to a second line on small screens
        height = e.height();
        if (height > 19) {
            e.css({
                marginTop: "-9px"
            })
        } else {
            e.css({
                marginTop: "0px"
            })
        }
    }
    function t(e) { // reset the nudge when we make the screen big again
        $(".widget h2").each(function () {
            $(this).css({
                marginTop: "0px"
            })
        })
    }
    $(function () {
        $("#exportdatamodal input[name=optionsRadios1]").click(function () {
            if ($("#exportdatamodal input[name=optionsRadios1]:checked").val() == "downloadasfile" || $("#exportdatamodal input[name=optionsRadios1]:checked").val() == "sendviaemail") {
                $("#exportformatdiv").fadeIn()
            } else {
                $("#exportformatdiv").fadeOut()
            }
            if ($("#exportdatamodal input[name=optionsRadios1]:checked").val() == "sendviaemail") {
                $("#emailsendto").fadeIn()
            } else {
                $("#emailsendto").fadeOut()
            }
        })
    });
    $(window).resize(function () { // functions to do on screen resize
        if ($(window).width() < 780) { // small screens: nudge up widget headers
            $(".widget h2").each(function () {
                $this = $(this);
                e($this)
            })
        } else { // big screens: reset the nudge
            $(".widget h2").each(function () {
                t($(this))
            })
        }
    });
    if ($(window).width() < 780) { // check if we need to do the header nudge on load
        $(".widget h2").each(function () {
            $this = $(this);
            e($this)
        })
    }
    $("#exportdatamodal").on("hidden", function () { // when we hide the export data modal...
        $("#exportdatamodal form")[0].reset(); // reset the form
        $("#exportformatdiv").fadeOut(); // and hide the currently irrelevant fields
        $("#emailsendto").fadeOut()
    })

    // set the divider under the login name to the width of the name - we assume this will be widest because of the font size!
    var headerWidth = $('.username').width();
    $('.logininfodivider').width(headerWidth);
});
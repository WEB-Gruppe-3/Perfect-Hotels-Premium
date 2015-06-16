/**
 * JS Stuff for vedlikehold/index.php
 */

/* ################## SCRIPT START ################## */

startDateInput = $("#startDateInput");
startDateInput1 = $("#startDateInput1");
startDateInput2 = $("#startDateInput2");
startDateInput3 = $("#startDateInput3");
startDateInput4 = $("#startDateInput4");
startDateInput5 = $("#startDateInput5");
startDateInput6 = $("#startDateInput6");
startDateInput7 = $("#startDateInput7");
startDateInput8 = $("#startDateInput8");
startDateInput9 = $("#startDateInput9");
startDateInput10 = $("#startDateInput10");

endDateInput = $("#endDateInput");
endDateInput1 = $("#endDateInput1");
endDateInput2 = $("#endDateInput2");
endDateInput3 = $("#endDateInput3");
endDateInput4 = $("#endDateInput4");
endDateInput5 = $("#endDateInput5");
endDateInput6 = $("#endDateInput6");
endDateInput7 = $("#endDateInput7");
endDateInput8 = $("#endDateInput8");
endDateInput9 = $("#endDateInput9");
endDateInput10 = $("#endDateInput10");

editstartDateInput = $("#editstartDateInput");
editendDateInput = $("#editendDateInput");

$(function() {

    // Setting onClick @ #logOutButton
    $("#logOutButton").click(function() {
        requestLogout()
    });

    // Setting and configuring date picker
    startDateInput.datepicker({ minDate: 0 });
    startDateInput1.datepicker({ minDate: 0 });
    startDateInput2.datepicker({ minDate: 0 });
    startDateInput3.datepicker({ minDate: 0 });
    startDateInput4.datepicker({ minDate: 0 });
    startDateInput5.datepicker({ minDate: 0 });
    startDateInput6.datepicker({ minDate: 0 });
    startDateInput7.datepicker({ minDate: 0 });
    startDateInput8.datepicker({ minDate: 0 });
    startDateInput9.datepicker({ minDate: 0 });
    startDateInput10.datepicker({ minDate: 0 });

    endDateInput.datepicker({ minDate: 1 });
    endDateInput1.datepicker({ minDate: 1 });
    endDateInput2.datepicker({ minDate: 1 });
    endDateInput3.datepicker({ minDate: 1 });
    endDateInput4.datepicker({ minDate: 1 });
    endDateInput5.datepicker({ minDate: 1 });
    endDateInput6.datepicker({ minDate: 1 });
    endDateInput7.datepicker({ minDate: 1 });
    endDateInput8.datepicker({ minDate: 1 });
    endDateInput9.datepicker({ minDate: 1 });
    endDateInput10.datepicker({ minDate: 1 });

    editstartDateInput.datepicker({ minDate: 0 });
    editendDateInput.datepicker({ minDate: 1 });

    $.datepicker.setDefaults(
        $.extend(
            {'dateFormat':'yy-mm-dd'}
        )
    );

});

/* ################## SCRIPT END ################## */

/* ################## Request functions ################## */
/**
 *  OnClick: logOutButton
 *  Request a log-out
 */
function requestLogout() {
    // Find the sessionID
    var sessionID = $("#hiddenFormSessionID").html();

    $.ajax({
        url: "index.ajax.php",

        data: {
            request: "logOut",
            sessionID: sessionID
        },

        type: "GET",

        // Expected return data
        dataType : "json",

        error: function( xhr, status, errorThrown ) {
            console.log("Error from showSearchResults()");
            console.log( "Error: " + errorThrown );
            console.log( "Status: " + status );
            console.dir( xhr );
        },

        success: function( json ) {
           console.log(json);
            location.href = "../nettsted/index.php"
        }
    });
}
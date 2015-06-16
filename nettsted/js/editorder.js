/**
 * JS Stuff for index.php
 */

/* ########################### SCRIPT START ########################### */

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

    /** onClick: reference ok button */
    var okButton = $("[name=checkinbutton]");
    var refInput = $("[name=search]");

    okButton.click(function(event) {
        if(refInput.val() === "" || refInput.val() === " " || refInput.val().length < 32) {
            event.preventDefault();
            refInput.css("background-color", "red");
        }
        else {
            refInput.css("background-color", "green");
        }
    });

    /** onClick: sjekkButton */
    var sjekkButton = $("#sjekkButton");
    sjekkButton.click(function(event) {
        // SjekkButton should not submit anything.
        event.preventDefault();

        // Remove any message that is present from before
        var htmlElementMsg = $("#sjekkAvailRoomsMsg");
        htmlElementMsg.html("");

        // Check if the dates are valid.
        var startDateVal = $("#startDateInput").val();
        var endDateVal = $("#endDateInput").val();
        checkDates(startDateVal, endDateVal, true);

    });


    /** onClick: Update button */
    var updateButton = $("#checkbutton");
    updateButton.click(function(event) {
        // Prevent the button from submitting
        event.preventDefault();

        // Run checkDates which will submit the form if the dates are valid
        var startDateVal = $("#startDateInput").val();
        var endDateVal = $("#endDateInput").val();
        checkDates(startDateVal, endDateVal, false);
    });


});
/* ########################### SCRIPT END ########################### */



/* ########################### AJAX REQUESTS START ########################### */
function checkDates(startDate, endDate, isCheck) {
    $.ajax({
        // The URL for the request
        url: "editorder.ajax.php",

        // The data to send (will be converted to a query string)
        data: {
            requestedData: "dateCheck",
            startDate: startDate,
            endDate: endDate
        },

        // Whether this is a POST or GET request
        type: "GET",

        // The type of data we expect back
        dataType : "json",

        // Code to run if the request fails; the raw request and
        // status codes are passed to the function
        error: function( xhr, status, errorThrown ) {
            console.log("Error from isDatesValid() in editorder.js");
            console.log( "Error: " + errorThrown );
            console.log( "Status: " + status );
            console.dir( xhr );
        },

        // On completion, set data from JSON
        success: function( json ) {
            var htmlElementMsg = $("#sjekkAvailRoomsMsg");

            if(! json.isValid) {
                htmlElementMsg.html(json.message);
                htmlElementMsg.css("background-color", "red");
            }
            else if(isCheck) {
                requestNumberOfAvailableRooms();
            }
            else if(! isCheck) {
                // submit the form
                var form = $("#editform");
                form.submit();
            }
        }
    });

}

function requestNumberOfAvailableRooms() {
    // Get the fucking dataz
    var hotelID = $("[name = 'HotelID']").val();
    var roomTypeID = $("[name = 'RoomTypeID']").val();
    var startDate = $("#startDateInput").val(); // format: yyyy-mm-dd
    var endDate = $("#endDateInput").val();

    $.ajax({
        // The URL for the request
        url: "editorder.ajax.php",

        // The data to send (will be converted to a query string)
        data: {
            requestedData: "getNumAvailRooms",
            hotelID: hotelID,
            roomTypeID: roomTypeID,
            startDate: startDate,
            endDate: endDate
        },

        // Whether this is a POST or GET request
        type: "GET",

        // The type of data we expect back
        dataType : "json",

        // Code to run if the request fails; the raw request and
        // status codes are passed to the function
        error: function( xhr, status, errorThrown ) {
            console.log("Error from requestNumberOfAvailableRooms() in editorder.js");
            console.log( "Error: " + errorThrown );
            console.log( "Status: " + status );
            console.dir( xhr );
        },

        // On completion, set data from JSON
        success: function( json ) {
            var htmlElementMsg = $("#sjekkAvailRoomsMsg");
            var msg = "";

            if(json.numRooms > 0) {
                htmlElementMsg.css("background-color", "green");
                msg = "Det finnes ledige rom i denne perioden :)"
            } else {
                htmlElementMsg.css("background-color", "red");
                msg = "Det ikke finnes ledige rom i denne perioden :("
            }

            htmlElementMsg.html(msg);
        }
    });
}

/* ########################### AJAX REQUESTS END ########################### */

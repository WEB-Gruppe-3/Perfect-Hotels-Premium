/**
 * JS Stuff for index.php
 */

/**
 * Date picker
 */
$(function() {

    // Clearing inputs and setting the datepicker
    var startDate = $("#startDateInput");
    var endDate = $("#endDateInput");
    startDate.datepicker();
    endDate.datepicker();
    startDate.empty();
    endDate.empty();

    // Configuring
    $.datepicker.setDefaults(
        $.extend(
            {'dateFormat':'dd-mm-yy'},
            $.datepicker.regional['no']
        )
    );

});


/**
 * -------------------- Functions --------------------
 */

/**
 * OnChange: hotelSelect
 */
function populateRoomTypeList() {
    // Figure out the hotels' ID
    var hotelID = $("#hotelSelect").val();

    // Request a JSON with the room types of this hotel.
    $.ajax({
        // The URL for the request
        url: "index.ajax.php",

        // The data to send (will be converted to a query string)
        data: {
            requestedData: "roomTypes",
            hotelID: hotelID
        },

        // Whether this is a POST or GET request
        type: "GET",

        // The type of data we expect back
        dataType : "json",

        // Upon completion, populate the dropdown menu.
        success: function( roomTypeData ) {
            ids = roomTypeData[0];
            names = roomTypeData[1];

            var roomTypeSelect = $("#roomTypeSelect");

            // Clear dropdown list
            roomTypeSelect.empty();

            // Enable input
            roomTypeSelect.prop('disabled', false);

            // Populate the list
            var items = "";
            for(var i = 0; i < ids.length; i++) {
                items += "<option value='" + ids[i] + "'>" + names[i] + "</option>";
            }
            roomTypeSelect.append(items);
        }
    });
}

/**
 * OnClick: Søk
 */
function getAvailableRooms() {
    // Getting the form data
    var hotelID = $("#hotelSelect").val();
    var roomTypeID = $("#roomTypeSelect").val();
    var startDate = $("#startDateInput").val();
    var endDate = $("#endDateInput").val();

    console.log("Data sent in JSON: \n" +
                "hotelID: " + hotelID +
                " roomTypeID: " + roomTypeID +
                " startDate: " + startDate +
                " endDate: " + endDate );

    // Request a JSON with the number of available rooms.
    $.ajax({
        // The URL for the request
        url: "index.ajax.php",

        // The data to send (will be converted to a query string)
        data: {
            requestedData: "numOfAvailableRooms",
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
            console.log("Error from getAvailableRooms()");
            console.log( "Error: " + errorThrown );
            console.log( "Status: " + status );
            console.dir( xhr );
        },

        // Upon completion, do this.
        success: function( numOfAvailRooms ) {
            var span = $("#numOfAvailableRooms");
            span.empty();
            span.append(numOfAvailRooms[0]);
        }
    });
}

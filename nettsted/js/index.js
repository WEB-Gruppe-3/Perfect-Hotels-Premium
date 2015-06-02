/**
 * JS Stuff for index.php
 */

/**
 * Setting the date picker
 */
$(function() {
    $("#startDate").datepicker();
    $("#endDate").datepicker();
});


/**
 * Functions
 */
function populateRoomTypeList() {
    // Figure out the hotels' ID
    var hotelId = $("#hotelSelect").val();

    // Request a JSON with the room types of this hotel.
    $.ajax({
        // The URL for the request
        url: "index.ajax.php",

        // The data to send (will be converted to a query string)
        data: {
            hotelID: hotelId
        },

        // Whether this is a POST or GET request
        type: "GET",

        // The type of data we expect back
        dataType : "json",

        // Upon completion, populate the dropdown menu.
        success: function( roomTypes ) {
            var roomTypeSelect = $("#roomTypeSelect");

            // Clear dropdown list
            roomTypeSelect.empty();

            // Populate the list
            var items = null;
            for(var i = 0; i < roomTypes.length; i++) {
                items += "<option value='" + roomTypes[i] + "'>" + roomTypes[i] + "</option>";
            }
            roomTypeSelect.append(items);
        }
    });
}

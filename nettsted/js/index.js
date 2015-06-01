// TODO: after i understand this, reformat this so its not just copy paste.

function getAndSetRoomTypes() {
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

        // Code to run if the request succeeds;
        // the response is passed to the function
        success: function( roomTypes ) {
            populateRoomTypeSelect(roomTypes);
        },

        // Code to run if the request fails; the raw request and
        // status codes are passed to the function
        error: function( xhr, status, errorThrown ) {
            alert( "Sorry, there was a problem!" );
            console.log( "Error: " + errorThrown );
            console.log( "Status: " + status );
            console.dir( xhr );
        },

        // Code to run regardless of success or failure
        complete: function( xhr, status ) {
            alert( "The request is complete!" );
        }
    });

}

function populateRoomTypeSelect(roomTypes) {
    console.log(roomTypes);
}
/**
 * JS Stuff for index.php
 */

/**
 * Declaring elements and inputs for later use
 */
// Search form elements
hotelSelect = $("#hotelSelect");
roomTypeSelect = $("#roomTypeSelect");
startDateInput = $("#startDateInput");
endDateInput = $("#endDateInput");

// Modal window elements
modalWindow = $(".modalWindow");
modalHotelTitle = $("#modalHotelTitle");
modalRoomTypeTitle = $("#modalRoomTypeTitle");
modalDateTitle = $("#modalDateTitle");
emailInput = $("#emailInput");

modalPreOrderContent = $("#modalPreOrderContent");
modalPostOrderContent = $("#modalPostOrderContent");
modalRefNrTitle = $("#modalRefNrTitle");

$(function() {
    // Resetting search form
    hotelSelect.find("option:first").attr("selected", "selected");
    startDateInput.val("");
    endDateInput.val("");

    populateRoomTypeList();

    // Setting and configuring date picker
    startDateInput.datepicker();
    endDateInput.datepicker();

    $.datepicker.setDefaults(
        $.extend(
            {'dateFormat':'dd-mm-yy'},
            $.datepicker.regional['no']
        )
    );

});


/* -------------------- Functions -------------------- */

/* -------------------- EVENT HANDLER FUNCTIONS -------------------- */
/**
 * Display and animate room type select upon press from hotel select
 */
function displayAndAnimateRoomTypeSelect() {
    var roomTypeSelectDiv = $("#roomTypeSelectDiv");
    roomTypeSelectDiv.slideDown();
}

/**
 * Display and animate date select upon press from room type select
 */
function displayAndAnimateDateSelect() {
    var dateSelectDiv = $("#dateSelectDiv");
    dateSelectDiv.slideDown();
}

/**
 * OnClick: Bestill-button
 */
function showOrderOverlay() {
    modalWindow.css("visibility", "visible");

    modalPreOrderContent.css("visibility", "visible");
    modalPostOrderContent.css("visibility", "hidden");

    // Clear email input if there was anything left from previous showing of the window.
    emailInput.val("");

    // Setting search values
    modalHotelTitle.html(hotelSelect.find("option:selected").html());
    modalRoomTypeTitle.html(roomTypeSelect.find("option:selected").html());
    modalDateTitle.html(startDateInput.val() + " - " + endDateInput.val());
}

/**
 * OnClick: Modal close
 */
function closeOrderOverlay() {
    modalPostOrderContent.css("visibility", "hidden");
    modalPreOrderContent.css("visibility", "hidden");
    modalWindow.css("visibility", "hidden");
}

function showPostOrder(isSuccess, refNr) {
    if(! isSuccess) {
        alert("Bestillingen gikk til helvette!");
    }

    modalPreOrderContent.css("visibility", "hidden");
    modalPostOrderContent.css("visibility", "visible");

    // Set ref nr
    modalRefNrTitle.html(refNr);
}

/**
 * OnClick: Order overlay book rooms
 *
 * Adds a booking to the database, then informs the user of success or failiure!
 */
function doOrder() {
    // Getting the selected values
    var hotelID = hotelSelect.val();
    var roomTypeID = roomTypeSelect.val();
    var startDate = startDateInput.val();
    var endDate = endDateInput.val();
    var email = emailInput.val();

    // Now that we have the dataz, lets get the server to add the shit
    // Request a JSON with the room types of this hotel.
    $.ajax({
        // The URL for the request
        url: "index.ajax.php",

        // The data to send (will be converted to a query string)
        data: {
            requestedData: "addBook",
            hotelID: hotelID,
            roomTypeID: roomTypeID,
            startDate: startDate,
            endDate: endDate,
            email: email
        },

        // Whether this is a POST or GET request
        type: "GET",

        // The type of data we expect back
        dataType : "json",

        success: function( data ) {
            console.log(data);
            showPostOrder(data.isSuccess, data.refNr);
        }
    });
}

/**
 * OnChange: hotelSelect
 */
function populateRoomTypeList() {
    // Figure out the hotels' ID
    var hotelID = hotelSelect.val();

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
            var ids = roomTypeData[0];
            var names = roomTypeData[1];

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
function search() {
    // Getting the selected values
    var hotelID = hotelSelect.val();
    var roomTypeID = roomTypeSelect.val();
    var startDate = startDateInput.val();
    var endDate = endDateInput.val();

    // Setting search parameters in result screen
    var hotelTitle = hotelSelect.find(":selected").text();
    $("#hotelTitle").text(hotelTitle);
    var roomTypeTitle = roomTypeSelect.find(":selected").text();
    $("#roomTypeTitle").text(roomTypeTitle);
    $("#dateTitle").text(startDate + " - " + endDate);

    // Requesting a JSON from server containing:
    // - Hotel image URL
    // - Hotel description
    // - RoomType image URL
    // - RoomType description
    $.ajax({
        // The URL for the request
        url: "index.ajax.php",

        // The data to send (will be converted to a query string)
        data: {
            requestedData: "searchData",
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
            console.log("Error from search()");
            console.log( "Error: " + errorThrown );
            console.log( "Status: " + status );
            console.dir( xhr );
        },

        // On completion, set data from JSON
        success: function( data ) {
            // Finding elements
            var numRoomsElement = $("#numOfAvailableRooms");
            var hotelImageElement = $("#hotelImage");
            var hotelDescElement = $("#hotelDescription");
            var roomTypeImageElement = $("#roomTypeImage");
            var roomTypeDescElement = $("#roomTypeDescription");

            // Setting element values
            numRoomsElement.html(data.numRooms);
            hotelImageElement.attr("src", data.hotelImageURL);
            hotelDescElement.html(data.hotelDescription);
            roomTypeImageElement.attr("src", data.roomTypeImageURL);
            roomTypeDescElement.html(data.roomTypeDescription);

            console.log(data);
        }
    });
}

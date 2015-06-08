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


/**
 * -------------------- Functions --------------------
 */

/**
 * OnClick: Book room button
 */
function openModalWindow() {
    // Setting search values
    modalHotelTitle.append(hotelSelect.find("option:selected").html());
    modalRoomTypeTitle.append(roomTypeSelect.find("option:selected").html());
    modalDateTitle.html(startDateInput.val() + " - " + endDateInput.val());

    // Make the modal window visible
    modalWindow.css("visibility", "visible");
    modalPostOrderContent.css("visibility", "hidden");
}

/**
 * OnClick: Modal close
 */
function closeModalWindow() {
    // Clearing elements
    emailInput.val("");
    modalHotelTitle.html("");
    modalRoomTypeTitle.html("");
    modalDateTitle.html("");

    modalRefNrTitle.html("");

    modalWindow.css("visibility", "hidden");
}

/**
 * OnClick: Modal order room
 *
 * Adds a booking to the database, then informs the user of success or failiure!
 */
function bookRoom() {
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

function showPostOrder(isSuccess, refNr) {
    if(! isSuccess) {
        alert("Bestillingen gikk til helvette!");
    }

    modalPreOrderContent.css("visibility", "hidden");
    modalPostOrderContent.css("visibility", "visible");

    // Set ref nr
    modalRefNrTitle.append(refNr);
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
function getAvailableRooms() {
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
            console.log("Error from getAvailableRooms()");
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

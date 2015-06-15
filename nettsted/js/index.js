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

// Content divs
welcomeDiv = $("#welcome");
searchButton = $("#searchButton");
newSearchButton = $("#newSearchButton");

resultDiv = $("#result");
goToOrderButton = $("#goToOrderButton");
freeRoomsBox = $("#freeRoomsBox");
freeRoomsBoxNumberElement = $("#numOfAvailableRooms");

orderDiv = $("#order");
emailInput = $("#emailInput");
emailErrorMessage = $("#invalidEmailError");
orderButton = $("#orderButton");

orderCompleteDiv = $("#orderComplete");
refNrElement = $("#refNr");

searchDateErrorMessage = $("#searchDateErrorMessage");


/* -------------------- Script start -------------------- */
$(function() {
    // Setting onClick listeners
    searchButton.click(function() {
        search();
    });

    newSearchButton.click(function() {
        newSearch();
    });

    goToOrderButton.click(function() {
        onClickBestill();
    });

    orderButton.click(function() {
        doOrder();
    });

    // Resetting showSearchResults form
    toggleSearchInputs(true);

    resetInputsAndElements();

    populateRoomTypeList();

    // Setting and configuring date picker
    startDateInput.datepicker({ minDate: 0 });
    endDateInput.datepicker({ minDate: 1 });

    $.datepicker.setDefaults(
        $.extend(
            {'dateFormat':'dd-mm-yy'},
            $.datepicker.regional['no']
        )
    );

    // Slide out room type select and date select
    displayAndAnimateHotelSelect();
    displayAndAnimateRoomTypeSelect();
    displayAndAnimateDateSelect();
});
/* -------------------- Script end -------------------- */



/* -------------------- Functions -------------------- */
/**
 * Display and animate hotel select
 */
function displayAndAnimateHotelSelect() {
    var hotelSelectDiv = $("#hotelSelectDiv");
    hotelSelectDiv.slideDown();
}

/**
 * Display and animate room type select
 */
function displayAndAnimateRoomTypeSelect() {
    var roomTypeSelectDiv = $("#roomTypeSelectDiv");
    roomTypeSelectDiv.slideDown(600);
}

/**
 * Display and animate date select
 */
function displayAndAnimateDateSelect() {
    var dateSelectDiv = $("#dateSelectDiv");
    dateSelectDiv.slideDown(800);
}

function toggleSearchInputs(doEnable) {
    if(doEnable) {
        hotelSelect.removeAttr("disabled");
        roomTypeSelect.removeAttr("disabled");
        startDateInput.removeAttr("disabled");
        endDateInput.removeAttr("disabled");
        searchButton.show();
    } else {
        hotelSelect.attr("disabled", "disabled");
        roomTypeSelect.attr("disabled", "disabled");
        startDateInput.attr("disabled", "disabled");
        endDateInput.attr("disabled", "disabled");
        searchButton.hide();
    }
}

function resetInputsAndElements() {
    hotelSelect.find("option:first").attr("selected", "selected");
    startDateInput.val("");
    endDateInput.val("");

    hotelSelect.css("background-color", "");
    roomTypeSelect.css("background-color", "");
    startDateInput.css("background-color", "");
    endDateInput.css("background-color", "");

    freeRoomsBox.css("background-color", "rgb(104, 177, 135)");

    emailInput.val("");

    refNrElement.html("");

    freeRoomsBoxNumberElement.html("-");

    searchDateErrorMessage.hide();

    emailErrorMessage.html("");
    emailErrorMessage.hide();
}

/**
 * New search
 */
function newSearch() {
    resetInputsAndElements();

    resultDiv.hide();
    orderDiv.hide();
    orderCompleteDiv.hide();

    welcomeDiv.show();

    toggleSearchInputs(true);

}

/**
 * Validates an input
 */
function isInputValid(hotelSelectVal, roomTypeSelectVal, dateStartVal, dateEndVal) {
    var isHotelValid = false;
    var isRoomTypeValid = false;
    var isStartDateValid = false;
    var isEndDateValid = false;

    var failColor = "red";
    var goodColor = "limegreen";

    // Hotel ID should not be an illegal number
    if(isNaN(hotelSelectVal)) {
        hotelSelect.css("background-color", failColor);
        isHotelValid = false;
    } else {
        hotelSelect.css("background-color", goodColor);
        isHotelValid = true;
    }

    // RoomTypeID should not be an illegal number
    if(isNaN(roomTypeSelectVal)) {
        roomTypeSelect.css("background-color", failColor);
        isRoomTypeValid = false;
    } else {
        roomTypeSelect.css("background-color", goodColor);
        isRoomTypeValid = true;
    }

    // DATE VALIDATION
    // Check if they are empty
    if(dateStartVal === "" || dateEndVal === "") {
        searchDateErrorMessage.show();
        searchDateErrorMessage.html("Begge datofeltene må fylles ut!");
    }

    // Both dates should follow the pattern: "00.00.0000" (/\d\d.\d\d.\d\d\d\d/)
    var reg = /\d\d.\d\d.\d\d\d\d/;
    var isStartMatch = dateStartVal.match(reg);
    var isEndMatch = dateEndVal.match(reg);

    if(isStartMatch) {
        startDateInput.css("background-color", goodColor);
        isStartDateValid = true;
    } else {
        startDateInput.css("background-color", failColor);
        isStartDateValid = false;
    }

    if(isEndMatch) {
        endDateInput.css("background-color", goodColor);
        isEndDateValid = true;
    } else {
        endDateInput.css("background-color", failColor);
        isEndDateValid = false;
    }

    return (isHotelValid && isRoomTypeValid && isStartDateValid && isEndDateValid);
}

/* -------------------- Event handling -------------------- */
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
    // Validate inputs via JS
    if(false === isInputValid(hotelSelect.val(), roomTypeSelect.val(), startDateInput.val(), endDateInput.val())) {
        // If inputs are not valid, there is no reason to continue
        return;
    }

    // Validate inputs via PHP, and if they are good, fire off showSearchResults()
    // Requesting results from php validation
    $.ajax({
        // The URL for the request
        url: "index.ajax.php",

        // The data to send (will be converted to a query string)
        data: {
            requestedData: "validateDates",
            startDateString: startDateInput.val(),
            endDateString: endDateInput.val()
        },

        // Whether this is a POST or GET request
        type: "GET",

        // The type of data we expect back
        dataType : "json",

        error: function( xhr, status, errorThrown ) {
            console.log("Error from showSearchResults()");
            console.log( "Error: " + errorThrown );
            console.log( "Status: " + status );
            console.dir( xhr );
        },

        // Upon completion, return the validation array.
        success: function( validation ) {
            if(validation.isValid === true) {
                searchDateErrorMessage.hide();

                startDateInput.css("background-color", "limegreen");
                endDateInput.css("background-color", "limegreen");

                // Disable inputs so user can't change them during the ordering process
                toggleSearchInputs(false);

                showSearchResults();
            } else {
                searchDateErrorMessage.html(validation.message);
                searchDateErrorMessage.show();
                startDateInput.css("background-color", "red");
                endDateInput.css("background-color", "red");
            }
        }
    });
}

/**
 * Show search results
 */
function showSearchResults() {
    // Show the result div
    var resultDiv = $("#result");
    var welcomeDiv = $("#welcome");
    resultDiv.show();
    welcomeDiv.hide();

    // Getting the selected values
    var hotelID = hotelSelect.val();
    var roomTypeID = roomTypeSelect.val();
    var startDate = startDateInput.val();
    var endDate = endDateInput.val();

    // Setting showSearchResults parameters in result screen
    var hotelTitle = hotelSelect.find(":selected").text();
    $("#hotelTitle").text(hotelTitle);
    var roomTypeTitle = roomTypeSelect.find(":selected").text();
    $("#roomTypeTitle").text("Rom: " + roomTypeTitle);
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
            console.log("Error from showSearchResults()");
            console.log( "Error: " + errorThrown );
            console.log( "Status: " + status );
            console.dir( xhr );
        },

        // On completion, set data from JSON
        success: function( data ) {
            console.log(data);

            // Finding elements
            var numRoomsElement = $("#numOfAvailableRooms");
            var hotelImageElement = $("#hotelImage");
            var hotelDescElement = $("#hotelDescription");
            var roomTypeImageElement = $("#roomTypeImage");
            var roomTypeDescElement = $("#roomTypeDescription");
            var roomTypePriceElement = $("#roomTypePrice");
            var roomTypeNumOfBedsElement = $("#roomTypeNumOfBeds");

            // Setting element values
            numRoomsElement.html(data.numRooms);
            hotelImageElement.attr("src", data.hotelImageURL);
            hotelDescElement.html(data.hotelDescription);
            roomTypeImageElement.attr("src", data.roomTypeImageURL);
            roomTypeDescElement.html(data.roomTypeDescription);
            roomTypePriceElement.html(data.roomTypePrice + ",-");
            roomTypeNumOfBedsElement.html(data.roomTypeNumOfBeds);

            // If the number of avail rooms are < 1, set bg color to red
            if(data.numRooms < 1) {
                freeRoomsBox.css("background-color", "rgb(245, 24, 24)");
            }
        }
    });
}

/**
 * OnClick: "Gå til bestilling"
 */
function onClickBestill() {
    // First, check if there are available rooms for ordering. If not, alert the user.
    var numOfAvailRooms = parseInt($("#numOfAvailableRooms").html());
    if(numOfAvailRooms < 1) {
        // No available rooms, we cannot proceed.

        // Animate the num of rooms box to indicate error
        var freeRoomsBox = $("#freeRoomsBox");

        freeRoomsBox.animate({
            backgroundColor: "rgb(255, 255, 255)"
        }, 200).animate({
            backgroundColor: "rgb(245, 24, 24)"
        }, 200).animate({
            backgroundColor: "rgb(255, 255, 255)"
        }, 200).animate({
            backgroundColor: "rgb(245, 24, 24)"
        }, 200);

        return;
    }

    var resultDiv = $("#result");
    var orderDiv = $("#order");

    // Empty the email field
    emailInput.empty();

    // Enable the order div, and set order details
    orderDiv.show();

    $("#orderHotelTitle").html(hotelSelect.find("option:selected").html());
    $("#orderRoomTypeTitle").html(roomTypeSelect.find("option:selected").html());
    $("#orderDateTitle").html(startDateInput.val() + " - " + endDateInput.val());

    // Hide the search result div
    resultDiv.hide();
}

/**
 * Shows the order complete div
 */
function showModalPostOrderContent(refNr) {
    // Showing the order complete div and setting refnr
    orderCompleteDiv.show();
    refNrElement.html(refNr);

    // Hide the order div
    orderDiv.hide();
}

/**
 * OnClick: Bestill
 *
 * Adds a booking to the database, then informs the user of success or failure!
 */
function doOrder() {
    // Check that the input is not empty or contain a single space
    if(emailInput.val() === "" || emailInput.val() === " ") {
        emailErrorMessage.show();
        emailErrorMessage.html("Feltet kan ikke være tomt!");
    }

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

            if(data.isSuccess) {
                showModalPostOrderContent(data.refNr);
            }
            else if(data.isSuccess === false && data.message.length > 0) {
                // If isSuccess is false and the message is > 0, the email was unvalid.
                emailErrorMessage.show();
                emailErrorMessage.html(data.message);
            }
            else {
                alert("Bestillingen feilet!");
            }

        }
    });
}





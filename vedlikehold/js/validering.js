/**
 * The validating starts with the press of one of two buttons.
 *
 * If the ADD button is pressed, validate the inputs for a given table within the ADDFORM.
 * If the EDIT button is pressed, validate the inputs for a given table within the EDITFORM.
 */

// Constants
goodColor = "green";
badColor = "red";

/** ************************ Script start ************************ */
$(function() {
    errorMessageElement = $("#jsValidateFeedback");
    editDiv = $("#popup");
    editForm = $("#EditForm");
    addDiv = $("#innholdRight");
    addForm = $("#addForm");
    selectedTable = $("#table_list").val();

    // Setting onClick handlers for the buttons
    var updateKnapp = $("#updateknapp");
    updateKnapp.click(function(event) {
        onClickUpdate(event);
    });

    var addKnapp = $("#addknapp");
    addKnapp.click(function(event) {
        onClickAdd(event);
    });

});
/** ************************ Script end ************************ */

/**
 * ON-CLICK: updateKnapp
 */
var onClickUpdate = function(event) {
    event.preventDefault();

    clearError();

    var currentContainer = editDiv;
    var currentForm = editForm;

    switch(selectedTable) {
        case "booking":
            validateInputsBooking(currentContainer, currentForm);
            break;
    }
};

/**
 * ON-CLICK: addKnapp
 */
var onClickAdd = function(event) {
    event.preventDefault();

    clearError();

    var currentContainer = addDiv;
    var currentForm = addForm;

    switch(selectedTable) {
        case "booking":
            validateInputsBooking(currentContainer, currentForm);
            break;
    }
};

/**
 * Show error message.
 */
function showError(errorMessage) {
    var currentText = errorMessageElement.html();

    if(currentText.length != 0) {
        errorMessageElement.append(" og " + errorMessage);
    } else {
        errorMessageElement.append(errorMessage);
    }

    errorMessageElement.show();
}

/**
 * Clear error message & reset color on all inputs
 */
function clearError() {
    errorMessageElement.html("");
    errorMessageElement.hide();
    $("input").css("background-color", "");
}

/**
 * ---------------------- Validate input functions ----------------------
 */

/**
 * Table: Booking
 *
 * READ: Special case in booking. The date inputs in editDiv are called editstartDateInput and editendDateInput.
 */
function validateInputsBooking(container, form) {
    var isDatesValid = false;
    var isRoomValid = false;

    // Inputs
    var roomDropDown = container.find("[name=RoomID]");
    var startDateInput = null;
    var endDateInput = null;

    if(container.attr("id") === "popup") { // 'popup' is editDiv
        startDateInput = container.find("#editstartDateInput");
        endDateInput = container.find("#editendDateInput");
    }
    else {
        startDateInput = container.find("#startDateInput");
        endDateInput = container.find("#endDateInput");
    }

    // Values
    var startDateString = startDateInput.val();
    var endDateString = endDateInput.val();
    var selectedRoom = roomDropDown.val();

    // Validating dates
    // Check for empty field.
    if(startDateString.length < 10 && endDateString.length < 10) {
        startDateInput.css("background-color", badColor);
        endDateInput.css("background-color", badColor);
        showError("Du må sette data i begge datofeltene!");
    }
    else if(startDateString.length < 10) {
        startDateInput.css("background-color", badColor);
        showError("Du må velge en start-dato!");
    }
    else if(endDateString.length < 10) {
        endDateInput.css("background-color", badColor);
        showError("Du må velge en slutt-dato!")
    }
    else {
        $.ajax({
            url: "index.ajax.php",

            data: {
                request: "dateCheck",
                startDateString: startDateString,
                endDateString: endDateString
            },

            type: "GET",

            dataType : "json",

            error: function( xhr, status, errorThrown ) {
                console.log("Error from isDatesValid() in validering.js");
                console.log( "Error: " + errorThrown );
                console.log( "Status: " + status );
                console.dir( xhr );
            },

            success: function( data ) {
                if(data.isValid) {
                    isDatesValid = true;
                    startDateInput.css("background-color", goodColor);
                    endDateInput.css("background-color", goodColor);
                }
                else {
                    startDateInput.css("background-color", badColor);
                    endDateInput.css("background-color", badColor);
                    showError(data.message);
                }
            }
        });
    }

    // Validating room
    if(selectedRoom == 0) {
        showError("Du må velge et rom!");
        roomDropDown.css("background-color", badColor);
    }
    else {
        isRoomValid = true;
        roomDropDown.css("background-color", goodColor);
    }

    // Submit the form if the inputs are valid
    if(isDatesValid + isRoomValid) {
        //form.submit();
        $("#addknapp").unbind();
        $("#addknapp").click();
    }

}

/**
 * Table: CustomerOrder
 */
function validateInputsCustomerOrder(container) {
    var referenceInput = container.find("[name=Reference]");
}

/**
 * Table: Hotel
 */
function validateInputsHotel(container) {
    var nameInput = container.find("[name=Name]");
    var descriptionInput = container.find("[name=Description]");
}

/**
 * Table: HotelRoomType
 */
// Needs no validation

/**
 * Table: Image
 */
function validateInputsImage(container) {
    var urlInpur = container.find("[name=URL]");
    var descriptionInput = container.find("[name=Description]");
}

/**
 * Table: MaintenanceUser
 */
function validateInputsMaintenanceUser(container) {
    var usernameInput = container.find("[name=UserName]");
    var passwordInput = container.find("[name=Password]");
}

/**
 * Table: Room
 */
function validateInputsRoom(container) {
    var roomNumberInput = container.find("[name=RoomNumber]");
}

/**
 * Table: RoomType
 */
function validateInputsRoomType(container) {
    var nameInput = container.find("[name=Name]");
    var numBedsInput = container.find(["name=NumOfBeds"]);
    var priceInput = container.find("[name=Price]");
    var descriptionInput = container.find("[name=Description]");
}


































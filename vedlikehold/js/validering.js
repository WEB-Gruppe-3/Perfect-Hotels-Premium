/**
 * The validating starts with the press of one of two buttons.
 *
 * If the ADD button is pressed, validate the inputs for a given table within the ADDFORM.
 * If the EDIT button is pressed, validate the inputs for a given table within the EDITFORM.
 */
$(function() {
    // Constants
    var goodColor = "green";
    var badColor = "red";

    // Elements
    var errorMessageElement = $("#jsValidateFeedback");

    var addDiv = $("#innholdRight");
    var addForm = $("#addForm");
    var addKnapp = $("#addknapp");

    var editDiv = $("#popup");
    var editForm = $("#EditForm");
    var editKnapp = $("#updateknapp");

    var selectedTable = $("#table_list").val();

    // Setting onClick listeners for buttons
    addKnapp.click(function(event) {
        event.preventDefault();
        onClickAdd();
    });

    editKnapp.click(function(event) {
        event.preventDefault();
        onClickEdit();
    });

    /**
     * onClick: addKnapp
     */
    var onClickAdd = function() {
        clearError();

        switch(selectedTable) {
            case "booking":
                validateInputsBooking(addDiv, addForm);
                break;
        }
    };

    /**
     * onClick: editKnapp
     */
    var onClickEdit = function() {
        clearError();

        switch(selectedTable) {
            case "booking":
                validateInputsBooking(editDiv, editForm);
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

            // Adding knapp-data to $_POST because it wont do it otherwise.
            var input = $("<input>")
                .attr("type", "hidden")
                .attr("name", "updateknapp").val("Update");
            form.append($(input));
        }
        else {
            startDateInput = container.find("#startDateInput");
            endDateInput = container.find("#endDateInput");

            // Adding knapp-data to $_POST because it wont do it otherwise.
            var input = $("<input>")
                .attr("type", "hidden")
                .attr("name", "addknapp").val("Add");
            form.append($(input));
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
        else {
            isDatesValid = true;
            startDateInput.css("background-color", goodColor);
            endDateInput.css("background-color", goodColor);
        }

        if(startDateString.length < 10) {
            startDateInput.css("background-color", badColor);
            showError("Du må velge en start-dato!");
        }
        else {
            startDateInput.css("background-color", goodColor);
        }

        if(endDateString.length < 10) {
            endDateInput.css("background-color", badColor);
            showError("Du må velge en slutt-dato!")
        }
        else {
            endDateInput.css("background-color", goodColor);
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

        // IF ALL INPUTS ARE VALID HERE, DO FINAL DATE CHECK.
        if(isDatesValid && isRoomValid) {
            validateInputsBookingAjaxCheckDates(startDateString, endDateString);
        }
    }

    function validateInputsBookingAjaxCheckDates(startDateString, endDateString) {
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
                // Check if the dates are valid
                console.log("Ajax date check. isValid = " + data.isValid +" Msg = "+ data.message);

                if(data.isValid) {
                    console.log("DATES ARE VALID!");
                    // If the dates are valid, submit the form.
                    addForm.submit();
                } else {
                    console.log("INVALID DATES");

                    showError(data.message);
                    startDateInput.css("background-color", "red");
                    endDateInput.css("background-color", "red");
                }
            }
        });
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



}); // End $











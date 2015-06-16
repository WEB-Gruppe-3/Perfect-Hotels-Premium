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

            case "customerorder":
                validateInputsCustomerOrder(addDiv, addForm);
                break;

            case "hotel":
                validateInputsHotel(addDiv, addForm);
                break;

            case "hotelroomtype":
                validateInputsHotelRoomType(addDiv, addForm);
                break;

            case "image":
                validateInputsImage(addDiv, addForm);
                break;

            case "maintenanceuser":
                validateInputsMaintenanceUser(addDiv, addForm);
                break;

            case "room":
                validateInputsRoom(addDiv, addForm);
                break;

            case "roomtype":
                validateInputsRoomType(addDiv, addForm);
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

            case "customerorder":
                validateInputsCustomerOrder(editDiv, editForm);
                break;

            case "hotel":
                validateInputsHotel(editDiv, editForm);
                break;

            case "hotelroomtype":
                validateInputsHotelRoomType(editDiv, editForm);
                break;

            case "image":
                validateInputsImage(editDiv, editForm);
                break;

            case "maintenanceuser":
                validateInputsMaintenanceUser(editDiv, editForm);
                break;

            case "room":
                validateInputsRoom(editDiv, editForm);
                break;

            case "roomtype":
                validateInputsRoomType(editDiv, editForm);
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
            validateInputsBookingAjaxCheckDates(startDateString, endDateString, form, startDateInput, endDateInput);
        }
    }

    function validateInputsBookingAjaxCheckDates(startDateString, endDateString, form, customStartDateInput, customEndDateInput) {
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
                    // If the dates are valid, submit the form.
                    submitForm(form);
                } else {
                    showError(data.message);
                    customStartDateInput.css("background-color", badColor);
                    customEndDateInput.css("background-color", badColor);
                }
            }
        });
    }

    /**
     * Table: CustomerOrder
     */
    function validateInputsCustomerOrder(container, form) {
        var referenceInput = container.find("[name=Reference]");

        var referenceValue = referenceInput.val();

        // Check if the string is 32 characters long
        if(referenceValue.length != 32) {
            referenceInput.css("background-color", badColor);
            showError("Referansen må være nøyaktig 32 bokstaver!")
        } else {
            referenceInput.css("background-color", goodColor);
            submitForm(form);
        }
    }

    /**
     * Table: Hotel
     */
    function validateInputsHotel(container, form) {
        var nameInput = container.find("[name=Name]");
        var descriptionInput = container.find("[name=Description]");

        var isNameValid = false;
        var isDescriptValid = false;

        // Name should be max 45 chars
        if(nameInput.val().length > 45 || nameInput.val() == "") {
            nameInput.css("background-color", badColor);
            showError("Navnet må være mellom 1 og 45 bokstaver!")
        }
        else {
            nameInput.css("background-color", goodColor);
            isNameValid = true;
        }

        // Description should be max 500 chars
        if(descriptionInput.val().length > 500 || descriptionInput.val() == "") {
            descriptionInput.css("background-color", badColor);
            showError("Beskrivelsen må være mellom 1 og 500 bokstaver!")
        } else {
            descriptionInput.css("background-color", goodColor);
            isDescriptValid = true;
        }

        if(isNameValid && isDescriptValid) {
            console.log("hotel is valid");
            submitForm(form);
        }
    }

    /**
     * Table: HotelRoomType
     */
    function validateInputsHotelRoomType(container, form) {
        var roomTypeIdInput = $("[name=RoomTypeID]");
        var hotelIdInput = $("[name=HotelID]");



    }


    /**
     * Table: Image
     */
    function validateInputsImage(container, form) {
        var urlInpur = container.find("[name=URL]");
        var descriptionInput = container.find("[name=Description]");
    }

    /**
     * Table: MaintenanceUser
     */
    function validateInputsMaintenanceUser(container, form) {
        var usernameInput = container.find("[name=UserName]");
        var passwordInput = container.find("[name=Password]");
    }

    /**
     * Table: Room
     */
    function validateInputsRoom(container, form) {
        var roomNumberInput = container.find("[name=RoomNumber]");
    }

    /**
     * Table: RoomType
     */
    function validateInputsRoomType(container, form) {
        var nameInput = container.find("[name=Name]");
        var numBedsInput = container.find(["name=NumOfBeds"]);
        var priceInput = container.find("[name=Price]");
        var descriptionInput = container.find("[name=Description]");
    }

    /**
     * Adds a button to the form, then submits it
     */
    function submitForm(form) {
        var input = $("<input>").attr("type", "hidden");

        switch(form.attr("id")) {
            case "addForm":
                input.attr("name", "addknapp").val("Add");
                break;

            case "EditForm":
                input.attr("name", "updateknapp").val("Update");
                break;
        }
        form.append(input);
        form.submit();
    }


}); // End $











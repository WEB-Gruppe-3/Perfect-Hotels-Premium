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
        // Rooms are valid no matter what
        roomDropDown.css("background-color", goodColor);
        isRoomValid = true;

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

        // Needs no validation.
        roomTypeIdInput.css("background-color", goodColor);
        hotelIdInput.css("background-color", goodColor);

        submitForm(form);
    }


    /**
     * Table: Image
     */
    function validateInputsImage(container, form) {
        var urlInput = container.find("[name=URL]");
        var descriptionInput = container.find("[name=Description]");

        var isUrlValid = false;
        var isDescriptValid = false;

        if(urlInput.val() == "" || urlInput.val().length > 300) {
            urlInput.css("background-color", badColor);
            showError("URL'en må være mellom 1 og 300 bokstaver!")
        } else {
            isUrlValid = true;
        }

        if(descriptionInput.val() == "" || descriptionInput.val().length > 300) {
            descriptionInput.css("background-color", badColor);
            showError("Beskrivelsen må være mellom 1 og 300 bokstaver!")
        } else {
            isDescriptValid = true;
        }

        if(isDescriptValid && isUrlValid) {
            descriptionInput.css("background-color", goodColor);
            urlInput.css("background-color", goodColor);
            submitForm(form);
        }
    }

    /**
     * Table: MaintenanceUser
     */
    function validateInputsMaintenanceUser(container, form) {
        var usernameInput = container.find("[name=UserName]");
        var passwordInput = container.find("[name=Password]");

        var isUserNameValid = false;
        var isPasswordValid = false;

        // Max 45 chars
        if(usernameInput.val() == "" || usernameInput.val().length > 45) {
            usernameInput.css("background-color", badColor);
            showError("Brukernavn må være mellom 1 og 45 bokstaver");
        } else {
            isUserNameValid = true;
        }

        if(passwordInput.val() == "" || passwordInput.val().length > 45) {
            passwordInput.css("background-color", badColor);
            showError("Passordet må være mellom 1 og 45 bokstaver!");
        } else {
            isPasswordValid = true;
        }

        if(isPasswordValid && isUserNameValid) {
            passwordInput.css("background-color", goodColor);
            usernameInput.css("background-color", goodColor);
            submitForm(form);
        }
    }

    /**
     * Table: Room
     */
    function validateInputsRoom(container, form) {
        var roomNumberInput = container.find("[name=RoomNumber]");

        if(isNaN(roomNumberInput.val())) {
            roomNumberInput.css("background-color", badColor);
            showError("Romnummer må være et tall!");
        }
        else if(roomNumberInput.val() < 0 || roomNumberInput.val() > 999) {
            showError("Romnummer må bestå av en tallverdi fra 0 til 999");
        }
        else {
            roomNumberInput.css("background-color", goodColor);
            submitForm(form);
        }
    }

    /**
     * Table: RoomType
     */
    function validateInputsRoomType(container, form) {
        var nameInput = container.find("[name=Name]");
        var numBedsInput = container.find("[name=NumOfBeds]");
        var priceInput = container.find("[name=Price]");
        var descriptionInput = container.find("[name=Description]");

        var nameVal = nameInput.val();
        var numBedsVal = numBedsInput.val();
        var priceVal = priceInput.val();
        var descriptVal = descriptionInput.val();

        var isNameValid = false;
        var isNumBedsValid = false;
        var isPriceValid = false;
        var isDescriptValid = false;

        // Validate name (max 45 length)
        if(nameVal.length < 1 || nameVal.length > 45) {
            nameInput.css("background-color", badColor);
            showError("Navn må være mellom 1 til 45 bokstaver!");
        } else {
            nameInput.css("background-color", goodColor);
            isNameValid = true;
        }

        // Validate number of beds
        if(isNaN(numBedsVal)) {
            numBedsInput.css("background-color", badColor);
            showError("Antall senger må være et tall!");
        }
        else if(numBedsVal < 1 || numBedsVal > 10) {
            numBedsInput.css("background-color", badColor);
            showError("Antall senger må være et tall mellom 1 til 10!");
        }
        else {
            numBedsInput.css("background-color", goodColor);
            isNumBedsValid = true;
        }

        // Validate price
        if(isNaN(priceVal)) {
            priceInput.css("background-color", badColor);
            showError("Pris må være et tall!");
        }
        else if(priceVal < 1 || priceVal > 50000) {
            priceInput.css("background-color", badColor);
            showError("Pris må være et tall mellom 1 til 50000!");
        }
        else {
            priceInput.css("background-color", goodColor);
            isPriceValid = true;
        }

        // Validate description (max 500 length)
        if(descriptVal.length < 1 || descriptVal.length > 500) {
            descriptionInput.css("background-color", badColor);
            showError("Beskrivelsen må være mellom 1 og 500 bokstaver!")
        } else {
            descriptionInput.css("background-color", goodColor);
            isDescriptValid = true;
        }

        // Submit if all is valid
        if(isNameValid && isNumBedsValid && isPriceValid && isDescriptValid) {
            submitForm(form);
        }
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
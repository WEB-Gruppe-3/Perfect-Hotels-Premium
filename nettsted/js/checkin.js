$(function() {

    /** Validering: Input referanse felt */
    var okButton = $("[name=checkinbutton]");
    var refInput = $("[name=search]");

    okButton.click(function(event) {
        if(refInput.val() === "" || refInput.val() === " ") {
            event.preventDefault();
            refInput.css("background-color", "red");
        }
        else {
            refInput.css("background-color", "green");
        }
    });

    /** Validering: Input room */
    // Brukeren skal ikke kunne velge "-"

    var roomInput = $("select[name=Room]");
    var completeButton = $("#donebutton");

    completeButton.click(function(event) {
        if(roomInput.val() === "") {
            event.preventDefault();
            roomInput.css("background-color", "red");
        }
        else {
            roomInput.css("background-color", "green");
        }
    });

});
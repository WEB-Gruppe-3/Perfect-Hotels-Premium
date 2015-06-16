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
});
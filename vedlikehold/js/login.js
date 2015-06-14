// --------------------------------- Input placeholder text ---------------------------------
userInput = $("#usernameInput");
passInput = $("#passwordInput");
submitButton = $("#submitButton");

defaultUserInputVal = "(brukernavn)";
defaultPassInputVal = "(passord)";

userInput.val(defaultUserInputVal);
passInput.val(defaultPassInputVal);

userInput.focus(function() {
    if(userInput.val() === defaultUserInputVal) {
        userInput.val("");
    }
});

userInput.blur(function() {
    if(userInput.val() === "") {
        userInput.val(defaultUserInputVal);
    }
});

passInput.focus(function() {
    if(passInput.val() === defaultPassInputVal) {
        passInput.val("");
    }
});

passInput.blur(function() {
    if(passInput.val() === "") {
        passInput.val(defaultPassInputVal);
    }
});

// --------------------------------- Validating ---------------------------------
submitButton.click(function(event) {

    // Check if inputs are empty or default
    if( userInput.val() === "" || userInput.val() === defaultUserInputVal ||
        passInput.val() === "" || passInput.val() === defaultPassInputVal) {

        event.preventDefault();
        var error = $("#emptyInputErrorMessage");
        error.html("Både brukernavn og passord må fylles ut!");
        error.show();
    }

});
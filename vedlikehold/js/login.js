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
// Validating on button click
submitButton.click(function(event) {
    if(isInputsEmpty()) {
        event.preventDefault();
        showErrorIfEmptyInputs();
    }
});

// Validating on inputs-enter-press
userInput.keyup(function(event) {
    // Is the enter key pressed? (key 13)
   if(event.keyCode === 13) {
       if(isInputsEmpty()) {
           event.preventDefault();
           showErrorIfEmptyInputs();
       }
   }
});

/**
 * Check if the inputs are empty or have the default value.
 * @return boolean Returns true if they are empty, false otherwise.
 */
var isInputsEmpty = function() {
    return (userInput.val() === "" ||
            userInput.val() === defaultUserInputVal ||
            passInput.val() === "" ||
            passInput.val() === defaultPassInputVal)
};

var showErrorIfEmptyInputs = function() {
    var error = $("#emptyInputErrorMessage");
    error.html("Både brukernavn og passord må fylles ut!");
    error.show();
};